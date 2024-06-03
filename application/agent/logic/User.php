<?php

//decode by http://chiran.taobao.com/
namespace app\agent\logic;

use think\Logic;
class User extends Logic
{
	public function uppwd()
	{
		$this->allowMethods = 'post';
		$this->rules = array('ypwd' => 'require', 'npwd2' => array('require', '/^.*(?=.{6,16})(?=.*\\d)(?=.*[A-Z]{1,})(?=.*[a-z]{1,}).*$/'), 'npwd' => array('require', '/^.*(?=.{6,16})(?=.*\\d)(?=.*[A-Z]{1,})(?=.*[a-z]{1,}).*$/'));
		$this->message = array('ypwd' => '原密码必须', 'npwd2' => '新密码6-16位，包括至少1个大写字母，1个小写字母，1个数字', 'npwd' => '新密码6-16位，包括至少1个大写字母，1个小写字母，1个数字');
	}
}