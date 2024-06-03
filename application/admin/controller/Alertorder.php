<?php 
//decode by http://chiran.taobao.com/
namespace app\admin\controller;
use app\api\controller\Notify;
use app\common\library\Createlog;
use app\common\library\AlertBanlancequery;
use app\common\model\Balance;
use app\common\model\Client;
use app\common\model\Porder as PorderModel;
use app\common\model\Product;
use app\common\model\Product as ProductModel;
use app\common\library\CreateBalanceQueryRecord ;
use think\Db;
class Alertorder extends Admin
{
	public function index()
	{
		$list = M('alert_order')->order("Create_time desc")->select();
		$this->assign('_list', $list);
		return view();
	}
	
    public function deletequerydata()
	{
        $ids=I('id/a');
        if(!$ids){
            return $this->error('请选择要删除的订单');
        }
        if (Db::table('dyr_alert_order')->where(array('id'=>array('in',$ids)))->delete(true)) {
			return $this->success('删除成功！');
		} else {
			return $this->error('删除失败！');
		}
		$list = M('alert_order')->order("Create_time desc")->select();
		$this->assign('_list', $list);
		return view();
	}
}