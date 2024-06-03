<?php


namespace Recharge;


use app\common\library\Email;
use app\common\model\Porder as PorderModel;

/**
 * http://xxxxx/hfApi/order
 **/
class Liwen
{
    private $uid;//商户编号
    private $key;
    private $apiurl;//话费充值接口
    private $notify_url;//回调地址

    public function __construct($option)
    {
        $this->uid = isset($option['param1']) ? $option['param1'] : '';
        $this->key = isset($option['param2']) ? $option['param2'] : '';
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
        $this->notify_url = isset($option['notify']) ? $option['notify'] : (isset($option['param3']) ? $option['param3'] : '');
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($orderNo, $mobile, $param, $isp = '')
    {
        $data = [
            "amount" => $param['param1'],
            "hsNum" => $this->uid,
            'notifyUrl' => $this->notify_url,
            'outOrderNum' => $orderNo,
            "phone" => $mobile,
            "timestamp" => time(),
        ];
        $sign_str = http_build_query($data);
        $sign_str .= "&key=" . $this->key;
        $data['sign'] = strtolower(md5(urldecode($sign_str)));
        return $this->http_post($this->apiurl, $data);
    }

    /**
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
            if (intval($result['code']) == 200) {
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
        Email::sendMail('liwen_' . $_SERVER['HTTP_HOST'], var_export($_POST, true));
        $state = I('status');
        if ($state == 'success') {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('liwen', I('outOrderNum'), $_POST);
            echo "ok";
        } else if ($state == 'fail') {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('liwen', I('outOrderNum'), $_POST);
            echo "ok";
        }
    }

}