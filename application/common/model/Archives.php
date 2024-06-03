<?php

//decode by http://chiran.taobao.com/
namespace app\common\model;

use think\Model;
class Archives extends Model
{
	public static function getDoc($id)
	{
		$detail = M('archives')->where(array('id' => $id))->find();
		if (!$detail) {
			return false;
		}
		$channel = M('channeltype')->where(array('id' => $detail['channel']))->find();
		$addtable = M()->table($channel['addtable'])->where(array('aid' => $detail['id']))->find();
		if ($addtable) {
			$detail = array_merge($detail, $addtable);
		}
		return $detail;
	}
	public static function getTypeDoc($typeid)
	{
		$detail = M('archives')->where(array('typeid' => $typeid))->order('id desc')->find();
		if (!$detail) {
			return false;
		}
		$channel = M('channeltype')->where(array('id' => $detail['channel']))->find();
		$addtable = M()->table($channel['addtable'])->where(array('aid' => $detail['id']))->find();
		if ($addtable) {
			$detail = array_merge($detail, $addtable);
		}
		return $detail;
	}
	public static function getTypeAllDoc($typeid)
	{
		$details = M('archives')->where(array('typeid' => $typeid))->order('id desc')->select();
		if (!$details) {
			return false;
		}
		foreach ($details as &$detail) {
			$channel = M('channeltype')->where(array('id' => $detail['channel']))->find();
			$addtable = M()->table($channel['addtable'])->where(array('aid' => $detail['id']))->find();
			if ($addtable) {
				$detail = array_merge($detail, $addtable);
			}
		}
		return $details;
	}
}