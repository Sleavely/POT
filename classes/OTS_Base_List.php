<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.5
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 * @todo future: Iterator classes (to map id => name iterations) with tutorial.
 * @todo 0.2.0: Use fetchObject() to reduce amount of SQL queries.
 */

/**
 * Basic list class routines.
 * 
 * <p>
 * This class defines entire lists mechanism for classes that represents records set from OTServ database. All child classes only have to define {@link OTS_Base_List::init() init() method} to set table info for queries.
 * </p>
 * 
 * <p>
 * Table on which list will operate has to contain integer <var>"id"</var> field and single row representing class has to support loading by this filed as key.
 * </p>
 * 
 * <p>
 * This class is mostly usefull when you create own extensions for POT code.
 * </p>
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.0.5
 * @property-write int $limit Sets LIMIT clause.
 * @property-write int $offset Sets OFFSET clause.
 * @property-write OTS_SQLFilter $filter Sets filter for list SQL query.
 */
abstract class OTS_Base_List implements Iterator, Countable
{
/**
 * Database connection.
 * 
 * @var PDO
 * @version 0.1.5
 * @since 0.0.5
 */
    protected $db;

/**
 * Limit for SELECT query.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @var int
 */
    private $limit = false;

/**
 * Offset for SELECT query.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @var int
 */
    private $offset = false;

/**
 * WHERE clause filter.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @var OTS_SQLFilter
 */
    private $filter = null;

/**
 * List of sorting criteriums.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @var array
 */
    private $orderBy = array();

/**
 * Query results.
 * 
 * @since 0.0.5
 * @var array
 * @version 0.1.5
 */
    protected $rows;

/**
 * Default table name for queries.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @var string
 */
    protected $table;

/**
 * Class of generated objects.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @var string
 */
    protected $class;

/**
 * Sets database connection handler.
 * 
 * @version 0.2.0+SVN
 * @since 0.0.5
 */
    public function __construct()
    {
        $this->db = POT::getDBHandle();
        $this->init();
    }

/**
 * Sets list parameters.
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    abstract public function init();

/**
 * Magic PHP5 method.
 * 
 * <p>
 * Allows object serialisation.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @return array List of properties that should be saved.
 */
    public function __sleep()
    {
        return array('limit', 'offset', 'filter', 'orderBy', 'table', 'class');
    }

/**
 * Magic PHP5 method.
 * 
 * <p>
 * Allows object unserialisation.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.0.5
 */
    public function __wakeup()
    {
        $this->db = POT::getDBHandle();
    }

/**
 * Magic PHP5 method.
 * 
 * <p>
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * </p>
 * 
 * @version 0.1.3
 * @since 0.0.5
 * @param array $properties List of object properties.
 */
    public static function __set_state($properties)
    {
        // deletes database handle
        if( isset($properties['db']) )
        {
            unset($properties['db']);
        }

        // initializes new object with current database connection
        $object = new self();

        // loads properties
        foreach($properties as $name => $value)
        {
            $object->$name = $value;
        }

        return $object;
    }

/**
 * Sets LIMIT clause.
 * 
 * <p>
 * Reduces amount of seleced rows up to given number.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @param int|bool $limit Limit for SELECT (false to reset).
 */
    public function setLimit($limit = false)
    {
        if( is_int($limit) )
        {
            $this->limit = $limit;
        }
        else
        {
            $this->limit = false;
        }
    }

/**
 * Sets OFFSET clause.
 * 
 * <p>
 * Moves starting rows of selected set to given position.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @param int|bool $offset Offset for SELECT (false to reset).
 */
    public function setOffset($offset = false)
    {
        if( is_int($offset) )
        {
            $this->offset = $offset;
        }
        else
        {
            $this->offset = false;
        }
    }

/**
 * Returns current row.
 * 
 * <p>
 * Returns object of class which handle single row representation. Object is initialised with ID of current position in result cursor.
 * </p>
 * 
 * @version 0.1.3
 * @since 0.0.5
 * @return OTS_Base_DAO Current row.
 */
    public function current()
    {
        $id = current($this->rows);

        $class = 'OTS_' . $this->class;
        return new $class( (int) $id['id']);
    }

/**
 * Select rows from database.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @throws PDOException On PDO operation error.
 */
    public function rewind()
    {
        $this->rows = $this->db->query( $this->getSQL() )->fetchAll();
    }

/**
 * Moves to next row.
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    public function next()
    {
        next($this->rows);
    }

/**
 * Current cursor position.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @return mixed Array key.
 */
    public function key()
    {
        return key($this->rows);
    }

/**
 * Checks if there are any rows left.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @return bool Does next row exist.
 */
    public function valid()
    {
        return key($this->rows) !== null;
    }

/**
 * Returns number of rows on list in current criterium.
 * 
 * @version 0.1.5
 * @since 0.0.5
 * @return int Number of rows.
 * @throws PDOException On PDO operation error.
 */
    public function count()
    {
        return $this->db->query( $this->getSQL(true) )->fetchColumn();
    }

/**
 * Sets filter on list.
 * 
 * <p>
 * Call without argument to reset filter.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @param OTS_SQLFilter|null $filter Filter for list.
 */
    public function setFilter(OTS_SQLFilter $filter = null)
    {
        $this->filter = $filter;
    }

/**
 * Clears ORDER BY clause.
 * 
 * @version 0.0.5
 * @since 0.0.5
 */
    public function resetOrder()
    {
        $this->orderBy = array();
    }

/**
 * Appends sorting rule.
 * 
 * <p>
 * First parameter may be of type string, then it will be used as literal field name, or object of {@link OTS_SQLField OTS_SQLField class}, then it's representation will be used as qualiffied SQL identifier name.
 * </p>
 * 
 * <p>
 * Note: Since 0.0.7 version <var>$field</var> parameter can be instance of {@link OTS_SQLField OTS_SQLField class}.
 * </p>
 * 
 * @version 0.0.7
 * @since 0.0.5
 * @param OTS_SQLField|string $field Field name.
 * @param int $order Sorting order (ascending by default).
 */
    public function orderBy($field, $order = POT::ORDER_ASC)
    {
        // constructs field name filter
        if($field instanceof OTS_SQLField)
        {
            $table = $field->getTable();

            // full table name
            if( !empty($table) )
            {
                $table = $this->db->tableName($table) . '.';
            }

            $field = $table . $this->db->fieldName( $field->getName() );
        }
        // literal name
        else
        {
            $field = $this->db->fieldName($field);
        }

        $this->orderBy[] = array('field' => $field, 'order' => $order);
    }

/**
 * Returns SQL query for SELECT.
 * 
 * @version 0.1.5
 * @since 0.0.5
 * @param bool $count Shows if the SQL should be generated for COUNT() variant.
 * @return string SQL query part.
 */
    protected function getSQL($count = false)
    {
        // fields list
        if($count)
        {
            $fields = 'COUNT(' . $this->db->tableName($this->table) . '.' . $this->db->fieldName('id') . ')';
        }
        else
        {
            $fields = $this->db->tableName($this->table) . '.' . $this->db->fieldName('id') . ' AS ' . $this->db->fieldName('id');
        }

        return $this->prepareSQL( array($fields), $count);
    }

/**
 * Returns generic SQL query that can be adaptated by child classes.
 * 
 * @version 0.1.5
 * @since 0.1.5
 * @param array $fields Fields to be selected.
 * @param bool $count Shows if the SQL should be generated for COUNT() variant.
 * @return string SQL query.
 */
    protected function prepareSQL($fields, $count = false)
    {
        $tables = array();

        // generates tables list for current qeury
        if( isset($this->filter) )
        {
            $tables = $this->filter->getTables();
        }

        // adds default table
        if( !in_array($this->table, $tables) )
        {
            $tables[] = $this->table;
        }

        // prepares tables names
        foreach($tables as &$name)
        {
            $name = $this->db->tableName($name);
        }

        // WHERE clause
        if( isset($this->filter) )
        {
            $where = ' WHERE ' . $this->filter->__toString();
        }
        else
        {
            $where = '';
        }

        // ORDER BY clause
        if($count || empty($this->orderBy) )
        {
            $orderBy = '';
        }
        else
        {
            $orderBy = array();

            foreach($this->orderBy as $criterium)
            {
                switch($criterium['order'])
                {
                    case POT::ORDER_ASC:
                        $orderBy[] = $criterium['field'] . ' ASC';
                        break;

                    case POT::ORDER_DESC:
                        $orderBy[] = $criterium['field'] . ' DESC';
                        break;
                }
            }

            $orderBy = ' ORDER BY ' . implode(', ', $orderBy);
        }

        return 'SELECT ' . implode(', ', $fields) . ' FROM ' . implode(', ', $tables) . $where . $orderBy . $this->db->limit($this->limit, $this->offset);
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.0
 * @since 0.1.0
 * @param string $name Property name.
 * @param mixed $value Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'limit':
                $this->setLimit($value);
                break;

            case 'offset':
                $this->setOffset($value);
                break;

            case 'filter':
                $this->setFilter($value);
                break;

            default:
                throw new OutOfBoundsException();
        }
    }
}

?>