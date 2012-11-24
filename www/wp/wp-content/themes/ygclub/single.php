<?php get_header(); ?>
        
    	<div id="content">
				<?php while ( have_posts() ) : the_post() ?>
					<div id="post-<?php the_ID() ?>" class="entry">
			        
						
			            <div class="entry-head">
			            	<h2 class="entry-title"><?php the_title() ?></h2>
			            </div>
			            
						<div class="entry-content">
			        	<?php the_content('阅读全文 &raquo;'); ?></div>
						
			            <div class="entry-meta">
						<span class="author vcard"><?php printf('作者： %s', '<a class="url fn n" href="'.get_author_link(false, $authordata->ID, $authordata->user_nicename).'" title="' . sprintf('查看 %s 的所有日志', $authordata->display_name) . '">'.get_the_author().'</a>') ?></span>
						<span class="meta-sep">|</span>
						<span class="cat-links"><?php printf('分类： %s', get_the_category_list(', ')) ?></span>
						</div>
					</div><!-- .post -->
			
			<?php endwhile ?>
			
					<div id="navigation">
						<div class="nav-previous"><?php previous_posts_link('&laquo; 上一页') ?></div>
						<div class="nav-next"><?php next_posts_link('下一页 &raquo; ') ?></div>
					</div>
        </div>
        
<?php get_sidebar(); ?>
<?php get_footer(); ?>