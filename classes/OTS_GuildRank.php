<?php

/**#@+
 * @version 0.0.3+SVN
 * @since 0.0.3+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ guild rank abstraction.
 * 
 * @package POT
 */
class OTS_GuildRank implements IOTS_DAO
{
/**
 * Database connection.
 * 
 * @var IOTS_DB
 */
    private $db;

/**
 * Rank data.
 * 
 * @var array
 */
    private $data = array();

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
 * Loads rank with given id.
 * 
 * @param int $id Rank's ID.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('guild_id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('level') . ' FROM ' . $this->db->tableName('guild_ranks') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Loads rank by it's name.
 * 
 * As there can be several ranks with same name in different guilds you can pass optional second parameter to specify in which guild script should look for rank.
 * 
 * @param string $name Rank's name.
 * @param OTS_Guild $guild Guild in which rank should be found.
 */
    public function find($name, OTS_Guild $guild = null)
    {
        $where = '';

        // additional guild id criterium
        if( isset($guild) )
        {
            $where = ' AND ' . $this->db->fieldName('guild_id') . ' = ' . $guild->getId();
        }

        // finds player's ID
        $id = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('name') . ' = ' . $this->db->SQLquote($name) . $where)->fetch();

        // if anything was found
        if( isset($id['id']) )
        {
            $this->load($id['id']);
        }
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
 * Saves rank in database.
 */
    public function save()
    {
        // updates existing rank
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->SQLquery('UPDATE ' . $this->db->tableName('guild_ranks') . ' SET ' . $this->db->fieldName('guild_id') . ' = ' . $this->db->SQLquote($this->data['guild_id']) . ', ' . $this->db->fieldName('name') . ' = ' . $this->data['name'] . ', ' . $this->db->fieldName('level') . ' = ' . $this->data['level'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new rank
        else
        {
            // INSERT query on database
            $this->db->SQLquery('INSERT INTO ' . $this->db->tableName('guild_ranks') . ' (' . $this->db->fieldName('guild_id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('level') . ') VALUES (' . $this->data['guild_id'] . ', ' . $this->db->SQLquote($this->data['name']) . ', ' . $this->data['level'] . ')');
            // ID of new rank
            $this->data['id'] = $this->db->lastInsertId();
        }
    }

/**
 * Rank ID.
 * 
 * @return int Rank ID.
 * @throws E_OTS_NotLoaded If rank is not loaded.
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
 * Rank name.
 * 
 * @return string Rank's name.
 * @throws E_OTS_NotLoaded If rank is not loaded.
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
 * Sets rank's name.
 * 
 * @param string $name Name.
 */
    public function setName($name)
    {
        $this->data['name'] = (string) $name;
    }

/**
 * Returns guild of this rank.
 * 
 * @return OTS_Guild Guild of this rank.
 * @throws E_OTS_NotLoaded If rank is not loaded.
 */
    public function getGuild()
    {
        if( !isset($this->data['guild_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $guild = POT::getInstance()->createObject('Guild');
        $guild->load($this->data['guild_id']);
        return $guild;
    }

/**
 * Assigns rank to guild.
 * 
 * @param OTS_Guild $guild Owning guild.
 */
    public function setGuild(OTS_Guild $guild)
    {
        $this->data['guild_id'] = $guild->getId();
    }

/**
 * Rank's access level.
 * 
 * @return int Rank access level within guild.
 * @throws E_OTS_NotLoaded If rank is not loaded.
 */
    public function getLevel()
    {
        if( !isset($this->data['level']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['level'];
    }

/**
 * Sets rank's access level within guild.
 * 
 * @param int $level access level within guild.
 */
    public function setLevel($level)
    {
        $this->data['level'] = (int) $level;
    }

/**
 * Reads custom field.
 * 
 * Reads field by it's name. Can read any field of given record that exists in database.
 * 
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * 
 * @param string $field Field name.
 * @return string Field value.
 * @throws E_OTS_NotLoaded If rank is not loaded.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $value = $this->db->SQLquery('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('guild_ranks') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetch();
        return $value[$field];
    }

/**
 * Writes custom field.
 * 
 * Write field by it's name. Can write any field of given record that exists in database.
 * 
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * 
 * Note: Make sure that you pass $value argument of correct type. This method determinates whether to quote field value. It is safe - it makes you sure that no unproper queries that could lead to SQL injection will be executed, but it can make your code working wrong way. For example: $object->setCustomField('foo', '1'); will quote 1 as as string ('1') instead of passing it as a integer.
 * 
 * @param string $field Field name.
 * @param mixed $value Field value.
 * @throws E_OTS_NotLoaded If rank is not loaded.
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

        $this->db->SQLquery('UPDATE ' . $this->db->tableName('guild_ranks') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * Reads all players who has this rank set.
 * 
 * @return array List of members.
 * @throws E_OTS_NotLoaded If rank is not loaded.
 */
    public function getPlayers()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $players = array();

        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('rank_id') . ' = ' . $this->data['id'])->fetchAll() as $player)
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
