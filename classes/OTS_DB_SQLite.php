<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * SQLite connection interface.
 * 
 * <p>
 * At all everything that you really need to read from this class documentation is list of parameters for driver's constructor.
 * </p>
 * 
 * @package POT
 * @version 0.2.0+SVN
 */
class OTS_DB_SQLite extends OTS_Base_DB
{
/**
 * Creates database connection.
 * 
 * <p>
 * Connects to SQLite database on given arguments.
 * <p>
 * 
 * <p>
 * List of parameters for this drivers:
 * </p>
 * 
 * <ul>
 * <li><var>database</var> - database name.</li>
 * </ul>
 * 
 * @version 0.0.7
 * @param array $params Connection parameters.
 * @throws PDOException On PDO operation error.
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
    }
}

?>
