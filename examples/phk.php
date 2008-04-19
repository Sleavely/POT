<?php

// includes POT package
include_once('POT.phk');

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
$ots = POT::getInstance();
$ots->connect(null, $config);
// could be: $ots->connect(POT::DB_MYSQL, $config);

?>
