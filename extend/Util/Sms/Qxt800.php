<?php

//decode by http://chiran.taobao.com/
namespace Util\Sms;

use app\common\library\Email;
class Qxt800
{
	const API_URL = "http://118.31.9.10:8088/sms.aspx";
	public static function send($config, $mobile, $content)
	{
		if (!(isset($config['userid']) && isset($config['account']) && isset($config['password']))) {
			return rjson(1, '短信参数未配置');
		}
		if (!$mobile || strlen($mobile) != 11 || !is_numeric($mobile)) {
			return rjson(1, '短信发送手机格式不正确');
		}
		$param = array('userid' => $config['userid'], 'account' => $config['account'], 'password' => $config['password'], 'mobile' => $mobile, 'content' => $content, 'sendTime' => '', 'action' => 'send', 'extno' => '');
		return self::http_post(self::API_URL, $param);
	}
	private static function http_post($url, $param)
	{
		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
		}
		if (is_string($param)) {
			$strPOST = $param;
		} else {
			$strPOST = http_build_query($param);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
		curl_setopt($oCurl, CURLOPT_HEADER, 0);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			$result = self::xmlToArray($sContent);
			if ($result['returnstatus'] == 'Success') {
				isset($result['remainpoint']) && S('qxt800_balance', $result['remainpoint']);
				return rjson(0, $result['message'], $result);
			} else {
				return rjson(1, $result['message'], $result);
			}
		} else {
			return rjson(500, '短信接口访问失败，http错误码' . $aStatus["http_code"]);
		}
	}
	private static function xmlToArray($xml)
	{
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
}