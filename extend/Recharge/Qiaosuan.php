<?php


namespace Recharge;


use app\common\model\Porder as PorderModel;
use think\Log;

/**
 * @description 巧算中控
 */
class Qiaosuan
{
    private $mchid;//商户编号
    private $apikey;
    private $notify;
    private $apiurl;//下单接口地址

    public function __construct($option)
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['notify']) ? $option['notify'] : (isset($option['param3']) ? $option['param3'] : '');
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
    }

    /**
     * 提交充值号码充值
     */
    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        
        $data = array(
            "username" => $this->mchid,
            "order_code" => $out_trade_num,
            "account" => $mobile,
            "amount" => intval($param['param1']),
            "type" => $param['param2'],
            "lineid"=>$param['param3'],
            "area" => $param['guishu_pro'],
            "city" => $param['guishu_city'],
            'notify_url' => $this->notify
        );

        $sign_str = $data['username'].$data['order_code'].$data['account'].$data['amount'].$data['type'].$this->apikey;
        $data['sign'] = md5($sign_str);
        isset($param['oparam1']) && $data['id_card_no'] = $param['oparam1'];
        isset($param['oparam2']) && $data['card_type'] = $param['oparam2'];

        return $this->http_post($this->apiurl, $data);//提交下单接口地址
    }

    /**
    * 查询订单状态
    */
    public function check($partner_order_no, $mobile)
    {
        $reqData = array(
            "username" => $this->mchid,
            "order_code" => $partner_order_no,
            "account" => $mobile
        );
        
        $sign_str = $reqData['username'].$reqData['order_code'].$reqData['account'].$this->apikey;
        $reqData['sign'] = md5($sign_str);
        
        $url = str_replace("order", "query_order", $this->apiurl);
        return $this->http_post($url, $reqData);
    }

    /**
    * 订单自查方法
    */
    public function selfcheck($param)
    {
        $retJson = $this->check($param['api_order_number'], $param['mobile']);
        Log::error("巧算==".json_encode($retJson));
        if($retJson['errno']==0){
            if($retJson['data']['code']==1){
                $data = $retJson['data']['data'];
                if($data['status'] == 1){
                    PorderModel::rechargeSusApi('Qiaosuan', $param['api_order_number'], $data);
                }else if ($data['charged'] > 0) {
                    PorderModel::rechargeRateApi('Qiaosuan',$param['api_order_number'], $data, $data['charged']);
                }
            }
        }
    }
    
         /**
     * 取消
     */
    private function remove($out_trade_num, $mobile)
    {
        $reqData = array(
            "username" => $this->mchid,
            "order_code" => $out_trade_num
        );
        
        $sign_str = $reqData['username'].$reqData['order_code'].$this->apikey;
        $reqData['sign'] = md5($sign_str);
        
        $url = str_replace("order", "cancel_order", $this->apiurl);
        return $this->http_post($url, $reqData);
    }
    
    
    /**
    * 自助撤单
    */
    public function selfremove($params){
        $retJson = $this->remove($params['api_order_number'], $params['mobile']);
        Log::error("巧算撤单==".json_encode($retJson));
    }
    
    
    public function notify()
    {
        //订单状态(0充值中；1充值完成；2充值失败；3部分充值；)
        $charge_status = intval(I('status'));
        //回调订单号
        $order_number = I('order_code');
        //已充值金额
        $charge_amount = intval(I('charged'));
        
        if (!$order_number) {
            echo "fail";
            return;
        }
        
        if($charge_status==1){
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('Qiaosuan', $order_number, $_POST, '充值成功');
            echo "success";
        }else {
            if ($charge_amount == 0) {
                //充值失败,根据自身业务逻辑进行后续处理
                PorderModel::rechargeFailApi('Qiaosuan', $order_number, $_POST, '手动取消');
                echo "success";
            }else{
                //部分充值
                PorderModel::rechargePartApi('Qiaosuan', $order_number, $_POST, "部分充值:" . $charge_amount, $charge_amount);
                echo "success";
            }
        }
    }
        
    /**
     * post请求
     */
    private function http_post($url, $param)
    {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            // 关闭https验证
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
        //不取得返回头信息
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        //设置请求头信息
        $header = array(
            //'Content-Type: multipart/form-data',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            //'Content-Type: application/json',
        );
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
        
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 0) {
            return rjson(0, "http状态码0 无法确认是否提交成功请查看渠道", 'http状态码0 无法确认是否提交成功请查看渠道');
        }else {
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
    }
}