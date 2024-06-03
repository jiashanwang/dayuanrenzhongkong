<?php

//decode by http://chiran.taobao.com/
namespace Emails;

include_once "class.phpmailer.php";
include_once "class.smtp.php";
class Phpmailers
{
	private $MAIL_HOST = "";
	private $MAIL_USERNAME = "";
	private $MAIL_FROM = "";
	private $MAIL_FROMNAME = "";
	private $MAIL_PASSWORD = "";
	private $MAIL_CHARSET = "utf-8";
	private $PORT = 465;
	private $DEBUG = 0;
	public $errmsg;
	public function __construct($options = [])
	{
		$this->MAIL_HOST = isset($options['mail_smtp_host']) ? $options['mail_smtp_host'] : '';
		$this->MAIL_USERNAME = isset($options['mail_smtp_user']) ? $options['mail_smtp_user'] : '';
		$this->MAIL_PASSWORD = isset($options['mail_smtp_pass']) ? $options['mail_smtp_pass'] : '';
		$this->MAIL_FROM = isset($options['mail_smtp_user']) ? $options['mail_smtp_user'] : '';
		$this->MAIL_FROMNAME = isset($options['mail_smtp_name']) ? $options['mail_smtp_name'] : '';
		$this->DEBUG = isset($options['debug']) ? $options['debug'] : 0;
		$this->PORT = isset($options['mail_smtp_port']) ? $options['mail_smtp_port'] : 465;
	}
	function sendMail($to, $title, $content)
	{
		$mail = new \PHPMailer();
		$mail->SMTPDebug = $this->DEBUG;
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = $this->MAIL_HOST;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $this->PORT;
		$mail->CharSet = $this->MAIL_CHARSET;
		$mail->FromName = $this->MAIL_FROMNAME;
		$mail->Username = $this->MAIL_USERNAME;
		$mail->Password = $this->MAIL_PASSWORD;
		$mail->From = $this->MAIL_FROM;
		$mail->isHTML(true);
		$mail->addAddress($to, 'dayuanren');
		$mail->Subject = $title;
		$mail->Body = $content;
		$status = $mail->send();
		$this->errmsg = $mail->ErrorInfo;
		if ($status) {
			return true;
		} else {
			return false;
		}
	}
}