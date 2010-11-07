<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for monsters list.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @tutorial POT/data_directory.pkg#monsters
 */
class OTS_MonstersList implements Iterator, Countable, ArrayAccess
{
/**
 * Monsters directory.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var string
 */
    private $monstersPath;

/**
 * List of loaded monsters.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var array
 */
    private $monsters = array();

/**
 * Loads monsters mapping file.
 * 
 * <p>
 * Note: You pass directory path, not monsters.xml file name itself.
 * </p>
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $path Monsters directory.
 * @throws DOMException On DOM operation error.
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
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param array $properties List of object properties.
 */
    public function __set_state(array $properties)
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
 * Checks if given monster ID exists on list.
 * 
 * @version 0.1.3
 * @since 0.1.3
 * @param string $name Monster name.
 * @return bool If monster is set then true.
 */
    public function hasMonster($name)
    {
        return isset($this->monsters[$name]);
    }

/**
 * Returns loaded data of given monster.
 * 
 * @version 0.1.3
 * @since 0.1.0
 * @param string $name Monster name.
 * @return OTS_Monster Monster data.
 * @throws OutOfBoundsException If not exists.
 * @throws DOMException On DOM operation error.
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

        throw new OutOfBoundsException();
    }

/**
 * Returns amount of monsters loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int Count of monsters.
 */
    public function count()
    {
        return count($this->monsters);
    }

/**
 * Returns monster at current position in iterator.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return OTS_Monster Monster.
 * @throws DOMException On DOM operation error.
 */
    public function current()
    {
        return $this->getMonster( key($this->monsters) );
    }

/**
 * Moves to next iterator monster.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function next()
    {
        next($this->monsters);
    }

/**
 * Returns name of current position.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return string Current position key.
 */
    public function key()
    {
        return key($this->monsters);
    }

/**
 * Checks if there is anything more in interator.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return bool If iterator has anything more.
 */
    public function valid()
    {
        return key($this->monsters) !== null;
    }

/**
 * Resets iterator index.
 * 
 * @version 0.1.0
 * @since 0.1.0
 */
    public function rewind()
    {
        reset($this->monsters);
    }

/**
 * Checks if given element exists.
 * 
 * @version 0.1.0
 * @since 0.1.0
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
 * @version 0.1.3
 * @since 0.1.0
 * @param string $offset Array key.
 * @return OTS_Monster Monster instance.
 * @throws DOMException On DOM operation error.
 */
    public function offsetGet($offset)
    {
        return $this->getMonster($offset);
    }

/**
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to monsters list. Any call to this method will cause {@link E_OTS_ReadOnly E_OTS_ReadOnly} raise.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string|int $offset Array key.
 * @param mixed $value Field value.
 * @throws E_OTS_ReadOnly Always - this class is read-only.
 */
    public function offsetSet($offset, $value)
    {
        throw new E_OTS_ReadOnly();
    }

/**
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to monsters list. Any call to this method will cause {@link E_OTS_ReadOnly E_OTS_ReadOnly} raise.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string|int $offset Array key.
 * @throws E_OTS_ReadOnly Always - this class is read-only.
 */
    public function offsetUnset($offset)
    {
        throw new E_OTS_ReadOnly();
    }

/**
 * Returns string representation of object.
 * 
 * <p>
 * If any display driver is currently loaded then it uses it's method.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.3
 * @return string String representation of object.
 */
    public function __toString()
    {
        // checks if display driver is loaded
        if( POT::isDataDisplayDriverLoaded() )
        {
            return POT::getDataDisplayDriver()->displayMonstersList($this);
        }

        return (string) $this->count();
    }
}

?>