<?php

/**#@+
 * @version 0.0.1
 */

/**
 * @package POT
 * @version 0.0.1+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ account abstraction.
 * 
 * @package POT
 * @version 0.0.1+SVN
 */
class OTS_Account implements IOTS_DAO
{
/**
 * Database connection.
 * 
 * @var IOTS_DB
 */
    private $db;

/**
 * Account data.
 * 
 * @var array
 */
    private $data = array('email' => '', 'blocked' => false, 'premdays' => 0);

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
 * Creates new account.
 * 
 * Create new account in given range (1 - 9999999 by default).
 * 
 * <p>
 * Remember! This method sets blocked flag to true after account creation!
 * </p>
 * 
 * @param int $min Minimum number.
 * @param int $max Maximum number.
 * @return int Created account number.
 * @example examples/account.php account.php
 * @throws Exception When there are no free account numbers.
 */
    public function create($min = 1, $max = 9999999)
    {
        // generates random account number
        $random = rand($min, $max);
        $number = $random;
        $exist = array();

        // reads already existing accounts
        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('accounts') ) as $account)
        {
            $exist[] = $account['id'];
        }

        // finds unused number
        while(true)
        {
            // unused - found
            if( !in_array($number, $exist) )
            {
                break;
            }

            // used - next one
            $number++;

            // we need to re-set
            if($number > $max)
            {
                $number = $min;
            }

            // we checked all possibilities
            if($number == $random)
            {
                throw new Exception('No free account number are available.');
            }
        }

        // saves blank account info
        $this->data['id'] = $number;
        $this->data['blocked'] = true;

        $this->db->SQLquery('INSERT INTO ' . $this->db->tableName('accounts') . ' (' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('password') . ', ' . $this->db->fieldName('email') . ', ' . $this->db->fieldName('blocked') . ', ' . $this->db->fieldName('premdays') . ') VALUES (' . $number . ', \'\', \'\', 1, 0)');

        return $number;
    }

/**
 * Loads account with given number.
 * 
 * @param int $id Account number.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('password') . ', ' . $this->db->fieldName('email') . ', ' . $this->db->fieldName('blocked') . ', ' . $this->db->fieldName('premdays') . ' FROM ' . $this->db->tableName('accounts') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Loads account by it's e-mail address.
 * 
 * @version 0.0.1+SVN
 * @param string $email Account's e-mail address.
 */
    public function find($email)
    {
        // finds player's ID
        $id = $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('accounts') . ' WHERE ' . $this->db->fieldName('email') . ' = ' . $this->db->SQLquote($email) )->fetch();

        // if anything was found
        if( isset($id['id']) )
        {
            $this->load($id['id']);
        }
    }

/**
 * Checks if object is loaded.
 * 
 * @return bool Load state.
 */
    public function isLoaded()
    {
        return isset($this->data['id']);
    }

/**
 * Updates account in database.
 * 
 * @return bool False if account doesn't have ID assigned.
 */
    public function save()
    {
        if( !isset($this->data['id']) )
        {
            trigger_error('Can\'t save account which is not created.', E_USER_WARNING);
            return false;
        }

        // UPDATE query on database
        $this->db->SQLquery('UPDATE ' . $this->db->tableName('accounts') . ' SET ' . $this->db->fieldName('password') . ' = ' . $this->db->SQLquote($this->data['password']) . ', ' . $this->db->fieldName('email') . ' = ' . $this->db->SQLquote($this->data['email']) . ', ' . $this->db->fieldName('blocked') . ' = ' . (int) $this->data['blocked'] . ', ' . $this->db->fieldName('premdays') . ' = ' . $this->data['premdays'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);

        return true;
    }

/**
 * Account number.
 * 
 * @return int|bool Account number (false if not loaded).
 */
    public function getId()
    {
        if( !isset($this->data['id']) )
        {
            trigger_error('Tries to get property of not loaded account.', E_USER_NOTICE);
            return false;
        }

        return $this->data['id'];
    }

/**
 * Account's password.
 * 
 * @return string|bool Password (false if not loaded).
 */
    public function getPassword()
    {
        if( !isset($this->data['password']) )
        {
            trigger_error('Tries to get property of not loaded account.', E_USER_NOTICE);
            return false;
        }

        return $this->data['password'];
    }

/**
 * Sets account's password.
 * 
 * @param string $password Password.
 */
    public function setPassword($password)
    {
        $this->data['password'] = (string) $password;
    }

/**
 * E-mail address.
 * 
 * @return string|bool E-mail (false if not loaded).
 */
    public function getEMail()
    {
        if( !isset($this->data['email']) )
        {
            trigger_error('Tries to get property of not loaded account.', E_USER_NOTICE);
            return false;
        }

        return $this->data['email'];
    }

/**
 * Sets account's email.
 * 
 * @param string $email E-mail address.
 */
    public function setEMail($email)
    {
        $this->data['email'] = (string) $email;
    }

/**
 * Checks if account is blocked.
 * 
 * @return bool|null PACC days (null if not loaded).
 */
    public function isBlocked()
    {
        if( !isset($this->data['blocked']) )
        {
            trigger_error('Tries to get property of not loaded account.', E_USER_NOTICE);
            return null;
        }

        return $this->data['blocked'];
    }

/**
 * Unblocks account.
 */
    public function unblock()
    {
        $this->data['blocked'] = false;
    }

/**
 * Blocks account.
 */
    public function block()
    {
        $this->data['blocked'] = true;
    }

/**
 * PACC days.
 * 
 * @return int|bool PACC days (false if not loaded).
 */
    public function getPACCDays()
    {
        if( !isset($this->data['premdays']) )
        {
            trigger_error('Tries to get property of not loaded account.', E_USER_NOTICE);
            return false;
        }

        return $this->data['premdays'];
    }

/**
 * Sets PACC days count.
 * 
 * @param int $pacc PACC days.
 */
    public function setPACCDays($premdays)
    {
        $this->data['premdays'] = (int) $premdays;
    }

/**
 * List of characters on account.
 * 
 * @return array|bool Array of OTS_Player objects from given account (false if not loaded).
 */
    public function getPlayers()
    {
        if( !isset($this->data['id']) )
        {
            trigger_error('Tries to get characters list of not loaded account.', E_USER_NOTICE);
            return false;
        }

        $players = array();

        foreach( $this->db->SQLquery('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('players') . ' WHERE ' . $this->db->fieldName('account_id') . ' = ' . $this->data['id']) as $player)
        {
            // creates new object
            $object = POT::getInstance()->createObject('Player');
            $object->load($player['id']);
            $players[] = $object;
        }

        return $players;
    }
}

/**#@-*/

?>
