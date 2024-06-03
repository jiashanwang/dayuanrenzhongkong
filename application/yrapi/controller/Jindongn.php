<?php

//decode by http://chiran.taobao.com/
namespace app\yrapi\controller;

use app\common\model\Porder as PorderModel;
use think\Log;
use think\Response;
class Jindongn extends Base
{
	public function _yrapibase()
	{
		$this->user = M('Customer')->where(array('id' => C('JDCONFIG.userid')))->find();
	}
	public function recharge()
	{
		Log::error('kakafs_POST' . "_" . var_export($_POST, true));
		Log::error('kakafs_DATA' . "_" . var_export(base64_decode(I('data')), true));
		$this->writelog(var_export($_POST, true));
		$data = json_decode(base64_decode(I('data')), true);
		$mobile = $data['gameAccount'];
		$skuId = $data['skuId'];
		$out_trade_num = $data['orderId'];
		$ys = C('JDSKUYS');
		if (!isset($ys[$skuId])) {
			return $this->jdret(109, "不存在的产品映射关系");
		}
		$product_id = $ys[$skuId];
		if (intval($data['buyNum']) > 1) {
			return $this->jdret(110, '单次不能下多件');
		}
		if (!$mobile || !$product_id || !$out_trade_num) {
			return $this->jdret(104, '参数有误');
		}
		if (!M('product')->where(array('id' => $product_id))->find()) {
			return $this->jdret(109, '没有此商品');
		}
		$res = PorderModel::jinDongOrder($mobile, $product_id, $out_trade_num, '');
		if ($res['errno'] == 208) {
			return $this->jdret(102, '订单已存在');
		}
		if ($res['errno'] != 0) {
			return $this->jdret(999, $res['errmsg']);
		}
		return $this->jdret(100, '成功');
	}
	public function order()
	{
		Log::error('kakafs_POST' . "_" . var_export($_POST, true));
		Log::error('kakafs_DATA' . "_" . var_export(base64_decode(I('data')), true));
		$this->writelog(var_export($_POST, true));
		$data = json_decode(base64_decode(I('data')), true);
		$out_trade_num = $data['orderId'];
		if (!$out_trade_num) {
			return $this->jdret(104, '参数错误');
		}
		$porder = M('porder')->where(array('out_trade_num' => $out_trade_num, 'customer_id' => $this->user['id'], 'is_del' => 0))->field("order_number,status,out_trade_num,create_time,mobile,product_id")->find();
		if (!$porder) {
			return $this->jdret(101, '订单不存在');
		}
		$state = PorderModel::getState($porder['status']);
		if ($state == 1) {
			return $this->jdret(100, '查询成功', array('orderStatus' => 0));
		} elseif ($state == 2) {
			return $this->jdret(100, '查询成功', array('orderStatus' => 2));
		} else {
			return $this->jdret(100, '查询成功', array('orderStatus' => 1));
		}
	}
	private function jdret($retcode, $retmsg, $datas = '')
	{
		$data['retCode'] = $retcode;
		$data['retMessage'] = $retmsg;
		$datas && ($data['data'] = base64_encode(json_encode($datas)));
		$this->writelog(var_export($data, true) . "_" . var_export($datas, true));
		return Response::create($data, 'json');
	}
	private function writelog($text)
	{
		($myfile = fopen("jindongn_log_" . time_format(time(), 'Ymd') . ".txt", "a")) || die("Unable to open file!");
		fwrite($myfile, '---------start---------' . "\r\n" . time_format(time()) . "\r\n" . $text . "\r\n---------end---------\r\n\r\n");
		fclose($myfile);
	}
}