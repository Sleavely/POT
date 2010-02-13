<?php

/**
 * @package POT
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Wrapper for house auction information.
 * 
 *
 * @package POT
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @property int $id House ID.
 * @property string $name House name.
 * @property int $bid House auction bid.
 * @property int $limit House auction limit.
 * @property int $endtime House auction endtime.
 */
class OTS_Auction extends OTS_Base_DAO
{
/**
 * House rent info.
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @var array
 */
    private $data = array('bid' => 0, 'limit' => 0, 'endtime' => 0);

	
	public function __construct($id)
    {
		parent::__construct();
		$this->load($id);
	}
/**
 * Loads auction with given auction ID
 * 
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @param int $id Auction id
 * @throws PDOException On PDO operation error.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('house_id') . ', ' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('bid') . ', ' . $this->db->fieldName('limit') . ', ' . $this->db->fieldName('endtime') .  ' FROM ' . $this->db->tableName('house_auctions') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Loads auction by it's house ID
 * 
 * 
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @param string $id Auction id
 * @throws PDOException On PDO operation error.
 */
    public function find($id)
    {
        // finds player's ID
        $id = $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('house_auctions') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int)$id->fetch() )->fetchColumn();

        // if anything was found
        if($id !== false)
        {
            $this->load($id);
        }
    }

/**
 * Saves info in database.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0b+SVN
 * @throws PDOException On PDO operation error.
 */
    public function save()
    {
        // inserts new record
        if( empty($this->data) )
        {
            $this->db->query('INSERT INTO ' . $this->db->tableName('house_auctions') . ' (' . $this->db->fieldName('house_id') . ', ' . $this->db->fieldName('player_id') . ', ' . $this->db->fieldName('bid') . ', ' . $this->db->fieldName('limit') . ', ' . $this->db->fieldName('endtime') . ') VALUES (' . $this->getId() . ', ' . $this->data['player_id'] . ', ' . (int) $this->data['bid'] . ', ' . $this->data['limit'] . ', ' . $this->data['endtime'] . ')');
        }
        // updates previous one
        else
        {
            $this->db->query('UPDATE ' . $this->db->tableName('house_auctions') . ' SET ' . $this->db->fieldName('house_id') . ' = ' . $this->getId() . ', ' . $this->db->fieldName('player_id') . ' = ' . $this->data['player_id'] . ', ' . $this->db->fieldName('bid') . ' = ' . $this->data['bid'] . ', ' . $this->db->fieldName('limit') . ' = ' . $this->data['limit'] . ', ' . $this->db->fieldName('endtime') . ' = ' . $this->data['endtime'] . ' WHERE ' . $this->db->fieldName('house_id') . ' = ' . $this->getId() );
        }
    }

/**
 * Deletes house info from database.
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @throws PDOException On PDO operation error.
 */
    public function delete()
    {
        // deletes row from database
        $this->db->query('DELETE FROM ' . $this->db->tableName('house_auctions') . ' WHERE ' . $this->db->fieldName('house_id') . ' = ' . $this->data['house_id']);

        // resets object handle
        $this->data = array();
    }
	
/**
 * Returns house's ID.
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @return int House ID.
 * @throws E_OTS_NotLoaded If house is not loaded.
 */
    public function getId()
    {
       if( !isset($this->data['house_id']) )
       {
			throw new E_OTS_NotLoaded();
       }
       
       return $this->data['house_id'];
    }
	
/**
 * Returns current house auction player winner.
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @return OTS_Player|null Player that currently wins house auction (null if there is no one).
 */
    public function getPlayer()
    {
        if( isset($this->data['player_id']) )
        {
            $player = new OTS_Player();
            $player->load($this->data['player_id']);
            return $player;
        }
        // not set
        else
        {
            return null;
        }
    }
	
/**
 * Sets house auction player_id winner.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @param OTS_Player $player House auction player_id winner
 * @throws E_OTS_NotLoaded If given <var>$player</var> object is not loaded.
 */
    public function setPlayerId(OTS_Player $player)
    {
        $this->data['player_id'] = $player->getId();
    }

/**
 * Returns current house auction bid.
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @return int|null Currently bid on the house auction (null if there is no bid).
 */
    public function getBid()
    {
        if( isset($this->data['bid']) && $this->data['bid'] != 0)
        {
            return $this->data['bid'];
        }
        // not set
        else
        {
            return null;
        }
    }

/**
 * Sets house auction bid.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @param int $bid House bid value.
 * @throws E_OTS_NotLoaded If given <var>$player</var> object is not loaded.
 */
    public function setBid($bid)
    {
        $this->data['bid'] = $bid;
    }
	
/**
 * Returns current house auction limit.
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @return int|null Currently limit on the house auction (null if there is no limit).
 */
    public function getLimit()
    {
        if( isset($this->data['limit']) && $this->data['limit'] != 0)
        {
            return $this->data['limit'];
        }
        // not set
        else
        {
            return null;
        }
    }

/**
 * Sets house limit.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @param int $limit House auction limit value.
 * @throws E_OTS_NotLoaded If given <var>$player</var> object is not loaded.
 */
    public function setLimit($limit)
    {
        $this->data['limit'] = $limit;
    }
	
/**
 * Returns current house end time.
 * 
 * @version 0.1.0
 * @since 0.2.0b+SVN
 * @return int|null Currently end time on the house auction (null if there is no end time).
 */
    public function getEndtime()
    {
        if( isset($this->data['endtime']) && $this->data['endtime'] != 0)
        {
            return $this->data['endtime'];
        }
        // not set
        else
        {
            return null;
        }
    }

/**
 * Sets house auction end time.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_House::save() save() method} to flush changes to database.
 * </p>
 * 
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @param int $endtime House auction end time value.
 * @throws E_OTS_NotLoaded If given <var>$player</var> object is not loaded.
 */
    public function setEndtime($endtime)
    {
        $this->data['endtime'] = $endtime;
    }


/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0b+SVN
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws E_OTS_NotLoaded When atempt to read info about map while map not being loaded.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __get($name)
    {
		switch($name)
        	{
			case 'id':
				return $this->getId();
				
			case 'bid':
				return $this->getBid();

			case 'limit':
				return $this->getLimit();

			case 'endtime':
				return $this->getEndtime();

			default:
				throw new OutOfBoundsException();
		}
	}

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0b+SVN
 * @since 0.2.0b+SVN
 * @param string $name Property name.
 * @param mixed $value Property value.
 * @throws E_OTS_NotLoaded If passed parameter for owner field won't be loaded.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'bid':
                $this->setBid($value);
                break;

            case 'limit':
                $this->setLimit($value);
                break;

            case 'endtime':
                $this->setEndtime($values);
                break;

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Returns string representation of object.
 * 
 * <p>
 * If any display driver is currently loaded then it uses it's method. Otherwise just returns house ID.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0b+SVN
 * @return string String representation of object.
 */
    public function __toString()
    {
        // checks if display driver is loaded
        if( POT::isDataDisplayDriverLoaded() )
        {
            return POT::getDataDisplayDriver()->displayAuction($this);
        }

        return $this->getId();
    }
}

?>
