<?php

//decode by http://chiran.taobao.com/
namespace app\common\model;

use app\common\library\Createlog;
use app\common\library\SubscribeMessage;
use app\common\library\Templetmsg;
use think\Model;
use Util\GoogleAuth;
class Customer extends Model
{
	protected $append = ["grade_name", "grade_is_zdy_price"];
	public static function init()
	{
	}
	public static function reg($username, $headimg, $mobile, $f_id, $sex, $wx_openid, $type, $grade_id, $client, $weixin_appid, $password = '', $device_id = '')
	{
		$weixin = M('weixin')->where(array('appid' => $weixin_appid, 'type' => $client, 'is_del' => 0))->find();
		if (!$weixin) {
			return rjson(1, '客户端配置信息未找到');
		}
		if ($weixin['customer_id']) {
			$arr['f_id'] = $weixin['customer_id'];
		} else {
			if ($f_id && ($fcus = M('customer')->where(array('id' => $f_id, 'is_del' => 0))->field('id')->find())) {
				$arr['f_id'] = $fcus['id'];
			}
		}
		if ($wx_openid && M('customer')->where(array('wx_openid' => $wx_openid, 'is_del' => 0, 'type' => $type))->find()) {
			return rjson(1, '已经存在账号，请刷新页面');
		}
		$arr['username'] = $username;
		$arr['headimg'] = $headimg;
		$arr['create_time'] = time();
		$arr['mobile'] = $mobile;
		$arr['sex'] = $sex;
		$arr['wx_openid'] = $wx_openid;
		$arr['type'] = $type;
		$arr['grade_id'] = $grade_id;
		$arr['client'] = $client;
		$arr['weixin_appid'] = $weixin_appid;
		$arr['device_id'] = $device_id;
		$password && ($arr['password'] = dyr_encrypt($password));
		$arr['apikey'] = strtoupper(md5(time()));
		$aid = M('customer')->insertGetId($arr);
		if ($aid) {
			$customer = M('customer')->where(array('id' => $aid))->find();
			Createlog::customerLog($customer['id'], '账号注册成功', '');
			return rjson(0, '注册成功', $customer);
		} else {
			return rjson(1, '注册失败');
		}
	}
	public function getGradeNameAttr($value, $data)
	{
		return M('customer_grade')->where(array('id' => $data['grade_id']))->value('grade_name');
	}
	public function getGradeIsZdyPriceAttr($value, $data)
	{
		return M('customer_grade')->where(array('id' => $data['grade_id']))->value('is_zdy_price');
	}
	public static function getInfo($uid)
	{
		$data = self::where(array('id' => $uid, 'is_del' => 0))->field("id,status,username,mobile,headimg,type,f_id,create_time,balance,integral,grade_id,(select is_agent from dyr_customer_grade where id=grade_id) as is_agent")->find();
		return $data;
	}
	public static function checkShareImg($customer_id)
	{
		$customer = M('customer')->where(array('id' => $customer_id))->find();
		if (!$customer) {
			return rjson(1, '用户不存在');
		}
		switch ($customer['client']) {
			case Client::CLIENT_H5:
				$config = M('weixin')->where(array('appid' => $customer['weixin_appid'], 'type' => 2, 'is_del' => 0))->find();
				if (!$config) {
					return rjson(1, '微信配置未找到');
				}
				return self::createQrH5($customer_id, $config);
			case Client::CLIENT_WX:
				$config = M('weixin')->where(array('appid' => $customer['weixin_appid'], 'type' => 1, 'is_del' => 0))->find();
				if (!$config) {
					return rjson(1, '微信配置未找到');
				}
				return self::createQrWx($customer_id, $config);
				break;
			default:
				Createlog::customerLog($customer_id, "检查生成海报二维码出错，不支持的客户端", '系统');
				return rjson(1, '不支持的客户端');
				break;
		}
	}
	public static function createQrWx($customer_id, $config)
	{
		M('customer')->where(array('id' => $customer_id, 'share_img_time' => array('lt', time())))->setField(array('qr_value' => '', 'qrurl' => '', 'share_img_time' => 0));
		$customer = M('customer')->where(array('id' => $customer_id))->find();
		$weixin = new \Util\Wechat($config);
		if (!$customer['qrurl']) {
			$res = $weixin->getQRCode($customer['id'], 'QR_LIMIT_STR_SCENE');
			if ($res['errno'] != 0) {
				return rjson($res['errno'], $res['errmsg'], $res['data']);
			}
			$qr = $res['data'];
			if (!$qr['url']) {
				return rjson(1, '失败，未获取到qrurl');
			}
			$qrurl = create_qrcode($qr['url']);
			$customer['qrurl'] = $qrurl;
			M('customer')->where(array('id' => $customer['id']))->setField(array('qr_value' => $qr['url'], 'qrurl' => $qrurl, 'share_img_time' => 4102329600));
		}
		if (!$customer['headimg_base64'] && $customer['headimg']) {
			$headimg_base64 = base64_encode(file_get_contents($customer['headimg']));
			M('customer')->where(array('id' => $customer['id']))->setField(array('headimg_base64' => $headimg_base64));
		}
		return rjson(0, '微信二维码生成成功');
	}
	public static function createQrMp($customer_id, $config)
	{
		$customer = M('customer')->where(array('id' => $customer_id))->find();
		if (!$customer) {
			return rjson(1, '用户不存在');
		}
		if ($customer['mp_qrurl']) {
			return rjson(0, '小程序码存在', $customer['mp_qrurl']);
		}
		$applet = new \Util\Applet($config);
		$data = $applet->getWxaCodeUnlimit('vi=' . $customer_id, '');
		if ($data['errno'] != 0) {
			return rjson($data['errno'], $data['errmsg'], $data['data']);
		}
		M('customer')->where(array('id' => $customer_id))->setField(array('mp_qrurl' => $data['data']));
		return rjson(0, '小程序码生成成功', $data['data']);
	}
	public static function createQrH5($customer_id, $config)
	{
		$customer = M('customer')->where(array('id' => $customer_id))->find();
		if (!$customer) {
			return rjson(1, '用户不存在');
		}
		if ($customer['h5_qrurl']) {
			return rjson(0, '分享码存在', $customer['h5_qrurl']);
		}
		$qrurl = create_qrcode(C('WEB_URL') . '#/pages/index/index?vi=' . $customer['id'] . "&appid=" . $config['id']);
		$customer['qrurl'] = $qrurl;
		M('customer')->where(array('id' => $customer_id))->setField(array('h5_qrurl' => $qrurl));
		return rjson(0, 'H5分销码生成成功', $qrurl);
	}
	public static function inviteSus($customer_id, $child_id)
	{
		$customer = M('customer')->where(array('id' => $customer_id))->find();
		$child = M('customer')->where(array('id' => $child_id))->find();
		if (!($customer && $child)) {
			return rjson(1, '参数错误');
		}
		$customer['client'] == Client::CLIENT_WX && Templetmsg::newUser($customer_id, '有人通过您邀请成功注册', $child['username'], $child['id'], time_format($child['create_time']), "如有任何疑问请联系在线客服");
		return rjson(0, '成功');
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
	public static function pwdLogin($username, $password, $verifycode)
	{
		$user = self::where(array('username' => $username, 'is_del' => 0))->find();
		if (!$user) {
			return rjson(1, '不存在的账号');
		}
		$goret = GoogleAuth::verifyCode($user['google_auth_secret'], $verifycode, 1);
		if (!$goret) {
			Createlog::customerLog($user['id'], '通过账号密码登录失败，谷歌验证码错误，ip' . get_client_ip(), '');
			return rjson(1, "谷歌身份验证码错误！");
		}
		$res = self::is_login_lock($user['id']);
		if ($res['errno'] != 0) {
			return rjson($res['errno'], $res['errmsg'], $res['data']);
		}
		if ($user['status'] != 1) {
			Createlog::customerLog($user['id'], '通过账号密码登录失败，该账户已被禁用，ip' . get_client_ip(), '');
			return rjson(1, "该账户已被禁用！");
		}
		if (!$user['password']) {
			Createlog::customerLog($user['id'], '通过账号密码登录失败，账号未设置登录密码，ip' . get_client_ip(), '');
			return rjson(1, '账号未设置登录密码，请联系客服处理');
		}
		if ($user['password'] != dyr_encrypt($password)) {
			$resl = self::lock_login_error($user['id']);
			if ($resl['errno'] != 0) {
				Createlog::customerLog($user['id'], '通过账号密码登录失败，' . $resl['errmsg'] . '，ip' . get_client_ip(), '');
				return rjson($resl['errno'], $resl['errmsg'], $resl['data']);
			}
			Createlog::customerLog($user['id'], '通过账号密码登录失败，密码输入错误，ip' . get_client_ip(), '');
			return rjson(1, "密码输入错误");
		}
		self::where(array('id' => $user['id']))->setField(array('last_login_ip' => get_client_ip(), 'login_error_count' => 0));
		Createlog::customerLog($user['id'], '通过账号密码登录成功，ip' . get_client_ip(), '');
		return rjson(0, "登录验证通过", $user);
	}
	public static function canQueryIspz($customer_id)
	{
		$defult_num = 10;
		$query_num = 3;
		$customer = M('customer')->where(array('id' => $customer_id))->find();
		$pcount = M('porder')->where(array('customer_id' => $customer_id, 'status' => array('gt', 1)))->count();
		$allc = $query_num * intval($pcount) + $defult_num;
		if ($customer['ipsz_query_num'] >= $allc) {
			return rjson(1, '查询转网次数超限');
		}
		M('customer')->where(array('id' => $customer_id))->setInc('ipsz_query_num', 1);
		return rjson(0, '转网可以查询');
	}
}