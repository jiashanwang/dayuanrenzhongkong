<?php


namespace Recharge;


use app\common\model\Porder as PorderModel;
use think\Log;

 /**
 * @description 小芒赢州hf
 */
class Xiaomangyingzhou
{
    private $mchid;
    private $apikey;
    private $notify;
    private $apiurl;

    public function __construct($option)
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['notify']) ? $option['notify'] : (isset($option['param3']) ? $option['param3'] : '');
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
    }
    
    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        $teltype = $this->get_teltype($isp);
        
        $data = array(
            "mchid" => $this->mchid,
            "tel" => $mobile,
            "price" => $param['param1'],
            "orderid" => $out_trade_num,
            "teltype" => $teltype,
            'timeout' => $param['param2'],
            'notify' => $this->notify,
            'time' => time(),
            'rand' => rand(100000, 999999)
        );
        
        $data['sign'] = $this->makeSign($data, $this->apikey);
        
        return $this->http_post($this->apiurl, $data);
    }

    /**
     * 运营商 0 移动1 联通2 电信
     */
    private function get_teltype($str)
    {
        switch ($str) {
            case '移动':
                return 0;
            case '联通':
                return 1;
            case '电信':
                return 2;
            default:
                return 9;
        }
    }

    /**
     * 生成签名
     */
    public function makeSign(array $params = [], $secretKey = '')
    {
        $sign_str = '';
        if(empty($params)){
            return $sign_str;
        }
        
        foreach ($params as $key => $value){
            $sign_str= $sign_str .$value;
        }
        
        $sign_str = $sign_str .$secretKey;

        return md5($sign_str);
    }

    /**
     * post请求
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
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            $result = json_decode($sContent, true);
            if ($result['code'] == 0) {
                return rjson(0, $result['msg'], $result);
            } else {
                return rjson(1, $result['msg'], $result);
            }
        } else {
            return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"]);
        }
    }

    public function notify()
    {
        Log::error("回调数据：".json_encode($_POST));
        $state = intval(I('status'));
        if ($state == 1) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('xiaomangyingzhou', I('order_id'), $_POST ,'充值成功');
            echo "success";
        } else {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('xiaomangyingzhou', I('order_id'), $_POST , '手动取消');
            echo "success";
        }
    }
}