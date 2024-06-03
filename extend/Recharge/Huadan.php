<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;
use think\Log;//引用LOG
/**
 * 电费 接入网关/Pay_Api_upmerchant.gt
 **/
class Huadan
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
        
         if(!($this->mchid && $this->apikey && $this->notify && $this->apiUrl)){
            Log::error("Huadan参数不全");
            return rjson(500, '接口访问失败，参数不全');
          
        }
        
         //Log:: error ("HuadanHuadan整合的参数".json_encode($param));
         //Log:: error ("Huadan整合的参数".json_encode($isp));
        $data = [
            "hd_mchid"=>$this->mchid,
            "hd_orderid"=>$out_trade_num,
            "hd_tel"=>$mobile,
            "hd_price"=>$param['param1'],
            "hd_teltype"=>$param['param2'],
            "hd_timeout"=>$param['param3'],
            "hd_notify"=>$this->notify,
            "rand"=>rand(100000,999999)
        ];
        //Log:: error ("Huadan整合的参数".json_encode($data));
      
        
        
        $data['sign'] = $this->makeSign($data,$this->apikey);
        // $res = $this->http_post($this->apiUrl, $data);
        $res = $this->exec_curl($this->apiUrl,true, $data);
        // $res['errno'] == 0 && M('porder')->where(['api_order_number' => $out_trade_num])->setField(['api_trade_num' => $res['data']['orderid']]);
        
        //Log:: error ("Huadan整合的参数222".$res);
        //Log:: error ("Huadan整合的参数222".json_encode($res));
        $res = $this->object_to_array($res);
        $res["data"] = $res;
        $res["errno"] = 0;
        $res["errmsg"] = 1111;
        return $res;
    }
    
    function object_to_array($obj) {
            $obj = (array)$obj;
            foreach ($obj as $k => $v) {
                if (gettype($v) == 'resource') {
                    return;
                }
                if (gettype($v) == 'object' || gettype($v) == 'array') {
                    $obj[$k] = (array)object_to_array($v);
                }
            }
         
            return $obj;
    }
    
    
    function makeSign($params, $appsecret)
    {
        
        // md5(hd_mchid+hd_tel+hd_orderid+hd_price+hd_teltype+hd_timeout + hd_notify + rand + 商户秘钥);
        
        $str = $params["hd_mchid"].$params["hd_tel"].$params["hd_orderid"].$params["hd_price"].$params["hd_teltype"].$params["hd_timeout"].$params["hd_notify"].$params["rand"].$appsecret;
        
        return md5($str);
    }
        

    function exec_curl($url, $ispost = false, $data = array(), $in = 'utf8', $out = 'utf8', $cookie = '')
    {

        if (function_exists('file_get_contents')) {
            if ($ispost) {
                if($data)
                {
                   $query   = http_build_query($data); 
               }else{
                    $query = '';
               }
                // halt($url);
                $options = array('http' => array('method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content'  => $query));

                $cxContext = stream_context_create($options);

                $fm        = @file_get_contents($url, false, $cxContext);
            } else {
                $fm = @file_get_contents($url);
            }
        } else {
            $fn = curl_init();
            curl_setopt($fn, CURLOPT_URL, $url);
            curl_setopt($fn, CURLOPT_TIMEOUT, 30);
            curl_setopt($fn, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($fn, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($fn, CURLOPT_REFERER, $url);
            curl_setopt($fn, CURLOPT_HEADER, 0);
            if ($cookie) {
                curl_setopt($fn, CURLOPT_COOKIE, $cookie);
            }

            if ($ispost) {
                curl_setopt($fn, CURLOPT_POST, true);
                curl_setopt($fn, CURLOPT_POSTFIELDS, $data);
            }
            $fm = curl_exec($fn);
            curl_close($fn);
            if ($in != $out) {
                $fm = Newiconv($in, $out, $fm);
            }
        }

        return $fm;
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
		 //回调
		$charge_status = intval(I('status'));
		$charge_amount = intval(I('charge_amount'));
		$hd_orderid = I('hd_orderid');
		
		if ($charge_status == 1) {//1充值成功
			PorderModel::rechargeSusApi('huadan', $hd_orderid, $_POST, "完成充值");
			echo "OK";
		} elseif ($charge_status == 2 || $charge_status == 3) {//2已退款  3 已超时/已失败
			//充值失败
			$result= PorderModel::rechargeFailApi('huadan', $hd_orderid, $_POST, "失败状态码 -".$charge_status);
			if ($result) {
				echo "OK";
			}else{
				echo "fail--《1》数据库或者日志写入出错\n<2> 数据库记录已经存在日志写入过";
			}
		} else if($charge_status == 0) { 
			//0充值中， 没有部分充值，暂时没处理
			//部分充值
			PorderModel::rechargePartApi('huadan', $hd_orderid, $_POST, '部分充值:' . I('charge_amount'), I('charge_amount'));
			echo "OK";
		}else {
			echo "未知的状态";
		}
    }
}