<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->loadplugin('mapabc');
	}

	//默认函数
	function dodefault(){
		$wishlist=$_ENV['wish']->get_list();
		$this->view->assign('wishlist',$wishlist);
		$this->view->display('file://plugins/wish/view/allwish');
	}
	
	//设置标点
	function doset(){
		$did	=	string::hiconv(trim($this->post['did']));
		$title	=	string::hiconv(trim($this->post['title']));
		$description	=	string::hiconv(trim($this->post['description']));
		$zoom	=	string::hiconv(trim($this->post['zoom']));
		$cordx	=	string::hiconv(trim($this->post['cordx']));
		$cordy	=	string::hiconv(trim($this->post['cordy']));
		if( $_ENV['mapabc']->set_point( $did, $cordx, $cordy, $title, $description, $zoom )) {
			$this->message('1','',2);		
		}else{
			echo "Test";
		}
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
