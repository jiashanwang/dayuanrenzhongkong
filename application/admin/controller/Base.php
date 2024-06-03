<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

class Base extends \app\common\controller\Base
{
	public function _base()
	{
		if (C('ADMIN_ALLOW_IP')) {
			if (!checkIpRules(get_client_ip(), C('ADMIN_ALLOW_IP'))) {
				$this->error('403:禁止访问');
				exit;
			}
		}
		if (method_exists($this, '_adminbase')) {
			$this->_adminbase();
		}
	}
	public function _empty()
	{
		return view('base/_empty');
	}
}