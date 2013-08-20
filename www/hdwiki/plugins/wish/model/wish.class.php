<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class wishmodel {

	var $db;
	var $base;

	function wishmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function install(){
		$sqls="CREATE TABLE wiki_wish (
				`id` mediumint(8) unsigned NOT NULL auto_increment,
				`wish` varchar(200) NOT NULL default '',
				`receiver` varchar(15) NOT NULL default '',
				`author` varchar(15) NOT NULL default '',
				`style` int(2) NOT NULL default '1',
				`time` int(10) unsigned NOT NULL default '0',
				PRIMARY KEY  (`id`),
				KEY `time` (`time`)) TYPE=MyISAM DEFAULT CHARSET=".DB_CHARSET.";";
		$this->db->query($sqls);
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`type`) VALUES ('许愿墙','wish-default|wish-will|wish-post','1')");
		$this->db->query("UPDATE `".DB_TABLEPRE."usergroup` SET regulars =  CONCAT(regulars,'|wish-default|wish-will|wish-post') ");
		$plugin=array(
			'name'=>'许愿墙',
			'identifier'=>'wish',
			'description'=>'每个人都有很多美好的愿望，许愿墙，一个你可以随意许愿的地方，这里人人平等，朋友之间也可以互相祝福！',
			'datatables'=>'',
			'type'=>'1',
			'copyright'=>'lovewiki',
			'homepage'=>'http://hi.baidu.com/songdenggao',
			'version'=>'1.1',
			'suit'=>'4.0.3,4.0.4',
			'modules'=>''
		);
		$plugin['vars']=array();
		$plugin['hooks']=array();
		return $plugin;
	}

	function uninstall(){
		$this->db->query("DROP TABLE IF EXISTS wiki_wish");
	}
	
	
	function add_wish($receiver,$author,$wish){
		$style=rand(1,8);
		$this->db->query("INSERT INTO  ".DB_TABLEPRE."wish (receiver,author,wish,style,time) VALUES ('$receiver','$author','$wish',$style,'".$this->base->time."') ");
	}
	
	function get_list($start=0,$limit=100){
		$wishlist=array();
		$query=$this->db->query("SELECT *  FROM ".DB_TABLEPRE."wish ORDER BY time DESC limit $start,$limit ");
		while($wish=$this->db->fetch_array($query)){
			$wish['time']=$this->base->date($wish['time']);
			$wish['left']=rand(1,1024);
			$wish['top']=rand(1,800);
			$wishlist[]=$wish;
		}
		return $wishlist;
	}
	
	
	function remove_wish($id=0){
		$sql='DELETE FROM   '.DB_TABLEPRE.'wish WHERE 1=1 ';
		if($id){
			$sql.=' and id='.$id;
		}
		$this->db->query($sql);
	}

}
?>
