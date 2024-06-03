<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use Util\Http;
class Otherapi
{
	public static function canUserQuery($customer_id)
	{
		$customer = M('customer')->where(array('id' => $customer_id))->field('id,user_query_num')->find();
		$pcount = M('porder')->where(array('customer_id' => $customer_id, 'status' => array('gt', 1)))->count();
		$allc = intval(C('PORDER_QUERY_NUM')) * intval($pcount) + intval(C('DEFAULT_QUERY_NUM'));
		if ($customer['user_query_num'] >= $allc) {
			return rjson(1, '查询次数超限！');
		}
		M('customer')->where(array('id' => $customer_id))->setInc('user_query_num', 1);
		return rjson(0, '可以查询');
	}
	public static function eleBalanceQuery($customer_id, $account, $param = [])
	{
		$res = self::canUserQuery($customer_id);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg']);
		}
		$config = C('ELE_QUERY_CONFIG');
		$classname = 'Query\\' . ucfirst($config['callapi']);
		if (!class_exists($classname)) {
			return rjson(1, '系统错误，查询接口类:' . $classname . '不存在');
		}
		$model = new $classname($config);
		if (!method_exists($model, 'query')) {
			return rjson(1, '系统错误，查询接口类:' . $classname . '的查询方法（query）不存在');
		}
		return $model->query($account, $param);
	}
	public static function phoneBalanceQuery($customer_id, $account, $param = [])
	{
		$res = self::canUserQuery($customer_id);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg']);
		}
		$config = C('PHONE_QUERY_CONFIG');
		$classname = 'Query\\' . ucfirst($config['callapi']);
		if (!class_exists($classname)) {
			return rjson(1, '系统错误，查询接口类:' . $classname . '不存在');
		}
		$model = new $classname($config);
		if (!method_exists($model, 'query')) {
			return rjson(1, '系统错误，查询接口类:' . $classname . '的查询方法（query）不存在');
		}
		return $model->query($account, $param);
	}
	public static function qqNickQuery($customer_id, $qq)
	{
		$res = self::canUserQuery($customer_id);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg']);
		}
		$api = 'http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg';
		$url = $api . '?' . http_build_query(array('uins' => $qq));
		$ret = Http::get($url);
		$ret = mb_convert_encoding((string) $ret, 'UTF-8', 'GB2312');
		$ret = str_replace("\r\n", '', $ret);
		preg_match('/^.*\\((.*)\\)\\;{0,1}$/', $ret, $match);
		$infoStr = $match[1];
		$infoArr = json_decode($infoStr, true);
		if (!$infoArr || isset($infoArr['error'])) {
			$errInfo = $infoArr['error'];
			$errmsg = isset($errInfo['msg']) ? $errInfo['msg'] : json_encode($errInfo);
			return rjson(1, $errmsg);
		}
		foreach ($infoArr as $userId => $info) {
			$data = array('qq' => $userId, 'head_img' => isset($info[0]) ? $info[0] : '', 'nickname' => isset($info[6]) ? $info[6] : '');
			return rjson(0, 'ok', $data);
		}
		return rjson(1, '未查询到qq昵称信息');
	}
}