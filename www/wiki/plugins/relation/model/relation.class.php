<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class relationmodel {

	var $db;
	var $base;

	function relationmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function install(){

		$plugin=array(
			'name'=>'相关词条增强插件',
			'identifier'=>'relation',
			'description'=>'此版本为UTF8.相关词条增强插件，解决目前相关词条无法关联问题。同时可以根据词条标题和词条标签进行模糊匹配。',
			'datatables'=>'',
			'type'=>'0',
			'copyright'=>'truk',
			'homepage'=>'http://www.rjf.cc',
			'version'=>'2.1',
			'suit'=>'5.0 beta',
			'modules'=>''
		);
		$plugin['vars']=array(
		    array(
			    'displayorder'=>'0',
				'title'=>'相关词条显示数量',
				'description'=>'最多显示相关词条的数量',
				'variable'=>'countr',
				'type'=>'text',
				'value'=>'10',
				'extra'=>''
		    ),
		   array(
			    'displayorder'=>'1',
				'title'=>'开启标题匹配',
				'description'=>'根据词条标题进行匹配',
				'variable'=>'is_title',
				'type'=>'radio',
				'value'=>'0',
				'extra'=>''
		    ),
		    array(
			    'displayorder'=>'2',
				'title'=>'开启TAG匹配',
				'description'=>'根据词条TAG进行匹配',
				'variable'=>'is_tag',
				'type'=>'radio',
				'value'=>'0',
				'extra'=>''
		    ),
		    array(
			    'displayorder'=>'3',
				'title'=>'开启分类匹配',
				'description'=>'根据词条所在分类进行匹配',
				'variable'=>'is_category',
				'type'=>'radio',
				'value'=>'1',
				'extra'=>''
		    )
		);
		$plugin['hooks']=array(
			array(
			'available'=>"1",
			'title'=>'relation',
			'description'=>' 在hdwiki根目录下control下的doc.php 中，约112行查找找 $relatelist = $_ENV[&#39;doc&#39;]->get_related_doc($doc[&#39;did&#39;]); 将此钩子放在此行代码下面。',
			'code'=>'
		/*相关词条 开始*/
if($this->plugin["relation"]["available"]){
			$this->loadplugin("relation");
			$limit = $this->plugin["relation"]["vars"]["countr"];
			$is_title =$this->plugin["relation"]["vars"]["is_title"];
			$is_tag =$this->plugin["relation"]["vars"]["is_tag"];
			$is_category =$this->plugin["relation"]["vars"]["is_category"];

			// 获得存在的相关词条
			$relatelist = $_ENV["relation"]->get_list($relatelist,$doc["did"],$doc["title"],$limit);
			// 启用标题搜索
			if($is_title && count($relation)<$limit){
				$limit=$limit-count($relation);
				$relatelist=$_ENV["relation"]->get_search($relatelist, $doc["title"],$doc["did"],$limit);
			}
			// 启用标签搜索
			if($is_tag && count($relatelist)<$limit ){ //如果搜索还不够 $limit 的数量 启用同类词
				$limit=$limit-count($relatelist);
				$relatelist=$_ENV["relation"]->get_tag($relatelist,$doc["tag"],$doc["did"],$limit);
			}
			
			// 启用分类搜索
			if($is_category && count($relatelist)<$limit ){ //如果搜索还不够 $limit 的数量 启用同类词
				$limit=$limit-count($relatelist);
				$relatelist=$_ENV["relation"]->get_category($relatelist,$doc["did"],$limit);
			}
			if(!empty($relatelist) && is_array($relatelist)){
				arsort($relatelist);
			}
			
		}
/*相关词条 结束*/

			')
		);
		return $plugin;
	}

	function uninstall(){
		;
	}

	function get_list($relatelist,$did,$dtitle,$limit=10){

		// 获得反向相关词条
		if(count($relatelist)<$limit){
			$limit=$limit-count($relatelist);
			$sql="SELECT * FROM ".DB_TABLEPRE."relation WHERE relatedtitle='$dtitle' and did != '$did' ORDER BY RAND() LIMIT $limit";
			$query=$this->db->query($sql);
			while($relation=$this->db->fetch_array($query)){
				$relatelist[]=$relation['title'];
			}
		}
		$relatelist = $this->doc_unique($relatelist);

		return $relatelist;
	}

  // 标题搜索
	function get_search($relatelist, $title,$did,$limit=10){
		$sql="SELECT title FROM ".DB_TABLEPRE."doc WHERE title LIKE '%$title%' AND did!='".$did."' ORDER BY RAND() LIMIT $limit";

		$query=$this->db->query($sql);
		while($relation=$this->db->fetch_array($query)){
			$relatelist[]=$relation['title'];
		}
		$relatelist = $this->doc_unique($relatelist);
		return $relatelist;
	}
	
	 // 标签搜索
	function get_tag($relatelist,$tag,$did,$limit=10){
		
		if(!empty($tag)){

			$sqltag = '';
			foreach($tag as $v) {
				$sqltag .= " tag LIKE '%$v%' OR";
			}
			$sqltag = trim($sqltag, 'OR');
			
			$query=$this->db->query("SELECT title,did FROM ".DB_TABLEPRE."doc WHERE 1 and ($sqltag)  AND did!='".$did."' ORDER BY views DESC  LIMIT $limit");
			while($relation=$this->db->fetch_array($query)){
				$relatelist[]=$relation['title'];
			}
			
			$relatelist = $this->doc_unique($relatelist);
		}
		
		return $relatelist;
	}
	
	// 分类搜索
	function get_category($relatelist,$did,$limit=10){
		// 获得词条所属分类
		$sql = "select cid from ".DB_TABLEPRE."categorylink where did= $did";
		$query=$this->db->query($sql);
		while($cid=$this->db->fetch_array($query)){
				$cidarr[]=$cid['cid'];
		}
		
		if(!empty($cidarr) && is_array($cidarr)){
			$cidstr = implode(',', $cidarr);
			$sql = "select did from ".DB_TABLEPRE."categorylink where  cid IN ($cidstr) and did != $did group by did order by RAND() LIMIT $limit ";
			$query=$this->db->query($sql);
			while($did=$this->db->fetch_array($query)){
					$didar[]=$did['did'];
			}

			if(!empty($didar) && is_array($didar)){
				$didstr = implode(',', $didar);
				$query=$this->db->query("SELECT title FROM ".DB_TABLEPRE."doc WHERE  did IN ($didstr) ");
				while($relation=$this->db->fetch_array($query)){
					$relatelist[]=$relation['title'];
				}
				$relatelist = $this->doc_unique($relatelist);
			}
		}
 		return $relatelist;
	}

	function doc_unique($list){
		// 去掉数组中重复的值
		if(!empty($list) && is_array($list)){
			foreach($list as $k => $v){   
				$tem[$v]=$k;
			}
			$list = array_flip($tem);
			sort($list);
		}
		return $list;
	}

}
?>
