<?php

//decode by http://chiran.taobao.com/
namespace app\api\logic;

use think\Logic;
class Customer extends Logic
{
	public function tixian()
	{
		$this->allowMethods = 'post';
		$this->rules = array('money' => 'require|number|gt:0');
		$this->message = array('money' => '提现金额不正确');
	}
	public function bind_mobile()
	{
		$this->allowMethods = 'post';
		$this->rules = array('mobile' => 'require|number|length:11', 'code' => 'require|number|length:6');
		$this->message = array('mobile' => '手机格式错误', 'code' => '验证码格式错误');
	}
}