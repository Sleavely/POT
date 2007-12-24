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
 * Wrapper for vocations.xml file.
 * 
 * @package POT
 */
class OTS_VocationsList implements Iterator, Countable
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
 * Returns vocation at current position in iterator.
 * 
 * @return string Vocation name.
 */
    public function current()
    {
        return current($this->vocations);
    }

/**
 * Moves to next iterator vocation.
 */
    public function next()
    {
        next($this->vocations);
    }

/**
 * Returns ID of current position.
 * 
 * @return int Current position key.
 */
    public function key()
    {
        return key($this->vocations);
    }

/**
 * Checks if there is anything more in interator.
 * 
 * @return bool If iterator has anything more.
 */
    public function valid()
    {
        return key($this->vocations) !== null;
    }

/**
 * Resets iterator index.
 */
    public function rewind()
    {
        reset($this->vocations);
    }
}

/**#@-*/

?>
