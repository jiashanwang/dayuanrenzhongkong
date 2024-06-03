<?php

//decode by http://chiran.taobao.com/
namespace app\api\logic;

use think\Logic;
class More extends Logic
{
	public function queryorder()
	{
		$this->allowMethods = 'post';
		$this->rules = array('key' => 'require');
		$this->message = array('key' => '请输入查询号码或者单号');
	}
}