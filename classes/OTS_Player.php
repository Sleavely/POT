<?php

/**#@+
 * @version 0.0.1
 */

/**
 * @package POT
 * @version 0.0.4
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ character abstraction.
 * 
 * @package POT
 * @version 0.0.4
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
 * @version 0.0.4
 * @var array
 */
    private $data = array('premend' => 0, 'sex' => POT::SEX_FEMALE, 'vocation' => POT::VOCATION_NONE, 'experience' => 0, 'level' => 1, 'maglevel' => 0, 'health' => 100, 'healthmax' => 100, 'mana' => 100, 'manamax' => 100, 'manaspent' => 0, 'soul' => 0, 'direction' => POT::DIRECTION_NORTH, 'lookbody' => 10, 'lookfeet' => 10, 'lookhead' => 10, 'looklegs' => 10, 'looktype' => 136, 'lookaddons' => 0, 'posx' => 0, 'posy' => 0, 'posz' => 0, 'cap' => 0, 'lastlogin' => 0, 'lastip' => 0, 'save' => true, 'redskulltime' => 0, 'redskull' => false, 'guildnick' => '', 'loss_experience' => 10, 'loss_mana' => 10, 'loss_skills' => 10);

/**
 * Player skills.
 * 
 * @version 0.0.2
 * @since 0.0.2
 * @var array
 */
    private $skills = array();

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
 * @version 0.0.4
 * @since 0.0.4
 */
    public function __sleep()
    {
        return array('data', 'skills');
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object unserialisation.
 * 
 * @internal Magic PHP5 method.
 * @version 0.0.4
 * @since 0.0.4
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
 * @version 0.0.4
 * @since 0.0.4
 */
    public function __clone()
    {
        unset($this->data['id']);
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @internal Magic PHP5 method.
 * @version 0.0.4
 * @since 0.0.4
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
 * Loads player with given id.
 * 
 * @version 0.0.2
 * @param int $id Player's ID.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('account_id') . ', ' . $this->db->fieldName('group_id') . ', ' . $this->db->fieldName('premend') . ', ' . $this->db->fieldName('sex') . ', ' . $this->db->fieldName('vocation') . ', ' . $this->db->fieldName('experience') . ', ' . $this->db->fieldName('level') . ', ' . $this->db->fieldName('maglevel') . ', ' . $this->db->fieldName('health') . ', ' . $this->db->fieldName('healthmax') . ', ' . $this->db->fieldName('mana') . ', ' . $this->db->fieldName('manamax') . ', ' . $this->db->fieldName('manaspent') . ', ' . $this->db->fieldName('soul') . ', ' . $this->db->fieldName('direction') . ', ' . $this->db->fieldName('lookbody') . ', ' . $this->db->fieldName('lookfeet') . ', ' . $this->db->fieldName('lookhead') . ', ' . $this->db->fieldName('looklegs') . ', ' . $this->db->fieldName('looktype') . ', ' . $this->db->fieldName('lookaddons') . ', ' . $this->db->fieldName('posx') . ', ' . $this->db->fieldName('posy') . ', ' . $this->db->fieldName('posz') . ', ' . $this->db->fieldName('cap') . ', ' . $this->db->fieldName('lastlogin') . ', ' . $this->db->fieldName('lastip') . ', ' . $this->db->fieldName('save') . ', ' . $this->db->fieldName('conditions') . ', ' . $this->db->fieldName('redskulltime') . ', ' . $this->db->fieldName('redskull') . ', ' . $this->db->fieldName('guildnick') . ', ' . $this->db->fieldName('rank_id') . ', ' . $this->db->fieldName('town_id') . ', ' . $this->db->fieldName('loss_experience') . ', ' . $this->db->fieldName('loss_mana') . ', ' . $this->db->fieldName('loss_skills') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();

        // loads skills
        if( $this->isLoaded() )
        {
            foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('skillid') . ', ' . $this->db->fieldName('value') . ', ' . $this->db->fieldName('count') . ' FROM ' . $this->db->tableName('player_skills') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'])->fetchAll() as $skill)
            {
                $this->skills[ $skill['skillid'] ] = array('value' => $skill['value'], 'tries' => $skill['count']);
            }
        }
    }

/**
 * Loads player by it's name.
 * 
 * @since 0.0.2
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
 * Saves player in database.
 * 
 * @version 0.0.2
 */
    public function save()
    {
        // updates existing player
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->SQLquery('UPDATE ' . $this->db->tableName('players') . ' SET ' . $this->db->fieldName('name') . ' = ' . $this->db->SQLquote($this->data['name']) . ', ' . $this->db->fieldName('account_id') . ' = ' . $this->data['account_id'] . ', ' . $this->db->fieldName('group_id') . ' = ' . $this->data['group_id'] . ', ' . $this->db->fieldName('premend') . ' = ' . $this->data['premend'] . ', ' . $this->db->fieldName('sex') . ' = ' . $this->data['sex'] . ', ' . $this->db->fieldName('vocation') . ' = ' . $this->data['vocation'] . ', ' . $this->db->fieldName('experience') . ' = ' . $this->data['experience'] . ', ' . $this->db->fieldName('level') . ' = ' . $this->data['level'] . ', ' . $this->db->fieldName('maglevel') . ' = ' . $this->data['maglevel'] . ', ' . $this->db->fieldName('health') . ' = ' . $this->data['health'] . ', ' . $this->db->fieldName('healthmax') . ' = ' . $this->data['healthmax'] . ', ' . $this->db->fieldName('mana') . ' = ' . $this->data['mana'] . ', ' . $this->db->fieldName('manamax') . ' = ' . $this->data['manamax'] . ', ' . $this->db->fieldName('manaspent') . ' = ' . $this->data['manaspent'] . ', ' . $this->db->fieldName('soul') . ' = ' . $this->data['soul'] . ', ' . $this->db->fieldName('direction') . ' = ' . $this->data['direction'] . ', ' . $this->db->fieldName('lookbody') . ' = ' . $this->data['lookbody'] . ', ' . $this->db->fieldName('lookfeet') . ' = ' . $this->data['lookfeet'] . ', ' . $this->db->fieldName('lookhead') . ' = ' . $this->data['lookhead'] . ', ' . $this->db->fieldName('looklegs') . ' = ' . $this->data['looklegs'] . ', ' . $this->db->fieldName('looktype') . ' = ' . $this->data['looktype'] . ', ' . $this->db->fieldName('lookaddons') . ' = ' . $this->data['lookaddons'] . ', ' . $this->db->fieldName('posx') . ' = ' . $this->data['posx'] . ', ' . $this->db->fieldName('posy') . ' = ' . $this->data['posy'] . ', ' . $this->db->fieldName('posz') . ' = ' . $this->data['posz'] . ', ' . $this->db->fieldName('cap') . ' = ' . $this->data['cap'] . ', ' . $this->db->fieldName('lastlogin') . ' = ' . $this->data['lastlogin'] . ', ' . $this->db->fieldName('lastip') . ' = ' . $this->data['lastip'] . ', ' . $this->db->fieldName('save') . ' = ' . (int) $this->data['save'] . ', ' . $this->db->fieldName('conditions') . ' = ' . $this->db->SQLquote($this->data['conditions']) . ', ' . $this->db->fieldName('redskulltime') . ' = ' . $this->data['redskulltime'] . ', ' . $this->db->fieldName('redskull') . ' = ' . (int) $this->data['redskull'] . ', ' . $this->db->fieldName('guildnick') . ' = ' . $this->db->SQLquote($this->data['guildnick']) . ', ' . $this->db->fieldName('rank_id') . ' = ' . $this->data['rank_id'] . ', ' . $this->db->fieldName('town_id') . ' = ' . $this->data['town_id'] . ', ' . $this->db->fieldName('loss_experience') . ' = ' . $this->data['loss_experience'] . ', ' . $this->db->fieldName('loss_mana') . ' = ' . $this->data['loss_mana'] . ', ' . $this->db->fieldName('loss_skills') . ' = ' . $this->data['loss_skills'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new player
        else
        {
            // INSERT query on database
            $this->db->SQLquery('INSERT INTO ' . $this->db->tableName('players') . ' (' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('account_id') . ', ' . $this->db->fieldName('group_id') . ', ' . $this->db->fieldName('premend') . ', ' . $this->db->fieldName('sex') . ', ' . $this->db->fieldName('vocation') . ', ' . $this->db->fieldName('experience') . ', ' . $this->db->fieldName('level') . ', ' . $this->db->fieldName('maglevel') . ', ' . $this->db->fieldName('health') . ', ' . $this->db->fieldName('healthmax') . ', ' . $this->db->fieldName('mana') . ', ' . $this->db->fieldName('manamax') . ', ' . $this->db->fieldName('manaspent') . ', ' . $this->db->fieldName('soul') . ', ' . $this->db->fieldName('direction') . ', ' . $this->db->fieldName('lookbody') . ', ' . $this->db->fieldName('lookfeet') . ', ' . $this->db->fieldName('lookhead') . ', ' . $this->db->fieldName('looklegs') . ', ' . $this->db->fieldName('looktype') . ', ' . $this->db->fieldName('lookaddons') . ', ' . $this->db->fieldName('posx') . ', ' . $this->db->fieldName('posy') . ', ' . $this->db->fieldName('posz') . ', ' . $this->db->fieldName('cap') . ', ' . $this->db->fieldName('lastlogin') . ', ' . $this->db->fieldName('lastip') . ', ' . $this->db->fieldName('save') . ', ' . $this->db->fieldName('conditions') . ', ' . $this->db->fieldName('redskulltime') . ', ' . $this->db->fieldName('redskull') . ', ' . $this->db->fieldName('guildnick') . ', ' . $this->db->fieldName('rank_id') . ', ' . $this->db->fieldName('town_id') . ', ' . $this->db->fieldName('loss_experience') . ', ' . $this->db->fieldName('loss_mana') . ', ' . $this->db->fieldName('loss_skills') . ') VALUES (' . $this->db->SQLquote($this->data['name']) . ', ' . $this->data['account_id'] . ', ' . $this->data['group_id'] . ', ' . $this->data['premend'] . ', ' . $this->data['sex'] . ', ' . $this->data['vocation'] . ', ' . $this->data['experience'] . ', ' . $this->data['level'] . ', ' . $this->data['maglevel'] . ', ' . $this->data['health'] . ', ' . $this->data['healthmax'] . ', ' . $this->data['mana'] . ', ' . $this->data['manamax'] . ', ' . $this->data['manaspent'] . ', ' . $this->data['soul'] . ', ' . $this->data['direction'] . ', ' . $this->data['lookbody'] . ', ' . $this->data['lookfeet'] . ', ' . $this->data['lookhead'] . ', ' . $this->data['looklegs'] . ', ' . $this->data['looktype'] . ', ' . $this->data['lookaddons'] . ', ' . $this->data['posx'] . ', ' . $this->data['posy'] . ', ' . $this->data['posz'] . ', ' . $this->data['cap'] . ', ' . $this->data['lastlogin'] . ', ' . $this->data['lastip'] . ', ' . (int) $this->data['save'] . ', ' . $this->db->SQLquote($this->data['conditions']) . ', ' . $this->data['redskulltime'] . ', ' . (int) $this->data['redskull'] . ', ' . $this->db->SQLquote($this->data['guildnick']) . ', ' . $this->data['rank_id'] . ', ' . $this->data['town_id'] . ', ' . $this->data['loss_experience'] . ', ' . $this->data['loss_mana'] . ', ' . $this->data['loss_skills'] . ')');
            // ID of new group
            $this->data['id'] = $this->db->lastInsertId();
        }

        // updates skills - doesn't matter if we have just created character - trigger inserts new skills
        foreach($this->skills as $id => $skill)
        {
            $this->db->SQLquery('UPDATE ' . $this->db->tableName('player_skills') . ' SET ' . $this->db->fieldName('value') . ' = ' . $skill['value'] . ', ' . $this->db->fieldName('count') . ' = ' . $skill['tries'] . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('skillid') . ' = ' . $id);
        }
    }

/**
 * Player ID.
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
 * @param string $name Name.
 */
    public function setName($name)
    {
        $this->data['name'] = (string) $name;
    }

/**
 * Returns account of this player.
 * 
 * @version 0.0.3
 * @return OTS_Account Owning account.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getAccount()
    {
        if( !isset($this->data['account_id']) )
        {
            throw new E_OTS_NotLoaded();
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
 * @version 0.0.3
 * @return OTS_Group Group of which current character is member.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getGroup()
    {
        if( !isset($this->data['group_id']) )
        {
            throw new E_OTS_NotLoaded();
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
 * Player's Premium Account expiration timestamp.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @return int Player PACC expiration timestamp.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getPremiumEnd()
    {
        if( !isset($this->data['premend']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['premend'];
    }

/**
 * Sets player's Premium Account expiration timestamp.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param int $premend PACC expiration timestamp.
 */
    public function setPremiumEnd($premend)
    {
        $this->data['premend'] = (int) $premend;
    }

/**
 * Player gender.
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
 * @param int $sex Player gender.
 */
    public function setSex($sex)
    {
        $this->data['sex'] = (int) $sex;
    }

/**
 * Player proffesion.
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
 * @param int $vocation Player proffesion.
 */
    public function setVocation($vocation)
    {
        $this->data['vocation'] = (int) $vocation;
    }

/**
 * Experience points.
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
 * @param int $experience Experience points.
 */
    public function setExperience($experience)
    {
        $this->data['experience'] = (int) $experience;
    }

/**
 * Experience level.
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
 * @param int $level Experience level.
 */
    public function setLevel($level)
    {
        $this->data['level'] = (int) $level;
    }

/**
 * Magic level.
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
 * @param int $maglevel Magic level.
 */
    public function setMagLevel($maglevel)
    {
        $this->data['maglevel'] = (int) $maglevel;
    }

/**
 * Current HP.
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
 * @param int $health Current HP.
 */
    public function setHealth($health)
    {
        $this->data['health'] = (int) $health;
    }

/**
 * Maximum HP.
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
 * @param int $healthmax Maximum HP.
 */
    public function setHealthMax($healthmax)
    {
        $this->data['healthmax'] = (int) $healthmax;
    }

/**
 * Current mana.
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
 * @param int $mana Current mana.
 */
    public function setMana($mana)
    {
        $this->data['mana'] = (int) $mana;
    }

/**
 * Maximum mana.
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
 * @param int $manamax Maximum mana.
 */
    public function setManaMax($manamax)
    {
        $this->data['manamax'] = (int) $manamax;
    }

/**
 * Mana spent.
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
 * @param int $manaspent Mana spent.
 */
    public function setManaSpent($manaspent)
    {
        $this->data['manaspent'] = (int) $manaspent;
    }

/**
 * Soul points.
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
 * @param int $soul Soul points.
 */
    public function setSoul($soul)
    {
        $this->data['soul'] = (int) $soul;
    }

/**
 * Looking direction.
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
 * @param int $direction Looking direction.
 */
    public function setDirection($direction)
    {
        $this->data['direction'] = (int) $direction;
    }

/**
 * Body color.
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
 * @param int $lookbody Body color.
 */
    public function setLookBody($lookbody)
    {
        $this->data['lookbody'] = (int) $lookbody;
    }

/**
 * Boots color.
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
 * @param int $lookfeet Boots color.
 */
    public function setLookFeet($lookfeet)
    {
        $this->data['lookfeet'] = (int) $lookfeet;
    }

/**
 * Hair color.
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
 * @param int $lookhead Hair color.
 */
    public function setLookHead($lookhead)
    {
        $this->data['lookhead'] = (int) $lookhead;
    }

/**
 * Legs color.
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
 * @param int $looklegs Legs color.
 */
    public function setLookLegs($looklegs)
    {
        $this->data['looklegs'] = (int) $looklegs;
    }

/**
 * Outfit.
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
 * @param int $looktype Outfit.
 */
    public function setLookType($looktype)
    {
        $this->data['looktype'] = (int) $looktype;
    }

/**
 * Addons.
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
 * @param int $lookaddons Addons.
 */
    public function setLookAddons($lookaddons)
    {
        $this->data['lookaddons'] = (int) $lookaddons;
    }

/**
 * X map coordinate.
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
 * @param int $posx X map coordinate.
 */
    public function setPosX($posx)
    {
        $this->data['posx'] = (int) $posx;
    }

/**
 * Y map coordinate.
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
 * @param int $posy Y map coordinate.
 */
    public function setPosY($posy)
    {
        $this->data['posy'] = (int) $posy;
    }

/**
 * Z map coordinate.
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
 * @param int $posz Z map coordinate.
 */
    public function setPosZ($posz)
    {
        $this->data['posz'] = (int) $posz;
    }

/**
 * Capacity.
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
 * @param int $cap Capacity.
 */
    public function setCap($cap)
    {
        $this->data['cap'] = (int) $cap;
    }

/**
 * Last login timestamp.
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
 * @param int $lastlogin Last login timestamp.
 */
    public function setLastLogin($lastlogin)
    {
        $this->data['lastlogin'] = (int) $lastlogin;
    }

/**
 * Last login IP.
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
 * @param int $lastip Last login IP.
 */
    public function setLastIP($lastip)
    {
        $this->data['lastip'] = (int) $lastip;
    }

/**
 * Checks if save flag is set.
 * 
 * @version 0.0.3
 * @return bool PACC days.
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
 * @version 0.0.3
 * @return mixed Conditions.
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
 * @param mixed $conditions Condition binary field.
 */
    public function setConditions($conditions)
    {
        $this->data['conditions'] = $conditions;
    }

/**
 * Red skulled time remained.
 * 
 * @version 0.0.3
 * @return int Red skulled time remained.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getRedSkullTime()
    {
        if( !isset($this->data['redskulltime']) )
        {
            throw new E_OTS_NotLoaded();
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
 * @version 0.0.3
 * @return bool Red skull state.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function hasRedSkull()
    {
        if( !isset($this->data['redskull']) )
        {
            throw new E_OTS_NotLoaded();
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
 * @version 0.0.3
 * @return string Guild title.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getGuildNick()
    {
        if( !isset($this->data['guildnick']) )
        {
            throw new E_OTS_NotLoaded();
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
 * @version 0.0.3
 * @return int Guild rank ID.
 * @throws E_OTS_NotLoaded If player is not loaded.
 * @deprecated 0.0.4 Use getRank().
 */
    public function getRankId()
    {
        if( !isset($this->data['rank_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['rank_id'];
    }

/**
 * Assigned guild rank.
 * 
 * @return OTS_GuildRank|null Guild rank (null if not member of any).
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getRank()
    {
        if( !isset($this->data['rank_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        if($this->data['rank_id'] == 0)
        {
            return null;
        }

        $guildRank = POT::getInstance()->createObject('GuildRank');
        $guildRank->load($this->data['rank_id']);
        return $guildRank;
    }

/**
 * Sets guild rank ID.
 * 
 * @param int $rank_id Guild rank ID.
 * @deprecated 0.0.4 Use setRank().
 */
    public function setRankId($rank_id)
    {
        $this->data['rank_id'] = (int) $rank_id;
    }

/**
 * Assigns guild rank.
 * 
 * @param OTS_GuildRank|null Guild rank (null to clear assign).
 */
    public function setRank(OTS_GuildRank $guildRank = null)
    {
        if( isset($guildRank) )
        {
            $this->data['rank_id'] = $guildRank->getId();
        }
        else
        {
            $this->data['rank_id'] = 0;
        }
    }

/**
 * Residence town's ID.
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
 * @param int $town_id Residence town's ID.
 */
    public function setTownId($town_id)
    {
        $this->data['town_id'] = (int) $town_id;
    }

/**
 * Percentage of experience lost after dead.
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
 * @param int $loss_experience Percentage of experience lost after dead.
 */
    public function setLossExperience($loss_experience)
    {
        $this->data['loss_experience'] = (int) $loss_experience;
    }

/**
 * Percentage of used mana lost after dead.
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
 * @param int $loss_mana Percentage of used mana lost after dead.
 */
    public function setLossMana($loss_mana)
    {
        $this->data['loss_mana'] = (int) $loss_mana;
    }

/**
 * Percentage of skills lost after dead.
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
 * @param int $loss_skills Percentage of skills lost after dead.
 */
    public function setLossSkills($loss_skills)
    {
        $this->data['loss_skills'] = (int) $loss_skills;
    }

/**
 * Reads custom field.
 * 
 * Reads field by it's name. Can read any field of given record that exists in database.
 * 
 * <p>
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * </p>
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param string $field Field name.
 * @return string Field value.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $value = $this->db->SQLquery('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetch();
        return $value[$field];
    }

/**
 * Writes custom field.
 * 
 * Write field by it's name. Can write any field of given record that exists in database.
 * 
 * <p>
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * </p>
 * 
 * <p>
 * Note: Make sure that you pass $value argument of correct type. This method determinates whether to quote field value. It is safe - it makes you sure that no unproper queries that could lead to SQL injection will be executed, but it can make your code working wrong way. For example: $object->setCustomField('foo', '1'); will quote 1 as as string ('1') instead of passing it as a integer.
 * </p>
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param string $field Field name.
 * @param mixed $value Field value.
 * @throws E_OTS_NotLoaded If player is not loaded.
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

        $this->db->SQLquery('UPDATE ' . $this->db->tableName('players') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
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
 * Deletes item with contained items.
 * 
 * @version 0.0.3
 * @since 0.0.3
 * @param int $sid Item unique player's ID.
 */
    private function deleteItem($sid)
    {
        // deletes all sub-items
        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $sid)->fetchAll() as $item)
        {
            $this->deleteItem($item['sid']);
        }

        // deletes item
        $this->db->SQLquery('DELETE FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('sid') . ' = ' . $sid);
    }

/**
 * Returns items tree from given slot.
 * 
 * Note: OTS_Player class has no information about item types. It returns all items as OTS_Item, unless they have any contained items in database, so empty container will be instanced as OTS_Item object, not OTS_Container.
 * 
 * @version 0.0.4
 * @since 0.0.3
 * @param int $slot Slot to get items.
 * @return OTS_Item|null Item in given slot (items tree if in given slot there is a container). If there is no item in slot then null value will be returned.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getSlot($slot)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // loads current item
        $item = $this->db->SQLquery('SELECT ' . $this->db->fieldName('itemtype') . ', ' . $this->db->fieldName('sid') . ', ' . $this->db->fieldName('count') . ', ' . $this->db->fieldName('attributes') . ' FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName($slot > POT::SLOT_AMMO ? 'sid' : 'pid') . ' = ' . $slot)->fetch();

        if( empty($item) )
        {
            return null;
        }

        // checks if there are any items under current one
        $items = array();
        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $item['sid'])->fetchAll() as $sub)
        {
            $items[] = $this->getSlot($sub['sid']);
        }

        // checks if current item is a container
        if( empty($items) )
        {
            $slot = new OTS_Item($item['itemtype']);
        }
        else
        {
            $slot = new OTS_Container($item['itemtype']);

            // puts items into container
            foreach($items as $sub)
            {
                $slot->addItem($sub);
            }
        }

        $slot->setCount($item['count']);
        $slot->setAttributes($item['attributes']);

        return $slot;
    }

/**
 * Sets slot content.
 * 
 * @version 0.0.4
 * @since 0.0.3
 * @param int $slot Slot to save items.
 * @param OTS_Item $item Item (can be a container with content) for given slot. Leave this parameter blank to clear slot.
 * @param int $pid Deprecated, not used anymore.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function setSlot($slot, OTS_Item $item = null, $pid = 0)
    {
        static $sid;

        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // clears current slot
        if($slot <= POT::SLOT_AMMO)
        {
            $id = $this->db->SQLquery('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $slot)->fetch();
            $this->deleteItem( (int) $id['sid']);
        }

        // checks if there is any item to insert
        if( isset($item) )
        {
            // current maximum sid (over slot sids)
            if( !isset($sid) )
            {
                $sid = $this->db->SQLquery('SELECT MAX(' . $this->db->fieldName('sid') . ') AS `sid` FROM ' . $this->db->tableName('player_items') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'])->fetch();
                $sid = $sid['sid'] > POT::SLOT_AMMO ? $sid['sid'] : POT::SLOT_AMMO;
            }

            $sid++;

            // inserts given item
            $this->db->SQLquery('INSERT INTO ' . $this->db->tableName('player_items') . ' (' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('sid') . ', ' . $this->db->fieldName('pid') . ', ' . $this->db->fieldName('itemtype') . ', ' . $this->db->fieldName('count') . ', ' . $this->db->fieldName('attributes') . ') VALUES (' . $this->data['id'] . ', ' . $sid . ', ' . $slot . ', ' . $item->getId() . ', ' . $item->getCount() . ', ' . $this->db->SQLquote( $item->getAttributes() ) . ')');

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
 * @version 0.0.4
 * @since 0.0.3
 * @param int $sid Depot item unique player's ID.
 */
    private function deleteDepot($sid)
    {
        // deletes all sub-items
        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $sid)->fetchAll() as $item)
        {
            $this->deleteDepot($item['sid']);
        }

        // deletes item
        $this->db->SQLquery('DELETE FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('sid') . ' = ' . $sid);
    }

/**
 * Returns items tree from given depot.
 * 
 * Note: OTS_Player class has no information about item types. It returns all items as OTS_Item, unless they have any contained items in database, so empty container will be instanced as OTS_Item object, not OTS_Container.
 * 
 * @version 0.0.4
 * @since 0.0.3
 * @param int $depot Depot ID to get items.
 * @return OTS_Item|null Item in given depot (items tree if in given depot there is a container). If there is no item in depot then null value will be returned.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function getDepot($depot)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // loads current item
        $item = $this->db->SQLquery('SELECT ' . $this->db->fieldName('itemtype') . ', ' . $this->db->fieldName('sid') . ', ' . $this->db->fieldName('count') . ', ' . $this->db->fieldName('attributes') . ' FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName($depot > POT::DEPOT_SID_FIRST ? 'sid' : 'pid') . ' = ' . $depot)->fetch();

        if( empty($item) )
        {
            return null;
        }

        // checks if there are any items under current one
        $items = array();
        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $item['sid'])->fetchAll() as $sub)
        {
            $items[] = $this->getDepot($sub['sid']);
        }

        // checks if current item is a container
        if( empty($items) )
        {
            $depot = new OTS_Item($item['itemtype']);
        }
        else
        {
            $depot = new OTS_Container($item['itemtype']);

            // puts items into container
            foreach($items as $sub)
            {
                $depot->addItem($sub);
            }
        }

        $depot->setCount($item['count']);
        $depot->setAttributes($item['attributes']);

        return $depot;
    }

/**
 * Sets depot content.
 * 
 * @version 0.0.4
 * @since 0.0.3
 * @param int $depot Depot ID to save items.
 * @param OTS_Item $item Item (can be a container with content) for given depot. Leave this parameter blank to clear depot.
 * @param int $pid Deprecated, not used anymore.
 * @param int $depot_id Internal, for further use.
 * @throws E_OTS_NotLoaded If player is not loaded.
 */
    public function setDepot($depot, OTS_Item $item = null, $pid = 0, $depot_id = 0)
    {
        static $sid;

        // if no depot_id is specified then it is same as depot slot
        if($depot_id == 0)
        {
            $depot_id = $depot;
        }

        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // clears current depot
        if($depot <= POT::DEPOT_SID_FIRST)
        {
            $id = $this->db->SQLquery('SELECT ' . $this->db->fieldName('sid') . ' FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'] . ' AND ' . $this->db->fieldName('pid') . ' = ' . $depot)->fetch();
            $this->deleteDepot( (int) $id['sid']);
        }

        // checks if there is any item to insert
        if( isset($item) )
        {
            // current maximum sid (over depot sids)
            if( !isset($sid) )
            {
                $sid = $this->db->SQLquery('SELECT MAX(' . $this->db->fieldName('sid') . ') AS `sid` FROM ' . $this->db->tableName('player_depotitems') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->data['id'])->fetch();
                $sid = $sid['sid'] > POT::DEPOT_SID_FIRST ? $sid['sid'] : POT::DEPOT_SID_FIRST;
            }

            $sid++;

            // inserts given item
            $this->db->SQLquery('INSERT INTO ' . $this->db->tableName('player_depotitems') . ' (' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('depot_id') . ', ' . $this->db->fieldName('sid') . ', ' . $this->db->fieldName('pid') . ', ' . $this->db->fieldName('itemtype') . ', ' . $this->db->fieldName('count') . ', ' . $this->db->fieldName('attributes') . ') VALUES (' . $this->data['id'] . ', ' . $depot_id . ', ' . $sid . ', ' . $depot . ', ' . $item->getId() . ', ' . $item->getCount() . ', ' . $this->db->SQLquote( $item->getAttributes() ) . ')');

            // checks if this is container
            if($item instanceof OTS_Container)
            {
                $pid = $sid;

                // inserts all contained items
                foreach($item as $sub)
                {
                    $this->setDepot($pid, $sub, 0, $depot_id);
                }
            }
        }

        // clears $sid for next public call
        if($depot <= POT::DEPOT_SID_FIRST)
        {
            $sid = null;
        }
    }
}

/**#@-*/

?>
