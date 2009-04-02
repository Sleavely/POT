<?php

// this is the way you should work with POT if you moved main OTS.php file outside POT's directory
include('path/to/OTS.php');

// dont use 'new POT()'!!!
// dont use POT::getInstance() anymore
POT::setPOTPath('classes');

/*
    here comes your stuff...
*/

?>
