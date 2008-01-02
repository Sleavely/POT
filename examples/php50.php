<?php

/**
 * @ignore
 * @package examples
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

// do that before any POT operations!
include('../compat.php');

// to not repeat all that stuff
include('quickstart.php');

// STEP 1: no error here - even thought we loaded class that implements Countable interface which does not exists in PHP 5.0 SPL library, because 'compat' library defines it.
$list = new OTS_Players_List();

// STEP 2: we can do that in every version - count() is in fact just a public method
echo $list->count();

// STEP 3: it won't work correctly in PHP 5.0 - PHP won't call internaly count() method of object, will print trivial count() evaluation result on object
echo count($list);

?>
