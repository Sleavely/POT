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
 * List of players.
 * 
 * @package POT
 */
class OTS_Players_List implements IOTS_DAO, Iterator, Countable
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
 * Deletes player.
 * 
 * @param OTS_Player $player Player to be deleted.
 * @return bool False if attempts to delete null player.
 */
    public function deletePlayer(OTS_Player $player)
    {
        if( !$player->isLoaded() )
        {
            trigger_error('Tries to delete not loaded player.', E_USER_NOTICE);
            return false;
        }

        $this->db->SQLquery('DELETE FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $player->getId() );
        return true;
    }

/**
 * Returns current row.
 * 
 * @return OTS_Player Current player.
 */
    public function current()
    {
        $id = current($this->rows);

        $player = POT::getInstance()->createObject('Player');
        $player->load($id['id']);
        return $player;
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
 * Select players from database.
 */
    public function rewind()
    {
        $this->rows = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('players') . $this->db->limit($this->limit, $this->offset) )->fetchAll();
    }

/**
 * Returns number of characters on list in current criterium.
 * 
 * @return int Number of players.
 */
    public function count()
    {
        $count = $this->db->SQLquery('SELECT COUNT(' . $this->db->fieldName('id') . ') AS ' . $this->db->fieldName('count') . ' FROM ' . $this->db->tableName('players') . $this->db->limit($this->limit, $this->offset) )->fetch();
        return $count['count'];
    }
}

/**#@-*/

?>
