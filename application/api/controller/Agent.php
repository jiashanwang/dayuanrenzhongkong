<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\model\Customer as CustomerModel;
use app\common\model\CustomerHezuoPrice;
use app\common\model\Porder as PorderModel;
use app\common\model\Product as ProductModel;
class Agent extends Home
{
	public function _inithomechild()
	{
		if ($this->isfxh5) {
			djson(1, "代理H5端不可访问！")->send();
			exit;
		}
		$customer = CustomerModel::getInfo($this->customer['id']);
		if ($customer['is_agent'] == 0) {
			djson(1, "非代理不可访问！")->send();
			exit;
		}
	}
	public function get_invite_log()
	{
		$lists = M('customer')->where(array('f_id' => $this->customer['id'], 'is_del' => 0))->field("id,username,headimg,create_time")->order("create_time desc")->paginate(20);
		return djson(0, 'ok', $lists);
	}
	public function tg_links()
	{
		return djson(0, 'ok', array(C('WEB_URL') . '#/?vi=' . $this->customer['id'] . "&appid=" . $this->wxconfig['id']));
	}
	public function rebate_order()
	{
		$map['rebate_id'] = $this->customer['id'];
		$map['status'] = array('gt', 1);
		if (I('key')) {
			$map['customer_id|order_number'] = I('key');
		}
		if (I('is_rebate')) {
			$map['is_rebate'] = 1;
		}
		if (I('end_time') && I('begin_time')) {
			$map['create_time'] = array('between', array(strtotime(I('begin_time')), strtotime(I('end_time')) + 86400));
		}
		$data['lists'] = PorderModel::with(array('customer' => function ($query) {
			$query->field('id,grade_id,username,headimg');
		}))->where($map)->field("order_number,type,customer_id,status,mobile,total_price,product_name,rebate_price,is_rebate,create_time,title,rebate_time")->order("create_time desc")->paginate(20)->each(function ($item) {
			$item['rebate_status_text'] = $item->getRebateStatusText($item->is_rebate, $item->status);
		});
		$data['total_price'] = M('porder')->where($map)->sum('total_price');
		$data['rebate_price'] = M('porder')->where($map)->where(array('status' => array('not in', array(1, 7, 5, 6))))->sum('rebate_price');
		$data['counts'] = M('porder')->where($map)->count();
		return djson(0, 'ok', $data);
	}
	public function hzPriceList()
	{
		$customer = M('customer')->where(array('id' => $this->customer['id']))->find();
		$map['p.is_del'] = 0;
		$map['p.added'] = 1;
		$key = trim(I('key'));
		if ($key) {
			if (I('query_name')) {
				$map[I('query_name')] = $key;
			} else {
				$map['p.name|p.desc'] = array('like', '%' . $key . '%');
			}
		}
		if (I('type')) {
			$map['p.type'] = I('type');
		}
		if (I('cate_id')) {
			$map['p.cate_id'] = I('cate_id');
		}
		if (I('product_id')) {
			$map['p.id'] = I('product_id');
		}
		$resdata = ProductModel::getProducts($map, $customer['id']);
		$cates = $resdata['data'];
		foreach ($cates as &$cate) {
			foreach ($cate['products'] as &$item) {
				$hzprice = M('customer_hezuo_price')->where(array('customer_id' => $customer['id'], 'product_id' => $item['id']))->field('id as rangesid,ranges,ys_tag')->find();
				if (!$hzprice) {
					$item['ys_tag'] = '';
					$item['rangesid'] = 0;
					$item['ranges'] = 0;
				} else {
					$item['ys_tag'] = $hzprice['ys_tag'];
					$item['rangesid'] = $hzprice['rangesid'];
					$item['ranges'] = $hzprice['ranges'];
				}
			}
		}
		return djson(0, '', $cates);
	}
	public function upHzPrice()
	{
		$pr_id = I('id');
		$product_id = I('product_id');
		$customer = M('customer')->where(array('id' => $this->customer['id']))->find();
		$map['p.is_del'] = 0;
		$map['p.id'] = $product_id;
		$resdata = ProductModel::getProduct($map, $customer['id']);
		if ($resdata['errno'] != 0) {
			return djson(1, $resdata['errmsg']);
		}
		$product = $resdata['data'];
		$ranges = floatval(I('ranges'));
		if ($ranges < 0) {
			return djson(1, '浮动金额不能小于0');
		}
		if (floatval($product['max_price']) > 0 && $product['price'] + $ranges > $product['max_price']) {
			return djson(1, '不能设置高于封顶价格');
		}
		$res = CustomerHezuoPrice::saveValues($pr_id, $this->customer['id'], $product_id, array('ranges' => $ranges));
		return djson($res['errno'], $res['errmsg'], $res['data']);
	}
	public function uphz_ystag()
	{
		$res = CustomerHezuoPrice::saveValues(I('id'), $this->customer['id'], I('product_id'), array('ys_tag' => I('ys_tag')));
		return djson($res['errno'], $res['errmsg'], $res['data']);
	}
	public function get_tixian_styles()
	{
		$arr = array(array('id' => '0', 'name' => '请选择'));
		$types = C('TIXIAN_STYLE');
		foreach ($types as $k => $item) {
			$arr[] = array('id' => $k, 'name' => $item);
		}
		$tx_d_str = M('customer')->where(array('id' => $this->customer['id']))->value('tixian_data');
		if ($tx_d_str && ($data = json_decode($tx_d_str, true))) {
		} else {
			$data = array('money' => '', 'acount' => '', 'name' => '', 'style' => 0);
		}
		$active = 0;
		foreach ($arr as $key => &$item) {
			if ($item['id'] == $data['style']) {
				$active = $key;
				continue;
			}
		}
		return rjson(0, 'ok', array('txdata' => $data, 'styles' => $arr, 'styles_index' => $active));
	}
}