<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use app\common\library\Configapi;
use Util\GoogleAuth;
use Util\Updatedt;
class Update extends Admin
{
	public function _init()
	{
		if (!IS_CLI && (!function_exists('get_shoquan_key') || !S(md5(get_shoquan_key())))) {
			echo C('sqyc_msg');
			exit;
		}
	}
	public function index()
	{
		$up = new Updatedt();
		$res = $up->check();
		if ($res['errno'] == 0) {
			$this->assign('updata', $res['data']['wgt']);
		}
		if ($res['errno'] == 0 || $res['errno'] == 2) {
			//$this->assign('history', $res['data']['history_wgt']);
			$this->assign('appinfo', $res['data']);
		}
		$this->assign('check_msg', $res['errmsg']);
		return view();
	}
	public function now_update()
	{
		set_time_limit(0);
		ini_set('max_execution_time', '0');
		$goret = GoogleAuth::verifyCode($this->adminuser['google_auth_secret'], I('verifycode'), 1);
		if (!$goret) {
			return $this->error("谷歌身份验证码错误！");
		}
		$up = new Updatedt();
		$res = $up->check();
		if ($res['errno'] != 0) {
			return $this->error($res['errmsg']);
		}
		try {
			$zipres = $up->start($res['data']['wgt']['version'], $res['data']['wgt']['path']);
			if ($zipres['errno'] != 0) {
				$up->log($res['data']['wgt']['version'], 'zip:' . $zipres['errmsg'], $res['data']['wgt']['path']);
				return $this->error($zipres['errmsg']);
			}
			$sqlres = $up->executesql();
			if ($sqlres['errno'] != 0) {
				$up->log($res['data']['wgt']['version'], 'zip:' . $zipres['errmsg'] . 'sql:' . $sqlres['errmsg'], $res['data']['wgt']['path']);
				return $this->error($sqlres['errmsg']);
			}
			$up->log($res['data']['wgt']['version'], 'zip:' . $zipres['errmsg'] . 'sql:' . $sqlres['errmsg'], $res['data']['wgt']['path']);
			Configapi::clear();
			return $this->success("更新完成");
		} catch (\Exception $exception) {
			return $this->error($exception->getMessage());
		}
	}
	public function his_update()
	{
		set_time_limit(0);
		ini_set('max_execution_time', '0');
		$goret = GoogleAuth::verifyCode($this->adminuser['google_auth_secret'], I('verifycode'), 1);
		if (!$goret) {
			return $this->error("谷歌身份验证码错误！");
		}
		$up = new Updatedt();
		try {
			$version = I('version');
			$path = I('version_path');
			$zipres = $up->start($version, $path, 2, false);
			if ($zipres['errno'] != 0) {
				$up->log($version, 'zip:' . $zipres['errmsg'], $path);
				return $this->error($zipres['errmsg']);
			}
			$sqlres = $up->executesql();
			if ($sqlres['errno'] != 0) {
				$up->log($version, 'zip:' . $zipres['errmsg'] . 'sql:' . $sqlres['errmsg'], $path);
				return $this->error($sqlres['errmsg']);
			}
			$up->log($version, 'zip:' . $zipres['errmsg'] . 'sql:' . $sqlres['errmsg'], $path);
			Configapi::clear();
			return $this->success("更新完成");
		} catch (\Exception $exception) {
			return $this->error($exception->getMessage());
		}
	}
	public function repairsql()
	{
		set_time_limit(0);
		ini_set('max_execution_time', '0');
		$up = new Updatedt();
		try {
			$res = $up->executesql();
			if ($res['errno'] != 0) {
				return $this->error("无法执行修复！", '', $res['data']);
			}
			Configapi::clear();
			return $this->success("修复完成", '', $res['data']);
		} catch (\Exception $exception) {
			return $this->error($exception->getMessage());
		}
	}
}