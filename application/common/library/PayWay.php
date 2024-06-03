<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use app\common\model\Client;
class PayWay
{
	const PAY_WAY_NULL = 0;
	const PAY_WAY_JSYS = 1;
	const PAY_WAY_BLA = 2;
	const PAY_WAY_MPYS = 3;
	const PAY_WAY_OFFL = 4;
	const PAY_WAY_H5YS = 5;
	const PAY_WAY_ALIH5 = 6;
	public static function create($paytype, $client, $option)
	{
		$config = M('weixin')->where(array('appid' => $option['appid'], 'type' => $client, 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '没有找到支付配置信息！');
		}
		switch ($paytype) {
			case 1:
				switch ($client) {
					case Client::CLIENT_WX:
						return self::pay(self::PAY_WAY_JSYS, $option, $config);
					case Client::CLIENT_H5:
						return self::pay(self::PAY_WAY_H5YS, $option, $config);
					default:
						return rjson(1, '该客户端不支持的支付方式');
				}
				break;
			case 2:
				switch ($client) {
					case Client::CLIENT_H5:
						return self::pay(self::PAY_WAY_ALIH5, $option, $config);
					default:
						return rjson(1, '该客户端不支持的支付方式');
				}
				break;
			default:
				return rjson(1, '未知的支付方式');
				break;
		}
	}
	private static function pay($payway, $option, $config)
	{
		switch ($payway) {
			case self::PAY_WAY_JSYS:
				$classname = 'Payapi\\' . ucfirst($config['wx_payclass']);
				if (!class_exists($classname)) {
					return rjson(1, '系统错误，支付接口:' . $classname . '不存在');
				}
				$model = new $classname($config);
				if (!method_exists($model, 'create_wxpay_js')) {
					return rjson(1, '系统错误，支付接口:' . $classname . '不支持支付方法（create_wxpay_js）');
				}
				return $model->create_wxpay_js($option, $config);
			case self::PAY_WAY_H5YS:
				$classname = 'Payapi\\' . ucfirst($config['wx_payclass']);
				if (!class_exists($classname)) {
					return rjson(1, '系统错误，支付接口:' . $classname . '不存在');
				}
				$model = new $classname($config);
				if (!method_exists($model, 'create_wxpay_h5')) {
					return rjson(1, '系统错误，支付接口:' . $classname . '不支持支付方法（create_wxpay_h5）');
				}
				$res = $model->create_wxpay_h5($option, $config);
				if ($res['errno'] == 0) {
					return rjson(100, 'ok', $res['data']);
				}
				return $res;
			case self::PAY_WAY_ALIH5:
				$classname = 'Payapi\\' . ucfirst($config['ali_payclass']);
				if (!class_exists($classname)) {
					return rjson(1, '系统错误，支付接口:' . $classname . '不存在');
				}
				$model = new $classname($config);
				if (!method_exists($model, 'create_alipay_h5')) {
					return rjson(1, '系统错误，支付接口:' . $classname . '不支持支付方法（create_alipay_h5）');
				}
				$res = $model->create_alipay_h5($option, $config);
				if ($res['errno'] == 0) {
					return rjson(100, 'ok', $res['data']);
				}
				return $res;
			default:
				return rjson(1, '未知的支付方式');
		}
	}
	public static function refund($payway, $option)
	{
		$config = M('weixin')->where(array('appid' => $option['weixin_appid'], 'type' => $option['type'], 'is_del' => 0))->find();
		if (!$config) {
			return rjson(1, '未配置微信退款参数');
		}
		switch ($payway) {
			case self::PAY_WAY_JSYS:
			case self::PAY_WAY_H5YS:
				$classname = 'Payapi\\' . ucfirst($config['wx_payclass']);
				if (!class_exists($classname)) {
					return rjson(1, '系统错误，退款接口:' . $classname . '不存在');
				}
				$model = new $classname($config);
				if (!method_exists($model, 'refund')) {
					return rjson(1, '系统错误，退款接口:' . $classname . '不支持支付方法（refund）');
				}
				return $model->refund($option, $config);
			case self::PAY_WAY_ALIH5:
				$classname = 'Payapi\\' . ucfirst($config['ali_payclass']);
				if (!class_exists($classname)) {
					return rjson(1, '系统错误，退款接口:' . $classname . '不存在');
				}
				$model = new $classname($config);
				if (!method_exists($model, 'refund')) {
					return rjson(1, '系统错误，退款接口:' . $classname . '不支持支付方法（refund）');
				}
				return $model->refund($option, $config);
			default:
				return rjson(1, '未知的支付方式');
		}
	}
}