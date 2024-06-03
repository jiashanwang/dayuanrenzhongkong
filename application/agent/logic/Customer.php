<?php

//decode by http://chiran.taobao.com/
namespace app\agent\logic;

use think\Logic;
class Customer extends Logic
{
	public function up_password()
	{
		$this->allowMethods = 'post';
		$this->rules = array('password' => array('require', '/^.*(?=.{6,16})(?=.*\\d)(?=.*[A-Z]{1,})(?=.*[a-z]{1,}).*$/'));
		$this->message = array('password' => '密码6-16位，包括至少1个大写字母，1个小写字母，1个数字');
	}
}