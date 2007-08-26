<?php

/**#@+
 * @version 0.0.3
 * @since 0.0.3
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Single item representation.
 * 
 * @package POT
 */
class OTS_Item implements Countable
{
/**
 * Item ID.
 * 
 * @var int
 */
    private $id;

/**
 * Item count.
 * 
 * @var int
 */
    private $count = 0;

/**
 * Additional attributes.
 * 
 * @var string
 */
    private $attributes;

/**
 * Creates item of given ID.
 * 
 * @param int $id Item ID.
 */
    public function __construct($id)
    {
        $this->id = $id;
    }

/**
 * Returns item type.
 * 
 * @return int Item ID.
 */
    public function getId()
    {
        return $this->id;
    }

/**
 * Returns count of item.
 * 
 * @return int Count of item.
 */
    public function getCount()
    {
        return $this->count;
    }

/**
 * Sets count of item.
 * 
 * @param int $count Count.
 */
    public function setCount($count)
    {
        $this->count = (int) $count;
    }

/**
 * Returns item custom attributes.
 * 
 * @return string Attributes.
 */
    public function getAttributes()
    {
        return $this->attributes;
    }

/**
 * Sets item attributes.
 * 
 * @param string $attributes Item Attributes.
 */
    public function setAttributes($attributes)
    {
        $this->attributes = (string) $attributes;
    }

/**
 * Count value for current item.
 * 
 * @return int Count of item.
 */
    public function count()
    {
        return $this->count;
    }
}

/**#@-*/

?>
