<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for binary server status request.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @property-read int $uptime Uptime.
 * @property-read string $ip IP number.
 * @property-read string $name Server name.
 * @property-read int $port Server port.
 * @property-read string $location Server physical location.
 * @property-read string $url Website URL.
 * @property-read string $serverType Server software.
 * @property-read string $serverVersion Server version.
 * @property-read string $clientVersion Client version.
 * @property-read string $owner Owner name.
 * @property-read string $eMail Owner's e-mail.
 * @property-read int $onlinePlayers Players online count.
 * @property-read int $maxPlayers Maximum allowed players count.
 * @property-read int $playersPeak Record of players online.
 * @property-read string $mapName Map name.
 * @property-read string $mapAuthor Map author.
 * @property-read int $mapWidth Map width.
 * @property-read int $mapHeight Map height.
 * @property-read string $motd Message Of The Day.
 * @property-read array $players Online players list.
 */
class OTS_ServerStatus
{
/**
 * Basic server info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const REQUEST_BASIC_SERVER_INFO = 0x01;
/**
 * Server owner info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const REQUEST_OWNER_SERVER_INFO = 0x02;
/**
 * Server extra info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const REQUEST_MISC_SERVER_INFO = 0x04;
/**
 * Players stats info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const REQUEST_PLAYERS_INFO = 0x08;
/**
 * Map info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const REQUEST_MAP_INFO = 0x10;
/**
 * Extended players info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const REQUEST_EXT_PLAYERS_INFO = 0x20;
/**
 * Player status info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const REQUEST_PLAYER_STATUS_INFO = 0x40;
/**
 * Server software info.
 *
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    const REQUEST_SERVER_SOFTWARE_INFORMATION = 0x80;

/**
 * Basic server respond.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const RESPOND_BASIC_SERVER_INFO = 0x10;
/**
 * Server owner respond.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const RESPOND_OWNER_SERVER_INFO = 0x11;
/**
 * Server extra respond.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const RESPOND_MISC_SERVER_INFO = 0x12;
/**
 * Players stats respond.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const RESPOND_PLAYERS_INFO = 0x20;
/**
 * Map respond.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const RESPOND_MAP_INFO = 0x30;
/**
 * Extended players info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const RESPOND_EXT_PLAYERS_INFO = 0x21;
/**
 * Player status info.
 *
 * @version 0.1.4
 * @since 0.1.4
 */
    const RESPOND_PLAYER_STATUS_INFO = 0x22;
/**
 * Server software info.
 *
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    const RESPOND_SERVER_SOFTWARE_INFORMATION = 0x23;

/**
 * Server name.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var string
 */
    protected $name;
/**
 * Server IP.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var string
 */
    protected $ip;
/**
 * Server port.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var string
 */
    protected $port;
/**
 * Owner name.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var string
 */
    protected $owner;
/**
 * Owner's e-mail.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var string
 */
    protected $eMail;
/**
 * Message of the day.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var stirng
 */
    protected $motd;
/**
 * Server location.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var string
 */
    protected $location;
/**
 * Website URL.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var stirng
 */
    protected $url;
/**
 * Uptime.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var int
 */
    protected $uptime;
/**
 * Players online.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var int
 */
    protected $online;
/**
 * Maximum players.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var int
 */
    protected $max;
/**
 * Players peak.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var int
 */
    protected $peak;
/**
 * Map name.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var string
 */
    protected $map;
/**
 * Map author.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var string
 */
    protected $author;
/**
 * Map width.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var int
 */
    protected $width;
/**
 * Map height.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var int
 */
    protected $height;
/**
 * Players online list.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @var array
 */
    protected $players = array();
/**
 * Server software.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @var string
 */
    protected $serverType;
/**
 * Server version.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @var string
 */
    protected $serverVersion;
/**
 * Supported client version.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @var string
 */
    protected $clientVersion;

/**
 * Reads info from respond packet.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.4
 * @param OTS_Buffer $info Information packet.
 */
    public function __construct(OTS_Buffer $info)
    {
        // skips packet length
        $info->getShort();

        while( $info->isValid() )
        {
            switch( $info->getChar() )
            {
                case self::RESPOND_BASIC_SERVER_INFO:
                    $this->name = $info->getString();
                    $this->ip = $info->getString();
                    $this->port = (int) $info->getString();
                    break;

                case self::RESPOND_OWNER_SERVER_INFO:
                    $this->owner = $info->getString();
                    $this->eMail = $info->getString();
                    break;

                case self::RESPOND_MISC_SERVER_INFO:
                    $this->motd = $info->getString();
                    $this->location = $info->getString();
                    $this->url = $info->getString();

                    $this->uptime = $info->getLong() << 32;
                    $this->uptime += $info->getLong();
                    break;

                case self::RESPOND_PLAYERS_INFO:
                    $this->online = $info->getLong();
                    $this->max = $info->getLong();
                    $this->peak = $info->getLong();
                    break;

                case self::RESPOND_MAP_INFO:
                    $this->map = $info->getString();
                    $this->author = $info->getString();
                    $this->width = $info->getShort();
                    $this->height = $info->getShort();
                    break;

                case self::RESPOND_EXT_PLAYERS_INFO:
                    $count = $info->getLong();

                    for($i = 0; $i < $count; $i++)
                    {
                        $name = $info->getString();
                        $this->players[$name] = $info->getLong();
                    }
                    break;

                case self::RESPOND_SERVER_SOFTWARE_INFORMATION:
                    $this->serverType = $info->getString();
                    $this->serverVersion = $info->getString();
                    $this->clientVersion = $info->getString();
                    break;
            }
        }
    }

/**
 * Returns server uptime.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return int Uptime.
 */
    public function getUptime()
    {
        return $this->uptime;
    }

/**
 * Returns server IP.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string IP.
 */
    public function getIP()
    {
        return $this->ip;
    }

/**
 * Returns server name.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Name.
 */
    public function getName()
    {
        return $this->name;
    }

/**
 * Returns server port.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return int Port.
 */
    public function getPort()
    {
        return $this->port;
    }

/**
 * Returns server location.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Location.
 */
    public function getLocation()
    {
        return $this->location;
    }

/**
 * Returns server website.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Website URL.
 */
    public function getURL()
    {
        return $this->url;
    }

/**
 * Returns server software type.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return string Version.
 */
    public function getServerType()
    {
        return $this->serverType;
    }

/**
 * Returns server version.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Version.
 */
    public function getServerVersion()
    {
        return $this->serverVersion;
    }

/**
 * Returns client version.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return string Version.
 */
    public function getClientVersion()
    {
        return $this->clientVersion;
    }

/**
 * Returns owner name.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Owner name.
 */
    public function getOwner()
    {
        return $this->owner;
    }

/**
 * Returns owner e-mail.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Owner e-mail.
 */
    public function getEMail()
    {
        return $this->eMail;
    }

/**
 * Returns current amount of players online.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return int Count of players.
 */
    public function getOnlinePlayers()
    {
        return $this->online;
    }

/**
 * Returns maximum amount of players online.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return int Maximum allowed count of players.
 */
    public function getMaxPlayers()
    {
        return $this->max;
    }

/**
 * Returns record of online players.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return int Players online record.
 */
    public function getPlayersPeak()
    {
        return $this->peak;
    }

/**
 * Returns map name.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Map name.
 */
    public function getMapName()
    {
        return $this->map;
    }

/**
 * Returns map author.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Mapper name.
 */
    public function getMapAuthor()
    {
        return $this->author;
    }

/**
 * Returns map width.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return int Map width.
 */
    public function getMapWidth()
    {
        return $this->width;
    }

/**
 * Returns map height.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return int Map height.
 */
    public function getMapHeight()
    {
        return $this->height;
    }

/**
 * Returns server's Message Of The Day
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @return string Server MOTD.
 */
    public function getMOTD()
    {
        return $this->motd;
    }

/**
 * Returns list of players currently online.
 * 
 * @version 0.1.5
 * @since 0.1.4
 * @return array List of players in format 'name' => level.
 */
    public function getPlayers()
    {
        return $this->players;
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.4
 * @since 0.1.4
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'uptime':
                return $this->getUptime();

            case 'ip':
                return $this->getIP();

            case 'name':
                return $this->getName();

            case 'port':
                return $this->getPort();

            case 'location':
                return $this->getLocation();

            case 'url':
                return $this->getURL();

            case 'serverType':
                return $this->getServerType();

            case 'serverVersion':
                return $this->getServerVersion();

            case 'clientVersion':
                return $this->getClientVersion();

            case 'owner':
                return $this->getOwner();

            case 'eMail':
                return $this->getEMail();

            case 'onlinePlayers':
                return $this->getOnlinePlayers();

            case 'maxPlayers':
                return $this->getMaxPlayers();

            case 'playersPeak':
                return $this->getPlayersPeak();

            case 'mapName':
                return $this->getMapName();

            case 'mapAuthor':
                return $this->getMapAuthor();

            case 'mapWidth':
                return $this->getMapWidth();

            case 'mapHeight':
                return $this->getMapHeight();

            case 'motd':
                return $this->getMOTD();

            case 'players':
                return $this->getPlayers();

            default:
                throw new OutOfBoundsException();
        }
    }
}

?>
