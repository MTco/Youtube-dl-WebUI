<?php

class FileHandler
{
	private $config = [];

	public function __construct()
	{
		$this->config = require dirname(__DIR__).'/config/config.php';
	}

	public function listFiles()
	{
		$files = [];

		if(!$this->outuput_folder_exists())
			return;

		$folder = dirname(__DIR__).'/'.$this->config["outputFolder"].'/';

		foreach(glob($folder.'*.*', GLOB_BRACE) as $file)
		{
			$content = [];
			$content["name"] = str_replace($folder, "", $file);
			$content["size"] = $this->to_human_filesize(filesize($file));
			
			$files[] = $content;
		}

		return $files;
	}

	public function listLogs()
	{
		$files = [];
		
		if(!$this->config["log"])
			return;

		if(!$this->outuput_folder_exists())
			return;

		$folder = dirname(__DIR__).'/'.$this->config["logFolder"].'/';

		foreach(glob($folder.'*.txt', GLOB_BRACE) as $file)
		{
			$content = [];
			$content["name"] = str_replace($folder, "", $file);
			$content["size"] = $this->to_human_filesize(filesize($file));
			
			$files[] = $content;
		}

		return $files;
	}

	public function delete($id, $type)
	{
		$folder = dirname(__DIR__).'/'.$this->config["outputFolder"].'/';
		$i = 0;

		foreach(glob($folder.'*.*', GLOB_BRACE) as $file)
		{
			if($i == $id)
			{
				unlink($file);
			}
			$i++;
		}
	}

	public function deleteLog($id, $type)
	{
		$folder = dirname(__DIR__).'/'.$this->config["logFolder"].'/';
		$i = 0;

		foreach(glob($folder.'*.txt', GLOB_BRACE) as $file)
		{
			if($i == $id)
			{
				unlink($file);
			}
			$i++;
		}
	}

	private function outuput_folder_exists()
	{
		if(!is_dir($this->config['outputFolder']))
		{
			//Folder doesn't exist
			if(!mkdir('./'.$this->config['outputFolder'], 0777))
			{
				return false; //No folder and creation failed
			}
		}
		
		return true;
	}

	public function to_human_filesize($bytes, $decimals = 0)
	{
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}

	public function free_space()
	{
		return $this->to_human_filesize(disk_free_space($this->config["outputFolder"]));
	}

	public function get_downloads_folder()
	{
		return $this->config["outputFolder"];
	}
}

?>
