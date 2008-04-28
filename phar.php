<?php

$phar = new Phar('POT.phar');

$files = array();

$files['compat.php'] = 'compat.php';
$files['bin/cli.php'] = 'phk/cli.php';
$files['bin/webinfo.php'] = 'phk/webinfo.php';
$files['info/AUTHORS'] = '../../otserv/trunk/AUTHORS';
$files['info/CHANGELOG'] = 'CHANGELOG';
$files['info/INSTALL'] = 'INSTALL';
$files['info/LICENSE'] = 'LICENSE';
$files['info/LICENSE.PEAR.CRYPT_RSA'] = 'LICENSE.PEAR.CRYPT_RSA';
$files['info/LICENSE.PEAR.CRYPT_XTEA'] = 'LICENSE.PEAR.CRYPT_XTEA';
$files['info/NEWS'] = 'NEWS';
$files['info/README'] = 'README';
$files['info/RULES'] = 'RULES';

foreach( new RegexIterator( new DirectoryIterator('classes'), '/\.php$/i') as $file)
{
    $file = $file->getFileName();
    $files['classes/' . $file] = 'classes/' . $file;
}

$phar->buildFromIterator( new ArrayIterator($files) );

$phar->setStub( $phar->createDefaultStub('bin/cli.php', 'bin/webinfo.php') );

?>
