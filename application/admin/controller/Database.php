<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

use think\Db;
use think\Request;
class Database extends Admin
{
	public function index()
	{
		$list = Db::query("SHOW TABLE STATUS");
		$list = array_map('array_change_key_case', $list);
		$this->assign('list', $list);
		return view();
	}
	public function repair($tables = null)
	{
		if ($tables) {
			if (is_array($tables)) {
				$tables = implode('`,`', $tables);
				$list = Db::query("REPAIR TABLE `" . $tables . "`");
				if ($list) {
					return $this->success("数据表修复完成！");
				} else {
					return $this->error("数据表修复出错请重试！");
				}
			} else {
				$list = Db::query("REPAIR TABLE `" . $tables . "`");
				if ($list) {
					return $this->success("数据表'" . $tables . "'修复完成！");
				} else {
					return $this->error("数据表'" . $tables . "'修复出错请重试！");
				}
			}
		} else {
			return $this->error("请指定要修复的表！");
		}
	}
	public function optimize()
	{
		$tables = I('tables/a');
		if ($tables) {
			if (is_array($tables)) {
				$tables = implode('`,`', $tables);
				$list = Db::query("OPTIMIZE TABLE `" . $tables . "`");
				if ($list) {
					return $this->success("数据表优化完成！");
				} else {
					return $this->error("数据表优化出错请重试！");
				}
			} else {
				$list = Db::query("OPTIMIZE TABLE `" . $tables . "`");
				if ($list) {
					return $this->success("数据表'" . $tables . "'优化完成！");
				} else {
					return $this->error("数据表'" . $tables . "'优化出错请重试！");
				}
			}
		} else {
			return $this->error("请指定要优化的表！");
		}
	}
	public function export($id = null, $start = null)
	{
		$tables = I('post.tables/a');
		if (Request::instance()->isPost() && !empty($tables) && is_array($tables)) {
			$config = C('data_backup');
			$config['path'] = dirname(ROOT_PATH) . $config['path'];
			$lock = $config['path'] . "backup.lock";
			if (is_file($lock)) {
				return $this->error('检测到有一个备份任务正在执行，请稍后再试！');
			} else {
				file_put_contents($lock, time());
			}
			is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
			session('backup_config', $config);
			$file = array('name' => date('Ymd-His', time()), 'part' => 1);
			session('backup_file', $file);
			$Database = new \Util\Database($file, $config);
			if (false !== $Database->create()) {
				foreach ($tables as $key => $table) {
					$start = $Database->backup($table, 0);
					if (false === $start) {
						return $this->error('备份出错！');
					}
				}
				unlink($lock);
				return $this->success('备份完成！');
			} else {
				return $this->error('初始化失败，备份文件创建失败！');
			}
		} else {
			return $this->error('参数错误！');
		}
	}
}