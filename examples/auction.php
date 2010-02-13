<?php

// to not repeat all that stuff
include_once('quickstart.php');

$auctions = new OTS_Auctions_List();

foreach($auctions as $i => $auction)
{
	echo $auction->getId() . " ";
	echo $auction->getPlayer()->getName() . " ";
	echo $auction->getBid() . " ";
	echo $auction->getLimit() . " ";
	echo $auction->getEndtime() . "<br />";
}

?>