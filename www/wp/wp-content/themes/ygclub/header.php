<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>document.title='北京(LEAD)阳光志愿者俱乐部';</script>
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
            
            <h1 id="logo"><a href="<?php bloginfo('url'); ?>" title="阳光志愿者">阳光志愿者</a></h1>
            
            <div id="nav">
            	<div id="nav_l"></div>
                
                <ul id="menu_l">
                	<li id="home" <?php if (is_home())  echo " class=\"current_page_item\""; ?>><a href="<?php bloginfo('url'); ?>">首页</a></li>
			<li id="bbs"><a href="/bbs" title="阳光论坛">阳光论坛</a></li>
                        <li id="wiki"><a href="/wiki" title="阳光百科">阳光百科</a></li>
        					<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
                	<li><a href="bbs/thread-4734-1-1.html" title="加入我们">加入我们</a></li>
                	<li><a href="/wiki/index.php?title=%E8%81%94%E7%B3%BB%E6%88%91%E4%BB%AC" title="联系我们">联系我们</a></li>
                </ul>
                
                <div id="nav_r"></div>
            </div>
        </div>