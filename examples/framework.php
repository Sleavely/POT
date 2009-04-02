<?php

// includes POT main file
include_once('classes/OTS.php');

// dont use POT::getInstance() anymore

// connects to database
POT::connect(POT::DB_MYSQL, array('host' => 'localhost', 'user' => 'wrzasq', 'database' => 'otserv') );

// load all resources
POT::loadVocations('/home/wrzasq/.otserv/data/vocations.xml');
POT::loadMonsters('/home/wrzasq/.otserv/data/monster/');
POT::loadSpells('/home/wrzasq/.otserv/data/spells/spells.xml');
POT::loadItems('/home/wrzasq/.otserv/data/items/');
POT::loadMap('/home/wrzasq/.otserv/data/world/map.otbm');

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
POT::setDisplayDriver( new DisplayDriver() );
POT::setDataDisplayDriver( new DataDisplayDriver() );

?>
