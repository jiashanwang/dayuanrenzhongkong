<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

use app\common\model\Customer as CustomerModel;
class Login extends Base
{
	public function login()
	{
		return view();
	}
	public function logindo()
	{
		$res = CustomerModel::pwdLogin(I('username'), I('password'), I('verifycode'));
		if ($res['errno'] != 0) {
			return djson(1, $res['errmsg'], $res['data']);
		}
		$customer = $res['data'];
		if ($customer['type'] != 2) {
			return djson(1, '开通的账号不是代理账号');
		}
		$auth = array('id' => $customer['id'], 'username' => $customer['username'], 'headimg' => $customer['headimg'], 'mobile' => $customer['mobile']);
		session('user_auth_agent', $auth);
		return djson(0, "登录成功", array('member' => $customer, 'url' => U('Admin/index')));
	}
	public function logout()
	{
		session('user_auth_agent', null);
		$this->redirect('Login/login');
	}
}