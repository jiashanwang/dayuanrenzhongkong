<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\library\CreateLog;
use app\common\library\PayApi;
use app\common\library\SmsNotice;
use app\common\library\UserCom;
use app\common\library\Userlogin;
use app\common\model\Archives;
use app\common\model\Client;
use app\common\model\Customer as CustomerModel;
use Map\Bmap;
class Open extends Base
{
	public function pwdlogin()
	{return;
		$username = I('username');
		$password = I('password');
		$verifycode = I('verifycode');
		$res = CustomerModel::pwdLogin($username, $password, $verifycode);
		if ($res['errno'] != 0) {
			return djson(1, $res['errmsg'], $res['data']);
		}
		$customer = $res['data'];
		$data = Userlogin::create_login_data($customer['id']);
		return djson($data['errno'], $data['errmsg'], $data['data']);
	}
	public function h5reg()
	{return;
		$username = I('username');
		$password = I('password');
		if (S('piccode' . md5($username)) != strtolower(I('imgcode'))) {
			return djson(1, "图片验证码错误");
		}
		$customer = M('customer')->where(array('username' => $username, 'is_del' => 0))->find();
		if ($customer) {
			return djson(1, "账号已注册，请登录");
		}
		$res = Userlogin::h5_user_reg($username, $password, I('vi'), $this->wxconfig['appid']);
		if ($res['errno'] != 0) {
			return djson(1, $res['errmsg']);
		}
		$inid = $res['data']['id'];
		$data = Userlogin::create_login_data($inid);
		return djson($data['errno'], $data['errmsg'], $data['data']);
	}
	public function h5login()
	{return;
		if ($customer = M('customer')->where(array('device_id' => I('device_id'), 'weixin_appid' => $this->wxconfig['appid'], 'is_del' => 0))->find()) {
			if ($customer['status'] != 1) {
				return djson(1, "该账户已被禁用！");
			}
			$data = Userlogin::create_login_data($customer['id']);
			return djson($data['errno'], $data['errmsg'], $data['data']);
		} else {
			$regret = CustomerModel::reg("H5用户", C('DEFAULT_HEADIMG'), '', I('vi'), 0, '', 1, 1, Client::CLIENT_H5, $this->wxconfig['appid'], '', I('device_id'));
			if ($regret['errno'] != 0) {
				return djson($regret['errno'], $regret['errmsg']);
			}
			$data = Userlogin::create_login_data($regret['data']['id']);
			return djson($data['errno'], $data['errmsg'], $data['data']);
		}
	}
	public function get_ad()
	{
		$key = I('key');
		$map['a.key'] = $key;
		$map['a.weixin_appid'] = $this->wxconfig['appid'];
		if ($data = M('ad a')->join("adc c", "a.id=c.ad_id")->where($map)->order("c.sort asc,c.id asc")->find()) {
			return djson(0, 'ok', $data);
		} else {
			return djson(1, '没有');
		}
	}
	public function get_ads()
	{
		$key = I('key');
		$map['a.key'] = $key;
		$map['a.weixin_appid'] = $this->wxconfig['appid'];
		if ($data = M('ad a')->join("adc c", "a.id=c.ad_id")->where($map)->order("c.sort asc,c.id asc")->select()) {
			return djson(0, 'ok', $data);
		} else {
			return djson(1, '没有');
		}
	}
	public function get_doc()
	{
		$id = I('id');
		$doc = Archives::getDoc($id);
		if (!$doc) {
			return djson(1, '未找到文档');
		}
		return djson(0, 'ok', $doc);
	}
	public function get_copy_right()
	{
		return djson(0, 'ok', $this->wxconfig['copy_right']);
	}
	public function get_about_us()
	{
		return djson(0, 'ok', $this->wxconfig['about_us']);
	}
	public function get_kefu_doc()
	{
		return djson(0, 'ok', $this->wxconfig['kefu_doc']);
	}
	public function get_h5_title()
	{
		return djson(0, 'ok', $this->wxconfig['name']);
	}
	public function has_alipay()
	{
		if ($this->wxconfig['alipay_appid'] && $this->wxconfig['alipay_privatekey'] && $this->wxconfig['alipay_publickey']) {
			return djson(0, '有支付宝支付');
		} else {
			return djson(1, '无支付宝支付');
		}
	}
	public function has_wxpay()
	{
		if ($this->wxconfig['mch_id'] && $this->wxconfig['key']) {
			return djson(0, '有微信支付');
		} else {
			return djson(1, '无微信支付');
		}
	}
	public function has_phone_bla()
	{
		if ($this->wxconfig['is_phone_bla']) {
			return djson(0, '有话费查询');
		} else {
			return djson(1, '无话费查询');
		}
	}
	public function has_ele_bla()
	{
		if ($this->wxconfig['is_ele_bla']) {
			return djson(0, '有电费查询');
		} else {
			return djson(1, '无电费查询');
		}
	}
	public function helptxt()
	{
		$map['weixin_appid'] = $this->wxconfig['appid'];
		$list = M('help_txt')->where($map)->order('sort asc,id asc')->select();
		return djson(0, 'ok', $list);
	}
	public function taglinetxt()
	{
		$map = array();
		$list = M('tagline_txt')->where($map)->order('sort asc')->select();
		return djson(0, 'ok', $list);
	}
	public function devlogin()
	{return;
		$data = Userlogin::create_login_data(1);
		return djson($data['errno'], $data['errmsg'], $data['data']);
	}
	public function get_config()
	{
		return djson(0, 'ok', C(I('key')));
	}
	public function get_city()
	{
		$initials = M('electricity_city')->where(array('is_del' => 0, 'pid' => 0))->group('initial asc')->field('initial')->select();
		$arr = array();
		foreach ($initials as $ini) {
			$list = M('electricity_city')->where(array('initial' => $ini['initial'], 'is_del' => 0, 'pid' => 0))->order('sort asc')->select();
			foreach ($list as &$v) {
				$v['city'] = M('electricity_city')->where(array('pid' => $v['id'], 'is_del' => 0))->order('sort asc,city_name asc')->select();
			}
			$arr[] = array('letter' => $ini['initial'], 'list' => $list);
		}
		return djson(0, 'ok', $arr);
	}
	public function notice()
	{
		$map['a.typeid'] = 2;
		$list = M('archives a')->where($map)->field("a.id,a.title")->order("a.pubdate desc")->select();
		return djson(0, 'ok', $list);
	}
	public function get_client_city()
	{
		$bmap = new Bmap();
		$res = $bmap->location_ip(get_client_ip());
		if ($res['status'] == 0 && isset($res['address'])) {
			$arr = explode('|', $res['address']);
			$pro = str_replace('市', '', str_replace('省', '', $arr[1]));
			return djson(0, 'ok', array('pro' => $pro, 'city' => $arr[2]));
		} else {
			return djson(0, '未获取到定位', array('pro' => "北京", 'city' => "北京"));
		}
	}
	public function get_ele_curcity()
	{
		$bmap = new Bmap();
		$res = $bmap->location_ip(get_client_ip());
		if ($res['status'] == 0 && isset($res['address'])) {
			$arr = explode('|', $res['address']);
			$pro = str_replace('市', '', str_replace('省', '', $arr[1]));
			$city = M('electricity_city')->where(array('is_del' => 0, 'pid' => 0, 'city_name' => array('like', '%' . $pro . '%')))->find();
			if ($city) {
				$city['city'] = M('electricity_city')->where(array('pid' => $city['id'], 'is_del' => 0))->order('sort asc,city_name asc')->select();
				return djson(0, 'ok', $city);
			}
		}
		return djson(0, '未找到所在省份', M('electricity_city')->where(array('is_del' => 0, 'pid' => 0))->find());
	}
	public function send_code()
	{
		$mobile = I('mobile');
		$code = \Util\Random::numeric(6);
		$rets = SmsNotice::sendCode($mobile, $code);
		if ($rets['errno'] == 0) {
			UserCom::saveSmsCode($mobile, $code);
			return djson(0, "发送成功！", $rets);
		} else {
			return djson(1, "短信发送失败!", $rets['errmsg']);
		}
	}
}