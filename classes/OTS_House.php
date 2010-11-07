<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for house information.
 * 
 * <p>
 * Unlike other {@link OTS_Base_DAO OTS_Base_DAO} child classes, OTS_House bases not only on database, but also loads some additional info from XML DOM node. It can't be load - it is always initialised with {@link http://www.php.net/manual/en/ref.dom.php DOMElement} object. Saving will only update database row - won't change XML data. Same about using {@link OTS_House::delete() delete() method}.
 * </p>
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @property OTS_Player $owner House owner.
 * @property int $paid Paid time.
 * @property int $warnings Warnings message.
 * @property bool $guildHall Guild hall type.
 * @property int $door Doors.
 * @property int $beds Beds.
 * @property int $lastWarning Last warning.
 * @property-read int $id House ID.
 * @property-read string $name House name.
 * @property-read int $townId ID of town where house is located.
 * @property-read string $townName Name of town where house is located.
 * @property-read int $rent Rent cost.
 * @property-read int $size House size.
 * @property-read OTS_MapCoords $entry Entry point.
 * @property-read array $tiles List of tile points which house uses.
 */
class OTS_House extends OTS_Base_DAO
{
/**
 * House rent info.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var array
 */
    private $data = array();

/**
 * Information handler.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var DOMElement
 */
    private $element;

/**
 * Tiles list.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var array
 */
    //private $tiles = array();

/**
 * Creates wrapper for given house element.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param DOMElement $element House information.
 * @throws PDOException On PDO operation error.
 */
    public function __construct(DOMElement $element)
    {
        parent::__construct();
        $this->element = $element;

        // loads SQL part - `id` field is not needed as we have it from XML
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('townid') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('rent') . ', ' . $this->db->fieldName('guildhall') . ', ' . $this->db->fieldName('tiles') . ', ' . $this->db->fieldName('doors') . ', ' . $this->db->fieldName('beds') . ', ' . $this->db->fieldName('lastwarning') . ', ' . $this->db->fieldName('paid') . ', ' . $this->db->fieldName('owner') . ', ' . $this->db->fieldName('warnings') . ' FROM ' . $this->db->tableName('houses') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->getId() )->fetch();
    }

/**
 * Magic PHP5 method.
 * 
 * <p>
 * Allows object serialisation.
 * </p>
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return array List of properties that should be saved.
 */
    public function __sleep()
    {
        return array('data', 'element');
    }

/**
 * Saves info in database.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @throws PDOException On PDO operation error.
 */
    public function save()
    {
        // inserts new record
        if( empty($this->data) )
        {
            $this->db->query('INSERT INTO ' . $this->db->tableName('houses') . ' (' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('guildhall') . ', ' . $this->db->fieldName('tiles') . ', ' . $this->db->fieldName('doors') . ', ' . $this->db->fieldName('beds') . ', ' . $this->db->fieldName('lastwarning') . ', ' . $this->db->fieldName('owner') . ', ' . $this->db->fieldName('paid') . ', ' . $this->db->fieldName('warnings') . ') VALUES (' . $this->getId() . ', ' . $this->data['owner'] . ', ' . (int) $this->data['guildhall'] . ', ' . $this->data['doors'] . ', ' . $this->data['beds'] . ', ' . $this->data['lastwarning'] . ', ' . $this->data['paid'] . ', ' . $this->data['warnings'] . ')');
        }
        // updates previous one
        else
        {
            $this->db->query('UPDATE ' . $this->db->tableName('houses') . ' SET ' . $this->db->fieldName('id') . ' = ' . $this->getId() . ', ' . $this->db->fieldName('owner') . ' = ' . $this->data['owner'] . ', ' . $this->db->fieldName('guildhall') . ' = ' . (int) $this->data['guildhall'] . ', ' . $this->db->fieldName('tiles') . ' = ' . $this->data['tiles'] . ', ' . $this->db->fieldName('doors') . ' = ' . $this->data['doors'] . ', ' . $this->db->fieldName('beds') . ' = ' . $this->data['beds'] . ', ' . $this->db->fieldName('lastwarning') . ' = ' . $this->data['lastwarning'] . ', ' . $this->db->fieldName('paid') . ' = ' . $this->data['paid'] . ', ' . $this->db->fieldName('warnings') . ' = ' . $this->data['warnings'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->getId() );
        }
    }

/**
 * Deletes house info from database.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @throws PDOException On PDO operation error.
 */
    public function delete()
    {
        // deletes row from database
        $this->db->query('DELETE FROM ' . $this->db->tableName('houses') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);

        // resets object handle
        $this->data = array();
    }

/**
 * Returns house's ID.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int House ID.
 * @throws DOMException On DOM operation error.
 */
    public function getId()
    {
        return (int) $this->element->getAttribute('houseid');
    }

/**
 * Return house's name.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return string House name.
 * @throws DOMException On DOM operation error.
 */
    public function getName()
    {
        return $this->element->getAttribute('name');
    }

/**
 * Returns town ID in which house is located.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int Town ID.
 * @throws DOMException On DOM operation error.
 */
    public function getTownId()
    {
        return (int) $this->element->getAttribute('townid');
    }

/**
 * Returns town name.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return string Town name.
 * @throws E_OTS_NotLoaded When map file is not loaded to fetch towns names.
 */
    public function getTownName()
    {
        return POT::getMap()->getTownName( $this->getTownId() );
    }

/**
 * Returns house rent cost.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int Rent cost.
 * @throws DOMException On DOM operation error.
 */
    public function getRent()
    {
        return (int) $this->element->getAttribute('rent');
    }

/**
 * Returns house size.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int House size.
 * @throws DOMException On DOM operation error.
 */
    public function getSize()
    {
        return (int) $this->element->getAttribute('size');
    }

/**
 * Returns entry position.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return OTS_MapCoords Entry coordinations on map.
 */
    public function getEntry()
    {
        return new OTS_MapCoords( (int) $this->element->getAttribute('entryx'), (int) $this->element->getAttribute('entryy'), (int) $this->element->getAttribute('entryz') );
    }

/**
 * Returns current house owner.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return OTS_Player|null Player that currently owns house (null if there is no owner).
 */
    public function getOwner()
    {
        if( isset($this->data['owner']) && $this->data['owner'] != 0)
        {
            $player = new OTS_Player();
            $player->load($this->data['owner']);
            return $player;
        }
        // not rent
        else
        {
            return null;
        }
    }

/**
 * Sets house owner.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param OTS_Player $player House owner to be set.
 * @throws E_OTS_NotLoaded If given <var>$player</var> object is not loaded.
 */
    public function setOwner(OTS_Player $player)
    {
        $this->data['owner'] = $player->getId();
    }

/**
 * Checks if house is guildhall.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return bool Guildhall type flag.
 */
    public function isGuildHall()
    {
        return isset($this->data['guildhall']) && $this->data['guildhall'];
    }

/**
 * Unsets guildhall type.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    public function unsetGuildHall()
    {
        $this->data['guildhall'] = false;
    }

/**
 * Sets guildhall type.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    public function setGuildHall()
    {
        $this->data['guildhall'] = true;
    }

/**
 * Returns paid date.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int|false Date timestamp until which house is rent (false if none).
 */
    public function getPaid()
    {
        if( isset($this->data['paid']) )
        {
            return $this->data['paid'];
        }
        // not rent
        else
        {
            return false;
        }
    }

/**
 * Sets paid date.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param int $paid Sets paid timestamp to passed one.
 */
    public function setPaid($paid)
    {
        $this->data['paid'] = $paid;
    }

/**
 * Returns house warnings.
 * 
 * @version 0.1.2
 * @since 0.1.0
 * @return int|false Warnings text (false if none).
 */
    public function getWarnings()
    {
        if( isset($this->data['warnings']) )
        {
            return $this->data['warnings'];
        }
        // not rent
        else
        {
            return false;
        }
    }

/**
 * Sets house warnings.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.1.2
 * @since 0.1.0
 * @param int $warnings Sets house warnings.
 */
    public function setWarnings($warnings)
    {
        $this->data['warnings'] = (int) $warnings;
    }

/**
 * Returns house doors.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int|false House doors (false if none).
 */
    public function getDoors()
    {
        if( isset($this->data['doors']) )
        {
            return $this->data['doors'];
        }
        // not set
        else
        {
            return false;
        }
    }

/**
 * Sets house doors.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $doors Sets house doors.
 */
    public function setDoors($doors)
    {
        $this->data['doors'] = (int) $doors;
    }

/**
 * Returns house beds.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int|false House beds (false if none).
 */
    public function getBeds()
    {
        if( isset($this->data['beds']) )
        {
            return $this->data['beds'];
        }
        // not set
        else
        {
            return false;
        }
    }

/**
 * Sets house beds.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $beds Sets house beds.
 */
    public function setBeds($beds)
    {
        $this->data['beds'] = (int) $beds;
    }

/**
 * Returns house last warning.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int|false House last warning text (false if none).
 */
    public function getLastWarning()
    {
        if( isset($this->data['lastwarning']) )
        {
            return $this->data['lastwarning'];
        }
        // not rent
        else
        {
            return false;
        }
    }

/**
 * Sets house last warning.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $lastWarning Sets house last warning.
 */
    public function setLastWarning($lastWarning)
    {
        $this->data['lastwarning'] = (int) $lastWarning;
    }
    
/**
 * Returns house tiles.
 * 
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @return int House tiles.
 */
    public function getTiles()
    {
        if( isset($this->data['tiles']) )
        {
            return $this->data['tiles'];
        }
        // not set
        else
        {
            return false;
        }
    }

/**
 * Sets house tiles.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @param int $lastWarning Sets house last warning.
 */
    public function setTile($tile)
    {
        $this->data['tiles'] = (int) $tile;
    }

/**
 * Adds tile to house.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param OTS_MapCoords $tile Tile to be added.
 * @deprecated Use setTile() for that
 */
    public function addTile(OTS_MapCoords $tile)
    {
        //$this->tiles[] = $tile;
    }

/**
 * Returns tiles list.
 * 
 * <p>
 * This returns list of coords of tiles used by this house on map. It will succeed only if house object was created during map loading with houses file opened to assign loaded tiles.
 * </p>
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return array List of tiles.
 */
 /*
    public function getTiles()
    {
        return $this->tiles;
    }
*/

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws E_OTS_NotLoaded When atempt to read info about map while map not being loaded.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws DOMException On DOM operation error.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'id':
                return $this->getId();

            case 'name':
                return $this->getName();

            case 'townId':
                return $this->getTownId();

            case 'townName':
                return $this->getTownName();

            case 'rent':
                return $this->getRent();

            case 'size':
                return $this->getSize();

            case 'entry':
                return $this->getEntry();

            case 'owner':
                return $this->getOwner();

            case 'guildHall':
                return $this->isGuildHall();

            case 'doors':
                return $this->getDoors();

            case 'beds':
                return $this->getBeds();

            case 'lastWarning':
                return $this->getLastWarning();

            case 'paid':
                return $this->getPaid();

            case 'warnings':
                return $this->getWarnings();

            case 'tiles':
                return $this->getTiles();

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param string $name Property name.
 * @param mixed $value Property value.
 * @throws E_OTS_NotLoaded If passed parameter for owner field won't be loaded.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'owner':
                $this->setOwner($value);
                break;

            case 'paid':
                $this->setPaid($value);
                break;

            case 'warnings':
                $this->setWarnings($values);
                break;

            case 'guildHall':
                if($value)
                {
                    $this->setGuildHall();
                }
                else
                {
                    $this->unsetGuildHall();
                }
                break;
                
            case 'tiles':
                $this->setTiles($values);
                break;

            case 'doors':
                $this->setDoors($values);
                break;

            case 'beds':
                $this->setBeds($values);
                break;

            case 'lastWarning':
                $this->setLastWarning($values);
                break;

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Returns string representation of object.
 * 
 * <p>
 * If any display driver is currently loaded then it uses it's method. Otherwise just returns house ID.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.3
 * @return string String representation of object.
 */
    public function __toString()
    {
        // checks if display driver is loaded
        if( POT::isDataDisplayDriverLoaded() )
        {
            return POT::getDataDisplayDriver()->displayHouse($this);
        }

        return $this->getId();
    }
}

?>