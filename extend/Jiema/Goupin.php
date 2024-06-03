<?php

//decode by http://chiran.taobao.com/
namespace Jiema;

use app\common\model\Porder as PorderModel;
use think\Log;
class Goupin
{
	private $apiurl;
	private $appid;
	private $secret;
	public function __construct($option)
	{
		$this->apiurl = isset($option['apiurl']) ? $option['apiurl'] : '';
		$this->appid = isset($option['param1']) ? $option['param1'] : '';
		$this->secret = isset($option['param2']) ? $option['param2'] : '';
	}
	public function getVeOne($phone, $orderno, $paramo)
	{
		$param = array("appid" => $this->appid, "product_id" => $paramo['param1'], "type" => 1, "mobile" => $phone);
		ksort($param);
		$param['sign'] = strtoupper(md5(http_build_query($param) . "&secret=" . $this->secret));
		$res = $this->http_get($this->apiurl . "/api/a/recharge/code", $param);
		if ($res['errno'] == 0) {
			return rjson(0, $res['errmsg'], array('extend_param1' => $res['data']['data']['transaction_no'], 'extend_param2' => $res['data']['data']['order_no']));
		}
		return $res;
	}
	public function saveVeCode($phone, $orderno, $paramo)
	{
		$param = array("appid" => $this->appid, "product_id" => $paramo['param1'], "code" => $paramo['code'], "transaction_no" => $paramo['extend_param1']);
		ksort($param);
		$param['sign'] = strtoupper(md5(http_build_query($param) . "&secret=" . $this->secret));
		return $this->http_get($this->apiurl . "/api/a/recharge/saveCode", $param);
	}
	public function getVeTow($phone, $orderno, $paramo)
	{
		$param = array("appid" => $this->appid, "product_id" => $paramo['param1'], "type" => 2, "mobile" => $phone, "transaction_no" => $paramo['extend_param1']);
		ksort($param);
		$param['sign'] = strtoupper(md5(http_build_query($param) . "&secret=" . $this->secret));
		return $this->http_get($this->apiurl . "/api/a/recharge/code", $param);
	}
	public function recharge($phone, $orderno, $paramo)
	{
		$param = array("appid" => $this->appid, "product_id" => $paramo['param1'], "code" => $paramo['extend_param3'], "mobile" => $phone, "transaction_no" => $paramo['extend_param1'], "source_order_no" => $paramo['extend_param2']);
		ksort($param);
		$param['sign'] = strtoupper(md5(http_build_query($param) . "&secret=" . $this->secret));
		return $this->http_get($this->apiurl . "/api/a/recharge", $param);
	}
	public function check($phone, $orderno, $paramo)
	{
		$param = array("appid" => $this->appid, "order_no" => $paramo['extend_param2']);
		ksort($param);
		$param['sign'] = strtoupper(md5(http_build_query($param) . "&secret=" . $this->secret));
		$res = $this->http_get($this->apiurl . "/api/a/order/info", $param);
		if ($res['errno'] != 0 && !isset($res['data']['data']['status_key'])) {
			return rjson(49, "检查失败");
		}
		$nodata = $res['data']['data'];
		if ($nodata['status_key'] == 'FINISH') {
			PorderModel::rechargeSus($paramo['order_number'], "接码订单回调|充值成功|" . $nodata['msg']);
			return rjson(50, "订单成功了", $nodata);
		} else {
			if ($res['data']['data']['status_key'] == 'FAILED') {
				PorderModel::rechargeFail($paramo['order_number'], "接码订单回调|充值失败|" . $nodata['msg'], '充值失败');
				return rjson(51, "订单失败了-" . $nodata['msg'], $nodata);
			} else {
				return rjson(52, "订单充值中", $nodata);
			}
		}
	}
	private function http_get($url, $param)
	{
		Log::error("goupin_" . $url . "_" . var_export($param, true));
		if (is_string($param)) {
			$strPOST = $param;
		} else {
			$strPOST = http_build_query($param);
		}
		$oCurl = curl_init();
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
		curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
		curl_setopt($oCurl, CURLOPT_HEADER, false);
		$sContent = curl_exec($oCurl);
		Log::error("goupin_" . $sContent);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (isset($aStatus["http_code"]) && intval($aStatus["http_code"]) == 200) {
			$result = json_decode($sContent, true);
			if (intval($result['code']) == 0) {
				return rjson(0, $result['message'], $result);
			} else {
				return rjson(1, $result['message'], $result);
			}
		} else {
			return rjson(500, '接码接口访问失败，http错误码' . $aStatus["http_code"], '接码接口访问失败，http错误码' . $aStatus["http_code"]);
		}
	}
}