<?php

// to not repeat all that stuff
include('quickstart.php');

// loads map file
$towns = new OTS_OTBMFile('/home/wrzasq/.otserv/data/world/map.otbm');

// lists towns
foreach($towns as $id => $name)
{
    echo 'Town ID: ', $id, ', name: ', $name, "\n";
}

?>
