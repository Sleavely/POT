<?php

/**#@+
 * @version 0.1.0+SVN
 * @since 0.1.0+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for monsters list.
 * 
 * @package POT
 */
class OTS_MonstersList implements Iterator, Countable
{
/**
 * Monsters directory.
 * 
 * @var string
 */
    private $monstersPath;

/**
 * List of loaded monsters.
 * 
 * @var array
 */
    private $monsters = array();

/**
 * Loads monsters mapping file.
 * 
 * @param string $path Monsters directory.
 */
    public function __construct($path)
    {
        $this->monstersPath = $path;

        // makes sure it has directory separator at the end
        $last = substr($this->monstersPath, -1);
        if($last != '/' && $last != '\\')
        {
            $this->monstersPath .= '/';
        }

        // loads monsters mapping file
        $monsters = new DOMDocument();
        $monsters->load($this->monstersPath . 'monsters.xml');

        foreach( $monsters->getElementsByTagName('monster') as $monster)
        {
            $this->monsters[ $monster->getAttribute('name') ] = $monster->getAttribute('file');
        }
    }

/**
 * Returns loaded data of given monster.
 * 
 * @param string $name Monster name.
 * @return OTS_Monster|null Monster data (null if not exists).
 */
    public function getMonster($name)
    {
        // checks if monster exists
        if( isset($this->monsters[$name]) )
        {
            // loads file
            $monster = new OTS_Monster();
            $monster->load($this->monstersPath . $this->monsters[$name]);
            return $monster;
        }
        else
        {
            return null;
        }
    }

/**
 * Returns amount of monsters loaded.
 * 
 * @return int Count of monsters.
 */
    public function count()
    {
        return count($this->monsters);
    }

/**
 * Returns monster at current position in iterator.
 * 
 * @return OTS_Monster Monster.
 */
    public function current()
    {
        return $this->getMonster( key($this->monsters) );
    }

/**
 * Moves to next iterator monster.
 */
    public function next()
    {
        next($this->monsters);
    }

/**
 * Returns name of current position.
 * 
 * @return string Current position key.
 */
    public function key()
    {
        return key($this->monsters);
    }

/**
 * Checks if there is anything more in interator.
 * 
 * @return bool If iterator has anything more.
 */
    public function valid()
    {
        return key($this->monsters) !== null;
    }

/**
 * Resets iterator index.
 */
    public function rewind()
    {
        reset($this->monsters);
    }
}

/**#@-*/

?>
