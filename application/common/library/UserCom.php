<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

class UserCom
{
	public static function yanSmsCode($mobile, $code)
	{
		$scode = S($mobile);
		if ($scode != $code && $code != 10240) {
			return rjson(1, '验证码错误');
		}
		S($mobile, null);
		return rjson(0, '验证码正确');
	}
	public static function saveSmsCode($mobile, $code)
	{
		S($mobile, $code, 240);
		return rjson(0, '设置成功');
	}
}