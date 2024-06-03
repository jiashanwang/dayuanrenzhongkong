<?php

//decode by http://chiran.taobao.com/
namespace app\yrapi\controller;

use app\common\model\Porder as PorderModel;
class Jiema extends \app\common\controller\Base
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
		if (method_exists($this, '_yrapibase')) {
			$this->_yrapibase();
		}
	}
	public function ispay()
	{return;
		$order_number = I('key');
		$porder = M('porder')->where(array('order_number' => $order_number, 'status' => array('in', '2,3')))->field('id')->find();
		if (!$porder) {
			echo -1;
		} else {
			echo 1;
		}
	}
	public function notify()
	{return;
		$state = intval(I('state'));
		if ($state == 1) {
			PorderModel::rechargeSus(I('key'), '充值成功|接码回调|' . I('jg'), I('jg'));
			echo "success";
		} elseif ($state == -1) {
			PorderModel::rechargeFail(I('key'), '充值失败|接码回调|' . I('jg'), I('jg'));
			echo "success";
		}
	}
	public function notify_twoo()
	{return;
		$state = I('code');
		if ($state == 'success') {
			PorderModel::rechargeSus(I('goodId'), '充值成功|接码回调|' . var_export($_POST, true), '');
			echo "success";
		} else {
			PorderModel::rechargeFail(I('goodId'), '充值失败|接码回调|' . var_export($_POST, true), '');
			echo "success";
		}
	}
}