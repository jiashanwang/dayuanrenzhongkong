<?php

//decode by http://chiran.taobao.com/
namespace app\agent\controller;

use app\common\model\Archives;
use think\View;
use Util\GoogleAuth;
class Index extends Admin
{
	public function index()
	{
		$data['order_num'] = M('porder')->where(array('status' => array('gt', 1), 'customer_id' => $this->user['id'], 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
		$data['today_order_all_num'] = M('porder')->where(array('status' => array('gt', 1), 'customer_id' => $this->user['id'], 'is_del' => 0, 'is_apart' => array('in', array(0, 2)), 'pay_time' => array('egt', strtotime(date('Y-m-d')))))->count();
		$data['order_ing_num'] = M('porder')->where(array('status' => array('in', '2,3,8,9,10,11'), 'customer_id' => $this->user['id'], 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
		$data['today_order_sus_num'] = M('porder')->where(array('status' => array('in', '4,12,13'), 'customer_id' => $this->user['id'], 'is_del' => 0, 'is_apart' => array('in', array(0, 2)), 'finish_time' => array('egt', strtotime(date('Y-m-d')))))->count();
		$data['today_order_fail_num'] = M('porder')->where(array('status' => array('in', '5,6'), 'customer_id' => $this->user['id'], 'is_del' => 0, 'is_apart' => array('in', array(0, 2)), 'finish_time' => array('egt', strtotime(date('Y-m-d')))))->count();
		$data['leiji_total_price'] = M('porder')->where(array('status' => array('in', '2,3,4,5,8,9,10,11,12,13'), 'is_del' => 0, 'is_apart' => array('in', array(0, 2)), 'customer_id' => $this->user['id']))->sum('total_price');
		$data['today_total_price'] = M('porder')->where(array('status' => array('in', '2,3,4,5,8,9,10,11,12,13'), 'is_del' => 0, 'is_apart' => array('in', array(0, 2)), 'customer_id' => $this->user['id'], 'pay_time' => array('egt', strtotime(date('Y-m-d')))))->sum('total_price');
		$data['balance'] = $this->user['balance'];
		$data['shouxin_e'] = $this->user['shouxin_e'];
		$this->assign('data', $data);
		$this->assign('noticedoc', Archives::getDoc(2));
		return view();
	}
	public function notice_doc()
	{
		$doc = Archives::getTypeDoc(3);
		if (!$doc) {
			return djson(1, '未查询到数据');
		}
		$key = $this->user['id'] . '_' . md5($doc['body']);
		if (S($key)) {
			$isread = 1;
		} else {
			$isread = 0;
			S($key, 1);
		}
		return djson(0, 'ok', array('body' => $doc['body'], 'is_read' => $isread));
	}
	public function all_notice_doc()
	{
		$docs = Archives::getTypeAllDoc(3);
		$this->assign('_list', $docs);
		return view();
	}
	public function statistics()
	{
		$list = M()->query('select sum(total_price) as price,FROM_UNIXTIME(create_time,\'%Y年%m月%d日\') as time from dyr_porder where status in(2,3,4,5) and is_apart in(0,2) and is_del=0 and customer_id=' . $this->user['id'] . ' GROUP BY time order by time asc');
		return djson(0, 'ok', $list);
	}
	public function bind_google_auth()
	{
		if ($this->user['google_auth_secret']) {
			$this->redirect('admin/index');
		}
		$name = C('WEB_SITE_TITLE') . "-代理端" . "-" . $this->user['username'];
		$secret = GoogleAuth::createSecret();
		$qrCodeUrl = GoogleAuth::getQRCodeGoogleUrl($name, $secret);
		$this->assign('qrcode_url', $qrCodeUrl);
		$this->assign('secret', $secret);
		$this->assign('name', $name);
		return view();
	}
	public function save_google_auth()
	{
		if ($this->user['google_auth_secret']) {
			$this->redirect('admin/index');
		}
		$secret = dyr_encrypt(I('secret'));
		$goret = GoogleAuth::verifyCode($secret, I('verifycode'), 1);
		if (!$goret) {
			return $this->error("谷歌身份验证码错误！");
		}
		M('customer')->where(array('id' => $this->user['id']))->setField(array('google_auth_secret' => $secret));
		return $this->success("绑定成功！", U('admin/index'));
	}
	public function skip_google_auth()
	{
		if ($this->user['google_auth_secret']) {
			$this->redirect('admin/index');
		}
		M('customer')->where(array('id' => $this->user['id']))->setField(array('google_auth_secret' => '0'));
		return $this->success("已跳过绑定操作", U('admin/index'));
	}
}