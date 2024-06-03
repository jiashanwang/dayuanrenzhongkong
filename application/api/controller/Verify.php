<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

class Verify extends Base
{
	public function img()
	{
		$id = I('?id') ? md5(I('id')) : 0;
		$captcha = new \Util\Captcha(82, 34, 4);
		$captcha->showImg();
		S('piccode' . $id, strtolower($captcha->getCaptcha()));
		return;
	}
	public function check($code)
	{
		$id = I('?id') ? md5(I('id')) : 0;
		if (S('piccode' . $id) == $code) {
			return djson(0, '正确');
		} else {
			return djson(1, '错误');
		}
	}
}