<?php

//decode by http://chiran.taobao.com/
namespace app\yrapi\controller;

use app\common\library\JdAgent;
use app\common\model\Porder as PorderModel;
use think\Response;
class Jindong extends Base
{
	public function _yrapibase()
	{
		$this->user = M('Customer')->where(array('id' => C('JDCONFIG.userid')))->find();
	}
	public function recharge()
	{
		$this->writelog(var_export($_POST, true));
		$mobile = I('produceAccount');
		$product_id = I('wareNo');
		$out_trade_num = I('jdOrderNo');
		$notify_url = I('notifyUrl');
		if (!$mobile || !$product_id || !$out_trade_num || !$notify_url) {
			return $this->jdret(array('code' => 'JDO_500', 'jdOrderNo' => $out_trade_num, 'agentOrderNo' => '', 'produceStatus' => 4));
		}
		if (!M('product')->where(array('id' => $product_id))->find()) {
			return $this->jdret(array('code' => 'JDO_302', 'jdOrderNo' => $out_trade_num, 'agentOrderNo' => '', 'produceStatus' => 2));
		}
		$res = PorderModel::jinDongOrder($mobile, $product_id, $out_trade_num, $notify_url);
		if ($res['errno'] == 208) {
			return $this->jdret(array('code' => 'JDO_201', 'jdOrderNo' => $out_trade_num, 'agentOrderNo' => $res['data'], 'produceStatus' => 3));
		}
		if ($res['errno'] != 0) {
			return $this->jdret(array('code' => 'JDO_302', 'jdOrderNo' => $out_trade_num, 'agentOrderNo' => '', 'produceStatus' => 2));
		}
		$porder = $res['data'];
		return $this->jdret(array('code' => 'JDO_201', 'jdOrderNo' => $out_trade_num, 'agentOrderNo' => $porder['order_number'], 'produceStatus' => 3));
	}
	public function order()
	{
		$this->writelog(var_export($_POST, true));
		$out_trade_num = I('jdOrderNo');
		if (!$out_trade_num) {
			return $this->jdret(array('code' => "JDO_500", 'jdOrderNo' => $out_trade_num, 'agentOrderNo' => '', 'produceStatus' => 2));
		}
		$porder = M('porder')->where(array('out_trade_num' => $out_trade_num, 'customer_id' => $this->user['id'], 'is_del' => 0))->field("order_number,status,out_trade_num,create_time,mobile,product_id")->find();
		$state = PorderModel::getState($porder['status']);
		if ($state == 1) {
			$state = 1;
			$code = "JDO_200";
		} elseif ($state == 2) {
			$state = 2;
			$code = "JDO_302";
		} else {
			$state = 3;
			$code = "JDO_201";
		}
		return $this->jdret(array('code' => $code, 'jdOrderNo' => $out_trade_num, 'agentOrderNo' => $porder['order_number'], 'produceStatus' => $state));
	}
	private function jdret($data)
	{
		$data['timestamp'] = date("YmdHis", time());
		$data['sign'] = JdAgent::sign($data);
		$data['signType'] = 'MD5';
		return Response::create($data, 'json');
	}
	private function writelog($text)
	{
		($myfile = fopen("jindong_log.txt", "a")) || die("Unable to open file!");
		fwrite($myfile, '---------start---------' . "\r\n" . time_format(time()) . "\r\n" . $text . "\r\n---------end---------\r\n\r\n");
		fclose($myfile);
	}
}