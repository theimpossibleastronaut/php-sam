<?php
namespace PHP_SAM;

class SAM3
{
	public $samHost = "127.0.0.1";
	public $samPort = 7656;
	public $signatureType = Signatures::EdDSA_SHA512_Ed25519;
	public $sessionId = "";

	protected $samSocket = null;
	protected $sentHello = false;

	function __construct( string $samHost = null, int $samPort = null, string $signatureType = null )
	{
		if ( !is_null( $samHost ) && !empty( $samHost ) ) {
			$this->samHost = $samHost;
		}

		if ( !is_null( $samPort ) && !empty( $samPort ) ) {
			$this->samPort = $samPort;
		}

		if ( !is_null( $signatureType ) && !empty( $signatureType ) ) {
			$this->signatureType = $signatureType;
		}
	}

	function __destruct()
	{
		if ( isset( $this->samSocket ) && !is_null( $this->samSocket ) ) {
			@socket_close( $this->samSocket );
		}
	}

	public function getSAMAddress():String
	{
		return $this->samHost . ":" . $this->samPort;
	}

	public function getSignatureType():String
	{
		return Signatures::getSignatureType( $this->signatureType );
	}

	public function getSocket() {
		return $this->samSocket;
	}

	public function getSessionId():String {
		if ( !is_null( $this->sessionId ) && !empty( $this->sessionId ) ) {
			return $this->sessionId;
		}

		return "";
	}

	public function connect( bool $sendHello = true ):Void {
		if ( isset( $this->samSocket ) && !is_null( $this->samSocket ) ) {
			throw new SAMException( SAMException::ALREADY_CONNECTED );
		}

		$this->samSocket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );

		if ( $this->samSocket === false ) {
			throw new SAMException( SAMException::SOCKET_CREATE_ERROR );
		}

		$result = socket_connect( $this->samSocket, $this->samHost, $this->samPort );

		if ( $result === false ) {
			throw new SAMException( SAMException::SOCKET_CONNECT_ERROR );
		}

		if ( $sendHello === true ) {
			$this->sendHello();
		}
	}

	public function sendHello():SAMReply
	{
		if ( $this->sentHello === true ) {
			throw new SAMException( SAMException::HELLO_ALREADY_SENT );
		}

		$reply = $this->commandSAM( "HELLO VERSION MIN=3.0 MAX=3.1 \n" );

		if ( $reply->getResult() !== \PHP_SAM\SAMReply::REPLY_TYPE_OK ) {
			throw new SAMException( SAMException::HELLO_FAILED );
		}

		$this->sentHello = true;

		return $reply;
	}

	public function commandSAM( $args ):SAMReply
	{
		if ( !isset( $this->samSocket ) || is_null( $this->samSocket ) ) {
			throw new SAMException( SAMException::NOT_CONNECTED );
		}

		$result = socket_write( $this->samSocket, $args . "\n", strlen( $args . "\n" ) );

		if ( $result === false ) {
			throw new SAMException( SAMException::SOCKET_WRITE_ERROR );
		}

		$message = "";
		while ( $out = socket_read( $this->samSocket, 2048 ) ) {
			for ( $i = 0; $i < strlen( $out ); $i++ ) {
				if ( $out[ $i ] === "\r" || $out[ $i ] === "\n" ) {
					$message .= substr( $out, 0, $i );
					break 2;
				}
			}

			$message .= $out;
		}

		return new SAMReply( $message );
	}

	public function createSession( String $id, String $destination = null ):String
	{
		if ( empty( $destination ) ) {
			$destination = "TRANSIENT";
		}

		$reply = $this->commandSAM( "SESSION CREATE STYLE=STREAM ID=" . $id . " DESTINATION=" . $destination . " \n" );
		if ( $reply->getResult() === \PHP_SAM\SAMReply::REPLY_TYPE_OK ) {
			$this->sessionId = $id;
			return $id;
		}

		return "";
	}

	public function lookupName( String $name ):String
	{
		$reply = $this->commandSAM( "NAMING LOOKUP NAME=" . $name . "\n" );
		if ( $reply->getResult() === \PHP_SAM\SAMReply::REPLY_TYPE_OK ) {
			return $reply->getReplyMapValue( "VALUE" );
		}

		return "";
	}

	public function connectSession( string $id, string $destination ):SAM3 {
		$sam3 = new SAM3( $this->samHost, $this->samPort, $this->signatureType );
		$sam3->connect();

		if ( substr( strtolower( $destination ), -4 ) === ".i2p" ) {
			$destination = $sam3->lookupName( $destination );
		}

		$reply = $sam3->commandSAM( "STREAM CONNECT ID=" . $id . " DESTINATION=" . $destination . " SILENT=false" );
		if ( $reply->getResult() === \PHP_SAM\SAMReply::REPLY_TYPE_OK ) {
			$sam3->sessionId = $id;
		}

		return $sam3;
	}

	public function acceptSession( string $id ):SAM3 {
		$sam3 = new SAM3( $this->samHost, $this->samPort, $this->signatureType );
		$sam3->connect();

		$reply = $sam3->commandSAM( "STREAM ACCEPT ID=" . $id . " SILENT=false" );
		if ( $reply->getResult() === \PHP_SAM\SAMReply::REPLY_TYPE_OK ) {
			$sam3->sessionId = $id;
		}

		return $sam3;
	}

}