<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.5
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * SQL identifier representation.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.5
 * @property-read string $name Field name.
 * @property-read string $table Table name.
 * @tutorial POT/List_objects.pkg#filters.field
 */
class OTS_SQLField
{
/**
 * Field name.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @var string
 */
    private $name;
/**
 * Table name.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @var string
 */
    private $table;

/**
 * Creates new field representation.
 * 
 * @version 0.0.5
 * @since 0.0.5
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
 * @version 0.0.5
 * @since 0.0.5
 * @return string Field name.
 */
    public function getName()
    {
        return $this->name;
    }

/**
 * Returns table name.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @return string Table name.
 */
    public function getTable()
    {
        return $this->table;
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'name':
            case 'table':
                return $this->$name;

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Returns string representation for WHERE clause.
 * 
 * <p>
 * Returned string can be easily inserted into SQL query.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return string String WHERE clause.
 */
    public function __toString()
    {
        // database handle
        $db = POT::getDBHandle();

        // basic name
        $name = $db->fieldName($this->name);

        // prepends table name
        if( !empty($this->table) )
        {
            $name = $db->tableName($this->table) . '.' . $name;
        }

        return $name;
    }
}

?>
