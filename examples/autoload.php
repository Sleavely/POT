<?php

/**
 * @ignore
 * @package examples
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

// includes POT main file
include('../classes/OTS.php');

function __autoload($class)
{
    // checks if it's POT class
    if( preg_match('/^I?OTS_/', $class) != 0)
    {
        POT::getInstance()->loadClass($class);
    }
/*
    // possibly call your own __autoload() handler
    else
    {
        here comes your stuff...
    }
*/
}

?>
