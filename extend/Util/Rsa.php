<?php

//decode by http://chiran.taobao.com/
namespace Util;

class Rsa
{
	public $publicKey = "";
	public $privateKey = "";
	private $_privKey;
	private $_pubKey;
	private $_keyPath;
	function __construct($publicKey = null, $privateKey = null)
	{
		$this->setKey($publicKey, $privateKey);
	}
	public function setKey($publicKey = null, $privateKey = null)
	{
		if (!is_null($publicKey)) {
			$this->publicKey = $publicKey;
		}
		if (!is_null($privateKey)) {
			$this->privateKey = $privateKey;
		}
	}
	private function setupPrivKey()
	{
		if (is_resource($this->_privKey)) {
			return true;
		}
		$pem = chunk_split($this->privateKey, 64, "\r\n");
		$pem = "-----BEGIN PRIVATE KEY-----\r\n" . $pem . "-----END PRIVATE KEY-----\r\n";
		$this->_privKey = openssl_pkey_get_private($pem);
		return true;
	}
	private function setupPubKey()
	{
		if (is_resource($this->_pubKey)) {
			return true;
		}
		$pem = chunk_split($this->publicKey, 64, "\r\n");
		$pem = "-----BEGIN PUBLIC KEY-----\r\n" . $pem . "-----END PUBLIC KEY-----\r\n";
		$this->_pubKey = openssl_pkey_get_public($pem);
		return true;
	}
	public function privEncrypt($data)
	{
		if (!is_string($data)) {
			return null;
		}
		$this->setupPrivKey();
		$r = openssl_private_encrypt($data, $encrypted, $this->_privKey);
		if ($r) {
			return base64_encode($encrypted);
		}
		return null;
	}
	public function privDecrypt($encrypted)
	{
		if (!is_string($encrypted)) {
			return null;
		}
		$this->setupPrivKey();
		$encrypted = base64_decode($encrypted);
		$r = openssl_private_decrypt($encrypted, $decrypted, $this->_privKey);
		if ($r) {
			return $decrypted;
		}
		return null;
	}
	public function pubEncrypt($data)
	{
		if (!is_string($data)) {
			return null;
		}
		$this->setupPubKey();
		$r = openssl_public_encrypt($data, $encrypted, $this->_pubKey);
		if ($r) {
			return base64_encode($encrypted);
		}
		return null;
	}
	public function pubDecrypt($crypted)
	{
		if (!is_string($crypted)) {
			return null;
		}
		$this->setupPubKey();
		$crypted = base64_decode($crypted);
		$r = openssl_public_decrypt($crypted, $decrypted, $this->_pubKey);
		if ($r) {
			return $decrypted;
		}
		return null;
	}
	public function sign($dataString)
	{
		$this->setupPrivKey();
		$signature = false;
		openssl_sign($dataString, $signature, $this->_privKey);
		return base64_encode($signature);
	}
	public function verify($dataString, $signString)
	{
		$this->setupPubKey();
		$signature = base64_decode($signString);
		$flg = openssl_verify($dataString, $signature, $this->_pubKey);
		return $flg;
	}
	public function __destruct()
	{
		is_resource($this->_privKey) && @openssl_free_key($this->_privKey);
		is_resource($this->_pubKey) && @openssl_free_key($this->_pubKey);
	}
}