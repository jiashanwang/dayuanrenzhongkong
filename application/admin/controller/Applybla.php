<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use app\common\model\Balance;
class Applybla extends Admin
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
		$list = M('apply_addbla a')->join('customer c', 'c.id=a.customer_id')->order('a.id desc')->field('a.*,c.username')->paginate(C('LIST_ROWS'));
		$this->assign('_list', $list);
		return view();
	}
	public function shenhe()
	{
		$id = I('id');
		$status = I('status');
		$deal_remark = I('deal_remark');
		$addbla = M('apply_addbla')->where(array('id' => $id, 'status' => 1))->find();
		if (!$addbla) {
			return $this->error('不存在的申请记录');
		}
		if ($status == 2) {
			Balance::revenue($addbla['customer_id'], $addbla['money'], '打款申请通过加款，收款账户：' . $addbla['platform_account'] . ",流水号：" . $addbla['remark'], Balance::STYLE_RECHARGE, $this->adminuser['nickname']);
			M('apply_addbla')->where(array('id' => $id))->setField(array('status' => 2, 'deal_time' => time(), 'deal_remark' => "审核通过", 'operator' => $this->adminuser['nickname']));
			return $this->success('操作成功');
		}
		if ($status == 3) {
			M('apply_addbla')->where(array('id' => $id))->setField(array('status' => 3, 'deal_time' => time(), 'deal_remark' => $deal_remark, 'operator' => $this->adminuser['nickname']));
			return $this->success('操作成功');
		}
		return $this->error('未知操作');
	}
	public function banks()
	{
		$map = array();
		$this->assign('_list', M('apply_addbla_bank')->where($map)->select());
		return view();
	}
	public function bank_edit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			$arr['tishi'] = isset($_POST['tishi']) ? $_POST['tishi'] : '';
			unset($arr['id']);
			if (I('id')) {
				$data = M('apply_addbla_bank')->where(array('id' => I('id')))->setField($arr);
				if ($data) {
					return $this->success('保存成功');
				} else {
					return $this->error('编辑失败');
				}
			} else {
				$aid = M('apply_addbla_bank')->insertGetId($arr);
				if ($aid) {
					return $this->success('新增成功');
				} else {
					return $this->error('新增失败');
				}
			}
		} else {
			$info = M('apply_addbla_bank')->where(array('id' => I('id')))->find();
			$this->assign('info', $info);
			return view();
		}
	}
	public function bank_del()
	{
		if (M('apply_addbla_bank')->where(array('id' => I('id')))->delete()) {
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
}