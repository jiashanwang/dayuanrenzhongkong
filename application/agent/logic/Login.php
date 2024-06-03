<?php

//decode by http://chiran.taobao.com/
namespace app\agent\logic;

use think\Logic;
class Login extends Logic
{
	public function logindo()
	{
		$this->allowMethods = 'post';
		$this->rules = array('username' => 'require', 'password' => 'require');
		$this->message = array('username' => '用户名必须', 'password' => '密码必须');
	}
}