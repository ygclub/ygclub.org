<?php

!defined('IN_HDWIKI') && exit('Access Denied');

class sitemapmodel {

	var $db;
	var $base;
	var $xml;

	function sitemapmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}
	
	function install(){
	
		$plugin=array(
			'name'=>'Sitemap',
			'identifier'=>'sitemap',
			'description'=>'HDwiki首届插件大赛参赛作品——Sitemap 2.0 ，功能如下：
1.增加百度Sitemap功能（百度互联网新闻开放协议标准XML）并可以设置自动刷新；
2.使用 Sitemap 索引文件技术，解决了1.0 版在大数据量网站上的效率问题；
3.自动生成符合标准的Sitemap文件并可提交到Google；
4.更新频率可设定；
5.解决了1.0版不支持PHP4的问题；
6.添加多个实用的设置项，使用更自由。
			',
			'datatables'=>'',
			'type'=>'0',
			'copyright'=>'Fanbin',
			'homepage'=>'http://www.webird.net',
			'version'=>'2.0',
			'suit'=>'4.04',
			'modules'=>''
		);
		$plugin['vars']=array(
			array('displayorder'=>"0", 
				'title'=>'使用压缩格式的Sitemap文件：',
				'description'=>'是：使用压缩过的.gz格式，否：使用普通.xml格式。<br />修改后请到“管理”页重建 Sitemap',
				'variable'=>'use_gzip',
				'type'=>'radio',
				'value'=>'1',
				'extra'=>''
			),
			array('displayorder'=>"1",
				'title'=>'Sitemap 中网站首页更新频率',
				'description'=>'具体说明请参考 <a href="http://www.sitemaps.org/zh_CN/protocol.php#xmlTagDefinitions" target="_blank">这儿</a>',
				'variable'=>'idx_changefreq',
				'type'=>'select',
				'value'=>'hourly',
				'extra'=>'always=总是更新
					hourly=每小时更新
					daily=每天更新
					weekly=每周更新
					monthly=每月更新
					yearly=每年更新
					never=永不更新'
			),
			array('displayorder'=>"2",
				'title'=>'Sitemap 中词条页面更新频率',
				'description'=>'具体说明请参考 <a href="http://www.sitemaps.org/zh_CN/protocol.php#xmlTagDefinitions" target="_blank">这儿</a>',
				'variable'=>'doc_changefreq',
				'type'=>'select',
				'value'=>'monthly',
				'extra'=>'always=总是更新
					hourly=每小时更新
					daily=每天更新
					weekly=每周更新
					monthly=每月更新
					yearly=每年更新
					never=永不更新'
			),
			array('displayorder'=>"3",
				'title'=>'百度 Sitemap 正文',
				'description'=>'正文使用的字段。选择“使用正文字段”内容更完整，但可能加重系统负担。',
				'variable'=>'textcolumn',
				'type'=>'select',
				'value'=>'summary',
				'extra'=>'summary=使用提要字段
					content=使用正文字段
					none=不使用任何字段'
			),
			array('displayorder'=>"4",
				'title'=>'百度 Sitemap 更新周期',
				'description'=>'以分钟为单位，具体说明请参考 <a href="http://news.baidu.com/newsop.html#ks3" target="_blank">这儿</a>',
				'variable'=>'updateperi',
				'type'=>'text',
				'value'=>'20',
				'extra'=>''
			),
			array('displayorder'=>"5",
				'title'=>'自动更新百度 Sitemap',
				'description'=>'是否按更新周期自动更新百度 Sitemap。当更新周期设定过短时，可能会降低系统执行效率。启用此功能需先按钩子页说明添加钩子代码。',
				'variable'=>'autoupdate_baiduxml',
				'type'=>'radio',
				'value'=>'0',
				'extra'=>''
			)
		);
		$plugin['hooks']=array(
			array('available'=>"1",
				'title'=>'auto_baiduxml',
				'description'=>'在/control/index.php中，查找：<br />$this->view->assign(\\\'indexcache\\\',$indexcache); <br />在这一行后面添加“调用代码”中的代码。此钩子可实现百度地图自动刷新。',
				'code'=>'
					$this->loadplugin("sitemap");
					$_ENV["sitemap"]->autoupdate_baiduxml();
				'
			)
		);
		
		return $plugin;
	}

	function uninstall(){
		$this->_remove_all_sitemaps();
	}

	
	/**
	 * 创建分页的词条 sitemap. 有剩余返回当前offset，无剩余返回false
	 *
	 * @return mixed
	 */
	function create_doc_page() {
		//获取上次创建分页；
		$current_page = $this->_lastpage_get();
		//如果实际总分页小于当前分页（生成sitemap后又删除一些词条的情况下），则当前页=总分页。
		$rs = $this->db->fetch_first("SELECT count(did) as count_id from ".DB_TABLEPRE."doc where visible = 1");
		$total_page = empty($rs['count_id']) ? 0 : floor($rs['count_id']/1000);
		if($total_page < $current_page) {
			$current_page = $total_page;
		}
				
		$current_offset = $current_page * 1000;
		$query = $this->db->query("SELECT did, lastedit FROM ".DB_TABLEPRE."doc where visible = 1 order by did asc limit {$current_offset}, 1000");
		$this->_sitemap_start_new();
		$doc = array();
		$page_last_did = 0;
		while($row = $this->db->fetch_array($query)){
			$doc['loc']        = "{$this->base->setting['site_url']}/{$this->base->setting['seo_prefix']}doc-view-{$row['did']}{$this->base->setting['seo_suffix']}";
			$doc['lastmod']    = gmdate('Y-m-d\TH:i:s+00:00', $row['lastedit']);
			$doc['changefreq'] = $this->base->plugin['sitemap']['vars']['doc_changefreq']; //////////////////
			$doc['priority']   = "0.8"; ////////////////
			$this->_sitemap_add_item($doc);
			$page_last_did = $row['did'];
		}		
		$this->_sitemap_end_save('sitemap_doc_'.$current_page);
		
		if($this->db->affected_rows() < 1000) { //如果当前不足一页（每页1000条），则记录最后页为当前页，下次更新继续以当前页更新。
			$this->_lastpage_log($current_page);
		} else { //否则记录下次更新以下页开始更新。
			$this->_lastpage_log($current_page + 1);
		}
		
		$rs = $this->db->fetch_first("SELECT max(did) as max_id from ".DB_TABLEPRE."doc where visible = 1");
		if(!empty($rs['max_id']) && $page_last_did < $rs['max_id']) { //如果当前页最后一条的did小于数据库中的最大did，则表示尚未完毕
			return $current_offset;
		} else { //如果是最后，创建索引页
			$this->_create_index();
			return false;
		}
	}
	
	function _lastpage_get() {
		$fh = fopen(HDWIKI_ROOT.'/plugins/sitemap/last_page.log', 'a+');
		$page = fgets($fh);
		return $page ? $page : 0;
	}
	
	function _lastpage_log($page) {
		$fh = fopen(HDWIKI_ROOT.'/plugins/sitemap/last_page.log', 'wb+');
		fwrite($fh, $page);
		fclose($fh);
	}
	
	function rebuild() { //重置Sitemap
		$this->_remove_all_sitemaps();
		$this->_lastpage_log(0);
	}
	
	function _remove_all_sitemaps() {
		$dh = opendir(HDWIKI_ROOT); 
		while($filename = readdir($dh)){
		    if(strpos($filename, '.xml') !== false && strpos($filename, 'sitemap') !== false) {
				unlink(HDWIKI_ROOT.'/'.$filename);
		    }
		}
		closedir($dh);
	}
	
	function _create_index() {
		$this->_create_home();
		$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!-- Created by HDWiki Sitemap Plugin. Programmed by http://www.webird.net -->
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
XML;
		//$fh = fopen('sitemap.xml', 'wb+');
		//fwrite($fh, $xml);
		$dh = opendir(HDWIKI_ROOT); 
		while($filename = readdir($dh)){
		    if($this->_is_sitemap($filename)) {
		    	$filemtime = gmdate('Y-m-d\TH:i:s+00:00', filemtime($filename));
		    	$xml .= "<sitemap><loc>{$this->base->setting['site_url']}/{$filename}</loc><lastmod>{$filemtime}</lastmod></sitemap>";
		    }
		}
		closedir($dh);
		$xml .= '</sitemapindex>';
		
		$filename = 'sitemap';
		if($this->base->plugin['sitemap']['vars']['use_gzip']) {
			$filename = $filename.'.xml.gz';
			$fh = fopen($filename, 'wb+');
			fwrite($fh, gzencode($xml));
		} else {
			$filename = $filename.'.xml';
			$fh = fopen($filename, 'wb+');
			fwrite($fh, $xml);
		}
		fclose($fh);
		$xml = null;
	}
	
	function _is_sitemap($filename) {
		if($this->base->plugin['sitemap']['vars']['use_gzip']) {
			return $filename != 'sitemap.xml.gz' && substr($filename, -7) == '.xml.gz' && strpos($filename, 'sitemap') !== false;
		} else {
			return $filename != 'sitemap.xml' && substr($filename, -4) == '.xml' && strpos($filename, 'sitemap') !== false;
		}
	}
	
	//生成首页sitemap
	function _create_home() {
		$this->_sitemap_start_new();
		$url['loc']        = "{$this->base->setting['site_url']}";
		$url['lastmod']    = gmdate('Y-m-d\TH:i:s+00:00');
		$url['changefreq'] = $this->base->plugin['sitemap']['vars']['idx_changefreq']; //////////////////
		$url['priority']   = "1.0"; ////////////////
		$this->_sitemap_add_item($url);
		$this->_sitemap_end_save('sitemap_idx');
	}
	
	function _sitemap_start_new() {
		$this->xml = <<<XML
<?xml version='1.0' encoding='UTF-8'?>
<!-- Created by HDWiki Sitemap Plugin. Programmed by http://www.webird.net -->
<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>
XML;
	}
	
	function _sitemap_add_item($item) {
		if(empty($this->xml)) { return false; }
		$this->xml .= '<url>';
		foreach ($item as $key=>$value) {
			$this->xml .= "<{$key}>{$value}</{$key}>";
		}
		$this->xml .= '</url>';
		return true;
	}
		
	function _sitemap_end_save($filename) {
		$this->xml .= '</urlset>';

		if($this->base->plugin['sitemap']['vars']['use_gzip']) {
			$filename = $filename.'.xml.gz';
			$fh = fopen($filename, 'wb+');
			fwrite($fh, gzencode($this->xml));
		} else {
			$filename = $filename.'.xml';
			$fh = fopen($filename, 'wb+');
			fwrite($fh, $this->xml);
		}
		fclose($fh);
		$this->xml = null;
		return $filename;
	}
	
	function submit() {	
    	$filename = $this->base->plugin['sitemap']['vars']['use_gzip'] == '1' ? 'sitemap.xml.gz' : 'sitemap.xml';
    	if(!file_exists(HDWIKI_ROOT.'/'.$filename)) {
    		return false;
    	}
    	$services = array('Google'=>'http://www.google.com/webmasters/sitemaps',
    		'Ask.com'=>'http://submissions.ask.com');
    	$result = array();
    	foreach ($services as $site=>$url) {
    		$url .= '/ping?sitemap='.urlencode($this->base->setting['site_url'].'/'.$filename);
    		$result[$site] = $this->_fetchUrl($url);
    	}
    	return $result;
    }
    
    function _fetchUrl($url) {
    	$result = '';
    	$url = parse_url($url);
    	$errno = $errstr = '';
    	$fp = fsockopen($url['host'], 80, $errno, $errstr, 30);
		if ($fp) {
		    $out = "GET {$url['path']}?{$url['query']} HTTP/1.1\r\n";
		    $out .= "Host: {$url['host']}\r\n";
		    $out .= "Connection: Close\r\n\r\n";
		    fwrite($fp, $out);
		    $result = fgets($fp);
		    fclose($fp);
		}
		return $result;
    }
    
    
    ///////////////////////////
    
    function create_baiduxml() {
    	$this->base->load('user');
    	$filename = 'baidu.xml';
    	$fh = fopen($filename, 'wb+');
    	$website = parse_url($this->base->setting['site_url']);
    	$website = $website['host'];
    	$webmaster = $_ENV['user']->get_admin_email();
    	$updateperi = intval($this->base->plugin['sitemap']['vars']['updateperi']);
    	if($this->base->plugin['sitemap']['vars']['textcolumn'] == 'content') {
    		$textcolumn = 'd.content as `text`,';
    	} else if($this->base->plugin['sitemap']['vars']['textcolumn'] == 'summary')  {
    		$textcolumn = 'd.summary as `text`,';
    	} else {
    		$textcolumn = '';
    	}
    	$xml = <<<XML
<?xml version='1.0' encoding='UTF-8'?>
<!-- Created by HDWiki Sitemap Plugin. Programmed by http://www.webird.net -->
<document>
	<webSite>{$website}</webSite>
	<webMaster>$webmaster</webMaster>
	<updatePeri>$updateperi</updatePeri>

XML;
    	fwrite($fh, $xml);
    	$query = $this->db->query("SELECT d.did, d.title, $textcolumn d.lastedit, c.name as category FROM ".DB_TABLEPRE."doc d left join ".DB_TABLEPRE."category c on d.cid = c.cid order by did desc limit 100");
    	while($row = $this->db->fetch_array($query)){
    		$item = "<item>\n\r";
    		$item .= "<link>{$this->base->setting['site_url']}/{$this->base->setting['seo_prefix']}doc-view-{$row['did']}{$this->base->setting['seo_suffix']}</link>\n\r";
    		$item .= "<title>".$this->_escapedata($row['title'])."</title>\n\r";
    		if($this->base->plugin['sitemap']['vars']['textcolumn'] != 'none') {
    			$item .= "<text><![CDATA[".strip_tags($row['text'])."]]></text>\n\r";
    		}
    		$item .= "<category>".$this->_escapedata($row['category'])."</category>\n\r";
    		$item .= "<pubDate>".date('Y/m/d H:i:s', $row['lastedit'])."</pubDate>\n\r";
    		$item .= "</item>\n\r";
    		
    		fwrite($fh, $item);
    		
    	}
    	//...
    	
    	fwrite($fh, '</document>');
    	fclose($fh);
    }
    
    function autoupdate_baiduxml() {
    	$filename = 'baidu.xml';
    	if($this->base->plugin['sitemap']['vars']['autoupdate_baiduxml'] 
    		&& file_exists($filename) 
    		&& (time() - filemtime($filename) >= 60*intval($this->base->plugin['sitemap']['vars']['updateperi']))) 
    	{
    		$this->create_baiduxml();
    	}
    }

	
	function _escapedata($data)
    {
        $position=0;
        $length=strlen($data);
        $escapeddata='';
        for(;$position<$length;)
        {
            $character=substr($data,$position,1);
            $code=Ord($character);
            switch($code)
            {
                case 34:
                    $character='&quot;';
                    break;
                case 38:
                    $character='&amp;';
                    break;
                case 39:
                    $character='&apos;';
                    break;
                case 60:
                    $character='&lt;';
                    break;
                case 62:
                    $character='&gt;';
                    break;
                default:
                    if($code<32)
                        $character=('&#'.strval($code).';');
                    break;
            }
            $escapeddata.=$character;
            $position++;
        }
        return $escapeddata;
    }
    
    function get_last_update($filename) {
    	return file_exists($filename) ? filemtime($filename) : false;
    }
}	

?>
