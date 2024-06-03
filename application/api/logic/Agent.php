<?php

//decode by http://chiran.taobao.com/
namespace app\api\logic;

use think\Logic;
class Agent extends Logic
{
	public function apply()
	{
		$this->allowMethods = 'post';
		$this->rules = array('name' => 'require|chs|max:25|min:2', 'weixin' => 'require|alphaDash', 'content' => 'require');
		$this->message = array('name' => '姓名格式填写错误', 'weixin' => '微信格式填写错误', 'content' => '介绍填写错误');
	}
}