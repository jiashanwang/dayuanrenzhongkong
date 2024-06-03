<?php

//decode by http://chiran.taobao.com/
namespace app\yrapi\controller;

use app\common\library\KsAgent;
use app\common\model\Porder as PorderModel;
use think\Log;
use think\Response;
class Kuaishou extends Base
{
	public function _yrapibase()
	{
		Log::error(PHP_EOL . '[HEADER]' . var_export(request()->header(), 1) . PHP_EOL . '[POST]' . var_export(file_get_contents("php://input"), true) . PHP_EOL . '[GET]' . var_export($_GET, true));
		$this->user = M('Customer')->where(array('id' => C('KSCONFIG.userid')))->find();
	}
	private function checksign($indata)
	{
		$sign = $indata['sign'];
		$mysign = KsAgent::sign($indata);
		if ($mysign != $sign) {
			return rjson(1, '签名校验失败');
		}
		return rjson(0, '签名检验通过');
	}
	public function index()
	{
		$url = KsAgent::getCodeUrl();
		echo "<a href='" . $url . "'>开始授权</a>";
	}
	public function code_notify()
	{
		$indata = $_GET;
		if (!isset($indata['code'])) {
			return $this->jdret(4013008, '参数缺失');
		}
		$this->writelog(var_export($indata, true));
		return KsAgent::getrefreshToken($indata['code']);
	}
	public function recharge()
	{
		$indata = json_decode(file_get_contents("php://input"), true);
		if (!($indata && isset($indata['param']))) {
			return $this->jdret(4013008, '参数缺失');
		}
		$retsign = $this->checksign($indata);
		if ($retsign['errno'] != 0) {
			$this->jdret(4013007, '签名校验失败');
		}
		$param = $indata['param'];
		$this->writelog(var_export($param, true));
		$mobile = $param['mobile'];
		$relItemId = $param['relItemId'];
		$out_trade_num = $param['orderId'];
		$biztype = $param['bizType'];
		$amount = $param['amount'];
		if (!$mobile || !$relItemId || !$out_trade_num) {
			return $this->jdret(4013008, '参数缺失');
		}
		$ys = C('KS_PRODUCT_YS');
		if (!isset($ys[$relItemId])) {
			return $this->jdret(4012003, "不存在的产品映射关系");
		}
		$product_id = C('KS_PRODUCT_YS')[$relItemId];
		if (!M('product')->where(array('id' => $product_id))->find()) {
			return $this->jdret(4012003, "不存在的产品");
		}
		$res = PorderModel::kuaiShouOrder($mobile, $product_id, $out_trade_num, $biztype, $amount);
		if ($res['errno'] == 208) {
			return $this->jdret(1, '订单已经创建成功了', array('createTime' => date('Y-m-d\\TH:i:s+0800', time()), 'orderId' => $out_trade_num, 'orderNo' => $res['data'], 'status' => "ACCEPTED"));
		}
		if ($res['errno'] != 0) {
			return $this->jdret(4013010, '创建订单失败，' . $res['errmsg']);
		}
		$porder = $res['data'];
		return $this->jdret(1, '创建成功', array('createTime' => date('Y-m-d\\TH:i:s+0800', time()), 'orderId' => $out_trade_num, 'orderNo' => $porder['order_number'], 'status' => "ACCEPTED"));
	}
	public function order()
	{
		$indata = $_GET;
		if (!($indata && isset($indata['param']))) {
			return $this->jdret(4013011, 'param参数缺失');
		}
		$param = json_decode($indata['param'], true);
		$out_trade_num = $param['orderId'];
		if (!$out_trade_num) {
			return $this->jdret(4013011, '业务订单号参数缺失');
		}
		$porder = M('porder')->where(array('out_trade_num' => $out_trade_num, 'customer_id' => $this->user['id'], 'is_del' => 0))->field("create_time,kuaishou_biztype,order_number,status,out_trade_num,create_time,mobile,product_id")->find();
		if (!$porder) {
			return $this->jdret(4012002, '订单不存在');
		}
		$state = PorderModel::getState($porder['status']);
		if ($state == 1) {
			$status = "SUCCESS";
		} elseif ($state == 2) {
			$status = "FAILED";
		} else {
			$status = "ACCEPTED";
		}
		return $this->jdret(1, '查询成功', array('createTime' => date('Y-m-d\\TH:i:s+0800', $porder['create_time']), 'orderId' => $out_trade_num, 'mobile' => $porder['mobile'], 'bizType' => $porder['kuaishou_biztype'], 'orderNo' => $porder['order_number'], 'status' => $status));
	}
	private function jdret($code, $errmsg, $param = [])
	{
		$data['data'] = $param;
		$data['result'] = $code;
		$data['error_msg'] = $errmsg;
		$this->writelog(var_export($data, true));
		return Response::create($data, 'json');
	}
	private function writelog($text)
	{
		($myfile = fopen("jindong_log_" . time_format(time(), 'Ymd') . ".txt", "a")) || die("Unable to open file!");
		fwrite($myfile, '---------start---------' . "\r\n" . time_format(time()) . "\r\n" . $text . "\r\n---------end---------\r\n\r\n");
		fclose($myfile);
	}
}