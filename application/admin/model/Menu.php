<?php

//decode by http://chiran.taobao.com/
namespace app\admin\model;

class Menu extends \app\common\model\Menu
{
	public function get_menu($ids)
	{
		$map = array('status' => 1, 'hide' => 0, 'type' => 0);
		$map['is_dev'] = array('elt', C('app_debug') ? 1 : 0);
		if (!IS_ROOT) {
			$map['id'] = array('in', $ids);
		}
		return $this->where($map)->order('sort,id')->select();
	}
	public static function getTree($data, $pid)
	{
		$tree = array();
		foreach ($data as $k => $v) {
			if ($v['pid'] == $pid) {
				$v['child'] = self::getTree($data, $v['id']);
				$tree[] = $v;
			}
		}
		return $tree;
	}
	public static function procHtml($tree, $lv = 0)
	{
		$clasarray = array('nav-second-level', 'nav-third-level', 'nav-four-level');
		$html = '';
		foreach ($tree as $t) {
			$i = "";
			if ($t['pid'] == 0) {
				$i = "<i class=\"fa " . $t['icon'] . "\"></i>";
			}
			if ($t['child']) {
				$html .= " <li><a href=\"#\">" . $i . "<span class=\"nav-label\">" . $t['title'] . "</span><span class=\"fa arrow\"></span></a><ul class=\"nav " . $clasarray[$lv] . "\">";
				$html .= self::procHtml($t['child'], $lv + 1);
				$html = $html . "</ul></li>";
			} else {
				$url = preg_match('/(http:\\/\\/)|(https:\\/\\/)/i', $t['url']) ? $t['url'] : url($t['url']);
				$html .= "<li><a class=\"J_menuItem\" href=\"" . $url . "\"> " . $i . "<span class=\"nav-label\">" . $t['title'] . "</span></a></li>";
			}
		}
		return $html;
	}
	public function toTree($list = null, $pk = 'id', $pid = 'pid', $child = '_child')
	{
		if (null === $list) {
			$list =& $this->dataList;
		}
		$tree = array();
		if (is_array($list)) {
			$refer = array();
			foreach ($list as $key => $data) {
				$_key = is_object($data) ? $data->{$pk} : $data[$pk];
				$refer[$_key] =& $list[$key];
			}
			foreach ($list as $key => $data) {
				$parentId = is_object($data) ? $data->{$pid} : $data[$pid];
				$is_exist_pid = false;
				foreach ($refer as $k => $v) {
					if ($parentId == $k) {
						$is_exist_pid = true;
						break;
					}
				}
				if ($is_exist_pid) {
					if (isset($refer[$parentId])) {
						$parent =& $refer[$parentId];
						$parent[$child][] =& $list[$key];
					}
				} else {
					$tree[] =& $list[$key];
				}
			}
		}
		return $tree;
	}
	private $formatTree;
	private function _toFormatTree($list, $level = 0, $title = 'title')
	{
		foreach ($list as $key => $val) {
			$tmp_str = str_repeat("&nbsp;", $level * 2);
			$tmp_str .= "└";
			$val['level'] = $level;
			$val['title_show'] = $level == 0 ? $val[$title] . "&nbsp;" : $tmp_str . $val[$title] . "&nbsp;";
			if (!array_key_exists('_child', $val)) {
				array_push($this->formatTree, $val);
			} else {
				$tmp_ary = $val['_child'];
				unset($val['_child']);
				array_push($this->formatTree, $val);
				$this->_toFormatTree($tmp_ary, $level + 1, $title);
			}
		}
		return;
	}
	public function toFormatTree($list, $title = 'title', $pk = 'id', $pid = 'pid', $root = 0)
	{
		$list = list_to_tree($list, $pk, $pid, '_child', $root);
		$this->formatTree = array();
		$this->_toFormatTree($list, 0, $title);
		return $this->formatTree;
	}
}