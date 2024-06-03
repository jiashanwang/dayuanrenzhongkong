<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use think\Log;
use Util\Sms\Qxt800;
class SmsNotice
{
	public static function agentBlalow()
	{
		if (intval(C('SMS_BLA_SW')) == 0) {
			return;
		}
		$lowbal = intval(C('SMS_BLA_LOWBAL'));
		$maxcount = 3;
		$jiankemiao = 86400;
		$xfh_list = M('customer')->where(array('status' => 1, 'is_del' => 0, 'type' => 2, 'balance' => array('gt', $lowbal), 'no_balance_count' => array('gt', 0), 'no_balance_last_time' => array('elt', time() - $jiankemiao)))->field('id,balance')->select();
		foreach ($xfh_list as $k => $v) {
			M('customer')->where(array('id' => $v['id']))->setField(array('no_balance_count' => 0, 'no_balance_last_time' => 0));
		}
		$no_list = M('customer')->where(array('status' => 1, 'is_del' => 0, 'type' => 2, 'balance' => array('elt', $lowbal), 'no_balance_count' => array('lt', $maxcount), 'no_balance_last_time' => array('elt', time() - $jiankemiao)))->field('id,mobile,balance,no_balance_count,no_balance_last_time')->select();
		foreach ($no_list as $k => $user) {
			if ($user['balance'] == 0) {
				continue;
			}
			$template = C('SMS_TMP_BLA');
			$content = self::createConent($template, array('balance' => $user['balance']));
			$res = Qxt800::send(C('SMS_CONFIG'), $user['mobile'], $content);
			M('customer')->where(array('id' => $user['id']))->setInc('no_balance_count', 1);
			M('customer')->where(array('id' => $user['id']))->setField(array('no_balance_last_time' => time()));
			if ($res['errno'] != 0) {
				Createlog::customerLog($user['id'], '短信通知手机：' . $user['mobile'] . '失败，结果：' . $res['errmsg'], '系统');
				continue;
			}
			Createlog::customerLog($user['id'], '短信通知手机：' . $user['mobile'] . '成功，内容：' . $content, '系统');
		}
	}
	private static function createConent($template, $data)
	{
		$content = $template;
		$param = array();
		preg_match_all('/{([\\s\\S]*?)}/', $template, $param);
		foreach ($param[1] as $key => $name) {
			if (isset($data[$name])) {
				$content = str_replace('{' . $name . '}', $data[$name], $content);
			}
		}
		return $content;
	}
	public static function sendCode($mobile, $code)
	{
		$template = C('SMS_TMP_CODE');
		$content = self::createConent($template, array('code' => $code));
		$res = Qxt800::send(C('SMS_CONFIG'), $mobile, $content);
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg']);
		}
		return rjson(0, '发送成功');
	}
	public static function porderSusNotice()
	{
		$maxcount = intval(C('SMS_PORDER_SUS_COUNT'));
		$jiankemiao = 86400;
		if ($maxcount == 0) {
			return;
		}
		$porders = M('porder')->where(array('status' => 4, 'is_del' => 0, 'type' => array('in', array(1, 2)), 'is_apart' => array('in', array(0, 2)), 'sms_notice_count' => array('elt', $maxcount), 'sms_notice_time' => array('elt', time() - $jiankemiao), 'finish_time' => array('egt', strtotime(date('Y-m-d', time())) - $maxcount * 86400)))->field('id,mobile,product_name,finish_time')->select();
		foreach ($porders as $k => $order) {
			$template = C('SUS_TMP_PORDER');
			$content = self::createConent($template, array('account' => $order['mobile'], 'product' => $order['product_name']));
			$res = Qxt800::send(C('SMS_CONFIG'), $order['mobile'], $content);
			M('porder')->where(array('id' => $order['id']))->setInc('sms_notice_count', 1);
			M('porder')->where(array('id' => $order['id']))->setField(array('sms_notice_time' => time()));
			if ($res['errno'] != 0) {
				Createlog::porderLog($order['id'], '订单成功，短信通知手机：' . $order['mobile'] . '失败，结果：' . $res['errmsg']);
				continue;
			}
			Createlog::porderLog($order['id'], '订单成功，短信通知手机：' . $order['mobile'] . '成功，内容：' . $content);
		}
	}
	public static function porderBatchSms($paramstr)
	{
		$param = json_decode($paramstr, true);
		$ids = $param['ids'];
		$template = $param['template'];
		$operator = $param['operator'];
		$porders = M('porder')->where(array('id' => array('in', $ids)))->group('mobile')->field('id,mobile,product_name')->select();
		if (!$porders) {
			return rjson(1, '订单不可申请撤单');
		}
		foreach ($porders as $k => $order) {
			Createlog::porderLog($order['id'], '操作发送短信通知|管理员：' . $operator);
			if (!is_numeric($order['mobile']) || mb_strlen($order['mobile']) != 11) {
				Createlog::porderLog($order['id'], '发送短信失败|手机号格式不正确，' . $order['mobile']);
				continue;
			}
			if (!$template) {
				Createlog::porderLog($order['id'], '发送短信失败|短信内容未设置');
				continue;
			}
			$content = self::createConent($template, array('account' => $order['mobile'], 'product' => $order['product_name']));
			$res = Qxt800::send(C('SMS_CONFIG'), $order['mobile'], $content);
			if ($res['errno'] != 0) {
				Createlog::porderLog($order['id'], '短信通知手机：' . $order['mobile'] . '失败，结果：' . $res['errmsg']);
				continue;
			}
			Createlog::porderLog($order['id'], '短信通知手机：' . $order['mobile'] . '成功，内容：' . $content);
		}
	}
}