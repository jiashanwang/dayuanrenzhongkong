<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\common\library;use app\common\model\Porder;use Util\Ispzw;use Util\Isphone;class Rechargeapi{public static function recharge($porder_id){unset($Kh9tIMC);$Kh9tIMC=Porder::getCurApi($porder_id);$res=$Kh9tIMC;$Kh9MC=$res['errno']!=0;if($Kh9MC)goto Kh9eWjgx2;goto Kh9ldMhx2;Kh9eWjgx2:return rjson($res['errno'],$res['errmsg']);goto Kh9x1;Kh9ldMhx2:Kh9x1:unset($Kh9tIMC);$Kh9tIMC=$res['data']['api'];$api=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$res['data']['num'];$cur_num=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$res['data']['index'];$cur_index=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('porder')->where(array('id'=>$porder_id,'status'=>2,'api_open'=>1))->find();$porder=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('reapi')->where(array('id'=>$api['reapi_id']))->find();$config=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('reapi_param')->where(array('id'=>$api['param_id']))->find();$param=$Kh9tIMC;$Kh9MC=!$config;$Kh9ME=(bool)$Kh9MC;$Kh9MF=!$Kh9ME;if($Kh9MF)goto Kh9eWjgx5;goto Kh9ldMhx5;Kh9eWjgx5:$Kh9MD=!$param;$Kh9ME=$Kh9MC||$Kh9MD;goto Kh9x4;Kh9ldMhx5:Kh9x4:if($Kh9ME)goto Kh9eWjgx6;goto Kh9ldMhx6;Kh9eWjgx6:return rjson(1,'接口未找到');goto Kh9x3;Kh9ldMhx6:Kh9x3:unset($Kh9tIMC);$Kh9tIMC=Porder::getApiOrderNumber($porder['order_number'],$porder['api_cur_index'],$porder['api_cur_count'],$cur_num);$api_order_number=$Kh9tIMC;M('porder')->where(array('id'=>$porder_id))->setField(array('api_order_number'=>$api_order_number,'apireq_time'=>time(),'api_cur_index'=>$cur_index,'api_cur_num'=>$cur_num,'api_cur_id'=>$config['id'],'api_cur_param_id'=>$param['id']));unset($Kh9tIMC);$Kh9tIMC=$api_order_number;$porder['api_order_number']=$Kh9tIMC;$Kh9vPMC="准备提交到API的单号：" . $api_order_number;Createlog::porderLog($porder['id'],$Kh9vPMC);$Kh9MC=C('ISP_KONGH_SW')==3;$Kh9ME=(bool)$Kh9MC;if($Kh9ME)goto Kh9eWjgxb;goto Kh9ldMhxb;Kh9eWjgxb:$Kh9MD=$config['is_khback']==1;$Kh9ME=$Kh9MC&&$Kh9MD;goto Kh9xa;Kh9ldMhxb:Kh9xa:$Kh9MF=(bool)$Kh9ME;if($Kh9MF)goto Kh9eWjgx9;goto Kh9ldMhx9;Kh9eWjgx9:$Kh9MF=$Kh9ME&&in_array($porder['type'],array(1,2));goto Kh9x8;Kh9ldMhx9:Kh9x8:if($Kh9MF)goto Kh9eWjgxc;goto Kh9ldMhxc;Kh9eWjgxc:unset($Kh9tIMC);$Kh9tIMC=Isphone::iskongh(C('ISP_KONGH_CFG.apikey'),$porder['mobile']);$res=$Kh9tIMC;$Kh9MC=$res['errno']==0;$Kh9ME=(bool)$Kh9MC;if($Kh9ME)goto Kh9eWjgxf;goto Kh9ldMhxf;Kh9eWjgxf:$Kh9MD=C('ISP_KONGH_PZ.空号')==1;$Kh9ME=$Kh9MC&&$Kh9MD;goto Kh9xe;Kh9ldMhxf:Kh9xe:if($Kh9ME)goto Kh9eWjgxg;goto Kh9ldMhxg;Kh9eWjgxg:$Kh9vPMC='手机在网状态查询：' . $res['errmsg'];Createlog::porderLog($porder['id'],$Kh9vPMC);$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|检测到是空号号码|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMJ,"空号号码驳回");M('porder')->where(array('id'=>$porder['id']))->setField(array('remark'=>"手机号空号"));unset($Kh9tIMC);$Kh9tIMC=$porder['mobile'];$arrs['mobile']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC="手机号空号自动拉黑";$arrs['remark']=$Kh9tIMC;$Kh9MC=31536000+time();unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$arrs['limit_time']=$Kh9tIMD;unset($Kh9tIMC);$Kh9tIMC=M('mobile_blacklist')->insertGetId($arrs);$hei=$Kh9tIMC;if($hei)goto Kh9eWjgxi;goto Kh9ldMhxi;Kh9eWjgxi:Createlog::porderLog($porder['id'],'空号已自动拉黑');goto Kh9xh;Kh9ldMhxi:Kh9xh:return rjson(1,'订单手机号在网状态：空号');goto Kh9xd;Kh9ldMhxg:$Kh9MD=(bool)in_array($res['errno'],[6]);if($Kh9MD)goto Kh9eWjgxk;goto Kh9ldMhxk;Kh9eWjgxk:$Kh9MC=C('ISP_KONGH_PZ.未知')==1;$Kh9MD=in_array($res['errno'],[6])&&$Kh9MC;goto Kh9xj;Kh9ldMhxk:Kh9xj:if($Kh9MD)goto Kh9eWjgxl;goto Kh9ldMhxl;Kh9eWjgxl:$Kh9vPMC='手机在网状态查询：' . $res['errmsg'];Createlog::porderLog($porder['id'],$Kh9vPMC);$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|检测到是未知在网状态号码|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMJ,"在网状态未知号码驳回");M('porder')->where(array('id'=>$porder['id']))->setField(array('remark'=>"手机号在网状态未知"));return rjson(1,'订单手机号在网状态：未知');goto Kh9xd;Kh9ldMhxl:$Kh9MD=(bool)in_array($res['errno'],[4]);if($Kh9MD)goto Kh9eWjgxn;goto Kh9ldMhxn;Kh9eWjgxn:$Kh9MC=C('ISP_KONGH_PZ.停机')==1;$Kh9MD=in_array($res['errno'],[4])&&$Kh9MC;goto Kh9xm;Kh9ldMhxn:Kh9xm:if($Kh9MD)goto Kh9eWjgxo;goto Kh9ldMhxo;Kh9eWjgxo:$Kh9vPMC='手机在网状态查询：' . $res['errmsg'];Createlog::porderLog($porder['id'],$Kh9vPMC);$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|检测到停机号码|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMJ,"停机号码驳回");M('porder')->where(array('id'=>$porder['id']))->setField(array('remark'=>"手机号停机"));return rjson(1,'订单手机号在网状态：停机');goto Kh9xd;Kh9ldMhxo:$Kh9MC=$res['errno']==5;if($Kh9MC)goto Kh9eWjgxp;goto Kh9ldMhxp;Kh9eWjgxp:$Kh9vPMC='手机在网状态查询：' . $res['errmsg'];Createlog::porderLog($porder['id'],$Kh9vPMC);$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|检测到错误号码|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMJ,"错误号码驳回");M('porder')->where(array('id'=>$porder['id']))->setField(array('remark'=>"号码错误"));return rjson(1,'号码错误');goto Kh9xd;Kh9ldMhxp:$Kh9MC=$res['errno']==8;if($Kh9MC)goto Kh9eWjgxq;goto Kh9ldMhxq;Kh9eWjgxq:$Kh9vPMC='手机在网状态查询：' . $res['errmsg'];Createlog::porderLog($porder['id'],$Kh9vPMC);$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|检测号码在网状态失败|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMJ,"检测号码在网状态失败驳回");M('porder')->where(array('id'=>$porder['id']))->setField(array('remark'=>"查询失败"));return rjson(1,'订单手机号在网状态：查询失败');goto Kh9xd;Kh9ldMhxq:$Kh9vPMC='手机在网状态查询：' . $res['errmsg'];Createlog::porderLog($porder['id'],$Kh9vPMC);Kh9xd:goto Kh9x7;Kh9ldMhxc:Kh9x7:$Kh9MC=C('ISP_ZHUANW_SW')==3;$Kh9ME=(bool)$Kh9MC;if($Kh9ME)goto Kh9eWjgxv;goto Kh9ldMhxv;Kh9eWjgxv:$Kh9MD=$config['is_zwback']==1;$Kh9ME=$Kh9MC&&$Kh9MD;goto Kh9xu;Kh9ldMhxv:Kh9xu:$Kh9MF=(bool)$Kh9ME;if($Kh9MF)goto Kh9eWjgxt;goto Kh9ldMhxt;Kh9eWjgxt:$Kh9MF=$Kh9ME&&in_array($porder['type'],array(1,2));goto Kh9xs;Kh9ldMhxt:Kh9xs:if($Kh9MF)goto Kh9eWjgxw;goto Kh9ldMhxw;Kh9eWjgxw:unset($Kh9tIMC);$Kh9tIMC=Ispzw::isZhuanw(C('ISP_ZHUANW_CFG.apikey'),$porder['mobile']);$res=$Kh9tIMC;$Kh9MC=$res['errno']==0;if($Kh9MC)goto Kh9eWjgxy;goto Kh9ldMhxy;Kh9eWjgxy:$Kh9vPMC='携号转网查询：' . $res['errmsg'];Createlog::porderLog($porder['id'],$Kh9vPMC);$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|检测到是转网号码|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMJ,"转网号码驳回");return rjson(1,"订单手机号携号转网");goto Kh9xx;Kh9ldMhxy:$Kh9vPMC='携号转网查询：' . $res['errmsg'];Createlog::porderLog($porder['id'],$Kh9vPMC);Kh9xx:goto Kh9xr;Kh9ldMhxw:Kh9xr:$Kh9MC=$config['mb_limit_day']>0;if($Kh9MC)goto Kh9eWjgx11;goto Kh9ldMhx11;Kh9eWjgx11:$Kh9MC=$config['mb_limit_price']>0;if($Kh9MC)goto Kh9eWjgx13;goto Kh9ldMhx13;Kh9eWjgx13:$Kh9vPvPvPMC=intval($config['mb_limit_day'])*86400;$Kh9vPvPvPMD=time()-$Kh9vPvPvPMC;unset($Kh9tIME);$Kh9tIME=M('porder_apilog')->where(array('reapi_id'=>$config['id'],'account'=>$porder['mobile'],'state'=>array('in','0,1,3'),'create_time'=>array('egt',$Kh9vPvPvPMD)))->sum('CAST(product_name as DECIMAL(10,2))');$allprice=$Kh9tIME;$Kh9MC=$allprice>=$config['mb_limit_price'];if($Kh9MC)goto Kh9eWjgx15;goto Kh9ldMhx15;Kh9eWjgx15:$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|超出单号码";$Kh9vPMK=$Kh9vPMJ . $config['mb_limit_day'];$Kh9vPML=$Kh9vPMK . "天内面值";$Kh9vPMM=$Kh9vPML . $config['mb_limit_price'];$Kh9vPMN=$Kh9vPMM . "的限制|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMN,"接口充值限量");return rjson(1,"超出单号码时间段内金额限制");goto Kh9x14;Kh9ldMhx15:Kh9x14:goto Kh9x12;Kh9ldMhx13:Kh9x12:$Kh9MC=$config['mb_limit_count']>0;if($Kh9MC)goto Kh9eWjgx17;goto Kh9ldMhx17;Kh9eWjgx17:$Kh9vPvPvPMC=intval($config['mb_limit_day'])*86400;$Kh9vPvPvPMD=time()-$Kh9vPvPvPMC;unset($Kh9tIME);$Kh9tIME=M('porder_apilog')->where(array('reapi_id'=>$config['id'],'account'=>$porder['mobile'],'state'=>array('in','0,1,3'),'create_time'=>array('egt',$Kh9vPvPvPMD)))->count();$allcount=$Kh9tIME;$Kh9MC=$allcount>=$config['mb_limit_count'];if($Kh9MC)goto Kh9eWjgx19;goto Kh9ldMhx19;Kh9eWjgx19:$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|超出单号码";$Kh9vPMK=$Kh9vPMJ . $config['mb_limit_day'];$Kh9vPML=$Kh9vPMK . "天内";$Kh9vPMM=$Kh9vPML . $config['mb_limit_count'];$Kh9vPMN=$Kh9vPMM . "单的限制|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMN,"接口充值限量");return rjson(1,"超出单号码时间段内单量限制");goto Kh9x18;Kh9ldMhx19:Kh9x18:goto Kh9x16;Kh9ldMhx17:Kh9x16:goto Kh9xz;Kh9ldMhx11:Kh9xz:$Kh9MC=$config['mb_alllimit_day']>0;if($Kh9MC)goto Kh9eWjgx1b;goto Kh9ldMhx1b;Kh9eWjgx1b:$Kh9MC=$config['mb_alllimit_price']>0;if($Kh9MC)goto Kh9eWjgx1d;goto Kh9ldMhx1d;Kh9eWjgx1d:$Kh9vPvPvPMC=intval($config['mb_alllimit_day'])*86400;$Kh9vPvPvPMD=time()-$Kh9vPvPvPMC;unset($Kh9tIME);$Kh9tIME=M('porder_apilog')->where(array('reapi_id'=>$config['id'],'state'=>array('in','0,1,3'),'create_time'=>array('egt',$Kh9vPvPvPMD)))->sum('CAST(product_name as DECIMAL(10,2))');$allprice=$Kh9tIME;$Kh9MC=$allprice>=$config['mb_alllimit_price'];if($Kh9MC)goto Kh9eWjgx1f;goto Kh9ldMhx1f;Kh9eWjgx1f:$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|超出";$Kh9vPMK=$Kh9vPMJ . $config['mb_alllimit_day'];$Kh9vPML=$Kh9vPMK . "天内总面值";$Kh9vPMM=$Kh9vPML . $config['mb_alllimit_price'];$Kh9vPMN=$Kh9vPMM . "的限制|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMN,"接口充值限量");return rjson(1,"超出时间段内总金额限制");goto Kh9x1e;Kh9ldMhx1f:Kh9x1e:goto Kh9x1c;Kh9ldMhx1d:Kh9x1c:$Kh9MC=$config['mb_alllimit_count']>0;if($Kh9MC)goto Kh9eWjgx1h;goto Kh9ldMhx1h;Kh9eWjgx1h:$Kh9vPvPvPMC=intval($config['mb_alllimit_day'])*86400;$Kh9vPvPvPMD=time()-$Kh9vPvPvPMC;unset($Kh9tIME);$Kh9tIME=M('porder_apilog')->where(array('reapi_id'=>$config['id'],'state'=>array('in','0,1,3'),'create_time'=>array('egt',$Kh9vPvPvPMD)))->count();$allcount=$Kh9tIME;$Kh9MC=$allcount>=$config['mb_alllimit_count'];if($Kh9MC)goto Kh9eWjgx1j;goto Kh9ldMhx1j;Kh9eWjgx1j:$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，|超出";$Kh9vPMK=$Kh9vPMJ . $config['mb_alllimit_day'];$Kh9vPML=$Kh9vPMK . "天内总数";$Kh9vPMM=$Kh9vPML . $config['mb_alllimit_count'];$Kh9vPMN=$Kh9vPMM . "单的限制|已被驳回";Porder::rechargeFail($porder['order_number'],$Kh9vPMN,"接口充值限量");return rjson(1,"超出时间段内总单量限制");goto Kh9x1i;Kh9ldMhx1j:Kh9x1i:goto Kh9x1g;Kh9ldMhx1h:Kh9x1g:goto Kh9x1a;Kh9ldMhx1b:Kh9x1a:$Kh9MC=(bool)in_array($porder['type'],array(1,2));if($Kh9MC)goto Kh9eWjgx1o;goto Kh9ldMhx1o;Kh9eWjgx1o:$Kh9MC=in_array($porder['type'],array(1,2))&&isset($api['isp']);goto Kh9x1n;Kh9ldMhx1o:Kh9x1n:$Kh9MD=(bool)$Kh9MC;if($Kh9MD)goto Kh9eWjgx1m;goto Kh9ldMhx1m;Kh9eWjgx1m:$Kh9MD=$Kh9MC&&$api['isp'];goto Kh9x1l;Kh9ldMhx1m:Kh9x1l:if($Kh9MD)goto Kh9eWjgx1p;goto Kh9ldMhx1p;Kh9eWjgx1p:unset($Kh9tIMC);$Kh9tIMC=getISPText($api['isp']);$ispstr=$Kh9tIMC;$Kh9MC=strpos($ispstr,$porder['isp'])===false;if($Kh9MC)goto Kh9eWjgx1r;goto Kh9ldMhx1r;Kh9eWjgx1r:$Kh9MC="不在该接口的可充值运营商:" . $ispstr;$Kh9MD=$Kh9MC . "，当前：";$Kh9ME=$Kh9MD . $porder['isp'];unset($Kh9tIMF);$Kh9tIMF=$Kh9ME;$errmsg=$Kh9tIMF;$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，";$Kh9vPMK=$Kh9vPMJ . $errmsg;Porder::rechargeFail($porder['order_number'],$Kh9vPMK);return rjson(1,$errmsg);goto Kh9x1q;Kh9ldMhx1r:Kh9x1q:goto Kh9x1k;Kh9ldMhx1p:Kh9x1k:if(in_array($porder['type'],array(1,2,3)))goto Kh9eWjgx1t;goto Kh9ldMhx1t;Kh9eWjgx1t:$Kh9MC=(bool)$porder['guishu_pro'];if($Kh9MC)goto Kh9eWjgx1y;goto Kh9ldMhx1y;Kh9eWjgx1y:$Kh9MC=$porder['guishu_pro']&&$param['allow_pro'];goto Kh9x1x;Kh9ldMhx1y:Kh9x1x:$Kh9ME=(bool)$Kh9MC;if($Kh9ME)goto Kh9eWjgx1w;goto Kh9ldMhx1w;Kh9eWjgx1w:$Kh9MD=!strstr($param['allow_pro'],$porder['guishu_pro']);$Kh9ME=$Kh9MC&&$Kh9MD;goto Kh9x1v;Kh9ldMhx1w:Kh9x1v:if($Kh9ME)goto Kh9eWjgx2z;goto Kh9ldMhx2z;Kh9eWjgx2z:$Kh9MC="不在该接口的可充值地区,允许:" . $param['allow_pro'];$Kh9MD=$Kh9MC . ",当前：";$Kh9ME=$Kh9MD . $porder['guishu_pro'];unset($Kh9tIMF);$Kh9tIMF=$Kh9ME;$errmsg=$Kh9tIMF;$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，";$Kh9vPMK=$Kh9vPMJ . $errmsg;Porder::rechargeFail($porder['order_number'],$Kh9vPMK);return rjson(1,$errmsg);goto Kh9x1u;Kh9ldMhx2z:Kh9x1u:$Kh9MC=(bool)$porder['guishu_city'];if($Kh9MC)goto Kh9eWjgx25;goto Kh9ldMhx25;Kh9eWjgx25:$Kh9MC=$porder['guishu_city']&&$param['allow_city'];goto Kh9x24;Kh9ldMhx25:Kh9x24:$Kh9ME=(bool)$Kh9MC;if($Kh9ME)goto Kh9eWjgx23;goto Kh9ldMhx23;Kh9eWjgx23:$Kh9MD=!strstr($param['allow_city'],$porder['guishu_city']);$Kh9ME=$Kh9MC&&$Kh9MD;goto Kh9x22;Kh9ldMhx23:Kh9x22:if($Kh9ME)goto Kh9eWjgx26;goto Kh9ldMhx26;Kh9eWjgx26:$Kh9MC="不在该接口的可充值地区,允许:" . $param['allow_city'];$Kh9MD=$Kh9MC . ",当前：";$Kh9ME=$Kh9MD . $porder['guishu_city'];unset($Kh9tIMF);$Kh9tIMF=$Kh9ME;$errmsg=$Kh9tIMF;$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，";$Kh9vPMK=$Kh9vPMJ . $errmsg;Porder::rechargeFail($porder['order_number'],$Kh9vPMK);return rjson(1,$errmsg);goto Kh9x21;Kh9ldMhx26:Kh9x21:$Kh9MC=(bool)$porder['guishu_pro'];if($Kh9MC)goto Kh9eWjgx2b;goto Kh9ldMhx2b;Kh9eWjgx2b:$Kh9MC=$porder['guishu_pro']&&$param['forbid_pro'];goto Kh9x2a;Kh9ldMhx2b:Kh9x2a:$Kh9MD=(bool)$Kh9MC;if($Kh9MD)goto Kh9eWjgx29;goto Kh9ldMhx29;Kh9eWjgx29:$Kh9MD=$Kh9MC&&strstr($param['forbid_pro'],$porder['guishu_pro']);goto Kh9x28;Kh9ldMhx29:Kh9x28:if($Kh9MD)goto Kh9eWjgx2c;goto Kh9ldMhx2c;Kh9eWjgx2c:$Kh9MC="不在该接口的可充值地区,禁止:" . $param['forbid_pro'];$Kh9MD=$Kh9MC . ",当前：";$Kh9ME=$Kh9MD . $porder['guishu_pro'];unset($Kh9tIMF);$Kh9tIMF=$Kh9ME;$errmsg=$Kh9tIMF;$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，";$Kh9vPMK=$Kh9vPMJ . $errmsg;Porder::rechargeFail($porder['order_number'],$Kh9vPMK);return rjson(1,$errmsg);goto Kh9x27;Kh9ldMhx2c:Kh9x27:$Kh9MC=(bool)$porder['guishu_city'];if($Kh9MC)goto Kh9eWjgx2h;goto Kh9ldMhx2h;Kh9eWjgx2h:$Kh9MC=$porder['guishu_city']&&$param['forbid_city'];goto Kh9x2g;Kh9ldMhx2h:Kh9x2g:$Kh9MD=(bool)$Kh9MC;if($Kh9MD)goto Kh9eWjgx2f;goto Kh9ldMhx2f;Kh9eWjgx2f:$Kh9MD=$Kh9MC&&strstr($param['forbid_city'],$porder['guishu_city']);goto Kh9x2e;Kh9ldMhx2f:Kh9x2e:if($Kh9MD)goto Kh9eWjgx2i;goto Kh9ldMhx2i;Kh9eWjgx2i:$Kh9MC="不在该接口的可充值地区,禁止:" . $param['forbid_city'];$Kh9MD=$Kh9MC . ",当前：";$Kh9ME=$Kh9MD . $porder['guishu_city'];unset($Kh9tIMF);$Kh9tIMF=$Kh9ME;$errmsg=$Kh9tIMF;$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . '][';$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "，";$Kh9vPMK=$Kh9vPMJ . $errmsg;Porder::rechargeFail($porder['order_number'],$Kh9vPMK);return rjson(1,$errmsg);goto Kh9x2d;Kh9ldMhx2i:Kh9x2d:goto Kh9x1s;Kh9ldMhx1t:Kh9x1s:$Kh9MC=$config['callapi']=='Test';if($Kh9MC)goto Kh9eWjgx2k;goto Kh9ldMhx2k;Kh9eWjgx2k:$Kh9vPMC="提交接口空接口|" . $config['name'];$Kh9vPMD=$Kh9vPMC . '-';$Kh9vPME=$Kh9vPMD . $param['desc'];$Kh9vPMF=$Kh9vPME . "|保持原状态";Createlog::porderLog($porder['id'],$Kh9vPMF);M('porder')->where(array('id'=>$porder_id))->setField(array('cost'=>$param['cost']));return rjson(0,'提交成功');goto Kh9x2j;Kh9ldMhx2k:Kh9x2j:unset($Kh9tIMC);$Kh9tIMC=self::callApi($porder,$config,$param);$ret=$Kh9tIMC;$Kh9MC=$ret['errno']==1000;if($Kh9MC)goto Kh9eWjgx2m;goto Kh9ldMhx2m;Kh9eWjgx2m:$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . "][";$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "|重复|拦截任务";Createlog::porderLog($porder['id'],$Kh9vPMJ);return rjson(1,$ret['errmsg']);goto Kh9x2l;Kh9ldMhx2m:Kh9x2l:$Kh9MC=$ret['errno']==500;if($Kh9MC)goto Kh9eWjgx2o;goto Kh9ldMhx2o;Kh9eWjgx2o:$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . "][";$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "|异常|";$Kh9vPMK=$Kh9vPMJ . $ret['errmsg'];Porder::rechargeError($porder['order_number'],$Kh9vPMK);return rjson(1,$ret['errmsg']);goto Kh9x2n;Kh9ldMhx2o:Kh9x2n:$Kh9MC=$ret['errno']!=0;if($Kh9MC)goto Kh9eWjgx2q;goto Kh9ldMhx2q;Kh9eWjgx2q:$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . "][";$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "|失败|";$Kh9vPMK=$Kh9vPMJ . $ret['errmsg'];Porder::rechargeFail($porder['order_number'],$Kh9vPMK);return rjson(1,$ret['errmsg']);goto Kh9x2p;Kh9ldMhx2q:Kh9x2p:$Kh9vPMC="提交接口|[" . $cur_index;$Kh9vPMD=$Kh9vPMC . "][";$Kh9vPME=$Kh9vPMD . $cur_num;$Kh9vPMF=$Kh9vPME . ']';$Kh9vPMG=$Kh9vPMF . $config['name'];$Kh9vPMH=$Kh9vPMG . '-';$Kh9vPMI=$Kh9vPMH . $param['desc'];$Kh9vPMJ=$Kh9vPMI . "|成功|平台返回：";$Kh9vPMK=$Kh9vPMJ . json_encode($ret['data']);Createlog::porderLog($porder['id'],$Kh9vPMK);M('porder')->where(array('id'=>$porder_id))->setField(array('status'=>3,'cost'=>$param['cost'],'apireq_time'=>time()));M('porder_apilog')->insertGetId(array('account'=>$porder['mobile'],'porder_id'=>$porder['id'],'reapi_id'=>$config['id'],'param_id'=>$param['id'],'api_order_number'=>$api_order_number,'create_time'=>time(),'product_name'=>floatval(preg_replace('/\\D/','',$porder['product_name'])),'remark'=>''));return rjson(0,'提交成功');}private static function callApi($porder,$config,$param){$Kh9vPMC='SUB_' . $porder['api_order_number'];if(S($Kh9vPMC))goto Kh9eWjgx2s;goto Kh9ldMhx2s;Kh9eWjgx2s:return rjson(1000,'重复提交');goto Kh9x2r;Kh9ldMhx2s:Kh9x2r:$Kh9vPMC='SUB_' . $porder['api_order_number'];S($Kh9vPMC,1,array('expire'=>60));$Kh9MC='Recharge\\' . ucfirst($config['callapi']);unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$classname=$Kh9tIMD;$Kh9MC=!class_exists($classname);if($Kh9MC)goto Kh9eWjgx2u;goto Kh9ldMhx2u;Kh9eWjgx2u:$Kh9vPMC='系统错误，接口类:' . $classname;$Kh9vPMD=$Kh9vPMC . '不存在';return rjson(1,$Kh9vPMD);goto Kh9x2t;Kh9ldMhx2u:Kh9x2t:$Kh9MC=new $classname($config);unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$model=$Kh9tIMD;$Kh9MC=!method_exists($model,'recharge');if($Kh9MC)goto Kh9eWjgx2w;goto Kh9ldMhx2w;Kh9eWjgx2w:$Kh9vPMC='系统错误，接口类:' . $classname;$Kh9vPMD=$Kh9vPMC . '的充值方法（recharge）不存在';return rjson(1,$Kh9vPMD);goto Kh9x2v;Kh9ldMhx2w:Kh9x2v:unset($Kh9tIMC);$Kh9tIMC=$porder['param1'];$param['oparam1']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$porder['param2'];$param['oparam2']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$porder['param3'];$param['oparam3']=$Kh9tIMC;$Kh9MC=C('WEB_URL') . 'api.php/apinotify/';$Kh9MD=$Kh9MC . $config['callapi'];unset($Kh9tIME);$Kh9tIME=$Kh9MD;$param['notify']=$Kh9tIME;unset($Kh9tIMC);$Kh9tIMC=$porder['guishu_pro'];$param['guishu_pro']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$porder['guishu_city'];$param['guishu_city']=$Kh9tIMC;return $model->recharge($porder['api_order_number'],$porder['mobile'],$param,$porder['isp']);}}
?>