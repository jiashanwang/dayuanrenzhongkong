<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\model\Porder as PorderModel;
use Payapi\Payalipay;
class More extends \app\common\controller\Base
{
	public function _base()
	{
		$host_name = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "*";
		header("Access-Control-Allow-Origin: " . $host_name);
		header("Access-Control-Allow-Credentials:true");
		header("Access-Control-Max-Age:120");
		header("Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS");
		header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type,Accept,Authorization,Content-Language,Client,Appid,Vid");
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			exit;
		}
		$this->checkWeihu();
	}
	public function alipay_h5()
	{
		$param = I('param');
		$option = json_decode(dyr_decrypt($param), true);
		$ret = Payalipay::create_alipay_do(array('appid' => $option['appid'], 'total_price' => $option['total_price'], 'order_number' => $option['order_number'], 'body' => $option['body']));
		if ($ret['errno'] == 0) {
			echo $ret['data'];
			exit;
		}
		echo $ret['errmsg'];
	}
	public function queryorder()
	{
		if (C('IS_OPEN_COM_PORDER') != 1) {
			return djson(1, '系统未开启查询功能');
		}
		if (empty(trim(I('key')))) {
			return djson(1, '输入充值号码/单号查询');
		}
		$ip = get_client_ip();
		$count = floatval(S('Limitqueryorder' . $ip));
		if ($count >= 1) {
			return djson(1, '查询过于频繁，请稍后再试');
		}
		S('Limitqueryorder' . $ip, $count + 1, array('expire' => 3));
		$map['is_apart'] = array('in', array(0, 2));
		$map['is_del'] = 0;
		$map['order_number|mobile|out_trade_num'] = I('key');
		$porders = PorderModel::where($map)->order('id desc')->field('id,status,order_number,mobile,create_time,isp,product_name,param1,param2,param3,finish_time,out_trade_num,remark,guishu')->select();
		if (!$porders) {
			return djson(1, '没有查询到相关订单信息');
		}
		foreach ($porders as &$porder) {
			$porder['haoshi'] = elapsed_time($porder['create_time'], $porder['finish_time']);
		}
		return djson(0, '查询完成', $porders);
	}
}