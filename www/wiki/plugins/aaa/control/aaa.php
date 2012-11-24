<?php

!defined('IN_HDWIKI') && exit('Access Denied');
 
class control extends base{
	var $setting;
	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->loadplugin('aaa');
		$this->load('user');
	
		$this->setting['credit_create'] = $this->plugin['aaa']['vars']['credit_create'];
		$this->setting['credit_answer'] = $this->plugin['aaa']['vars']['credit_answer'];
		$this->setting['credit_isright'] = $this->plugin['aaa']['vars']['credit_isright'];
		$this->setting['credit_chooseright'] = $this->plugin['aaa']['vars']['credit_chooseright'];
		
		$this->status[0] = '未解决';
		$this->status[1] = '已解决';
		$this->status[2] = '已关闭';
	}
	
	function dodefault() {
		
		$this->view->assign('new_asks', $_ENV['aaa']->get_list($_ENV['aaa']->opening, 0, 10));
		$this->view->assign('new_resolves', $_ENV['aaa']->get_list($_ENV['aaa']->resolved, 0, 10));
		$this->view->assign('pluginid',$this->plugin['finvite']['pluginid']);
		$this->view->display('file://plugins/aaa/view/default');

	}
	
	
	function docreate(){
		$this->load('doc');
		if (isset($this->post['create_submit'])){
			if(@trim($this->post['title'])==''||@trim($this->post['question'])==''){
				$this->message('相关词条和标题必填','BACK',0);
			}
			echo $this->post['title'];
			if(!(bool)($doc = $_ENV['doc']->get_doc_by_title($this->post['title']))){
				$this->message('词条不存在','BACK',0);
			}
			if(@!(bool)$this->post['description']){
				$this->post['description']='';
			}
			
			if($this->user['credit1'] -(intval($this->post['reward'])) + intval($this->setting['credit_create']) < 0) {
				$this->message('您的金币只有'.$this->user['credit1'].'，无法提问', 'BACK', 0);
			}
			$ask['did']=$doc['did'];
			$ask['title']=addslashes($doc['title']);
			$ask['question']=string::substring(string::stripscript($_ENV['aaa']->replace_danger_word(trim($this->post['question']))),0,250);
			$ask['description']=strip_tags(string::stripscript($_ENV['aaa']->replace_danger_word($this->post['description'])));
			$ask['reward']=intval($this->post['reward']);
			$ask['time']=$this->time;
			$ask['visible']='1'; ///////////////////////////////
			if(4 == $this->user['groupid']){
				$ask['visible'] = 1;
			}
			
			
			$id=$_ENV['aaa']->add_ask($ask);
			
			if($id) {
				$credit = -(intval($this->post['reward'])) + intval($this->setting['credit_create']);
				$_ENV['user']->add_credit($this->user['uid'],'aaa-create',0, $credit);
				$this->message('问题已发布', 'BACK',0);
			} else {
				$this->message('系统错误', 'BACK',0);
			}
		} else {
			if(is_numeric(@$this->get[2])){
				$this->load('doc');
				if((bool)($doc = $_ENV['doc']->get_doc($this->get[2]))){
					$relative_asks = $_ENV['aaa']->get_asks_by_did($doc['did'], 0, 10);		
					$this->view->assign('doc', $doc);
					$this->view->assign('relative_asks', $relative_asks);
					$this->view->assign('title', $doc['title']);
				} else {
					$this->message('词条不存在','BACK',0);
				}
			}
			$this->view->assign('type', 'ask');
			$this->view->display('file://plugins/aaa/view/create'); //添加Create表单 
		}
	}
	
	function doanswer() {
		if(!is_numeric(@$this->post['id'])){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		if(@trim($this->post['answer'])==''){
			$this->message('请填写内容','BACK',0);
		}
		if(!(bool)($ask = $_ENV['aaa']->get_ask($this->post['id']))){
			$this->message('问题不存在','BACK',0);
		}
		if($this->user['uid'] == $ask['authorid']) {
			$this->message('自问自答是不好滴...', 'BACK', 0);
		}
		if($_ENV['aaa']->user_answered($this->post['id'], $this->user['uid'])) {
			$this->message('您已回答过此问题', 'BACK', 0);
		}
		$ans['ask_id'] = $this->post['id'];
		$ans['answer'] = strip_tags(string::stripscript($_ENV['aaa']->replace_danger_word($this->post['answer'])));
		$ans['time']=$this->time;
		$id = $_ENV['aaa']->add_answer($ans);
		
		if($id) {
			$_ENV['user']->add_credit($this->user['uid'],'aaa-answer', 0, intval($this->setting['credit_answer']));
			$this->message('感谢您的回答', 'BACK',0);
		} else {
			$this->message('系统错误', 'BACK',0);
		}
		
	}
	
	function doview() {
		if(!is_numeric(@$this->get[2])){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		$ask=$_ENV['aaa']->get_ask($this->get[2]);
		if(!(bool)$ask){
			$this->message('问题不存在','index.php',0);
		}elseif($ask['visible']=='0' && $doc['authorid']!= $this->user['uid']){
			$this->message('此问题尚未通过审核','index.php',0);
		}
		
		$_ENV['aaa']->update_field('views',1,$ask['id'],0);
		
		$ask['time']=date('Y-m-d',$ask['time']);
		$ask['title'] = $ask['title'];
		$ask['description'] = nl2br($ask['description']);
		$ask['statusstr'] = $this->status[$ask['status']];
		$relative_asks = $_ENV['aaa']->get_asks_by_did($ask['did']);
		$answers = $_ENV['aaa']->get_answers_by_ask_id($ask['id']);
		foreach ($answers as $key=>$value) {
			$answers[$key]['answer'] = nl2br($answers[$key]['answer']);
		}
		
		$this->view->assign('ask',$ask);
		
		$this->view->assign('relative_asks', $relative_asks);
		$this->view->assign('answers',$answers);
		
		$this->view->vars['setting']['seo_keywords']=$ask['title'];
		$this->view->vars['setting']['seo_description']=$ask['question'];
		$this->view->display('file://plugins/aaa/view/viewask');
	}
	
	function dochoose() {
		if(!is_numeric(@$this->get[2])){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		$ans=$_ENV['aaa']->get_answer($this->get[2]);
		if(!(bool)$ans){
			$this->message('答案不存在','index.php',0);
		}
		$ask = $_ENV['aaa']->get_ask($ans['ask_id']);
		if(!(bool)$ask || $ask['authorid'] != $this->user['uid']) {
			$this->message('权限不足', 'index.php', 0);
		}
		if($ask['status'] == $_ENV['aaa']->resolved) {
			$this->message('已选择过正确答案', 'index.php', 0);
		}
		
		if((bool)$_ENV['aaa']->update_answer_field('right', 1, $ans['id']) && 
			(bool)$_ENV['aaa']->update_field('status', $_ENV['aaa']->resolved, $ask['id'])) {
			$_ENV['user']->add_credit($this->user['uid'],'aaa-chooseright', 0,  intval($this->setting['credit_chooseright']));
			$_ENV['user']->add_credit($ans['authorid'],'aaa-isright', 0,  intval($this->setting['credit_isright'])+$ask['reward']);
			$this->message('答案已选择，感谢支持', 'BACK', 0);
		}
	}
	
	function doclose() {
		if(!is_numeric(@$this->get[2])){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		$ask=$_ENV['aaa']->get_ask($this->get[2]);
		if(!(bool)$ask || $ask['authorid'] != $this->user['uid']) {
			$this->message('权限不足', 'index.php', 0);
		}
		if($ask['status'] == $_ENV['aaa']->resolved) {
			$this->message('已选择过正确答案', 'index.php', 0);
		}
		
		if((bool)$_ENV['aaa']->update_field('status', $_ENV['aaa']->closed, $ask['id'])) {
			$this->message('问题已关闭', 'BACK', 0);
		}
	}
	
	/**
	 * 词条按状态浏览列表
	 *
	 */
	function dolist() {
		if(!is_numeric(@$this->get[2])){
			$type = false;
		} else {
			$type = $this->get[2];
		}
		
		$page = max(1, intval($this->get[3]));
		$start_limit = ($page - 1) * $this->setting['list_allcredit'];
		$total=100;
		$count=$_ENV['aaa']->get_total_num($type);
		$count=($count<$total)?$count:$total;
		
		$list=$_ENV['aaa']->get_list($type, $start_limit, $this->setting['list_allcredit']);
		foreach ($list as $key=>$value) {
			$list[$key]['statusstr'] = $this->status[$value['status']];
		}
		$departstr=$this->multi($count, $this->setting['list_allcredit'], $page, 'plugin-aaa-aaa-list-'.$type);
		$this->view->assign("type",$type === false ? 'all' : $type);
		$this->view->assign("departstr",$departstr);
		$this->view->assign("status", $this->status);
		$this->view->assign('list',$list);
		$this->view->display('file://plugins/aaa/view/list');
	}

	function dodoc() {
		if(!is_numeric(@$this->get[2])){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		} else {
			$did = $this->get[2];
		}
		$this->load('doc');
		$doc = $_ENV['doc']->get_doc($did);
		if(!(bool)$doc) {
			$this->message('词条不存在', 'BACK', 0);
		}
		$page = max(1, intval($this->get[3]));
		$start_limit = ($page - 1) * $this->setting['list_allcredit'];
		$total=100;
		$count=$_ENV['aaa']->get_doc_total_num($did);
		$count=($count<$total)?$count:$total;
		
		$list=$_ENV['aaa']->get_doc_list($did, $start_limit, $this->setting['list_allcredit']);
		
		foreach ($list as $key=>$value) {
			$list[$key]['statusstr'] = $this->status[$value['status']];
		}
		$departstr=$this->multi($count, $this->setting['list_allcredit'], $page, 'plugin-aaa-aaa-doc-'.$did);
		$this->view->assign("type",'doc');
		$this->view->assign("doc", $doc);
		$this->view->assign("departstr",$departstr);
		$this->view->assign("status", $this->status);
		$this->view->assign('list',$list);
		$this->view->display('file://plugins/aaa/view/list');
	}
	
	
	function dodocrelative() {
		
	}

}

?>
