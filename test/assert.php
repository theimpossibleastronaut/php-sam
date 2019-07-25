<?php
// Enable zend.assertions in php.ini in order to generate assertions code

assert_options( ASSERT_ACTIVE, 1 );
assert_options( ASSERT_WARNING, 0 );
assert_options( ASSERT_QUIET_EVAL, 1 );

function assertHandler( $file, $line, $code, $desc = null )
{
	echo "Assert failed at: " . $file . " at line " . $line . "\n" . $code;
	if ( $desc ) {
		echo $desc;
	}
	echo "\n\n";
}

assert_options( ASSERT_CALLBACK, "assertHandler" );

require_once( dirname( __FILE__ ) . "/../lib/php-sam.php" );

$sam = new \PHP_SAM\SAM3();

assert( $sam->getSAMAddress() === "127.0.0.1:7656" );
assert( $sam->getSignatureType() === \PHP_SAM\Signatures::EdDSA_SHA512_Ed25519 );