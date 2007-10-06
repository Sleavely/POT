<?php

/**#@+
 * @version 0.0.1
 */

/**
 * @package POT
 * @version 0.0.3
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ user group abstraction.
 * 
 * @package POT
 * @version 0.0.3
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
 * @version 0.0.3
 * @return int Group ID.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function getId()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['id'];
    }

/**
 * Group name.
 * 
 * @version 0.0.3
 * @return string Name.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function getName()
    {
        if( !isset($this->data['name']) )
        {
            throw new E_OTS_NotLoaded();
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
 * @version 0.0.3
 * @return int Flags.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function getFlags()
    {
        if( !isset($this->data['flags']) )
        {
            throw new E_OTS_NotLoaded();
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
 * @version 0.0.3
 * @return int Access level.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function getAccess()
    {
        if( !isset($this->data['access']) )
        {
            throw new E_OTS_NotLoaded();
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
 * @version 0.0.3
 * @return int Maximum value.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function getMaxDepotItems()
    {
        if( !isset($this->data['maxdepotitems']) )
        {
            throw new E_OTS_NotLoaded();
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
 * @version 0.0.3
 * @return int Maximum value.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function getMaxVIPList()
    {
        if( !isset($this->data['maxviplist']) )
        {
            throw new E_OTS_NotLoaded();
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
 * Reads custom field.
 * 
 * Reads field by it's name. Can read any field of given record that exists in database.
 * 
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param string $field Field name.
 * @return string Field value.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $value = $this->db->SQLquery('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('groups') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetch();
        return $value[$field];
    }

/**
 * Writes custom field.
 * 
 * Write field by it's name. Can write any field of given record that exists in database.
 * 
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * 
 * Note: Make sure that you pass $value argument of correct type. This method determinates whether to quote field name. It is safe - it makes you sure that no unproper queries that could lead to SQL injection will be executed, but it can make your code working wrong way. For example: $object->setCustomField('foo', '1'); will quote 1 as as string ('1') instead of passing it as a integer.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param string $field Field name.
 * @param mixed $value Field value.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function setCustomField($field, $value)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // quotes value for SQL query
        if(!( is_int($value) || is_float($value) ))
        {
            $value = $this->db->SQLquote($value);
        }

        $this->db->SQLquery('UPDATE ' . $this->db->tableName('groups') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * List of characters in given group.
 * 
 * @version 0.0.3
 * @return array Array of OTS_Player objects from given group.
 * @throws E_OTS_NotLoaded If group is not loaded.
 */
    public function getPlayers()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $players = array();

        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('group_id') . ' = ' . $this->data['id'])->fetchAll() as $player)
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
