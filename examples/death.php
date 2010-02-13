<?php

// to not repeat all that stuff
include_once('quickstart.php');

$deaths = new OTS_Deaths_List();
$deaths->setLimit(10);

foreach($deaths as $index => $death)
{
	echo "Player " . $death->getPlayer()->getName() . " got killed by ";
	$kills = $death->getKillsList();
	foreach($kills as $index2 => $kill)
	{	
		$monsters = $kill->getEnvironments();
		foreach($monsters as $index3 => $monster)
        	{
        	   echo $monster . ", ";
		}
		$players = $kill->getPlayersList();
		foreach($players as $index3 => $player)
		{
			echo $player . ", ";
		}
	}
	echo "<br />";
}
?>
