<?php get_header(); ?>
	<div id="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<div id="article">
			<h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
			<div class="info">
            	<span class="meat_span">作者: <?php the_author() ?></span>
                <span class="meat_span">分类: <?php the_category(', ') ?></span>
                <span class="meat_span">发布时间: <?php the_time('Y-m-d H:i') ?></span>
                <span class="meat_span"><i class="iconfont">&#279;</i>浏览<?php post_views(' ', ' 次'); ?></span>
                <span class="meat_span"><i class="iconfont">&#54;</i><?php comments_popup_link ('没有评论','1条评论','%条评论'); ?></span>
                <?php edit_post_link('编辑', '<span class="meat_span">', '</span>'); ?>
            </div>
			<div class="text">
			<?php the_content(); ?>
			<!--文章分页效果-->
			<?php 
				 wp_link_pages('before=&after=&next_or_number=next&previouspagelink=上一页&nextpagelink= ');
				 wp_link_pages('before=&after=&next_or_number=number'); echo "";
				 wp_link_pages('before=&after=&next_or_number=next&previouspagelink= &nextpagelink=下一页'); 
			?>
			<!--文章分页结束-->
			</div>
            <div class="text_add">
                <div class="copy"><p style="color:#F00;">本文出自 <?php bloginfo('name');?>，转载时请注明出处及相应链接。</p><p style="color:#777;font-size:12px;">本文永久链接: <?php the_permalink() ?></p></div>
                <div class="share">
				<?php
					if(stripslashes(get_option('tang_share'))) 
					{
						echo stripslashes(get_option('tang_share'));
					}
					else
					{
				?>
				<!-- 百度分享按钮 -->
				<div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_t163" data-cmd="t163" title="分享到网易微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a></div>
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=86835285.js?cdnversion='+~(-new Date()/36e5)];</script>
				<!-- 百度分享按钮结束 -->
				<?php			
					}
				?>
				</div>
                </div>
			<div class="meta"><i class="iconfont">&#48;</i><?php the_tags('', ', ', ''); ?></div>
		</div>

		<!--cnzz tui-->
		<?php echo stripslashes(get_option('tang_tuijian')); ?>
		<!--cnzz tui-->

		<?php endwhile; ?>
        <div class="post_link">
			<div class="prev"><?php previous_post_link('« %link') ?></div>
			<div class="next"><?php next_post_link('%link »') ?></div>
        </div>
        
        <div id="comments"><?php comments_template(); ?></div>
	</div>
	<?php get_sidebar('single'); ?>
<?php get_footer(); ?>