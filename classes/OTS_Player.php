﻿<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ character abstraction.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @property string $name Character name.
 * @property OTS_Account $account Account to which character belongs.
 * @property OTS_Group $group Group of which character is member.
 * @property int $sex Gender.
 * @property int $vocation Vocation.
 * @property int $experience Experience points.
 * @property int $level Experience level.
 * @property int $magLevel Magic level.
 * @property int $health Hit points.
 * @property int $healthMax Maximum hit points.
 * @property int $mana Mana.
 * @property int $manaMax Maximum mana.
 * @property int $manaSpent Spent mana.
 * @property int $soul Soul points.
 * @property int $direction Looking direction.
 * @property int $lookBody Body color.
 * @property int $lookFeet Feet color.
 * @property int $lookHead Hairs color.
 * @property int $lookLegs Legs color.
 * @property int $lookType Outfit type.
 * @property int $lookAddons Addons.
 * @property int $posX Spawn X coord.
 * @property int $posY Spawn Y coord.
 * @property int $posZ Spawn Z coord.
 * @property int $cap Capacity.
 * @property int $lastLogin Last login timestamp.
 * @property int $lastLogout Last logout timestamp.
 * @property int $lastIP Last login IP number.
 * @property string $conditions Binary conditions.
 * @property int $skullTime Timestamp for which skull will last.
 * @property OTS_GuildRank $rank Guild rank.
 * @property int $townId Residence town.
 * @property int $lossExperience Experience loosing rate.
 * @property int $lossMana Mana loosing rate.
 * @property int $lossSkills Skills loosing rate.
 * @property int $lossItems Items loosing rate.
 * @property int $lossContainers Containers loosing rate.
 * @property int $balance Bank balance.
 * @property int $stamina Stamina miliseconds.
 * @property bool $save Player save flag.
 * @property bool $skull Player skull flag.
 * @property bool $online Player online state.
 * @property-read int $id Player ID.
 * @property-read bool $loaded Loaded state.
 * @property-read string $townName Name of town in which player residents.
 * @property-read OTS_House $house House which player rents.
 * @property-read OTS_Players_List $vipsList List of VIPs of player.
 * @property-read string $vocationName String vocation representation.
 * @property-read array $spellsList List of known spells.
 * @property-read OTS_Deaths_List $deathsList List of player deaths.
 * @property-read OTS_Kills_List $killsList List of player frags.
 * @tutorial POT/Players.pkg
 */
class OTS_Player extends OTS_Row_DAO implements IteratorAggregate, Countable
{
/**
 * Player data.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @var array
 */
    protected $data = array('sex' => POT::SEX_FEMALE, 'vocation' => 0, 'experience' => 0, 'level' => 1, 'maglevel' => 0, 'health' => 100, 'healthmax' => 100, 'mana' => 100, 'manamax' => 100, 'manaspent' => 0, 'soul' => 0, 'direction' => POT::DIRECTION_NORTH, 'lookbody' => 10, 'lookfeet' => 10, 'lookhead' => 10, 'looklegs' => 10, 'looktype' => 136, 'lookaddons' => 0, 'posx' => 0, 'posy' => 0, 'posz' => 0, 'cap' => 0, 'lastlogin' => 0, 'lastlogout' => 0, 'lastip' => 0, 'save' => true, 'skull_type' => 0, 'skull_time' => 0, 'loss_experience' => 100, 'loss_mana' => 100, 'loss_skills' => 100, 'loss_items' => 10, 'loss_containers' => 100, 'town_id' => 1, 'balance' => 0, 'stamina' => 151200000, 'online' => 0);

/**
 * Player skills.
 * 
 * @version 0.0.2
 * @since 0.0.2
 * @var array
 */
    private $skills = array();

/**
 * Magic PHP5 method.
 * 
 * Allows object serialisation.
 * 
 * @return array List of properties that should be saved.
 * @version 0.0.4
 * @since 0.0.4
 */
    public function __sleep()
    {
        return array('data', 'skills');
    }

/**
 * Loads player with given id.
 * 
 * @version 0.1.6
 * @param int $id Player's ID.
 * @throws PDOException On PDO operation error.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('account_id') . ', ' . $this->db->fieldName('group_id') . ', ' . $this->db->fieldName('sex') . ', ' . $this->db->fieldName('vocation') . ', ' . $this->db->fieldName('experience') . ', ' . $this->db->fieldName('level') . ', ' . $this->db->fieldName('maglevel') . ', ' . $this->db->fieldName('health') . ', ' . $this->db->fieldName('healthmax') . ', ' . $this->db->fieldName('mana') . ', ' . $this->db->fieldName('manamax') . ', ' . $this->db->fieldName('manaspent') . ', ' . $this->db->fieldName('soul') . ', ' . $this->db->fieldName('direction') . ', ' . $this->db->fieldName('lookbody') . ', ' . $this->db->fieldName('lookfeet') . ', ' . $this->db->fieldName('lookhead') . ', ' . $this->db->fieldName('looklegs') . ', ' . $this->db->fieldName('looktype') . ', ' . $this->db->fieldName('lookaddons') . ', ' . $this->db->fieldName('posx') . ', ' . $this->db->fieldName('posy') . ', ' . $this->db->fieldName('posz') . ', ' . $this->db->fieldName('cap') . ', ' . $this->db->fieldName('lastlogin') . ', ' . $this->db->fieldName('lastlogout') . ', ' . $this->db->fieldName('lastip') . ', ' . $this->db->fieldName('save') . ', ' . $this->db->fieldName('conditions') . ', ' . $this->db->fieldName('skull_type') . ', ' . $this->db->fieldName('skull_time') . ', ' . $this->db->fieldName('loss_experience') . ', ' . $this->db->fieldName('loss_mana') . ', ' . $this->db->fieldName('loss_skills') . ', ' . $this->db->fieldName('loss_items') . ', ' . $this->db->fieldName('loss_containers') . ', ' . $this->db->fieldName('town_id') . ', ' . $this->db->fieldName('balance') . ', ' . $this->db->fieldName('stamina') . ', ' . $this->db->fieldName('online') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();

        // loads skills
        if( $this->isLoaded() )
        {
            foreach( $this->db->query('SELECT ' . $this->db->fieldName('skillid') . ', ' . $this->db->fieldName('value') . ', ' . $this->db->fieldName('count') . ' FROM ' . $this->db->tableName('player_skills') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'])->fetchAll() as $skill)
            {
                $this->skills[ $skill['skillid'] ] = array('value' => $skill['value'], 'tries' => $skill['count']);
            }
        }
    }

/**
 * Loads player by it's name.
 * 
 * @version 0.2.0+SVN
 * @since 0.0.2
 * @param string $name Player's name.
 * @throws PDOException On PDO operation error.
 */
    public function find($name)
    {
        // finds player's ID
        $id = $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('name') . ' = ' . $this->db->quote($name) )->fetchColumn();

        // if anything was found
        if($id !== false)
        {
            $this->load($id);
        }
    }

/**
 * Checks if object is loaded.
 * 
 * @version 0.0.1
 * @return bool Load state.
 */
    public function isLoaded()
    {
        return isset($this->data['id']);
    }

/**
 * Saves player in database.
 * 
 * <p>
 * If player is not loaded to represent any existing row it will create new row for it.
 * </p>
 * 
 * @version 0.1.6
 * @throws PDOException On PDO operation error.
 */
    public function save()
    {
        // updates existing player
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->query('UPDATE ' . $this->db->tableName('players') . ' SET ' . $this->db->fieldName('name') . ' = ' . $this->db->quote($this->data['name']) . ', ' . $this->db->fieldName('account_id') . ' = ' . $this->data['account_id'] . ', ' . $this->db->fieldName('group_id') . ' = ' . $this->data['group_id'] . ', ' . $this->db->fieldName('sex') . ' = ' . $this->data['sex'] . ', ' . $this->db->fieldName('vocation') . ' = ' . $this->data['vocation'] . ', ' . $this->db->fieldName('experience') . ' = ' . $this->data['experience'] . ', ' . $this->db->fieldName('level') . ' = ' . $this->data['level'] . ', ' . $this->db->fieldName('maglevel') . ' = ' . $this->data['maglevel'] . ', ' . $this->db->fieldName('health') . ' = ' . $this->data['health'] . ', ' . $this->db->fieldName('healthmax') . ' = ' . $this->data['healthmax'] . ', ' . $this->db->fieldName('mana') . ' = ' . $this->data['mana'] . ', ' . $this->db->fieldName('manamax') . ' = ' . $this->data['manamax'] . ', ' . $this->db->fieldName('manaspent') . ' = ' . $this->data['manaspent'] . ', ' . $this->db->fieldName('soul') . ' = ' . $this->data['soul'] . ', ' . $this->db->fieldName('direction') . ' = ' . $this->data['direction'] . ', ' . $this->db->fieldName('lookbody') . ' = ' . $this->data['lookbody'] . ', ' . $this->db->fieldName('lookfeet') . ' = ' . $this->data['lookfeet'] . ', ' . $this->db->fieldName('lookhead') . ' = ' . $this->data['lookhead'] . ', ' . $this->db->fieldName('looklegs') . ' = ' . $this->data['looklegs'] . ', ' . $this->db->fieldName('looktype') . ' = ' . $this->data['looktype'] . ', ' . $this->db->fieldName('lookaddons') . ' = ' . $this->data['lookaddons'] . ', ' . $this->db->fieldName('posx') . ' = ' . $this->data['posx'] . ', ' . $this->db->fieldName('posy') . ' = ' . $this->data['posy'] . ', ' . $this->db->fieldName('posz') . ' = ' . $this->data['posz'] . ', ' . $this->db->fieldName('cap') . ' = ' . $this->data['cap'] . ', ' . $this->db->fieldName('lastlogin') . ' = ' . $this->data['lastlogin'] . ', ' . $this->db->fieldName('lastlogout') . ' = ' . $this->data['lastlogout'] . ', ' . $this->db->fieldName('lastip') . ' = ' . $this->data['lastip'] . ', ' . $this->db->fieldName('save') . ' = ' . (int) $this->data['save'] . ', ' . $this->db->fieldName('conditions') . ' = ' . $this->db->quote($this->data['conditions']) . ', ' . $this->db->fieldName('skull_type') . ' = ' . $this->data['skull_type'] . ', ' . $this->db->fieldName('skull_time') . ' = ' . (int) $this->data['skull_time'] . ', ' . $this->db->fieldName('loss_experience') . ' = ' . $this->data['loss_experience'] . ', ' . $this->db->fieldName('loss_mana') . ' = ' . $this->data['loss_mana'] . ', ' . $this->db->fieldName('loss_skills') . ' = ' . $this->data['loss_skills'] . ', ' . $this->db->fieldName('loss_items') . ' = ' . $this->data['loss_items'] . ', ' . $this->db->fieldName('loss_containers') . ' = ' . $this->data['loss_containers'] . ', ' . $this->db->fieldName('town_id') . ' = ' . $this->data['town_id'] . ', ' . $this->db->fieldName('balance') . ' = ' . $this->data['balance'] . ', ' . $this->db->fieldName('stamina') . ' = ' . $this->data['stamina'] . ', ' . $this->db->fieldName('online') . ' = ' . (int) $this->data['online'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new player
        else
        {
            // INSERT query on database
            $this->db->query('INSERT INTO ' . $this->db->tableName('players') . ' (' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('account_id') . ', ' . $this->db->fieldName('group_id') . ', ' . $this->db->fieldName('sex') . ', ' . $this->db->fieldName('vocation') . ', ' . $this->db->fieldName('experience') . ', ' . $this->db->fieldName('level') . ', ' . $this->db->fieldName('maglevel') . ', ' . $this->db->fieldName('health') . ', ' . $this->db->fieldName('healthmax') . ', ' . $this->db->fieldName('mana') . ', ' . $this->db->fieldName('manamax') . ', ' . $this->db->fieldName('manaspent') . ', ' . $this->db->fieldName('soul') . ', ' . $this->db->fieldName('direction') . ', ' . $this->db->fieldName('lookbody') . ', ' . $this->db->fieldName('lookfeet') . ', ' . $this->db->fieldName('lookhead') . ', ' . $this->db->fieldName('looklegs') . ', ' . $this->db->fieldName('looktype') . ', ' . $this->db->fieldName('lookaddons') . ', ' . $this->db->fieldName('posx') . ', ' . $this->db->fieldName('posy') . ', ' . $this->db->fieldName('posz') . ', ' . $this->db->fieldName('cap') . ', ' . $this->db->fieldName('lastlogin') . ', ' . $this->db->fieldName('lastlogout') . ', ' . $this->db->fieldName('lastip') . ', ' . $this->db->fieldName('save') . ', ' . $this->db->fieldName('conditions') . ', ' . $this->db->fieldName('skull_type') . ', ' . $this->db->fieldName('skull_time') . ', ' . $this->db->fieldName('loss_experience') . ', ' . $this->db->fieldName('loss_mana') . ', ' . $this->db->fieldName('loss_skills') . ', ' . $this->db->fieldName('loss_items') . ', ' . $this->db->fieldName('loss_containers') . ', ' . $this->db->fieldName('town_id') . ', ' . $this->db->fieldName('balance') . ', ' . $this->db->fieldName('stamina') . ', ' . $this->db->fieldName('online') . ') VALUES (' . $this->db->quote($this->data['name']) . ', ' . $this->data['account_id'] . ', ' . $this->data['group_id'] . ', ' . $this->data['sex'] . ', ' . $this->data['vocation'] . ', ' . $this->data['experience'] . ', ' . $this->data['level'] . ', ' . $this->data['maglevel'] . ', ' . $this->data['health'] . ', ' . $this->data['healthmax'] . ', ' . $this->data['mana'] . ', ' . $this->data['manamax'] . ', ' . $this->data['manaspent'] . ', ' . $this->data['soul'] . ', ' . $this->data['direction'] . ', ' . $this->data['lookbody'] . ', ' . $this->data['lookfeet'] . ', ' . $this->data['lookhead'] . ', ' . $this->data['looklegs'] . ', ' . $this->data['looktype'] . ', ' . $this->data['lookaddons'] . ', ' . $this->data['posx'] . ', ' . $this->data['posy'] . ', ' . $this->data['posz'] . ', ' . $this->data['cap'] . ', ' . $this->data['lastlogin'] . ', ' . $this->data['lastlogout'] . ', ' . $this->data['lastip'] . ', ' . (int) $this->data['save'] . ', ' . $this->db->quote($this->data['conditions']) . ', ' . $this->data['skull_type'] . ', ' . (int) $this->data['skull_time'] . ', ' . $this->data['loss_experience'] . ', ' . $this->data['loss_mana'] . ', ' . $this->data['loss_skills'] . ', ' . $this->data['loss_items'] . ', ' . $this->data['loss_containers'] . ', ' . $this->data['town_id'] . ', ' . $this->data['balance'] . ', ' . $this->data['stamina'] . ', ' . (int) $this->data['online'] . ')');
            // ID of new group
            $this->data['id'] = $this->db->lastInsertId();
        }

        // updates skills - doesn't matter if we have just created character - trigger inserts new skills
        foreach($this->skills as $id => $skill)
        {
            $this->db->query('UPDATE ' . $this->db->tableName('player_skills') . ' SET ' . $this->db->fieldName('value') . ' = ' . $skill['value'] . ', ' . $this->db->fieldName('count') . ' = ' . $skill['tries'] . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('skillid') . ' = ' . $id);
        }
    }

/**
 * Player ID.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Player ID.
 * @throws E_OTS_NotLoaded If player is not loaded.
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
 * Player name.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return string Player's name.
 * @throws E_OTS_NotLoaded If player is not loaded.
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
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param string $name Name.
 */
    public function setName($name)
    {
        $this->data['name'] = (string) $name;
    }

/**
 * Returns account of this player.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.1.0
 * @return OTS_Account Owning account.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getAccount()
    {
        if( !isset($this->data['account_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $account = new OTS_Account();
        $account->load($this->data['account_id']);
        return $account;
    }

/**
 * Assigns character to account.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param OTS_Account $account Owning account.
 * @throws E_OTS_NotLoaded If passed <var>$account</var> parameter is not loaded.
 */
    public function setAccount(OTS_Account $account)
    {
        $this->data['account_id'] = $account->getId();
    }

/**
 * Returns group of this player.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.1.0
 * @return OTS_Group Group of which current character is member.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getGroup()
    {
        if( !isset($this->data['group_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $group = new OTS_Group();
        $group->load($this->data['group_id']);
        return $group;
    }

/**
 * Assigns character to group.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param OTS_Group $group Group to be a member.
 * @throws E_OTS_NotLoaded If passed <var>$group</var> parameter is not loaded.
 */
    public function setGroup(OTS_Group $group)
    {
        $this->data['group_id'] = $group->getId();
    }

/**
 * Player gender.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Player gender.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getSex()
    {
        if( !isset($this->data['sex']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['sex'];
    }

/**
 * Sets player gender.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $sex Player gender.
 */
    public function setSex($sex)
    {
        $this->data['sex'] = (int) $sex;
    }

/**
 * Player proffesion.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Player proffesion.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getVocation()
    {
        if( !isset($this->data['vocation']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['vocation'];
    }

/**
 * Sets player proffesion.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $vocation Player proffesion.
 */
    public function setVocation($vocation)
    {
        $this->data['vocation'] = (int) $vocation;
    }

/**
 * Experience points.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Experience points.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getExperience()
    {
        if( !isset($this->data['experience']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['experience'];
    }

/**
 * Sets experience points.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $experience Experience points.
 */
    public function setExperience($experience)
    {
        $this->data['experience'] = (int) $experience;
    }

/**
 * Experience level.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Experience level.
 * @throws E_OTS_NotLoaded If player is not loaded.
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
 * Sets experience level.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $level Experience level.
 */
    public function setLevel($level)
    {
        $this->data['level'] = (int) $level;
    }

/**
 * Magic level.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Magic level.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getMagLevel()
    {
        if( !isset($this->data['maglevel']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['maglevel'];
    }

/**
 * Sets magic level.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $maglevel Magic level.
 */
    public function setMagLevel($maglevel)
    {
        $this->data['maglevel'] = (int) $maglevel;
    }

/**
 * Current HP.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Current HP.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getHealth()
    {
        if( !isset($this->data['health']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['health'];
    }

/**
 * Sets current HP.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $health Current HP.
 */
    public function setHealth($health)
    {
        $this->data['health'] = (int) $health;
    }

/**
 * Maximum HP.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Maximum HP.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getHealthMax()
    {
        if( !isset($this->data['healthmax']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['healthmax'];
    }

/**
 * Sets maximum HP.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $healthmax Maximum HP.
 */
    public function setHealthMax($healthmax)
    {
        $this->data['healthmax'] = (int) $healthmax;
    }

/**
 * Current mana.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Current mana.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getMana()
    {
        if( !isset($this->data['mana']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['mana'];
    }

/**
 * Sets current mana.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $mana Current mana.
 */
    public function setMana($mana)
    {
        $this->data['mana'] = (int) $mana;
    }

/**
 * Maximum mana.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Maximum mana.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getManaMax()
    {
        if( !isset($this->data['manamax']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['manamax'];
    }

/**
 * Sets maximum mana.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $manamax Maximum mana.
 */
    public function setManaMax($manamax)
    {
        $this->data['manamax'] = (int) $manamax;
    }

/**
 * Mana spent.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Mana spent.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getManaSpent()
    {
        if( !isset($this->data['manaspent']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['manaspent'];
    }

/**
 * Sets mana spent.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $manaspent Mana spent.
 */
    public function setManaSpent($manaspent)
    {
        $this->data['manaspent'] = (int) $manaspent;
    }

/**
 * Soul points.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Soul points.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getSoul()
    {
        if( !isset($this->data['soul']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['soul'];
    }

/**
 * Sets soul points.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $soul Soul points.
 */
    public function setSoul($soul)
    {
        $this->data['soul'] = (int) $soul;
    }

/**
 * Looking direction.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Looking direction.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getDirection()
    {
        if( !isset($this->data['direction']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['direction'];
    }

/**
 * Sets looking direction.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $direction Looking direction.
 */
    public function setDirection($direction)
    {
        $this->data['direction'] = (int) $direction;
    }

/**
 * Body color.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Body color.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLookBody()
    {
        if( !isset($this->data['lookbody']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['lookbody'];
    }

/**
 * Sets body color.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $lookbody Body color.
 */
    public function setLookBody($lookbody)
    {
        $this->data['lookbody'] = (int) $lookbody;
    }

/**
 * Boots color.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Boots color.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLookFeet()
    {
        if( !isset($this->data['lookfeet']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['lookfeet'];
    }

/**
 * Sets boots color.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $lookfeet Boots color.
 */
    public function setLookFeet($lookfeet)
    {
        $this->data['lookfeet'] = (int) $lookfeet;
    }

/**
 * Hair color.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Hair color.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLookHead()
    {
        if( !isset($this->data['lookhead']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['lookhead'];
    }

/**
 * Sets hair color.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $lookhead Hair color.
 */
    public function setLookHead($lookhead)
    {
        $this->data['lookhead'] = (int) $lookhead;
    }

/**
 * Legs color.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Legs color.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLookLegs()
    {
        if( !isset($this->data['looklegs']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['looklegs'];
    }

/**
 * Sets legs color.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $looklegs Legs color.
 */
    public function setLookLegs($looklegs)
    {
        $this->data['looklegs'] = (int) $looklegs;
    }

/**
 * Outfit.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Outfit.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLookType()
    {
        if( !isset($this->data['looktype']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['looktype'];
    }

/**
 * Sets outfit.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $looktype Outfit.
 */
    public function setLookType($looktype)
    {
        $this->data['looktype'] = (int) $looktype;
    }

/**
 * Addons.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Addons.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLookAddons()
    {
        if( !isset($this->data['lookaddons']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['lookaddons'];
    }

/**
 * Sets addons.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $lookaddons Addons.
 */
    public function setLookAddons($lookaddons)
    {
        $this->data['lookaddons'] = (int) $lookaddons;
    }

/**
 * X map coordinate.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int X map coordinate.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getPosX()
    {
        if( !isset($this->data['posx']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['posx'];
    }

/**
 * Sets X map coordinate.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $posx X map coordinate.
 */
    public function setPosX($posx)
    {
        $this->data['posx'] = (int) $posx;
    }

/**
 * Y map coordinate.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Y map coordinate.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getPosY()
    {
        if( !isset($this->data['posy']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['posy'];
    }

/**
 * Sets Y map coordinate.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $posy Y map coordinate.
 */
    public function setPosY($posy)
    {
        $this->data['posy'] = (int) $posy;
    }

/**
 * Z map coordinate.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Z map coordinate.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getPosZ()
    {
        if( !isset($this->data['posz']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['posz'];
    }

/**
 * Sets Z map coordinate.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $posz Z map coordinate.
 */
    public function setPosZ($posz)
    {
        $this->data['posz'] = (int) $posz;
    }

/**
 * Capacity.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Capacity.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getCap()
    {
        if( !isset($this->data['cap']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['cap'];
    }

/**
 * Sets capacity.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $cap Capacity.
 */
    public function setCap($cap)
    {
        $this->data['cap'] = (int) $cap;
    }

/**
 * Last login timestamp.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Last login timestamp.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLastLogin()
    {
        if( !isset($this->data['lastlogin']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['lastlogin'];
    }

/**
 * Sets last login timestamp.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $lastlogin Last login timestamp.
 */
    public function setLastLogin($lastlogin)
    {
        $this->data['lastlogin'] = (int) $lastlogin;
    }

/**
 * Last logout timestamp.
 * 
 * @version 0.1.6
 * @since 0.1.6
 * @return int Last logout timestamp.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLastLogout()
    {
        if( !isset($this->data['lastlogout']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['lastlogout'];
    }

/**
 * Sets last logout timestamp.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $lastlogout Last logout timestamp.
 */
    public function setLastLogout($lastlogout)
    {
        $this->data['lastlogout'] = (int) $lastlogout;
    }

/**
 * Last login IP.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Last login IP.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLastIP()
    {
        if( !isset($this->data['lastip']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['lastip'];
    }

/**
 * Sets last login IP.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $lastip Last login IP.
 */
    public function setLastIP($lastip)
    {
        $this->data['lastip'] = (int) $lastip;
    }

/**
 * Checks if save flag is set.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.7
 * @return bool Save flag state.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function isSaveSet()
    {
        if( !isset($this->data['save']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['save'];
    }

/**
 * Unsets save flag.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.7
 */
    public function unsetSave()
    {
        $this->data['save'] = false;
    }

/**
 * Sets save flag.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN version this method takes no parameters.
 * </p>
 * 
 * @version 0.2.0+SVN
 */
    public function setSave()
    {
        $this->data['save'] = true;
    }

/**
 * Checks if player is online.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return bool Online status.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function isOnline()
    {
        if( !isset($this->data['online']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['online'];
    }

/**
 * Marks player offline.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    public function setOffline()
    {
        $this->data['online'] = false;
    }

/**
 * Marks player online.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    public function setOnline()
    {
        $this->data['online'] = true;
    }

/**
 * Conditions.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return string Conditions binary string.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getConditions()
    {
        if( !isset($this->data['conditions']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['conditions'];
    }

/**
 * Sets conditions.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param string $conditions Condition binary string.
 */
    public function setConditions($conditions)
    {
        $this->data['conditions'] = $conditions;
    }

/**
 * Skull type time remained.
 * 
 * <p>
 * Note: This method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.1
 * @return int Type skulled time remained.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
	public function getSkullTime()
	{
		if( !isset($this->data['skull_type']) )
		{
			throw new E_OTS_NotLoaded();
		}
		
		return $this->data['skull_time'];
	}
 
/**
 * Sets skulled time remained.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $skulltype Type of the skull.
 * @param int $skulltime Skulled time remained.
 */
    public function setSkullTime($skulltype, $skulltime)
    {
		$this->data['skull_type'] = (int) $skulltype;
        $this->data['skull_time'] = (int) $skulltime;
    }

/**
 * Checks if player has skull.
 * 
 * <p>
 * Note: This method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.1
 * @return bool Skull state.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function hasSkull()
	{
        if( !isset($this->data['skull_type']) )
		{
            throw new E_OTS_NotLoaded();
        }

        return $this->data['skull_type'];
    }

/**
 * Unsets skull flag.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 */
    public function unsetSkull()
    {
        $this->data['skull_type'] = false;
    }

/**
 * Sets skull flag.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 */
    public function setSkull()
    {
        $this->data['skull_type'] = true;
    }

/**
 * Residence town's ID.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Residence town's ID.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getTownId()
    {
        if( !isset($this->data['town_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['town_id'];
    }

/**
 * Sets residence town's ID.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $town_id Residence town's ID.
 */
    public function setTownId($town_id)
    {
        $this->data['town_id'] = (int) $town_id;
    }

/**
 * Percentage of experience lost after dead.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Percentage of experience lost after dead.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLossExperience()
    {
        if( !isset($this->data['loss_experience']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['loss_experience'];
    }

/**
 * Sets percentage of experience lost after dead.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $loss_experience Percentage of experience lost after dead.
 */
    public function setLossExperience($loss_experience)
    {
        $this->data['loss_experience'] = (int) $loss_experience;
    }

/**
 * Percentage of used mana lost after dead.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Percentage of used mana lost after dead.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLossMana()
    {
        if( !isset($this->data['loss_mana']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['loss_mana'];
    }

/**
 * Sets percentage of used mana lost after dead.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $loss_mana Percentage of used mana lost after dead.
 */
    public function setLossMana($loss_mana)
    {
        $this->data['loss_mana'] = (int) $loss_mana;
    }

/**
 * Percentage of skills lost after dead.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Percentage of skills lost after dead.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLossSkills()
    {
        if( !isset($this->data['loss_skills']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['loss_skills'];
    }

/**
 * Sets percentage of skills lost after dead.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.0.1
 * @param int $loss_skills Percentage of skills lost after dead.
 */
    public function setLossSkills($loss_skills)
    {
        $this->data['loss_skills'] = (int) $loss_skills;
    }

/**
 * Percentage of items lost after dead.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return int Percentage of items lost after dead.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLossItems()
    {
        if( !isset($this->data['loss_items']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['loss_items'];
    }

/**
 * Sets percentage of items lost after dead.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @param int $loss_items Percentage of items lost after dead.
 */
    public function setLossItems($loss_items)
    {
        $this->data['loss_items'] = (int) $loss_items;
    }

/**
 * Percentage of containers lost after dead.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int Percentage of containers lost after dead.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getLossContainers()
    {
        if( !isset($this->data['loss_containers']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['loss_containers'];
    }

/**
 * Sets percentage of containers lost after dead.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $loss_items Percentage of containers lost after dead.
 */
    public function setLossContainers($loss_containers)
    {
        $this->data['loss_containers'] = (int) $loss_containers;
    }

/**
 * Bank balance.
 * 
 * @version 0.1.2
 * @since 0.1.2
 * @return int Amount of money stored in bank.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getBalance()
    {
        if( !isset($this->data['balance']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['balance'];
    }

/**
 * Sets bank balance value.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.1.2
 * @since 0.1.2
 * @param int $balance Amount of money to be set in bank.
 */
    public function setBalance($balance)
    {
        $this->data['balance'] = (int) $balance;
    }

/**
 * Stamina.
 * 
 * @version 0.1.6
 * @since 0.1.6
 * @return int Miliseconds of stamina left.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getStamina()
    {
        if( !isset($this->data['stamina']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['stamina'];
    }

/**
 * Sets stamina time left.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.1.6
 * @since 0.1.6
 * @param int $stamina Miliseconds of stamina.
 */
    public function setStamina($stamina)
    {
        $this->data['stamina'] = (int) $stamina;
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
 * @since 0.0.3
 * @param string $field Field name.
 * @return string Field value.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->db->query('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetchColumn();
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
 * @since 0.0.3
 * @param string $field Field name.
 * @param mixed $value Field value.
 * @throws E_OTS_NotLoaded If player is not loaded.
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

        $this->db->query('UPDATE ' . $this->db->tableName('players') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * Returns player's skill.
 * 
 * @version 0.0.2
 * @since 0.0.2
 * @param int $skill Skill ID.
 * @return int Skill value.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getSkill($skill)
    {
        if( !isset($this->skills[$skill]) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->skills[$skill]['value'];
    }

/**
 * Sets skill value.
 * 
 * @version 0.0.2
 * @since 0.0.2
 * @param int $skill Skill ID.
 * @param int $value Skill value.
 */
    public function setSkill($skill, $value)
    {
        $this->skills[ (int) $skill]['value'] = (int) $value;
    }

/**
 * Returns player's skill's tries for next level.
 * 
 * @version 0.0.2
 * @since 0.0.2
 * @param int $skill Skill ID.
 * @return int Skill tries.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getSkillTries($skill)
    {
        if( !isset($this->skills[$skill]) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->skills[$skill]['tries'];
    }

/**
 * Sets skill's tries for next level.
 * 
 * @version 0.0.2
 * @since 0.0.2
 * @param int $skill Skill ID.
 * @param int $tries Skill tries.
 */
    public function setSkillTries($skill, $tries)
    {
        $this->skills[ (int) $skill]['tries'] = (int) $tries;
    }

/**
 * Returns value of storage record.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.2
 * @param int $key Storage key.
 * @return int|null Stored value (null if not set).
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getStorage($key)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $value = $this->db->query('SELECT ' . $this->db->fieldName('value') . ' FROM ' . $this->db->tableName('player_storage') . ' WHERE ' . $this->db->fieldName('key') . ' = ' . (int) $key . ' AND ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'])->fetchColumn();

        if($value === false)
        {
            return null;
        }

        return $value;
    }

/**
 * Sets value of storage record.
 * 
 * @version 0.1.2
 * @since 0.1.2
 * @param int $key Storage key.
 * @param int $value Stored value.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function setStorage($key, $value)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $current = $this->getStorage($key);

        // checks if there is any row to be updates
        if( isset($current) )
        {
            $this->db->query('UPDATE ' . $this->db->tableName('player_storage') . ' SET ' . $this->db->fieldName('value') . ' = ' . (int) $value . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('key') . ' = ' . (int) $key);
        }
        // inserts new storage record
        else
        {
            $this->db->query('INSERT INTO ' . $this->db->tableName('player_storage') . ' (' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('key') . ', ' . $this->db->fieldName('value') . ') VALUES (' . $this->data['id'] . ', ' . (int) $key . ', ' . (int) $value . ')');
        }
    }

/**
 * Deletes item with contained items.
 * 
 * @version 0.0.5
 * @since 0.0.3
 * @param int $sid Item unique player's ID.
 * @throws PDOException On PDO operation error.
 */
    private function deleteItem($sid)
    {
        // deletes all sub-items
        foreach( $this->db->query('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $sid)->fetchAll() as $item)
        {
            $this->deleteItem($item['sid']);
        }

        // deletes item
        $this->db->query('DELETE FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('sid') . ' = ' . $sid);
    }

/**
 * Returns items tree from given slot.
 * 
 * <p>
 * You need global items list resources loaded in order to use this method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.3
 * @param int $slot Slot to get items.
 * @return OTS_Item|null Item in given slot (items tree if in given slot there is a container). If there is no item in slot then null value will be returned.
 * @throws E_OTS_NotLoaded If player is not loaded or there is no global items list resource loaded.
 * @throws E_OTS_NotAContainer If item which is not of type container contains sub items.
 * @throws PDOException On PDO operation error.
 */
    public function getSlot($slot)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // loads current item
        $item = $this->db->query('SELECT ' . $this->db->fieldName('itemtype') . ', ' . $this->db->fieldName('sid') . ', ' . $this->db->fieldName('count') . ', ' . $this->db->fieldName('attributes') . ' FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName($slot > POT::SLOT_AMMO ? 'sid' : 'pid') . ' = ' . (int) $slot)->fetch();

        if( empty($item) )
        {
            return null;
        }

        // checks if there are any items under current one
        $items = array();
        foreach( $this->db->query('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $item['sid'])->fetchAll() as $sub)
        {
            $items[] = $this->getSlot($sub['sid']);
        }

        // item type
        $slot = POT::getItemsList()->getItemType($item['itemtype'])->createItem();
        $slot->setCount($item['count']);
        $slot->setAttributes($item['attributes']);

        // checks if current item has any contained items
        if( !empty($items) )
        {
            // checks if item is realy a container
            if(!$slot instanceof OTS_Container)
            {
                throw new E_OTS_NotAContainer();
            }

            // puts items into container
            foreach($items as $sub)
            {
                $slot->addItem($sub);
            }
        }

        return $slot;
    }

/**
 * Sets slot content.
 * 
 * @version 0.1.2
 * @since 0.0.3
 * @param int $slot Slot to save items.
 * @param OTS_Item $item Item (can be a container with content) for given slot. Leave this parameter blank to clear slot.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function setSlot($slot, OTS_Item $item = null)
    {
        static $sid;

        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // clears current slot
        if($slot <= POT::SLOT_AMMO)
        {
            $id = $this->db->query('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . (int) $slot)->fetch();
            $this->deleteItem( (int) $id['sid']);
        }

        // checks if there is any item to insert
        if( isset($item) )
        {
            // current maximum sid (over slot sids)
            if( !isset($sid) )
            {
                $sid = $this->db->query('SELECT MAX(' . $this->db->fieldName('sid') . ') AS `sid` FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'])->fetch();
                $sid = $sid['sid'] > POT::SLOT_AMMO ? $sid['sid'] : POT::SLOT_AMMO;
            }

            $sid++;

            // inserts given item
            $this->db->query('INSERT INTO ' . $this->db->tableName('player_items') . ' (' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('sid') . ', ' . $this->db->fieldName('pid') . ', ' . $this->db->fieldName('itemtype') . ', ' . $this->db->fieldName('count') . ', ' . $this->db->fieldName('attributes') . ') VALUES (' . $this->data['id'] . ', ' . $sid . ', ' . (int) $slot . ', ' . $item->getId() . ', ' . $item->getCount() . ', ' . $this->db->quote( $item->getAttributes() ) . ')');

            // checks if this is container
            if($item instanceof OTS_Container)
            {
                $pid = $sid;

                // inserts all contained items
                foreach($item as $sub)
                {
                    $this->setSlot($pid, $sub);
                }
            }
        }

        // clears $sid for next public call
        if($slot <= POT::SLOT_AMMO)
        {
            $sid = null;
        }
    }

/**
 * Deletes depot item with contained items.
 * 
 * @version 0.0.5
 * @since 0.0.3
 * @param int $sid Depot item unique player's ID.
 * @throws PDOException On PDO operation error.
 */
    private function deleteDepot($sid)
    {
        // deletes all sub-items
        foreach( $this->db->query('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $sid)->fetchAll() as $item)
        {
            $this->deleteDepot($item['sid']);
        }

        // deletes item
        $this->db->query('DELETE FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('sid') . ' = ' . $sid);
    }

/**
 * Returns items tree from given depot.
 * 
 * <p>
 * You need global items list resources loaded in order to use this method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.3
 * @param int $depot Depot ID to get items.
 * @return OTS_Item|null Item in given depot (items tree if in given depot there is a container). If there is no item in depot then null value will be returned.
 * @throws E_OTS_NotLoaded If player is not loaded or there is no global items list resource loaded.
 * @throws E_OTS_NotAContainer If item which is not of type container contains sub items.
 * @throws PDOException On PDO operation error.
 */
    public function getDepot($depot)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // loads current item
        $item = $this->db->query('SELECT ' . $this->db->fieldName('itemtype') . ', ' . $this->db->fieldName('sid') . ', ' . $this->db->fieldName('count') . ', ' . $this->db->fieldName('attributes') . ' FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName($depot > POT::DEPOT_SID_FIRST ? 'sid' : 'pid') . ' = ' . (int) $depot)->fetch();

        if( empty($item) )
        {
            return null;
        }

        // checks if there are any items under current one
        $items = array();
        foreach( $this->db->query('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $item['sid'])->fetchAll() as $sub)
        {
            $items[] = $this->getDepot($sub['sid']);
        }

        // item type
        $depot = POT::getItemsList()->getItemType($item['itemtype'])->createItem();
        $depot->setCount($item['count']);
        $depot->setAttributes($item['attributes']);

        // checks if current item has any contained items
        if( !empty($items) )
        {
            // checks if item is realy a container
            if(!$depot instanceof OTS_Container)
            {
                throw new E_OTS_NotAContainer();
            }

            // puts items into container
            foreach($items as $sub)
            {
                $depot->addItem($sub);
            }
        }

        return $depot;
    }

/**
 * Sets depot content.
 * 
 * @version 0.2.0+SVN
 * @since 0.0.3
 * @param int $depot Depot ID to save items.
 * @param OTS_Item $item Item (can be a container with content) for given depot. Leave this parameter blank to clear depot.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function setDepot($depot, OTS_Item $item = null)
    {
        static $sid;

        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // clears current depot
        if($depot <= POT::DEPOT_SID_FIRST)
        {
            $id = $this->db->query('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . (int) $depot)->fetch();
            $this->deleteDepot( (int) $id['sid']);
        }

        // checks if there is any item to insert
        if( isset($item) )
        {
            // current maximum sid (over depot sids)
            if( !isset($sid) )
            {
                $sid = $this->db->query('SELECT MAX(' . $this->db->fieldName('sid') . ') AS `sid` FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'])->fetch();
                $sid = $sid['sid'] > POT::DEPOT_SID_FIRST ? $sid['sid'] : POT::DEPOT_SID_FIRST;
            }

            $sid++;

            // inserts given item
            $this->db->query('INSERT INTO ' . $this->db->tableName('player_depotitems') . ' (' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('sid') . ', ' . $this->db->fieldName('pid') . ', ' . $this->db->fieldName('itemtype') . ', ' . $this->db->fieldName('count') . ', ' . $this->db->fieldName('attributes') . ') VALUES (' . $this->data['id'] . ', ' . $sid . ', ' . (int) $depot . ', ' . $item->getId() . ', ' . $item->getCount() . ', ' . $this->db->quote( $item->getAttributes() ) . ')');

            // checks if this is container
            if($item instanceof OTS_Container)
            {
                $pid = $sid;

                // inserts all contained items
                foreach($item as $sub)
                {
                    $this->setDepot($pid, $sub);
                }
            }
        }

        // clears $sid for next public call
        if($depot <= POT::DEPOT_SID_FIRST)
        {
            $sid = null;
        }
    }

/**
 * Deletes player.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function delete()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // deletes row from database
        $this->db->query('DELETE FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);

        // resets object handle
        unset($this->data['id']);
    }

/**
 * Player proffesion name.
 * 
 * <p>
 * You need global vocations list resource loaded in order to use this method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @return string Player proffesion name.
 * @throws E_OTS_NotLoaded If player is not loaded or global vocations list is not loaded.
 */
    public function getVocationName()
    {
        if( !isset($this->data['vocation']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return POT::getVocationsList()->getVocationName($this->data['vocation']);
    }

/**
 * Player residence town name.
 * 
 * <p>
 * You need global map resource loaded in order to use this method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return string Player town name.
 * @throws E_OTS_NotLoaded If player is not loaded or global map is not loaded.
 */
    public function getTownName()
    {
        if( !isset($this->data['town_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return POT::getMap()->getTownName($this->data['town_id']);
    }

/**
 * Returns house rented by this player.
 * 
 * <p>
 * You need global houses list resource loaded in order to use this method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return OTS_House|null House rented by player.
 * @throws E_OTS_NotLoaded If player is not loaded or global houses list is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getHouse()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // SELECT query on database
        $house = $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('houses') . ' WHERE ' . $this->db->fieldName('owner') . ' = ' . $this->data['id'])->fetch();

        if( !empty($house) )
        {
            return POT::getHousesList()->getHouse($house['id']);
        }

        return null;
    }

/**
 * Returns list of VIPs.
 * 
 * <p>
 * It means list of players which this player have on his/her list.
 * </p>
 * 
 * @version 0.1.3
 * @since 0.1.3
 * @return OTS_Players_List List of VIPs.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getVIPsList()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $list = new OTS_Players_List();

        // foreign table fields identifiers
        $field1 = new OTS_SQLField('player_id', 'player_viplist');
        $field2 = new OTS_SQLField('vip_id', 'player_viplist');

        // creates filter
        $filter = new OTS_SQLFilter();
        $filter->addFilter($field1, $this->data['id']);
        $filter->compareField('id', $field2);

        // puts filter onto list
        $list->setFilter($filter);

        return $list;
    }

/**
 * Adds player to VIP list.
 * 
 * @version 0.1.4
 * @since 0.1.3
 * @param OTS_Player $player Player to be added.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function addVIP(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $this->db->query('INSERT INTO ' . $this->db->tableName('player_viplist') . ' (' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('vip_id') . ') VALUES (' . $this->data['id'] . ', ' . $player->getId() . ')');
    }

/**
 * Checks if given player is a VIP for current one.
 * 
 * @version 0.1.5
 * @since 0.1.3
 * @param OTS_Player $player Player to check.
 * @return bool True, if given player is on VIP list.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function isVIP(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->db->query('SELECT COUNT(' . $this->db->fieldName('vip_id') . ') FROM ' . $this->db->tableName('player_viplist') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('vip_id') . ' = ' . $player->getId() )->fetchColumn() > 0;
    }

/**
 * Deletes player from VIP list.
 * 
 * @version 0.1.4
 * @since 0.1.3
 * @param OTS_Player $player Player to be deleted.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function deleteVIP(OTS_Player $player)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $this->db->query('DELETE FROM ' . $this->db->tableName('player_viplist') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('vip_id') . ' = ' . $player->getId() );
    }

/**
 * Returns list of known spells.
 * 
 * <p>
 * You need global spells list resource loaded in order to use this method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @return array List of known spells.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getSpellsList()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $spells = array();
        $list = POT::getSpellsList();

        // reads all known spells
        foreach( $this->db->query('SELECT ' . $this->db->fieldName('name') . ' FROM ' . $this->db->tableName('player_spells') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id']) as $spell)
        {
            // checks if there is rune, instant or conjure spell with given name

            if( $list->hasRune($spell['name']) )
            {
                $spells[] = $list->getRune($spell['name']);
            }

            if( $list->hasInstant($spell['name']) )
            {
                $spells[] = $list->getInstant($spell['name']);
            }

            if( $list->hasConjure($spell['name']) )
            {
                $spells[] = $list->getConjure($spell['name']);
            }
        }

        return $spells;
    }

/**
 * Checks if player knows given spell.
 * 
 * @version 0.1.5
 * @since 0.1.4
 * @param OTS_Spell $spell Spell to be checked.
 * @return bool True if player knows given spell, false otherwise.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function hasSpell(OTS_Spell $spell)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->db->query('SELECT COUNT(' . $this->db->fieldName('name') . ') FROM ' . $this->db->tableName('player_spells') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('name') . ' = ' . $this->db->quote( $spell->getName() ) )->fetchColumn() > 0;
    }

/**
 * Adds given spell to player's spell book (makes him knowing it).
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @param OTS_Spell $spell Spell to be learned.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function addSpell(OTS_Spell $spell)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $this->db->query('INSERT INTO ' . $this->db->tableName('player_spells') . ' (' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('name') . ') VALUES (' . $this->data['id'] . ', ' . $this->db->quote( $spell->getName() ) . ')');
    }

/**
 * Removes given spell from player's spell book.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @param OTS_Spell $spell Spell to be removed.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function deleteSpell(OTS_Spell $spell)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $this->db->query('DELETE FROM ' . $this->db->tableName('player_spells') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('name') . ' = ' . $this->db->quote( $spell->getName() ) );
    }

/**
 * List of character deaths.
 * 
 * <p>
 * Note: Returned object is only prepared, but not initialised. When using as parameter in foreach loop it doesn't matter since it will return it's iterator, but if you will wan't to execute direct operation on that object you will need to call {@link OTS_Base_List::rewind() rewind() method} first.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return OTS_Deaths_List List of player deaths.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getDeathsList()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // creates filter
        $filter = new OTS_SQLFilter();
        $filter->compareField('player_id', (int) $this->data['id']);

        // creates list object
        $list = new OTS_Deaths_List();
        $list->setFilter($filter);

        return $list;
    }

/**
 * Returns list of player frags.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return OTS_Kills_List List of frags.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getKillsList()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $list = new OTS_Kills_List();

        // foreign table fields identifiers
        $field1 = new OTS_SQLField('player_id', 'player_killers');
        $field2 = new OTS_SQLField('kill_id', 'player_killers');

        // creates filter
        $filter = new OTS_SQLFilter();
        $filter->addFilter($field1, $this->data['id']);
        $filter->compareField('id', $field2);

        // puts filter onto list
        $list->setFilter($filter);

        return $list;
    }

/**
 * Adds kill log.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param OTS_Kill $kill Kill to be added.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function addKill(OTS_Kill $kill)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $this->db->query('INSERT INTO ' . $this->db->tableName('player_killers') . ' (' . $this->db->fieldName('kill_id') . ', ' . $this->db->fieldName('player_id') . ') VALUES (' . $kill->getId() . ', ' . $this->data['id'] . ')');
    }

/**
 * Returns players iterator.
 * 
 * <p>
 * There is no need to implement entire Iterator interface since we have {@link OTS_Deaths_List deaths list class} for it.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 * @return Iterator List of deaths.
 */
    public function getIterator()
    {
        return $this->getDeathsList();
    }

/**
 * Returns number of deaths.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @throws PDOException On PDO operation error.
 * @return int Count of deaths.
 */
    public function count()
    {
        return $this->getDeathsList()->count();
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws E_OTS_NotLoaded When player is not loaded.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'id':
                return $this->getId();

            case 'name':
                return $this->getName();

            case 'account':
                return $this->getAccount();

            case 'group':
                return $this->getGroup();

            case 'sex':
                return $this->getSex();

            case 'vocation':
                return $this->getVocation();

            case 'experience':
                return $this->getExperience();

            case 'level':
                return $this->getLevel();

            case 'magLevel':
                return $this->getMagLevel();

            case 'health':
                return $this->getHealth();

            case 'healthMax':
                return $this->getHealthMax();

            case 'mana':
                return $this->getMana();

            case 'manaMax':
                return $this->getManaMax();

            case 'manaSpent':
                return $this->getManaSpent();

            case 'soul':
                return $this->getSoul();

            case 'direction':
                return $this->getDirection();

            case 'lookBody':
                return $this->getLookBody();

            case 'lookFeet':
                return $this->getLookFeet();

            case 'lookHead':
                return $this->getLookHead();

            case 'lookLegs':
                return $this->getLookLegs();

            case 'lookType':
                return $this->getLookType();

            case 'lookAddons':
                return $this->getLookAddons();

            case 'posX':
                return $this->getPosX();

            case 'posY':
                return $this->getPosY();

            case 'posZ':
                return $this->getPosZ();

            case 'cap':
                return $this->getCap();

            case 'lastLogin':
                return $this->getLastLogin();

            case 'lastLogout':
                return $this->getLastLogout();

            case 'lastIP':
                return $this->getLastIP();

            case 'save':
                return $this->isSaveSet();

            case 'conditions':
                return $this->getConditions();

            case 'skullTime':
                return $this->getSkullTime();

            case 'skull':
                return $this->hasSkull();

            case 'rank':
                return $this->getRank();

            case 'townId':
                return $this->getTownId();

            case 'townName':
                return $this->getTownName();

            case 'house':
                return $this->getHouse();

            case 'lossExperience':
                return $this->getLossExperience();

            case 'lossMana':
                return $this->getLossMana();

            case 'lossSkills':
                return $this->getLossSkills();

            case 'lossItems':
                return $this->getLossItems();

            case 'lossContainers':
                return $this->getLossContainers();
                break;

            case 'balance':
                return $this->getBalance();

            case 'stamina':
                return $this->getStamina();

            case 'loaded':
                return $this->isLoaded();

            case 'online':
                return $this->isOnline();

            case 'vipsList':
                return $this->getVIPsList();

            case 'vocationName':
                return $this->getVocationName();

            case 'spellsList':
                return $this->getSpellsList();

            case 'deathsList':
                return $this->getDeathsList();

            case 'killsList':
                return $this->getKillsList();

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
 * @throws E_OTS_NotLoaded When passing object value which represents not-initialised instance.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'name':
                $this->setName($value);
                break;

            case 'account':
                $this->setAccount($value);
                break;

            case 'group':
                $this->setGroup($value);
                break;

            case 'sex':
                $this->setSex($value);
                break;

            case 'vocation':
                $this->setVocation($value);
                break;

            case 'experience':
                $this->setExperience($value);
                break;

            case 'level':
                $this->setLevel($value);
                break;

            case 'magLevel':
                $this->setMagLevel($value);
                break;

            case 'health':
                $this->setHealth($value);
                break;

            case 'healthMax':
                $this->setHealthMax($value);
                break;

            case 'mana':
                $this->setMana($value);
                break;

            case 'manaMax':
                $this->setManaMax($value);
                break;

            case 'manaSpent':
                $this->setManaSpent($value);
                break;

            case 'soul':
                $this->setSoul($value);
                break;

            case 'direction':
                $this->setDirection($value);
                break;

            case 'lookBody':
                $this->setLookBody($value);
                break;

            case 'lookFeet':
                $this->setLookFeet($value);
                break;

            case 'lookHead':
                $this->setLookHead($value);
                break;

            case 'lookLegs':
                $this->setLookLegs($value);
                break;

            case 'lookType':
                $this->setLookType($value);
                break;

            case 'lookAddons':
                $this->setLookAddons($value);
                break;

            case 'posX':
                $this->setPosX($value);
                break;

            case 'posY':
                $this->setPosY($value);
                break;

            case 'posZ':
                $this->setPosZ($value);
                break;

            case 'cap':
                $this->setCap($value);
                break;

            case 'lastLogin':
                $this->setLastLogin($value);
                break;

            case 'lastLogout':
                $this->setLastLogout($value);
                break;

            case 'lastIP':
                $this->setLastIP($value);
                break;

            case 'conditions':
                $this->setConditions($value);
                break;

            case 'skullTime':
                $this->setSkullTime($value);
                break;
								
            case 'rank':
                if( isset($value) )
                {
                    $this->setRank($value);
                }
                else
                {
                    $this->unsetRank();
                }
                break;

            case 'townId':
                $this->setTownId($value);
                break;

            case 'lossExperience':
                $this->setLossExperience($value);
                break;

            case 'lossMana':
                $this->setLossMana($value);
                break;

            case 'lossSkills':
                $this->setLossSkills($value);
                break;

            case 'lossItems':
                $this->setLossItems($value);
                break;

            case 'lossContainers':
                $this->setLossContainers($value);
                break;

            case 'balance':
                $this->setBalance($value);
                break;

            case 'stamina':
                $this->setStamina($value);
                break;

            case 'skull':
                if($value)
                {
                    $this->setSkull();
                }
                else
                {
                    $this->unsetSkull();
                }
                break;

            case 'save':
                if($value)
                {
                    $this->setSave();
                }
                else
                {
                    $this->unsetSave();
                }
                break;

            case 'online':
                if($value)
                {
                    $this->setOnline();
                }
                else
                {
                    $this->setOffline();
                }
                break;

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Returns string representation of object.
 * 
 * <p>
 * If any display driver is currently loaded then it uses it's method. Else it returns character name.
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
            return POT::getDisplayDriver()->displayPlayer($this);
        }

        return $this->getName();
    }
}

?>
