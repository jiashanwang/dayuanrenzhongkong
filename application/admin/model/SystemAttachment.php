<?php

//decode by http://chiran.taobao.com/
namespace app\admin\model;

use think\Model;
class SystemAttachment extends Model
{
	protected $pk = "att_id";
	protected $name = "system_attachment";
	protected $append = ["size_mb"];
	public function getSizeMbAttr($value, $data)
	{
		$filesize = $data['att_size'];
		if ($filesize >= 1073741824) {
			$filesize = round($filesize / 1073741824 * 100) / 100 . ' Gb';
		} elseif ($filesize >= 1048576) {
			$filesize = round($filesize / 1048576 * 100) / 100 . ' Mb';
		} elseif ($filesize >= 1024) {
			$filesize = round($filesize / 1024 * 100) / 100 . ' Kb';
		} else {
			$filesize = $filesize . ' bytes';
		}
		return $filesize;
	}
	public static function attachmentAdd($name, $att_size, $att_type, $att_dir, $satt_dir = '', $pid = 0, $imageType = 1, $time = 0, $module_type = 1)
	{
		$data['name'] = $name;
		$data['att_dir'] = $att_dir;
		$data['satt_dir'] = $satt_dir;
		$data['att_size'] = $att_size;
		$data['att_type'] = $att_type;
		$data['image_type'] = $imageType;
		$data['module_type'] = $module_type;
		$data['time'] = $time ?: time();
		$data['pid'] = $pid;
		return self::create($data);
	}
	public static function getAll($id)
	{
		$model = new self();
		$where['pid'] = $id;
		$where['module_type'] = 1;
		$model->where($where)->order('att_id desc');
		return $model->page($model, $where, '', 24);
	}
	public static function getInfo($value, $field = 'att_id')
	{
		$where[$field] = $value;
		$count = self::where($where)->count();
		if (!$count) {
			return false;
		}
		return self::where($where)->find()->toArray();
	}
	public static function emptyYesterdayAttachment()
	{
		$list = self::whereTime('time', 'yesterday')->where('module_type', 2)->field('name,att_dir,att_id,image_type')->select();
		try {
			$uploadType = (int) sys_config('upload_type', 1);
			$upload = new Upload($uploadType, array('accessKey' => sys_config('accessKey'), 'secretKey' => sys_config('secretKey'), 'uploadUrl' => sys_config('uploadUrl'), 'storageName' => sys_config('storage_name'), 'storageRegion' => sys_config('storage_region')));
			foreach ($list as $key => $item) {
				if ($item['image_type'] == 1) {
					$att_dir = $item['att_dir'];
					if ($att_dir && strstr($att_dir, 'uploads') !== false) {
						if (strstr($att_dir, 'http') === false) {
							$upload->delete($att_dir);
						} else {
							$filedir = substr($att_dir, strpos($att_dir, 'uploads'));
							if ($filedir) {
								$upload->delete($filedir);
							}
						}
					}
				} else {
					if ($item['name']) {
						$upload->delete($item['name']);
					}
				}
			}
			self::whereTime('time', 'yesterday')->where('module_type', 2)->delete();
			return true;
		} catch (\Exception $e) {
			self::whereTime('time', 'yesterday')->where('module_type', 2)->delete();
			return true;
		}
	}
}