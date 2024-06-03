<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

class SubscribeMessage
{
	private static function send($openid, $template_id, $config, $data, $page = '')
	{
		$applet = new \Util\Applet($config);
		foreach ($data as $key => $vo) {
			$data[$key] = array('value' => $vo);
		}
		$request = array('touser' => $openid, 'template_id' => $template_id, 'page' => $page, 'data' => $data);
		$ret = $applet->sendSubscribeMessage($request);
		M('weixin_subscribe_log')->insertGetId(array('weixin_appid' => $config['appid'], 'openid' => $openid, 'template_id' => $template_id, 'request' => json_encode($request), 'result' => json_encode($ret), 'create_time' => time()));
		if ($ret['errno'] != 0) {
			return rjson($ret['errno'], $ret['errmsg']);
		}
		return rjson(0, "发送成功", $ret['data']);
	}
	private static function sendToCus($customer_id, $tmp_clo, $data, $page = '')
	{
		$customer = M('customer')->where(array('id' => $customer_id))->field("id,ap_openid,weixin_appid")->find();
		if (!$customer) {
			return rjson(1, '不存在的用户');
		}
		if (!$customer['ap_openid']) {
			return rjson(1, '非微信小程序用户');
		}
		$templet = M('weixin_subscribe')->where(array('weixin_appid' => $customer['weixin_appid']))->find();
		if (!$templet || !isset($templet[$tmp_clo])) {
			return rjson(1, '未设置模板消息ID');
		}
		$config = M('weixin')->where(array('appid' => $customer['weixin_appid'], 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '未找到微信公众号配置信息');
		}
		return self::send($customer['ap_openid'], $templet[$tmp_clo], $config, $data, $page);
	}
	public static function refundSus($customer_id, $order_number, $price, $style, $jieguo, $time)
	{
		return self::sendToCus($customer_id, "refund_template_id", array('character_string7' => $order_number, 'amount1' => $price, 'phrase5' => $style, 'phrase2' => $jieguo, 'date4' => $time), "pages/index/record");
	}
	public static function rechargeFail($customer_id, $order_number, $product, $mobile, $remark)
	{
		return self::sendToCus($customer_id, 'chargefail_template_id', array('character_string4' => $order_number, 'thing9' => $product, 'phone_number1' => $mobile, 'thing5' => $remark), "pages/index/record");
	}
	public static function rechargeSus($customer_id, $order_number, $product, $mobile, $remark)
	{
		return self::sendToCus($customer_id, 'chargesus_template_id', array('character_string6' => $order_number, 'thing8' => $product, 'phone_number5' => $mobile, 'thing7' => $remark), "pages/index/record");
	}
	public static function paySus($customer_id, $order_number, $product, $price, $remark)
	{
		return self::sendToCus($customer_id, 'ordersus_template_id', array('character_string3' => $order_number, 'thing6' => $product, 'amount4' => $price, 'thing5' => $remark), "pages/index/record");
	}
	public static function balanceXf($customer_id, $userid, $price, $balance)
	{
		return self::sendToCus($customer_id, 'balancexf_template_id', array('character_string2' => $userid, 'amount4' => '￥' . number_format($price, 2), 'amount5' => '￥' . number_format($balance, 2)), "pages/my/my");
	}
	public static function balanceKk($customer_id, $order_number, $price, $remark)
	{
		return self::sendToCus($customer_id, 'balancekk_template_id', array('amount1' => '￥' . number_format($price, 2), 'character_string2' => $order_number, 'thing3' => $remark), "pages/my/my");
	}
	public static function integralCg($customer_id, $integral, $time, $balance, $remark)
	{
		return self::sendToCus($customer_id, 'integralcg_template_id', array('number1' => intval($integral), 'time8' => $time, 'character_string7' => intval($balance), 'thing5' => $remark), "pages/index/record");
	}
	public static function tixianSh($customer_id, $time, $status, $remark)
	{
		return self::sendToCus($customer_id, 'tixiansh_template_id', array('thing4' => "提现审核", 'time5' => $time, 'phrase6' => $status, 'thing3' => $remark), "pages/index/record");
	}
	public static function newUser($customer_id, $remark, $time)
	{
		return self::sendToCus($customer_id, 'newuser_template_id', array('thing3' => $remark, 'time5' => $time), "pages/my/my");
	}
	public static function upSus($customer_id, $status, $remark)
	{
		return self::sendToCus($customer_id, 'upsus_template_id', array('thing4' => "用户升级", 'phrase6' => $status, 'thing3' => $remark), "pages/my/my");
	}
}