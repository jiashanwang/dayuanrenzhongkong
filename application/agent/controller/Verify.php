<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

class Verify extends Base
{
	public function verify()
	{
		$captcha = new \Util\Captcha(82, 34, 4);
		echo $captcha->showImg();
		session('piccode', $captcha->getCaptcha());
		exit;
	}
	public function check($code)
	{
		if (session('piccode') == $code) {
			return true;
		} else {
			return false;
		}
	}
}