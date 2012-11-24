<?php
// Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	
	if ( post_password_required() ) { ?>
		<p class="nocomments">本文受密码保护，请输入密码查看评论！</p> 
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h3 id="comments">目前<?php comments_number('还没有', '已有1条', '已有%条');?>评论 - <small><a href="#comment" title="发表评论">发表评论&raquo;</a></small></h3>
    <div id="commentField">
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<ol class="commentlist">
	<?php wp_list_comments('type=comment&callback=ladytess_comment'); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
    </div>
 <?php else : // this is displayed if there are no comments so far ?>
	<h3 id="comments">目前还没有评论 - <small><a href="#comment" title="发表评论">发表评论&raquo;</a></small></h3>
	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">本文已关闭评论</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<div id="respond">

<h3><?php comment_form_title('发表评论', '评论“%s”' ); ?></h3>

<div id="cancel-comment-reply"> 
	<small><?php cancel_comment_reply_link() ?></small>
</div> 

<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p>您必须<a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">登入</a>后才能发表评论！</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( is_user_logged_in() ) : ?>

<p>登入为<a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>。 <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="登出">登出&raquo;</a></p>

<?php else : ?>

<!--开始-->
<?php if ( $comment_author != "" ) : ?>
  <script type="text/javascript">function setStyleDisplay(id, status){document.getElementById(id).style.display = status;}</script>
   <p class="form_row small">
     
<?php printf(__('欢迎您， <strong>%s</strong>！'), $comment_author) ?>    
   <span style="" id="show_author_info"><a href="javascript:setStyleDisplay('author_info','');setStyleDisplay('show_author_info','none');setStyleDisplay('hide_author_info','');">改变身份 ?</a></span>
   <span style="display: none;" id="hide_author_info"><a href="javascript:setStyleDisplay('author_info','none');setStyleDisplay('show_author_info','');setStyleDisplay('hide_author_info','none');">关闭 ?</a></span>
   </p>

<?php endif; ?>
<!--结束-->
<div id="author_info">
<p>
<label for="author">名字(必须填写)</label><br />
<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
</p>

<p>
<label for="email">邮箱(绝不公开)</label><br />
<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" /></p>

<p>
<label for="url">网站</label><br />
<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
</p>
</div>
<!--开始-->
<?php if ( $comment_author != "" ) : ?>
<script type="text/javascript">setStyleDisplay('hide_author_info','none');setStyleDisplay('author_info','none');</script>
<?php endif; ?>
<!--结束-->

<?php endif; ?>

<!--<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>', 'kubrick'), allowed_tags()); ?></small></p>-->

<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="提交评论" />
<?php comment_id_fields(); ?> 
</p>
<?php do_action('comment_form', $post->ID); ?>
<script type="text/javascript">       
        document.getElementById("comment").onkeydown = function (moz_ev)
        {
                var ev = null;
                if (window.event){
                        ev = window.event;
                }else{
                        ev = moz_ev;
                }
                if (ev != null && ev.ctrlKey && ev.keyCode == 13)
                {
                        document.getElementById("submit").click();
                }
        }
</script>
</form>

<?php endif; // If registration required and not logged in ?>
</div>
<?php endif; // if you delete this the sky will fall on your head ?>