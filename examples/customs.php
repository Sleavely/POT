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

// creates new OTS_Player object
$player = new OTS_Player();

// sets basic fields
$player->setName('Wrzasq');
$player->setSex(POT::SEX_MALE);
$player->setVocation(POT::VOCATION_KNIGHT);
/* etc... */

/*
this is bad! we can't call this now as we dont have object ID assinged yet

$player->setCustomField('my_field', 2);

must save before that to get automatic ID:
*/
$player->save();

// now we can call that:
// 2 won't be quoted - it's integer
$player->setCustomField('my_field', 2);
// 3 will be quoted - '3' is a string!
$player->setCustomField('another_field', '3');

?>
