<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;

/**
 * 话费系统API请求地址 http://ip/api/receiveOrder
 **/

class Porschehf
{
    private $mchid;
    private $apikey;
    private $notify;
    private $apiUrl;

    public function __construct($option)
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['notify']) ? $option['notify'] : (isset($option['param3']) ? $option['param3'] : '');
        $this->apiUrl = isset($option['param4']) ? $option['param4'] : '';
    }

    //提交充值号码充值
    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        $teltype = $this->get_teltype($isp);
        $price = $param['param1'];
        $sign_str = $this->mchid . $out_trade_num . $mobile . $price . $teltype . $this->notify . $this->apikey;
        $sign = md5(urldecode($sign_str));
        $data = [
            "partner_id" => $this->mchid,
            "phone" => $mobile,
            "partner_order_no" => $out_trade_num,
            "amount" => $price,
            "type" => $teltype,
            'notify_url' => $this->notify,
            'sign' => $sign
        ];
        return $this->http_post($this->apiUrl, $data);
    }
    
     private function get_teltype($str)
    {
        switch ($str) {
            case '联通':
                return '1';
            case '移动':
                return '2';
            case '电信':
                return '3';
            default:
                return '';
        }
    }

    //get请求
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


    //查询订单
    public function check($out_trade_num)
    {
        $sign_str = $this->mchid . $out_trade_num . $this->apikey;
        $sign = md5(urldecode($sign_str));
        $data = [
            "partner_id" => $this->mchid,
            "partner_order_no" => $out_trade_num,
            'sign' => $sign
        ];
        return $this->http_post(str_replace('receiveOrder', 'queryOrder', $this->apiUrl), $data);
    }

    //订单自查方法
    public function selfcheck($param)
    {
        $partner_id = M('reapi')->where(['id' => $param['api_cur_id']])->value('param1');
        if (!$partner_id) {
            return;
        }
        $res = $this->check($param['api_order_number']);
        var_dump($res);
        if ($res['errno'] == 0 && isset($res['data']['data']['status']) && $res['data']['data']['partner_id'] == $partner_id) {
            if ($res['data']['data']['status'] == 1) {
                PorderModel::rechargeSusApi('Porschehf', $param['api_order_number'], $res['data']);
            } elseif (in_array($res['data']['data']['status'], [-1, 1])) {
                if ($res['data']['data']['charge_amount'] != $res['data']['data']['amount'] && $res['data']['data']['charge_amount'] > 0) {
                    //充值进度变化
                    PorderModel::rechargeRateApi('Porschehf', $param['api_order_number'], $res['data'], $res['data']['data']['charge_amount']);
                }
            }
        }
    }


    //回调查询
   public function notify()
    {
        $state = intval(I('status'));
        if ($state == 1) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('Porschehf', I('partner_order_no'), $_POST);
            echo "success";
        } else if ($state == -1) {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('Porschehf', I('partner_order_no'), $_POST);
            echo "success";
            }
            echo "接口名称:屌毛船长";
        }
}