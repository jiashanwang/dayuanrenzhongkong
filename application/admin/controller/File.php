<?php

//decode by http://chiran.taobao.com/
namespace app\admin\controller;

class File extends Admin
{
	public function upload()
	{
		$file = request()->file('file');
		if (empty($file)) {
			return $this->error('请选择上传文件');
		}
		$info = $file->validate(array('size' => C('DOWNLOAD_UPLOAD.maxSize'), 'ext' => C('DOWNLOAD_UPLOAD.exts')))->move(C('DOWNLOAD_UPLOAD.movePath'));
		if ($info) {
			return djson(0, '上传成功', C('DOWNLOAD_UPLOAD.rootPath') . $info->getSaveName());
		} else {
			return djson(1, $file->getError());
		}
	}
	public function uploads()
	{
		$files = request()->file('files');
		$imginfo = array();
		foreach ($files as $file) {
			$info = $file->validate(array('size' => C('DOWNLOAD_UPLOAD.maxSize'), 'ext' => C('DOWNLOAD_UPLOAD.exts')))->move(C('DOWNLOAD_UPLOAD.movePath'));
			if ($info) {
				array_push($imginfo, C('DOWNLOAD_UPLOAD.rootPath') . $info->getSaveName());
			} else {
				return djson(0, $file->getError());
			}
		}
		return djson(0, '文件上传成功', $imginfo);
	}
	public function upload_txt()
	{
		$file = request()->file('file');
		if (empty($file)) {
			return $this->error('请选择上传文件');
		}
		$info = $file->validate(array('size' => C('DOWNLOAD_UPLOAD_TXT.maxSize'), 'ext' => C('DOWNLOAD_UPLOAD_TXT.exts')))->move(C('DOWNLOAD_UPLOAD_TXT.movePath'), '');
		if ($info) {
			return djson(0, '上传成功', C('DOWNLOAD_UPLOAD_TXT.rootPath') . $info->getSaveName());
		} else {
			return djson(1, $file->getError());
		}
	}
}