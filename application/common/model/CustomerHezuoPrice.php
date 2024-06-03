<?php

//decode by http://chiran.taobao.com/
namespace app\common\model;

use think\Model;
class CustomerHezuoPrice extends Model
{
	public static function initPrice()
	{
		$grades = M('customer_grade')->where(array('is_zdy_price' => 1))->select();
		$list = M('customer')->where(array('grade_id' => array('in', array_column($grades, 'id'))))->select();
		foreach ($list as $k => $v) {
			$nolist = M()->query("select p.id from dyr_product as p left join dyr_customer_hezuo_price as gp on gp.product_id=p.id and p.is_del=0 and gp.customer_id=" . $v['id'] . " where gp.id is null");
			foreach ($nolist as $key => $vo) {
				$aid = M('customer_hezuo_price')->where(array('customer_id' => $v['id'], 'product_id' => $vo['id']))->find();
				if ($aid) {
					continue;
				}
				$ga_ranges = M('customer_grade_price')->where(array('grade_id' => $v['grade_id'], 'product_id' => $vo['id']))->value('ranges');
				$g1_ranges = M('customer_grade_price')->where(array('grade_id' => 1, 'product_id' => $vo['id']))->value('ranges');
				$ranges = $g1_ranges - $ga_ranges;
				M('customer_hezuo_price')->insertGetId(array('customer_id' => $v['id'], 'product_id' => $vo['id'], 'ranges' => $ranges ?: 0));
			}
		}
	}
	public static function saveValues($id, $customer_id, $product_id, $data)
	{
		$price = M('customer_hezuo_price')->where(array('id' => $id, 'customer_id' => $customer_id))->find();
		if (!$price) {
			$id = M('customer_hezuo_price')->insertGetId(array('customer_id' => $customer_id, 'product_id' => $product_id));
		}
		M('customer_hezuo_price')->where(array('id' => $id))->setField($data);
		return rjson(0, '保存成功');
	}
}