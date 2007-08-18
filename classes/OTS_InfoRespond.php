<?php

/**#@+
 * @version 0.0.2
 * @since 0.0.2
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for 'info' respond's DOMDocument.
 * 
 * Note: as this class extends DOMDocument class and contains exacly respond XML tree you can work on it as on normal DOM tree.
 * 
 * @package POT
 */
class OTS_InfoRespond extends DOMDocument
{
/**
 * Returns version of root element.
 * 
 * @return string TSPQ version.
 */
    public function getTSPQVersion()
    {
        return $this->documentElement->getAttribute('version');
    }

/**
 * Returns server uptime.
 * 
 * @return int Uptime.
 */
    public function getUptime()
    {
        return (int) $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('uptime');
    }

/**
 * Returns server IP.
 * 
 * @return string IP.
 */
    public function getIP()
    {
        return $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('ip');
    }

/**
 * Returns server name.
 * 
 * @return string Name.
 */
    public function getName()
    {
        return $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('servername');
    }

/**
 * Returns server port.
 * 
 * @return int Port.
 */
    public function getPort()
    {
        return (int) $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('port');
    }

/**
 * Returns server location.
 * 
 * @return string Location.
 */
    public function getLocation()
    {
        return $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('location');
    }

/**
 * Returns server website.
 * 
 * @return string Website URL.
 */
    public function getURL()
    {
        return $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('url');
    }

/**
 * Returns server attribute.
 * 
 * I have no idea what the hell is it representing :P.
 * 
 * @return string Attribute value.
 */
    public function getServer()
    {
        return $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('server');
    }

/**
 * Returns server version.
 * 
 * @return string Version.
 */
    public function getServerVersion()
    {
        return $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('version');
    }

/**
 * Returns dedicated version of client.
 * 
 * @return string Version.
 */
    public function getClientVersion()
    {
        return $this->documentElement->getElementsByTagName('serverinfo')->item(0)->getAttribute('client');
    }

/**
 * Returns owner name.
 * 
 * @return string Owner name.
 */
    public function getOwner()
    {
        return $this->documentElement->getElementsByTagName('owner')->item(0)->getAttribute('name');
    }

/**
 * Returns owner e-mail.
 * 
 * @return string Owner e-mail.
 */
    public function getEMail()
    {
        return $this->documentElement->getElementsByTagName('owner')->item(0)->getAttribute('email');
    }

/**
 * Returns current amount of players online.
 * 
 * @return int Count of players.
 */
    public function getOnlinePlayers()
    {
        return (int) $this->documentElement->getElementsByTagName('players')->item(0)->getAttribute('online');
    }

/**
 * Returns maximum amount of players online.
 * 
 * @return int Maximum allowed count of players.
 */
    public function getMaxPlayers()
    {
        return (int) $this->documentElement->getElementsByTagName('players')->item(0)->getAttribute('max');
    }

/**
 * Returns record of online players.
 * 
 * @return int Players online record.
 */
    public function getPlayersPeak()
    {
        return (int) $this->documentElement->getElementsByTagName('players')->item(0)->getAttribute('peak');
    }

/**
 * Returns number of all monsters on map.
 * 
 * @return int Count of monsters.
 */
    public function getMonstersCount()
    {
        return (int) $this->documentElement->getElementsByTagName('monsters')->item(0)->getAttribute('total');
    }

/**
 * Returns map name.
 * 
 * @return string Map name.
 */
    public function getMapName()
    {
        return $this->documentElement->getElementsByTagName('map')->item(0)->getAttribute('name');
    }


/**
 * Returns map author.
 * 
 * @return string Mapper name.
 */
    public function getMapAuthor()
    {
        return $this->documentElement->getElementsByTagName('map')->item(0)->getAttribute('author');
    }

/**
 * Returns map width.
 * 
 * @return int Map width.
 */
    public function getMapWidth()
    {
        return (int) $this->documentElement->getElementsByTagName('map')->item(0)->getAttribute('width');
    }

/**
 * Returns map height.
 * 
 * @return int Map height.
 */
    public function getMapHeight()
    {
        return (int) $this->documentElement->getElementsByTagName('map')->item(0)->getAttribute('height');
    }

/**
 * Returns server's Message Of The Day
 * 
 * @return string Server MOTD.
 */
    public function getMOTD()
    {
        // look for text node child
        foreach( $this->documentElement->getElementsByTagName("motd")->item(0)->childNodes as $child)
        {
            if($child->nodeType == XML_TEXT_NODE)
            {
                // found
                return $child->nodeValue;
            }
        }

        // strange...
        return '';
    }
}

/**#@-*/

?>
