<?php

//decode by http://chiran.taobao.com/
namespace Util;

class Http
{
	public static function post($url, $params = [], $options = [])
	{
		$req = self::sendRequest($url, $params, 'POST', $options);
		return $req['ret'] ? $req['msg'] : '';
	}
	public static function get($url, $params = [], $options = [])
	{
		$req = self::sendRequest($url, $params, 'GET', $options);
		return $req['ret'] ? $req['msg'] : '';
	}
	public static function postAsync($url, $params = [])
	{
		$req = self::sendAsyncRequest($url, $params, 'POST');
		return $req;
	}
	public static function sendRequest($url, $params = [], $method = 'POST', $options = [])
	{
		$method = strtoupper($method);
		$protocol = substr($url, 0, 5);
		$query_string = is_array($params) ? http_build_query($params) : $params;
		$ch = curl_init();
		$defaults = array();
		if ('GET' == $method) {
			$geturl = $query_string ? $url . (stripos($url, "?") !== FALSE ? "&" : "?") . $query_string : $url;
			$defaults[CURLOPT_URL] = $geturl;
		} else {
			$defaults[CURLOPT_URL] = $url;
			if ($method == 'POST') {
				$defaults[CURLOPT_POST] = 1;
			} else {
				$defaults[CURLOPT_CUSTOMREQUEST] = $method;
			}
			$defaults[CURLOPT_POSTFIELDS] = $query_string;
		}
		$defaults[CURLOPT_HEADER] = FALSE;
		$defaults[CURLOPT_USERAGENT] = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.98 Safari/537.36";
		$defaults[CURLOPT_FOLLOWLOCATION] = TRUE;
		$defaults[CURLOPT_RETURNTRANSFER] = TRUE;
		$defaults[CURLOPT_CONNECTTIMEOUT] = 3;
		$defaults[CURLOPT_TIMEOUT] = 3;
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		if ('https' == $protocol) {
			$defaults[CURLOPT_SSL_VERIFYPEER] = FALSE;
			$defaults[CURLOPT_SSL_VERIFYHOST] = FALSE;
		}
		curl_setopt_array($ch, (array) $options + $defaults);
		$ret = curl_exec($ch);
		$err = curl_error($ch);
		if (FALSE === $ret || !empty($err)) {
			$errno = curl_errno($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			return array('ret' => FALSE, 'errno' => $errno, 'msg' => $err, 'info' => $info);
		}
		curl_close($ch);
		return array('ret' => TRUE, 'msg' => $ret);
	}
	public static function sendAsyncRequest($url, $params = [], $method = 'POST')
	{
		$method = strtoupper($method);
		$method = $method == 'POST' ? 'POST' : 'GET';
		if (is_array($params)) {
			$post_params = array();
			foreach ($params as $k => &$v) {
				if (is_array($v)) {
					$v = implode(',', $v);
				}
				$post_params[] = $k . '=' . urlencode($v);
			}
			$post_string = implode('&', $post_params);
		} else {
			$post_string = $params;
		}
		$parts = parse_url($url);
		if (!isset($parts['host'])) {
			return false;
		}
		if ($method == 'GET' && $post_string) {
			$parts['query'] = isset($parts['query']) ? $parts['query'] . '&' . $post_string : $post_string;
			$post_string = '';
		}
		$parts['query'] = isset($parts['query']) && $parts['query'] ? '?' . $parts['query'] : '';
		$fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 3);
		if (!$fp) {
			return FALSE;
		}
		stream_set_timeout($fp, 3);
		$out = $method . " " . $parts['path'] . $parts['query'] . " HTTP/1.1\r\n";
		$out .= "Host: " . $parts['host'] . "\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: " . strlen($post_string) . "\r\n";
		$out .= "Connection: Close\r\n\r\n";
		if ($post_string !== '') {
			$out .= $post_string;
		}
		fwrite($fp, $out);
		fclose($fp);
		return TRUE;
	}
	public static function sendToBrowser($file, $delaftersend = true, $exitaftersend = true)
	{
		if (file_exists($file) && is_readable($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment;filename = ' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check = 0, pre-check = 0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			if ($delaftersend) {
				unlink($file);
			}
			if ($exitaftersend) {
				exit;
			}
		}
	}
}