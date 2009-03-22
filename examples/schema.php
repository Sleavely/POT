<?php

// to not repeat all that stuff
include('quickstart.php');

// fetches schema info
$info = $ots->getSchemaInfo();

// displays database version
echo 'Your database structure version is: ', $info['version'];

?>
