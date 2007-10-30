<?php

/**#@+
 * @version 0.0.4
 * @since 0.0.4
 */

/**
 * @package POT
 * @version 0.0.4+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ guild abstraction.
 * 
 * @package POT
 * @version 0.0.4+SVN
 */
class OTS_Guild implements IOTS_DAO
{
/**
 * Database connection.
 * 
 * @var IOTS_DB
 */
    private $db;

/**
 * Guild data.
 * 
 * @var array
 */
    private $data = array();

/**
 * Invites handler.
 * 
 * @var IOTS_GuildAction
 */
    private $invites;

/**
 * Membership requests handler.
 * 
 * @var IOTS_GuildAction
 */
    private $requests;

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
 * Magic PHP5 method.
 * 
 * Allows object serialisation.
 * 
 * @return array List of properties that should be saved.
 * @internal Magic PHP5 method.
 */
    public function __sleep()
    {
        return array('data', 'invites', 'requests');
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object unserialisation.
 * 
 * @internal Magic PHP5 method.
 */
    public function __wakeup()
    {
        $this->db = POT::getInstance()->getDBHandle();
    }

/**
 * Creates clone of object.
 * 
 * Copy of object needs to have different ID.
 * 
 * @internal magic PHP5 method.
 */
    public function __clone()
    {
        unset($this->data['id']);

        $this->invites = clone $this->invites;
        $this->invites->__construct($this);

        $this->requests = clone $this->requests;
        $this->requests->__construct($this);
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @internal Magic PHP5 method.
 * @param array $properties List of object properties.
 */
    public static function __set_state(array $properties)
    {
        // deletes database handle
        if( isset($properties['db']) )
        {
            unset($properties['db']);
        }

        // initializes new object with current database connection
        $object = new self( POT::getInstance()->getDBHandle() );

        // loads properties
        foreach($properties as $name => $value)
        {
            $object->$name = $value;
        }

        return $object;
    }

/**
 * Assigns invites handler.
 * 
 * @param IOTS_GuildAction $invites Invites driver (don't pass it to clear driver).
 */
    public function setInvitesDriver(IOTS_GuildAction $invites = null)
    {
        $this->invites = $invites;
    }

/**
 * Assigns requests handler.
 * 
 * @param IOTS_GuildAction $requests Membership requests driver (don't pass it to clear driver).
 */
    public function setRequestsDriver(IOTS_GuildAction $requests = null)
    {
        $this->requests = $requests;
    }

/**
 * Loads guild with given id.
 * 
 * @param int $id Guild's ID.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('ownerid') . ', ' . $this->db->fieldName('creationdata') . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Loads guild by it's name.
 * 
 * @param string $name Guild's name.
 */
    public function find($name)
    {
        // finds player's ID
        $id = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('name') . ' = ' . $this->db->SQLquote($name) )->fetch();

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
 * Saves guild in database.
 */
    public function save()
    {
        // updates existing guild
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->SQLquery('UPDATE ' . $this->db->tableName('guilds') . ' SET ' . $this->db->fieldName('name') . ' = ' . $this->db->SQLquote($this->data['name']) . ', ' . $this->db->fieldName('ownerid') . ' = ' . $this->data['ownerid'] . ', ' . $this->db->fieldName('creationdata') . ' = ' . $this->data['creationdata'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new guild
        else
        {
            // INSERT query on database
            $this->db->SQLquery('INSERT INTO ' . $this->db->tableName('guilds') . ' (' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('ownerid') . ', ' . $this->db->fieldName('creationdata') . ') VALUES (' . $this->db->SQLquote($this->data['name']) . ', ' . $this->data['ownerid'] . ', ' . $this->data['creationdata'] . ')');
            // ID of new group
            $this->data['id'] = $this->db->lastInsertId();
        }
    }

/**
 * Guild ID.
 * 
 * @return int Guild ID.
 * @throws E_OTS_NotLoaded If guild is not loaded.
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
 * Guild name.
 * 
 * @return string Guild's name.
 * @throws E_OTS_NotLoaded If guild is not loaded.
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
 * Sets players's name.
 * 
 * @param string $name Name.
 */
    public function setName($name)
    {
        $this->data['name'] = (string) $name;
    }

/**
 * Returns owning player of this player.
 * 
 * @return OTS_Player Owning player.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 */
    public function getOwner()
    {
        if( !isset($this->data['ownerid']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $owner = POT::getInstance()->createObject('Player');
        $owner->load($this->data['ownerid']);
        return $owner;
    }

/**
 * Assigns guild to owner.
 * 
 * @param OTS_Player $owner Owning player.
 */
    public function setOwner(OTS_Player $owner)
    {
        $this->data['ownerid'] = $owner->getId();
    }

/**
 * Guild creation data.
 * 
 * @return int Guild creation data.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 */
    public function getCreationData()
    {
        if( !isset($this->data['creationdata']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['creationdata'];
    }

/**
 * Sets guild creation data.
 * 
 * @param int $creationdata Guild creation data.
 */
    public function setCreationData($creationdata)
    {
        $this->data['creationdata'] = (int) $creationdata;
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
 * @throws E_OTS_NotLoaded If guild is not loaded.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $value = $this->db->SQLquery('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'] . ' ORDER BY ' . $this->db->fieldName('level') . ' DESC')->fetch();
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
 * @throws E_OTS_NotLoaded If guild is not loaded.
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

        $this->db->SQLquery('UPDATE ' . $this->db->tableName('guilds') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * Reads all ranks that are in this guild.
 * 
 * @return array List of ranks.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @deprecated 0.0.4+SVN Use getGuildRanksList().
 */
    public function getGuildRanks()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $guildRanks = array();

        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('guild_ranks') . ' WHERE ' . $this->db->fieldName('guild_id') . ' = ' . $this->data['id'])->fetchAll() as $guildRank)
        {
            // creates new object
            $object = POT::getInstance()->createObject('GuildRank');
            $object->load($guildRank['id']);
            $guildRanks[] = $object;
        }

        return $guildRanks;
    }

/**
 * List of ranks in guild.
 * 
 * In difference to {@link OTS_Guild::getGuildRanks() getGuildRanks() method} this method returns filtered {@link OTS_GuildRanks_List OTS_GuildRanks_List} object instead of array of {@link OTS_GuildRank OTS_GuildRank} objects. It is more effective since OTS_GuildRanks_List doesn't perform all rows loading at once.
 * 
 * @version 0.0.4+SVN
 * @since 0.0.4+SVN
 * @return OTS_GuildRanks_List List of ranks from current guild.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 */
    public function getGuildRanksList()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $ots = POT::getInstance();

        // creates filter
        $filter = $ots->createFilter();
        $filter->compareField('guild_id', (int) $this->data['id']);

        // creates list object
        $list = $ots->createObject('GuildRanks_List');
        $list->setFilter($filter);

        return $list;
    }

/**
 * Returns list of invited players.
 * 
 * @return array List of invited players.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws E_OTS_NoDriver If there is no invites driver assigned.
 */
    public function listInvites()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if( !isset($this->invites) )
        {
            throw new E_OTS_NoDriver();
        }

        // driven action
        return $this->invites->listRequests();
    }

/**
 * Invites player to guild.
 * 
 * @param OTS_Player Player to be invited.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws E_OTS_NoDriver If there is no invites driver assigned.
 */
    public function invite(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if( !isset($this->invites) )
        {
            throw new E_OTS_NoDriver();
        }

        // driven action
        return $this->invites->addRequest($player);
    }

/**
 * Deletes invitation for player to guild.
 * 
 * @param OTS_Player Player to be un-invited.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws E_OTS_NoDriver If there is no invites driver assigned.
 */
    public function deleteInvite(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if( !isset($this->invites) )
        {
            throw new E_OTS_NoDriver();
        }

        // driven action
        return $this->invites->deleteRequest($player);
    }

/**
 * Finalise invitation.
 * 
 * @param OTS_Player Player to be joined.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws E_OTS_NoDriver If there is no invites driver assigned.
 */
    public function acceptInvite(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if( !isset($this->invites) )
        {
            throw new E_OTS_NoDriver();
        }

        // driven action
        return $this->invites->submitRequest($player);
    }

/**
 * Returns list of players that requested membership.
 * 
 * @return array List of players.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws E_OTS_NoDriver If there is no requests driver assigned.
 */
    public function listRequests()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if( !isset($this->requests) )
        {
            throw new E_OTS_NoDriver();
        }

        // driven action
        return $this->requests->listRequests();
    }

/**
 * Requests membership in guild for player player.
 * 
 * @param OTS_Player Player that requested membership.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws E_OTS_NoDriver If there is no requests driver assigned.
 */
    public function request(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if( !isset($this->requests) )
        {
            throw new E_OTS_NoDriver();
        }

        // driven action
        return $this->requests->addRequest($player);
    }

/**
 * Deletes request from player.
 * 
 * @param OTS_Player Player to be rejected.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws E_OTS_NoDriver If there is no requests driver assigned.
 */
    public function deleteRequest(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if( !isset($this->requests) )
        {
            throw new E_OTS_NoDriver();
        }

        // driven action
        return $this->requests->deleteRequest($player);
    }

/**
 * Accepts player.
 * 
 * @param OTS_Player Player to be accepted.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws E_OTS_NoDriver If there is no requests driver assigned.
 */
    public function acceptRequest(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if( !isset($this->requests) )
        {
            throw new E_OTS_NoDriver();
        }

        // driven action
        return $this->requests->submitRequest($player);
    }

/**
 * Deletes guild.
 * 
 * @version 0.0.4+SVN
 * @since 0.0.4+SVN
 * @throws E_OTS_NotLoaded If guild is not loaded.
 */
    public function delete()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // deletes row from database
        $this->db->SQLquery('DELETE FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);

        // resets object handle
        unset($this->data['id']);
    }
}

/**#@-*/

?>
