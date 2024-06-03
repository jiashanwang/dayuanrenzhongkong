<?php

//decode by http://chiran.taobao.com/
namespace Util;

use think\Db;
use think\db\Query;
class Syslog
{
	public static function write($action = '行为', $content = "内容描述", $username = "无")
	{
		$request = request();
		$data = array('ip' => $request->ip(), 'url' => $request->url(), 'action' => $action, 'content' => is_array($content) ? json_encode($content) : $content, 'username' => $username, 'create_time' => time());
		return M('system_log')->insert($data) !== false;
	}
}