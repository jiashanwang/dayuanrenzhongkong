<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

class H5base extends Admin
{
	public function _init()
	{
		if ($this->user['is_h5fx'] !== 1) {
			echo "您的账号没有开通此权限";
			exit;
		}
		if (method_exists($this, '_h5binit')) {
			$this->_h5binit();
		}
	}
	public function weixin()
	{
		$info = M('weixin')->where(array('is_del' => 0, 'customer_id' => $this->user['id']))->find();
		if (request()->isPost()) {
			$arr = I('post.');
			if (!$arr['appid']) {
				return $this->error('开发者ID(appid)必填');
			}
			$arr['kefu_doc'] = isset($_POST['kefu_doc']) ? $_POST['kefu_doc'] : '';
			$arr['about_us'] = isset($_POST['about_us']) ? $_POST['about_us'] : '';
			$arr['copy_right'] = isset($_POST['copy_right']) ? $_POST['copy_right'] : '';
			$arr['subsc_doc'] = isset($_POST['subsc_doc']) ? $_POST['subsc_doc'] : '';
			$arr['customer_id'] = $this->user['id'];
			if ($info) {
				M('weixin')->where(array('id' => $info['id']))->setField($arr);
			} else {
				if (M('weixin')->where(array('appid' => $arr['appid'], 'is_del' => 0))->find()) {
					return $this->error('系统已有相同的appid应用');
				}
				M('weixin')->insertGetId($arr);
			}
			return $this->success('保存成功', U('h5fx/index'));
		} else {
			$this->wxconfig = M('weixin')->where(array('customer_id' => $this->user['id'], 'is_del' => 0))->find();
			$this->assign('info', $info);
			return view();
		}
	}
}