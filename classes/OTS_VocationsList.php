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
 * Wrapper for vocations.xml file.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @example examples/vocations.php vocations.php
 * @tutorial POT/data_directory.pkg#vocations
 */
class OTS_VocationsList implements IteratorAggregate, Countable, ArrayAccess
{
/**
 * List of vocations.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @var array
 */
    private $vocations = array();

/**
 * Loads vocations list from given file.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $file vocations.xml file location.
 * @throws DOMException On DOM operation error.
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
 * <p>
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param array $properties List of object properties.
 * @throws DOMException On DOM operation error.
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
 * Checks if given vocation ID exists on list.
 * 
 * @version 0.1.3
 * @since 0.1.3
 * @param int $id ID.
 * @return bool If vocation is set then true.
 */
    public function hasVocationId($id)
    {
        return isset($this->vocations[$id]);
    }

/**
 * Returns vocation's ID.
 * 
 * @version 0.1.3
 * @since 0.1.0
 * @param string $name Vocation.
 * @return int ID.
 * @throws OutOfBoundsException If not found.
 */
    public function getVocationId($name)
    {
        $id = array_search($name, $this->vocations);

        // checks if vocation was found
        if($id === false)
        {
            throw new OutOfBoundsException();
        }

        return $id;
    }

/**
 * Checks if given vocation name exists on list.
 * 
 * @version 0.1.3
 * @since 0.1.3
 * @param string $name Vocation.
 * @return bool If vocation is set then true.
 */
    public function hasVocationName($name)
    {
        return array_search($name, $this->vocations) !== false;
    }

/**
 * Returns name of given vocation's ID.
 * 
 * @version 0.1.3
 * @since 0.1.0
 * @param int $id Vocation ID.
 * @return string Name.
 * @throws OutOfBoundsException If not found.
 */
    public function getVocationName($id)
    {
        if( isset($this->vocations[$id]) )
        {
            return $this->vocations[$id];
        }

        throw new OutOfBoundsException();
    }

/**
 * Returns amount of vocations loaded.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return int Count of vocations.
 */
    public function count()
    {
        return count($this->vocations);
    }

/**
 * Returns iterator handle for loops.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return ArrayIterator Vocations list iterator.
 */
    public function getIterator()
    {
        return new ArrayIterator($this->vocations);
    }

/**
 * Checks if given element exists.
 * 
 * @version 0.1.3
 * @since 0.1.0
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
        return array_search($offset, $this->vocations) !== false;
    }

/**
 * Returns item from given position.
 * 
 * @version 0.1.3
 * @since 0.1.0
 * @param string|int $offset Array key.
 * @return string|int If key is an integer (type-sensitive!) then returns vocation name. If it's a string then return associated ID.
 */
    public function offsetGet($offset)
    {
        // integer key
        if( is_int($offset) )
        {
            return $this->getVocationName($offset);
        }

        // vocations name
        return $this->getVocationId($offset);
    }

/**
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to vocations list. Any call to this method will cause {@link E_OTS_ReadOnly E_OTS_ReadOnly} raise.
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
 * This method is implemented for ArrayAccess interface. In fact you can't write/append to vocations list. Any call to this method will cause {@link E_OTS_ReadOnly E_OTS_ReadOnly} raise.
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
}

?>
