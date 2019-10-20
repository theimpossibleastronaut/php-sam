<?php
namespace PHP_SAM;

class Signatures
{
	const DSA_SHA1 = "DSA_SHA1";
	const ECDSA_SHA256_P256 = "ECDSA_SHA256_P256";
	const ECDSA_SHA384_P384 = "ECDSA_SHA384_P384";
	const ECDSA_SHA512_P521 = "ECDSA_SHA512_P521";
	const EDDSA_SHA512_ED25519 = "EdDSA_SHA512_Ed25519";

	public static function getSignatureType( string $signatureType ):String
	{
		return "SIGNATURE_TYPE=" . $signatureType;
	}
}
