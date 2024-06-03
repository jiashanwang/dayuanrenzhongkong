<?php

//decode by http://chiran.taobao.com/
namespace app\admin\model;

use think\Model;
class SystemAttachmentCategory extends Model
{
	protected $pk = "id";
	protected $name = "system_attachment_category";
	protected $append = ["child"];
	public static function Add($name, $att_size, $att_type, $att_dir, $satt_dir = '', $pid = 0)
	{
		$data['name'] = $name;
		$data['att_dir'] = $att_dir;
		$data['satt_dir'] = $satt_dir;
		$data['att_size'] = $att_size;
		$data['att_type'] = $att_type;
		$data['time'] = time();
		$data['pid'] = $pid;
		return self::create($data);
	}
	public function getChildAttr($value, $data)
	{
		return SystemAttachmentCategory::all(array('pid' => $data['id']));
	}
	private $formatTree;
	private function _toFormatTree($list, $level = 0, $title = 'name')
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
	public function toFormatTree($list, $title = 'name', $pk = 'id', $pid = 'pid', $root = 0)
	{
		$list = list_to_tree($list, $pk, $pid, '_child', $root);
		$this->formatTree = array();
		$this->_toFormatTree($list, 0, $title);
		return $this->formatTree;
	}
}