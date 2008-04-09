<?php

// OTS_Admin class automaticly negotaites encryption, generates XTEA key and negotiates RSA encryption
$admin = new OTS_Admin('127.0.0.1');

// checks if server requires logging in
if( $admin->requiresLogin() )
{
    $admin->login('password');
}

//commands examples:

// ping (in seconds)
echo 'Server ping: ', $admin->ping(), ' s', "\n";

// sends broadcast message to all players
$admin->broadcast('Server is going down for maintenance');

// closes server for new connections - server is still running
$admin->close();

// requests all rented houses to be paid on server
$admin->payHouses();

// shuts server down - closes it physicly
$admin->shutdown();

?>
