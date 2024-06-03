<?php

//decode by http://chiran.taobao.com/
namespace Map;

class Tmap
{
	const API_URL = "http://apis.map.qq.com/";
	const ak = "HTZBZ-M35HX-6KY4N-Z5BQP-2NTSH-BUF3J";
	public static function geocoder($lat, $lon)
	{
		return self::http_get('ws/geocoder/v1/', array('location' => $lat . "," . $lon));
	}
	private static function http_get($methond, $param)
	{
		$param['key'] = self::ak;
		$url = self::API_URL . $methond . "?" . http_build_query($param);
		$result = json_decode(file_get_contents($url), true);
		if ($result['status'] == 0) {
			return $result;
		} else {
			return false;
		}
	}
}