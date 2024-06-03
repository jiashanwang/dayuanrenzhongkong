<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

use app\admin\model\MemberLog;
use app\agent\model\AgentLog;
class Admin extends Base
{
	public function _agentbase()
	{
		define('UID', is_login());
		if (!UID) {
			$this->redirect('Login/login');
		}
		$this->user = M('customer')->where(array('id' => UID))->field('*,(select is_zdy_price from dyr_customer_grade where id=grade_id) as is_zdy_price,(select is_agent from dyr_customer_grade where id=grade_id) as is_agent')->find();
		$request = \think\Request::instance();
		define('MODULE_NAME', $request->module());
		define('ACTION_NAME', $request->action());
		define('CONTROLLER_NAME', $request->controller());
		$this->memLog();
		$this->checkGoogleAuth();
		if (method_exists($this, '_init')) {
			$this->_init();
		}
	}
	private function checkGoogleAuth()
	{
		$controller = strtolower(request()->controller());
		if ($controller != 'index' && $this->user['google_auth_secret'] == '') {
			$this->redirect('index/bind_google_auth');
			exit;
		}
	}
	private function memLog()
	{
		$url = strtolower(request()->controller() . '/' . request()->action());
		AgentLog::addLog($this->user['id'], $this->user['username'], $url);
	}
	public function index()
	{
		$mns = D('AgentMenu')->get_menu();
		$menu = $this->getTree($mns, 0);
		$menuhtml = $this->procHtml($menu);
		$this->assign('menuhtml', $menuhtml);
		$this->assign('user', session('user_auth_agent'));
		return view();
	}
	public function getTree($data, $pid)
	{
		$tree = array();
		foreach ($data as $k => $v) {
			if ($v['pid'] == $pid) {
				$v['child'] = $this->getTree($data, $v['id']);
				$tree[] = $v;
			}
		}
		return $tree;
	}
	public function procHtml($tree, $lv = 0)
	{
		$clasarray = array('nav-second-level', 'nav-third-level', 'nav-four-level');
		$html = '';
		foreach ($tree as $t) {
			$i = "";
			if ($t['pid'] == 0) {
				$i = "<i class=\"fa " . $t['icon'] . "\"></i>";
			}
			if ($t['child']) {
				$html .= " <li><a href=\"#\">" . $i . "<span class=\"nav-label\">" . $t['title'] . "</span><span class=\"fa arrow\"></span></a><ul class=\"nav " . $clasarray[$lv] . "\">";
				$html .= $this->procHtml($t['child'], $lv + 1);
				$html = $html . "</ul></li>";
			} else {
				$url = preg_match('/(http:\\/\\/)|(https:\\/\\/)/i', $t['url']) ? $t['url'] : url($t['url']);
				$html .= "<li><a class=\"J_menuItem\" href=\"" . $url . "\"> " . $i . "<span class=\"nav-label\">" . $t['title'] . "</span></a></li>";
			}
		}
		return $html;
	}
	protected function lists($model, $where = array(), $field = true)
	{
		return M($model)->where($where)->field($field)->paginate(C('LIST_ROWS'));
	}
	protected final function editRow($model, $data, $where, $msg)
	{
		$msg = array_merge(array('success' => '操作成功！', 'error' => '操作失败！', 'url' => ''), (array) $msg);
		if (M($model)->where($where)->update($data) !== false) {
			return $this->success($msg['success'], $msg['url']);
		} else {
			return $this->error($msg['error'], $msg['url']);
		}
	}
	protected function forbid($model, $where = array(), $msg = array('success' => '操作成功！', 'error' => '操作失败！'))
	{
		$data = array('status' => 0);
		$this->editRow($model, $data, $where, $msg);
	}
	protected function resume($model, $where = array(), $msg = array('success' => '操作成功！', 'error' => '操作失败！'))
	{
		$data = array('status' => 1);
		$this->editRow($model, $data, $where, $msg);
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
	protected function delete_false($model, $where = array(), $msg = array('success' => '删除成功！', 'error' => '删除失败！'))
	{
		$data = array('is_del' => 1);
		$this->editRow($model, $data, $where, $msg);
	}
}