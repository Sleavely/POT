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
class OTS_MonstersList implements Iterator, Countable, ArrayAccess
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
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @internal Magic PHP5 method.
 * @param array $properties List of object properties.
 */
    public function __set_state($properties)
    {
        $object = new self();

        // loads properties
        foreach($properties as $name => $value)
        {
            $object->$name = $value;
        }

        return $object;
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

/**
 * Checks if given element exists.
 * 
 * @param string $offset Array key.
 * @return bool True if it's set.
 */
    public function offsetExists($offset)
    {
        return isset($this->monsters[$offset]);
    }

/**
 * Returns item from given position.
 * 
 * @param string $offset Array key.
 * @return OTS_Monster|bool Monster instance. False if offset is not set.
 */
    public function offsetGet($offset)
    {
        if( isset($this->monsters[$offset]) )
        {
            return $this->getMonster($offset);
        }
        // keys is not set
        else
        {
            return false;
        }
    }

/**
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to monsters list. Any call to this method will cause E_OTS_ReadOnly raise.
 * 
 * @param string|int $offset Array key.
 * @param mixed $value Field value.
 * @throws E_OTS_ReadOnly Always - this class is read-only.
 */
    public function offsetSet($offset, $value)
    {
        throw new E_OTS_ReadOnly();
    }

/**
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to monsters list. Any call to this method will cause E_OTS_ReadOnly raise.
 * 
 * @param string|int $offset Array key.
 * @throws E_OTS_ReadOnly Always - this class is read-only.
 */
    public function offsetUnset($offset)
    {
        throw new E_OTS_ReadOnly();
    }
}

/**#@-*/

?>
