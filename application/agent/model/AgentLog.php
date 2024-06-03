<?php

//decode by http://chiran.taobao.com/
namespace app\agent\model;

use think\Model;
class AgentLog extends Model
{
	public static function addLog($customer_id, $name, $url)
	{
		$mm = M('agent_menu')->where(array('url' => $url))->find();
		$title = self::getUrlSource($mm['id']);
		M('agent_log')->insertGetId(array('customer_id' => $customer_id, 'name' => $name, 'title' => $title, 'url' => self::handleGetCurrentUrlAdmin(), 'create_time' => time(), 'ip' => get_client_ip(), 'param' => json_encode(request()->param()), 'method' => $_SERVER['REQUEST_METHOD']));
	}
	private static function getUrlSource($id)
	{
		$mm = M('agent_menu')->where(array('id' => $id))->find();
		$title = '';
		if ($mm['pid']) {
			$title .= self::getUrlSource($mm['pid']) . '>';
		}
		$title .= $mm['title'];
		return $title;
	}
	private static function handleGetCurrentUrlAdmin()
	{
		$http_type = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? 'https://' : 'http://';
		$domain = $_SERVER['HTTP_HOST'];
		$requestUri = $_SERVER['REQUEST_URI'];
		$currentUrl = $http_type . $domain . $requestUri;
		return $currentUrl;
	}
}