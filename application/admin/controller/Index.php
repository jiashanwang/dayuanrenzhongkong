<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/
namespace app\admin\controller;
use app\common\model\Balance;
use think\Db;
use Util\GoogleAuth;
use Util\Ispzw;
use Util\Isphone;
class Index extends Admin {
	public function index() {
		unset($Kh9tIMC);
		$Kh9tIMC=M('order_upgrade')->where(array('is_pay'=>1))->sum('total_price');
		$data['total_price_upgrade']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('status'=>array('in','2,3,4,5,8,9,10,11,12,13'),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->sum('total_price');
		$data['total_price']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('status'=>array('in','4,12,13'),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->sum('total_price');
		$data['total_price_sus']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('status'=>array('in','3,11'),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->sum('total_price');
		$data['total_price_ing']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('status'=>array('in','2,10'),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->sum('total_price');
		$data['total_price_wait']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('pay_time'=>array('egt',strtotime(date('Y-m-d'))),'status'=>array('in','2,3,4,5,8,9,10,11,12,13'),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->sum('total_price');
		$data['today_price']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('status'=>array('gt',1),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->count();
		$data['order_num']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('status'=>array('gt',1),'pay_time'=>array('egt',strtotime(date('Y-m-d'))),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->count();
		$data['today_order_num']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('status'=>array('in','3,11'),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->count();
		$data['total_order_ing']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('customer')->where(array('type'=>1,'is_del'=>0))->count();
		$data['cus_num']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('customer')->where(array('type'=>2,'is_del'=>0))->count();
		$data['agent_num']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('customer')->where(array('type'=>1,'is_del'=>0))->sum('balance');
		$data['cus_balance']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('customer')->where(array('type'=>2,'is_del'=>0))->sum('balance');
		$data['agent_balance']=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('balance_log')->where(array('style'=>Balance::STYLE_RECHARGE,'type'=>1,'create_time'=>array('egt',strtotime(date('Y-m-d')))))->sum('money');
		$jk=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=M('balance_log')->where(array('style'=>Balance::STYLE_RECHARGE,'type'=>2,'create_time'=>array('egt',strtotime(date('Y-m-d')))))->sum('money');
		$kk=$Kh9tIMC;
		$Kh9vPMC=$jk-$kk;
		unset($Kh9tIMD);
		$Kh9tIMD=sprintf("%.2f",$Kh9vPMC);
		$data['today_jiakuan']=$Kh9tIMD;
		$Kh9vPvPvPvPMC=strtotime(date('Y-m-d'))-86400;
		unset($Kh9tIMD);
		$Kh9tIMD=M('balance_log')->where(array('style'=>Balance::STYLE_RECHARGE,'type'=>1,'create_time'=>array('between',array($Kh9vPvPvPvPMC,strtotime(date('Y-m-d'))))))->sum('money');
		$jkz=$Kh9tIMD;
		$Kh9vPvPvPvPMC=strtotime(date('Y-m-d'))-86400;
		unset($Kh9tIMD);
		$Kh9tIMD=M('balance_log')->where(array('style'=>Balance::STYLE_RECHARGE,'type'=>2,'create_time'=>array('between',array($Kh9vPvPvPvPMC,strtotime(date('Y-m-d'))))))->sum('money');
		$kkz=$Kh9tIMD;
		$Kh9vPMC=$jkz-$kkz;
		unset($Kh9tIMD);
		$Kh9tIMD=sprintf("%.2f",$Kh9vPMC);
		$data['zuotian_jiakuan']=$Kh9tIMD;
		unset($Kh9tIMC);
		$Kh9tIMC=M('customer')->where(array('is_del'=>0))->sum('shouxin_e');
		$data['shouxin_e']=$Kh9tIMC;
		$Kh9MC=C('ISP_ZHUANW_SW')!=0;
		if($Kh9MC)goto Kh9eWjgx2;
		goto Kh9ldMhx2;
		Kh9eWjgx2:unset($Kh9tIMC);
		$Kh9tIMC=Ispzw::balance(C('ISP_ZHUANW_CFG.apikey'));
		$res=$Kh9tIMC;
		$Kh9MC=$res['errno']==0;
		if($Kh9MC)goto Kh9eWjgx4;
		goto Kh9ldMhx4;
		Kh9eWjgx4:$Kh9MD=$res['data'];
		goto Kh9x3;
		Kh9ldMhx4:$Kh9MD='未配置';
		Kh9x3:unset($Kh9tIME);
		$Kh9tIME=$Kh9MD;
		$data['ispzw_balance']=$Kh9tIME;
		goto Kh9x1;
		Kh9ldMhx2:unset($Kh9tIMC);
		$Kh9tIMC='接口关闭';
		$data['ispzw_balance']=$Kh9tIMC;
		Kh9x1:$Kh9MC=C('ISP_KONGH_SW')!=0;
		if($Kh9MC)goto Kh9eWjgx6;
		goto Kh9ldMhx6;
		Kh9eWjgx6:unset($Kh9tIMC);
		$Kh9tIMC=Isphone::balance(C('ISP_KONGH_CFG.apikey'));
		$ress=$Kh9tIMC;
		$Kh9MC=$ress['errno']==0;
		if($Kh9MC)goto Kh9eWjgx8;
		goto Kh9ldMhx8;
		Kh9eWjgx8:$Kh9MD=$ress['data'];
		goto Kh9x7;
		Kh9ldMhx8:$Kh9MD='未配置';
		Kh9x7:unset($Kh9tIME);
		$Kh9tIME=$Kh9MD;
		$data['isphone_balance']=$Kh9tIME;
		goto Kh9x5;
		Kh9ldMhx6:unset($Kh9tIMC);
		$Kh9tIMC='接口关闭';
		$data['isphone_balance']=$Kh9tIMC;
		Kh9x5:unset($Kh9tIMC);
		$Kh9tIMC=C('ISP_ZHUANW_SW');
		$data['zwsw']=$Kh9tIMC;
		//------------------------------------------
		// 今日订单
	        $data['today_amount'] = M('porder')->where(array('pay_time' => array('egt',strtotime(date('Y-m-d'))),'status' => array('gt', 1), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
	        $data['today_count'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status' => array('gt', 1), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
	        $data['today_yes_amount'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status'=>4,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
	        $data['today_yes_count'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status'=>4,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
	        $data['today_yes9_amount'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status'=>9,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('charge_amount');
	        $data['today_yes9_count'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status'=>9,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
	        $data['today_yes_amount'] += $data['today_yes9_amount'];
	        $data['today_yes_count'] += $data['today_yes9_count'];
	        $data['today_no_amount'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status'=>6,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('refund_price');
	        $data['today_no_count'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status'=>6,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
	        $data['today_in_amount'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status' => array('in', '3,11'),'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
	        $data['today_in_count'] = M('porder')->where(array('pay_time' => array('egt', strtotime(date('Y-m-d'))),'status' => array('in', '3,11'),'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();

	        // 昨日订单
	        $data['zuori_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status' => array('gt', 1), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
	        $data['zuori_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status' => array('gt', 1), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
	        $data['zuori_yes_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status'=>4,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
	        $data['zuori_yes_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status'=>4,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
	        $data['zuori_yes9_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status'=>9,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('charge_amount');
	        $data['zuori_yes9_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status'=>9,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
	        $data['zuori_yes_amount'] += $data['today_yes9_amount'];
	        $data['zuori_yes_count'] += $data['today_yes9_count'];
	        $data['zuori_no_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status'=>6,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('refund_price');
	        $data['zuori_no_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status'=>6,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
	        $data['zuori_in_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status' => array('in', '3,11'),'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
	        $data['zuori_in_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d')))),'status' => array('in', '3,11'),'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
                
            // 前日订单
            $data['qianri_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d')) - 86400)),'status' => array('gt', 1), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
            $data['qianri_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d')) - 86400)),'status' => array('gt', 1), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
            $data['qianri_yes_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d')) - 86400)),'status'=>4,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
            $data['qianri_yes_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400)),'status'=>4,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
            $data['qianri_yes9_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400)),'status'=>9,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('charge_amount');
            $data['qianri_yes9_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400)),'status'=>9,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
            $data['qianri_yes_amount'] += $data['today_yes9_amount'];
            $data['qianri_yes_count'] += $data['today_yes9_count'];
            $data['qianri_no_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400)),'status'=>6,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('refund_price');
            $data['qianri_no_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400)),'status'=>6,'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
            $data['qianri_in_amount'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400)),'status' => array('in', '3,11'),'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
            $data['qianri_in_count'] = M('porder')->where(array('pay_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400)),'status' => array('in', '3,11'),'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
            
            // 总订单
            //$data['total_price'] = M('porder')->where(array('status' => array('in', '2,3,4,5,8,9,10,11,12,13'), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
            $data['total_count'] = M('porder')->where(array('status' => array('gt', 1), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
            $data['total_yes_price'] = M('porder')->where(array('status' => array('in', '4,12,13'), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
            $data['total_yes_count'] = M('porder')->where(array('status' => array('in', '4,12,13'), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
            $data['total_no_price'] = M('porder')->where(array('status' => array('in', 6), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
            $data['total_no_count'] = M('porder')->where(array('status' => array('in', 6), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
            $data['total_ing_price'] = M('porder')->where(array('status' => array('in', '3,11'), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->sum('total_price');
            $data['total_ing_count'] = M('porder')->where(array('status' => array('in', '3,11'), 'is_del' => 0, 'is_apart' => array('in', array(0, 2))))->count();
    
            // 代理总加款
            $jk = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 1))->sum('money');
            $kk = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 2))->sum('money');
            $data['jiakuan'] = sprintf("%.2f", $jk - $kk);
            $data['jiakuan_count'] = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 1))->count();
            // 今日代理加款
            $jk = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 1, 'create_time' => array('egt', strtotime(date('Y-m-d')))))->sum('money');
            $kk = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 2, 'create_time' => array('egt', strtotime(date('Y-m-d')))))->sum('money');
            $data['today_jiakuan'] = sprintf("%.2f", $jk - $kk);
            $data['today_jiakuan_count'] = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 1, 'create_time' => array('egt', strtotime(date('Y-m-d')))))->count();
            // 昨日代理加款
            $jkz = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 1, 'create_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d'))))))->sum('money');
            $kkz = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 2, 'create_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d'))))))->sum('money');
            $data['zuotian_jiakuan'] = sprintf("%.2f", $jkz - $kkz);
            $data['zuotian_jiakuan_count'] = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 1, 'create_time' => array('between', array(strtotime(date('Y-m-d')) - 86400, strtotime(date('Y-m-d'))))))->count();
            // 前日代理加款
            $jkz = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 1, 'create_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400))))->sum('money');
            $kkz = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 2, 'create_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400))))->sum('money');
            $data['qianri_jiakuan'] = sprintf("%.2f", $jkz - $kkz);
            $data['qianri_jiakuan_count'] = M('balance_log')->where(array('style' => Balance::STYLE_RECHARGE, 'type' => 1, 'create_time' => array('between', array(strtotime(date('Y-m-d')) - (86400*2), strtotime(date('Y-m-d'))- 86400))))->count();
    
            // 代理商与余额
            $data['agent_num'] = M('customer')->where(array('type' => 2, 'is_del' => 0))->count();
            $data['agent_balance'] = M('customer')->where(array('type' => 2, 'is_del' => 0))->sum('balance');
            $data['shouxin_e'] = M('customer')->where(array('is_del' => 0))->sum('shouxin_e');
    
            // 会员与余额
            $data['cus_num'] = M('customer')->where(array('type' => 1, 'is_del' => 0))->count();
            $data['cus_balance'] = M('customer')->where(array('type' => 1, 'is_del' => 0))->sum('balance');
            
            //待充值单数
            $data['total_count_wait']=M('porder')->where(array('status'=>array('in','2,10'),'is_del'=>0,'is_apart'=>array('in',array(0,2))))->count();
            //------------------------------------------
            $this->assign('data',$data);
            return view();
	}
	public function sysinfo() {
		$Kh9vPMC='v' . C('dtupdate.version');
		$Kh9vPMD=ini_get('max_execution_time') . '秒';
		$Kh9vPME=$_SERVER['SERVER_NAME'] . ' [ ';
		$Kh9vPMF=$Kh9vPME . gethostbyname($_SERVER['SERVER_NAME']);
		$Kh9vPMG=$Kh9vPMF . ' ]';
		if(get_cfg_var("memory_limit"))goto Kh9eWjgxa;
		goto Kh9ldMhxa;
		Kh9eWjgxa:$Kh9vPMH=get_cfg_var("memory_limit");
		goto Kh9x9;
		Kh9ldMhxa:$Kh9vPMH="无";
		Kh9x9:$Kh9vPvPMI=1024*1024;
		$Kh9vPvPMJ=disk_free_space(".")/$Kh9vPvPMI;
		$Kh9vPMK=round($Kh9vPvPMJ,2) . 'M';
		unset($Kh9tIML);
		$Kh9tIML=array('系统版本'=>$Kh9vPMC,'操作系统'=>PHP_OS,'运行环境'=>$_SERVER["SERVER_SOFTWARE"],'PHP版本'=>PHP_VERSION,'MYSQL版本'=>Db::query('select version() as v')[0]['v'],'上传附件限制'=>ini_get('upload_max_filesize'),'执行时间限制'=>$Kh9vPMD,'服务器时间'=>date("Y年n月j日 H:i:s"),'授权域名/IP地址'=>$Kh9vPMG,'当前登录用户IP地址'=>get_client_ip(0),'脚本运行占用最大内存'=>$Kh9vPMH,'磁盘剩余空间'=>$Kh9vPMK);
		$server_info=$Kh9tIML;
		$this->assign('server_info',$server_info);
		return view();
	}
	public function tongji() {
		return view();
	}
	public function statistics() {
		unset($Kh9tIMC);
		$Kh9tIMC=M()->query('select sum(total_price) as price,FROM_UNIXTIME(create_time,\'%Y年%m月%d日\') as time from dyr_porder where status in(2,3,4) GROUP BY time order by time asc');
		$list=$Kh9tIMC;
		return djson(0,'ok',$list);
	}
	public function bind_google_auth() {
		if($this->adminuser['google_auth_secret'])goto Kh9eWjgxc;
		goto Kh9ldMhxc;
		Kh9eWjgxc:$this->redirect('admin/index');
		goto Kh9xb;
		Kh9ldMhxc:Kh9xb:$Kh9MC=C('WEB_SITE_TITLE') . "-";
		$Kh9MD=$Kh9MC . $this->adminuser['nickname'];
		unset($Kh9tIME);
		$Kh9tIME=$Kh9MD;
		$name=$Kh9tIME;
		unset($Kh9tIMC);
		$Kh9tIMC=GoogleAuth::createSecret();
		$secret=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=GoogleAuth::getQRCodeGoogleUrl($name,$secret);
		$qrCodeUrl=$Kh9tIMC;
		$this->assign('qrcode_url',$qrCodeUrl);
		$this->assign('secret',$secret);
		return view();
	}
	public function save_google_auth() {
		if($this->adminuser['google_auth_secret'])goto Kh9eWjgxe;
		goto Kh9ldMhxe;
		Kh9eWjgxe:$this->redirect('admin/index');
		goto Kh9xd;
		Kh9ldMhxe:Kh9xd:unset($Kh9tIMC);
		$Kh9tIMC=dyr_encrypt(I('secret'));
		$secret=$Kh9tIMC;
		unset($Kh9tIMC);
		$Kh9tIMC=GoogleAuth::verifyCode($secret,I('verifycode'),1);
		$goret=$Kh9tIMC;
		$Kh9MC=!$goret;
		if($Kh9MC)goto Kh9eWjgxg;
		goto Kh9ldMhxg;
		Kh9eWjgxg:return $this->error("谷歌身份验证码错误！");
		goto Kh9xf;
		Kh9ldMhxg:Kh9xf:M('Member')->where(array('id'=>$this->adminuser['id']))->setField(array('google_auth_secret'=>$secret));
		return $this->success("绑定成功！",U('admin/index'));
	}
	public function skip_google_auth() {
		if($this->adminuser['google_auth_secret'])goto Kh9eWjgxi;
		goto Kh9ldMhxi;
		Kh9eWjgxi:$this->redirect('admin/index');
		goto Kh9xh;
		Kh9ldMhxi:Kh9xh:M('Member')->where(array('id'=>$this->adminuser['id']))->setField(array('google_auth_secret'=>'0'));
		return $this->success("已跳过绑定操作",U('admin/index'));
	}
	public function has_apbla() {
		unset($Kh9tIMC);
		$Kh9tIMC=M('apply_addbla')->where(array('status'=>1))->find();
		$bb=$Kh9tIMC;
		if($bb)goto Kh9eWjgxk;
		goto Kh9ldMhxk;
		Kh9eWjgxk:return djson(0,'有待处理的打款申请单',$bb);
		goto Kh9xj;
		Kh9ldMhxk:return djson(1,'无');
		Kh9xj:
	}
	public function has_apply_refund() {
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('apply_refund'=>1,'status'=>3))->field('id,order_number')->find();
		$bb=$Kh9tIMC;
		if($bb)goto Kh9eWjgxm;
		goto Kh9ldMhxm;
		Kh9eWjgxm:return djson(0,'有待处理的退款申请单',$bb);
		goto Kh9xl;
		Kh9ldMhxm:return djson(1,'无');
		Kh9xl:
	}
	public function has_apply_unusual() {
		unset($Kh9tIMC);
		$Kh9tIMC=M('porder')->where(array('status'=>8))->field('id,order_number')->find();
		$bb=$Kh9tIMC;
		if($bb)goto Kh9eWjgxo;
		goto Kh9ldMhxo;
		Kh9eWjgxo:return djson(0,'有待处理的异常单',$bb);
		goto Kh9xn;
		Kh9ldMhxo:return djson(1,'无');
		Kh9xn:
	}
}
?>
