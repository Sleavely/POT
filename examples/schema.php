<?php

// to not repeat all that stuff
include('quickstart.php');

// fetches schema info
$info = POT::getSchemaInfo();

// displays database version
echo 'Your database structure version is: ', $info['version'];

?>
