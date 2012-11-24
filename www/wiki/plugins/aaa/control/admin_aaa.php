<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load('plugin');
		$this->loadplugin('aaa');
		$this->view->setlang('zh','back');
	}

	function dodefault() {
		
		$title=isset($this->post['qtitle'])?trim($this->post['qtitle']):trim($this->get[2]);
		$author=isset($this->post['qauthor'])?trim($this->post['qauthor']):trim($this->get[3]);
		$status=isset($this->post['qstatus'])?trim($this->post['qstatus']):trim($this->get[4]);
		$keywords=isset($this->post['qkeywords'])?trim($this->post['qkeywords']):trim($this->get[5]);
		
		$page = max(1, intval(end($this->get)));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		
		$count = $_ENV['aaa']->search_num($title,$author,$status,$keywords);
		$searchdata='plugin-aaa-admin_aaa-default-'.urlencode("$title-$author-$status-$keywords");
		$departstr=$this->multi($count, $num, $page,$searchdata);
		$asklist=$_ENV['aaa']->search($start_limit,$num,$title,$author, $status,$keywords);
		
		$this->view->assign("searchdata", $searchdata.'-'.$page);
		$this->view->assign("asksum",$count);
		$this->view->assign("qtitle",$title);
		$this->view->assign("qauthor",$author);
		$this->view->assign("qstatus",$status);
		$this->view->assign("qkeywords",$keywords);
		
		$this->view->assign("departstr",$departstr);
		$this->view->assign("asklist",$asklist);
		
		$this->view->assign('pluginid',$this->plugin['aaa']['pluginid']);
		$this->view->display('file://plugins/aaa/view/admin_default');
	}
	
	function dodel() {
		if(!is_numeric(@$this->get[2])){
			$this->message($this->view->lang['parameterError'],'BACK');
		}
		$_ENV['aaa']->remove_ask($this->get[2]);
		$this->message('已删除', 'BACK');
		
	}
	
	function dousage() {
		$html = <<<HTML
<div class="columns">
<h2 class="col-h2">关于本词条的提问</h2>
<a href="{\$setting['seo_prefix']}plugin-aaa-aaa-create-{\$doc['did']}{\$setting['seo_suffix']}" class="more">查看全部/我要提问&gt;&gt;</a>
<dl class="col-dl">
<!--{loop \$questionlist \$data}-->
<dd><a href="{\$setting['seo_prefix']}plugin-aaa-aaa-view-{\$data['id']}{\$setting['seo_suffix']}" >{\$data['question']}</a></dd>
<!--{/loop}-->
</dl>
</div>
HTML;
		$html = nl2br(htmlspecialchars($html));
		$this->view->assign('html', $html);
		$this->view->display('file://plugins/aaa/view/admin_usage');
	}

}

?>
