<?php
require_once( dirname( __FILE__ ) . "/../lib/php-sam.php" );

$sam = new \PHP_SAM\SAM3();
$sam->connect();
$samSession = $sam->connectSession( "zzz", "zzz.i2p" );

var_dump( $samSession );

$socket = $samSession->getSocket();

var_dump( $socket );