<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use app\admin\model\Menu as MenuModel;
class Auth extends Admin
{
	public function index()
	{
		$module = I('module') ?: 'admin';
		$this->assign('module', $module);
		$list = D('AuthGroup')->get_auth($module);
		$menu_count = M('menu')->where(array('module' => $module, 'type' => array('in', '0,1')))->count();
		foreach ($list as $key => $val) {
			$list_count = count(explode(',', trim($val['rules'], ',')));
			$list[$key]['access_num'] = $list_count;
			$list[$key]['menu_num'] = $menu_count;
		}
		$this->assign('list', $list);
		return view();
	}
	public function access()
	{
		$group_id = I('group_id');
		$module = I('module') ?: 'admin';
		$group = M('auth_group')->where(array('id' => $group_id))->find();
		$this->assign("group", $group);
		$rules = explode(',', $group['rules']);
		$mns = M('menu')->where(array('module' => $module, 'type' => array('in', '0,1')))->order('sort asc')->select();
		$menu = MenuModel::getTree($mns, 0);
		$menuhtml = $this->proc_access_html($menu, 0, $rules);
		$this->assign('access_html', $menuhtml);
		return view();
	}
	public function edit_access()
	{
		if (!I('ids')) {
			return $this->error("修改失败");
		}
		$arr['rules'] = I('post.ids');
		M('auth_group')->where(array('id' => I('post.gid')))->update($arr);
		return $this->success("修改成功！");
	}
	private function proc_access_html($tree, $lv = 0, $rules)
	{
		$html = '';
		foreach ($tree as $t) {
			if ($t['pid'] == 0) {
				$html .= "<div class=\"panel panel-default\">" . " <div class=\"panel-heading\">" . "     <div class=\"checkbox i-checks\" style='margin:0'>";
				if (in_array($t['id'], $rules)) {
					$html .= "<label style='padding:0'><input type=\"checkbox\" data-pid='" . $t['pid'] . "' checked value=\"" . $t['id'] . "\"> <i></i>" . $t['title'] . "</label>";
				} else {
					$html .= "<label style='padding:0'><input type=\"checkbox\" data-pid='" . $t['pid'] . "' value=\"" . $t['id'] . "\"> <i></i>" . $t['title'] . "</label>";
				}
				$html .= " </div></div>";
			}
			if ($t['child']) {
				$html .= $this->proc_access_htmls($t['child'], $lv + 1, $rules);
			}
			$html .= "</div>";
		}
		return $html;
	}
	private function proc_access_htmls($tree, $lv = 1, $rules)
	{
		$html = '';
		foreach ($tree as $t) {
			if ($lv == 1) {
				$pad_left = 20;
			} else {
				$pad_left = 0;
			}
			if ($lv > 0) {
				$pad_left = $pad_left + 10 * $lv;
			}
			$block = "block";
			if ($lv > 1) {
				$block = "inline-block;";
			}
			if ($lv > 2) {
				$float = "float:left;clear:none";
			} else {
				$float = "clear:left;";
			}
			if ($t['child']) {
				$html .= " <div class=\"panel-collapse collapse in\" style='padding-left:" . $pad_left . "px;display:" . $block . $float . "'>" . "     <div class=\"checkbox i-checks\">";
				if (in_array($t['id'], $rules)) {
					$html .= "<label style='padding:0'><input type=\"checkbox\" data-pid='" . $t['pid'] . "' checked value=\"" . $t['id'] . "\"> <i></i>" . $t['title'] . "</label></div>";
				} else {
					$html .= "<label style='padding:0'><input type=\"checkbox\" data-pid='" . $t['pid'] . "' value=\"" . $t['id'] . "\"> <i></i>" . $t['title'] . "</label></div>";
				}
				$html .= $this->proc_access_htmls($t['child'], $lv + 1, $rules);
				$html .= "</div>";
			} else {
				$html .= " <div class=\"panel-collapse collapse in\" style='padding-left:" . $pad_left . "px;display:" . $block . $float . "'>" . "     <div class=\"checkbox i-checks\">";
				if (in_array($t['id'], $rules)) {
					$html .= "<label style='padding:0'><input type=\"checkbox\" data-pid='" . $t['pid'] . "' checked value=\"" . $t['id'] . "\"> <i></i>" . $t['title'] . "</label></div>";
				} else {
					$html .= "<label style='padding:0'><input type=\"checkbox\" data-pid='" . $t['pid'] . "' value=\"" . $t['id'] . "\"> <i></i>" . $t['title'] . "</label></div>";
				}
				$html .= "</div>";
			}
		}
		return $html;
	}
	public function user()
	{
		$group_id = I('group_id');
		$module = I('module') ?: 'admin';
		$list = D('AuthGroup')->get_user($group_id, $module);
		$this->assign('list', $list);
		$this->assign('group_list', D('AuthGroup')->get_group($module));
		return view();
	}
	public function change_user_auth()
	{
		$map['group_id'] = I('group_id');
		$map['id'] = I('id');
		if (M('auth_group_access')->where($map)->delete()) {
			return $this->success("解除成功！");
		} else {
			return $this->error("解除失败");
		}
	}
	public function add_user_auth()
	{
		$gid = I('group_id');
		$uid = explode(',', I('uid'));
		if (empty($uid)) {
			return $this->error('参数有误');
		}
		$group = M('AuthGroup')->where(array("id" => $gid))->find();
		if (!$group) {
			return $this->error('未找到权限组');
		}
		$data = array();
		foreach ($uid as $key => $val) {
			$arr['user_id'] = $val;
			$arr['group_id'] = $gid;
			if ($group['module'] == 'admin') {
				$u_find = M('member')->where(array("id" => $val, 'is_del' => 0))->find();
				if ($val == 1) {
					return $this->error('超级管理员无需设置角色组:' . $val);
				}
			} else {
				$u_find = M('customer')->where(array("id" => $val, 'is_del' => 0))->find();
			}
			if (!$u_find) {
				return $this->error('用户不存在:' . $val);
			}
			$g_find = M('auth_group_access')->where($arr)->find();
			if ($g_find) {
				return $this->error('用户已经在该角色组了:' . $val);
			}
			$data[] = $arr;
		}
		$ret = M('auth_group_access')->insertAll($data);
		if ($ret) {
			return $this->success("添加成功！");
		} else {
			return $this->error('添加失败，请重试！');
		}
	}
	public function change_status()
	{
		if (D('AuthGroup')->where(array("id" => I('id')))->setField(array('status' => I('value')))) {
			return $this->success("改变成功！");
		} else {
			return $this->error("修改失败");
		}
	}
	public function edit()
	{
		if (request()->isPost()) {
			$arr['status'] = I('post.status');
			$arr['title'] = I('post.title');
			$arr['description'] = I('post.description');
			$arr['module'] = I('post.module');
			if (I('id')) {
				if (M('AuthGroup')->where(array('id' => I('id')))->update($arr)) {
					return $this->success("修改成功！", U('index'));
				} else {
					return $this->error("修改失败");
				}
			} else {
				$arr['status'] = 1;
				$arr['type'] = 1;
				if (M('AuthGroup')->insertGetId($arr)) {
					return $this->success("新增成功！", U('index'));
				} else {
					return $this->error("修改失败");
				}
			}
		} else {
			$info = M('AuthGroup')->where(array('id' => I('id')))->find();
			$this->assign('info', $info);
			return view();
		}
	}
	public function deleteauth($id)
	{
		M('AuthGroup')->where(array('id' => $id))->delete();
		M('AuthGroupAccess')->where(array('group_id' => $id))->delete();
		return $this->success("删除成功！", U('index'));
	}
}