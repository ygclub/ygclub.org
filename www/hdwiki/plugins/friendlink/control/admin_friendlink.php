<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('plugin');
		$this->loadplugin('friendlink');
		$this->view->setlang('zh','back');
		$plugin=$_ENV['plugin']->get_plugin_by_identifier('friendlink');
		$this->pluginid=$plugin['pluginid'];
	}

	function dodefault() {
		$friendlist=$_ENV['friendlink']->get_list();
		$this->view->assign('friendlist',$friendlist);
		$this->view->assign('pluginid',$this->pluginid);
		$this->view->display('file://plugins/friendlink/view/admin_friendlist');
	}
	
	function doremove(){
		if(!empty($this->post['friend'])){
			$_ENV['friendlink']->remove($this->post['friend']);
			$this->message('删除成功！','index.php?plugin-friendlink-admin_friendlink');
		}else{
			$this->message('请选择您要删除的项！','BACK');
		}
	}
	
	function dopass(){
		if(!empty($this->post['friend'])){
			$link=$_ENV['friendlink']->get_link_by_id($this->post['friend']);
			$_ENV['friendlink']->pass($link);
			$this->message('审核成功！','index.php?plugin-friendlink-admin_friendlink');
		}else{
			$this->message('请选择您要审核的项！','BACK');
		}
	}
	
	function doapplication(){
		if(substr($this->post['friend']['url'],0,7)!="http://"){
			$this->post['friend']['url']="http://".$this->post['friend']['url'];
		}
		if(substr($this->post['friend']['logo'],0,7)!="http://"){
			$this->post['friend']['logo']="http://".$this->post['friend']['logo'];
		}		
		$_ENV['friendlink']->addlink($this->post['friend']);
		$this->message("友情链接申请提交成功!请等待审核!",$this->setting['seo_prefix']."plugin-friendlink.php",0);
	}
}

?>
