<?php

//decode by http://chiran.taobao.com/
namespace app\agent\logic;

use think\Logic;
class Porder extends Logic
{
	public function get_product()
	{
		$this->allowMethods = 'post';
	}
	public function create_order()
	{
		$this->allowMethods = 'post';
		$this->rules = array('mobile' => 'require', 'product_id' => 'require|number');
		$this->message = array('mobile' => '充值账号必填', 'product_id' => '产品选择不正确');
	}
	public function complaint_sub()
	{
		$this->rules = array('id' => 'require|number', 'name' => 'require', 'mobile' => 'require|number', 'issue' => 'require');
		$this->message = array('id' => '参数错误', 'name' => '联系人必填', 'mobile' => '联系人电话必填', 'issue' => '遇到的问题必填');
	}
}