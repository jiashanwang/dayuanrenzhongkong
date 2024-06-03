<?php

//decode by http://chiran.taobao.com/
namespace app\agent\model;

use think\Model;
class AgentMenu extends Model
{
	public function get_menu()
	{
		$map = array('status' => 1, 'hide' => 0, 'type' => 0);
		return $this->where($map)->order('sort,id')->select();
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
	public function get_menu_like($ids, $key)
	{
		$map = array('status' => 1, 'hide' => 0, 'type' => 0);
		$map['title|url'] = array('like', "%" . $key . "%");
		if (!IS_ROOT) {
			$map['id'] = array('in', $ids);
		}
		return $this->where($map)->select();
	}
}