<?php 
//decode by http://chiran.taobao.com/
namespace app\agent\controller;
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
		$list = M('alert_order')->where(array('Customer_id' =>$this->user['id']))->order("Create_time desc")->select();
		$this->assign('_list', $list);
		return view();
	}
}
?>