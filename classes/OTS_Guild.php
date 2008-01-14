<?php

/**#@+
 * @version 0.0.4
 * @since 0.0.4
 */

/**
 * @package POT
 * @version 0.1.0
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ guild abstraction.
 * 
 * @package POT
 * @version 0.1.0
 * @property string $read Guild name.
 * @property OTS_Player $owner Guild founder.
 * @property int $creationData Guild creation data (mostly timestamp).
 * @property-read int $id Guild ID.
 * @property-read OTS_GuildRanks_List $guildRanksList Ranks in this guild.
 * @property-read array $invites List of invited players.
 * @property-read array $requests List of players that requested invites.
 * @property-write IOTS_GuildAction $invitesDriver Invitations handler.
 * @property-write IOTS_GuildAction $requestsDriver Membership requests handler.
 */
class OTS_Guild extends OTS_Base_DAO implements IteratorAggregate, Countable
{
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
 * @version 0.0.5
 * @param int $id Guild's ID.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('ownerid') . ', ' . $this->db->fieldName('creationdata') . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Loads guild by it's name.
 * 
 * @version 0.0.5
 * @param string $name Guild's name.
 */
    public function find($name)
    {
        // finds player's ID
        $id = $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('name') . ' = ' . $this->db->quote($name) )->fetch();

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
 * 
 * @version 0.0.5
 */
    public function save()
    {
        // updates existing guild
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->query('UPDATE ' . $this->db->tableName('guilds') . ' SET ' . $this->db->fieldName('name') . ' = ' . $this->db->quote($this->data['name']) . ', ' . $this->db->fieldName('ownerid') . ' = ' . $this->data['ownerid'] . ', ' . $this->db->fieldName('creationdata') . ' = ' . $this->data['creationdata'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new guild
        else
        {
            // INSERT query on database
            $this->db->query('INSERT INTO ' . $this->db->tableName('guilds') . ' (' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('ownerid') . ', ' . $this->db->fieldName('creationdata') . ') VALUES (' . $this->db->quote($this->data['name']) . ', ' . $this->data['ownerid'] . ', ' . $this->data['creationdata'] . ')');
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
 * @version 0.1.0
 * @return OTS_Player Owning player.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 */
    public function getOwner()
    {
        if( !isset($this->data['ownerid']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $owner = new OTS_Player();
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
 * @version 0.0.8
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

        $value = $this->db->query('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetch();
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
 * @version 0.0.5
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
            $value = $this->db->quote($value);
        }

        $this->db->query('UPDATE ' . $this->db->tableName('guilds') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * Reads all ranks that are in this guild.
 * 
 * @version 0.1.0
 * @return array List of ranks.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @deprecated 0.0.5 Use getGuildRanksList().
 */
    public function getGuildRanks()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $guildRanks = array();

        foreach( $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('guild_ranks') . ' WHERE ' . $this->db->fieldName('guild_id') . ' = ' . $this->data['id'])->fetchAll() as $guildRank)
        {
            // creates new object
            $object = new OTS_GuildRank();
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
 * @version 0.1.0
 * @since 0.0.5
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
        $filter = new OTS_SQLFilter();
        $filter->compareField('guild_id', (int) $this->data['id']);

        // creates list object
        $list = new OTS_GuildRanks_List();
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
 * @version 0.0.5
 * @since 0.0.5
 * @throws E_OTS_NotLoaded If guild is not loaded.
 */
    public function delete()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // deletes row from database
        $this->db->query('DELETE FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);

        // resets object handle
        unset($this->data['id']);
    }

/**
 * Returns ranks iterator.
 * 
 * There is no need to implement entire Iterator interface since we have {@link OTS_GuildRanks_List ranks list class} for it.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @return Iterator List of ranks.
 */
    public function getIterator()
    {
        return $this->getGuildRanksList();
    }

/**
 * Returns number of ranks within.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @return int Count of ranks.
 */
    public function count()
    {
        return $this->getGuildRanksList()->count();
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
            case 'id':
                return $this->getId();

            case 'name':
                return $this->getName();

            case 'owner':
                return $this->getOwner();

            case 'creationData':
                return $this->getCreationData();

            case 'guildRanksList':
                return $this->getGuildRanksList();

            case 'invites':
                return $this->listInvites();

            case 'requests':
                return $this->listRequests();

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
            case 'name':
                $this->setName($value);
                break;

            case 'owner':
                $this->setOwner($value);
                break;

            case 'creationData':
                $this->setCreationData($value);
                break;

            case 'invitesDriver':
                $this->setInvitesDriver($value);
                break;

            case 'requestsDriver':
                $this->setRequestsDriver($value);
                break;

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Returns string representation of object.
 * 
 * If any display driver is currently loaded then it uses it's method. Else it returns guild name.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return string String representation of object.
 */
    public function __toString()
    {
        $ots = POT::getInstance();

        // checks if display driver is loaded
        if( $ots->isDisplayDriverLoaded() )
        {
            return $ots->getDisplayDriver()->displayGuild($this);
        }
        else
        {
            return $this->getName();
        }
    }
}

/**#@-*/

?>
