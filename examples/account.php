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

// creates new OTS_Account object
$account = $ots->createObject('Account');

// generates new account number
$number = $account->create();

/*
to generate number from 111111 to 999999 use:
$number = $account->create(111111, 999999);
*/

// sets account info
$account->setPassword('secret'); // $account->setPassword( md5('secret') );
$account->setEMail('foo@example.com');
$account->unblock(); // remember to unblock!
$account->setPACCDays(0);
$account->save();

// give user his number
echo 'Your account number is: ', $number;

?>
