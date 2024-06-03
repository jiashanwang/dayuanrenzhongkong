<?php

//decode by http://chiran.taobao.com/
namespace Payapi;

class Jindjuhe
{
	private $apiurl = "https://openapi.duolabao.com";
	public function create_wxpay_js($option, $config)
	{
		$data = array('agentNum' => '10001015538389095192551', 'customerNum' => '10001116504507981042309', 'shopNum' => '10001216504509951767083', 'authId' => $option['openid'], 'bankType' => 'WX', 'requestNum' => $option['order_number'] . "", 'amount' => $option['total_price'], 'callbackUrl' => C('WEB_URL') . 'api.php/notify/jindjuhe');
		$timestamp = time();
		$path = "/v3/order/pay/create";
		$signarr['secretKey'] = $config['key'];
		$signarr['timestamp'] = $timestamp;
		$signarr['path'] = $path;
		$signarr['body'] = json_encode($data);
		$header[] = 'token:' . strtoupper(sha1(urldecode(http_build_query($signarr))));
		$header[] = 'timestamp:' . $timestamp;
		$header[] = "accessKey:a45a354ea12744e5b9e3aefea2f891534e27e0e1";
		$res = $this->http_post($this->apiurl . $path, $data, $header);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg'], $res['data']);
		}
		$resdata = $res['data']['data']['bankRequest'];
		$pays = array('appId' => $resdata['APPID'], 'timeStamp' => $resdata['TIMESTAMP'], 'nonceStr' => $resdata['NONCESTR'], 'package' => $resdata['PACKAGE'], 'signType' => $resdata['SIBGTYPE'], 'sign' => $resdata['PAYSIGN']);
		return rjson(0, '创建微信支付订单成功', $pays);
	}
	public function create_wxpay_h5($option, $config)
	{
	}
	public function refund($option, $config)
	{
		$data = array('agentNum' => '10001015538389095192551', 'customerNum' => '10001116504507981042309', 'shopNum' => '10001216504509951767083', 'requestNum' => $option['order_number'], 'refundPartAmount' => $option['refund_price']);
		$timestamp = time();
		$path = "/v3/order/refund/part";
		$signarr['secretKey'] = $config['key'];
		$signarr['timestamp'] = $timestamp;
		$signarr['path'] = $path;
		$signarr['body'] = json_encode($data);
		$header[] = 'token:' . strtoupper(sha1(urldecode(http_build_query($signarr))));
		$header[] = 'timestamp:' . $timestamp;
		$header[] = "accessKey:a45a354ea12744e5b9e3aefea2f891534e27e0e1";
		$res = $this->http_post($this->apiurl . $path, $data, $header);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg'], $res['data']);
		}
		return rjson(0, '退款成功', $res);
	}
	private function http_post($url, $param, $header)
	{
		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, json_encode($param));
		curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
		curl_setopt($oCurl, CURLOPT_HEADER, 0);
		$header[] = "Content-Type:application/json";
		curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			$result = json_decode($sContent, true);
			if (!isset($result['error'])) {
				return rjson(0, $result['result'], $result);
			} else {
				return rjson(1, $result['error']['errorCode'], $result);
			}
		} else {
			return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"]);
		}
	}
}