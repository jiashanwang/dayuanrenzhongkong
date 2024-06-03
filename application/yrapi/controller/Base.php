<?php

//decode by http://chiran.taobao.com/
namespace app\yrapi\controller;

class Base extends \app\common\controller\Base
{
	public function _base()
	{
		$host_name = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "*";
		header("Access-Control-Allow-Origin: " . $host_name);
		header("Access-Control-Allow-Credentials:true");
		header("Access-Control-Max-Age:120");
		header("Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS");
		header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type,Accept,Authorization,Content-Language");
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			exit;
		}
		$this->checkWeihu();
		if (C('API_SWITCH') == 0) {
			djson(1, '供应商接口已经关闭', '')->send();
			exit;
		}
		if (method_exists($this, '_yrapibase')) {
			$this->_yrapibase();
		}
	}
	public function _empty()
	{
		return djson(1, '不存在的api！');
	}
}