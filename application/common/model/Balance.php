<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\common\model;

use app\common\library\SubscribeMessage;
use app\common\library\Templetmsg;
use think\Model;
use app\common\library\Createlog;

class Balance extends Model
{
    const STYLE_ORDERS = 1;
    const STYLE_REWARDS = 2;
    const STYLE_WITHDRAW = 3;
    const STYLE_RECHARGE = 4;
    const STYLE_REFUND = 5;

    public static function revenue($customer_id, $money, $remark, $style, $operator)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $customer_id))->value('balance');
        $balance_pr = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $customer_id))->setInc("balance", $money);
        $uid = $Kh9tIMC;
        $Kh9MC = !$uid;
        if ($Kh9MC) goto Kh9eWjgx2;
        goto Kh9ldMhx2;
        Kh9eWjgx2:
        return rjson(1, '收入失败');
        goto Kh9x1;
        Kh9ldMhx2:Kh9x1:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $customer_id))->find();
        $user = $Kh9tIMC;
        $Kh9MC = (bool)$uid;
        if ($Kh9MC) goto Kh9eWjgx4;
        goto Kh9ldMhx4;
        Kh9eWjgx4:
        $Kh9MC = $uid && M('balance_log')->insertGetId(array('money' => $money, 'type' => 1, 'remark' => $remark, 'create_time' => time(), 'style' => $style, 'customer_id' => $customer_id, 'balance' => $user['balance'], 'balance_pr' => $balance_pr, 'operator' => $operator));
        goto Kh9x3;
        Kh9ldMhx4:Kh9x3:
        $Kh9MC = $user['client'] == Client::CLIENT_WX;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx6;
        goto Kh9ldMhx6;
        Kh9eWjgx6:
        $Kh9MD = $Kh9MC && Templetmsg::balanceCg($customer_id, '你有新的余额收入了', time_format(time()), $remark, $money, $user['balance']);
        goto Kh9x5;
        Kh9ldMhx6:Kh9x5:
        return rjson(0, '用户余额收入操作成功');
    }

    public static function expend($customer_id, $money, $remark, $style, $operator)
    {
        $Kh9MC = $money <= 0;
        if ($Kh9MC) goto Kh9eWjgx8;
        goto Kh9ldMhx8;
        Kh9eWjgx8:
        return rjson(1, '支出金额不合法！');
        goto Kh9x7;
        Kh9ldMhx8:Kh9x7:
        unset($Kh9tIMC);
        $Kh9tIMC = time();
        $create_time = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('balance_log')->where(array('money' => $money, 'type' => 2, 'remark' => $remark, 'create_time' => $create_time, 'style' => $style, 'customer_id' => $customer_id, 'operator' => $operator))->find();
        $has = $Kh9tIMC;
        if ($has) goto Kh9eWjgxa;
        goto Kh9ldMhxa;
        Kh9eWjgxa:
        return rjson(1, '扣费系统异常，请稍后再试！');
        goto Kh9x9;
        Kh9ldMhxa:Kh9x9:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $customer_id))->field("balance,shouxin_e")->find();
        $user = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $user['balance'];
        $balance_pr = $Kh9tIMC;
        $Kh9MC = $user['balance'] - $money;
        $Kh9MD = -1 * $user['shouxin_e'];
        $Kh9ME = $Kh9MC < $Kh9MD;
        if ($Kh9ME) goto Kh9eWjgxc;
        goto Kh9ldMhxc;
        Kh9eWjgxc:
        return rjson(1, '检查到授信额度不足！');
        goto Kh9xb;
        Kh9ldMhxc:Kh9xb:
        $Kh9vPvPvPvPMC = $money - $user['shouxin_e'];
        unset($Kh9tIMD);
        $Kh9tIMD = M('customer')->where(array('id' => $customer_id, 'balance' => array(array('egt', $Kh9vPvPvPvPMC), array('eq', $balance_pr), 'and')))->setDec("balance", $money);
        $uid = $Kh9tIMD;
        $Kh9MC = !$uid;
        if ($Kh9MC) goto Kh9eWjgxe;
        goto Kh9ldMhxe;
        Kh9eWjgxe:
        $i = 0;
        Kh9xf:
        $Kh9MC = $i < 5;
        if ($Kh9MC) goto Kh9eWjgxr;
        goto Kh9ldMhxr;
        Kh9eWjgxr:
        $Kh9MC = $money <= 0;
        if ($Kh9MC) goto Kh9eWjgxj;
        goto Kh9ldMhxj;
        Kh9eWjgxj:
        return rjson(1, '支出金额不合法！');
        goto Kh9xi;
        Kh9ldMhxj:Kh9xi:
        unset($Kh9tIMC);
        $Kh9tIMC = time();
        $create_time = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('balance_log')->where(array('money' => $money, 'type' => 2, 'remark' => $remark, 'create_time' => $create_time, 'style' => $style, 'customer_id' => $customer_id, 'operator' => $operator))->find();
        $has = $Kh9tIMC;
        if ($has) goto Kh9eWjgxl;
        goto Kh9ldMhxl;
        Kh9eWjgxl:
        return rjson(1, '扣费系统异常，请稍后再试！');
        goto Kh9xk;
        Kh9ldMhxl:Kh9xk:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $customer_id))->field("balance,shouxin_e")->find();
        $user = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $user['balance'];
        $balance_pr = $Kh9tIMC;
        $Kh9MC = $user['balance'] - $money;
        $Kh9MD = -1 * $user['shouxin_e'];
        $Kh9ME = $Kh9MC < $Kh9MD;
        if ($Kh9ME) goto Kh9eWjgxn;
        goto Kh9ldMhxn;
        Kh9eWjgxn:
        return rjson(1, '检查到授信额度不足！');
        goto Kh9xm;
        Kh9ldMhxn:Kh9xm:
        $Kh9vPvPvPvPMC = $money - $user['shouxin_e'];
        unset($Kh9tIMD);
        $Kh9tIMD = M('customer')->where(array('id' => $customer_id, 'balance' => array(array('egt', $Kh9vPvPvPvPMC), array('eq', $balance_pr), 'and')))->setDec("balance", $money);
        unset($Kh9tIMC);
        $Kh9tIMC = $Kh9tIMD;
        $uid = $Kh9tIMC;
        if ($uid) goto Kh9eWjgxp;
        goto Kh9ldMhxp;
        Kh9eWjgxp:
        goto Kh9xh;
        goto Kh9xo;
        Kh9ldMhxp:Kh9xo:Kh9xg:
        $Kh9oB219 = $i;
        $Kh9oB220 = $i + 1;
        $i = $Kh9oB220;
        goto Kh9xf;
        goto Kh9xq;
        Kh9ldMhxr:Kh9xq:Kh9xh:
        $Kh9MC = !$uid;
        if ($Kh9MC) goto Kh9eWjgxt;
        goto Kh9ldMhxt;
        Kh9eWjgxt:
        return rjson(1, '余额支出时发生异常');
        goto Kh9xs;
        Kh9ldMhxt:Kh9xs:
        goto Kh9xd;
        Kh9ldMhxe:Kh9xd:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $customer_id))->find();
        $user = $Kh9tIMC;
        $Kh9MC = (bool)$uid;
        if ($Kh9MC) goto Kh9eWjgxv;
        goto Kh9ldMhxv;
        Kh9eWjgxv:
        $Kh9MC = $uid && M('balance_log')->insertGetId(array('money' => $money, 'type' => 2, 'remark' => $remark, 'create_time' => $create_time, 'style' => $style, 'customer_id' => $customer_id, 'balance' => $user['balance'], 'balance_pr' => $balance_pr, 'operator' => $operator));
        goto Kh9xu;
        Kh9ldMhxv:Kh9xu:
        $Kh9MC = $user['client'] == Client::CLIENT_WX;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgxx;
        goto Kh9ldMhxx;
        Kh9eWjgxx:
        $Kh9MD = $Kh9MC && Templetmsg::balanceCg($customer_id, '你有新的余额支出了', time_format(time()), $remark, $money, $user['balance']);
        goto Kh9xw;
        Kh9ldMhxx:Kh9xw:
        return rjson(0, '用户余额支出操作成功');
    }
}

?>