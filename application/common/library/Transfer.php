<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use Util\Random;
use Util\Rsa;
class Transfer
{
	public static $msg;
	public static function wx_transfers($appid, $openid, $partner_trade_no, $totalfee, $desc = '付款到零钱')
	{
		$config = M('weixin')->where(array('appid' => $appid, 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '微信支付，没有配置JS支付信息！');
		}
		if (!$config['mch_id']) {
			return rjson(1, '未配置微信mch_id');
		}
		if (!$config['key']) {
			return rjson(1, '未配置微信支付key');
		}
		if (!$config['cert_pem']) {
			return rjson(1, '未上传微信提现cert证书');
		}
		if (!$config['key_pem']) {
			return rjson(1, '未上传微信提现key证书');
		}
		if (!$openid) {
			return rjson(1, '未设置用户openid');
		}
		$data = array('mch_appid' => $appid, 'mchid' => $config['mch_id'], 'nonce_str' => Random::alnum(25), 'partner_trade_no' => $partner_trade_no, 'openid' => $openid, 'check_name' => 'NO_CHECK', 'amount' => $totalfee, 'desc' => $desc, 'spbill_create_ip' => get_client_ip());
		$wxpay = new \Util\Wxpay();
		$data['sign'] = $wxpay->getSign($data, $config['key']);
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
		$sslcert = ROOT_PATH . DS . 'public' . $config['cert_pem'];
		$sslkey = ROOT_PATH . DS . 'public' . $config['key_pem'];
		$trid = M('transfers_log')->insertGetId(array('trade_no' => $partner_trade_no, 'totalfee' => $totalfee, 'param' => json_encode($data), 'create_time' => time()));
		$ret = $wxpay->postXmlSSLCurl($wxpay->arrayToXml($data), $url, $sslcert, $sslkey);
		if (!$ret) {
			M('transfers_log')->where(array('id' => $trid))->setField(array('return_all' => '没有返回值', 'status' => 0, 'err_msg' => '没有返回值'));
			return rjson(1, '微信付款零钱请求失败');
		}
		$json = json_decode(json_encode(simplexml_load_string($ret, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		if ($json['return_code'] != 'SUCCESS') {
			M('transfers_log')->where(array('id' => $trid))->setField(array('return_all' => json_encode($json), 'status' => 0, 'err_msg' => $json['return_msg']));
			return rjson(1, $json['return_msg'], $json);
		}
		if ($json['result_code'] != 'SUCCESS') {
			M('transfers_log')->where(array('id' => $trid))->setField(array('return_all' => json_encode($json), 'status' => 0, 'err_msg' => $json['err_code_des']));
			return rjson(1, $json['err_code_des'], $json);
		}
		M('transfers_log')->where(array('id' => $trid))->setField(array('return_all' => json_encode($json), 'status' => 1));
		return rjson(0, '付款成功', $json);
	}
	public static function wx_pay_bank($config, $sslcert, $sslkey, $partner_trade_no, $bank_no, $name, $bank_code, $totalfee, $desc = '付款到银行卡')
	{
		if (!$config['appid']) {
			self::$msg = '未配置微信商户账号appi';
			return false;
		}
		if (!$config['mch_id']) {
			self::$msg = '未配置微信商户号mch_id';
			return false;
		}
		if (!$config['key']) {
			self::$msg = '未配置微信支付key';
			return false;
		}
		if (!$config['publickey']) {
			self::$msg = '公钥未配置';
			return false;
		}
		if (!$sslcert) {
			self::$msg = '未上传微信cert证书';
			return false;
		}
		if (!$sslkey) {
			self::$msg = '未上传微信key证书';
			return false;
		}
		$wxpay = new \Util\Wxpay();
		$rsa = new RSA($config['publickey']);
		$data = array('mch_id' => $config['mch_id'], 'nonce_str' => Random::alnum(25), 'partner_trade_no' => $partner_trade_no, 'enc_bank_no' => $rsa->pubEncrypt($bank_no), 'enc_true_name' => $rsa->pubEncrypt($name), 'bank_code' => $bank_code, 'amount' => $totalfee, 'desc' => $desc);
		$data['sign'] = $wxpay->getSign($data, $config['key']);
		$sslcert = $_SERVER['DOCUMENT_ROOT'] . $sslcert;
		$sslkey = $_SERVER['DOCUMENT_ROOT'] . $sslkey;
		$trid = M('transfers_log')->insertGetId(array('trade_no' => $partner_trade_no, 'totalfee' => $totalfee, 'param' => json_encode($data), 'create_time' => time()));
		$ret = $wxpay->postXmlSSLCurl($wxpay->arrayToXml($data), "https://api.mch.weixin.qq.com/mmpaysptrans/pay_bank", $sslcert, $sslkey);
		if ($ret) {
			$json = json_decode(json_encode(simplexml_load_string($ret, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			if ($json['return_code'] == "SUCCESS") {
				if ($json['result_code'] == 'SUCCESS') {
					M('transfers_log')->where(array('id' => $trid))->setField(array('return_all' => json_encode($json), 'status' => 1, 'err_msg' => $json['return_msg']));
					return true;
				} else {
					self::$msg = $json['err_code_des'];
					return false;
				}
			}
			M('transfers_log')->where(array('id' => $trid))->setField(array('return_all' => json_encode($json), 'status' => 0, 'err_msg' => $json['return_msg']));
			self::$msg = $json['return_msg'];
			return false;
		}
		M('transfers_log')->where(array('id' => $trid))->setField(array('return_all' => '没有返回值', 'status' => 0, 'err_msg' => '没有返回值'));
		self::$msg = "请求失败！";
		return false;
	}
	public static function wx_get_public_key($mch_id, $nonce_str, $key, $sslcert, $sslkey)
	{
		$wxpay = new \Util\Wxpay();
		$publickeydata = array('mch_id' => $mch_id, 'nonce_str' => $nonce_str, 'sign_type' => 'MD5');
		$sslcert = $_SERVER['DOCUMENT_ROOT'] . $sslcert;
		$sslkey = $_SERVER['DOCUMENT_ROOT'] . $sslkey;
		$publickeydata['sign'] = $wxpay->getSign($publickeydata, $key);
		$ret_pu_key = $wxpay->postXmlSSLCurl($wxpay->arrayToXml($publickeydata), "https://fraud.mch.weixin.qq.com/risk/getpublickey ", $sslcert, $sslkey);
		if ($ret_pu_key) {
			$json_pu_key = json_decode(json_encode(simplexml_load_string($ret_pu_key, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			if ($json_pu_key['return_code'] == "SUCCESS" && $json_pu_key['result_code'] == 'SUCCESS') {
				($myfile = fopen($mch_id . "pub.pem", "w")) || die("Unable to open file!");
				fwrite($myfile, $json_pu_key['pub_key']);
				fclose($myfile);
				return true;
			}
			self::$msg = $json_pu_key['return_msg'];
		}
		return false;
	}
	public static function ali_transfers($weixin_appid, $account, $real_name, $out_biz_no, $trans_amount, $desc = '转账到支付宝')
	{
		$config = M('weixin')->where(array('appid' => $weixin_appid, 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '支付宝提现，没有配置信息！');
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
		$biz_content = json_encode(array('out_biz_no' => $out_biz_no, 'payee_type' => 'ALIPAY_LOGONID', 'payee_account' => $account, 'amount' => $trans_amount, 'payer_show_name' => $desc, 'payee_real_name' => $real_name, 'remark' => '单笔转账'));
		vendor('alipay.AopSdk');
		$c = new \AopClient();
		$c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
		$c->appId = $config['alipay_appid'];
		$c->rsaPrivateKey = $config['alipay_privatekey'];
		$c->alipayrsaPublicKey = $config['alipay_publickey'];
		$c->format = "json";
		$c->charset = "utf-8";
		$c->signType = "RSA2";
		$request = new \AlipayFundTransToaccountTransferRequest();
		$request->setBizContent($biz_content);
		$result = $c->execute($request);
		if ($result->alipay_fund_trans_toaccount_transfer_response->code == '10000') {
			return rjson(0, '成功', $result->alipay_fund_trans_toaccount_transfer_response);
		} else {
			return rjson(1, '失败', $result->alipay_fund_trans_toaccount_transfer_response->msg);
		}
	}
}