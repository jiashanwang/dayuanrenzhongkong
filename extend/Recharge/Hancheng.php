<?php


namespace Recharge;


use app\common\model\Porder as PorderModel;

/**
 * http://ip:9999/api/pay/telpay
 **/
class Hancheng
{
    private $mchid;//商户编号
    private $apikey;
    private $notify;
    private $apiurl;//话费充值接口

    public function __construct($option)
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['param3']) ? $option['param3'] : '';
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($orderid, $tel, $param, $isp = '')
    {
        $teltype = $this->get_teltype($isp);
        $time = time();
        $rand = rand(100000, 999999);
        $sign_str = $this->mchid . $tel . $param['param1'] . $orderid . $teltype . $param['param2'] . $this->notify . $time . $rand . $this->apikey;
        $sign = md5($sign_str);
        return $this->http_post($this->apiurl, [
            "mchid" => $this->mchid,
            "tel" => $tel,
            "orderid" => $orderid,
            "price" => $param['param1'],
            "teltype" => $teltype,
            'timeout' => $param['param2'],
            'notify' => $this->notify,
            'time' => $time,
            'rand' => $rand,
            'sign' => $sign
        ]);
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
     * get请求
     * @param $methond
     * @param $param
     * @return bool|mixed
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
        $state = intval(I('status'));
        if ($state == 1) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('hancheng', I('order_id'), $_POST);
            echo "success";
        } else if (in_array($state, [2, 3])) {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('hancheng', I('order_id'), $_POST);
            echo "success";
        }
    }
}