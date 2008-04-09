<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new players list object
$players = new OTS_Players_List();

// creates filter
$filter = new OTS_SQLFilter();

// sets filter to choose only players with level 8
$filter->compareField('level', 8);

// sets filter on list
$players->setFilter($filter);

// iterates throught selected players
foreach($players as $index => $player)
{
    $player->getName(), "\n";
}

?>
