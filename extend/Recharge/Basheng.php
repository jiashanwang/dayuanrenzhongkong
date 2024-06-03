<?php


namespace Recharge;


use app\common\model\Porder as PorderModel;
use think\Log;

/**
 * @description 八省专线
 */
class Basheng
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
        $timestamps = time();
        //Log::error("Basheng猿人的参数==".json_encode($param));
		$area = str_replace('省', '', str_replace('市', '', $isp));
		$city = $param['guishu_city'];
		if (!$city) {
            $city = $param['oparam3'];
        }
        
        $data = [
            "id" => $this->mchid,//供应商ID
			"sn" => $out_trade_num,//订单号
			"card" => $mobile, //充值号码
			"amount" => $param['param1'],//充值金额
			"notify_url" => $this->notify,
			"timestamp" => $timestamps,
			"type" => 5,//5:电费, 6:话费, 7:石化, 8:中燃, 9:新奥, 10:华润, 11:港华, 12:北京燃气, 13:天翼电费, 14:昆仑燃气  默认:5
			"area" => $this->getareacode($area),//电费地区编码见“接口说明”
			"city" => $this->getcitycode($city)
         ];

        $data['sign'] = $this->sign_str($data,$this->apikey);
		
        //Log::error("Basheng整合的参数==".json_encode($data));
        return $this->http_post($this->apiurl, $data);//提交下单接口地址
    }

    
    private function getcitycode($city){
        $mycity = '%' . $city . '%';//模糊查询
        $option = M('region')->where(['name' => array('like', $mycity)])->find();
        if(!$option){
            return 123456;
        }
        
        return $option['id'];
    }

	private function getareacode($area){
		$data = [
		'北京市' => 11102,
		'天津市' => 12101,
		'河北省' => 13102,
		'冀北' => 13103,
		'山西省' => 14101,
		'内蒙古自治区' => 15101,
		'辽宁省' => 21102,
		'吉林省' => 22101,
		'黑龙江省' => 23101,
		'上海市' => 31102,
		'江苏省' => 32101,
		'浙江省' => 33101,
		'安徽省' => 34101,
		'福建省' => 35101,
		'江西省' => 36101,
		'山东省' => 37101,
		'河南省' => 41101,
		'湖北省' => 42102,
		'湖南省' => 43101,
		'重庆市' => 50101,
		'四川省' => 51101,
		'贵州省' => 52101,
		'西藏自治区' => 54101,
		'陕西省' => 61102,
		'甘肃省' => 62101,
		'青海省' => 63101,
		'宁夏回族自治区' => 64101,
		'新疆维吾尔自治区' => 65101,
        ];
        foreach ($data as $k => $v) {
            if (strstr($k, $area)) {
                return $v;
            }
        }
        return 12345;
	}

    private function sign_str($params,$secret)
    {
		ksort($params);
		$str = '';
		foreach ($params as $k => $v) {
			$str = $str . $k . $v;
		}
		$str = $secret . $str . $secret;
		return strtoupper(md5($str));
	}
	
    /**
     *查询订单状态
     */
    private function check($out_trade_num)
    {
        //$sign_str = $this->mchid . $out_trade_num . $this->apikey;
        //$sign = md5(urldecode($sign_str));
        $data = [
            "id" => $this->mchid,
            "sn" => $out_trade_num,
            'timestamp' => time()
        ];
		$data['sign'] = $this->sign_str($data,$this->apikey);
		
        return $this->http_post(str_replace('index', 'query', $this->apiurl), $data);
    }
     /**
     * 取消
     */
    private function remove($out_trade_num)
    {
        //$sign_str = $this->mchid . $out_trade_num . $this->apikey;
        //$sign = md5(urldecode($sign_str));
        $data = [
            "id" => $this->mchid,
            "sn" => $out_trade_num,
            'timestamp' => time()
        ];
		$data['sign'] = $this->sign_str($data,$this->apikey);
		
        return $this->http_post(str_replace('index', 'cancel', $this->apiurl), $data);
    }
    
    
    /**
    * 自助撤单
    */
    public function selfremove($params){
        $retJson = $this->remove($params['api_order_number']);
    }
    
    
    public function notify()//回调函数
    {
        if (I('selfcheck')) {
            //自查
            $porders = M('porder p')
                ->join('reapi r', 'r.id=p.api_cur_id')
                ->where(['r.callapi' => 'Basheng', 'p.status' => 3])
                ->order('p.pegging_time asc ,p.create_time asc')->limit(10)
                ->field('p.id,p.mobile,p.api_order_number,p.api_trade_num,p.api_cur_param_id,p.api_cur_id')
                ->select();
            foreach ($porders as $k => $order) {
                $option = M('reapi')->where(['id' => $order['api_cur_id']])->find();
                
                $hangmin = new Basheng($option);
                $res = $hangmin->check($order['api_order_number']);
                //print_r($res);
                //Log::error("Basheng-selfcheck的参数==".$order['mobile']);
                
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
        	//回调状态码status // 0:待充值, 1:充值中, 2:已完成, 3:异常, 4:退单, 5:暂停
    		$charge_status = I('status');
    		//完成金额amount
    		$charge_amount = intval(I('amount'));
    		//备注
    		$remarks = I('remark');
    		//回调订单号sn
    		$order_number = I('sn');
    		if (!$order_number) {
    			echo "fail";
    			return;
    		}
    
    		if ($charge_status == 2) {
    			//充值成功,根据自身业务逻辑进行后续处理
    			PorderModel::rechargeSusApi('basheng', $order_number, $_POST, '完成充值');
    			echo "success";
    		} else {
    			if ($charge_amount == 0) {
    				//充值失败,根据自身业务逻辑进行后续处理
    				PorderModel::rechargeFailApi('basheng', $order_number, $_POST,  $remarks);
    				echo "success";
    			}else{
    				//部分充值
    				PorderModel::rechargePartApi('basheng', $order_number, $_POST, "部分充值:" . $charge_amount, $charge_amount);
    				echo "success";
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
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, json_encode($param));
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 90);
		//不取得返回头信息
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
		//设置请求头信息
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);//X-Requested-With: XMLHttpRequest
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