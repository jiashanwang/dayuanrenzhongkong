<?php

//decode by http://chiran.taobao.com/
namespace app\admin\logic;

use think\Logic;
class Customer extends Logic
{
	public function up_password()
	{
		$this->allowMethods = 'post';
		$this->rules = array('id' => 'require', 'password' => array('require', '/^.*(?=.{6,16})(?=.*\\d)(?=.*[A-Z]{1,})(?=.*[a-z]{1,}).*$/'));
		$this->message = array('id' => 'id参数错误', 'password' => '密码6-16位，包括至少1个大写字母，1个小写字母，1个数字');
	}
}