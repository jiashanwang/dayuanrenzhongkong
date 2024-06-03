<?php

//decode by http://chiran.taobao.com/
namespace Payapi;

use Util\Random;
class Payweixin
{
	public function create_wxpay_js($option, $config)
	{
		if (!isset($option['appid']) || !$option['appid']) {
			return rjson(1, '微信支付，appid参数缺失！');
		}
		if (!$config['mch_id']) {
			return rjson(1, '未配置微信mch_id');
		}
		if (!$config['key']) {
			return rjson(1, '未配置微信支付key');
		}
		$data = array('appid' => $config['appid'], 'mch_id' => $config['mch_id'], 'nonce_str' => Random::alnum(25), 'openid' => $option['openid'], 'sign_type' => 'MD5', 'body' => $option['body'], 'detail' => $option['body'], 'attach' => json_encode(array('order_number' => $option['order_number'])), 'out_trade_no' => $option['order_number'], 'total_fee' => (int) ($option['total_price'] * 100), 'spbill_create_ip' => get_client_ip(), 'notify_url' => C('WEB_URL') . 'api.php/notify/weixin', 'trade_type' => "JSAPI");
		$wxpay = new \Util\Wxpay();
		$data['sign'] = $wxpay->getSign($data, $config['key']);
		$xml = $wxpay->postXmlCurl($wxpay->arrayToXml($data), "https://api.mch.weixin.qq.com/pay/unifiedorder");
		$ret = $wxpay->xmlToArray($xml);
		if (!$ret) {
			return rjson(1, '微信支付提交失败');
		}
		if ($ret['return_code'] != 'SUCCESS') {
			return rjson(1, $ret['return_msg']);
		}
		if ($ret['result_code'] != 'SUCCESS') {
			return rjson(1, $ret['err_code_des']);
		}
		$pays = array('appId' => $config['appid'], 'timeStamp' => time(), 'nonceStr' => Random::alnum(25), 'package' => 'prepay_id=' . $ret['prepay_id'], 'signType' => 'MD5');
		$pays['sign'] = $wxpay->getSign($pays, $config['key']);
		return rjson(0, '创建微信支付订单成功', $pays);
	}
	public function create_wxpay_h5($option, $config)
	{
		if (!isset($option['appid']) || !$option['appid']) {
			return rjson(1, '微信支付，appid参数缺失！');
		}
		if (!$config['mch_id']) {
			return rjson(1, '未配置微信mch_id');
		}
		if (!$config['key']) {
			return rjson(1, '未配置微信支付key');
		}
		$data = array('appid' => $config['appid'], 'mch_id' => $config['mch_id'], 'nonce_str' => Random::alnum(25), 'sign_type' => 'MD5', 'body' => $option['body'], 'detail' => $option['body'], 'attach' => json_encode(array('order_number' => $option['order_number'])), 'out_trade_no' => $option['order_number'], 'total_fee' => (int) ($option['total_price'] * 100), 'spbill_create_ip' => get_client_ip(), 'notify_url' => C('WEB_URL') . 'api.php/notify/weixin', 'trade_type' => "MWEB");
		$wxpay = new \Util\Wxpay();
		$data['sign'] = $wxpay->getSign($data, $config['key']);
		$xml = $wxpay->postXmlCurl($wxpay->arrayToXml($data), "https://api.mch.weixin.qq.com/pay/unifiedorder");
		$ret = $wxpay->xmlToArray($xml);
		if (!$ret) {
			return rjson(1, '微信支付提交失败');
		}
		if ($ret['return_code'] != 'SUCCESS') {
			return rjson(1, $ret['return_msg']);
		}
		if ($ret['result_code'] != 'SUCCESS') {
			return rjson(1, $ret['err_code_des']);
		}
		return rjson(0, '创建微信H5支付订单成功', $ret['mweb_url']);
	}
	public function refund($option, $config)
	{
		if (!$config['mch_id']) {
			return rjson(1, '未配置微信mch_id');
		}
		if (!$config['key']) {
			return rjson(1, '未配置微信支付key');
		}
		if (!$config['appid']) {
			return rjson(1, '未配置微信支付appid');
		}
		if (!$config['cert_pem']) {
			return rjson(1, '未配置微信支付证书文件');
		}
		if (!$config['key_pem']) {
			return rjson(1, '未配置微信支付证书秘钥文件');
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
}