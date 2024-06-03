<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use app\common\library\SubscribeMessage;
use app\common\library\Templetmsg;
use app\common\library\Transfer;
use app\common\model\Balance;
use app\common\model\Client;
class Tixian extends Admin
{
	public function _init()
	{
		if (!IS_CLI && (!function_exists('get_shoquan_key') || !S(md5(get_shoquan_key())))) {
			echo C('sqyc_msg');
			exit;
		}
	}
	public function index()
	{
		$map = $this->create_map();
		$list = M('tixian a')->join('customer c', 'c.id=a.customer_id')->where($map)->field('a.*,c.username,(select name from dyr_weixin where appid=c.weixin_appid and type=1 and is_del=0) as weixin_name')->order("a.create_time desc")->paginate(C('LIST_ROWS'));
		$total_money = M('tixian a')->join('customer c', 'c.id=a.customer_id')->where($map)->sum('a.money');
		$this->assign('_list', $list);
		$this->assign('_count', $list->total());
		$this->assign('total_money', $total_money);
		return view();
	}
	public function out_excel()
	{
		$map = $this->create_map();
		$ret = M('tixian a')->join('customer c', 'c.id=a.customer_id')->where($map)->field('a.*,c.username')->order("create_time desc")->select();
		$field_arr = array(array('title' => '申请人', 'field' => 'username'), array('title' => '姓名', 'field' => 'name'), array('title' => '账号', 'field' => 'acount'), array('title' => '方式', 'field' => 'style'), array('title' => '金额', 'field' => 'money'), array('title' => '备注', 'field' => 'remark'), array('title' => '提交时间', 'field' => 'create_time'), array('title' => '状态', 'field' => 'status'), array('title' => '处理时间', 'field' => 'deal_time'));
		foreach ($ret as $key => $vo) {
			$ret[$key]['username'] = '[' . $vo['id'] . ']' . $vo['username'];
			$ret[$key]['style'] = C('TIXIAN_STYLE')[$vo['style']];
			$ret[$key]['create_time'] = time_format($vo['create_time']);
			$ret[$key]['deal_time'] = time_format($vo['deal_time']);
			$ret[$key]['status'] = C('TIXIAN_STATUS')[$vo['status']];
		}
		$this->exportToExcel('提现报表' . time(), $field_arr, $ret);
	}
	private function create_map()
	{
		$map = array();
		if (I('key')) {
			$map['a.remark|a.customer_id|c.username'] = array('like', '%' . I('key') . '%');
		}
		if (I('status')) {
			$map['a.status'] = I('status');
		}
		if (I('end_time') && I('begin_time')) {
			$map['a.create_time'] = array('between', array(strtotime(I('begin_time')), strtotime(I('end_time'))));
		}
		return $map;
	}
	public function shenhe()
	{
		$status = I('status');
		$ids = I('id/a');
		$style = I('style');
		$tixians = M('tixian')->where(array('id' => array('in', $ids), 'status' => array('in', '1')))->select();
		if (!$tixians) {
			return $this->error('未查询到需要操作的提现');
		}
		$counts = 0;
		$errmsg = '';
		foreach ($tixians as $tixian) {
			$cus = M('customer')->where(array('id' => $tixian['customer_id']))->find();
			if ($status == 2) {
				if ($style == 1) {
					$ret = Transfer::wx_transfers($cus['weixin_appid'], $cus['wx_openid'] ?: $cus['ap_openid'], $tixian['order_number'], intval($tixian['money'] * 100));
					if ($ret['errno'] != 0) {
						$errmsg .= $ret['errmsg'] . ';';
						continue;
					}
					$reason = "已转入您的微信钱包余额啦！";
				} elseif ($style == 2) {
					$ret = Transfer::ali_transfers($cus['weixin_appid'], $tixian['acount'], $tixian['name'], $tixian['order_number'], $tixian['money']);
					if ($ret['errno'] != 0) {
						$errmsg .= $tixian['acount'] . ',' . $ret['errmsg'] . ',' . $ret['data'] . '|';
						continue;
					}
					$reason = "已转入您的支付宝账户啦！";
				} elseif ($style == 3) {
					$reason = "已转入您提交的" . C('TIXIAN_STYLE')[$tixian['style']] . "的账户中！";
				} else {
					return $this->error('未知的提现渠道');
				}
				$cus['client'] == Client::CLIENT_WX && Templetmsg::tixianSh($tixian['customer_id'], "您好，申请的提现已到账啦～", $cus['username'], time_format($tixian['create_time']), $tixian['money'], $reason);
				M('tixian')->where(array('id' => $tixian['id']))->setField(array('status' => 2, 'deal_time' => time()));
				$counts++;
			} else {
				$reason = I('prompt_remark');
				if (M('tixian')->where(array('id' => $tixian['id']))->setField(array('status' => 3, 'deal_time' => time()))) {
					Balance::revenue($tixian['customer_id'], $tixian['money'], '提现失败退款', Balance::STYLE_REFUND, '管理员');
					$cus['client'] == Client::CLIENT_WX && Templetmsg::tixianSh($tixian['customer_id'], "您好，申请的提现处理失败，已退回钱包余额～", $cus['username'], time_format($tixian['create_time']), $tixian['money'], $reason);
				}
				$counts++;
			}
		}
		if ($counts == 0) {
			return $this->error('操作失败,' . $errmsg);
		}
		return $this->success("成功处理" . $counts . "条");
	}
}