<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use Util\Wechat;
class Templetmsg
{
	private static function sendTemplateMessage($open_id, $template_id, $config, $datam, $url)
	{
	    $open_id=$open_id;
		$weixin = new Wechat($config);
		$data = array('touser' => $open_id, 'template_id' => $template_id, 'url' => $url, 'topcolor' => "#FF0000", 'data' => $datam);
		$res = $weixin->sendTemplateMessage($data);
		M('weixin_templetmsg_log')->insert(array('msg' => $res['errmsg'], 'weixin_appid' => $config['appid'], 'data' => json_encode($data), 'create_time' => time(), 'ret' => json_encode($res)));
		return rjson($res['errno'], $res['errmsg'], $res['data']);
	}
	public static function send($user_id, $tmp_clo, $data, $url = "")
	{
		$customer = M("customer")->where(array('id' => $user_id, 'is_del' => 0))->find();
		if (!$customer || !$customer['weixin_appid'] || !$customer['wx_openid']) {
			return rjson(1, '该用户无法发送模板消息');
		}
		$templet = M('weixin_templetmsg')->where(array('weixin_appid' => $customer['weixin_appid']))->find();
		if (!$templet || !isset($templet[$tmp_clo])) {
			return rjson(1, '未设置模板消息ID');
		}
		$config = M('weixin')->where(array('appid' => $customer['weixin_appid'], 'type' => 1, 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '未找到微信公众号配置信息');
		}
		return self::sendTemplateMessage($customer['wx_openid'], $templet[$tmp_clo], $config, $data, $url);
	}
	//余额变动 结算项目{{thing12.DATA}}结算时间{{time2.DATA}}结算金额{{amount3.DATA}}账户余额{{amount16.DATA}}
	public static function balanceCg($user_id, $first, $cgtime, $reason, $money, $balance, $remark = "", $url = "")
	{
	    if (strpos($reason, "支付") !== false){
        	$reason="支付";
        }elseif (strpos($reason, "退款") !== false) {
        	$reason="退款";
        }elseif (strpos($reason, "充值返利") !== false) {
        	$reason="充值返利";
        }elseif (strpos($reason, "代理获得收益") !== false) {
        	$reason="升级代理收益";
        }
		$data = array('thing12' => array('value' => $reason, 'color' => '#000'),'time2' => array('value' => $cgtime, 'color' => '#000'),  'amount3' => array('value' => '￥' . round($money, 2), 'color' => '#000'), 'amount16' => array('value' => '￥' . $balance, 'color' => '#000'));
		return self::send($user_id, 'balancecg_template_id', $data, $url);
	}
	public static function integralCg($user_id, $first, $cgtime, $reason, $money, $balance, $remark = "", $url = "")
	{
		$data = array('first' => array('value' => $first, 'color' => '#000'), 'keyword1' => array('value' => $cgtime, 'color' => '#000'), 'keyword2' => array('value' => $reason, 'color' => '#000'), 'keyword3' => array('value' => round($money, 0) . '积分', 'color' => '#000'), 'keyword4' => array('value' => $balance . '积分', 'color' => '#000'), 'remark' => array('value' => $remark, 'color' => '#000'));
		return self::send($user_id, 'balancecg_template_id', $data, $url);
	}
	//提现审核 提现金额 {{amount1.DATA}}提现时间{{time2.DATA}}
	public static function tixianSh($user_id, $first, $username, $shtime, $money, $remark = "", $url = "")
	{
		$data = array('amount1' => array('value' => $money, 'color' => '#000'), 'time2' => array('value' => $shtime, 'color' => '#000'));
		return self::send($user_id, 'tixiansh_template_id', $data, $url);
	}
	//用户注册成功通知 推广人{{thing16.DATA}}用户名{{thing19.DATA}}用户ID{{character_string6.DATA}}注册时间{{time3.DATA}}
	public static function newUser($user_id, $first, $nick, $userid, $regtime, $remark = "如有任何疑问请联系在线客服", $url = "")
	{
		$data = array('thing16' => array('value' => $first, 'color' => '#000'), 'thing19' => array('value' => $nick, 'color' => '#000'), 'character_string6' => array('value' => $userid, 'color' => '#000'), 'time3' => array('value' => $regtime, 'color' => '#000'));
		return self::send($user_id, 'newuser_template_id', $data, $url);
	}
	//支付成功号码{{phone_number2.DATA}}订单编号{{character_string4.DATA}}支付金额{{amount7.DATA}}
	public static function paySus($user_id, $first, $order_number, $money, $remark = "如有任何疑问请联系在线客服", $url = "")
	{
		$firstarr=explode(",",$first);
        $first=substr($firstarr[0],13);
		$data = array('phone_number2' => array('value' => $first, 'color' => '#000'), 'character_string4' => array('value' => $order_number, 'color' => '#000'), 'amount7' => array('value' => $money, 'color' => '#000'));
		return self::send($user_id, 'paysus_template_id', $data, $url);
	}
	//充值成功充值户号{{number8.DATA}}充值平台{{thing25.DATA}}充值名称{{thing24.DATA}}充值时间{{time13.DATA}}
	public static function chargeSus($user_id, $first, $type, $money, $sustime, $remark = "如有任何疑问请联系在线客服", $url = "")
	{
		$firstarr=explode(",",$first);
        $first=substr($firstarr[0],13);
        
		$data = array('number8' => array('value' => $first, 'color' => '#000'), 'thing25' => array('value' => $type, 'color' => '#000'), 'thing24' => array('value' => $money, 'color' => '#000'), 'time13' => array('value' => $sustime, 'color' => '#000'));
		return self::send($user_id, 'chargesus_template_id', $data, $url);
	}
	//充值失败手机号码{{phone_number13.DATA}}充值渠道{{thing2.DATA}}失败时间{{time15.DATA}}
	public static function chargeFail($user_id, $first, $money, $failtime, $remark = "如有任何疑问请联系在线客服", $url = "")
	{
		$firstarr=explode(",",$first);
        $first=substr($firstarr[0],13);
		$data = array('phone_number13' => array('value' => $first, 'color' => '#000'), 'thing2' => array('value' => $money, 'color' => '#000'), 'time15' => array('value' => $failtime, 'color' => '#000'));
		return self::send($user_id, 'chargefail_template_id', $data, $url);
	}
	//退款成功 用户编号{{character_string7.DATA}}订单号{{character_string6.DATA}}退款金额{{amount2.DATA}}退款时间{{time10.DATA}}
	public static function refund($user_id, $first, $money, $deltime, $restyle = '原路退回', $remark = "如有任何疑问请联系在线客服", $url = "")
	{
		$firstarr=explode(",",$first);
        $phone=substr($firstarr[0],13);
        $order_code=substr(strstr($firstarr[1],"退",true),7);
		$data = array('character_string7' => array('value' => $phone, 'color' => '#000'),'character_string6' => array('value' => $order_code, 'color' => '#000'), 'amount2' => array('value' => "￥" . $money, 'color' => '#000'), 'time10' => array('value' => $deltime, 'color' => '#000'));
		return self::send($user_id, 'refund_template_id', $data, $url);
	}
	public static function upSus($user_id, $first, $name, $oldgrade, $newgrade, $remark = "如有任何疑问请联系在线客服", $url = "")
	{
		$data = array('first' => array('value' => $first, 'color' => '#000'), 'keyword1' => array('value' => $name, 'color' => '#000'), 'keyword2' => array('value' => $oldgrade, 'color' => '#000'), 'keyword3' => array('value' => $newgrade, 'color' => '#000'), 'keyword4' => array('value' => time_format(time()), 'color' => '#000'), 'remark' => array('value' => $remark, 'color' => '#ff0000'));
		return self::send($user_id, 'upsus_template_id', $data, $url);
	}
	public static function yewuNoc($user_id, $first, $danwei, $date, $qinkuang, $remark = "如有任何疑问请联系在线客服", $url = "")
	{
		$data = array('first' => array('value' => $first, 'color' => '#000'), 'keyword1' => array('value' => $danwei, 'color' => '#000'), 'keyword2' => array('value' => $date, 'color' => '#000'), 'keyword3' => array('value' => $qinkuang, 'color' => '#000'), 'remark' => array('value' => $remark, 'color' => '#ff0000'));
		return self::send($user_id, 'yewu_template_id', $data, $url);
	}
}