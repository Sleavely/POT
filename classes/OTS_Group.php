<?php

/**#@+
 * @version 0.0.1
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ user group abstraction.
 * 
 * @package POT
 */
class OTS_Group implements IOTS_DAO
{
/**
 * Database connection.
 * 
 * @var IOTS_DB
 */
    private $db;

/**
 * Player data.
 * 
 * @var array
 */
    private $data = array('flags' => 0);

/**
 * Sets database connection handler.
 * 
 * @param IOTS_DB $db Database connection object.
 */
    public function __construct(IOTS_DB $db)
    {
        $this->db = $db;
    }

/**
 * Loads group with given id.
 * 
 * @param int $id Group number.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('flags') . ', ' . $this->db->fieldName('access') . ', ' . $this->db->fieldName('maxdepotitems') . ', ' . $this->db->fieldName('maxviplist') . ' FROM ' . $this->db->tableName('groups') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Checks if object is loaded.
 * 
 * @return bool Load state.
 */
    public function isLoaded()
    {
        return isset($this->data['id']);
    }

/**
 * Saves account in database.
 */
    public function save()
    {
        // updates existing group
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->SQLquery('UPDATE ' . $this->db->tableName('groups') . ' SET ' . $this->db->fieldName('name') . ' = ' . $this->db->SQLquote($this->data['name']) . ', ' . $this->db->fieldName('flags') . ' = ' . $this->data['flags'] . ', ' . $this->db->fieldName('access') . ' = ' . $this->data['access'] . ', ' . $this->db->fieldName('maxdepotitems') . ' = ' . $this->data['maxdepotitems'] . ', ' . $this->db->fieldName('maxviplist') . ' = ' . $this->data['maxviplist'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new group
        else
        {
            // INSERT query on database
            $this->db->SQLquery('INSERT INTO ' . $this->db->tableName('groups') . ' (' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('flags') . ', ' . $this->db->fieldName('access') . ', ' . $this->db->fieldName('maxdepotitems') . ', ' . $this->db->fieldName('maxviplist') . ') VALUES (' . $this->db->quote($this->data['name']) . ', ' . $this->data['flags'] . ', ' . $this->data['access'] . ', ' . $this->data['maxdepotitems'] . ', ' . $this->data['maxviplist'] . ')');
            // ID of new group
            $this->data['id'] = $this->db->lastInsertId();
        }
    }

/**
 * Group ID.
 * 
 * @return int|bool Group ID (false if not loaded).
 */
    public function getId()
    {
        if( !isset($this->data['id']) )
        {
            trigger_error('Tries to get property of not loaded group.', E_USER_NOTICE);
            return false;
        }

        return $this->data['id'];
    }

/**
 * Group name.
 * 
 * @return string|bool Name (false if not loaded).
 */
    public function getName()
    {
        if( !isset($this->data['name']) )
        {
            trigger_error('Tries to get property of not loaded group.', E_USER_NOTICE);
            return false;
        }

        return $this->data['name'];
    }

/**
 * Sets group's name.
 * 
 * @param string $name Name.
 */
    public function setName($name)
    {
        $this->data['name'] = (string) $name;
    }

/**
 * Rights flags.
 * 
 * @return int|bool Flags (false if not loaded).
 */
    public function getFlags()
    {
        if( !isset($this->data['flags']) )
        {
            trigger_error('Tries to get property of not loaded group.', E_USER_NOTICE);
            return false;
        }

        return $this->data['flags'];
    }

/**
 * Sets rights flags.
 * 
 * @param int $flags Flags.
 */
    public function setFlags($flags)
    {
        $this->data['flags'] = (int) $flags;
    }

/**
 * Access level.
 * 
 * @return int|bool Access level (false if not loaded).
 */
    public function getAccess()
    {
        if( !isset($this->data['access']) )
        {
            trigger_error('Tries to get property of not loaded group.', E_USER_NOTICE);
            return false;
        }

        return $this->data['access'];
    }

/**
 * Sets access level.
 * 
 * @param int $access Access level.
 */
    public function setAccess($access)
    {
        $this->data['access'] = (int) $access;
    }

/**
 * Maximum count of items in depot.
 * 
 * @return int|bool Maximum value (false if not loaded).
 */
    public function getMaxDepotItems()
    {
        if( !isset($this->data['maxdepotitems']) )
        {
            trigger_error('Tries to get property of not loaded group.', E_USER_NOTICE);
            return false;
        }

        return $this->data['maxdepotitems'];
    }

/**
 * Sets maximum count of items in depot.
 * 
 * @param int $maxdepotitems Maximum value.
 */
    public function setMaxDepotItems($maxdepotitems)
    {
        $this->data['maxdepotitems'] = (int) $maxdepotitems;
    }

/**
 * Maximum count of players in VIP list.
 * 
 * @return int|bool Maximum value (false if not loaded).
 */
    public function getMaxVIPList()
    {
        if( !isset($this->data['maxviplist']) )
        {
            trigger_error('Tries to get property of not loaded group.', E_USER_NOTICE);
            return false;
        }

        return $this->data['maxviplist'];
    }

/**
 * Sets maximum count of players in VIP list.
 * 
 * @param int $maxdepotitems Maximum value.
 */
    public function setMaxVIPList($maxviplist)
    {
        $this->data['maxviplist'] = (int) $maxviplist;
    }

/**
 * List of characters in given group.
 * 
 * @return array|bool Array of OTS_Player objects from given group (false if not loaded).
 */
    public function getPlayers()
    {
        if( !isset($this->data['id']) )
        {
            trigger_error('Tries to get characters list of not loaded group.', E_USER_NOTICE);
            return false;
        }

        $players = array();

        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('group_id') . ' = ' . $this->data['id']) as $player)
        {
            // creates new object
            $object = POT::getInstance()->createObject('Player');
            $object->load($player['id']);
            $players[] = $object;
        }

        return $players;
    }
}

/**#@-*/

?>
