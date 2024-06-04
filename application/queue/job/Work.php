<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\queue\job;

use app\common\controller\Base;
use app\common\library\Createlog;
use app\common\library\Email;
use app\common\library\PayWay;
use app\common\model\Porder;
use think\Log;
use think\queue\Job;

class Work extends Base
{
    public function failed($data)
    {
        $Kh9vPMC = '队列Work失败' . var_export($data, true);
        Log::error($Kh9vPMC);
    }

    public function fire(Job $job, $data)
    {
        $Kh9MC = $job->attempts() > 2;
        if ($Kh9MC) goto Kh9eWjgx2;
        goto Kh9ldMhx2;
        Kh9eWjgx2:
        $Kh9vPMC = 'Work超过2次了，将停止' . json_encode($data);
        Log::error($Kh9vPMC);
        $job->delete();
        return;
        goto Kh9x1;
        Kh9ldMhx2:Kh9x1:
        $Kh9vPMC = "消息已经执行了" . $job->attempts();
        $Kh9vPMD = $Kh9vPMC . '次';
        Log::error($Kh9vPMD);
        $Kh9vPMC = 'Work执行了' . json_encode($data);
        Log::error($Kh9vPMC);
        $job->release(3);
    }

    public function porderSubApi(Job $job, $porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t1 = $Kh9tIMC;
        Porder::subApi($porder_id);
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t2 = $Kh9tIMC;
        $Kh9vPvPMC = $t2 - $t1;
        $Kh9vPMD = "提交API充值耗时：" . round($Kh9vPvPMC, 3);
        $Kh9vPME = $Kh9vPMD . 's';
        Createlog::porderLog($porder_id, $Kh9vPME);
        $job->delete();
    }

    public function pordersSubApi(Job $job, $data)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = array();
        $api_arr = $Kh9tIMC;
        foreach ($data['apiparam'] as $k => $api) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('reapi_param rp')->join('reapi r', 'r.id=rp.reapi_id')->where(array('rp.id' => $api['param_id']))->field("rp.reapi_id,rp.id as param_id,1 as num")->find();
            $apione = $Kh9tIMC;
            $Kh9MC = !$apione;
            if ($Kh9MC) goto Kh9eWjgx4;
            goto Kh9ldMhx4;
            Kh9eWjgx4:
            continue 1;
            goto Kh9x3;
            Kh9ldMhx4:Kh9x3:
            unset($Kh9tIMC);
            $Kh9tIMC = $api['num'];
            $apione['num'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $apione;
            $api_arr[] = $Kh9tIMC;
        }
        foreach ($data['ids'] as $id) {
            $Kh9vPMC = '后台批量提交接口充值|管理员:' . $data['op'];
            Createlog::porderLog($id, $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = M('porder')->where(array('id' => $id))->field('id,status')->find();
            $porder = $Kh9tIMC;
            $Kh9MC = !in_array($porder['status'], array(2, 10));
            if ($Kh9MC) goto Kh9eWjgx6;
            goto Kh9ldMhx6;
            Kh9eWjgx6:
            Createlog::porderLog($id, '后台批量提交接口充值|订单不是待充值状态，已经被驳回请求');
            Porder::untlock($id);
            goto Kh9x5;
            Kh9ldMhx6:
            M('porder')->where(array('id' => $id))->setInc('api_cur_count', 1);
            M('porder')->where(array('id' => $id))->setField(array('api_arr' => json_encode($api_arr), 'status' => 2, 'api_open' => 1, 'api_cur_index' => -1, 'apply_refund' => 0, 'delay_time' => 0));
            $Kh9vPMC = '后台批量提交接口充值|数据:' . json_encode($api_arr);
            Createlog::porderLog($id, $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = microtime(true);
            $t1 = $Kh9tIMC;
            Porder::subApi($id);
            unset($Kh9tIMC);
            $Kh9tIMC = microtime(true);
            $t2 = $Kh9tIMC;
            $Kh9vPvPMC = $t2 - $t1;
            $Kh9vPMD = "提交API充值耗时：" . round($Kh9vPvPMC, 3);
            $Kh9vPME = $Kh9vPMD . 's';
            Createlog::porderLog($id, $Kh9vPME);
            Porder::untlock($id);
            Kh9x5:
        }
        $job->delete();
    }

    public function porderssSubApi(Job $job, $data)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = array();
        $api_arr = $Kh9tIMC;
        foreach ($data['apiparam'] as $k => $api) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('reapi_param rp')->join('reapi r', 'r.id=rp.reapi_id')->where(array('rp.id' => $api['param_id']))->field("rp.reapi_id,rp.id as param_id,1 as num")->find();
            $apione = $Kh9tIMC;
            $Kh9MC = !$apione;
            if ($Kh9MC) goto Kh9eWjgx8;
            goto Kh9ldMhx8;
            Kh9eWjgx8:
            continue 1;
            goto Kh9x7;
            Kh9ldMhx8:Kh9x7:
            unset($Kh9tIMC);
            $Kh9tIMC = $api['num'];
            $apione['num'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $apione;
            $api_arr[] = $Kh9tIMC;
        }
        foreach ($data['ids'] as $id) {
            $Kh9vPMC = '后台重提接口充值|管理员:' . $data['op'];
            Createlog::porderLog($id, $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = M('porder')->where(array('id' => $id))->field('id,status')->find();
            $porder = $Kh9tIMC;
            $Kh9MC = !in_array($porder['status'], array(2, 10));
            if ($Kh9MC) goto Kh9eWjgxa;
            goto Kh9ldMhxa;
            Kh9eWjgxa:
            Createlog::porderLog($id, '后台重提接口充值|订单不是待充值状态，已经被驳回请求');
            Porder::untlock($id);
            goto Kh9x9;
            Kh9ldMhxa:
            M('porder')->where(array('id' => $id))->setInc('api_cur_count', 1);
            M('porder')->where(array('id' => $id))->setField(array('api_arr' => json_encode($api_arr), 'status' => 2, 'api_open' => 1, 'api_cur_index' => -1, 'apply_refund' => 0, 'delay_time' => 0));
            $Kh9vPMC = '后台重提接口充值|数据:' . json_encode($api_arr);
            Createlog::porderLog($id, $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = microtime(true);
            $t1 = $Kh9tIMC;
            Porder::subApi($id);
            unset($Kh9tIMC);
            $Kh9tIMC = microtime(true);
            $t2 = $Kh9tIMC;
            $Kh9vPvPMC = $t2 - $t1;
            $Kh9vPMD = "提交API充值耗时：" . round($Kh9vPvPMC, 3);
            $Kh9vPME = $Kh9vPMD . 's';
            Createlog::porderLog($id, $Kh9vPME);
            Porder::untlock($id);
            Kh9x9:
        }
        $job->delete();
    }

    public function pordersSubApip(Job $job, $data)
    {
        foreach ($data['ids'] as $id) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('porder')->where(array('id' => $id))->field('api_cur_param_id')->find();
            $api = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = array();
            $api_arr = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = M('reapi_param rp')->join('reapi r', 'r.id=rp.reapi_id')->where(array('rp.id' => $api['api_cur_param_id']))->field("rp.reapi_id,rp.id as param_id,1 as num")->find();
            $apione = $Kh9tIMC;
            $Kh9MC = !$apione;
            if ($Kh9MC) goto Kh9eWjgxc;
            goto Kh9ldMhxc;
            Kh9eWjgxc:
            continue 1;
            goto Kh9xb;
            Kh9ldMhxc:Kh9xb:
            unset($Kh9tIMC);
            $Kh9tIMC = 1;
            $apione['num'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $apione;
            $api_arr[] = $Kh9tIMC;
            $Kh9vPMC = '后台批量重提接口充值|管理员:' . $data['op'];
            Createlog::porderLog($id, $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = M('porder')->where(array('id' => $id))->field('id,status')->find();
            $porder = $Kh9tIMC;
            $Kh9MC = !in_array($porder['status'], array(2, 10));
            if ($Kh9MC) goto Kh9eWjgxe;
            goto Kh9ldMhxe;
            Kh9eWjgxe:
            Createlog::porderLog($id, '后台批量重提接口充值|订单不是待充值状态，已经被驳回请求');
            Porder::untlock($id);
            goto Kh9xd;
            Kh9ldMhxe:
            M('porder')->where(array('id' => $id))->setInc('api_cur_count', 1);
            M('porder')->where(array('id' => $id))->setField(array('api_arr' => json_encode($api_arr), 'status' => 2, 'api_open' => 1, 'api_cur_index' => -1, 'apply_refund' => 0, 'delay_time' => 0));
            $Kh9vPMC = '后台批量重提接口充值|数据:' . json_encode($api_arr);
            Createlog::porderLog($id, $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = microtime(true);
            $t1 = $Kh9tIMC;
            Porder::subApi($id);
            unset($Kh9tIMC);
            $Kh9tIMC = microtime(true);
            $t2 = $Kh9tIMC;
            $Kh9vPvPMC = $t2 - $t1;
            $Kh9vPMD = "提交API充值耗时：" . round($Kh9vPvPMC, 3);
            $Kh9vPME = $Kh9vPMD . 's';
            Createlog::porderLog($id, $Kh9vPME);
            Porder::untlock($id);
            Kh9xd:
        }
        $job->delete();
    }

    public function pordersBatchApart(Job $job, $param)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = array();
        $product_arr = $Kh9tIMC;
        foreach ($param['products'] as $k => $ap) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('product p')->where(array('p.id' => $ap['product_id']))->field("p.id,p.name,p.cate_id,p.desc,p.api_open,p.type,1 as num,(select type_name from dyr_product_type where id=p.type) as type_name")->find();
            $pone = $Kh9tIMC;
            $Kh9MC = !$pone;
            if ($Kh9MC) goto Kh9eWjgxg;
            goto Kh9ldMhxg;
            Kh9eWjgxg:
            continue 1;
            goto Kh9xf;
            Kh9ldMhxg:Kh9xf:
            unset($Kh9tIMC);
            $Kh9tIMC = M('product_api')->where(array('product_id' => $pone['id'], 'status' => 1))->order('sort')->select();
            $apiarr = $Kh9tIMC;
            $Kh9MD = (bool)$pone['api_open'];
            if ($Kh9MD) goto Kh9eWjgxk;
            goto Kh9ldMhxk;
            Kh9eWjgxk:
            $Kh9MC = count($apiarr) > 0;
            $Kh9MD = $pone['api_open'] && $Kh9MC;
            goto Kh9xj;
            Kh9ldMhxk:Kh9xj:
            if ($Kh9MD) goto Kh9eWjgxi;
            goto Kh9ldMhxi;
            Kh9eWjgxi:
            $Kh9ME = 1;
            goto Kh9xh;
            Kh9ldMhxi:
            $Kh9ME = 0;
            Kh9xh:
            unset($Kh9tIMF);
            $Kh9tIMF = $Kh9ME;
            $pone['api_open'] = $Kh9tIMF;
            unset($Kh9tIMC);
            $Kh9tIMC = json_encode($apiarr);
            $pone['api_arr'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $ap['num'];
            $pone['num'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $pone;
            $product_arr[] = $Kh9tIMC;
        }
        foreach ($param['ids'] as $id) {
            $Kh9vPMC = '后台批量拆单|管理员:' . $param['op'];
            Createlog::porderLog($id, $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = M('porder')->where(array('id' => $id))->field('customer_id,order_number as apart_order_number,out_trade_num,total_price,create_time,status,remark,mobile,guishu,isp,client,pay_way,weixin_appid,param1,param2,param3,guishu_pro,guishu_city')->find();
            $porder = $Kh9tIMC;
            $Kh9MC = !$porder;
            if ($Kh9MC) goto Kh9eWjgxm;
            goto Kh9ldMhxm;
            Kh9eWjgxm:
            continue 1;
            goto Kh9xl;
            Kh9ldMhxm:Kh9xl:
            $Kh9MC = !in_array($porder['status'], array(2, 9, 10));
            if ($Kh9MC) goto Kh9eWjgxo;
            goto Kh9ldMhxo;
            Kh9eWjgxo:
            Createlog::porderLog($id, '后台批量拆单|订单不是待充值、部分充值、压单状态，已经被驳回请求');
            goto Kh9xn;
            Kh9ldMhxo:
            unset($Kh9tIMC);
            $Kh9tIMC = 0;
            $chaicount = $Kh9tIMC;
            foreach ($product_arr as $k => $product) {
                $Kh9vPMC = '后台批量拆单|开始|拆成产品:[' . $product['id'];
                $Kh9vPMD = $Kh9vPMC . ']';
                $Kh9vPME = $Kh9vPMD . $product['type_name'];
                $Kh9vPMF = $Kh9vPME . '-';
                $Kh9vPMG = $Kh9vPMF . $product['name'];
                $Kh9vPMH = $Kh9vPMG . ',数量：';
                $Kh9vPMI = $Kh9vPMH . $product['num'];
                $Kh9vPMJ = $Kh9vPMI . '单';
                Createlog::porderLog($id, $Kh9vPMJ);
                $i = 0;
                Kh9xp:
                $Kh9MC = $i < $product['num'];
                if ($Kh9MC) goto Kh9eWjgxv;
                goto Kh9ldMhxv;
                Kh9eWjgxv:
                unset($Kh9tIMC);
                $Kh9tIMC = $porder;
                $data = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $product['id'];
                $data['product_id'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = 0;
                $data['total_price'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = 1;
                $data['status'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $product['type'];
                $data['type'] = $Kh9tIMC;
                $Kh9MC = $product['name'] . $product['type_name'];
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                unset($Kh9tIMC);
                $Kh9tIMC = $Kh9tIMD;
                $data['title'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $product['name'];
                $data['product_name'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $product['desc'];
                $data['product_desc'] = $Kh9tIMC;
                $Kh9MC = '为账号' . $data['mobile'];
                $Kh9MD = $Kh9MC . '充值';
                $Kh9ME = $Kh9MD . $product['name'];
                $Kh9MF = $Kh9ME . $product['type_name'];
                unset($Kh9tIMG);
                $Kh9tIMG = $Kh9MF;
                unset($Kh9tIMC);
                $Kh9tIMC = $Kh9tIMG;
                $data['body'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $product['api_open'];
                $data['api_open'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $product['api_arr'];
                $data['api_arr'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = -1;
                $data['api_cur_index'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = 0;
                $data['api_cur_count'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = 1;
                $data['is_apart'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $porder['guishu_pro'];
                $data['guishu_pro'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $porder['guishu_city'];
                $data['guishu_city'] = $Kh9tIMC;
                $Kh9MC = new Porder();
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                unset($Kh9tIMC);
                $Kh9tIMC = $Kh9tIMD;
                $model = $Kh9tIMC;
                $model->save($data);
                unset($Kh9tIMC);
                $Kh9tIMC = $model->id;
                $aid = $Kh9tIMC;
                $Kh9MD = !$Kh9tIMC;
                if ($Kh9MD) goto Kh9eWjgxt;
                goto Kh9ldMhxt;
                Kh9eWjgxt:
                $Kh9vPMC = '后台批量拆单|失败|产品ID:' . $product['id'];
                Createlog::porderLog($id, $Kh9vPMC);
                goto Kh9xq;
                goto Kh9xs;
                Kh9ldMhxt:Kh9xs:
                unset($Kh9tIMC);
                $Kh9tIMC = M('porder')->where(array('id' => $aid))->field('id,order_number,pay_way')->find();
                $neworder = $Kh9tIMC;
                $Kh9vPMC = '后台批量拆单|成功|拆成产品:[' . $product['id'];
                $Kh9vPMD = $Kh9vPMC . ']';
                $Kh9vPME = $Kh9vPMD . $product['type_name'];
                $Kh9vPMF = $Kh9vPME . '-';
                $Kh9vPMG = $Kh9vPMF . $product['name'];
                $Kh9vPMH = $Kh9vPMG . ',新单号：';
                $Kh9vPMI = $Kh9vPMH . $neworder['order_number'];
                Createlog::porderLog($id, $Kh9vPMI);
                $Kh9vPMC = '来自订单拆单|原订单:[' . $id;
                $Kh9vPMD = $Kh9vPMC . ']';
                $Kh9vPME = $Kh9vPMD . $porder['apart_order_number'];
                Createlog::porderLog($aid, $Kh9vPME);
                Porder::notify($neworder['order_number'], $neworder['pay_way'], '');
                $Kh9oB225 = $chaicount;
                $Kh9oB226 = $chaicount + 1;
                unset($Kh9tIMC);
                $Kh9tIMC = $Kh9oB226;
                $chaicount = $Kh9tIMC;
                Kh9xq:
                $Kh9oB226 = $i;
                $Kh9oB227 = $i + 1;
                $i = $Kh9oB227;
                goto Kh9xp;
                goto Kh9xu;
                Kh9ldMhxv:Kh9xu:Kh9xr:
            }
            $Kh9MC = $chaicount > 0;
            if ($Kh9MC) goto Kh9eWjgxx;
            goto Kh9ldMhxx;
            Kh9eWjgxx:
            M('porder')->where(array('id' => $id))->setField(array('is_apart' => 2, 'status' => 11));
            $Kh9vPMC = '成功拆单|拆成' . $chaicount;
            $Kh9vPMD = $Kh9vPMC . '条';
            Createlog::porderLog($id, $Kh9vPMD);
            goto Kh9xw;
            Kh9ldMhxx:Kh9xw:Kh9xn:
        }
        $job->delete();
    }

    public function porderRefund(Job $job, $data)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t1 = $Kh9tIMC;
        Porder::refund($data['id'], $data['remark'], $data['operator']);
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t2 = $Kh9tIMC;
        $Kh9vPvPMC = $t2 - $t1;
        $Kh9vPMD = "退款耗时：" . round($Kh9vPvPMC, 3);
        $Kh9vPME = $Kh9vPMD . 's';
        Createlog::porderLog($data['id'], $Kh9vPME);
        $job->delete();
    }

    public function adminPushExcel(Job $job, $data)
    {
        $i = 0;
        Kh9xy:
        $Kh9MC = $i < count($data);
        if ($Kh9MC) goto Kh9eWjgx13;
        goto Kh9ldMhx13;
        Kh9eWjgx13:
        Porder::adminExcelOrder($data[$i]['id']);
        Kh9xz:
        $Kh9oB227 = $i;
        $Kh9oB228 = $i + 1;
        $i = $Kh9oB228;
        goto Kh9xy;
        goto Kh9x12;
        Kh9ldMhx13:Kh9x12:Kh9x11:
        $job->delete();
    }

    public function agentPushExcel(Job $job, $data)
    {
        $i = 0;
        Kh9x14:
        $Kh9MC = $i < count($data);
        if ($Kh9MC) goto Kh9eWjgx18;
        goto Kh9ldMhx18;
        Kh9eWjgx18:
        Porder::agentExcelOrder($data[$i]['id']);
        Kh9x15:
        $Kh9oB228 = $i;
        $Kh9oB229 = $i + 1;
        $i = $Kh9oB229;
        goto Kh9x14;
        goto Kh9x17;
        Kh9ldMhx18:Kh9x17:Kh9x16:
        $job->delete();
    }

    public function agentApiPayPorder(Job $job, $data)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t1 = $Kh9tIMC;
        Porder::agentApiPayPorder($data['porder_id'], $data['customer_id'], $data['notify_url']);
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t2 = $Kh9tIMC;
        $Kh9vPvPMC = $t2 - $t1;
        $Kh9vPMD = "代理API订单余额支付耗时：" . round($Kh9vPvPMC, 3);
        $Kh9vPME = $Kh9vPMD . 's';
        Createlog::porderLog($data['porder_id'], $Kh9vPME);
        $job->delete();
    }

    public function callFunc(Job $job, $data)
    {
        $job->delete();
        unset($Kh9tIMC);
        $Kh9tIMC = $data['class'];
        $classname = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $data['func'];
        $fun = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $data['param'];
        $param = $Kh9tIMC;
        call_user_func(array($classname, $fun), $param);
    }
}

?>