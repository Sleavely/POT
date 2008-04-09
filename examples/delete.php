<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new player object
$player = new OTS_Player('Wrzasq');

// deletes player
$player->delete();

// as player was deleted this will insert new row into database but all data will be restored
$player->save();

?>
