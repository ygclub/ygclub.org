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

                	<a href="/bbs" title="阳光论坛">阳光论坛</a> | <a href="/wiki" title="阳光百科">阳光百科</a>

            </div>
            
            <h1 id="logo">阳光志愿者</h1>
            
            <div id="nav">
            	<div id="nav_l"></div>
                
                <ul id="menu_l">
                	<li id="home" <?php if (is_home())  echo " class=\"current_page_item\""; ?>><a href="<?php bloginfo('url'); ?>">首页</a></li>
        					<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
                	<li><a href="/wiki/index.php?doc-view-59.html" title="联系我们">联系我们</a></li>
                </ul>
                
                <div id="nav_r"></div>
            </div>
        </div>
        
    	<div id="content">
				<?php while ( have_posts() ) : the_post() ?>
					<div id="post-<?php the_ID() ?>" class="entry">
			        
						
			            <div class="entry-head">
			            	<h2 class="entry-title"><?php the_title() ?></h2>
			            </div>
			            
						<div class="entry-content">
			        	<?php the_content('阅读全文 &raquo;'); ?></div>
						
			         
					</div><!-- .post -->
			
			<?php endwhile ?>
			
					<div id="navigation">
						<div class="nav-previous"><?php previous_posts_link('&laquo; 上一页') ?></div>
						<div class="nav-next"><?php next_posts_link('下一页 &raquo; ') ?></div>
					</div>
        </div>
        
<?php get_sidebar(); ?>
<?php get_footer(); ?>
