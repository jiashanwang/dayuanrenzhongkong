<?php

//decode by http://chiran.taobao.com/
namespace Query;

class Phoneyunj
{
	private $uid;
	private $secret;
	private $apiurl;
	public function __construct($option)
	{
		$this->uid = isset($option['param1']) ? $option['param1'] : '';
		$this->secret = isset($option['param2']) ? $option['param2'] : '';
		$this->apiurl = isset($option['param4']) ? $option['param4'] : '';
	}
	public function query($account, $param)
	{
		$body = array('userId' => $this->uid, 'account' => $account);
		$sign_str = $this->uid . $account . $this->secret;
		$body['sign'] = strtolower(md5($sign_str));
		$res = $this->http_post($this->apiurl, $body);
		if ($res['errno'] != 0) {
			return $res;
		}
		return rjson(0, '查询成功', array('account' => $res['data']['data']['account'], 'name' => $res['data']['data']['name'], 'balance' => $res['data']['data']['balance']));
	}
	private function http_post($url, $param)
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
		curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($oCurl, CURLOPT_TIMEOUT, 30);
		curl_setopt($oCurl, CURLOPT_HEADER, 0);
		curl_setopt($oCurl, CURLOPT_HTTPHEADER, array("ContentType:application/x-www-form-urlencoded;charset=utf-8"));
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			$result = json_decode($sContent, true);
			if ($result['status'] == true) {
				return rjson(0, $result['msg'], $result);
			} else {
				return rjson(1, $result['msg'], $result);
			}
		} else {
			return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"]);
		}
	}
}