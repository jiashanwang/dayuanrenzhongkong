<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

class AgentGrade
{
	public static function initPrice()
	{
		$list = M('customer_grade')->select();
		foreach ($list as $k => $v) {
			$nolist = M()->query("select p.id from dyr_product as p left join dyr_customer_grade_price as gp on gp.product_id=p.id and p.is_del=0 and gp.grade_id=" . $v['id'] . " where gp.id is null");
			foreach ($nolist as $key => $vo) {
				$aid = M('customer_grade_price')->where(array('grade_id' => $v['id'], 'product_id' => $vo['id']))->find();
				if ($aid) {
					continue;
				}
				M('customer_grade_price')->insertGetId(array('grade_id' => $v['id'], 'product_id' => $vo['id']));
			}
		}
	}
}