<?php

//decode by http://chiran.taobao.com/
namespace Map;

class Bmap
{
	const API_URL = "http://api.map.baidu.com/";
	const AKS = ["wTgh0GqqO2B2d7OaC10wDBa7lcPzWHnx", "gcMCaQFbGb7IG6RxcMGAORljobCUVGaC"];
	public $msg;
	public function __construct()
	{
	}
	public function place_search_city($query, $region = '', $tag = '', $city_limit = false)
	{
		return $this->http_get('place/v2/search', array('query' => $query, 'tag' => $tag, 'region' => $region, 'output' => 'json', 'city_limit' => $city_limit, 'page_size' => 10));
	}
	public function place_search_radius($query, $location, $radius = 1000, $radius_limit = false)
	{
		return $this->http_get('place/v2/search', array('query' => $query, 'location' => $location, 'radius' => $radius, 'output' => 'json', 'radius_limit' => $radius_limit, 'page_size' => 10));
	}
	public function place_search_bounds($query, $bounds)
	{
		return $this->http_get('place/v2/search', array('query' => $query, 'bounds' => $bounds, 'output' => 'json', 'page_size' => 10));
	}
	public function place_detail($uid, $scope = 2)
	{
		return $this->http_get('place/v2/detail', array('uid' => $uid, 'scope' => $scope, 'output' => 'json'));
	}
	public function place_suggestion($query, $region = '', $city_limit = false)
	{
		return $this->http_get('place/v2/suggestion', array('query' => $query, 'region' => $region, 'city_limit' => $city_limit, 'output' => 'json', 'page_size' => 10));
	}
	public function geocoder($address)
	{
		return $this->http_get('geocoder/v2/', array('address' => $address, 'output' => 'json'));
	}
	public function ungeocoder($location)
	{
		return $this->http_get('geocoder/v2/', array('location' => $location, 'output' => 'json'));
	}
	public function direction_transit($origin, $destination)
	{
		return $this->http_get('direction/v2/transit', array('origin' => $origin, 'destination' => $destination, 'output' => 'json'));
	}
	public function direction_riding($origin, $destination)
	{
		return $this->http_get('direction/v2/riding', array('origin' => $origin, 'destination' => $destination, 'output' => 'json'));
	}
	public function direction_driving($origin, $destination)
	{
		return $this->http_get('direction/v2/driving', array('origin' => $origin, 'destination' => $destination, 'output' => 'json'));
	}
	public function direction_walking($origins, $destinations)
	{
		return $this->http_get('routematrix/v2/walking', array('origins' => $origins, 'destinations' => $destinations, 'output' => 'json'));
	}
	public function location_ip($ip)
	{
		return $this->http_get('location/ip', array('ip' => $ip, 'output' => 'json'));
	}
	public function geoconv($coords, $from = 3, $to = 5)
	{
		return $this->http_get('geoconv/v1/', array('coords' => $coords, 'from' => $from, 'to' => $to, 'output' => 'json'));
	}
	private function http_get($methond, $param)
	{
		$rand = rand(0, count(self::AKS) - 1);
		$param['ak'] = self::AKS[$rand];
		$url = self::API_URL . $methond . "?" . http_build_query($param);
		$result = json_decode(file_get_contents($url), true);
		if ($result['status'] == 0) {
			return $result;
		} else {
			return false;
		}
	}
}