<?php

/**
 * This file contains main toolkit class. Please read README file for quick startup guide and/or tutorials for more info.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 * @todo future: Code as C++ extension (as an alternative to pure PHP library which of course would still be available).
 * @todo future: Implement POT namespace when it will be supported by PHP.
 * @todo future: Complete phpUnit test.
 * @todo 0.2.0: Use prepared statements.
 * @todo 1.0.0: Deprecations cleanup (0.x).
 * @todo 1.0.0: Replace all private members with procteted (left only reasonable private members).
 * @todo 1.0.0: E_* classes into *Exception, IOTS_* into *Interface, change POT classes prefix from OTS_* into OT_*, unify *List and *_List naming into *List, remove prefix from filenames.
 */

/**
 * Main POT class.
 * 
 * @package POT
 * @version 0.2.0+SVN
 */
class POT
{
/**
 * MySQL driver.
 * 
 * @version 0.0.1
 */
    const DB_MYSQL = 1;
/**
 * SQLite driver.
 * 
 * @version 0.0.1
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
 * 
 * @version 0.0.1
 */
    const SEX_FEMALE = 0;
/**
 * Male gender.
 * 
 * @version 0.0.1
 */
    const SEX_MALE = 1;

/**
 * North.
 * 
 * @version 0.0.1
 */
    const DIRECTION_NORTH = 0;
/**
 * East.
 * 
 * @version 0.0.1
 */
    const DIRECTION_EAST = 1;
/**
 * South.
 * 
 * @version 0.0.1
 */
    const DIRECTION_SOUTH = 2;
/**
 * West.
 * 
 * @version 0.0.1
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
 * Singleton.
 * 
 * <p>
 * This method return global instance of POT class.
 * </p>
 * 
 * @version 0.0.1
 * @return POT Global POT class instance.
 * @deprecated 0.2.0+SVN Just use static members instead of using singleton.
 */
    public static function getInstance()
    {
        static $instance;

        // creates new instance
        if( !isset($instance) )
        {
            $instance = new self();
        }

        return $instance;
    }

/**
 * POT classes directory.
 * 
 * <p>
 * Directory path to POT files.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @var string
 */
    protected static $path = '';

/**
 * Set POT directory.
 * 
 * <p>
 * Use this method if you keep your POT package in different directory then this file. Don't need to care about trailing directory separator - it will append it if needed.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @param string $path POT files path.
 * @example examples/fakeroot.php fakeroot.php
 * @tutorial POT/Basics.pkg#basics.fakeroot
 */
    public static function setPOTPath($path)
    {
        self::$path = str_replace('\\', '/', $path);

        // appends ending slash to directory path
        if( substr(self::$path, -1) != '/')
        {
            self::$path .= '/';
        }
    }

/**
 * Loads POT class file.
 * 
 * <p>
 * Runtime class loading on demand - usefull for autoloading functions. Usualy you don't need to call this method directly.
 * </p>
 * 
 * <p>
 * Note: Since 0.0.2 version this method is suitable for spl_autoload_register().
 * </p>
 * 
 * <p>
 * Note: Since 0.0.3 version this method handles also exception classes.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @param string $class Class name.
 */
    public static function loadClass($class)
    {
        if( preg_match('/^(I|E_)?OTS_/', $class) > 0)
        {
            include_once(self::$path . $class . '.php');
        }
    }

/**
 * Database connection.
 * 
 * <p>
 * OTServ database connection object.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @var PDO
 */
    protected static $db;

/**
 * Connects to database.
 * 
 * <p>
 * Creates OTServ database connection object.
 * </p>
 * 
 * <p>
 * First parameter is one of database driver constants values. Currently {@link OTS_DB_MySQL MySQL}, {@link OTS_DB_SQLite SQLite}, {@link OTS_DB_PostgreSQL PostgreSQL} and {@link OTS_DB_ODBC ODBC} drivers are supported. This parameter can be null, then you have to specify <var>'driver'</var> parameter. Such way is comfortable to store entire database configuration in one array and possibly runtime evaluation and/or configuration file saving.
 * </p>
 * 
 * <p>
 * For parameters list see driver documentation. Common parameters for all drivers are:
 * </p>
 * 
 * <ul>
 * <li><var>driver</var> - optional, specifies driver, aplies when <var>$driver</var> method parameter is <i>null</i>,</li>
 * <li><var>prefix</var> - optional, prefix for database tables, use if you have more then one OTServ installed on one database.</li>
 * </ul>
 * 
 * <p>
 * Note: Since 0.1.1 version this method throws {@link E_OTS_Generic E_OTS_Generic exceptions} instead of general Exception class objects. Since all exception classes are child classes of Exception class so your old code will still handle all exceptions.
 * </p>
 * 
 * <p>
 * Note: Since 0.1.2 version this method checks if PDO extension is loaded and if not, then throws LogicException. This exception class is part of SPL library and was introduced in PHP 5.1 so if you use PHP 5.0 you will need to load {@link compat.php compat.php library} first.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @param int|null $driver Database driver type.
 * @param array $params Connection info.
 * @throws E_OTS_Generic When driver is not supported or not supported.
 * @throws LogicException When PDO extension is not loaded.
 * @throws PDOException On PDO operation error.
 * @example examples/quickstart.php quickstart.php
 * @tutorial POT/Basics.pkg#basics.database
 */
    public static function connect($driver, array $params)
    {
        // checks if PDO extension is loaded
        if( !extension_loaded('PDO') )
        {
            throw new LogicException();
        }

        // $params['driver'] option instead of $driver
        if( !isset($driver) )
        {
            if( isset($params['driver']) )
            {
                $driver = $params['driver'];
            }
            else
            {
                throw new E_OTS_Generic(E_OTS_Generic::CONNECT_NO_DRIVER);
            }
        }
        unset($params['driver']);

        // switch() structure provides us further flexibility
        switch($driver)
        {
            // MySQL database
            case self::DB_MYSQL:
                self::$db = new OTS_DB_MySQL($params);
                break;

            // SQLite database
            case self::DB_SQLITE:
                self::$db = new OTS_DB_SQLite($params);
                break;

            // SQLite database
            case self::DB_PGSQL:
                self::$db = new OTS_DB_PostgreSQL($params);
                break;

            // SQLite database
            case self::DB_ODBC:
                self::$db = new OTS_DB_ODBC($params);
                break;

            // unsupported driver
            default:
                throw new E_OTS_Generic(E_OTS_Generic::CONNECT_INVALID_DRIVER);
        }

        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
 * <p>
 * Note that before you will be able to fetch connection handler, you have to connect to database using {@link POT::connect() connect() method}.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.4
 * @return PDO Database connection handle.
 */
    public static function getDBHandle()
    {
        return self::$db;
    }

/**
 * List of vocations.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.5
 * @var OTS_VocationsList
 */
    protected static $vocations;

/**
 * Loads vocations list.
 * 
 * <p>
 * This method loads vocations from given file. You can create local instances of vocations lists directly - calling this method will associate loaded list with POT class instance and will make it available everywhere in the code.
 * </p>
 * 
 * <p>
 * Note: Since 0.1.0 version this method loads instance of {@link OTS_VocationsList OTS_VocationsList} which you should fetch to get vocations info instead of calling POT class methods.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.5
 * @param string $file vocations.xml file location.
 * @throws DOMException On DOM operation error.
 */
    public static function loadVocations($file)
    {
        // loads DOM document
        self::$vocations = new OTS_VocationsList($file);
    }

/**
 * Checks if vocations are loaded.
 * 
 * <p>
 * You should use this method before fetching vocations list in new enviroment, or after loading new list to make sure it is loaded.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return bool True if vocations are loaded.
 */
    public static function areVocationsLoaded()
    {
        return isset(self::$vocations);
    }

/**
 * Unloads vocations list.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 */
    public static function unloadVocations()
    {
        unset(self::$vocations);
    }

/**
 * Returns vocations list object.
 * 
 * <p>
 * Note: Since 0.1.0 version this method returns loaded instance of {@link OTS_VocationsList OTS_VocationsList} instead of array. However {@link OTS_VocationsList OTS_VocationsList class} provides full array interface including Iterator, Countable and ArrayAccess interfaces so your code will work fine with it.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.5
 * @return OTS_VocationsList List of vocations.
 * @throws E_OTS_NotLoaded If vocations list is not loaded.
 */
    public static function getVocationsList()
    {
        if( isset(self::$vocations) )
        {
            return self::$vocations;
        }

        throw new E_OTS_NotLoaded();
    }

/**
 * List of loaded monsters.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @var OTS_MonstersList
 */
    protected static $monsters;

/**
 * Loads monsters mapping file.
 * 
 * <p>
 * This method loads monsters list from <var>monsters.xml</var> file in given directory. You can create local instances of monsters lists directly - calling this method will associate loaded list with POT class instance and will make it available everywhere in the code.
 * </p>
 * 
 * <p>
 * Note: Since 0.1.0 version this method loads instance of {@link OTS_MonstersList OTS_MonstersList} which you should fetch to get vocations info instead of calling POT class methods.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @param string $path Monsters directory.
 * @throws DOMException On DOM operation error.
 */
    public static function loadMonsters($path)
    {
        self::$monsters = new OTS_MonstersList($path);
    }

/**
 * Checks if monsters are loaded.
 * 
 * <p>
 * You should use this method before fetching monsters list in new enviroment, or after loading new list to make sure it is loaded.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return bool True if monsters are loaded.
 */
    public static function areMonstersLoaded()
    {
        return isset(self::$monsters);
    }

/**
 * Unloads monsters list.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 */
    public static function unloadMonsters()
    {
        unset(self::$monsters);
    }

/**
 * Returns list of laoded monsters.
 * 
 * <p>
 * Note: Since 0.1.0 version this method returns loaded instance of {@link OTS_MonstersList OTS_MonstersList} instead of array. However {@link OTS_MonstersList OTS_MonstersList class} provides full array interface including Iterator, Countable and ArrayAccess interfaces so your code will work fine with it.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.6
 * @return OTS_MonstersList List of monsters.
 * @throws E_OTS_NotLoaded If monsters list is not loaded.
 */
    public static function getMonstersList()
    {
        if( isset(self::$monsters) )
        {
            return self::$monsters;
        }

        throw new E_OTS_NotLoaded();
    }

/**
 * Spells list.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @var OTS_SpellsList
 */
    protected static $spells;

/**
 * Loads spells list.
 * 
 * <p>
 * This method loads spells list from given file. You can create local instances of spells lists directly - calling this method will associate loaded list with POT class instance and will make it available everywhere in the code.
 * </p>
 * 
 * <p>
 * Note: Since 0.1.0 version this method loads instance of {@link OTS_SpellsList OTS_SpellsList} which you should fetch to get vocations info instead of calling POT class methods.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.7
 * @param string $file Spells file name.
 * @throws DOMException On DOM operation error.
 */
    public static function loadSpells($file)
    {
        self::$spells = new OTS_SpellsList($file);
    }

/**
 * Checks if spells are loaded.
 * 
 * <p>
 * You should use this method before fetching spells list in new enviroment, or after loading new list to make sure it is loaded.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return bool True if spells are loaded.
 */
    public static function areSpellsLoaded()
    {
        return isset(self::$spells);
    }

/**
 * Unloads spells list.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 */
    public static function unloadSpells()
    {
        unset(self::$spells);
    }

/**
 * Returns list of laoded spells.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return OTS_SpellsList List of spells.
 * @throws E_OTS_NotLoaded If spells list is not loaded.
 */
    public static function getSpellsList()
    {
        if( isset(self::$spells) )
        {
            return self::$spells;
        }

        throw new E_OTS_NotLoaded();
    }

/**
 * List of loaded houses.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @var OTS_HousesList
 */
    protected static $houses;

/**
 * Loads houses list file.
 * 
 * <p>
 * This method loads houses list from given file. You can create local instances of houses lists directly - calling this method will associate loaded list with POT class instance and will make it available everywhere in the code.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param string $path Houses file.
 * @throws DOMException On DOM operation error.
 */
    public static function loadHouses($path)
    {
        self::$houses = new OTS_HousesList($path);
    }

/**
 * Checks if houses are loaded.
 * 
 * <p>
 * You should use this method before fetching houses list in new enviroment, or after loading new list to make sure it is loaded.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return bool True if houses are loaded.
 */
    public static function areHousesLoaded()
    {
        return isset(self::$houses);
    }

/**
 * Unloads houses list.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 */
    public static function unloadHouses()
    {
        unset(self::$houses);
    }

/**
 * Returns list of laoded houses.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return OTS_HousesList List of houses.
 * @throws E_OTS_NotLoaded If houses list is not loaded.
 */
    public static function getHousesList()
    {
        if( isset(self::$houses) )
        {
            return self::$houses;
        }

        throw new E_OTS_NotLoaded();
    }

/**
 * Cache handler for items loading.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @var IOTS_FileCache
 */
    protected static $itemsCache;

/**
 * Presets cache handler for items loader.
 * 
 * <p>
 * Use this method in order to preset cache handler for items list that you want to load into global POT instance. Note that this driver will be set for global resource only. If you will create local items list instances they won't use this driver automaticly.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @param IOTS_FileCache $cache Cache handler (skip this parameter to reset cache handler to null).
 */
    public static function setItemsCache(IOTS_FileCache $cache = null)
    {
        self::$itemsCache = $cache;
    }

/**
 * List of loaded items.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @var OTS_ItemsList
 */
    protected static $items;

/**
 * Loads items list.
 * 
 * <p>
 * This method loads items list from <var>items.xml</var> and <var>items.otb</var> files from given directory. You can create local instances of items lists directly - calling this method will associate loaded list with POT class instance and will make it available everywhere in the code.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param string $path Items information directory.
 * @throws E_OTS_FileLoaderError On binary file loading error.
 */
    public static function loadItems($path)
    {
        self::$items = new OTS_ItemsList();

        // sets items cache if any
        if( isset(self::$itemsCache) )
        {
            self::$items->setCacheDriver(self::$itemsCache);
        }

        self::$items->loadItems($path);
    }

/**
 * Checks if items are loaded.
 * 
 * <p>
 * You should use this method before fetching items list in new enviroment, or after loading new list to make sure it is loaded.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return bool True if items are loaded.
 */
    public static function areItemsLoaded()
    {
        return isset(self::$items);
    }

/**
 * Unloads items list.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 */
    public static function unloadItems()
    {
        unset(self::$items);
    }

/**
 * Returns list of laoded items.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return OTS_ItemsList List of items.
 * @throws E_OTS_NotLoaded If items list is not loaded.
 */
    public static function getItemsList()
    {
        if( isset(self::$items) )
        {
            return self::$items;
        }

        throw new E_OTS_NotLoaded();
    }

/**
 * Cache handler for OTBM loading.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @var IOTS_FileCache
 */
    protected static $mapCache;

/**
 * Presets cache handler for OTBM loader.
 * 
 * <p>
 * Use this method in order to preset cache handler for map that you want to load into global POT instance. Note that this driver will be set for global resource only. If you will create local OTBM instances they won't use this driver automaticly.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @param IOTS_FileCache $cache Cache handler (skip this parameter to reset cache handler to null).
 */
    public static function setMapCache(IOTS_FileCache $cache = null)
    {
        self::$mapCache = $cache;
    }

/**
 * Loaded map.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @var OTS_OTBMFile
 */
    protected static $map;

/**
 * Loads OTBM map.
 * 
 * <p>
 * This method loads OTBM map from given file. You can create local instances of maps directly - calling this method will associate loaded map with POT class instance and will make it available everywhere in the code.
 * </p>
 * 
 * <p>
 * Note: This method will also load houses list associated with map.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param string $path Map file path.
 */
    public static function loadMap($path)
    {
        self::$map = new OTS_OTBMFile();

        // sets items cache if any
        if( isset(self::$mapCache) )
        {
            self::$map->setCacheDriver(self::$mapCache);
        }

        self::$map->loadFile($path);
        self::$houses = self::$map->getHousesList();
    }

/**
 * Checks if OTBM is loaded.
 * 
 * <p>
 * You should use this method before fetching map information in new enviroment, or after loading new map to make sure it is loaded.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return bool True if map is loaded.
 */
    public static function isMapLoaded()
    {
        return isset(self::$map);
    }

/**
 * Unloads OTBM map.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 */
    public static function unloadMap()
    {
        unset(self::$map);
    }

/**
 * Returns loaded map.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return OTS_OTBMFile Loaded OTBM file.
 * @throws E_OTS_NotLoaded If map is not loaded.
 */
    public static function getMap()
    {
        if( isset(self::$map) )
        {
            return self::$map;
        }

        throw new E_OTS_NotLoaded();
    }

/**
 * Display driver.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @var IOTS_Display
 */
    protected static $display;

/**
 * Sets display driver for database-related resources.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param IOTS_Display $display Display driver.
 */
    public static function setDisplayDriver(IOTS_Display $display)
    {
        self::$display = $display;
    }

/**
 * Checks if any display driver is loaded.
 * 
 * <p>
 * This method is mostly used internaly by POT classes.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return bool True if driver is loaded.
 */
    public static function isDisplayDriverLoaded()
    {
        return isset(self::$display);
    }

/**
 * Unloads display driver.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 */
    public static function unloadDisplayDriver()
    {
        unset(self::$display);
    }

/**
 * Returns current display driver.
 * 
 * <p>
 * This method is mostly used internaly by POT classes.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return IOTS_Display Current display driver.
 * @throws E_OTS_NotLoaded If display driver is not loaded.
 */
    public static function getDisplayDriver()
    {
        if( isset(self::$display) )
        {
            return self::$display;
        }

        throw new E_OTS_NotLoaded();
    }

/**
 * Display driver for non-database resources.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is static.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.3
 * @var IOTS_DataDisplay
 */
    protected static $dataDisplay;

/**
 * Sets display driver for non-database resources.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.3
 * @param IOTS_DataDisplay $dataDisplay Display driver.
 */
    public static function setDataDisplayDriver(IOTS_DataDisplay $dataDisplay)
    {
        self::$dataDisplay = $dataDisplay;
    }

/**
 * Checks if any display driver for non-database resources is loaded.
 * 
 * <p>
 * This method is mostly used internaly by POT classes.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.3
 * @return bool True if driver is loaded.
 */
    public static function isDataDisplayDriverLoaded()
    {
        return isset(self::$dataDisplay);
    }

/**
 * Unloads display driver.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.3
 */
    public static function unloadDataDisplayDriver()
    {
        unset(self::$dataDisplay);
    }

/**
 * Returns current display driver.
 * 
 * <p>
 * This method is mostly used internaly by POT classes.
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.3
 * @return IOTS_DataDisplay Current display driver.
 * @throws E_OTS_NotLoaded If display driver is not loaded.
 */
    public static function getDataDisplayDriver()
    {
        if( isset(self::$dataDisplay) )
        {
            return self::$dataDisplay;
        }

        throw new E_OTS_NotLoaded();
    }

/**
 * Returns OTServ database information.
 * 
 * <p>
 * Especialy currently only schema version is available (via <i>'version'</i> key).
 * </p>
 * 
 * <p>
 * Note: Since 0.2.0+SVN this method is static.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.6
 * @return array List of schema settings.
 * @throws PDOException On PDO operation error.
 * @example examples/schema.php schema.php
 */
    public static function getSchemaInfo()
    {
        $info = array();

        // generates associative array
        foreach( self::$db->query('SELECT ' . self::$db->fieldName('name') . ', ' . self::$db->fieldName('value') . ' FROM ' . self::$db->tableName('schema_info') ) as $row)
        {
            $info[ $row['name'] ] = $row['version'];
        }

        return $info;
    }
}

// default POT directory
POT::setPOTPath( dirname(__FILE__) . '/');
// registers POT autoload mechanism
spl_autoload_register( array('POT', 'loadClass') );

?>
