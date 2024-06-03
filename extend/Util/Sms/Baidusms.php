<?php

//decode by http://chiran.taobao.com/
namespace Util\Sms;

header("Content-Type: text/html; charset=utf-8");
class Baidusms
{
	protected $endPoint;
	protected $accessKey;
	protected $secretAccessKey;
	public $errmsg;
	function __construct()
	{
		$this->endPoint = 'sms.bj.baidubce.com';
		$this->accessKey = '';
		$this->secretAccessKey = '';
	}
	public function sendMessage($telnumber, $code)
	{
		$message = array("invokeId" => "mavXlgBC-BkAq-cOGe", "phoneNumber" => $telnumber, "templateCode" => "smsTpl:e7476122a1c24e37b3b0de19d04ae901", "contentVar" => array("code" => $code));
		$json_data = json_encode($message);
		$signer = new \SampleSigner();
		$credentials = array("ak" => $this->accessKey, "sk" => $this->secretAccessKey);
		$httpMethod = "POST";
		$path = "/bce/v2/message";
		$params = array();
		$timestamp = new \DateTime();
		$timestamp->setTimezone(new \DateTimeZone("GMT"));
		$datetime = $timestamp->format("Y-m-d\\TH:i:s\\Z");
		$datetime_gmt = $timestamp->format("D, d M Y H:i:s T");
		$headers = array("Host" => $this->endPoint);
		$str_sha256 = hash('sha256', $json_data);
		$headers['x-bce-content-sha256'] = $str_sha256;
		$headers['Content-Length'] = strlen($json_data);
		$headers['Content-Type'] = "application/json";
		$headers['x-bce-date'] = $datetime;
		$options = array(\SignOption::TIMESTAMP => $timestamp, \SignOption::HEADERS_TO_SIGN => array('host', 'x-bce-content-sha256'));
		$ret = $signer->sign($credentials, $httpMethod, $path, $headers, $params, $options);
		$headers_curl = array('Content-Type:application/json', 'Host:' . $this->endPoint, 'x-bce-date:' . $datetime, 'Content-Length:' . strlen($json_data), 'x-bce-content-sha256:' . $str_sha256, 'Authorization:' . $ret, "Accept-Encoding: gzip,deflate", 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0', 'Date:' . $datetime_gmt);
		$url = 'http://' . $this->endPoint . $path;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers_curl);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		$this->errmsg = curl_errno($curl);
		curl_close($curl);
		$ret = json_decode($result, true);
		if ($ret['code'] == 1000) {
			return true;
		} else {
			$this->errmsg = $ret['code'] . $ret['message'];
			return false;
		}
	}
}
class SignOption
{
	const EXPIRATION_IN_SECONDS = "expirationInSeconds";
	const HEADERS_TO_SIGN = "headersToSign";
	const TIMESTAMP = "timestamp";
	const DEFAULT_EXPIRATION_IN_SECONDS = 1800;
	const MIN_EXPIRATION_IN_SECONDS = 300;
	const MAX_EXPIRATION_IN_SECONDS = 129600;
}
class HttpUtil
{
	public static $PERCENT_ENCODED_STRINGS;
	public static function __init()
	{
		HttpUtil::$PERCENT_ENCODED_STRINGS = array();
		for ($i = 0; $i < 256; $i++) {
			HttpUtil::$PERCENT_ENCODED_STRINGS[$i] = sprintf("%%%02X", $i);
		}
		foreach (range('a', 'z') as $ch) {
			HttpUtil::$PERCENT_ENCODED_STRINGS[ord($ch)] = $ch;
		}
		foreach (range('A', 'Z') as $ch) {
			HttpUtil::$PERCENT_ENCODED_STRINGS[ord($ch)] = $ch;
		}
		foreach (range('0', '9') as $ch) {
			HttpUtil::$PERCENT_ENCODED_STRINGS[ord($ch)] = $ch;
		}
		HttpUtil::$PERCENT_ENCODED_STRINGS[ord('-')] = '-';
		HttpUtil::$PERCENT_ENCODED_STRINGS[ord('.')] = '.';
		HttpUtil::$PERCENT_ENCODED_STRINGS[ord('_')] = '_';
		HttpUtil::$PERCENT_ENCODED_STRINGS[ord('~')] = '~';
	}
	public static function urlEncodeExceptSlash($path)
	{
		return str_replace("%2F", "/", HttpUtil::urlEncode($path));
	}
	public static function urlEncode($value)
	{
		$result = '';
		for ($i = 0; $i < strlen($value); $i++) {
			$result .= HttpUtil::$PERCENT_ENCODED_STRINGS[ord($value[$i])];
		}
		return $result;
	}
	public static function getCanonicalQueryString(array $parameters)
	{
		if (count($parameters) == 0) {
			return '';
		}
		$parameterStrings = array();
		foreach ($parameters as $k => $v) {
			if (strcasecmp('Authorization', $k) == 0) {
				continue;
			}
			if (!isset($k)) {
				throw new \InvalidArgumentException("parameter key should not be null");
			}
			if (isset($v)) {
				$parameterStrings[] = HttpUtil::urlEncode($k) . '=' . HttpUtil::urlEncode((string) $v);
			} else {
				$parameterStrings[] = HttpUtil::urlEncode($k) . '=';
			}
		}
		sort($parameterStrings);
		return implode('&', $parameterStrings);
	}
	public static function getCanonicalURIPath($path)
	{
		if (empty($path)) {
			return '/';
		} else {
			if ($path[0] == '/') {
				return HttpUtil::urlEncodeExceptSlash($path);
			} else {
				return '/' . HttpUtil::urlEncodeExceptSlash($path);
			}
		}
	}
	public static function getCanonicalHeaders($headers)
	{
		if (count($headers) == 0) {
			return '';
		}
		$headerStrings = array();
		foreach ($headers as $k => $v) {
			if ($k === null) {
				continue;
			}
			if ($v === null) {
				$v = '';
			}
			$headerStrings[] = HttpUtil::urlEncode(strtolower(trim($k))) . ':' . HttpUtil::urlEncode(trim($v));
		}
		sort($headerStrings);
		return implode("\r\n", $headerStrings);
	}
}
HttpUtil::__init();
class SampleSigner
{
	const BCE_AUTH_VERSION = "bce-auth-v1";
	const BCE_PREFIX = "x-bce-";
	public static $defaultHeadersToSign;
	public static function __init()
	{
		SampleSigner::$defaultHeadersToSign = array("host", "content-length", "content-type", "content-md5");
	}
	public function sign(array $credentials, $httpMethod, $path, $headers, $params, $options = array())
	{
		if (!isset($options[SignOption::EXPIRATION_IN_SECONDS])) {
			$expirationInSeconds = SignOption::DEFAULT_EXPIRATION_IN_SECONDS;
		} else {
			$expirationInSeconds = $options[SignOption::EXPIRATION_IN_SECONDS];
		}
		$accessKeyId = $credentials['ak'];
		$secretAccessKey = $credentials['sk'];
		if (!isset($options[SignOption::TIMESTAMP])) {
			$timestamp = new \DateTime();
		} else {
			$timestamp = $options[SignOption::TIMESTAMP];
		}
		$timestamp->setTimezone(new \DateTimeZone("GMT"));
		$authString = SampleSigner::BCE_AUTH_VERSION . '/' . $accessKeyId . '/' . $timestamp->format("Y-m-d\\TH:i:s\\Z") . '/' . $expirationInSeconds;
		$signingKey = hash_hmac('sha256', $authString, $secretAccessKey);
		$canonicalURI = HttpUtil::getCanonicalURIPath($path);
		$canonicalQueryString = HttpUtil::getCanonicalQueryString($params);
		$headersToSign = null;
		if (isset($options[SignOption::HEADERS_TO_SIGN])) {
			$headersToSign = $options[SignOption::HEADERS_TO_SIGN];
		}
		$canonicalHeader = HttpUtil::getCanonicalHeaders(SampleSigner::getHeadersToSign($headers, $headersToSign));
		$signedHeaders = '';
		if ($headersToSign !== null) {
			$signedHeaders = strtolower(trim(implode(";", $headersToSign)));
		}
		$canonicalRequest = $httpMethod . "\r\n" . $canonicalURI . "\r\n" . $canonicalQueryString . "\r\n" . $canonicalHeader;
		$signature = hash_hmac('sha256', $canonicalRequest, $signingKey);
		$authorizationHeader = $authString . "/" . $signedHeaders . "/" . $signature;
		return $authorizationHeader;
	}
	public static function getHeadersToSign($headers, $headersToSign)
	{
		$filter_empty = function ($v) {
			return trim((string) $v) !== '';
		};
		$headers = array_filter($headers, $filter_empty);
		$trim_and_lower = function ($str) {
			return strtolower(trim($str));
		};
		$temp = array();
		$process_keys = function ($k, $v) use(&$temp, $trim_and_lower) {
			$temp[$trim_and_lower($k)] = $v;
		};
		array_map($process_keys, array_keys($headers), $headers);
		$headers = $temp;
		$header_keys = array_keys($headers);
		$filtered_keys = null;
		if ($headersToSign !== null) {
			$headersToSign = array_map($trim_and_lower, $headersToSign);
			$filtered_keys = array_intersect_key($header_keys, $headersToSign);
		} else {
			$filter_by_default = function ($k) {
				return SampleSigner::isDefaultHeaderToSign($k);
			};
			$filtered_keys = array_filter($header_keys, $filter_by_default);
		}
		return array_intersect_key($headers, array_flip($filtered_keys));
	}
	public static function isDefaultHeaderToSign($header)
	{
		$header = strtolower(trim($header));
		if (in_array($header, SampleSigner::$defaultHeadersToSign)) {
			return true;
		}
		return substr_compare($header, SampleSigner::BCE_PREFIX, 0, strlen(SampleSigner::BCE_PREFIX)) == 0;
	}
}
SampleSigner::__init();