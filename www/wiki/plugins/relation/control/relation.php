<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load("doc");
		$this->load('plugin');
		$this->loadplugin('relation');
	}

	function dodefault(){
		exit('Access Denied');
	}

	function doadd(){
		$ajaxtitle=string::hiconv(trim($this->post['newname']));
		$title=string::substring(string::stripscript($_ENV['doc']->replace_danger_word(trim($ajaxtitle))),0,80);
		if(@!is_numeric($this->post['did'])){ //没有传来did 返回 操作失败
			$this->message("-1","",2);
		}elseif($ajaxtitle!=string::stripscript($ajaxtitle)){//过滤词语 返回 含有危险代码
			$this->message("-3","",2);
		}elseif(@!(bool)$_ENV['doc']->get_doc_by_title($title)){ //词条不存在 返回 关系词不存在
			$this->message("-2","",2);
		}elseif($tdoc=$_ENV['doc']->get_doc_by_title($title)){
			$_ENV['relation']->add_relation($this->post['did'],$tdoc['did']);
			$this->message("1","",2);
		}else{
			$this->message("0","",2);
		}
	}

	function dodel(){
		$fdid=$this->post['fdid'];
		$tdid=$this->post['tdid'];
		if(@!is_numeric($fdid)){ //没有传来fdid 返回 操作失败
			$this->message("-1","",2);
		}elseif(@!is_numeric($tdid)){//没有传来tdid 返回 操作失败
			$this->message("-1","",2);
		}else{
			$_ENV['relation']->remove_relation($fdid,$tdid);
			$this->message("1","",2);
		}
	}
}

?>
