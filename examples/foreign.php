<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new players list object
$players = new OTS_Players_List();

// creates filter
$filter = new OTS_SQLFilter();

// selects player whose rank belongs to guild with ID 5
$filter->addFilter( new OTS_SQLField('rank_id', 'players'), new OTS_SQLField('id', 'ranks') );
$filter->addFilter( new OTS_SQLField('guild_id', 'ranks'), 5);

// sets filter on list
$players->setFilter($filter);

// iterates throught selected players
foreach($players as $index => $player)
{
    echo $player->getName(), "\n";
}

?>
