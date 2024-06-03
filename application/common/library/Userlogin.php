<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use app\common\model\Balance;
use app\common\model\Client;
use app\common\model\Customer as CustomerModel;
class Userlogin
{
	public static function create_login_data($customer_id)
	{
		$customer = M('customer')->where(array('id' => $customer_id))->field("id,status,username,mobile,headimg,type,f_id,create_time,balance,integral,is_mp_auth")->find();
		if ($customer['status'] != 1) {
			return array('errno' => 1, 'errmsg' => '账户被禁用', 'data' => '');
		}
		$last_access_token = S('USERLOGINONE' . $customer['id']);
		if ($last_access_token) {
			S($last_access_token, null);
		}
		$access_token = dyr_encrypt(json_encode($customer['id']) . time());
		$adata['customer'] = $customer;
		$adata['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		S($access_token, $adata);
		$retdata['access_token'] = $access_token;
		$retdata['customer'] = $customer;
		S('USERLOGINONE' . $customer['id'], $access_token);
		return array('errno' => 0, 'errmsg' => '登录成功', 'data' => $retdata);
	}
	public static function get_userinfo_by_token($access_token)
	{
		$adata = S($access_token);
		if (!$adata) {
			return array('errno' => -1, 'errmsg' => '请重新登录，您的登录已经过期了', 'data' => '');
		}
		$customer = M('customer')->where(array('id' => $adata['customer']['id'], 'is_del' => 0))->find();
		if (!$customer) {
			return array('errno' => -1, 'errmsg' => '请重新登录', 'data' => '');
		}
		if ($customer['status'] != 1) {
			return array('errno' => 1, 'errmsg' => '账户被禁用', 'data' => '');
		}
		return array('errno' => 0, 'errmsg' => 'ok', 'data' => $customer);
	}
	public static function wxh5_user_reg($userinfo, $f_id, $weixin_appid, $client)
	{
		$customer = M('customer')->where(array("wx_openid" => $userinfo['openid'], 'is_del' => 0))->find();
		if ($customer) {
			return rjson(1, '已经注册', $customer);
		}
		$weixin = M('weixin')->where(array('appid' => $weixin_appid, 'type' => 1, 'is_del' => 0))->find();
		if (!$weixin) {
			return rjson(1, '微信配置未找到');
		}
		if ($weixin['customer_id']) {
			$arr['f_id'] = $weixin['customer_id'];
		} else {
			if ($f_id && ($fcus = M('customer c')->join('customer_grade g', 'g.id=c.grade_id')->where(array('c.id' => $f_id, 'c.is_del' => 0, 'g.is_agent' => 1))->field('c.id')->find())) {
				$arr['f_id'] = $fcus['id'];
			}
		}
		$arr['username'] = $userinfo['nickname'];
		$arr['headimg'] = $userinfo['headimgurl'];
		$arr['create_time'] = time();
		$arr['sex'] = $userinfo['sex'];
		$arr['weixin_appid'] = $weixin_appid;
		$arr['wx_openid'] = $userinfo['openid'];
		$arr['apikey'] = strtoupper(md5(time()));
		$arr['client'] = $client;
		$aid = M('customer')->insertGetId($arr);
		if ($aid) {
			Createlog::customerLog($aid, '注册成功', '系统');
			$customer = M('customer')->where(array('id' => $aid))->find();
			if ($customer['f_id'] && $userinfo['headimgurl']) {
				CustomerModel::inviteSus($customer['f_id'], $customer['id']);
			}
			CustomerModel::checkShareImg($customer['id']);
			return rjson(0, '注册成功', $customer);
		} else {
			return rjson(1, '注册失败');
		}
	}
	public static function wx_up_userinfo($customer_id, $userinfo)
	{
		$customer = M('customer')->where(array("id" => $customer_id))->find();
		if (!$customer) {
			return rjson(1, '用户信息不存在');
		}
		if (!$customer['username'] || !$customer['headimg']) {
			M('customer')->where(array("id" => $customer_id))->setField(array('username' => $userinfo['nickname'], 'headimg' => $userinfo['headimgurl']));
			if ($customer['f_id'] && $userinfo['headimgurl']) {
				CustomerModel::inviteSus($customer['f_id'], $customer['id']);
			}
		}
		return rjson(0, '更新完成');
	}
	public static function wxmp_user_reg($ap_openid, $f_id, $weixin_appid, $session_key)
	{
		$customer = M('customer')->where(array("ap_openid" => $ap_openid, 'weixin_appid' => $weixin_appid, 'is_del' => 0))->find();
		if ($customer) {
			return rjson(1, '已经注册', $customer);
		}
		if ($f_id) {
			if ($fcus = M('customer')->where(array('id' => $f_id, 'is_del' => 0))->field('id')->find()) {
				$arr['f_id'] = $fcus['id'];
			}
		}
		$arr['username'] = '';
		$arr['headimg'] = '';
		$arr['create_time'] = time();
		$arr['weixin_appid'] = $weixin_appid;
		$arr['ap_openid'] = $ap_openid;
		$arr['session_key'] = $session_key;
		$arr['apikey'] = strtoupper(md5(time()));
		$arr['client'] = Client::CLIENT_MP;
		$aid = M('customer')->insertGetId($arr);
		if ($aid) {
			Createlog::customerLog($aid, '注册成功', '小程序');
			$customer = M('customer')->where(array('id' => $aid))->find();
			if ($customer['f_id']) {
				CustomerModel::inviteSus($customer['f_id'], $customer['id']);
			}
			return rjson(0, '注册成功', $customer);
		} else {
			return rjson(1, '注册失败');
		}
	}
	public static function aga_user_reg($username, $headimg, $mobile, $f_id)
	{
		if (M('Customer')->where(array('username' => $username))->find()) {
			return rjson(1, '已有相同的用户名,请换一个名称');
		}
		$kkmoney = floatval(C('YF_KONGKAI_MONEY'));
		$fuser = M('customer')->where(array("id" => $f_id, 'is_del' => 0))->find();
		if ($fuser) {
			$arr['f_id'] = $f_id;
			if ($kkmoney > 0) {
				$brets = Balance::expend($f_id, $kkmoney, "开户，代理商划拨余额给分销商，扣除", Balance::STYLE_RECHARGE, '代理商：' . $fuser['username']);
				if ($brets['errno'] != 0) {
					return rjson(1, $brets['errmsg']);
				}
			}
		}
		$arr['username'] = $username;
		$arr['headimg'] = $headimg;
		$arr['type'] = 2;
		$arr['mobile'] = $mobile;
		$arr['password'] = dyr_encrypt($mobile);
		$arr['grade_id'] = 3;
		$arr['create_time'] = time();
		$arr['apikey'] = strtoupper(md5(time()));
		$arr['client'] = Client::CLIENT_AGA;
		$aid = M('customer')->insertGetId($arr);
		if ($aid) {
			Createlog::customerLog($aid, '注册成功', '代理商');
			$customer = M('customer')->where(array('id' => $aid))->find();
			if ($kkmoney > 0 && $fuser) {
				Balance::revenue($aid, $kkmoney, "开户，首次划拨", Balance::STYLE_RECHARGE, '代理商：' . $fuser['username']);
			}
			return rjson(0, '注册成功', $customer);
		} else {
			return rjson(1, '注册失败');
		}
	}
	public static function h5_user_reg($username, $password, $f_id, $weixin_appid)
	{
		if (M('Customer')->where(array('username' => $username))->find()) {
			return rjson(1, '已有相同用户名,请换一个名称');
		}
		$weixin = M('weixin')->where(array('appid' => $weixin_appid, 'type' => 2, 'is_del' => 0))->find();
		if (!$weixin) {
			return rjson(1, 'H5配置未找到');
		}
		if ($weixin['customer_id']) {
			$arr['f_id'] = $weixin['customer_id'];
		} else {
			if ($f_id && ($fcus = M('customer')->where(array('id' => $f_id, 'is_del' => 0))->field('id')->find())) {
				$arr['f_id'] = $fcus['id'];
			}
		}
		$arr['username'] = $username;
		$arr['headimg'] = C('DEFAULT_HEADIMG');
		$arr['type'] = 1;
		$arr['mobile'] = "";
		$arr['password'] = dyr_encrypt($password);
		$arr['grade_id'] = 1;
		$arr['create_time'] = time();
		$arr['apikey'] = strtoupper(md5(time()));
		$arr['client'] = Client::CLIENT_H5;
		$aid = M('customer')->insertGetId($arr);
		if ($aid) {
			Createlog::customerLog($aid, '注册成功', 'H5端');
			$customer = M('customer')->where(array('id' => $aid))->find();
			return rjson(0, '注册成功', $customer);
		} else {
			return rjson(1, '注册失败');
		}
	}
}