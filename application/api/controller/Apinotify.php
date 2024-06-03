<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

class Apinotify extends \app\common\controller\Base
{
	public function _base()
	{
		$request = \think\Request::instance();
		$action_name = ucfirst($request->action());
		$classname = 'Recharge\\' . $action_name;
		if (!class_exists($classname)) {
			echo "系统错误，接口" . $action_name . "不存在";
			exit;
		}
		$model = new $classname(array());
		if (!method_exists($model, 'notify')) {
			echo "系统错误，接口" . $action_name . "的回调方法（notify）不存在";
			exit;
		}
		$model->notify();
		exit;
	}
}