<?php

// OTS_Admin class automaticly negotaites encryption, generates XTEA key and negotiates RSA encryption
$admin = new OTS_Admin('127.0.0.1');

/* put your login steps here */

// closes server for new connections - server is still running
$admin->close();

// save current state
$admin->save();

// open it again
$admin->open();

?>
