<?php

/**#@+
 * @version 0.1.0
 * @since 0.1.0
 */

/**
 * @package POT
 * @version 0.1.3+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for house information.
 * 
 * @package POT
 * @version 0.1.3+SVN
 * @property OTS_Player $owner House owner.
 * @property int $paid Paid time.
 * @property int $warnings Warnings message.
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
 * @var array
 */
    private $data = array();

/**
 * Information handler.
 * 
 * @var DOMElement
 */
    private $element;

/**
 * Tiles list.
 * 
 * @var array
 */
    private $tiles = array();

/**
 * Creates wrapper for given house element.
 * 
 * @param DOMElement $element House information.
 */
    public function __construct(DOMElement $element)
    {
        parent::__construct();
        $this->element = $element;

        // loads SQL part - `id` field is not needed as we have it from XML
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('owner') . ', ' . $this->db->fieldName('paid') . ', ' . $this->db->fieldName('warnings') . ' FROM ' . $this->db->tableName('houses') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->getId() )->fetch();
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object serialisation.
 * 
 * @return array List of properties that should be saved.
 */
    public function __sleep()
    {
        return array('data', 'element');
    }

/**
 * Saves info in database.
 */
    public function save()
    {
        // inserts new record
        if( empty($this->data) )
        {
            $this->db->query('INSERT INTO ' . $this->db->tableName('houses') . ' (' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('owner') . ', ' . $this->db->fieldName('paid') . ', ' . $this->db->fieldName('warnings') . ') VALUES (' . $this->getId() . ', ' . $this->data['owner'] . ', ' . $this->data['paid'] . ', ' . $this->data['warnings'] . ')');
        }
        // updates previous one
        else
        {
            $this->db->query('UPDATE ' . $this->db->tableName('houses') . ' SET ' . $this->db->fieldName('id') . ' = ' . $this->getId() . ', ' . $this->db->fieldName('owner') . ' = ' . $this->data['owner'] . ', ' . $this->db->fieldName('paid') . ' = ' . $this->data['paid'] . ', ' . $this->db->fieldName('warnings') . ' = ' . $this->data['warnings'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->getId() );
        }
    }

/**
 * Deletes house info from database.
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
 * @return int House ID.
 */
    public function getId()
    {
        return (int) $this->element->getAttribute('houseid');
    }

/**
 * Return house's name.
 * 
 * @return string House name.
 */
    public function getName()
    {
        return $this->element->getAttribute('name');
    }

/**
 * Returns town ID in which house is located.
 * 
 * @return int Town ID.
 */
    public function getTownId()
    {
        return (int) $this->element->getAttribute('townid');
    }

/**
 * Returns town name.
 * 
 * @return string Town name.
 */
    public function getTownName()
    {
        return POT::getInstance()->getMap()->getTownName( $this->getTownId() );
    }

/**
 * Returns house rent cost.
 * 
 * @return int Rent cost.
 */
    public function getRent()
    {
        return (int) $this->element->getAttribute('rent');
    }

/**
 * Returns house size.
 * 
 * @return int House size.
 */
    public function getSize()
    {
        return (int) $this->element->getAttribute('size');
    }

/**
 * Returns entry position.
 * 
 * @return OTS_MapCoords Entry coordinations on map.
 */
    public function getEntry()
    {
        return new OTS_MapCoords( (int) $this->element->getAttribute('entryx'), (int) $this->element->getAttribute('entryy'), (int) $this->element->getAttribute('entryz') );
    }

/**
 * Returns current house owner.
 * 
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
 * @param OTS_Player $player House owner to be set.
 */
    public function setOwner(OTS_Player $player)
    {
        $this->data['owner'] = $player->getId();
    }

/**
 * Returns paid date.
 * 
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
 * @version 0.1.2
 * @param int $warnings Sets house warnings.
 */
    public function setWarnings($warnings)
    {
        $this->data['warnings'] = (int) $warnings;
    }

/**
 * Adds tile to house.
 * 
 * @param OTS_MapCoords $tile Tile to be added.
 */
    public function addTile(OTS_MapCoords $tile)
    {
        $this->tiles[] = $tile;
    }

/**
 * Returns tiles list.
 * 
 * @return array List of tiles.
 */
    public function getTiles()
    {
        return $this->tiles;
    }

/**
 * Magic PHP5 method.
 * 
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
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
 * @param string $name Property name.
 * @param mixed $value Property value.
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

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Returns string representation of object.
 * 
 * If any display driver is currently loaded then it uses it's method. Otherwise just returns house ID.
 * 
 * @version 0.1.3+SVN
 * @since 0.1.3+SVN
 * @return string String representation of object.
 */
    public function __toString()
    {
        $ots = POT::getInstance();

        // checks if display driver is loaded
        if( $ots->isDataDisplayDriverLoaded() )
        {
            return $ots->getDataDisplayDriver()->displayHouse($this);
        }

        return $this->getId();
    }
}

/**#@-*/

?>
