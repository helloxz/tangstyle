<?php
//生成文章目录,默认关闭
if (get_option('tang_mulu') == '显示')
{
	function article_index($content) {

	$matches = array();
	$ul_li = '';

	$r = "/<h3>([^<]+)<\/h3>/im";

	if(preg_match_all($r, $content, $matches)) {
	foreach($matches[1] as $num => $title) {
	$content = str_replace($matches[0][$num], '<h3 id="title-'.$num.'">'.$title.'</h3>', $content);
	$ul_li .= '<li><a href="#title-'.$num.'" title="'.$title.'">'.$title."</a></li>\n";
	}

	$content = "\n<div id=\"article-index\">
	<strong>文章目录</strong>
	<ul id=\"index-ul\">\n" . $ul_li . "</ul>
	</div>\n" . $content;
	}

	return $content;
	}

	add_filter( "the_content", "article_index" );
}
//生成文章目录结束

//显示友情链接模块
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
//显示友链模块结束

//让友情链接只显示在首页
function rbt_friend_links($output){
   if (!is_home()|| is_paged()){
   $output = "";
   }
   return $output;
   }
   add_filter('wp_list_bookmarks','rbt_friend_links');
//让友情链接显示在首页结束

//找回上传设置
if(get_option('upload_path')=='wp-content/uploads' || get_option('upload_path')==null) {
	update_option('upload_path',WP_CONTENT_DIR.'/uploads');
}
//找回上传设置结束

//判断文章页面是否被百度收录
function d4v($url){
	$url='http://www.baidu.com/s?wd='.$url;
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	$rs=curl_exec($curl);
	curl_close($curl);
	if(!strpos($rs,'没有找到')){
		return 1;
	}else{
		return 0;
	}
}
add_filter( 'the_content',  'baidu_submit' );
function baidu_submit( $content ) {
	if( is_single() && current_user_can( 'manage_options') )
		if(d4v(get_permalink()) == 1) 
			$content="<p align=right>百度已收录(仅管理员可见)</p>".$content; 
		else 
			$content="<p align=right><b><a style=color:red target=_blank href=http://zhanzhang.baidu.com/sitesubmit/index?sitename=".get_permalink().">百度未收录!点击此处提交</a></b>(仅管理员可见)</p>".$content;  
		return $content;
	}
//判断百度收录结束

//HTML文本增强
//添加HTML编辑器自定义快捷标签按钮
add_action('after_wp_tiny_mce', 'bolo_after_wp_tiny_mce');
function bolo_after_wp_tiny_mce($mce_settings) {
?>
<script type="text/javascript">
QTags.addButton( 'hr', 'hr', "<hr />\n", "" );
QTags.addButton( 'h1', 'h1', "\n<h1>", "</h1>\n" );
QTags.addButton( 'h2', 'h2', "\n<h2>", "</h2>\n" );
QTags.addButton( 'h3', 'h3', "\n<h3>", "</h3>\n" );
QTags.addButton( 'bt3', '标题3', "<div id = \"biaoti\"><h3></h3></div>", "\n" );
QTags.addButton( 'br', 'br', "<br />", "" );
QTags.addButton( 'red', '红色', "\n<span style = \"color:red;\">红色字体内容</span>", "" );
QTags.addButton( 'syntax', '高亮', "\n<pre lang=\"html\" line=\"1\" escaped=\"true\">", "\n\n</pre>" );
QTags.addButton( 'wp_page', '分页', "<!--nextpage-->\n", "" );
QTags.addButton( 'nofollow', 'nofollow', "<a href = \"URL\" title = \"标题\" rel=\"nofollow\" target = \"_blank\">链接文本</a>", "" );



function bolo_QTnextpage_arg1() {
}
</script>
<?php
}
//HTML文本增强结束


/*自己添加的文本增强*/
function add_editor_buttons($buttons) {

$buttons[] = 'fontselect';

$buttons[] = 'fontsizeselect';

$buttons[] = 'cleanup';

$buttons[] = 'styleselect';

$buttons[] = 'hr';

$buttons[] = 'del';

$buttons[] = 'sub';

$buttons[] = 'sup';

$buttons[] = 'copy';

$buttons[] = 'paste';

$buttons[] = 'cut';

$buttons[] = 'undo';

$buttons[] = 'image';

$buttons[] = 'anchor';

$buttons[] = 'backcolor';

$buttons[] = 'wp_page';

$buttons[] = 'charmap';

return $buttons;

}

add_filter("mce_buttons_3", "add_editor_buttons");
//增强编辑器结束

//禁用谷歌字体
class Disable_Google_Fonts {
public function __construct() {
add_filter( 'gettext_with_context', array( $this, 'disable_open_sans' ), 888, 4 );
}
public function disable_open_sans( $translations, $text, $context, $domain ) {
if ( 'Open Sans font: on or off' == $context && 'on' == $text ) {
$translations = 'off';
}
return $translations;
}
}
$disable_google_fonts = new Disable_Google_Fonts;
//禁用谷歌字体结束

//面包屑导航
function dimox_breadcrumbs() {
 
  $delimiter = '&raquo;';
  $name = '首页'; //text for the 'Home' link
  $currentBefore = '<span>';
  $currentAfter = '</span>';
 
  if ( !is_home() && !is_front_page() || is_paged() ) {
 
    echo '<div id="crumbs">';
 
    global $post;
    $home = get_bloginfo('url');
    echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';
 
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $currentBefore . 'Archive by category &#39;';
      single_cat_title();
      echo '&#39;' . $currentAfter;
 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('d') . $currentAfter;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('F') . $currentAfter;
 
    } elseif ( is_year() ) {
      echo $currentBefore . get_the_time('Y') . $currentAfter;
 
    } elseif ( is_single() ) {
      $cat = get_the_category(); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo $currentBefore;
      the_title();
      echo $currentAfter;
 
    } elseif ( is_page() && !$post->post_parent ) {
      echo $currentBefore;
      the_title();
      echo $currentAfter;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $currentBefore;
      the_title();
      echo $currentAfter;
 
    } elseif ( is_search() ) {
      echo $currentBefore . 'Search results for &#39;' . get_search_query() . '&#39;' . $currentAfter;
 
    } elseif ( is_tag() ) {
      echo $currentBefore . 'Posts tagged &#39;';
      single_tag_title();
      echo '&#39;' . $currentAfter;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;
 
    } elseif ( is_404() ) {
      echo $currentBefore . 'Error 404' . $currentAfter;
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
 
    echo '</div>';
 
  }
}
/*面包屑导航结束*/

/*文章浏览次数统计*/
function record_visitors()
{
	if (is_singular())
	{
	  global $post;
	  $post_ID = $post->ID;
	  if($post_ID)
	  {
		  $post_views = (int)get_post_meta($post_ID, 'views', true);
		  if(!update_post_meta($post_ID, 'views', ($post_views+1)))
		  {
			add_post_meta($post_ID, 'views', 1, true);
		  }
	  }
	}
}
add_action('wp_head', 'record_visitors');
 
function post_views($before = '(点击 ', $after = ' 次)', $echo = 1)
{
  global $post;
  $post_ID = $post->ID;
  $views = (int)get_post_meta($post_ID, 'views', true);
  if ($echo) echo $before, number_format($views), $after;
  else return $views;
}
/*文章浏览次数统计结束*/

function tangstyle_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'tangstyle_page_menu_args' );

function tangstyle_widgets_init() {
	register_sidebar(array(
		'name' => '首页侧栏',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => '分类页侧栏',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => '内容页侧栏',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}
add_action( 'widgets_init', 'tangstyle_widgets_init' );

if ( ! function_exists( 'tangstyle_content_nav' ) ) :

register_nav_menus(array('header-menu' => __( 'TangStyle导航菜单' ),));
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 200, 150 );

//评论模板
function tangstyle_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'tangstyle' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'tangstyle' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
    <li id="li-comment-<?php comment_ID(); ?>">
    <div id="comment-<?php comment_ID(); ?>">
    	<div class="avatar"><?php echo get_avatar( $comment, 40 );?></div>
    	<div class="comment">
        	<div class="comment_meta">
            <?php printf(__('<cite>%s</cite>'), get_comment_author_link()) ?>
            <span class="time"><?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></span>
            <span class="reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( '回复', 'tangstyle' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
            <?php edit_comment_link( __( '编辑', 'tangstyle' ), '<span class="edit_link">', '</span>' ); ?>
            </div>
            <?php comment_text(); ?>
            <?php if ( '0' == $comment->comment_approved ) : ?><p style="color:#F00;"><?php _e( '您的评论正在等待审核。', 'tangstyle' ); ?></p><?php endif; ?>
        </div>
    </div>
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

// 获得热评文章
function tangstyle_get_most_viewed($posts_num=10, $days=180){
    global $wpdb;
    $sql = "SELECT ID , post_title , comment_count FROM $wpdb->posts WHERE post_type = 'post' AND TO_DAYS(now()) - TO_DAYS(post_date) < $days AND ($wpdb->posts.`post_status` = 'publish' OR $wpdb->posts.`post_status` = 'inherit') ORDER BY comment_count DESC LIMIT 0 , $posts_num ";
    $posts = $wpdb->get_results($sql);
    $output = "";
    foreach ($posts as $post){
        $output .= "\n<li><a href= \"".get_permalink($post->ID)."\" title=\"".$post->post_title."\" >".$post->post_title."</a></li>";
    }
    echo $output;
}

//分页
function pagination($query_string){
global $posts_per_page, $paged;
$my_query = new WP_Query($query_string ."&posts_per_page=-1");
$total_posts = $my_query->post_count;
if(empty($paged))$paged = 1;
$prev = $paged - 1;							
$next = $paged + 1;	
$range = 5; // 分页数设置
$showitems = ($range * 2)+1;
$pages = ceil($total_posts/$posts_per_page);
if(1 != $pages){
	echo "<div class='pagination'>";
	echo ($paged > 2 && $paged+$range+1 > $pages && $showitems < $pages)? "<a href='".get_pagenum_link(1)."' class='fir_las'>最前</a>":"";
	echo ($paged > 1 && $showitems < $pages)? "<a href='".get_pagenum_link($prev)."' class='page_previous'>« 上一页</a>":"";		
	for ($i=1; $i <= $pages; $i++){
	if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
	echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>"; 
	}
	}
	echo ($paged < $pages && $showitems < $pages) ? "<a href='".get_pagenum_link($next)."' class='page_next'>下一页 »</a>" :"";
	echo ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) ? "<a href='".get_pagenum_link($pages)."' class='fir_las'>最后</a>":"";
	echo "</div>\n";
	}
}

//彩色标签云
function colorCloud($text) {
$text = preg_replace_callback('|<a (.+?)>|i', 'colorCloudCallback', $text);
return $text;
}
function colorCloudCallback($matches) {
$text = $matches[1];
$color = dechex(rand(0,16777215));
$pattern = '/style=(\'|\")(.*)(\'|\")/i';
$text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
return "<a $text>";
}
add_filter('wp_tag_cloud', 'colorCloud', 1);

//新窗口打开评论里的链接
function remove_comment_links() {
global $comment;
$url = get_comment_author_url();
$author = get_comment_author();
if ( empty( $url ) || 'http://' == $url )
$return = $author;
else
$return = "<a href='$url' rel='external nofollow' target='_blank'>$author</a>";
return $return;
}
add_filter('get_comment_author_link', 'remove_comment_links');
remove_filter('comment_text', 'make_clickable', 9);

//移除WordPress版本号
function wpbeginner_remove_version() {
return '';
}
add_filter('the_generator', 'wpbeginner_remove_version');

//文章中第一张图片获取图片
function catch_that_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);//用正则过滤文章
	$first_img = $matches [1] [0];
	if(empty($first_img)){
		$first_img = '';//第一张图片为空，也可以为一个默认地址。
	}
	return $first_img;
}

// 评论回应邮件通知
function comment_mail_notify($comment_id) {
  $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $to = $parent_id ? trim(get_comment($parent_id)->comment_author_email) : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam') && ($to != $admin_email) && ($comment_author_email == $admin_email)) {
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // no-reply 可改为可用的 e-mail.
    $subject = '您在 [' . get_option("blogname") . '] 的评论有新的回复';
    $message = '
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在 [' . get_option("blogname") . '] 的文章 《' . get_the_title($comment->comment_post_ID) . '》 上发表评论:<br />'
       . nl2br(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您的回复如下:<br />'
       . nl2br($comment->comment_content) . '<br /></p>
      <p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回复的完整內容</a></p>
      <p>欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
      <p>(此邮件由系统自动发出,请勿直接回复.)</p>
    </div>';
	$message = convert_smilies($message);
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
    //echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
  }
}
add_action('comment_post', 'comment_mail_notify');

?>
<?php
$themename = "TangStyle";
$shortname = "tang";
$options = array (
	array("name" => "标题（Title)",
	"id" => $shortname."_title",
	"type" => "text",
	"std" => "网站标题",
	"explain" => "SEO设置<br>它将显示在网站首页的title标签里，必填项。"
	),
	array("name" => "描述（Description）",
	"id" => $shortname."_description",
	"type" => "textarea",
	"css" => "class='h60px'",
	"std" => "网站描述",
	"explain" => "SEO设置<br>它将显示在网站首页的meta标签的description属性里"
	),
	array("name" => "关键字（KeyWords）",
	"id" => $shortname."_keywords",
	"type" => "textarea",
	"css" => "class='h60px'",
	"std" => "网站关键字",
	"explain" => "SEO设置<br>多个关键字请以英文逗号隔开，它将显示在网站首页的meta标签的keywords属性里"
	),
	array("name" => "是否显示新浪微博",
    "id" => $shortname."_weibo",
    "type" => "select",
    "std" => "隐藏",
    "options" => array("隐藏", "显示")),
	array("name" => "新浪微博地址",
    "id" => $shortname."_weibo_url",
    "type" => "text",
    "std" => "http://weibo.com/782622",
	"explain" => "请输入您的新浪微博地址"),
	array("name" => "是否显示腾讯微博",
    "id" => $shortname."_tqq",
    "type" => "select",
    "std" => "隐藏",
    "options" => array("隐藏", "显示")),
	array("name" => "腾讯微博地址",
    "id" => $shortname."_tqq_url",
    "type" => "text",
    "std" => "http://t.qq.com/tangjie",
	"explain" => "请输入您的腾讯微博地址"),
	array("name" => "是否显示文章目录（新增）",
    "id" => $shortname."_mulu",
    "type" => "select",
    "std" => "隐藏",
    "options" => array("隐藏", "显示")
	),
	array("name" => "版权年份",
	"id" => $shortname."_years",
	"std" => "2014",
	"type" => "text",
	"explain" => "它将显示在页面底部"
	),
	array("name" => "ICP备案号",
	"id" => $shortname."_icp",
	"type" => "text",
	"explain" => "页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入您的备案号，它将显示在页面底部，如果没有请留空"
	),
	array("name" => "摘要字数",
	"id" => $shortname."_zhaiyao",
	"std" => "330",
	"type" => "text",
	"explain" => "控制首页显示的摘要字数，默认为330(新增)"
	),
	array("name" => "分享代码",
	"id" => $shortname."_share",
	"type" => "textarea",
	"css" => "class='h80px'",
	"explain" => "如果留空则显示默认的百度分享按钮，若不为空则显示用户设置的分享按钮。<br />第三方分享工具主要有：百度分享、JiaThis、BShare 等（修改）"
	),
	array("name" => "统计代码",
	"id" => $shortname."_tongji",
	"type" => "textarea",
	"css" => "class='h80px'",
	"explain" => "页面底部可以显示第三方统计<br>您可以放一个或者多个统计代码"
	),
	array("name" => "CNZZ云推荐",
	"id" => $shortname."_tuijian",
	"type" => "textarea",
	"css" => "class='h80px'",
	"explain" => "添加CNZZ云推荐，在文章中将会显示相关文章，增强用户粘性。<br />详细使用教程请查看：<a href = \"http://www.zouxiuping.com\" target = \"_blank\">小z博客</a>（新增）"
	),
);
function mytheme_add_admin() {
    global $themename, $shortname, $options;
    if ( $_GET['page'] == basename(__FILE__) ) {
        if ( 'save' == $_REQUEST['action'] ) {
            foreach ($options as $value) {
            update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
            foreach ($options as $value) {
            if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }
            header("Location: themes.php?page=functions.php&saved=true");
            die;
        } else if( 'reset' == $_REQUEST['action'] ) {
            foreach ($options as $value) {
                delete_option( $value['id'] );
                update_option( $value['id'], $value['std'] );
            }
            header("Location: themes.php?page=functions.php&reset=true");
            die;
        }
    }
    add_theme_page($themename." 设置", "$themename 设置", 'edit_themes', basename(__FILE__), 'mytheme_admin');
}
function mytheme_admin() {
    global $themename, $shortname, $options;
    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' 设置已保存。</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' 设置已重置。</strong></p></div>';
?>

<style type="text/css">
.wrap h2 {color:#09C;}
.themeadmin {border:1px dashed #999;margin-top:20px;width:420px;position:10px;}
.options {margin-top:20px;}
.options input,.options textarea {padding:2px;border:1px solid;border-color:#666 #CCC #CCC #666;background:#F9F9F9;color:#333;resize:none;width:400px;}
.options .h80px {height:80px;}
.options .h60px {height:60px;}
.options .setup {border-top:1px dotted #CCC;padding:10px 0 10px 10px;overflow:hidden;}
.options .setup h3 {font-size:14px;margin:0;padding:0;}
.options .setup .value {float:left;width:410px;}
.options .setup .explain {float:left;}
</style>
<div class="wrap">
	<h2><b><?php echo $themename; ?>主题设置</b></h2>
    <hr />
	<div>主题作者：<a href="http://tangjie.me" target="_blank">唐杰</a> ¦ 当前版本：<a href="http://tangjie.me/tangstyle" title="TangStyle V1.0.9" target="_blank">V1.1</a> ¦ 主题介绍、使用帮助及升级请访问：<a href="http://tangjie.me/tangstyle" title="TangStyle" target="_blank">http://tangjie.me/TangStyle</a></div>
<form method="post">
<div class="options">
<?php foreach ($options as $value) {
	if ($value['type'] == "text") { ?>
	<div class="setup">
		<h3><?php echo $value['name']; ?></h3>
    	<div class="value"><input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?>" /></div>
    	<div class="explain"><?php echo $value['explain']; ?></div>
	</div>
	<?php } elseif ($value['type'] == "textarea") { ?>
	<div class="setup">
    	<h3><?php echo $value['name']; ?></h3>
        <div class="value"><textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" <?php echo $value['css']; ?> ><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?></textarea></div>
        <div class="explain"><?php echo $value['explain']; ?></div>
    </div>
    <?php } elseif ($value['type'] == "select") { ?>
	<div class="setup">
    	<h3><?php echo $value['name']; ?></h3>
        <div class="value">
<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>"><?php foreach ($value['options'] as $option) { ?>
		<option value="<?php echo $option;?>" <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>>
		<?php
		if ((empty($option) || $option == '' ) && isset($value['option'])) {
			echo $value['option'];
		} else {
			echo $option; 
		}?></option><?php } ?>
</select>
        </div>
        <div class="explain"><?php echo $value['explain']; ?></div>
    </div>
	<?php } ?>
<?php } ?>
</div>
<div class="submit">
<input style="font-size:12px !important;" name="save" type="submit" value="保存设置" class="button-primary" />
<input type="hidden" name="action" value="save" />
</div>
</form>

<form method="post">
	<div style="margin:50px 0;border-top:1px solid #F00;padding-top:10px;">
    <input style="font-size:12px !important;" name="reset" type="submit" value="还原默认设置" />
    <input type="hidden" name="action" value="reset" />
    </div>
</form>

</div>
<?php
}
add_action('admin_menu', 'mytheme_add_admin');
?>