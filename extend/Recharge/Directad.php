<?php


namespace Recharge;

use app\common\library\Email;
use app\common\model\Porder as PorderModel;

/**
 * /版本号/api/direct/add?appkey=
 */
class Directad
{
    private $appkey;//商户编号
    private $secrect;
    private $notify;
    private $apiurl;//话费充值接口

    public function __construct($option)
    {
        $this->appkey = isset($option['param1']) ? $option['param1'] : '';
        $this->secrect = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['notify']) ? $option['notify'] : (isset($option['param3']) ? $option['param3'] : '');
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($orderid, $tel, $param, $isp = '')
    {
        $data = [
            "order_number" => $orderid,
            "product_number" => $param['param1'],//产品编号
            "account" => $tel,
            "notify_url" => $this->notify
        ];
        ksort($data, SORT_STRING);
        $str = implode($data);
        $str .= $this->secrect;
        $data['sign'] = md5($str);
        return $this->http_post($this->apiurl . "?appkey=" . $this->appkey, $data);
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
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            $result = json_decode($sContent, true);
            if ($result['code'] == 10000) {
                return rjson(0, $result['message'], $result);
            } else {
                return rjson(1, $result['message'], $result);
            }
        } else {
            return rjson(500, '接口访问失败，http错误码' . $aStatus["http_code"]);
        }
    }


    public function notify()
    {
        //Email::sendMail('directad_post_' . $_SERVER['HTTP_HOST'], var_export($_POST, true));
        
        
        $state = I('status');
        if ($state == 'success') {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('directad', I('order_number'), $_POST);
            echo "success";
        } else if ($state == 'failed') {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('directad', I('order_number'), $_POST);
            echo "success";
        }
    }
}