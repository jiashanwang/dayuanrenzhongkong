<?php


namespace Recharge;

use app\common\model\Porder as PorderModel;

class Hfcz
{
    private $mchid;
    private $apikey;
    private $notify;
    private $apiUrl;

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

       $teltype = 0;
       if($isp == '移动'){
           $teltype = 1;
       }else if($isp == '联通'){
           $teltype =3;
       }else if($isp == '电信'){
           $teltype = 2;
       }

        $data = [
            "partner_id" => $this->mchid,
            "partner_order_no"=>$out_trade_num,
            "account" => strval($mobile),
            "amount" => $param['param1'],//充值金额
            "type" => $teltype,//1移动，2电信，3联通
            "area" =>$param['guishu_pro'],
            "charge_type" => $param['param2'],//1快充 0慢充
            'notify_url' => $this->notify
        ];

        $sign_str = '';
        foreach ($data as $k=>$v){
           $sign_str .= $v;
        }
        $sign_str .=  $this->apikey;
        $data['sign'] = md5($sign_str);
		
        $res = $this->http_post($this->apiUrl, $data);
        
        return $res;
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
	*  自助撤单
	*/
	public function selfcancel($param)
    {
        $option = M('reapi')->where(['id' => $param['api_cur_id']])->find();

        $hangmin = new Hfcz($option);

        $params = explode("|", $option['param5']);
        $login_url = $params[0] . '/partner/user/login.html';
        $login_param = 'userName=' . $params[1] . '&password='. $params[2];

        $cookie = str_replace('Cookie: ', '', $this->http_cookie($login_url, $login_param));

        $order_url = $params[0] . '/partner/chargeAccount/list.html?page=1&limit=15&partner_order_no=&account='. $param['mobile']. '&amount=&type=&status=&charge_status=&notify_status=0';

        $ids = $this->http_id($order_url, $cookie);
        foreach ($ids as $id) {
            if ( $id['partner_order_no'] == $param['api_order_number'])


            $cancel_url = $params[0] . '/partner/chargeAccount/cancel.html';
            $cancel_param = 'id=' . $id['id'];
            $result = $this->http_cd($cancel_url, $cancel_param, $cookie);
            print_r($result);

            if ($result['errno'] == 0)
            {
				echo $param['api_order_number'] . '-' . $param['mobile'] . '-撤单成功</br>';
            }else{
				echo $param['api_order_number'] . '-' . $param['mobile'] . '-撤单失败，请检查账号密码是否正确！</br>';
           }
        }
    }

	 /**
	 * 查询订单状态
	 */
    public function check($partner_order_no)
    {
        $reqData = array(
            'partner_id'=>$this->mchid,
            'partner_order_no'=>$partner_order_no,
        );
        $signStr = $reqData['partner_id'].$reqData['partner_order_no'].$this->apikey;
        $reqData['sign'] = md5($signStr);
        $url = str_replace("receiveOrder","queryOrder",$this->apiUrl);
        $retJson = $this->http_post($url, $reqData);
        return $retJson;
    }

	/**
	 * 订单自查方法
	 */
    public function selfcheck($param)
    {
        $retJson = $this->check($param['api_order_number']);
        if($retJson['errno']==0){
            if($retJson['data']['code']==1){
                $data = $retJson['data']['data'];
                if($data['status'] == 2){
                    PorderModel::rechargeSusApi('hfcz', $param['api_order_number'], $data);
                }else if ($data['charge_amount'] > 0) {
                    PorderModel::rechargeRateApi('hfcz',$param['api_order_number'], $data, $data['charge_amount']);
                }
            }
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

    public function notify()
    {
		if (I('selfcheck')) {
			//自查
		}else if (I('cancel')) {
			//自助撤单
			$porders = M('porder p')
			->join('reapi r', 'r.id=p.api_cur_id')
			->where(['r.callapi' => 'Hfcz', 'p.status' => 3, 'apply_refund' => 1])
			->order('p.pegging_time asc ,p.create_time asc')->limit(100)
			->field('p.id,p.mobile,p.api_order_number,p.api_trade_num,p.api_cur_param_id,p.api_cur_id')
			->select();
			
            foreach ($porders as $k => $order) {
                $option = M('reapi')->where(['id' => $order['api_cur_id']])->find();
                $hangmin = new Hfcz($option);

                $params = explode("|", $option['param5']);
                $login_url = $params[0] . '/partner/user/login.html';
                $login_param = 'userName=' . $params[1] . '&password=' . $params[2];
                $cookie = str_replace('Cookie: ', '', $this->http_cookie($login_url, $login_param));

                $order_url = $params[0] . '/partner/chargeAccount/list.html?page=1&limit=15&partner_order_no=&account=' . $order['mobile'] . '&amount=&type=&status=&charge_status=&notify_status=0';
                $ids = $this->http_id($order_url, $cookie);

                foreach ($ids as $id) {
                    if ($id['partner_order_no'] == $order['api_order_number']) {
                        print_r($id);
                        $cancel_url = $params[0] . '/partner/chargeAccount/cancel.html';
                        $cancel_param = 'id=' . $id['id'];

                        $res = $this->http_cd($cancel_url, $cancel_param, $cookie);

                        print_r($res);

                        if ($res['errno'] == 0) {
                            echo $order['api_order_number'] . '-' . $order['mobile'] . '-撤单成功</br>';
                            echo "success";
                        } else {
                            echo $order['api_order_number'] . '-' . $order['mobile'] . '-撤单失败，请检查账号密码是否正确！</br>';
                        }
                    }
                }
            }
			
		}else{
			//V1.3文档回调说明
			//status：-3手动取消,-2充值失败,-1待检测,0待充值,1部分充值,2充值完成 
			$charge_status = I('status');
			$order_number = I('partner_order_no');
			$charge_amount = intval(I('charge_amount'));
			
			if (!$order_number) {
				echo "fail";
				return;
			}

			if ($charge_status == 2) {
				//充值成功,根据自身业务逻辑进行后续处理
				PorderModel::rechargeSusApi('hfcz', $order_number, $_POST, '完成充值');
				echo "success";
			} else {
				if ($charge_amount == 0) {
					//充值失败,根据自身业务逻辑进行后续处理
					PorderModel::rechargeFailApi('hfcz', $order_number, $_POST, I('remarks') . "|失败状态码: ".$charge_status);
					echo "success";
				}else{
					//部分充值
					PorderModel::rechargePartApi('hfcz', $order_number, $_POST, "部分充值:" . I('charge_amount'), I('charge_amount'));
					echo "success";
				}
			}
		}
    }
}