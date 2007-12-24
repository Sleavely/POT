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
 * @todo 0.1.0: Houses SQL part support.
 */

/**
 * Wrapper for houses list.
 * 
 * @package POT
 */
class OTS_HousesList implements Iterator, Countable
{
/**
 * List of houses elements.
 * 
 * @var array
 */
    private $houses = array();

/**
 * Loads houses information.
 * 
 * @param string $path Houses file.
 */
    public function __construct($path)
    {
        $houses = new DOMDocument();
        $houses->load($path);

        foreach( $houses->getElementsByTagName('house') as $house)
        {
            $this->houses[ (int) $house->getAttribute('houseid') ] = $house;
        }
    }

/**
 * Returns house information.
 * 
 * @param int $id House ID.
 * @return OTS_House|null House information wrapper (null if not found house).
 */
    public function getHouse($id)
    {
        if( isset($this->houses[$id]) )
        {
            return $this->houses[$id];
        }
        else
        {
            return null;
        }
    }

/**
 * Returns ID of house with given name.
 * 
 * @param string $name House name.
 * @return int|bool House ID (false if not found).
 */
    public function getHouseId($name)
    {
        foreach($this->houses as $house)
        {
            // checks houses id
            if( $house->getAttribute('name') == $name)
            {
                return $house->getAttribute('houseid');
            }
        }

        return false;
    }

/**
 * Returns amount of houses.
 * 
 * @return int Count of houses.
 */
    public function count()
    {
        return count($this->houses);
    }

/**
 * Returns house at current position in iterator.
 * 
 * @return OTS_House House.
 */
    public function current()
    {
        return $this->getHouse( key($this->houses) );
    }

/**
 * Moves to next iterator house.
 */
    public function next()
    {
        next($this->houses);
    }

/**
 * Returns ID of current position.
 * 
 * @return int Current position key.
 */
    public function key()
    {
        return key($this->houses);
    }

/**
 * Checks if there is anything more in interator.
 * 
 * @return bool If iterator has anything more.
 */
    public function valid()
    {
        return key($this->houses) !== null;
    }

/**
 * Resets iterator index.
 */
    public function rewind()
    {
        reset($this->houses);
    }
}

/**#@-*/

?>
