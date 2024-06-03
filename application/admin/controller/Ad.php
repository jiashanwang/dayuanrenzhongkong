<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

class Ad extends Admin
{
	public function _init()
	{
		if (!IS_CLI && (!function_exists('get_shoquan_key') || !S(md5(get_shoquan_key())))) {
			echo C('sqyc_msg');
			exit;
		}
	}
	public function pindex()
	{
		$map = array();
		if (I('appid')) {
			$map['weixin_appid'] = I('appid');
		}
		$list = M('ad')->where($map)->select();
		$this->assign('list', $list);
		return view();
	}
	public function pedit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			if (I('post.id')) {
				$data = M('Ad')->update($arr);
				if ($data) {
					return $this->success('更新成功', U('pindex'));
				} else {
					return $this->error('更新失败');
				}
			} else {
				$hs = M('ad')->where(array('weixin_appid' => $arr['weixin_appid'], 'key' => $arr['key']))->find();
				if ($hs) {
					return $this->error('已经添加过该广告位了');
				}
				$data = M('Ad')->insert($arr);
				if ($data) {
					return $this->success('新增成功', U('pindex'));
				} else {
					return $this->error('新增失败');
				}
			}
		} else {
			if (I('id')) {
				$info = M('ad')->find(I('id'));
				$this->assign("info", $info);
			}
		}
		return view();
	}
	public function pdel()
	{
		$ret = M('ad')->where(array('id' => I('id')))->delete();
		if ($ret) {
			M('adc')->where(array('ad_id' => I('id')))->delete();
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
	public function cindex()
	{
		$list = M('adc')->where('ad_id', I('ad_id'))->order('sort')->select();
		$this->assign('list', $list);
		return view();
	}
	public function cedit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			$arr['url'] = $_POST['url'];
			if (I('post.id')) {
				$data = M('adc')->update($arr);
				if ($data) {
					return $this->success('更新成功');
				} else {
					return $this->error('更新失败');
				}
			} else {
				$arr['create_time'] = time();
				$data = M('adc')->insert($arr);
				if ($data) {
					return $this->success('新增成功');
				} else {
					return $this->error('新增失败');
				}
			}
		} else {
			if (I('id')) {
				$info = M('adc')->find(I('id'));
				$this->assign("info", $info);
			}
		}
		return view();
	}
	public function cdel()
	{
		$ret = M('adc')->where(array('id' => I('id')))->delete();
		if ($ret) {
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
}