<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\yrapi\controller;

use app\common\library\Createlog;
use app\common\model\Client;
use app\common\model\Porder as PorderModel;
use app\common\model\Product as ProductModel;

class Index extends Home
{
    public function index()
    {
        return djson(1, '欢迎访问');
    }

    public function recharge()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('mobile'));
        $mobile = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('product_id'));
        $product_id = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('out_trade_num'));
        $out_trade_num = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('notify_url'));
        $notify_url = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('area'));
        $area = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('id_card_no'));
        $id_card_no = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('city'));
        $city = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = I('amount');
        $amount = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = I('price');
        $price = $Kh9tIMC;
        if (I('?ytype')) goto Kh9eWjgx2;
        goto Kh9ldMhx2;
        Kh9eWjgx2:
        $Kh9MC = trim(I('ytype'));
        goto Kh9x1;
        Kh9ldMhx2:
        $Kh9MC = 1;
        Kh9x1:
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $ytype = $Kh9tIMD;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('param1'));
        $param1 = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('param2'));
        $param2 = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = trim(I('param3'));
        $param3 = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t1 = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('out_trade_num' => $out_trade_num, 'status' => array('not in', array(7)), 'customer_id' => $this->customer['id']))->find();
        $reod = $Kh9tIMC;
        if ($Kh9tIMC) goto Kh9eWjgx4;
        goto Kh9ldMhx4;
        Kh9eWjgx4:
        return djson(1, '已经存在相同商户订单号的订单');
        goto Kh9x3;
        Kh9ldMhx4:Kh9x3:
        unset($Kh9tIMC);
        $Kh9tIMC = PorderModel::createOrder($mobile, $product_id, array('prov' => $area, 'city' => $city, 'ytype' => $ytype, 'id_card_no' => $id_card_no, 'param1' => $param1, 'param2' => $param2, 'param3' => $param3), $this->customer['id'], Client::CLIENT_API, '', $out_trade_num, $amount, $price);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgx6;
        goto Kh9ldMhx6;
        Kh9eWjgx6:
        return djson($res['errno'], $res['errmsg'], $res['data']);
        goto Kh9x5;
        Kh9ldMhx6:Kh9x5:
        unset($Kh9tIMC);
        $Kh9tIMC = $res['data'];
        $aid = $Kh9tIMC;
        queue('app\\queue\\job\\Work@agentApiPayPorder', array('porder_id' => $aid, 'customer_id' => $this->customer['id'], 'notify_url' => $notify_url));
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $aid))->field("id,order_number,mobile,product_id,total_price,create_time,guishu,title,out_trade_num")->find();
        $porder = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t2 = $Kh9tIMC;
        $Kh9vPvPMC = $t2 - $t1;
        $Kh9vPMD = "创建订单耗时：" . round($Kh9vPvPMC, 3);
        $Kh9vPME = $Kh9vPMD . 's';
        Createlog::porderLog($aid, $Kh9vPME);
        return djson(0, "提交成功", $porder);
    }

    public function user()
    {
        return djson(0, "ok", array('id' => $this->customer['id'], 'username' => $this->customer['username'], 'balance' => $this->customer['balance']));
    }

    public function check()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = I('out_trade_nums');
        $out_trade_nums = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('customer_id' => $this->customer['id'], 'out_trade_num' => array('in', $out_trade_nums), 'is_apart' => array('in', '0,2'), 'is_del' => 0))->field("id,order_number,status,out_trade_num,create_time,mobile,product_id,charge_amount,charge_kami,isp,product_name,finish_time,remark")->select();
        $porder = $Kh9tIMC;
        foreach ($porder as &$vo) {
            unset($Kh9tIMC);
            $Kh9tIMC = PorderModel::getState($vo['status']);
            $state = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $state;
            $vo['state'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = PorderModel::getVoucherUrl($vo['id'], $state);
            $vo['voucher'] = $Kh9tIMC;
            $Kh9MC = C('IS_SHOW_CLIENT_REMARK') != 1;
            $Kh9ME = (bool)$Kh9MC;
            if ($Kh9ME) goto Kh9eWjgx8;
            goto Kh9ldMhx8;
            Kh9eWjgx8:
            unset($Kh9tIMD);
            $Kh9tIMD = "";
            unset($Kh9tIMF);
            $Kh9tIMF = $Kh9tIMD;
            $vo['remark'] = $Kh9tIMF;
            $Kh9ME = $Kh9MC && $Kh9tIMD;
            goto Kh9x7;
            Kh9ldMhx8:Kh9x7:
        }
        return djson(0, 'ok', $porder);
    }

    public function remove()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = '';
        $signtext = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $counts = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = I('out_trade_nums');
        $out_trade_nums = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('customer_id' => $this->customer['id'], 'out_trade_num' => array('in', $out_trade_nums), 'is_apart' => array('in', '0,2'), 'is_del' => 0))->field("id,order_number,status,out_trade_num,api_order_number,create_time,mobile,product_id,charge_amount,isp,product_name,finish_time,remark")->select();
        $porder = $Kh9tIMC;
        foreach ($porder as &$vo) {
            unset($Kh9tIMC);
            $Kh9tIMC = PorderModel::applyCancelOrderapi($vo['id'], $this->customer['id']);
            $res = $Kh9tIMC;
            $Kh9MC = $res['errno'] == 1;
            if ($Kh9MC) goto Kh9eWjgxa;
            goto Kh9ldMhxa;
            Kh9eWjgxa:
            unset($Kh9tIMC);
            $Kh9tIMC = array("out_trade_num" => $vo["out_trade_num"], "mobile" => $vo["mobile"], "status" => 1);
            $conn[] = $Kh9tIMC;
            goto Kh9x9;
            Kh9ldMhxa:
            unset($Kh9tIMC);
            $Kh9tIMC = array("out_trade_num" => $vo["out_trade_num"], "mobile" => $vo["mobile"], "status" => 0);
            $conn[] = $Kh9tIMC;
            $Kh9oB229 = $counts;
            $Kh9oB230 = $counts + 1;
            $counts = $Kh9oB230;
            Kh9x9:
        }
        $Kh9MC = $counts == 0;
        if ($Kh9MC) goto Kh9eWjgxc;
        goto Kh9ldMhxc;
        Kh9eWjgxc:
        return djson(1, '失败', '未找到该订单号');
        goto Kh9xb;
        Kh9ldMhxc:
        return djson(0, $res['errmsg'], $conn);
        Kh9xb:
    }

    public function product()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $map['p.is_del'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = 1;
        $map['p.added'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = array('in', '1,3');
        $map['p.show_style'] = $Kh9tIMC;
        $Kh9MD = (bool)I('type');
        if ($Kh9MD) goto Kh9eWjgxe;
        goto Kh9ldMhxe;
        Kh9eWjgxe:
        unset($Kh9tIMC);
        $Kh9tIMC = I('type');
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $map['p.type'] = $Kh9tIME;
        $Kh9MD = I('type') && $Kh9tIMC;
        goto Kh9xd;
        Kh9ldMhxe:Kh9xd:
        $Kh9MD = (bool)I('cate_id');
        if ($Kh9MD) goto Kh9eWjgxg;
        goto Kh9ldMhxg;
        Kh9eWjgxg:
        unset($Kh9tIMC);
        $Kh9tIMC = I('cate_id');
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $map['p.cate_id'] = $Kh9tIME;
        $Kh9MD = I('cate_id') && $Kh9tIMC;
        goto Kh9xf;
        Kh9ldMhxg:Kh9xf:
        unset($Kh9tIMC);
        $Kh9tIMC = ProductModel::getProducts($map, $this->customer['id']);
        $resdata = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $resdata['data'];
        $lists = $Kh9tIMC;
        return djson(0, 'ok', $lists);
    }

    public function price()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $map['p.is_del'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = array('in', '1,3');
        $map['p.show_style'] = $Kh9tIMC;
        $Kh9MD = (bool)I('id');
        if ($Kh9MD) goto Kh9eWjgxi;
        goto Kh9ldMhxi;
        Kh9eWjgxi:
        unset($Kh9tIMC);
        $Kh9tIMC = I('id');
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $map['p.id'] = $Kh9tIME;
        $Kh9MD = I('id') && $Kh9tIMC;
        goto Kh9xh;
        Kh9ldMhxi:Kh9xh:
        unset($Kh9tIMC);
        $Kh9tIMC = ProductModel::getProductp($map, $this->customer['id']);
        $resdata = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $resdata['data'];
        $lists = $Kh9tIMC;
        return djson(0, 'ok', $lists);
    }

    public function typecate()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('product_type')->where(array('status' => 1))->order('sort asc,id asc')->field('id,type_name')->select();
        $types = $Kh9tIMC;
        foreach ($types as &$item) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('product_cate')->where(array('type' => $item['id']))->order('sort asc,id asc')->field('id,cate,type')->select();
            $item['cate'] = $Kh9tIMC;
        }
        return djson(0, 'ok', $types);
    }

    public function elecity()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('electricity_city')->where(array('is_del' => 0, 'pid' => 0))->order('initial asc,sort asc')->field('id,city_name,sort,initial,need_ytype,need_city,pid')->select();
        $lists = $Kh9tIMC;
        foreach ($lists as &$v) {
            if ($v['need_city']) goto Kh9eWjgxk;
            goto Kh9ldMhxk;
            Kh9eWjgxk:
            unset($Kh9tIMC);
            $Kh9tIMC = M('electricity_city')->where(array('pid' => $v['id'], 'is_del' => 0))->order('sort asc,city_name asc')->field('id,city_name,sort,initial,pid')->select();
            $v['city'] = $Kh9tIMC;
            goto Kh9xj;
            Kh9ldMhxk:
            unset($Kh9tIMC);
            $Kh9tIMC = array();
            $v['city'] = $Kh9tIMC;
            Kh9xj:
        }
        return djson(0, 'ok', $lists);
    }
}

?>