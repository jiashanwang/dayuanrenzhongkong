<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\agent\controller;use app\common\model\CustomerHezuoPrice;use app\common\model\Product as ProductModel;class Product extends Admin{public function index(){unset($Kh9tIMC);$Kh9tIMC=0;$map['p.is_del']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=1;$map['p.added']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=array('in','1,3');$map['p.show_style']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('product_type')->where(array('status'=>1))->order('sort asc,id asc')->value('id');$type=$Kh9tIMC;if(I('type'))goto Kh9eWjgx2;goto Kh9ldMhx2;Kh9eWjgx2:unset($Kh9tIMC);$Kh9tIMC=I('type');$type=$Kh9tIMC;goto Kh9x1;Kh9ldMhx2:Kh9x1:unset($Kh9tIMC);$Kh9tIMC=$type;$map['p.type']=$Kh9tIMC;if(I('key'))goto Kh9eWjgx4;goto Kh9ldMhx4;Kh9eWjgx4:$Kh9vPMC='%' . I('key');$Kh9vPMD=$Kh9vPMC . '%';unset($Kh9tIME);$Kh9tIME=array('like',$Kh9vPMD);$map['p.name|p.desc']=$Kh9tIME;goto Kh9x3;Kh9ldMhx4:Kh9x3:if(I('id'))goto Kh9eWjgx6;goto Kh9ldMhx6;Kh9eWjgx6:unset($Kh9tIMC);$Kh9tIMC=I('id');$map['p.id']=$Kh9tIMC;goto Kh9x5;Kh9ldMhx6:Kh9x5:if(I('cate_id'))goto Kh9eWjgx8;goto Kh9ldMhx8;Kh9eWjgx8:unset($Kh9tIMC);$Kh9tIMC=I('cate_id');$map['p.cate_id']=$Kh9tIMC;goto Kh9x7;Kh9ldMhx8:Kh9x7:unset($Kh9tIMC);$Kh9tIMC=ProductModel::getProducts($map,$this->user['id']);$resdata=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$resdata['data'];$lists=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('customer_grade')->where(array('id'=>$this->user['grade_id']))->find();$grade=$Kh9tIMC;$Kh9MD=(bool)$lists;if($Kh9MD)goto Kh9eWjgxb;goto Kh9ldMhxb;Kh9eWjgxb:$Kh9MC=$grade['is_zdy_price']==1;$Kh9MD=$lists&&$Kh9MC;goto Kh9xa;Kh9ldMhxb:Kh9xa:if($Kh9MD)goto Kh9eWjgxc;goto Kh9ldMhxc;Kh9eWjgxc:foreach($lists as&$cate){foreach($cate['products']as&$item){unset($Kh9tIMC);$Kh9tIMC=M('customer_hezuo_price')->where(array('customer_id'=>$this->user['id'],'product_id'=>$item['id']))->field('id as rangesid,ranges,ys_tag,show_style,name')->find();$hzprice=$Kh9tIMC;$Kh9MC=!$hzprice;if($Kh9MC)goto Kh9eWjgxe;goto Kh9ldMhxe;Kh9eWjgxe:unset($Kh9tIMC);$Kh9tIMC='';$item['ys_tag']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=0;$item['rangesid']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=0;$item['ranges']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC='';$item['zdyname']=$Kh9tIMC;goto Kh9xd;Kh9ldMhxe:unset($Kh9tIMC);$Kh9tIMC=$hzprice['ys_tag'];$item['ys_tag']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$hzprice['rangesid'];$item['rangesid']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$hzprice['ranges'];$item['ranges']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$hzprice['show_style'];$item['show_style']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$hzprice['name'];$item['zdyname']=$Kh9tIMC;Kh9xd:}}goto Kh9x9;Kh9ldMhxc:Kh9x9:$this->assign('_list',$lists);$this->assign('is_zdy_price',$grade['is_zdy_price']);$this->assign('_types',M('product_type')->where(array('status'=>1))->order('sort asc,id asc')->select());$this->assign('_cates',M('product_cate')->where(array('type'=>I('type')))->order('sort asc,id asc')->select());$this->assign('typeid',$type);return view();}public function hz_price_edit(){if(request()->isPost())goto Kh9eWjgxg;goto Kh9ldMhxg;Kh9eWjgxg:unset($Kh9tIMC);$Kh9tIMC=I('id');$pr_id=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=I('product_id');$product_id=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=0;$map['p.is_del']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$product_id;$map['p.id']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('customer')->where(array('id'=>$this->user['id']))->find();$customer=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=ProductModel::getProduct($map,$customer['id']);$resdata=$Kh9tIMC;$Kh9MC=$resdata['errno']!=0;if($Kh9MC)goto Kh9eWjgxi;goto Kh9ldMhxi;Kh9eWjgxi:return $this->error($resdata['errmsg']);goto Kh9xh;Kh9ldMhxi:Kh9xh:unset($Kh9tIMC);$Kh9tIMC=$resdata['data'];$product=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=floatval(I('ranges'));$ranges=$Kh9tIMC;$Kh9MC=$ranges<0;if($Kh9MC)goto Kh9eWjgxk;goto Kh9ldMhxk;Kh9eWjgxk:return $this->error('浮动金额不能小于0');goto Kh9xj;Kh9ldMhxk:Kh9xj:$Kh9MC=floatval($product['max_price'])>0;$Kh9MF=(bool)$Kh9MC;if($Kh9MF)goto Kh9eWjgxn;goto Kh9ldMhxn;Kh9eWjgxn:$Kh9MD=$product['price']+$ranges;$Kh9ME=$Kh9MD>$product['max_price'];$Kh9MF=$Kh9MC&&$Kh9ME;goto Kh9xm;Kh9ldMhxn:Kh9xm:if($Kh9MF)goto Kh9eWjgxo;goto Kh9ldMhxo;Kh9eWjgxo:return $this->error('不能设置高于封顶价格');goto Kh9xl;Kh9ldMhxo:Kh9xl:unset($Kh9tIMC);$Kh9tIMC=CustomerHezuoPrice::saveValues($pr_id,$this->user['id'],$product_id,array('ranges'=>$ranges));$res=$Kh9tIMC;$Kh9MC=$res['errno']==0;if($Kh9MC)goto Kh9eWjgxq;goto Kh9ldMhxq;Kh9eWjgxq:return $this->success('保存成功');goto Kh9xp;Kh9ldMhxq:return $this->error('编辑失败');Kh9xp:goto Kh9xf;Kh9ldMhxg:unset($Kh9tIMC);$Kh9tIMC=M('customer_hezuo_price')->where(array('id'=>I('id')))->find();$info=$Kh9tIMC;$this->assign('info',$info);return view();Kh9xf:}public function set_prices(){if(request()->isPost())goto Kh9eWjgxs;goto Kh9ldMhxs;Kh9eWjgxs:$Kh9MC=I('prompt_remark')!='';if($Kh9MC)goto Kh9eWjgxu;goto Kh9ldMhxu;Kh9eWjgxu:unset($Kh9tIMC);$Kh9tIMC=I('product_id/a');$product_id=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=0;$map['p.is_del']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=0;$counts=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=floatval(I('prompt_remark'));$range=$Kh9tIMC;foreach($product_id as $k=>$v){unset($Kh9tIMC);$Kh9tIMC=explode("|",$v);$ress=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$ress[0];$map['p.id']=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$ress[0];$product_ids=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=$ress[1];$pr_id=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=M('customer')->where(array('id'=>$this->user['id']))->find();$customer=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=ProductModel::getProduct($map,$customer['id']);$resdata=$Kh9tIMC;$Kh9MC=$resdata['errno']==0;if($Kh9MC)goto Kh9eWjgxw;goto Kh9ldMhxw;Kh9eWjgxw:unset($Kh9tIMC);$Kh9tIMC=$resdata['data'];$product=$Kh9tIMC;$Kh9MC=floatval(preg_replace('/\\D/','',$product['name']))/100;$Kh9MD=$Kh9MC*$range;unset($Kh9tIME);$Kh9tIME=$Kh9MD;$ranges=$Kh9tIME;$Kh9MC=$ranges<0;if($Kh9MC)goto Kh9eWjgxy;goto Kh9ldMhxy;Kh9eWjgxy:return ;goto Kh9xx;Kh9ldMhxy:Kh9xx:$Kh9MC=floatval($product['max_price'])>0;$Kh9MF=(bool)$Kh9MC;if($Kh9MF)goto Kh9eWjgx12;goto Kh9ldMhx12;Kh9eWjgx12:$Kh9MD=$product['price']+$ranges;$Kh9ME=$Kh9MD>$product['max_price'];$Kh9MF=$Kh9MC&&$Kh9ME;goto Kh9x11;Kh9ldMhx12:Kh9x11:if($Kh9MF)goto Kh9eWjgx13;goto Kh9ldMhx13;Kh9eWjgx13:return ;goto Kh9xz;Kh9ldMhx13:Kh9xz:unset($Kh9tIMC);$Kh9tIMC=CustomerHezuoPrice::saveValues($pr_id,$this->user['id'],$product_ids,array('ranges'=>$ranges));$res=$Kh9tIMC;$Kh9MC=$res['errno']==0;if($Kh9MC)goto Kh9eWjgx15;goto Kh9ldMhx15;Kh9eWjgx15:$Kh9oB217=$counts;$Kh9oB218=$counts+1;$counts=$Kh9oB218;goto Kh9x14;Kh9ldMhx15:Kh9x14:goto Kh9xv;Kh9ldMhxw:Kh9xv:}$Kh9MC=$counts==0;if($Kh9MC)goto Kh9eWjgx17;goto Kh9ldMhx17;Kh9eWjgx17:return $this->error('操作失败');goto Kh9x16;Kh9ldMhx17:Kh9x16:$Kh9vPMC="成功设置" . $counts;$Kh9vPMD=$Kh9vPMC . "条";return $this->success($Kh9vPMD);goto Kh9xt;Kh9ldMhxu:return $this->error('操作有误');Kh9xt:goto Kh9xr;Kh9ldMhxs:Kh9xr:}public function hz_price_ystag_edit(){unset($Kh9tIMC);$Kh9tIMC=CustomerHezuoPrice::saveValues(I('id'),$this->user['id'],I('product_id'),array('ys_tag'=>I('ys_tag')));$res=$Kh9tIMC;$Kh9MC=$res['errno']==0;if($Kh9MC)goto Kh9eWjgx19;goto Kh9ldMhx19;Kh9eWjgx19:return $this->success('保存成功');goto Kh9x18;Kh9ldMhx19:return $this->error('编辑失败');Kh9x18:}public function hz_price_zdyname_edit(){unset($Kh9tIMC);$Kh9tIMC=CustomerHezuoPrice::saveValues(I('id'),$this->user['id'],I('product_id'),array('name'=>I('name')));$res=$Kh9tIMC;$Kh9MC=$res['errno']==0;if($Kh9MC)goto Kh9eWjgx1b;goto Kh9ldMhx1b;Kh9eWjgx1b:return $this->success('保存成功');goto Kh9x1a;Kh9ldMhx1b:return $this->error('编辑失败');Kh9x1a:}public function type(){ProductModel::initAgentProductType($this->user['id']);unset($Kh9tIMC);$Kh9tIMC=M('product_type p')->join('agent_product_type ap','ap.product_type_id=p.id')->where(array('p.status'=>1,'ap.customer_id'=>$this->user['id']))->order('p.sort asc,p.id asc')->field('ap.*,p.type_name,p.sort')->select();$list=$Kh9tIMC;$this->assign('_list',$list);return view();}public function type_edit(){if(request()->isPost())goto Kh9eWjgx1d;goto Kh9ldMhx1d;Kh9eWjgx1d:unset($Kh9tIMC);$Kh9tIMC=I('post.');$arr=$Kh9tIMC;if(isset($_POST['tishidoc']))goto Kh9eWjgx1f;goto Kh9ldMhx1f;Kh9eWjgx1f:$Kh9MC=$_POST['tishidoc'];goto Kh9x1e;Kh9ldMhx1f:$Kh9MC='';Kh9x1e:unset($Kh9tIMD);$Kh9tIMD=$Kh9MC;$arr['tishidoc']=$Kh9tIMD;unset($arr['id']);if(I('id'))goto Kh9eWjgx1h;goto Kh9ldMhx1h;Kh9eWjgx1h:unset($Kh9tIMC);$Kh9tIMC=M('agent_product_type')->where(array('id'=>I('id'),'customer_id'=>$this->user['id']))->setField($arr);$data=$Kh9tIMC;if($data)goto Kh9eWjgx1j;goto Kh9ldMhx1j;Kh9eWjgx1j:return $this->success('更新成功');goto Kh9x1i;Kh9ldMhx1j:return $this->error('更新失败');Kh9x1i:goto Kh9x1g;Kh9ldMhx1h:Kh9x1g:goto Kh9x1c;Kh9ldMhx1d:unset($Kh9tIMC);$Kh9tIMC=M('agent_product_type')->where(array('id'=>I('id'),'customer_id'=>$this->user['id']))->field("*,(select type_name from dyr_product_type where id=product_type_id) as type_name")->find();$info=$Kh9tIMC;$this->assign("info",$info);Kh9x1c:return view();}public function edit(){unset($Kh9tIMC);$Kh9tIMC=I('id');$id=$Kh9tIMC;unset($Kh9tIMC);$Kh9tIMC=I('product_id');$product_id=$Kh9tIMC;if(request()->isPost())goto Kh9eWjgx1l;goto Kh9ldMhx1l;Kh9eWjgx1l:unset($Kh9tIMC);$Kh9tIMC=CustomerHezuoPrice::saveValues($id,$this->user['id'],I('product_id'),array('show_style'=>I('show_style')));$res=$Kh9tIMC;$Kh9MC=$res['errno']==0;if($Kh9MC)goto Kh9eWjgx1n;goto Kh9ldMhx1n;Kh9eWjgx1n:return $this->success('保存成功');goto Kh9x1m;Kh9ldMhx1n:return $this->error('编辑失败');Kh9x1m:goto Kh9x1k;Kh9ldMhx1l:$this->assign("product",M('product')->where(array('id'=>$product_id))->find());unset($Kh9tIMC);$Kh9tIMC=M('customer_hezuo_price')->where(array('id'=>$id,'customer_id'=>$this->user['id']))->find();$info=$Kh9tIMC;$this->assign("info",$info);Kh9x1k:return view();}public function cate(){ProductModel::initAgentProductCate($this->user['id']);unset($Kh9tIMC);$Kh9tIMC=M('product_cate p')->join('agent_product_cate ap','ap.cate_id=p.id')->where(array('ap.customer_id'=>$this->user['id']))->order('p.sort asc,p.id asc')->field('ap.*,p.cate as ycate,p.sort,(select type_name from dyr_product_type where id=p.type) as type_name')->select();$list=$Kh9tIMC;$this->assign('_list',$list);return view();}public function cate_edit(){if(request()->isPost())goto Kh9eWjgx1p;goto Kh9ldMhx1p;Kh9eWjgx1p:unset($Kh9tIMC);$Kh9tIMC=I('post.');$arr=$Kh9tIMC;unset($arr['id']);if(I('id'))goto Kh9eWjgx1r;goto Kh9ldMhx1r;Kh9eWjgx1r:unset($Kh9tIMC);$Kh9tIMC=M('agent_product_cate')->where(array('id'=>I('id'),'customer_id'=>$this->user['id']))->setField($arr);$data=$Kh9tIMC;if($data)goto Kh9eWjgx1t;goto Kh9ldMhx1t;Kh9eWjgx1t:return $this->success('更新成功');goto Kh9x1s;Kh9ldMhx1t:return $this->error('更新失败');Kh9x1s:goto Kh9x1q;Kh9ldMhx1r:Kh9x1q:goto Kh9x1o;Kh9ldMhx1p:unset($Kh9tIMC);$Kh9tIMC=M('agent_product_cate')->where(array('id'=>I('id'),'customer_id'=>$this->user['id']))->field("*,(select cate from dyr_product_cate where id=cate_id) as ycate")->find();$info=$Kh9tIMC;$this->assign("info",$info);Kh9x1o:return view();}}
?>