<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace Util;class Updatedt{protected static $instance;private static $check_url="\x68\x74\x74\x70\x73\x3A\x2F\x2F\x77\x77\x77\x2E\x62\x61\x69\x64\x75\x2E\x63\x6F\x6D";private function getFile($url,$path='',$filename='',$type=0){$Kh9MC=$url=='';if($Kh9MC)goto Kh9eWjgx2;goto Kh9ldMhx2;Kh9eWjgx2:return false;goto Kh9x1;Kh9ldMhx2:Kh9x1:$Kh9MC=$type===0;if($Kh9MC)goto Kh9eWjgx4;goto Kh9ldMhx4;Kh9eWjgx4:unset($Kh9tIMC);$Kh9tIMC=curl_init();$ch=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=5;$timeout=$Kh9tIMC;curl_setopt($ch,CURLOPT_URL,$url);curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);unset($Kh9tIMC);$Kh9tIMC=curl_exec($ch);$img=$Kh9tIMC;curl_close($ch);goto Kh9x3;Kh9ldMhx4:Kh9x3:$Kh9MC=$type===1;if($Kh9MC)goto Kh9eWjgx6;goto Kh9ldMhx6;Kh9eWjgx6:ob_start();readfile($url);unset($Kh9tIMC);$Kh9tIMC=ob_get_contents();$img=$Kh9tIMC;ob_end_clean();goto Kh9x5;Kh9ldMhx6:Kh9x5:$Kh9MC=$type===2;if($Kh9MC)goto Kh9eWjgx8;goto Kh9ldMhx8;Kh9eWjgx8:unset($Kh9tIMC);$Kh9tIMC=file_get_contents($url);$img=$Kh9tIMC;goto Kh9x7;Kh9ldMhx8:Kh9x7:if(empty($img))goto Kh9eWjgxa;goto Kh9ldMhxa;Kh9eWjgxa:return rjson(1,"下载错误,无法下载更新文件！");goto Kh9x9;Kh9ldMhxa:Kh9x9:$Kh9MC=$path==='';if($Kh9MC)goto Kh9eWjgxc;goto Kh9ldMhxc;Kh9eWjgxc:unset($Kh9tIMC);$Kh9tIMC="./";$path=$Kh9tIMC;goto Kh9xb;Kh9ldMhxc:Kh9xb:$Kh9MC=$filename==="";if($Kh9MC)goto Kh9eWjgxe;goto Kh9ldMhxe;Kh9eWjgxe:unset($Kh9tIMC);$Kh9tIMC=md5($img);$filename=$Kh9tIMC;goto Kh9xd;Kh9ldMhxe:Kh9xd:unset($Kh9tIMC);$Kh9tIMC=substr($url,strrpos($url,'.'));$ext=$Kh9tIMC;$Kh9MD=(bool)$ext;if($Kh9MD)goto Kh9eWjgxh;goto Kh9ldMhxh;Kh9eWjgxh:$Kh9MC=strlen($ext)<5;$Kh9MD=$ext&&$Kh9MC;goto Kh9xg;Kh9ldMhxh:Kh9xg:if($Kh9MD)goto Kh9eWjgxi;goto Kh9ldMhxi;Kh9eWjgxi:$filename=$filename.$ext;$Kh9nWMC=$filename;goto Kh9xf;Kh9ldMhxi:Kh9xf:$Kh9MC=rtrim($path,"/") . "/";unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$path=$Kh9tIMD;$Kh9vPMC=$path . $filename;unset($Kh9tIMD);$Kh9tIMD=@fopen($Kh9vPMC,'a');$fp2=$Kh9tIMD;fwrite($fp2,$img);fclose($fp2);return rjson(0,'下载完成',$filename);}public function unzip($version,$name){$Kh9MC=$_SERVER['DOCUMENT_ROOT'] . '/../';unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$dir=$Kh9tIMD;if(class_exists('ZipArchive'))goto Kh9eWjgxk;goto Kh9ldMhxk;Kh9eWjgxk:$Kh9MC=new \ZipArchive();unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$zip=$Kh9tIMD;$Kh9MC=$zip->open($name)!==TRUE;if($Kh9MC)goto Kh9eWjgxm;goto Kh9ldMhxm;Kh9eWjgxm:return rjson(1,'无法打开更新文件');goto Kh9xl;Kh9ldMhxm:Kh9xl:$i=0;Kh9xn:$Kh9MC=$i<$zip->numFiles;if($Kh9MC)goto Kh9eWjgxr;goto Kh9ldMhxr;Kh9eWjgxr:unset($Kh9tIMC);$Kh9tIMC=$zip->getNameIndex($i);$filename=$Kh9tIMC;$Kh9vPMC='framework-' . $version;unset($Kh9tIMD);$Kh9tIMD=str_replace($Kh9vPMC,'',$filename);unset($Kh9tIMC);$Kh9tIMC=$Kh9tIMD;$_filename=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=ini_get("error_reporting");$GLOBALS["Ox3430"]=$Kh9tIMC;error_reporting(0);$Kh9vPvPMC=$dir . $_filename;$Kh9eRMD=mkdir(dirname($Kh9vPvPMC),0777,true);error_reporting($GLOBALS["Ox3430"]);unset($Kh9tIMC);$Kh9tIMC=ini_get("error_reporting");$GLOBALS["Ox3430"]=$Kh9tIMC;error_reporting(0);$Kh9vPMC="zip://" . $name;$Kh9vPMD=$Kh9vPMC . "#";$Kh9vPME=$Kh9vPMD . $filename;$Kh9vPMF=$dir . $_filename;$Kh9eRMG=copy($Kh9vPME,$Kh9vPMF);error_reporting($GLOBALS["Ox3430"]);Kh9xo:$Kh9oB230=$i;$Kh9oB231=$i+1;$i=$Kh9oB231;goto Kh9xn;goto Kh9xq;Kh9ldMhxr:Kh9xq:Kh9xp:$zip->close();return rjson(0,'解压完成',$dir);goto Kh9xj;Kh9ldMhxk:Kh9xj:return rjson(1,'服务器环境异常，无法解压更新文件，请确保ZipArchive安装正确');}public function checkThinkPHPVersion(){return C('dtupdate.version');}public function download($download_url,$version,$type=0,$checkv=true){unset($Kh9tIMC);$Kh9tIMC=$this->checkThinkPHPVersion();$old_version=$Kh9tIMC;$Kh9MD=(bool)$checkv;if($Kh9MD)goto Kh9eWjgxu;goto Kh9ldMhxu;Kh9eWjgxu:$Kh9MC=$old_version>=$version;$Kh9MD=$checkv&&$Kh9MC;goto Kh9xt;Kh9ldMhxu:Kh9xt:if($Kh9MD)goto Kh9eWjgxv;goto Kh9ldMhxv;Kh9eWjgxv:$Kh9vPMC="当前版本不需要更新！" . $old_version;$Kh9vPMD=$Kh9vPMC . $version;return rjson(1,$Kh9vPMD);goto Kh9xs;Kh9ldMhxv:Kh9xs:$Kh9MC=$version . '-';$Kh9MD=$Kh9MC . time();unset($Kh9tIME);$Kh9tIME=$Kh9MD;$filename=$Kh9tIME;return $this->getFile($download_url,'',$filename,$type);}public function start($version,$download_url,$type=2,$checkv=true){unset($Kh9tIMC);$Kh9tIMC=$this->download($download_url,$version,$type,$checkv);$dowres=$Kh9tIMC;$Kh9MC=$dowres['errno']!=0;if($Kh9MC)goto Kh9eWjgxx;goto Kh9ldMhxx;Kh9eWjgxx:return $dowres;goto Kh9xw;Kh9ldMhxx:Kh9xw:unset($Kh9tIMC);$Kh9tIMC=$dowres['data'];$filename=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$this->unzip($version,$filename);$res=$Kh9tIMC;unlink($filename);return $res;}public function check(){unset($Kh9tIMC);$Kh9tIMC=array('content'=>'已是最新版');$res['data']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=0;$res['errno']=$Kh9tIMC;return rjson(2,'当前版本不需要更新！',$res['data']);die();unset($Kh9tIMC);$Kh9tIMC=Http::get(self::$check_url,array('host'=>$_SERVER['HTTP_HOST']));$json=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=json_decode($json,true);$res=$Kh9tIMC;$Kh9MC=!$res;if($Kh9MC)goto Kh9eWjgxz;goto Kh9ldMhxz;Kh9eWjgxz:return rjson(1,'异常，无法检查版本号,请联系官方客服人员，电话/微信:18380807104');goto Kh9xy;Kh9ldMhxz:Kh9xy:$Kh9MC=$res['code']==0;$Kh9MD=(bool)$Kh9MC;if($Kh9MD)goto Kh9eWjgx13;goto Kh9ldMhx13;Kh9eWjgx13:$Kh9MD=$Kh9MC&&$res['data']['wgt'];goto Kh9x12;Kh9ldMhx13:Kh9x12:if($Kh9MD)goto Kh9eWjgx14;goto Kh9ldMhx14;Kh9eWjgx14:unset($Kh9tIMC);$Kh9tIMC=floatval(C('dtupdate.version'));$localv=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=floatval($res['data']['wgt']['version']);$onlinev=$Kh9tIMC;$Kh9MC=$localv>=$onlinev;if($Kh9MC)goto Kh9eWjgx16;goto Kh9ldMhx16;Kh9eWjgx16:return rjson(2,'当前版本不需要更新！',$res['data']);goto Kh9x15;Kh9ldMhx16:Kh9x15:return rjson(0,'系统可更新',$res['data']);goto Kh9x11;Kh9ldMhx14:$Kh9vPMC='异常，无法检查版本号，提示：' . $res['msg'];return rjson(1,$Kh9vPMC);Kh9x11:}public function executesql(){$Kh9MC=$_SERVER['DOCUMENT_ROOT'] . '/../update.sql';unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$sqlfile=$Kh9tIMD;$Kh9MC=!file_exists($sqlfile);if($Kh9MC)goto Kh9eWjgx18;goto Kh9ldMhx18;Kh9eWjgx18:return rjson(1,'不存在sql更新文件');goto Kh9x17;Kh9ldMhx18:Kh9x17:unset($Kh9tIMC);$Kh9tIMC=file_get_contents($sqlfile);$sql=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=explode("
",$sql);$sqlqrr=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=array();$data=$Kh9tIMC;foreach($sqlqrr as $k=>$hang){try{M()->strict(false)->query($hang);}catch(\Exception $exception){$Kh9MC='[' . $k;$Kh9MD=$Kh9MC . '行]';$Kh9ME=$Kh9MD . $exception->getMessage();unset($Kh9tIMF);$Kh9tIMF=$Kh9ME;$data[]=$Kh9tIMF;}}unlink($sqlfile);return rjson(0,'sql更新成功',$data);}public function log($version,$sql_ret,$zip_url){M('sysupdate_log')->insertGetId(array('version'=>$version,'sql_ret'=>$sql_ret,'zip_url'=>$zip_url,'create_time'=>time()));}}
?>