<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new OTS_Account object
$account = new OTS_Account();

// group for account
$group = new OTS_Group();

// loads group with id 1
$group->load(1);

// generates new account number
$number = $account->createEx($group);

// give user his number
echo 'Your account number is: ', $number;

?>
