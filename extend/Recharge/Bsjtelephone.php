<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;
use think\Log;
/**
 * @author: 
 * 话费 http://ip/api/receiveOrder
 **/
class Bsjtelephone
{
    private $mchid;//商户编号
    private $apikey;
    private $notify;
    private $apiurl;//话费充值接口

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
        $data = [
            "partner_id" => $this->mchid,
            "partner_order_no"=>$out_trade_num,
            "phone" => $mobile,
            "amount" => $param['param1'],
            "type" => $this->get_teltype($isp),
            'notify_url' => $this->notify
        ];

        $sign_str = $data['partner_id'] .$data['partner_order_no'] .$data['phone'] .$data['amount'] .$data['type'].$data['notify_url'] .$this->apikey;
        
        $data['sign'] = md5($sign_str);
        
        return $this->http_post($this->apiurl, $data);
    }

    /**
    *  1联通，2移动，3电信
    */
    public function get_teltype($str)
    {
        switch ($str) {
            case '联通':
                return 1;
            case '电信':
                return 3;
            case '移动':
                return 2;
            case '广电':
                return 4;
            default:
                return 404;
        }
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

	private function http_id($url, $cookies)
    {
        unset($SeltIJR);
        $SeltIJR = "stripos";
        $GLOBALS["AAAA_AA"] = $SeltIJR;
        unset($SeltIJR);
        $SeltIJR = curl_init();
        $AAA_AAA = $SeltIJR;
        $SelJR = $GLOBALS["AAAA_AA"]($url, "https://") !== FALSE;
        if ($SelJR)
            goto SeleWjgxq;
        goto SelldMhxq;
        SeleWjgxq:
        curl_setopt($AAA_AAA, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($AAA_AAA, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($AAA_AAA, CURLOPT_SSLVERSION, 1);
        goto Selxp;
        SelldMhxq:
        Selxp:
        curl_setopt($AAA_AAA, CURLOPT_URL, $url);
        curl_setopt($AAA_AAA, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($AAA_AAA, CURLOPT_CONNECTTIMEOUT, 90);
        curl_setopt($AAA_AAA, CURLOPT_TIMEOUT, 120);
        curl_setopt($AAA_AAA, CURLOPT_HEADER, 0);
        curl_setopt($AAA_AAA, CURLOPT_COOKIE, $cookies);
        unset($SeltIJR);
        $SeltIJR = curl_exec($AAA_AAA);
        $AAAA___ = $SeltIJR;
        unset($SeltIJR);
        $SeltIJR = curl_getinfo($AAA_AAA);
        $AAAA__A = $SeltIJR;
        curl_close($AAA_AAA);
        unset($SeltIJR);
        $SeltIJR = json_decode($AAAA___, true);
        $AAAA_A_ = $SeltIJR;
        return $AAAA_A_["data"];
    }
    private function http_cookie($url, $param)
    {
        unset($SeltIJR);
        $SeltIJR = "stripos";
        $GLOBALS["A_____A_"] = $SeltIJR;
        unset($SeltIJR);
        $SeltIJR = "preg_match";
        $GLOBALS["A_____AA"] = $SeltIJR;
        unset($SeltIJR);
        $SeltIJR = curl_init();
        $AAAAA__ = $SeltIJR;
        $SelJR = $GLOBALS["A_____A_"]($url, "https://") !== FALSE;
        if ($SelJR)
            goto SeleWjgxs;
        goto SelldMhxs;
        SeleWjgxs:
        curl_setopt($AAAAA__, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($AAAAA__, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($AAAAA__, CURLOPT_SSLVERSION, 1);
        goto Selxr;
        SelldMhxs:
        Selxr:
        unset($SeltIJR);
        $SeltIJR = $param;
        $AAAAA_A = $SeltIJR;
        curl_setopt($AAAAA__, CURLOPT_URL, $url);
        curl_setopt($AAAAA__, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($AAAAA__, CURLOPT_POST, true);
        curl_setopt($AAAAA__, CURLOPT_CONNECTTIMEOUT, 90);
        curl_setopt($AAAAA__, CURLOPT_TIMEOUT, 120);
        curl_setopt($AAAAA__, CURLOPT_POSTFIELDS, $AAAAA_A);
        curl_setopt($AAAAA__, CURLOPT_HEADER, 1);
        unset($SeltIJR);
        $SeltIJR = curl_exec($AAAAA__);
        $AAAAAA_ = $SeltIJR;
        unset($SeltIJR);
        $SeltIJR = curl_getinfo($AAAAA__);
        $AAAAAAA = $SeltIJR;
        curl_close($AAAAA__);
        $GLOBALS["A_____AA"]('/Cookie:(.*);/iU', $AAAAAA_, $A_______);
        unset($SeltIJR);
        $SeltIJR = $A_______[0];
        $A______A = $SeltIJR;
        $SelJR = intval($AAAAAAA["http_code"]) == 200;
        if ($SelJR)
            goto SeleWjgxu;
        goto SelldMhxu;
        SeleWjgxu:
        return $A______A;
        goto Selxt;
        SelldMhxu:
        Selxt:
    }
    private function http_cd($url, $param, $cookies)
    {
        unset($SeltIJR);
        $SeltIJR = "stripos";
        $GLOBALS["A___A__A"] = $SeltIJR;
        unset($SeltIJR);
        $SeltIJR = curl_init();
        $A____A__ = $SeltIJR;
        $SelJR = $GLOBALS["A___A__A"]($url, "https://") !== FALSE;
        if ($SelJR)
            goto SeleWjgxw;
        goto SelldMhxw;
        SeleWjgxw:
        curl_setopt($A____A__, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($A____A__, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($A____A__, CURLOPT_SSLVERSION, 1);
        goto Selxv;
        SelldMhxw:
        Selxv:
        if (is_string($param))
            goto SeleWjgxy;
        goto SelldMhxy;
        SeleWjgxy:
        unset($SeltIJR);
        $SeltIJR = $param;
        $A____A_A = $SeltIJR;
        goto Selxx;
        SelldMhxy:
        unset($SeltIJR);
        $SeltIJR = http_build_query($param);
        $A____A_A = $SeltIJR;
        Selxx:
        curl_setopt($A____A__, CURLOPT_URL, $url);
        curl_setopt($A____A__, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($A____A__, CURLOPT_POST, true);
        curl_setopt($A____A__, CURLOPT_CONNECTTIMEOUT, 90);
        curl_setopt($A____A__, CURLOPT_TIMEOUT, 120);
        curl_setopt($A____A__, CURLOPT_POSTFIELDS, $A____A_A);
        curl_setopt($A____A__, CURLOPT_HEADER, 0);
        curl_setopt($A____A__, CURLOPT_COOKIE, $cookies);
        unset($SeltIJR);
        $SeltIJR = curl_exec($A____A__);
        $A____AA_ = $SeltIJR;
        unset($SeltIJR);
        $SeltIJR = curl_getinfo($A____A__);
        $A____AAA = $SeltIJR;
        curl_close($A____A__);
        $SelJR = intval($A____AAA["http_code"]) == 200;
        if ($SelJR)
            goto SeleWjgx11;
        goto SelldMhx11;
        SeleWjgx11:
        unset($SeltIJR);
        $SeltIJR = json_decode($A____AA_, true);
        $A___A___ = $SeltIJR;
        $SelJR = $A___A___['msg'] == '操作成功';
        if ($SelJR)
            goto SeleWjgx13;
        goto SelldMhx13;
        SeleWjgx13:
        return rjson(0, $A___A___['msg'], $A___A___);
        goto Selx12;
        SelldMhx13:
        return rjson(1, $A___A___['msg'], $A___A___);
        Selx12:
        goto Selxz;
        SelldMhx11:
        $SelvPJR = '接口访问失败，http错误码' . $A____AAA["http_code"];
        return rjson(500, $SelvPJR);
        Selxz:
    }
    
    /**
    * 查询订单状态
    */
    public function check($partner_order_no)
    {
        $reqData = array(
            'partner_id'=>$this->mchid,
            'partner_order_no'=>$partner_order_no
        );
        $signStr = $reqData['partner_id'].$reqData['partner_order_no'].$this->apikey;
        $reqData['sign'] = md5($signStr);
        $url = str_replace("receiveOrder","queryOrder",$this->apiurl);
        $retJson = $this->http_post($url, $reqData);
        return $retJson;
    }

    /**
    * 订单自查方法
    */
    public function selfcheck($param)
    {
        /*$retJson = $this->check($param['api_order_number']);
        if($retJson['errno']==0){
            if($retJson['data']['code']==1){
                $data = $retJson['data']['data'];
                if($data['status'] == 2){
                    PorderModel::rechargeSusApi('Bsjtelephone', $param['api_order_number'], $data);
                }else if ($data['charge_amount'] > 0) {
                    PorderModel::rechargeRateApi('Bsjtelephone',$param['api_order_number'], $data, $data['charge_amount']);
                }
            }
        }*/
    }

    /**
     * 取消
     */
    private function remove($out_trade_num)
    {
        $data = [
            "partner_id"=>$this->mchid,
            "partner_order_no"=>$out_trade_num,
        ];
		
		$sign_str = $this->mchid . $out_trade_num . $this->apikey;
		$data['sign'] = md5($sign_str);
		
        return $this->http_post(str_replace('receiveOrder', 'cancelOrder', $this->apiurl), $data);
    }

    /**
    * 自助撤单
    */
    public function selfremove($params){
        $retJson = $this->remove($params['api_order_number']);
    }


    public function notify()
    {
        //状态订单返回代码, 1 充值成功 0 订单退回
        $charge_status = intval(I('status'));
        //回调订单号
        $order_number = I('partner_order_no');
        
        if (!$order_number) {
            echo "fail";
            return;
        }
        
        if($charge_status==1){
            //充值成功,根据自身业务逻辑进行后续处理
            PorderModel::rechargeSusApi('Bsjtelephone', $order_number, $_POST, '充值成功');
            echo "success";
        }else {
            PorderModel::rechargeFailApi('Bsjtelephone', $order_number, $_POST, '手动失败');
            echo "success";
        }
    }
}