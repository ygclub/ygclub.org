<?php

!defined('IN_HDWIKI') && exit('Access Denied');
class control extends base{

	function control(& $get,& $post){
		$this->base( & $get,& $post);
		$this->load("doc");
		$this->load("category");
		$this->load("user");
		$this->load("usergroup");
		$this->load("synonym");
		$this->load("attachment");
		$this->load("comment");
		$this->load("innerlink");
		$this->load("search");
	}

	function doview() {		
		$this->_anti_copy();
		$this->load("reference");
		if(!is_numeric(@$this->get[2])){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		$doc=$this->db->fetch_by_field('doc','did',$this->get[2]);
		if(!(bool)$doc){
			$this->message($this->view->lang['docNotExist'],'index.php',0);
		}elseif($synonym=$_ENV['synonym']->get_synonym_by_src(addslashes($doc['title']))){
			$this->view->assign('synonymdoc',$synonym['srctitle']);
			$this->get[2]=$synonym['destdid'];
			$this->doview();
			exit;
		}elseif($doc['visible']=='0' &&!$this->checkable('doc-audit') && $doc['lasteditorid']!= $this->user['uid']){
			if($doc['editions']>0){
				$doc=$_ENV['doc']->get_lastdoc($this->get[2],$doc['lastedit']);
				if(!$doc){
					$this->message($this->view->lang['viewDocTip4'],'index.php',0);
				}
			}else{
				$this->message($this->view->lang['viewDocTip4'],'index.php',0);
			}
		}elseif(@$this->get[3]=="locked"&&$doc['locked']==1){
			$this->view->assign('locked','1');
		}
		$_ENV['doc']->update_field('views',1,$doc['did'],0);
		$this->view->vars['setting']['seo_keywords']=$doc['tag'];
		$this->view->vars['setting']['seo_description']=$doc['summary'];
		$doc['tag']=$_ENV['doc']->spilttags($doc['tag']);
		$doc['rawtitle']=$doc['title'];
		$editors=$_ENV['usergroup']->get_userstar( array($doc['authorid'],$doc['lasteditorid']) );
		
		//eval($this->plugin['ucenter']['hooks']['doc_user_image']);
		UC_OPEN && $_ENV['ucenter']->doc_user_image($editors,$doc);
		$author=$editors[$doc['author']];
		$author?$this->view->assign('author',$author)
			:$this->view->assign('author_removed',1);
			
		if ($doc['lasteditorid'] > 0 && $doc['time'] < $doc['lastedit']){
			$lasteditor = $editors[$doc['lasteditor']];
			
			$lasteditor ? $this->view->assign('lasteditor',$lasteditor)
				:$this->view->assign('lasteditor_removed',1);
		}
		$doc['lastedit']=date('Y-m-d',$doc['lastedit']);
		$navigation= $_ENV['doc']->get_cids_by_did($doc['did']);
		
		if(!empty($this->setting['random_open'])){
			$this->load('anticopy');
			$_ENV['anticopy']->add_randomstr($doc['content']);
		}		
		
		$doc['content']=$_ENV['innerlink']->get($doc['did'], $doc['content']);
		$doc['sectionlist']=$_ENV['doc']->splithtml($doc['content']);
		$doc['doctitle'] = $doc['title'];
		$doc['title'] = addslashes($doc['title']);
		$sectionlist=$_ENV['doc']->getsections($doc['sectionlist']);
		$editlock['locked']=$_ENV['doc']->iseditlocked($doc['did']);
		if($editlock['locked']){
			$editlock['user']=$this->db->fetch_by_field('user','uid',$editlock['locked']);
		}
		//eval($this->plugin['hdapi']['hooks']['uniontitle']);
		$this->load("hdapi");
		if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
			$this->view->assign("doclink",1);
			$uniontitle = $_ENV["hdapi"]->get_uniontitle_by_did($doc["did"]);
			$uniontitle = is_array($uniontitle)?$uniontitle["docdeclaration"]:"";
			$this->view->assign("uniontitle",$uniontitle);
		}
		//adv start
		$this->load('adv');
		$categorys=array();
		foreach($navigation as $category){
			$categorys[]=$category['cid'];
		}
		$advlist=$_ENV['adv']->adv_doc_filter($this->advertisement,$categorys);
		if($advlist){
			$this->view->assign('advlist',$advlist);
		}
		//adv end
		
		if($this->setting['attachment_open']){
			$attachment=$_ENV['attachment']->get_attachment('did',$doc['did']);
			$this->view->assign('attachment',$attachment);
		}
		$synonyms=$_ENV['synonym']->get_synonym_by_dest('',$doc['title']);
		$referencelist = ($this->setting['base_isreferences'] === '0')? array() :$_ENV['reference']->getall($doc['did']);
		$doc['title']=htmlspecialchars(stripslashes($doc['title']));
		$doc['doctitle']=$doc['title'];
		
		$relatelist = array();
		$relatelist = $_ENV['doc']->get_related_doc($doc['did']);
		if(!count($relatelist)){
			if($this->setting['isrelate']){
				$relatelist = array_unique(explode(';',$this->setting['relateddoc']));
			}
		}
		
		$neighbor=$_ENV['doc']->get_neighbor($this->get[2]);
		$coin_download = isset($this->setting['coin_download'])?$this->setting['coin_download']:10;
		
		$this->view->assign('docletter',$doc['letter']);
		$this->view->assign('neighbor',$neighbor);
		$this->view->assign('relatelist',$relatelist);
		$this->view->assign('userid',$this->user['uid']);
		$this->view->assign('groupid',$this->user['groupid']);
		$this->view->assign('synonyms',$synonyms);
		$this->view->assign('doc',$doc);
		$this->view->assign("attachment_type", implode("|",$_ENV['attachment']->get_attachment_type()));
		$this->view->assign('attach_download',$this->checkable('attachment-download'));
		$this->view->assign('audit', ($this->checkable('admin_doc-audit') || $this->checkable('doc-audit')));
		$this->view->assign('relate', ($this->checkable('doc-getrelateddoc') && $this->checkable('doc-addrelatedoc')));
		$this->view->assign('synonym_audit',$this->checkable('synonym-savesynonym'));
		$this->view->assign('attachment_upload',$this->checkable('attachment-upload'));
		$this->view->assign('attachment_remove',$this->checkable('attachment-remove'));
		$this->view->assign('comment_add',$this->checkable('comment-add'));
		$this->view->assign('doc_edit',$this->checkable('doc-edit'));
		$this->view->assign('doc_editletter',$this->checkable('doc-editletter'));
		
		$this->view->assign('navtitle',$doc['doctitle'].'-');
		$this->view->assign("searchtext",$doc['title']);
		$this->view->assign('editlock',$editlock);
		$this->view->assign('sectionlist',$sectionlist);
		$this->view->assign('navigation',$navigation);
		$this->view->assign("referencelist", $referencelist);
		$this->view->assign("coin", $this->user['credit1']);
		$this->view->assign("coin_download", $coin_download);
		//$this->view->display('viewdoc');
		$_ENV['block']->view('viewdoc');
	}

	function docreate(){
		if(4 != $this->user['groupid'] && ($this->time-$this->user['regtime'] < $this->setting['forbidden_edit_time']*60)){
			$this->message($this->view->lang['editTimeLimit1'].$this->setting['forbidden_edit_time'].$this->view->lang['editTimeLimit2'],'BACK',0);
		}
		if(!isset($this->post['create_submit'])){
			if('0' === $this->user['checkup']){
				$this->message($this->view->lang['createDocTip17'],'BACK',0);
			}
			if(isset($this->post['title'])){
				$this->view->assign('title',htmlspecialchars(stripslashes($this->post['title'])));
			}
			if(@is_numeric($this->get[2])){
				$category=$_ENV['category']->get_category($this->get[2]);
				$this->view->assign("category",$category);
			}
			$this->view->assign('navtitle',$this->view->lang['createDoc']."-");
			$this->view->display('createdoc');
		}else if(!isset($this->post['publishsubmit'])){
			if(trim($this->post['title'])=="" ){
				$this->message($this->view->lang['createDocTip1'],'BACK',0);
			}
			if($synonym=$_ENV['synonym']->get_synonym_by_src($this->post['title'])){
				$this->view->assign('synonymdoc',$synonym['srctitle']);
				$this->get[2]=$synonym['destdid'];
				$this->doview();
				exit;
			}
			if($_ENV['doc']->have_danger_word($this->post['title'])){
				$this->message($this->view->lang['docHaveDanerWord'],'BACK',0);
			}
			if(!(bool)$_ENV['category']->vilid_category($this->post['category'])){
				$this->message($this->view->lang['categoryNotExist'],'BACK',0);
			}
			$title=string::substring(string::stripscript($_ENV['doc']->replace_danger_word(trim($this->post['title']))),0,80);
			if(!(bool)$title){
				$this->message($this->view->lang['createDocTip16'],'BACK',0);
			}
			$data=$this->db->fetch_by_field('doc','title',$title);
			if((bool)$data){
				$this->header('doc-view-'.$data['did']);
			}
			$doc['title']=htmlspecialchars($title);
			$doc['cid']=$this->post['category'];
			$doc['did']=$_ENV['doc']->add_doc_placeholder($doc);
			
			$this->view->assign("savetime",60000);
			$this->view->assign("filter_external",$this->setting['filter_external']);
			//eval($this->plugin['hdapi']['hooks']['readcontent']);
			$this->load("hdapi");
			if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
				if($_ENV["hdapi"]->islock($doc["title"])){
					$doc["locked"]=1;
					$this->message("�ô������ڱ��ٿ�������վ�����༭��","BACK",0);
				}else{
					$_ENV["hdapi"]->lock($doc["title"]);
					$content=$_ENV["hdapi"]->get_content($doc["title"]);
					if(is_string($content) && !empty($content)){$doc["content"]=$content;}
				}
			}
			
			$this->view->assign('navtitle',"$title-".$this->view->lang['createDoc']."-");
			$this->view->assign("page_action","create");
			$this->view->assign("attachment_open",$this->setting['attachment_open']);
			//$this->view->assign("attachment_type","['".implode("','",$_ENV['attachment']->get_attachment_type())."']");
			$this->view->assign('attachment_size',$this->setting['attachment_size']);
			
			$doc['title']=stripcslashes($doc['title']);
			eval($this->plugin['doctemplate']['hooks']['doctemplate']);//�������ģ�档
			$this->view->assign('doc',$doc);
			$this->view->assign("doc_verification_create_code", ($this->setting['checkcode']!=3 && $this->setting['doc_verification_create_code']));
			
			$this->view->assign('g_img_big',$this->setting['img_width_big']);
			$this->view->assign('g_img_small',$this->setting['img_width_small']);
			//$this->view->display('editor');
			$_ENV['block']->view('editor');
		}else{
			if($this->setting['checkcode']!=3 && $this->setting['doc_verification_create_code'] && strtolower($this->post['code'])!=$_ENV['user']->get_code()){
				$this->message($this->view->lang['codeError'],'BACK',0);
			}
			if(@trim($this->post['content'])==''||@trim($this->post['title'])==''){
				$this->message($this->view->lang['contentIsNull'],'BACK',0);
			}
			$doc['title']=string::substring(string::stripscript($_ENV['doc']->replace_danger_word(trim($this->post['title']))),0,80);
			$_doc=$this->db->fetch_by_field('doc','title',$doc['title']);
			if((bool)$_doc && !empty($_doc['content'])){
				$this->message($this->view->lang['createDocTip5'],'BACK',0);
			}
			if(!(bool)$_ENV['category']->vilid_category($this->post['category'])){
				$this->message($this->view->lang['categoryNotExist'],'BACK',0);
			}
			if(@!(bool)$this->post['summary']){
				$doc['summary']=trim(strip_tags($_ENV['doc']->replace_danger_word($this->post['summary'])));
			}
			$doc['did']=$this->post['did'];
			$doc['letter']=string::getfirstletter($this->post['title']);
			$doc['category']=$this->post['category'];
			
			//$doc['tags']=$_ENV['doc']->jointags($this->post['tags']);
			$doc['tags']=$this->post['tags'];
			$doc['tags']=$_ENV['doc']->replace_danger_word($doc['tags']);
			$doc['content']=string::stripscript($_ENV['doc']->replace_danger_word($this->post['content']));
			$doc['content']= $this->setting['auto_picture']?$_ENV['doc']->auto_picture($doc['content'],$doc['did']):$doc['content'];
			$doc['summary']=trim(strip_tags($_ENV['doc']->replace_danger_word($this->post['summary'])));
			$doc['summary']=(bool)$doc['summary']?$doc['summary']:$doc['content'];
			$doc['summary']=trim(string::convercharacter(string::substring(strip_tags($doc['summary']),0,100)));
			$doc['images']=util::getimagesnum($doc['content']);
			$doc['time']=$this->time;
			$doc['words']=string::hstrlen($doc['content']);
			$doc['visible']=$this->setting['verify_doc']?'0':'1';
			if(strpos($this->user['regulars'], 'doc-immunity') !== false || 4 == $this->user['groupid'] || !$this->setting['verify_doc']){
				$doc['visible'] = 1;
			}
			if($doc['visible'] == 1){
				$_ENV['user']->add_credit($this->user['uid'],'doc-create',$this->setting['credit_create'],$this->setting['coin_create']);
			}
			/*foreach($this->post['tags'] as $search_tags){
				$doc['search_tags'] .=string::convert_to_unicode($search_tags).";";
			}*/
			
			$did=$_ENV['doc']->add_doc($doc);
			$_ENV['user']->update_field('creates',$this->user['creates']+1,$this->user['uid']);	
			//$_ENV['category']->update_category_docs($this->post['category']);
			//�����������Ӵ���,���·����´�������������doc.class.php->add_doc_placeholder()->add_doc_category()
			
			//�����Ѿ����ٱ༭ҳ���ϴ�
			//$message=$_ENV['attachment']->upload_attachment($did);
			
			$_ENV['innerlink']->update($doc['title'], $did);
			$_ENV['doc']->unset_editlock($doc['did'],$this->user['uid']);
			
			$this->_send_noticemail($did, $doc, 'doc-create');

			//eval($this->plugin["ucenter"]["hooks"]["create_feed"]);
			UC_OPEN && $_ENV['ucenter']->create_feed($doc,$did);
			
			//eval($this->plugin['hdapi']['hooks']['postcontent']);
			$this->load("hdapi");
			if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
				$_ENV["hdapi"]->post_content($doc["title"],$doc["content"]);
			}
			
			if(1 == $this->setting['cloud_search'] && 1 == $doc['visible'] ) {
				// �������� ֪ͨ������
				$_ENV['search']->cloud_change(array('dids'=>$did,'mode'=>'1'));
			}
			
			if((bool)$message){
				$this->message($message,$this->setting['seo_prefix']."doc-view-".$did.$this->setting['seo_suffix'],0);
			}else{
				$msg=$this->view->lang['docPublishSuccess'];
				if($this->setting['hdapi_autoshare_create']){$msg.="<script>$.get('index.php?hdapi-hdautosns-create-'+$did);</script>";}
			
				$this->message($msg, $this->setting['seo_prefix']."doc-view-".$did.$this->setting['seo_suffix'], 0);
			}
		}
	}

	function doverify(){
		$ajaxtitle=trim($this->post['title']);
		if (WIKI_CHARSET == 'GBK'){$ajaxtitle = string::hiconv($ajaxtitle);}
		$title=string::substring(string::stripscript($ajaxtitle),0,80);
		if($_ENV['doc']->have_danger_word($title)){
			$this->message("-1","",2);
		}
		if($ajaxtitle!=string::stripscript($ajaxtitle)){
			$this->message("-2","",2);
		}
		if($synonym=$_ENV['synonym']->get_synonym_by_src($ajaxtitle)){
			$this->message($synonym[destdid]." ".$synonym[desttitle],"",2);
		}
		$data=$this->db->fetch_by_field('doc','title',$title);
		if(!(bool)$data){
			$this->message("1","",2);
		}else{
			$this->message("0","",2);
		}
	}
	
	function doedit(){
		$this->_anti_copy();
		if(isset($this->post['predoctitle'])){
			$title = $this->post['predoctitle'];
			$content=string::stripscript($_ENV['doc']->replace_danger_word($this->post['content']));
			$this->view->assign("content",stripslashes($content));
			$this->view->assign("title",$title);
			//$this->view->display("previewdoc");
			$_ENV['block']->view('previewdoc');
			return;
		}
		if(isset($this->post['tagtext'])){
			$tags = $this->post['tagtext'];
			if(string::hstrtoupper(WIKI_CHARSET)=='GBK'){
				$tags=string::hiconv($tags,'gbk','utf-8');
			}
			$tags = trim(strip_tags($_ENV['doc']->replace_danger_word($tags)));
			$did = $this->post['did'];
			if(!is_numeric($did)){
				exit($this->view->lang['parameterError']);
			}
			$_ENV['doc']->update_field('tag',$tags,$did);
			echo 'OK';
			return;
		}
		if('0' === $this->user['checkup']){
			$this->message($this->view->lang['createDocTip17'],'BACK',0);
		}
		if(4 != $this->user['groupid'] && ($this->time-$this->user['regtime'] < $this->setting['forbidden_edit_time']*60)){
			$this->message($this->view->lang['editTimeLimit1'].$this->setting['forbidden_edit_time'].$this->view->lang['editTimeLimit2'],'BACK',0);
		}
		@$did=isset($this->get[2])?$this->get[2]:$this->post['did'];
		if(!is_numeric($did)){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		$doc=$this->db->fetch_by_field('doc','did',$did);
		if(!(bool)$doc){
			$this->message($this->view->lang['docNotExist'],'index.php',0);
		}
		if($doc['visible']=='0'&&!$this->checkable('doc-audit') && $doc['lasteditorid']!= $this->user['uid']){
			$this->message($this->view->lang['viewDocTip4'],'index.php',0);
		}
		if(!isset($this->post['publishsubmit'])){
			$this->load("reference");
			$editlockuid=$_ENV['doc']->iseditlocked($did);
			if($editlockuid!=0&&$editlockuid!=$this->user['uid']){
				$this->message($this->view->lang['viewDocTip5'].$this->view->lang['viewDocTip6'],'BACK',0);
			}
			//eval($this->plugin['hdapi']['hooks']['readcontent']);
			$this->load("hdapi");
			if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
				if($_ENV["hdapi"]->islock($doc["title"])){
					$doc["locked"]=1;
					$this->message("�ô������ڱ��ٿ�������վ�����༭��","BACK",0);
				}else{
					$_ENV["hdapi"]->lock($doc["title"]);
					$content=$_ENV["hdapi"]->get_content($doc["title"]);
					if(is_string($content) && !empty($content)){$doc["content"]=$content;}
				}
			}
			if($doc['locked']){
				$this->header("doc-view-".$doc['did']."-locked");
			}
			
			$this->view->assign("savetime",60000);
			$ramus=isset($this->get[3])?$this->get[3]:'';
			$autosave=$_ENV['doc']->is_autosave($this->user['uid'],$doc['did']);
			if((bool)$autosave){
				$doc['content']=$autosave['content'];
				$doc['autosavetime']=$this->date($autosave['time']);
			}
			$referencelist = $_ENV['reference']->getall($doc['did']);
			//$doc['tag']=$_ENV['doc']->spilttags($doc['tag']);
			//$attachment=$_ENV['attachment']->get_attachment('did',$doc['did']);
			//$this->view->assign('attachment',$attachment);
			$this->view->assign('navtitle',$doc['title'].'-'.$this->view->lang['editDoc'].'-');
			$this->view->assign("page_action","edit");
			//$this->view->assign("attachment_power",$this->checkable('attachment-remove'));
			//$this->view->assign("attachment_type","['".implode("','",$_ENV['attachment']->get_attachment_type())."']");
			$this->view->assign("filter_external",$this->setting['filter_external']);
			$doc = str_replace("&", "&amp;", $doc);
			$doc['title']=htmlspecialchars(stripslashes($doc['title']));
			$this->view->assign("doc",$doc);
			$this->view->assign("referencelist", $referencelist);
			$this->view->assign("doc_verification_edit_code", ($this->setting['checkcode']!=3 && $this->setting['doc_verification_edit_code']));
			$this->view->assign('g_img_big',$this->setting['img_width_big']);
			$this->view->assign('g_img_small',$this->setting['img_width_small']);
			//$this->view->display("editor");
			$_ENV['block']->view('editor');
		}else{
			if($this->setting['checkcode']!=3 && $this->setting['doc_verification_edit_code'] && strtolower($this->post['code'])!=$_ENV['user']->get_code()){
				$this->message($this->view->lang['codeError'],'BACK',0);
			}
			if(trim($this->post['content'])==""){
				$this->message($this->view->lang['contentIsNull'],'BACK',0);
			}
			//$doc['tags']=$_ENV['doc']->jointags($this->post['tags']);
			$doc['tags']=$this->post['tags'];
			$doc['tags']=$_ENV['doc']->replace_danger_word($doc['tags']);
			$doc['content']=string::stripscript($_ENV['doc']->replace_danger_word($this->post['content']));
			$doc['content']= $this->setting['auto_picture']?$_ENV['doc']->auto_picture($doc['content'], $did):$doc['content'];
			$doc['summary']=trim(strip_tags($_ENV['doc']->replace_danger_word($this->post['summary'])));
			$doc['summary']=(bool)$doc['summary']?$doc['summary']:$doc['content'];
			$doc['summary'] =trim(string::convercharacter(string::substring(strip_tags($doc['summary']),0,100)));
			$doc['time']=$this->time;
			
			$doc['reason']=htmlspecialchars(trim(implode(',',$this->post['editreason'])," \t\n,"));
			/*foreach($this->post['tags'] as $search_tags){
				$doc['search_tags'] .=string::convert_to_unicode($search_tags).";";
			}*/
			
			if (0 == $doc['visible'] && $this->user['uid'] == $doc['lasteditorid'] && $this->user['type'] == 2){
				$_ENV['doc']->edit_unaudit_doc($doc);
				$_ENV['doc']->unset_editlock($doc['did'],$this->user['uid']);
				$this->message($this->view->lang['docPublishSuccess'],$this->setting['seo_prefix']."doc-view-".$doc['did'].$this->setting['seo_suffix'],0);
			}
			
			if( strpos($this->user['regulars'], 'doc-audit') !== false
				|| strpos($this->user['regulars'], 'doc-immunity') !== false
				|| (empty($this->user['regulars']) && $this->user['type'] == 1)
			){
				$doc['visible'] = 1;
			}else{
				$doc['visible']=$this->setting['verify_doc']?'0':'1';
			}
			
			$_ENV['doc']->edit_doc($doc,"1");
			$_ENV['doc']->unset_editlock($doc['did'],$this->user['uid']);
			if($doc['visible']==1 && $_ENV['doc']->is_addcredit($doc['did'],$this->user['uid'])){
				$_ENV['user']->add_credit($this->user['uid'],'doc-edit',$this->setting['credit_edit'],$this->setting['coin_edit']);
			}
			$_ENV['user']->update_field('edits',$this->user['edits']+1,$this->user['uid']);
			$_ENV['doc']->del_autosave('',$this->user['uid'],$doc['did']);
			/*
			$_ENV['attachment']->update_desc($this->post['attachment_id'],$this->post['attachment_desc']);
			if($this->checkable('attachment-remove')){
				$attachmentlist=$_ENV['attachment']->get_attachment('did',$doc['did']);
				for($i=0;$i<count($attachmentlist);$i++){
					if($attachmentlist[$i]['isimage']=="0"&&!in_array($attachmentlist[$i]['id'],(array)$this->post['attachment_id'])){
						@unlink($attachmentlist[$i]['attachment']);
						$remove_attachid[]=$attachmentlist[$i]['id'];
					}
				}
				$_ENV['attachment']->remove($remove_attachid);
			}
			$message=$_ENV['attachment']->upload_attachment($doc['did']);
			*/
			
			$this->_send_noticemail($did, $doc, 'doc-edit');
			
			//eval($this->plugin["ucenter"]["hooks"]["edit_feed"]);
			UC_OPEN && $_ENV['ucenter']->edit_feed($doc);
			
			//eval($this->plugin['hdapi']['hooks']['postcontent']);
			$this->load("hdapi");
			if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
				$_ENV["hdapi"]->post_content($doc["title"],$doc["content"]);
			}
			// �༭������֪ͨ������
			if(1 == $this->setting['cloud_search']) {
				$_ENV['search']->cloud_change(array('dids'=>$did,'mode'=>'2'));
			}
			if((bool)$message){
				$this->message($message,$this->setting['seo_prefix']."doc-view-".$doc['did'].$this->setting['seo_suffix'],0);
			}else{
				$msg=$this->view->lang['docPublishSuccess'];
				if($this->setting['hdapi_autoshare_edit']){$msg.="<script>$.get('index.php?hdapi-hdautosns-edit-'+$did);</script>";}

				$this->message($msg, $this->setting['seo_prefix']."doc-view-".$doc['did'].$this->setting['seo_suffix'],0);
			}
		}
	}

	function doeditsection(){
		$this->_anti_copy();
		if(4 != $this->user['groupid'] && ($this->time-$this->user['regtime'] < $this->setting['forbidden_edit_time']*60)){
			$this->message($this->view->lang['editTimeLimit1'].$this->setting['forbidden_edit_time'].$this->view->lang['editTimeLimit2'],'BACK',0);
		}
		if('0' === $this->user['checkup']){
			$this->message($this->view->lang['createDocTip17'],'BACK',0);
		}
		@$did=isset($this->get[2])?$this->get[2]:$this->post['did'];
		@$id=isset($this->get[3])?$this->get[3]:$this->post['section_id'];
		if(!is_numeric($did)||!is_numeric($id)){
			$this->message($this->view->lang['parameterError'],'index.php',0);
		}
		$doc=$this->db->fetch_by_field('doc','did',$did);
		//eval($this->plugin['hdapi']['hooks']['readcontent']);
		$this->load("hdapi");
		if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
			if($_ENV["hdapi"]->islock($doc["title"])){
				$doc["locked"]=1;
				$this->message("�ô������ڱ��ٿ�������վ�����༭��","BACK",0);
			}else{
				$_ENV["hdapi"]->lock($doc["title"]);
				$content=$_ENV["hdapi"]->get_content($doc["title"]);
				if(is_string($content) && !empty($content)){$doc["content"]=$content;}
			}
		}
			
		if(!(bool)$doc){
			$this->message($this->view->lang['docNotExist'],'index.php',0);
		}
		if($doc['visible']=='0'&&!$this->checkable('admin_doc-audit')){
			$this->message($this->view->lang['viewDocTip4'],'index.php',0);
		}
		if(!isset($this->post['publishsubmit'])){
			$editlockuid=$_ENV['doc']->iseditlocked($did);
			if($editlockuid!=0&&$editlockuid!=$this->user['uid']){
				$this->message($this->view->lang['viewDocTip5'].$this->view->lang['viewDocTip6'],'BACK',0);
			}
			if($doc['locked']){
				$this->header("doc-view-".$doc['did']."-locked");
			}
			$array_section=$_ENV['doc']->splithtml($doc['content']);
			if(!isset($array_section[$id+1]['value'])){
				$this->header('doc-edit-'.$did);
			}
			//$doc['tag']=$_ENV['doc']->spilttags($doc['tag']);
			$doc['content']=$array_section[$id+1]['value'];
			
			$this->view->assign("savetime",60000);
			$ramus=isset($this->get[4])?$this->get[4]:'';
			$autosave=$_ENV['doc']->is_autosave($this->user['uid'],$doc['did']);
			if((bool)$autosave){
				if($ramus){
					$doc['content']=$autosave['content'];
				}else{
					$autosave['content']=str_replace(array("\r\n","\r","\n"),"",addslashes($autosave['content']));
					$autosave['showtime']=$this->date($autosave['time']);
					$this->view->assign("autosave",$autosave);
				}
			}
			
			$doc['content']= $this->setting['auto_picture']?$_ENV['doc']->auto_picture($doc['content'],$did):$doc['content'];
			$doc['title']=$doc['title']."-".$array_section[$id]['value'];
			$doc['section_id']=$id;
			$this->view->assign('navtitle',$doc['title'].'-'.$this->view->lang['editionEdit'].'-');
			$this->view->assign("page_action","editsection");
			$doc = str_replace("&", "&amp;", $doc);
			$doc['title']=htmlspecialchars(stripslashes($doc['title']));
			$this->view->assign("doc",$doc);
			$this->view->assign("doc_verification_edit_code", ($this->setting['checkcode']!=3 && $this->setting['doc_verification_edit_code']));
			$this->view->assign('g_img_big',$this->setting['img_width_big']);
			$this->view->assign('g_img_small',$this->setting['img_width_small']);
			//$this->view->display("editor");
			$_ENV['block']->view('editor');
		}else{
			if($this->setting['checkcode']!=3 && $this->setting['doc_verification_edit_code'] && strtolower($this->post['code'])!=$_ENV['user']->get_code()){
				$this->message($this->view->lang['codeError'],'BACK',0);
			}
			if(trim($this->post['content'])==""){
				$this->message($this->view->lang['contentIsNull'],'BACK',0);
			}
			$tem=$_ENV['doc']->splithtml($doc['content']);
			$tem[$id+1]['value']=$_ENV['doc']->replace_danger_word(stripcslashes($this->post['content']));
			$doc['content']=string::haddslashes(string::stripscript($_ENV['doc']->joinhtml($tem)),1);
			//$doc['tags']=$_ENV['doc']->jointags($this->post['tags']);
			$doc['tags']=$this->post['tags'];
			$doc['tags']=$_ENV['doc']->replace_danger_word($doc['tags']);
			$doc['summary']=trim(strip_tags($_ENV['doc']->replace_danger_word($this->post['summary'])));
			$doc['summary']=(bool)$doc['summary']?$doc['summary']:$doc['content'];
			$doc['summary'] =trim(string::convercharacter(string::substring(strip_tags($doc['summary']),0,100)));
			$doc['images']=util::getimagesnum($doc['content']);
			$doc['time']=$this->time;
			$doc['visible']=$this->setting['verify_doc']?'0':'1';
			$doc['words']=string::hstrlen($doc['content']);
			$doc['reason']=htmlspecialchars(trim(implode(',',$this->post['editreason']),' \t\n,'));
			/*foreach($this->post['tags'] as $search_tags){
				$doc['search_tags'] .=string::convert_to_unicode($search_tags).";";
			} */
			$_ENV['doc']->edit_doc($doc,"2");
			$_ENV['doc']->unset_editlock($doc['did'],$this->user['uid']);
			if($doc['visible']==1 && $_ENV['doc']->is_addcredit($doc['did'],$this->user['uid'])){
				$_ENV['user']->add_credit($this->user['uid'],'doc-edit',$this->setting['credit_edit'],$this->setting['coin_edit']);
			}
			$_ENV['user']->update_field('edits',$this->user['edits']+1,$this->user['uid']);
			$_ENV['doc']->del_autosave('',$this->user['uid'],$doc['did']);
			
			$this->_send_noticemail($did, $doc, 'doc-edit');
			
			//eval($this->plugin['hdapi']['hooks']['postcontent']);
			$this->load("hdapi");
			if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
				$_ENV["hdapi"]->post_content($doc["title"],$doc["content"]);
			}
			
			//#clode#
			if(1 == $this->setting['cloud_search']) {
				// �༭���� ֪ͨ������
				$_ENV['search']->cloud_change(array('dids'=>$did,'mode'=>'2'));
			}
			
			$msg=$this->view->lang['docPublishSuccess'];
			if($this->setting['hdapi_autoshare_edit']){$msg.="<script>$.get('index.php?hdapi-hdautosns-edit-'+$did);</script>";}
			$this->message($msg, $this->setting['seo_prefix']."doc-view-".$doc['did'].$this->setting['seo_suffix'],0);

		}
	}

	function dorefresheditlock(){
		$_ENV['doc']->refresheditlock($this->get[2],$this->user['uid']);
		//eval($this->plugin['hdapi']['hooks']['refreshlock']);
		$this->load("hdapi");
		if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
			$tmp = $_ENV["hdapi"]->get_doc_title_by_id(intval($this->get[2]));
			$title = $tmp["title"];
			$_ENV["hdapi"]->refresh_lock($title);
		}
	}

	function dounseteditlock(){
		$did = $this->get[2];
		$action = $this->get[3];
		$_ENV['doc']->unset_editlock($did, $this->user['uid']);
		if ($action == 'create'){
			$_ENV['doc']->uncreate($did);
			$this->header('doc-create');
		}else{
			//eval($this->plugin['hdapi']['hooks']['unlockdoc']);
			$this->load("hdapi");
			if($this->setting['site_nick'] && $this->setting["hdapi_bklm"]){
				$tmp = $_ENV["hdapi"]->get_doc_title_by_id(intval($this->get[2]));
				$title = $tmp["title"];
				$_ENV["hdapi"]->un_lock($title);
			}
			
			
			$this->header("doc-view-".$this->get[2]);
		}
	}

	function doinnerlink(){
		$len=strlen('doc-innerlink-');
		$title=str_replace("+",urlencode("+"),substr($_SERVER['QUERY_STRING'],$len));
		$title=string::haddslashes(string::hiconv(trim(urldecode($title))),1);
		if($this->setting['seo_suffix']){
			$title=str_replace($this->setting['seo_suffix'],'',$title);
		}
		
		$doc=$this->db->fetch_by_field('doc','title',addslashes($title));
		$this->view->assign("docrewrite","1");
		if(!(bool)$doc){
			$doc=$_ENV['synonym']->get_synonym_by_src($title);
			if($doc){
				$this->view->assign('synonymdoc',$doc['srctitle']);
				$this->get[2]=$doc['destdid'];
				$this->doview();
				exit;
			}else{
				$this->view->assign("search_tip_switch", $this->setting['search_tip_switch']);
				$this->view->assign("searchtext",stripslashes($title));
				$this->view->assign("searchword",urlencode(string::hiconv($title,'utf-8')));
				$this->view->assign("title",$title);
				$this->view->display("notexist");
			}
		}else{
			$this->get[2]=$doc['did'];
			$this->doview();
		}
	}

	function dosummary(){
		$count=count($this->get);
		@$title=$this->get[2];
		for($i=3;$i<$count;$i++){
			$title .='-'.$this->get[$i];
		}
		$title=trim($title);
		$title2 = $title;
		
		$title=urldecode($title);
		if(string::hstrtoupper(WIKI_CHARSET)=='GBK'){
			$title=string::hiconv($title,$to='gbk',$from='utf-8');
		}
		$doc=$this->db->fetch_by_field('doc','title',addslashes($title));
		if((bool)$doc){
			$doc['image']=util::getfirstimg($doc['content']);
			$doc['url']=$this->setting['site_url']."/".$this->setting['seo_prefix']."doc-view-".$doc['did'].$this->setting['seo_suffix'];
			$doc_exists=1;
		}else{
			$url = 'http://www.hudong.com/validateDocSummary.do?doc_title='.$title2;
			$data = util::hfopen($url);
			$doc_exists=1;
			if($data && stripos($data,'<flag>true</flag>') && preg_match_all("/<\!\[CDATA\[(.*)\]\]>/", $data, $matches)){
				$summary = $matches[1][1];
				$image = $matches[1][2];
				if ($summary == 'null') $summary = '';
				if ($image == 'null') $image = '';
				$doc = array(
					'image'=>$image,
					'url'=>'http://www.hudong.com/wiki/'.$title2,
					'summary'=>$summary
				);
			}else{
				$doc_exists=0;
			}
			
		}
		$this->view->assign("doc_exists",$doc_exists);
		$this->view->assign("doc",$doc);
		$this->view->assign("encode",WIKI_CHARSET);
		$this->view->assign("title",$title);
		//$this->view->display('hdmomo');
		$_ENV['block']->view('hdmomo');
	}

	function dosandbox(){
		$did = $this->setting['sandbox_id'];
		$maxid =  $_ENV['doc']->get_maxid();
		
		if (!is_numeric($did) || $did < 1 || $did > $maxid){
			$did = $maxid;
		}
		if($did){
			$this->header('doc-edit-'.$did);
		}else{
			$this->message($this->view->lang['sandboxTip1'],'index.php',0);
		}
	}
	
	function dosetfocus(){
		if(@!is_numeric($this->post['did']) || @!is_numeric($this->post['doctype'])){
			$this->message("-1","",2);
		}elseif(@$this->post['visible']!="1"){
			$this->message("-2","",2);
		}elseif($_ENV['doc']->set_focus_doc(array($this->post['did']),$this->post['doctype'])){
			$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
			$this->message("1","",2);
		}else{
			$this->message("0","",2);
		}
	}

	function doremovefocus(){
		if(@!is_numeric($this->post['did'])){
			$this->message("-1","",2);
		}elseif(@$this->post['visible']!="1"){
			$this->message("-2","",2);
		}elseif($_ENV['doc']->remove_focus(array($this->post['did']))){
			$this->cache->removecache('data_'.$GLOBALS['theme'].'_index');
			$this->message("1","",2);
		}else{
			$this->message("0","",2);
		}
	}

	function dogetcategroytree(){
		$all_category=$_ENV['category']->get_category_cache();
		$categorytree=$_ENV['category']->get_categrory_tree($all_category);
		$this->message($categorytree,"",2);
	}
	
	function dochangename(){
		$ajaxtitle = trim($this->post['newname']);
		if(string::hstrtoupper(WIKI_CHARSET)=='GBK'){
			$ajaxtitle=string::hiconv($ajaxtitle,'gbk','utf-8','true');
		}
		$title=string::substring(string::stripscript($_ENV['doc']->replace_danger_word(trim($ajaxtitle))),0,80);
		if(@!is_numeric($this->post['did'])){
			$this->message("-1","",2);
		}elseif($ajaxtitle!=string::stripscript($ajaxtitle)){
			$this->message("-3","",2);
		}elseif(!$title){
			$this->message("-4","",2);
		}elseif(@(bool)$this->db->fetch_by_field('doc','title',$title)){
			$this->message("-2","",2);
		}elseif($_ENV['doc']->change_name($this->post['did'],$title)){
			$_ENV['synonym']->synonym_change_doc($this->post['did'],$title);
			// ������֪ͨ
			if(1 == $this->setting['cloud_search']) {
				// �༭���� ֪ͨ������
				$_ENV['search']->cloud_change(array('dids'=>$this->post['did'],'mode'=>'2'));
			}
			$this->message("1","",2);
		}else{
			$this->message("0","",2);
		}
	}
	
	function dochangecategory(){
		if(@is_numeric($this->post['did'])&&$_ENV['category']->vilid_category($this->post['newcategory'])){
			if($_ENV['doc']->change_category($this->post['did'],$this->post['newcategory'])){
				$categorys = $_ENV['category']->get_category($this->post['newcategory'], 2);
				foreach($categorys as $category){
					@$result .="<a href=\"".$this->setting['seo_prefix']."category-view-{$category['cid']}".$this->setting['seo_suffix']."\" > {$category['name']} </a>&nbsp;&nbsp;";
				}
				$this->message($result,"",2);
			}
		}else{
			$this->message("0","",2);
		}
	}

	function dolock(){
		if(@is_numeric($this->post['did'])&&(bool)$_ENV['doc']->lock(array($this->post['did']))){
			$this->message("1","",2);
		}else{
			$this->message("0","",2);
		}
	}

	function dounlock(){
		if(@is_numeric($this->post['did'])&&(bool)$_ENV['doc']->lock( array($this->post['did']),0)){
			$this->message("1","",2);
		}else{
			$this->message("0","",2);
		}
	}

	function doaudit(){
		if(@is_numeric($this->post['did'])&&(bool)$_ENV['doc']->audit_doc(array($this->post['did']))){
			$this->message("1","",2);
		}else{
			$this->message("0","",2);
		}
	}

	function doremove(){
		if(@!is_numeric($this->get[2])){
			$this->message($this->view->lang['parameterError'],'BACK',0);
		}else{
			$_ENV['doc']->remove_doc(array($this->get[2]));
			if(1 == $this->setting['cloud_search']) {
				// ɾ������ ֪ͨ������
				$_ENV['search']->cloud_change(array('dids'=>$this->get[2],'mode'=>'0'));
			}
			$this->header("list");
		}
	}
	
	
	function dorandom(){
		$did=$_ENV['doc']->get_random();
		if(0==$did){
			$this->header();
		}else{
			$this->header('doc-view-'.$did);
		}
	}
	
	function dovote(){
		if(@is_numeric($this->post['did'])){
			$did=$this->post['did'];
			$uid=$this->user['uid'];
			@$hdvote = $this->hgetcookie("vote_{$uid}_{$did}");
			if(!isset($hdvote)){
				$_ENV['doc']->update_field('votes',1,$did,0);
				$this->hsetcookie("vote_{$uid}_{$did}", $did);
				$this->message('1','',2);
			}
		}
		$this->message('0','',2);
	}

	function doautosave(){
		$did=isset($this->get[2])?$this->get[2]:$this->post['did'];
		$id=isset($this->post['id'])?$this->post['id']:-1;
		$notfirst=isset($this->post['notfirst'])?$this->post['notfirst']:0;
		$savecontent=isset($this->post['savecontent'])?$this->post['savecontent']:'';
		if (WIKI_CHARSET == 'GBK'){$savecontent = string::hiconv($savecontent);}
		if($savecontent!==''){
			$_ENV['doc']->update_autosave($this->user['uid'],$did,$savecontent,$id,$notfirst);
		}
		$this->message('sucess','',2);
	}
	
	function dodelsave(){
		$aid=isset($this->get[2])?$this->get[2]:'';
		if(empty($aid)){
			$aid=$this->post['checkid'];
			$num=count($aid);
			if($num>0){
				$aids='';
				for($i=0;$i<$num;$i++){
					$aids.=$aid[$i].',';
				}
				$aids=substr($aids,0,-1);
				$_ENV['doc']->del_autosave($aids);
				$this->message($this->view->lang['saveDelSucess'],'index.php?doc-managesave',0);
			}else{
				$did=isset($this->post['did'])?$this->post['did']:'';
				if(is_numeric($did)){
					$_ENV['doc']->del_autosave('',$this->user['uid'],$did);
				}else{
					$this->message('fail','',2);
				}
			}
		}else{
			$_ENV['doc']->del_autosave($aid);
		}
		$this->message('sucess','',2);
	}
	
	function domanagesave(){
		$page = max(1, intval($this->get[2]));
		$num = isset($this->setting['list_prepage'])?$this->setting['list_prepage']:20;
		$start_limit = ($page - 1) * $num;
		$count=$_ENV['doc']->get_autosave_number($this->user['uid']);
		$savelist=$_ENV['doc']->get_autosave_by_uid($this->user['uid'],$start_limit,$num);
		$departstr=$this->multi($count, $num, $page,"doc-managesave");
		
		$this->view->assign('departstr',$departstr);
		$this->view->assign('savelist',$savelist);
		$this->view->assign('count',$count);
		$this->view->display('managesave');
	}
	
	function dogetrelateddoc(){
		$did = trim($this->post['did']);
		$relateddoc=$_ENV['doc']->get_related_doc($did);
		$doclist = json_encode($relateddoc);	
		$this->message($doclist,"",2);
	}
	
	function doaddrelatedoc(){
		$did = trim($this->post['did']);
		if(is_numeric($did)){
			$relate = trim($this->post['relatename']);
			$title = htmlspecialchars(trim($this->post['title']));
			if(string::hstrtoupper(WIKI_CHARSET)=='GBK'){
				$relate=string::hiconv($relate,'gbk','utf-8');
				$title=string::hiconv($title,'gbk','utf-8');
			}

			$list=array();
			if($relate){
				$list = array_unique(explode(';',$relate));
				foreach($list as $key => $relatename){
					$relatename = htmlspecialchars($relatename);
					if($_ENV['doc']->have_danger_word($relatename)){
						unset($list[$key]);
						$this->message("2","",2);
					}
				}
			}
			$_ENV['doc']->add_relate_title($did,$title,$list);
			$this->message("1","",2);
		}
	}

	function docooperate(){
		
		$coopdoc = array();
		$cooperatedocs = explode(';',$this->setting['cooperatedoc']);
		$counts = count($cooperatedocs);
		for($i=0;$i<$counts;$i++){
			if($cooperatedocs[$i]==''){
				unset($cooperatedocs[$i]);
			}else{
				$coopdoc[$i]['shorttitle'] = (string::hstrlen($cooperatedocs[$i])>4)?string::substring($cooperatedocs[$i],0,4)."...":$cooperatedocs[$i];
				$coopdoc[$i]['title'] = $cooperatedocs[$i];
			}
		}
		$this->view->assign('coopdoc',$coopdoc);
		//$this->view->display('cooperate');
		$_ENV['block']->view('cooperate');
	}
	
	function hdgetcat(){
		$evaljs = '';
		$did=intval($this->post['did']);
		$cats = $_ENV['doc']->get_cids_by_did($did);
		if($cats){
			foreach($cats as $cat){
				$cat['name'] = string::haddslashes($cat['name'],1);
				$evaljs .= "catevalue.scids.push(".$cat['cid'].");catevalue.scnames.push('".string::haddslashes($cat['name'])."');";
			}
		}
		$this->message($evaljs,'',2);
	}
	
	function doeditletter(){
		$first_letter = trim($this->post['first_letter']);
		if(preg_match("/^[a-zA-Z]$/i" , $first_letter)){
			$did = intval($this->post['did']);
			$doc=$this->db->fetch_by_field('doc','did',$did,'letter');
			if($_ENV['doc']->change_letter($did,$first_letter)){
				$this->cache->removecache('list_'.strtolower($first_letter).'_0');
				$this->cache->removecache('list_'.strtolower($doc['letter']).'_0');
				$this->message("1","",2);
			}
		}else{
			$this->message("-1","",2);
		} 
	}
	function _send_noticemail($did, &$doc, $type) {
		if(empty($this->setting['noticemail'])) {
			$this->setting['noticemail'] = '';
		}
		$noticemail_config = unserialize($this->setting['noticemail']);
		$mail_uids = array();
		
		if('doc-edit' == $type && !empty($noticemail_config['doc-edit'])) {
			$mail_subject = sprintf(addslashes($this->view->lang['docEditSubject']), $this->user['username'], addslashes($doc['title']));
			$mail_message = sprintf(addslashes($this->view->lang['docEditMessage']), 
				addslashes($doc['title']), $this->date($doc['time']), $doc['reason'], $this->setting['site_url'].'/'.$this->setting['seo_prefix']."doc-view-".$did.$this->setting['seo_suffix']);
			$to_groups = str_replace(array('CREATOR,', 'EDITORS,'), array(), $noticemail_config['doc-edit'].',');
			$to_groups = substr($to_groups, 0, strlen($to_groups)-1);
			if($to_groups) {
				$to_groups = $_ENV['user']->get_group_users($to_groups, 'uid'); 		
				while($user = array_pop($to_groups)) {
					$mail_uids[] = $user['uid'];
				}
			}
			if(false !== strpos($noticemail_config['doc-edit'], 'CREATOR')) {
				$mail_uids[] = $doc['authorid'];
			}
			if(false !== strpos($noticemail_config['doc-edit'], 'EDITORS') && $doc['editions'] > 0) {
				$editors = $_ENV['doc']->get_recenteditor($did, 100);
				while($editor = array_pop($editors)) {
					$mail_uids[] = $editor['authorid'];
				}
			}
			$mail_uids = array_unique($mail_uids);
		}
		
		if('doc-create' == $type && !empty($noticemail_config['doc-create'])) {
			$mail_subject = sprintf(addslashes($this->view->lang['docCreateSubject']), $this->user['username'], $doc['title']);
			$mail_message = sprintf(addslashes($this->view->lang['docCreateMessage']),
				$doc['title'], $this->date($doc['time']), $doc['summary'], $this->setting['site_url'].'/'.$this->setting['seo_prefix']."doc-view-".$did.$this->setting['seo_suffix']);
			$to_users = $_ENV['user']->get_group_users($noticemail_config['doc-create'], 'uid');
			while($user = array_pop($to_users)) {
				$mail_uids[] = $user['uid'];
			}
		}
		
		if(count($mail_uids) > 0) {
			$this->load('mail');
			$_ENV['mail']->add($mail_uids, array(), $mail_subject, $mail_message);
		}
	}
	
	function _anti_copy() {
		if(!empty($this->setting['check_useragent'])) {
			$this->load('anticopy');
			if(!$_ENV['anticopy']->check_useragent()){
				$this->message('��ֹ����','',0);
			}
		}
		if(!empty($this->setting['check_visitrate'])) {
			$this->load('anticopy');
			$_ENV['anticopy']->check_visitrate();
		}
	}
}

?>
