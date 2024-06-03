<?php

//decode by http://chiran.taobao.com/
namespace Payapi;

class Payalipay
{
	public function create_alipay_h5($option, $config)
	{
		$url = C('WEB_URL') . 'api.php/more/alipay_h5.html?param=' . dyr_encrypt(json_encode($option));
		return rjson(0, 'ok', $url);
	}
	public static function create_alipay_do($option)
	{
		if (!isset($option['appid']) || !$option['appid']) {
			return rjson(1, '支付宝，appid参数缺失！');
		}
		$config = M('weixin')->where(array('appid' => $option['appid'], 'type' => 2, 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '支付宝支付，没有配置信息！');
		}
		if (!$config['alipay_appid']) {
			return rjson(1, '未配置支付宝appid');
		}
		if (!$config['alipay_privatekey']) {
			return rjson(1, '未配置支付宝应用私钥');
		}
		if (!$config['alipay_publickey']) {
			return rjson(1, '未配置支付宝公钥');
		}
		$biz_content = json_encode(array('body' => $option['body'], 'subject' => $option['body'], 'out_trade_no' => $option['order_number'], 'timeout_express' => '90m', 'total_amount' => $option['total_price'], 'product_code' => 'QUICK_WAP_WAY'));
		vendor('alipay.AopSdk');
		$c = new \AopClient();
		$c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
		$c->appId = $config['alipay_appid'];
		$c->rsaPrivateKey = $config['alipay_privatekey'];
		$c->alipayrsaPublicKey = $config['alipay_publickey'];
		$c->format = "json";
		$c->charset = "utf-8";
		$c->signType = "RSA2";
		$request = new \AlipayTradeWapPayRequest();
		$request->setBizContent($biz_content);
		$request->setNotifyUrl(C('WEB_URL') . 'api.php/notify/alipay');
		$request->setReturnUrl(C('WEB_URL') . '#/pages/index/index');
		$result = $c->pageExecute($request);
		return rjson(0, '成功', $result);
	}
	public function refund($option, $config)
	{
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
			return rjson(1, '支付宝退款失败', $resultCode);
		}
	}
}