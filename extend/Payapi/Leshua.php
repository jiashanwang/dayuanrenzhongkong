<?php

//decode by http://chiran.taobao.com/
namespace Payapi;

use Util\Random;
class Leshua
{
	private $apiurl = "https://paygate.leshuazf.com";
	public function create_wxpay_js($option, $config)
	{
		$data = array('service' => 'get_tdcode', 'pay_way' => 'WXZF', 'merchant_id' => $config['mch_id'], 'third_order_id' => $option['order_number'], 'amount' => $option['total_price'], 'jspay_flag' => 1, 'appid' => $config['appid'], 'sub_openid' => $option['openid'], 'notify_url' => C('WEB_URL') . 'api.php/notify/weixin', 'client_ip' => get_client_ip(), 'body' => $option['body'], 'nonce_str' => Random::alnum(25));
		ksort($data);
		$sign_str = urldecode(urldecode(http_build_query($data)) . "&key=" . $config['key']);
		$data['sign'] = strtoupper(md5($sign_str));
		$res = $this->http_post($this->apiurl . "/cgi-bin/lepos_pay_gateway.cgi", $data);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg'], $res['data']);
		}
		if ($res['data']['result_code'] != 0) {
			return rjson(1, $res['data']['error_msg'], $res['data']);
		}
		return rjson(0, '创建微信支付订单成功!', $res['data']['jspay_info']);
	}
	public function create_wxpay_h5($option, $config)
	{
	}
	public function refund($option, $config)
	{
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
		curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
		curl_setopt($oCurl, CURLOPT_HEADER, 0);
		curl_setopt($oCurl, CURLOPT_HTTPHEADER, array("ContentType:application/x-www-form-urlencoded;charset=utf-8"));
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			$result = $this->xmlToArray($sContent);
			if ($result['resp_code'] == 0) {
				return rjson(0, $result['resp_msg'], $result);
			} else {
				return rjson(1, $result['resp_msg'], $result);
			}
		} else {
			return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"]);
		}
	}
	private function xmlToArray($xml)
	{
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
}