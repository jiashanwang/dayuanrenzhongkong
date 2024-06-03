<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use app\admin\model\MemberLog;
use app\admin\model\Menu;
class Admin extends Base
{
	public function _adminbase()
	{
		define('UID', is_login());
		if (!UID) {
			$this->redirect('Login/login');
		}
		$this->adminuser = M('member')->where(array('id' => UID))->find();
		define('IS_ROOT', is_administrator());
		$request = \think\Request::instance();
		define('MODULE_NAME', $request->module());
		define('ACTION_NAME', $request->action());
		define('CONTROLLER_NAME', $request->controller());
		if (!IS_ROOT) {
			$rule = strtolower(CONTROLLER_NAME . '/' . ACTION_NAME);
			$menu = new Menu();
			if (!$menu->check_rules($rule, $request->module(), UID)) {
				$this->error("未授权访问");
				exit;
			}
		}
		$this->memLog();
		$this->checkGoogleAuth();
		if (method_exists($this, '_init')) {
			$this->_init();
		}
	}
	private function memLog()
	{
		$url = strtolower(request()->controller() . '/' . request()->action());
		MemberLog::addLog($this->adminuser['id'], $this->adminuser['nickname'], $url);
	}
	private function checkGoogleAuth()
	{
		$controller = strtolower(request()->controller());
		if ($controller != 'index' && $this->adminuser['google_auth_secret'] == '') {
			$this->redirect('index/bind_google_auth');
			exit;
		}
	}
	public function index()
	{
		$menu = new Menu();
		$mns = $menu->get_menu($menu->get_menu_ids(request()->module(), UID));
		$menu = Menu::getTree($mns, 0);
		$menuhtml = Menu::procHtml($menu);
		$this->assign('menuhtml', $menuhtml);
		$this->assign('user', session('user_auth'));
		return view();
	}
	protected function lists($model, $where = array(), $field = true)
	{
		return M($model)->where($where)->field($field)->paginate(C('LIST_ROWS'));
	}
	protected function delete($model, $where = array(), $msg = array('success' => '删除成功！', 'error' => '删除失败！'))
	{
		$ret = M($model)->where($where)->delete();
		if ($ret) {
			return $this->success($msg['success'], '');
		} else {
			return $this->error($msg['error'], '');
		}
	}
}