<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="志愿者,ygclub,北京,流动儿童,阳光志愿者,阳光志愿者俱乐部,阳光义教,阳光义教俱乐部,打工子弟,留守儿童,爱心,非营利性组织,NGO,农民工,支教,志教,志愿教育" />
<meta name="description" content="北京阳光志愿者俱乐部（简称“阳光志愿者”或LEAD）是一个独立运作、专注于为流动儿童教育提供志愿教育机会的纯“草根”志愿者组织。" />
<title>
<?php if (is_home()) {
	bloginfo('name');
} 
	elseif (is_404()) {
	?>404错误，该页面不存在！<?php 
} elseif (is_category()) {
	?><?php bloginfo('name'); ?> - 分类: <?php wp_title(''); ?><?php 
} elseif (is_search()) {
	?><?php bloginfo('name'); ?> - 搜索结果 <?php 
} elseif ( is_day() || is_month() || is_year() ) {
	?><?php bloginfo('name'); ?> - 存档: <?php wp_title(''); ?><?php 
} else { ?><?php wp_title(''); ?> - <?php bloginfo('name'); ?><?php 
}
?>
</title>

<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom" href="<?php bloginfo('atom_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>

<body>
<div id="top"></div>
<div id="wrapper">
 
	<div id="container">
    
		<div id="header">
        	<div id="statu_bar">

                	<a href="http://www.ygclub.org/bbs" title="阳光论坛">阳光论坛</a> | <a href="http://www.ygclub.org/wiki" title="阳光百科">阳光百科</a>

            </div>
            
            <h1 id="logo"><a href="<?php bloginfo('url'); ?>" title="阳光志愿者">阳光志愿者</a></h1>
            
            <div id="nav">
            	<div id="nav_l"></div>
                
                <ul id="menu_l">
                	<li id="home" <?php if (is_home())  echo " class=\"current_page_item\""; ?>><a href="<?php bloginfo('url'); ?>">首页</a></li>
        					<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
                	<li><a href="http://www.ygclub.org/wiki/index.php?doc-view-59.html" title="联系我们">联系我们</a></li>
                </ul>
                
                <div id="nav_r"></div>
            </div>
        </div>

		<div id="banner"></div>
        
    	<div id="content">
        	<div id="ygclub_about">
            		
                	北京阳光志愿者俱乐部（简称“阳光志愿者”或LEAD）是一个独立运作、专注于为流动儿童教育提供志愿教育机会的纯“草根”志愿者组织。阳光志愿者成立于2003年6月1日，主要利用互联网的广泛联络功能和口口相传的宣传形式，以明确的宗旨——“让教育实现梦想(Let Education Achieve Dreams)”及“身体力行”的公益行动吸引有意愿的年青人主动靠拢，使社会个体的零散资源聚集在一起。再通过“周末课堂”的形式，组织志愿者为北京城区打工子弟学校的孩子提供英语、计算机、艺术、科普、国学启蒙、趣味经济等课外兴趣课。...(<span><a href="http://ygclub.org/wp/?page_id=2" title="了解详细">了解详细&raquo;</a></span>)
                
            </div>
            
            <div id="ygclub_projects">
            	<h3>阳光项目</h3>
                <ul>
                	<li><a href="http://www.ygclub.org/wiki/index.php?doc-view-553.html"><span class="project_add">昌平</span><span class="project_name">信心学校</span></a></li>
                	<li><a href="http://www.ygclub.org/wiki/index.php?doc-view-511.html"><span class="project_add">昌平</span><span class="project_name">经纬学校</span></a></li>
                	<li><a href="http://www.ygclub.org/wiki/index.php?doc-view-517.html"><span class="project_add">怀柔</span><span class="project_name">育才学校</span></a></li>
                	<li><a href="http://www.ygclub.org/bbs/thread-5364-1-1.html"><span class="project_add">昌平</span><span class="project_name">爱加倍社区中心</span></a></li>
                	<li><a href="http://www.ygclub.org/bbs/thread-5632-1-1.html"><span class="project_add">海淀</span><span class="project_name">朱房村社区中心</span></a></li>
                	<li><a href="http://www.ygclub.org/bbs/thread-5649-1-1.html"><span class="project_add">昌平</span><span class="project_name">木兰社区中心</span></a></li>
                	<!--li><a href="#"><span class="project_name project_more">其它项目&raquo;</span></a></li-->
                </ul>
            </div>
            
            <div id="ygclub_activities">
            	<h3>我们在做什么</h3>
                <ul>
									<script type="text/javascript" src="http://www.ygclub.org/bbs/api/javascript.php?key=%E9%A6%96%E9%A1%B5%E8%B0%83%E7%94%A8"></script>
                	<li><a href="http://www.ygclub.org/bbs/forum-2-1.html" title=" ">更多近期活动&raquo;</a></li>
                </ul>
            </div>
        </div>
        
        <div id="sidebar">
        	  <h3>关注我们</h3>
        		<div id="sina_weibo">
              <iframe width="100%" height="220" class="share_self"  frameborder="0" scrolling="no" src="http://service.t.sina.com.cn/widget/WeiboShow.php?width=0&height=220&fansRow=2&ptype=0&speed=300&skin=3&isTitle=0&noborder=1&isWeibo=1&isFans=0&uid=1729744050&verifier=b1fc848b"></iframe> 
            </div>

            <div id="ygclub_elsewhere">
                <h3>阳光俱乐部在：</h3>
                <div id="social_site">
                	<ul>
                    	<li id="douban"><a href="http://site.douban.com/ygclub/" title="阳光志愿者俱乐部在豆瓣网">豆瓣网</a></li>
                    	<li id="facebook"><a href="http://www.facebook.com/pages/北京LEAD阳光志愿者/192549724111377" title="阳光志愿者俱乐部在Facebook">Facebook</a></li>
                    	<li id="twitter"><a href="http://twitter.com/ygclub" title="阳光志愿者俱乐部在Twitter">twitter</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
<?php get_footer(); ?>