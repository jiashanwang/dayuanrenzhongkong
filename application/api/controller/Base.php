<?php

//decode by http://chiran.taobao.com/
namespace app\api\controller;

use app\common\model\Client;
class Base extends \app\common\controller\Base
{
	public function _base()
	{
		$host_name = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : "*";
		header("Access-Control-Allow-Origin: " . $host_name);
		header("Access-Control-Allow-Credentials:true");
		header("Access-Control-Max-Age:120");
		header("Access-Control-Allow-Methods: PUT,POST,GET,DELETE,OPTIONS");
		header("Access-Control-Allow-Headers: Origin,X-Requested-With,Content-Type,Accept,Authorization,Content-Language,Client,Appid,Vid");
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			exit;
		}
		$this->checkWeihu();
		$this->header = getHeaders();
		$this->client = isset($this->header['Client']) && $this->header['Client'] ? intval($this->header['Client']) : Client::CLIENT_WX;
		$this->wxconfig = null;
		if (isset($this->header['Appid']) && $this->header['Appid']) {
			$map = array('id' => $this->header['Appid'], 'appid' => $this->header['Appid']);
			$this->wxconfig = M('weixin')->where(array('type' => $this->client, 'is_del' => 0))->where(function ($query) use($map) {
				$query->whereOr($map);
			})->find();
		}
		if (!$this->wxconfig) {
			djson(500, "客户端无法打开，请联系管理员配置！")->send();
			exit;
		}
		if (!($this->client == Client::CLIENT_WX && $this->wxconfig['type'] == 1) && !($this->client == Client::CLIENT_H5 && $this->wxconfig['type'] == 2)) {
			djson(500, "该客户端无法打开，请联系管理员配置！")->send();
			exit;
		}
		if ($this->wxconfig && $this->wxconfig['customer_id'] > 0) {
			$fxuser = M('customer')->where(array('id' => $this->wxconfig['customer_id'], 'is_h5fx' => 1))->cache(true)->field('username,mobile,h5_qrurl')->find();
			if (!$fxuser) {
				djson(500, "代理不具备开客户端的条件！")->send();
				exit;
			}
			$this->isfxh5 = true;
			$this->fxuser = $fxuser;
		} else {
			$this->isfxh5 = false;
		}
		if (method_exists($this, '_apibase')) {
			$this->_apibase();
		}
	}
	public function _empty()
	{
		return djson(1, '页面找到不到了！');
	}
}