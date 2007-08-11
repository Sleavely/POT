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
 * SQLite connection interface.
 * 
 * @package POT
 */
class OTS_DB_SQLite extends PDO implements IOTS_DB
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
 * Connects to SQLite database on given arguments.
 * 
 * <p>
 * List of parameters for this drivers:
 * </p>
 * 
 * - <var>database</var> - database name.
 * 
 * @param array $params Connection parameters.
 * @see POT::connect()
 */
    public function __construct(array $params)
    {
        if( isset($params['prefix']) )
        {
            $this->prefix = $params['prefix'];
        }

        // PDO constructor
        parent::__construct('sqlite:' . $params['database']);

        // this class will drop quotes from field names
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('OTS_SQLite_Results') );
        // function to handle REGEXP operator
        $this->sqliteCreateFunction('regexp', array($this, 'regexp') );
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
 * REGEXP operator for SQLite
 * 
 * @internal For SQLite REGEXP driver.
 * @param string $name Regular expression to test.
 * @param string $content String to test.
 * @return bool Test results.
 */
    public function regexp($name, $content)
    {
        return preg_match('#' . str_replace('#', '\\#', $name) . '#', $content) != 0;
    }

/**
 * IOTS_DB method.
 * 
 * Overwrites PDO method - we won't use quoting agains other values.
 * 
 * @param stirng $string String to be quoted.
 * @return string Quoted string.
 * @internal bridge over ISQL_DB and PDO.
 */
    public function SQLquote($string)
    {
        return parent::quote($string, PDO::PARAM_STR);
    }

/**
 * IOTS_DB method.
 * 
 * Overwrites PDO method.
 * 
 * @param string $query SQL query.
 * @return PDOStatement|bool Query results.
 * @internal bridge over ISQL_DB and PDO.
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
