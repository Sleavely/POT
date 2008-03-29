<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new OTS_Player object
$players = new OTS_Players_List();

// count of all players - Countable interface implemented
echo 'There are ' . count($players) . ' players in our database.', "\n";

// sets limitation
$players->setLimit(10);
$players->setOffset(2);

// iterates throught selected players
foreach($players as $index => $player)
{
    // each returned item is instance of OTS_Player class
    echo (2 + $index) . ': ' . $player->getName(), "\n";
}

?>
