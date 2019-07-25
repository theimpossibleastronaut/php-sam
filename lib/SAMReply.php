<?php
namespace PHP_SAM;

class SAMReply
{
	protected $message = null;

	function __construct( string $message )
	{
		$this->message = $message;
	}
}