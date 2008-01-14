<?php

/**#@+
 * @version 0.0.3
 * @since 0.0.3
 */

/**
 * @package POT
 * @version 0.1.0
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Container item representation.
 * 
 * @package POT
 * @version 0.1.0
 */
class OTS_Container extends OTS_Item implements IteratorAggregate
{
/**
 * Contained items.
 * 
 * @var array
 */
    private $items = array();

/**
 * Adds item to container.
 * 
 * @param OTS_Item $item Item.
 */
    public function addItem(OTS_Item $item)
    {
        $this->items[] = $item;
    }

/**
 * Removes given item from current container.
 * 
 * Passed item must be exacly instance of item which is stored in container, not it's copy.
 * 
 * @param OTS_Item $item Item.
 */
    public function removeItem(OTS_Item $item)
    {
        foreach($this->items as $index => $current)
        {
            // checks if it is EXACLY the same item, not similar
            if($item === $current)
            {
                unset($this->items[$index]);
            }
        }
    }

/**
 * Number of items inside container.
 * 
 * OTS_Container implementation of Countable interface differs from OTS_Item implemention. {@link OTS_Item::count() OTS_Item::count()} returns count of given item, OTS_Container::count() returns number of items inside container. If somehow it would be possible to make container items with more then 1 in one place, you can use {@link OTS_Item::getCount() OTS_Item::getCount()} and {@link OTS_Item::setCount() OTS_Item::setCount()} in code where you are not sure if working with regular item, or container.
 * 
 * @return int Number of items.
 */
    public function count()
    {
        return count($items);
    }

/**
 * Returns current item.
 * 
 * @return OTS_Item Current item.
 * @deprecated 0.1.0 Use getIterator().
 */
    public function current()
    {
        return current($this->items);
    }

/**
 * Moves to next item.
 * 
 * @deprecated 0.1.0 Use getIterator().
 */
    public function next()
    {
        next($this->items);
    }

/**
 * Current cursor position.
 * 
 * @return mixed Iterator position.
 * @deprecated 0.1.0 Use getIterator().
 */
    public function key()
    {
        return key($this->items);
    }

/**
 * Checks if there are any items left.
 * 
 * @return bool Does next item exist.
 * @deprecated 0.1.0 Use getIterator().
 */
    public function valid()
    {
        return key($this->items) !== null;
    }

/**
 * Resets internal items array pointer.
 * 
 * @deprecated 0.1.0 Use getIterator().
 */
    public function rewind()
    {
        reset($this->items);
    }

/**
 * Returns iterator handle for loops.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @return ArrayIterator Items iterator.
 */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}

/**#@-*/

?>
