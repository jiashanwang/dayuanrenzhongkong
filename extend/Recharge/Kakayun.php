<?php


namespace Recharge;


use app\common\model\Porder as PorderModel;
use think\Log;

/**
 * @description 卡卡云商城: https://www.kancloud.cn/kakayun001/api_dock/2052058
 */
class Kakayun
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
            "userid" => $this->mchid,
            'goodsid' => $param['param1'], //商品编码
            'buynum' => 1,                 //购买数量
            "outorderno" => $out_trade_num,
            'maxmoney' => $param['param2'], //最大成本价，防止亏本
            "attach" => $mobile,
            'callbackurl' => $this->notify
        );

        $data['sign'] = $this->makeSign($data, $this->apikey);

        return $this->http_post($this->apiurl, $data);//提交下单接口地址
    }


    /**
     * 生成签名
     */
    public function makeSign(array $params = [], $secretKey = '')
    {
        $sign_str = '';
        if(empty($params)){
            return $sign_str;
        }
        
        ksort($params); 
        reset($params); 
        
        foreach ($params as $key => $value){
            if (empty($value) || $key == 'sign') continue; 
            $sign_str.= $key .'=' .$value .'&';
        }
        $sign_str = rtrim($sign_str, '&');
        
        $sign_str = $sign_str .$secretKey;
        
        return md5($sign_str);
    }
    
    
    private function queryprice($params){
        $data = array(
            "userid" => $this->mchid,
            "goodsid" => $params['param1']
        );
        $data['sign'] = $this->makeSign($data, $this->apikey);
       
        $retJson = $this->http_post(str_replace('/dockapi/index/buy', '/dockapi/v2/goodsdetails.html', $this->apiurl), $data);
        return $retJson;
    }
    
    public function selfprice($params) {
        $retJson = $this->queryprice($params);
        // $retJson= '{"errno":0,"errmsg":"操作成功","data":{"code":0,"msg":"操作成功","result":{"discount":99.5,"goodsId":100193,"goodsName":"充值卡20元","price":19.9}}}';
        // $retJson = json_decode($retJson , true);

        if($retJson['errno']==0 && isset($retJson['data']['goodsdetails']) && isset($retJson['data']['goodsdetails']['goodsprice'])){
            
            $newprice = floatval($retJson['data']['goodsdetails']['goodsprice']);
            
            $p_price = floatval($params['price']);

            if($newprice != $p_price){

                M('product')->where(array('id' => $params['id']))->setField(['price' => $newprice]);
                //M('reapi_param')->where(array('id' => $params['reapi_param_id']))->setField(['param1' => $newprice]);
            }
        }
    }
    
    public function notify()
    {
        /*{
            "orderno": "MEE23071423665A30N1",
            "outorderno": "D202307140150438195865224",
            "userid": "19560",
            "status": "5",
            "refundstatus": "0",
            "money": "3.3500",
            "refundmoney": "0.0000",
            "receipt": "\u5145\u503c\u6210\u529f",
            "refundreceipt": "",
            "create_time": "1689270643",
            "update_time": "1689270657",
            "timestamp": "1689270660",
            "sign": "bc0f9303358f24b6f32b44c7d7716bda"
        }*/
        
        // 充值状态：status: 4：撤回订单 5：确定发货
        $charge_status = intval(I('status'));
        // 退款状态：refundstatus：0 未退款 1 已全部退 2 部分退款 3 仅仅标记退款 4 原路退款
        $refund_status = intval(I('refundstatus'));
        //订单回执消息
        $remarks = I('receipt');
        //回调订单号
        $order_number = I('orderno');
        
        if (!$order_number) {
            echo "fail";
            return;
        }
        
        if($charge_status==5){
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('kakayun', $order_number, $_POST, $remarks);
            echo "ok";
        }else {
            if (in_array($refund_status, [1, 4])) {
                //充值失败,根据自身业务逻辑进行后续处理
                PorderModel::rechargeFailApi('kakayun', $order_number, $_POST,  I('refundreceipt'));
                echo "ok";
            }
            
            if($refund_status == 2){
                //部分退款：使用充值进度变化函数告诉管理员，这个订单部分充值了
                PorderModel::rechargeRateApi('kakayun', $order_number, $_POST, "订单已经部分充值了，请联系渠道处理！");
                echo "ok";
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
        /*if (is_string($param)) {
            $strPOST = $param;
        } else {
            $strPOST = http_build_query($param);
        }*/
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $param);
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
        //不取得返回头信息
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        //设置请求头信息
        $header = array(
            'Content-Type: multipart/form-data',
            //'Content-Type: application/x-www-form-urlencoded',
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