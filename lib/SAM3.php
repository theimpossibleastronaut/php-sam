<?php
namespace PHP_SAM;

class SAM3
{
	public $samHost = "127.0.0.1";
	public $samPort = 7656;
	public $signatureType = Signatures::EdDSA_SHA512_Ed25519;

	protected $samSocket = null;

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

	public function connect():Void {
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
}