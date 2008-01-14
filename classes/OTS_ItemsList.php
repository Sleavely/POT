<?php

/**#@+
 * @version 0.0.8
 * @since 0.0.8
 */

/**
 * Code in this file bases on oryginal OTServ items loading C++ code (itemloader.h, items.cpp, items.h).
 * 
 * @package POT
 * @version 0.1.0
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Items list loader.
 * 
 * @package POT
 * @version 0.1.0
 * @property-read int $otbVersion OTB file version.
 * @property-read int $clientVersion Dedicated client version.
 * @property-read int $buildVersion File build version.
 */
class OTS_ItemsList extends OTS_FileLoader implements IteratorAggregate, Countable, ArrayAccess
{
/**
 * Root file attribute.
 */
    const ROOT_ATTR_VERSION = 1;

/**
 * Tibia client 7.5 version.
 */
    const CLIENT_VERSION_750 = 1;
/**
 * Tibia client 7.55 version.
 */
    const CLIENT_VERSION_755 = 2;
/**
 * Tibia client 7.6 version.
 */
    const CLIENT_VERSION_760 = 3;
/**
 * Tibia client 7.7 version.
 */
    const CLIENT_VERSION_770 = 3;
/**
 * Tibia client 7.8 version.
 */
    const CLIENT_VERSION_780 = 4;
/**
 * Tibia client 7.9 version.
 */
    const CLIENT_VERSION_790 = 5;
/**
 * Tibia client 7.92 version.
 */
    const CLIENT_VERSION_792 = 6;
/**
 * Tibia client 8.0 version.
 */
    const CLIENT_VERSION_800 = 7;

/**
 * Server ID.
 */
    const ITEM_ATTR_SERVERID = 16;
/**
 * Client ID.
 */
    const ITEM_ATTR_CLIENTID = 17;
/**
 * Speed.
 */
    const ITEM_ATTR_SPEED = 20;
/**
 * Light.
 */
    const ITEM_ATTR_LIGHT2 = 42;
/**
 * Always-on-top order.
 */
    const ITEM_ATTR_TOPORDER = 43;

/**
 * Temple positions.
 * 
 * @var array
 */
    private $items = array();

/**
 * OTB version.
 * 
 * @var int
 */
    private $otbVersion;

/**
 * Client version.
 * 
 * @var int
 */
    private $clientVersion;

/**
 * Build version.
 * 
 * @var int
 */
    private $buildVersion;

/**
 * Magic PHP5 method.
 * 
 * Allows object unserialisation.
 * 
 * @internal Magic PHP5 method.
 */
    public function __wakeup()
    {
        // loads map info from recovered root node
        $this->parse();
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @internal Magic PHP5 method.
 * @param array $properties List of object properties.
 */
    public static function __set_state($properties)
    {
        $object = new self();

        // loads properties
        foreach($properties as $name => $value)
        {
            $object->$name = $value;
        }

        return $object;
    }

/**
 * Loads items.xml and items.otb files.
 * 
 * @param string $path Path to data/items directory.
 */
    public function loadItems($path)
    {
        $empty = false;

        // loads items.xml cache
        if( isset($this->cache) && $this->cache instanceof IOTS_ItemsCache)
        {
            $this->items = $this->cache->readItems( md5($path . '/items.xml') );
        }

        // checks if cache is loaded
        if( empty($this->items) )
        {
            // marks list to be saved in new cache
            $empty = true;

            // lodas XML file
            $xml = new DOMDocument();
            $xml->load($path . '/items.xml');

            // reads items info
            foreach( $xml->documentElement->getElementsByTagName('item') as $tag)
            {
                // composes basic item info
                $item = new OTS_ItemType( $tag->getAttribute('id') );
                $item->setName( $tag->getAttribute('name') );

                // reads attributes
                foreach( $tag->getElementsByTagName('attribute') as $attribute)
                {
                    $item->setAttribute( $attribute->getAttribute('key'), $attribute->getAttribute('value') );
                }

                $this->items[ $item->getId() ] = $item;
            }
        }

        // loads items.otb
        parent::loadFile($path . '/items.otb');

        // parses loaded file
        $this->parse();

        // saves cache
        if($empty && isset($this->cache) && $this->cache instanceof IOTS_ItemsCache)
        {
            $this->cache->writeItems( md5($path . '/items.xml'), $this->items);
        }
    }

/**
 * Parses loaded file.
 * 
 * @version 0.1.0
 * @throws E_OTS_FileLoaderError If file has invalid format.
 */
    private function parse()
    {
        // root node header
        $this->root->skip(4);

        // root attribute
        if( $this->root->getChar() == self::ROOT_ATTR_VERSION)
        {
            // checks if data lengths if same as version record which is supposed to be stored here
            if( $this->root->getShort() != 140)
            {
                throw E_OTS_FileLoaderError(E_OTS_FileLoaderError::ERROR_INVALID_FORMAT);
            }

            // version record
            $this->otbVersion = $this->root->getLong();
            $this->clientVersion  = $this->root->getLong();
            $this->buildVersion = $this->root->getLong();
        }

        // reads root's first child
        $node = $this->root->getChild();

        // reads all item types
        while($node)
        {
            // resets info
            $id = null;
            $clientId = null;
            $speed = null;
            $lightLevel = null;
            $lightColor = null;
            $topOrder = null;

            // reads flags
            $flags = $node->getLong();

            // reads node attributes
            while( $node->isValid() )
            {
                $attribute = $node->getChar();
                $length = $node->getShort();

                switch($attribute)
                {
                    // server-side ID
                    case self::ITEM_ATTR_SERVERID:
                        // checks length
                        if($length != 2)
                        {
                            throw E_OTS_FileLoaderError(E_OTS_FileLoaderError::ERROR_INVALID_FORMAT);
                        }

                        $id = $node->getShort();
                        break;

                    // client-side ID
                    case self::ITEM_ATTR_CLIENTID:
                        // checks length
                        if($length != 2)
                        {
                            throw E_OTS_FileLoaderError(E_OTS_FileLoaderError::ERROR_INVALID_FORMAT);
                        }

                        $clientId = $node->getShort();
                        break;

                    // speed
                    case self::ITEM_ATTR_SPEED:
                        // checks length
                        if($length != 2)
                        {
                            throw E_OTS_FileLoaderError(E_OTS_FileLoaderError::ERROR_INVALID_FORMAT);
                        }

                        $speed = $node->getShort();
                        break;

                    // server-side ID
                    case self::ITEM_ATTR_LIGHT2:
                        // checks length
                        if($length != 4)
                        {
                            throw E_OTS_FileLoaderError(E_OTS_FileLoaderError::ERROR_INVALID_FORMAT);
                        }

                        $lightLevel = $node->getShort();
                        $lightColor = $node->getShort();
                        break;

                    // server-side ID
                    case self::ITEM_ATTR_TOPORDER:
                        // checks length
                        if($length != 1)
                        {
                            throw E_OTS_FileLoaderError(E_OTS_FileLoaderError::ERROR_INVALID_FORMAT);
                        }

                        $topOrder = $node->getChar();
                        break;

                    // skips unknown attributes
                    default:
                        $node->skip($length);
                }
            }

            // checks if type exist
            if( isset($this->items[$id]) )
            {
                $type = $this->items[$id];
                $type->setGroup( $node->getType() );
                $type->setFlags($flags);

                // sets OTB attributes
                if( isset($clientId) )
                {
                    $type->setClientId($clientId);
                }

                if( isset($speed) )
                {
                    $type->setAttribute('speed', $speed);
                }

                if( isset($lightLevel) )
                {
                    $type->setAttribute('lightLevel', $lightLevel);
                }

                if( isset($lightColor) )
                {
                    $type->setAttribute('lightColor', $lightColor);
                }

                if( isset($topOrder) )
                {
                    $type->setAttribute('topOrder', $topOrder);
                }

                switch( $node->getType() )
                {
                    // container
                    case OTS_ItemType::ITEM_GROUP_CONTAINER:
                        $type->setType(OTS_ItemType::ITEM_TYPE_CONTAINER);
                        break;

                    // door
                    case OTS_ItemType::ITEM_GROUP_DOOR:
                        $type->setType(OTS_ItemType::ITEM_TYPE_DOOR);
                        break;

                    // magic field
                    case OTS_ItemType::ITEM_GROUP_MAGICFIELD:
                        $type->setType(OTS_ItemType::ITEM_TYPE_MAGICFIELD);
                        break;

                    // teleport field
                    case OTS_ItemType::ITEM_GROUP_TELEPORT:
                        $type->setType(OTS_ItemType::ITEM_TYPE_TELEPORT);
                        break;

                    // nothing special for this groups but we have to separate from default section
                    case OTS_ItemType::ITEM_GROUP_NONE:
                    case OTS_ItemType::ITEM_GROUP_GROUND:
                    case OTS_ItemType::ITEM_GROUP_RUNE:
                    case OTS_ItemType::ITEM_GROUP_SPLASH:
                    case OTS_ItemType::ITEM_GROUP_FLUID:
                    case OTS_ItemType::ITEM_GROUP_DEPRECATED:
                        break;

                    // unknown type
                    default:
                        throw new E_OTS_FileLoaderError(E_OTS_FileLoaderError::ERROR_INVALID_FORMAT);
                }
            }

            $node = $node->getNext();
        }
    }

/**
 * Returns OTB file version.
 * 
 * @return int OTB format version.
 */
    public function getOTBVersion()
    {
        return $this->otbVersion;
    }

/**
 * Returns client version.
 * 
 * @return int Client version.
 */
    public function getClientVersion()
    {
        return $this->clientVersion;
    }

/**
 * Returns build version.
 * 
 * @return int Build version.
 */
    public function getBuildVersion()
    {
        return $this->buildVersion;
    }

/**
 * Returns given item type.
 * 
 * @param int $id Item type (server) ID.
 * @return OTS_ItemType|null Returns item type of given ID (null if not exists).
 */
    public function getItemType($id)
    {
        if( isset($this->items[$id]) )
        {
            return $this->items[$id];
        }
        else
        {
            return null;
        }
    }

/**
 * Finds item type by it's name.
 * 
 * <p>
 * Note: If there are more then one items with same name this function will return first found server ID. It doesn't also mean that it will be the lowest ID - item types are ordered in order that they were loaded from items.xml file.
 * </p>
 * 
 * @param string $name Item type name.
 * @return int|bool Returns item type (server) ID (false if not found).
 */
    public function getItemTypeId($name)
    {
        foreach($this->items as $id => $type)
        {
            if( $type->getName() == $name)
            {
                // found it
                return $id;
            }
        }

        // not found
        return false;
    }

/**
 * Returns all loaded items.
 * 
 * @return array List of item types.
 * @deprecated 0.1.0 Use this class object as array for iterations, counting and methods for field fetching.
 */
    public function getItemTypesList()
    {
        return $this->items;
    }

/**
 * Returns amount of items loaded.
 * 
 * @return int Count of types.
 */
    public function count()
    {
        return count($this->items);
    }

/**
 * Returns item at current position in iterator.
 * 
 * @return string Item name.
 * @deprecated 0.1.0 Use getIterator().
 */
    public function current()
    {
        return current($this->items);
    }

/**
 * Moves to next iterator item.
 * 
 * @deprecated 0.1.0 Use getIterator().
 */
    public function next()
    {
        next($this->items);
    }

/**
 * Returns ID of current position.
 * 
 * @return int Current position key.
 * @deprecated 0.1.0 Use getIterator().
 */
    public function key()
    {
        return key($this->items);
    }

/**
 * Checks if there is anything more in interator.
 * 
 * @return bool If iterator has anything more.
 * @deprecated 0.1.0 Use getIterator().
 */
    public function valid()
    {
        return key($this->items) !== null;
    }

/**
 * Resets iterator index.
 * 
 * @deprecated 0.1.0 Use getIterator().
 */
    public function rewind()
    {
        reset($this->items);
    }

/**
 * Returns iterator handle for loops.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return ArrayIterator Items list iterator.
 */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

/**
 * Checks if given element exists.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string|int $offset Array key.
 * @return bool True if it's set.
 */
    public function offsetExists($offset)
    {
        // integer key
        if( is_int($offset) )
        {
            return isset($this->items[$offset]);
        }
        // item type name
        else
        {
            return $this->getItemTypeId($offset) !== false;
        }
    }

/**
 * Returns item from given position.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string|int $offset Array key.
 * @return mixed If key is an integer (type-sensitive!) then returns item type instance. If it's a string then return associated ID found by type name. False if offset is not set.
 */
    public function offsetGet($offset)
    {
        // integer key
        if( is_int($offset) )
        {
            if( isset($this->items[$offset]) )
            {
                return $this->items[$offset];
            }
            // keys is not set
            else
            {
                return false;
            }
        }
        // item type name
        else
        {
            return $this->getItemTypeId($offset);
        }
    }

/**
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to items list. Any call to this method will cause E_OTS_ReadOnly raise.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string|int $offset Array key.
 * @param mixed $value Field value.
 * @throws E_OTS_ReadOnly Always - this class is read-only.
 */
    public function offsetSet($offset, $value)
    {
        throw new E_OTS_ReadOnly();
    }

/**
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to items list. Any call to this method will cause E_OTS_ReadOnly raise.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string|int $offset Array key.
 * @throws E_OTS_ReadOnly Always - this class is read-only.
 */
    public function offsetUnset($offset)
    {
        throw new E_OTS_ReadOnly();
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'otbVersion':
            case 'clientVersion':
            case 'buildVersion':
                return $this->$name;

            default:
                throw new OutOfBoundsException();
        }
    }
}

/**#@-*/

?>
