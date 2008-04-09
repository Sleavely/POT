<?php

// to not repeat all that stuff
include('quickstart.php');

// server and port
$server = '127.0.0.1';
$port = 7171;

// gets POT class instance
$ots = POT::getInstance();

// queries server of status info
$status = $ots->serverStatus($server, $port);

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
    echo 'Required client version: ', $status->getClientVersion(), "\n";
    echo 'All monsters: ', $status->getMonstersCount(), "\n";
    echo 'Server message: ', $status->getMOTD(), "\n";
}

?>
