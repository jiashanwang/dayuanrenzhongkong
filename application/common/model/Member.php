<?php

//decode by http://chiran.taobao.com/
namespace app\common\model;

use think\Model;
use Util\GoogleAuth;
class Member extends Model
{
	public function register($nickname, $pwd)
	{
		return $this->save(array('nickname' => $nickname, 'password' => dyr_encrypt(trim($pwd)), 'reg_ip' => get_client_ip(1), 'reg_time' => time()));
	}
	public function up_pwd($id, $ypwd, $npwd)
	{
		if ($user = $this->get(array('id' => $id))) {
			if ($user['password'] == dyr_encrypt(trim($ypwd))) {
				return $this->update(array('id' => $id, 'password' => dyr_encrypt(trim($npwd)), 'last_login_ip' => ''));
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function reset_pwd($id, $npwd)
	{
		if ($user = $this->get(array('id' => $id))) {
			return $this->update(array('id' => $id, 'password' => dyr_encrypt(trim($npwd)), 'last_login_ip' => ''));
		} else {
			return false;
		}
	}
	public static function is_login_lock($id)
	{
		$login_lock_time = self::where(array('id' => $id))->value('login_lock_time');
		if ($login_lock_time > time()) {
			return rjson(1, '账号已锁定，请于' . time_format($login_lock_time) . '后再尝试登录');
		}
		if ($login_lock_time > 0) {
			self::where(array('id' => $id))->setField(array('login_lock_time' => 0, 'login_error_count' => 0));
		}
		return rjson(0, '账号未锁定');
	}
	public static function lock_login_error($id)
	{
		$count = intval(C('LOGIN_ERR_LOCK_COUNT'));
		$lock_time = intval(C('LOGIN_ERR_LOCK_TIME'));
		if (!$count || !$lock_time) {
			return rjson(0, '未锁定');
		}
		self::where(array('id' => $id))->setInc('login_error_count', 1);
		$error_count = self::where(array('id' => $id))->value('login_error_count');
		if ($error_count < $count) {
			return rjson(1, '密码错误,还有' . ($count - $error_count) . '次密码输入错误将被锁定' . $lock_time . '分钟');
		}
		$locked_time = $lock_time * 60 + time();
		self::where(array('id' => $id))->setField(array('login_lock_time' => $locked_time));
		return rjson(1, '密码输入次数过多，账户已限制登录，请于' . time_format($locked_time) . '后再尝试登录');
	}
	public static function pwdLogin($nickname, $password, $verifycode)
	{
		$member = self::where(array('nickname' => $nickname, 'is_del' => 0))->find();
		if (!$member) {
			return rjson(1, '不存在的账号');
		}
		$goret = GoogleAuth::verifyCode($member['google_auth_secret'], $verifycode, 1);
		if (!$goret) {
			return rjson(1, "谷歌身份验证码错误！");
		}
		$res = self::is_login_lock($member['id']);
		if ($res['errno'] != 0) {
			return rjson($res['errno'], $res['errmsg'], $res['data']);
		}
		if ($member['status'] != 1) {
			return rjson(1, "该账户已被禁用！");
		}
		if (!$member['password']) {
			return rjson(1, '账号未设置登录密码，请联系客服处理');
		}
		if ($member['password'] != dyr_encrypt(trim($password))) {
			$resl = self::lock_login_error($member['id']);
			if ($resl['errno'] != 0) {
				return rjson($resl['errno'], $resl['errmsg'], $resl['data']);
			}
			return rjson(1, "密码输入错误");
		}
		self::where(array('id' => $member['id']))->setField(array('last_login_time' => time(), 'last_login_ip' => get_client_ip(), 'login_error_count' => 0));
		return rjson(0, "登录验证通过", $member = self::where(array('id' => $member['id']))->find());
	}
}