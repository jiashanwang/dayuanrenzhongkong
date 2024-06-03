<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

class Yrapilib
{
	public static function sign($signstr)
	{
		return strtoupper(md5($signstr));
	}
}