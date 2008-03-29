<?php

// this is the way you should work with POT if you moved main OTS.php file outside POT's directory
include('path/to/OTS.php');

// dont use 'new POT()'!!!
$ots = POT::getInstance();
$ots->setPOTPath('../classes/');

/*
    here comes your stuff...
*/

?>
