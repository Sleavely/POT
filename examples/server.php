<?php

// to not repeat all that stuff
include('quickstart.php');

// server and port
$server = '127.0.0.1';
$port = 7171;

// queries server of status info
$info = new OTS_ServerInfo($server, $port);
$status = $info->info(OTS_ServerStatus::REQUEST_BASIC_SERVER_INFO | OTS_ServerStatus::REQUEST_OWNER_SERVER_INFO | OTS_ServerStatus::REQUEST_MISC_SERVER_INFO | OTS_ServerStatus::REQUEST_PLAYERS_INFO | OTS_ServerStatus::REQUEST_MAP_INFO);

// offline
if(!$status)
{
    echo 'Server ', $server, ' is offline.', "\n";
}
// displays various info
else
{
    echo 'Server name: ', $status->getName(), "\n";
    echo 'Server owner: ', $status->getOwner(), "\n";
    echo 'Players online: ', $status->getOnlinePlayers(), "\n";
    echo 'Maximum allowed number of players: ', $status->getMaxPlayers(), "\n";
    echo 'Server message: ', $status->getMOTD(), "\n";
}

// checks if given player is online
if( $info->playerStatus('Hurz') )
{
    echo 'Hurz is online', "\n";
}
else
{
    echo 'Hurz is offline', "\n";
}

// list online players
foreach( $info->players() as $player => $level)
{
    echo 'Player: ', $player, ' at level ', $level, "\n";
}

?>
