<?php

//decode by http://chiran.taobao.com/
namespace app\yrapi\controller;

use app\common\library\Notification;
use app\common\model\Client;
class Auto extends \app\common\controller\Base
{
	function _base()
	{
		set_time_limit(0);
		ini_set('memory_limit', '3072M');
		ini_set('max_execution_time', '0');
	}
	public function crontab_60()
	{
		return djson(1, '弃用');
	}
	private function notification()
	{
		$lists = M('porder')->where(array('status' => array('in', '4,5,6'), 'client' => Client::CLIENT_API, 'is_notification' => 0, 'notification_num' => array('elt', 2)))->field("id,status")->select();
		foreach ($lists as $k => $v) {
			if ($v['status'] == 4) {
				Notification::rechargeSus($v['id']);
			} else {
				Notification::rechargeFail($v['id']);
			}
		}
	}
}