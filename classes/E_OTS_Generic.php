<?php

/**
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.1
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2009 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Generic exception class for general exceptions.
 * 
 * @package POT
 * @version 0.2.0+SVN
 * @since 0.1.1
 */
class E_OTS_Generic extends E_OTS_ErrorCode
{
/**
 * No database driver speciffied.
 * 
 * @version 0.1.1
 * @since 0.1.1
 */
    const CONNECT_NO_DRIVER = 1;
/**
 * Invalid database driver.
 * 
 * @version 0.1.1
 * @since 0.1.1
 */
    const CONNECT_INVALID_DRIVER = 2;
}

?>
