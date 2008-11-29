<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new account object
$account = new OTS_Account();

// generates new account
$name = $account->createNamed();

// give user his name
echo 'Your account name is: ', $name;

?>
