<?php

//decode by http://chiran.taobao.com/
namespace app\common\model;

use think\Db;
use think\Model;
class Menu extends Model
{
	protected static function init()
	{
		Menu::event('after_insert', function ($menu) {
			$groups = M('auth_group')->where(array('module' => $menu->module, 'is_admin' => 1))->select();
			foreach ($groups as $k => $g) {
				M('auth_group')->where(array('id' => $g['id']))->setField(array('rules' => $g['rules'] . ',' . $menu['id']));
			}
		});
	}
	public function getUrlAttr($value)
	{
		return strtolower($value);
	}
	public function getModuleAttr($value)
	{
		return strtolower($value);
	}
	public function setUrlAttr($value)
	{
		return strtolower($value);
	}
	public function setModuleAttr($value)
	{
		return strtolower($value);
	}
	public function get_menu_ids($module, $user_id)
	{
		$rules_group = Db::name('auth_group_access a')->join("auth_group g", "a.group_id=g.id", 'LEFT')->where(array('g.module' => $module, "a.user_id" => $user_id, 'g.status' => 1))->field('rules')->select();
		$ids = array();
		foreach ($rules_group as $g) {
			$ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
		}
		$ids = array_unique($ids);
		return $ids;
	}
	public function get_auth_list($module, $user_id)
	{
		$ids = $this->get_menu_ids($module, $user_id);
		$map = array('id' => array('in', $ids), 'status' => 1);
		$rules = Db::name("menu")->where($map)->field('url')->select();
		$authList = array();
		foreach ($rules as $rule) {
			$authList[] = strtolower($rule['url']);
		}
		S('auth_list_' . $module . '_' . $user_id, $authList, array('expire' => 60 * 3));
		return array_unique($authList);
	}
	public function check_rules($name, $module, $user_id)
	{
		if ($auth_list = S('auth_list_' . $module . '_' . $user_id)) {
		} else {
			$auth_list = $this->get_auth_list($module, $user_id);
		}
		$name = strtolower($name);
		if (in_array($name, $auth_list)) {
			return true;
		} else {
			return false;
		}
	}
	public function autoMenu($url, $module)
	{
		$mm = M('menu')->where(array('url' => strtolower($url), 'module' => $module))->find();
		if ($mm) {
			return false;
		}
		$fm = M('menu')->where(array('url' => array('like', strtolower(request()->controller()) . '/%'), 'module' => $module))->find();
		$this->save(array('title' => $url, 'module' => $module, 'pid' => $fm ? $fm['id'] : 0, 'sort' => 0, 'url' => strtolower($url), 'hide' => 0, 'is_dev' => 0, 'status' => 1, 'type' => 1));
		return true;
	}
}