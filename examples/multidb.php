<?php

// includes POT main file
include_once('classes/OTS.php');

// define database ids
define(DB_OTSERV1, 100);
define(DB_OTSERV2, 101);


// Config of first database
$config_db1 = array(
	'driver' => POT::DB_MYSQL,
    'prefix' => '',
    'host' => 'localhost',
    'user' => 'wrzasq',
	'password' => '',
    'database' => 'otserv1'
);

// Config of second database
$config_db2 = array(
    'driver' => POT::DB_MYSQL,
    'prefix' => '',
    'host' => 'localhost',
	'user' => 'wrzasq',
	'password' => '',
    'database' => 'otserv2'
);

// Catch PDO Exceptions!
try
{
	// Set the database we want to use
	POT::setCurrentDB(DB_OTSERV1);
	POT::connect(null, $config_db1);
	
	// Change to another ID to connect
	POT::setCurrentDB(DB_OTSERV2);
	POT::connect(null, $config_db2);
}
catch (Exception $e)
{
	var_dump($e->getMessage());
}

// To use a database you must set it with POT::setCurrentDB(DB_ID)
POT::setCurrentDB(DB_OTSERV1);

// Then you can get the DB Handle
$ot_db = POT::getDBHandle();

// ... and use it!

?>
