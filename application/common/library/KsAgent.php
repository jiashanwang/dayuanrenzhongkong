<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use app\common\model\Porder as PorderModel;
use think\Response;
use Util\Http;
class KsAgent
{
	const apiurl = "https://open.kwaixiaodian.com/";
	public function __construct()
	{
		$this->access_token = DataCacheks::get('access_token');
		if (!$this->access_token) {
			$this->getAccessToken();
		}
	}
	public static function sign($indata)
	{
		if (isset($indata['param']) && !is_string($indata['param'])) {
			$indata['param'] = json_encode($indata['param']);
		}
		if (isset($indata['sign'])) {
			unset($indata['sign']);
		}
		ksort($indata);
		$signstr = urldecode(http_build_query($indata)) . "&signSecret=" . C('KSCONFIG.signSecret');
		return md5($signstr);
	}
	private function ret($code, $errmsg, $param = [])
	{
		$data['data'] = $param;
		$data['result'] = $code;
		$data['error_msg'] = $errmsg;
		return Response::create($data, 'json');
	}
	public static function getCodeUrl()
	{
		return self::apiurl . "oauth/authorize?appId=" . C('KSCONFIG.appkey') . "&redirect_uri=" . C('WEB_URL') . "yrapi.php/kuaishou/code_notify.html" . "&scope=merchant_order,merchant_refund,merchant_user,user_info&response_type=code&state=1";
	}
	public static function getrefreshToken($code)
	{
		$param = array('app_id' => C('KSCONFIG.appkey'), 'app_secret' => C('KSCONFIG.appSecret'), 'grant_type' => 'code', 'code' => $code);
		$url = self::apiurl . "oauth2/access_token?" . http_build_query($param);
		$json = Http::get($url);
		$res = json_decode($json, true);
		if ($res['result'] == 1) {
			DataCacheks::set($res['refresh_token'], 'refresh_token', $res['refresh_token_expires_in']);
			DataCacheks::set($res['access_token'], 'access_token', 86400);
			return rjson(0, '刷新成功');
		}
		return rjson(1, '刷新失败', $res);
	}
	public function getAccessToken()
	{
		$refresh_token = DataCacheks::get('refresh_token');
		$param = array('app_id' => C('KSCONFIG.appkey'), 'app_secret' => C('KSCONFIG.appSecret'), 'grant_type' => 'refresh_token', 'refresh_token' => $refresh_token);
		$url = self::apiurl . "oauth2/refresh_token?" . http_build_query($param);
		$json = Http::get($url);
		$res = json_decode($json, true);
		if ($res['result'] == 1) {
			DataCacheks::set($res['refresh_token'], 'refresh_token', $res['refresh_token_expires_in']);
			DataCacheks::set($res['access_token'], 'access_token', 86400);
		} else {
			Email::sendMail(C('WEB_URL') . '_快手获取token失败了', var_export($res, true));
		}
	}
	public function notify($porder)
	{
		$state = PorderModel::getState($porder['status']);
		if (!in_array($state, array(1, 2))) {
			return rjson(1, '状态异常');
		}
		if ($state == 1) {
			$status = "SUCCESS";
		} elseif ($state == 2) {
			$status = "FAILED";
		} else {
			$status = "ACCEPTED";
		}
		$paramo = array('orderId' => $porder['out_trade_num'], 'businessTime' => date('Y-m-d\\TH:i:s+0800', $porder['create_time']), 'mobile' => $porder['mobile'], 'orderNo' => $porder['order_number'], 'status' => $status, 'amount' => $porder['kuaishou_amount'], 'bizType' => $porder['kuaishou_biztype']);
		$param['appkey'] = C('KSCONFIG.appkey');
		$param['method'] = 'integration.virtual.topup.mobile.order.callback';
		$param['version'] = '1';
		$param['param'] = json_encode($paramo);
		$param['access_token'] = DataCacheks::get('access_token');
		$param['timestamp'] = time() * 1000;
		$param['signMethod'] = 'MD5';
		$param['sign'] = KsAgent::sign($param);
		$notify_url = self::apiurl . "integration/virtual/topup/mobile/order/callback";
		$res = $this->http_post($notify_url, $param);
		Createlog::porderLog($porder['id'], '快手回调地址：' . $notify_url . '，回调参数：' . var_export($param, true));
		M('porder')->where(array('id' => $porder['id']))->setField(array('notification_time' => time()));
		if ($res['errno'] == 0) {
			Createlog::porderLog($porder['id'], '快手回调返回：' . var_export($res['data'], true));
			Createlog::porderLog($porder['id'], '快手回调通知成功');
			M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
			return rjson(0, 'api回调通知成功');
		} else {
			Createlog::porderLog($porder['id'], '京东回调通知失败,响应数据：' . var_export($res['data'], true));
			return rjson(1, 'api回调通知失败,响应数据：' . var_export($res['data'], true));
		}
	}
	private function http_post($url, $param)
	{
		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
		}
		if (is_string($param)) {
			$strPOST = $param;
		} else {
			$strPOST = http_build_query($param);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($oCurl, CURLOPT_TIMEOUT, 30);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
		curl_setopt($oCurl, CURLOPT_HEADER, 0);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			$result = json_decode($sContent, true);
			if ($result['result'] == 1) {
				return rjson(0, $result['error_msg'], $result);
			} else {
				return rjson(1, $result['error_msg'], $result);
			}
		} else {
			return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"]);
		}
	}
}
class DataCacheks
{
	public static $file = ".kuaishou";
	public static function get($name = 'token')
	{
		$filename = $name . "_" . C('KSCONFIG.appkey') . self::$file;
		if (!is_file($filename)) {
			return false;
		}
		$json = file_get_contents($filename);
		$data = json_decode($json, true);
		if ($data['create_time'] + $data['expire'] < time()) {
			return false;
		}
		return $data['content'];
	}
	public static function set($content, $name = 'token', $expire = 7200)
	{
		$filename = $name . "_" . C('KSCONFIG.appkey') . self::$file;
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode(array('create_time' => time(), 'expire' => $expire, 'content' => $content)));
		fclose($fp);
	}
}