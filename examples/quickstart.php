<?php

/**
 * @ignore
 * @package examples
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

// binds your __autoload code
if( function_exists('__autoload') )
{
    spl_autoload_register('__autoload');
}

// includes POT main file
include('../classes/OTS.php');

// database configuration - can be simply moved to external file, eg. config.php
$config = array(
    'driver' => POT::DB_MYSQL,
    'host' => 'localhost',
    'user' => 'wrzasq',
    'database' => 'otserv'
);

// creates POT instance (or get existing one)
$ots = POT::getInstance();
$ots->connect(null, $config);

?>
