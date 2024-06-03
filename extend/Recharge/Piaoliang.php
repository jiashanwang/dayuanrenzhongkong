<?php


namespace Recharge;


use app\common\model\Porder as PorderModel;

/**
 * http://ip/api/order/phone/recharge
 **/
class Piaoliang
{
    private $username;//商户编号
    private $key;
    private $apiurl;//话费充值接口
    private $notify_url;

    public function __construct($option)
    {
        $this->username = isset($option['param1']) ? $option['param1'] : '';
        $this->key = isset($option['param2']) ? $option['param2'] : '';
        $this->notify_url = isset($option['param3']) ? $option['param3'] : '';
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($orderNo, $mobile, $param, $isp = '')
    {
        $data = [
            "username" => $this->username,
            "mobile" => $mobile,
            'orderNo' => $orderNo . "",
            "amount" => sprintf('%.2f', $param['param1']),
            'productType' => $param['param2'],//产品类型：fast_call 快充话费,slow_call慢充话费
            "notifyUrl" => $this->notify_url,
            "timestamp" => time() . ""
        ];
        $signstr = "";
        foreach ($data as $k => $v) {
            $signstr .= $v;
        }
        $signstr .= $this->key;
        $data['special'] = 0;
        $data['sign'] = strtoupper(md5(urldecode($signstr)));
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
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, json_encode($param, JSON_UNESCAPED_UNICODE));
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, ["Content-Type:application/json; charset=utf-8"]);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            $result = json_decode($sContent, true);
            if ($result['status_code'] == 200) {
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
        $str = file_get_contents("php://input");
        $data = json_decode($str, true);
        if ($data['order_status'] == 3) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('piaoliang', $data['orderNo'], $data);
            echo "ok";
        } else if (in_array($data['order_status'], [4, 5])) {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('piaoliang', $data['orderNo'], $data);
            echo "ok";
        }
    }

}