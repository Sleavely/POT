<?php

/**#@+
 * @version 0.0.1
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * List of groups.
 * 
 * @package POT
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
 * @param OTS_Group $group Group to be deleted.
 * @return bool False if attempts to delete null group.
 */
    public function deleteGroup(OTS_Group $group)
    {
        if( !$group->isLoaded() )
        {
            trigger_error('Tries to delete not loaded group.', E_USER_NOTICE);
            return false;
        }

        $this->db->SQLquery('DELETE FROM ' . $this->db->tableName('groups') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $group->getId() );
        return true;
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
 * Select groups from database.
 */
    public function rewind()
    {
        $this->rows = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('groups') . $this->db->limit($this->limit, $this->offset) )->fetchAll();
    }

/**
 * Returns number of groups on list in current criterium.
 * 
 * @return int Number of groups.
 */
    public function count()
    {
        $count = $this->db->SQLquery('SELECT COUNT(' . $this->db->fieldName('id') . ') AS ' . $this->db->fieldName('count') . ' FROM ' . $this->db->tableName('groups') . $this->db->limit($this->limit, $this->offset) )->fetch();
        return $count['count'];
    }
}

/**#@-*/

?>
