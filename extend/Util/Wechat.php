<?php

//decode by http://chiran.taobao.com/
namespace Util;

use app\common\library\Email;
class Wechat
{
	const MSGTYPE_TEXT = "text";
	const MSGTYPE_IMAGE = "image";
	const MSGTYPE_LOCATION = "location";
	const MSGTYPE_LINK = "link";
	const MSGTYPE_EVENT = "event";
	const MSGTYPE_MUSIC = "music";
	const MSGTYPE_NEWS = "news";
	const MSGTYPE_VOICE = "voice";
	const MSGTYPE_VIDEO = "video";
	const API_URL_PREFIX = "https://api.weixin.qq.com/cgi-bin";
	const AUTH_URL = "/token?grant_type=client_credential&";
	const MENU_CREATE_URL = "/menu/create?";
	const MENU_GET_URL = "/menu/get?";
	const MENU_DELETE_URL = "/menu/delete?";
	const MEDIA_GET_URL = "/media/get?";
	const CALLBACKSERVER_GET_URL = "/getcallbackip?";
	const QRCODE_CREATE_URL = "/qrcode/create?";
	const QR_SCENE = 0;
	const QR_LIMIT_SCENE = 1;
	const QRCODE_IMG_URL = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=";
	const SHORT_URL = "/shorturl?";
	const USER_GET_URL = "/user/get?";
	const USER_INFO_URL = "/user/info?";
	const USER_UPDATEREMARK_URL = "/user/info/updateremark?";
	const GROUP_GET_URL = "/groups/get?";
	const USER_GROUP_URL = "/groups/getid?";
	const GROUP_CREATE_URL = "/groups/create?";
	const GROUP_UPDATE_URL = "/groups/update?";
	const GROUP_MEMBER_UPDATE_URL = "/groups/members/update?";
	const CUSTOM_SEND_URL = "/message/custom/send?";
	const MEDIA_UPLOADNEWS_URL = "/media/uploadnews?";
	const MEDIA_GET_FOREVER_LIST = "/material/batchget_material?";
	const MEDIA_GET_FOREVER_ONE = "/material/get_material?";
	const MEDIA_ADD_FOREVER = "/material/add_news?";
	const MEDIA_RDIT_FOREVER = "/material/update_news?";
	const MEDIA_DELETE_FOREVER = "/material/del_material?";
	const MEDIA_ADD_PIC = "/media/uploadimg?";
	const MASS_SEND_URL = "/message/mass/send?";
	const TEMPLATE_SEND_URL = "/message/template/send?";
	const TEMPLATE_ALL_URL = "/template/get_all_private_template?";
	const MASS_SEND_GROUP_URL = "/message/mass/sendall?";
	const MASS_DELETE_URL = "/message/mass/delete?";
	const UPLOAD_MEDIA_URL = "http://file.api.weixin.qq.com/cgi-bin";
	const MEDIA_UPLOAD = "/media/upload?";
	const OAUTH_PREFIX = "https://open.weixin.qq.com/connect/oauth2";
	const OAUTH_AUTHORIZE_URL = "/authorize?";
	const OAUTH_TOKEN_PREFIX = "https://api.weixin.qq.com/sns/oauth2";
	const OAUTH_TOKEN_URL = "/access_token?";
	const OAUTH_REFRESH_URL = "/refresh_token?";
	const OAUTH_USERINFO_URL = "https://api.weixin.qq.com/sns/userinfo?";
	const OAUTH_AUTH_URL = "https://api.weixin.qq.com/sns/auth?";
	const PAY_DELIVERNOTIFY = "https://api.weixin.qq.com/pay/delivernotify?";
	const PAY_ORDERQUERY = "https://api.weixin.qq.com/pay/orderquery?";
	const CUSTOM_SERVICE_GET_RECORD = "/customservice/getrecord?";
	const CUSTOM_SERVICE_GET_KFLIST = "/customservice/getkflist?";
	const CUSTOM_SERVICE_GET_ONLINEKFLIST = "/customservice/getkflist?";
	const SEMANTIC_API_URL = "https://api.weixin.qq.com/semantic/semproxy/search?";
	const JS_API_TIKET = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?";
	const USER_SUMMARY = "https://api.weixin.qq.com/datacube/getusersummary?";
	const USER_CUMULATE = "https://api.weixin.qq.com/datacube/getusercumulate?";
	private $token;
	private $encodingAesKey;
	private $encrypt_type;
	private $appid;
	private $appsecret;
	private $access_token;
	private $user_token;
	private $partnerid;
	private $partnerkey;
	private $paysignkey;
	private $postxml;
	private $_msg;
	private $_funcflag = false;
	private $_receive;
	private $_text_filter = true;
	public $debug = false;
	public $errCode = 40001;
	public $errMsg = "no access";
	private $_logcallback;
	private $wxtype;
	private $wxHighService;
	private $wxName;
	public function __construct($options)
	{
		$this->token = isset($options['token']) ? $options['token'] : '';
		$this->encodingAesKey = isset($options['encodingaeskey']) ? $options['encodingaeskey'] : '';
		$this->appid = isset($options['appid']) ? $options['appid'] : '';
		$this->appsecret = isset($options['appsecret']) ? $options['appsecret'] : '';
		$this->partnerid = isset($options['partnerid']) ? $options['partnerid'] : '';
		$this->partnerkey = isset($options['partnerkey']) ? $options['partnerkey'] : '';
		$this->paysignkey = isset($options['paysignkey']) ? $options['paysignkey'] : '';
		$this->debug = isset($options['debug']) ? $options['debug'] : false;
		$this->_logcallback = isset($options['logcallback']) ? $options['logcallback'] : false;
		$this->wxtype = isset($options['wxtype']) ? $options['wxtype'] : '';
		$this->wxHighService = isset($options['wxHighService']) ? $options['wxHighService'] : '';
		$this->wxName = isset($options['wxName']) ? $options['wxName'] : '';
		$this->get_access_token();
	}
	public function get_access_token()
	{
		$token = DataCache::get('token_' . $this->appid);
		if (!$token) {
			$ret = $this->checkAuth();
			if ($ret['errno'] != 0) {
				return $ret;
			}
			$token = $ret['data'];
		}
		$this->access_token = $token;
		return rjson(0, 'ok', $token);
	}
	public function reset_access_token()
	{
		return $this->checkAuth();
	}
	private function checkSignature($str = '')
	{
		$signature = isset($_GET["signature"]) ? $_GET["signature"] : '';
		$signature = isset($_GET["msg_signature"]) ? $_GET["msg_signature"] : $signature;
		$timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
		$nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : '';
		$token = $this->token;
		$tmpArr = array($token, $timestamp, $nonce, $str);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	public function get_jspai_tiket()
	{
		$ticket = DataCache::get('ticket_' . $this->appid);
		if ($ticket) {
			return rjson(0, 'ok', $ticket);
		} else {
			$url = self::JS_API_TIKET . "access_token=" . $this->access_token . "&type=jsapi";
			$scontent = $this->http_get($url);
			$ret = $this->resultRjson($scontent);
			if ($ret['errno'] == 0) {
				DataCache::set($ret['data']['ticket'], 'ticket_' . $this->appid, 7000);
				return rjson(0, 'ok', $ret['data']['ticket']);
			} else {
				return $ret;
			}
		}
	}
	public function get_user_summary($begin_date, $end_date)
	{
		$data = array('begin_date' => $begin_date, 'end_date' => $end_date);
		$url = self::USER_SUMMARY . "access_token=" . $this->access_token;
		$scontent = $this->http_post($url, self::json_encode($data));
		$ret = $this->resultRjson($scontent);
		return $ret;
	}
	public function valid($return = false)
	{
		$encryptStr = "";
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$postStr = file_get_contents("php://input");
			$array = (array) simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->encrypt_type = isset($_GET["encrypt_type"]) ? $_GET["encrypt_type"] : '';
			if ($this->encrypt_type == 'aes') {
				die('decrypt error!');
				$this->log($postStr);
				$encryptStr = $array['Encrypt'];
				$pc = new Prpcrypt($this->encodingAesKey);
				$array = $pc->decrypt($encryptStr, $this->appid);
				if (!isset($array[0]) || $array[0] != 0) {
					if (!$return) {
						die('decrypt error!');
					} else {
						return false;
					}
				}
				$this->postxml = $array[1];
				if (!$this->appid) {
					$this->appid = $array[2];
				}
			} else {
				$this->postxml = $postStr;
			}
		} else {
			if (isset($_GET["echostr"])) {
				$echoStr = $_GET["echostr"];
				if ($return) {
					if ($this->checkSignature()) {
						return $echoStr;
					} else {
						return false;
					}
				} else {
					if ($this->checkSignature()) {
						die($echoStr);
					} else {
						die('no access');
					}
				}
			}
		}
		if (!$this->checkSignature($encryptStr)) {
			if ($return) {
				return false;
			} else {
				die('no access');
			}
		}
		return true;
	}
	public function Message($msg = '', $append = false)
	{
		if (is_null($msg)) {
			$this->_msg = array();
		} else {
			if (is_array($msg)) {
				if ($append) {
					$this->_msg = array_merge($this->_msg, $msg);
				} else {
					$this->_msg = $msg;
				}
				return $this->_msg;
			} else {
				return $this->_msg;
			}
		}
	}
	public function setFuncFlag($flag)
	{
		$this->_funcflag = $flag;
		return $this;
	}
	private function log($log)
	{
		if ($this->debug && function_exists($this->_logcallback)) {
			if (is_array($log)) {
				$log = print_r($log, true);
			}
			return call_user_func($this->_logcallback, $log);
		}
	}
	public function getRev()
	{
		if ($this->_receive) {
			return $this;
		}
		$postStr = !empty($this->postxml) ? $this->postxml : file_get_contents("php://input");
		$this->log($postStr);
		if (!empty($postStr)) {
			$this->_receive = (array) simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		}
		return $this;
	}
	public function getRevData()
	{
		return $this->_receive;
	}
	public function getRevFrom()
	{
		if (isset($this->_receive['FromUserName'])) {
			return $this->_receive['FromUserName'];
		} else {
			return false;
		}
	}
	public function getRevTo()
	{
		if (isset($this->_receive['ToUserName'])) {
			return $this->_receive['ToUserName'];
		} else {
			return false;
		}
	}
	public function getRevType()
	{
		if (isset($this->_receive['MsgType'])) {
			return $this->_receive['MsgType'];
		} else {
			return false;
		}
	}
	public function getRevID()
	{
		if (isset($this->_receive['MsgId'])) {
			return $this->_receive['MsgId'];
		} else {
			return false;
		}
	}
	public function getRevCtime()
	{
		if (isset($this->_receive['CreateTime'])) {
			return $this->_receive['CreateTime'];
		} else {
			return false;
		}
	}
	public function getRevContent()
	{
		if (isset($this->_receive['Content'])) {
			return $this->_receive['Content'];
		} elseif (isset($this->_receive['Recognition'])) {
			return $this->_receive['Recognition'];
		} else {
			return false;
		}
	}
	public function getRevPic()
	{
		if (isset($this->_receive['PicUrl'])) {
			return array('mediaid' => $this->_receive['MediaId'], 'picurl' => (string) $this->_receive['PicUrl']);
		} else {
			return false;
		}
	}
	public function getRevLink()
	{
		if (isset($this->_receive['Url'])) {
			return array('url' => $this->_receive['Url'], 'title' => $this->_receive['Title'], 'description' => $this->_receive['Description']);
		} else {
			return false;
		}
	}
	public function getRevGeo()
	{
		if (isset($this->_receive['Location_X'])) {
			return array('x' => $this->_receive['Location_X'], 'y' => $this->_receive['Location_Y'], 'scale' => $this->_receive['Scale'], 'label' => $this->_receive['Label']);
		} else {
			return false;
		}
	}
	public function getRevEventGeo()
	{
		if (isset($this->_receive['Latitude'])) {
			return array('x' => $this->_receive['Latitude'], 'y' => $this->_receive['Longitude'], 'precision' => $this->_receive['Precision']);
		} else {
			return false;
		}
	}
	public function getRevEvent()
	{
		if (isset($this->_receive['Event'])) {
			$array['event'] = $this->_receive['Event'];
		}
		if (isset($this->_receive['EventKey'])) {
			$array['key'] = $this->_receive['EventKey'];
		}
		if (isset($array) && count($array) > 0) {
			return $array;
		} else {
			return false;
		}
	}
	public function getRevScanInfo()
	{
		if (isset($this->_receive['ScanCodeInfo'])) {
			if (!is_array($this->_receive['ScanCodeInfo'])) {
				$array = (array) $this->_receive['ScanCodeInfo'];
				$this->_receive['ScanCodeInfo'] = $array;
			} else {
				$array = $this->_receive['ScanResult'];
			}
		}
		if (isset($array) && count($array) > 0) {
			return $array;
		} else {
			return false;
		}
	}
	public function getRevSendPicsInfo()
	{
		if (isset($this->_receive['SendPicsInfo'])) {
			if (!is_array($this->_receive['SendPicsInfo'])) {
				$array = (array) $this->_receive['SendPicsInfo'];
				if (isset($array['PicList'])) {
					$array['PicList'] = (array) $array['PicList'];
					$item = $array['PicList']['item'];
					$array['PicList']['item'] = array();
					foreach ($item as $key => $value) {
						$array['PicList']['item'][$key] = (array) $value;
					}
				}
				$this->_receive['SendPicsInfo'] = $array;
			} else {
				$array = $this->_receive['SendPicsInfo'];
			}
		}
		if (isset($array) && count($array) > 0) {
			return $array;
		} else {
			return false;
		}
	}
	public function getRevSendGeoInfo()
	{
		if (isset($this->_receive['SendLocationInfo'])) {
			if (!is_array($this->_receive['SendLocationInfo'])) {
				$array = (array) $this->_receive['SendLocationInfo'];
				if (empty($array['Poiname'])) {
					$array['Poiname'] = "";
				}
				if (empty($array['Label'])) {
					$array['Label'] = "";
				}
				$this->_receive['SendLocationInfo'] = $array;
			} else {
				$array = $this->_receive['SendLocationInfo'];
			}
		}
		if (isset($array) && count($array) > 0) {
			return $array;
		} else {
			return false;
		}
	}
	public function getRevVoice()
	{
		if (isset($this->_receive['MediaId'])) {
			return array('mediaid' => $this->_receive['MediaId'], 'format' => $this->_receive['Format']);
		} else {
			return false;
		}
	}
	public function getRevVideo()
	{
		if (isset($this->_receive['MediaId'])) {
			return array('mediaid' => $this->_receive['MediaId'], 'thumbmediaid' => $this->_receive['ThumbMediaId']);
		} else {
			return false;
		}
	}
	public function getRevTicket()
	{
		if (isset($this->_receive['Ticket'])) {
			return $this->_receive['Ticket'];
		} else {
			return false;
		}
	}
	public function getRevSceneId()
	{
		if (isset($this->_receive['EventKey'])) {
			return str_replace('qrscene_', '', $this->_receive['EventKey']);
		} else {
			return false;
		}
	}
	public function getRevTplMsgID()
	{
		if (isset($this->_receive['MsgID'])) {
			return $this->_receive['MsgID'];
		} else {
			return false;
		}
	}
	public function getRevStatus()
	{
		if (isset($this->_receive['Status'])) {
			return $this->_receive['Status'];
		} else {
			return false;
		}
	}
	public static function xmlSafeStr($str)
	{
		return '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $str) . ']]>';
	}
	public static function data_to_xml($data)
	{
		$xml = '';
		foreach ($data as $key => $val) {
			is_numeric($key) && ($key = "item id=\"" . $key . "\"");
			$xml .= "<" . $key . ">";
			$xml .= is_array($val) || is_object($val) ? self::data_to_xml($val) : self::xmlSafeStr($val);
			list($key, ) = explode(' ', $key);
			$xml .= "</" . $key . ">";
		}
		return $xml;
	}
	public function xml_encode($data, $root = 'xml', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
	{
		if (is_array($attr)) {
			$_attr = array();
			foreach ($attr as $key => $value) {
				$_attr[] = $key . "=\"" . $value . "\"";
			}
			$attr = implode(' ', $_attr);
		}
		$attr = trim($attr);
		$attr = empty($attr) ? '' : " " . $attr;
		$xml = "<" . $root . $attr . ">";
		$xml .= self::data_to_xml($data, $item, $id);
		$xml .= "</" . $root . ">";
		return $xml;
	}
	private function _auto_text_filter($text)
	{
		if (!$this->_text_filter) {
			return $text;
		}
		return str_replace("\r\n", "\r\n", $text);
	}
	public function text($text = '')
	{
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$msg = array('ToUserName' => $this->getRevFrom(), 'FromUserName' => $this->getRevTo(), 'MsgType' => self::MSGTYPE_TEXT, 'Content' => $this->_auto_text_filter($text), 'CreateTime' => time(), 'FuncFlag' => $FuncFlag);
		$this->Message($msg);
		return $this;
	}
	public function image($mediaid = '')
	{
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$msg = array('ToUserName' => $this->getRevFrom(), 'FromUserName' => $this->getRevTo(), 'MsgType' => self::MSGTYPE_IMAGE, 'Image' => array('MediaId' => $mediaid), 'CreateTime' => time(), 'FuncFlag' => $FuncFlag);
		$this->Message($msg);
		return $this;
	}
	public function voice($mediaid = '')
	{
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$msg = array('ToUserName' => $this->getRevFrom(), 'FromUserName' => $this->getRevTo(), 'MsgType' => self::MSGTYPE_VOICE, 'Voice' => array('MediaId' => $mediaid), 'CreateTime' => time(), 'FuncFlag' => $FuncFlag);
		$this->Message($msg);
		return $this;
	}
	public function video($mediaid = '', $title = '', $description = '')
	{
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$msg = array('ToUserName' => $this->getRevFrom(), 'FromUserName' => $this->getRevTo(), 'MsgType' => self::MSGTYPE_VIDEO, 'Video' => array('MediaId' => $mediaid, 'Title' => $title, 'Description' => $description), 'CreateTime' => time(), 'FuncFlag' => $FuncFlag);
		$this->Message($msg);
		return $this;
	}
	public function music($title, $desc, $musicurl, $hgmusicurl, $thumbmediaId = "")
	{
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$msg = array('ToUserName' => $this->getRevFrom(), 'FromUserName' => $this->getRevTo(), 'CreateTime' => time(), 'MsgType' => self::MSGTYPE_MUSIC, 'Music' => array('Title' => $title, 'Description' => $desc, 'MusicUrl' => $musicurl, 'HQMusicUrl' => $hgmusicurl, 'ThumbMediaId' => $thumbmediaId), 'FuncFlag' => $FuncFlag);
		$this->Message($msg);
		return $this;
	}
	public function news($newsData = array())
	{
		$FuncFlag = $this->_funcflag ? 1 : 0;
		$count = count($newsData);
		$msg = array('ToUserName' => $this->getRevFrom(), 'FromUserName' => $this->getRevTo(), 'MsgType' => self::MSGTYPE_NEWS, 'CreateTime' => time(), 'ArticleCount' => $count, 'Articles' => $newsData, 'FuncFlag' => $FuncFlag);
		$this->Message($msg);
		return $this;
	}
	public function reply($msg = array(), $return = false)
	{
		if (empty($msg)) {
			$msg = $this->_msg;
		}
		$xmldata = $this->xml_encode($msg);
		$this->log($xmldata);
		if ($this->encrypt_type == 'aes') {
			$pc = new Prpcrypt($this->encodingAesKey);
			$array = $pc->encrypt($xmldata, $this->appid);
			$ret = $array[0];
			if ($ret != 0) {
				$this->log('encrypt err!');
				return false;
			}
			$timestamp = time();
			$nonce = rand(77, 999) * rand(605, 888) * rand(11, 99);
			$encrypt = $array[1];
			$tmpArr = array($this->token, $timestamp, $nonce, $encrypt);
			sort($tmpArr, SORT_STRING);
			$signature = implode($tmpArr);
			$signature = sha1($signature);
			$xmldata = $this->generate($encrypt, $signature, $timestamp, $nonce);
			$this->log($xmldata);
		}
		if ($return) {
			return $xmldata;
		} else {
			echo $xmldata;
		}
	}
	private function generate($encrypt, $signature, $timestamp, $nonce)
	{
		$format = "<xml>\r\n\t\t\t<Encrypt><![CDATA[%s]]></Encrypt>\r\n\t\t\t<MsgSignature><![CDATA[%s]]></MsgSignature>\r\n\t\t\t<TimeStamp>%s</TimeStamp>\r\n\t\t\t<Nonce><![CDATA[%s]]></Nonce>\r\n\t\t\t</xml>";
		return sprintf($format, $encrypt, $signature, $timestamp, $nonce);
	}
	private function http_get($url)
	{
		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return json_encode(array('errcode' => 1, 'errmsg' => '请求微信接口错误', 'data' => $url));
		}
	}
	private function http_post($url, $param, $post_file = false)
	{
		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
		}
		if (is_string($param) || $post_file) {
			$strPOST = $param;
		} else {
			$aPOST = array();
			foreach ($param as $key => $val) {
				$aPOST[] = $key . "=" . urlencode($val);
			}
			$strPOST = join("&", $aPOST);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200) {
			return $sContent;
		} else {
			return json_encode(array('errcode' => 1, 'errmsg' => '请求微信接口错误', 'data' => $url));
		}
	}
	public function resultRjson($sContent)
	{
		$json = json_decode($sContent, true);
		if (!isset($json['errcode'])) {
			return rjson(0, 'ok', $json);
		}
		if (isset($json['errcode']) && $json['errcode'] == 40001) {
			$this->reset_access_token();
		}
		return rjson($json['errcode'], $json['errmsg'], $json);
	}
	private function handleGetCurrentUrl()
	{
		if (!IS_CLI) {
			$http_type = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? 'https://' : 'http://';
			$domain = $_SERVER['HTTP_HOST'];
			$requestUri = $_SERVER['REQUEST_URI'];
			$currentUrl = $http_type . $domain . $requestUri;
			return $currentUrl;
		} else {
			return __DIR__;
		}
	}
	public function checkAuth()
	{
		$result = $this->http_get(self::API_URL_PREFIX . self::AUTH_URL . 'appid=' . $this->appid . '&secret=' . $this->appsecret);
		$ret = $this->resultRjson($result);
		if ($ret['errno'] != 0) {
			return $ret;
		}
		$this->access_token = $ret['data']['access_token'];
		DataCache::set($ret['data']['access_token'], 'token_' . $this->appid, intval($ret['data']['expires_in']));
		return rjson(0, 'ok', $ret['data']['access_token']);
	}
	static function json_encode($arr)
	{
		$parts = array();
		$is_list = false;
		$keys = array_keys($arr);
		$max_length = count($arr) - 1;
		if ($keys[0] === 0 && $keys[$max_length] === $max_length) {
			$is_list = true;
			for ($i = 0; $i < count($keys); $i++) {
				if ($i != $keys[$i]) {
					$is_list = false;
					break;
				}
			}
		}
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				if ($is_list) {
					$parts[] = self::json_encode($value);
				} else {
					$parts[] = '"' . $key . '":' . self::json_encode($value);
				}
			} else {
				$str = '';
				if (!$is_list) {
					$str = '"' . $key . '":';
				}
				if (!is_string($value) && is_numeric($value) && $value < 2000000000) {
					$str .= $value;
				} else {
					if ($value === false) {
						$str .= 'false';
					} elseif ($value === true) {
						$str .= 'true';
					} else {
						$str .= '"' . addslashes($value) . '"';
					}
				}
				$parts[] = $str;
			}
		}
		$json = implode(',', $parts);
		if ($is_list) {
			return '[' . $json . ']';
		}
		return '{' . $json . '}';
	}
	public function getServerIp()
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::CALLBACKSERVER_GET_URL . 'access_token=' . $this->access_token);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function createMenu($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MENU_CREATE_URL . 'access_token=' . $this->access_token, $data);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getMenu()
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::MENU_GET_URL . 'access_token=' . $this->access_token);
		if ($result) {
			$json = json_decode($result, true);
			if (!$json || isset($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
				return false;
			}
			return $json;
		}
		return false;
	}
	public function deleteMenu()
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::MENU_DELETE_URL . 'access_token=' . $this->access_token);
		if ($result) {
			$json = json_decode($result, true);
			if (!$json || !empty($json['errcode'])) {
				$this->errCode = $json['errcode'];
				$this->errMsg = $json['errmsg'];
				return false;
			}
			return true;
		}
		return false;
	}
	public function uploadMedia($data, $type)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::UPLOAD_MEDIA_URL . self::MEDIA_UPLOAD . 'access_token=' . $this->access_token . '&type=' . $type, $data, true);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getMedia($media_id)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::UPLOAD_MEDIA_URL . self::MEDIA_GET_URL . 'access_token=' . $this->access_token . '&media_id=' . $media_id);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getForeverMaterialList($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MEDIA_GET_FOREVER_LIST . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getForeverMaterialById($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MEDIA_GET_FOREVER_ONE . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function addForeverMaterial($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MEDIA_ADD_FOREVER . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function editForeverMaterial($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MEDIA_RDIT_FOREVER . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function delForeverMaterial($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MEDIA_DELETE_FOREVER . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function sendMassMessage($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MASS_SEND_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function sendGroupMassMessage($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MASS_SEND_GROUP_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function deleteMassMessage($msg_id)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::MASS_DELETE_URL . 'access_token=' . $this->access_token, self::json_encode(array('msg_id' => $msg_id)));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getQRCode($scene_id, $type = 'QR_SCENE', $expire = 1800)
	{
		if ($type == 'QR_LIMIT_STR_SCENE') {
			$data = array('action_name' => $type, 'expire_seconds' => $expire, 'action_info' => array('scene' => array('scene_str' => $scene_id)));
		} else {
			$data = array('action_name' => $type, 'expire_seconds' => $expire, 'action_info' => array('scene' => array('scene_id' => $scene_id)));
		}
		if ($type != 'QR_SCENE') {
			unset($data['expire_seconds']);
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::QRCODE_CREATE_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getQRUrl($ticket)
	{
		return self::QRCODE_IMG_URL . $ticket;
	}
	public function getShortUrl($long_url)
	{
		if (!$this->access_token) {
			return false;
		}
		$data = array('action' => 'long2short', 'long_url' => $long_url);
		$result = $this->http_post(self::API_URL_PREFIX . self::SHORT_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getUserList($next_openid = '')
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::USER_GET_URL . 'access_token=' . $this->access_token . '&next_openid=' . $next_openid);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getUserInfo($openid)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::USER_INFO_URL . 'access_token=' . $this->access_token . '&openid=' . $openid);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getAccess_token()
	{
		return $this->access_token;
	}
	public function updateUserRemark($openid, $remark)
	{
		if (!$this->access_token) {
			return false;
		}
		$data = array('openid' => $openid, 'remark' => $remark);
		$result = $this->http_post(self::API_URL_PREFIX . self::USER_UPDATEREMARK_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getGroup()
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::GROUP_GET_URL . 'access_token=' . $this->access_token);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getUserGroup($openid)
	{
		if (!$this->access_token) {
			return false;
		}
		$data = array('openid' => $openid);
		$result = $this->http_post(self::API_URL_PREFIX . self::USER_GROUP_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function createGroup($name)
	{
		if (!$this->access_token) {
			return false;
		}
		$data = array('group' => array('name' => $name));
		$result = $this->http_post(self::API_URL_PREFIX . self::GROUP_CREATE_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function updateGroup($groupid, $name)
	{
		if (!$this->access_token) {
			return false;
		}
		$data = array('group' => array('id' => $groupid, 'name' => $name));
		$result = $this->http_post(self::API_URL_PREFIX . self::GROUP_UPDATE_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function updateGroupMembers($groupid, $openid)
	{
		if (!$this->access_token) {
			return false;
		}
		$data = array('openid' => $openid, 'to_groupid' => $groupid);
		$result = $this->http_post(self::API_URL_PREFIX . self::GROUP_MEMBER_UPDATE_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function sendCustomMessage($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::CUSTOM_SEND_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getOauthRedirect($callback, $state = '', $scope = 'snsapi_userinfo')
	{
		return self::OAUTH_PREFIX . self::OAUTH_AUTHORIZE_URL . 'appid=' . $this->appid . '&redirect_uri=' . urlencode($callback) . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
	}
	public function getOauthAccessToken()
	{
		$code = isset($_GET['code']) ? $_GET['code'] : '';
		if (!$code) {
			return rjson(1, 'code不能为空');
		}
		$result = $this->http_get(self::OAUTH_TOKEN_PREFIX . self::OAUTH_TOKEN_URL . 'appid=' . $this->appid . '&secret=' . $this->appsecret . '&code=' . $code . '&grant_type=authorization_code');
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getOauthRefreshToken($refresh_token)
	{
		$result = $this->http_get(self::OAUTH_TOKEN_PREFIX . self::OAUTH_REFRESH_URL . 'appid=' . $this->appid . '&grant_type=refresh_token&refresh_token=' . $refresh_token);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getOauthUserinfo($access_token, $openid)
	{
		$result = $this->http_get(self::OAUTH_USERINFO_URL . 'access_token=' . $access_token . '&openid=' . $openid);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getOauthAuth($access_token, $openid)
	{
		$result = $this->http_get(self::OAUTH_AUTH_URL . 'access_token=' . $access_token . '&openid=' . $openid);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getSignature($arrdata, $method = "sha1")
	{
		if (!function_exists($method)) {
			return false;
		}
		ksort($arrdata);
		$paramstring = "";
		foreach ($arrdata as $key => $value) {
			if (strlen($paramstring) == 0) {
				$paramstring .= $key . "=" . $value;
			} else {
				$paramstring .= "&" . $key . "=" . $value;
			}
		}
		$paySign = $method($paramstring);
		return $paySign;
	}
	public function generateNonceStr($length = 16)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		return $str;
	}
	public function createNativeUrl($productid)
	{
		$nativeObj["appid"] = $this->appid;
		$nativeObj["appkey"] = $this->paysignkey;
		$nativeObj["productid"] = urlencode($productid);
		$nativeObj["timestamp"] = time();
		$nativeObj["noncestr"] = $this->generateNonceStr();
		$nativeObj["sign"] = $this->getSignature($nativeObj);
		unset($nativeObj["appkey"]);
		$bizString = "";
		foreach ($nativeObj as $key => $value) {
			if (strlen($bizString) == 0) {
				$bizString .= $key . "=" . $value;
			} else {
				$bizString .= "&" . $key . "=" . $value;
			}
		}
		return "weixin://wxpay/bizpayurl?" . $bizString;
	}
	public function createPackage($out_trade_no, $body, $total_fee, $notify_url, $spbill_create_ip, $fee_type = 1, $bank_type = "WX", $input_charset = "UTF-8", $time_start = "", $time_expire = "", $transport_fee = "", $product_fee = "", $goods_tag = "", $attach = "")
	{
		$arrdata = array("bank_type" => $bank_type, "body" => $body, "partner" => $this->partnerid, "out_trade_no" => $out_trade_no, "total_fee" => $total_fee, "fee_type" => $fee_type, "notify_url" => $notify_url, "spbill_create_ip" => $spbill_create_ip, "input_charset" => $input_charset);
		if ($time_start) {
			$arrdata['time_start'] = $time_start;
		}
		if ($time_expire) {
			$arrdata['time_expire'] = $time_expire;
		}
		if ($transport_fee) {
			$arrdata['transport_fee'] = $transport_fee;
		}
		if ($product_fee) {
			$arrdata['product_fee'] = $product_fee;
		}
		if ($goods_tag) {
			$arrdata['goods_tag'] = $goods_tag;
		}
		if ($attach) {
			$arrdata['attach'] = $attach;
		}
		ksort($arrdata);
		$paramstring = "";
		foreach ($arrdata as $key => $value) {
			if (strlen($paramstring) == 0) {
				$paramstring .= $key . "=" . $value;
			} else {
				$paramstring .= "&" . $key . "=" . $value;
			}
		}
		$stringSignTemp = $paramstring . "&key=" . $this->partnerkey;
		$signValue = strtoupper(md5($stringSignTemp));
		$package = http_build_query($arrdata) . "&sign=" . $signValue;
		return $package;
	}
	public function getPaySign($package, $timeStamp, $nonceStr)
	{
		$arrdata = array("appid" => $this->appid, "timestamp" => $timeStamp, "noncestr" => $nonceStr, "package" => $package, "appkey" => $this->paysignkey);
		$paySign = $this->getSignature($arrdata);
		return $paySign;
	}
	public function checkOrderSignature($orderxml = '')
	{
		if (!$orderxml) {
			$postStr = file_get_contents("php://input");
			if (!empty($postStr)) {
				$orderxml = (array) simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			} else {
				return false;
			}
		}
		$arrdata = array('appid' => $orderxml['AppId'], 'appkey' => $this->paysignkey, 'timestamp' => $orderxml['TimeStamp'], 'noncestr' => $orderxml['NonceStr'], 'openid' => $orderxml['OpenId'], 'issubscribe' => $orderxml['IsSubscribe']);
		$paySign = $this->getSignature($arrdata);
		if ($paySign != $orderxml['AppSignature']) {
			return false;
		}
		return true;
	}
	public function sendPayDeliverNotify($openid, $transid, $out_trade_no, $status = 1, $msg = 'ok')
	{
		if (!$this->access_token) {
			return false;
		}
		$postdata = array("appid" => $this->appid, "appkey" => $this->paysignkey, "openid" => $openid, "transid" => strval($transid), "out_trade_no" => strval($out_trade_no), "deliver_timestamp" => strval(time()), "deliver_status" => strval($status), "deliver_msg" => $msg);
		$postdata['app_signature'] = $this->getSignature($postdata);
		$postdata['sign_method'] = 'sha1';
		unset($postdata['appkey']);
		$result = $this->http_post(self::PAY_DELIVERNOTIFY . 'access_token=' . $this->access_token, self::json_encode($postdata));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getPayOrder($out_trade_no)
	{
		if (!$this->access_token) {
			return false;
		}
		$sign = strtoupper(md5("out_trade_no=" . $out_trade_no . "&partner=" . $this->partnerid . "&key=" . $this->partnerkey));
		$postdata = array("appid" => $this->appid, "appkey" => $this->paysignkey, "package" => "out_trade_no=" . $out_trade_no . "&partner=" . $this->partnerid . "&sign=" . $sign, "timestamp" => strval(time()));
		$postdata['app_signature'] = $this->getSignature($postdata);
		$postdata['sign_method'] = 'sha1';
		unset($postdata['appkey']);
		$result = $this->http_post(self::PAY_ORDERQUERY . 'access_token=' . $this->access_token, self::json_encode($postdata));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getAddrSign($url, $timeStamp, $nonceStr, $user_token = '')
	{
		if (!$user_token) {
			$user_token = $this->user_token;
		}
		if (!$user_token) {
			$this->errMsg = 'no user access token found!';
			return false;
		}
		$url = htmlspecialchars_decode($url);
		$arrdata = array('appid' => $this->appid, 'url' => $url, 'timestamp' => strval($timeStamp), 'noncestr' => $nonceStr, 'accesstoken' => $user_token);
		return $this->getSignature($arrdata);
	}
	public function sendTemplateMessage($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::TEMPLATE_SEND_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getAllTemplate()
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::TEMPLATE_ALL_URL . 'access_token=' . $this->access_token);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function apiAddTemplate($template_id_short)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . '/template/api_add_template?' . 'access_token=' . $this->access_token, self::json_encode(array('template_id_short' => $template_id_short)));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getCustomServiceMessage($data)
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_post(self::API_URL_PREFIX . self::CUSTOM_SERVICE_GET_RECORD . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function transfer_customer_service($customer_account = '')
	{
		$msg = array('ToUserName' => $this->getRevFrom(), 'FromUserName' => $this->getRevTo(), 'CreateTime' => time(), 'MsgType' => 'transfer_customer_service');
		if (!$customer_account) {
			$msg['TransInfo'] = array('KfAccount' => $customer_account);
		}
		$this->Message($msg);
		return $this;
	}
	public function getCustomServiceKFlist()
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::CUSTOM_SERVICE_GET_KFLIST . 'access_token=' . $this->access_token);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function getCustomServiceOnlineKFlist()
	{
		if (!$this->access_token) {
			return false;
		}
		$result = $this->http_get(self::API_URL_PREFIX . self::CUSTOM_SERVICE_GET_ONLINEKFLIST . 'access_token=' . $this->access_token);
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function querySemantic($uid, $query, $category, $latitude = 0, $longitude = 0, $city = "", $region = "")
	{
		if (!$this->access_token) {
			return false;
		}
		$data = array('query' => $query, 'category' => $category, 'appid' => $this->appid, 'uid' => '');
		if ($latitude) {
			$data['latitude'] = $latitude;
			$data['longitude'] = $longitude;
		} else {
			if ($city) {
				$data['city'] = $city;
			} else {
				if ($region) {
					$data['region'] = $region;
				}
			}
		}
		$result = $this->http_post(self::SEMANTIC_API_URL . 'access_token=' . $this->access_token, self::json_encode($data));
		$ret = $this->resultRjson($result);
		return $ret;
	}
	public function arr2tree($list, $id = 'id', $pid = 'pid', $son = 'sub')
	{
		list($tree, $map) = array(array(), array());
		foreach ($list as $item) {
			$map[$item[$id]] = $item;
		}
		foreach ($list as $item) {
			if (isset($item[$pid]) && isset($map[$item[$pid]])) {
				$map[$item[$pid]][$son][] =& $map[$item[$id]];
			} else {
				$tree[] =& $map[$item[$id]];
			}
		}
		unset($map);
		return $tree;
	}
}
class PKCS7Encoder
{
	public static $block_size = 32;
	function encode($text)
	{
		$block_size = PKCS7Encoder::$block_size;
		$text_length = strlen($text);
		$amount_to_pad = PKCS7Encoder::$block_size - $text_length % PKCS7Encoder::$block_size;
		if ($amount_to_pad == 0) {
			$amount_to_pad = PKCS7Encoder::block_size;
		}
		$pad_chr = chr($amount_to_pad);
		$tmp = "";
		for ($index = 0; $index < $amount_to_pad; $index++) {
			$tmp .= $pad_chr;
		}
		return $text . $tmp;
	}
	function decode($text)
	{
		$pad = ord(substr($text, -1));
		if ($pad < 1 || $pad > PKCS7Encoder::$block_size) {
			$pad = 0;
		}
		return substr($text, 0, strlen($text) - $pad);
	}
}
class Prpcrypt
{
	public $key;
	function Prpcrypt($k)
	{
		$this->key = base64_decode($k . "=");
	}
	public function encrypt($text, $appid)
	{
		try {
			$random = $this->getRandomStr();
			$text = $random . pack("N", strlen($text)) . $text . $appid;
			$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$iv = substr($this->key, 0, 16);
			$pkc_encoder = new PKCS7Encoder();
			$text = $pkc_encoder->encode($text);
			mcrypt_generic_init($module, $this->key, $iv);
			$encrypted = mcrypt_generic($module, $text);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
			return array(ErrorCode::$OK, base64_encode($encrypted));
		} catch (Exception $e) {
			return array(ErrorCode::$EncryptAESError, null);
		}
	}
	public function decrypt($encrypted, $appid)
	{
		try {
			$ciphertext_dec = base64_decode($encrypted);
			$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			$iv = substr($this->key, 0, 16);
			mcrypt_generic_init($module, $this->key, $iv);
			$decrypted = mdecrypt_generic($module, $ciphertext_dec);
			mcrypt_generic_deinit($module);
			mcrypt_module_close($module);
		} catch (Exception $e) {
			return array(ErrorCode::$DecryptAESError, null);
		}
		try {
			$pkc_encoder = new PKCS7Encoder();
			$result = $pkc_encoder->decode($decrypted);
			if (strlen($result) < 16) {
				return "";
			}
			$content = substr($result, 16, strlen($result));
			$len_list = unpack("N", substr($content, 0, 4));
			$xml_len = $len_list[1];
			$xml_content = substr($content, 4, $xml_len);
			$from_appid = substr($content, $xml_len + 4);
			if (!$appid) {
				$appid = $from_appid;
			}
		} catch (Exception $e) {
			return array(ErrorCode::$IllegalBuffer, null);
		}
		if ($from_appid != $appid) {
			return array(ErrorCode::$ValidateAppidError, null);
		}
		return array(0, $xml_content, $from_appid);
	}
	function getRandomStr()
	{
		$str = "";
		$str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($str_pol) - 1;
		for ($i = 0; $i < 16; $i++) {
			$str .= $str_pol[mt_rand(0, $max)];
		}
		return $str;
	}
}
class ErrorCode
{
	public static $OK = 0;
	public static $ValidateSignatureError = 40001;
	public static $ParseXmlError = 40002;
	public static $ComputeSignatureError = 40003;
	public static $IllegalAesKey = 40004;
	public static $ValidateAppidError = 40005;
	public static $EncryptAESError = 40006;
	public static $DecryptAESError = 40007;
	public static $IllegalBuffer = 40008;
	public static $EncodeBase64Error = 40009;
	public static $DecodeBase64Error = 40010;
	public static $GenReturnXmlError = 40011;
	public static $errCode = array("0" => "处理成功", "40001" => "校验签名失败", "40002" => "解析xml失败", "40003" => "计算签名失败", "40004" => "不合法的AESKey", "40005" => "校验AppID失败", "40006" => "AES加密失败", "40007" => "AES解密失败", "40008" => "公众平台发送的xml不合法", "40009" => "Base64编码失败", "40010" => "Base64解码失败", "40011" => "公众帐号生成回包xml失败");
	public static function getErrText($err)
	{
		if (isset(self::$errCode[$err])) {
			return self::$errCode[$err];
		} else {
			return false;
		}
	}
}
class DataCache
{
	public static $file = ".wechat";
	public static function get($name = 'token')
	{
		$filename = md5($name) . self::$file;
		if (!is_file($filename)) {
			return false;
		}
		$json = file_get_contents($filename);
		$data = json_decode($json, true);
		if ($data['create_time'] + $data['expire'] < time()) {
			return false;
		}
		return $data['content'];
	}
	public static function set($content, $name = 'token', $expire = 7200)
	{
		$filename = md5($name) . self::$file;
		$fp = fopen($filename, 'w');
		fwrite($fp, json_encode(array('create_time' => time(), 'expire' => $expire, 'content' => $content)));
		fclose($fp);
	}
}