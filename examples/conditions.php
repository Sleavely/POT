<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new players list object
$players = new OTS_Players_List();

// creates filter
$filter = new OTS_SQLFilter();

// sets filter to choose players with capacity equal to hit points
$filter->addFilter( new OTS_SQLField('cap'), new OTS_SQLField('health') );

// another filter
$sub = new OTS_SQLFilter();

// only players with level 8 and higher...
$sub->compareField('level', 8, OTS_SQLFilter::OPERATOR_NLOWER);

// ... OR magic level 5 and higher
$sub->compareField('maglevel', 5, OTS_SQLFilter::OPERATOR_NLOWER, OTS_SQLFilter::CRITERIUM_OR);

// final result is:
// "cap" = "health" AND ("level" = 8 OR "maglevel" = 5)
$filter->addFilter($sub)

// sets filter on list
$players->setFilter($filter);

// iterates throught selected players
foreach($players as $index => $player)
{
    $player->getName(), "\n";
}

?>
