<?php
!defined('IN_HDWIKI') && exit('Access Denied');

class control extends base {

    function control(& $get,& $post) {
        $this->base( $get, $post);
        $this->load('plugin');
        $this->loadplugin('mapabc');
        $this->view->setlang('zh','back');
    }

    function dodefault() {
		$pluginid = $this->get_pluginid();
        $this->view->assign('pluginid',$pluginid);
        $this->view->display('file://plugins/mapabc/view/admin_mapabc');
    }
    
    function get_pluginid() {
        if (is_null($this->pluginid)) {
            $plugin=$_ENV['plugin']->get_plugin_by_identifier('mapabc');
            $this->pluginid = $plugin['pluginid'];
        }
        return $this->pluginid;
    }

}

?>
