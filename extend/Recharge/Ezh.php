<?php

namespace Recharge;

use app\common\model\Porder as PorderModel;
/**
 * Class Ezh
 * 话费充值
 * URL：/api/v1/order/submit
 */
class Ezh
{

    private $uid;//name
    private $apikey;//apikey
    private $notify;
    private $apiurl;//话费充值接口

    public function __construct($option)
    {
        $this->uid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['notify']) ? $option['notify'] : (isset($option['param3']) ? $option['param3'] : '');
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        $data = [
            "Pid" => intval($param['param1']),
            "Account" => $mobile,
            "ExternalOrderNo" => $out_trade_num,
            "MchId" => $this->uid,
            "TimeStamp" => time(),
        ];
        $data['Sign'] = $this->sign($data);
        $data['NotifyUrl']=$this->notify;
        if(intval($param['param2']==1)){
            $data['Province']=str_replace('省', '', str_replace('市', '', $isp));
            $data['City']=str_replace('市', '',isset($param['oparam3']) ? $param['oparam3'] : '');
        }
        return $this->http_post($this->apiurl, $data);
    }


    private function sign($param){
    $str = '';
    foreach ($param as $k => $v) {
        $str .= $v;
    }
    return md5($str.$this->apikey);
}



    /**
     * post
     */
    private function http_post($url, $param)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
        }
        if (is_string($param)) {
            $strPOST = $param;
        } else {
            $strPOST = http_build_query($param);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
        curl_setopt($oCurl, CURLOPT_HEADER, false);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, array("ContentType:application/x-www-form-urlencoded;charset=utf-8"));
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (isset($aStatus["http_code"]) && intval($aStatus["http_code"]) == 200) {
            $result = json_decode($sContent, true);
            if ($result['status'] == 10001 ) {
                 return rjson(0, $result['message'], $result);  
            }else {
                return rjson(1, $result['message'].'|请求post：'.$strPOST, $result);
            }
        } else {
            return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"], '接口访问失败，http错误码' . $aStatus["http_code"]);
        }
    }

    public function notify()
    { 
        $data = file_get_contents("php://input");
        $result = json_decode($data,true);
        $state = intval($result['status']);
        if ($state == 2) {
            PorderModel::rechargeSusApi('ezh', $result["externalOrderNo"], $result);
        } else if($state== 3) {
                    PorderModel::rechargeFailApi('ezh', $result["externalOrderNo"], $result);
                } 
        }
}