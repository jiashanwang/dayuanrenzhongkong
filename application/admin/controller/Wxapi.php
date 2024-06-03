<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use app\common\library\Createlog;
use app\common\library\Email;
use app\common\library\Userlogin;
use app\common\model\Client;
class Wxapi extends Base
{
	private $wechat;
	private $type;
	private $openid;
	public function _adminbase()
	{
		$this->wxconfig = M('weixin')->where(array('appid' => I('id'), 'type' => 1, 'is_del' => 0))->find();
		$this->wechat = new \Util\Wechat($this->wxconfig);
	}
	public function index()
	{
		$this->wechat->valid();
		$this->type = $this->wechat->getRev()->getRevType();
		$this->openid = $this->wechat->getRevFrom();
		$this->type && $this->add_weixin_log($this->openid, $this->type, json_encode($this->wechat->getRevEvent()), json_encode($this->wechat->getRevData()));
		switch ($this->type) {
			case 'text':
				$this->textReply();
				break;
			case 'event':
				$this->eventReply();
				break;
			case 'image':
				$pic = $this->wechat->getRevPic();
				break;
			case 'voice':
				$voice = $this->wechat->getRevVoice();
				break;
			case 'video':
			case 'shortvideo':
				$video = $this->wechat->getRevVideo();
				break;
			case 'location':
				$loc = $this->wechat->getRevGeo();
				break;
			case 'link':
				$link = $this->wechat->getRevLink();
				break;
			default:
				break;
		}
	}
	private function textReply()
	{
		$content = $this->wechat->getRevContent();
		$map['keywords'] = array('like', '%' . $content . '%');
		$map['type'] = 1;
		$map['status'] = 1;
		$map['weixin_appid'] = $this->wxconfig['appid'];
		$reply = M('weixin_reply')->where($map)->find();
		if (!$reply) {
			$map['keywords'] = "*";
			$reply = M('weixin_reply')->where($map)->find();
		}
		$this->auto_reply($reply);
	}
	private function eventReply()
	{
		$event = $this->wechat->getRevEvent();
		$map['event'] = $event['event'];
		$map['type'] = 2;
		$map['status'] = 1;
		$map['weixin_appid'] = $this->wxconfig['appid'];
		switch (strtolower($event['event'])) {
			case 'unsubscribe':
				break;
			case 'subscribe':
				break;
			case 'scan':
				break;
			case 'click':
				if (strpos($event['key'], 'wechat_menu') !== false) {
					$evarr = explode('#', $event['key']);
					$menu = M('weixin_menu')->where(array('id' => $evarr[2]))->find();
					if ($menu['type'] == 'text') {
						$this->wechat->text($menu['content'])->reply();
					}
					if ($menu['type'] == 'keys') {
						$event['key'] = $menu['content'];
					}
				}
				$map['eventkey'] = $event['key'];
				break;
			case 'location':
				$location = $this->wechat->getRevEventGeo();
				break;
			case 'scancode_push':
				$scan = $this->wechat->getRevScanInfo();
				break;
			case 'scancode_waitmsg':
				$scan = $this->wechat->getRevScanInfo();
				$this->wechat->text("你扫码我知道了")->reply();
				break;
			case 'pic_sysphoto':
				$pic = $this->wechat->getRevSendPicsInfo();
				break;
			case 'pic_photo_or_album':
				$pic = $this->wechat->getRevSendPicsInfo();
				break;
			case 'pic_weixin':
				$pic = $this->wechat->getRevSendPicsInfo();
				break;
			case 'location_select':
				$loc = $this->wechat->getRevSendGeoInfo();
				break;
			default:
				break;
		}
		$reply = M('weixin_reply')->where($map)->find();
		$this->auto_reply($reply);
		$this->eventDeal($event);
	}
	private function auto_reply($reply)
	{
		if ($reply) {
			switch (intval($reply['reply_style'])) {
				case 1:
					$this->wechat->text(htmlspecialchars_decode($reply['text']))->reply();
					break;
				case 2:
					$msg[0]['Title'] = htmlspecialchars_decode($reply['title']);
					$msg[0]['Description'] = htmlspecialchars_decode($reply['description']);
					$msg[0]['PicUrl'] = $reply['picurl'];
					$msg[0]['Url'] = $reply['url'];
					$this->wechat->news($msg)->reply();
					break;
				case 3:
					$this->wechat->image($reply['media_id'])->reply();
					break;
				case 4:
					$this->wechat->voice($reply['media_id'])->reply();
					break;
				case 5:
					$this->wechat->video($reply['media_id'], htmlspecialchars_decode($reply['title']), htmlspecialchars_decode($reply['description']))->reply();
					break;
				case 6:
					$this->wechat->music(htmlspecialchars_decode($reply['title']), htmlspecialchars_decode($reply['description']), $reply['musicurl'], $reply['musicurl'], $reply['media_id'])->reply();
					break;
				case 7:
					$this->wechat->transfer_customer_service()->reply();
					break;
				default:
					break;
			}
		}
	}
	private function eventDeal($event)
	{
		switch (strtolower($event['event'])) {
			case 'unsubscribe':
				if ($user = M('customer')->where(array("wx_openid" => $this->openid, 'weixin_appid' => $this->wxconfig['appid'], 'is_del' => 0))->find()) {
					M('customer')->where(array("id" => $user['id']))->setField(array('is_subscribe' => 0));
					Createlog::customerLog($user['id'], '用户关注公众号', '系统');
				}
				break;
			case 'subscribe':
				if ($user = M('customer')->where(array("wx_openid" => $this->openid, 'weixin_appid' => $this->wxconfig['appid'], 'is_del' => 0))->find()) {
					M('customer')->where(array("id" => $user['id']))->setField(array('is_subscribe' => 1));
					Createlog::customerLog($user['id'], '用户关注公众号', '系统');
				} else {
					$key = $this->wechat->getRevSceneId();
					$res = $this->wechat->getUserInfo($this->openid);
					if ($res['errno'] != 0) {
						return;
					}
					$userinfo = $res['data'];
					if ($key) {
						$res = Userlogin::wxh5_user_reg($userinfo, $key, $this->wxconfig['appid'], Client::CLIENT_WX);
						$res['errno'] == 0 && M('customer')->where(array("id" => $res['data']['id']))->setField(array('is_subscribe' => 1));
					}
				}
				break;
			case 'scan':
				break;
			case 'click':
				break;
			case 'view':
				break;
			case 'location':
				$location = $this->wechat->getRevEventGeo();
				break;
			case 'scancode_push':
				$scan = $this->wechat->getRevScanInfo();
				break;
			case 'scancode_waitmsg':
				$scan = $this->wechat->getRevScanInfo();
				break;
			case 'pic_sysphoto':
				$pic = $this->wechat->getRevSendPicsInfo();
				break;
			case 'pic_photo_or_album':
				$pic = $this->wechat->getRevSendPicsInfo();
				break;
			case 'pic_weixin':
				$pic = $this->wechat->getRevSendPicsInfo();
				break;
			case 'location_select':
				$loc = $this->wechat->getRevSendGeoInfo();
				break;
			default:
				break;
		}
	}
	private function add_weixin_log($openid, $msg_type, $event, $text)
	{
		M('weixin_log')->insertGetId(array('create_time' => time(), 'openid' => $openid, 'msg_type' => $msg_type, 'event' => $event, 'text' => $text, 'weixin_appid' => $this->wxconfig['appid']));
	}
	private function writelog($text)
	{
		($myfile = fopen("wxapi.txt", "a")) || die("Unable to open file!");
		fwrite($myfile, '***' . time_format(time()) . "***\r\n" . '---------start---------' . "\r\n" . $text . "\r\n---------end---------\r\n\r\n");
		fclose($myfile);
	}
}