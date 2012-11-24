<?php !defined('IN_HDWIKI') && exit('Access Denied');
class filecheckmodel {
	var $base;
	var $db;
	var $dir;
	var $hdwiki_root;
	function filecheckmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
		$this->hdwiki_root = $this->formatpath(HDWIKI_ROOT);
		$this->dir = $this->hdwiki_root.'data/md5_file/';
	}

	function set($filename = '')
	{
		if($filename == '' || !file_exists($this->dir.$filename)) 
		{
			$this->file = $this->dir.date('Y-m-d');
		}
		else
		{
			$this->file = $this->dir.$filename;
		}
	}

	function check($dir, $exts = 'php|js|html', $require = 1)
	{
		$list = $this->lists($dir, $exts, $require);
		if(!$list) return false;
		$files = array();
		$data = file_get_contents($this->file);
		foreach($list as $v)
		{
			$md5 = md5_file($v);
			$filepath = str_replace(HDWIKI_ROOT, '', $v);
			$line = $md5.' '.$filepath."\n";
			if(strpos($data, $line) !== false) continue;
			if(strpos($data, ' '.$filepath."\n") !== false) $files['edited'][] = $filepath;
			else $files['unknow'][] = $filepath;
		}
		return $files;
	}

	function make($exts = 'php|js|html')
	{
		file::forcemkdir($this->dir);
		$list = $this->lists($this->hdwiki_root);
		if(!$list) return false;
		$data = '';
		foreach($list as $v)
		{
			$data .= md5_file($v).' '.str_replace($this->hdwiki_root, '', $v)."\n";
			
		}
		return file::writetofile($this->file, $data);
	}

	function lists($dir, $exts = 'php|js|html', $require = 1)
	{
		$files = array();
		if($require)
		{
			@set_time_limit(600);
			$list = $this->get_files_from_dir($this->hdwiki_root);
			if(!$list) return false;
			foreach($list as $v)
			{
				if(is_file($v) && preg_match("/\.($exts)$/i", $v)) $files[] = $v;
			}
		}
		else
		{
			$list = glob($dir.'*');
			foreach($list as $v)
			{
				if(is_file($v) && preg_match("/\.($exts)$/i", $v)) $files[] = $v;
			}
		}
		return $files;
	}
	
	function get_files_from_dir($path, $exts = '', $list= array())
	{
		$path = $this->formatpath($path);
		$files = glob($path.'*');
		foreach($files as $v)
		{	
			if((!$exts || preg_match("/\.($exts)/i", $v)))
			{
				$list[] = $v;
				if(is_dir($v)){	
					$list = $this->get_files_from_dir($v, $exts, $list);
				}
			}
		}
		return $list;
	}
	//把路径格式化为"/"形式的
	function formatpath($path){
		$path = str_replace('\\', '/', $path);
		if(substr($path, -1) != '/'){
			$path = $path.'/';
		}
		return $path;	
	}
	
	function dirs()
	{
		$dirs = array();
		$list = glob($this->hdwiki_root.'*');
		foreach($list as $v)
		{
			if(is_dir($v)) $dirs[] = str_replace($this->hdwiki_root, '', $v);
		}
		return $dirs;
	}

	function checked_dirs()
	{
		return array('block','control','data','install','js','lang','lib','model','plugins','style','uploads','view');
	}

	function md5_files()
	{
		return array_map('basename', glob($this->dir.'*'));
	}

	function scan_dir($dir, $option = array(), $file_list=array())
	{
		if($dir == './')$dir = '';
		$fp = dir($this->hdwiki_root.$dir);
		while (false !== ($en = $fp->read())) {
			if ($en != '.' && $en != '..') {
				if (is_dir($this->hdwiki_root.$dir.'/'.$en) && !empty($dir)) {
					$file_list = $this->scan_dir($dir.'/'.$en, $option, $file_list);
				}
				else 
				{
					$key = strrpos($dir.'/'.$en, '/') == 0 ? $en : $dir.'/'.$en;
					if(!empty($option))
					{
						$p = pathinfo($this->hdwiki_root.$dir.'/'.$en, PATHINFO_EXTENSION);
						if (in_array($p, $option)) {
							$file_list[$key] = md5_file($this->hdwiki_root.$dir.'/'.$en);
						}
					}
					else 
					{
						$file_list[$key] = md5_file($this->hdwiki_root.$dir.'/'.$en);
					}
				}
			}
		}
		return $file_list;
	}
	//处理数组用于显示结果页
	function getlist($filelists){
		if($filelists){
			foreach($filelists as $key=>$filelist){
				$filelists[$key]['funtimes'] = isset($filelist['func']) ? count($filelist['func']) : 0;
				$filelists[$key]['funstr'] = $this->get_func_code($filelist['func']);
				$filelists[$key]['codetimes'] = isset($filelist['code']) ? count($filelist['code']) : 0;
				$filelists[$key]['codestr'] = $this->get_func_code($filelist['code']);
				$filelists[$key]['key'] = $this->urlcode($key);
			}
		}
		return $filelists;
	}
	//得到函数和代码拼接的字符串
	function get_func_code($allvals){
		$str = '';
		if(isset($allvals)){
			foreach ($allvals as $keys=>$vals)
			{
				$d[$keys] = strtolower($vals[1]);
			}
			$d = array_unique($d);
			foreach ($d as $vals)
			{
				$str .= "<font color='red'>".$vals."</font>  ";
			}
		}
		return $str;
	}
	//把url里的-和.替换掉，以便于get传输
	function urlcode($url,$remove=0){
		$old = array('-','.');
		$new = array('###','^^^');
		if($remove){
			$url = urldecode(str_replace($new, $old, $url));
		}else{
			$url = str_replace($old, $new, urlencode($url));
		}
		return $url;
	}
	//编辑时 函数和代码的onclick事件
	function getjscode($func,$iscode = 0){
		 if ($func) {
		 	 $func = array_unique($func);
		 	 foreach ($func as $val)
			 {
			 	if($val)
			 	{
			 		$val = $iscode ? htmlentities($val) : $val;
			 		$out .= "<input type='button' onclick=\";findInPage(this.form.code,'$val');\" value='".$val."' class=\"inp_btn2 m-r10\"> ";
			 	}
			 }
		 }
		return $out;
	}
}