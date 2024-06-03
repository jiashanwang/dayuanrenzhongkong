<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\common\library;class Createlog{public static function porderLog($porderid,$log){$Kh9vPMC=$porderid%10;$Kh9vPMD='porder_log' . $Kh9vPMC;M($Kh9vPMD)->insertGetId(array('porder_id'=>$porderid,'log'=>$log,'create_time'=>time()));}public static function customerLog($customer_id,$log,$operator){M('customer_log')->insertGetId(array('customer_id'=>$customer_id,'log'=>$log,'operator'=>$operator,'create_time'=>time()));}}
?>