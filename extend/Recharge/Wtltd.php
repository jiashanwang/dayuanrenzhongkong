<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;

/**
 * 电费 http://ip/api/receiveOrder
 **/
class Wtltd
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
        $teltype = $param['param2'];//1住宅，2店铺，3企事业
        $price = $param['param1'];
        $area = str_replace('省', '', str_replace('市', '', $isp));
        $sign_str = $this->mchid . $out_trade_num . $mobile . $price . $teltype . $area . $this->notify . $this->apikey;
        $sign = md5(urldecode($sign_str));
        $data = [
            "partner_id" => $this->mchid,
            "account" => $mobile,
            "partner_order_no" => $out_trade_num,
            "amount" => $price,
            "type" => $teltype,
            "area" => $area,
            'notify_url' => $this->notify,
            'sign' => $sign
        ];
        isset($param['oparam1']) && $data['id_card_no'] = $param['oparam1'];
        return $this->http_post($this->apiUrl, $data);
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
        $charge_amount = intval(I('charge_amount'));
        $state = intval(I('status'));
        if ($state == 2) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('wtltd', I('partner_order_no'), $_POST, I('remarks'));
            echo "success";
        } else if (in_array($state, [-1, -2, -3])) {
            //充值失败,根据自身业务逻辑进行后续处理
            if ($charge_amount == 0) {
                PorderModel::rechargeFailApi('wtltd', I('partner_order_no'), $_POST, I('remarks'));
            } else {
                PorderModel::rechargePartApi('wtltd', I('partner_order_no'), $_POST, I('remarks') . "|部分充值：" . I('charge_amount'));
            }
            echo "success";
        } else if ($state == 1) {
            //部分充值
            PorderModel::rechargePartApi('wtltd', I('partner_order_no'), $_POST, "部分充值：" . I('charge_amount'));
            echo "success";
        } else if ($state == 0) {
            //订单取消
            if ($charge_amount == 0) {
                PorderModel::rechargeFailApi('wtltd', I('partner_order_no'), $_POST, I('remarks'));
            } else {
                PorderModel::rechargePartApi('wtltd', I('partner_order_no'), $_POST, I('remarks') . "|部分充值：" . I('charge_amount'));
            }
            echo "success";
        }
    }
}