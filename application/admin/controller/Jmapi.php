<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

class Jmapi extends Admin
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
		$list = M('jmapi')->where(array('is_del' => 0))->select();
		$this->assign('_list', $list);
		return view();
	}
	public function edit()
	{
		if (request()->isPost()) {
			$arr = $_POST;
			if (I('id')) {
				$data = M('jmapi')->where(array('id' => I('id')))->setField($arr);
				if ($data) {
					return $this->success('保存成功');
				} else {
					return $this->error('编辑失败');
				}
			} else {
				$aid = M('jmapi')->insertGetId($arr);
				if ($aid) {
					return $this->success('新增成功');
				} else {
					return $this->error('新增失败');
				}
			}
		} else {
			$info = M('jmapi')->where(array('id' => I('id')))->find();
			$this->assign('info', $info);
			return view();
		}
	}
	public function param()
	{
		$api = M('jmapi')->where(array('id' => I('id')))->find();
		if (!$api) {
			return $this->error('参数错误');
		}
		$this->assign('api', $api);
		$list = M('jmapi_param')->where(array('jmapi_id' => I('id')))->select();
		$this->assign('_list', $list);
		return view();
	}
	public function param_edit()
	{
		if (request()->isPost()) {
			$arr = $_POST;
			if (I('id')) {
				$data = M('jmapi_param')->where(array('id' => I('id')))->setField($arr);
				if ($data) {
					return $this->success('保存成功');
				} else {
					return $this->error('编辑失败');
				}
			} else {
				$data = M('jmapi_param')->insertGetId($arr);
				if ($data) {
					return $this->success('添加成功');
				} else {
					return $this->error('添加失败');
				}
			}
		} else {
			$api = M('jmapi')->where(array('id' => I('jmapi_id')))->find();
			if (!$api) {
				return $this->error('参数错误');
			}
			$info = M('jmapi_param')->where(array('id' => I('id')))->find();
			$this->assign('info', $info);
			return view();
		}
	}
	public function deletes()
	{
		$reapi = M('jmapi')->where(array('id' => I('id')))->find();
		if (!$reapi) {
			return $this->error('未找到接口');
		}
		if (M('product_api')->where(array('reapi_id' => $reapi['id']))->find()) {
			return $this->error('该接口还有产品在使用中，请先取消接口绑定的所有产品');
		}
		if (M('jmapi')->where(array('id' => $reapi['id']))->setField(array('is_del' => 1))) {
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
	public function deletes_param()
	{
		$param = M('jmapi_param')->where(array('id' => I('id')))->find();
		if (!$param) {
			return $this->error('未找到套餐');
		}
		if (M('product_api')->where(array('param_id' => $param['id']))->find()) {
			return $this->error('该套餐还有产品在使用中，请先取消接口套餐绑定的所有产品');
		}
		if (M('jmapi_param')->where(array('id' => $param['id']))->delete()) {
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
	public function get_jmapi_param()
	{
		$map['jmapi_id'] = I('jmapi_id');
		$lists = M('jmapi_param')->where($map)->order("desc asc")->select();
		return djson(0, 'ok', $lists);
	}
}