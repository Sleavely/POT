<?php

/**#@+
 * @version 0.1.0
 * @since 0.1.0
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for vocations.xml file.
 * 
 * @package POT
 */
class OTS_VocationsList implements IteratorAggregate, Countable, ArrayAccess
{
/**
 * List of vocations.
 * 
 * @var array
 */
    private $vocations = array();

/**
 * Loads vocations list.
 * 
 * Loads vocations list from given file.
 * 
 * @param string $file vocations.xml file location.
 */
    public function __construct($file)
    {
        // loads DOM document
        $vocations = new DOMDocument();
        $vocations->load($file);

        // loads vocations
        foreach( $vocations->getElementsByTagName('vocation') as $vocation)
        {
            $this->vocations[ (int) $vocation->getAttribute('id') ] = $vocation->getAttribute('name');
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
 * Returns vocation's ID.
 * 
 * @param string $name Vocation.
 * @return int|bool ID (false if not found).
 */
    public function getVocationId($name)
    {
        return array_search($name, $this->vocations);
    }

/**
 * Returns name of given vocation's ID.
 * 
 * @param int $id Vocation ID.
 * @return string|bool Name (false if not found).
 */
    public function getVocationName($id)
    {
        if( isset($this->vocations[$id]) )
        {
            return $this->vocations[$id];
        }
        else
        {
            return false;
        }
    }

/**
 * Returns amount of vocations loaded.
 * 
 * @return int Count of vocations.
 */
    public function count()
    {
        return count($this->vocations);
    }

/**
 * Returns iterator handle for loops.
 * 
 * @return ArrayIterator Vocations list iterator.
 */
    public function getIterator()
    {
        return new ArrayIterator($this->vocations);
    }

/**
 * Checks if given element exists.
 * 
 * @param string|int $offset Array key.
 * @return bool True if it's set.
 */
    public function offsetExists($offset)
    {
        // integer key
        if( is_int($offset) )
        {
            return isset($this->vocations[$offset]);
        }
        // vocation name
        else
        {
            return array_search($offset, $this->vocations) !== false;
        }
    }

/**
 * Returns item from given position.
 * 
 * @param string|int $offset Array key.
 * @return mixed If key is an integer (type-sensitive!) then returns vocation name. If it's a string then return associated ID. False if offset is not set.
 */
    public function offsetGet($offset)
    {
        // integer key
        if( is_int($offset) )
        {
            if( isset($this->vocations[$offset]) )
            {
                return $this->vocations[$offset];
            }
            // keys is not set
            else
            {
                return false;
            }
        }
        // vocations name
        else
        {
            return array_search($offset, $this->vocations);
        }
    }

/**
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to vocations list. Any call to this method will cause E_OTS_ReadOnly raise.
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
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to vocations list. Any call to this method will cause E_OTS_ReadOnly raise.
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
