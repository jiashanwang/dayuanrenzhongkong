<?php
/*
 本代码由 成都大猿人网络科技有限公司 原创开发
 官方网址：www.dayuanren.cn
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace app\admin\controller;
class Reapi extends Admin
{
    public function _init()
    {
        $PhqMT = !IS_CLI;
        $PhqMX = (bool)$PhqMT;
        if ($PhqMX) goto PhqeWjgx5;
        goto PhqldMhx5;
        PhqeWjgx5:
        $PhqMU = !function_exists('get_shoquan_key');
        $PhqMW = (bool)$PhqMU;
        $PhqMY = !$PhqMW;
        if ($PhqMY) goto PhqeWjgx3;
        goto PhqldMhx3;
        PhqeWjgx3:
        $PhqMV = !S(md5(get_shoquan_key()));
        $PhqMW = $PhqMU || $PhqMV;
        goto Phqx2;
        PhqldMhx3:Phqx2:
        $PhqMX = $PhqMT && $PhqMW;
        goto Phqx4;
        PhqldMhx5:Phqx4:
        if ($PhqMX) goto PhqeWjgx6;
        goto PhqldMhx6;
        PhqeWjgx6:
        echo C('sqyc_msg');
        exit();
        goto Phqx1;
        PhqldMhx6:Phqx1:
    }

    public function index()
    {
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('is_del' => 0))->order('sort asc,id asc')->select();
        $list = $PhqtIMT;
        $this->assign('_list', $list);
        return view();
    }

    public function noindex()
    {
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('is_del' => 1))->order('sort asc,id asc')->select();
        $list = $PhqtIMT;
        $this->assign('_list', $list);
        return view();
    }

    public function edit()
    {
        if (request()->isPost()) goto PhqeWjgx8;
        goto PhqldMhx8;
        PhqeWjgx8:
        unset($PhqtIMT);
        $PhqtIMT = $_POST;
        $arr = $PhqtIMT;
        if (I('id')) goto PhqeWjgxa;
        goto PhqldMhxa;
        PhqeWjgxa:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('id' => I('id')))->setField($arr);
        $data = $PhqtIMT;
        if ($data) goto PhqeWjgxc;
        goto PhqldMhxc;
        PhqeWjgxc:
        return $this->success('保存成功');
        goto Phqxb;
        PhqldMhxc:
        return $this->error('编辑失败');
        Phqxb:
        goto Phqx9;
        PhqldMhxa:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->insertGetId($arr);
        $aid = $PhqtIMT;
        if ($aid) goto PhqeWjgxe;
        goto PhqldMhxe;
        PhqeWjgxe:
        return $this->success('新增成功');
        goto Phqxd;
        PhqldMhxe:
        return $this->error('新增失败');
        Phqxd:Phqx9:
        goto Phqx7;
        PhqldMhx8:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('id' => I('id')))->find();
        $info = $PhqtIMT;
        $this->assign('info', $info);
        return view();
        Phqx7:
    }

    public function param()
    {
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('id' => I('id')))->find();
        $api = $PhqtIMT;
        $PhqMT = !$api;
        if ($PhqMT) goto PhqeWjgxg;
        goto PhqldMhxg;
        PhqeWjgxg:
        return $this->error('参数错误');
        goto Phqxf;
        PhqldMhxg:Phqxf:
        $this->assign('api', $api);
        unset($PhqtIMT);
        $PhqtIMT = M('reapi_param')->where(array('reapi_id' => I('id')))->select();
        $list = $PhqtIMT;
        $this->assign('_list', $list);
        return view();
    }

    public function param_edit()
    {
        if (request()->isPost()) goto PhqeWjgxi;
        goto PhqldMhxi;
        PhqeWjgxi:
        unset($PhqtIMT);
        $PhqtIMT = $_POST;
        $arr = $PhqtIMT;
        if (I('id')) goto PhqeWjgxk;
        goto PhqldMhxk;
        PhqeWjgxk:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi_param')->where(array('id' => I('id')))->setField($arr);
        $data = $PhqtIMT;
        if ($data) goto PhqeWjgxm;
        goto PhqldMhxm;
        PhqeWjgxm:
        return $this->success('保存成功');
        goto Phqxl;
        PhqldMhxm:
        return $this->error('编辑失败');
        Phqxl:
        goto Phqxj;
        PhqldMhxk:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi_param')->insertGetId($arr);
        $data = $PhqtIMT;
        if ($data) goto PhqeWjgxo;
        goto PhqldMhxo;
        PhqeWjgxo:
        return $this->success('添加成功');
        goto Phqxn;
        PhqldMhxo:
        return $this->error('添加失败');
        Phqxn:Phqxj:
        goto Phqxh;
        PhqldMhxi:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('id' => I('reapi_id')))->find();
        $api = $PhqtIMT;
        $PhqMT = !$api;
        if ($PhqMT) goto PhqeWjgxq;
        goto PhqldMhxq;
        PhqeWjgxq:
        return $this->error('参数错误');
        goto Phqxp;
        PhqldMhxq:Phqxp:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi_param')->where(array('id' => I('id')))->find();
        $info = $PhqtIMT;
        $this->assign('info', $info);
        return view();
        Phqxh:
    }

    public function deletes()
    {
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('id' => I('id')))->find();
        $reapi = $PhqtIMT;
        $PhqMT = !$reapi;
        if ($PhqMT) goto PhqeWjgxs;
        goto PhqldMhxs;
        PhqeWjgxs:
        return $this->error('未找到接口');
        goto Phqxr;
        PhqldMhxs:Phqxr:
        if (M('product_api')->where(array('reapi_id' => $reapi['id']))->find()) goto PhqeWjgxu;
        goto PhqldMhxu;
        PhqeWjgxu:
        return $this->error('该接口还有产品在使用中，请先取消接口绑定的所有产品');
        goto Phqxt;
        PhqldMhxu:Phqxt:
        if (M('reapi')->where(array('id' => $reapi['id']))->setField(array('is_del' => 1))) goto PhqeWjgxw;
        goto PhqldMhxw;
        PhqeWjgxw:
        return $this->success('删除成功');
        goto Phqxv;
        PhqldMhxw:
        return $this->error('删除失败');
        Phqxv:
    }

    public function restore()
    {
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('id' => I('id')))->find();
        $reapi = $PhqtIMT;
        $PhqMT = !$reapi;
        if ($PhqMT) goto PhqeWjgxy;
        goto PhqldMhxy;
        PhqeWjgxy:
        return $this->error('未找到接口');
        goto Phqxx;
        PhqldMhxy:Phqxx:
        if (M('reapi')->where(array('id' => $reapi['id']))->setField(array('is_del' => 0))) goto PhqeWjgx11;
        goto PhqldMhx11;
        PhqeWjgx11:
        return $this->success('恢复成功');
        goto Phqxz;
        PhqldMhx11:
        return $this->error('恢复失败');
        Phqxz:
    }

    public function deletes_param()
    {
        unset($PhqtIMT);
        $PhqtIMT = I('id/a');
        $ids = $PhqtIMT;
        unset($PhqtIMT);
        $PhqtIMT = 0;
        $counts = $PhqtIMT;
        unset($PhqtIMT);
        $PhqtIMT = M('reapi_param')->where(array('id' => array('in', $ids)))->select();
        $param = $PhqtIMT;
        $PhqMT = !$param;
        if ($PhqMT) goto PhqeWjgx13;
        goto PhqldMhx13;
        PhqeWjgx13:
        return $this->error('未找到套餐');
        goto Phqx12;
        PhqldMhx13:Phqx12:
        foreach ($param as $vo) {
            if (M('product_api')->where(array('param_id' => $vo['id']))->find()) goto PhqeWjgx15;
            goto PhqldMhx15;
            PhqeWjgx15:
            return $this->error('套餐中还有产品在使用中，请先取消接口套餐绑定的所有产品');
            goto Phqx14;
            PhqldMhx15:Phqx14:
            if (M('reapi_param')->where(array('id' => $vo['id']))->delete()) goto PhqeWjgx17;
            goto PhqldMhx17;
            PhqeWjgx17:
            $PhqoB233 = $counts;
            $PhqoB234 = $counts + 1;
            $counts = $PhqoB234;
            goto Phqx16;
            PhqldMhx17:Phqx16:
        }
        $PhqMT = $counts > 0;
        if ($PhqMT) goto PhqeWjgx19;
        goto PhqldMhx19;
        PhqeWjgx19:
        $PhqvPMT = '成功删除' . $counts;
        $PhqvPMU = $PhqvPMT . '条';
        return $this->success($PhqvPMU);
        goto Phqx18;
        PhqldMhx19:
        return $this->error('删除失败');
        Phqx18:
    }

    public function get_reapi_param()
    {
        unset($PhqtIMT);
        $PhqtIMT = array();
        $map = $PhqtIMT;
        if (I('reapi_id')) goto PhqeWjgx1b;
        goto PhqldMhx1b;
        PhqeWjgx1b:
        unset($PhqtIMT);
        $PhqtIMT = I('reapi_id');
        $map['reapi_id'] = $PhqtIMT;
        goto Phqx1a;
        PhqldMhx1b:Phqx1a:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi_param')->where($map)->order("desc asc")->select();
        $lists = $PhqtIMT;
        return djson(0, 'ok', $lists);
    }

    public function api_balance()
    {
        if (request()->isPost()) goto PhqeWjgx1d;
        goto PhqldMhx1d;
        PhqeWjgx1d:
        unset($PhqtIMT);
        $PhqtIMT = I('api_id');
        $reapiid = $PhqtIMT;
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where(array('id' => $reapiid))->find();
        $config = $PhqtIMT;
        $PhqMT = 'Recharge\\' . ucfirst($config['callapi']);
        unset($PhqtIMU);
        $PhqtIMU = $PhqMT;
        $classname = $PhqtIMU;
        $PhqMT = !class_exists($classname);
        if ($PhqMT) goto PhqeWjgx1f;
        goto PhqldMhx1f;
        PhqeWjgx1f:
        return rjson(1, '查询失败，未找到该接口', '查询失败，未找到该接口');
        goto Phqx1e;
        PhqldMhx1f:Phqx1e:
        $PhqMT = new $classname($config);
        unset($PhqtIMU);
        $PhqtIMU = $PhqMT;
        $model = $PhqtIMU;
        $PhqMT = !method_exists($model, 'balance');
        if ($PhqMT) goto PhqeWjgx1h;
        goto PhqldMhx1h;
        PhqeWjgx1h:
        return rjson(1, '查询失败，该接口不支持余额查询', '查询失败，该接口不支持余额查询');
        goto Phqx1g;
        PhqldMhx1h:Phqx1g:
        unset($PhqtIMT);
        $PhqtIMT = $model->balance();
        $res = $PhqtIMT;
        $PhqMT = $res != '';
        if ($PhqMT) goto PhqeWjgx1j;
        goto PhqldMhx1j;
        PhqeWjgx1j:
        return djson(0, '查询成功', $res);
        goto Phqx1i;
        PhqldMhx1j:
        return djson(1, '查询失败', '查询失败');
        Phqx1i:
        goto Phqx1c;
        PhqldMhx1d:Phqx1c:
    }

    public function fenxi()
    {
        unset($PhqtIMT);
        $PhqtIMT = 0;
        $apmap['is_del'] = $PhqtIMT;
        if (I('reapi_id')) goto PhqeWjgx1l;
        goto PhqldMhx1l;
        PhqeWjgx1l:
        unset($PhqtIMT);
        $PhqtIMT = I('reapi_id');
        $apmap['id'] = $PhqtIMT;
        goto Phqx1k;
        PhqldMhx1l:Phqx1k:
        unset($PhqtIMT);
        $PhqtIMT = M('reapi')->where($apmap)->select();
        $apis = $PhqtIMT;
        $PhqMT = !$apis;
        if ($PhqMT) goto PhqeWjgx1n;
        goto PhqldMhx1n;
        PhqeWjgx1n:
        return $this->error('参数错误');
        goto Phqx1m;
        PhqldMhx1n:Phqx1m:
        $PhqMT = (bool)I('end_time');
        if ($PhqMT) goto PhqeWjgx1q;
        goto PhqldMhx1q;
        PhqeWjgx1q:
        $PhqMT = I('end_time') && I('begin_time');
        goto Phqx1p;
        PhqldMhx1q:Phqx1p:
        if ($PhqMT) goto PhqeWjgx1r;
        goto PhqldMhx1r;
        PhqeWjgx1r:
        unset($PhqtIMT);
        $PhqtIMT = strtotime(date("Y-m-d", strtotime(I('begin_time'))));
        $start_time = $PhqtIMT;
        $PhqMT = strtotime(date("Y-m-d", strtotime(I('end_time')))) + 86400;
        unset($PhqtIMU);
        $PhqtIMU = $PhqMT;
        $end_time = $PhqtIMU;
        goto Phqx1o;
        PhqldMhx1r:
        unset($PhqtIMT);
        $PhqtIMT = strtotime(date("Y-m-01", time()));
        $start_time = $PhqtIMT;
        $PhqMT = strtotime(date("Y-m-d", time())) + 86400;
        unset($PhqtIMU);
        $PhqtIMU = $PhqMT;
        $end_time = $PhqtIMU;
        Phqx1o:
        unset($PhqtIMT);
        $PhqtIMT = array();
        $datas = $PhqtIMT;
        foreach ($apis as $api) {
            unset($PhqtIMT);
            $PhqtIMT = $api['name'];
            $arr['name'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = date("Y-m-d", $start_time);
            $arr['date_start'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = date("Y-m-d", $end_time);
            $arr['date_end'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = M('porder_apilog')->where(array('reapi_id' => $api['id'], 'create_time' => array('between', array($start_time, $end_time))))->count();
            $arr['all_count'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = M('porder')->where(array('api_cur_id' => $api['id'], 'apireq_time' => array('between', array($start_time, $end_time))))->where(array('status' => 3))->count();
            $arr['ing_count'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = M('porder')->where(array('api_cur_id' => $api['id'], 'apireq_time' => array('between', array($start_time, $end_time))))->where(array('status' => array('in', array(4, 12, 13))))->count();
            $arr['sus_count'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = M('porder')->where(array('api_cur_id' => $api['id'], 'apireq_time' => array('between', array($start_time, $end_time))))->where(array('status' => array('in', array(4))))->sum("total_price");
            $arr['sus_price'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = M('porder')->where(array('api_cur_id' => $api['id'], 'apireq_time' => array('between', array($start_time, $end_time))))->where(array('status' => array('in', array(4))))->sum("cost");
            $arr['sus_cost'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = M('porder')->where(array('api_cur_id' => $api['id'], 'apireq_time' => array('between', array($start_time, $end_time))))->where(array('status' => array('in', array(12, 13))))->sum("total_price-refund_price");
            $arr['pasus_price'] = $PhqtIMT;
            unset($PhqtIMT);
            $PhqtIMT = M('porder')->where(array('api_cur_id' => $api['id'], 'apireq_time' => array('between', array($start_time, $end_time))))->where(array('status' => array('in', array(12, 13))))->sum("cost*(1-refund_price/total_price)");
            $arr['pasus_cost'] = $PhqtIMT;
            if ($arr['all_count']) goto PhqeWjgx1t;
            goto PhqldMhx1t;
            PhqeWjgx1t:
            $PhqvPMT = $arr['sus_count'] / $arr['all_count'];
            $PhqvPMU = $PhqvPMT * 100;
            $PhqMV = round($PhqvPMU, 2);
            goto Phqx1s;
            PhqldMhx1t:
            $PhqMV = 0;
            Phqx1s:
            unset($PhqtIMW);
            $PhqtIMW = $PhqMV;
            $arr['sus_ratio'] = $PhqtIMW;
            unset($PhqtIMT);
            $PhqtIMT = $arr;
            $datas[] = $PhqtIMT;
        }
        $this->assign('data', $datas);
        $this->assign('apis', M('reapi')->where(array('is_del' => 0))->select());
        return view();
    }
}

?>