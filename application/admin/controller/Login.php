<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use Util\Syslog;
use app\common\model\Member as MemberModel;
class Login extends Base
{
	public function login()
	{
		return view();
	}
	public function logindo()
	{
		$res = MemberModel::pwdLogin(I('nickname'), I('password'), I('verifycode'));
		if ($res['errno'] != 0) {
			Syslog::write("后台登录失败", dyr_encrypt(var_export($_POST, true)), I('nickname'));
			return djson(1, $res['errmsg'], $res['data']);
		}
		$member = $res['data'];
		$auth = array('id' => $member['id'], 'nickname' => $member['nickname'], 'last_login_time' => $member['last_login_time'], 'headimg' => $member['headimg'], 'short_auth_login' => 0);
		session('user_auth', $auth);
		session('user_auth_sign', data_auth_sign($auth));
		Syslog::write("后台登录成功", dyr_encrypt(var_export($_POST, true)), $member['nickname']);
		return djson(0, "登录成功", array('member' => $member, 'url' => U('Admin/index')));
	}
	public function logout()
	{
		session('user_auth', null);
		session('user_auth_sign', null);
		session('Auth_List', null);
		$this->redirect('Login/login');
	}
	public function authlogin()
	{
		if (request()->isPost()) {
			$key = I('key');
			$uid = S('shortauth' . $key);
			if (!$uid) {
				return $this->redirect('Login/Login');
			}
			$member = M('member')->where(array('id' => $uid, 'is_del' => 0))->find();
			if (!$member) {
				return $this->redirect('Login/Login');
			}
			S('shortauth' . $key, false);
			$auth = array('id' => $member['id'], 'nickname' => $member['nickname'], 'last_login_time' => $member['last_login_time'], 'headimg' => $member['headimg'], 'short_auth_login' => 1);
			M('member')->where(array('id' => $member['id']))->setField(array('last_login_time' => time(), 'last_login_ip' => get_client_ip(), 'login_error_count' => 0));
			session('user_auth', $auth);
			session('user_auth_sign', data_auth_sign($auth));
			Syslog::write("后台登录成功(临时授权)", dyr_encrypt("key:" . $key), $member['nickname']);
			$this->redirect('Admin/index');
		} else {
			return view();
		}
	}
}