<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class aaamodel {

	var $db;
	var $base;
	var $opening = 0;
	var $resolved = 1;
	var $closed = 2;

	function aaamodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function install(){
		$this->db->query("CREATE TABLE  `".DB_TABLEPRE."aaa_answer` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ask_id` int(10) unsigned NOT NULL default '0',
  `authorid` int(10) unsigned NOT NULL default '0',
  `author` char(15) NOT NULL default '',
  `answer` text NOT NULL,
  `time` int(10) unsigned NOT NULL default '0',
  `right` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ask_id` (`ask_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		$this->db->query("CREATE TABLE  `".DB_TABLEPRE."aaa_ask` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `did` int(10) unsigned NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `question` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `reward` int(10) unsigned NOT NULL default '0',
  `authorid` int(10) unsigned NOT NULL default '0',
  `author` char(15) NOT NULL default '0',
  `status` smallint(5) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  `visible` tinyint(1) NOT NULL default '1',
  `views` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `did_index` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`regulargroupid`,`type`) VALUES ('访问百科问答','aaa-default','18',2)");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`regulargroupid`,`type`) VALUES ('百科问答浏览问题','aaa-view','18',2)");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`regulargroupid`,`type`) VALUES ('百科问答浏览列表','aaa-list','18',2)");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`regulargroupid`,`type`) VALUES ('百科问答浏览词条相关问题列表','aaa-doc','18',2)");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`regulargroupid`,`type`) VALUES ('百科问答提问','aaa-create','19',2)");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`regulargroupid`,`type`) VALUES ('百科问答回答','aaa-answer','19',2)");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`regulargroupid`,`type`) VALUES ('百科问答选择正确答案','aaa-choose','19',2)");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`regulargroupid`,`type`) VALUES ('百科问答关闭问题','aaa-close','19',2)");

		$this->db->query("UPDATE `".DB_TABLEPRE."usergroup` SET regulars =  CONCAT(regulars,'|aaa-default|aaa-view|aaa-list|aaa-doc|aaa-create|aaa-answer|aaa-choose|aaa-close') where groupid!=4");
		
		$plugin=array(
			'name'=>'百科问答',
			'identifier'=>'aaa',
			'description'=>'百科问答插件(AskAndAnswer) For HDwiki 5.0。
1.新增问答频道；
2.具有权限的用户可以基于词条发布问题；
3.问题显示在相关词条的下方；
4.提问者可设置悬赏金币；
5.问题可以关闭；',
			'datatables'=>'',
			'type'=>'1',
			'copyright'=>'Fanbin',
			'homepage'=>'http://www.midishow.com',
			'version'=>'1.01',
			'suit'=>'5.0 beta',
			'modules'=>''
		);
		$plugin['vars']=array(
			array('displayorder'=>"0", 
				'title'=>'提出问题增加金币：',
				'description'=>'填写负数表示扣除',
				'variable'=>'credit_create',
				'type'=>'text',
				'value'=>'-5',
				'extra'=>''
			),
			array('displayorder'=>"1", 
				'title'=>'回答问题奖励金币',
				'description'=>'填写负数表示扣除',
				'variable'=>'credit_answer',
				'type'=>'text',
				'value'=>'3',
				'extra'=>''
			),
			array('displayorder'=>"2", 
				'title'=>'被选为正确答案奖励金币',
				'description'=>'填写负数表示扣除',
				'variable'=>'credit_isright',
				'type'=>'text',
				'value'=>'5',
				'extra'=>''
			),
			array('displayorder'=>"3", 
				'title'=>'提问者选择正确答案奖励金币',
				'description'=>'填写负数表示扣除',
				'variable'=>'credit_chooseright',
				'type'=>'text',
				'value'=>'5',
				'extra'=>''
			)
		);
		$plugin['hooks']=array(
			array('available'=>"1",
				'title'=>'relative_aaa',
				'description'=>'在/control/doc.php中，查找：<br />$_ENV[\\\'block\\\']->view(\\\'viewdoc\\\'); <br />在这一行之前添加“调用代码”中的代码。',
				'code'=>'
					$this->loadplugin("aaa");
					$this->view->assign("questionlist", $_ENV["aaa"]->get_asks_by_did($doc["did"], 0, 5));
				'
			)
		);
		return $plugin;
	}

	function uninstall(){
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'aaa-default' and type=2");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'aaa-view' and type=2");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'aaa-list' and type=2");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'aaa-doc' and type=2");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'aaa-create' and type=2");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'aaa-answer' and type=2");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'aaa-choose' and type=2");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'aaa-close' and type=2");
		
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|aaa-default','')");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|aaa-view','')");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|aaa-list','')");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|aaa-doc','')");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|aaa-create','')");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|aaa-answer','')");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|aaa-choose','')");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|aaa-close','')");
		
		$this->db->query("DROP TABLE IF EXISTS `".DB_TABLEPRE."aaa_ask`;");
		$this->db->query("DROP TABLE IF EXISTS `".DB_TABLEPRE."aaa_answer`;");
	}
	
	/**
	 * 根据ID获取问题
	 *
	 * @param int $id
	 * @return array
	 */
	function get_ask($id){
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."aaa_ask WHERE id='$id' ");
		return $this->db->fetch_array($query);
	}

	/**
	 * 根据ID获取答案
	 *
	 * @param int $id
	 * @return array
	 */
	function get_answer($id){
		$query=$this->db->query("SELECT * FROM ".DB_TABLEPRE."aaa_answer WHERE id='$id' ");
		return $this->db->fetch_array($query);
	}
	
	function user_answered($ask_id, $uid) {
		return $this->db->result_first("SELECT count(id) c FROM ".DB_TABLEPRE."aaa_answer WHERE ask_id='$ask_id' and authorid ='$uid'") >= 1;
	}
	
	/**
	 * 更新答案字段
	 *
	 * @param unknown_type $field
	 * @param unknown_type $value
	 * @param unknown_type $id
	 * @param unknown_type $type
	 */
	function update_answer_field($field,$value,$id,$type=1){
		if($type){
			$sql="UPDATE ".DB_TABLEPRE."aaa_answer SET `$field`='$value' WHERE id= $id ";
		}else{
			$sql="UPDATE ".DB_TABLEPRE."aaa_answer SET `$field`=`$field`+$value WHERE id= $id ";
		}
		return $this->db->query($sql);
	} 
	
	/**
	 * 更新问题字段
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param int $id
	 * @param int $type
	 */
	function update_field($field,$value,$id,$type=1){
		if($type){
			$sql="UPDATE ".DB_TABLEPRE."aaa_ask SET `$field`='$value' WHERE id= $id ";
		}else{
			$sql="UPDATE ".DB_TABLEPRE."aaa_ask SET `$field`=`$field`+$value WHERE id= $id ";
		}
		return $this->db->query($sql);
	} 

	/**
	 * 获取指定词条相关的问题
	 *
	 * @param int $did
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	function get_asks_by_did($did,$start=0,$limit=5) {
		$asklist=array();
		if(is_array($did)){
			$did=implode(",",$did);
		}
		$sql="SELECT * FROM ".DB_TABLEPRE."aaa_ask WHERE did IN ($did) ORDER BY id DESC LIMIT $start,$limit";
		
		$query=$this->db->query($sql);
		while($ask=$this->db->fetch_array($query)){
			$ask['time']=$this->base->date($ask['time']);
			$asklist[]=$ask;
		}
		return $asklist;
	}
	
	/**
	 * 获取指定问题的所有答案
	 *
	 * @param int $id
	 * @return array
	 */
	function get_answers_by_ask_id($id) {
		$anslist = array();
		$sql="SELECT * FROM ".DB_TABLEPRE."aaa_answer WHERE ask_id = $id ORDER BY `right` DESC, id ASC";
		
		$query=$this->db->query($sql);
		while($ans=$this->db->fetch_array($query)){
			$ans['time']=$this->base->date($ans['time']);
			$anslist[]=$ans;
		}
		return $anslist;
	}

	/**
	 * 添加提问
	 *
	 * @param array $ask
	 * @return int
	 */
	function add_ask($ask) {
		$this->db->query("INSERT INTO ".DB_TABLEPRE."aaa_ask
		(did,title,question,description ,reward ,author,authorid,time, visible)
		VALUES ('".$ask['did']."','".$ask['title']."','".$ask['question']."','".$ask['description']."','".$ask['reward']."',
		'".$this->base->user['username']."','".$this->base->user['uid']."',".$ask['time'].",'".$ask['visible']."')");

		$id=$this->db->insert_id();

		return $id;
	}
	
	/**
	 * 添加回答
	 *
	 * @param array $ans
	 * @return int
	 */
	function add_answer($ans) {
		$this->db->query("INSERT INTO ".DB_TABLEPRE."aaa_answer
		(ask_id,authorid,author,answer ,time)
		VALUES ('".$ans['ask_id']."','".$this->base->user['uid']."','".$this->base->user['username']."',
		'".$ans['answer']."','".$ans['time']."')");
	
		$id=$this->db->insert_id();

		return $id;
	}

	
	/**
	 * 获取问题列表
	 *
	 * @param int $type
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	function get_list($type = false, $start=0, $limit = 10) {
		$questions = array();
		$status_sql = $type === false ? '' : " AND `status` = {$type} ";
		$sql = "SELECT * FROM ".DB_TABLEPRE."aaa_ask WHERE 1 {$status_sql} ORDER BY id DESC LIMIT $start, $limit";
		$query = $this->db->query($sql);
		while($ask=$this->db->fetch_array($query)){
			$ask['time']=$this->base->date($ask['time']);
			$ask['shortquestion']=string::substring($ask['question'],0,16);
			$questions[] = $ask;
		}
		return $questions;
	}
	
	/**
	 * 获取词条问题列表
	 *
	 * @param int $type
	 * @param int $start
	 * @param int $limit
	 * @return array
	 */
	function get_doc_list($did = 0, $start=0, $limit = 10) {
		$questions = array();
		
		$sql = "SELECT * FROM ".DB_TABLEPRE."aaa_ask WHERE did = {$did} ORDER BY id DESC LIMIT $start, $limit";
		$query = $this->db->query($sql);
		while($ask=$this->db->fetch_array($query)){
			$ask['time']=$this->base->date($ask['time']);
			$ask['shortquestion']=string::substring($ask['question'],0,16);
			$questions[] = $ask;
		}
		return $questions;
	}
	
	
	function search($start=0,$limit=10, $title='',$author='', $status='', $keywords=''){
		$sql = "SELECT * FROM ".DB_TABLEPRE."aaa_ask where 1=1 ";
		
		if($title){
			$sql=$sql." and title ='$title' ";
		}
		if($author){
			$sql=$sql." and author='$author' ";
		}
		if($status != ''){
			$sql=$sql." and status='$status' ";
		}
		if($keywords){
			$sql=$sql." and question like '%$keywords%' ";
		}
		$sql=$sql." order by id desc limit $start,$limit ";
		$asklist=array();
		$query=$this->db->query($sql);
		while($ask=$this->db->fetch_array($query)){
			$ask['time'] = $this->base->date($ask['time']);
			$asklist[]=$ask;
		}
		return $asklist;
	}
 
	function search_num($title='',$author='', $status='', $keywords='' ){
		$sql = "SELECT count(id) FROM ".DB_TABLEPRE."aaa_ask where 1=1 ";
		
		if($title){
			$sql=$sql." and title ='$title' ";
		}
		if($author){
			$sql=$sql." and author='$author' ";
		}
		if($status != ''){
			$sql=$sql." and status='$status' ";
		}
		if($keywords){
			$sql=$sql." and question like '%$keywords%' ";
		}
		return $this->db->result_first($sql);
	}
	
	function get_doc_total_num($did) {
		$sql = "SELECT count(id) num FROM ".DB_TABLEPRE."aaa_ask WHERE did = {$did}";
		return $this->db->result_first($sql);
	}	
	
	function get_total_num($type) {
		$status_sql = $type === false ? '' : " AND `status` = {$type} ";
		$sql = "SELECT count(id) num FROM ".DB_TABLEPRE."aaa_ask WHERE 1 {$status_sql}";
		return $this->db->result_first($sql);
	}
	

	/**
	 * Enter description here...
	 *
	 * @param unknown_type $id
	 */
	function remove_ask($id){
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."aaa_ask WHERE id = $id ");
		$this->db->query("DELETE FROM  ".DB_TABLEPRE."aaa_answer WHERE ask_id = $id ");
	}	
	
	/**
	 * 替换危险字符
	 *
	 * @param unknown_type $content
	 * @return unknown
	 */
	function replace_danger_word($content){
		$words=$this->base->cache->load('word');
		foreach($words as $key=>$word){
			$content=str_replace($key,$word,$content);
		}
		return $content;
	}
}	

?>
