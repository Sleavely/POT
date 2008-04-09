<?php

// to not repeat all that stuff
include('quickstart.php');

// your non-random number
$number = 123456;

// creates new account object
$account = new OTS_Account();
$account->load($number);

// number is busy
if( $account->isLoaded() )
{
    echo 'Account number ', $number, ' is used.', "\n";
}
// it is not
else
{
    // generate number from exacly $number - $number range
    $number = $account->create($number, $number);
    echo 'Your account number is: ', $number, '.', "\n";
}

?>
