<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

class Poster extends Admin
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
		$list = M('poster_config')->select();
		$this->assign('_list', $list);
		$this->assign('_prefix', C('WEB_URL'));
		return view();
	}
	public function deletes()
	{
		if (M('poster_config')->where(array('id' => I('id')))->delete()) {
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
				$data = M('poster_config')->where(array('id' => I('id')))->setField($arr);
				if ($data) {
					$this->success('保存成功');
				} else {
					$this->error('编辑失败');
				}
			} else {
				$aid = M('poster_config')->insertGetId($arr);
				if ($aid) {
					$this->success('新增成功');
				} else {
					$this->error('新增失败');
				}
			}
		} else {
			$info = M('poster_config')->where(array('id' => I('id')))->find();
			$this->assign('info', $info);
			return view();
		}
	}
}