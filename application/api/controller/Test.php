<?php

namespace app\api\controller;


use app\common\library\Createlog;
use app\common\library\KsAgent;
use app\common\library\Otherapi;
use app\common\library\SmsNotice;
use app\common\model\Porder;
use Payapi\Jindjuhe;
use Payapi\Leshua;
use think\Log;
use Util\Pinyin;

/**
 * Class test
 * 开放控制器，登录注册等操作
 */
class Test extends \app\common\controller\Base
{
//
    public function test()
    {
        $config = M('reapi')->where(['id' => 33])->find();
        $param = M('reapi_param')->where(['id' => 219])->find();
        $classname = 'Recharge\\' . $config['callapi'];
        $model = new $classname($config);
        $param['oparam1'] = '';
        $param['oparam2'] = '';
        $param['oparam3'] = '';
        $param['guishu_pro'] = '四川';
//        return $model->recharge(time(), '11111111111', $param, '移动');
//        return $model->addcard('3200155675626', $param, '江苏');
//        return $model->product(2);
    }

    public function test3()
    {
//        $option = [
//            'openid' => 'oNAd-xCweRSon0afLnVB2dOlguWM',
//            'body' => '测试',
//            'order_number' => time(),
//            'total_price' => '0.01',
//            'appid' => 'wx9ad50c6ee8c5e5b2'
//        ];
//        $config = M('weixin')->where(['appid' => $option['appid'], 'is_del' => 0])->find();
//        $classname = 'Payapi\\' . ucfirst($config['wx_payclass']);
//        if (!class_exists($classname)) {
//            return rjson(1, '系统错误，支付接口:' . $classname . '不存在');
//        }
//        $model = new $classname($config);
//        if (!method_exists($model, 'create_wxpay_js')) {
//            return rjson(1, '系统错误，支付接口:' . $classname . '不支持支付方法（create_wxpay_js）');
//        }
//        return $model->create_wxpay_js($option, $config);
    }

    public function test2()
    {
//        $porders = M('porder p')
//            ->join('reapi r', 'r.id=p.api_cur_id')
//            ->where(['r.id' => 6, 'p.status' => 3])
//            ->order('p.pegging_time asc ,p.create_time asc')
//            ->limit(100)
//            ->field('p.id,p.mobile,p.order_number,p.api_order_number,p.api_trade_num,p.api_cur_param_id,p.api_cur_id')
//            ->select();
//        foreach ($porders as $k => $order) {
//            $config = M('reapi')->where(['id' => $order['api_cur_id']])->find();
//            M('porder')->where(['id' => $order['id']])->setField(['pegging_time' => time()]);
//            $classname = 'Recharge\\' . ucfirst($config['callapi']);
//            if (!class_exists($classname)) {
//                continue;
//            }
//            $model = new $classname($config);
//            if (!method_exists($model, 'selfcheck')) {
//                continue;
//            }
//            $model->selfcheck($order);
//        }
    }


}
