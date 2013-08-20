<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{
//	var $plugin;
	
	function control(& $get,& $post){
		$this->base( & $get,& $post);
//		$this->load('user');
		$this->load('plugin');
		$this->loadplugin('sitemap');
		$this->view->setlang('zh','back');
		//$this->plugin=$_ENV['plugin']->get_plugin_by_identifier('sitemap');
	}

	function dodefault() {
		$plugin=$_ENV['plugin']->get_plugin_by_identifier('sitemap');
		$this->view->assign('pluginid',$plugin['pluginid']);
		$this->view->assign('baidu_update', $_ENV['sitemap']->get_last_update('baidu.xml') ? $this->date($_ENV['sitemap']->get_last_update('baidu.xml')) : '暂未更新');
		$this->view->assign('sitemap_update', $_ENV['sitemap']->get_last_update(HDWIKI_ROOT.'/plugins/sitemap/last_page.log') ? $this->date($_ENV['sitemap']->get_last_update(HDWIKI_ROOT.'/plugins/sitemap/last_page.log')) : '暂未更新');
		$this->view->display('file://plugins/sitemap/view/admin_default');
	}
	
	function docreatedoc() {
		$_ENV['sitemap']->rebuild();
		$this->message('正在重建词条Sitemap，请稍候','index.php?plugin-sitemap-admin_sitemap-updatedoc');
	}
	
	function doupdatedoc() {
		if(($next_offset = $_ENV['sitemap']->create_doc_page()) !== false) {
			$next_offset ++;
			$end_offset = $next_offset + 999;
			$this->message("已完成第{$next_offset}-{$end_offset}条，正在继续，请稍候",'index.php?plugin-sitemap-admin_sitemap-updatedoc');
		} else {
			$this->message('Sitemap索引文件已生成。全部已完成。', 'index.php?plugin-sitemap-admin_sitemap');
		}
	}
	
	function dosubmit() {
		$rs = $_ENV['sitemap']->submit();
		if($rs === false) {
			$this->message('相应的sitemap不存在，请先刷新sitemap再提交', 'index.php?plugin-sitemap-admin_sitemap');
		} else {
			$message = '';
			foreach ($rs as $site=>$response) {
				if(strpos($response, '200') !== false) {
					$message .= $site.' 提交成功，返回状态：'.$response.'<br />';
				} else {
					$message .= $site.' 提交失败，返回状态：'.$response.'<br />';
				}
			}
				
			$this->message($message, 'index.php?plugin-sitemap-admin_sitemap');
		}
	}
	
	function dobaiduxml() {
		$_ENV['sitemap']->create_baiduxml();
		$this->message('操作成功', 'index.php?plugin-sitemap-admin_sitemap');
	}
}

?>
