<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use app\common\model\Porder as PorderModel;
use Util\Http;
class JdAgent
{
	public static function sign($data)
	{
		ksort($data);
		$parameters = "";
		foreach ($data as $k => $v) {
			$v != '' && ($parameters = $parameters . ($k . $v));
		}
		return md5($parameters . C('JDCONFIG.key'));
	}
	public static function notify($porder)
	{
		$state = PorderModel::getState($porder['status']);
		if (!in_array($state, array(1, 2))) {
			return rjson(1, '状态异常');
		}
		$param = array('vendorId' => C('JDCONFIG.mchid'), 'jdOrderNo' => $porder['out_trade_num'], 'agentOrderNo' => $porder['order_number'], 'produceStatus' => $state, 'quantity' => 1);
		$param['timestamp'] = date("YmdHis", time());
		$param['sign'] = JdAgent::sign($param);
		$json = Http::post($porder['notify_url'], $param);
		Createlog::porderLog($porder['id'], '京东回调地址：' . $porder['notify_url'] . '，回调参数：' . var_export($param, true));
		M('porder')->where(array('id' => $porder['id']))->setField(array('notification_time' => time()));
		$result = json_decode($json, true);
		if ($result && $result['code'] == 0) {
			Createlog::porderLog($porder['id'], '京东回调通知成功');
			M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
			return rjson(0, 'api回调通知成功');
		} else {
			Createlog::porderLog($porder['id'], '京东回调通知失败,响应数据：' . var_export($result, true));
			return rjson(1, 'api回调通知失败,响应数据：' . var_export($result, true));
		}
	}
}