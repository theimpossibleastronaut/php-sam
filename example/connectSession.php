<?php
require_once( dirname( __FILE__ ) . "/../lib/php-sam.php" );

$sam = new \PHP_SAM\SAM3();
$sam->connect();

$sam->createSession( "zzz" );
$samSession = $sam->connectSession( "zzz", "zzz.i2p" );

var_dump( $samSession );

$socket = $samSession->getSocket();

var_dump( $socket );

// Now use socket_write and socket_read to use the socket you're given <3