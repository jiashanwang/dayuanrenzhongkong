<?php

//decode by http://chiran.taobao.com/
function is_login()
{
	$user = session('user_auth');
	if (empty($user)) {
		return 0;
	}
	$last_login_ip = M('member')->where(array('id' => $user['id']))->value('last_login_ip');
	if ($last_login_ip != get_client_ip()) {
		return 0;
	}
	return session('user_auth_sign') == data_auth_sign($user) ? $user['id'] : 0;
}
function is_administrator($uid = null)
{
	$uid = is_null($uid) ? is_login() : $uid;
	return $uid && intval($uid) === config('USER_ADMINISTRATOR');
}
function data_auth_sign($data)
{
	if (!is_array($data)) {
		$data = (array) $data;
	}
	ksort($data);
	$code = http_build_query($data);
	$sign = sha1($code);
	return $sign;
}
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
	$tree = array();
	if (is_array($list)) {
		$refer = array();
		foreach ($list as $key => $data) {
			$refer[$data[$pk]] =& $list[$key];
		}
		foreach ($list as $key => $data) {
			$parentId = $data[$pid];
			if ($root == $parentId) {
				$tree[] =& $list[$key];
			} else {
				if (isset($refer[$parentId])) {
					$parent =& $refer[$parentId];
					$parent[$child][] =& $list[$key];
				}
			}
		}
	}
	return $tree;
}
function parse_config_attr($string)
{
	$array = preg_split('/[,;\\r\\n]+/', trim($string, ",;\r\n"));
	if (strpos($string, ':')) {
		$value = array();
		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[$k] = $v;
		}
	} else {
		$value = $array;
	}
	return $value;
}
function return_material_img($text)
{
	$url = str_replace(".html", "", U('Weixin/get_path_by_url'));
	return str_replace("data-src=\"", "src=\"" . $url . "?url=", $text);
}
function return_material_video($media_id)
{
	$map['media_id'] = $media_id;
	$ret = M('weixin_pic')->where($map)->find();
	return $ret['path'];
}
function get_shoquan_key()
{
	if (!IS_CLI) {
		return 'webshouquan' . $_SERVER['HTTP_HOST'];
	}
	return "";
}