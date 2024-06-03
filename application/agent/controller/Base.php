<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

class Base extends \app\common\controller\Base
{
	public function _base()
	{
		$this->checkWeihu();
		if (method_exists($this, '_agentbase')) {
			$this->_agentbase();
		}
	}
	public function _empty()
	{
		return view('base/_empty');
	}
}