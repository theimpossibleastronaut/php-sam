<?php
require_once( dirname( __FILE__ ) . "/../lib/php-sam.php" );

$sam = new \PHP_SAM\SAM3();
$sam->connect();
$samSession = $sam->createSession( "ircsession" );
$samSession = $sam->connectSession( "ircsession", "irc.ilita.i2p" );

$socket = $samSession->getSocket();

socket_write( $socket, "NICK php_sam3\n" );
socket_write( $socket, "USER php_sam3 php_sam3 php_sam3\n" );

$buffer = null;
while ( true )
{
	socket_recv( $socket, $buffer, 2048, MSG_DONTWAIT );
	echo $buffer . PHP_EOL;
}