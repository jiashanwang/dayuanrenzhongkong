<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\library\Email;
use app\common\library\PayWay;
use app\common\model\OrderUpgrade;
use app\common\model\Porder as PorderModel;
use think\Log;
class Notify extends \app\common\controller\Base
{
	public function _base()
	{
	}
	private function notify($info)
	{
		$szm = substr($info['order_number'], 0, 3);
		switch ($szm) {
			case 'AAA':
			case PorderModel::prfun():
				PorderModel::notify($info['order_number'], $info['payway'], $info['serial_number']);
				break;
			case OrderUpgrade::PR:
				OrderUpgrade::notify($info['order_number'], $info['payway'], $info['serial_number']);
				break;
			default:
				break;
		}
	}
	public function weixin()
	{
		$xml = file_get_contents("php://input");
		$data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		$config = M('weixin')->where(array('appid' => $data['appid'], 'type' => $data['trade_type'] == 'MWEB' ? 2 : 1, 'is_del' => 0))->find();
		if (!$config) {
			return;
		}
		Vendor("wxpay.WxPayPubHelper");
		$notify = new \Notify_pub(array('appid' => $config['appid'], 'mch_id' => $config['mch_id'], 'key' => $config['key'], 'appsecret' => $config['appsecret']));
		$notify->saveData($xml);
		if ($notify->checkSign() == FALSE) {
			$notify->setReturnParameter("return_code", "FAIL");
			$notify->setReturnParameter("return_msg", "签名失败");
		} else {
			$notify->setReturnParameter("return_code", "SUCCESS");
		}
		$returnXml = $notify->returnXml();
		echo $returnXml;
		$sv = array('order_number' => $data['out_trade_no'], 'sign' => $data['sign'], 'channel_type' => '微信支付', 'trade_status' => $data['result_code'], 'transaction_id' => $data['transaction_id'], 'transaction_type' => '支付', 'total_fee' => $data['total_fee'], 'allstring' => $returnXml, 'create_time' => time(), 'optionals' => isset($data['attach']) ? $data['attach'] : "");
		M('pay_log')->insertGetId($sv);
		if ($notify->checkSign() == TRUE) {
			$info = array('order_number' => $data['out_trade_no'], 'money' => intval(strval(floatval($data['total_fee']) / 100)), 'serial_number' => $data['transaction_id'], 'pay_time' => strtotime($data['time_end']), 'payway' => $data['trade_type'] == 'MWEB' ? PayWay::PAY_WAY_H5YS : ($config['type'] == 1 ? PayWay::PAY_WAY_JSYS : PayWay::PAY_WAY_MPYS));
			$this->notify($info);
		}
	}
	public function alipay()
	{return;
		$data = $_POST;
		vendor('alipay.AopSdk');
		$aop = new \AopClient();
		$config = M('weixin')->where(array('alipay_appid' => $data['app_id'], 'is_del' => 0, 'type' => 2))->find();
		if (!$config) {
			echo 'fail';
			return;
		}
		$aop->alipayrsaPublicKey = $config['alipay_publickey'];
		$flag = $aop->rsaCheckV1($data, NULL, "RSA2");
		if ($flag) {
			if (M('pay_log')->where(array('transaction_id' => $data['trade_no']))->find()) {
				echo 'success';
				exit;
			}
			$sv = array('order_number' => $data['out_trade_no'], 'sign' => $data['sign'], 'channel_type' => '支付宝', 'trade_status' => $data['trade_status'], 'transaction_id' => $data['trade_no'], 'transaction_type' => '支付', 'total_fee' => intval(strval(floatval($data['total_amount']) * 100)), 'allstring' => json_encode($data), 'create_time' => time(), 'optionals' => isset($data['passback_params']) ? $data['passback_params'] : "");
			M('pay_log')->insertGetId($sv);
			$info = array('order_number' => $data['out_trade_no'], 'money' => floatval($data['total_amount']), 'serial_number' => $data['trade_no'], 'payway' => PayWay::PAY_WAY_ALIH5);
			$this->notify($info);
			echo 'success';
		} else {
			echo 'fail';
		}
	}
	public function jindjuhe()
	{return;
		$str = file_get_contents("php://input");
		$data = json_decode($str, true);
		if ($data['status'] != 'SUCCESS') {
			return;
		}
		$sv = array('order_number' => $data['requestNum'], 'sign' => '', 'channel_type' => '支付宝', 'trade_status' => $data['status'], 'transaction_id' => $data['requestNum'], 'transaction_type' => '支付', 'total_fee' => intval(strval(floatval($data['orderAmount']) * 100)), 'allstring' => json_encode($data), 'create_time' => time(), 'optionals' => isset($data['extraInfo']) ? $data['extraInfo'] : "");
		M('pay_log')->insertGetId($sv);
		$info = array('order_number' => $data['requestNum'], 'money' => floatval($data['orderAmount']), 'serial_number' => $data['orderNum'], 'payway' => PayWay::PAY_WAY_JSYS);
		$this->notify($info);
	}
	public function jeepay()
	{return;
		$data = $_GET;
		if ($data['state'] != 2) {
			return;
		}
		$sv = array('order_number' => $data['mchOrderNo'], 'sign' => '', 'channel_type' => '支付宝', 'trade_status' => $data['state'], 'transaction_id' => $data['payOrderId'], 'transaction_type' => '支付', 'total_fee' => intval(strval(floatval($data['amount']) * 100)), 'allstring' => json_encode($data), 'create_time' => time(), 'optionals' => isset($data['body']) ? $data['body'] : "");
		M('pay_log')->insertGetId($sv);
		$info = array('order_number' => $data['mchOrderNo'], 'money' => floatval($data['amount']), 'serial_number' => $data['payOrderId'], 'payway' => PayWay::PAY_WAY_JSYS);
		$this->notify($info);
	}
	public function balance($order_number)
	{
		$this->notify(array('order_number' => $order_number, 'serial_number' => $order_number, 'payway' => PayWay::PAY_WAY_BLA));
	}
	public function offline($order_number)
	{
		$this->notify(array('order_number' => $order_number, 'serial_number' => $order_number, 'payway' => PayWay::PAY_WAY_OFFL));
	}
	private function write($text)
	{
		($myfile = fopen("paylog.txt", "a")) || die("Unable to open file!");
		fwrite($myfile, $text . "\r\n");
		fclose($myfile);
	}
}