<?php

//decode by http://chiran.taobao.com/
namespace app\admin\logic;

use think\Logic;
class Login extends Logic
{
	public function logindo()
	{
		$this->allowMethods = 'post';
		$this->rules = array('nickname' => 'require', 'password' => 'require');
		$this->message = array('nickname' => '用户名必须', 'password' => '密码必须');
	}
}