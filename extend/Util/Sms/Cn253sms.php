<?php

//decode by http://chiran.taobao.com/
namespace Util\Sms;

class Cn253sms
{
	const SEND_URL = "http://sms.253.com/msg/send";
	private $un = "";
	private $pw = "";
	private $rd = 0;
	public $msg = "";
	public function __construct()
	{
	}
	public function vi_code($mobile, $code)
	{
		$smsContent = "【签名】您获得的验证码是:" . $code . "，2分钟内有效，打死也不要告诉别人哦！";
		return $this->send($mobile, $smsContent);
	}
	public function custom($mobile, $smsContent)
	{
		return $this->send($mobile, $smsContent);
	}
	private function send($mobile, $content)
	{
		$post_data = array();
		$post_data['un'] = $this->un;
		$post_data['pw'] = $this->pw;
		$post_data['msg'] = $content;
		$post_data['phone'] = $mobile . "";
		$post_data['rd'] = $this->rd;
		$res = $this->http_request("http://sms.253.com/msg/send", http_build_query($post_data));
		return $res;
	}
	private function http_request($url, $data = null)
	{
		if (function_exists('curl_init')) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			if (!empty($data)) {
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			}
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($curl);
			curl_close($curl);
			$result = preg_split("/[,\r\n]/", $output);
			if ($result[1] == 0) {
				$this->msg = "发送成功";
				return true;
			} else {
				$this->msg = "发送失败：" . $result[1];
				return false;
			}
		} elseif (function_exists('file_get_contents')) {
			$output = file_get_contents($url . $data);
			$result = preg_split("/[,\r\n]/", $output);
			if ($result[1] == 0) {
				return true;
			} else {
				$this->msg = $result[1];
				return false;
			}
		} else {
			return false;
		}
	}
}