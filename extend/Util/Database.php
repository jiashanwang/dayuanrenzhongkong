<?php

//decode by http://chiran.taobao.com/
namespace Util;

use think\Db;
class Database
{
	private $fp;
	private $file;
	private $size = 0;
	private $config;
	public function __construct($file, $config, $type = 'export')
	{
		$this->file = $file;
		$this->config = $config;
	}
	private function open($size)
	{
		if ($this->fp) {
			$this->size += $size;
			if ($this->size > $this->config['part']) {
				if ($this->config['compress']) {
					@gzclose($this->fp);
				} else {
					@fclose($this->fp);
				}
				$this->fp = null;
				$this->file['part']++;
				session('backup_file', $this->file);
				$this->create();
			}
		} else {
			$backuppath = $this->config['path'];
			$filename = $backuppath . $this->file['name'] . "-" . $this->file['part'] . ".sql";
			if ($this->config['compress']) {
				$filename = $filename . ".gz";
				$this->fp = @gzopen($filename, "a" . $this->config['level']);
			} else {
				$this->fp = @fopen($filename, 'a');
			}
			$this->size = filesize($filename) + $size;
		}
	}
	public function create()
	{
		$sql = "-- -----------------------------\n";
		$sql .= "-- Think MySQL Data Transfer \n";
		$sql .= "-- \n";
		$sql .= "-- Host     : " . C('database.hostname') . "\n";
		$sql .= "-- Port     : " . C('database.hostport') . "\n";
		$sql .= "-- Database : " . C('database.database') . "\n";
		$sql .= "-- \n";
		$sql .= "-- Part : #" . $this->file['part'] . "\n";
		$sql .= "-- Date : " . date("Y-m-d H:i:s") . "\n";
		$sql .= "-- -----------------------------\n\n";
		$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
		return $this->write($sql);
	}
	private function write($sql)
	{
		$size = strlen($sql);
		$size = $this->config['compress'] ? $size / 2 : $size;
		$this->open($size);
		return $this->config['compress'] ? @gzwrite($this->fp, $sql) : @fwrite($this->fp, $sql);
	}
	public function backup($table, $start)
	{
		if (0 == $start) {
			$result = Db::query("SHOW CREATE TABLE `" . $table . "`");
			$sql = "\n";
			$sql .= "-- -----------------------------\n";
			$sql .= "-- Table structure for `" . $table . "`\n";
			$sql .= "-- -----------------------------\n";
			$sql .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
			$sql .= trim($result[0]['Create Table']) . ";\n\n";
			if (false === $this->write($sql)) {
				return false;
			}
		}
		$result = Db::query("SELECT COUNT(*) AS count FROM `" . $table . "`");
		$count = $result['0']['count'];
		if ($count) {
			if (0 == $start) {
				$sql = "-- -----------------------------\n";
				$sql .= "-- Records of `" . $table . "`\n";
				$sql .= "-- -----------------------------\n";
				$this->write($sql);
			}
			$result = Db::query("SELECT * FROM `" . $table . "` LIMIT " . $start . ", 1000");
			foreach ($result as $row) {
				$row = array_map('addslashes', $row);
				$sql = "INSERT INTO `" . $table . "` VALUES ('" . implode("', '", $row) . "');\n";
				if (false === $this->write($sql)) {
					return false;
				}
			}
			if ($count > $start + 1000) {
				return array($start + 1000, $count);
			}
		}
		return 0;
	}
	public function import($start)
	{
		if ($this->config['compress']) {
			$gz = gzopen($this->file[1], 'r');
			$size = 0;
		} else {
			$size = filesize($this->file[1]);
			$gz = fopen($this->file[1], 'r');
		}
		$sql = '';
		if ($start) {
			if ($this->config['compress']) {
				gzseek($gz, $start);
			} else {
				fseek($gz, $start);
			}
		}
		for ($i = 0; $i < 1000; $i++) {
			$sql .= $this->config['compress'] ? gzgets($gz) : fgets($gz);
			if (preg_match('/.*;$/', trim($sql))) {
				if (false !== Db::query($sql)) {
					$start += strlen($sql);
				} else {
					return false;
				}
				$sql = '';
			} else {
				if ($this->config['compress'] ? gzeof($gz) : feof($gz)) {
					return 0;
				}
			}
		}
		return array($start, $size);
	}
	public function __destruct()
	{
		if ($this->config['compress']) {
			@gzclose($this->fp);
		} else {
			@fclose($this->fp);
		}
	}
}