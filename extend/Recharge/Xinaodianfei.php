<?php


namespace Recharge;


use app\common\model\Porder as PorderModel;
use think\Log;

/**
 * @description 云/国网
 */
class Xinaodianfei
{
    private $mchid;
    private $apikey;
    private $notify;
    private $apiurl;//下单接口地址

    public function __construct($option)//参数初始化构造函数 $option对应接口配置参数
    {
        $this->mchid = isset($option['param1']) ? $option['param1'] : '';
        $this->apikey = isset($option['param2']) ? $option['param2'] : '';
        $this->notify = isset($option['param3']) ? $option['param3'] : '';
        $this->apiurl = isset($option['param4']) ? $option['param4'] : '';
    }


    public function recharge($out_trade_num, $mobile, $param, $isp = '')
    {
        /*$area = str_replace('省', '', str_replace('市', '', $isp));
        
        if(in_array($area, ['天津','上海','重庆','北京'])){
            $area = $area .'-市辖区';
        }else{
            if(strpos($param['guishu_city'], '%u5E02')){
                $area = $area .'-' . $param['guishu_city'];
            }else{
                $area = $area .'-' . $param['guishu_city'] . '市';
            }
        }*/
        //省-城市-电力公司|cityCode|companyId:省-城市-电力公司
        $city_code =1;
        $company_code =1;
        $area = '';
        if (!empty($param['oparam2'])) {
            $codeparams = explode("|", $param['oparam2']);
            $city_code = $codeparams[1];
            $company_code = $codeparams[2];
            
            $codeparams = explode("-", $param['oparam2']);
            $area = $codeparams[0] .'-' .$codeparams[1];
        }else{
            $area = $param['guishu_pro'] .'-' .$param['guishu_city'];
        }

        $data = [
            "partner_id" => $this->mchid,
            "partner_order_no" => $out_trade_num,
            "account" => $mobile, 
            "type" => $param['param2'],
            "amount" => $param['param1'],
            "area" => $area,
            "notify_url" => $this->notify
         ];
         
         if(intval($data['type'])==16 || intval($data['type'])==17){
            $data['city_code'] = $city_code;
            $data['company_code'] = $company_code;
         }
        
        $data['sign'] = $this->makeSign($data,$this->apikey);
        return $this->http_post($this->apiurl, $data);//提交下单接口地址
    }

    private function makeSign(array $params = [], $secretKey)
    {
        $urlParams = '';
        if (!empty($params)){
            $newArr = $params;
            ksort($newArr);
            foreach ($newArr as $key => $value){
                if (!empty($value))$urlParams.= $key.'=' .$value.'&';
            }
            
            //$urlParams = rtrim($urlParams, '&');
            $urlParams = $urlParams .'key=' .$secretKey;
        }
        
        return strtolower(md5($urlParams));
    }
    
    private function remove($out_trade_num, $mobile)
    {
        $data = [
            "partner_id" => $this->mchid,
            "partner_order_no" => $out_trade_num
        ];
        $data['sign'] = $this->makeSign($data,$this->apikey);

        $retJson = $this->http_post(str_replace('Payment_Dfpay_dianfei', 'Payment_Dfpay_close', $this->apiurl), $data);
        return $retJson;
    }
    
    public function selfremove($params){
        $retJson = $this->remove($params['api_order_number'], $params['mobile']);
        if($retJson['errno']==0){
            if(isset($retJson['data']['code']) && $retJson['data']['code']==1){
                $resultCheckJson = $this->check($params['api_order_number'], $params['mobile']);
                if($resultCheckJson['errno']==0 && isset($resultCheckJson['data']['code']) && $resultCheckJson['data']['code']==1 &&  !empty($resultCheckJson['data']['data'])){
                        $data = $resultCheckJson['data']['data'];
                        if ($data['status']==90 && $data['charge_amount']==0) {
                            PorderModel::rechargeFailApi('xinaodianfei', $params['api_order_number'], $data,  'api取消');
                        }
                }
            }
        }
    }
    
    private function check($out_trade_num, $mobile)
    {
        $data = [
            "partner_id" => $this->mchid,
            "partner_order_no" => $out_trade_num
        ];
        $data['sign'] = $this->makeSign($data,$this->apikey);
        
        $retJson = $this->http_post(str_replace('Payment_Dfpay_dianfei', 'Payment_Dfpay_query', $this->apiurl), $data);
        return $retJson;
    }
    
    public function selfcheck($param)
    {
        $retJson = $this->check($param['api_order_number'], $param['mobile']);
        if($retJson['errno']==0){

            if(isset($retJson['data']['code']) && $retJson['data']['code']==1 &&  !empty($retJson['data']['data'])){
                $data = $retJson['data']['data'];
                
                if ($data['charge_amount'] > 0) {
                    PorderModel::rechargeRateApi('xinaodianfei',$param['api_order_number'], $data, $data['charge_amount']);
                }
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
        if (intval($aStatus["http_code"]) == 200) {
            $result = json_decode($sContent, true);
            
            if ($result['code'] == 1) {
                return rjson(0, $result['msg'], $result);
            } else {
                return rjson(1, $result['msg'], $result);
            }
        } else {
            return rjson(500, '接口访问失败，无法确认是否提交渠道，http错误码' . $aStatus["http_code"]);
        }
    }
    
    public function notify()//回调函数
    {
        if (I('selfcheckxxxxxx')) {
            //自查
            $porders = M('porder p')
                ->join('reapi r', 'r.id=p.api_cur_id')
                ->where(['r.callapi' => 'Xinaodianfei', 'p.status' => 3])
                ->order('p.pegging_time asc ,p.create_time asc')->limit(10)
                ->field('p.id,p.mobile,p.api_order_number,p.api_trade_num,p.api_cur_param_id,p.api_cur_id')
                ->select();
            foreach ($porders as $k => $order) {
                $option = M('reapi')->where(['id' => $order['api_cur_id']])->find();
                
                $hangmin = new Xinaodianfei($option);
                $res = $hangmin->check($order['api_order_number']);
                //print_r($res);
                
                M('porder')->where(['id' => $order['id']])->setField(['pegging_time' => time()]);
                if ($res['errno'] == 0 && isset($res['data']['data']['status'])) {
                    
                    if ($res['data']['data']['status'] == 0  && $res['data']['data']['amount']>0) {
                        M('porder')->where(['id' => $order['id'], 'status' => ['in', [3]]])->setField(['remark' => "已充值：" . $res['data']['data']['amount']]);
                            //echo "已充值:" . $order['api_order_number'] . '-' . $res['data']['data']['amount'] . '<br/>';
                            print_r($order['api_order_number'] . "-已充值:" . $res['data']['data']['amount'] ."\n");
                    }
                }
            }
        }else {
            $retJson=file_get_contents("php://input");
            $charge_status = I('status');
            //下单面值amount
            $order_amount = intval(I('amount'));
            //完成金额amount
            $charge_amount = intval(I('charge_amount'));
            //备注
            $remarks = I('remark');
            //回调订单号sn
            $order_number = I('partner_order_no');
            if (!$order_number) {
            	echo "fail";
            	return;
            }
            //Log::error("Xinaodianfei回调order_amount=" .$order_amount . ", charge_amount=".$charge_amount);
            if ($charge_amount>1 && $charge_amount==$order_amount) {
                //充值成功,根据自身业务逻辑进行后续处理
                PorderModel::rechargeSusApi('xinaodianfei', $order_number, $retJson, '完成充值');
                echo json_encode(["code" => 100,"msg" => "success"]);
            }elseif ($charge_amount>1 && $charge_amount<$order_amount ) {
                //部分充值
                PorderModel::rechargePartApi('xinaodianfei', $order_number, $retJson, "已充值:" .$charge_amount . "【系统仍在继续充值，请勿自行充值, 撤单联系上级】", $charge_amount);
                echo json_encode(["code" => 100,"msg" => "success"]); 
            }elseif($charge_amount==0) {
                //充值失败,根据自身业务逻辑进行后续处理
                PorderModel::rechargeFailApi('xinaodianfei', $order_number, $retJson,  '手动取消');
                echo json_encode(["code" => 100,"msg" => "success"]);
            }else{
                echo "fail";
            }
        }
    }
}