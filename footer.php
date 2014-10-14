</div>
<div id="totop" class="totop"><i class="iconfont">&#404;</i>回顶部</div>
<script type="text/javascript">
	$(window).scroll(function () {
        var dt = $(document).scrollTop();
        var wt = $(window).height();
        if (dt <= 0) {
            $("#totop").hide();
            return;
        }
        $("#totop").show();
        if ($.browser.msie && $.browser.version == 6.0) {//IE6返回顶部
            $("#totop").css("top", wt + dt - 110 + "px");
        }
    });
    $("#totop").click(function () { $("html,body").animate({ scrollTop: 0 }, 200) });
</script>
</div>
<div id="footer">
	&copy; <?php echo get_option('tang_years'); ?> <?php bloginfo('name'); ?>.
	Powered by WordPress.
    Theme by <a href="http://tangjie.me/tangstyle" title="TangStyle" target="_blank">TangStyle</a>.
    <?php echo stripslashes(get_option('tang_icp')); ?>
    <?php echo stripslashes(get_option('tang_tongji')); ?>
	由<a href = "http://www.xiaoz.me/" title = "wordpress教程" target = "_blank">小z博客</a>优化.
</div>
<?php wp_footer(); ?>
</body>
</html>
