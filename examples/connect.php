<?php

// includes POT main file
include('../classes/OTS.php');

// you can easily store such structure in config.php
$config = array(
    'driver' => POT::DB_MYSQL,
    'prefix' => '',
    'host' => 'localhost',
    'user' => 'wrzasq',
    'password' => '',
    'database' => 'otserv'
);

// connects to database
$ots = POT::getInstance();
$ots->connect(null, $config);
// could be: $ots->connect(POT::DB_MYSQL, $config);

?>
