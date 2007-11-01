<?php

/**#@+
 * @version 0.0.5
 * @since 0.0.5
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * SQL identifier representation.
 * 
 * @package POT
 */
class OTS_SQLField
{
/**
 * Field name.
 * 
 * @var string
 */
    private $name;
/**
 * Table name.
 * 
 * @var string
 */
    private $table;

/**
 * Creates new field representation.
 * 
 * @param string $name Field name.
 * @param string $table Table name.
 */
    public function __construct($name, $table = '')
    {
        $this->name = $name;
        $this->table = $table;
    }

/**
 * Returns field name.
 * 
 * @return string Field name.
 */
    public function getName()
    {
        return $this->name;
    }

/**
 * Returns table name.
 * 
 * @return string Table name.
 */
    public function getTable()
    {
        return $this->table;
    }
}

/**#@-*/

?>
