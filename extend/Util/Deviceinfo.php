<?php

//decode by http://chiran.taobao.com/
namespace Util;

class Deviceinfo
{
	public static function get_browser()
	{
		if (empty($_SERVER['HTTP_USER_AGENT'])) {
			return 'no_agent';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
			return 'Internet Explorer 9.0';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
			return 'Internet Explorer 8.0';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
			return 'Internet Explorer 7.0';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
			return 'Internet Explorer 6.0';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
			return 'Firefox';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
			return 'Chrome';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
			return 'Safari';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
			return 'Opera';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], '360SE')) {
			return '360SE';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'QQ')) {
			return 'QQ';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'UCBrowser')) {
			return 'UC';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
			return 'Wechat';
		}
		if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'baidu')) {
			return 'Baidu';
		}
		return 'Other';
	}
	public static function get_os()
	{
		if (!empty($_SERVER['HTTP_USER_AGENT'])) {
			$os = $_SERVER['HTTP_USER_AGENT'];
			if (preg_match('/win/i', $os)) {
				$os = 'Windows';
			} elseif (preg_match('/mac/i', $os)) {
				$os = 'MAC';
			} elseif (preg_match('/linux/i', $os)) {
				$os = 'Linux';
			} elseif (preg_match('/unix/i', $os)) {
				$os = 'Unix';
			} elseif (preg_match('/bsd/i', $os)) {
				$os = 'BSD';
			} else {
				$os = 'Other';
			}
			return $os;
		} else {
			return 'unknow';
		}
	}
	public static function is_mobile()
	{
		if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
			return true;
		}
		if (isset($_SERVER['HTTP_VIA'])) {
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			}
		}
		return false;
	}
	public static function get_headers()
	{
		$headers = array();
		$copy_server = array('CONTENT_TYPE' => 'Content-Type', 'CONTENT_LENGTH' => 'Content-Length', 'CONTENT_MD5' => 'Content-Md5');
		foreach ($_SERVER as $key => $value) {
			if (substr($key, 0, 5) === 'HTTP_') {
				$key = substr($key, 5);
				if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
					$key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
					$headers[$key] = $value;
				}
			} else {
				if (isset($copy_server[$key])) {
					$headers[$copy_server[$key]] = $value;
				}
			}
		}
		if (!isset($headers['Authorization'])) {
			if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
				$headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
			} elseif (isset($_SERVER['PHP_AUTH_USER'])) {
				$basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
				$headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
			} elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
				$headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
			}
		}
		return $headers;
	}
	public static function get_ipinfo($ip)
	{
		$data = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
		$ret = json_decode($data, $assoc = true);
		if ($ret['code'] == 0) {
			return $ret['data'];
		} else {
			return false;
		}
	}
	public static function get_client_ip($type = 0)
	{
		$type = $type ? 1 : 0;
		static $ip = NULL;
		if ($ip !== NULL) {
			return $ip[$type];
		}
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown', $arr);
			if (false !== $pos) {
				unset($arr[$pos]);
			}
			$ip = trim($arr[0]);
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$long = sprintf("%u", ip2long($ip));
		$ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
		return $ip[$type];
	}
	public static function get_url()
	{
		if ($_SERVER["SERVER_PORT"] == '80') {
			return 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
		} else {
			return 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		}
	}
}