<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.3
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Base class for all database drivers.
 * 
 * <p>
 * It defines additional rotines required by database driver for POT using default SQL standard-compliant method.
 * </p>
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.3
 */
abstract class OTS_Base_DB extends PDO
{
/**
 * Tables prefix.
 * 
 * @version 0.1.3
 * @since 0.1.3
 * @var string
 */
    private $prefix = '';

/**
 * Query-quoted field name.
 * 
 * @version 0.1.3
 * @since 0.1.3
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
 * @version 0.1.3
 * @since 0.1.3
 * @param string $name Table name.
 * @return string Quoted name.
 */
    public function tableName($name)
    {
        return $this->fieldName($this->prefix . $name);
    }

/**
 * LIMIT/OFFSET clause for queries.
 * 
 * @version 0.1.3
 * @since 0.1.3
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

?>
