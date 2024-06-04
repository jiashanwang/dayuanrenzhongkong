<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\common\model;

use think\Model;
use Util\Ispzw;

class Product extends Model
{
    public static function getProducts($map, $user_id, $province = '', $city = '')
    {
        M('customer_hezuo_price')->where(['show_style' => 0])->setField(['show_style' => 1]);
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $map['p.is_del'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(['id' => $user_id, 'is_del' => 0])->field("*,grade_id as sgrade_id,(select is_zdy_price from dyr_customer_grade where id=grade_id) as is_zdy_price")->find();
        $user = $Kh9tIMC;
        $Kh9MC = !$user;
        if ($Kh9MC) goto Kh9eWjgx2;
        goto Kh9ldMhx2;
        Kh9eWjgx2:
        return rjson(1, '查询产品时，用户信息无效', []);
        goto Kh9x1;
        Kh9ldMhx2:Kh9x1:
        unset($Kh9tIMC);
        $Kh9tIMC = M('product p')->where($map)->group('p.cate_id')->field('p.cate_id')->select();
        $cateids = $Kh9tIMC;
        $Kh9MC = !$cateids;
        if ($Kh9MC) goto Kh9eWjgx4;
        goto Kh9ldMhx4;
        Kh9eWjgx4:
        return rjson(1, '查询产品时，没有查询条件中的分类！', []);
        goto Kh9x3;
        Kh9ldMhx4:Kh9x3:
        unset($Kh9tIMC);
        $Kh9tIMC = ['status' => 1];
        $typesmap = $Kh9tIMC;
        $Kh9MD = (bool)$user['f_id'];
        if ($Kh9MD) goto Kh9eWjgx9;
        goto Kh9ldMhx9;
        Kh9eWjgx9:
        $Kh9MC = $user['type'] == 1;
        $Kh9MD = $user['f_id'] && $Kh9MC;
        goto Kh9x8;
        Kh9ldMhx9:Kh9x8:
        $Kh9MF = (bool)$Kh9MD;
        if ($Kh9MF) goto Kh9eWjgx7;
        goto Kh9ldMhx7;
        Kh9eWjgx7:
        unset($Kh9tIME);
        $Kh9tIME = M('customer')->where(['id' => $user['f_id'], 'is_del' => 0, 'is_h5fx' => 1])->field('id,grade_id')->find();
        unset($Kh9tIMG);
        $Kh9tIMG = $Kh9tIME;
        $fuser = $Kh9tIMG;
        $Kh9MF = $Kh9MD && $Kh9tIME;
        goto Kh9x6;
        Kh9ldMhx7:Kh9x6:
        if ($Kh9MF) goto Kh9eWjgxa;
        goto Kh9ldMhxa;
        Kh9eWjgxa:
        unset($Kh9tIMC);
        $Kh9tIMC = $fuser['grade_id'];
        $user['sgrade_id'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('agent_product_type')->where(['customer_id' => $user['f_id'], 'status' => 0])->field('product_type_id')->select();
        $agtype = $Kh9tIMC;
        if ($Kh9tIMC) goto Kh9eWjgxc;
        goto Kh9ldMhxc;
        Kh9eWjgxc:
        unset($Kh9tIMC);
        $Kh9tIMC = ['not in', array_column($agtype, 'product_type_id')];
        $typesmap['id'] = $Kh9tIMC;
        goto Kh9xb;
        Kh9ldMhxc:Kh9xb:
        $Kh9MD = (bool)isset($map['p.show_style']);
        if ($Kh9MD) goto Kh9eWjgxf;
        goto Kh9ldMhxf;
        Kh9eWjgxf:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer_hezuo_price')->where(['customer_id' => $user['f_id'], 'show_style' => ['not in', $map['p.show_style'][1]]])->field('product_id')->select();
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $showtss = $Kh9tIME;
        $Kh9MD = isset($map['p.show_style']) && $Kh9tIMC;
        goto Kh9xe;
        Kh9ldMhxf:Kh9xe:
        if ($Kh9MD) goto Kh9eWjgxg;
        goto Kh9ldMhxg;
        Kh9eWjgxg:
        unset($Kh9tIMC);
        $Kh9tIMC = ['not in', array_column($showtss, 'product_id')];
        $map['p.id'] = $Kh9tIMC;
        goto Kh9xd;
        Kh9ldMhxg:Kh9xd:
        goto Kh9x5;
        Kh9ldMhxa:Kh9x5:
        unset($Kh9tIMC);
        $Kh9tIMC = M('product_type')->where($typesmap)->field('id')->select();
        $types = $Kh9tIMC;
        $Kh9MC = !$types;
        if ($Kh9MC) goto Kh9eWjgxi;
        goto Kh9ldMhxi;
        Kh9eWjgxi:
        return rjson(1, '查询产品时，没有查询条件中的产品类型！', []);
        goto Kh9xh;
        Kh9ldMhxi:Kh9xh:
        unset($Kh9tIMC);
        $Kh9tIMC = M('product_cate')->where(['type' => ['in', array_column($types, 'id')]])->where(['id' => ['in', array_column($cateids, 'cate_id')]])->order('type asc,sort asc')->select();
        $cates = $Kh9tIMC;
        $Kh9MC = !$cates;
        if ($Kh9MC) goto Kh9eWjgxk;
        goto Kh9ldMhxk;
        Kh9eWjgxk:
        return rjson(1, '查询产品时，没有查询条件中的分类，可能是H5分销关闭的！', []);
        goto Kh9xj;
        Kh9ldMhxk:Kh9xj:
        unset($Kh9tIMC);
        $Kh9tIMC = self::agentNCate($cates, $user_id);
        $cateres = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $cateres['data'];
        $cates = $Kh9tIMC;
        foreach ($cates as $ckey => &$cate) {
            unset($Kh9tIMC);
            $Kh9tIMC = $cate['id'];
            $map['p.cate_id'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = M('product p')->where($map)->order("p.type,p.sort asc")->field("p.id,p.name,p.name as yname,p.desc,p.api_open,p.isp,p.ys_tag,p.price,p.show_style,p.cate_id,p.delay_api,p.grade_ids,0 as y_price,p.max_price,p.type,p.allow_pro,p.allow_city,p.forbid_pro,p.forbid_city,p.jmapi_id,p.jmapi_param_id,p.is_jiema,(select cate from dyr_product_cate where id=p.cate_id) as cate_name,(select type_name from dyr_product_type where id=p.type) as type_name,(select typec_id from dyr_product_type where id=p.type) as typec_id")->select();
            $cate['products'] = $Kh9tIMC;
            foreach ($cate['products'] as $pkey => &$product) {
                $Kh9MC = (bool)$province;
                if ($Kh9MC) goto Kh9eWjgxp;
                goto Kh9ldMhxp;
                Kh9eWjgxp:
                $Kh9MC = $province && $product['allow_pro'];
                goto Kh9xo;
                Kh9ldMhxp:Kh9xo:
                $Kh9ME = (bool)$Kh9MC;
                if ($Kh9ME) goto Kh9eWjgxn;
                goto Kh9ldMhxn;
                Kh9eWjgxn:
                $Kh9MD = !strstr($product['allow_pro'], $province);
                $Kh9ME = $Kh9MC && $Kh9MD;
                goto Kh9xm;
                Kh9ldMhxn:Kh9xm:
                if ($Kh9ME) goto Kh9eWjgxq;
                goto Kh9ldMhxq;
                Kh9eWjgxq:
                unset($cate['products'][$pkey]);
                continue 1;
                goto Kh9xl;
                Kh9ldMhxq:Kh9xl:
                $Kh9MC = (bool)$city;
                if ($Kh9MC) goto Kh9eWjgxv;
                goto Kh9ldMhxv;
                Kh9eWjgxv:
                $Kh9MC = $city && $product['allow_city'];
                goto Kh9xu;
                Kh9ldMhxv:Kh9xu:
                $Kh9ME = (bool)$Kh9MC;
                if ($Kh9ME) goto Kh9eWjgxt;
                goto Kh9ldMhxt;
                Kh9eWjgxt:
                $Kh9MD = !strstr($product['allow_city'], $city);
                $Kh9ME = $Kh9MC && $Kh9MD;
                goto Kh9xs;
                Kh9ldMhxt:Kh9xs:
                if ($Kh9ME) goto Kh9eWjgxw;
                goto Kh9ldMhxw;
                Kh9eWjgxw:
                unset($cate['products'][$pkey]);
                continue 1;
                goto Kh9xr;
                Kh9ldMhxw:Kh9xr:
                $Kh9MC = (bool)$province;
                if ($Kh9MC) goto Kh9eWjgx12;
                goto Kh9ldMhx12;
                Kh9eWjgx12:
                $Kh9MC = $province && $product['forbid_pro'];
                goto Kh9x11;
                Kh9ldMhx12:Kh9x11:
                $Kh9MD = (bool)$Kh9MC;
                if ($Kh9MD) goto Kh9eWjgxz;
                goto Kh9ldMhxz;
                Kh9eWjgxz:
                $Kh9MD = $Kh9MC && strstr($product['forbid_pro'], $province);
                goto Kh9xy;
                Kh9ldMhxz:Kh9xy:
                if ($Kh9MD) goto Kh9eWjgx13;
                goto Kh9ldMhx13;
                Kh9eWjgx13:
                unset($cate['products'][$pkey]);
                continue 1;
                goto Kh9xx;
                Kh9ldMhx13:Kh9xx:
                $Kh9MC = (bool)$city;
                if ($Kh9MC) goto Kh9eWjgx18;
                goto Kh9ldMhx18;
                Kh9eWjgx18:
                $Kh9MC = $city && $product['forbid_city'];
                goto Kh9x17;
                Kh9ldMhx18:Kh9x17:
                $Kh9MD = (bool)$Kh9MC;
                if ($Kh9MD) goto Kh9eWjgx16;
                goto Kh9ldMhx16;
                Kh9eWjgx16:
                $Kh9MD = $Kh9MC && strstr($product['forbid_city'], $city);
                goto Kh9x15;
                Kh9ldMhx16:Kh9x15:
                if ($Kh9MD) goto Kh9eWjgx19;
                goto Kh9ldMhx19;
                Kh9eWjgx19:
                unset($cate['products'][$pkey]);
                continue 1;
                goto Kh9x14;
                Kh9ldMhx19:Kh9x14:
                $Kh9MD = (bool)$product['grade_ids'];
                if ($Kh9MD) goto Kh9eWjgx1c;
                goto Kh9ldMhx1c;
                Kh9eWjgx1c:
                $Kh9MC = !inArrayDou($product['grade_ids'], $user['sgrade_id']);
                $Kh9MD = $product['grade_ids'] && $Kh9MC;
                goto Kh9x1b;
                Kh9ldMhx1c:Kh9x1b:
                if ($Kh9MD) goto Kh9eWjgx1d;
                goto Kh9ldMhx1d;
                Kh9eWjgx1d:
                unset($cate['products'][$pkey]);
                continue 1;
                goto Kh9x1a;
                Kh9ldMhx1d:Kh9x1a:
                unset($Kh9tIMC);
                $Kh9tIMC = self::computePrice($product['id'], $user_id);
                $fdres = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $fdres['data']['price'];
                $fd_price = $Kh9tIMC;
                $Kh9MC = (bool)isset($fdres['data']['name']);
                if ($Kh9MC) goto Kh9eWjgx1g;
                goto Kh9ldMhx1g;
                Kh9eWjgx1g:
                $Kh9MC = isset($fdres['data']['name']) && $fdres['data']['name'];
                goto Kh9x1f;
                Kh9ldMhx1g:Kh9x1f:
                if ($Kh9MC) goto Kh9eWjgx1h;
                goto Kh9ldMhx1h;
                Kh9eWjgx1h:
                unset($Kh9tIMC);
                $Kh9tIMC = $fdres['data']['name'];
                $product['name'] = $Kh9tIMC;
                goto Kh9x1e;
                Kh9ldMhx1h:Kh9x1e:
                $Kh9MC = (bool)isset($fdres['data']['ys_tag']);
                if ($Kh9MC) goto Kh9eWjgx1k;
                goto Kh9ldMhx1k;
                Kh9eWjgx1k:
                $Kh9MC = isset($fdres['data']['ys_tag']) && $fdres['data']['ys_tag'];
                goto Kh9x1j;
                Kh9ldMhx1k:Kh9x1j:
                if ($Kh9MC) goto Kh9eWjgx1l;
                goto Kh9ldMhx1l;
                Kh9eWjgx1l:
                unset($Kh9tIMC);
                $Kh9tIMC = $fdres['data']['ys_tag'];
                $product['ys_tag'] = $Kh9tIMC;
                goto Kh9x1i;
                Kh9ldMhx1l:Kh9x1i:
                $Kh9vPMC = $product['price'] + $fd_price;
                unset($Kh9tIMD);
                $Kh9tIMD = sprintf("%.2f", $Kh9vPMC);
                $product['price'] = $Kh9tIMD;
                unset($Kh9tIMC);
                $Kh9tIMC = $product['ys_tag'];
                $product['ys_tags'] = $Kh9tIMC;
                $Kh9MC = $product['is_jiema'] == 1;
                $Kh9ME = (bool)$Kh9MC;
                if ($Kh9ME) goto Kh9eWjgx1q;
                goto Kh9ldMhx1q;
                Kh9eWjgx1q:
                $Kh9MD = $product['jmapi_id'] > 0;
                $Kh9ME = $Kh9MC && $Kh9MD;
                goto Kh9x1p;
                Kh9ldMhx1q:Kh9x1p:
                $Kh9MG = (bool)$Kh9ME;
                if ($Kh9MG) goto Kh9eWjgx1o;
                goto Kh9ldMhx1o;
                Kh9eWjgx1o:
                $Kh9MF = $product['jmapi_param_id'] > 0;
                $Kh9MG = $Kh9ME && $Kh9MF;
                goto Kh9x1n;
                Kh9ldMhx1o:Kh9x1n:
                if ($Kh9MG) goto Kh9eWjgx1r;
                goto Kh9ldMhx1r;
                Kh9eWjgx1r:
                unset($Kh9tIMC);
                $Kh9tIMC = M('jmapi_param jmp')->join('jmapi jm', 'jm.id=jmp.jmapi_id')->where(['jmp.id' => $product['jmapi_param_id'], 'jm.is_del' => 0])->field('jmp.jmnum,jmp.desc,jm.name')->find();
                $jiema = $Kh9tIMC;
                if ($jiema) goto Kh9eWjgx1t;
                goto Kh9ldMhx1t;
                Kh9eWjgx1t:
                unset($Kh9tIMC);
                $Kh9tIMC = $jiema;
                $product['jiema'] = $Kh9tIMC;
                goto Kh9x1s;
                Kh9ldMhx1t:
                unset($Kh9tIMC);
                $Kh9tIMC = 0;
                $product['is_jiema'] = $Kh9tIMC;
                Kh9x1s:
                goto Kh9x1m;
                Kh9ldMhx1r:Kh9x1m:
            }
            $Kh9MC = count($cate['products']) == 0;
            if ($Kh9MC) goto Kh9eWjgx1v;
            goto Kh9ldMhx1v;
            Kh9eWjgx1v:
            unset($cates[$ckey]);
            goto Kh9x1u;
            Kh9ldMhx1v:
            unset($Kh9tIMC);
            $Kh9tIMC = array_values($cate['products']);
            $cate['products'] = $Kh9tIMC;
            Kh9x1u:
        }
        return rjson(0, '查询成功！', array_values($cates));
    }

    public static function getProductp($map, $user_id, $province = '', $city = '')
    {
        M('customer_hezuo_price')->where(['show_style' => 0])->setField(['show_style' => 1]);
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $map['p.is_del'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(['id' => $user_id, 'is_del' => 0])->field("*,grade_id as sgrade_id,(select is_zdy_price from dyr_customer_grade where id=grade_id) as is_zdy_price")->find();
        $user = $Kh9tIMC;
        $Kh9MC = !$user;
        if ($Kh9MC) goto Kh9eWjgx1x;
        goto Kh9ldMhx1x;
        Kh9eWjgx1x:
        return rjson(1, '查询产品时，用户信息无效', []);
        goto Kh9x1w;
        Kh9ldMhx1x:Kh9x1w:
        unset($Kh9tIMC);
        $Kh9tIMC = M('product p')->where($map)->field('p.id')->select();
        $cateids = $Kh9tIMC;
        $Kh9MC = !$cateids;
        if ($Kh9MC) goto Kh9eWjgx2z;
        goto Kh9ldMhx2z;
        Kh9eWjgx2z:
        return rjson(1, '查询产品时，没有查询条件中的产品ID！', []);
        goto Kh9x1y;
        Kh9ldMhx2z:Kh9x1y:
        unset($Kh9tIMC);
        $Kh9tIMC = ['status' => 1];
        $typesmap = $Kh9tIMC;
        $Kh9MD = (bool)$user['f_id'];
        if ($Kh9MD) goto Kh9eWjgx25;
        goto Kh9ldMhx25;
        Kh9eWjgx25:
        $Kh9MC = $user['type'] == 1;
        $Kh9MD = $user['f_id'] && $Kh9MC;
        goto Kh9x24;
        Kh9ldMhx25:Kh9x24:
        $Kh9MF = (bool)$Kh9MD;
        if ($Kh9MF) goto Kh9eWjgx23;
        goto Kh9ldMhx23;
        Kh9eWjgx23:
        unset($Kh9tIME);
        $Kh9tIME = M('customer')->where(['id' => $user['f_id'], 'is_del' => 0, 'is_h5fx' => 1])->field('id,grade_id')->find();
        unset($Kh9tIMG);
        $Kh9tIMG = $Kh9tIME;
        $fuser = $Kh9tIMG;
        $Kh9MF = $Kh9MD && $Kh9tIME;
        goto Kh9x22;
        Kh9ldMhx23:Kh9x22:
        if ($Kh9MF) goto Kh9eWjgx26;
        goto Kh9ldMhx26;
        Kh9eWjgx26:
        unset($Kh9tIMC);
        $Kh9tIMC = $fuser['grade_id'];
        $user['sgrade_id'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('agent_product_type')->where(['customer_id' => $user['f_id'], 'status' => 0])->field('product_type_id')->select();
        $agtype = $Kh9tIMC;
        if ($Kh9tIMC) goto Kh9eWjgx28;
        goto Kh9ldMhx28;
        Kh9eWjgx28:
        unset($Kh9tIMC);
        $Kh9tIMC = ['not in', array_column($agtype, 'product_type_id')];
        $typesmap['id'] = $Kh9tIMC;
        goto Kh9x27;
        Kh9ldMhx28:Kh9x27:
        $Kh9MD = (bool)isset($map['p.show_style']);
        if ($Kh9MD) goto Kh9eWjgx2b;
        goto Kh9ldMhx2b;
        Kh9eWjgx2b:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer_hezuo_price')->where(['customer_id' => $user['f_id'], 'show_style' => ['not in', $map['p.show_style'][1]]])->field('product_id')->select();
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $showtss = $Kh9tIME;
        $Kh9MD = isset($map['p.show_style']) && $Kh9tIMC;
        goto Kh9x2a;
        Kh9ldMhx2b:Kh9x2a:
        if ($Kh9MD) goto Kh9eWjgx2c;
        goto Kh9ldMhx2c;
        Kh9eWjgx2c:
        unset($Kh9tIMC);
        $Kh9tIMC = ['not in', array_column($showtss, 'product_id')];
        $map['p.id'] = $Kh9tIMC;
        goto Kh9x29;
        Kh9ldMhx2c:Kh9x29:
        goto Kh9x21;
        Kh9ldMhx26:Kh9x21:
        unset($Kh9tIMC);
        $Kh9tIMC = M('product p')->where(array('id' => $map['p.id']))->field("p.id,p.name,p.api_open,p.isp,p.ys_tag,p.price,p.show_style,p.cate_id,p.delay_api,p.grade_ids,0 as y_price,p.max_price,p.type,p.allow_pro,p.allow_city,p.forbid_pro,p.forbid_city,p.added")->select();
        $cate['products'] = $Kh9tIMC;
        foreach ($cate['products'] as $pkey => &$product) {
            unset($Kh9tIMC);
            $Kh9tIMC = self::computePrice($product['id'], $user_id);
            $fdres = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $fdres['data']['price'];
            $fd_price = $Kh9tIMC;
            $Kh9MC = (bool)isset($fdres['data']['name']);
            if ($Kh9MC) goto Kh9eWjgx2f;
            goto Kh9ldMhx2f;
            Kh9eWjgx2f:
            $Kh9MC = isset($fdres['data']['name']) && $fdres['data']['name'];
            goto Kh9x2e;
            Kh9ldMhx2f:Kh9x2e:
            if ($Kh9MC) goto Kh9eWjgx2g;
            goto Kh9ldMhx2g;
            Kh9eWjgx2g:
            unset($Kh9tIMC);
            $Kh9tIMC = $fdres['data']['name'];
            $product['name'] = $Kh9tIMC;
            goto Kh9x2d;
            Kh9ldMhx2g:Kh9x2d:
            $Kh9MC = (bool)isset($fdres['data']['ys_tag']);
            if ($Kh9MC) goto Kh9eWjgx2j;
            goto Kh9ldMhx2j;
            Kh9eWjgx2j:
            $Kh9MC = isset($fdres['data']['ys_tag']) && $fdres['data']['ys_tag'];
            goto Kh9x2i;
            Kh9ldMhx2j:Kh9x2i:
            if ($Kh9MC) goto Kh9eWjgx2k;
            goto Kh9ldMhx2k;
            Kh9eWjgx2k:
            unset($Kh9tIMC);
            $Kh9tIMC = $fdres['data']['ys_tag'];
            $product['ys_tag'] = $Kh9tIMC;
            goto Kh9x2h;
            Kh9ldMhx2k:Kh9x2h:
            $Kh9vPMC = $product['price'] + $fd_price;
            unset($Kh9tIMD);
            $Kh9tIMD = sprintf("%.2f", $Kh9vPMC);
            $product['price'] = $Kh9tIMD;
        }
        return rjson(0, '查询成功！', $cate['products']);
    }

    public static function getProduct($map, $user_id, $province = '', $city = '', $mobile = '')
    {
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $map['p.is_del'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(['id' => $user_id, 'is_del' => 0])->field("*,grade_id as sgrade_id,(select is_zdy_price from dyr_customer_grade where id=grade_id) as is_zdy_price")->find();
        $user = $Kh9tIMC;
        $Kh9MC = !$user;
        if ($Kh9MC) goto Kh9eWjgx2m;
        goto Kh9ldMhx2m;
        Kh9eWjgx2m:
        return rjson(1, '查询产品时，用户信息无效', false);
        goto Kh9x2l;
        Kh9ldMhx2m:Kh9x2l:
        unset($Kh9tIMC);
        $Kh9tIMC = M('product p')->where($map)->order("p.sort asc")->field("p.id,p.name,p.name as yname,p.desc,p.api_open,p.isp,p.ys_tag,p.price,p.show_style,p.cate_id,p.delay_api,p.grade_ids,0 as y_price,p.max_price,p.type,p.allow_pro,p.allow_city,p.allow_isp,p.forbid_pro,p.forbid_city,p.forbid_isp,p.jmapi_id,p.jmapi_param_id,p.is_jiema,p.limit_m_month_num,p.limit_m_num,p.limit_m_day,plimit_one_porder,p.start_time,p.end_time,(select cate from dyr_product_cate where id=p.cate_id) as cate_name,(select type_name from dyr_product_type where id=p.type) as type_name,(select typec_id from dyr_product_type where id=p.type) as typec_id")->find();
        $info = $Kh9tIMC;
        $Kh9MC = !$info;
        if ($Kh9MC) goto Kh9eWjgx2o;
        goto Kh9ldMhx2o;
        Kh9eWjgx2o:
        return rjson(1, '未找到符合该充值的产品，请查看代理端产品列表是否存在该产品ID！', false);
        goto Kh9x2n;
        Kh9ldMhx2o:Kh9x2n:
        $Kh9MD = (bool)$user['f_id'];
        if ($Kh9MD) goto Kh9eWjgx2t;
        goto Kh9ldMhx2t;
        Kh9eWjgx2t:
        $Kh9MC = $user['type'] == 1;
        $Kh9MD = $user['f_id'] && $Kh9MC;
        goto Kh9x2s;
        Kh9ldMhx2t:Kh9x2s:
        $Kh9MF = (bool)$Kh9MD;
        if ($Kh9MF) goto Kh9eWjgx2r;
        goto Kh9ldMhx2r;
        Kh9eWjgx2r:
        unset($Kh9tIME);
        $Kh9tIME = M('customer')->where(['id' => $user['f_id'], 'is_del' => 0, 'is_h5fx' => 1])->field('id,grade_id')->find();
        unset($Kh9tIMG);
        $Kh9tIMG = $Kh9tIME;
        $fuser = $Kh9tIMG;
        $Kh9MF = $Kh9MD && $Kh9tIME;
        goto Kh9x2q;
        Kh9ldMhx2r:Kh9x2q:
        if ($Kh9MF) goto Kh9eWjgx2u;
        goto Kh9ldMhx2u;
        Kh9eWjgx2u:
        unset($Kh9tIMC);
        $Kh9tIMC = $fuser['grade_id'];
        $user['sgrade_id'] = $Kh9tIMC;
        goto Kh9x2p;
        Kh9ldMhx2u:Kh9x2p:
        $Kh9MC = (bool)$province;
        if ($Kh9MC) goto Kh9eWjgx3z;
        goto Kh9ldMhx3z;
        Kh9eWjgx3z:
        $Kh9MC = $province && $info['allow_pro'];
        goto Kh9x2y;
        Kh9ldMhx3z:Kh9x2y:
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx2x;
        goto Kh9ldMhx2x;
        Kh9eWjgx2x:
        $Kh9MD = !strstr($info['allow_pro'], $province);
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x2w;
        Kh9ldMhx2x:Kh9x2w:
        if ($Kh9ME) goto Kh9eWjgx31;
        goto Kh9ldMhx31;
        Kh9eWjgx31:
        return rjson(1, '省份不在可充范围！', false);
        goto Kh9x2v;
        Kh9ldMhx31:Kh9x2v:
        $Kh9MC = (bool)$city;
        if ($Kh9MC) goto Kh9eWjgx36;
        goto Kh9ldMhx36;
        Kh9eWjgx36:
        $Kh9MC = $city && $info['allow_city'];
        goto Kh9x35;
        Kh9ldMhx36:Kh9x35:
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx34;
        goto Kh9ldMhx34;
        Kh9eWjgx34:
        $Kh9MD = !strstr($info['allow_city'], $city);
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x33;
        Kh9ldMhx34:Kh9x33:
        if ($Kh9ME) goto Kh9eWjgx37;
        goto Kh9ldMhx37;
        Kh9eWjgx37:
        return rjson(1, '城市不在可充范围！', false);
        goto Kh9x32;
        Kh9ldMhx37:Kh9x32:
        $Kh9MC = (bool)substr($mobile, 0, 3);
        if ($Kh9MC) goto Kh9eWjgx3c;
        goto Kh9ldMhx3c;
        Kh9eWjgx3c:
        $Kh9MC = substr($mobile, 0, 3) && $info['allow_isp'];
        goto Kh9x3b;
        Kh9ldMhx3c:Kh9x3b:
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx3a;
        goto Kh9ldMhx3a;
        Kh9eWjgx3a:
        $Kh9MD = !strstr($info['allow_isp'], substr($mobile, 0, 3));
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x39;
        Kh9ldMhx3a:Kh9x39:
        if ($Kh9ME) goto Kh9eWjgx3d;
        goto Kh9ldMhx3d;
        Kh9eWjgx3d:
        return rjson(1, '号段不在可充范围！', false);
        goto Kh9x38;
        Kh9ldMhx3d:Kh9x38:
        $Kh9MC = (bool)$province;
        if ($Kh9MC) goto Kh9eWjgx3i;
        goto Kh9ldMhx3i;
        Kh9eWjgx3i:
        $Kh9MC = $province && $info['forbid_pro'];
        goto Kh9x3h;
        Kh9ldMhx3i:Kh9x3h:
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx3g;
        goto Kh9ldMhx3g;
        Kh9eWjgx3g:
        $Kh9MD = $Kh9MC && strstr($info['forbid_pro'], $province);
        goto Kh9x3f;
        Kh9ldMhx3g:Kh9x3f:
        if ($Kh9MD) goto Kh9eWjgx3j;
        goto Kh9ldMhx3j;
        Kh9eWjgx3j:
        return rjson(1, '省份在限制范围！', false);
        goto Kh9x3e;
        Kh9ldMhx3j:Kh9x3e:
        $Kh9MC = (bool)$city;
        if ($Kh9MC) goto Kh9eWjgx3o;
        goto Kh9ldMhx3o;
        Kh9eWjgx3o:
        $Kh9MC = $city && $info['forbid_city'];
        goto Kh9x3n;
        Kh9ldMhx3o:Kh9x3n:
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx3m;
        goto Kh9ldMhx3m;
        Kh9eWjgx3m:
        $Kh9MD = $Kh9MC && strstr($info['forbid_city'], $city);
        goto Kh9x3l;
        Kh9ldMhx3m:Kh9x3l:
        if ($Kh9MD) goto Kh9eWjgx3p;
        goto Kh9ldMhx3p;
        Kh9eWjgx3p:
        return rjson(1, '城市在限制范围！', false);
        goto Kh9x3k;
        Kh9ldMhx3p:Kh9x3k:
        $Kh9MC = (bool)substr($mobile, 0, 3);
        if ($Kh9MC) goto Kh9eWjgx3u;
        goto Kh9ldMhx3u;
        Kh9eWjgx3u:
        $Kh9MC = substr($mobile, 0, 3) && $info['forbid_isp'];
        goto Kh9x3t;
        Kh9ldMhx3u:Kh9x3t:
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx3s;
        goto Kh9ldMhx3s;
        Kh9eWjgx3s:
        $Kh9MD = $Kh9MC && strstr($info['forbid_isp'], substr($mobile, 0, 3));
        goto Kh9x3r;
        Kh9ldMhx3s:Kh9x3r:
        if ($Kh9MD) goto Kh9eWjgx3v;
        goto Kh9ldMhx3v;
        Kh9eWjgx3v:
        return rjson(1, '号段在限制范围！', false);
        goto Kh9x3q;
        Kh9ldMhx3v:Kh9x3q:
        $Kh9MD = (bool)$info['grade_ids'];
        if ($Kh9MD) goto Kh9eWjgx3y;
        goto Kh9ldMhx3y;
        Kh9eWjgx3y:
        $Kh9MC = !inArrayDou($info['grade_ids'], $user['sgrade_id']);
        $Kh9MD = $info['grade_ids'] && $Kh9MC;
        goto Kh9x3x;
        Kh9ldMhx3y:Kh9x3x:
        if ($Kh9MD) goto Kh9eWjgx4z;
        goto Kh9ldMhx4z;
        Kh9eWjgx4z:
        return rjson(1, '产品被限制用户等级使用！', false);
        goto Kh9x3w;
        Kh9ldMhx4z:Kh9x3w:
        unset($Kh9tIMC);
        $Kh9tIMC = self::computePrice($info['id'], $user_id);
        $fdres = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $fdres['data']['price'];
        $fd_price = $Kh9tIMC;
        $Kh9MC = (bool)isset($fdres['data']['name']);
        if ($Kh9MC) goto Kh9eWjgx43;
        goto Kh9ldMhx43;
        Kh9eWjgx43:
        $Kh9MC = isset($fdres['data']['name']) && $fdres['data']['name'];
        goto Kh9x42;
        Kh9ldMhx43:Kh9x42:
        if ($Kh9MC) goto Kh9eWjgx44;
        goto Kh9ldMhx44;
        Kh9eWjgx44:
        unset($Kh9tIMC);
        $Kh9tIMC = $fdres['data']['name'];
        $info['name'] = $Kh9tIMC;
        goto Kh9x41;
        Kh9ldMhx44:Kh9x41:
        $Kh9vPMC = $info['price'] + $fd_price;
        unset($Kh9tIMD);
        $Kh9tIMD = sprintf("%.2f", $Kh9vPMC);
        $info['price'] = $Kh9tIMD;
        unset($Kh9tIMC);
        $Kh9tIMC = M('product_api')->where(['product_id' => $info['id'], 'status' => 1])->order('sort')->select();
        $apiarr = $Kh9tIMC;
        $Kh9MD = (bool)$info['api_open'];
        if ($Kh9MD) goto Kh9eWjgx48;
        goto Kh9ldMhx48;
        Kh9eWjgx48:
        $Kh9MC = count($apiarr) > 0;
        $Kh9MD = $info['api_open'] && $Kh9MC;
        goto Kh9x47;
        Kh9ldMhx48:Kh9x47:
        if ($Kh9MD) goto Kh9eWjgx46;
        goto Kh9ldMhx46;
        Kh9eWjgx46:
        $Kh9ME = 1;
        goto Kh9x45;
        Kh9ldMhx46:
        $Kh9ME = 0;
        Kh9x45:
        unset($Kh9tIMF);
        $Kh9tIMF = $Kh9ME;
        $info['api_open'] = $Kh9tIMF;
        unset($Kh9tIMC);
        $Kh9tIMC = json_encode($apiarr);
        $info['api_arr'] = $Kh9tIMC;
        $Kh9MC = $info['is_jiema'] == 1;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx4d;
        goto Kh9ldMhx4d;
        Kh9eWjgx4d:
        $Kh9MD = $info['jmapi_id'] > 0;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x4c;
        Kh9ldMhx4d:Kh9x4c:
        $Kh9MG = (bool)$Kh9ME;
        if ($Kh9MG) goto Kh9eWjgx4b;
        goto Kh9ldMhx4b;
        Kh9eWjgx4b:
        $Kh9MF = $info['jmapi_param_id'] > 0;
        $Kh9MG = $Kh9ME && $Kh9MF;
        goto Kh9x4a;
        Kh9ldMhx4b:Kh9x4a:
        if ($Kh9MG) goto Kh9eWjgx4e;
        goto Kh9ldMhx4e;
        Kh9eWjgx4e:
        unset($Kh9tIMC);
        $Kh9tIMC = M('jmapi_param jmp')->join('jmapi jm', 'jm.id=jmp.jmapi_id')->where(['jmp.id' => $info['jmapi_param_id'], 'jm.is_del' => 0])->field('jmp.jmnum,jmp.desc,jm.name')->find();
        $jiema = $Kh9tIMC;
        if ($jiema) goto Kh9eWjgx4g;
        goto Kh9ldMhx4g;
        Kh9eWjgx4g:
        unset($Kh9tIMC);
        $Kh9tIMC = $jiema;
        $info['jiema'] = $Kh9tIMC;
        goto Kh9x4f;
        Kh9ldMhx4g:
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $info['is_jiema'] = $Kh9tIMC;
        Kh9x4f:
        goto Kh9x49;
        Kh9ldMhx4e:Kh9x49:
        return rjson(0, '查询成功', $info);
    }

    public static function computePrice($product_id, $user_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('Customer')->where(['id' => $user_id])->field("id,f_id,grade_id")->find();
        $user = $Kh9tIMC;
        $Kh9MC = !$user;
        if ($Kh9MC) goto Kh9eWjgx4i;
        goto Kh9ldMhx4i;
        Kh9eWjgx4i:
        return rjson(500, 'ok', ['price' => 0]);
        goto Kh9x4h;
        Kh9ldMhx4i:Kh9x4h:
        $Kh9MD = (bool)$user['f_id'];
        if ($Kh9MD) goto Kh9eWjgx4l;
        goto Kh9ldMhx4l;
        Kh9eWjgx4l:
        unset($Kh9tIMC);
        $Kh9tIMC = M('Customer')->where(['id' => $user['f_id']])->field("id,f_id,grade_id,(select is_zdy_price from dyr_customer_grade where id=grade_id) as is_zdy_price")->find();
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $fuser = $Kh9tIME;
        $Kh9MD = $user['f_id'] && $Kh9tIMC;
        goto Kh9x4k;
        Kh9ldMhx4l:Kh9x4k:
        if ($Kh9MD) goto Kh9eWjgx4m;
        goto Kh9ldMhx4m;
        Kh9eWjgx4m:
        $Kh9MD = (bool)$fuser;
        if ($Kh9MD) goto Kh9eWjgx4r;
        goto Kh9ldMhx4r;
        Kh9eWjgx4r:
        $Kh9MC = $user['grade_id'] < $fuser['grade_id'];
        $Kh9MD = $fuser && $Kh9MC;
        goto Kh9x4q;
        Kh9ldMhx4r:Kh9x4q:
        $Kh9ME = (bool)$Kh9MD;
        if ($Kh9ME) goto Kh9eWjgx4p;
        goto Kh9ldMhx4p;
        Kh9eWjgx4p:
        $Kh9ME = $Kh9MD && $fuser['is_zdy_price'];
        goto Kh9x4o;
        Kh9ldMhx4p:Kh9x4o:
        if ($Kh9ME) goto Kh9eWjgx4s;
        goto Kh9ldMhx4s;
        Kh9eWjgx4s:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer_hezuo_price')->where(['customer_id' => $user['f_id'], 'product_id' => $product_id])->find();
        $hezuo = $Kh9tIMC;
        $Kh9MC = (bool)$hezuo;
        if ($Kh9MC) goto Kh9eWjgx4x;
        goto Kh9ldMhx4x;
        Kh9eWjgx4x:
        $Kh9MC = $hezuo && $hezuo['ranges'];
        goto Kh9x4w;
        Kh9ldMhx4x:Kh9x4w:
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx4v;
        goto Kh9ldMhx4v;
        Kh9eWjgx4v:
        $Kh9MD = floatval($hezuo['ranges']) > 0;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x4u;
        Kh9ldMhx4v:Kh9x4u:
        if ($Kh9ME) goto Kh9eWjgx4y;
        goto Kh9ldMhx4y;
        Kh9eWjgx4y:
        unset($Kh9tIMC);
        $Kh9tIMC = $hezuo['ranges'];
        $ranges = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = self::computePrice($product_id, $user['f_id']);
        $resfd = $Kh9tIMC;
        $Kh9vPMC = $resfd['errno'] == 0;
        if ($Kh9vPMC) goto Kh9eWjgx51;
        goto Kh9ldMhx51;
        Kh9eWjgx51:
        $Kh9vPMD = $resfd['data']['price'];
        goto Kh9x5z;
        Kh9ldMhx51:
        $Kh9vPMD = 0;
        Kh9x5z:
        $Kh9vPME = $ranges + $Kh9vPMD;
        unset($Kh9tIMF);
        $Kh9tIMF = floatval($Kh9vPME);
        $hezuo['price'] = $Kh9tIMF;
        return rjson(0, 'ok', $hezuo);
        goto Kh9x4t;
        Kh9ldMhx4y:
        $Kh9MC = !$hezuo;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx53;
        goto Kh9ldMhx53;
        Kh9eWjgx53:
        unset($Kh9tIMD);
        $Kh9tIMD = [];
        unset($Kh9tIMF);
        $Kh9tIMF = $Kh9tIMD;
        $hezuo = $Kh9tIMF;
        $Kh9ME = $Kh9MC && $Kh9tIMD;
        goto Kh9x52;
        Kh9ldMhx53:Kh9x52:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer_grade_price')->where(['grade_id' => $user['grade_id'], 'product_id' => $product_id])->value('ranges');
        $ranges = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = floatval($ranges);
        $hezuo['price'] = $Kh9tIMC;
        return rjson(0, 'ok', $hezuo);
        Kh9x4t:
        goto Kh9x4n;
        Kh9ldMhx4s:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer_grade_price')->where(['grade_id' => $user['grade_id'], 'product_id' => $product_id])->value('ranges');
        $ranges = $Kh9tIMC;
        return rjson(0, 'ok', ['price' => floatval($ranges)]);
        Kh9x4n:
        goto Kh9x4j;
        Kh9ldMhx4m:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer_grade_price')->where(['grade_id' => $user['grade_id'], 'product_id' => $product_id])->value('ranges');
        $ranges = $Kh9tIMC;
        return rjson(0, 'ok', ['price' => floatval($ranges)]);
        Kh9x4j:
    }

    public static function agentNCate($cates, $user_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('Customer')->where(['id' => $user_id])->field("id,f_id,grade_id")->find();
        $user = $Kh9tIMC;
        $Kh9MC = !$user;
        if ($Kh9MC) goto Kh9eWjgx55;
        goto Kh9ldMhx55;
        Kh9eWjgx55:
        return rjson(0, 'ok', $cates);
        goto Kh9x54;
        Kh9ldMhx55:Kh9x54:
        $Kh9MD = (bool)$user['f_id'];
        if ($Kh9MD) goto Kh9eWjgx58;
        goto Kh9ldMhx58;
        Kh9eWjgx58:
        unset($Kh9tIMC);
        $Kh9tIMC = M('Customer')->where(['id' => $user['f_id']])->field("id,f_id,grade_id,is_h5fx")->find();
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $fuser = $Kh9tIME;
        $Kh9MD = $user['f_id'] && $Kh9tIMC;
        goto Kh9x57;
        Kh9ldMhx58:Kh9x57:
        if ($Kh9MD) goto Kh9eWjgx59;
        goto Kh9ldMhx59;
        Kh9eWjgx59:
        $Kh9MD = (bool)$fuser;
        if ($Kh9MD) goto Kh9eWjgx5e;
        goto Kh9ldMhx5e;
        Kh9eWjgx5e:
        $Kh9MC = $user['grade_id'] < $fuser['grade_id'];
        $Kh9MD = $fuser && $Kh9MC;
        goto Kh9x5d;
        Kh9ldMhx5e:Kh9x5d:
        $Kh9ME = (bool)$Kh9MD;
        if ($Kh9ME) goto Kh9eWjgx5c;
        goto Kh9ldMhx5c;
        Kh9eWjgx5c:
        $Kh9ME = $Kh9MD && $fuser['is_h5fx'];
        goto Kh9x5b;
        Kh9ldMhx5c:Kh9x5b:
        if ($Kh9ME) goto Kh9eWjgx5f;
        goto Kh9ldMhx5f;
        Kh9eWjgx5f:
        foreach ($cates as $ckey => &$cate) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('agent_product_cate')->where(['customer_id' => $fuser['id'], 'cate_id' => $cate['id']])->find();
            $acate = $Kh9tIMC;
            $Kh9MC = !$acate;
            if ($Kh9MC) goto Kh9eWjgx5h;
            goto Kh9ldMhx5h;
            Kh9eWjgx5h:
            continue 1;
            goto Kh9x5g;
            Kh9ldMhx5h:Kh9x5g:
            if ($acate['cate']) goto Kh9eWjgx5j;
            goto Kh9ldMhx5j;
            Kh9eWjgx5j:
            unset($Kh9tIMC);
            $Kh9tIMC = $acate['cate'];
            $cate['cate'] = $Kh9tIMC;
            goto Kh9x5i;
            Kh9ldMhx5j:Kh9x5i:
            $Kh9MC = $acate['status'] == 0;
            if ($Kh9MC) goto Kh9eWjgx5l;
            goto Kh9ldMhx5l;
            Kh9eWjgx5l:
            unset($cates[$ckey]);
            goto Kh9x5k;
            Kh9ldMhx5l:Kh9x5k:
        }
        goto Kh9x5a;
        Kh9ldMhx5f:Kh9x5a:
        goto Kh9x56;
        Kh9ldMhx59:Kh9x56:
        return rjson(0, 'ok', array_values($cates));
    }

    public static function initAgentProductType($customer_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('product_type')->select();
        $types = $Kh9tIMC;
        foreach ($types as $type) {
            $Kh9MC = !M('agent_product_type')->where(['customer_id' => $customer_id, 'product_type_id' => $type['id']])->find();
            if ($Kh9MC) goto Kh9eWjgx5n;
            goto Kh9ldMhx5n;
            Kh9eWjgx5n:
            M('agent_product_type')->insertGetId(['customer_id' => $customer_id, 'product_type_id' => $type['id'], 'tishidoc' => $type['tishidoc'], 'status' => $type['status']]);
            goto Kh9x5m;
            Kh9ldMhx5n:Kh9x5m:
        }
        return rjson(0, '初始化完成');
    }

    public static function initAgentProductCate($customer_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('product_cate')->select();
        $cates = $Kh9tIMC;
        foreach ($cates as $cate) {
            $Kh9MC = !M('agent_product_cate')->where(['customer_id' => $customer_id, 'cate_id' => $cate['id']])->find();
            if ($Kh9MC) goto Kh9eWjgx5p;
            goto Kh9ldMhx5p;
            Kh9eWjgx5p:
            M('agent_product_cate')->insertGetId(['customer_id' => $customer_id, 'cate' => '', 'cate_id' => $cate['id'], 'status' => 1]);
            goto Kh9x5o;
            Kh9ldMhx5p:Kh9x5o:
        }
        return rjson(0, '初始化完成');
    }

    public static function Ispzhan($mobile, $customer_id, $guishu)
    {
        $Kh9MC = C('ISP_ZHUANW_SW') != 2;
        if ($Kh9MC) goto Kh9eWjgx5r;
        goto Kh9ldMhx5r;
        Kh9eWjgx5r:
        return $guishu;
        goto Kh9x5q;
        Kh9ldMhx5r:Kh9x5q:
        unset($Kh9tIMC);
        $Kh9tIMC = Customer::canQueryIspz($customer_id);
        $resc = $Kh9tIMC;
        $Kh9MC = $resc['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgx5t;
        goto Kh9ldMhx5t;
        Kh9eWjgx5t:
        unset($Kh9tIMC);
        $Kh9tIMC = $resc['errmsg'];
        $guishu['data']['ipsz_msg'] = $Kh9tIMC;
        return $guishu;
        goto Kh9x5s;
        Kh9ldMhx5t:Kh9x5s:
        unset($Kh9tIMC);
        $Kh9tIMC = Ispzw::isZhuanw(C('ISP_ZHUANW_CFG.apikey'), $mobile);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] == 0;
        if ($Kh9MC) goto Kh9eWjgx5v;
        goto Kh9ldMhx5v;
        Kh9eWjgx5v:
        unset($Kh9tIMC);
        $Kh9tIMC = $res['data']['result']['Now_isp'];
        $guishu['data']['ispstr'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = ispstrtoint($res['data']['result']['Now_isp']);
        $guishu['data']['isp'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = explode("-", $res['data']['result']['Area']);
        $arr = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $arr[0];
        $guishu['data']['prov'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $arr[1];
        $guishu['data']['city'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $res['errmsg'];
        $guishu['data']['ipsz_msg'] = $Kh9tIMC;
        return $guishu;
        goto Kh9x5u;
        Kh9ldMhx5v:
        unset($Kh9tIMC);
        $Kh9tIMC = $res['errmsg'];
        $guishu['data']['ipsz_msg'] = $Kh9tIMC;
        return $guishu;
        Kh9x5u:
    }

    public static function getTypec($typec_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('product_typec')->where(['id' => $typec_id])->find();
        $info = $Kh9tIMC;
        $Kh9MC = !$info;
        if ($Kh9MC) goto Kh9eWjgx5x;
        goto Kh9ldMhx5x;
        Kh9eWjgx5x:
        return false;
        goto Kh9x5w;
        Kh9ldMhx5x:Kh9x5w:
        unset($Kh9tIMC);
        $Kh9tIMC = M('product_typec_ziduan')->where(['typec_id' => $typec_id])->order('sort asc,id asc')->select();
        $ziduans = $Kh9tIMC;
        foreach ($ziduans as &$zd) {
            $Kh9MC = $zd['input_type'] == 4;
            if ($Kh9MC) goto Kh9eWjgx6z;
            goto Kh9ldMhx6z;
            Kh9eWjgx6z:
            unset($Kh9tIMC);
            $Kh9tIMC = parseMaoArr($zd['select_items']);
            $zd['select_items'] = $Kh9tIMC;
            goto Kh9x5y;
            Kh9ldMhx6z:Kh9x5y:
        }
        unset($Kh9tIMC);
        $Kh9tIMC = $ziduans;
        $info['ziduan'] = $Kh9tIMC;
        return $info;
    }
}

?>