<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use think\Request;
class User extends Admin
{
	public function _init()
	{
		if (!IS_CLI && (!function_exists('get_shoquan_key') || !S(md5(get_shoquan_key())))) {
			echo C('sqyc_msg');
			exit;
		}
	}
	public function infos()
	{
		if (Request::instance()->isPost()) {
			if (M('member')->where(array('id' => I('id')))->update(array('sex' => I('sex'), 'headimg' => I('headimg')))) {
				return $this->success("保存成功！");
			} else {
				return $this->error("保存失败！");
			}
		} else {
			$info = D('member')->find(UID);
			$this->assign('info', $info);
			return view();
		}
	}
	public function uppwd()
	{
		if (D('member')->up_pwd(UID, I('ypwd'), I('npwd'))) {
			return $this->success("修改成功！");
		} else {
			return $this->error("修改失败，请重试！");
		}
	}
	public function shortauth()
	{
		$user = session('user_auth');
		if (!isset($user['short_auth_login']) || $user['short_auth_login'] == 1) {
			$this->assign('authurl', "临时授权用户不可用此功能！");
		} else {
			$key = md5(UID . time());
			S('shortauth' . $key, UID, array('expire' => 60 * 10));
			$this->assign('authurl', C('WEB_URL') . 'admin.php/login/authlogin?key=' . $key);
		}
		return view();
	}
}