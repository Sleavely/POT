<?php

/**#@+
 * @version 0.0.2
 */

/**
 * POT compatibility assurance package.
 * 
 * This package makes you sure that POT scripts won't cause FATAL errors on PHP older PHP 5.x versions. However remember that some PHP features won't be enabled with it. For example if you have PHP 5.0.x, this package will define Countable interface for you so PHP will know it, but it won't allow you to use count($countableObject) structure.
 * 
 * @package POT
 * @subpackage compat
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

// Countable for PHP 5.0.x
if( !interface_exists('Countable') )
{
/**
 * @ignore
 */
    interface Countable
    {
        public function count();
    }
}

// spl_autoload_register() walkaround
if( !function_exists('spl_autoload_register') )
{
/**
 * @ignore
 */
    function spl_autoload_register($callback)
    {
        if( !function_exists('__autoload') )
        {
/**
 * @ignore
 */
            function __autoload($class)
            {
                POT::getInstance()->loadClass($class);
            }
        }
    }
}

/**#@-*/

?>
