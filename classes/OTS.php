<?php

/**#@+
 * @version 0.0.1
 */

/**
 * This file contains main toolkit class. Please read README file for quick startup guide and/or tutorials for more info.
 * 
 * @package POT
 * @version 0.0.1+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Main POT class.
 * 
 * @package POT
 * @version 0.0.1+SVN
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
 * Female gender.
 */
    const SEX_FEMALE = 0;
/**
 * Male gender.
 */
    const SEX_MALE = 1;

/**
 * None vocation.
 */
    const VOCATION_NONE = 0;
/**
 * Sorcerer.
 */
    const VOCATION_SORCERER = 1;
/**
 * Druid.
 */
    const VOCATION_DRUID = 2;
/**
 * Paladin.
 */
    const VOCATION_PALADIN = 3;
/**
 * Knight.
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
 * @version 0.0.1+SVN
 * @since 0.0.1+SVN
 */
    const SKILL_FIST = 0;
/**
 * Club fighting.
 * 
 * @version 0.0.1+SVN
 * @since 0.0.1+SVN
 */
    const SKILL_CLUB = 1;
/**
 * Sword fighting.
 * 
 * @version 0.0.1+SVN
 * @since 0.0.1+SVN
 */
    const SKILL_SWORD = 2;
/**
 * Axe fighting.
 * 
 * @version 0.0.1+SVN
 * @since 0.0.1+SVN
 */
    const SKILL_AXE = 3;
/**
 * Distance fighting.
 * 
 * @version 0.0.1+SVN
 * @since 0.0.1+SVN
 */
    const SKILL_DISTANCE = 4;
/**
 * Shielding.
 * 
 * @version 0.0.1+SVN
 * @since 0.0.1+SVN
 */
    const SKILL_SHIELDING = 5;
/**
 * Fishing.
 * 
 * @version 0.0.1+SVN
 * @since 0.0.1+SVN
 */
    const SKILL_FISHING = 6;

/**
 * Singleton.
 * 
 * @return POT Global POD class instance.
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
 * @see POT::getInstance();
 * @internal
 */
    public function __construct()
    {
        // default POT directory
        $this->path = dirname(__FILE__) . '/';
    }

/**
 * Loads POT class file.
 * 
 * Runtime class loading on demand - usefull for __autoload() function.
 * 
 * @param string $class Class name.
 * @throws Exception When give class is not POT toolkit class.
 * @example examples/autoload.php autoload.php
 */
    public function loadClass($class)
    {
        if( preg_match('/^I?OTS_/', $class) == 0)
        {
            throw new Exception('\'' . $class . '\' is not part of PHP OTS Toolkit.');
        }

        include_once($this->path . $class . '.php');
    }

/**
 * Database connection.
 * 
 * OTServ database connection object.
 * 
 * @var IOTS_DB
 */
    private $db;

/**
 * Connects to database.
 * 
 * Creates OTServ database connection object.
 * 
 * <p>
 * First parameter is one of database driver constants values. Currently MySQL and SQLite drivers are supported. XML is not planned.<br />
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
                throw new Exception('You must specify datbase driver to connect with.');
            }
        }
        unset($params['driver']);

        // checks driver support
        if($driver != self::DB_MYSQL && $driver != self::DB_SQLITE)
        {
            throw new Exception('Driver \'' . $driver . '\' is not supported.');
        }

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
        }
    }

/**
 * Creates OTServ DAO class instance.
 * 
 * Currently it means Account, or Player object.
 * 
 * @param string $class Class name.
 * @return IOTS_DAO OTServ database object.
 */
    public function createObject($class)
    {
        $class = 'OTS_' . $class;
        return new $class($this->db);
    }
}

/**#@-*/

?>
