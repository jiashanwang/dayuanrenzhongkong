<?php

//decode by http://chiran.taobao.com/
namespace app\yrapi\controller;

use app\common\library\Yrapilib;
class Home extends Base
{
	public function _yrapibase()
	{
		$param = I('post.');
		if (!isset($param['userid']) || !isset($param['sign'])) {
			djson(1, '参数错误或请求方式错误，请使用http-post表单')->send();
			exit;
		}
		$this->customer = M('customer')->where(array('id' => $param['userid'], 'type' => 2))->find();
		if (!$this->customer) {
			djson(1, '未开通的商户ID,请联系客服开通')->send();
			exit;
		}
		if ($this->customer['is_openapi'] != 1) {
			djson(1, '当前商户未开通api传单')->send();
			exit;
		}
		unset($param['sign']);
		ksort($param);
		$sign_str = urldecode(http_build_query($param) . '&apikey=' . $this->customer['apikey']);
		$sign = Yrapilib::sign($sign_str);
		if ($sign != I('sign')) {
			djson(1, "签名错误")->send();
			exit;
		}
		if ($this->customer['status'] != 1) {
			djson(1, '账户不可用,请联系客服')->send();
			exit;
		}
		if ($this->customer['ip_white_list'] && strpos($this->customer['ip_white_list'], get_client_ip()) === false) {
			djson(1, '请将ip加入接口白名单')->send();
			exit;
		}
		if (method_exists($this, '_inithomechild')) {
			$this->_inithomechild();
		}
	}
}