<?php

/**#@+
 * @version 0.0.5
 * @since 0.0.1
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTserv database object.
 * 
 * This insterface indicates that class is a OTServ DAO class.
 * 
 * @package POT
 */
interface IOTS_DAO
{
/**
 * DAO objects must be initialized with a database.
 * 
 * @param PDO $db Database connection object.
 * @deprecated 0.0.5 This constructor convention won't be part of interface in future.
 */
    public function __construct(PDO $db);
}

/**#@-*/

?>
