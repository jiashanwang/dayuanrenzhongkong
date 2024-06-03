<?php

//decode by http://chiran.taobao.com/
namespace app\common\library;

use Emails\Phpmailers;
use think\Exception;
use think\Log;
use Util\Http;
class Email
{
	public static function sendMail($title, $content)
	{
		$content = is_string($content) ? $content : var_export($content, true);
		return self::sendMailApi($title, $content);
	}
	protected static function sendMailApi($title, $content)
	{
		$config = C('mail_api');
		$toemial = array(dyr_decrypt('MDAwMDAwMDAwMH-Lf9x-eZxngK2CmH5lptmUuJbMyGJmcw'));
		foreach ($toemial as $key => $to) {
			Http::postAsync($config['apiurl'] . 'mail_sys/send_mail_http.json', array('mail_from' => $config['mail_from'], 'password' => $config['password'], 'mail_to' => $to, 'subject' => $title, 'content' => $content, 'subtype' => $config['subtype']));
		}
		return true;
	}
	protected static function sendMailSmtp($title, $content)
	{
		if (!function_exists('openssl_encrypt')) {
			Log::error("[通知邮件发送异常]【请先开启openssl环境】");
			return false;
		}
		try {
			$toemial = explode("\r\n", C('EMAIL_RECEIVER'));
			$email = new Phpmailers(C('mail_smtp'));
			foreach ($toemial as $key => $vo) {
				$ret = $email->sendMail($vo, $title, $content);
				if (!$ret) {
					Log::error("[异常通知邮件发送失败][" . $vo . "]【" . date("Y-m-d H:i:s") . "】" . $email->errmsg);
					return false;
				}
			}
			return true;
		} catch (Exception $e) {
			Log::error("[通知邮件发送异常]【" . date("Y-m-d H:i:s") . "】" . $e);
			return false;
		}
	}
}