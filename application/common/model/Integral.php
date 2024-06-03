<?php

//decode by http://chiran.taobao.com/
namespace app\common\model;

use app\common\library\SubscribeMessage;
use app\common\library\Templetmsg;
use think\Model;
class Integral extends Model
{
	public static function revenue($customer_id, $money, $remark)
	{
		$uid = M('customer')->where(array('id' => $customer_id))->setInc("integral", $money);
		if (!$uid) {
			return rjson(1, '收入失败');
		}
		$user = M('customer')->where(array('id' => $customer_id))->find();
		$uid && M('integral_log')->insertGetId(array('integral' => $money, 'type' => 1, 'remark' => $remark, 'create_time' => time(), 'customer_id' => $customer_id, 'balance' => $user['integral']));
		$user['client'] == Client::CLIENT_WX && Templetmsg::integralCg($customer_id, '您有新的积分收入了', time_format(time()), '收入', $money, $user['integral'], $remark);
		return rjson(0, '操作成功');
	}
	public static function expend($customer_id, $money, $remark)
	{
		$uid = M('customer')->where(array('id' => $customer_id, 'integral' => array('egt', $money)))->setDec("integral", $money);
		if (!$uid) {
			return rjson(1, '账户积分不足！');
		}
		$user = M('customer')->where(array('id' => $customer_id))->find();
		$uid && M('integral_log')->insertGetId(array('integral' => $money, 'type' => 2, 'remark' => $remark, 'create_time' => time(), 'customer_id' => $customer_id, 'balance' => $user['integral']));
		$user['client'] == Client::CLIENT_WX && Templetmsg::integralCg($customer_id, '您有新的积分支出了', time_format(time()), '支出', $money, $user['integral'], $remark);
		return rjson(0, '操作成功');
	}
}