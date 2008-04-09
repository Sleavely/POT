<?php

// to not repeat all that stuff
include('quickstart.php');

// creates new account objects
$account = new OTS_Account(111111);

// this is your password
$password = 'secret';

// so if you use for example SHA1 hashing simply before any operation do
$password = sha1($password);

// saves password hash into database
$account->setPassword($password);
$account->save();

?>
