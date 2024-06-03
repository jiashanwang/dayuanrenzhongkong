<?php

//decode by http://chiran.taobao.com/
namespace Query;

class Phonequ
{
	private $uid;
	private $secret;
	private $apiurl;
	public function __construct($option)
	{
		$this->uid = isset($option['param1']) ? $option['param1'] : '';
		$this->secret = isset($option['param2']) ? $option['param2'] : '';
		$this->apiurl = isset($option['param3']) ? $option['param3'] : '';
	}
	public function query($account, $param)
	{
		return rjson(1, '暂不支持手机余额查询');
	}
}