<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new account object
$account = new OTS_Account();

// loads account with ID (account number) 111111
$account->load(111111);

// alternative way - shorter
$account2 = new OTS_Account(111111);

// WARNING! This one won't work!
//$account2 = new OTS_Account('111111');
// '111111' is a string! not integer

// shows results of loading account
echo '111111 e-mail address: ', $account->getEMail();

?>
