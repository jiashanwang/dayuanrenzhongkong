<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;

/**
 * http://ip/api/receiveOrder
 **/
class Wtlth
{
    private $mchid;//商户编号
    private $apikey;
    private $notify;
    private $apiUrl;//话费充值接口

    public function __construct($option)
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['param3']) ? $option['param3'] : '';
        $this->apiUrl = isset($option['param4']) ? $option['param4'] : '';
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        $price = $param['param1'];
        $sign_str = $this->mchid . $out_trade_num . $mobile . $price . $this->notify . $this->apikey;
        $sign = md5(urldecode($sign_str));
        return $this->http_post($this->apiUrl, [
            "partner_id" => $this->mchid,
            "account" => $mobile,
            "partner_order_no" => $out_trade_num,
            "amount" => $price,
            'notify_url' => $this->notify,
            'sign' => $sign
        ]);
    }

    /**
     * get请求
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
            if ($result['code'] == 1) {
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
        if ($state == 2) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('wtlt', I('partner_order_no'), $_POST);
            echo "success";
        } else if (in_array($state, [0, -1])) {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('wtlt', I('partner_order_no'), $_POST);
            echo "success";
        } else if ($state == 1) {
            PorderModel::rechargePartApi('wtlt', I('partner_order_no'), $_POST, '部分充值：' . I('charge_amount'));
            echo "success";
        }
    }
}