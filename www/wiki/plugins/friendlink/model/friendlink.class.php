<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class friendlinkmodel {

	var $db;
	var $base;

	function friendlinkmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function install(){
		$sqls="
		CREATE TABLE ".DB_TABLEPRE."unionlink (
		  `id` tinyint(8) NOT NULL auto_increment,
		  `name` varchar(100) NOT NULL,
		  `url` varchar(100) NOT NULL,
		  `logo` varchar(100) NOT NULL,
		  `mail` varchar(100) NOT NULL,
		  `desc` varchar(200) NOT NULL,
		  `pass` tinyint(1) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		)TYPE=MyISAM DEFAULT CHARSET=".DB_CHARSET; 
		$this->db->query($sqls);
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`type`) VALUES ('友情链接申请(插件)','friendlink-default','2')");
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`type`) VALUES ('提交友情链接申请(插件)','admin_friendlink-application','2')");
		$this->db->query("UPDATE `".DB_TABLEPRE."usergroup` SET regulars =  CONCAT(regulars,'|friendlink-default') where groupid!=4");
		$this->db->query("UPDATE `".DB_TABLEPRE."usergroup` SET regulars =  CONCAT(regulars,'|admin_friendlink-application') where groupid!=4");
		$plugin=array(
			'name'=>'友情链接申请',
			'identifier'=>'friendlink',
			'description'=>'此插件给广大用户及站长提供了友情链接交换的平台.',
			'datatables'=>'',
			'type'=>'1',
			'copyright'=>'互动在线（北京）科技有限公司',
			'homepage'=>'http://www.hudong.com',
			'version'=>'1.0',
			'suit'=>'4.0.4',
			'modules'=>''
		);
		$plugin['vars']=array();
		$plugin['hooks']=array();
		return $plugin;
	}

	function uninstall(){
		$this->db->query("DROP TABLE IF EXISTS ".DB_TABLEPRE."unionlink;");
		$this->db->query("DELETE from ".DB_TABLEPRE."regular where regular='friendlink-default' and type=2");
		$this->db->query("DELETE from ".DB_TABLEPRE."regular where regular='admin_friendlink-application' and type=2");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|friendlink-default','')");
		$this->db->query("update ".DB_TABLEPRE."usergroup set regulars=replace(regulars,'|admin_friendlink-application','')");
	}
	
	function get_list($start=0,$limit=100){
		$linklist=array();
		$query=$this->db->query("SELECT *  FROM ".DB_TABLEPRE."unionlink ORDER BY id DESC limit $start,$limit ");
		while($link=$this->db->fetch_array($query)){
			$linklist[]=$link;
		}
		return $linklist;
	}
	
	function remove($friendlink){
		$link =implode($friendlink,',');
		$this->db->query("delete from  ".DB_TABLEPRE."unionlink where id in ($link) ");
	}
	
	function  get_link_by_id($id){
		$linkid =implode($id,',');
		$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."unionlink WHERE id in ($linkid)");
		while($link=$this->db->fetch_array($query)){
			$linklist[]=$link;
		}		
		return $linklist;
	}
	
	function pass($link){
		$linknum=count($link);
		for($i=0;$i<$linknum;$i++){
			$this->db->query("update ".DB_TABLEPRE."unionlink set pass=1 where id=".$link[$i]['id']);
			$this->db->query("INSERT INTO ".DB_TABLEPRE."friendlink (name,url,description,logo) VALUES ('".$link[$i][name]."','".$link[$i][url]."','".$link[$i][desc]."','".$link[$i][logo]."')");
		}
	}
	
	function addlink($link){
		$this->db->query("INSERT INTO ".DB_TABLEPRE."unionlink (name,url,logo,mail,`desc`,pass) VALUES ('$link[name]','$link[url]','$link[logo]','$link[mail]','$link[desc]','0')");
	}
}	

?>
