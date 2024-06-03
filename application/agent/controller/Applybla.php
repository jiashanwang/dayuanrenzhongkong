<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

class Applybla extends Admin
{
	public function index()
	{
		$map['customer_id'] = $this->user['id'];
		$list = M('apply_addbla')->where($map)->order('id desc')->paginate(C('LIST_ROWS'));
		$this->assign('_list', $list);
		return view();
	}
	public function edit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			$arr['customer_id'] = $this->user['id'];
			$arr['create_time'] = time();
			if ($arr['remark'] && M('apply_addbla')->where(array('remark' => $arr['remark']))->find()) {
				return $this->error('已存在相同的订单号/流水号/凭证号，请检查是否已经提交过申请！');
			}
			$aid = M('apply_addbla')->insertGetId($arr);
			if ($aid) {
				return $this->success('提交成功，待平台审核');
			} else {
				return $this->error('提交失败');
			}
		} else {
			return view();
		}
	}
	public function get_banks_ajax()
	{
		$banks = M('apply_addbla_bank')->select();
		if (!$banks) {
			return djson(1, '请联系管理员添加收款方式');
		}
		return djson(0, 'ok', $banks);
	}
}