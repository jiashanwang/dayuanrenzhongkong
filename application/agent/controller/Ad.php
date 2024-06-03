<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

class Ad extends H5fx
{
	public function pindex()
	{
		$list = M('ad')->where(array('weixin_appid' => $this->wxconfig['appid']))->select();
		$this->assign('list', $list);
		return view();
	}
	public function pedit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			$arr['weixin_appid'] = $this->wxconfig['appid'];
			unset($arr['id']);
			if (I('post.id')) {
				$data = M('Ad')->where(array('weixin_appid' => $this->wxconfig['appid'], 'id' => I('id')))->setField($arr);
				if ($data) {
					return $this->success('更新成功', U('pindex'));
				} else {
					return $this->error('更新失败');
				}
			} else {
				$hs = M('ad')->where(array('weixin_appid' => $this->wxconfig['appid'], 'key' => $arr['key']))->find();
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
				$info = M('ad')->where(array('weixin_appid' => $this->wxconfig['appid'], 'id' => I('id')))->find();
				$this->assign("info", $info);
			}
		}
		return view();
	}
	public function pdel()
	{
		$ret = M('ad')->where(array('id' => I('id'), 'weixin_appid' => $this->wxconfig['appid']))->delete();
		if ($ret) {
			M('adc')->where(array('ad_id' => I('id')))->delete();
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
	public function cindex()
	{
		$ad = M('ad')->where(array('id' => I('ad_id'), 'weixin_appid' => $this->wxconfig['appid']))->find();
		if (!$ad) {
			return $this->error('参数错误');
		}
		$list = M('adc')->where(array('ad_id' => I('ad_id')))->order('sort')->select();
		$this->assign('list', $list);
		return view();
	}
	public function cedit()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			$ad_id = I('ad_id');
			$ad = M('ad')->where(array('id' => $ad_id, 'weixin_appid' => $this->wxconfig['appid']))->find();
			if (!$ad) {
				return $this->error('参数错误');
			}
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
				$ad = M('ad')->where(array('id' => $info['ad_id'], 'weixin_appid' => $this->wxconfig['appid']))->find();
				if (!$ad) {
					return $this->error('参数错误');
				}
				$this->assign("info", $info);
			}
		}
		return view();
	}
	public function cdel()
	{
		$adc = M('adc')->where(array('id' => I('id')))->find();
		if (!$adc) {
			return $this->error('参数错误');
		}
		$ad = M('ad')->where(array('id' => $adc['ad_id'], 'weixin_appid' => $this->wxconfig['appid']))->find();
		if (!$ad) {
			return $this->error('参数错误');
		}
		$ret = M('adc')->where(array('id' => $adc['id']))->delete();
		if ($ret) {
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
}