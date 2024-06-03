<?php


namespace Recharge;


use app\common\library\Email;
use app\common\model\Porder as PorderModel;

/**
 * http://域名/api/order/telorder
 **/
class Zhongh
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
    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        $teltype = $this->get_teltype($isp);
        $sign_str = "mchId=" . $this->mchid . '&orderId=' . $out_trade_num . '&price=' . $param['param1'] . '&telNum=' . $mobile . '&telType=' . $teltype . '&timeOut=' . $param['param2'] . '&notify=' . $this->notify . '&key=' . $this->apikey;
        $sign = strtoupper(md5($sign_str));
        return $this->http_post($this->apiurl, [
            "mchId" => $this->mchid,
            "recharge_channel" => $param['param3'],
            "telNum" => $mobile,
            "orderId" => $out_trade_num,
            "price" => $param['param1'],
            "telType" => $teltype,
            'timeOut' => $param['param2'],
            'notify' => $this->notify,
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
            if (intval($result['code']) == 200) {
                return rjson(0, $result['message'], $result);
            } else {
                return rjson(1, $result['message'], $result);
            }
        } else {
            return rjson(1, '接口访问失败，http错误码' . $aStatus["http_code"]);
        }
    }

    public function notify()
    {
        $data = I('post.');
        $state = intval($data['status']);
        if ($state == 2) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('zhongh', $data['data']['orderId'], $data);
            echo "success";
        } else if (in_array($state, [3, 4])) {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('zhongh', $data['data']['orderId'], $data);
            echo "success";
        }
    }
}