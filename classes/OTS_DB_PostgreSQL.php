<?php

/**#@+
 * @version 0.0.4
 * @since 0.0.4
 */

/**
 * @package POT
 * @version 0.0.6
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * PostgreSQL connection interface.
 * 
 * @package POT
 * @version 0.0.6
 */
class OTS_DB_PostgreSQL extends PDO implements IOTS_DB
{
/**
 * Tables prefix.
 * 
 * @var string
 */
    private $prefix = '';

/**
 * Creates database connection.
 * 
 * Connects to PgSQL database on given arguments.
 * 
 * <p>
 * List of parameters for this drivers:
 * </p>
 * 
 * - <var>host</var> - database server.
 * - <var>port</var> - port (optional, also it is possible to use host:port in <var>host</var> parameter).
 * - <var>database</var> - database name.
 * - <var>user</var> - user login.
 * - <var>password</var> - user password.
 * 
 * @version 0.0.6
 * @param array $params Connection parameters.
 * @see POT::connect()
 */
    public function __construct($params)
    {
        $user = null;
        $password = null;
        $dns = array();

        // host:port support
        if( strpos(':', $params['host']) !== false)
        {
            $host = explode(':', $params['host'], 2);

            $params['host'] = $host[0];
            $params['port'] = $host[1];
        }

        if( isset($params['host']) )
        {
            $dns[] = 'host=' . $params['host'];
        }

        if( isset($params['port']) )
        {
            $dns[] = 'port=' . $params['port'];
        }

        if( isset($params['database']) )
        {
            $dns[] = 'dbname=' . $params['database'];
        }

        if( isset($params['user']) )
        {
            $user = $params['user'];
        }

        if( isset($params['password']) )
        {
            $password = $params['password'];
        }

        if( isset($params['prefix']) )
        {
            $this->prefix = $params['prefix'];
        }

        // PDO constructor
        parent::__construct('pgsql:' . implode(' ', $dns), $user, $password);
    }

/**
 * Query-quoted field name.
 * 
 * @param string $name Field name.
 * @return string Quoted name.
 */
    public function fieldName($name)
    {
        return '"' . $name . '"';
    }

/**
 * Query-quoted table name.
 * 
 * @param string $name Table name.
 * @return string Quoted name.
 */
    public function tableName($name)
    {
        return $this->fieldName($this->prefix . $name);
    }

/**
 * IOTS_DB method.
 * 
 * Overwrites PDO method - we won't use quoting agains other values.
 * 
 * @param stirng $string String to be quoted.
 * @return string Quoted string.
 * @internal bridge over ISQL_DB and PDO.
 * @deprecated 0.0.5 Use PDO::quote().
 * @version 0.0.7
 */
    public function SQLquote($string)
    {
        return parent::quote($string, PDO_PARAM_STR);
    }

/**
 * IOTS_DB method.
 * 
 * Overwrites PDO method.
 * 
 * @param string $query SQL query.
 * @return PDOStatement|bool Query results.
 * @internal bridge over ISQL_DB and PDO.
 * @deprecated 0.0.5 Use PDO::query().
 */
    public function SQLquery($query)
    {
        return parent::query($query);
    }

/**
 * LIMIT/OFFSET clause for queries.
 * 
 * @param int|bool $limit Limit of rows to be affected by query (false if no limit).
 * @param int|bool $offset Number of rows to be skipped before applying query effects (false if no offset).
 * @return string LIMIT/OFFSET SQL clause for query.
 */
    public function limit($limit = false, $offset = false)
    {
        // by default this is empty part
        $sql = '';

        if($limit !== false)
        {
            $sql = ' LIMIT ' . $limit;

            // OFFSET has no effect if there is no LIMIT
            if($offset !== false)
            {
                $sql .= ' OFFSET ' . $offset;
            }
        }

        return $sql;
    }
}

/**#@-*/

?>
