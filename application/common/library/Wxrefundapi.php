<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use Util\Random;
class Wxrefundapi
{
	public static function wxpay_refund($option)
	{
		$config = M('weixin')->where(array('appid' => $option['weixin_appid'], 'type' => $option['type'], 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '未配置微信退款参数');
		}
		$data = array('appid' => $config['appid'], 'mch_id' => $config['mch_id'], 'nonce_str' => Random::alnum(25), 'sign_type' => 'MD5', 'out_trade_no' => $option['order_number'], 'out_refund_no' => $option['order_number'], 'total_fee' => (int) ($option['total_price'] * 100), 'refund_fee' => (int) ($option['refund_price'] * 100));
		$wxpay = new \Util\Wxpay();
		$data['sign'] = $wxpay->getSign($data, $config['key']);
		$url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
		$sslcert = ROOT_PATH . DS . 'public' . $config['cert_pem'];
		$sslkey = ROOT_PATH . DS . 'public' . $config['key_pem'];
		$ret = $wxpay->postXmlSSLCurl($wxpay->arrayToXml($data), $url, $sslcert, $sslkey);
		if (!$ret) {
			return rjson(1, '退款失败|微信退款接口|请求接口未成功|可能原因：证书文件错误');
		}
		$json = json_decode(json_encode(simplexml_load_string($ret, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		if ($json['return_code'] != 'SUCCESS') {
			return rjson(1, '退款失败|微信退款接口|请求退款失败' . $json['return_msg']);
		}
		if ($json['result_code'] != 'SUCCESS') {
			return rjson(1, '退款失败|微信退款接口|' . $json['err_code_des']);
		}
		return rjson(0, '退款退款成功');
	}
	public static function alipay_refund($option)
	{
		$config = M('weixin')->where(array('appid' => $option['weixin_appid'], 'type' => 2, 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '未配置支付宝退款参数');
		}
		vendor('alipay.AopSdk');
		$aop = new \AopClient();
		$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
		$aop->appId = $config['alipay_appid'];
		$aop->rsaPrivateKey = $config['alipay_privatekey'];
		$aop->alipayrsaPublicKey = $config['alipay_publickey'];
		$aop->apiVersion = '1.0';
		$aop->signType = 'RSA2';
		$aop->postCharset = 'UTF-8';
		$aop->format = 'json';
		$request = new \AlipayTradeRefundRequest();
		$request->setBizContent("{" . "\"out_trade_no\":\"" . $option['order_number'] . "\"," . "\"trade_no\":\"" . $option['serial_number'] . "\"," . "\"refund_amount\":" . $option['refund_price'] . "," . "\"refund_reason\":\"" . $option['reason'] . "\"," . "\"out_request_no\":\"" . $option['order_number'] . "\"" . "  }");
		$result = $aop->execute($request);
		$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
		$resultCode = $result->{$responseNode}->code;
		if (!empty($resultCode) && $resultCode == 10000) {
			return rjson(0, '支付宝退款成功', $resultCode);
		} else {
			return rjson(1, '支付宝退款失败，错误码:' . $resultCode . ',' . $result->{$responseNode}->sub_msg, $resultCode);
		}
	}
}