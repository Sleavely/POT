<?php

// includes POT main file
include_once('classes/OTS.php');

$ots = POT::getInstance();

// connects to database
$ots->connect(POT::DB_MYSQL, array('host' => 'localhost', 'user' => 'wrzasq', 'database' => 'otserv') );

// load all resources
$ots->loadVocations('/home/wrzasq/.otserv/data/vocations.xml');
$ots->loadMonsters('/home/wrzasq/.otserv/data/monster/');
$ots->loadSpells('/home/wrzasq/.otserv/data/spells/spells.xml');
$ots->loadItems('/home/wrzasq/.otserv/data/items/');
$ots->loadMap('/home/wrzasq/.otserv/data/world/map.otbm');

/*
    Invites handling driver.
*/

class InvitesDriver implements IOTS_GuildAction
{
/* implement IOTS_GuildAction here */
}

/*
    Membership requests handling driver.
*/

class RequestsDriver implements IOTS_GuildAction
{
/* implement IOTS_GuildAction here */
}

/*
    Standard binary format cache.
*/

class FileCache implements IOTS_FileCache
{
/* implement IOTS_FileCache here */
}

/*
    Items cache driver.
*/

class ItemsCache implements IOTS_ItemsCache
{
/* implement IOTS_ItemsCache here */
}

/*
    Database objects display driver.
*/

class DisplayDriver implements IOTS_Display
{
/* implement IOTS_Display here */
}

/*
    Display driver for data/ directory resources.
*/

class DataDisplayDriver implements IOTS_DataDisplay
{
/* implement IOTS_DataDisplay here */
}

// sets display drivers for current enviroment
$ots->setDisplayDriver( new DisplayDriver() );
$ots->setDataDisplayDriver( new DataDisplayDriver() );

?>
