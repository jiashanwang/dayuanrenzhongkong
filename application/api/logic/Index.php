<?php

//decode by http://chiran.taobao.com/
namespace app\api\logic;

use think\Logic;
class Index extends Logic
{
	public function get_product_mobile()
	{
		$this->allowMethods = 'post';
		$this->rules = array('mobile' => 'require|number|length:11');
		$this->message = array('mobile' => '手机格式错误');
	}
	public function qq_nick_query()
	{
		$this->allowMethods = 'post';
		$this->rules = array('account' => 'require|number|length:5,11');
		$this->message = array('account' => '请先输入正确的qq号');
	}
	public function test()
	{
		$this->allowMethods = 'get';
		$this->allowMethods = 'post';
		$this->rules = array('name' => 'require|max:25|min:2', 'email' => 'require|email', 'age' => 'number|between:1,120', 'numbt' => 'number|notBetween:1,10', 'status' => 'in:1,2,3', 'status2' => 'notIn:1,2,3', 'card' => 'number|length:16,18', 'scard' => 'number|length:18', 'float' => 'float', 'repassword' => 'require|confirm:password', 'account' => 'require|different:name', 'boolean' => 'boolean', 'array' => 'array', 'score1' => 'eq:100', 'score2' => 'egt:60', 'score3' => 'elt:100', 'score4' => 'lt:100', 'price' => 'lt:market_price', 'accepted' => 'accepted', 'date' => 'date', 'alpha' => 'alpha', 'alphaNum' => 'alphaNum', 'alphaDash' => 'alphaDash', 'chs' => 'chs', 'chsAlpha' => 'chsAlpha', 'chsAlphaNum' => 'chsAlphaNum', 'chsDash' => 'chsDash', 'activeUrl' => 'activeUrl', 'url' => 'url', 'ip' => 'ip', 'create_time' => 'dateFormat:y-m-d', 'begin_time' => 'after:2016-3-18', 'end_time' => 'before:2016-10-01', 'expire_time' => 'expire:2016-2-1,2016-10-01', 'chepai1' => array('regex' => '/[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领 A-Z]{1}[A-HJ-NP-Z]{1}(([0-9]{5}[DF])|([DF][A-HJ-NP-Z0-9][0-9]{4}))$/'), 'chepai2' => array('regex' => '/^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领 A-Z]{1}[A-HJ-NP-Z]{1}[A-Z0-9]{4}[A-Z0-9挂学警港澳]{1}$/'), 'mobile' => array('regex' => '/^1((3[\\d])|(4[5,6,7,9])|(5[0-3,5-9])|(6[5-7])|(7[0-8])|(8[\\d])|(9[1,8,9]))\\d{8}$/'), 'tel' => array('regex' => '/^\\d{8}(0\\d|11|12)([0-2]\\d|30|31)\\d{3}$/'), 'idcard' => array('regex' => '/^\\d{6}(18|19|20)\\d{2}(0\\d|11|12)([0-2]\\d|30|31)\\d{3}(\\d|X|x)$/'), 'qq' => array('regex' => '/^[1-9][0-9]{4,10}$/'), 'post_code' => array('regex' => '/^(0[1-7]|1[0-356]|2[0-7]|3[0-6]|4[0-7]|5[1-7]|6[1-7]|7[0-5]|8[013-6])\\d{4}$/'), 'password' => array('require', '/^.*(?=.{6,16})(?=.*\\d)(?=.*[A-Z]{1,})(?=.*[a-z]{1,}).*$/'));
		$this->message = array('name.require' => '名称必须', 'name.max' => '名称最多不能超过25个字符', 'name.min' => '名称长度不能少于2个字符', 'email' => '邮箱格式错误', 'age' => '年龄格式错误');
	}
}