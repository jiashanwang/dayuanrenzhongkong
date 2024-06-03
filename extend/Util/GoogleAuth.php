<?php

//decode by http://chiran.taobao.com/
namespace Util;

class GoogleAuth
{
	protected static $_codeLength = 6;
	public static function createSecret($secretLength = 16)
	{
		$validChars = self::_getBase32LookupTable();
		if ($secretLength < 16 || $secretLength > 128) {
			throw new \Exception('Bad secret length');
		}
		$secret = '';
		$rnd = false;
		if (function_exists('random_bytes')) {
			$rnd = random_bytes($secretLength);
		} elseif (function_exists('mcrypt_create_iv')) {
			$rnd = mcrypt_create_iv($secretLength, MCRYPT_DEV_URANDOM);
		} elseif (function_exists('openssl_random_pseudo_bytes')) {
			$rnd = openssl_random_pseudo_bytes($secretLength, $cryptoStrong);
			if (!$cryptoStrong) {
				$rnd = false;
			}
		}
		if ($rnd !== false) {
			for ($i = 0; $i < $secretLength; $i++) {
				$secret .= $validChars[ord($rnd[$i]) & 31];
			}
		} else {
			throw new \Exception('No source of secure random');
		}
		return $secret;
	}
	public static function getCode($secret, $timeSlice = null)
	{
		if ($timeSlice === null) {
			$timeSlice = floor(time() / 30);
		}
		$secretkey = self::_base32Decode($secret);
		$time = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timeSlice);
		$hm = hash_hmac('SHA1', $time, $secretkey, true);
		$offset = ord(substr($hm, -1)) & 0xf;
		$hashpart = substr($hm, $offset, 4);
		$value = unpack('N', $hashpart);
		$value = $value[1];
		$value = $value & 0x7fffffff;
		$modulo = pow(10, self::$_codeLength);
		return str_pad($value % $modulo, self::$_codeLength, '0', STR_PAD_LEFT);
	}
	public static function getQRCodeGoogleUrl($name, $secret, $title = null, $params = array())
	{
		$urlencoded = urlencode('otpauth://totp/' . $name . '?secret=' . $secret . '');
		if (isset($title)) {
			$urlencoded .= urlencode('&issuer=' . urlencode($title));
		}
		return HTTP_TYPE . $_SERVER['HTTP_HOST'] . '/' . create_qrcode(urldecode($urlencoded), 7);
	}
	public static function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null)
	{
		if ($currentTimeSlice === null) {
			$currentTimeSlice = floor(time() / 30);
		}
		if ((!$secret || $secret == '' || $secret == '0') && ($code == '' || $code == '000000')) {
			return true;
		}
		if (strlen($code) != 6) {
			return false;
		}
		if (strlen($secret) > 20) {
			$secret = dyr_decrypt($secret);
		}
		for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
			$calculatedCode = self::getCode($secret, $currentTimeSlice + $i);
			if (self::timingSafeEquals($calculatedCode, $code)) {
				return true;
			}
		}
		return false;
	}
	protected static function _base32Decode($secret)
	{
		if (empty($secret)) {
			return '';
		}
		$base32chars = self::_getBase32LookupTable();
		$base32charsFlipped = array_flip($base32chars);
		$paddingCharCount = substr_count($secret, $base32chars[32]);
		$allowedValues = array(6, 4, 3, 1, 0);
		if (!in_array($paddingCharCount, $allowedValues)) {
			return false;
		}
		for ($i = 0; $i < 4; $i++) {
			if ($paddingCharCount == $allowedValues[$i] && substr($secret, -1 * $allowedValues[$i]) != str_repeat($base32chars[32], $allowedValues[$i])) {
				return false;
			}
		}
		$secret = str_replace('=', '', $secret);
		$secret = str_split($secret);
		$binaryString = '';
		for ($i = 0; $i < count($secret); $i += 8) {
			$x = '';
			if (!in_array($secret[$i], $base32chars)) {
				return false;
			}
			for ($j = 0; $j < 8; $j++) {
				$x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
			}
			$eightBits = str_split($x, 8);
			for ($z = 0; $z < count($eightBits); $z++) {
				$binaryString .= ($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48 ? $y : '';
			}
		}
		return $binaryString;
	}
	protected static function _getBase32LookupTable()
	{
		return array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '2', '3', '4', '5', '6', '7', '=');
	}
	private static function timingSafeEquals($safeString, $userString)
	{
		if (function_exists('hash_equals')) {
			return hash_equals($safeString, $userString);
		}
		$safeLen = strlen($safeString);
		$userLen = strlen($userString);
		if ($userLen != $safeLen) {
			return false;
		}
		$result = 0;
		for ($i = 0; $i < $userLen; $i++) {
			$result |= ord($safeString[$i]) ^ ord($userString[$i]);
		}
		return $result === 0;
	}
}