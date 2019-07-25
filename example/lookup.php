<?php
require_once( dirname( __FILE__ ) . "/../lib/php-sam.php" );

$sam = new \PHP_SAM\SAM3();
$sam->connect();
$reply = $sam->lookupName( "zzz.i2p" );

echo $reply . PHP_EOL;