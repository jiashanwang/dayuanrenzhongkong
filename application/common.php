<?php

//decode by http://chiran.taobao.com/
error_reporting(E_ERROR | E_PARSE );
if (!function_exists('dyr_encrypt')) {
	function dyr_encrypt($data, $key = '', $expire = 0)
	{
		$key = md5(empty($key) ? config('md5_prefix') : $key);
		$data = base64_encode($data);
		$x = 0;
		$len = strlen($data);
		$l = strlen($key);
		$char = '';
		for ($i = 0; $i < $len; $i++) {
			if ($x == $l) {
				$x = 0;
			}
			$char .= substr($key, $x, 1);
			$x++;
		}
		$str = sprintf('%010d', $expire ? $expire + time() : 0);
		for ($i = 0; $i < $len; $i++) {
			$str .= chr(ord(substr($data, $i, 1)) + ord(substr($char, $i, 1)) % 256);
		}
		return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
	}
}
if (!function_exists('dyr_decrypt')) {
	function dyr_decrypt($data, $key = '')
	{
		$key = md5(empty($key) ? C('md5_prefix') : $key);
		$data = str_replace(array('-', '_'), array('+', '/'), $data);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		$data = base64_decode($data);
		$expire = substr($data, 0, 10);
		$data = substr($data, 10);
		if ($expire > 0 && $expire < time()) {
			return '';
		}
		$x = 0;
		$len = strlen($data);
		$l = strlen($key);
		$char = $str = '';
		for ($i = 0; $i < $len; $i++) {
			if ($x == $l) {
				$x = 0;
			}
			$char .= substr($key, $x, 1);
			$x++;
		}
		for ($i = 0; $i < $len; $i++) {
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
				$str .= chr(ord(substr($data, $i, 1)) + 256 - ord(substr($char, $i, 1)));
			} else {
				$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
		}
		return base64_decode($str);
	}
}
if (!function_exists('get_client_ip')) {
	function get_client_ip($type = 0)
	{
		$type = $type ? 1 : 0;
		static $ip = NULL;
		if ($ip !== NULL) {
			return $ip[$type];
		}
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown', $arr);
			if (false !== $pos) {
				unset($arr[$pos]);
			}
			$ip = trim($arr[0]);
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$long = sprintf("%u", ip2long($ip));
		$ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
		return $ip[$type];
	}
}
if (!function_exists('time_format')) {
	function time_format($time = NULL, $format = 'Y-m-d H:i')
	{
		if (!$time) {
			return "";
		} else {
			return date($format, intval($time));
		}
	}
}
if (!function_exists('magic_time_format')) {
	function magic_time_format($time)
	{
		$day = strtotime(date('Y-m-d', time()));
		$pday = strtotime(date('Y-m-d', strtotime('-1 day')));
		$ppday = strtotime(date('Y-m-d', strtotime('-2 day')));
		$nowtime = time();
		$tc = $nowtime - $time;
		if ($time < $ppday) {
			$str = date('Y-m-d H:i', $time);
		} elseif ($time < $day && $time > $pday) {
			$str = "昨天 " . date('H:i', $time);
		} elseif ($time < $pday && $time > $ppday) {
			$str = "前天 " . date('H:i', $time);
		} elseif ($tc > 60 * 60) {
			$str = floor($tc / (60 * 60)) . "小时前";
		} elseif ($tc > 60) {
			$str = floor($tc / 60) . "分钟前";
		} else {
			$str = "刚刚";
		}
		return $str;
	}
}
if (!function_exists('format_bytes')) {
	function format_bytes($size, $delimiter = '')
	{
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
		$i = 0;
		while ($size >= 1024 && $i < 5) {
			$size /= 1024;
			$i++;
		}
		return round($size, 2) . $delimiter . $units[$i];
	}
}
if (!function_exists('is_weixin_browser')) {
	function is_weixin_browser()
	{
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
			return true;
		} else {
			return false;
		}
	}
}
if (!function_exists('create_qrcode')) {
	function create_qrcode($txt, $size = 4)
	{
		$data = $txt;
		$level = 'L';
		$erweima = C('DOWNLOAD_UPLOAD.filePath') . "qr/" . time() . md5($txt) . ".png";
		\Phpqrcode\QRcode::png($data, $erweima, $level, $size);
		return $erweima;
	}
}
if (!function_exists('save_web_image')) {
	function save_web_image($imgurl)
	{
		$imgStr = file_get_contents($imgurl);
		$path = C('DOWNLOAD_UPLOAD.filePath') . "webimg/";
		if (!is_dir($path)) {
			mkdir(iconv("UTF-8", "GBK", $path), 0777, true);
		}
		$filename = md5($imgurl) . ".jpg";
		$fp = fopen($path . $filename, 'wb');
		fwrite($fp, $imgStr);
		return $path . $filename;
	}
}
if (!function_exists('filterEmoji')) {
	function filterEmoji($str)
	{
		$str = preg_replace_callback('/./u', function (array $match) {
			return strlen($match[0]) >= 4 ? '' : $match[0];
		}, $str);
		return $str;
	}
}
if (!function_exists('QCellCore')) {
	function QCellCore($mobile)
	{
		if (substr($mobile, 0, 1) == '1') {
			if ($phone = M('phone')->where(array('phone' => substr($mobile, 0, 7)))->find()) {
				$data['type'] = 1;
				$data['ispstr'] = $phone['isp'];
				$data['prov'] = $phone['province'];
				$data['city'] = $phone['city'];
				$data['isp'] = ispstrtoint($phone['isp']);
				return rjson(0, 'ok', $data);
			} else {
				return rjson(1, '未找到该号码归属地');
			}
		} else {
			return rjson(1, '号码错误');
		}
	}
}
if (!function_exists('ispstrtoint')) {
	function ispstrtoint($ispstr)
	{
		if ('移动' == $ispstr) {
			return 1;
		}
		if ('电信' == $ispstr) {
			return 2;
		}
		if ('联通' == $ispstr) {
			return 3;
		}
		if (strpos($ispstr, '虚拟') !== false) {
			return 4;
		}
		return 0;
	}
}
if (!function_exists('getISPText')) {
	function getISPText($ispidstr)
	{
		if (!$ispidstr) {
			return '';
		}
		$arr = explode(",", $ispidstr);
		$data = array();
		foreach ($arr as $key => $vo) {
			$vo = trim($vo);
			$vo && ($data[] = C('ISP_TEXT')[$vo]);
		}
		return implode(',', $data);
	}
}
if (!function_exists('inArrayDou')) {
	function inArrayDou($arrstr, $needle)
	{
		if (!$arrstr || !is_string($arrstr)) {
			return false;
		}
		$arr = explode(",", $arrstr);
		return in_array($needle, $arr);
	}
}
if (!function_exists('getGradeIdsNameText')) {
	function getGradeIdsNameText($grade_ids)
	{
		$lists = M('customer_grade')->where(array('id' => array('in', $grade_ids)))->select();
		if (!$lists) {
			return "";
		}
		return implode('<br/>', array_column($lists, 'grade_name'));
	}
}
if (!function_exists('checkIp')) {
	function checkIp($ip, $rule)
	{
		$rule_regexp = str_replace('.*', 'a', $rule);
		$rule_regexp = preg_quote($rule_regexp, '/');
		$rule_regexp = str_replace('a', '\\.\\d{1,3}', $rule_regexp);
		if (preg_match('/^' . $rule_regexp . '$/', $ip)) {
			return true;
		} else {
			return false;
		}
	}
}
if (!function_exists('checkIpRules')) {
	function checkIpRules($ip, $rules_str)
	{
		if (!$rules_str) {
			return true;
		}
		$allow = false;
		$iprules = explode(',', $rules_str);
		foreach ($iprules as $rule) {
			if (checkIp($ip, $rule)) {
				$allow = true;
				break;
			}
		}
		return $allow;
	}
}
function elapsed_time($startint, $endint)
{
	if (!$startint) {
		return "";
	}
	if (!is_numeric($startint)) {
		$startint = strtotime($startint);
	}
	if (!$endint) {
		$endint = time();
	}
	if (!is_numeric($endint)) {
		$endint = strtotime($endint);
	}
	$date = floor(($endint - $startint) / 86400);
	$hour = floor(($endint - $startint) % 86400 / 3600);
	$minute = floor(($endint - $startint) % 86400 % 3600 / 60);
	$miao = floor(($endint - $startint) % 86400 % 3600 % 60);
	$str = $hour . '时' . $minute . '分' . $miao . '秒';
	if ($date) {
		$str = $date . '天' . $str;
	}
	return $str;
}
function parseMaoArr($value)
{
	if (!$value) {
		return array();
	}
	$array = preg_split('/[,;\\r\\n]+/', trim($value, ",;\r\n"));
	if (strpos($value, ':')) {
		$value = array();
		foreach ($array as $val) {
			$k = substr($val, 0, strpos($val, ':'));
			$v = substr($val, strpos($val, ':') + 1);
			$value[$k] = $v;
		}
	} else {
		$value = $array;
	}
	return $value;
}
function get_name($id)
{
	return M('customer')->where('id=' . $id)->value('username');
}
function getReapiName($id)
{
	return M('reapi')->where(array('id' => $id))->value('name');
}
function getReapiParamName($id)
{
	return M('reapi_param')->where(array('id' => $id))->value('desc');
}
function get_user_grade_name($id)
{
	return M('customer c')->join('customer_grade g', 'g.id=c.grade_id')->where(array('c.id' => $id))->value('grade_name');
}
function getApartOrderNum($order_number)
{
	return M('porder')->where(array('apart_order_number' => $order_number))->count();
}
function getJmApiName($id)
{
	return M('jmapi')->where(array('id' => $id))->value('name');
}
function getJmApiParamName($id)
{
	return M('jmapi_param')->where(array('id' => $id))->value('desc');
}
function balance_query($mobile,$isp="",$area="",$customer_id=""){
    if($isp === '联通'){
        $channel_id = 'unicom_balance';
        $channel3_id = 'lt';
        $channel_price=C('HFYE_API_LT');
        $Query_type="联通余额查询";
    }elseif($isp === '电信'){
        $channel_id = 'telecom_balance';
        $channel3_id = 'dx';
        $channel_price=C('HFYE_API_DX');
        $Query_type="电信余额查询";
    }elseif($isp === '移动'){
        $channel_id = 'mobile_balance';
        $channel3_id = 'yd';
        $channel_price=C('HFYE_API_YD');
        $Query_type="移动余额查询";
    }elseif($isp === 'electricity_balance'){
    	$channel_id = 'electricity_balance';
    	$channel3_id = '';
    	$channel_price=C('HFYE_API_DFHH');
        $Query_type="电费户号查询";
    }elseif($isp === 'electricity_balance_query'){
    	$channel_id = 'electricity_balance_query';
    	$channel3_id = '';
    	$channel_price=C('HFYE_API_DF');
        $Query_type="电费户号余额查询";
    }elseif($isp === 'detection_mnp'){
    	$channel_id = 'detection_mnp';
    	$channel3_id = '';
    	$channel_price=C('HFYE_API_HMJC');
        $Query_type="虚拟号携号转网查询";
    }
    $HFYE_API_TYPE=C('HFYE_API_TYPE');
    $HFYE_ID=C('HFYE_ID');
    $HFYE_KEY=C('HFYE_KEY');
    $HFYE_API=C('HFYE_API');
    if($customer_id){
        $user=M('customer')->where(array('id'=>$customer_id))->field("balance,shouxin_e")->find();
        $Kh9MC=$user['balance']-$channel_price;
        $Kh9MD=-1*$user['shouxin_e'];
        if($Kh9MC<$Kh9MD){
            $return['code']=1;$return['message']='检查到授信额度不足！';return $return;
        }
    }
    
    if($HFYE_API_TYPE == 1){
        $phone_balance_data = ["userid"=> $HFYE_ID,"account"=>$mobile,"isp"=>$isp];
        if(in_array($isp,['electricity_balance','electricity_balance_query'])){
            $phone_balance_data['electricity_are']=$area;
        }
        //字典排序
        ksort($phone_balance_data);
        //拼接签名串
        $sign_str = http_build_query($phone_balance_data).'&apikey='.$HFYE_KEY;
        //签名
        $sign = strtoupper(md5(urldecode($sign_str)));
        $phone_balance_data['sign'] = $sign;
        $httpdata = $phone_balance_data;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $HFYE_API);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($httpdata)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $httpdata);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($curl);
        curl_close($curl);
        $return = json_decode($sContent, true);
        if(empty($return)){
            $return['code']=1;$return['message']='查询平台暂时无法查询！';
        }
        if($return['errno']==0){
            $return['code']=200;
        }else{
            $return['code']=$return['errno'];
        }
        $return['message']=$return['errmsg'];
        if(in_array($isp,['electricity_balance'])){
            $return['typeName']=$return['data']['typeName'];
            $return['userAddr']=$return['data']['userAddr'];
            $return['company']=$return['data']['company'];
            $return['userName']=$return['data']['userName'];
        }
    }elseif($HFYE_API_TYPE == 2){
        $phone_balance_data = array (
            'id' => $HFYE_ID,
            'key' => $HFYE_KEY,
            'mobile' => $mobile,
            'channel' => $channel_id
        );
        if(in_array($channel_id,['electricity_balance','electricity_balance_query'])){
            if($area == '北京'){
            	$phone_balance_data['electricity_are']=1;
            }elseif($area == '天津'){
            	$phone_balance_data['electricity_are']=2;
            }elseif($area == '辽宁'){
            	$phone_balance_data['electricity_are']=3;
            }elseif($area == '江苏'){
            	$phone_balance_data['electricity_are']=4;
            }elseif($area == '浙江'){
            	$phone_balance_data['electricity_are']=5;
            }elseif($area == '山东'){
            	$phone_balance_data['electricity_are']=6;
            }elseif($area == '湖南'){
            	$phone_balance_data['electricity_are']=7;
            }elseif($area == '陕西'){
            	$phone_balance_data['electricity_are']=8;
            }elseif($area == '冀北'){
            	$phone_balance_data['electricity_are']=9;
            }elseif($area == '新疆'){
            	$phone_balance_data['electricity_are']=10;
            }elseif($area == '宁夏'){
            	$phone_balance_data['electricity_are']=11;
            }elseif($area == '安徽'){
            	$phone_balance_data['electricity_are']=12;
            }elseif($area == '重庆'){
            	$phone_balance_data['electricity_are']=13;
            }elseif($area == '福建'){
            	$phone_balance_data['electricity_are']=14;
            }elseif($area == '河南'){
            	$phone_balance_data['electricity_are']=15;
            }elseif($area == '青海'){
            	$phone_balance_data['electricity_are']=16;
            }elseif($area == '湖北'){
            	$phone_balance_data['electricity_are']=17;
            }elseif($area == '蒙东'){
            	$phone_balance_data['electricity_are']=18;
            }elseif($area == '黑龙江'){
            	$phone_balance_data['electricity_are']=19;
            }elseif($area == '吉林'){
            	$phone_balance_data['electricity_are']=20;
            }elseif($area == '上海'){
            	$phone_balance_data['electricity_are']=21;
            }elseif($area == '甘肃'){
            	$phone_balance_data['electricity_are']=22;
            }elseif($area == '山西'){
            	$phone_balance_data['electricity_are']=23;
            }elseif($area == '四川'){
            	$phone_balance_data['electricity_are']=24;
            }elseif($area == '江西'){
            	$phone_balance_data['electricity_are']=25;
            }elseif($area == '河北'){
            	$phone_balance_data['electricity_are']=26;
            }
        }
        
        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_URL, $HFYE_API);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, json_encode($phone_balance_data));
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, ["Content-Type:application/json;charset=utf-8"]);
        $sContent = curl_exec($oCurl);
        curl_close($oCurl);
        $return = json_decode($sContent, true);
        if(empty($return)){
            $return['code']=1;$return['message']='查询平台暂时无法查询！';
        }
        if(in_array($isp,['electricity_balance'])){
            $return['typeName']=$return['data']['typeName'];
            $return['userAddr']=$return['data']['userAddr'];
            $return['company']=$return['data']['company'];
            $return['userName']=$return['data']['userName'];
        }
        if(isset($return['data']['curFee'])){
            $return['data']['mobile_fee']=$return['data']['curFee'];
        }else{
            if($channel_id=="electricity_balance"){
                $return['data']['mobile_fee']=0;
            }elseif($channel_id=="electricity_balance_query"){
                $return['data']['mobile_fee']=$return['data']['availableBalance'];
            }elseif($channel_id=="detection_mnp"){
                $return['data']['mobile_fee']=0;
            }
        }
    }elseif($HFYE_API_TYPE == 3){
        $phone_balance_data = array (
            'key' => $HFYE_KEY,
            'mobile' => $mobile,
            'isp' => $channel3_id,
            'order'=>time()
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $HFYE_API);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($phone_balance_data));
        curl_setopt($ch, CURLOPT_REFERER,  $HFYE_API);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $sContent = curl_exec($ch);
        curl_close($ch);
        $return = json_decode($sContent, true);
        if(empty($return)){
            $return['code']=1;$return['message']='查询平台暂时无法查询！';
        }
        if($return['code']==10000){
            $return['code']=200;
            $return['msg']="余额是0的 不一定准确,有余额的才准确";
        }else{
            $return['code']=$return['code'];
            $return['msg']="该号码无法查询，可能是携号转网、号码欠费、空号等原因";
            $return['data']=[];
            $return['data']['curFee']="";
        }
        $return['message']=$return['msg'];
        $return['data']['mobile']=$mobile;
        $return['data']['mobile_fee']=$return['data']['curFee'];
    }else{
        $return['code']=1;$return['message']='暂时无法查询！';
    }
    $return['mobile']=$mobile;
    $return['channel_price']=$channel_price;
    $return['mobile_fee']=$return['data']['mobile_fee']??null;
    $return['owedBalance']=$return['data']['owedBalance']??0;
    $return['availableBalance']=$return['data']['availableBalance']??0;
    $return['isVirtuallyIsp']=$return['data']['isVirtuallyIsp']??0;
    $return['isTransfer']=$return['data']['isTransfer']??0;
    $return['remark']=$return['data']['remark']??$return['message'];
    //存储查询记录
    if($customer_id){
        M('balance_query_record')->insertGetId(array('Customer_id' => $customer_id,'Query_type' => $Query_type, 'Query_number' => $mobile, 'Account_balance' => $return['mobile_fee'], 'Minimum_payment_amount' => 0,'OwedBalance' =>$return['owedBalance'],'AvailableBalance' =>$return['availableBalance'],'IsVirtuallyIsp' =>$return['isVirtuallyIsp'],'isTransfer' =>$return['isTransfer'],'request'=>json_encode($phone_balance_data,JSON_UNESCAPED_UNICODE),'message'=>json_encode($return,JSON_UNESCAPED_UNICODE)));
    }
    unset($return['data']);
    return $return;
}