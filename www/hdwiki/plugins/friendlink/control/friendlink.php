<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->loadplugin('friendlink');
	}
	
	function dodefault(){
		$this->view->display('file://plugins/friendlink/view/friendlink');
	}

}

?>
