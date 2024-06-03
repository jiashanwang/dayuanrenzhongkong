<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

use app\common\library\Createlog;
use app\common\model\Balance;
use app\common\model\Porder as PorderModel;
class H5fx extends H5base
{
	protected $wechat;
	public function _h5binit()
	{
		$this->wxconfig = M('weixin')->where(array('customer_id' => $this->user['id'], 'is_del' => 0))->find();
		if (!$this->wxconfig) {
			$this->redirect('h5base/weixin');
			exit;
		}
		$this->wechat = new \Util\Wechat($this->wxconfig);
		if (method_exists($this, '_h5finit')) {
			$this->_h5finit();
		}
	}
	public function index()
	{
		$weixin = $this->wxconfig;
		if ($weixin) {
			$weixin['apiurl'] = C('WEB_URL') . 'admin.php/wxapi/index/id/' . $weixin['appid'] . '.html';
			$weixin['url'] = C('WEB_URL') . '#/?appid=' . $weixin['id'];
			if ($weixin['type'] == 1) {
				$wechat = new \Util\Wechat($weixin);
				$res = $wechat->get_access_token();
				$weixin['status'] = $res['errno'] != 0 ? $res['errmsg'] : '正常';
			}
		}
		$this->assign('weixin', $weixin);
		return view();
	}
	public function customer()
	{
		$map = $this->create_user_map();
		$list = $this->getUserList($map);
		$this->assign('_list', $list);
		$this->assign('_count', $list->total());
		$this->assign('_balance', M('Customer c')->where($map)->sum('balance'));
		return view();
	}
	private function create_user_map()
	{
		$map['c.is_del'] = 0;
		$map['weixin_appid'] = $this->wxconfig['appid'];
		if (I('key')) {
			if (I('query_name')) {
				$map[I('query_name')] = trim(I('key'));
			} else {
				$map['c.username|c.mobile'] = array('like', '%' . trim(I('key')) . '%');
			}
		}
		if (I('grade_id')) {
			$map['c.grade_id'] = I('grade_id');
		}
		if (I('is_subscribe')) {
			$map['c.is_subscribe'] = intval(I('is_subscribe')) - 1;
		}
		if (I('status')) {
			$map['c.status'] = I('status') - 1;
		}
		if (I('id')) {
			$map['c.id'] = I('id');
		}
		if (I('f_id')) {
			$map['c.f_id'] = I('f_id');
		}
		if (I('client')) {
			$map['c.client'] = I('client');
		}
		return $map;
	}
	private function getUserList($map, $page = true)
	{
		if ($page) {
			$list = M('Customer c')->where($map)->field('c.*,(select username from dyr_customer where id=c.f_id) as usernames,(select grade_name from dyr_customer_grade where id=c.grade_id) as grade_name,(select is_zdy_price from dyr_customer_grade where id=c.grade_id) as is_zdy_price,(select sum(total_price) from dyr_porder where customer_id=c.id and status in (2,3,4)) as total_price,(select count(*) from dyr_porder where customer_id=c.id and status>1) as porder_num,(select count(*) from dyr_customer where f_id=c.id and is_del=0) as child_num')->order("c.create_time desc")->paginate(C('LIST_ROWS'));
		} else {
			$list = M('Customer c')->where($map)->field('c.*,(select username from dyr_customer where id=c.f_id) as usernames,(select grade_name from dyr_customer_grade where id=c.grade_id) as grade_name,(select is_zdy_price from dyr_customer_grade where id=c.grade_id) as is_zdy_price,(select sum(total_price) from dyr_porder where customer_id=c.id and status in (2,3,4)) as total_price,(select count(*) from dyr_porder where customer_id=c.id and status>1) as porder_num,(select count(*) from dyr_customer where f_id=c.id and is_del=0) as child_num')->order("c.create_time desc")->select();
		}
		return $list;
	}
	public function qi_jin()
	{
		if (I('status') == 0) {
			if (M('Customer')->where(array('id' => I('id'), 'weixin_appid' => $this->wxconfig['appid']))->setField(array('status' => 0))) {
				Createlog::customerLog(I('id'), '禁用账户', 'H5分销代理：' . $this->user['username']);
				return $this->success('禁用成功');
			} else {
				return $this->error('禁用失败');
			}
		} else {
			if (M('Customer')->where(array('id' => I('id'), 'weixin_appid' => $this->wxconfig['appid']))->setField(array('status' => 1))) {
				Createlog::customerLog(I('id'), '启用账户', 'H5分销代理：' . $this->user['username']);
				return $this->success('启用成功');
			} else {
				return $this->error('启用失败');
			}
		}
	}
	public function orders()
	{
		$map = $this->create_order_map();
		if (I('sort')) {
			$sort = I('sort');
		} else {
			$sort = "id desc";
		}
		$list = D('porder')->where($map)->field("*,(select username from dyr_customer where id=customer_id) as username,(select type_name from dyr_product_type where id=type) as type_name")->order($sort)->paginate(C('LIST_ROWS'));
		$this->assign('total_price', M('porder')->where($map)->sum("total_price"));
		$this->assign('sus_total_price', M('porder')->where($map)->where(array('status' => array('in', 4)))->sum("total_price"));
		$this->assign('_list', $list);
		$this->assign('_total', $list->total());
		$this->assign('_types', M('product_type')->where(array('status' => 1))->order('sort asc,id asc')->select());
		$this->assign('_refund_price', M('porder')->where($map)->sum('refund_price'));
		$this->assign('sus_cost_price', M('porder')->where($map)->where(array('status' => array('in', 4)))->sum('h5fxpay_price'));
		$this->assign('agent_cancel_sw', C('AGENT_CANCEL_SW'));
		return view();
	}
	public function apply_cancel_order()
	{
		if (C('AGENT_CANCEL_SW') != 1) {
			return djson(1, '功能关闭');
		}
		$porder = M('porder')->where(array('weixin_appid' => $this->wxconfig['appid'], 'is_del' => 0, 'id' => I('id')))->find();
		if (!$porder) {
			return djson(1, '订单未找到');
		}
		return PorderModel::applyCancelOrder(array($porder['id']), "H5分销代理-[" . $this->user['id'] . ']' . $this->user['username']);
	}
	private function create_order_map()
	{
		$map['is_del'] = 0;
		$map['weixin_appid'] = $this->wxconfig['appid'];
		$map['is_apart'] = array('in', array(0, 2));
		if ($key = trim(I('key'))) {
			$query_name = I('query_name');
			if ($query_name) {
				if (strpos($query_name, '.') !== false) {
					$qu_arr = explode('.', $query_name);
					$qu_rets = M($qu_arr[0])->where(array($qu_arr[1] => $key))->field('id')->select();
					$map[$qu_arr[2]] = array('in', array_column($qu_rets, 'id'));
				} else {
					$map[$query_name] = $key;
				}
			} else {
				$map['order_number|title|product_name|mobile|out_trade_num|guishu|api_order_number|remark|isp|apart_order_number'] = array('like', '%' . $key . '%');
			}
		}
		if (I('status')) {
			$map['status'] = array('in', I('status'));
		}
		if (I('type')) {
			$map['type'] = I('type');
		}
		if (I('isp')) {
			$map['isp'] = getISPText(I('isp'));
		}
		if (I('customer_id')) {
			$map['customer_id'] = I('customer_id');
		}
		if (I('end_time') && I('begin_time')) {
			$time_style = I('time_style') ?: 'create_time';
			$map[$time_style] = array('between', array(strtotime(I('begin_time')), strtotime(I('end_time'))));
		}
		if (I('batch_mobile')) {
			$batch_order_number = str_replace(' ', '', str_replace(array("\r\n", "\r\n", "\r\n"), ",", I('batch_mobile')));
			$bt_mo = preg_grep('/\\S+/', explode(',', $batch_order_number));
			$bt_mo && ($map['mobile'] = array("in", $bt_mo));
		}
		return $map;
	}
	public function help()
	{
		$map['weixin_appid'] = $this->wxconfig['appid'];
		$list = M('help_txt')->where($map)->order("sort asc")->select();
		$this->assign('_list', $list);
		return view();
	}
	public function help_deletes()
	{
		if (M('help_txt')->where(array('id' => I('id')))->delete()) {
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
	public function help_edit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			if (I('id')) {
				$help = M('help_txt')->where(array('id' => I('id'), 'weixin_appid' => $this->wxconfig['appid']))->setField($arr);
				if (!$help) {
					return $this->error('未找到保存项');
				}
				$data = M('help_txt')->where(array('id' => $help['id']))->setField($arr);
				if ($data) {
					return $this->success('保存成功');
				} else {
					return $this->error('编辑失败');
				}
			} else {
				$arr['weixin_appid'] = $this->wxconfig['appid'];
				$aid = M('help_txt')->insertGetId($arr);
				if ($aid) {
					return $this->success('新增成功');
				} else {
					return $this->error('新增失败');
				}
			}
		} else {
			$info = M('help_txt')->where(array('id' => I('id'), 'weixin_appid' => $this->wxconfig['appid']))->find();
			$this->assign('info', $info);
			return view();
		}
	}
}