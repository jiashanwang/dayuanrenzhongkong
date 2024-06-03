<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\admin\controller;
use app\common\library\Createlog;
use app\common\library\Templetmsg;
use app\common\model\Balance;
use app\common\model\Porder as PorderModel;
use app\common\model\Product as ProductModel;
class Query extends Admin{
    public function phone_query() {
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
        $porder['electricity_are']=$porder['isp'];
        if($porder['type']==3){
            $porder['isp']="electricity_balance_query";
        }
        $return=balance_query($porder['mobile'],$porder['isp'],$porder['electricity_are'],'');
        M('porder')->where(array('id' => $porder['id']))->setField(array('phone_balance_'.I('type') => $return['mobile_fee']??null));
        Createlog::porderLog($porder['id'], '余额查询结果：' . $return['mobile_fee']??null);
        if($return['code'] != 200){
            return djson($return['code'],$return['message'],$return);
        }else{
            return djson(0,'余额查询结果：' . $return['mobile_fee'],$return);
        }
    }
    
    public function set_customer_grade_price() {
        $range=I('prompt_remark');
    	$product_id=I('id/a');
    	
    	if(!$product_id){
    	    return $this->error('请选择要设置的等级');
    	}
    	if(!$range){
    	    return $this->error('请填写每100元上浮价格');
    	}
    	foreach($product_id as $k=>$v) {
    		$Kh9tIMC=M('customer_grade_price')->where(array('id'=>$v))->find();
    		$product=M('product')->where(array('id'=>$Kh9tIMC['product_id']))->find();
    		$Kh9MC=floatval(preg_replace('/\\D/','',$product['name']))/100;
    		$Kh9MD=$Kh9MC*$range;
    		$Kh9tIMC=M('customer_grade_price')->where(array('id'=>$v))->setField('ranges',$Kh9MD);
    	}
    	return $this->success('保存成功');
    }
    public function set_product_price() {
        $range=I('prompt_remark');
    	$product_id=I('id/a');
    	
    	if(!$product_id){
    	    return $this->error('请选择要设置的产品');
    	}
    	if(!$range){
    	    return $this->error('请填写每100元上浮价格');
    	}
    	foreach($product_id as $k=>$v) {
    		$product=M('product')->where(array('id'=>$v))->find();
    		$Kh9MC=floatval(preg_replace('/\\D/','',$product['name']))/100;
    		$Kh9MD=$Kh9MC*$range;
    		$Kh9tIMC=M('product')->where(array('id'=>$v))->setField('price',$Kh9MD);
    	}
    	return $this->success('保存成功');
    }
    public function set_porder_time() {
        $create_time=I('create_time');
        $finish_time=I('finish_time');
    	$porder_id=I('id/a');
        if($create_time){
            $prompt='create_time';
            $prompt_content='将订单下单时间设置为：';
            $prompt_remark=$create_time;
            $arr=array('create_time'=>strtotime($prompt_remark),'pay_time'=>strtotime($prompt_remark));
        }
        if($finish_time){
            $prompt='finish_time';
            $prompt_content='将订单完成时间设置为：';
            $prompt_remark=$finish_time;
            $arr=array('finish_time'=>strtotime($prompt_remark));
        }
    	if(!$prompt){
    	    return $this->error('参数错误，暂时无法设置！');
    	}
    	$patten = "/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])(\:(0?[0-9]|[1-5][0-9]))?)?$/";
        if (!preg_match($patten, $prompt_remark)) {
            return $this->error('日期格式不正确');
        }
    	foreach($porder_id as $k=>$v) {
    		$Kh9tIMC=M('porder')->where(array('id'=>$v))->setField($arr);
            Createlog::porderLog($v, $prompt_content. $prompt_remark??null);
    	}
    	return $this->success('保存成功');
    }
}
?>