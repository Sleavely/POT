<?php

/**#@+
 * @version 0.0.1
 */

/**
 * @package POT
 * @version 0.0.4
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * List of groups.
 * 
 * @package POT
 * @version 0.0.4
 */
class OTS_Groups_List implements IOTS_DAO, Iterator, Countable
{
/**
 * Database connection.
 * 
 * @var IOTS_DB
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
 * Sets database connection handler.
 * 
 * @param IOTS_DB $db Database connection object.
 */
    public function __construct(IOTS_DB $db)
    {
        $this->db = $db;
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object serialisation.
 * 
 * @return array List of properties that should be saved.
 * @internal Magic PHP5 method.
 * @version 0.0.4
 * @since 0.0.4
 */
    public function __sleep()
    {
        return array('limit', 'offset');
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object unserialisation.
 * 
 * @internal Magic PHP5 method.
 * @version 0.0.4
 * @since 0.0.4
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
 * @internal Magic PHP5 method.
 * @version 0.0.4
 * @since 0.0.4
 * @param array $properties List of object properties.
 */
    public static function __set_state(array $properties)
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
 * Deletes group.
 * 
 * @version 0.0.3
 * @param OTS_Group $group Group to be deleted.
 * @deprecated 0.0.4+SVN Use OTS_Group->delete().
 */
    public function deleteGroup(OTS_Group $group)
    {
        $this->db->SQLquery('DELETE FROM ' . $this->db->tableName('groups') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $group->getId() );
    }

/**
 * Returns current row.
 * 
 * @return OTS_Group Current group.
 */
    public function current()
    {
        $id = current($this->rows);

        $group = POT::getInstance()->createObject('Group');
        $group->load($id['id']);
        return $group;
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
 * WHERE clause filter.
 * 
 * @version 0.0.4+SVN
 * @since 0.0.4+SVN
 * @var OTS_SQLFilter
 */
    private $filter = null;

/**
 * Sets filter on list.
 * 
 * Call without argument to reset filter.
 * 
 * @version 0.0.4+SVN
 * @since 0.0.4+SVN
 * @param OTS_SQLFilter|null $filter Filter for list.
 */
    public function setFilter(OTS_SQLFilter $filter = null)
    {
        $this->filter = $filter;
    }

/**
 * List of sorting criteriums.
 * 
 * @version 0.0.4+SVN
 * @since 0.0.4+SVN
 * @var array
 */
    private $orderBy = array();

/**
 * Clears ORDER BY clause.
 * 
 * @version 0.0.4+SVN
 * @since 0.0.4+SVN
 */
    public function resetOrder()
    {
        $this->orderBy = array();
    }

/**
 * Appends sorting rule.
 * 
 * @version 0.0.4+SVN
 * @since 0.0.4+SVN
 * @param string $field Field name.
 * @param int $order Sorting order (ascending by default).
 */
    public function orderBy($filed, $order = POT::ORDER_ASC)
    {
        $this->orderBy[] = array('field' => $this->db->fieldName($field), 'order' => $order);
    }

/**
 * Select groups from database.
 * 
 * @version 0.0.4+SVN
 */
    public function rewind()
    {
        $tables = array();

        // generates tables list for current qeury
        if( isset($this->filter) )
        {
            $tables = $this->filter->getTables();
        }

        // adds default table
        if( !in_array('groups', $tables) )
        {
            $tables[] = 'groups';
        }

        // prepares tables names
        foreach($tables as &$table)
        {
            $table = $this->db->tableName($table);
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
        if( empty($this->orderBy) )
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

        $this->rows = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . implode(', ', $tables) . $where . $orderBy . $this->db->limit($this->limit, $this->offset) )->fetchAll();
    }

/**
 * Returns number of groups on list in current criterium.
 * 
 * @version 0.0.4+SVN
 * @return int Number of groups.
 */
    public function count()
    {
        $tables = array();

        // generates tables list for current qeury
        if( isset($this->filter) )
        {
            $tables = $this->filter->getTables();
        }

        // adds default table
        if( !in_array('groups', $tables) )
        {
            $tables[] = 'groups';
        }

        // prepares tables names
        foreach($tables as &$table)
        {
            $table = $this->db->tableName($table);
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

        $count = $this->db->SQLquery('SELECT COUNT(' . $this->db->fieldName('id') . ') AS ' . $this->db->fieldName('count') . ' FROM ' . implode(', ', $tables) . $where . $this->db->limit($this->limit, $this->offset) )->fetch();
        return $count['count'];
    }
}

/**#@-*/

?>
