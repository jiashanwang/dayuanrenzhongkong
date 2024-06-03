<?php

namespace app\agent\controller;
use app\api\controller\Notify;
use app\common\library\Createlog;
use app\common\model\Balance;
use app\common\model\Client;
use app\common\model\Porder as PorderModel;
use app\common\model\Product;
use app\common\model\Product as ProductModel;
class Query extends Admin
{
    //订单列表余额查询
    public function phone_query() {
        if(C('HFYE_SWITCH_CHECK_D')==0){
            return djson(1,'暂时无法查询');
        }
        if(empty(I('id'))||empty(I('type'))){
            return djson(1,'参数错误，暂时无法查询！');
        }
        $porder = M('porder')->where(array('id' => I('id'), 'is_del' => 0))->find();
        if(empty($porder['mobile'])){
            return djson(1,'查询号码不正确');
        }
    	if(empty($porder['isp'])){
            return $return;return djson(1,'运营商不正确');
        }
        $customer_id=$this ->user['id'];
        $porder['electricity_are']=$porder['isp'];
        if($porder['type']==3){
            $porder['isp']="electricity_balance_query";
        }
        $return=balance_query($porder['mobile'],$porder['isp'],$porder['electricity_are'],$customer_id);
        
        if($return['mobile_fee']){
            M('porder')->where(array('id' => $porder['id']))->setField(array('phone_balance_'.I('type') => $return['mobile_fee']));
        }
        if($return['code'] != 200){
            return djson($return['code'],$return['message'],$return);
        }else{
            $Kh9tIMH = Balance::expend($customer_id, $return['channel_price'], "[支付]查询号码号".$return['mobile']."余额消费，余额：".$return['mobile_fee'].",备注：".$return['remark'], Balance::STYLE_ORDERS, '代理商_手工');
            if($Kh9tIMH['errno'] != 0){
                return djson($Kh9tIMH['errno'], $Kh9tIMH['errmsg']);
            }else{
                return djson(0,'余额查询结果：' . $return['mobile_fee'],$return);
            }

        }
    }

    //余额查询记录
    public function balance_query_record(){
        $map['customer_id'] = $this->user['id'];
		$list = M('balance_query_record')->where(array('Customer_id' =>$this->user['id']))->order("Create_time desc")->select();
		$info = M('customer')->find($this->user['id']);
		$this->assign('_list', $list);
		$this->assign('info', $info);
		
        $this->assign('yd_price',C('HFYE_API_YD'));
        $this->assign('dx_price',C('HFYE_API_DX'));
        $this->assign('lt_price',C('HFYE_API_LT'));
        $this->assign('df_price',C('HFYE_API_DF'));
        $this->assign('dfhh_price',C('HFYE_API_DFHH'));
        $this->assign('hmjc_price',C('HFYE_API_HMJC'));
        return view();
    }
    
    //新增余额查询
    public function balance_query_add(){
        $this->assign('yd_price',C('HFYE_API_YD'));
        $this->assign('dx_price',C('HFYE_API_DX'));
        $this->assign('lt_price',C('HFYE_API_LT'));
        $this->assign('df_price',C('HFYE_API_DF'));
        $this->assign('dfhh_price',C('HFYE_API_DFHH'));
        $this->assign('hmjc_price',C('HFYE_API_HMJC'));
        return view();
    }
    
    //删除余额查询记录
    public function balance_query_deleate()
	{
        $customer_id = $this->user['id'];
        if(M('balance_query_record')->where(array('Customer_id' => $customer_id))->delete()) {
			return $this->success('删除成功！');
		} else {
			return $this->error('删除失败！');
		}
		$list = M('balance_query_record')->where(array('Customer_id' =>$customer_id))->order("Create_time desc")->select();
		$this->assign('_list', $list);
		return view();
	}
    public function balance_query_adddo() {
        if(C('HFYE_SWITCH_CHECK_D')==0){
            return djson(1,'暂时无法查询');
        }
        $isp = trim(I('channel'));
		$mobile = I('mobile');
		$area = trim(I('electricity_are'));
        
        if(empty($mobile)){
            return djson(1,'查询号码不正确');
        }
    	if(empty($isp)){
            return $return;return djson(1,'运营商不正确');
        }
        $customer_id=$this ->user['id'];
        $return=balance_query($mobile,$isp,$area,$customer_id);
        
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
                $balance_message="查询号码".$return['mobile']."号码检测 + 携号转网，是否虚拟号：".$return['isVirtuallyIspName'].",是否携号转网:".$return['isTransferName'].",备注：".$return['remark'];
            }elseif($isp=="electricity_balance"){
                $balance_message="查询号码".$return['mobile']."户号信息消费，类型：".$return['typeName'].",地址：".$return['userAddr'].",营业厅：".$return['company'].",户名：".$return['userName'].",备注：".$return['remark'];
            }else{
                $balance_message="查询号码".$return['mobile']."余额消费，余额：".$return['mobile_fee'].",备注：".$return['remark'];
            }
            $Kh9tIMH = Balance::expend($customer_id, $return['channel_price'], "[支付]".$balance_message, Balance::STYLE_ORDERS, '代理商_手工');
            if($Kh9tIMH['errno'] != 0){
                return djson($Kh9tIMH['errno'], $Kh9tIMH['errmsg']);
            }else{
                return djson(0,'余额查询结果：' . $balance_message,$return);
            }
        }
    }
}