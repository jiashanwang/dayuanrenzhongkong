<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\library\Userlogin;
use app\common\model\Client;
class Weixinh5 extends Base
{
	public function _apibase()
	{
		$this->weixin = new \Util\Wechat($this->wxconfig);
	}
	public function getOauthRedirect()
	{
		$view_url = HTTP_TYPE . $_SERVER['HTTP_HOST'] . "/#/pages/login/oauth";
		$url = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $this->wxconfig['appid'] . "&redirect_uri=" . urlencode($view_url) . "&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
		return djson(0, '', $url);
	}
	public function getOauthAccessToken()
	{
		$res = $this->weixin->getOauthAccessToken();
		if ($res['errno'] != 0) {
			return djson($res['errno'], $res['errmsg'], $res['data']);
		}
		$oauthdata = $res['data'];
		$res = $this->weixin->getOauthUserinfo($oauthdata['access_token'], $oauthdata['openid']);
		if ($res['errno'] != 0) {
			return djson($res['errno'], $res['errmsg'], $res['data']);
		}
		$userinfo = $res['data'];
		if ($customer = M('customer')->where(array("wx_openid" => $userinfo['openid'], 'is_del' => 0))->field("id")->find()) {
			Userlogin::wx_up_userinfo($customer['id'], $userinfo);
			$data = Userlogin::create_login_data($customer['id']);
			return djson($data['errno'], $data['errmsg'], $data['data']);
		} else {
			$regret = Userlogin::wxh5_user_reg($userinfo, I('vi'), $this->wxconfig['appid'], Client::CLIENT_WX);
			if ($regret['errno'] != 0) {
				return djson($regret['errno'], $regret['errmsg']);
			}
			$data = Userlogin::create_login_data($regret['data']['id']);
			return djson($data['errno'], $data['errmsg'], $data['data']);
		}
	}
}