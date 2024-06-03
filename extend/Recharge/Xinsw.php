<?php


namespace Recharge;


use app\common\library\Email;
use app\common\model\Porder as PorderModel;

/**
 * http://域名/api/v1/order/submit
 **/
class Xinsw
{
    private $mchid;//商户编号
    private $key;
    private $apiurl;
    private $notify;

    public function __construct($option)
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->key = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['param3']) ? $option['param3'] : '';
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        $data = [
            "Pid" => $param['param1'],
            'Account' => $mobile,
            "ExternalOrderNo" => $out_trade_num,
            "MchId" => $this->mchid,
            "TimeStamp" => time()
        ];
        $data['Sign'] = $this->sign($data);
        $data['NotifyUrl'] = $this->notify;
        return $this->http_post($this->apiurl, $data);
    }

    function sign($param)
    {
        $str = '';
        foreach ($param as $k => $v) {
            $str .= $v;
        }
        $signstr = $str . $this->key;
        return md5($signstr);
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
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, ["ContentType:application/x-www-form-urlencoded;charset=utf-8"]);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            $result = json_decode($sContent, true);
            if ($result['status'] == 10001) {
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
        $str = file_get_contents("php://input");
        $data = json_decode($str, true);
        if ($data['status'] == 2) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('xinsw', $data['externalOrderNo'], $str);
            echo "ok";
        } else if ($data['status'] == 3) {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('xinsw', $data['externalOrderNo'], $str);
            echo "ok";
        }
    }

}