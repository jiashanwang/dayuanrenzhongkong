<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use app\common\model\Client;
use app\common\model\Porder as PorderModel;
use Util\Http;
class Notification
{
	public static function rechargeIng($porder_id)
	{
		$porder = M('porder')->where(array('id' => $porder_id, 'status' => 3, 'is_del' => 0, 'is_apart' => array('in', '0,2')))->find();
		if (!$porder) {
			return rjson(1, '未找到订单');
		}
		$customer = M('customer')->where(array('id' => $porder['customer_id'], 'is_del' => 0, 'status' => 1))->find();
		if (!$customer) {
			return rjson(1, '用户未找到');
		}
		switch ($porder['client']) {
			case Client::CLIENT_WX:
				return rjson(0, '公众号无需充值中变动通知');
			case Client::CLIENT_API:
				$t1 = microtime(true);
				if (!$porder['notify_url']) {
					Createlog::porderLog($porder['id'], '未设置api回调地址');
					return rjson(1, '未设置api回调地址');
				} else {
					$param = self::notify_data($customer, $porder);
					Createlog::porderLog($porder['id'], '[充值中]回调链接：' . $porder['notify_url'] . '，post数据:' . http_build_query($param));
					$result = Http::post($porder['notify_url'], $param);
					$t2 = microtime(true);
					Createlog::porderLog($porder['id'], "[充值中]回调通知耗时：" . round($t2 - $t1, 3) . 's');
					if ($result == 'success') {
						Createlog::porderLog($porder['id'], '[充值中]api回调通知成功');
						M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
						return rjson(0, 'api回调通知成功');
					} else {
						Createlog::porderLog($porder['id'], '[充值中]api回调通知失败,响应数据：' . $result);
						return rjson(1, 'api回调通知失败,响应数据：' . $result);
					}
				}
				break;
			case Client::CLIENT_ADM:
				Createlog::porderLog($porder['id'], '后台导入订单无需充值中变动通知');
				return rjson(0, '无需通知');
				break;
			default:
				Createlog::porderLog($porder['id'], '该端订单无需充值中变动通知');
				return rjson(0, '无需通知');
				break;
		}
	}
	public static function rechargeSus($porder_id)
	{
		$porder = M('porder')->where(array('id' => $porder_id, 'status' => 4, 'is_del' => 0, 'is_apart' => array('in', '0,2')))->find();
		if (!$porder) {
			return rjson(1, '未找到订单');
		}
		$customer = M('customer')->where(array('id' => $porder['customer_id'], 'is_del' => 0, 'status' => 1))->find();
		if (!$customer) {
			return rjson(1, '用户未找到');
		}
		
	    if(!empty(C('HFYE_SWITCH_CHECK_B')) && (($porder['type']==1&&in_array(C('HFYE_API_TYPE_STSTUS'),[1,3]))||($porder['type']==3&&in_array(C('HFYE_API_TYPE_STSTUS'),[2,3])))){
	        $porder['electricity_are']=$porder['isp'];
	        if($porder['type']==3){
                $porder['isp']="electricity_balance_query";
	        }
	        self::balance_query($porder['id'], $porder['mobile'], $porder['isp'],$porder['electricity_are'], 'b');
	    }
	    //告警订单检查
	    self::alert_porder_check($porder_id);
		switch ($porder['client']) {
			case Client::CLIENT_WX:
				Templetmsg::chargeSus($porder['customer_id'], '充值账号:' . $porder['mobile'] . ',已经充值成功！', PorderModel::getTypeName($porder['type']), $porder['title'], date('Y-m-d H:i', time()));
				M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
				return rjson(0, '公众号通知成功');
			case Client::CLIENT_API:
				$t1 = microtime(true);
				M('porder')->where(array('id' => $porder['id']))->setInc("notification_num", 1);
				if (!$porder['notify_url']) {
					Createlog::porderLog($porder['id'], '未设置api回调地址');
					return rjson(1, '未设置api回调地址');
				} else {
					$param = self::notify_data($customer, $porder);
					Createlog::porderLog($porder['id'], '回调链接：' . $porder['notify_url'] . '，post数据:' . http_build_query($param));
					$result = Http::post($porder['notify_url'], $param);
					M('porder')->where(array('id' => $porder['id']))->setField(array('notification_time' => time()));
					$t2 = microtime(true);
					Createlog::porderLog($porder['id'], "回调通知耗时：" . round($t2 - $t1, 3) . 's');
					if ($result == 'success') {
						Createlog::porderLog($porder['id'], 'api回调通知成功');
						M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
						return rjson(0, 'api回调通知成功');
					} else {
						Createlog::porderLog($porder['id'], 'api回调通知失败,响应数据：' . $result);
						return rjson(1, 'api回调通知失败,响应数据：' . $result);
					}
				}
				break;
			case Client::CLIENT_ADM:
				if ($porder['customer_id'] == intval(C('JDCONFIG.userid'))) {
					return JdAgent::notify($porder);
				}
				if ($porder['customer_id'] == intval(C('KSCONFIG.userid'))) {
					$ks = new KsAgent();
					return $ks->notify($porder);
				}
				Createlog::porderLog($porder['id'], '后台导入订单无需充值成功通知');
				return rjson(0, '无需通知');
				break;
			default:
				Createlog::porderLog($porder['id'], '该端订单无需充值成功通知');
				return rjson(0, '无需通知');
				break;
		}
	}
	public static function rechargeFail($porder_id)
	{
		$porder = M('porder')->where(array('id' => $porder_id, 'status' => array('in', '5,6,7'), 'is_del' => 0, 'is_apart' => array('in', '0,2')))->find();
		if (!$porder) {
			return rjson(1, '未找到订单');
		}
		$customer = M('customer')->where(array('id' => $porder['customer_id'], 'is_del' => 0, 'status' => 1))->find();
		if (!$customer) {
			return rjson(1, '用户未找到,可能原因删除、禁用');
		}
	    //告警订单检查
	    self::alert_porder_check($porder_id);
		switch ($porder['client']) {
			case Client::CLIENT_WX:
				Templetmsg::chargeFail($porder['customer_id'], '充值账号:' . $porder['mobile'] . ',充值失败了！', $porder['title'], time_format(time()), $porder['remark']);
				M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
				return rjson(0, '公众号通知成功');
			case Client::CLIENT_API:
				$t1 = microtime(true);
				M('porder')->where(array('id' => $porder['id']))->setInc("notification_num", 1);
				if (!$porder['notify_url']) {
					Createlog::porderLog($porder['id'], '未设置api回调地址');
					return rjson(1, '未设置api回调地址');
				} else {
					$param = self::notify_data($customer, $porder);
					Createlog::porderLog($porder['id'], '回调链接：' . $porder['notify_url'] . '，post数据:' . http_build_query($param));
					$result = Http::post($porder['notify_url'], $param);
					M('porder')->where(array('id' => $porder['id']))->setField(array('notification_time' => time()));
					$t2 = microtime(true);
					Createlog::porderLog($porder['id'], "回调通知耗时：" . round($t2 - $t1, 3) . 's');
					if ($result == 'success') {
						Createlog::porderLog($porder['id'], 'api回调通知成功');
						M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
						return rjson(0, 'api回调通知成功');
					} else {
						Createlog::porderLog($porder['id'], 'api回调通知失败,响应数据：' . var_export($result, true));
						return rjson(1, 'api回调通知失败,响应数据：' . var_export($result, true));
					}
				}
				break;
			case Client::CLIENT_ADM:
				if ($porder['customer_id'] == intval(C('JDCONFIG.userid'))) {
					return JdAgent::notify($porder);
				}
				if ($porder['customer_id'] == intval(C('KSCONFIG.userid'))) {
					$ks = new KsAgent();
					return $ks->notify($porder);
				}
				Createlog::porderLog($porder['id'], '后台导入订单无需充值失败通知');
				return rjson(0, '无需通知');
				break;
			default:
				Createlog::porderLog($porder['id'], '该端订单无需充值失败通知');
				return rjson(0, '无需通知');
				break;
		}
	}
	public static function rechargePart($porder_id)
	{
		$porder = M('porder')->where(array('id' => $porder_id, 'status' => array('in', array(12, 13)), 'is_del' => 0, 'is_apart' => array('in', '0,2')))->find();
		if (!$porder) {
			return rjson(1, '未找到订单');
		}
		$customer = M('customer')->where(array('id' => $porder['customer_id'], 'is_del' => 0, 'status' => 1))->find();
		if (!$customer) {
			return rjson(1, '用户未找到');
		}
		if(!empty(C('HFYE_SWITCH_CHECK_B')) && (($porder['type']==1&&in_array(C('HFYE_API_TYPE_STSTUS'),[1,3]))||($porder['type']==3&&in_array(C('HFYE_API_TYPE_STSTUS'),[2,3])))){
	        $porder['electricity_are']=$porder['isp'];
	        if($porder['type']==3){
                $porder['isp']="electricity_balance_query";
	        }
	        self::balance_query($porder['id'], $porder['mobile'], $porder['isp'],$porder['electricity_are'], 'b');
	    }
	    //告警订单检查
	    self::alert_porder_check($porder_id);
		switch ($porder['client']) {
			case Client::CLIENT_WX:
				Templetmsg::chargeSus($porder['customer_id'], '充值账号:' . $porder['mobile'] . ',部分充值成功！', PorderModel::getTypeName($porder['type']), $porder['title'], date('Y-m-d H:i', time()));
				M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
				return rjson(0, '公众号通知成功');
			case Client::CLIENT_API:
				M('porder')->where(array('id' => $porder['id']))->setInc("notification_num", 1);
				if (!$porder['notify_url']) {
					Createlog::porderLog($porder['id'], '未设置api回调地址');
					return rjson(1, '未设置api回调地址');
				} else {
					$param = self::notify_data($customer, $porder);
					Createlog::porderLog($porder['id'], '回调链接：' . $porder['notify_url'] . '，post数据:' . http_build_query($param));
					$result = Http::post($porder['notify_url'], $param);
					M('porder')->where(array('id' => $porder['id']))->setField(array('notification_time' => time()));
					if ($result == 'success') {
						Createlog::porderLog($porder['id'], 'api回调通知成功');
						M('porder')->where(array('id' => $porder['id']))->setField(array('is_notification' => 1));
						return rjson(0, 'api回调通知成功');
					} else {
						Createlog::porderLog($porder['id'], 'api回调通知失败,响应数据：' . $result);
						return rjson(1, 'api回调通知失败,响应数据：' . $result);
					}
				}
				break;
			case Client::CLIENT_ADM:
				Createlog::porderLog($porder['id'], '后台导入订单无需部分充值成功通知');
				return rjson(0, '无需通知');
				break;
			default:
				Createlog::porderLog($porder['id'], '该端订单无需部分充值成功通知');
				return rjson(0, '无需通知');
				break;
		}
	}
	public static function refundSus($porder_id)
	{
		$porder = M('porder')->where(array('id' => $porder_id, 'status' => 6, 'is_del' => 0, 'is_apart' => array('in', '0,2')))->find();
		if (!$porder) {
			return rjson(1, '未找到订单');
		}
		$customer = M('customer')->where(array('id' => $porder['customer_id'], 'is_del' => 0, 'status' => 1))->find();
		if (!$customer) {
			return rjson(1, '用户未找到');
		}
		switch ($porder['client']) {
			case Client::CLIENT_WX:
				return Templetmsg::refund($porder['customer_id'], '充值账号:' . $porder['mobile'] . ',订单:' . $porder['order_number'] . '退款成功！', $porder['total_price'], date('Y-m-d H:i', time()));
			default:
				Createlog::porderLog($porder['id'], '该端订单无需退款通知');
				return rjson(0, '无需通知');
		}
	}
	public static function paySus($porder_id)
	{
		$porder = M('porder')->where(array('id' => $porder_id, 'status' => 2, 'is_del' => 0, 'is_apart' => array('in', '0,2')))->find();
		if (!$porder) {
			return rjson(1, '未找到订单');
		}
		$customer = M('customer')->where(array('id' => $porder['customer_id'], 'is_del' => 0, 'status' => 1))->find();
		if (!$customer) {
			return rjson(1, '用户未找到');
		}
        if(!empty(C('HFYE_SWITCH_CHECK_A')) && (($porder['type']==1&&in_array(C('HFYE_API_TYPE_STSTUS'),[1,3]))||($porder['type']==3&&in_array(C('HFYE_API_TYPE_STSTUS'),[2,3])))){
	        $porder['electricity_are']=$porder['isp'];
	        if($porder['type']==3){
                $porder['isp']="electricity_balance_query";
	        }
	        self::balance_query($porder['id'], $porder['mobile'], $porder['isp'],$porder['electricity_are'], 'a');
	    }
		switch ($porder['client']) {
			case Client::CLIENT_WX:
				return Templetmsg::paySus($porder['customer_id'], '充值账号:' . $porder['mobile'] . ',订单已经提交，正在充值中...', $porder['order_number'], $porder['total_price']);
			default:
				Createlog::porderLog($porder['id'], '该端订单无需下单成功通知');
				return rjson(0, '无需通知');
		}
	}
	private static function notify_data($customer, $porder)
	{
		$state = PorderModel::getState($porder['status']);
		$data = array('userid' => $porder['customer_id'], 'order_number' => $porder['order_number'], 'out_trade_num' => $porder['out_trade_num'], 'mobile' => $porder['mobile'], 'otime' => time(), 'state' => $state, 'remark' => C('IS_SHOW_CLIENT_REMARK') == 1 ? $porder['remark'] : '', 'charge_amount' => $porder['charge_amount'], 'voucher' => PorderModel::getVoucherUrl($porder['id'], $state), 'charge_kami' => $porder['charge_kami']);
		ksort($data);
		$sign_str = urldecode(http_build_query($data) . '&apikey=' . $customer['apikey']);
		$data['sign'] = Yrapilib::sign($sign_str);
		return $data;
	}
	
	public static function alert_porder_check($porder_id)
	{
		$porder = M('porder')->where(array('id' => $porder_id,  'is_del' => 0, 'is_apart' => array('in', '0,2')))->find();
		if (is_numeric($porder["phone_balance_a"])&&is_numeric($porder["phone_balance_b"])) {
		    preg_match('/\d+/',$porder['product_name'],$productbalance);
		    $rechargeblance=$porder["phone_balance_b"]-$porder["phone_balance_a"];
		    
		    if(abs($productbalance[0]-$rechargeblance)>50){//如有问题将问题订单放到告警订单表中
	            Createlog::porderLog($porder_id, '告警订单：部分到账金额'.$rechargeblance);
                M('alert_order')->insertGetId(array('Customer_id' => $porder["customer_id"],'Order_number' => $porder["order_number"], 'Product_name' => $porder["product_name"], 'Total_price' => $porder["total_price"], 'Charge_amount' => $rechargeblance,'Pre_recharge_balance' =>$porder["phone_balance_a"],'Balance_after_recharge' =>$porder["phone_balance_b"]));
            }
		}
		return true;
	}
	private static function balance_query($id, $mobile, $isp,$electricity_are, $type)
    {
        Createlog::porderLog($id, '余额查询前后：'.$type);
        $return=balance_query($mobile,$isp,$electricity_are,'');
        Createlog::porderLog($id, '余额查询结果='. $return['mobile_fee']??null);
        if($type == 'a'){
            M('porder')->where(array('id' => $id))->setField(array('phone_balance_a' =>  $return['mobile_fee']??null));
        }elseif($type == 'b'){
            M('porder')->where(array('id' => $id))->setField(array('phone_balance_b' =>  $return['mobile_fee']??null));
        }
        return true;
    }
}