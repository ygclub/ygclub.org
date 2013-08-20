<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('plugin');
		$this->loadplugin('wish');
		$this->view->setlang('zh','back');
	}

	function dodefault() {
		$wishlist=$_ENV['wish']->get_list();
		$this->view->assign('wishlist',$wishlist);
		$this->view->display('file://plugins/wish/view/admin_wishlist');
	}

	function doclear(){
		$_ENV['wish']->remove_wish();
		$this->message('全部删除成功!','index.php?plugin-wish-admin_wish');	
	}
	
	function doremove(){
		$wallid=trim($this->post['wallid']);
		$id=substr($wallid,4);
		$_ENV['wish']->remove_wish($id);
		$this->message('1','',2);
	}
}

?>
