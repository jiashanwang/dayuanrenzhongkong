<?php

//decode by http://chiran.taobao.com/
namespace app\api\logic;

use think\Logic;
class Applet extends Logic
{
	public function login()
	{
		$this->allowMethods = 'post';
		$this->rules = array('code' => 'require', 'vi' => 'number');
		$this->message = array('code' => 'code错误', 'vi' => 'vi错误');
	}
	public function applet_reg()
	{
		$this->allowMethods = 'post';
		$this->rules = array('code' => 'require', 'iv' => 'require', 'vi' => 'number');
		$this->message = array('code' => 'code错误', 'iv' => 'iv错误', 'vi' => 'vi错误');
	}
}