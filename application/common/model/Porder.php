<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\common\model;

use app\api\controller\Notify;
use app\common\library\Createlog;
use app\common\library\Notification;
use app\common\library\PayWay;
use app\common\library\Rechargeapi;
use app\common\model\Porder as PorderModel;
use think\Exception;
use think\Model;
use Util\Ispzw;
use Util\Isphone;

class Porder extends Model
{
    protected $append = ["\x73\x74\x61\x74\x75\x73\x5F\x74\x65\x78\x74", "\x73\x74\x61\x74\x75\x73\x5F\x74\x65\x78\x74\x32", "\x73\x74\x61\x74\x75\x73\x5F\x74\x65\x78\x74\x5F\x63\x6F\x6C\x6F\x72", "\x73\x74\x61\x74\x65", "\x73\x74\x61\x74\x65\x5F\x74\x65\x78\x74", "\x63\x72\x65\x61\x74\x65\x5F\x74\x69\x6D\x65\x5F\x74\x65\x78\x74"];

    public static function init()
    {
        self::event('after_insert', function ($porder) {
            $Kh9MC = Porder::prfun() . date('ymd', time());
            $Kh9MD = $Kh9MC . $porder->id;
            unset($Kh9tIME);
            $Kh9tIME = $Kh9MD;
            $order_number = $Kh9tIME;
            $porder->where(array('id' => $porder->id))->update(array('order_number' => $order_number));
        });
    }

    public static function prfun()
    {
        $Kh9vPMC = C('PORDER_PR') . 'AAA';
        return substr($Kh9vPMC, 0, 3);
    }

    public static function pr()
    {
        $Kh9vPMC = C('PORDER_PR') . 'AAA';
        return substr($Kh9vPMC, 0, 3);
    }

    public function Customer()
    {
        return $this->belongsTo('Customer', 'customer_id');
    }

    public function getStatusTextAttr($value, $data)
    {
        return C('PORDER_STATUS')[$data['status']];
    }

    public function getStatusText2Attr($value, $data)
    {
        return C('ORDER_STUTAS')[$data['status']];
    }

    public function getStatusTextColorAttr($value, $data)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = $data['status'];
        $status = $Kh9tIMC;
        if (in_array($status, array(2, 10))) goto Kh9eWjgx2;
        goto Kh9ldMhx2;
        Kh9eWjgx2:
        return "success";
        goto Kh9x1;
        Kh9ldMhx2:
        if (in_array($status, array(3, 9, 11))) goto Kh9eWjgx3;
        goto Kh9ldMhx3;
        Kh9eWjgx3:
        return 'warning';
        goto Kh9x1;
        Kh9ldMhx3:
        if (in_array($status, array(4, 12))) goto Kh9eWjgx4;
        goto Kh9ldMhx4;
        Kh9eWjgx4:
        return 'primary';
        goto Kh9x1;
        Kh9ldMhx4:
        if (in_array($status, array(5, 8))) goto Kh9eWjgx5;
        goto Kh9ldMhx5;
        Kh9eWjgx5:
        return "danger";
        goto Kh9x1;
        Kh9ldMhx5:
        return "default";
        Kh9x1:
    }

    public function getStateAttr($value, $data)
    {
        return self::getState($data['status']);
    }

    public function getStateTextAttr($value, $data)
    {
        return C('ORDER_STATE')[self::getState($data['status'])];
    }

    public function getCreateTimeTextAttr($value, $data)
    {
        return time_format($data['create_time']);
    }

    public function getRebateStatusText($is_rebate, $status)
    {
        if ($is_rebate) goto Kh9eWjgx7;
        goto Kh9ldMhx7;
        Kh9eWjgx7:
        return "已返利";
        goto Kh9x6;
        Kh9ldMhx7:
        if (in_array($status, array(5, 6))) goto Kh9eWjgx8;
        goto Kh9ldMhx8;
        Kh9eWjgx8:
        return "失败不返";
        goto Kh9x6;
        Kh9ldMhx8:
        if (in_array($status, array(4, 12, 13))) goto Kh9eWjgx9;
        goto Kh9ldMhx9;
        Kh9eWjgx9:
        return "待返利";
        goto Kh9x6;
        Kh9ldMhx9:
        if (in_array($status, array(7))) goto Kh9eWjgxa;
        goto Kh9ldMhxa;
        Kh9eWjgxa:
        return "取消不返";
        goto Kh9x6;
        Kh9ldMhxa:
        return "充值中";
        Kh9x6:
    }

    public static function getState($status)
    {
        if (in_array($status, array(4))) goto Kh9eWjgxc;
        goto Kh9ldMhxc;
        Kh9eWjgxc:
        unset($Kh9tIMC);
        $Kh9tIMC = 1;
        $state = $Kh9tIMC;
        goto Kh9xb;
        Kh9ldMhxc:
        if (in_array($status, array(1, 2, 3, 8, 9, 10, 11))) goto Kh9eWjgxd;
        goto Kh9ldMhxd;
        Kh9eWjgxd:
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $state = $Kh9tIMC;
        goto Kh9xb;
        Kh9ldMhxd:
        if (in_array($status, array(5, 6))) goto Kh9eWjgxe;
        goto Kh9ldMhxe;
        Kh9eWjgxe:
        unset($Kh9tIMC);
        $Kh9tIMC = 2;
        $state = $Kh9tIMC;
        goto Kh9xb;
        Kh9ldMhxe:
        if (in_array($status, array(12, 13))) goto Kh9eWjgxf;
        goto Kh9ldMhxf;
        Kh9eWjgxf:
        unset($Kh9tIMC);
        $Kh9tIMC = 3;
        $state = $Kh9tIMC;
        goto Kh9xb;
        Kh9ldMhxf:
        if (in_array($status, array(7))) goto Kh9eWjgxg;
        goto Kh9ldMhxg;
        Kh9eWjgxg:
        unset($Kh9tIMC);
        $Kh9tIMC = -1;
        $state = $Kh9tIMC;
        goto Kh9xb;
        Kh9ldMhxg:
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $state = $Kh9tIMC;
        Kh9xb:
        return $state;
    }

    public static function getVoucherUrl($porder_id, $status)
    {
        $Kh9MC = $status == 1;
        if ($Kh9MC) goto Kh9eWjgxi;
        goto Kh9ldMhxi;
        Kh9eWjgxi:
        $Kh9MD = C('WEB_URL') . 'yrapi.php/open/voucher/id/';
        $Kh9ME = $Kh9MD . $porder_id;
        $Kh9MF = $Kh9ME . '.html';
        $Kh9MG = $Kh9MF;
        goto Kh9xh;
        Kh9ldMhxi:
        $Kh9MG = '';
        Kh9xh:
        return $Kh9MG;
    }

    public static function getTypeName($type)
    {
        return M('product_type')->where(array('id' => $type))->value('type_name');
    }

    public static function getCurApiInfos($apiarrstr, $api_cur_index = 0, $apiopen = 1)
    {
        $Kh9MC = $apiopen != 1;
        if ($Kh9MC) goto Kh9eWjgxk;
        goto Kh9ldMhxk;
        Kh9eWjgxk:
        return "";
        goto Kh9xj;
        Kh9ldMhxk:Kh9xj:
        unset($Kh9tIMC);
        $Kh9tIMC = json_decode($apiarrstr, true);
        $apiarr = $Kh9tIMC;
        $Kh9MC = !$apiarr;
        if ($Kh9MC) goto Kh9eWjgxm;
        goto Kh9ldMhxm;
        Kh9eWjgxm:
        return "";
        goto Kh9xl;
        Kh9ldMhxm:Kh9xl:
        foreach ($apiarr as $k => $v) {
            $Kh9MC = $api_cur_index == $k;
            if ($Kh9MC) goto Kh9eWjgxo;
            goto Kh9ldMhxo;
            Kh9eWjgxo:
            $Kh9MC = getReapiName($v['reapi_id']) . '-';
            $Kh9MD = $Kh9MC . getReapiParamName($v['param_id']);
            return $Kh9MD;
            goto Kh9xn;
            Kh9ldMhxo:Kh9xn:
        }
        return "";
    }

    public static function createOrder($mobile, $product_id, $extparam, $customer_id, $client = 1, $remark = '', $out_trade_num = '', $amount = '', $price = '', $isfxh5 = 0)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = trim($mobile);
        $mobile = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('product p')->join('product_type pt', 'p.type=pt.id')->where(array('p.id' => $product_id, 'p.added' => 1))->field('p.*,pt.typec_id')->find();
        $prd = $Kh9tIMC;
        $Kh9MC = !$prd;
        if ($Kh9MC) goto Kh9eWjgxq;
        goto Kh9ldMhxq;
        Kh9eWjgxq:
        return rjson(1, '未找到相关产品(产品ID不正确或已下架)');
        goto Kh9xp;
        Kh9ldMhxq:Kh9xp:
        $Kh9MD = (bool)$out_trade_num;
        if ($Kh9MD) goto Kh9eWjgxt;
        goto Kh9ldMhxt;
        Kh9eWjgxt:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('out_trade_num' => $out_trade_num, 'status' => array('gt', 1), 'customer_id' => $customer_id))->find();
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $reod = $Kh9tIME;
        $Kh9MD = $out_trade_num && $Kh9tIMC;
        goto Kh9xs;
        Kh9ldMhxt:Kh9xs:
        if ($Kh9MD) goto Kh9eWjgxu;
        goto Kh9ldMhxu;
        Kh9eWjgxu:
        return rjson(208, '已经存在相同商户订单号的订单', $reod['order_number']);
        goto Kh9xr;
        Kh9ldMhxu:Kh9xr:
        if (M('mobile_blacklist')->where(array('mobile' => $mobile, 'limit_time' => array('gt', time())))->find()) goto Kh9eWjgxw;
        goto Kh9ldMhxw;
        Kh9eWjgxw:
        return rjson(1, '该号码无法充值-黑名单');
        goto Kh9xv;
        Kh9ldMhxw:Kh9xv:
        $Kh9MC = intval(C('LIMIT_ONE_PORDER')) > 0;
        if ($Kh9MC) goto Kh9eWjgxy;
        goto Kh9ldMhxy;
        Kh9eWjgxy:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('mobile' => $mobile, 'type' => array('in', array(1, 2)), 'status' => array('not in', array(4, 5, 6, 7, 12, 13)), 'is_del' => 0))->count();
        $shuliang = $Kh9tIMC;
        $Kh9MC = $shuliang >= intval(C('LIMIT_ONE_PORDER'));
        if ($Kh9MC) goto Kh9eWjgx11;
        goto Kh9ldMhx11;
        Kh9eWjgx11:
        $Kh9vPMC = '该号码已达到系统设置的次数上限</br>该号码系统已存在：' . $shuliang;
        $Kh9vPMD = $Kh9vPMC . '单</br>请等待充值成功后再下单';
        return rjson(1, $Kh9vPMD);
        goto Kh9xz;
        Kh9ldMhx11:Kh9xz:
        goto Kh9xx;
        Kh9ldMhxy:Kh9xx:
        $Kh9MC = intval(C('LIMITS_ONE_PORDER')) > 0;
        if ($Kh9MC) goto Kh9eWjgx13;
        goto Kh9ldMhx13;
        Kh9eWjgx13:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('mobile' => $mobile, 'type' => 3, 'status' => array('not in', array(4, 5, 6, 7, 12, 13)), 'is_del' => 0))->count();
        $shuliang = $Kh9tIMC;
        $Kh9MC = $shuliang >= intval(C('LIMITS_ONE_PORDER'));
        if ($Kh9MC) goto Kh9eWjgx15;
        goto Kh9ldMhx15;
        Kh9eWjgx15:
        $Kh9vPMC = '该号码已达到系统设置的次数上限</br>该号码系统已存在：' . $shuliang;
        $Kh9vPMD = $Kh9vPMC . '单</br>请等待充值成功后再下单';
        return rjson(1, $Kh9vPMD);
        goto Kh9x14;
        Kh9ldMhx15:Kh9x14:
        goto Kh9x12;
        Kh9ldMhx13:Kh9x12:
        $Kh9MC = C('LIMIT_ONE_CATE') == 1;
        if ($Kh9MC) goto Kh9eWjgx17;
        goto Kh9ldMhx17;
        Kh9eWjgx17:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('mobile' => $mobile, 'product_cate' => array('not in', array(0, $prd['cate_id'])), 'status' => array('not in', array(4, 5, 6, 7, 12, 13))))->count();
        $cates = $Kh9tIMC;
        $Kh9MC = $cates > 0;
        if ($Kh9MC) goto Kh9eWjgx19;
        goto Kh9ldMhx19;
        Kh9eWjgx19:
        return rjson(1, '请不要同时在多个分类下单!');
        goto Kh9x18;
        Kh9ldMhx19:Kh9x18:
        goto Kh9x16;
        Kh9ldMhx17:Kh9x16:
        unset($Kh9tIMC);
        $Kh9tIMC = '';
        $province = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = '';
        $city = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $customer_id))->find();
        $user = $Kh9tIMC;
        switch ($prd['typec_id']) {
            case 1:
            case 2:
                $Kh9MC = !is_numeric($mobile);
                $Kh9ME = (bool)$Kh9MC;
                $Kh9MF = !$Kh9ME;
                if ($Kh9MF) goto Kh9eWjgx1f;
                goto Kh9ldMhx1f;
                Kh9eWjgx1f:
                $Kh9MD = mb_strlen($mobile) != 11;
                $Kh9ME = $Kh9MC || $Kh9MD;
                goto Kh9x1e;
                Kh9ldMhx1f:Kh9x1e:
                if ($Kh9ME) goto Kh9eWjgx1g;
                goto Kh9ldMhx1g;
                Kh9eWjgx1g:
                return rjson(1, '手机号格式不正确');
                goto Kh9x1d;
                Kh9ldMhx1g:Kh9x1d:
                unset($Kh9tIMC);
                $Kh9tIMC = QCellCore($mobile);
                $guishu = $Kh9tIMC;
                $Kh9MC = $guishu['errno'] != 0;
                if ($Kh9MC) goto Kh9eWjgx1i;
                goto Kh9ldMhx1i;
                Kh9eWjgx1i:
                return rjson($guishu['errno'], $guishu['errmsg']);
                goto Kh9x1h;
                Kh9ldMhx1i:Kh9x1h:
                unset($Kh9tIMC);
                $Kh9tIMC = Product::Ispzhan($mobile, $customer_id, $guishu);
                $guishu = $Kh9tIMC;
                $Kh9vPMC = '%' . $guishu['data']['isp'];
                $Kh9vPMD = $Kh9vPMC . '%';
                unset($Kh9tIME);
                $Kh9tIME = array('like', $Kh9vPMD);
                $map['p.isp'] = $Kh9tIME;
                unset($Kh9tIMC);
                $Kh9tIMC = $guishu['data']['ispstr'];
                $data['isp'] = $Kh9tIMC;
                $Kh9MC = $guishu['data']['prov'] . $guishu['data']['city'];
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                $data['guishu'] = $Kh9tIMD;
                unset($Kh9tIMC);
                $Kh9tIMC = $guishu['data']['prov'];
                $data['guishu_pro'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $guishu['data']['city'];
                $data['guishu_city'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $guishu['data']['prov'];
                $province = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $guishu['data']['city'];
                $city = $Kh9tIMC;
                break 1;
            case 3:
                $Kh9MC = !isset($extparam['prov']);
                $Kh9ME = (bool)$Kh9MC;
                $Kh9MF = !$Kh9ME;
                if ($Kh9MF) goto Kh9eWjgx1m;
                goto Kh9ldMhx1m;
                Kh9eWjgx1m:
                $Kh9MD = !$extparam['prov'];
                $Kh9ME = $Kh9MC || $Kh9MD;
                goto Kh9x1l;
                Kh9ldMhx1m:Kh9x1l:
                if ($Kh9ME) goto Kh9eWjgx1n;
                goto Kh9ldMhx1n;
                Kh9eWjgx1n:
                return rjson(1, '请选择电费地区！');
                goto Kh9x1k;
                Kh9ldMhx1n:Kh9x1k:
                $Kh9vPvPvPMC = '%' . $extparam['prov'];
                $Kh9vPvPvPMD = $Kh9vPvPvPMC . '%';
                unset($Kh9tIME);
                $Kh9tIME = M('electricity_city')->where(array('city_name' => array('like', $Kh9vPvPvPMD), 'is_del' => 0))->find();
                $ecity = $Kh9tIME;
                $Kh9MC = !$ecity;
                if ($Kh9MC) goto Kh9eWjgx1p;
                goto Kh9ldMhx1p;
                Kh9eWjgx1p:
                return rjson(1, '不支持的电费地区！');
                goto Kh9x1o;
                Kh9ldMhx1p:Kh9x1o:
                unset($Kh9tIMC);
                $Kh9tIMC = $ecity['city_name'];
                $data['isp'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $ecity['city_name'];
                $data['guishu_pro'] = $Kh9tIMC;
                unset($Kh9tIMC);
                $Kh9tIMC = $ecity['city_name'];
                $province = $Kh9tIMC;
                if (isset($extparam['city'])) goto Kh9eWjgx1r;
                goto Kh9ldMhx1r;
                Kh9eWjgx1r:
                $Kh9MC = $extparam['city'];
                goto Kh9x1q;
                Kh9ldMhx1r:
                $Kh9MC = '';
                Kh9x1q:
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                $city = $Kh9tIMD;
                $Kh9MC = (bool)isset($extparam['id_card_no']);
                if ($Kh9MC) goto Kh9eWjgx21;
                goto Kh9ldMhx21;
                Kh9eWjgx21:
                $Kh9MC = isset($extparam['id_card_no']) && $extparam['id_card_no'];
                goto Kh9x2z;
                Kh9ldMhx21:Kh9x2z:
                $Kh9MI = (bool)$Kh9MC;
                if ($Kh9MI) goto Kh9eWjgx1y;
                goto Kh9ldMhx1y;
                Kh9eWjgx1y:
                $Kh9MD = !isset($extparam['ytype']);
                $Kh9MF = (bool)$Kh9MD;
                $Kh9MK = !$Kh9MF;
                if ($Kh9MK) goto Kh9eWjgx1w;
                goto Kh9ldMhx1w;
                Kh9eWjgx1w:
                $Kh9ME = !$extparam['ytype'];
                $Kh9MF = $Kh9MD || $Kh9ME;
                goto Kh9x1v;
                Kh9ldMhx1w:Kh9x1v:
                $Kh9MH = (bool)$Kh9MF;
                $Kh9MJ = !$Kh9MH;
                if ($Kh9MJ) goto Kh9eWjgx1u;
                goto Kh9ldMhx1u;
                Kh9eWjgx1u:
                $Kh9MG = !in_array(intval($extparam['ytype']), array(1, 2, 3));
                $Kh9MH = $Kh9MF || $Kh9MG;
                goto Kh9x1t;
                Kh9ldMhx1u:Kh9x1t:
                $Kh9MI = $Kh9MC && $Kh9MH;
                goto Kh9x1x;
                Kh9ldMhx1y:Kh9x1x:
                if ($Kh9MI) goto Kh9eWjgx22;
                goto Kh9ldMhx22;
                Kh9eWjgx22:
                return rjson(1, '电费充值三要素验证类型错误，必须是1/2/3！', $ecity);
                goto Kh9x1s;
                Kh9ldMhx22:Kh9x1s:
                $Kh9MC = (bool)isset($extparam['id_card_no']);
                if ($Kh9MC) goto Kh9eWjgx27;
                goto Kh9ldMhx27;
                Kh9eWjgx27:
                $Kh9MC = isset($extparam['id_card_no']) && $extparam['id_card_no'];
                goto Kh9x26;
                Kh9ldMhx27:Kh9x26:
                $Kh9ME = (bool)$Kh9MC;
                if ($Kh9ME) goto Kh9eWjgx25;
                goto Kh9ldMhx25;
                Kh9eWjgx25:
                $Kh9MD = mb_strlen($extparam['id_card_no']) != 6;
                $Kh9ME = $Kh9MC && $Kh9MD;
                goto Kh9x24;
                Kh9ldMhx25:Kh9x24:
                if ($Kh9ME) goto Kh9eWjgx28;
                goto Kh9ldMhx28;
                Kh9eWjgx28:
                return rjson(1, '电费充值身份证/银行卡/营业执照后六位不正确！', $ecity);
                goto Kh9x23;
                Kh9ldMhx28:Kh9x23:
                $Kh9MC = $ecity['need_ytype'] == 1;
                $Kh9ME = (bool)$Kh9MC;
                if ($Kh9ME) goto Kh9eWjgx2f;
                goto Kh9ldMhx2f;
                Kh9eWjgx2f:
                $Kh9MD = $ecity['force_ytype'] == 1;
                $Kh9ME = $Kh9MC && $Kh9MD;
                goto Kh9x2e;
                Kh9ldMhx2f:Kh9x2e:
                $Kh9MI = (bool)$Kh9ME;
                if ($Kh9MI) goto Kh9eWjgx2d;
                goto Kh9ldMhx2d;
                Kh9eWjgx2d:
                $Kh9MF = !isset($extparam['id_card_no']);
                $Kh9MH = (bool)$Kh9MF;
                $Kh9MJ = !$Kh9MH;
                if ($Kh9MJ) goto Kh9eWjgx2b;
                goto Kh9ldMhx2b;
                Kh9eWjgx2b:
                $Kh9MG = !$extparam['id_card_no'];
                $Kh9MH = $Kh9MF || $Kh9MG;
                goto Kh9x2a;
                Kh9ldMhx2b:Kh9x2a:
                $Kh9MI = $Kh9ME && $Kh9MH;
                goto Kh9x2c;
                Kh9ldMhx2d:Kh9x2c:
                if ($Kh9MI) goto Kh9eWjgx2g;
                goto Kh9ldMhx2g;
                Kh9eWjgx2g:
                return rjson(1, '电费充值身份证/银行卡/营业执照后六位必填！');
                goto Kh9x29;
                Kh9ldMhx2g:Kh9x29:
                $Kh9MC = $ecity['need_city'] == 1;
                $Kh9ME = (bool)$Kh9MC;
                if ($Kh9ME) goto Kh9eWjgx2n;
                goto Kh9ldMhx2n;
                Kh9eWjgx2n:
                $Kh9MD = $ecity['force_city'] == 1;
                $Kh9ME = $Kh9MC && $Kh9MD;
                goto Kh9x2m;
                Kh9ldMhx2n:Kh9x2m:
                $Kh9MI = (bool)$Kh9ME;
                if ($Kh9MI) goto Kh9eWjgx2l;
                goto Kh9ldMhx2l;
                Kh9eWjgx2l:
                $Kh9MF = !isset($extparam['city']);
                $Kh9MH = (bool)$Kh9MF;
                $Kh9MJ = !$Kh9MH;
                if ($Kh9MJ) goto Kh9eWjgx2j;
                goto Kh9ldMhx2j;
                Kh9eWjgx2j:
                $Kh9MG = !$extparam['city'];
                $Kh9MH = $Kh9MF || $Kh9MG;
                goto Kh9x2i;
                Kh9ldMhx2j:Kh9x2i:
                $Kh9MI = $Kh9ME && $Kh9MH;
                goto Kh9x2k;
                Kh9ldMhx2l:Kh9x2k:
                if ($Kh9MI) goto Kh9eWjgx2o;
                goto Kh9ldMhx2o;
                Kh9eWjgx2o:
                return rjson(1, '电费充值请选择地级市！');
                goto Kh9x2h;
                Kh9ldMhx2o:Kh9x2h:
                if (isset($extparam['id_card_no'])) goto Kh9eWjgx2q;
                goto Kh9ldMhx2q;
                Kh9eWjgx2q:
                $Kh9MC = $extparam['id_card_no'];
                goto Kh9x2p;
                Kh9ldMhx2q:
                $Kh9MC = '';
                Kh9x2p:
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                $data['param1'] = $Kh9tIMD;
                $Kh9ME = (bool)$data['param1'];
                if ($Kh9ME) goto Kh9eWjgx2u;
                goto Kh9ldMhx2u;
                Kh9eWjgx2u:
                if (isset($extparam['ytype'])) goto Kh9eWjgx2s;
                goto Kh9ldMhx2s;
                Kh9eWjgx2s:
                $Kh9MC = $extparam['ytype'];
                goto Kh9x2r;
                Kh9ldMhx2s:
                $Kh9MC = '';
                Kh9x2r:
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                unset($Kh9tIMF);
                $Kh9tIMF = $Kh9tIMD;
                $data['param2'] = $Kh9tIMF;
                $Kh9ME = $data['param1'] && $Kh9tIMD;
                goto Kh9x2t;
                Kh9ldMhx2u:Kh9x2t:
                if (isset($extparam['city'])) goto Kh9eWjgx2w;
                goto Kh9ldMhx2w;
                Kh9eWjgx2w:
                $Kh9MC = $extparam['city'];
                goto Kh9x2v;
                Kh9ldMhx2w:
                $Kh9MC = '';
                Kh9x2v:
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                $data['param3'] = $Kh9tIMD;
                unset($Kh9tIMC);
                $Kh9tIMC = $data['param3'];
                $data['guishu_city'] = $Kh9tIMC;
                break 1;
            default:
                if (isset($extparam['param1'])) goto Kh9eWjgx3z;
                goto Kh9ldMhx3z;
                Kh9eWjgx3z:
                $Kh9MC = $extparam['param1'];
                goto Kh9x2y;
                Kh9ldMhx3z:
                $Kh9MC = '';
                Kh9x2y:
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                $data['param1'] = $Kh9tIMD;
                if (isset($extparam['param2'])) goto Kh9eWjgx32;
                goto Kh9ldMhx32;
                Kh9eWjgx32:
                $Kh9MC = $extparam['param2'];
                goto Kh9x31;
                Kh9ldMhx32:
                $Kh9MC = '';
                Kh9x31:
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                $data['param2'] = $Kh9tIMD;
                if (isset($extparam['param3'])) goto Kh9eWjgx34;
                goto Kh9ldMhx34;
                Kh9eWjgx34:
                $Kh9MC = $extparam['param3'];
                goto Kh9x33;
                Kh9ldMhx34:
                $Kh9MC = '';
                Kh9x33:
                unset($Kh9tIMD);
                $Kh9tIMD = $Kh9MC;
                $data['param3'] = $Kh9tIMD;
                break 1;
        }
        unset($Kh9tIMC);
        $Kh9tIMC = $product_id;
        $map['p.id'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = 1;
        $map['p.added'] = $Kh9tIMC;
        $Kh9MD = (bool)in_array($client, array(Client::CLIENT_API, Client::CLIENT_AGA));
        $Kh9ME = !$Kh9MD;
        if ($Kh9ME) goto Kh9eWjgx37;
        goto Kh9ldMhx37;
        Kh9eWjgx37:
        $Kh9MC = $isfxh5 == 1;
        $Kh9MD = in_array($client, array(Client::CLIENT_API, Client::CLIENT_AGA)) || $Kh9MC;
        goto Kh9x36;
        Kh9ldMhx37:Kh9x36:
        if ($Kh9MD) goto Kh9eWjgx38;
        goto Kh9ldMhx38;
        Kh9eWjgx38:
        unset($Kh9tIMC);
        $Kh9tIMC = array('in', '1,3');
        $map['p.show_style'] = $Kh9tIMC;
        goto Kh9x35;
        Kh9ldMhx38:Kh9x35:
        $Kh9MD = (bool)in_array($client, array(Client::CLIENT_WX, Client::CLIENT_H5, Client::CLIENT_MP));
        if ($Kh9MD) goto Kh9eWjgx3b;
        goto Kh9ldMhx3b;
        Kh9eWjgx3b:
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        unset($Kh9tIME);
        $Kh9tIME = $Kh9tIMC;
        $isfxh5 = $Kh9tIME;
        $Kh9MD = in_array($client, array(Client::CLIENT_WX, Client::CLIENT_H5, Client::CLIENT_MP)) && $Kh9tIMC;
        goto Kh9x3a;
        Kh9ldMhx3b:Kh9x3a:
        if ($Kh9MD) goto Kh9eWjgx3c;
        goto Kh9ldMhx3c;
        Kh9eWjgx3c:
        unset($Kh9tIMC);
        $Kh9tIMC = array('in', '1,2');
        $map['p.show_style'] = $Kh9tIMC;
        goto Kh9x39;
        Kh9ldMhx3c:Kh9x39:
        unset($Kh9tIMC);
        $Kh9tIMC = Product::getProduct($map, $user['id'], $province, $city, $mobile);
        $resdata = $Kh9tIMC;
        $Kh9MC = $resdata['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgx3e;
        goto Kh9ldMhx3e;
        Kh9eWjgx3e:
        return rjson(1, $resdata['errmsg']);
        goto Kh9x3d;
        Kh9ldMhx3e:Kh9x3d:
        unset($Kh9tIMC);
        $Kh9tIMC = $resdata['data'];
        $product = $Kh9tIMC;
        $Kh9MC = !isset($product['price']);
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MF = !$Kh9ME;
        if ($Kh9MF) goto Kh9eWjgx3h;
        goto Kh9ldMhx3h;
        Kh9eWjgx3h:
        $Kh9MD = !$product['price'];
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9x3g;
        Kh9ldMhx3h:Kh9x3g:
        if ($Kh9ME) goto Kh9eWjgx3i;
        goto Kh9ldMhx3i;
        Kh9eWjgx3i:
        return rjson(1, '要充值的产品还没有准备好，请联系平台！');
        goto Kh9x3f;
        Kh9ldMhx3i:Kh9x3f:
        unset($Kh9tIMC);
        $Kh9tIMC = floatval(preg_replace('/\\D/', '', $product['name']));
        $real_amount = $Kh9tIMC;
        $Kh9MC = (bool)$amount;
        if ($Kh9MC) goto Kh9eWjgx3n;
        goto Kh9ldMhx3n;
        Kh9eWjgx3n:
        $Kh9MC = $amount && $real_amount;
        goto Kh9x3m;
        Kh9ldMhx3n:Kh9x3m:
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx3l;
        goto Kh9ldMhx3l;
        Kh9eWjgx3l:
        $Kh9MD = $amount != $real_amount;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x3k;
        Kh9ldMhx3l:Kh9x3k:
        if ($Kh9ME) goto Kh9eWjgx3o;
        goto Kh9ldMhx3o;
        Kh9eWjgx3o:
        return rjson(1, '面值检测不相同，提单不通过！');
        goto Kh9x3j;
        Kh9ldMhx3o:Kh9x3j:
        $Kh9MD = (bool)$price;
        if ($Kh9MD) goto Kh9eWjgx3r;
        goto Kh9ldMhx3r;
        Kh9eWjgx3r:
        $Kh9MC = $product['price'] > $price;
        $Kh9MD = $price && $Kh9MC;
        goto Kh9x3q;
        Kh9ldMhx3r:Kh9x3q:
        if ($Kh9MD) goto Kh9eWjgx3s;
        goto Kh9ldMhx3s;
        Kh9eWjgx3s:
        return rjson(1, '成本限制，提单不通过！');
        goto Kh9x3p;
        Kh9ldMhx3s:Kh9x3p:
        $Kh9MC = $product['limit_m_day'] > 0;
        if ($Kh9MC) goto Kh9eWjgx3u;
        goto Kh9ldMhx3u;
        Kh9eWjgx3u:
        $Kh9MC = $product['limit_m_num'] > 0;
        if ($Kh9MC) goto Kh9eWjgx3w;
        goto Kh9ldMhx3w;
        Kh9eWjgx3w:
        $Kh9vPvPvPMC = intval($product['limit_m_day']) * 86400;
        $Kh9vPvPvPMD = time() - $Kh9vPvPvPMC;
        unset($Kh9tIME);
        $Kh9tIME = M('porder')->where(array('mobile' => $mobile, 'product_id' => $product['id'], 'create_time' => array('egt', $Kh9vPvPvPMD), 'status' => array('in', '1,2,3,4,8,9,10,11')))->count();
        $allcount = $Kh9tIME;
        $Kh9MC = $allcount >= $product['limit_m_num'];
        if ($Kh9MC) goto Kh9eWjgx3y;
        goto Kh9ldMhx3y;
        Kh9eWjgx3y:
        $Kh9vPMC = "该产品本号码" . $product['limit_m_day'];
        $Kh9vPMD = $Kh9vPMC . "天内限制";
        $Kh9vPME = $Kh9vPMD . $product['limit_m_num'];
        $Kh9vPMF = $Kh9vPME . "单";
        return rjson(1, $Kh9vPMF);
        goto Kh9x3x;
        Kh9ldMhx3y:Kh9x3x:
        goto Kh9x3v;
        Kh9ldMhx3w:Kh9x3v:
        $Kh9MC = $product['limit_m_month_num'] > 0;
        if ($Kh9MC) goto Kh9eWjgx41;
        goto Kh9ldMhx41;
        Kh9eWjgx41:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('mobile' => $mobile, 'product_id' => $product['id'], 'create_time' => array('between', array(strtotime(date('Y-m-01')), time())), 'status' => array('in', '1,2,3,4,8,9,10,11')))->count();
        $allcount = $Kh9tIMC;
        $Kh9MC = $allcount >= $product['limit_m_month_num'];
        if ($Kh9MC) goto Kh9eWjgx43;
        goto Kh9ldMhx43;
        Kh9eWjgx43:
        $Kh9vPMC = "该产品本号码本月限制" . $product['limit_m_month_num'];
        $Kh9vPMD = $Kh9vPMC . "单";
        return rjson(1, $Kh9vPMD);
        goto Kh9x42;
        Kh9ldMhx43:Kh9x42:
        goto Kh9x4z;
        Kh9ldMhx41:Kh9x4z:
        goto Kh9x3t;
        Kh9ldMhx3u:Kh9x3t:
        $Kh9MC = $product['plimit_one_porder'] > 0;
        if ($Kh9MC) goto Kh9eWjgx45;
        goto Kh9ldMhx45;
        Kh9eWjgx45:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('mobile' => $mobile, 'status' => array('not in', array(4, 5, 6, 7, 12, 13)), 'is_del' => 0))->count();
        $shuliang = $Kh9tIMC;
        $Kh9MC = $shuliang >= $product['plimit_one_porder'];
        if ($Kh9MC) goto Kh9eWjgx47;
        goto Kh9ldMhx47;
        Kh9eWjgx47:
        $Kh9vPMC = '该号码已达到当前产品设置的次数上限</br>该号码系统已存在：' . $shuliang;
        $Kh9vPMD = $Kh9vPMC . '单</br>请等待充值成功后再下单';
        return rjson(1, $Kh9vPMD);
        goto Kh9x46;
        Kh9ldMhx47:Kh9x46:
        goto Kh9x44;
        Kh9ldMhx45:Kh9x44:
        $Kh9MC = $product['start_time'] != "";
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx4a;
        goto Kh9ldMhx4a;
        Kh9eWjgx4a:
        $Kh9MD = $product['end_time'] != "";
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x49;
        Kh9ldMhx4a:Kh9x49:
        if ($Kh9ME) goto Kh9eWjgx4b;
        goto Kh9ldMhx4b;
        Kh9eWjgx4b:
        unset($Kh9tIMC);
        $Kh9tIMC = time();
        $times = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = date('Y-m-d ', $times);
        $Date = $Kh9tIMC;
        $Kh9vPMC = $Date . " ";
        $Kh9vPMD = $Kh9vPMC . $product['start_time'];
        unset($Kh9tIME);
        $Kh9tIME = strtotime($Kh9vPMD);
        $Begin1 = $Kh9tIME;
        $Kh9vPMC = $Date . " ";
        $Kh9vPMD = $Kh9vPMC . $product['end_time'];
        unset($Kh9tIME);
        $Kh9tIME = strtotime($Kh9vPMD);
        $Begin2 = $Kh9tIME;
        $Kh9MC = $times < $Begin1;
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MF = !$Kh9ME;
        if ($Kh9MF) goto Kh9eWjgx4e;
        goto Kh9ldMhx4e;
        Kh9eWjgx4e:
        $Kh9MD = $times > $Begin2;
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9x4d;
        Kh9ldMhx4e:Kh9x4d:
        if ($Kh9ME) goto Kh9eWjgx4f;
        goto Kh9ldMhx4f;
        Kh9eWjgx4f:
        $Kh9vPMC = '当前产品只限在' . $product['start_time'];
        $Kh9vPMD = $Kh9vPMC . '-';
        $Kh9vPME = $Kh9vPMD . $product['end_time'];
        $Kh9vPMF = $Kh9vPME . '时间段内下单';
        return rjson(1, $Kh9vPMF);
        goto Kh9x4c;
        Kh9ldMhx4f:Kh9x4c:
        goto Kh9x48;
        Kh9ldMhx4b:Kh9x48:
        unset($Kh9tIMC);
        $Kh9tIMC = $product['id'];
        $data['product_id'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $product['cate_id'];
        $data['product_cate'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $customer_id;
        $data['customer_id'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $product['price'];
        $data['total_price'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = time();
        $data['create_time'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = 1;
        $data['status'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $remark;
        $data['remark'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $mobile;
        $data['mobile'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $product['type'];
        $data['type'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $product['typec_id'];
        $data['typec'] = $Kh9tIMC;
        $Kh9MC = $product['yname'] . $product['type_name'];
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $data['title'] = $Kh9tIMD;
        unset($Kh9tIMC);
        $Kh9tIMC = $product['yname'];
        $data['product_name'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $product['desc'];
        $data['product_desc'] = $Kh9tIMC;
        $Kh9MC = '为账号' . $mobile;
        $Kh9MD = $Kh9MC . '充值';
        $Kh9ME = $Kh9MD . $product['yname'];
        $Kh9MF = $Kh9ME . $product['type_name'];
        unset($Kh9tIMG);
        $Kh9tIMG = $Kh9MF;
        $data['body'] = $Kh9tIMG;
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
        $Kh9tIMC = $out_trade_num;
        $data['out_trade_num'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = PayWay::PAY_WAY_NULL;
        $data['pay_way'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $data['api_cur_count'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $client;
        $data['client'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $user['weixin_appid'];
        $data['weixin_appid'] = $Kh9tIMC;
        $Kh9MC = $product['delay_api'] > 0;
        if ($Kh9MC) goto Kh9eWjgx4h;
        goto Kh9ldMhx4h;
        Kh9eWjgx4h:
        $Kh9MD = $product['delay_api'] * 60;
        $Kh9ME = $Kh9MD * 60;
        $Kh9MF = time() + $Kh9ME;
        $Kh9MG = $Kh9MF;
        goto Kh9x4g;
        Kh9ldMhx4h:
        $Kh9MG = 0;
        Kh9x4g:
        unset($Kh9tIMH);
        $Kh9tIMH = $Kh9MG;
        $data['delay_time'] = $Kh9tIMH;
        unset($Kh9tIMC);
        $Kh9tIMC = $product['is_jiema'];
        $data['is_jiema'] = $Kh9tIMC;
        $Kh9MC = new self();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $model = $Kh9tIMD;
//        创建订单接口，在这里做拦截
        $model->save($data);
        unset($Kh9tIMC);
        $Kh9tIMC = $model->id;
        $aid = $Kh9tIMC;
        $Kh9MD = !$Kh9tIMC;
        if ($Kh9MD) goto Kh9eWjgx4j;
        goto Kh9ldMhx4j;
        Kh9eWjgx4j:
        return rjson(1, '下单失败，请重试！');
        goto Kh9x4i;
        Kh9ldMhx4j:Kh9x4i:
        return rjson(0, '下单成功', $model->id);
    }

    public static function create_pay($aid, $payway, $client)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = self::where(array('id' => $aid, 'status' => 1))->find();
        $order = $Kh9tIMC;
        $Kh9MC = !$order;
        if ($Kh9MC) goto Kh9eWjgx4l;
        goto Kh9ldMhx4l;
        Kh9eWjgx4l:
        $Kh9vPMC = '订单无需支付' . $aid;
        return rjson(1, $Kh9vPMC);
        goto Kh9x4k;
        Kh9ldMhx4l:Kh9x4k:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $order['customer_id']))->find();
        $customer = $Kh9tIMC;
        $Kh9MC = !$customer;
        if ($Kh9MC) goto Kh9eWjgx4n;
        goto Kh9ldMhx4n;
        Kh9eWjgx4n:
        return rjson(1, '用户数据不存在');
        goto Kh9x4m;
        Kh9ldMhx4n:Kh9x4m:
        if ($customer['wx_openid']) goto Kh9eWjgx4p;
        goto Kh9ldMhx4p;
        Kh9eWjgx4p:
        $Kh9vPvPMC = $customer['wx_openid'];
        goto Kh9x4o;
        Kh9ldMhx4p:
        $Kh9vPvPMC = $customer['ap_openid'];
        Kh9x4o:
        return PayWay::create($payway, $client, array('openid' => $Kh9vPvPMC, 'body' => $order['body'], 'order_number' => $order['order_number'], 'total_price' => $order['total_price'], 'appid' => $customer['weixin_appid']));
    }

    public static function notify($order_number, $payway, $serial_number)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('order_number' => $order_number, 'status' => 1))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx4r;
        goto Kh9ldMhx4r;
        Kh9eWjgx4r:
        return rjson(1, '不存在订单');
        goto Kh9x4q;
        Kh9ldMhx4r:Kh9x4q:
        $Kh9vPMC = "用户支付回调成功，总金额：￥" . $porder['total_price'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        M('porder')->where(array('id' => $porder['id'], 'status' => 1))->setField(array('status' => 2, 'pay_time' => time(), 'pay_way' => $payway, 'serial_number' => $serial_number));
        Notification::paySus($porder['id']);
        try {
            unset($Kh9tIMC);
            $Kh9tIMC = self::h5AgentChildPay($porder['id']);
            $h5res = $Kh9tIMC;
            $Kh9MC = $h5res['errno'] != 500;
            $Kh9ME = (bool)$Kh9MC;
            if ($Kh9ME) goto Kh9eWjgx4v;
            goto Kh9ldMhx4v;
            Kh9eWjgx4v:
            $Kh9MD = $h5res['errno'] != 0;
            $Kh9ME = $Kh9MC && $Kh9MD;
            goto Kh9x4u;
            Kh9ldMhx4v:Kh9x4u:
            if ($Kh9ME) goto Kh9eWjgx4w;
            goto Kh9ldMhx4w;
            Kh9eWjgx4w:
            $Kh9vPMC = 'H5代理商扣款失败，订单被拦截，直接失败！原因：' . $h5res['errmsg'];
            self::rechargeFailDo($porder['order_number'], $Kh9vPMC);
            M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "H5代理商扣款失败"));
            return rjson(1, 'H5代理商扣款失败');
            goto Kh9x4t;
            Kh9ldMhx4w:Kh9x4t:
        } catch (Exception $e) {
            $Kh9vPMC = 'H5代理商扣款失败，订单被拦截，直接失败！原因：系统报错-' . $e->getMessage();
            self::rechargeFailDo($porder['order_number'], $Kh9vPMC);
            M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "H5代理商扣款失败"));
            return rjson(1, 'H5代理商扣款失败');
        }
        if ($porder['is_jiema']) goto Kh9eWjgx4y;
        goto Kh9ldMhx4y;
        Kh9eWjgx4y:
        return self::jmnotify($porder);
        goto Kh9x4x;
        Kh9ldMhx4y:Kh9x4x:
        $Kh9MC = C('ISP_KONGH_24') == 1;
        if ($Kh9MC) goto Kh9eWjgx51;
        goto Kh9ldMhx51;
        Kh9eWjgx51:
        $Kh9vPvPvPvPMC = time() - 86400;
        unset($Kh9tIMD);
        $Kh9tIMD = M('porder')->where(array('mobile' => $porder['mobile'], 'create_time' => array('between', array($Kh9vPvPvPvPMC, time())), 'status' => array('in', array(3, 4, 9, 10, 11, 12, 13))))->count();
        $KONHGH = $Kh9tIMD;
        $Kh9MC = $KONHGH == 0;
        if ($Kh9MC) goto Kh9eWjgx53;
        goto Kh9ldMhx53;
        Kh9eWjgx53:
        $Kh9MC = C('ISP_KONGH_SW') == 4;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx56;
        goto Kh9ldMhx56;
        Kh9eWjgx56:
        $Kh9MD = $Kh9MC && in_array($porder['type'], array(1, 2));
        goto Kh9x55;
        Kh9ldMhx56:Kh9x55:
        if ($Kh9MD) goto Kh9eWjgx57;
        goto Kh9ldMhx57;
        Kh9eWjgx57:
        unset($Kh9tIMC);
        $Kh9tIMC = Isphone::iskongh(C('ISP_KONGH_CFG.apikey'), $porder['mobile']);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] == 0;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx5a;
        goto Kh9ldMhx5a;
        Kh9eWjgx5a:
        $Kh9MD = C('ISP_KONGH_PZ.空号') == 1;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x59;
        Kh9ldMhx5a:Kh9x59:
        if ($Kh9ME) goto Kh9eWjgx5b;
        goto Kh9ldMhx5b;
        Kh9eWjgx5b:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        return self::rechargeSus($porder['order_number'], "空号自动回调成功");
        Createlog::porderLog($porder['id'], '空号已自动回调成功');
        goto Kh9x58;
        Kh9ldMhx5b:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        Kh9x58:
        goto Kh9x54;
        Kh9ldMhx57:Kh9x54:
        $Kh9MC = C('ISP_KONGH_SW') == 1;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx5e;
        goto Kh9ldMhx5e;
        Kh9eWjgx5e:
        $Kh9MD = $Kh9MC && in_array($porder['type'], array(1, 2));
        goto Kh9x5d;
        Kh9ldMhx5e:Kh9x5d:
        if ($Kh9MD) goto Kh9eWjgx5f;
        goto Kh9ldMhx5f;
        Kh9eWjgx5f:
        unset($Kh9tIMC);
        $Kh9tIMC = Isphone::iskongh(C('ISP_KONGH_CFG.apikey'), $porder['mobile']);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] == 0;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx5i;
        goto Kh9ldMhx5i;
        Kh9eWjgx5i:
        $Kh9MD = C('ISP_KONGH_PZ.空号') == 1;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x5h;
        Kh9ldMhx5i:Kh9x5h:
        if ($Kh9ME) goto Kh9eWjgx5j;
        goto Kh9ldMhx5j;
        Kh9eWjgx5j:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号空号，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "手机号空号"));
        unset($Kh9tIMC);
        $Kh9tIMC = $porder['mobile'];
        $arrs['mobile'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = "手机号空号自动拉黑";
        $arrs['remark'] = $Kh9tIMC;
        $Kh9MC = 31536000 + time();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $arrs['limit_time'] = $Kh9tIMD;
        unset($Kh9tIMC);
        $Kh9tIMC = M('mobile_blacklist')->insertGetId($arrs);
        $hei = $Kh9tIMC;
        if ($hei) goto Kh9eWjgx5l;
        goto Kh9ldMhx5l;
        Kh9eWjgx5l:
        Createlog::porderLog($porder['id'], '空号已自动拉黑');
        goto Kh9x5k;
        Kh9ldMhx5l:Kh9x5k:
        return rjson(1, '订单手机号在网状态：空号');
        goto Kh9x5g;
        Kh9ldMhx5j:
        $Kh9MD = (bool)in_array($res['errno'], [6]);
        if ($Kh9MD) goto Kh9eWjgx5n;
        goto Kh9ldMhx5n;
        Kh9eWjgx5n:
        $Kh9MC = C('ISP_KONGH_PZ.未知') == 1;
        $Kh9MD = in_array($res['errno'], [6]) && $Kh9MC;
        goto Kh9x5m;
        Kh9ldMhx5n:Kh9x5m:
        if ($Kh9MD) goto Kh9eWjgx5o;
        goto Kh9ldMhx5o;
        Kh9eWjgx5o:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号未知，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "手机号在网状态未知"));
        return rjson(1, '订单手机号在网状态：未知');
        goto Kh9x5g;
        Kh9ldMhx5o:
        $Kh9MD = (bool)in_array($res['errno'], [4]);
        if ($Kh9MD) goto Kh9eWjgx5q;
        goto Kh9ldMhx5q;
        Kh9eWjgx5q:
        $Kh9MC = C('ISP_KONGH_PZ.停机') == 1;
        $Kh9MD = in_array($res['errno'], [4]) && $Kh9MC;
        goto Kh9x5p;
        Kh9ldMhx5q:Kh9x5p:
        if ($Kh9MD) goto Kh9eWjgx5r;
        goto Kh9ldMhx5r;
        Kh9eWjgx5r:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号停机，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "手机号停机"));
        return rjson(1, '订单手机号在网状态：停机');
        goto Kh9x5g;
        Kh9ldMhx5r:
        $Kh9MC = $res['errno'] == 5;
        if ($Kh9MC) goto Kh9eWjgx5s;
        goto Kh9ldMhx5s;
        Kh9eWjgx5s:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号号码错误，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "号码错误"));
        return rjson(1, '号码错误');
        goto Kh9x5g;
        Kh9ldMhx5s:
        $Kh9MC = $res['errno'] == 8;
        if ($Kh9MC) goto Kh9eWjgx5t;
        goto Kh9ldMhx5t;
        Kh9eWjgx5t:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '手机在网状态查询失败，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "查询失败"));
        return rjson(1, '订单手机号在网状态：查询失败');
        goto Kh9x5g;
        Kh9ldMhx5t:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        Kh9x5g:
        goto Kh9x5c;
        Kh9ldMhx5f:Kh9x5c:
        goto Kh9x52;
        Kh9ldMhx53:
        Createlog::porderLog($porder['id'], '手机在网状态查询：已开启24小时内同号码只查询一次，本次号码不查询。');
        Kh9x52:
        goto Kh9x5z;
        Kh9ldMhx51:
        $Kh9MC = C('ISP_KONGH_SW') == 4;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx5w;
        goto Kh9ldMhx5w;
        Kh9eWjgx5w:
        $Kh9MD = $Kh9MC && in_array($porder['type'], array(1, 2));
        goto Kh9x5v;
        Kh9ldMhx5w:Kh9x5v:
        if ($Kh9MD) goto Kh9eWjgx5x;
        goto Kh9ldMhx5x;
        Kh9eWjgx5x:
        unset($Kh9tIMC);
        $Kh9tIMC = Isphone::iskongh(C('ISP_KONGH_CFG.apikey'), $porder['mobile']);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] == 0;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx61;
        goto Kh9ldMhx61;
        Kh9eWjgx61:
        $Kh9MD = C('ISP_KONGH_PZ.空号') == 1;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x6z;
        Kh9ldMhx61:Kh9x6z:
        if ($Kh9ME) goto Kh9eWjgx62;
        goto Kh9ldMhx62;
        Kh9eWjgx62:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        return self::rechargeSus($porder['order_number'], "空号自动回调成功");
        Createlog::porderLog($porder['id'], '空号已自动回调成功');
        goto Kh9x5y;
        Kh9ldMhx62:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        Kh9x5y:
        goto Kh9x5u;
        Kh9ldMhx5x:Kh9x5u:
        $Kh9MC = C('ISP_KONGH_SW') == 1;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx65;
        goto Kh9ldMhx65;
        Kh9eWjgx65:
        $Kh9MD = $Kh9MC && in_array($porder['type'], array(1, 2));
        goto Kh9x64;
        Kh9ldMhx65:Kh9x64:
        if ($Kh9MD) goto Kh9eWjgx66;
        goto Kh9ldMhx66;
        Kh9eWjgx66:
        unset($Kh9tIMC);
        $Kh9tIMC = Isphone::iskongh(C('ISP_KONGH_CFG.apikey'), $porder['mobile']);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] == 0;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx69;
        goto Kh9ldMhx69;
        Kh9eWjgx69:
        $Kh9MD = C('ISP_KONGH_PZ.空号') == 1;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x68;
        Kh9ldMhx69:Kh9x68:
        if ($Kh9ME) goto Kh9eWjgx6a;
        goto Kh9ldMhx6a;
        Kh9eWjgx6a:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号空号，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "手机号空号"));
        unset($Kh9tIMC);
        $Kh9tIMC = $porder['mobile'];
        $arrs['mobile'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = "手机号空号自动拉黑";
        $arrs['remark'] = $Kh9tIMC;
        $Kh9MC = 31536000 + time();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $arrs['limit_time'] = $Kh9tIMD;
        unset($Kh9tIMC);
        $Kh9tIMC = M('mobile_blacklist')->insertGetId($arrs);
        $hei = $Kh9tIMC;
        if ($hei) goto Kh9eWjgx6c;
        goto Kh9ldMhx6c;
        Kh9eWjgx6c:
        Createlog::porderLog($porder['id'], '空号已自动拉黑');
        goto Kh9x6b;
        Kh9ldMhx6c:Kh9x6b:
        return rjson(1, '订单手机号在网状态：空号');
        goto Kh9x67;
        Kh9ldMhx6a:
        $Kh9MD = (bool)in_array($res['errno'], [6]);
        if ($Kh9MD) goto Kh9eWjgx6e;
        goto Kh9ldMhx6e;
        Kh9eWjgx6e:
        $Kh9MC = C('ISP_KONGH_PZ.未知') == 1;
        $Kh9MD = in_array($res['errno'], [6]) && $Kh9MC;
        goto Kh9x6d;
        Kh9ldMhx6e:Kh9x6d:
        if ($Kh9MD) goto Kh9eWjgx6f;
        goto Kh9ldMhx6f;
        Kh9eWjgx6f:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号未知，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "手机号在网状态未知"));
        return rjson(1, '订单手机号在网状态：未知');
        goto Kh9x67;
        Kh9ldMhx6f:
        $Kh9MD = (bool)in_array($res['errno'], [4]);
        if ($Kh9MD) goto Kh9eWjgx6h;
        goto Kh9ldMhx6h;
        Kh9eWjgx6h:
        $Kh9MC = C('ISP_KONGH_PZ.停机') == 1;
        $Kh9MD = in_array($res['errno'], [4]) && $Kh9MC;
        goto Kh9x6g;
        Kh9ldMhx6h:Kh9x6g:
        if ($Kh9MD) goto Kh9eWjgx6i;
        goto Kh9ldMhx6i;
        Kh9eWjgx6i:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号停机，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "手机号停机"));
        return rjson(1, '订单手机号在网状态：停机');
        goto Kh9x67;
        Kh9ldMhx6i:
        $Kh9MC = $res['errno'] == 5;
        if ($Kh9MC) goto Kh9eWjgx6j;
        goto Kh9ldMhx6j;
        Kh9eWjgx6j:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号号码错误，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "号码错误"));
        return rjson(1, '号码错误');
        goto Kh9x67;
        Kh9ldMhx6j:
        $Kh9MC = $res['errno'] == 8;
        if ($Kh9MC) goto Kh9eWjgx6k;
        goto Kh9ldMhx6k;
        Kh9eWjgx6k:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '手机在网状态查询失败，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "查询失败"));
        return rjson(1, '订单手机号在网状态：查询失败');
        goto Kh9x67;
        Kh9ldMhx6k:
        $Kh9vPMC = '手机在网状态查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        Kh9x67:
        goto Kh9x63;
        Kh9ldMhx66:Kh9x63:Kh9x5z:
        $Kh9MC = C('ISP_ZHUANW_SW') == 1;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx6n;
        goto Kh9ldMhx6n;
        Kh9eWjgx6n:
        $Kh9MD = $Kh9MC && in_array($porder['type'], array(1, 2));
        goto Kh9x6m;
        Kh9ldMhx6n:Kh9x6m:
        if ($Kh9MD) goto Kh9eWjgx6o;
        goto Kh9ldMhx6o;
        Kh9eWjgx6o:
        unset($Kh9tIMC);
        $Kh9tIMC = Ispzw::isZhuanw(C('ISP_ZHUANW_CFG.apikey'), $porder['mobile']);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] == 0;
        if ($Kh9MC) goto Kh9eWjgx6q;
        goto Kh9ldMhx6q;
        Kh9eWjgx6q:
        $Kh9vPMC = '携号转网查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        self::rechargeFailDo($porder['order_number'], '订单手机号已携号转网，订单被拦截，直接失败！');
        M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => "手机号携号转网"));
        unset($Kh9tIMC);
        $Kh9tIMC = $porder['mobile'];
        $arrs['mobile'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = "手机号携号转网自动拉黑";
        $arrs['remark'] = $Kh9tIMC;
        $Kh9MC = 31536000 + time();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $arrs['limit_time'] = $Kh9tIMD;
        unset($Kh9tIMC);
        $Kh9tIMC = M('mobile_blacklist')->insertGetId($arrs);
        $hei = $Kh9tIMC;
        if ($hei) goto Kh9eWjgx6s;
        goto Kh9ldMhx6s;
        Kh9eWjgx6s:
        Createlog::porderLog($porder['id'], '携号转网已自动拉黑');
        goto Kh9x6r;
        Kh9ldMhx6s:Kh9x6r:
        return rjson(1, '订单手机号携号转网');
        goto Kh9x6p;
        Kh9ldMhx6q:
        $Kh9vPMC = '携号转网查询：' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        Kh9x6p:
        goto Kh9x6l;
        Kh9ldMhx6o:Kh9x6l:
        $Kh9MC = $porder['delay_time'] <= time();
        if ($Kh9MC) goto Kh9eWjgx6u;
        goto Kh9ldMhx6u;
        Kh9eWjgx6u:
        $Kh9MC = $porder['api_open'] == 1;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx6w;
        goto Kh9ldMhx6w;
        Kh9eWjgx6w:
        $Kh9MD = $Kh9MC && queue('app\\queue\\job\\Work@porderSubApi', $porder['id']);
        goto Kh9x6v;
        Kh9ldMhx6w:Kh9x6v:
        goto Kh9x6t;
        Kh9ldMhx6u:
        $Kh9vPMC = '订单开启了延迟提交API，设定的提交时间:' . time_format($porder['delay_time']);
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        Kh9x6t:
        return rjson(0, '回调处理完成');
    }

    public static function jmnotify($porder)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('product')->where(array('id' => $porder['product_id']))->field('id,jmapi_id,jmapi_param_id')->find();
        $product = $Kh9tIMC;
        $Kh9MC = !$product;
        if ($Kh9MC) goto Kh9eWjgx6y;
        goto Kh9ldMhx6y;
        Kh9eWjgx6y:
        return djson(1, '产品未找到');
        goto Kh9x6x;
        Kh9ldMhx6y:Kh9x6x:
        unset($Kh9tIMC);
        $Kh9tIMC = M('jmapi')->where(array('id' => $product['jmapi_id'], 'is_del' => 0))->find();
        $config = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('jmapi_param')->where(array('id' => $product['jmapi_param_id']))->find();
        $param = $Kh9tIMC;
        $Kh9MC = !$config;
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MF = !$Kh9ME;
        if ($Kh9MF) goto Kh9eWjgx72;
        goto Kh9ldMhx72;
        Kh9eWjgx72:
        $Kh9MD = !$param;
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9x71;
        Kh9ldMhx72:Kh9x71:
        if ($Kh9ME) goto Kh9eWjgx73;
        goto Kh9ldMhx73;
        Kh9eWjgx73:
        return djson(1, '接码api信息未查询到');
        goto Kh9x7z;
        Kh9ldMhx73:Kh9x7z:
        $Kh9MC = 'Jiema\\' . $config['callapi'];
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $classname = $Kh9tIMD;
        $Kh9MC = new $classname($config);
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $model = $Kh9tIMD;
        unset($Kh9tIMC);
        $Kh9tIMC = $porder['extend_param1'];
        $param['extend_param1'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $porder['extend_param2'];
        $param['extend_param2'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $porder['extend_param3'];
        $param['extend_param3'] = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $model->recharge($porder['mobile'], $porder['order_number'], $param);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgx75;
        goto Kh9ldMhx75;
        Kh9eWjgx75:
        $Kh9vPMC = '接码订单提交api充值失败|' . $res['errmsg'];
        $Kh9vPMD = $Kh9vPMC . '|';
        $Kh9vPME = $Kh9vPMD . var_export($res['data']);
        self::rechargeFail($porder['order_number'], $Kh9vPME, "接码api提交失败");
        return rjson(0, '接码订单提交api充值失败');
        goto Kh9x74;
        Kh9ldMhx75:Kh9x74:
        M('porder')->where(array('id' => $porder['id'], 'status' => 2))->setField(array('status' => 3));
        $Kh9vPMC = '接码订单提交api成功|订单变成充值中状态|接口返回信息:' . $res['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        return rjson(0, '回调处理完成');
    }

    public static function subApi($porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => 2, 'api_open' => 1))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx77;
        goto Kh9ldMhx77;
        Kh9eWjgx77:
        return rjson(1, '订单无需提交接口充值');
        goto Kh9x76;
        Kh9ldMhx77:Kh9x76:
        Rechargeapi::recharge($porder['id']);
        return rjson(0, '提交接口工作完成');
    }

    public static function getCurApi($porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => array('in', '2,3'), 'api_open' => 1))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx79;
        goto Kh9ldMhx79;
        Kh9eWjgx79:
        return rjson(1, '自动充值订单无效');
        goto Kh9x78;
        Kh9ldMhx79:Kh9x78:
        unset($Kh9tIMC);
        $Kh9tIMC = json_decode($porder['api_arr'], true);
        $api_arr = $Kh9tIMC;
        $Kh9MC = count($api_arr) == 0;
        if ($Kh9MC) goto Kh9eWjgx7b;
        goto Kh9ldMhx7b;
        Kh9eWjgx7b:
        return rjson(1, '自动充值接口为空');
        goto Kh9x7a;
        Kh9ldMhx7b:Kh9x7a:
        $Kh9MC = count($api_arr) - 1;
        $Kh9MD = $porder['api_cur_index'] >= $Kh9MC;
        $Kh9MF = (bool)$Kh9MD;
        if ($Kh9MF) goto Kh9eWjgx7e;
        goto Kh9ldMhx7e;
        Kh9eWjgx7e:
        $Kh9ME = $api_arr[$porder['api_cur_index']]['num'] <= $porder['api_cur_num'];
        $Kh9MF = $Kh9MD && $Kh9ME;
        goto Kh9x7d;
        Kh9ldMhx7e:Kh9x7d:
        if ($Kh9MF) goto Kh9eWjgx7f;
        goto Kh9ldMhx7f;
        Kh9eWjgx7f:
        return rjson(1, '无可继续调用的API');
        goto Kh9x7c;
        Kh9ldMhx7f:Kh9x7c:
        $Kh9MC = $porder['api_cur_index'] >= 0;
        if ($Kh9MC) goto Kh9eWjgx7h;
        goto Kh9ldMhx7h;
        Kh9eWjgx7h:
        unset($Kh9tIMC);
        $Kh9tIMC = $api_arr[$porder['api_cur_index']]['num'];
        $num = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $porder['api_cur_num'];
        $cur_num = $Kh9tIMC;
        $Kh9MC = $cur_num >= $num;
        if ($Kh9MC) goto Kh9eWjgx7j;
        goto Kh9ldMhx7j;
        Kh9eWjgx7j:
        $Kh9MC = $porder['api_cur_index'] + 1;
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $index = $Kh9tIMD;
        unset($Kh9tIMC);
        $Kh9tIMC = 1;
        $cnum = $Kh9tIMC;
        goto Kh9x7i;
        Kh9ldMhx7j:
        unset($Kh9tIMC);
        $Kh9tIMC = $porder['api_cur_index'];
        $index = $Kh9tIMC;
        $Kh9MC = $porder['api_cur_num'] + 1;
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $cnum = $Kh9tIMD;
        Kh9x7i:
        return rjson(0, '请继续提交接口充值', array('api' => $api_arr[$index], 'index' => $index, 'num' => $cnum));
        goto Kh9x7g;
        Kh9ldMhx7h:
        $Kh9MC = $porder['api_cur_index'] + 1;
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $index = $Kh9tIMD;
        return rjson(0, '请继续提交接口充值', array('api' => $api_arr[$index], 'index' => $index, 'num' => 1));
        Kh9x7g:
    }

    public static function rechargeSusApi($api, $api_order_number, $data, $remark = '', $kami = '')
    {
        unset($Kh9tIMC);
        $Kh9tIMC = self::apinotify_log($api, $api_order_number, $data);
        $flag = $Kh9tIMC;
        $Kh9MC = !$flag;
        if ($Kh9MC) goto Kh9eWjgx7l;
        goto Kh9ldMhx7l;
        Kh9eWjgx7l:
        return rjson(1, '接口已回调过了');
        goto Kh9x7k;
        Kh9ldMhx7l:Kh9x7k:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('api_order_number' => $api_order_number, 'status' => array('in', '2,3,9')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx7n;
        goto Kh9ldMhx7n;
        Kh9eWjgx7n:
        return rjson(1, '订单未找到');
        goto Kh9x7m;
        Kh9ldMhx7n:Kh9x7m:
        $Kh9MC = $porder['is_apart'] == 1;
        if ($Kh9MC) goto Kh9eWjgx7p;
        goto Kh9ldMhx7p;
        Kh9eWjgx7p:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('apart_order_number' => $porder['apart_order_number'], 'status' => 4, 'is_del' => 0))->select();
        $susmzs = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $susmz = $Kh9tIMC;
        foreach ($susmzs as $porders) {
            $Kh9MC = $susmz + floatval(preg_replace('/\\D/', '', $porders['product_name']));
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $susmz = $Kh9tIMD;
        }
        $Kh9vPvPMC = '[充值中]子单部分充值成功：' . $susmz;
        M('porder')->where(['order_number' => $porder['apart_order_number']])->setField(array('remark' => $Kh9vPvPMC, 'charge_amount' => $susmz));
        goto Kh9x7o;
        Kh9ldMhx7p:Kh9x7o:
        $Kh9MC = (bool)$remark;
        if ($Kh9MC) goto Kh9eWjgx7r;
        goto Kh9ldMhx7r;
        Kh9eWjgx7r:
        $Kh9MC = $remark && M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => $remark));
        goto Kh9x7q;
        Kh9ldMhx7r:Kh9x7q:
        $Kh9MC = (bool)$kami;
        if ($Kh9MC) goto Kh9eWjgx7t;
        goto Kh9ldMhx7t;
        Kh9eWjgx7t:
        $Kh9MC = $kami && M('porder')->where(array('id' => $porder['id']))->setField(array('charge_kami' => $kami));
        goto Kh9x7s;
        Kh9ldMhx7t:Kh9x7s:
        M('porder_apilog')->where(array('api_order_number' => $api_order_number))->setField(array('state' => 1));
        $Kh9vPMC = "充值成功|接口回调|" . $remark;
        $Kh9vPMD = $Kh9vPMC . '|';
        $Kh9vPME = $Kh9vPMD . var_export($data, true);
        return self::rechargeSus($porder['order_number'], $Kh9vPME);
    }

    public static function rechargeSus($order_number, $remark)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('order_number' => $order_number, 'status' => array('in', '2,3,8,9,10,11')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx7v;
        goto Kh9ldMhx7v;
        Kh9eWjgx7v:
        return rjson(1, '订单未找到');
        goto Kh9x7u;
        Kh9ldMhx7v:Kh9x7u:
        M('porder')->where(array('id' => $porder['id']))->setField(array('status' => 4, 'finish_time' => time(), 'apply_refund' => 0, 'apply_break' => 0));
        $Kh9MC = $porder['is_apart'] == 1;
        if ($Kh9MC) goto Kh9eWjgx7x;
        goto Kh9ldMhx7x;
        Kh9eWjgx7x:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('apart_order_number' => $porder['apart_order_number'], 'status' => 4, 'is_del' => 0))->select();
        $susmzs = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $susmz = $Kh9tIMC;
        foreach ($susmzs as $porders) {
            $Kh9MC = $susmz + floatval(preg_replace('/\\D/', '', $porders['product_name']));
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $susmz = $Kh9tIMD;
        }
        $Kh9vPvPMC = '[充值中]子单部分充值成功：' . $susmz;
        M('porder')->where(['order_number' => $porder['apart_order_number']])->setField(array('remark' => $Kh9vPvPMC, 'charge_amount' => $susmz));
        goto Kh9x7w;
        Kh9ldMhx7x:Kh9x7w:
        Createlog::porderLog($porder['id'], $remark);
        queue('app\\queue\\job\\Work@callFunc', array('class' => '\\app\\common\\library\\Notification', 'func' => 'rechargeSus', 'param' => $porder['id']));
        self::rebate($porder['id']);
        $Kh9MC = $porder['is_apart'] == 1;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx8z;
        goto Kh9ldMhx8z;
        Kh9eWjgx8z:
        $Kh9MD = $Kh9MC && self::childFinishTrigger($porder['id']);
        goto Kh9x7y;
        Kh9ldMhx8z:Kh9x7y:
        return rjson(0, '操作成功');
    }

    public static function rechargeFailApi($api, $api_order_number, $data, $remark = '')
    {
        unset($Kh9tIMC);
        $Kh9tIMC = self::apinotify_log($api, $api_order_number, $data);
        $flag = $Kh9tIMC;
        $Kh9MC = !$flag;
        if ($Kh9MC) goto Kh9eWjgx82;
        goto Kh9ldMhx82;
        Kh9eWjgx82:
        return rjson(1, '接口已回调过了');
        goto Kh9x81;
        Kh9ldMhx82:Kh9x81:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('api_order_number' => $api_order_number, 'status' => array('in', '2,3')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx84;
        goto Kh9ldMhx84;
        Kh9eWjgx84:
        return rjson(1, '订单未找到');
        goto Kh9x83;
        Kh9ldMhx84:Kh9x83:
        M('porder_apilog')->where(array('api_order_number' => $api_order_number))->setField(array('state' => 2));
        $Kh9vPMC = "充值失败|接口回调|" . $remark;
        $Kh9vPMD = $Kh9vPMC . '|';
        $Kh9vPME = $Kh9vPMD . var_export($data, true);
        return self::rechargeFail($porder['order_number'], $Kh9vPME, $remark);
    }

    public static function rechargeFail($order_number, $log, $remark = '')
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('order_number' => $order_number, 'status' => array('in', '2,3,8,9,10,11')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx86;
        goto Kh9ldMhx86;
        Kh9eWjgx86:
        return rjson(1, '订单未找到');
        goto Kh9x85;
        Kh9ldMhx86:Kh9x85:
        $Kh9MC = (bool)$remark;
        if ($Kh9MC) goto Kh9eWjgx88;
        goto Kh9ldMhx88;
        Kh9eWjgx88:
        $Kh9MC = $remark && M('porder')->where(array('id' => $porder['id']))->setField(array('remark' => $remark));
        goto Kh9x87;
        Kh9ldMhx88:Kh9x87:
        M('porder')->where(array('id' => $porder['id']))->setField(array('apifail_time' => time()));
        $Kh9MC = $porder['apply_refund'] == 1;
        if ($Kh9MC) goto Kh9eWjgx8a;
        goto Kh9ldMhx8a;
        Kh9eWjgx8a:
        Createlog::porderLog($porder['id'], $log);
        return self::rechargeFailDo($order_number, $log);
        goto Kh9x89;
        Kh9ldMhx8a:Kh9x89:
        $Kh9MC = $porder['api_open'] == 1;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx8d;
        goto Kh9ldMhx8d;
        Kh9eWjgx8d:
        $Kh9MD = $porder['apply_break'] == 0;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x8c;
        Kh9ldMhx8d:Kh9x8c:
        if ($Kh9ME) goto Kh9eWjgx8e;
        goto Kh9ldMhx8e;
        Kh9eWjgx8e:
        unset($Kh9tIMC);
        $Kh9tIMC = Porder::getCurApi($porder['id']);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] == 0;
        if ($Kh9MC) goto Kh9eWjgx8g;
        goto Kh9ldMhx8g;
        Kh9eWjgx8g:
        Createlog::porderLog($porder['id'], $log);
        M('porder')->where(array('id' => $porder['id']))->setField(array('status' => 2));
        $Kh9vPMC = intval(C('API_FAILE_DELAY_MINUTE')) * 60;
        queue('app\\queue\\job\\Work@porderSubApi', $porder['id'], $Kh9vPMC);
        return rjson(0, '处理成功');
        goto Kh9x8f;
        Kh9ldMhx8g:Kh9x8f:
        goto Kh9x8b;
        Kh9ldMhx8e:Kh9x8b:
        $Kh9MC = intval(C('ODAPI_FAIL_STYLE')) == 3;
        if ($Kh9MC) goto Kh9eWjgx8i;
        goto Kh9ldMhx8i;
        Kh9eWjgx8i:
        $Kh9MD = M('product')->where(array('id' => $porder['product_id']))->value('api_fail_style');
        goto Kh9x8h;
        Kh9ldMhx8i:
        $Kh9MD = intval(C('ODAPI_FAIL_STYLE'));
        Kh9x8h:
        unset($Kh9tIME);
        $Kh9tIME = $Kh9MD;
        $apifailstyle = $Kh9tIME;
        $Kh9MC = $apifailstyle == 2;
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MF = !$Kh9ME;
        if ($Kh9MF) goto Kh9eWjgx8l;
        goto Kh9ldMhx8l;
        Kh9eWjgx8l:
        $Kh9MD = $porder['apply_break'] == 1;
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9x8k;
        Kh9ldMhx8l:Kh9x8k:
        if ($Kh9ME) goto Kh9eWjgx8m;
        goto Kh9ldMhx8m;
        Kh9eWjgx8m:
        Createlog::porderLog($porder['id'], $log);
        M('porder')->where(array('id' => $porder['id']))->setField(array('status' => 10, 'apply_break' => 0));
        Createlog::porderLog($porder['id'], "api失败,订单到压单状态（压单功能生效，该订单可以手动再次提交接口，如果不想使用此功能，请到 系统->网站设置->用户配置->订单api失败后处理方式 选项 改成:直接失败）");
        return rjson(0, '处理成功');
        goto Kh9x8j;
        Kh9ldMhx8m:
        return self::rechargeFailDo($order_number, $log);
        Kh9x8j:
    }

    public static function rechargeFailAgent($order_number, $remark)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('order_number' => $order_number, 'status' => array('in', '2,10')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx8o;
        goto Kh9ldMhx8o;
        Kh9eWjgx8o:
        return rjson(1, '订单不可取消');
        goto Kh9x8n;
        Kh9ldMhx8o:Kh9x8n:
        $Kh9MC = $porder['status'] == 2;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx8r;
        goto Kh9ldMhx8r;
        Kh9eWjgx8r:
        $Kh9MD = $porder['api_open'] == 1;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x8q;
        Kh9ldMhx8r:Kh9x8q:
        if ($Kh9ME) goto Kh9eWjgx8s;
        goto Kh9ldMhx8s;
        Kh9eWjgx8s:
        return rjson(1, '订单正在提交，暂时不可取消');
        goto Kh9x8p;
        Kh9ldMhx8s:Kh9x8p:
        return self::rechargeFailDo($order_number, $remark);
    }

    public static function rechargeFailDo($order_number, $remark)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('order_number' => $order_number, 'status' => array('in', '2,3,8,9,10,11')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx8u;
        goto Kh9ldMhx8u;
        Kh9eWjgx8u:
        return rjson(1, '订单未找到');
        goto Kh9x8t;
        Kh9ldMhx8u:Kh9x8t:
        M('porder')->where(array('id' => $porder['id']))->setField(array('status' => 5, 'refund_price' => $porder['total_price'], 'apply_refund' => 0, 'apply_break' => 0, 'finish_time' => time()));
        queue('app\\queue\\job\\Work@callFunc', array('class' => '\\app\\common\\library\\Notification', 'func' => 'rechargeFail', 'param' => $porder['id']));
        $Kh9MC = C('AUTO_REFUND') == 1;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx8w;
        goto Kh9ldMhx8w;
        Kh9eWjgx8w:
        $Kh9MD = $Kh9MC && queue('app\\queue\\job\\Work@porderRefund', array('id' => $porder['id'], 'remark' => $remark, 'operator' => '系统'));
        goto Kh9x8v;
        Kh9ldMhx8w:Kh9x8v:
        $Kh9MC = $porder['is_apart'] == 1;
        $Kh9MD = (bool)$Kh9MC;
        if ($Kh9MD) goto Kh9eWjgx8y;
        goto Kh9ldMhx8y;
        Kh9eWjgx8y:
        $Kh9MD = $Kh9MC && self::childFinishTrigger($porder['id']);
        goto Kh9x8x;
        Kh9ldMhx8y:Kh9x8x:
        return rjson(0, '操作成功');
    }

    public static function rechargeRateApi($api, $api_order_number, $data, $charge_amount)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('api_order_number' => $api_order_number, 'status' => array('in', '3'), 'charge_amount' => array('neq', $charge_amount)))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx91;
        goto Kh9ldMhx91;
        Kh9eWjgx91:
        return rjson(1, '订单未找到');
        goto Kh9x9z;
        Kh9ldMhx91:Kh9x9z:
        $Kh9MC = !$charge_amount;
        if ($Kh9MC) goto Kh9eWjgx93;
        goto Kh9ldMhx93;
        Kh9eWjgx93:
        return rjson(1, '充值进度变化不合法');
        goto Kh9x92;
        Kh9ldMhx93:Kh9x92:
        $Kh9vPMC = "充值进度|接口回调|已充值" . $charge_amount;
        $Kh9vPMD = $Kh9vPMC . '|';
        $Kh9vPME = $Kh9vPMD . var_export($data, true);
        Createlog::porderLog($porder['id'], $Kh9vPME);
        $Kh9vPvPMC = "已充值：" . $charge_amount;
        unset($Kh9tIMD);
        $Kh9tIMD = M('porder')->where(array('api_order_number' => $api_order_number, 'status' => array('in', array(3))))->setField(array('remark' => $Kh9vPvPMC, 'charge_amount' => $charge_amount));
        $flag = $Kh9tIMD;
        $Kh9MC = (bool)$flag;
        if ($Kh9MC) goto Kh9eWjgx95;
        goto Kh9ldMhx95;
        Kh9eWjgx95:
        $Kh9MC = $flag && queue('app\\queue\\job\\Work@callFunc', array('class' => '\\app\\common\\library\\Notification', 'func' => 'rechargeIng', 'param' => $porder['id']));
        goto Kh9x94;
        Kh9ldMhx95:Kh9x94:
        return rjson(0, '操作成功');
    }

    public static function rechargePartApi($api, $api_order_number, $data, $remark, $charge_amount = '')
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('api_order_number' => $api_order_number, 'status' => array('in', '3,9')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx97;
        goto Kh9ldMhx97;
        Kh9eWjgx97:
        return rjson(1, '订单未找到');
        goto Kh9x96;
        Kh9ldMhx97:Kh9x96:
        $Kh9vPMC = "部分充值|接口回调|" . $remark;
        $Kh9vPMD = $Kh9vPMC . '|';
        $Kh9vPME = $Kh9vPMD . var_export($data, true);
        Createlog::porderLog($porder['id'], $Kh9vPME);
        $Kh9MC = (bool)$charge_amount;
        if ($Kh9MC) goto Kh9eWjgx99;
        goto Kh9ldMhx99;
        Kh9eWjgx99:
        $Kh9MC = $charge_amount && M('porder')->where(array('id' => $porder['id'], 'status' => array('in', '3,9')))->setField(array('charge_amount' => $charge_amount));
        goto Kh9x98;
        Kh9ldMhx99:Kh9x98:
        $Kh9MC = (bool)$remark;
        if ($Kh9MC) goto Kh9eWjgx9b;
        goto Kh9ldMhx9b;
        Kh9eWjgx9b:
        $Kh9MC = $remark && M('porder')->where(array('id' => $porder['id'], 'status' => array('in', '3,9')))->setField(array('status' => 9, 'remark' => $remark));
        goto Kh9x9a;
        Kh9ldMhx9b:Kh9x9a:
        M('porder_apilog')->where(array('api_order_number' => $api_order_number))->setField(array('state' => 3));
        queue('app\\queue\\job\\Work@callFunc', array('class' => '\\app\\common\\library\\Notification', 'func' => 'rechargeIng', 'param' => $porder['id']));
        $Kh9MC = $porder['is_apart'] != 2;
        if ($Kh9MC) goto Kh9eWjgx9d;
        goto Kh9ldMhx9d;
        Kh9eWjgx9d:
        $Kh9MC = intval(C('ODPART_REG_REFUND')) == 3;
        if ($Kh9MC) goto Kh9eWjgx9f;
        goto Kh9ldMhx9f;
        Kh9eWjgx9f:
        $Kh9MD = M('product')->where(array('id' => $porder['product_id']))->value('odpart_teg_refund');
        goto Kh9x9e;
        Kh9ldMhx9f:
        $Kh9MD = intval(C('ODPART_REG_REFUND'));
        Kh9x9e:
        unset($Kh9tIME);
        $Kh9tIME = $Kh9MD;
        $odpart_teg_refund = $Kh9tIME;
        $Kh9MC = $odpart_teg_refund == 2;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx9i;
        goto Kh9ldMhx9i;
        Kh9eWjgx9i:
        $Kh9MD = $charge_amount > 0;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x9h;
        Kh9ldMhx9i:Kh9x9h:
        if ($Kh9ME) goto Kh9eWjgx9j;
        goto Kh9ldMhx9j;
        Kh9eWjgx9j:
        $Kh9vPMC = '部分充值完成:' . $charge_amount;
        self::rechargePartDo($porder['order_number'], '部分充值中订单自动退款', $charge_amount, $Kh9vPMC);
        goto Kh9x9g;
        Kh9ldMhx9j:Kh9x9g:
        goto Kh9x9c;
        Kh9ldMhx9d:Kh9x9c:
        return rjson(0, '操作成功');
    }

    public static function rechargePartDo($order_number, $remark, $charge_amount, $reason = '')
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('order_number' => $order_number, 'status' => array('in', '2,3,8,9,10,11')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgx9l;
        goto Kh9ldMhx9l;
        Kh9eWjgx9l:
        return rjson(1, '订单未找到');
        goto Kh9x9k;
        Kh9ldMhx9l:Kh9x9k:
        Createlog::porderLog($porder['id'], $remark);
        unset($Kh9tIMC);
        $Kh9tIMC = floatval(preg_replace('/\\D/', '', $porder['product_name']));
        $allmian = $Kh9tIMC;
        $Kh9MC = $allmian <= $charge_amount;
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MF = !$Kh9ME;
        if ($Kh9MF) goto Kh9eWjgx9o;
        goto Kh9ldMhx9o;
        Kh9eWjgx9o:
        $Kh9MD = $charge_amount <= 0;
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9x9n;
        Kh9ldMhx9o:Kh9x9n:
        if ($Kh9ME) goto Kh9eWjgx9p;
        goto Kh9ldMhx9p;
        Kh9eWjgx9p:
        Createlog::porderLog($porder['id'], '部分充值面值不合法');
        return rjson(1, '部分充值面值不合法');
        goto Kh9x9m;
        Kh9ldMhx9p:Kh9x9m:
        $Kh9MC = $allmian - $charge_amount;
        $Kh9MD = $Kh9MC / $allmian;
        unset($Kh9tIME);
        $Kh9tIME = $Kh9MD;
        $compratio = $Kh9tIME;
        $Kh9MC = $compratio <= 0;
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MF = !$Kh9ME;
        if ($Kh9MF) goto Kh9eWjgx9s;
        goto Kh9ldMhx9s;
        Kh9eWjgx9s:
        $Kh9MD = $compratio >= 1;
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9x9r;
        Kh9ldMhx9s:Kh9x9r:
        if ($Kh9ME) goto Kh9eWjgx9t;
        goto Kh9ldMhx9t;
        Kh9eWjgx9t:
        Createlog::porderLog($porder['id'], '部分充值退款比例不合法');
        return rjson(1, '部分充值退款比例不合法');
        goto Kh9x9q;
        Kh9ldMhx9t:Kh9x9q:
        $Kh9MC = $compratio * $porder['total_price'];
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $refund_price = $Kh9tIMD;
        M('porder')->where(array('id' => $porder['id']))->setField(array('status' => 12, 'apply_refund' => 0, 'apply_break' => 0, 'refund_price' => $refund_price, 'finish_time' => time(), 'remark' => $reason, 'charge_amount' => $charge_amount));
        $Kh9vPMC = "部分充值，总面值：" . $allmian;
        $Kh9vPMD = $Kh9vPMC . '，成功面值：';
        $Kh9vPME = $Kh9vPMD . $charge_amount;
        $Kh9vPMF = $Kh9vPME . "，总金额:￥";
        $Kh9vPMG = $Kh9vPMF . $porder['total_price'];
        $Kh9vPMH = $Kh9vPMG . '，应退款：￥';
        $Kh9vPMI = $Kh9vPMH . $refund_price;
        Createlog::porderLog($porder['id'], $Kh9vPMI);
        $Kh9MC = C('AUTO_REFUND') == 1;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgx9w;
        goto Kh9ldMhx9w;
        Kh9eWjgx9w:
        $Kh9MD = C('PART_REG_REFUND') == 1;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9x9v;
        Kh9ldMhx9w:Kh9x9v:
        if ($Kh9ME) goto Kh9eWjgx9x;
        goto Kh9ldMhx9x;
        Kh9eWjgx9x:
        queue('app\\queue\\job\\Work@porderRefund', array('id' => $porder['id'], 'remark' => $remark, 'operator' => '系统'));
        goto Kh9x9u;
        Kh9ldMhx9x:Kh9x9u:
        Notification::rechargePart($porder['id']);
        $Kh9MC = C('PART_REG_REBATE') == 1;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgxaz;
        goto Kh9ldMhxaz;
        Kh9eWjgxaz:
        $Kh9vPMD = 1 - $compratio;
        $Kh9ME = $Kh9MC && self::rebate($porder['id'], $Kh9vPMD);
        goto Kh9x9y;
        Kh9ldMhxaz:Kh9x9y:
        return rjson(0, '操作成功');
    }

    public static function rechargeError($order_number, $remark)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('order_number' => $order_number, 'status' => array('in', '2')))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxa2;
        goto Kh9ldMhxa2;
        Kh9eWjgxa2:
        return rjson(1, '订单未找到');
        goto Kh9xa1;
        Kh9ldMhxa2:Kh9xa1:
        Createlog::porderLog($porder['id'], $remark);
        Createlog::porderLog($porder['id'], "接口充值异常|请人工与渠道方确认后手动操作订单");
        M('porder')->where(array('id' => $porder['id']))->setField(array('status' => 8));
        return rjson(0, '操作成功');
    }

    public static function getApiOrderNumber($order_number, $api_cur_index = 0, $api_cur_count = 0, $num = 1)
    {
        $Kh9MC = $order_number . 'A';
        $Kh9MD = $Kh9MC . $api_cur_count;
        $Kh9ME = $api_cur_index + 1;
        $Kh9MF = $Kh9MD . $Kh9ME;
        $Kh9MG = $Kh9MF . 'N';
        $Kh9MH = $Kh9MG . $num;
        return $Kh9MH;
    }

    public static function notification($porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t1 = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => array('in', '3,4,5,6,7,12,13')))->field('id,status')->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxa4;
        goto Kh9ldMhxa4;
        Kh9eWjgxa4:
        return rjson(1, '未查询到可回调订单');
        goto Kh9xa3;
        Kh9ldMhxa4:Kh9xa3:
        if (in_array($porder['status'], array(4))) goto Kh9eWjgxa6;
        goto Kh9ldMhxa6;
        Kh9eWjgxa6:
        unset($Kh9tIMC);
        $Kh9tIMC = Notification::rechargeSus($porder['id']);
        $res = $Kh9tIMC;
        goto Kh9xa5;
        Kh9ldMhxa6:
        if (in_array($porder['status'], array(5, 6, 7))) goto Kh9eWjgxa7;
        goto Kh9ldMhxa7;
        Kh9eWjgxa7:
        unset($Kh9tIMC);
        $Kh9tIMC = Notification::rechargeFail($porder['id']);
        $res = $Kh9tIMC;
        goto Kh9xa5;
        Kh9ldMhxa7:
        if (in_array($porder['status'], array(12, 13))) goto Kh9eWjgxa8;
        goto Kh9ldMhxa8;
        Kh9eWjgxa8:
        unset($Kh9tIMC);
        $Kh9tIMC = Notification::rechargePart($porder['id']);
        $res = $Kh9tIMC;
        goto Kh9xa5;
        Kh9ldMhxa8:
        if (in_array($porder['status'], array(3))) goto Kh9eWjgxa9;
        goto Kh9ldMhxa9;
        Kh9eWjgxa9:
        unset($Kh9tIMC);
        $Kh9tIMC = Notification::rechargeIng($porder['id']);
        $res = $Kh9tIMC;
        goto Kh9xa5;
        Kh9ldMhxa9:
        return rjson(1, '状态不可回调');
        Kh9xa5:
        unset($Kh9tIMC);
        $Kh9tIMC = microtime(true);
        $t2 = $Kh9tIMC;
        $Kh9vPvPMC = $t2 - $t1;
        $Kh9vPMD = "回调通知耗时：" . round($Kh9vPvPMC, 3);
        $Kh9vPME = $Kh9vPMD . 's';
        Createlog::porderLog($porder_id, $Kh9vPME);
        return $res;
    }

    public static function refund($order_id, $remark, $operator)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $order_id, 'status' => array('in', array(5, 12))))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxab;
        goto Kh9ldMhxab;
        Kh9eWjgxab:
        return rjson(1, '未查询到可退款订单！');
        goto Kh9xaa;
        Kh9ldMhxab:Kh9xaa:
        if ($porder['refund_time']) goto Kh9eWjgxad;
        goto Kh9ldMhxad;
        Kh9eWjgxad:
        Createlog::porderLog($porder['id'], "退款失败|订单已经操作过退款，不可再申请！");
        return rjson(1, '订单已经操作过退款！');
        goto Kh9xac;
        Kh9ldMhxad:Kh9xac:
        $Kh9MC = $porder['refund_price'] > 0;
        if ($Kh9MC) goto Kh9eWjgxaf;
        goto Kh9ldMhxaf;
        Kh9eWjgxaf:
        switch ($porder['pay_way']) {
            case PayWay::PAY_WAY_JSYS:
                unset($Kh9tIMC);
                $Kh9tIMC = PayWay::refund($porder['pay_way'], array('weixin_appid' => $porder['weixin_appid'], 'order_number' => $porder['order_number'], 'total_price' => $porder['total_price'], 'refund_price' => $porder['refund_price'], 'reason' => '充值失败退款', 'type' => 1));
                $ret = $Kh9tIMC;
                break 1;
            case PayWay::PAY_WAY_BLA:
                $Kh9vPMC = '[退款]给账号:' . $porder['mobile'];
                $Kh9vPMD = $Kh9vPMC . ',充值产品:';
                $Kh9vPME = $Kh9vPMD . $porder['title'];
                $Kh9vPMF = $Kh9vPME . "失败-退款，单号:";
                $Kh9vPMG = $Kh9vPMF . $porder['order_number'];
                unset($Kh9tIMH);
                $Kh9tIMH = Balance::revenue($porder['customer_id'], $porder['refund_price'], $Kh9vPMG, Balance::STYLE_REFUND, $operator);
                $ret = $Kh9tIMH;
                break 1;
            case PayWay::PAY_WAY_OFFL:
                unset($Kh9tIMC);
                $Kh9tIMC = rjson(0, '线下支付无需退款');
                $ret = $Kh9tIMC;
                break 1;
            case PayWay::PAY_WAY_H5YS:
                unset($Kh9tIMC);
                $Kh9tIMC = PayWay::refund($porder['pay_way'], array('weixin_appid' => $porder['weixin_appid'], 'order_number' => $porder['order_number'], 'total_price' => $porder['total_price'], 'refund_price' => $porder['refund_price'], 'reason' => '充值失败退款', 'type' => 2));
                $ret = $Kh9tIMC;
                break 1;
            case PayWay::PAY_WAY_ALIH5:
                unset($Kh9tIMC);
                $Kh9tIMC = PayWay::refund($porder['pay_way'], array('weixin_appid' => $porder['weixin_appid'], 'serial_number' => $porder['serial_number'], 'order_number' => $porder['order_number'], 'total_price' => $porder['total_price'], 'refund_price' => $porder['refund_price'], 'reason' => '充值失败退款', 'type' => 2));
                $ret = $Kh9tIMC;
                break 1;
            default:
                unset($Kh9tIMC);
                $Kh9tIMC = rjson(1, '不支持');
                $ret = $Kh9tIMC;
                break 1;
        }
        $Kh9vPMC = '退款结果|' . $ret['errmsg'];
        Createlog::porderLog($porder['id'], $Kh9vPMC);
        $Kh9MC = $ret['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxao;
        goto Kh9ldMhxao;
        Kh9eWjgxao:
        $Kh9vPMC = "退款失败|退款金额：" . $porder['refund_price'];
        $Kh9vPMD = $Kh9vPMC . "|";
        $Kh9vPME = $Kh9vPMD . $remark;
        Createlog::porderLog($porder['id'], $Kh9vPME);
        return rjson(1, $ret['errmsg']);
        goto Kh9xan;
        Kh9ldMhxao:Kh9xan:
        goto Kh9xae;
        Kh9ldMhxaf:Kh9xae:
        $Kh9MC = $porder['total_price'] == $porder['refund_price'];
        if ($Kh9MC) goto Kh9eWjgxaq;
        goto Kh9ldMhxaq;
        Kh9eWjgxaq:
        M('porder')->where(array('id' => $porder['id']))->setField(array('status' => 6));
        $Kh9vPMC = "退款成功|全额退款金额：" . $porder['refund_price'];
        $Kh9vPMD = $Kh9vPMC . "|";
        $Kh9vPME = $Kh9vPMD . $remark;
        Createlog::porderLog($porder['id'], $Kh9vPME);
        goto Kh9xap;
        Kh9ldMhxaq:
        M('porder')->where(array('id' => $porder['id']))->setField(array('status' => 13));
        $Kh9vPMC = "退款成功|部分退款金额：" . $porder['refund_price'];
        $Kh9vPMD = $Kh9vPMC . "|";
        $Kh9vPME = $Kh9vPMD . $remark;
        Createlog::porderLog($porder['id'], $Kh9vPME);
        Kh9xap:
        M('porder')->where(array('id' => $porder['id']))->setField(array('refund_time' => time()));
        self::h5AgentChildRefund($porder['id']);
        Notification::refundSus($porder['id']);
        return rjson(0, "退款成功");
    }

    public static function compute_rebate($porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => array('in', '1,2'), 'is_del' => 0))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxas;
        goto Kh9ldMhxas;
        Kh9eWjgxas:
        return rjson(1, '未找到订单');
        goto Kh9xar;
        Kh9ldMhxas:Kh9xar:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $porder['customer_id'], 'is_del' => 0, 'status' => 1))->find();
        $customer = $Kh9tIMC;
        $Kh9MC = !$customer;
        if ($Kh9MC) goto Kh9eWjgxau;
        goto Kh9ldMhxau;
        Kh9eWjgxau:
        return rjson(1, '用户未找到');
        goto Kh9xat;
        Kh9ldMhxau:Kh9xat:
        unset($Kh9tIMC);
        $Kh9tIMC = $customer['f_id'];
        $rebate_id = $Kh9tIMC;
        $Kh9MC = !$rebate_id;
        if ($Kh9MC) goto Kh9eWjgxaw;
        goto Kh9ldMhxaw;
        Kh9eWjgxaw:
        Createlog::porderLog($porder_id, '不返利,没有上级');
        return rjson(1, '无上级，无需返利');
        goto Kh9xav;
        Kh9ldMhxaw:Kh9xav:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $customer['f_id'], 'is_del' => 0, 'status' => 1))->find();
        $fcus = $Kh9tIMC;
        $Kh9MC = !$fcus;
        if ($Kh9MC) goto Kh9eWjgxay;
        goto Kh9ldMhxay;
        Kh9eWjgxay:
        Createlog::porderLog($porder_id, '不返利，返利上级信息未查询到');
        return rjson(1, '不返利，返利上级信息未查询到');
        goto Kh9xax;
        Kh9ldMhxay:Kh9xax:
        unset($Kh9tIMC);
        $Kh9tIMC = Product::computePrice($porder['product_id'], $customer['id']);
        $fdres1 = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = Product::computePrice($porder['product_id'], $fcus['id']);
        $fdres2 = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $fdres1['data']['price'];
        $prod1_price = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $fdres2['data']['price'];
        $prod2_price = $Kh9tIMC;
        $Kh9MC = $prod1_price - $prod2_price;
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $rebate_price = $Kh9tIMD;
        $Kh9MC = $rebate_price > 0;
        if ($Kh9MC) goto Kh9eWjgxb1;
        goto Kh9ldMhxb1;
        Kh9eWjgxb1:
        M('porder')->where(array('id' => $porder_id))->setField(array('rebate_id' => $rebate_id, 'rebate_price' => $rebate_price));
        $Kh9vPMC = '[1]计算返利ID：' . $rebate_id;
        $Kh9vPMD = $Kh9vPMC . '，返利金额:￥';
        $Kh9vPME = $Kh9vPMD . $rebate_price;
        Createlog::porderLog($porder_id, $Kh9vPME);
        goto Kh9xbz;
        Kh9ldMhxb1:
        $Kh9vPMC = '[1]不返利,计算出金额：' . $rebate_price;
        Createlog::porderLog($porder_id, $Kh9vPMC);
        $Kh9vPMC = '不返利,计算出金额：' . $rebate_price;
        return rjson(1, $Kh9vPMC);
        Kh9xbz:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $fcus['f_id'], 'is_del' => 0, 'status' => 1))->field('id')->find();
        $ffcus = $Kh9tIMC;
        if ($ffcus) goto Kh9eWjgxb3;
        goto Kh9ldMhxb3;
        Kh9eWjgxb3:
        unset($Kh9tIMC);
        $Kh9tIMC = Product::computePrice($porder['product_id'], $ffcus['id']);
        $fdres3 = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $fdres3['data']['price'];
        $prod3_price = $Kh9tIMC;
        $Kh9MC = $prod2_price - $prod3_price;
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $rebate_price2 = $Kh9tIMD;
        $Kh9MC = $rebate_price2 > 0;
        if ($Kh9MC) goto Kh9eWjgxb5;
        goto Kh9ldMhxb5;
        Kh9eWjgxb5:
        M('porder')->where(array('id' => $porder_id))->setField(array('rebate_id2' => $ffcus['id'], 'rebate_price2' => $rebate_price2));
        $Kh9vPMC = '[2]上上级计算返利ID：' . $ffcus['id'];
        $Kh9vPMD = $Kh9vPMC . '，返利金额:￥';
        $Kh9vPME = $Kh9vPMD . $rebate_price2;
        Createlog::porderLog($porder_id, $Kh9vPME);
        goto Kh9xb4;
        Kh9ldMhxb5:
        $Kh9vPMC = '[2]上上级不返利,计算出金额：' . $rebate_price2;
        Createlog::porderLog($porder_id, $Kh9vPMC);
        Kh9xb4:
        goto Kh9xb2;
        Kh9ldMhxb3:
        Createlog::porderLog($porder_id, '[2]上上级不返利,没有查到用户信息');
        Kh9xb2:
        return rjson(0, '返利设置成功');
    }

    public static function rebate($porder_id, $compratio = 1)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => array('in', '4,12,13'), 'rebate_id' => array('gt', 0), 'rebate_price' => array('gt', 0), 'is_del' => 0, 'is_rebate' => 0))->find();
        $porder = $Kh9tIMC;
        if ($porder) goto Kh9eWjgxb7;
        goto Kh9ldMhxb7;
        Kh9eWjgxb7:
        $Kh9MC = $porder['rebate_price'] * $compratio;
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $rebate_price = $Kh9tIMD;
        M('porder')->where(array('id' => $porder_id))->setField(array('is_rebate' => 1, 'rebate_time' => time(), 'rebate_price' => $rebate_price));
        $Kh9vPMC = "返利给上级用户[" . $porder['rebate_id'];
        $Kh9vPMD = $Kh9vPMC . "]，金额￥";
        $Kh9vPME = $Kh9vPMD . $rebate_price;
        $Kh9vPMF = $Kh9vPME . "";
        Createlog::porderLog($porder_id, $Kh9vPMF);
        $Kh9vPMC = '[1]用户充值返利，单号' . $porder['order_number'];
        Balance::revenue($porder['rebate_id'], $rebate_price, $Kh9vPMC, Balance::STYLE_REWARDS, '系统');
        goto Kh9xb6;
        Kh9ldMhxb7:Kh9xb6:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => array('in', '4,12,13'), 'rebate_id2' => array('gt', 0), 'rebate_price2' => array('gt', 0), 'is_del' => 0, 'is_rebate2' => 0))->find();
        $porder2 = $Kh9tIMC;
        if ($porder2) goto Kh9eWjgxb9;
        goto Kh9ldMhxb9;
        Kh9eWjgxb9:
        $Kh9MC = $porder2['rebate_price2'] * $compratio;
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $rebate_price2 = $Kh9tIMD;
        M('porder')->where(array('id' => $porder_id))->setField(array('is_rebate2' => 1, 'rebate_time' => time(), 'rebate_price2' => $rebate_price2));
        $Kh9vPMC = "返利给上上级用户[" . $porder2['rebate_id2'];
        $Kh9vPMD = $Kh9vPMC . "]，金额￥";
        $Kh9vPME = $Kh9vPMD . $rebate_price2;
        $Kh9vPMF = $Kh9vPME . "";
        Createlog::porderLog($porder_id, $Kh9vPMF);
        $Kh9vPMC = '[2]用户充值返利，单号' . $porder2['order_number'];
        Balance::revenue($porder2['rebate_id2'], $rebate_price2, $Kh9vPMC, Balance::STYLE_REWARDS, '系统');
        goto Kh9xb8;
        Kh9ldMhxb9:Kh9xb8:
        return rjson(0, '操作完成');
    }

    public static function agentExcelOrder($id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('agent_proder_excel')->where(array('status' => 2, 'id' => $id))->find();
        $item = $Kh9tIMC;
        $Kh9MC = !$item;
        if ($Kh9MC) goto Kh9eWjgxbb;
        goto Kh9ldMhxbb;
        Kh9eWjgxbb:
        return rjson(1, '订单不可推送');
        goto Kh9xba;
        Kh9ldMhxbb:Kh9xba:
        M('agent_proder_excel')->where(array('status' => 2, 'id' => $id))->setField(array('status' => 3));
        unset($Kh9tIMC);
        $Kh9tIMC = PorderModel::createOrder($item['mobile'], $item['product_id'], array('prov' => $item['area'], 'city' => $item['city'], 'ytype' => $item['ytype'], 'id_card_no' => $item['id_card_no']), $item['customer_id'], Client::CLIENT_AGA, '', $item['out_trade_num']);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxbd;
        goto Kh9ldMhxbd;
        Kh9eWjgxbd:
        M('agent_proder_excel')->where(array('id' => $item['id']))->setField(array('status' => 5, 'resmsg' => $res['errmsg']));
        $Kh9vPMC = '下单失败,' . $res['errmsg'];
        return rjson(1, $Kh9vPMC);
        goto Kh9xbc;
        Kh9ldMhxbd:Kh9xbc:
        unset($Kh9tIMC);
        $Kh9tIMC = $res['data'];
        $aid = $Kh9tIMC;
        self::compute_rebate($aid);
        Createlog::porderLog($aid, "代理后台批量导入下单成功");
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $aid, 'status' => 1))->field("id,order_number,mobile,product_id,total_price,create_time,guishu,title,out_trade_num")->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxbf;
        goto Kh9ldMhxbf;
        Kh9eWjgxbf:
        $Kh9vPMC = "该订单状态不可发起支付，状态码：" . $porder['status'];
        Createlog::porderLog($aid, $Kh9vPMC);
        return rjson(1, "该订单状态不可发起支付");
        goto Kh9xbe;
        Kh9ldMhxbf:Kh9xbe:
        $Kh9vPMC = "[支付]代理商后台为账号:" . $porder['mobile'];
        $Kh9vPMD = $Kh9vPMC . ",充值产品:";
        $Kh9vPME = $Kh9vPMD . $porder['title'];
        $Kh9vPMF = $Kh9vPME . "，单号:";
        $Kh9vPMG = $Kh9vPMF . $porder['order_number'];
        unset($Kh9tIMH);
        $Kh9tIMH = Balance::expend($item['customer_id'], $porder['total_price'], $Kh9vPMG, Balance::STYLE_ORDERS, '代理商_导入');
        $ret = $Kh9tIMH;
        $Kh9MC = $ret['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxbh;
        goto Kh9ldMhxbh;
        Kh9eWjgxbh:
        $Kh9vPMC = "代理商导入下单时支付失败，取消订单，原因：" . $ret['errmsg'];
        self::payFailCancelOrder($aid, $Kh9vPMC);
        M('agent_proder_excel')->where(array('id' => $item['id']))->setField(array('status' => 5, 'resmsg' => $ret['errmsg']));
        $Kh9vPMC = '下单支付失败,' . $res['errmsg'];
        return rjson(1, $Kh9vPMC);
        goto Kh9xbg;
        Kh9ldMhxbh:Kh9xbg:
        Createlog::porderLog($aid, "余额支付成功");
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $aid))->field("id,order_number")->find();
        $porder = $Kh9tIMC;
        M('agent_proder_excel')->where(array('id' => $item['id']))->setField(array('status' => 4, 'order_number' => $porder['order_number']));
        $Kh9MC = new Notify();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $noticy = $Kh9tIMD;
        $noticy->balance($porder['order_number']);
        return rjson(1, '下单成功');
    }

    public static function agentApiPayPorder($porder_id, $customer_id, $notify_url)
    {
        self::where(array('id' => $porder_id))->setField(array('notify_url' => $notify_url));
        self::compute_rebate($porder_id);
        Createlog::porderLog($porder_id, "代理API下单成功");
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => 1))->field("id,order_number,remark,mobile,product_id,total_price,create_time,guishu,title,out_trade_num")->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxbj;
        goto Kh9ldMhxbj;
        Kh9eWjgxbj:
        $Kh9vPMC = "该订单状态不可发起支付，状态码：" . $porder['status'];
        Createlog::porderLog($porder_id, $Kh9vPMC);
        return rjson(1, "该订单状态不可发起支付");
        goto Kh9xbi;
        Kh9ldMhxbj:Kh9xbi:
        $Kh9vPMC = "[支付]api为账号:" . $porder['mobile'];
        $Kh9vPMD = $Kh9vPMC . ",充值产品:";
        $Kh9vPME = $Kh9vPMD . $porder['title'];
        $Kh9vPMF = $Kh9vPME . "，单号:";
        $Kh9vPMG = $Kh9vPMF . $porder['order_number'];
        unset($Kh9tIMH);
        $Kh9tIMH = Balance::expend($customer_id, $porder['total_price'], $Kh9vPMG, Balance::STYLE_ORDERS, '用户自己api');
        $ret = $Kh9tIMH;
        $Kh9MC = $ret['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxbl;
        goto Kh9ldMhxbl;
        Kh9eWjgxbl:
        $Kh9vPMC = "代理商API下单时支付失败，取消订单，原因：" . $ret['errmsg'];
        self::payFailCancelOrder($porder_id, $Kh9vPMC);
        queue('app\\queue\\job\\Work@callFunc', array('class' => '\\app\\common\\library\\Notification', 'func' => 'rechargeFail', 'param' => $porder['id']), 10);
        return rjson($ret['errno'], $ret['errmsg']);
        goto Kh9xbk;
        Kh9ldMhxbl:Kh9xbk:
        Createlog::porderLog($porder_id, "余额支付成功");
        $Kh9MC = new Notify();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $noticy = $Kh9tIMD;
        $noticy->balance($porder['order_number']);
        return rjson(0, '操作成功');
    }

    public static function adminExcelOrder($id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => C('PORDER_EXCEL_CUSID'), 'is_del' => 0))->find();
        $cus = $Kh9tIMC;
        $Kh9MC = !$cus;
        if ($Kh9MC) goto Kh9eWjgxbn;
        goto Kh9ldMhxbn;
        Kh9eWjgxbn:
        return rjson(1, '未找到正确的导入用户ID,点击导入设置配置用户ID');
        goto Kh9xbm;
        Kh9ldMhxbn:Kh9xbm:
        unset($Kh9tIMC);
        $Kh9tIMC = M('proder_excel')->where(array('id' => $id, 'status' => 2))->find();
        $item = $Kh9tIMC;
        $Kh9MC = !$item;
        if ($Kh9MC) goto Kh9eWjgxbp;
        goto Kh9ldMhxbp;
        Kh9eWjgxbp:
        return rjson(1, '不可推送');
        goto Kh9xbo;
        Kh9ldMhxbp:Kh9xbo:
        M('proder_excel')->where(array('status' => 2, 'id' => $id))->setField(array('status' => 3));
        unset($Kh9tIMC);
        $Kh9tIMC = PorderModel::createOrder($item['mobile'], $item['product_id'], array('prov' => $item['area'], 'city' => $item['city'], 'ytype' => $item['ytype'], 'id_card_no' => $item['id_card_no']), $cus['id'], Client::CLIENT_ADM, '');
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxbr;
        goto Kh9ldMhxbr;
        Kh9eWjgxbr:
        M('proder_excel')->where(array('id' => $item['id']))->setField(array('status' => 5, 'resmsg' => $res['errmsg']));
        $Kh9vPMC = '下单失败,' . $res['errmsg'];
        return rjson(1, $Kh9vPMC);
        goto Kh9xbq;
        Kh9ldMhxbr:Kh9xbq:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $res['data']))->field("id,order_number")->find();
        $porder = $Kh9tIMC;
        Createlog::porderLog($porder['id'], "总后台导入下单");
        M('proder_excel')->where(array('id' => $item['id']))->setField(array('status' => 4, 'order_number' => $porder['order_number']));
        $Kh9MC = new Notify();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $noticy = $Kh9tIMD;
        $noticy->offline($porder['order_number']);
        return rjson('成功推送');
    }

    public static function h5AgentChildPay($porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => 2, 'is_apart' => 0, 'h5fxpay_price' => 0, 'client' => array('in', array(Client::CLIENT_WX, Client::CLIENT_H5))))->where('weixin_appid', 'not null')->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxbt;
        goto Kh9ldMhxbt;
        Kh9eWjgxbt:
        return rjson(500, '订单未找到');
        goto Kh9xbs;
        Kh9ldMhxbt:Kh9xbs:
        unset($Kh9tIMC);
        $Kh9tIMC = M('weixin')->where(array('appid' => $porder['weixin_appid'], 'type' => $porder['client'], 'is_del' => 0, 'customer_id' => array('gt', 0)))->find();
        $weixin = $Kh9tIMC;
        $Kh9MC = !$weixin;
        if ($Kh9MC) goto Kh9eWjgxbv;
        goto Kh9ldMhxbv;
        Kh9eWjgxbv:
        return rjson(500, '微信配置未找到');
        goto Kh9xbu;
        Kh9ldMhxbv:Kh9xbu:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $weixin['customer_id'], 'is_del' => 0))->find();
        $cus = $Kh9tIMC;
        $Kh9MC = !$cus;
        if ($Kh9MC) goto Kh9eWjgxbx;
        goto Kh9ldMhxbx;
        Kh9eWjgxbx:
        return rjson(1, '未找到用户信息');
        goto Kh9xbw;
        Kh9ldMhxbx:Kh9xbw:
        unset($Kh9tIMC);
        $Kh9tIMC = M('product')->where(array('id' => $porder['product_id']))->value('price');
        $baseprice = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = Product::computePrice($porder['product_id'], $cus['id']);
        $fdres = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = $fdres['data']['price'];
        $fd_price = $Kh9tIMC;
        $Kh9MC = $baseprice + $fd_price;
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $total_price = $Kh9tIMD;
        $Kh9vPMC = "[支付]H5代理端id:[" . $cus['id'];
        $Kh9vPMD = $Kh9vPMC . "]为账号:";
        $Kh9vPME = $Kh9vPMD . $porder['mobile'];
        $Kh9vPMF = $Kh9vPME . ",充值产品:";
        $Kh9vPMG = $Kh9vPMF . $porder['title'];
        $Kh9vPMH = $Kh9vPMG . "，单号:";
        $Kh9vPMI = $Kh9vPMH . $porder['order_number'];
        unset($Kh9tIMJ);
        $Kh9tIMJ = Balance::expend($cus['id'], $total_price, $Kh9vPMI, Balance::STYLE_ORDERS, 'H5代理的客户');
        $blret = $Kh9tIMJ;
        $Kh9MC = $blret['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxcz;
        goto Kh9ldMhxcz;
        Kh9eWjgxcz:
        return rjson(1, $blret['errmsg'], $blret['data']);
        goto Kh9xby;
        Kh9ldMhxcz:Kh9xby:
        M('porder')->where(array('id' => $porder_id))->setField(array('h5fxpay_price' => $total_price));
        $Kh9vPMC = "H5代理商id:[" . $cus['id'];
        $Kh9vPMD = $Kh9vPMC . "],为该订单支付费用￥";
        $Kh9vPME = $Kh9vPMD . $total_price;
        Createlog::porderLog($porder_id, $Kh9vPME);
        return rjson(0, 'H5代理商成功支付');
    }

    public static function h5AgentChildRefund($porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => array('in', '6,13'), 'client' => array('in', array(Client::CLIENT_WX, Client::CLIENT_H5)), 'h5fxpay_price' => array('gt', 0), 'is_apart' => array('in', array(0, 2))))->where('weixin_appid', 'not null')->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxc2;
        goto Kh9ldMhxc2;
        Kh9eWjgxc2:
        return rjson(500, '订单未找到');
        goto Kh9xc1;
        Kh9ldMhxc2:Kh9xc1:
        unset($Kh9tIMC);
        $Kh9tIMC = M('weixin')->where(array('appid' => $porder['weixin_appid'], 'type' => $porder['client'], 'is_del' => 0, 'customer_id' => array('gt', 0)))->find();
        $weixin = $Kh9tIMC;
        $Kh9MC = !$weixin;
        if ($Kh9MC) goto Kh9eWjgxc4;
        goto Kh9ldMhxc4;
        Kh9eWjgxc4:
        return rjson(500, '微信配置未找到');
        goto Kh9xc3;
        Kh9ldMhxc4:Kh9xc3:
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => $weixin['customer_id'], 'is_del' => 0))->find();
        $cus = $Kh9tIMC;
        $Kh9MC = !$cus;
        if ($Kh9MC) goto Kh9eWjgxc6;
        goto Kh9ldMhxc6;
        Kh9eWjgxc6:
        return rjson(1, '未找到用户信息');
        goto Kh9xc5;
        Kh9ldMhxc6:Kh9xc5:
        $Kh9MC = $porder['total_price'] <= 0;
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MI = !$Kh9ME;
        if ($Kh9MI) goto Kh9eWjgxcb;
        goto Kh9ldMhxcb;
        Kh9eWjgxcb:
        $Kh9MD = $porder['h5fxpay_price'] <= 0;
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9xca;
        Kh9ldMhxcb:Kh9xca:
        $Kh9MG = (bool)$Kh9ME;
        $Kh9MH = !$Kh9MG;
        if ($Kh9MH) goto Kh9eWjgxc9;
        goto Kh9ldMhxc9;
        Kh9eWjgxc9:
        $Kh9MF = $porder['refund_price'] <= 0;
        $Kh9MG = $Kh9ME || $Kh9MF;
        goto Kh9xc8;
        Kh9ldMhxc9:Kh9xc8:
        if ($Kh9MG) goto Kh9eWjgxcc;
        goto Kh9ldMhxcc;
        Kh9eWjgxcc:
        Createlog::porderLog($porder_id, "H5代理商无需退款，退款金额0");
        return rjson(1, 'H5代理商无需退款，退款金额0');
        goto Kh9xc7;
        Kh9ldMhxcc:Kh9xc7:
        $Kh9vPMC = $porder['refund_price'] / $porder['total_price'];
        $Kh9vPMD = $Kh9vPMC * $porder['h5fxpay_price'];
        unset($Kh9tIME);
        $Kh9tIME = round($Kh9vPMD, 2);
        $refund_price = $Kh9tIME;
        $Kh9vPMC = "[退款]H5代理端id:[" . $cus['id'];
        $Kh9vPMD = $Kh9vPMC . "]为账号:";
        $Kh9vPME = $Kh9vPMD . $porder['mobile'];
        $Kh9vPMF = $Kh9vPME . ",充值产品:";
        $Kh9vPMG = $Kh9vPMF . $porder['title'];
        $Kh9vPMH = $Kh9vPMG . "失败退款，单号:";
        $Kh9vPMI = $Kh9vPMH . $porder['order_number'];
        unset($Kh9tIMJ);
        $Kh9tIMJ = Balance::revenue($cus['id'], $refund_price, $Kh9vPMI, Balance::STYLE_REFUND, '系统');
        $blret = $Kh9tIMJ;
        $Kh9MC = $blret['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxce;
        goto Kh9ldMhxce;
        Kh9eWjgxce:
        $Kh9vPMC = "订单退款到到H5代理商id:[" . $cus['id'];
        $Kh9vPMD = $Kh9vPMC . "]失败，请人工处理,金额￥";
        $Kh9vPME = $Kh9vPMD . $refund_price;
        Createlog::porderLog($porder_id, $Kh9vPME);
        return rjson(1, $blret['errmsg'], $blret['data']);
        goto Kh9xcd;
        Kh9ldMhxce:Kh9xcd:
        $Kh9vPMC = "订单退款到到H5代理商id:[" . $cus['id'];
        $Kh9vPMD = $Kh9vPMC . "]成功,金额￥";
        $Kh9vPME = $Kh9vPMD . $refund_price;
        Createlog::porderLog($porder_id, $Kh9vPME);
        return rjson(0, 'H5代理商退款成功');
    }

    public static function clientApiPayPorder($porder_id, $customer_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id))->field("id,order_number,remark,mobile,product_id,total_price,create_time,guishu,title,out_trade_num")->find();
        $porder = $Kh9tIMC;
        $Kh9vPMC = "[支付]客户端为账号:" . $porder['mobile'];
        $Kh9vPMD = $Kh9vPMC . ",充值产品:";
        $Kh9vPME = $Kh9vPMD . $porder['title'];
        $Kh9vPMF = $Kh9vPME . "，单号";
        $Kh9vPMG = $Kh9vPMF . $porder['order_number'];
        unset($Kh9tIMH);
        $Kh9tIMH = Balance::expend($customer_id, $porder['total_price'], $Kh9vPMG, Balance::STYLE_ORDERS, '客户端用户');
        $ret = $Kh9tIMH;
        $Kh9MC = $ret['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxcg;
        goto Kh9ldMhxcg;
        Kh9eWjgxcg:
        $Kh9vPMC = '余额支付失败，' . $ret['errmsg'];
        Createlog::porderLog($porder_id, $Kh9vPMC);
        return rjson($ret['errno'], $ret['errmsg']);
        goto Kh9xcf;
        Kh9ldMhxcg:Kh9xcf:
        Createlog::porderLog($porder_id, "余额支付成功");
        $Kh9MC = new Notify();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $noticy = $Kh9tIMD;
        $noticy->balance($porder['order_number']);
        return rjson(0, '支付成功');
    }

    public static function jinDongOrder($mobile, $product_id, $order_sn, $notify_url)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => C('JDCONFIG.userid'), 'is_del' => 0))->find();
        $cus = $Kh9tIMC;
        $Kh9MC = !$cus;
        if ($Kh9MC) goto Kh9eWjgxci;
        goto Kh9ldMhxci;
        Kh9eWjgxci:
        return rjson(1, '未找到正确的导入用户ID,点击导入设置配置用户ID');
        goto Kh9xch;
        Kh9ldMhxci:Kh9xch:
        unset($Kh9tIMC);
        $Kh9tIMC = PorderModel::createOrder($mobile, $product_id, array('prov' => '', 'city' => '', 'ytype' => 0, 'id_card_no' => ''), $cus['id'], Client::CLIENT_ADM, '', $order_sn);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxck;
        goto Kh9ldMhxck;
        Kh9eWjgxck:
        $Kh9vPMC = '下单失败,' . $res['errmsg'];
        return rjson($res['errno'], $Kh9vPMC, $res['data']);
        goto Kh9xcj;
        Kh9ldMhxck:Kh9xcj:
        M('porder')->where(array('id' => $res['data']))->setField(array('notify_url' => $notify_url));
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $res['data']))->field("id,order_number")->find();
        $porder = $Kh9tIMC;
        Createlog::porderLog($porder['id'], "京东下单成功");
        $Kh9MC = new Notify();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $noticy = $Kh9tIMD;
        $noticy->offline($porder['order_number']);
        return rjson(0, '成功推送', $porder);
    }

    public static function kuaiShouOrder($mobile, $product_id, $order_sn, $biztype, $amount)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('customer')->where(array('id' => C('KSCONFIG.userid'), 'is_del' => 0))->find();
        $cus = $Kh9tIMC;
        $Kh9MC = !$cus;
        if ($Kh9MC) goto Kh9eWjgxcm;
        goto Kh9ldMhxcm;
        Kh9eWjgxcm:
        return rjson(1, '未找到正确的导入用户ID,点击导入设置配置用户ID');
        goto Kh9xcl;
        Kh9ldMhxcm:Kh9xcl:
        unset($Kh9tIMC);
        $Kh9tIMC = PorderModel::createOrder($mobile, $product_id, array('prov' => '', 'city' => '', 'ytype' => 0, 'id_card_no' => ''), $cus['id'], Client::CLIENT_ADM, '', $order_sn);
        $res = $Kh9tIMC;
        $Kh9MC = $res['errno'] != 0;
        if ($Kh9MC) goto Kh9eWjgxco;
        goto Kh9ldMhxco;
        Kh9eWjgxco:
        $Kh9vPMC = '下单失败,' . $res['errmsg'];
        return rjson($res['errno'], $Kh9vPMC, $res['data']);
        goto Kh9xcn;
        Kh9ldMhxco:Kh9xcn:
        M('porder')->where(array('id' => $res['data']))->setField(array('kuaishou_biztype' => $biztype, 'kuaishou_amount' => $amount));
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $res['data']))->field("id,order_number")->find();
        $porder = $Kh9tIMC;
        Createlog::porderLog($porder['id'], "快手下单成功");
        $Kh9MC = new Notify();
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $noticy = $Kh9tIMD;
        $noticy->offline($porder['order_number']);
        return rjson(0, '成功推送', $porder);
    }

    public static function apinotify_log($api, $out_trade_no, $data)
    {
        $Kh9MC = !$out_trade_no;
        if ($Kh9MC) goto Kh9eWjgxcq;
        goto Kh9ldMhxcq;
        Kh9eWjgxcq:
        return false;
        goto Kh9xcp;
        Kh9ldMhxcq:Kh9xcp:
        unset($Kh9tIMC);
        $Kh9tIMC = M('apinotify_log')->where(array('api' => $api, 'out_trade_no' => $out_trade_no))->find();
        $log = $Kh9tIMC;
        M('apinotify_log')->insertGetId(array('api' => $api, 'out_trade_no' => $out_trade_no, 'data' => var_export($data, true), 'create_time' => time()));
        if ($log) goto Kh9eWjgxcs;
        goto Kh9ldMhxcs;
        Kh9eWjgxcs:
        return false;
        goto Kh9xcr;
        Kh9ldMhxcs:
        return true;
        Kh9xcr:
    }

    public static function childFinishTrigger($porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => array('in', array(4, 5, 6)), 'is_del' => 0, 'is_apart' => 1))->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MF = !$Kh9ME;
        if ($Kh9MF) goto Kh9eWjgxcv;
        goto Kh9ldMhxcv;
        Kh9eWjgxcv:
        $Kh9MD = !$porder['apart_order_number'];
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9xcu;
        Kh9ldMhxcv:Kh9xcu:
        if ($Kh9ME) goto Kh9eWjgxcw;
        goto Kh9ldMhxcw;
        Kh9eWjgxcw:
        return rjson(1, '未找到订单');
        goto Kh9xct;
        Kh9ldMhxcw:Kh9xct:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('order_number' => $porder['apart_order_number'], 'status' => 11, 'is_del' => 0))->find();
        $morder = $Kh9tIMC;
        $Kh9MC = !$morder;
        if ($Kh9MC) goto Kh9eWjgxcy;
        goto Kh9ldMhxcy;
        Kh9eWjgxcy:
        return rjson(1, '没有主订单');
        goto Kh9xcx;
        Kh9ldMhxcy:Kh9xcx:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('apart_order_number' => $porder['apart_order_number'], 'status' => array('not in', array(4, 5, 6)), 'is_del' => 0))->count();
        $othct = $Kh9tIMC;
        $Kh9MC = $othct > 0;
        if ($Kh9MC) goto Kh9eWjgxd1;
        goto Kh9ldMhxd1;
        Kh9eWjgxd1:
        return rjson(1, '无需变化状态');
        goto Kh9xdz;
        Kh9ldMhxd1:Kh9xdz:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('apart_order_number' => $porder['apart_order_number'], 'status' => 4, 'is_del' => 0))->count();
        $susct = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('apart_order_number' => $porder['apart_order_number'], 'status' => array('in', array(5, 6)), 'is_del' => 0))->count();
        $failct = $Kh9tIMC;
        $Kh9MC = $susct > 0;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgxd4;
        goto Kh9ldMhxd4;
        Kh9eWjgxd4:
        $Kh9MD = $failct == 0;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9xd3;
        Kh9ldMhxd4:Kh9xd3:
        if ($Kh9ME) goto Kh9eWjgxd5;
        goto Kh9ldMhxd5;
        Kh9eWjgxd5:
        M('porder')->where(['order_number' => $porder['apart_order_number']])->setField(['remark' => '充值完成']);
        return self::rechargeSus($morder['order_number'], '所有子单都已充值成功');
        goto Kh9xd2;
        Kh9ldMhxd5:Kh9xd2:
        $Kh9MC = $failct > 0;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgxd8;
        goto Kh9ldMhxd8;
        Kh9eWjgxd8:
        $Kh9MD = $susct == 0;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9xd7;
        Kh9ldMhxd8:Kh9xd7:
        if ($Kh9ME) goto Kh9eWjgxd9;
        goto Kh9ldMhxd9;
        Kh9eWjgxd9:
        $Kh9MC = intval(C('ODAPI_FAIL_STYLE')) == 3;
        if ($Kh9MC) goto Kh9eWjgxdb;
        goto Kh9ldMhxdb;
        Kh9eWjgxdb:
        $Kh9MD = M('product')->where(array('id' => $morder['product_id']))->value('api_fail_style');
        goto Kh9xda;
        Kh9ldMhxdb:
        $Kh9MD = intval(C('ODAPI_FAIL_STYLE'));
        Kh9xda:
        unset($Kh9tIME);
        $Kh9tIME = $Kh9MD;
        $apifailstyle = $Kh9tIME;
        $Kh9MC = $apifailstyle == 2;
        $Kh9ME = (bool)$Kh9MC;
        $Kh9MF = !$Kh9ME;
        if ($Kh9MF) goto Kh9eWjgxde;
        goto Kh9ldMhxde;
        Kh9eWjgxde:
        $Kh9MD = $morder['apply_break'] == 1;
        $Kh9ME = $Kh9MC || $Kh9MD;
        goto Kh9xdd;
        Kh9ldMhxde:Kh9xdd:
        if ($Kh9ME) goto Kh9eWjgxdf;
        goto Kh9ldMhxdf;
        Kh9eWjgxdf:
        M('porder')->where(array('id' => $morder['id']))->setField(array('status' => 10, 'apply_break' => 0));
        Createlog::porderLog($morder['id'], "所有子单都已充值失败,订单到压单状态（压单功能生效，该订单可以手动再次提交接口，如果不想使用此功能，请到 系统->网站设置->用户配置->订单api失败后处理方式 选项 改成:直接失败）");
        return rjson(0, '处理成功');
        goto Kh9xdc;
        Kh9ldMhxdf:
        return self::rechargeFailDo($morder['order_number'], '所有子单都已充值失败');
        Kh9xdc:
        goto Kh9xd6;
        Kh9ldMhxd9:Kh9xd6:
        $Kh9MC = $failct > 0;
        $Kh9ME = (bool)$Kh9MC;
        if ($Kh9ME) goto Kh9eWjgxdi;
        goto Kh9ldMhxdi;
        Kh9eWjgxdi:
        $Kh9MD = $susct > 0;
        $Kh9ME = $Kh9MC && $Kh9MD;
        goto Kh9xdh;
        Kh9ldMhxdi:Kh9xdh:
        if ($Kh9ME) goto Kh9eWjgxdj;
        goto Kh9ldMhxdj;
        Kh9eWjgxdj:
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('apart_order_number' => $porder['apart_order_number'], 'status' => 4, 'is_del' => 0))->select();
        $susmzs = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $susmz = $Kh9tIMC;
        foreach ($susmzs as $porders) {
            $Kh9MC = $susmz + floatval(preg_replace('/\\D/', '', $porders['product_name']));
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $susmz = $Kh9tIMD;
        }
        $Kh9vPvPMC = '[结束]子单部分充值成功：' . $susmz;
        M('porder')->where(['order_number' => $porder['apart_order_number']])->setField(["status" => 9, 'remark' => $Kh9vPvPMC]);
        return rjson(0, '操作成功');
        goto Kh9xdg;
        Kh9ldMhxdj:Kh9xdg:
        return rjson(0, '处理完成');
    }

    public static function delayTimeOrderSub()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('status' => 2, 'api_open' => 1, 'api_cur_index' => -1, 'api_cur_count' => 0, 'delay_time' => array('between', array(1, time()))))->field('id,delay_time')->select();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxdl;
        goto Kh9ldMhxdl;
        Kh9eWjgxdl:
        return rjson(1, '没有需要处理的延时api订单');
        goto Kh9xdk;
        Kh9ldMhxdl:Kh9xdk:
        foreach ($porder as $order) {
            M('porder')->where(array('id' => $order['id']))->setField(array('delay_time' => 0));
            $Kh9vPMC = '延时订单开始执行提交api,到期时间：' . time_format($order['delay_time']);
            Createlog::porderLog($order['id'], $Kh9vPMC);
            queue('app\\queue\\job\\Work@porderSubApi', $order['id']);
        }
        return rjson(0, '提交成功');
    }

    public static function applyCancelOrder($ids, $operator)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => array('in', $ids), 'apply_refund' => 0, 'status' => array('in', '2,3,10')))->select();
        $porders = $Kh9tIMC;
        $Kh9MC = !$porders;
        if ($Kh9MC) goto Kh9eWjgxdn;
        goto Kh9ldMhxdn;
        Kh9eWjgxdn:
        return rjson(1, '订单不可申请撤单');
        goto Kh9xdm;
        Kh9ldMhxdn:Kh9xdm:
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $counts = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = '';
        $errmsg = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = '';
        $signtext = $Kh9tIMC;
        foreach ($porders as $porder) {
            if (in_array($porder['status'], array(3))) goto Kh9eWjgxdp;
            goto Kh9ldMhxdp;
            Kh9eWjgxdp:
            $Kh9vPMC = "为订单申请退单，|申请人：" . $operator;
            Createlog::porderLog($porder['id'], $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = self::cancelApiOrder($porder['id']);
            $res = $Kh9tIMC;
            $Kh9MC = $res['errno'] == 0;
            if ($Kh9MC) goto Kh9eWjgxdr;
            goto Kh9ldMhxdr;
            Kh9eWjgxdr:
            $Kh9vPMC = "订单API申请取消成功|返回：" . var_export($res['data'], true);
            Createlog::porderLog($porder['id'], $Kh9vPMC);
            $Kh9vPMC = "取消订单|操作人：" . $operator;
            self::rechargeFailDo($porder['order_number'], $Kh9vPMC);
            goto Kh9xdq;
            Kh9ldMhxdr:
            $Kh9vPMC = "订单API申请取消失败|返回：" . var_export($res['data'], true);
            Createlog::porderLog($porder['id'], $Kh9vPMC);
            Createlog::porderLog($porder['id'], "订单进入退单等待期，不再提交后续api|该订单不会立马失败，当前api失败回调后订单才会自动失败，如果api回调充值成功，订单依然会成功");
            M('porder')->where(array('id' => $porder['id']))->setField(array('apply_refund' => 1));
            Kh9xdq:
            if ($signtext) goto Kh9eWjgxdt;
            goto Kh9ldMhxdt;
            Kh9eWjgxdt:
            $signtext = $signtext . ',';
            $Kh9nWMC = $signtext;
            goto Kh9xds;
            Kh9ldMhxdt:Kh9xds:
            $signtext = $signtext . $porder['api_order_number'];
            $Kh9nWMC = $signtext;
            $Kh9oB220 = $counts;
            $Kh9oB221 = $counts + 1;
            $counts = $Kh9oB221;
            goto Kh9xdo;
            Kh9ldMhxdp:
            if (in_array($porder['status'], array(2, 10))) goto Kh9eWjgxdv;
            goto Kh9ldMhxdv;
            Kh9eWjgxdv:
            $Kh9MC = $porder['status'] == 2;
            $Kh9ME = (bool)$Kh9MC;
            if ($Kh9ME) goto Kh9eWjgxdy;
            goto Kh9ldMhxdy;
            Kh9eWjgxdy:
            $Kh9MD = $porder['api_open'] == 1;
            $Kh9ME = $Kh9MC && $Kh9MD;
            goto Kh9xdx;
            Kh9ldMhxdy:Kh9xdx:
            if ($Kh9ME) goto Kh9eWjgxez;
            goto Kh9ldMhxez;
            Kh9eWjgxez:
            $Kh9MC = "订单" . $porder['order_number'];
            $Kh9MD = $Kh9MC . "正在提交，暂时不可取消；";
            $errmsg = $errmsg . $Kh9MD;
            $Kh9nWME = $errmsg;
            goto Kh9xdw;
            Kh9ldMhxez:Kh9xdw:
            $Kh9vPMC = "取消订单|操作人：" . $operator;
            self::rechargeFailDo($porder['order_number'], $Kh9vPMC);
            if ($signtext) goto Kh9eWjgxe2;
            goto Kh9ldMhxe2;
            Kh9eWjgxe2:
            $signtext = $signtext . ',';
            $Kh9nWMC = $signtext;
            goto Kh9xe1;
            Kh9ldMhxe2:Kh9xe1:
            $signtext = $signtext . $porder['api_order_number'];
            $Kh9nWMC = $signtext;
            $Kh9oB221 = $counts;
            $Kh9oB222 = $counts + 1;
            $counts = $Kh9oB222;
            goto Kh9xdu;
            Kh9ldMhxdv:Kh9xdu:Kh9xdo:
        }
        $Kh9MC = $counts == 0;
        if ($Kh9MC) goto Kh9eWjgxe4;
        goto Kh9ldMhxe4;
        Kh9eWjgxe4:
        return rjson(1, $errmsg);
        goto Kh9xe3;
        Kh9ldMhxe4:Kh9xe3:
        self::removeapi($signtext, $operator);
        $Kh9vPMC = "成功操作" . $counts;
        $Kh9vPMD = $Kh9vPMC . "条";
        return rjson(0, $Kh9vPMD);
    }

    public static function applyCancelOrderapi($ids, $operator)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => array('in', $ids), 'apply_refund' => 0, 'status' => array('in', '2,3,10')))->select();
        $porders = $Kh9tIMC;
        $Kh9MC = !$porders;
        if ($Kh9MC) goto Kh9eWjgxe6;
        goto Kh9ldMhxe6;
        Kh9eWjgxe6:
        return rjson(1, '订单不可申请撤单');
        goto Kh9xe5;
        Kh9ldMhxe6:Kh9xe5:
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $counts = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = '';
        $errmsg = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = '';
        $signtext = $Kh9tIMC;
        foreach ($porders as $porder) {
            if (in_array($porder['status'], array(3))) goto Kh9eWjgxe8;
            goto Kh9ldMhxe8;
            Kh9eWjgxe8:
            $Kh9vPMC = "【API接口】为订单申请退单，|申请人ID：" . $operator;
            Createlog::porderLog($porder['id'], $Kh9vPMC);
            unset($Kh9tIMC);
            $Kh9tIMC = self::cancelApiOrder($porder['id']);
            $res = $Kh9tIMC;
            $Kh9MC = $res['errno'] == 0;
            if ($Kh9MC) goto Kh9eWjgxea;
            goto Kh9ldMhxea;
            Kh9eWjgxea:
            $Kh9vPMC = "订单API申请取消成功|返回：" . var_export($res['data'], true);
            Createlog::porderLog($porder['id'], $Kh9vPMC);
            $Kh9vPMC = "取消订单|操作人ID：" . $operator;
            self::rechargeFailDo($porder['order_number'], $Kh9vPMC);
            goto Kh9xe9;
            Kh9ldMhxea:
            $Kh9vPMC = "订单API申请取消失败|返回：" . var_export($res['data'], true);
            Createlog::porderLog($porder['id'], $Kh9vPMC);
            Createlog::porderLog($porder['id'], "订单进入退单等待期，不再提交后续api|该订单不会立马失败，当前api失败回调后订单才会自动失败，如果api回调充值成功，订单依然会成功");
            M('porder')->where(array('id' => $porder['id']))->setField(array('apply_refund' => 1));
            Kh9xe9:
            if ($signtext) goto Kh9eWjgxec;
            goto Kh9ldMhxec;
            Kh9eWjgxec:
            $signtext = $signtext . ',';
            $Kh9nWMC = $signtext;
            goto Kh9xeb;
            Kh9ldMhxec:Kh9xeb:
            $signtext = $signtext . $porder['api_order_number'];
            $Kh9nWMC = $signtext;
            $Kh9oB222 = $counts;
            $Kh9oB223 = $counts + 1;
            $counts = $Kh9oB223;
            goto Kh9xe7;
            Kh9ldMhxe8:
            if (in_array($porder['status'], array(2, 10))) goto Kh9eWjgxee;
            goto Kh9ldMhxee;
            Kh9eWjgxee:
            $Kh9MC = $porder['status'] == 2;
            $Kh9ME = (bool)$Kh9MC;
            if ($Kh9ME) goto Kh9eWjgxeh;
            goto Kh9ldMhxeh;
            Kh9eWjgxeh:
            $Kh9MD = $porder['api_open'] == 1;
            $Kh9ME = $Kh9MC && $Kh9MD;
            goto Kh9xeg;
            Kh9ldMhxeh:Kh9xeg:
            if ($Kh9ME) goto Kh9eWjgxei;
            goto Kh9ldMhxei;
            Kh9eWjgxei:
            $Kh9MC = "订单" . $porder['order_number'];
            $Kh9MD = $Kh9MC . "正在提交，暂时不可取消；";
            $errmsg = $errmsg . $Kh9MD;
            $Kh9nWME = $errmsg;
            goto Kh9xef;
            Kh9ldMhxei:Kh9xef:
            $Kh9vPMC = "取消订单|操作人ID：" . $operator;
            self::rechargeFailDo($porder['order_number'], $Kh9vPMC);
            if ($signtext) goto Kh9eWjgxek;
            goto Kh9ldMhxek;
            Kh9eWjgxek:
            $signtext = $signtext . ',';
            $Kh9nWMC = $signtext;
            goto Kh9xej;
            Kh9ldMhxek:Kh9xej:
            $signtext = $signtext . $porder['api_order_number'];
            $Kh9nWMC = $signtext;
            $Kh9oB223 = $counts;
            $Kh9oB224 = $counts + 1;
            $counts = $Kh9oB224;
            goto Kh9xed;
            Kh9ldMhxee:Kh9xed:Kh9xe7:
        }
        $Kh9MC = $counts == 0;
        if ($Kh9MC) goto Kh9eWjgxem;
        goto Kh9ldMhxem;
        Kh9eWjgxem:
        return rjson(1, $errmsg);
        goto Kh9xel;
        Kh9ldMhxem:Kh9xel:
        self::removeapi($signtext, $operator);
        $Kh9vPMC = "成功申请撤单" . $counts;
        $Kh9vPMD = $Kh9vPMC . "条";
        return rjson(0, $Kh9vPMD);
    }

    public static function removeapi($api_order_numbers, $userid)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder p')->join('reapi r', 'r.id=p.api_cur_id')->where(['r.callapi' => 'Yuanren', 'p.api_order_number' => array('in', $api_order_numbers), 'apply_refund' => 1])->order('p.pegging_time asc ,p.create_time asc')->field('p.id,p.mobile,p.api_order_number,p.api_trade_num,p.api_cur_param_id,p.api_cur_id,r.param1,r.param2,r.param4')->select();
        $porders = $Kh9tIMC;
        foreach ($porders as $k => $order) {
            unset($Kh9tIMC);
            $Kh9tIMC = ["userid" => $order['param1'], "out_trade_nums" => $order['api_order_number']];
            $data = $Kh9tIMC;
            ksort($data);
            $Kh9MC = http_build_query($data) . '&apikey=';
            $Kh9MD = $Kh9MC . $order['param2'];
            unset($Kh9tIME);
            $Kh9tIME = $Kh9MD;
            $sign_str = $Kh9tIME;
            unset($Kh9tIMC);
            $Kh9tIMC = urldecode($sign_str);
            $sign_str = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = strtoupper(md5($sign_str));
            $data['sign'] = $Kh9tIMC;
            $Kh9vPMC = $order['param4'] . 'index/remove';
            self::http_get($Kh9vPMC, $data);
        }
    }

    public static function apiSelfPrice()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('product p')->join('reapi_param r', 'r.id=p.reapi_param_id')->where(array('p.is_jiage' => 1, 'p.is_del' => 0, 'p.added' => 1))->field('p.id,p.reapi_id,p.reapi_param_id,p.price,r.param1,r.param2,r.param3,r.param4,p.added')->select();
        $porders = $Kh9tIMC;
        foreach ($porders as $k => $order) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('reapi')->where(array('id' => $order['reapi_id']))->find();
            $config = $Kh9tIMC;
            $Kh9MC = 'Recharge\\' . ucfirst($config['callapi']);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $classname = $Kh9tIMD;
            $Kh9MC = !class_exists($classname);
            if ($Kh9MC) goto Kh9eWjgxeo;
            goto Kh9ldMhxeo;
            Kh9eWjgxeo:
            continue 1;
            goto Kh9xen;
            Kh9ldMhxeo:Kh9xen:
            $Kh9MC = new $classname($config);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $model = $Kh9tIMD;
            $Kh9MC = !method_exists($model, 'selfprice');
            if ($Kh9MC) goto Kh9eWjgxeq;
            goto Kh9ldMhxeq;
            Kh9eWjgxeq:
            continue 1;
            goto Kh9xep;
            Kh9ldMhxeq:Kh9xep:
            $model->selfprice($order);
            sleep(2);
        }
    }

    public static function apiSelfUp()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('product p')->join('reapi_param r', 'r.id=p.reapi_param_id')->where(array('p.is_up' => 1, 'p.is_del' => 0))->field('p.id,p.reapi_id,p.reapi_param_id,p.price,r.param1,r.param2,r.param3,r.param4,p.added')->select();
        $porders = $Kh9tIMC;
        foreach ($porders as $k => $order) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('reapi')->where(array('id' => $order['reapi_id']))->find();
            $config = $Kh9tIMC;
            $Kh9MC = 'Recharge\\' . ucfirst($config['callapi']);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $classname = $Kh9tIMD;
            $Kh9MC = !class_exists($classname);
            if ($Kh9MC) goto Kh9eWjgxes;
            goto Kh9ldMhxes;
            Kh9eWjgxes:
            continue 1;
            goto Kh9xer;
            Kh9ldMhxes:Kh9xer:
            $Kh9MC = new $classname($config);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $model = $Kh9tIMD;
            $Kh9MC = !method_exists($model, 'selfup');
            if ($Kh9MC) goto Kh9eWjgxeu;
            goto Kh9ldMhxeu;
            Kh9eWjgxeu:
            continue 1;
            goto Kh9xet;
            Kh9ldMhxeu:Kh9xet:
            $model->selfup($order);
            sleep(2);
        }
    }

    public static function apibalance($reapiid)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('reapi')->where(array('id' => $reapiid))->find();
        $config = $Kh9tIMC;
        $Kh9MC = 'Recharge\\' . ucfirst($config['callapi']);
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $classname = $Kh9tIMD;
        $Kh9MC = !class_exists($classname);
        if ($Kh9MC) goto Kh9eWjgxew;
        goto Kh9ldMhxew;
        Kh9eWjgxew:
        return rjson(1, '查询失败，未找到该接口', '查询失败，未找到该接口');
        goto Kh9xev;
        Kh9ldMhxew:Kh9xev:
        $Kh9MC = new $classname($config);
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $model = $Kh9tIMD;
        $Kh9MC = !method_exists($model, 'balance');
        if ($Kh9MC) goto Kh9eWjgxey;
        goto Kh9ldMhxey;
        Kh9eWjgxey:
        return rjson(1, '查询失败，该接口不支持余额查询', '查询失败，该接口不支持余额查询');
        goto Kh9xex;
        Kh9ldMhxey:Kh9xex:
        unset($Kh9tIMC);
        $Kh9tIMC = $model->balance();
        $res = $Kh9tIMC;
        $Kh9MC = $res != '';
        if ($Kh9MC) goto Kh9eWjgxf1;
        goto Kh9ldMhxf1;
        Kh9eWjgxf1:
        return rjson(0, '查询成功', $res);
        goto Kh9xfz;
        Kh9ldMhxf1:
        return rjson(1, '查询失败', '查询失败');
        Kh9xfz:
    }

    public static function applyBreakOrder($ids, $operator)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => array('in', $ids), 'apply_refund' => 0, 'apply_break' => 0, 'status' => array('in', '3')))->select();
        $porders = $Kh9tIMC;
        $Kh9MC = !$porders;
        if ($Kh9MC) goto Kh9eWjgxf3;
        goto Kh9ldMhxf3;
        Kh9eWjgxf3:
        return rjson(1, '订单不可申请中断');
        goto Kh9xf2;
        Kh9ldMhxf3:Kh9xf2:
        unset($Kh9tIMC);
        $Kh9tIMC = 0;
        $counts = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = '';
        $errmsg = $Kh9tIMC;
        foreach ($porders as $porder) {
            $Kh9vPMC = "订单进入申请中断状态，不再提交后续api|该订单不会立马失败，当前api失败回调后订单才会自动变成压单中，如果api回调充值成功，订单依然会成功|" . $operator;
            Createlog::porderLog($porder['id'], $Kh9vPMC);
            M('porder')->where(array('id' => $porder['id']))->setField(array('apply_break' => 1));
            $Kh9oB224 = $counts;
            $Kh9oB225 = $counts + 1;
            $counts = $Kh9oB225;
        }
        $Kh9MC = $counts == 0;
        if ($Kh9MC) goto Kh9eWjgxf5;
        goto Kh9ldMhxf5;
        Kh9eWjgxf5:
        return rjson(1, $errmsg);
        goto Kh9xf4;
        Kh9ldMhxf5:Kh9xf4:
        $Kh9vPMC = "成功操作" . $counts;
        $Kh9vPMD = $Kh9vPMC . "条";
        return rjson(0, $Kh9vPMD);
    }

    public static function timeOutCancelOrder()
    {
        $Kh9vPvPvPMC = 60 * 30;
        $Kh9vPvPvPMD = time() - $Kh9vPvPvPMC;
        unset($Kh9tIME);
        $Kh9tIME = M('porder')->where(array('status' => 1, 'client' => array('in', array(Client::CLIENT_WX, Client::CLIENT_H5, Client::CLIENT_AGA)), 'create_time' => array('lt', $Kh9vPvPvPMD)))->field('id')->select();
        $porder = $Kh9tIME;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxf7;
        goto Kh9ldMhxf7;
        Kh9eWjgxf7:
        return rjson(1, '没有需要处理的超时订单');
        goto Kh9xf6;
        Kh9ldMhxf7:Kh9xf6:
        foreach ($porder as $order) {
            self::payFailCancelOrder($order['id'], "订单超时未支付，系统自动取消");
        }
        return rjson(0, '处理完成');
    }

    public static function payFailCancelOrder($porder_id, $remark = '订单支付失败，系统取消订单')
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('id' => $porder_id, 'status' => 1))->field('id')->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxf9;
        goto Kh9ldMhxf9;
        Kh9eWjgxf9:
        Createlog::porderLog($porder_id, "支付失败取消订单的时候发生了错误:订单不是待支付状态");
        return rjson(1, '支付失败取消订单的时候发生了错误');
        goto Kh9xf8;
        Kh9ldMhxf9:Kh9xf8:
        Createlog::porderLog($porder_id, $remark);
        M('porder')->where(array('id' => $porder_id))->setField(array('status' => 7, 'remark' => $remark, 'finish_time' => time()));
        return rjson(0, '取消成功');
    }

    public static function cancelApiOrder($porder_id)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder p')->join('reapi r', 'r.id=p.api_cur_id')->where(array('p.id' => $porder_id, 'p.status' => 3))->field('r.callapi,p.api_cur_param_id,p.api_cur_id,p.api_order_number')->find();
        $porder = $Kh9tIMC;
        $Kh9MC = !$porder;
        if ($Kh9MC) goto Kh9eWjgxfb;
        goto Kh9ldMhxfb;
        Kh9eWjgxfb:
        return rjson(1, '不能操作取消');
        goto Kh9xfa;
        Kh9ldMhxfb:Kh9xfa:
        unset($Kh9tIMC);
        $Kh9tIMC = M('reapi')->where(array('id' => $porder['api_cur_id']))->find();
        $config = $Kh9tIMC;
        $Kh9MC = 'Recharge\\' . ucfirst($config['callapi']);
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $classname = $Kh9tIMD;
        $Kh9MC = !class_exists($classname);
        if ($Kh9MC) goto Kh9eWjgxfd;
        goto Kh9ldMhxfd;
        Kh9eWjgxfd:
        $Kh9vPMC = '系统错误，接口类:' . $classname;
        $Kh9vPMD = $Kh9vPMC . '不存在';
        return rjson(1, $Kh9vPMD);
        goto Kh9xfc;
        Kh9ldMhxfd:Kh9xfc:
        $Kh9MC = new $classname($config);
        unset($Kh9tIMD);
        $Kh9tIMD = $Kh9MC;
        $model = $Kh9tIMD;
        $Kh9MC = !method_exists($model, 'cancel');
        if ($Kh9MC) goto Kh9eWjgxff;
        goto Kh9ldMhxff;
        Kh9eWjgxff:
        $Kh9vPMC = '系统错误，接口类:' . $classname;
        $Kh9vPMD = $Kh9vPMC . '的取消方法（cancel）不存在';
        return rjson(1, $Kh9vPMD);
        goto Kh9xfe;
        Kh9ldMhxff:Kh9xfe:
        return $model->cancel($porder['api_order_number']);
    }

    public static function apiSelfCheck()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder p')->join('reapi r', 'r.id=p.api_cur_id')->where(array('r.is_self_check' => 1, 'p.status' => 3))->order('p.pegging_time asc ,p.create_time asc')->limit(100)->field('p.id,p.mobile,p.order_number,p.api_order_number,p.api_trade_num,p.api_cur_param_id,p.api_cur_id')->select();
        $porders = $Kh9tIMC;
        foreach ($porders as $k => $order) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('reapi')->where(array('id' => $order['api_cur_id']))->find();
            $config = $Kh9tIMC;
            $Kh9MC = 'Recharge\\' . ucfirst($config['callapi']);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $classname = $Kh9tIMD;
            $Kh9MC = !class_exists($classname);
            if ($Kh9MC) goto Kh9eWjgxfh;
            goto Kh9ldMhxfh;
            Kh9eWjgxfh:
            continue 1;
            goto Kh9xfg;
            Kh9ldMhxfh:Kh9xfg:
            $Kh9MC = new $classname($config);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $model = $Kh9tIMD;
            $Kh9MC = !method_exists($model, 'selfcheck');
            if ($Kh9MC) goto Kh9eWjgxfj;
            goto Kh9ldMhxfj;
            Kh9eWjgxfj:
            continue 1;
            goto Kh9xfi;
            Kh9ldMhxfj:Kh9xfi:
            $model->selfcheck($order);
        }
    }

    public static function apiSelfRemove()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder p')->join('reapi r', 'r.id=p.api_cur_id')->where(array('r.is_self_remove' => 1, 'p.status' => 3, 'p.apply_refund' => 1))->order('p.pegging_time asc ,p.create_time asc')->limit(50)->field('p.id,p.mobile,p.order_number,p.api_order_number,p.api_trade_num,p.api_cur_param_id,p.api_cur_id')->select();
        $porders = $Kh9tIMC;
        foreach ($porders as $k => $order) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('reapi')->where(array('id' => $order['api_cur_id']))->find();
            $config = $Kh9tIMC;
            $Kh9MC = 'Recharge\\' . ucfirst($config['callapi']);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $classname = $Kh9tIMD;
            $Kh9MC = !class_exists($classname);
            if ($Kh9MC) goto Kh9eWjgxfl;
            goto Kh9ldMhxfl;
            Kh9eWjgxfl:
            continue 1;
            goto Kh9xfk;
            Kh9ldMhxfl:Kh9xfk:
            $Kh9MC = new $classname($config);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $model = $Kh9tIMD;
            $Kh9MC = !method_exists($model, 'selfremove');
            if ($Kh9MC) goto Kh9eWjgxfn;
            goto Kh9ldMhxfn;
            Kh9eWjgxfn:
            continue 1;
            goto Kh9xfm;
            Kh9ldMhxfn:Kh9xfm:
            $model->selfremove($order);
        }
    }

    public static function untlock($id)
    {
        M('porder')->where(array('id' => $id))->setField(array('tlocking' => 0));
    }

    public static function jiemaCheckOrder()
    {
        unset($Kh9tIMC);
        $Kh9tIMC = M('porder')->where(array('is_del' => 0, 'status' => 3, 'is_jiema' => 1))->limit(100)->select();
        $porders = $Kh9tIMC;
        $Kh9MC = !$porders;
        if ($Kh9MC) goto Kh9eWjgxfp;
        goto Kh9ldMhxfp;
        Kh9eWjgxfp:
        return djson(1, '订单未找到');
        goto Kh9xfo;
        Kh9ldMhxfp:Kh9xfo:
        foreach ($porders as $porder) {
            unset($Kh9tIMC);
            $Kh9tIMC = M('product')->where(array('id' => $porder['product_id']))->field('id,jmapi_id,jmapi_param_id')->find();
            $product = $Kh9tIMC;
            $Kh9MC = !$product;
            if ($Kh9MC) goto Kh9eWjgxfr;
            goto Kh9ldMhxfr;
            Kh9eWjgxfr:
            Createlog::porderLog($porder['id'], "接码检查状态：产品信息未找到");
            continue 1;
            goto Kh9xfq;
            Kh9ldMhxfr:Kh9xfq:
            unset($Kh9tIMC);
            $Kh9tIMC = M('jmapi')->where(array('id' => $product['jmapi_id'], 'is_del' => 0))->find();
            $config = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = M('jmapi_param')->where(array('id' => $product['jmapi_param_id']))->find();
            $param = $Kh9tIMC;
            $Kh9MC = !$config;
            $Kh9ME = (bool)$Kh9MC;
            $Kh9MF = !$Kh9ME;
            if ($Kh9MF) goto Kh9eWjgxfu;
            goto Kh9ldMhxfu;
            Kh9eWjgxfu:
            $Kh9MD = !$param;
            $Kh9ME = $Kh9MC || $Kh9MD;
            goto Kh9xft;
            Kh9ldMhxfu:Kh9xft:
            if ($Kh9ME) goto Kh9eWjgxfv;
            goto Kh9ldMhxfv;
            Kh9eWjgxfv:
            Createlog::porderLog($porder['id'], "接码检查状态：接码api信息未找到");
            continue 1;
            goto Kh9xfs;
            Kh9ldMhxfv:Kh9xfs:
            $Kh9MC = 'Jiema\\' . $config['callapi'];
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $classname = $Kh9tIMD;
            $Kh9MC = new $classname($config);
            unset($Kh9tIMD);
            $Kh9tIMD = $Kh9MC;
            $model = $Kh9tIMD;
            unset($Kh9tIMC);
            $Kh9tIMC = $porder['extend_param1'];
            $param['extend_param1'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $porder['extend_param2'];
            $param['extend_param2'] = $Kh9tIMC;
            unset($Kh9tIMC);
            $Kh9tIMC = $porder['order_number'];
            $param['order_number'] = $Kh9tIMC;
            $model->check($porder['mobile'], $porder['order_number'], $param);
        }
        return rjson(0, '查询完成');
    }

    public static function http_get($url, $param)
    {
        unset($Kh9tIMC);
        $Kh9tIMC = curl_init();
        $oCurl = $Kh9tIMC;
        if (is_string($param)) goto Kh9eWjgxfx;
        goto Kh9ldMhxfx;
        Kh9eWjgxfx:
        unset($Kh9tIMC);
        $Kh9tIMC = $param;
        $strPOST = $Kh9tIMC;
        goto Kh9xfw;
        Kh9ldMhxfx:
        unset($Kh9tIMC);
        $Kh9tIMC = http_build_query($param);
        $strPOST = $Kh9tIMC;
        Kh9xfw:
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, 30);
        curl_setopt($oCurl, CURLOPT_HEADER, 0);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, ["ContentType:application/x-www-form-urlencoded;charset=utf-8"]);
        unset($Kh9tIMC);
        $Kh9tIMC = curl_exec($oCurl);
        $sContent = $Kh9tIMC;
        unset($Kh9tIMC);
        $Kh9tIMC = curl_getinfo($oCurl);
        $aStatus = $Kh9tIMC;
        curl_close($oCurl);
        $Kh9MC = intval($aStatus["http_code"]) == 200;
        if ($Kh9MC) goto Kh9eWjgxgz;
        goto Kh9ldMhxgz;
        Kh9eWjgxgz:
        unset($Kh9tIMC);
        $Kh9tIMC = json_decode($sContent, true);
        $result = $Kh9tIMC;
        $Kh9MC = $result['errno'] == 0;
        if ($Kh9MC) goto Kh9eWjgxg2;
        goto Kh9ldMhxg2;
        Kh9eWjgxg2:
        return rjson(0, $result['errmsg'], $result['data']);
        goto Kh9xg1;
        Kh9ldMhxg2:
        return rjson(1, $result['errmsg'], $result['data']);
        Kh9xg1:
        goto Kh9xfy;
        Kh9ldMhxgz:
        $Kh9vPMC = '接口访问失败，http错误码' . $aStatus["http_code"];
        return rjson(500, $Kh9vPMC);
        Kh9xfy:
    }
}

?>