<?php
namespace PHP_SAM;

class SAMException extends \Exception
{
	const ALREADY_CONNECTED = "Already connected to SAM bridge";
	const NOT_CONNECTED = "Not connected to SAM bridge";
	const HELLO_FAILED = "Failed to say hello";
	const SOCKET_CREATE_ERROR = "Error creating socket";
	const SOCKET_CONNECT_ERROR = "Error connecting socket";
	const SOCKET_WRITE_ERROR = "Error writing to the socket";
	const UNKNOWN_REPLY = "Unknown reply in SAM package";
}