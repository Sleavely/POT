<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.3
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Single item representation.
 * 
 * <p>
 * This class represents item that player has. It has no information about item feature, just it's handle in database. To get information about item type and it's features you have to use {@link OTS_ItemType OTS_ItemType class} - you can get it's object by calling {@link OTS_Item::getItemType() getItemType() method}, however you need to have global item types list loaded.
 * </p>
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.3
 * @property int $count Amount of item.
 * @property string $attributes Attributes binary string.
 * @property-read int $id Item type ID.
 * @property-read OTS_ItemType $itemType Item type instance.
 */
class OTS_Item implements Countable
{
/**
 * Item ID.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @var int
 */
    private $id;

/**
 * Item count.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @var int
 */
    private $count = 0;

/**
 * Additional attributes.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @var string
 */
    private $attributes;

/**
 * Creates item of given ID.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param int $id Item ID.
 */
    public function __construct($id)
    {
        $this->id = $id;
    }

/**
 * Returns item type.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @return int Item ID.
 */
    public function getId()
    {
        return $this->id;
    }

/**
 * Returns count of item.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @return int Count of item.
 */
    public function getCount()
    {
        return $this->count;
    }

/**
 * Sets count of item.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param int $count Count.
 */
    public function setCount($count)
    {
        $this->count = (int) $count;
    }

/**
 * Returns item custom attributes.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @return string Attributes.
 */
    public function getAttributes()
    {
        return $this->attributes;
    }

/**
 * Sets item attributes.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param string $attributes Item Attributes.
 */
    public function setAttributes($attributes)
    {
        $this->attributes = (string) $attributes;
    }

/**
 * Returns type of item.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return OTS_ItemType Returns item type of item (null if not exists).
 * @throws E_OTS_NotLoaded If global items list wasn't loaded.
 */
    public function getItemType()
    {
        return POT::getItemsList()->getItemType($this->id);
    }

/**
 * Count value for current item.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @return int Count of item.
 */
    public function count()
    {
        return $this->count;
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws E_OTS_NotLoaded If global items list wasn't loaded.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'count':
                return $this->getCount();

            case 'attributes':
                return $this->getAttributes();

            case 'id':
                return $this->getId();

            case 'itemType':
                return $this->getItemType();

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name Property name.
 * @param mixed $value Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'count':
                $this->setCount($value);
                break;

            case 'attributes':
                $this->setAttributes($value);
                break;

            default:
                throw new OutOfBoundsException();
        }
    }
}

?>
