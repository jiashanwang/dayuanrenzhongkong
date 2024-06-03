<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\library\Userlogin;
class Home extends Base
{
	public function _apibase()
	{
		if (!array_key_exists('Authorization', $this->header)) {
			djson(-1, "请登录")->send();
			exit;
		}
		$token = $this->header['Authorization'];
		$data = Userlogin::get_userinfo_by_token($token);
		if ($data['errno'] != 0) {
			djson($data['errno'], $data['errmsg'], $data['data'])->send();
			exit;
		}
		$this->customer = $data['data'];
		if (method_exists($this, '_inithomechild')) {
			$this->_inithomechild();
		}
	}
}