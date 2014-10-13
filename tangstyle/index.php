<?php get_header(); ?>
	<div id="main">
		<?php while ( have_posts() ) : the_post(); ?>
		<div class="post_list">
			<?php if ( is_sticky() ) : ?>
				<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                <div class="info"><?php the_author() ?> | <?php the_category(', ') ?> | <?php the_time('Y-m-d'); ?></div>
			<?php else : ?>
				<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <div class="info"><?php the_author() ?> | <?php the_category(', ') ?> | <?php the_time('Y-m-d'); ?></div>
				<div class="excerpt">
					<?php if( function_exists('catch_that_image')&&catch_that_image()!='' ) { ?><div class="thumbnail"><a href="<?php the_permalink();?>" title="<?php the_title();?>" target = "_blank"><img src="<?php echo catch_that_image() ?>" alt="<?php the_title();?>"/></a></div><?php } else { } ?>
					<!-- 首页摘要字数 -->
                	<?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, (int)stripslashes(get_option('tang_zhaiyao')),"..."); ?>
                	<span class="more">[<a href="<?php the_permalink() ?>" title="详细阅读 <?php the_title(); ?>" rel="bookmark" target = "_blank">阅读全文</a>]</span>
                </div>
                <div class="meta">
                	<span class="meat_span"><i class="iconfont">&#279;</i>浏览<?php post_views(' ', ' 次'); ?></span>
                    <span class="meat_span"><i class="iconfont">&#54;</i><?php comments_popup_link ('没有评论','1条评论','%条评论'); ?></span>
                    <span class="meat_span meat_max"><i class="iconfont">&#48;</i><?php the_tags('', ', ', ''); ?></span>
                </div>
			<?php endif; ?>
		</div>
		<?php endwhile; ?>
		<div class="navigation"><?php pagination($query_string); ?></div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>