<?php

/**#@+
 * @version 0.0.1
 * @since 0.0.1
 */

/**
 * This file contains main toolkit class. Please read README file for quick startup guide and/or tutorials for more info.
 * 
 * @package POT
 * @version 0.1.0
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 * @todo 0.1.1: Support for call constructors with ID/name parameter for automatic pre-load for data.
 * @todo 0.1.2: OTAdmin protocol.
 * @todo 0.1.3: SOAP interface for remote controll.
 * @todo 0.2.0: Implement OutOfBoundsException instead of mixed results types.
 * @todo 0.2.0: Implement NetworkMessage.
 * @todo 1.0.0: Unify *List and *_List naming (probably into *List).
 * @todo 1.0.0: Deprecations cleanup.
 * @todo 1.0.0: Complete phpUnit test.
 * @todo 1.0.0: More detailed documentation and tutorials, also update examples and tutorials.
 * @todo 1.0.0: Main POT class as database instance.
 * @todo 1.0.0: PHAR and PHK packages.
 * @todo 2.0.0: Code as C++ extension (as an alternative to pure PHP library which of course would still be available).
 */

/**
 * Main POT class.
 * 
 * @package POT
 * @version 0.1.0
 */
class POT
{
/**
 * MySQL driver.
 */
    const DB_MYSQL = 1;
/**
 * SQLite driver.
 */
    const DB_SQLITE = 2;
/**
 * PostgreSQL driver.
 * 
 * @version 0.0.4
 * @since 0.0.4
 */
    const DB_PGSQL = 3;
/**
 * ODBC driver.
 * 
 * @version 0.0.4
 * @since 0.0.4
 */
    const DB_ODBC = 4;

/**
 * Female gender.
 */
    const SEX_FEMALE = 0;
/**
 * Male gender.
 */
    const SEX_MALE = 1;

/**
 * None vocation.
 * 
 * @deprecated 0.0.5 Vocations are now loaded dynamicly from vocations.xml file.
 */
    const VOCATION_NONE = 0;
/**
 * Sorcerer.
 * 
 * @deprecated 0.0.5 Vocations are now loaded dynamicly from vocations.xml file.
 */
    const VOCATION_SORCERER = 1;
/**
 * Druid.
 * 
 * @deprecated 0.0.5 Vocations are now loaded dynamicly from vocations.xml file.
 */
    const VOCATION_DRUID = 2;
/**
 * Paladin.
 * 
 * @deprecated 0.0.5 Vocations are now loaded dynamicly from vocations.xml file.
 */
    const VOCATION_PALADIN = 3;
/**
 * Knight.
 * 
 * @deprecated 0.0.5 Vocations are now loaded dynamicly from vocations.xml file.
 */
    const VOCATION_KNIGHT = 4;

/**
 * North.
 */
    const DIRECTION_NORTH = 0;
/**
 * East.
 */
    const DIRECTION_EAST = 1;
/**
 * South.
 */
    const DIRECTION_SOUTH = 2;
/**
 * West.
 */
    const DIRECTION_WEST = 3;

/**
 * Fist fighting.
 * 
 * @version 0.0.2
 * @since 0.0.2
 */
    const SKILL_FIST = 0;
/**
 * Club fighting.
 * 
 * @version 0.0.2
 * @since 0.0.2
 */
    const SKILL_CLUB = 1;
/**
 * Sword fighting.
 * 
 * @version 0.0.2
 * @since 0.0.2
 */
    const SKILL_SWORD = 2;
/**
 * Axe fighting.
 * 
 * @version 0.0.2
 * @since 0.0.2
 */
    const SKILL_AXE = 3;
/**
 * Distance fighting.
 * 
 * @version 0.0.2
 * @since 0.0.2
 */
    const SKILL_DISTANCE = 4;
/**
 * Shielding.
 * 
 * @version 0.0.2
 * @since 0.0.2
 */
    const SKILL_SHIELDING = 5;
/**
 * Fishing.
 * 
 * @version 0.0.2
 * @since 0.0.2
 */
    const SKILL_FISHING = 6;

/**
 * Head slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_HEAD = 1;
/**
 * Necklace slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_NECKLACE = 2;
/**
 * Backpack slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_BACKPACK = 3;
/**
 * Armor slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_ARMOR = 4;
/**
 * Right hand slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_RIGHT = 5;
/**
 * Left hand slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_LEFT = 6;
/**
 * Legs slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_LEGS = 7;
/**
 * Boots slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_FEET = 8;
/**
 * Ring slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_RING = 9;
/**
 * Ammunition slot.
 * 
 * @version 0.0.3
 * @since 0.0.3
 */
    const SLOT_AMMO = 10;

/**
 * First depot item sid.
 * 
 * @version 0.0.4
 * @since 0.0.4
 */
    const DEPOT_SID_FIRST = 100;

/**
 * IP ban.
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    const BAN_IP = 1;
/**
 * Player ban.
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    const BAN_PLAYER = 2;
/**
 * Account ban.
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    const BAN_ACCOUNT = 3;

/**
 * Ascencind sorting order.
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    const ORDER_ASC = 1;
/**
 * Descending sorting order.
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    const ORDER_DESC = 2;

/**
 * Rune spell.
 * 
 * @version 0.0.7
 * @since 0.0.7
 * @deprecated 0.1.0 Use OTS_SpellsList::SPELL_RUNE.
 */
    const SPELL_RUNE = 0;
/**
 * Instant spell.
 * 
 * @version 0.0.7
 * @since 0.0.7
 * @deprecated 0.1.0 Use OTS_SpellsList::SPELL_INSTANT.
 */
    const SPELL_INSTANT = 1;
/**
 * Conjure spell.
 * 
 * @version 0.0.7
 * @since 0.0.7
 * @deprecated 0.1.0 Use OTS_SpellsList::SPELL_CONJURE.
 */
    const SPELL_CONJURE = 2;

/**
 * Singleton.
 * 
 * @return POT Global POT class instance.
 */
    public static function getInstance()
    {
        static $instance;

        // creates new instance
        if( !isset($instance) )
        {
            $instance = new self;
        }

        return $instance;
    }

/**
 * POT classes directory.
 * 
 * Directory path to POT files.
 * 
 * @var string
 * @see POT::setPOTPath()
 */
    private $path = '';

/**
 * Set POT directory.
 * 
 * Use this method if you keep your POT package in different directory then this file.
 * 
 * @param string $path POT files path.
 * @example examples/fakeroot.php fakeroot.php
 */
    public function setPOTPath($path)
    {
        $this->path = str_replace('\\', '/', $path);

        // appends ending slash to directory path
        if( substr($this->path, -1) != '/')
        {
            $this->path .= '/';
        }
    }

/**
 * Class initialization tools.
 * 
 * Never create instance of this class by yourself! Use POT::getInstance()!
 * 
 * @version 0.0.3
 * @see POT::getInstance()
 * @internal
 */
    private function __construct()
    {
        // default POT directory
        $this->path = dirname(__FILE__) . '/';
        // registers POT autoload mechanism
        spl_autoload_register( array($this, 'loadClass') );
    }

/**
 * Loads POT class file.
 * 
 * Runtime class loading on demand - usefull for __autoload() function.
 * 
 * <p>
 * Note: Since 0.0.2 version this function is suitable for spl_autoload_register().
 * </p>
 * 
 * <p>
 * Note: Since 0.0.3 version this function handles also exceptions.
 * </p>
 * 
 * @version 0.0.3
 * @param string $class Class name.
 */
    public function loadClass($class)
    {
        if( preg_match('/^(I|E_)?OTS_/', $class) > 0)
        {
            include_once($this->path . $class . '.php');
        }
    }

/**
 * Database connection.
 * 
 * OTServ database connection object.
 * 
 * @var PDO
 */
    private $db;

/**
 * Connects to database.
 * 
 * Creates OTServ database connection object.
 * 
 * <p>
 * First parameter is one of database driver constants values. Currently MySQL, SQLite, PostgreSQL and ODBC drivers are supported.<br />
 * This parameter can be null, then you have to specify <var>'driver'</var> parameter.<br />
 * Such way is comfortable to store entire database configuration in one array and possibly runtime evaluation and/or configuration file saving.<br />
 * </p>
 * 
 * <p>
 * For parameters list see driver documentation. Common parameters for all drivers are:
 * </p>
 * 
 * - <var>driver</var> - optional, specifies driver, aplies when <var>$driver</var> method parameter is <i>null</i>
 * - <var>prefix</var> - optional, prefix for database tables, use if you have more then one OTServ installed on one database.
 * 
 * @version 0.0.4
 * @param int|null $driver Database driver type.
 * @param array $params Connection info.
 * @throws Exception When driver is not supported.
 * @example examples/connect.php connect.php
 */
    public function connect($driver, $params)
    {
        // $params['driver'] option instead of $driver
        if( !isset($driver) )
        {
            if( isset($params['driver']) )
            {
                $driver = $params['driver'];
            }
            else
            {
                throw new Exception('You must specify database driver to connect with.');
            }
        }
        unset($params['driver']);

        // switch() structure provides us further flexibility
        switch($driver)
        {
            // MySQL database
            case self::DB_MYSQL:
                $this->db = new OTS_DB_MySQL($params);
                break;

            // SQLite database
            case self::DB_SQLITE:
                $this->db = new OTS_DB_SQLite($params);
                break;

            // SQLite database
            case self::DB_PGSQL:
                $this->db = new OTS_DB_PostgreSQL($params);
                break;

            // SQLite database
            case self::DB_ODBC:
                $this->db = new OTS_DB_ODBC($params);
                break;

            // unsupported driver
            default:
                throw new Exception('Driver \'' . $driver . '\' is not supported.');
        }
    }

/**
 * Creates OTServ DAO class instance.
 * 
 * @version 0.1.0
 * @param string $class Class name.
 * @return IOTS_DAO OTServ database object.
 * @deprecated 0.1.0 Create objects directly from now.
 */
    public function createObject($class)
    {
        $class = 'OTS_' . $class;
        return new $class();
    }

/**
 * Queries server status.
 * 
 * Sends 'info' packet to OTS server and return output.
 * 
 * @version 0.0.2
 * @since 0.0.2
 * @param string $server Server IP/domain.
 * @param int $port OTServ port.
 * @return OTS_InfoRespond|bool Respond content document (false when server is offline).
 * @example examples/info.php
 */
    public function serverStatus($server, $port)
    {
        // connects to server
        // gives maximum 5 seconds to connect
        $socket = fsockopen($server, $port, $error, $message, 5);

        // if connected then checking statistics
        if($socket)
        {
            // sets 5 second timeout for reading and writing
            stream_set_timeout($socket, 5);

            // sends packet with request
            // 06 - length of packet, 255, 255 is the comamnd identifier, 'info' is a request
            fwrite($socket, chr(6) . chr(0) . chr(255) . chr(255) . 'info');

            // reads respond
            $data = stream_get_contents($socket);

            // closing connection to current server
            fclose($socket);

            // sometimes server returns empty info
            if( empty($data) )
            {
                // returns offline state
                return false;
            }

            // loads respond XML
            $info = new OTS_InfoRespond();
            $info->loadXML($data);
            return $info;
        }
        // returns offline state
        else
        {
            return false;
        }
    }

/**
 * Returns database connection handle.
 * 
 * <p>
 * At all you shouldn't use this method and work with database using POT classes, but it may be sometime necessary to use direct database access (mainly until POT won't provide many important features).
 * </p>
 * 
 * <p>
 * It is also important as serialised objects after unserialisation needs to be re-initialised with database connection.
 * </p>
 * 
 * @version 0.0.4
 * @since 0.0.4
 * @return PDO Database connection handle.
 * @internal You should not call this function in your external code without real need.
 */
    public function getDBHandle()
    {
        return $this->db;
    }

/**
 * Bans given IP number.
 * 
 * Adds IP/mask ban. You can call this function with only one parameter to ban only given IP address without expiration.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @param string $ip IP to ban.
 * @param string $mask Mask for ban (by default bans only given IP).
 * @param int $time Time for time until expires (0 - forever).
 */
    public function banIP($ip, $mask = '255.255.255.255', $time = 0)
    {
        // long2ip( ip2long('255.255.255.255') ) != '255.255.255.255' -.-'
        // it's because that PHP integer types are signed
        if($ip == '255.255.255.255')
        {
            $ip = 4294967295;
        }
        else
        {
            $ip = sprintf('%u', ip2long($ip) );
        }

        if($mask == '255.255.255.255')
        {
            $mask = 4294967295;
        }
        else
        {
            $mask = sprintf('%u', ip2long($mask) );
        }

        $this->db->query('INSERT INTO ' . $this->db->tableName('bans') . ' (' . $this->db->fieldName('type') . ', ' . $this->db->fieldName('ip') . ', ' . $this->db->fieldName('mask') . ', ' . $this->db->fieldName('time') . ') VALUES (' . self::BAN_IP . ', ' . $ip . ', ' . $mask . ', ' . (int) $time . ')');
    }

/**
 * Deletes ban from given IP number.
 * 
 * Removes given IP/mask ban.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @param string $ip IP to ban.
 * @param string $mask Mask for ban (by default 255.255.255.255).
 */
    public function unbanIP($ip, $mask = '255.255.255.255')
    {
        // long2ip( ip2long('255.255.255.255') ) != '255.255.255.255' -.-'
        // it's because that PHP integer types are signed
        if($ip == '255.255.255.255')
        {
            $ip = 4294967295;
        }
        else
        {
            $ip = sprintf('%u', ip2long($ip) );
        }

        if($mask == '255.255.255.255')
        {
            $mask = 4294967295;
        }
        else
        {
            $mask = sprintf('%u', ip2long($mask) );
        }

        $this->db->query('DELETE FROM ' . $this->db->tableName('bans') . ' WHERE ' . $this->db->fieldName('type') . ' = ' . self::BAN_IP . ' AND ' . $this->db->fieldName('ip') . ' = ' . $ip . ' AND ' . $this->db->fieldName('mask') . ' = ' . $mask);
    }

/**
 * Checks if given IP is banned.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @param string $ip IP to ban.
 * @return bool True if IP number is banned, false otherwise.
 */
    public function isIPBanned($ip)
    {
        // long2ip( ip2long('255.255.255.255') ) != '255.255.255.255' -.-'
        // it's because that PHP integer types are signed
        if($ip == '255.255.255.255')
        {
            $ip = 4294967295;
        }
        else
        {
            $ip = sprintf('%u', ip2long($ip) );
        }

        $ban = $this->db->query('SELECT COUNT(' . $this->db->fieldName('type') . ') AS ' . $this->db->fieldName('count') . ' FROM ' . $this->db->tableName('bans') . ' WHERE ' . $this->db->fieldName('ip') . ' & ' . $this->db->fieldName('mask') . ' = ' . $ip . ' & ' . $this->db->fieldName('mask') . ' AND (' . $this->db->fieldName('time') . ' > ' . time() . ' OR ' . $this->db->fieldName('time') . ' = 0) AND ' . $this->db->fieldName('type') . ' = ' . self::BAN_IP)->fetch();
        return $ban['count'] > 0;
    }

/**
 * Creates lists filter.
 * 
 * @version 0.1.0
 * @since 0.0.5
 * @return OTS_SQLFilter Filter object.
 * @deprecated 0.1.0 Create objects directly from now.
 */
    public function createFilter()
    {
        return new OTS_SQLFilter();
    }

/**
 * List of vocations.
 * 
 * @version 0.1.0
 * @since 0.0.5
 * @var OTS_VocationsList
 */
    private $vocations;

/**
 * Loads vocations list.
 * 
 * @version 0.1.0
 * @since 0.0.5
 * @param string $file vocations.xml file location.
 */
    public function loadVocations($file)
    {
        // loads DOM document
        $this->vocations = new OTS_VocationsList($file);
    }

/**
 * Checks if vocations are loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return bool True if vocations are loaded.
 */
    public function areVocationsLoaded()
    {
        return isset($this->vocations);
    }

/**
 * Unloads vocations list.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function unloadVocations()
    {
        unset($this->vocations);
    }

/**
 * Returns vocations list object.
 * 
 * @version 0.1.0
 * @since 0.0.5
 * @return OTS_VocationsList List of vocations.
 * @throws E_OTS_NotLoaded If vocations list is not loaded.
 */
    public function getVocationsList()
    {
        if( isset($this->vocations) )
        {
            return $this->vocations;
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns vocation's ID.
 * 
 * @version 0.1.0
 * @since 0.0.5
 * @param string $name Vocation.
 * @return int|bool ID (false if not found).
 * @throws E_OTS_NotLoaded If vocations list is not loaded.
 */
    public function getVocationId($name)
    {
        if( isset($this->vocations) )
        {
            return $this->vocations->getVocationId($name);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns name of given vocation's ID.
 * 
 * @version 0.1.0
 * @since 0.0.5
 * @param int $id Vocation ID.
 * @return string|bool Name (false if not found).
 * @throws E_OTS_NotLoaded If vocations list is not loaded.
 */
    public function getVocationName($id)
    {
        if( isset($this->vocations) )
        {
            return $this->vocations->getVocationName($id);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * List of loaded monsters.
 * 
 * @version 0.1.0
 * @since 0.0.6
 * @var OTS_MonstersList
 */
    private $monsters;

/**
 * Loads monsters mapping file.
 * 
 * @version 0.1.0
 * @since 0.0.6
 * @param string $path Monsters directory.
 */
    public function loadMonsters($path)
    {
        $this->monsters = new OTS_MonstersList($path);
    }

/**
 * Checks if monsters are loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return bool True if monsters are loaded.
 */
    public function areMonstersLoaded()
    {
        return isset($this->monsters);
    }

/**
 * Unloads monsters list.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function unloadMonsters()
    {
        unset($this->monsters);
    }

/**
 * Returns list of laoded monsters.
 * 
 * @version 0.1.0
 * @since 0.0.6
 * @return OTS_MonstersList List of monsters.
 * @throws E_OTS_NotLoaded If monsters list is not loaded.
 */
    public function getMonstersList()
    {
        if( isset($this->monsters) )
        {
            return $this->monsters;
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns loaded data of given monster.
 * 
 * @version 0.1.0
 * @since 0.0.6
 * @param string $name Monster name.
 * @return OTS_Monster|null Monster data (null if not exists).
 * @throws E_OTS_NotLoaded If monsters list is not loaded.
 */
    public function getMonster($name)
    {
        if( isset($this->monsters) )
        {
            return $this->monsters->getMonster($name);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Spells list.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var OTS_SpellsList
 */
    private $spells;

/**
 * Loads spells list.
 * 
 * @version 0.1.0
 * @since 0.0.7
 * @param string $file Spells file name.
 */
    public function loadSpells($file)
    {
        $this->spells = new OTS_SpellsList($file);
    }

/**
 * Checks if spells are loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return bool True if spells are loaded.
 */
    public function areSpellsLoaded()
    {
        return isset($this->spells);
    }

/**
 * Unloads spells list.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function unloadSpells()
    {
        unset($this->spells);
    }

/**
 * Returns list of laoded spells.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return OTS_SpellsList List of spells.
 * @throws E_OTS_NotLoaded If spells list is not loaded.
 */
    public function getSpellsList()
    {
        if( isset($this->spells) )
        {
            return $this->spells;
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns list of runes.
 * 
 * @version 0.1.0
 * @since 0.0.7
 * @return array List of rune names.
 * @throws E_OTS_NotLoaded If spells list is not loaded.
 */
    public function getRunesList()
    {
        if( isset($this->spells) )
        {
            return $this->spells->getRunesList();
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns given rune spell.
 * 
 * @version 0.1.0
 * @since 0.0.7
 * @param string $name Rune name.
 * @return OTS_Spell|null Rune spell wrapper (null if rune does not exist).
 * @throws E_OTS_NotLoaded If spells list is not loaded.
 */
    public function getRune($name)
    {
        if( isset($this->spells) )
        {
            return $this->spells->getRune($name);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns list of instants.
 * 
 * @version 0.1.0
 * @since 0.0.7
 * @return array List of instant spells names.
 * @throws E_OTS_NotLoaded If spells list is not loaded.
 */
    public function getInstantsList()
    {
        if( isset($this->spells) )
        {
            return $this->spells->getInstantsList();
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns given instant spell.
 * 
 * @version 0.1.0
 * @since 0.0.7
 * @param string $name Spell name.
 * @return OTS_Spell|null Instant spell wrapper (null if rune does not exist).
 * @throws E_OTS_NotLoaded If spells list is not loaded.
 */
    public function getInstant($name)
    {
        if( isset($this->spells) )
        {
            return $this->spells->getInstant($name);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns list of conjure spells.
 * 
 * @version 0.1.0
 * @since 0.0.7
 * @return array List of conjure spells names.
 * @throws E_OTS_NotLoaded If spells list is not loaded.
 */
    public function getConjuresList()
    {
        if( isset($this->spells) )
        {
            return $this->spells->getConjuresList();
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns given conjure spell.
 * 
 * @version 0.1.0
 * @since 0.0.7
 * @param string $name Spell name.
 * @return OTS_Spell|null Conjure spell wrapper (null if rune does not exist).
 * @throws E_OTS_NotLoaded If spells list is not loaded.
 */
    public function getConjure($name)
    {
        if( isset($this->spells) )
        {
            return $this->spells->getConjure($name);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * List of loaded houses.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var OTS_HousesList
 */
    private $houses;

/**
 * Loads houses list file.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $path Houses file.
 */
    public function loadHouses($path)
    {
        $this->houses = new OTS_HousesList($path);
    }

/**
 * Checks if houses are loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return bool True if houses are loaded.
 */
    public function areHousesLoaded()
    {
        return isset($this->houses);
    }

/**
 * Unloads houses list.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function unloadHouses()
    {
        unset($this->houses);
    }

/**
 * Returns list of laoded houses.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return OTS_HousesList List of houses.
 * @throws E_OTS_NotLoaded If houses list is not loaded.
 */
    public function getHousesList()
    {
        if( isset($this->houses) )
        {
            return $this->houses;
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns house information.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param int $id House ID.
 * @return OTS_House|null House information wrapper (null if not found house).
 * @throws E_OTS_NotLoaded If houses list is not loaded.
 */
    public function getHouse($id)
    {
        if( isset($this->houses) )
        {
            return $this->houses->getHouse($id);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns ID of house with given name.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name House name.
 * @return int|bool House ID (false if not found).
 * @throws E_OTS_NotLoaded If houses list is not loaded.
 */
    public function getHouseId($name)
    {
        if( isset($this->houses) )
        {
            return $this->houses->getHouseId($name);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Cache handler for items loading.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var IOTS_FileCache
 */
    private $itemsCache;

/**
 * Presets cache handler for items loader.
 * 
 * @param IOTS_FileCache $cache Cache handler (skip this parameter to reset cache handler to null).
 */
    public function setItemsCache(IOTS_FileCache $cache = null)
    {
        $this->itemsCache = $cache;
    }

/**
 * List of loaded items.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var OTS_ItemsList
 */
    private $items;

/**
 * Loads items list.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $path Items information directory.
 */
    public function loadItems($path)
    {
        $this->items = new OTS_ItemsList();

        // sets items cache if any
        if( isset($this->itemsCache) )
        {
            $this->items->setCacheDriver($this->itemsCache);
        }

        $this->items->loadItems($path);
    }

/**
 * Checks if items are loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return bool True if items are loaded.
 */
    public function areItemsLoaded()
    {
        return isset($this->items);
    }

/**
 * Unloads items list.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function unloadItems()
    {
        unset($this->items);
    }

/**
 * Returns list of laoded items.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return OTS_ItemsList List of items.
 * @throws E_OTS_NotLoaded If items list is not loaded.
 */
    public function getItemsList()
    {
        if( isset($this->items) )
        {
            return $this->items;
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns item type instance.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param int $id Item type ID.
 * @return OTS_ItemType|null Item type object (null if not found).
 * @throws E_OTS_NotLoaded If items list is not loaded.
 */
    public function getItemType($id)
    {
        if( isset($this->items) )
        {
            return $this->items->getItemType($id);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns ID of type with given name.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name Item type name.
 * @return int|bool Type ID (false if not found).
 * @throws E_OTS_NotLoaded If items list is not loaded.
 */
    public function getItemTypeId($name)
    {
        if( isset($this->items) )
        {
            return $this->items->getItemTypeId($name);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Cache handler for OTBM loading.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var IOTS_FileCache
 */
    private $mapCache;

/**
 * Presets cache handler for OTBM loader.
 * 
 * @param IOTS_FileCache $cache Cache handler (skip this parameter to reset cache handler to null).
 */
    public function setMapCache(IOTS_FileCache $cache = null)
    {
        $this->mapCache = $cache;
    }

/**
 * Loaded map.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var OTS_OTBMFile
 */
    private $map;

/**
 * Loads OTBM map.
 * 
 * Note: This method will also load houses list associated with map.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $path Map file path.
 */
    public function loadMap($path)
    {
        $this->map = new OTS_OTBMFile();

        // sets items cache if any
        if( isset($this->mapCache) )
        {
            $this->map->setCacheDriver($this->mapCache);
        }

        $this->map->loadFile($path);
        $this->houses = $this->map->getHousesList();
    }

/**
 * Checks if OTBM is loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return bool True if map is loaded.
 */
    public function isMapLoaded()
    {
        return isset($this->map);
    }

/**
 * Unloads OTBM map.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function unloadMap()
    {
        unset($this->map);
    }

/**
 * Returns loaded map.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return OTS_OTBMFile Loaded OTBM file.
 * @throws E_OTS_NotLoaded If map is not loaded.
 */
    public function getMap()
    {
        if( isset($this->map) )
        {
            return $this->map;
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns map width.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int Map width.
 * @throws E_OTS_NotLoaded If map is not loaded.
 */
    public function getMapWidth()
    {
        if( isset($this->map) )
        {
            return $this->map->getWidth();
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns map height.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int Map height.
 * @throws E_OTS_NotLoaded If map is not loaded.
 */
    public function getMapHeight()
    {
        if( isset($this->map) )
        {
            return $this->map->getHeight();
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns map description.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return string Map description.
 * @throws E_OTS_NotLoaded If map is not loaded.
 */
    public function getMapDescription()
    {
        if( isset($this->map) )
        {
            return $this->map->getDescription();
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns town's ID.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name Town.
 * @return int|bool ID (false if not found).
 * @throws E_OTS_NotLoaded If map is not loaded.
 */
    public function getTownId($name)
    {
        if( isset($this->map) )
        {
            return $this->map->getTownId($name);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Returns name of given town's ID.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param int $id Town ID.
 * @return string|bool Name (false if not found).
 * @throws E_OTS_NotLoaded If map is not loaded.
 */
    public function getTownName($id)
    {
        if( isset($this->map) )
        {
            return $this->map->getTownName($id);
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }

/**
 * Display driver.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var IOTS_Display
 */
    private $display;

/**
 * Sets display driver.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param IOTS_Display $display Display driver.
 */
    public function setDisplayDriver(IOTS_Display $display)
    {
        $this->display = $display;
    }

/**
 * Checks if any display driver is loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return bool True if driver is loaded.
 */
    public function isDisplayDriverLoaded()
    {
        return isset($this->display);
    }

/**
 * Unloads display driver.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function unloadDisplayDriver()
    {
        unset($this->display);
    }

/**
 * Returns current display driver.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return IOTS_Display Current display driver.
 * @throws E_OTS_NotLoaded If display driver is not loaded.
 */
    public function getDisplayDriver()
    {
        if( isset($this->display) )
        {
            return $this->display;
        }
        else
        {
            throw new E_OTS_NotLoaded();
        }
    }
}

/*
 * This part is for PHP 5.0 compatibility.
 */

if( !defined('PDO_PARAM_STR') )
{
/**
 * Defines outdated PHP 5.0 constant on PHP 5.1 and newer versions so we can use it all over the POT.
 * 
 * @ignore
 * @version 0.0.7
 * @since 0.0.7
 * @deprecated Will be dropped after dropping IOTS_DB::SQLquote() since only this deprecated method uses it.
 */
    define('PDO_PARAM_STR', PDO::PARAM_STR);
}

if( !defined('PDO_ATTR_STATEMENT_CLASS') )
{
/**
 * Defines outdated PHP 5.0 constant on PHP 5.1 and newer versions so we can use it all over the POT.
 * 
 * @ignore
 * @version 0.0.7
 * @since 0.0.7
 * @deprecated Use PDO::ATTR_STATEMENT_CLASS, this is for PHP 5.0 compatibility.
 */
    define('PDO_ATTR_STATEMENT_CLASS', PDO::ATTR_STATEMENT_CLASS);
}

/**#@-*/

?>
