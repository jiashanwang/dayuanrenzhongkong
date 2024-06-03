<?php

//decode by http://chiran.taobao.com/
namespace Util;

class Ispzw
{
	private static $apiurl = "http://isp.qxt800.com/";
	public static function isZhuanw($apikey, $mobile)
	{
		if (!$apikey) {
			return rjson(1, '无转网配置参数，请先开户');
		}
		$res = self::check($apikey, $mobile);
		if ($res['errno'] != 0) {
			return rjson(1, '转网查询出错，信息：' . $res['errmsg']);
		}
		if (isset($res['data']['result']) && intval($res['data']['result']['res']) == 1) {
			return rjson(0, '手机号' . $res['data']['result']['Mobile'] . '携号转网,' . $res['data']['result']['Area'] . ',' . $res['data']['result']['Init_isp'] . '=>' . $res['data']['result']['Now_isp'], $res['data']);
		} else {
			return rjson(1, '未携号转网');
		}
	}
	private static function check($apikey, $mobile)
	{
		$data = array("apikey" => $apikey, 'mobile' => $mobile);
		return self::http_post(self::$apiurl . "carrier", $data);
	}
	public static function balance($apikey)
	{
		$data = array("apikey" => $apikey);
		$res = self::http_post(self::$apiurl . "balance", $data);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg'], $res['data']);
		}
		return rjson(0, "查询成功", sprintf("%.2f", $res['data']['result']['balance']));
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
		curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
		curl_setopt($oCurl, CURLOPT_HEADER, 0);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			$result = json_decode($sContent, true);
			if ($result['code'] == 0) {
				return rjson(0, $result['reason'], $result);
			} else {
				return rjson(1, $result['reason'], $result);
			}
		} else {
			return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"]);
		}
	}
}