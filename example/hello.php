<?php
require_once( dirname( __FILE__ ) . "/../lib/php-sam.php" );

$sam = new \PHP_SAM\SAM3();
$sam->connect( false );
$reply = $sam->commandSAM( "HELLO VERSION MIN=3.0 MAX=3.1 \n" );

if ( $reply->getResult() === \PHP_SAM\SAMReply::REPLY_TYPE_OK ) {
	echo "Got valid reply: ";
	echo $reply->message;
	echo PHP_EOL;
} else {
	echo "Got an invalid reply:";
	echo $reply->message;
	echo PHP_EOL;
}