<?php

/**#@+
 * @version 0.0.4
 * @since 0.0.4
 */

/**
 * @package POT
 * @version 0.1.3+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * ODBC connection interface.
 * 
 * <p>
 * At all everything that you really need to read from this class documentation is list of parameters for driver's constructor.
 * </p>
 * 
 * @package POT
 * @version 0.1.3+SVN
 */
class OTS_DB_ODBC extends PDO implements IOTS_DB
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
 * <p>
 * Connects to ODBC data source on given arguments.
 * </p>
 * 
 * <p>
 * List of parameters for this drivers:
 * </p>
 * 
 * <ul>
 * <li><var>host</var> - database host.</li>
 * <li><var>port</var> - ODBC driver.</li>
 * <li><var>database</var> - database name.</li>
 * <li><var>user</var> - user login.</li>
 * <li><var>password</var> - user password.</li>
 * <li><var>source</var> - ODBC data source.</li>
 * </ul>
 * 
 * <p>
 * Note: Since 0.1.3+SVN version <var>source</var> parameter was added.
 * </p>
 * 
 * @version 0.1.3+SVN
 * @param array $params Connection parameters.
 * @throws PDOException On PDO operation error.
 */
    public function __construct($params)
    {
        $user = null;
        $password = null;
        $dns = array();

        if( isset($params['host']) )
        {
            $dns[] = 'HOSTNAME={' . $params['host'] . '}';
        }

        if( isset($params['port']) )
        {
            $dns[] = 'DRIVER={' . $params['port'] . '}';
        }

        if( isset($params['database']) )
        {
            $dns[] = 'DATABASE={' . $params['database'] . '}';
        }

        if( isset($params['user']) )
        {
            $user = $params['user'];
            $dns[] = 'UID={' . $user . '}';
        }

        if( isset($params['password']) )
        {
            $password = $params['password'];
            $dns[] = 'PWD={' . $user . '}';
        }

        if( isset($params['prefix']) )
        {
            $this->prefix = $params['prefix'];
        }

        // composes DNS
        $dns = implode(';', $dns);

        // source parameter overwrites all other params
        if( isset($params['source']) )
        {
            $dns = $params['source'];
        }

        // PDO constructor
        parent::__construct('odbc:' . $dns, $user, $password);
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
 * @param stirng $string String to be quoted.
 * @return string Quoted string.
 * @deprecated 0.0.5 Use PDO::quote().
 * @version 0.0.7
 */
    public function SQLquote($string)
    {
        return parent::quote($string, PDO_PARAM_STR);
    }

/**
 * @param string $query SQL query.
 * @return PDOStatement|bool Query results.
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
