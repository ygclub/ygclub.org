<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('plugin');
	}

	function dodefault() {
		 $plugin=$_ENV['plugin']->get_plugin_by_identifier('relation');
		 $pluginid=$plugin['pluginid'];
		 $this->header('admin_plugin-setvar-'.$pluginid);
	}
}
?>