<?php

// to not repeat all that stuff
include('quickstart.php');

// loads vocations
$vocations = new OTS_VocationsList('/home/wrzasq/.otserv/data/vocations.xml');

// translates ID to vocation name
echo $vocations->getVocationName(1);

// translates vocations name to it's ID
echo $vocations->getVocationID('Knight');

?>
