<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->loadplugin('wish');
	}

	function dodefault(){
		$wishlist=$_ENV['wish']->get_list();
		$this->view->assign('wishlist',$wishlist);
		$this->view->display('file://plugins/wish/view/allwish');
	}
	
	
	function dopost(){
		$receiver=string::hiconv(trim(empty($this->post['receiver'])?'自己':$this->post['receiver']));
		$author=string::hiconv(trim(empty($this->post['author'])?'匿名':$this->post['author']));
		$wish=string::hiconv(trim($this->post['wish']));
		$_ENV['wish']->add_wish($receiver,$author,$wish);
		$this->message('1','',2);
	}
	
	function dowill(){
		$this->view->display('file://plugins/wish/view/postwish');
	}
	
}

?>
