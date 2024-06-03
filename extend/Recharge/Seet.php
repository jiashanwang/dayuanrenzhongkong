<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;

/**
 * 话费充值,猿人接口代写，中控安装
 * 配置1：商户ID，配置2：秘钥，配置3：回调地址；配置4：接口地址
 * 参数1：面值；参数2：超时时间（单位秒）
 * http://域名/pay/setbill/index.html
 **/
class Seet
{
    private $mchid;//商户编号
    private $apikey;
    private $notify;
    private $apiUrl;//话费充值接口

    public function __construct($option)
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['notify']) ? $option['notify'] : (isset($option['param3']) ? $option['param3'] : '');
        $this->apiUrl = isset($option['param4']) ? $option['param4'] : '';
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        $teltype = $this->get_teltype($isp);
        $price = $param['param1'];
        $hd_timeout = $param['param2'];//timeout
        $hd_notify = $this->notify;
        $rand = rand(100000, 999999);
        $sign_str = $this->mchid . $mobile . $out_trade_num . $price . $teltype . $hd_timeout . $hd_notify .  $rand . $this->apikey;
        $sign = md5($sign_str);
        $data = [
            "hd_mchid" => $this->mchid,
            "hd_tel" => $mobile,
            "hd_orderid" => $out_trade_num,
            "hd_price" => $price,//产品面值
            "hd_teltype" => $teltype,//充值金额
            "hd_timeout" => $hd_timeout,
            "hd_notify" => $hd_notify,
            "rand" => $rand,
            "sign" => $sign
        ];
        return $this->http_post($this->apiUrl, $data);
    }

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
                return -1;
        }
    }
    /**
     * POST请求
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
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            $result = json_decode($sContent, true);
            if ($result['status'] == 'success') {
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
        $state = intval(I('status'));
        if ($state == 1) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('seet', I('hd_orderid'), $_POST,"充值成功",I('sp_order'));
            echo "OK";
        } else if (in_array($state, [2,3])) {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('seet', I('hd_orderid'), $_POST);
            echo "OK";
        }
    }
}