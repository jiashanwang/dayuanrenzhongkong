<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;

/**
 * 电费 http://ip/api/receiveOrder
 **/
class Wtltdbsj
{
    private $mchid;//商户编号
    private $apikey;
    private $notify;
    private $apiUrl;//话费充值接口

    public function __construct($option)
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['notify']) ? $option['notify'] : (isset($option['param3']) ? $option['param3'] : '');
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
        isset($param['oparam2']) && $data['verif_type'] = $param['oparam2'];
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


    /**
     *查询订单状态
     */
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
        if ($res['errno'] == 0 && isset($res['data']['data']['status']) && $res['data']['data']['partner_id'] == $partner_id) {
            if ($res['data']['data']['status'] == 2 ) {
                PorderModel::rechargeSusApi('wtltdbsj', $param['api_order_number'], $res['data'], "完成充值:" . $res['data']['data']['charge_amount']);
            } elseif (in_array($res['data']['data']['status'], [-1, 1])) {
                if ($res['data']['data']['charge_amount'] != $res['data']['data']['amount'] && $res['data']['data']['charge_amount'] > 0) {
                    //充值进度变化
                    PorderModel::rechargeRateApi('wtltdbsj', $param['api_order_number'], $res['data'], $res['data']['data']['charge_amount']);
                }
            }
        }
    }


    public function notify()
    {
        if (I('selfcheck')) {
            //自查
            $porders = M('porder p')
                ->join('reapi r', 'r.id=p.api_cur_id')
                ->where(['r.callapi' => 'Wtltdbsj', 'p.status' => 3])
                ->order('p.pegging_time asc ,p.create_time asc')->limit(100)
                ->field('p.id,p.mobile,p.api_order_number,p.api_trade_num,p.api_cur_param_id,p.api_cur_id')
                ->select();
            foreach ($porders as $k => $order) {
                $option = M('reapi')->where(['id' => $order['api_cur_id']])->find();
                $hangmin = new Wtltdbsj($option);
                $res = $hangmin->check($order['api_order_number']);
                M('porder')->where(['id' => $order['id']])->setField(['pegging_time' => time()]);
                if ($res['errno'] == 0 && isset($res['data']['data']['status'])) {
                    if ($res['data']['data']['status'] == 2 ) {
                        PorderModel::rechargeSusApi('wtltdbsj', $order['api_order_number'], $res['data'], "|完成充值:" . $res['data']['data']['charge_amount']);
                    } elseif (in_array($res['data']['data']['status'], [-1, 1])) {
                        if ($res['data']['data']['charge_amount'] != $res['data']['data']['amount'] && $res['data']['data']['charge_amount'] > 0) {
                            M('porder')->where(['id' => $order['id'], 'status' => ['in', [3]]])->setField(['remark' => "已充值：" . $res['data']['data']['charge_amount']]);
                            echo "已充值:" . $order['api_order_number'] . '-' . $res['data']['data']['charge_amount'] . '<br/>';
                        }
                    }
                }
            }
        } else {
            //回调
		
            $charge_amount = intval(I('charge_amount'));
			$amount = intval(I('amount'));
            $state = intval(I('status'));
            if ($state == 2) {
                PorderModel::rechargeSusApi('wtltdbsj', I('partner_order_no'), $_POST, "完成充值:" . I('charge_amount'));
                echo "success";
            } elseif (in_array($state, [-1, 0, 1, 2])) {
                if ($charge_amount == 0) {
					PorderModel::rechargeFailApi('wtltdbsj', I('partner_order_no'), $_POST, I('remarks'));
					echo "success";
                } else {
                    PorderModel::rechargePartApi('wtltdbsj', I('partner_order_no'), $_POST, "|部分充值：" . I('charge_amount'));
					echo "success";
                }
                
            }
        }

    }
}