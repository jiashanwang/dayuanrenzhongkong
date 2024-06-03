<?php

//decode by http://chiran.taobao.com/
namespace app\api\logic;

use think\Logic;
class Open extends Logic
{
	public function pwdlogin()
	{
		$this->allowMethods = 'post';
		$this->rules = array('username' => 'require', 'password' => 'require', 'imgcode' => 'require');
		$this->message = array('username' => '用户名必填', 'password' => '密码错误必填', 'imgcode' => '图片验证码必填');
	}
	public function h5reg()
	{
		$this->allowMethods = 'post';
		$this->rules = array('username' => 'require', 'password' => 'require', 'imgcode' => 'require');
		$this->message = array('username' => '用户名必填', 'password' => '密码错误必填', 'imgcode' => '图片验证码必填');
	}
	public function get_ad()
	{
		$this->allowMethods = 'post';
		$this->rules = array('key' => 'require');
		$this->message = array('key' => 'key必填');
	}
	public function get_ads()
	{
		$this->allowMethods = 'post';
		$this->rules = array('key' => 'require');
		$this->message = array('key' => 'key必填');
	}
	public function get_doc()
	{
		$this->allowMethods = 'post';
		$this->rules = array('id' => 'require|number');
		$this->message = array('id' => 'id错误');
	}
	public function get_config()
	{
		$this->allowMethods = 'post';
		$this->rules = array('key' => 'require');
		$this->message = array('key' => 'key必填');
	}
	public function send_code()
	{
		$this->allowMethods = 'post';
		$this->rules = array('mobile' => 'require|number|length:11');
		$this->message = array('mobile' => '手机格式错误');
	}
}