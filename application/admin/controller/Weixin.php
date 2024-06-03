<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

class Weixin extends Admin
{
	private $wechat;
	public function _init()
	{
		if ($appid = I('id')) {
			S('admin_edit_appid_' . UID, I('id'));
		} else {
			$appid = S('admin_edit_appid_' . UID);
		}
		$this->wxconfig = M('weixin')->where(array('id' => $appid, 'is_del' => 0))->find();
		if ($this->wxconfig) {
			$this->wechat = new \Util\Wechat($this->wxconfig);
		}
		if (!IS_CLI && (!function_exists('get_shoquan_key') || !S(md5(get_shoquan_key())))) {
			echo C('sqyc_msg');
			exit;
		}
	}
	public function index()
	{
		$map['is_del'] = 0;
		$type = 1;
		if (I('type')) {
			$type = I('type');
		}
		$map['type'] = $type;
		$data = M('weixin')->where($map)->field("*,(select username from dyr_customer where id=customer_id) as username")->select();
		foreach ($data as &$item) {
			if ($item['type'] == 1) {
				$item['apiurl'] = U('wxapi/index', array('id' => $item['appid']), true, true);
				$wechat = new \Util\Wechat($item);
				$res = $wechat->get_access_token();
				$item['status'] = $res['errno'] != 0 ? $res['errmsg'] : '正常';
			} else {
				$item['apiurl'] = "-";
				$item['status'] = "-";
			}
		}
		$this->assign('list', $data);
		$this->assign('type', $type);
		return view();
	}
	public function editw()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			if (!$arr['appid']) {
				return $this->error('开发者ID(appid)必填');
			}
			I('?kefu_doc') && ($arr['kefu_doc'] = isset($_POST['kefu_doc']) ? $_POST['kefu_doc'] : '');
			I('?about_us') && ($arr['about_us'] = isset($_POST['about_us']) ? $_POST['about_us'] : '');
			I('?copy_right') && ($arr['copy_right'] = isset($_POST['copy_right']) ? $_POST['copy_right'] : '');
			I('?subsc_doc') && ($arr['subsc_doc'] = isset($_POST['subsc_doc']) ? $_POST['subsc_doc'] : '');
			if (I('id')) {
				$data = M('weixin')->where(array('id' => I('id')))->setField($arr);
				if ($data) {
					return $this->success('保存成功');
				} else {
					return $this->error('编辑失败');
				}
			} else {
				if (M('weixin')->where(array('appid' => $arr['appid'], 'type' => $arr['type'], 'is_del' => 0))->find()) {
					return $this->error('已有相同的appid应用');
				}
				$data = M('weixin')->insertGetId($arr);
				if ($data) {
					return $this->success('新增成功');
				} else {
					return $this->error('新增失败');
				}
			}
		} else {
			$info = M('weixin')->where(array('id' => I('id'), 'is_del' => 0))->find();
			$this->assign('info', $info);
			return view(I('tmp') ?: 'editw');
		}
	}
	public function deletes()
	{
		$weixin = M('weixin')->where(array('id' => I('id')))->find();
		if (!$weixin) {
			return $this->error('未找到微信');
		}
		if (M('weixin')->where(array('id' => $weixin['id']))->setField(array('is_del' => 1))) {
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败');
		}
	}
	public function log()
	{
		$map = array();
		$map['weixin_appid'] = $this->wxconfig['appid'];
		if (I('key')) {
			$map['text|msg_type|openid'] = array('like', '%' . I('key') . '%');
		}
		$data = M('weixin_log')->where($map)->order('create_time desc')->paginate(C('LIST_ROWS'));
		$this->assign('list', $data);
		return view();
	}
	public function add_api_template()
	{
		$res = $this->wechat->apiAddTemplate(I('short_id'));
		if ($res['errno'] != 0) {
			return rjson(1, $res['errmsg']);
		} else {
			return rjson(0, "添加成功", $res['data']['template_id']);
		}
	}
	public function all_template()
	{
		$res = $this->wechat->getAllTemplate();
		if ($res['errno'] != 0) {
			return $this->error($res['errmsg']);
		}
		$this->assign('list', $res['data']['template_list']);
		return view();
	}
	public function reply()
	{
		$map['weixin_appid'] = $this->wxconfig['appid'];
		$list = M('weixin_reply')->where($map)->order('id desc')->paginate(C('LIST_ROWS'));
		$this->assign("list", $list);
		return view();
	}
	public function reply_status()
	{
		$ids = I('ids/a');
		if (!$ids) {
			return $this->error("请选择要操作的数据！");
		}
		$map['id'] = array('in', $ids);
		if (M('weixin_reply')->where($map)->setField('status', I('status') == 1 ? 1 : 0)) {
			return $this->success("操作成功！");
		} else {
			return $this->error("操作失败！");
		}
	}
	public function reply_del()
	{
		$ids = I('ids/a');
		if (!$ids) {
			return $this->error("请选择要操作的数据！");
		}
		$map['id'] = array('in', $ids);
		if (M('weixin_reply')->where($map)->delete()) {
			return $this->success("操作成功！");
		} else {
			return $this->error("操作失败！");
		}
	}
	public function edit_reply()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			if (I('id')) {
				if (M('weixin_reply')->update($arr)) {
					return $this->success("保存成功", U('reply'));
				} else {
					return $this->error("保存失败");
				}
			} else {
				$arr['create_time'] = time();
				$arr['weixin_appid'] = $this->wxconfig['appid'];
				if (M('weixin_reply')->insert($arr)) {
					return $this->success("新增成功", U('reply'));
				} else {
					return $this->error("新增失败");
				}
			}
		} else {
			if (I('id')) {
				$info = M('weixin_reply')->find(I('id'));
			} else {
				$info = array('id' => "", 'type' => 1, 'reply_style' => 1);
			}
			$this->assign("info", $info);
			return view();
		}
	}
	public function edit_reply_init()
	{
		if (I('id')) {
			$info = M('weixin_reply')->find(I('id'));
		} else {
			$info = array('id' => "", 'type' => 1, 'reply_style' => 1);
		}
		return djson(0, '', $info);
	}
	protected $menuType = ["view" => "跳转URL", "click" => "点击推事件", "scancode_push" => "扫码推事件", "scancode_waitmsg" => "扫码推事件且弹出“消息接收中”提示框", "pic_sysphoto" => "弹出系统拍照发图", "pic_photo_or_album" => "弹出拍照或者相册发图", "pic_weixin" => "弹出微信相册发图器", "location_select" => "弹出地理位置选择器"];
	public function menu()
	{
		$map['weixin_appid'] = $this->wxconfig['appid'];
		$list = M('weixin_menu')->where($map)->select();
		$list = $this->wechat->arr2tree($list, 'index', 'pindex', 'sub');
		$this->assign("list", $list);
		return view();
	}
	public function edit()
	{
		if ($this->request->isPost()) {
			$post_data = $_POST['data'];
			!isset($post_data) && $this->error('访问出错，请稍候再试！');
			if (empty($post_data)) {
				try {
					M('weixin_menu')->where(array('weixin_appid' => $this->wxconfig['appid']))->delete();
					$this->wechat->deleteMenu();
				} catch (\Exception $e) {
					return $this->error('删除取消微信菜单失败，请稍候再试！' . $e->getMessage());
				}
				return $this->success('删除并取消微信菜单成功！', '');
			}
			foreach ($post_data as &$vo) {
				isset($vo['content']) && ($vo['content'] = str_replace('"', "'", $vo['content']));
				$vo['weixin_appid'] = $this->wxconfig['appid'];
			}
			M('weixin_menu')->where(array('weixin_appid' => $this->wxconfig['appid']))->delete();
			M('weixin_menu')->insertAll($post_data);
			$res = $this->menu_push();
			if ($res['errno'] == 0) {
				return $this->success('保存发布菜单成功！');
			} else {
				return $this->error('微信菜单发布失败，请稍候再试！' . $res['errmsg']);
			}
		}
	}
	private function menu_push()
	{
		list($map, $field) = array(array('status' => '1', 'weixin_appid' => $this->wxconfig['appid']), 'id,index,pindex,name,type,content');
		$result = M('weixin_menu')->field($field)->where($map)->order('sort ASC,id ASC')->select();
		foreach ($result as &$row) {
			empty($row['content']) && ($row['content'] = uniqid());
			if ($row['type'] === 'miniprogram') {
				list($row['appid'], $row['url'], $row['pagepath']) = explode(',', $row['content'] . ",,");
			} elseif ($row['type'] === 'view') {
				if (preg_match('#^(\\w+:)?//#', $row['content'])) {
					$row['url'] = $row['content'];
				} else {
					$row['url'] = url($row['content'], '', true, true);
				}
			} elseif ($row['type'] === 'event') {
				if (isset($this->menuType[$row['content']])) {
					list($row['type'], $row['key']) = array($row['content'], "wechat_menu#id#" . $row['id']);
				}
			} elseif ($row['type'] === 'media_id') {
				$row['media_id'] = $row['content'];
			} elseif ($row['type'] === 'keys') {
				$row['type'] = 'click';
				$row['key'] = $row['content'];
			} else {
				$row['key'] = "wechat_menu#id#" . $row['id'];
				!in_array($row['type'], $this->menuType) && ($row['type'] = 'click');
			}
			unset($row['content']);
		}
		$menus = $this->wechat->arr2tree($result, 'index', 'pindex', 'sub_button');
		foreach ($menus as &$menu) {
			unset($menu['index'], $menu['pindex'], $menu['id']);
			if (empty($menu['sub_button'])) {
				continue;
			}
			foreach ($menu['sub_button'] as &$submenu) {
				unset($submenu['index'], $submenu['pindex'], $submenu['id']);
			}
			unset($menu['type']);
		}
		$res = $this->wechat->createMenu(json_encode(array('button' => $menus), JSON_UNESCAPED_UNICODE));
		return $res;
	}
	public function templetmsg()
	{
		if (request()->isPost()) {
			$arr = I('post.');
			unset($arr['id']);
			M('weixin_templetmsg')->where(array('id' => I('id')))->setField($arr);
			return $this->success('保存成功');
		}
		if (!M('weixin_templetmsg')->where(array('weixin_appid' => $this->wxconfig['appid']))->find()) {
			M('weixin_templetmsg')->insertGetId(array('weixin_appid' => $this->wxconfig['appid']));
		}
		$info = M('weixin_templetmsg')->where(array('weixin_appid' => $this->wxconfig['appid']))->find();
		$this->assign('info', $info);
		return view();
	}
	public function material()
	{
		$type = I('type') ?: 'image';
		$list_data = array('type' => $type, "offset" => '0', "count" => '20');
		$res = $this->wechat->getForeverMaterialList($list_data);
		if ($res['errno'] != 0) {
			return $this->error($res['errmsg']);
		}
		$this->assign('list', $res['data']['item']);
		$this->assign('type', $type);
		$this->assign('types', array(array('type' => 'image', 'name' => '图片'), array('type' => 'video', 'name' => '视频'), array('type' => 'voice', 'name' => '语音'), array('type' => 'news', 'name' => '图文')));
		return view();
	}
	public function url()
	{
		$data[] = array('name' => '首页', 'url' => C('WEB_URL') . "#/pages/index/index?appid=" . $this->wxconfig['id']);
		$data[] = array('name' => '首页-话费', 'url' => C('WEB_URL') . "#/pages/index/index?appid=" . $this->wxconfig['id'] . "&typeid=1");
		$data[] = array('name' => '首页-电费', 'url' => C('WEB_URL') . "#/pages/index/index?appid=" . $this->wxconfig['id'] . "&typeid=3");
		$data[] = array('name' => '首页-其他类型', 'url' => C('WEB_URL') . "#/pages/index/index?appid=" . $this->wxconfig['id'] . "&typeid=其他类型的ID");
		$data[] = array('name' => '充值记录', 'url' => C('WEB_URL') . "#/pages/index/record?appid=" . $this->wxconfig['id']);
		$data[] = array('name' => '个人中心', 'url' => C('WEB_URL') . "#/pages/my/my?appid=" . $this->wxconfig['id']);
		$data[] = array('name' => '代理中心', 'url' => C('WEB_URL') . "#/pages/agent/index?appid=" . $this->wxconfig['id']);
		$data[] = array('name' => '帮助中心', 'url' => C('WEB_URL') . "#/pages/other/helps?appid=" . $this->wxconfig['id']);
		$data[] = array('name' => '推广海报', 'url' => C('WEB_URL') . "#/pages/agent/poster?appid=" . $this->wxconfig['id']);
		$data[] = array('name' => '余额提现', 'url' => C('WEB_URL') . "#/pages/agent/balance?appid=" . $this->wxconfig['id']);
		$data[] = array('name' => '推广链接', 'url' => C('WEB_URL') . "#/pages/agent/links?appid=" . $this->wxconfig['id']);
		$this->assign('list', $data);
		return view();
	}
}