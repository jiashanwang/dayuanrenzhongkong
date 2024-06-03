<?php

//decode by http://chiran.taobao.com/
namespace app\admin\model;

use think\Model;
class MemberLog extends Model
{
	public static function addLog($member_id, $name, $url)
	{
		$mm = M('menu')->where(array('url' => $url))->find();
		$title = self::getUrlSource($mm['id']);
		M('member_log')->insertGetId(array('member_id' => $member_id, 'name' => $name, 'title' => $title, 'url' => self::handleGetCurrentUrlAdmin(), 'create_time' => time(), 'ip' => get_client_ip(), 'param' => json_encode(request()->param()), 'method' => $_SERVER['REQUEST_METHOD']));
	}
	private static function getUrlSource($id)
	{
		$mm = M('menu')->where(array('id' => $id))->find();
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