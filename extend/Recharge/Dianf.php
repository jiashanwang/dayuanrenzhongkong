<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;

/**
 * 电费 接入网关/Pay_Api_upmerchant.gt
 **/
class Dianf
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
        $area = str_replace('省', '', str_replace('市', '', $isp));
        $data = [
            "merchant_id" => intval($this->mchid),
            "userid" => $mobile,
            "limit" => $param['param1'],//充值金额
            "type" => $param['param3'],//gsgw:国上国网，nwzx:南网在线
            "pcode" => $param['param3'] == 'nwzx' ? $param['oparam1'] : $this->getpcode($area),
            'notifyurl' => $this->notify,
            "close_time" => $param['param2']//关闭时间，单位：分钟
        ];
        ksort($data);
        $sign_str = urldecode(http_build_query($data) . "&key=" . $this->apikey);
        $data['sign'] = strtoupper(md5($sign_str));
        $res = $this->http_post($this->apiUrl, $data);
        $res['errno'] == 0 && M('porder')->where(['api_order_number' => $out_trade_num])->setField(['api_trade_num' => $res['data']['orderid']]);
        return $res;
    }

    public function getpcode($area)
    {
        $data = [
            '福建' => 35101,
            '浙江' => 33101,
            '天津' => 12101,
            '江苏' => 32101,
            '甘肃' => 62101,
            '内蒙' => 15101,
            '陕西' => 61102,
            '河北' => 13102,
            '辽宁' => 21102,
            '安徽' => 34101,
            '湖南' => 43101,
            '湖北' => 42102,
            '北京' => 11102,
            '新疆' => 65101,
            '上海' => 31102,
            '四川' => 51101,
            '山东' => 37101,
            '宁夏' => 64101,
            '江西' => 36101,
            '重庆' => 50101,
            '贵州' => 52101,
            '青海' => 63101,
            '西藏' => 54101,
            '黑龙江' => 23101,
            '山西' => 14101,
            '河南' => 41101,
        ];
        foreach ($data as $k => $v) {
            if (strstr($k, $area)) {
                return $v;
            }
        }
        return 0;
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
            if ($result['status'] == 'ok') {
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
        $limit = intval(I('limit'));
        $money = intval(I('money'));
        $orderid = I('orderid');
        $order_number = M('porder')->where(['api_trade_num' => $orderid])->value('api_order_number');
        if (!$orderid || !$order_number) {
            echo "fail";
            return;
        }
        if ($limit == $money) {
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('dianf', $order_number, $_POST);
            echo "OK";
        } else if ($money == 0) {
            //充值失败,根据自身业务逻辑进行后续处理
            PorderModel::rechargeFailApi('dianf', $order_number, $_POST);
            echo "OK";
        } else if ($limit >= $money) {
            //部分充值
            PorderModel::rechargePartApi('dianf', $order_number, $_POST, "部分充值：" . $money);
            echo "OK";
        }
    }
}