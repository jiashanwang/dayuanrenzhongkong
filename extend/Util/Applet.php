<?php

//decode by http://chiran.taobao.com/
namespace Util;

class Applet
{
	const API_URL_PREFIX = "https://api.weixin.qq.com/cgi-bin";
	const AUTH_URL = "/token?grant_type=client_credential&";
	const SEND_MSG = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=";
	const SESSION_KEY_URL = "https://api.weixin.qq.com/sns/jscode2session?";
	const SUBSCRIBE_MESSAGE_URL = "/message/subscribe/send?";
	const API_WXA_CODE_UNLIMIT = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?";
	private $appid;
	private $token;
	private $appsecret;
	private $_receive;
	private $errCode;
	public $errMsg;
	private $access_token;
	private $msgcontent;
	private $_text_filter = true;
	public function __construct($options)
	{
		$this->token = isset($options['token']) ? $options['token'] : '';
		$this->appid = isset($options['appid']) ? $options['appid'] : '';
		$this->appsecret = isset($options['appsecret']) ? $options['appsecret'] : '';
		$this->checkAuth();
	}
	public function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$token = $this->token;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if ($tmpStr == $signature) {
			echo $_GET['echostr'];
		}
	}
	public function getRev()
	{
		if ($this->_receive) {
			return $this;
		}
		$jsonstr = file_get_contents("php://input");
		$this->_receive = json_decode($jsonstr, true);
		return $this;
	}
	public function getRevType()
	{
		if (isset($this->_receive['MsgType'])) {
			return $this->_receive['MsgType'];
		} else {
			return false;
		}
	}
	public function getEventType()
	{
		if ($this->_receive['MsgType'] == "event") {
			return $this->_receive['Event'];
		} else {
			return false;
		}
	}
	public function getSessionFrom()
	{
		if ($this->_receive['MsgType'] == "event") {
			return $this->_receive['sessionFrom'];
		} else {
			return false;
		}
	}
	public function getRevFrom()
	{
		if (isset($this->_receive['FromUserName'])) {
			return $this->_receive['FromUserName'];
		} else {
			return false;
		}
	}
	public function text($text)
	{
		$data['touser'] = $this->_receive['FromUserName'];
		$data['msgtype'] = "text";
		$data['text']['content'] = $this->_auto_text_filter($text);
		$this->msgcontent = $data;
		return $this;
	}
	public function reply()
	{
		$result = $this->http_post(self::SEND_MSG . $this->access_token, json_encode($this->msgcontent));
		if ($result) {
			$json = json_decode($result, true);
			if (!$json || isset($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
				$this->log_result($result);
				return false;
			}
			return true;
		} else {
			$this->log_result("请求失败");
			return false;
		}
	}
	public function sendSubscribeMessage($data)
	{
		if (!$this->access_token) {
			return rjson(1, 'access_token未生成');
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::SUBSCRIBE_MESSAGE_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		if ($result) {
			$json = json_decode($result, true);
			if (!$json || !empty($json['errcode'])) {
				return rjson($json['errcode'], $json['errmsg'], $json);
			}
			return rjson(0, 'ok', $json);
		}
		return rjson(1, '请求接口失败');
	}
	public function addSubscribeMessage($data)
	{
		if (!$this->access_token) {
			return rjson(1, 'access_token未生成');
		}
		$result = $this->http_post('https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate?access_token=' . $this->access_token, self::json_encode($data));
		if ($result) {
			$json = json_decode($result, true);
			if (!$json || !empty($json['errcode'])) {
				return rjson($json['errcode'], $json['errmsg'], $json);
			}
			return rjson(0, 'ok', $json);
		}
		return rjson(1, '请求接口失败');
	}
	public function getWxaCodeUnlimit($scene, $page = '', $path = 'uploads/applet/unlimitqr/', $width = 430)
	{
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
		if (!$this->access_token) {
			return rjson(1, 'access_token未生成');
		}
		$data = array('scene' => $scene, 'page' => $page, 'width' => $width);
		$result = $this->http_post(self::API_WXA_CODE_UNLIMIT . 'access_token=' . $this->access_token, self::json_encode($data));
		if (is_null(json_decode($result))) {
			$filename = $path . time() . md5($page . $scene) . '.png';
			$file = fopen($filename, "w");
			fwrite($file, $result);
			fclose($file);
			return rjson(0, 'ok', $filename);
		} else {
			$json = json_decode($result, true);
			if (!$json || !empty($json['errcode'])) {
				return rjson($json['errcode'], $json['errmsg'], $json);
			}
			return rjson(1, '其他错误', $json);
		}
	}
	private function _auto_text_filter($text)
	{
		if (!$this->_text_filter) {
			return $text;
		}
		return str_replace("\r\n", "\r\n", $text);
	}
	public function checkAuth()
	{
		$token = DataCachea::get($this->appid);
		if ($token) {
			$this->access_token = $token;
			return $this->access_token;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::AUTH_URL . 'appid=' . $this->appid . '&secret=' . $this->appsecret);
		if ($result) {
			$json = json_decode($result, true);
			if (!$json || isset($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
				return false;
			}
			DataCachea::set($json['access_token'], $this->appid);
			$this->access_token = $json['access_token'];
			return $this->access_token;
		}
		return false;
	}
	private function http_get($url)
	{
		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
	private function http_post($url, $param, $post_file = false)
	{
		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
		}
		if (is_string($param) || $post_file) {
			$strPOST = $param;
		} else {
			$aPOST = array();
			foreach ($param as $key => $val) {
				$aPOST[] = $key . "=" . urlencode($val);
			}
			$strPOST = join("&", $aPOST);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
		curl_setopt($oCurl, CURLOPT_HTTPHEADER, array('Content-Type:application/json; charset=utf8'));
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
	public function get_Openid_by_code($code)
	{
		$result = $this->http_get(self::SESSION_KEY_URL . "appid=" . $this->appid . "&secret=" . $this->appsecret . "&js_code=" . $code . "&grant_type=authorization_code");
		if ($result) {
			$json = json_decode($result, true);
			if (!$json || isset($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
				return false;
			} else {
				return $json;
			}
		} else {
			return false;
		}
	}
	static function json_encode($arr)
	{
		$parts = array();
		$is_list = false;
		$keys = array_keys($arr);
		$max_length = count($arr) - 1;
		if ($keys[0] === 0 && $keys[$max_length] === $max_length) {
			$is_list = true;
			for ($i = 0; $i < count($keys); $i++) {
				if ($i != $keys[$i]) {
					$is_list = false;
					break;
				}
			}
		}
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				if ($is_list) {
					$parts[] = self::json_encode($value);
				} else {
					$parts[] = '"' . $key . '":' . self::json_encode($value);
				}
			} else {
				$str = '';
				if (!$is_list) {
					$str = '"' . $key . '":';
				}
				if (!is_string($value) && is_numeric($value) && $value < 2000000000) {
					$str .= $value;
				} else {
					if ($value === false) {
						$str .= 'false';
					} elseif ($value === true) {
						$str .= 'true';
					} else {
						$str .= '"' . addslashes($value) . '"';
					}
				}
				$parts[] = $str;
			}
		}
		$json = implode(',', $parts);
		if ($is_list) {
			return '[' . $json . ']';
		}
		return '{' . $json . '}';
	}
	function log_result($word, $file = "applet.txt")
	{
		$fp = fopen($file, "a");
		flock($fp, LOCK_EX);
		fwrite($fp, "执行日期：" . date("Y-m-d H:i", time()) . "\r\n" . $word . "\r\n\r\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
}
class DataCachea
{
	public static $file = ".wechat";
	public static function get($name = 'token')
	{
		$filename = md5($name) . self::$file;
		if (!is_file($filename)) {
			return false;
		}
		$json = file_get_contents($filename);
		$data = json_decode($json, true);
		if ($data['create_time'] + $data['expire'] < time()) {
			return false;
		}
		return $data['content'];
	}
	public static function set($content, $name = 'token', $expire = 7200)
	{
		$filename = md5($name) . self::$file;
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode(array('create_time' => time(), 'expire' => $expire, 'content' => $content)));
		fclose($fp);
	}
}