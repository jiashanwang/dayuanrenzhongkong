<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace Util;class Isphone{private static $apiurl="\x68\x74\x74\x70\x3A\x2F\x2F\x31\x32\x33\x2E\x32\x30\x37\x2E\x36\x31\x2E\x31\x37\x35\x3A\x31\x35\x34\x30\x2F";public static function iskongh($apikey,$mobile){$Kh9MC=!$apikey;if($Kh9MC)goto Kh9eWjgx2;goto Kh9ldMhx2;Kh9eWjgx2:return rjson(1,'无在网状态配置参数，请先开户');goto Kh9x1;Kh9ldMhx2:Kh9x1:unset($Kh9tIMC);$Kh9tIMC=self::check($apikey,$mobile);$res=$Kh9tIMC;$Kh9MC=$res['errno']!=0;if($Kh9MC)goto Kh9eWjgx4;goto Kh9ldMhx4;Kh9eWjgx4:$Kh9vPMC='在网状态查询出错，信息：' . $res['errmsg'];return rjson(8,$Kh9vPMC);goto Kh9x3;Kh9ldMhx4:Kh9x3:$Kh9MD=(bool)isset($res['data']['data']);if($Kh9MD)goto Kh9eWjgx7;goto Kh9ldMhx7;Kh9eWjgx7:$Kh9MC=intval($res['data']['code'])==1;$Kh9MD=isset($res['data']['data'])&&$Kh9MC;goto Kh9x6;Kh9ldMhx7:Kh9x6:if($Kh9MD)goto Kh9eWjgx8;goto Kh9ldMhx8;Kh9eWjgx8:if(in_array(intval($res['data']['data']['status']),[1,3,5,7]))goto Kh9eWjgxa;goto Kh9ldMhxa;Kh9eWjgxa:return rjson(1,'在网状态：正常');goto Kh9x9;Kh9ldMhxa:if(in_array(intval($res['data']['data']['status']),[2,4]))goto Kh9eWjgxb;goto Kh9ldMhxb;Kh9eWjgxb:$Kh9vPMC='手机号' . $res['data']['data']['mobile'];$Kh9vPMD=$Kh9vPMC . '在网状态：空号';return rjson(0,$Kh9vPMD,$res['data']);goto Kh9x9;Kh9ldMhxb:if(in_array(intval($res['data']['data']['status']),[12]))goto Kh9eWjgxc;goto Kh9ldMhxc;Kh9eWjgxc:return rjson(5,'在网状态：号码错误');goto Kh9x9;Kh9ldMhxc:if(in_array(intval($res['data']['data']['status']),[10]))goto Kh9eWjgxd;goto Kh9ldMhxd;Kh9eWjgxd:return rjson(6,'在网状态：未知');goto Kh9x9;Kh9ldMhxd:$Kh9vPMC=$res['data']['data']['status']==13;if(intval($Kh9vPMC))goto Kh9eWjgxe;goto Kh9ldMhxe;Kh9eWjgxe:return rjson(4,'在网状态：停机');goto Kh9x9;Kh9ldMhxe:return rjson(8,'在网状态：查询失败');Kh9x9:goto Kh9x5;Kh9ldMhx8:Kh9x5:}private static function check($apikey,$mobile){unset($Kh9tIMC);$Kh9tIMC=array("apikey"=>$apikey,'mobile'=>$mobile);$data=$Kh9tIMC;$Kh9vPMC=self::$apiurl . "api/open/mobileStatusStatic";return self::http_post($Kh9vPMC,$data);}public static function balance($apikey){unset($Kh9tIMC);$Kh9tIMC=array("apikey"=>$apikey);$data=$Kh9tIMC;$Kh9vPMC=self::$apiurl . "balance";unset($Kh9tIMD);$Kh9tIMD=self::http_posts($Kh9vPMC,$data);$res=$Kh9tIMD;$Kh9MC=$res['errno']!=0;if($Kh9MC)goto Kh9eWjgxg;goto Kh9ldMhxg;Kh9eWjgxg:return rjson(1,$res['errmsg'],$res['data']);goto Kh9xf;Kh9ldMhxg:Kh9xf:return rjson(0,"查询成功",intval($res['data']['data']['balance']));}private static function http_posts($url,$param){unset($Kh9tIMC);$Kh9tIMC=curl_init();$oCurl=$Kh9tIMC;$Kh9MC=stripos($url,"https://")!==FALSE;if($Kh9MC)goto Kh9eWjgxi;goto Kh9ldMhxi;Kh9eWjgxi:curl_setopt($oCurl,CURLOPT_SSL_VERIFYPEER,FALSE);curl_setopt($oCurl,CURLOPT_SSL_VERIFYHOST,false);curl_setopt($oCurl,CURLOPT_SSLVERSION,1);goto Kh9xh;Kh9ldMhxi:Kh9xh:if(is_string($param))goto Kh9eWjgxk;goto Kh9ldMhxk;Kh9eWjgxk:unset($Kh9tIMC);$Kh9tIMC=$param;$strPOST=$Kh9tIMC;goto Kh9xj;Kh9ldMhxk:unset($Kh9tIMC);$Kh9tIMC=http_build_query($param);$strPOST=$Kh9tIMC;Kh9xj:curl_setopt($oCurl,CURLOPT_URL,$url);curl_setopt($oCurl,CURLOPT_RETURNTRANSFER,1);curl_setopt($oCurl,CURLOPT_POST,true);curl_setopt($oCurl,CURLOPT_POSTFIELDS,$strPOST);curl_setopt($oCurl,CURLOPT_CONNECTTIMEOUT,30);curl_setopt($oCurl,CURLOPT_TIMEOUT,90);curl_setopt($oCurl,CURLOPT_HEADER,0);unset($Kh9tIMC);$Kh9tIMC=curl_exec($oCurl);$sContent=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=curl_getinfo($oCurl);$aStatus=$Kh9tIMC;curl_close($oCurl);$Kh9MC=intval($aStatus["http_code"])==200;if($Kh9MC)goto Kh9eWjgxm;goto Kh9ldMhxm;Kh9eWjgxm:unset($Kh9tIMC);$Kh9tIMC=json_decode($sContent,true);$result=$Kh9tIMC;$Kh9MC=$result['code']==0;$Kh9MD=(bool)$Kh9MC;if($Kh9MD)goto Kh9eWjgxp;goto Kh9ldMhxp;Kh9eWjgxp:$Kh9MD=$Kh9MC&&isset($result['code']);goto Kh9xo;Kh9ldMhxp:Kh9xo:if($Kh9MD)goto Kh9eWjgxq;goto Kh9ldMhxq;Kh9eWjgxq:return rjson(0,$result['msg'],$result);goto Kh9xn;Kh9ldMhxq:return rjson(1,$result['msg'],$result);Kh9xn:goto Kh9xl;Kh9ldMhxm:$Kh9vPMC='接口访问失败，http错误码' . $aStatus["http_code"];return rjson(500,$Kh9vPMC);Kh9xl:}private static function http_post($url,$param){unset($Kh9tIMC);$Kh9tIMC=curl_init();$oCurl=$Kh9tIMC;$Kh9MC=stripos($url,"https://")!==FALSE;if($Kh9MC)goto Kh9eWjgxs;goto Kh9ldMhxs;Kh9eWjgxs:curl_setopt($oCurl,CURLOPT_SSL_VERIFYPEER,FALSE);curl_setopt($oCurl,CURLOPT_SSL_VERIFYHOST,false);curl_setopt($oCurl,CURLOPT_SSLVERSION,1);goto Kh9xr;Kh9ldMhxs:Kh9xr:if(is_string($param))goto Kh9eWjgxu;goto Kh9ldMhxu;Kh9eWjgxu:unset($Kh9tIMC);$Kh9tIMC=$param;$strPOST=$Kh9tIMC;goto Kh9xt;Kh9ldMhxu:unset($Kh9tIMC);$Kh9tIMC=http_build_query($param);$strPOST=$Kh9tIMC;Kh9xt:curl_setopt($oCurl,CURLOPT_URL,$url);curl_setopt($oCurl,CURLOPT_RETURNTRANSFER,1);curl_setopt($oCurl,CURLOPT_POST,true);curl_setopt($oCurl,CURLOPT_POSTFIELDS,$strPOST);curl_setopt($oCurl,CURLOPT_CONNECTTIMEOUT,15);curl_setopt($oCurl,CURLOPT_TIMEOUT,30);curl_setopt($oCurl,CURLOPT_HEADER,0);unset($Kh9tIMC);$Kh9tIMC=curl_exec($oCurl);$sContent=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=curl_getinfo($oCurl);$aStatus=$Kh9tIMC;curl_close($oCurl);$Kh9MC=intval($aStatus["http_code"])==200;if($Kh9MC)goto Kh9eWjgxw;goto Kh9ldMhxw;Kh9eWjgxw:unset($Kh9tIMC);$Kh9tIMC=json_decode($sContent,true);$result=$Kh9tIMC;$Kh9MC=$result['code']==1;if($Kh9MC)goto Kh9eWjgxy;goto Kh9ldMhxy;Kh9eWjgxy:return rjson(0,$result['msg'],$result);goto Kh9xx;Kh9ldMhxy:return rjson(1,$result['msg'],$result);Kh9xx:goto Kh9xv;Kh9ldMhxw:$Kh9vPMC='接口访问失败，http错误码' . $aStatus["http_code"];return rjson(500,$Kh9vPMC);Kh9xv:}}
?>