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
