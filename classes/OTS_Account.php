<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ account abstraction.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @property string $name Account name.
 * @property string $password Password.
 * @property string $eMail Email address.
 * @property int $premiumEnd Timestamp of PACC end.
 * @property bool $blocked Blocked flag state.
 * @property int $warnings Warnings level.
 * @property bool $deleted Deleted flag state - deprecated.
 * @property bool $warned Warned flag state - deprecated.
 * @property-read int $id Account number.
 * @property-read bool $loaded Loaded state.
 * @property-read OTS_Players_List $playersList Characters of this account.
 * @property-read int $access Access level.
 * @tutorial POT/Accounts.pkg
 */
class OTS_Account extends OTS_Row_DAO implements IteratorAggregate, Countable
{
/**
 * Account data.
 * 
 * <p>
 * Note: Since 0.2.0+SVN this field is protected instead of private.
 * </p>
 * 
 * @var array
 * @version 0.2.0+SVN
 */
    protected $data = array('email' => '', 'premend' => 0, 'blocked' => false, 'warnings' => 0);

/**
 * Creates new account.
 * 
 * <p>
 * This method creates new account with given name. Account number is generated automaticly and saved into {@link OTS_Account::getId() ID field}.
 * </p>
 * 
 * <p>
 * If you won't specify account name then random one will be generated.
 * </p>
 * 
 * <p>
 * If you use own account name then it will be returned after success, and exception will be generated if it will be alredy used as name will be simply used in query with account create attempt.
 * </p>
 * 
 * @version 0.1.5
 * @since 0.1.5
 * @param string $name Account name.
 * @return string Account name.
 * @throws PDOException On PDO operation error.
 * @example examples/create.php create.php
 * @tutorial POT/Accounts.pkg#create
 */
    public function createNamed($name = null)
    {
        // if name is not passed then it will be generated randomly
        if( !isset($name) )
        {
            $exist = array();

            // reads already existing names
            foreach( $this->db->query('SELECT ' . $this->db->fieldName('name') . ' FROM ' . $this->db->tableName('accounts') )->fetchAll() as $account)
            {
                $exist[] = $account['name'];
            }

            // initial name
            $name = uniqid();

            // repeats until name is unique
            while( in_array($name, $exist) )
            {
                $name .= '_';
            }
        }

        // saves blank account info
        $this->db->query('INSERT INTO ' . $this->db->tableName('accounts') . ' (' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('password') . ', ' . $this->db->fieldName('email') . ') VALUES (' . $this->db->quote($name) . ', \'\', \'\')');

        // reads created account's ID
        $this->data['id'] = $this->db->lastInsertId();

        // return name of newly created account
        return $name;
    }

/**
 * Loads account with given number.
 * 
 * @version 0.2.0+SVN
 * @param int $id Account number.
 * @throws PDOException On PDO operation error.
 */
    public function load($id)
    {
        // SELECT query on database
        $this->data = $this->db->query('SELECT ' . $this->db->fieldName('id') . ', ' . $this->db->fieldName('name') . ', ' . $this->db->fieldName('password') . ', ' . $this->db->fieldName('email') . ', ' . $this->db->fieldName('premend') . ', ' . $this->db->fieldName('blocked') . ', ' . $this->db->fieldName('warnings') . ' FROM ' . $this->db->tableName('accounts') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . (int) $id)->fetch();
    }

/**
 * Loads account by it's name.
 * 
 * <p>
 * Note: Since 0.1.5 version this method loads account by it's name not by e-mail address. To find account by it's e-mail address use {@link OTS_Account::findByEMail() findByEMail() method}.
 * </p>
 * 
 * @version 0.1.5
 * @since 0.0.2
 * @param string $name Account's name.
 * @throws PDOException On PDO operation error.
 */
    public function find($name)
    {
        // finds player's ID
        $id = $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('accounts') . ' WHERE ' . $this->db->fieldName('name') . ' = ' . $this->db->quote($name) )->fetch();

        // if anything was found
        if( isset($id['id']) )
        {
            $this->load($id['id']);
        }
    }

/**
 * Loads account by it's e-mail address.
 * 
 * @version 0.1.5
 * @since 0.1.5
 * @param string $email Account's e-mail address.
 * @throws PDOException On PDO operation error.
 */
    public function findByEMail($email)
    {
        // finds player's ID
        $id = $this->db->query('SELECT ' . $this->db->fieldName('id') . ' FROM ' . $this->db->tableName('accounts') . ' WHERE ' . $this->db->fieldName('email') . ' = ' . $this->db->quote($email) )->fetch();

        // if anything was found
        if( isset($id['id']) )
        {
            $this->load($id['id']);
        }
    }

/**
 * Checks if object is loaded.
 * 
 * @version 0.0.1
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
 * Unlike other DAO objects account can't be saved without ID being set. It means that you can't just save unexisting account to automaticly create it. First you have to create record by using {@link OTS_Account::createName() createNamed() method}
 * </p>
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded exception} instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @throws E_OTS_NotLoaded If account doesn't have ID assigned.
 * @throws PDOException On PDO operation error.
 */
    public function save()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // UPDATE query on database
        $this->db->query('UPDATE ' . $this->db->tableName('accounts') . ' SET ' . $this->db->fieldName('password') . ' = ' . $this->db->quote($this->data['password']) . ', ' . $this->db->fieldName('email') . ' = ' . $this->db->quote($this->data['email']) . ', ' . $this->db->fieldName('premend') . ' = ' . $this->data['premend'] . ', ' . $this->db->fieldName('blocked') . ' = ' . (int) $this->data['blocked'] . ', ' . $this->db->fieldName('warnings') . ' = ' . $this->data['warnings'] . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * Account number.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return int Account number.
 * @throws E_OTS_NotLoaded If account is not loaded.
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
 * Name.
 * 
 * @version 0.1.5
 * @since 0.1.5
 * @return string Name.
 * @throws E_OTS_NotLoaded If account is not loaded.
 */
    public function getName()
    {
        if( !isset($this->data['name']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['name'];
    }

/**
 * Sets account's name.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.1.5
 * @since 0.1.5
 * @param string $name Account name.
 */
    public function setName($name)
    {
        $this->data['name'] = (string) $name;
    }

/**
 * Account's password.
 * 
 * <p>
 * Doesn't matter what password hashing mechanism is used by OTServ - this method will just return RAW database content. It is not possible to "decrypt" hashed strings, so it even wouldn't be possible to return real password string.
 * </p>
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return string Password.
 * @throws E_OTS_NotLoaded If account is not loaded.
 */
    public function getPassword()
    {
        if( !isset($this->data['password']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['password'];
    }

/**
 * Sets account's password.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * <p>
 * Remember that this method just sets database field's content. It doesn't apply any hashing/encryption so if OTServ uses hashing for passwords you have to apply it by yourself before passing string to this method.
 * </p>
 * 
 * @version 0.0.1
 * @param string $password Password.
 */
    public function setPassword($password)
    {
        $this->data['password'] = (string) $password;
    }

/**
 * E-mail address.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return string E-mail.
 * @throws E_OTS_NotLoaded If account is not loaded.
 */
    public function getEMail()
    {
        if( !isset($this->data['email']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['email'];
    }

/**
 * Sets account's email.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.0.1
 * @param string $email E-mail address.
 */
    public function setEMail($email)
    {
        $this->data['email'] = (string) $email;
    }

/**
 * Account's Premium Account expiration timestamp.
 * 
 * @version 0.1.5
 * @since 0.1.5
 * @return int Account PACC expiration timestamp.
 * @throws E_OTS_NotLoaded If account is not loaded.
 */
    public function getPremiumEnd()
    {
        if( !isset($this->data['premend']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['premend'];
    }

/**
 * Sets account's Premium Account expiration timestamp.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Player::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.1.5
 * @since 0.1.5
 * @param int $premend PACC expiration timestamp.
 */
    public function setPremiumEnd($premend)
    {
        $this->data['premend'] = (int) $premend;
    }

/**
 * Checks if account is blocked.
 * 
 * <p>
 * Note: Since 0.0.3 version this method throws {@link E_OTS_NotLoaded E_OTS_NotLoaded} exception instead of triggering E_USER_WARNING.
 * </p>
 * 
 * @version 0.0.3
 * @return bool Blocked state.
 * @throws E_OTS_NotLoaded If account is not loaded.
 */
    public function isBlocked()
    {
        if( !isset($this->data['blocked']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['blocked'];
    }

/**
 * Unblocks account.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.0.1
 */
    public function unblock()
    {
        $this->data['blocked'] = false;
    }

/**
 * Blocks account.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.0.1
 */
    public function block()
    {
        $this->data['blocked'] = true;
    }

/**
 * Warnings count.
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @return int Warnings.
 * @throws E_OTS_NotLoaded If account is not loaded.
 */
    public function getEMail()
    {
        if( !isset($this->data['warnings']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['warnings'];
    }

/**
 * Sets account's warnings level.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.2.0+SVN
 * @param int $warnings Warnings count.
 */
    public function setWarnings($warnings)
    {
        $this->data['warnings'] = (int) $warnings;
    }

/**
 * Checks if account is deleted (by flag setting).
 * 
 * @version 0.2.0+SVN
 * @since 0.1.5
 * @return bool Flag state.
 * @throws E_OTS_NotLoaded If account is not loaded.
 * @deprecated 0.2.0+SVN Delete and warn flags were replaced by warnings level: use {@link OTS_Account::getWarnings() getWarnings()} and {@link OTS_Account::setWarnings() setWarnings()}.
 */
    public function isDeleted()
    {
        if( !isset($this->data['deleted']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return false;
    }

/**
 * Unsets account's deleted flag.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.5
 * @deprecated 0.2.0+SVN Delete and warn flags were replaced by warnings level: use {@link OTS_Account::getWarnings() getWarnings()} and {@link OTS_Account::setWarnings() setWarnings()}.
 */
    public function unsetDeleted()
    {
    }

/**
 * Deletes account (only by setting flag state, not physicly).
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.5
 * @deprecated 0.2.0+SVN Delete and warn flags were replaced by warnings level: use {@link OTS_Account::getWarnings() getWarnings()} and {@link OTS_Account::setWarnings() setWarnings()}.
 */
    public function setDeleted()
    {
    }

/**
 * Checks if account is warned.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.5
 * @return bool Flag state.
 * @throws E_OTS_NotLoaded If account is not loaded.
 * @deprecated 0.2.0+SVN Delete and warn flags were replaced by warnings level: use {@link OTS_Account::getWarnings() getWarnings()} and {@link OTS_Account::setWarnings() setWarnings()}.
 */
    public function isWarned()
    {
        if( !isset($this->data['warned']) )
        {
            throw new E_OTS_NotLoaded();
        }

        return $this->data['warnings'] > 0;
    }

/**
 * Unwarns account.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.5
 * @deprecated 0.2.0+SVN Delete and warn flags were replaced by warnings level: use {@link OTS_Account::getWarnings() getWarnings()} and {@link OTS_Account::setWarnings() setWarnings()}.
 */
    public function unwarn()
    {
    }

/**
 * Warns account.
 * 
 * <p>
 * This method only updates object state. To save changes in database you need to use {@link OTS_Account::save() save() method} to flush changed to database.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.5
 * @deprecated 0.2.0+SVN Delete and warn flags were replaced by warnings level: use {@link OTS_Account::getWarnings() getWarnings()} and {@link OTS_Account::setWarnings() setWarnings()}.
 */
    public function warn()
    {
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
 * @version 0.0.5
 * @since 0.0.3
 * @param string $field Field name.
 * @return string Field value.
 * @throws E_OTS_NotLoaded If account is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function getCustomField($field)
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        $value = $this->db->query('SELECT ' . $this->db->fieldName($field) . ' FROM ' . $this->db->tableName('accounts') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id'])->fetch();
        return $value[$field];
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
 * @version 0.0.5
 * @since 0.0.3
 * @param string $field Field name.
 * @param mixed $value Field value.
 * @throws E_OTS_NotLoaded If account is not loaded.
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

        $this->db->query('UPDATE ' . $this->db->tableName('accounts') . ' SET ' . $this->db->fieldName($field) . ' = ' . $value . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);
    }

/**
 * List of characters on account.
 * 
 * <p>
 * In difference to {@link OTS_Account::getPlayers() getPlayers() method} this method returns filtered {@link OTS_Players_List OTS_Players_List} object instead of array of {@link OTS_Player OTS_Player} objects. It is more effective since OTS_Player_List doesn't perform all rows loading at once.
 * </p>
 * 
 * <p>
 * Note: Returned object is only prepared, but not initialised. When using as parameter in foreach loop it doesn't matter since it will return it's iterator, but if you will wan't to execute direct operation on that object you will need to call {@link OTS_Base_List::rewind() rewind() method} first.
 * </p>
 * 
 * @version 0.1.4
 * @since 0.0.5
 * @return OTS_Players_List List of players from current account.
 * @throws E_OTS_NotLoaded If account is not loaded.
 */
    public function getPlayersList()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // creates filter
        $filter = new OTS_SQLFilter();
        $filter->compareField('account_id', (int) $this->data['id']);

        // creates list object
        $list = new OTS_Players_List();
        $list->setFilter($filter);

        return $list;
    }

/**
 * Deletes account.
 * 
 * <p>
 * This method physicly deletes account from database! To set <i>deleted</i> flag use {@link OTS_Account::setDeleted() setDeleted() method}.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @throws E_OTS_NotLoaded If account is not loaded.
 * @throws PDOException On PDO operation error.
 */
    public function delete()
    {
        if( !isset($this->data['id']) )
        {
            throw new E_OTS_NotLoaded();
        }

        // deletes row from database
        $this->db->query('DELETE FROM ' . $this->db->tableName('accounts') . ' WHERE ' . $this->db->fieldName('id') . ' = ' . $this->data['id']);

        // resets object handle
        unset($this->data['id']);
    }

/**
 * Checks highest access level of account.
 * 
 * @version 0.0.1
 * @return int Access level (highest access level of all characters).
 * @throws PDOException On PDO operation error.
 */
    public function getAccess()
    {
        // by default
        $access = 0;

        // finds groups of all characters
        foreach( $this->getPlayersList() as $player)
        {
            $group = $player->getGroup();

            // checks if group's access level is higher then previouls found highest
            if( $group->getAccess() > $access)
            {
                $access = $group->getAccess();
            }
        }

        return $access;
    }

/**
 * Checks highest access level of account in given guild.
 * 
 * @version 0.0.1
 * @param OTS_Guild $guild Guild in which access should be checked.
 * @return int Access level (highest access level of all characters).
 * @throws PDOException On PDO operation error.
 */
    public function getGuildAccess(OTS_Guild $guild)
    {
        // by default
        $access = 0;

        // finds ranks of all characters
        foreach( $this->getPlayersList() as $player)
        {
            $rank = $player->getRank();

            // checks if rank's access level is higher then previouls found highest
            if( isset($rank) && $rank->getGuild()->getId() == $guild->getId() && $rank->getLevel() > $access)
            {
                $access = $rank->getLevel();
            }
        }

        return $access;
    }

/**
 * Returns players iterator.
 * 
 * <p>
 * There is no need to implement entire Iterator interface since we have {@link OTS_Players_List players list class} for it.
 * </p>
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @throws E_OTS_NotLoaded If account is not loaded.
 * @throws PDOException On PDO operation error.
 * @return Iterator List of players.
 */
    public function getIterator()
    {
        return $this->getPlayersList();
    }

/**
 * Returns number of player within.
 * 
 * @version 0.0.5
 * @since 0.0.5
 * @throws E_OTS_NotLoaded If account is not loaded.
 * @throws PDOException On PDO operation error.
 * @return int Count of players.
 */
    public function count()
    {
        return $this->getPlayersList()->count();
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws E_OTS_NotLoaded If account is not loaded.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __get($name)
    {
        switch($name)
        {
            case 'id':
                return $this->getId();

            case 'name':
                return $this->getName();

            case 'password':
                return $this->getPassword();

            case 'eMail':
                return $this->getEMail();

            case 'premiumEnd':
                return $this->getPremiumEnd();

            case 'loaded':
                return $this->isLoaded();

            case 'playersList':
                return $this->getPlayersList();

            case 'blocked':
                return $this->isBlocked();

            case 'warnings':
                return $this->getWarnings();

            case 'deleted':
                return $this->isDeleted();

            case 'warned':
                return $this->isWarned();

            case 'access':
                return $this->getAccess();

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @param string $name Property name.
 * @param mixed $value Property value.
 * @throws E_OTS_NotLoaded If account is not loaded.
 * @throws OutOfBoundsException For non-supported properties.
 * @throws PDOException On PDO operation error.
 */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'name':
                $this->setName($name);
                break;

            case 'password':
                $this->setPassword($value);
                break;

            case 'eMail':
                $this->setEMail($value);
                break;

            case 'premiumEnd':
                $this->setPremiumEnd($value);
                break;

            case 'blocked':
                if($value)
                {
                    $this->block();
                }
                else
                {
                    $this->unblock();
                }
                break;

            case 'warnings':
                $this->setWarnings($value);
                break;

            case 'deleted':
                if($value)
                {
                    $this->setDeleted();
                }
                else
                {
                    $this->unsetDeleted();
                }
                break;

            case 'warned':
                if($value)
                {
                    $this->warn();
                }
                else
                {
                    $this->unwarn();
                }
                break;

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Returns string representation of object.
 * 
 * <p>
 * If any display driver is currently loaded then it uses it's method. Otherwise just returns account number.
 * </p>
 * 
 * @version 0.2.0+SVN
 * @since 0.1.0
 * @return string String representation of object.
 */
    public function __toString()
    {
        // checks if display driver is loaded
        if( POT::isDisplayDriverLoaded() )
        {
            return POT::getDisplayDriver()->displayAccount($this);
        }

        return $this->getId();
    }
}

?>
