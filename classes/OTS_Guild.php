<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.4
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ guild abstraction.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.4
 * @property string $read Guild name.
 * @property OTS_Player $owner Guild founder.
 * @property int $creationDate Guild creation data (mostly timestamp).
 * @property-read int $id Guild ID.
 * @property-read bool $loaded Loaded state.
 * @property-read OTS_GuildRanks_List $guildRanksList Ranks in this guild.
 * @property-read array $invites List of invited players.
 * @property-read array $requests List of players that requested invites.
 * @property-write IOTS_GuildAction $invitesDriver Invitations handler.
 * @property-write IOTS_GuildAction $requestsDriver Membership requests handler.
 * @tutorial POT/Guilds.pkg
 */
class OTS_Guild extends OTS_Row_DAO implements IteratorAggregate, Countable
{
/**
 * Guild data.
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @var array
 */
    private $data = array();

/**
 * Invites handler.
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @var IOTS_GuildAction
 */
    private $invites;

/**
 * Membership requests handler.
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @var IOTS_GuildAction
 */
    private $requests;

/**
 * Magic PHP5 method.
 * 
 * Allows object serialisation.
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @return array List of properties that should be saved.
 */
    public function __sleep()
    {
        return array('data', 'invites', 'requests');
    }

/**
 * Creates clone of object.
 * 
 * <p>
 * Copy of object needs to have different ID. Also invites and requests drivers are copied and assigned to object's copy.
 * </p>
 * 
 * @version 0.1.3
 * @since 0.0.4
 */
    public function __clone()
    {
        unset($this->data['id']);

        if( isset($this->invites) )
        {
            $this->invites = clone $this->invites;
            $this->invites->__construct($this);
        }

        if( isset($this->requests) )
        {
            $this->requests = clone $this->requests;
            $this->requests->__construct($this);
        }
    }

/**
 * Assigns invites handler.
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @param IOTS_GuildAction $invites Invites driver (don't pass it to clear driver).
 */
    public function setInvitesDriver(IOTS_GuildAction $invites = null)
    {
        $this->invites = $invites;
    }

/**
 * Assigns requests handler.
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * @since 0.0.4
 * @param int $id Guild's ID.
 * @throws PDOException On PDO operation error.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('owner_id') . ', ' . $this->db->fieldName('creationdate') . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Loads guild by it's name.
 * 
 * @version 0.2.0+SVN
 * @since 0.0.4
 * @param string $name Guild's name.
 * @throws PDOException On PDO operation error.
 */
    public function find($name)
    {
        // finds player's ID
        $id = $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('name') . ' = ' . $this->db->quote($name) )->fetchColumn();

        // if anything was found
        if($id['id'] !== false)
        {
            $this->load($id);
        }
    }

/**
 * Checks if object is loaded.
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @return bool Load state.
 */
    public function isLoaded()
    {
        return isset($this->data['id']);
    }

/**
 * Saves guild in database.
 * 
 * <p>
 * If guild is not loaded to represent any existing group it will create new row for it.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.4
 * @throws PDOException On PDO operation error.
 */
    public function save()
    {
        // updates existing guild
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->query('UPDATE ' . $this->db->tableName('guilds') . ' SET ' . $this->db->fieldName('name') . ' = ' . $this->db->quote($this->data['name']) . ', ' . $this->db->fieldName('owner_id') . ' = ' . $this->data['owner_id'] . ', ' . $this->db->fieldName('creationdate') . ' = ' . $this->data['creationdate'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new guild
        else
        {
            // INSERT query on database
            $this->db->query('INSERT INTO ' . $this->db->tableName('guilds') . ' (' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('owner_id') . ', ' . $this->db->fieldName('creationdate') . ') VALUES (' . $this->db->quote($this->data['name']) . ', ' . $this->data['owner_id'] . ', ' . $this->data['creationdate'] . ')');
            // ID of new group
            $this->data['id'] = $this->db->lastInsertId();
        }
    }

/**
 * Guild ID.
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * @version 0.0.4
 * @since 0.0.4
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
 * Sets guild's name.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Guild::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * @since 0.0.4
 * @return OTS_Player Owning player.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getOwner()
    {
        if( !isset($this->data['owner_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $owner = new OTS_Player();
        $owner->load($this->data['owner_id']);
        return $owner;
    }

/**
 * Assigns guild to owner.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Guild::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @param OTS_Player $owner Owning player.
 * @throws E_OTS_NotLoaded If given <var>$owner</var> object is not loaded.
 */
    public function setOwner(OTS_Player $owner)
    {
        $this->data['owner_id'] = $owner->getId();
    }

/**
 * Guild creation data.
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @return int Guild creation data.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 */
    public function getCreationDate()
    {
        if( !isset($this->data['creationdate']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['creationdate'];
    }

/**
 * Sets guild creation data.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Guild::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @param int $creationdate Guild creation data.
 */
    public function setCreationDate($creationdate)
    {
        $this->data['creationdate'] = (int) $creationdate;
    }

/**
 * Reads custom field.
 * 
 * <p>
 * Reads field by it's name. Can read any field of given record that exists in database.
 * </p>
 * 
 * <p>
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.4
 * @param string $field Field name.
 * @return string Field value.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->db->query('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('guilds') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetchColumn();
    }

/**
 * Writes custom field.
 * 
 * <p>
 * Write field by it's name. Can write any field of given record that exists in database.
 * </p>
 * 
 * <p>
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * </p>
 * 
 * <p>
 * Note: Make sure that you pass $value argument of correct type. This method determinates whether to quote field value. It is safe - it makes you sure that no unproper queries that could lead to SQL injection will be executed, but it can make your code working wrong way. For example: $object->setCustomField('foo', '1'); will quote 1 as as string ('1') instead of passing it as a integer.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.4
 * @param string $field Field name.
 * @param mixed $value Field value.
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws PDOException On PDO operation error.
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
 * List of ranks in guild.
 * 
 * <p>
 * In difference to {@link OTS_Guild::getGuildRanks() getGuildRanks() method} this method returns filtered {@link OTS_GuildRanks_List OTS_GuildRanks_List} object instead of array of {@link OTS_GuildRank OTS_GuildRank} objects. It is more effective since OTS_GuildRanks_List doesn't perform all rows loading at once.
 * </p>
 * 
 * <p>
 * Note: Returned object is only prepared, but not initialised. When using as parameter in foreach loop it doesn't matter since it will return it's iterator, but if you will wan't to execute direct operation on that object you will need to call {@link OTS_Base_List::rewind() rewind() method} first.
 * </p>
 * 
 * @version 0.1.4
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
 * <p>
 * OTServ and it's database doesn't provide such feature like guild invitations. In order to use this mechanism you have to write own {@link IOTS_GuildAction invitations drivers} and assign it using {@link OTS_Guild::setInvitesDriver() setInvitesDriver() method}.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * <p>
 * OTServ and it's database doesn't provide such feature like guild invitations. In order to use this mechanism you have to write own {@link IOTS_GuildAction invitations drivers} and assign it using {@link OTS_Guild::setInvitesDriver() setInvitesDriver() method}.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * <p>
 * OTServ and it's database doesn't provide such feature like guild invitations. In order to use this mechanism you have to write own {@link IOTS_GuildAction invitations drivers} and assign it using {@link OTS_Guild::setInvitesDriver() setInvitesDriver() method}.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * <p>
 * OTServ and it's database doesn't provide such feature like guild invitations. In order to use this mechanism you have to write own {@link IOTS_GuildAction invitations drivers} and assign it using {@link OTS_Guild::setInvitesDriver() setInvitesDriver() method}.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * <p>
 * OTServ and it's database doesn't provide such feature like membership requests. In order to use this mechanism you have to write own {@link IOTS_GuildAction requests drivers} and assign it using {@link OTS_Guild::setInvitesDriver() setRequestsDriver() method}.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * <p>
 * OTServ and it's database doesn't provide such feature like membership requests. In order to use this mechanism you have to write own {@link IOTS_GuildAction requests drivers} and assign it using {@link OTS_Guild::setInvitesDriver() setRequestsDriver() method}.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * <p>
 * OTServ and it's database doesn't provide such feature like membership requests. In order to use this mechanism you have to write own {@link IOTS_GuildAction requests drivers} and assign it using {@link OTS_Guild::setInvitesDriver() setRequestsDriver() method}.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * <p>
 * OTServ and it's database doesn't provide such feature like membership requests. In order to use this mechanism you have to write own {@link IOTS_GuildAction requests drivers} and assign it using {@link OTS_Guild::setInvitesDriver() setRequestsDriver() method}.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
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
 * @throws PDOException On PDO operation error.
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
 * <p>
 * There is no need to implement entire Iterator interface since we have {@link OTS_GuildRanks_List ranks list class} for it.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @throws E_OTS_NotLoaded If guild is not loaded.
 * @throws PDOException On PDO operation error.
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
 * @throws PDOException On PDO operation error.
 * @return int Count of ranks.
 */
    public function count()
    {
        return $this->getGuildRanksList()->count();
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.3
 * @since 0.1.0
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'loaded':
                return $this->isLoaded();

            case 'id':
                return $this->getId();

            case 'name':
                return $this->getName();

            case 'owner':
                return $this->getOwner();

            case 'creationDate':
                return $this->getCreationDate();

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
 * @throws E_OTS_NotLoaded If passed parameter for owner field won't be loaded.
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

            case 'creationDate':
                $this->setCreationDate($value);
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
 * <p>
 * If any display driver is currently loaded then it uses it's method. Else it returns guild name.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return string String representation of object.
 */
    public function __toString()
    {
        // checks if display driver is loaded
        if( POT::isDisplayDriverLoaded() )
        {
            return POT::getDisplayDriver()->displayGuild($this);
        }

        return $this->getName();
    }
}

?>