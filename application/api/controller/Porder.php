<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\library\Createlog;
use app\common\library\Templetmsg;
use app\common\model\Client;
use app\common\model\Porder as PorderModel;
class Porder extends Home
{
	public function create_order()
	{
		$mobile = trim(I('mobile'));
		$product_id = I('product_id');
		$area = trim(I('area'));
		$city = trim(I('city'));
		$ytype = trim(I('ytype'));
		$id_card_no = trim(I('id_card_no'));
		$param1 = trim(I('param1'));
		$param2 = trim(I('param2'));
		$param3 = trim(I('param3'));
		$res = PorderModel::createOrder($mobile, $product_id, array('prov' => $area, 'city' => $city, 'ytype' => $ytype, 'id_card_no' => $id_card_no, 'param1' => $param1, 'param2' => $param2, 'param3' => $param3), $this->customer['id'], $this->client, '', '', '', '', $this->isfxh5);
		if ($res['errno'] != 0) {
			return djson($res['errno'], $res['errmsg'], $res['data']);
		}
		$aid = $res['data'];
		!$this->isfxh5 && PorderModel::compute_rebate($aid);
		Createlog::porderLog($aid, "用户客户端下单成功");
		return djson(0, "ok", array('id' => $aid));
	}
	public function topay()
	{
		$order_id = I('order_id');
		$paytype = I('paytype');
		if ($this->isfxh5 && $paytype == 3) {
			return djson(1, "请使用微信支付");
		}
		if ($paytype == 3) {
			$res = PorderModel::clientApiPayPorder($order_id, $this->customer['id']);
			if ($res['errno'] == 0) {
				return djson(101, $res['errmsg'], $res['data']);
			} else {
				return djson($res['errno'], $res['errmsg'], $res['data']);
			}
		} else {
			$res = PorderModel::create_pay($order_id, $paytype, $this->client);
			return djson($res['errno'], $res['errmsg'], $res['data']);
		}
	}
	public function order_list()
	{
		$map = array('customer_id' => $this->customer['id'], 'is_del' => 0, 'status' => array('gt', 1), 'is_apart' => array('in', array(0, 2)));
		if (I('type')) {
			$map['type'] = I('type');
		}
		if (I('key')) {
			$map['order_number|mobile'] = I('key');
		}
		$lists = PorderModel::where($map)->order("create_time desc")->paginate(10)->each(function ($item, $key) {
			C('IS_SHOW_CLIENT_REMARK') != 1 && ($item['remark'] = "");
			return $item;
		});
		if ($lists) {
			return djson(0, "ok", $lists);
		} else {
			return djson(1, "暂时还没有充值记录");
		}
	}
	public function orderinfo()
	{
		$info = M('porder')->where(array('customer_id' => $this->customer['id'], 'is_del' => 0, 'id' => I('id')))->find();
		if ($info) {
			return djson(0, "ok", $info);
		} else {
			return djson(1, "暂时没有记录信息");
		}
	}
	public function sub_complaint()
	{
		$porder = M('porder')->where(array('customer_id' => $this->customer['id'], 'is_del' => 0, 'id' => I('porder_id')))->find();
		if (!$porder) {
			return djson(1, '订单未找到');
		}
		$aid = M('porder_complaint')->insertGetId(array('customer_id' => $this->customer['id'], 'porder_id' => $porder['id'], 'name' => I('name'), 'mobile' => I('mobile'), 'issue' => I('issue'), 'create_time' => time(), 'status' => 1));
		return djson(0, '投诉提交成功，等待平台处理');
	}
	public function get_his_acount()
	{
		$map = array();
		if (I('type')) {
			$map['customer_id'] = I('type');
		}
		$map['customer_id'] = $this->customer['id'];
		$data = M('porder')->where($map)->group('mobile')->order('id desc')->field('mobile')->select();
		if (!$data) {
			return djson(1, '没有记录');
		}
		return djson(0, 'ok', $data);
	}
	public function jiema_veone_code()
	{
		$porder = M('porder')->where(array('customer_id' => $this->customer['id'], 'is_del' => 0, 'id' => I('porder_id'), 'status' => 1))->find();
		if (!$porder) {
			return djson(1, '订单未找到');
		}
		$product = M('product')->where(array('id' => $porder['product_id']))->field('id,jmapi_id,jmapi_param_id')->find();
		if (!$product) {
			return djson(1, '产品未找到');
		}
		$config = M('jmapi')->where(array('id' => $product['jmapi_id'], 'is_del' => 0))->find();
		$param = M('jmapi_param')->where(array('id' => $product['jmapi_param_id']))->find();
		if (!$config || !$param) {
			return djson(1, '接码api信息未查询到');
		}
		$classname = 'Jiema\\' . $config['callapi'];
		$model = new $classname($config);
		$param['extend_param1'] = $porder['extend_param1'];
		$param['extend_param2'] = $porder['extend_param2'];
		$res = $model->getVeOne($porder['mobile'], $porder['order_number'], $param);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg'], $res['data']);
		}
		M('porder')->where(array('id' => $porder['id']))->setField(array('extend_param1' => $res['data']['extend_param1'], 'extend_param2' => $res['data']['extend_param2']));
		return rjson(0, $res['errmsg'], $res['data']);
	}
	public function jiema_vetwo_code()
	{
		$porder = M('porder')->where(array('customer_id' => $this->customer['id'], 'is_del' => 0, 'id' => I('porder_id'), 'status' => 1))->find();
		if (!$porder) {
			return djson(1, '订单未找到');
		}
		$product = M('product')->where(array('id' => $porder['product_id']))->field('id,jmapi_id,jmapi_param_id')->find();
		if (!$product) {
			return djson(1, '产品未找到');
		}
		$config = M('jmapi')->where(array('id' => $product['jmapi_id'], 'is_del' => 0))->find();
		$param = M('jmapi_param')->where(array('id' => $product['jmapi_param_id']))->find();
		if (!$config || !$param) {
			return djson(1, '接码api信息未查询到');
		}
		$classname = 'Jiema\\' . $config['callapi'];
		$model = new $classname($config);
		$param['extend_param1'] = $porder['extend_param1'];
		$param['extend_param2'] = $porder['extend_param2'];
		$param['code'] = I('code');
		$svres = $model->check($porder['mobile'], $porder['order_number'], $param);
		if ($svres['errno'] != 52) {
			return rjson(1, $svres['errmsg']);
		}
		$svres = $model->saveVeCode($porder['mobile'], $porder['order_number'], $param);
		if ($svres['errno'] != 0) {
			return rjson(1, $svres['errmsg']);
		}
		$res = $model->getVeTow($porder['mobile'], $porder['order_number'], $param);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg']);
		}
		return rjson(0, $res['errmsg'], $res['data']);
	}
	public function jiema_save_last_code()
	{
		$porder = M('porder')->where(array('customer_id' => $this->customer['id'], 'is_del' => 0, 'id' => I('porder_id'), 'status' => 1))->find();
		if (!$porder) {
			return djson(1, '订单未找到');
		}
		$product = M('product')->where(array('id' => $porder['product_id']))->field('id,jmapi_id,jmapi_param_id')->find();
		if (!$product) {
			return djson(1, '产品未找到');
		}
		$code = I('code');
		if (!$code) {
			return djson(1, '请填入验证码');
		}
		$config = M('jmapi')->where(array('id' => $product['jmapi_id'], 'is_del' => 0))->find();
		$param = M('jmapi_param')->where(array('id' => $product['jmapi_param_id']))->find();
		if (!$config || !$param) {
			return djson(1, '接码api信息未查询到');
		}
		Createlog::porderLog($porder['id'], '接码api：' . $config['name'] . '产品:' . $param['desc']);
		M('porder')->where(array('id' => $porder['id']))->setField(array('extend_param3' => $code));
		return rjson(0, '继续');
	}
	public function jiema_check_order()
	{
		$porder = M('porder')->where(array('customer_id' => $this->customer['id'], 'is_del' => 0, 'id' => I('porder_id')))->find();
		if (!$porder) {
			return djson(1, '订单未找到');
		}
		$product = M('product')->where(array('id' => $porder['product_id']))->field('id,jmapi_id,jmapi_param_id')->find();
		if (!$product) {
			return djson(1, '产品未找到');
		}
		$config = M('jmapi')->where(array('id' => $product['jmapi_id'], 'is_del' => 0))->find();
		$param = M('jmapi_param')->where(array('id' => $product['jmapi_param_id']))->find();
		if (!$config || !$param) {
			return djson(1, '接码api信息未查询到');
		}
		$classname = 'Jiema\\' . $config['callapi'];
		$model = new $classname($config);
		$param['extend_param1'] = $porder['extend_param1'];
		$param['extend_param2'] = $porder['extend_param2'];
		$param['order_number'] = $porder['order_number'];
		$res = $model->check($porder['mobile'], $porder['order_number'], $param);
		return rjson($res['errno'], $res['errmsg'], $res['data']);
	}
}