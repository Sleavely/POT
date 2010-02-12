<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ player death abstraction.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @property OTS_Player $player Dead player.
 * @property int $date Timestamp of death.
 * @property int $level Level in moment of death.
 * @property-read int $id Account number.
 * @property-read bool $loaded Loaded state.
 * @property-read OTS_Kills_List $killsList Killers list.
 */
class OTS_Death extends OTS_Row_DAO implements IteratorAggregate, Countable
{
/**
 * Death data.
 * 
 * @var array
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    protected $data = array();

/**
 * Loads death with given ID.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $id Death ID.
 * @throws PDOException On PDO operation error.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('date') . ', ' . $this->db->fieldName('level') . ' FROM ' . $this->db->tableName('player_deaths') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }
		
/**
 * Load death by player id.
 *
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @param integer $player_id Player ID
 * @throws PDOException On PDO operation error.
 */
 
		public function find($player_id)
		{
				// finds deaths's ID
        $id = $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('player_deaths') . ' WHERE ' . $this->db->fieldName('player_id') . ' = ' . $this->db->quote($player_id) )->fetchColumn();

        // if anything was found
        if($id !== false)
        {
            $this->load($id);
        }
		}

/**
 * Checks if object is loaded.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return bool Load state.
 */
    public function isLoaded()
    {
        return isset($this->data['id']);
    }

/**
 * Updates account in database.
 * 
 * <p>
 * If object is not loaded to represent any existing death it will create new row for it.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @throws PDOException On PDO operation error.
 */
    public function save()
    {
        // updates existing death
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->query('UPDATE ' . $this->db->tableName('player_deaths') . ' SET ' . $this->db->fieldName('player_id') . ' = ' . $this->data['player_id'] . ', ' . $this->db->fieldName('date') . ' = ' . $this->data['date'] . ', ' . $this->db->fieldName('level') . ' = ' . $this->data['level'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new death
        else
        {
            // INSERT query on database
            $this->db->query('INSERT INTO ' . $this->db->tableName('player_deaths') . ' (' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('date') . ', ' . $this->db->fieldName('level') . ') VALUES (' . $this->data['player_id'] . ', ' . $this->data['date'] . ', ' . $this->data['level'] . ')');
            // ID of new group
            $this->data['id'] = $this->db->lastInsertId();
        }
    }

/**
 * Death ID.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int ID.
 * @throws E_OTS_NotLoaded If death is not loaded.
 */
    public function getId()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['id'];
    }

/**
 * Dead player.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return OTS_Player Victim.
 * @throws E_OTS_NotLoaded If death is not loaded.
 */
    public function getPlayer()
    {
        if( !isset($this->data['player_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $player = new OTS_Player();
        $player->load($this->data['player_id']);
        return $player;
    }

/**
 * Sets death's victim.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Death::save() save() method} to flush changes to database.
 * </p>
 * 
 * <p>
 * This method also automaticly sets level to player's level, so you don't need to call {@link OTS_Death::setLevel() setLevel() method} by yourself.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param OTS_Player $player Victim.
 */
    public function setPlayer(OTS_Player $player)
    {
        $this->data['player_id'] = $player->getId();
        $this->data['level'] = $player->getLevel();
    }

/**
 * Death timestamp.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int Timestamp.
 * @throws E_OTS_NotLoaded If death is not loaded.
 */
    public function getDate()
    {
        if( !isset($this->data['date']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['date'];
    }

/**
 * Sets moment of death.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Death::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $date Death timestamp.
 */
    public function setDate($date)
    {
        $this->data['date'] = (int) $date;
    }

/**
 * Players level.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int Level at death moment.
 * @throws E_OTS_NotLoaded If death is not loaded.
 */
    public function getLevel()
    {
        if( !isset($this->data['level']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['level'];
    }

/**
 * Sets level at death moment.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Death::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $level Level of player when dying.
 */
    public function setLevel($level)
    {
        $this->data['level'] = (int) $level;
    }

/**
 * Reads custom field.
 * 
 * <p>
 * Reads field by it's name. Can read any field of given record that exists in database.
 * </p>
 * 
 * <p>
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param string $field Field name.
 * @return string Field value.
 * @throws E_OTS_NotLoaded If death is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->db->query('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('player_deaths') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetchColumn();
    }

/**
 * Writes custom field.
 * 
 * <p>
 * Write field by it's name. Can write any field of given record that exists in database.
 * </p>
 * 
 * <p>
 * Note: You should use this method only for fields that are not provided in standard setters/getters (SVN fields). This method runs SQL query each time you call it so it highly overloads used resources.
 * </p>
 * 
 * <p>
 * Note: Make sure that you pass $value argument of correct type. This method determinates whether to quote field name. It is safe - it makes you sure that no unproper queries that could lead to SQL injection will be executed, but it can make your code working wrong way. For example: $object->setCustomField('foo', '1'); will quote 1 as as string ('1') instead of passing it as a integer.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param string $field Field name.
 * @param mixed $value Field value.
 * @throws E_OTS_NotLoaded If death is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function setCustomField($field, $value)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // quotes value for SQL query
        if(!( is_int($value) || is_float($value) ))
        {
            $value = $this->db->quote($value);
        }

        $this->db->query('UPDATE ' . $this->db->tableName('player_deaths') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * Deletes death.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @throws E_OTS_NotLoaded If death is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function delete()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // deletes row from database
        $this->db->query('DELETE FROM ' . $this->db->tableName('player_deaths') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);

        // resets object handle
        unset($this->data['id']);
    }

/**
 * Returns list of killers.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return OTS_Kills_List List of killers.
 * @throws E_OTS_NotLoaded If death is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getKillsList()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // creates filter
        $filter = new OTS_SQLFilter();
        $filter->compareField('death_id', (int) $this->data['id']);

        // puts filter onto list
        $list = new OTS_Kills_List();
        $list->setFilter($filter);

        return $list;
    }

/**
 * Returns kills iterator.
 * 
 * <p>
 * There is no need to implement entire Iterator interface since we have {@link OTS_Kills_List players list class} for it.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @throws E_OTS_NotLoaded If death is not loaded.
 * @throws PDOException On PDO operation error.
 * @return Iterator List of killers.
 */
    public function getIterator()
    {
        return $this->getKillsList();
    }

/**
 * Returns number of killers.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @throws E_OTS_NotLoaded If death is not loaded.
 * @throws PDOException On PDO operation error.
 * @return int Count of killers.
 */
    public function count()
    {
        return $this->getKillsList()->count();
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws E_OTS_NotLoaded If death is not loaded.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'id':
                return $this->getId();

            case 'player':
                return $this->getPlayer();

            case 'date':
                return $this->getDate();

            case 'level':
                return $this->getLevel();

            case 'loaded':
                return $this->isLoaded();

            case 'killsList':
                return $this->getKillsList();

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param string $name Property name.
 * @param mixed $value Property value.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'player':
                $this->setPlayer($value);
                break;

            case 'date':
                $this->setDate($value);
                break;

            case 'level':
                $this->setLevel($value);
                break;

            default:
                throw new OutOfBoundsException();
        }
    }
}

?>
