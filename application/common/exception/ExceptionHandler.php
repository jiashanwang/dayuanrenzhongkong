<?php

//decode by http://chiran.taobao.com/
namespace app\common\exception;

use app\common\library\Configapi;
use app\common\library\Email;
use think\exception\Handle;
use think\Log;
use Exception;
class ExceptionHandler extends Handle
{
	private $config;
	private $code;
	private $message;
	private $file = "";
	private $line = "";
	private $tracestr = "";
	public function __construct()
	{
		$this->config = Configapi::getconfig();
	}
	public function render(Exception $e)
	{
		$this->file = $e->getFile();
		$this->line = $e->getLine();
		$this->code = 500;
		$this->tracestr = $e->getTraceAsString();
		$this->message = $e->getMessage() ?: '很抱歉，服务器内部错误';
		$this->recordErrorLog($e);
		$this->errorEmailSend();
		if ($this->config['app_debug']) {
			return parent::render($e);
		}
		return djson($this->code, $this->message);
	}
	private function recordErrorLog(Exception $e)
	{
		Log::record($e->getMessage(), 'error');
		Log::record($e->getTraceAsString(), 'error');
	}
	private function errorEmailSend()
	{
		if (!function_exists('openssl_encrypt')) {
			Log::error("[通知邮件发送异常]【请先开启openssl环境】");
			return false;
		}
		$title = "系统报错猿人V2【" . gethostbyname($_SERVER['SERVER_NAME']) . "/" . $this->handleGetIp() . "】" . $this->handleGetCurrentUrl() . "【" . date("Y-m-d H:i:s") . "】";
		$content = '<h2>错误：' . $this->message . '</h2>';
		$content .= '<p style="font-size:10px;background-color:#000;color:#fff;">' . $this->file . ' ' . $this->line . '行</p>';
		$content .= '<p>请求地址：' . $this->handleGetCurrentUrl() . '<br/>客户端IP:' . $this->handleGetIp() . '<br/>请求参数：' . var_export($_REQUEST, 1) . '<br/><br/>详细错误：' . $this->tracestr . '<br/><br/>SERVER：' . var_export($_SERVER, 1) . '</p>';
		return Email::sendMail($title, $content);
	}
	private function handleGetIp()
	{
		if (!IS_CLI) {
			if (getenv('HTTP_CLIENT_IP')) {
				$onlineip = getenv('HTTP_CLIENT_IP');
			} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
				$onlineip = getenv('HTTP_X_FORWARDED_FOR');
			} elseif (getenv('REMOTE_ADDR')) {
				$onlineip = getenv('REMOTE_ADDR');
			} else {
				$onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
			}
			return $onlineip;
		} else {
			return "";
		}
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
}