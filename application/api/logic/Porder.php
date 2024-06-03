<?php

//decode by http://chiran.taobao.com/
namespace app\api\logic;

use think\Logic;
class Porder extends Logic
{
	public function create_order()
	{
		$this->allowMethods = 'post';
		$this->rules = array('mobile' => 'require', 'product_id' => 'require|number');
		$this->message = array('mobile' => '手机格式不正确', 'product_id' => '产品ID错误');
	}
	public function topay()
	{
		$this->allowMethods = 'post';
		$this->rules = array('order_id' => 'require|number', 'paytype' => 'require|number|in:1,2,3');
		$this->message = array('order_id' => '订单编号错误', 'paytype' => '支付方式错误');
	}
	public function orderinfo()
	{
		$this->allowMethods = 'post';
		$this->rules = array('id' => 'require|number');
		$this->message = array('id' => 'ID错误');
	}
	public function sub_complaint()
	{
		$this->allowMethods = 'post';
		$this->rules = array('porder_id' => 'require|number', 'name' => 'require', 'mobile' => 'require|number', 'issue' => 'require');
		$this->message = array('porder_id' => '参数错误', 'name' => '姓名必须填写', 'mobile' => '联系方式必须填写', 'issue' => '出现的问题必须填写');
	}
}