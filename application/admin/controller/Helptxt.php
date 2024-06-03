<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

class Helptxt extends Admin
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
		$map = array();
		if (I('appid')) {
			$map['weixin_appid'] = I('appid');
		}
		$list = M('help_txt')->where($map)->order("sort asc")->select();
		$this->assign('_list', $list);
		return view();
	}
	public function deletes()
	{
		if (M('help_txt')->where(array('id' => I('id')))->delete()) {
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
	public function edit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			if (I('id')) {
				$data = M('help_txt')->where(array('id' => I('id')))->setField($arr);
				if ($data) {
					return $this->success('保存成功');
				} else {
					return $this->error('编辑失败');
				}
			} else {
				$aid = M('help_txt')->insertGetId($arr);
				if ($aid) {
					return $this->success('新增成功');
				} else {
					return $this->error('新增失败');
				}
			}
		} else {
			$info = M('help_txt')->where(array('id' => I('id')))->find();
			$this->assign('info', $info);
			return view();
		}
	}
}