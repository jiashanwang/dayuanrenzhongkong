<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

class Voucher extends Admin
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
		$list = M('voucher_config')->field("*,(select type_name from dyr_product_type where id=type_id) as type_name")->select();
		$this->assign('_list', $list);
		$this->assign('_prefix', HTTP_TYPE . $_SERVER['HTTP_HOST']);
		return view();
	}
	public function deletes()
	{
		if (M('voucher_config')->where(array('id' => I('id')))->delete()) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}
	public function edit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			if (I('id')) {
				$data = M('voucher_config')->where(array('id' => I('id')))->setField($arr);
				if ($data) {
					$this->success('保存成功');
				} else {
					$this->error('编辑失败');
				}
			} else {
				$aid = M('voucher_config')->insertGetId($arr);
				if ($aid) {
					$this->success('新增成功');
				} else {
					$this->error('新增失败');
				}
			}
		} else {
			$info = M('voucher_config')->where(array('id' => I('id')))->find();
			$this->assign('info', $info);
			$this->assign('types', M('product_type')->where(array('status' => 1))->order('sort asc,id asc')->select());
			return view();
		}
	}
}