<?php

/**#@+
 * @version 0.0.1
 */

/**
 * @package POT
 * @version 0.0.2
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ character abstraction.
 * 
 * @package POT
 * @version 0.0.2
 */
class OTS_Player implements IOTS_DAO
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
 * @version 0.0.2
 * @var array
 */
    private $data = array('sex' => POT::SEX_FEMALE, 'vocation' => POT::VOCATION_NONE, 'experience' => 0, 'level' => 1, 'maglevel' => 0, 'health' => 100, 'maxhealth' => 100, 'mana' => 100, 'manamax' => 100, 'manasent' => 0, 'soul' => 0, 'direction' => POT::DIRECTION_NORTH, 'lookbody' => 10, 'lookfeet' => 10, 'lookhead' => 10, 'looklegs' => 10, 'looktype' => 136, 'lookaddons' => 0, 'posx' => 0, 'posy' => 0, 'posz' => 0, 'cap' => 0, 'lastlogin' => 0, 'lastip' => 0, 'save' => true, 'redskulltime' => 0, 'redskull' => false, 'guildnick' => '', 'loss_experience' => 10, 'loss_mana' => 10, 'loss_skills' => 10);

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
 * Loads player with given id.
 * 
 * @param int $id Player's ID.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('account_id') . ', ' . $this->db->fieldName('group_id') . ', ' . $this->db->fieldName('sex') . ', ' . $this->db->fieldName('vocation') . ', ' . $this->db->fieldName('experience') . ', ' . $this->db->fieldName('level') . ', ' . $this->db->fieldName('maglevel') . ', ' . $this->db->fieldName('health') . ', ' . $this->db->fieldName('healthmax') . ', ' . $this->db->fieldName('mana') . ', ' . $this->db->fieldName('manamax') . ', ' . $this->db->fieldName('manaspent') . ', ' . $this->db->fieldName('soul') . ', ' . $this->db->fieldName('direction') . ', ' . $this->db->fieldName('lookbody') . ', ' . $this->db->fieldName('lookfeet') . ', ' . $this->db->fieldName('lookhead') . ', ' . $this->db->fieldName('looklegs') . ', ' . $this->db->fieldName('looktype') . ', ' . $this->db->fieldName('lookaddons') . ', ' . $this->db->fieldName('posx') . ', ' . $this->db->fieldName('posy') . ', ' . $this->db->fieldName('posz') . ', ' . $this->db->fieldName('cap') . ', ' . $this->db->fieldName('lastlogin') . ', ' . $this->db->fieldName('lastip') . ', ' . $this->db->fieldName('save') . ', ' . $this->db->fieldName('conditions') . ', ' . $this->db->fieldName('redskulltime') . ', ' . $this->db->fieldName('redskull') . ', ' . $this->db->fieldName('guildnick') . ', ' . $this->db->fieldName('rank_id') . ', ' . $this->db->fieldName('town_id') . ', ' . $this->db->fieldName('loss_experience') . ', ' . $this->db->fieldName('loss_mana') . ', ' . $this->db->fieldName('loss_skills') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Loads player by it's name.
 * 
 * @param string $name Player's name.
 */
    public function find($name)
    {
        // finds player's ID
        $id = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('name') . ' = ' . $this->db->SQLquote($name) )->fetch();

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
 * Saves account in database.
 */
    public function save()
    {
        // updates existing group
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->SQLquery('UPDATE ' . $this->db->tableName('players') . ' SET ' . $this->db->fieldName('name') . ' = ' . $this->db->SQLquote($this->data['name']) . ', ' . $this->db->fieldName('account_id') . ' = ' . $this->data['account_id'] . ', ' . $this->db->fieldName('group_id') . ' = ' . $this->data['group_id'] . ', ' . $this->db->fieldName('sex') . ' = ' . $this->data['sex'] . ', ' . $this->db->fieldName('vocation') . ' = ' . $this->data['vocation'] . ', ' . $this->db->fieldName('experience') . ' = ' . $this->data['experience'] . ', ' . $this->db->fieldName('level') . ' = ' . $this->data['level'] . ', ' . $this->db->fieldName('maglevel') . ' = ' . $this->data['maglevel'] . ', ' . $this->db->fieldName('health') . ' = ' . $this->data['health'] . ', ' . $this->db->fieldName('healthmax') . ' = ' . $this->data['healthmax'] . ', ' . $this->db->fieldName('mana') . ' = ' . $this->data['mana'] . ', ' . $this->db->fieldName('manamax') . ' = ' . $this->data['manamax'] . ', ' . $this->db->fieldName('manaspent') . ' = ' . $this->data['manaspent'] . ', ' . $this->db->fieldName('soul') . ' = ' . $this->data['soul'] . ', ' . $this->db->fieldName('direction') . ' = ' . $this->data['direction'] . ', ' . $this->db->fieldName('lookbody') . ' = ' . $this->data['lookbody'] . ', ' . $this->db->fieldName('lookfeet') . ' = ' . $this->data['lookfeet'] . ', ' . $this->db->fieldName('lookhead') . ' = ' . $this->data['lookhead'] . ', ' . $this->db->fieldName('looklegs') . ' = ' . $this->data['looklegs'] . ', ' . $this->db->fieldName('looktype') . ' = ' . $this->data['looktype'] . ', ' . $this->db->fieldName('lookaddons') . ' = ' . $this->data['lookaddons'] . ', ' . $this->db->fieldName('posx') . ' = ' . $this->data['posx'] . ', ' . $this->db->fieldName('posy') . ' = ' . $this->data['posy'] . ', ' . $this->db->fieldName('posz') . ' = ' . $this->data['posz'] . ', ' . $this->db->fieldName('cap') . ' = ' . $this->data['cap'] . ', ' . $this->db->fieldName('lastlogin') . ' = ' . $this->data['lastlogin'] . ', ' . $this->db->fieldName('lastip') . ' = ' . $this->data['lastip'] . ', ' . $this->db->fieldName('save') . ' = ' . (int) $this->data['save'] . ', ' . $this->db->fieldName('conditions') . ' = ' . $this->db->SQLquote($this->data['conditions']) . ', ' . $this->db->fieldName('redskulltime') . ' = ' . $this->data['redskulltime'] . ', ' . $this->db->fieldName('redskull') . ' = ' . (int) $this->data['redskull'] . ', ' . $this->db->fieldName('guildnick') . ' = ' . $this->db->SQLquote($this->data['guildnick']) . ', ' . $this->db->fieldName('rank_id') . ' = ' . $this->data['rank_id'] . ', ' . $this->db->fieldName('town_id') . ' = ' . $this->data['town_id'] . ', ' . $this->db->fieldName('loss_experience') . ' = ' . $this->data['loss_experience'] . ', ' . $this->db->fieldName('loss_mana') . ' = ' . $this->data['loss_mana'] . ', ' . $this->db->fieldName('loss_skills') . ' = ' . $this->data['loss_skills'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new group
        else
        {
            // INSERT query on database
            $this->db->SQLquery('INSERT INTO ' . $this->db->tableName('players') . ' (' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('account_id') . ', ' . $this->db->fieldName('group_id') . ', ' . $this->db->fieldName('sex') . ', ' . $this->db->fieldName('vocation') . ', ' . $this->db->fieldName('experience') . ', ' . $this->db->fieldName('level') . ', ' . $this->db->fieldName('maglevel') . ', ' . $this->db->fieldName('health') . ', ' . $this->db->fieldName('healthmax') . ', ' . $this->db->fieldName('mana') . ', ' . $this->db->fieldName('manamax') . ', ' . $this->db->fieldName('manaspent') . ', ' . $this->db->fieldName('soul') . ', ' . $this->db->fieldName('direction') . ', ' . $this->db->fieldName('lookbody') . ', ' . $this->db->fieldName('lookfeet') . ', ' . $this->db->fieldName('lookhead') . ', ' . $this->db->fieldName('looklegs') . ', ' . $this->db->fieldName('looktype') . ', ' . $this->db->fieldName('lookaddons') . ', ' . $this->db->fieldName('posx') . ', ' . $this->db->fieldName('posy') . ', ' . $this->db->fieldName('posz') . ', ' . $this->db->fieldName('cap') . ', ' . $this->db->fieldName('lastlogin') . ', ' . $this->db->fieldName('lastip') . ', ' . $this->db->fieldName('save') . ', ' . $this->db->fieldName('conditions') . ', ' . $this->db->fieldName('redskulltime') . ', ' . $this->db->fieldName('redskull') . ', ' . $this->db->fieldName('guildnick') . ', ' . $this->db->fieldName('rank_id') . ', ' . $this->db->fieldName('town_id') . ', ' . $this->db->fieldName('loss_experience') . ', ' . $this->db->fieldName('loss_mana') . ', ' . $this->db->fieldName('loss_skills') . ') VALUES (' . $this->db->SQLquote($this->data['name']) . ', ' . $this->data['account_id'] . ', ' . $this->data['group_id'] . ', ' . $this->data['sex'] . ', ' . $this->data['vocation'] . ', ' . $this->data['experience'] . ', ' . $this->data['level'] . ', ' . $this->data['maglevel'] . ', ' . $this->data['health'] . ', ' . $this->data['healthmax'] . ', ' . $this->data['mana'] . ', ' . $this->data['manamax'] . ', ' . $this->data['manaspent'] . ', ' . $this->data['soul'] . ', ' . $this->data['direction'] . ', ' . $this->data['lookbody'] . ', ' . $this->data['lookfeet'] . ', ' . $this->data['lookhead'] . ', ' . $this->data['looklegs'] . ', ' . $this->data['looktype'] . ', ' . $this->data['lookaddons'] . ', ' . $this->data['posx'] . ', ' . $this->data['posy'] . ', ' . $this->data['posz'] . ', ' . $this->data['cap'] . ', ' . $this->data['lastlogin'] . ', ' . $this->data['lastip'] . ', ' . (int) $this->data['save'] . ', ' . $this->db->SQLquote($this->data['conditions']) . ', ' . $this->data['redskulltime'] . ', ' . (int) $this->data['redskull'] . ', ' . $this->db->SQLquote($this->data['guildnick']) . ', ' . $this->data['rank_id'] . ', ' . $this->data['town_id'] . ', ' . $this->data['loss_experience'] . ', ' . $this->data['loss_mana'] . ', ' . $this->data['loss_skills'] . ')');
            // ID of new group
            $this->data['id'] = $this->db->lastInsertId();
        }
    }

/**
 * Player ID.
 * 
 * @return int|bool Player ID (false if not loaded).
 */
    public function getId()
    {
        if( !isset($this->data['id']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['id'];
    }

/**
 * Player name.
 * 
 * @return string|bool Player's name (false if not loaded).
 */
    public function getName()
    {
        if( !isset($this->data['name']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
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
 * Returns account of this player.
 * 
 * @return OTS_Account Owning account.
 */
    public function getAccount()
    {
        if( !isset($this->data['account_id']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            // we dont do return false; here as it would cause problems in context code when relying on OTS_Account class of results
        }

        $account = POT::getInstance()->createObject('Account');
        $account->load($this->data['account_id']);
        return $account;
    }

/**
 * Assigns character to account.
 * 
 * @param OTS_Account $account Owning account.
 */
    public function setAccount(OTS_Account $account)
    {
        $this->data['account_id'] = $account->getId();
    }

/**
 * Returns group of this player.
 * 
 * @return OTS_Group Group of which current character is member.
 */
    public function getGroup()
    {
        if( !isset($this->data['group_id']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            // we dont do return false; here as it would cause problems in context code when relying on OTS_Group class of results
        }

        $group = POT::getInstance()->createObject('Group');
        $group->load($this->data['group_id']);
        return $group;
    }

/**
 * Assigns character to group.
 * 
 * @param OTS_Group $group Group to be a member.
 */
    public function setGroup(OTS_Group $group)
    {
        $this->data['group_id'] = $group->getId();
    }

/**
 * Player gender.
 * 
 * @return int|bool Player gender (false if not loaded).
 */
    public function getSex()
    {
        if( !isset($this->data['sex']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['sex'];
    }

/**
 * Sets player gender.
 * 
 * @param int $sex Player gender.
 */
    public function setSex($sex)
    {
        $this->data['sex'] = (int) $sex;
    }

/**
 * Player proffesion.
 * 
 * @return int|bool Player proffesion (false if not loaded).
 */
    public function getVocation()
    {
        if( !isset($this->data['vocation']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['vocation'];
    }

/**
 * Sets player proffesion.
 * 
 * @param int $vocation Player proffesion.
 */
    public function setVocation($vocation)
    {
        $this->data['vocation'] = (int) $vocation;
    }

/**
 * Experience points.
 * 
 * @return int|bool Experience points (false if not loaded).
 */
    public function getExperience()
    {
        if( !isset($this->data['experience']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['experience'];
    }

/**
 * Sets experience points.
 * 
 * @param int $experience Experience points.
 */
    public function setExperience($experience)
    {
        $this->data['experience'] = (int) $experience;
    }

/**
 * Experience level.
 * 
 * @return int|bool Experience level (false if not loaded).
 */
    public function getLevel()
    {
        if( !isset($this->data['level']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['level'];
    }

/**
 * Sets experience level.
 * 
 * @param int $level Experience level.
 */
    public function setLevel($level)
    {
        $this->data['level'] = (int) $level;
    }

/**
 * Magic level.
 * 
 * @return int|bool Magic level (false if not loaded).
 */
    public function getMagLevel()
    {
        if( !isset($this->data['maglevel']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['maglevel'];
    }

/**
 * Sets magic level.
 * 
 * @param int $maglevel Magic level.
 */
    public function setMagLevel($maglevel)
    {
        $this->data['maglevel'] = (int) $maglevel;
    }

/**
 * Current HP.
 * 
 * @return int|bool Current HP (false if not loaded).
 */
    public function getHealth()
    {
        if( !isset($this->data['health']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['health'];
    }

/**
 * Sets current HP.
 * 
 * @param int $health Current HP.
 */
    public function setHealth($health)
    {
        $this->data['health'] = (int) $health;
    }

/**
 * Maximum HP.
 * 
 * @return int|bool Maximum HP (false if not loaded).
 */
    public function getHealthMax()
    {
        if( !isset($this->data['healthmax']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['healthmax'];
    }

/**
 * Sets maximum HP.
 * 
 * @param int $healthmax Maximum HP.
 */
    public function setHealthMax($healthmax)
    {
        $this->data['healthmax'] = (int) $healthmax;
    }

/**
 * Current mana.
 * 
 * @return int|bool Current mana (false if not loaded).
 */
    public function getMana()
    {
        if( !isset($this->data['mana']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['mana'];
    }

/**
 * Sets current mana.
 * 
 * @param int $mana Current mana.
 */
    public function setMana($mana)
    {
        $this->data['mana'] = (int) $mana;
    }

/**
 * Maximum mana.
 * 
 * @return int|bool Maximum mana (false if not loaded).
 */
    public function getManaMax()
    {
        if( !isset($this->data['manamax']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['manamax'];
    }

/**
 * Sets maximum mana.
 * 
 * @param int $manamax Maximum mana.
 */
    public function setManaMax($manamax)
    {
        $this->data['manamax'] = (int) $manamax;
    }

/**
 * Mana spent.
 * 
 * @return int|bool Mana spent (false if not loaded).
 */
    public function getManaSpent()
    {
        if( !isset($this->data['manaspent']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['manaspent'];
    }

/**
 * Sets mana spent.
 * 
 * @param int $manaspent Mana spent.
 */
    public function setManaSpent($manaspent)
    {
        $this->data['manaspent'] = (int) $manaspent;
    }

/**
 * Soul points.
 * 
 * @return int|bool Soul points (false if not loaded).
 */
    public function getSoul()
    {
        if( !isset($this->data['soul']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['soul'];
    }

/**
 * Sets soul points.
 * 
 * @param int $soul Soul points.
 */
    public function setSoul($soul)
    {
        $this->data['soul'] = (int) $soul;
    }

/**
 * Looking direction.
 * 
 * @return int|bool Looking direction (false if not loaded).
 */
    public function getDirection()
    {
        if( !isset($this->data['direction']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['direction'];
    }

/**
 * Sets looking direction.
 * 
 * @param int $direction Looking direction.
 */
    public function setDirection($direction)
    {
        $this->data['direction'] = (int) $direction;
    }

/**
 * Body color.
 * 
 * @return int|bool Body color (false if not loaded).
 */
    public function getLookBody()
    {
        if( !isset($this->data['lookbody']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['lookbody'];
    }

/**
 * Sets body color.
 * 
 * @param int $lookbody Body color.
 */
    public function setLookBody($lookbody)
    {
        $this->data['lookbody'] = (int) $lookbody;
    }

/**
 * Boots color.
 * 
 * @return int|bool Boots color (false if not loaded).
 */
    public function getLookFeet()
    {
        if( !isset($this->data['lookfeet']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['lookfeet'];
    }

/**
 * Sets boots color.
 * 
 * @param int $lookfeet Boots color.
 */
    public function setLookFeet($lookfeet)
    {
        $this->data['lookfeet'] = (int) $lookfeet;
    }

/**
 * Hair color.
 * 
 * @return int|bool Hair color (false if not loaded).
 */
    public function getLookHead()
    {
        if( !isset($this->data['lookhead']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['lookhead'];
    }

/**
 * Sets hair color.
 * 
 * @param int $lookhead Hair color.
 */
    public function setLookHead($lookhead)
    {
        $this->data['lookhead'] = (int) $lookhead;
    }

/**
 * Legs color.
 * 
 * @return int|bool Legs color (false if not loaded).
 */
    public function getLookLegs()
    {
        if( !isset($this->data['looklegs']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['looklegs'];
    }

/**
 * Sets legs color.
 * 
 * @param int $looklegs Legs color.
 */
    public function setLookLegs($looklegs)
    {
        $this->data['looklegs'] = (int) $looklegs;
    }

/**
 * Outfit.
 * 
 * @return int|bool Outfit (false if not loaded).
 */
    public function getLookType()
    {
        if( !isset($this->data['looktype']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['looktype'];
    }

/**
 * Sets outfit.
 * 
 * @param int $looktype Outfit.
 */
    public function setLookType($looktype)
    {
        $this->data['looktype'] = (int) $looktype;
    }

/**
 * Addons.
 * 
 * @return int|bool Addons (false if not loaded).
 */
    public function getLookAddons()
    {
        if( !isset($this->data['lookaddons']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['lookaddons'];
    }

/**
 * Sets addons.
 * 
 * @param int $lookaddons Addons.
 */
    public function setLookAddons($lookaddons)
    {
        $this->data['lookaddons'] = (int) $lookaddons;
    }

/**
 * X map coordinate.
 * 
 * @return int|bool X map coordinate (false if not loaded).
 */
    public function getPosX()
    {
        if( !isset($this->data['posx']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['posx'];
    }

/**
 * Sets X map coordinate.
 * 
 * @param int $posx X map coordinate.
 */
    public function setPosX($posx)
    {
        $this->data['posx'] = (int) $posx;
    }

/**
 * Y map coordinate.
 * 
 * @return int|bool Y map coordinate (false if not loaded).
 */
    public function getPosY()
    {
        if( !isset($this->data['posy']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['posy'];
    }

/**
 * Sets Y map coordinate.
 * 
 * @param int $posy Y map coordinate.
 */
    public function setPosY($posy)
    {
        $this->data['posy'] = (int) $posy;
    }

/**
 * Z map coordinate.
 * 
 * @return int|bool Z map coordinate (false if not loaded).
 */
    public function getPosZ()
    {
        if( !isset($this->data['posz']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['posz'];
    }

/**
 * Sets Z map coordinate.
 * 
 * @param int $posz Z map coordinate.
 */
    public function setPosZ($posz)
    {
        $this->data['posz'] = (int) $posz;
    }

/**
 * Capacity.
 * 
 * @return int|bool Capacity (false if not loaded).
 */
    public function getCap()
    {
        if( !isset($this->data['cap']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['cap'];
    }

/**
 * Sets capacity.
 * 
 * @param int $cap Capacity.
 */
    public function setCap($cap)
    {
        $this->data['cap'] = (int) $cap;
    }

/**
 * Last login timestamp.
 * 
 * @return int|bool Last login timestamp (false if not loaded).
 */
    public function getLastLogin()
    {
        if( !isset($this->data['lastlogin']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['lastlogin'];
    }

/**
 * Sets last login timestamp.
 * 
 * @param int $lastlogin Last login timestamp.
 */
    public function setLastLogin($lastlogin)
    {
        $this->data['lastlogin'] = (int) $lastlogin;
    }

/**
 * Last login IP.
 * 
 * @return int|bool Last login IP (false if not loaded).
 */
    public function getLastIP()
    {
        if( !isset($this->data['lastip']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['lastip'];
    }

/**
 * Sets last login IP.
 * 
 * @param int $lastip Last login IP.
 */
    public function setLastIP($lastip)
    {
        $this->data['lastip'] = (int) $lastip;
    }

/**
 * Checks if save flag is set.
 * 
 * @return bool|null PACC days (null if not loaded).
 */
    public function isSaveSet()
    {
        if( !isset($this->data['save']) )
        {
            trigger_error('Tries to get property of not loaded account.', E_USER_NOTICE);
            return null;
        }

        return $this->data['save'];
    }

/**
 * Unsets save flag.
 */
    public function unsetSave()
    {
        $this->data['save'] = false;
    }

/**
 * Sets save flag.
 */
    public function setSave()
    {
        $this->data['save'] = true;
    }

/**
 * Conditions.
 * 
 * @return mixed|bool Conditions (false if not loaded).
 */
    public function getConditions()
    {
        if( !isset($this->data['conditions']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['conditions'];
    }

/**
 * Sets conditions.
 * 
 * @param mixed $conditions Condition binary field.
 */
    public function setConditions($conditions)
    {
        $this->data['conditions'] = $conditions;
    }

/**
 * Red skulled time remained.
 * 
 * @return int|bool Red skulled time remained (false if not loaded).
 */
    public function getRedSkullTime()
    {
        if( !isset($this->data['redskulltime']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['redskulltime'];
    }

/**
 * Sets red skulled time remained.
 * 
 * @param int $redskulltime Red skulled time remained.
 */
    public function setRedSkullTime($redskulltime)
    {
        $this->data['redskulltime'] = (int) $redskulltime;
    }

/**
 * Checks if player has red skull.
 * 
 * @return bool|null Red skull state (null if not loaded).
 */
    public function hasRedSkull()
    {
        if( !isset($this->data['redskull']) )
        {
            trigger_error('Tries to get property of not loaded account.', E_USER_NOTICE);
            return null;
        }

        return $this->data['redskull'];
    }

/**
 * Unsets red skull flag.
 */
    public function unsetRedSkull()
    {
        $this->data['redskull'] = false;
    }

/**
 * Sets red skull flag.
 */
    public function setRedSkull()
    {
        $this->data['redskull'] = true;
    }

/**
 * Guild nick.
 * 
 * @return string|bool Guild title (false if not loaded).
 */
    public function getGuildNick()
    {
        if( !isset($this->data['guildnick']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['guildnick'];
    }

/**
 * Sets guild nick.
 * 
 * @param string $guildnick Name.
 */
    public function setGuildNick($guildnick)
    {
        $this->data['guildnick'] = (string) $guildnick;
    }

/**
 * Guild rank ID.
 * 
 * @return int|bool Guild rank ID (false if not loaded).
 */
    public function getRankId()
    {
        if( !isset($this->data['rank_id']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['rank_id'];
    }

/**
 * Sets guild rank ID.
 * 
 * @param int $rank_id Guild rank ID.
 */
    public function setRankId($rank_id)
    {
        $this->data['rank_id'] = (int) $rank_id;
    }
/**
 * Residence town's ID.
 * 
 * @return int|bool Residence town's ID (false if not loaded).
 */
    public function getTownId()
    {
        if( !isset($this->data['town_id']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['town_id'];
    }

/**
 * Sets residence town's ID.
 * 
 * @param int $town_id Residence town's ID.
 */
    public function setTownId($town_id)
    {
        $this->data['town_id'] = (int) $town_id;
    }
/**
 * Percentage of experience lost after dead.
 * 
 * @return int|bool Percentage of experience lost after dead (false if not loaded).
 */
    public function getLossExperience()
    {
        if( !isset($this->data['loss_experience']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['loss_experience'];
    }

/**
 * Sets percentage of experience lost after dead.
 * 
 * @param int $loss_experience Percentage of experience lost after dead.
 */
    public function setLossExperience($loss_experience)
    {
        $this->data['loss_experience'] = (int) $loss_experience;
    }
/**
 * Percentage of used mana lost after dead.
 * 
 * @return int|bool Percentage of used mana lost after dead (false if not loaded).
 */
    public function getLossMana()
    {
        if( !isset($this->data['loss_mana']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['loss_mana'];
    }

/**
 * Sets percentage of used mana lost after dead.
 * 
 * @param int $loss_mana Percentage of used mana lost after dead.
 */
    public function setLossMana($loss_mana)
    {
        $this->data['loss_mana'] = (int) $loss_mana;
    }
/**
 * Percentage of skills lost after dead.
 * 
 * @return int|bool Percentage of skills lost after dead (false if not loaded).
 */
    public function getLossSkills()
    {
        if( !isset($this->data['loss_skills']) )
        {
            trigger_error('Tries to get property of not loaded player.', E_USER_NOTICE);
            return false;
        }

        return $this->data['loss_skills'];
    }

/**
 * Sets percentage of skills lost after dead.
 * 
 * @param int $loss_skills Percentage of skills lost after dead.
 */
    public function setLossSkills($loss_skills)
    {
        $this->data['loss_skills'] = (int) $loss_skills;
    }
}

/**#@-*/

?>
