<?php

// includes POT main file
include_once('classes/OTS.php');

// database configuration - can be simply moved to external file, eg. config.php
$config = array(
    'driver' => POT::DB_MYSQL,
//    'prefix' => '',
    'host' => 'localhost',
    'user' => 'wrzasq',
//    'password' => '',
    'database' => 'otserv'
);

// creates POT instance (or get existing one)
// dont use POT::getInstance() anymore
POT::connect(null, $config);
// could be: POT::connect(POT::DB_MYSQL, $config);

?>
