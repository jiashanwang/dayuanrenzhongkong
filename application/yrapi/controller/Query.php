<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\yrapi\controller;
use app\common\library\Createlog;
use app\common\model\Client;
use app\common\model\Porder as PorderModel;
use app\common\model\Balance;
use app\common\model\Product as ProductModel;
class Query extends Home{
	public function balance_query(){
		$mobile = I('account');
		$isp = I('isp');
		$electricity_are = I('electricity_are');
        if(empty($mobile)){
            return djson(1,'查询号码不正确');
        }
    	if(empty($isp)){
            return $return;return djson(1,'运营商不正确');
        }
		$customer_id=$this->customer['id'];
		if(C('HFYE_SWITCH_CHECK_D')==0){
            return djson(1,'暂时无法查询');
        }
        $return=balance_query($mobile,$isp,$electricity_are,$customer_id);
        if($return['code'] != 200){
            return djson($return['code'],$return['message'],$return);
        }else{
            if($isp=="detection_mnp"){
                if($return['isVirtuallyIsp']==1){
                    $return['isVirtuallyIspName']="是";
                }else{
                    $return['isVirtuallyIspName']="否";
                }
                if($return['isTransfer']==1){
                    $return['isTransferName']="是";
                }else{
                    $return['isTransferName']="否";
                }
                $balance_message="[支付]查询号码".$return['mobile']."号码检测 + 携号转网，是否虚拟号：".$return['isVirtuallyIspName'].",是否携号转网:".$return['isTransferName'].",备注：".$return['remark'];
            }elseif($isp=="electricity_balance"){
                $balance_message="[支付]查询号码".$return['mobile']."户号信息消费，类型：".$return['typeName'].",地址：".$return['userAddr'].",营业厅：".$return['company'].",户名：".$return['userName'].",备注：".$return['remark'];
            }else{
                $balance_message="[支付]查询号码".$return['mobile']."余额消费，余额：".$return['mobile_fee'].",备注：".$return['remark'];
            }
            $Kh9tIMH = Balance::expend($customer_id, $return['channel_price'], $balance_message, Balance::STYLE_ORDERS, '代理商_手工');
            if($Kh9tIMH['errno'] != 0){
                return djson($Kh9tIMH['errno'], $Kh9tIMH['errmsg']);
            }else{
                return djson(0,'余额查询结果：' . $return['mobile_fee'],$return);
            }
        }
	}}
?>