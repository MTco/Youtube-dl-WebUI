<?php
include_once('FileHandler.php');

class Downloader
{
	private $urls = [];
	private $config = [];
	private $audio_only = false;
	private $errors = [];
	private $download_path = "";
	private $log_path = "";
	private $outfilename = "%(title)s-%(uploader)s.%(ext)s";
	private $vformat = false;

	public function __construct($post, $audio_only, $outfilename=False, $vformat=False)
	{
		$this->config = require dirname(__DIR__).'/config/config.php';
		$this->download_path = (new FileHandler())->get_downloads_folder();
		
		if($this->config["log"])
		{
			$this->log_path = dirname(__DIR__).'/'.$this->config["logFolder"];
		}

		$this->audio_only = $audio_only;
		$this->urls = explode(",", $post);

		if(!$this->check_requirements($audio_only))
		{
			return;
		}
		if ($outfilename)
		{
			$this->outfilename = $outfilename;
		}
		if ($vformat)
		{
			$this->vformat = $vformat;
		}
			

		foreach ($this->urls as $url)
		{
			if(!$this->is_valid_url($url))
			{
				$this->errors[] = "\"".$url."\" is not a valid url !";
			}
		}

		if(isset($this->errors) && count($this->errors) > 0)
		{
			$_SESSION['errors'] = $this->errors;
			return;
		}

		if($this->config["max_dl"] == 0)
		{
			$this->do_download();
		}
		elseif($this->config["max_dl"] > 0)
		{
			if($this->background_jobs() >= 0 && $this->background_jobs() < $this->config["max_dl"])
			{
				$this->do_download();
			}
			else
			{
				$this->errors[] = "Simultaneous downloads limit reached !";
			}
		}

		if(isset($this->errors) && count($this->errors) > 0)
		{
			$_SESSION['errors'] = $this->errors;
			return;
		}
	}

	public static function background_jobs()
	{
		return shell_exec("ps aux | grep -v grep | grep -v \"youtube-dl -U\" | grep youtube-dl | wc -l");
	}

	public static function max_background_jobs()
	{
		$config = require dirname(__DIR__).'/config/config.php';
		return $config["max_dl"];
	}

	public static function get_current_background_jobs()
	{
		exec("ps -A -o user,pid,etime,cmd | grep -v grep | grep -v \"youtube-dl -U\" | grep youtube-dl", $output);

		$bjs = [];

		if(count($output) > 0)
		{
			foreach($output as $line)
			{
				$line = explode(' ', preg_replace ("/ +/", " ", $line), 4);
				$bjs[] = array(
					'user' => $line[0],
					'pid' => $line[1],
					'time' => $line[2],
					'cmd' => $line[3]
					);
			}

			return $bjs;
		}
		else
		{
			return null;
		}
	}

	public static function kill_them_all()
	{
		exec("ps -A -o pid,cmd | grep -v grep | grep youtube-dl | awk '{print $1}'", $output);

		if(count($output) <= 0)
			return;

		foreach($output as $p)
		{
			shell_exec("kill ".$p);
		}

		$config = require dirname(__DIR__).'/config/config.php';
		$folder = $this->download_path;

		foreach(glob($folder.'*.part') as $file)
		{
			unlink($file);
		}
	}

	private function check_requirements($audio_only)
	{
		if($this->is_youtubedl_installed() != 0)
		{
			$this->errors[] = "Youtube-dl is not installed, see <a>https://rg3.github.io/youtube-dl/download.html</a> !";
		}

		$this->check_outuput_folder();

		if($audio_only)
		{
			if($this->is_extracter_installed() != 0)
			{
				$this->errors[] = "Install an audio extracter (ex: avconv) !";
			}
		}

		if(isset($this->errors) && count($this->errors) > 0)
		{
			$_SESSION['errors'] = $this->errors;
			return false;
		}

		return true;
	}

	private function is_youtubedl_installed()
	{
		exec("which youtube-dl", $out, $r);
		return $r;
	}

	private function is_extracter_installed()
	{
		exec("which ".$this->config["extracter"], $out, $r);
		return $r;
	}

	private function is_valid_url($url)
	{
		return filter_var($url, FILTER_VALIDATE_URL);
	}

	private function check_outuput_folder()
	{
		if(!is_dir($this->download_path))
		{
			//Folder doesn't exist
			if(!mkdir($this->download_path, 0775))
			{
				$this->errors[] = "Output folder doesn't exist and creation failed! (".$this->download_path.")";
			}
		}
		else
		{
			//Exists but can I write ?
			if(!is_writable($this->download_path))
			{
				$this->errors[] = "Output folder isn't writable! (".$this->download_path.")";
			}
		}
		
		if(!is_dir($this->log_path))
		{
			//Folder doesn't exist
			if(!mkdir($this->log_path, 0775))
			{
				$this->errors[] = "Log folder doesn't exist and creation failed! (".$this->log_path.")";
			}
		}
		else
		{
			//Exists but can I write ?
			if(!is_writable($this->log_path))
			{
				$this->errors[] = "Log folder isn't writable! (".$this->log_path.")";
			}
		}
		
	}

	private function do_download()
	{
		$cmd = "youtube-dl";
		$cmd .= " -o ".$this->download_path."/";
		$cmd .= escapeshellarg($this->outfilename);
		
		if ($this->vformat) 
		{
			$cmd .= " --format ";
			$cmd .= escapeshellarg($this->vformat);
		}
		if($this->audio_only)
		{
			$cmd .= " -x ";
		}

		foreach($this->urls as $url)
		{
			$cmd .= " ".escapeshellarg($url);
		}

		$cmd .= " --restrict-filenames"; // --restrict-filenames is for specials chars
		if($this->config["log"])
		{
			$cmd .= " > ".$this->config["logFolder"]."/$(date  +\"%Y-%m-%d_%H-%M-%S-%N\").txt";
		}
		else
		{
			$cmd .= " > /dev/null ";
		}
		$cmd .= " & echo $!";

		shell_exec($cmd);
	}
}

?>
