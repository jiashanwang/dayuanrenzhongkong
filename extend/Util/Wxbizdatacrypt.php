<?php

//decode by http://chiran.taobao.com/
namespace Util;

class ErrorCode
{
	public static $OK = 0;
	public static $IllegalAesKey = -41001;
	public static $IllegalIv = -41002;
	public static $IllegalBuffer = -41003;
	public static $DecodeBase64Error = -41004;
}
class Wxbizdatacrypt
{
	private $appid;
	private $sessionKey;
	public function __construct($appid, $sessionKey)
	{
		$this->sessionKey = $sessionKey;
		$this->appid = $appid;
	}
	public function decryptData($encryptedData, $iv, &$data)
	{
		if (strlen($this->sessionKey) != 24) {
			return ErrorCode::$IllegalAesKey;
		}
		$aesKey = base64_decode($this->sessionKey);
		if (strlen($iv) != 24) {
			return ErrorCode::$IllegalIv;
		}
		$aesIV = base64_decode($iv);
		$aesCipher = base64_decode($encryptedData);
		$result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
		$dataObj = json_decode($result);
		if ($dataObj == NULL) {
			return ErrorCode::$IllegalBuffer;
		}
		if ($dataObj->watermark->appid != $this->appid) {
			return ErrorCode::$IllegalBuffer;
		}
		$data = $dataObj;
		return ErrorCode::$OK;
	}
}