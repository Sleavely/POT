<?php

/**
 * @ignore
 * @package examples
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

// to not repeat all that stuff
include('quickstart.php');

// your non-random number
$number = 123456;

// creates new OTS_Account object
$account = $ots->createObject('Account');
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
