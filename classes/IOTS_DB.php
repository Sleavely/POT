<?php

/**#@+
 * @version 0.0.1
 * @since 0.0.1
 */

/**
 * @package POT
 * @version 0.0.6
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTServ database handler interface.
 * 
 * This interface specifies routines requires by DAO classes.
 * 
 * @package POT
 * @version 0.0.6
 * @deprecated 0.0.5 Don't rely on this interface - it is for backward compatibility only. Check PDO instance instead.
 */
interface IOTS_DB
{
/**
 * Connection parameters.
 * 
 * @version 0.0.6
 * @param array $params Connection configuration.
 */
    public function __construct($params);

/**
 * Query-quoted field name.
 * 
 * @param string $name Field name.
 * @return string Quoted name.
 */
    public function fieldName($name);
/**
 * Query-quoted table name.
 * 
 * @param string $name Table name.
 * @return string Quoted name.
 */
    public function tableName($name);
/**
 * Query-quoted string value.
 * 
 * @param string $value Value to be quoted to be suitable for database query.
 * @return string Quoted string.
 */
    public function SQLquote($value);
/**
 * Evaluates query.
 * 
 * @param string $query Database query.
 * @return mixed Results set.
 */
    public function SQLquery($query);
/**
 * ID of last created record.
 * 
 * @return int Primary key value of new row.
 */
    public function lastInsertId();
/**
 * LIMIT/OFFSET clause for queries.
 * 
 * @param int|bool $limit Limit of rows to be affected by query (false if no limit).
 * @param int|bool $offset Number of rows to be skipped before applying query effects (false if no offset).
 * @return string LIMIT/OFFSET SQL clause for query.
 */
    public function limit($limit = false, $offset = false);
}

/**#@-*/

?>
