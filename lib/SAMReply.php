<?php
namespace PHP_SAM;

class SAMReply
{
	const REPLY_TYPE_OK = "OK";
	const REPLY_TYPE_CANT_REACH_PEER = "CANT_REACH_PEER";
	const REPLY_TYPE_DUPLICATED_ID = "DUPLICATED_ID";
	const REPLY_TYPE_DUPLICATED_DEST = "DUPLICATED_DEST";
	const REPLY_TYPE_I2P_ERROR = "I2P_ERROR";
	const REPLY_TYPE_INVALID_KEY = "INVALID_KEY";
	const REPLY_TYPE_KEY_NOT_FOUND = "KEY_NOT_FOUND";
	const REPLY_TYPE_PEER_NOT_FOUND = "PEER_NOT_FOUND";
	const REPLY_TYPE_TIMEOUT = "TIMEOUT";
	const REPLY_TYPE_UNKNOWN = "UNKNOWN";

	public $topic = null;
	public $type = null;
	public $replyMap = array();

	public $message = null;

	public static function getReplyType( String $replyType ):String
	{
		return "RESULT=" . $replyType;
	}

	function __construct( string $message )
	{
		$this->message = trim( $message );

		$parts = explode( " ", $this->message );

		if ( count( $parts ) < 2 ) {
			return;
		}

		$this->topic = $parts[ 0 ];
		$this->type = $parts[ 1 ];

		for ( $i = 2; $i < count( $parts ); $i++ ) {
			$dparts = explode( "=", $parts[ $i ] );
			if ( count( $dparts ) === 2 ) {
				$this->replyMap[ $dparts[ 0 ] ] = $dparts[ 1 ];
			}
		}
	}

	public function getResult():String {
		try {
			return $this->getReplyMapValue( "RESULT" );
		} catch ( SAMException $e ) {
			return "";
		}
	}

	public function getReplyMapValue( String $param ):String
	{
		if ( isset( $this->replyMap[ $param ] ) ) {
			return $this->replyMap[ $param ];
		}

		throw new SAMException( SAMException::UNKNOWN_REPLY );
	}
}