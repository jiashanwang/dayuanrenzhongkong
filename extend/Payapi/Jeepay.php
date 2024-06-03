<?php

//decode by http://chiran.taobao.com/
namespace Payapi;

class Jeepay
{
	private $apiurl = "https://pay.pbpay.vip/";
	public function create_wxpay_js($option, $config)
	{
		$exoption = parseMaoArr($config['wx_exoption']);
		if (!isset($exoption['appId'])) {
			return rjson(1, '请配置扩展参数appId');
		}
		$data = array('mchNo' => $config['mch_id'], 'appId' => $exoption['appId'], 'mchOrderNo' => $option['order_number'], 'wayCode' => 'QR_CASHIER', 'amount' => (int) ($option['total_price'] * 100), 'currency' => 'cny', 'subject' => $option['body'], 'body' => $option['body'], 'notifyUrl' => C('WEB_URL') . 'api.php/notify/jeepay', 'channelExtra' => json_encode(array('openid' => $option['openid'])), 'reqTime' => intval(time() * 1000), 'version' => '1.0', 'signType' => 'MD5');
		ksort($data);
		$sign_str = urldecode(http_build_query($data)) . "&key=" . $config['key'];
		$data['sign'] = strtoupper(md5($sign_str));
		$res = $this->http_post($this->apiurl . "api/pay/unifiedOrder", $data);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg'], $res['data']);
		}
		$mweb_url = $res['data']['data']['payData'];
		return rjson(100, '创建微信支付订单成功', $mweb_url);
	}
	public function create_wxpay_h5($option, $config)
	{
	}
	public function refund($option, $config)
	{
		$exoption = parseMaoArr($config['wx_exoption']);
		if (!isset($exoption['appId'])) {
			return rjson(1, '请配置扩展参数appId');
		}
		$data = array('mchNo' => $config['mch_id'], 'appId' => $exoption['appId'], 'mchOrderNo' => $option['order_number'], 'mchRefundNo' => $option['order_number'], 'refundAmount' => (int) ($option['refund_price'] * 100), 'currency' => 'cny', 'refundReason' => "退款", 'reqTime' => intval(time() * 1000), 'version' => '1.0', 'signType' => 'MD5');
		ksort($data);
		$data['sign'] = strtoupper(md5(urldecode(http_build_query($data)) . "&key=" . $config['key']));
		$res = $this->http_post($this->apiurl . "api/refund/refundOrder", $data);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg'], $res['data']);
		}
		return rjson(0, '退款成功', $res);
	}
	private function http_post($url, $param)
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
			if ($result['code'] == 0) {
				return rjson(0, $result['msg'], $result);
			} else {
				return rjson(1, $result['msg'], $result);
			}
		} else {
			return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"]);
		}
	}
}