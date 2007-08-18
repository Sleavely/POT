<?php

/**#@+
 * @version 0.0.1
 */

/**
 * This file contains main toolkit class. Please read README file for quick startup guide and/or tutorials for more info.
 * 
 * @package POT
 * @version 0.0.2
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Main POT class.
 * 
 * @package POT
 * @version 0.0.2
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
        // registers POT autoload mechanism
        spl_autoload_register( array($this, 'loadClass') );
    }

/**
 * Loads POT class file.
 * 
 * Runtime class loading on demand - usefull for __autoload() function.
 * 
 * Note: Since 0.0.2 version this function is suitable for spl_autoload_register().
 * 
 * @version 0.0.2
 * @param string $class Class name.
 * @example examples/autoload.php autoload.php
 */
    public function loadClass($class)
    {
        if( preg_match('/^I?OTS_/', $class) > 0)
        {
            include_once($this->path . $class . '.php');
        }
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
 * @param string $class Class name.
 * @return IOTS_DAO OTServ database object.
 */
    public function createObject($class)
    {
        $class = 'OTS_' . $class;
        return new $class($this->db);
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
            fwrite($socket, chr(6).chr(0).chr(255).chr(255).'info');

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
}

/**#@-*/

?>
