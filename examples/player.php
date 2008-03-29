<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new OTS_Player object
$player = new OTS_Player();

// loads player
$player->find('Wrzasq');

// checks if player exists
if( $player->isLoaded() )
{
    // prints character info
    echo 'Player \'' . $player->getName() . '\' has ' . $player->getLevel() . ' level.', "\n";

    // example of associated objects retriving
    echo 'Player \'' . $player->getName() . '\' is member of ' . $player->getGroup()->getName() . ' group.', "\n";
}
else
{
    echo 'Player does not exists.', "\n";
}

?>
