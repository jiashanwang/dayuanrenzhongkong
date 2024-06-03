<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\library\Otherapi;
use app\common\model\Product;
class Index extends Home
{
	public function get_product()
	{
		$map['p.is_del'] = 0;
		$map['p.added'] = 1;
		$map['p.show_style'] = array('in', $this->isfxh5 ? '1,3' : '1,2');
		$map['p.type'] = I('type') ?: 1;
		if (I('isp')) {
			$map['p.isp'] = array('like', '%' . I('isp') . '%');
		}
		$resdata = Product::getProducts($map, $this->customer['id']);
		$data = $resdata['data'];
		return djson(0, 'ok', $data);
	}
	public function get_product_mobile()
	{
		$mobile = I('mobile');
		$guishu = QCellCore($mobile);
		if ($guishu['errno'] != 0) {
			return djson($guishu['errno'], $guishu['errmsg']);
		}
		$guishu = Product::Ispzhan($mobile, $this->customer['id'], $guishu);
		$map['p.added'] = 1;
		$map['p.is_del'] = 0;
		$map['p.show_style'] = array('in', $this->isfxh5 ? '1,3' : '1,2');
		$map['p.isp'] = array('like', '%' . $guishu['data']['isp'] . '%');
		$map['p.type'] = I('type') ?: 1;
		$resdata = Product::getProducts($map, $this->customer['id'], $guishu['data']['prov'], $guishu['data']['city']);
		$data['lists'] = $resdata['data'];
		$data['guishu'] = $guishu['data'];
		return djson(0, 'ok', $data);
	}
	public function get_product_type()
	{
		if ($this->isfxh5) {
			$types = M('product_type p')->join('agent_product_type ap', 'ap.product_type_id=p.id')->where(array('p.status' => 1, 'ap.status' => 1, 'ap.customer_id' => $this->wxconfig['customer_id']))->order('p.sort asc,p.id asc')->field('ap.status,ap.tishidoc,p.id,p.type_name,p.sort,p.account_type,p.typec_id')->select();
		} else {
			$types = M('product_type')->where(array('status' => 1))->order('sort asc,id asc')->select();
		}
		foreach ($types as &$type) {
			$type['typec'] = Product::getTypec($type['typec_id']);
		}
		return djson(0, 'ok', $types);
	}
	public function get_history_acount()
	{
		$map['status'] = array('gt', 1);
		$map['customer_id'] = $this->customer['id'];
		if (I('type')) {
			$map['type'] = I('type');
		}
		$lists = M('porder')->where($map)->group('mobile')->order('id desc')->field('mobile,isp')->select();
		return djson(0, 'ok', $lists);
	}
	public function phone_balance_query()
	{
		$mobile = I('account');
		$guishu = QCellCore($mobile);
		if ($guishu['errno'] != 0) {
			return djson($guishu['errno'], $guishu['errmsg']);
		}
		return Otherapi::phoneBalanceQuery($this->customer['id'], $mobile, array('prov' => $guishu['data']['prov'], 'city' => $guishu['data']['city'], 'isp' => $guishu['data']['ispstr']));
	}
	public function ele_balance_query()
	{
		return Otherapi::eleBalanceQuery($this->customer['id'], I('account'), array('prov' => I('prov'), 'city' => I('city')));
	}
	public function qq_nick_query()
	{
		return Otherapi::qqNickQuery($this->customer['id'], I('account'));
	}
}