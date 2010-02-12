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
 * OTServ killer abstraction.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @property OTS_Death $death Target death.
 * @property bool $finalHit Final hit flag.
 * @property array $environments List of additional killing factors.
 * @property-read int $id Account number.
 * @property-read bool $loaded Loaded state.
 * @property-read OTS_Players_List $playersList Killers list.
 */
class OTS_Kill extends OTS_Row_DAO implements IteratorAggregate, Countable
{
/**
 * Kill data.
 * 
 * @var array
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    protected $data = array();

/**
 * Environment factors.
 * 
 * @var array
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    protected $environments = array();

/**
 * Loads kill with given ID.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $id Kill ID.
 * @throws PDOException On PDO operation error.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('death_id') . ', ' . $this->db->fieldName('final_hit') . ' FROM ' . $this->db->tableName('killers') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();

        // loads additional environmental killers
        if( $this->isLoaded() )
        {
            $this->environments = $this->db->query('SELECT ' . $this->db->fieldName('name') . ' FROM ' . $this->db->tableName('environment_killers') . ' WHERE ' . $this->db->fieldName('kill_id') . ' = ' . $this->data['id'])->fetchAll(PDO::FETCH_COLUMN);
        }
    }
		
/**
 * Load kill by player id.
 *
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @param integer $player_id Player ID
 * @throws PDOException On PDO operation error.
 */
 
		public function find($player_id)
		{
				// finds killer's ID
        $id = $this->db->query('SELECT ' . $this->db->fieldName('death_id') . ' FROM ' . $this->db->tableName('killers') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->db->quote($player_id) )->fetchColumn();

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
        // updates existing kill
        if( isset($this->data['id']) )
        {
            // UPDATE query on database
            $this->db->query('UPDATE ' . $this->db->tableName('killers') . ' SET ' . $this->db->fieldName('death_id') . ' = ' . $this->data['death_id'] . ', ' . $this->db->fieldName('final_hit') . ' = ' . (int) $this->data['final_hit'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
        }
        // creates new kill
        else
        {
            // INSERT query on database
            $this->db->query('INSERT INTO ' . $this->db->tableName('killers') . ' (' . $this->db->fieldName('death_id') . ', ' . $this->db->fieldName('final_hit') . ') VALUES (' . $this->data['death_id'] . ', ' . (int) $this->data['final_hit'] . ')');
            // ID of new group
            $this->data['id'] = $this->db->lastInsertId();
        }

        // clears current environment hits
        $this->db->query('DELETE FROM ' . $this->db->tableName('environment_killers') . ' WHERE ' . $this->db->fieldName('kill_id') . ' = ' . $this->data['id']);

        // inserts all environment hits
        foreach($this->environments as $environment)
        {
            $this->db->query('INSERT INTO ' . $this->db->tableName('environment_killers') . ' (' . $this->db->fieldName('kill_id') . ', ' . $this->db->fieldName('name') . ') VALUES (' . $this->data['id'] . ', ' . $this->db->quote($environment) . ')');
        }
    }

/**
 * Killer ID.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int ID.
 * @throws E_OTS_NotLoaded If kill is not loaded.
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
 * Death.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return OTS_Death Death event.
 * @throws E_OTS_NotLoaded If kill is not loaded.
 */
    public function getDeath()
    {
        if( !isset($this->data['death_id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $death = new OTS_Death();
        $death->load($this->data['death_id']);
        return $death;
    }

/**
 * Sets death event.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Kill::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param OTS_Death $death Death event.
 */
    public function setDeath(OTS_Death $death)
    {
        $this->data['death_id'] = $death->getId();
    }

/**
 * Checks if it was final hit.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @throws E_OTS_NotLoaded If kill is not loaded.
 */
    public function isFinalHit()
    {
        if( !isset($this->data['final_hit']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['final_hit'];
    }

/**
 * Unsets final hit flag.
 * 
 * <p>
    * This method only updates object state. To save changes in database you need to use {@link OTS_Kill::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    public function unsetFinalHit()
    {
        $this->data['final_hit'] = false;
    }

/**
 * Sets final hit flag.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Kill::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 */
    public function setFinalHit()
    {
        $this->data['final_hit'] = true;
    }

/**
 * Envoronment hits.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return array List of environment hits.
 * @throws E_OTS_NotLoaded If kill is not loaded.
 */
    public function gettEnvironments()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->environments;
    }

/**
 * Sets additional death factors.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Kill::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param array $environments List of additional hits.
 */
    public function setEnvironments(array $environments)
    {
        $this->environments = array_unique($environments);
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
 * @throws E_OTS_NotLoaded If kill is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->db->query('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('killers') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetchColumn();
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
 * @throws E_OTS_NotLoaded If kill is not loaded.
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

        $this->db->query('UPDATE ' . $this->db->tableName('killers') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * Deletes death.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @throws E_OTS_NotLoaded If kill is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function delete()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // deletes row from database
        $this->db->query('DELETE FROM ' . $this->db->tableName('killers') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);

        // resets object handle
        unset($this->data['id']);
    }

/**
 * Returns list of killers.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return OTS_Players_List List of killers.
 * @throws E_OTS_NotLoaded If kill is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getPlayersList()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $list = new OTS_Player_List();

        // foreign table fields identifiers
        $field1 = new OTS_SQLField('kill_id', 'player_killers');
        $field2 = new OTS_SQLField('player_id', 'player_killers');

        // creates filter
        $filter = new OTS_SQLFilter();
        $filter->addFilter($field1, $this->data['id']);
        $filter->compareField('id', $field2);

        // puts filter onto list
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
        return $this->getPlayersList();
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
        return $this->getPlayersList()->count();
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws E_OTS_NotLoaded If kill is not loaded.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'id':
                return $this->getId();

            case 'death':
                return $this->getDeath();

            case 'finalHit':
                return $this->isFinalHit();

            case 'environments':
                return $this->getEnvironments();

            case 'loaded':
                return $this->isLoaded();

            case 'playersList':
                return $this->getPlayersList();

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
            case 'death':
                $this->setDeath($value);
                break;

            case 'finalHit':
                if($value)
                {
                    $this->setFinalHit();
                }
                else
                {
                    $this->unsetFinalHit();
                }
                break;

            case 'environments':
                $this->setEnvironments($value);
                break;

            default:
                throw new OutOfBoundsException();
        }
    }
}

?>
