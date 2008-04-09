<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new account object
$account = new OTS_Account();

// generates new account number
$number = $account->create();

// give user his number
echo 'Your account number is: ', $number;

?>
