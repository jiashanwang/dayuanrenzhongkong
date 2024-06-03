<?php

//decode by http://chiran.taobao.com/
namespace Util;

use think\Config;
class Tree
{
	protected static $instance;
	protected $config = [];
	public $options = [];
	public $arr = [];
	public $icon = array("│", "├", "└");
	public $nbsp = "&nbsp;";
	public $pidname = "pid";
	public function __construct($options = [])
	{
		if ($config = Config::get('tree')) {
			$this->options = array_merge($this->config, $config);
		}
		$this->options = array_merge($this->config, $options);
	}
	public static function instance($options = [])
	{
		if (is_null(self::$instance)) {
			self::$instance = new static($options);
		}
		return self::$instance;
	}
	public function init($arr = [], $pidname = NULL, $nbsp = NULL)
	{
		$this->arr = $arr;
		if (!is_null($pidname)) {
			$this->pidname = $pidname;
		}
		if (!is_null($nbsp)) {
			$this->nbsp = $nbsp;
		}
		return $this;
	}
	public function getChild($myid)
	{
		$newarr = array();
		foreach ($this->arr as $value) {
			if (!isset($value['id'])) {
				continue;
			}
			if ($value[$this->pidname] == $myid) {
				$newarr[$value['id']] = $value;
			}
		}
		return $newarr;
	}
	public function getChildren($myid, $withself = FALSE)
	{
		$newarr = array();
		foreach ($this->arr as $value) {
			if (!isset($value['id'])) {
				continue;
			}
			if ($value[$this->pidname] == $myid) {
				$newarr[] = $value;
				$newarr = array_merge($newarr, $this->getChildren($value['id']));
			} else {
				if ($withself && $value['id'] == $myid) {
					$newarr[] = $value;
				}
			}
		}
		return $newarr;
	}
	public function getChildrenIds($myid, $withself = FALSE)
	{
		$childrenlist = $this->getChildren($myid, $withself);
		$childrenids = array();
		foreach ($childrenlist as $k => $v) {
			$childrenids[] = $v['id'];
		}
		return $childrenids;
	}
	public function getParent($myid)
	{
		$pid = 0;
		$newarr = array();
		foreach ($this->arr as $value) {
			if (!isset($value['id'])) {
				continue;
			}
			if ($value['id'] == $myid) {
				$pid = $value[$this->pidname];
				break;
			}
		}
		if ($pid) {
			foreach ($this->arr as $value) {
				if ($value['id'] == $pid) {
					$newarr[] = $value;
					break;
				}
			}
		}
		return $newarr;
	}
	public function getParents($myid, $withself = FALSE)
	{
		$pid = 0;
		$newarr = array();
		foreach ($this->arr as $value) {
			if (!isset($value['id'])) {
				continue;
			}
			if ($value['id'] == $myid) {
				if ($withself) {
					$newarr[] = $value;
				}
				$pid = $value[$this->pidname];
				break;
			}
		}
		if ($pid) {
			$arr = $this->getParents($pid, TRUE);
			$newarr = array_merge($arr, $newarr);
		}
		return $newarr;
	}
	public function getParentsIds($myid, $withself = FALSE)
	{
		$parentlist = $this->getParents($myid, $withself);
		$parentsids = array();
		foreach ($parentlist as $k => $v) {
			$parentsids[] = $v['id'];
		}
		return $parentsids;
	}
	public function getTree($myid, $itemtpl = "<option value=@id @selected @disabled>@spacer@name</option>", $selectedids = '', $disabledids = '', $itemprefix = '', $toptpl = '')
	{
		$ret = '';
		$number = 1;
		$childs = $this->getChild($myid);
		if ($childs) {
			$total = count($childs);
			foreach ($childs as $value) {
				$id = $value['id'];
				$j = $k = '';
				if ($number == $total) {
					$j .= $this->icon[2];
					$k = $itemprefix ? $this->nbsp : '';
				} else {
					$j .= $this->icon[1];
					$k = $itemprefix ? $this->icon[0] : '';
				}
				$spacer = $itemprefix ? $itemprefix . $j : '';
				$selected = $selectedids && in_array($id, is_array($selectedids) ? $selectedids : explode(',', $selectedids)) ? 'selected' : '';
				$disabled = $disabledids && in_array($id, is_array($disabledids) ? $disabledids : explode(',', $disabledids)) ? 'disabled' : '';
				$value = array_merge($value, array('selected' => $selected, 'disabled' => $disabled, 'spacer' => $spacer));
				$value = array_combine(array_map(function ($k) {
					return '@' . $k;
				}, array_keys($value)), $value);
				$nstr = strtr(($value["@" . $this->pidname] == 0 || $this->getChild($id)) && $toptpl ? $toptpl : $itemtpl, $value);
				$ret .= $nstr;
				$ret .= $this->getTree($id, $itemtpl, $selectedids, $disabledids, $itemprefix . $k . $this->nbsp, $toptpl);
				$number++;
			}
		}
		return $ret;
	}
	public function getTreeUl($myid, $itemtpl, $selectedids = '', $disabledids = '', $wraptag = 'ul', $wrapattr = '')
	{
		$str = '';
		$childs = $this->getChild($myid);
		if ($childs) {
			foreach ($childs as $value) {
				$id = $value['id'];
				unset($value['child']);
				$selected = $selectedids && in_array($id, is_array($selectedids) ? $selectedids : explode(',', $selectedids)) ? 'selected' : '';
				$disabled = $disabledids && in_array($id, is_array($disabledids) ? $disabledids : explode(',', $disabledids)) ? 'disabled' : '';
				$value = array_merge($value, array('selected' => $selected, 'disabled' => $disabled));
				$value = array_combine(array_map(function ($k) {
					return '@' . $k;
				}, array_keys($value)), $value);
				$nstr = strtr($itemtpl, $value);
				$childdata = $this->getTreeUl($id, $itemtpl, $selectedids, $disabledids, $wraptag, $wrapattr);
				$childlist = $childdata ? "<" . $wraptag . " " . $wrapattr . ">" . $childdata . "</" . $wraptag . ">" : "";
				$str .= strtr($nstr, array('@childlist' => $childlist));
			}
		}
		return $str;
	}
	public function getTreeMenu($myid, $itemtpl, $selectedids = '', $disabledids = '', $wraptag = 'ul', $wrapattr = '', $deeplevel = 0)
	{
		$str = '';
		$childs = $this->getChild($myid);
		if ($childs) {
			foreach ($childs as $value) {
				$id = $value['id'];
				unset($value['child']);
				$selected = in_array($id, is_array($selectedids) ? $selectedids : explode(',', $selectedids)) ? 'selected' : '';
				$disabled = in_array($id, is_array($disabledids) ? $disabledids : explode(',', $disabledids)) ? 'disabled' : '';
				$value = array_merge($value, array('selected' => $selected, 'disabled' => $disabled));
				$value = array_combine(array_map(function ($k) {
					return '@' . $k;
				}, array_keys($value)), $value);
				$bakvalue = array_intersect_key($value, array_flip(array('@url', '@caret', '@class')));
				$value = array_diff_key($value, $bakvalue);
				$nstr = strtr($itemtpl, $value);
				$value = array_merge($value, $bakvalue);
				$childdata = $this->getTreeMenu($id, $itemtpl, $selectedids, $disabledids, $wraptag, $wrapattr, $deeplevel + 1);
				$childlist = $childdata ? "<" . $wraptag . " " . $wrapattr . ">" . $childdata . "</" . $wraptag . ">" : "";
				$childlist = strtr($childlist, array('@class' => $childdata ? 'last' : ''));
				$value = array('@childlist' => $childlist, '@url' => $childdata || !isset($value['@url']) ? "javascript:;" : url($value['@url']), '@caret' => $childdata && (!isset($value['@badge']) || !$value['@badge']) ? '<i class="fa fa-angle-left"></i>' : '', '@badge' => isset($value['@badge']) ? $value['@badge'] : '', '@class' => ($selected ? ' active' : '') . ($disabled ? ' disabled' : '') . ($childdata ? ' treeview' : ''));
				$str .= strtr($nstr, $value);
			}
		}
		return $str;
	}
	public function getTreeSpecial($myid, $itemtpl1, $itemtpl2, $selectedids = 0, $disabledids = 0, $itemprefix = '')
	{
		$ret = '';
		$number = 1;
		$childs = $this->getChild($myid);
		if ($childs) {
			$total = count($childs);
			foreach ($childs as $id => $value) {
				$j = $k = '';
				if ($number == $total) {
					$j .= $this->icon[2];
					$k = $itemprefix ? $this->nbsp : '';
				} else {
					$j .= $this->icon[1];
					$k = $itemprefix ? $this->icon[0] : '';
				}
				$spacer = $itemprefix ? $itemprefix . $j : '';
				$selected = $selectedids && in_array($id, is_array($selectedids) ? $selectedids : explode(',', $selectedids)) ? 'selected' : '';
				$disabled = $disabledids && in_array($id, is_array($disabledids) ? $disabledids : explode(',', $disabledids)) ? 'disabled' : '';
				$value = array_merge($value, array('selected' => $selected, 'disabled' => $disabled, 'spacer' => $spacer));
				$value = array_combine(array_map(function ($k) {
					return '@' . $k;
				}, array_keys($value)), $value);
				$nstr = strtr(!isset($value['@disabled']) || !$value['@disabled'] ? $itemtpl1 : $itemtpl2, $value);
				$ret .= $nstr;
				$ret .= $this->getTreeSpecial($id, $itemtpl1, $itemtpl2, $selectedids, $disabledids, $itemprefix . $k . $this->nbsp);
				$number++;
			}
		}
		return $ret;
	}
	public function getTreeArray($myid, $itemprefix = '')
	{
		$childs = $this->getChild($myid);
		$n = 0;
		$data = array();
		$number = 1;
		if ($childs) {
			$total = count($childs);
			foreach ($childs as $id => $value) {
				$j = $k = '';
				if ($number == $total) {
					$j .= $this->icon[2];
					$k = $itemprefix ? $this->nbsp : '';
				} else {
					$j .= $this->icon[1];
					$k = $itemprefix ? $this->icon[0] : '';
				}
				$spacer = $itemprefix ? $itemprefix . $j : '';
				$value['spacer'] = $spacer;
				$data[$n] = $value;
				$data[$n]['childlist'] = $this->getTreeArray($id, $itemprefix . $k . $this->nbsp);
				$n++;
				$number++;
			}
		}
		return $data;
	}
	public function getTreeList($data = [], $field = 'name')
	{
		$arr = array();
		foreach ($data as $k => $v) {
			$childlist = isset($v['childlist']) ? $v['childlist'] : array();
			unset($v['childlist']);
			$v[$field] = $v['spacer'] . ' ' . $v[$field];
			$v['haschild'] = $childlist ? 1 : 0;
			if ($v['id']) {
				$arr[] = $v;
			}
			if ($childlist) {
				$arr = array_merge($arr, $this->getTreeList($childlist, $field));
			}
		}
		return $arr;
	}
}