<?php

//decode by http://chiran.taobao.com/
namespace app\api\logic;

use think\Logic;
class Weixin extends Logic
{
	public function getOauthAccessToken()
	{
		$this->allowMethods = 'post';
		$this->rules = array('vi' => 'number');
		$this->message = array('vi' => 'vi错误');
	}
	public function create_js_config()
	{
		$this->allowMethods = 'post';
		$this->rules = array('url' => 'url');
		$this->message = array('url' => 'url错误');
	}
}