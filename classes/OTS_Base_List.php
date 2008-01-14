<?php

/**#@+
 * @version 0.0.5
 * @since 0.0.5
 */

/**
 * @package POT
 * @version 0.1.0
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Basic list class routines.
 * 
 * @package POT
 * @version 0.1.0
 * @property-write int $limit Sets LIMIT clause.
 * @property-write int $offset Sets OFFSET clause.
 * @property-write OTS_SQLFilter $filter Sets filter for list SQL query.
 */
abstract class OTS_Base_List implements IOTS_DAO, Iterator, Countable
{
/**
 * Database connection.
 * 
 * @var PDO
 */
    private $db;

/**
 * Limit for SELECT query.
 * 
 * @var int
 */
    private $limit = false;

/**
 * Offset for SELECT query.
 * 
 * @var int
 */
    private $offset = false;

/**
 * Query results.
 * 
 * @var array
 */
    private $rows;

/**
 * WHERE clause filter.
 * 
 * @var OTS_SQLFilter
 */
    private $filter = null;

/**
 * List of sorting criteriums.
 * 
 * @var array
 */
    private $orderBy = array();

/**
 * Default table name for queries.
 * 
 * @var string
 */
    protected $table;

/**
 * Class of generated objects.
 * 
 * @var string
 */
    protected $class;

/**
 * Sets database connection handler.
 * 
 * @version 0.1.0
 */
    public function __construct()
    {
        $this->db = POT::getInstance()->getDBHandle();
        $this->init();
    }

/**
 * Sets list parameters.
 */
    abstract public function init();

/**
 * Magic PHP5 method.
 * 
 * Allows object serialisation.
 * 
 * @return array List of properties that should be saved.
 * @internal Magic PHP5 method.
 */
    public function __sleep()
    {
        return array('limit', 'offset', 'filter', 'orderBy', 'table', 'class');
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object unserialisation.
 * 
 * @internal Magic PHP5 method.
 */
    public function __wakeup()
    {
        $this->db = POT::getInstance()->getDBHandle();
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @version 0.0.6
 * @internal Magic PHP5 method.
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
        $object = new self( POT::getInstance()->getDBHandle() );

        // loads properties
        foreach($properties as $name => $value)
        {
            $object->$name = $value;
        }

        return $object;
    }

/**
 * Sets LIMIT.
 * 
 * @param int|bool Limit for SELECT (false to reset).
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
 * Sets OFFSET.
 * 
 * @param int|bool Offset for SELECT (false to reset).
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
 * @version 0.1.0
 * @return IOTS_DAO Current row.
 */
    public function current()
    {
        $id = current($this->rows);

        $class = 'OTS_' . $this->class;
        $object = new $class();
        $object->load($id['id']);
        return $object;
    }

/**
 * Select rows from database.
 */
    public function rewind()
    {
        $this->rows = $this->db->query( $this->getSQL() )->fetchAll();
    }

/**
 * Moves to next row.
 */
    public function next()
    {
        next($this->rows);
    }

/**
 * Current cursor position.
 * 
 * @return mixed Array key.
 */
    public function key()
    {
        return key($this->rows);
    }

/**
 * Checks if there are any rows left.
 * 
 * @return bool Does next row exist.
 */
    public function valid()
    {
        return key($this->rows) !== null;
    }

/**
 * Returns number of rows on list in current criterium.
 * 
 * @version 0.0.5
 * @return int Number of rows.
 */
    public function count()
    {
        $count = $this->db->query( $this->getSQL(true) )->fetch();
        return $count['count'];
    }

/**
 * Sets filter on list.
 * 
 * Call without argument to reset filter.
 * 
 * @param OTS_SQLFilter|null $filter Filter for list.
 */
    public function setFilter(OTS_SQLFilter $filter = null)
    {
        $this->filter = $filter;
    }

/**
 * Clears ORDER BY clause.
 */
    public function resetOrder()
    {
        $this->orderBy = array();
    }

/**
 * Appends sorting rule.
 * 
 * @version 0.0.7
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
 * @param bool $count Shows if the SQL should be generated for COUNT() variant.
 * @return string SQL query part.
 */
    private function getSQL($count = false)
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

        // fields list
        if($count)
        {
            $fields = 'COUNT(' . $this->db->tableName($this->table) . '.' . $this->db->fieldName('id') . ') AS ' . $this->db->fieldName('count');
        }
        else
        {
            $fields = $this->db->tableName($this->table) . '.' . $this->db->fieldName('id') . ' AS ' . $this->db->fieldName('id');
        }

        return 'SELECT ' . $fields . ' FROM ' . implode(', ', $tables) . $where . $orderBy . $this->db->limit($this->limit, $this->offset);
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

/**#@-*/

?>
