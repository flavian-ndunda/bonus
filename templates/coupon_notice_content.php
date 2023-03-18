<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<style>
#couponwheel_notice_content {
	<?php if ($wheel->coupon_notice_on_top) { ?>
	top: 0;
	bottom: auto;
	<?php } ?>
	background-color: <?php echo $wheel->notice_background_color?>;
	color: <?php echo $wheel->notice_text_color?>;
}
</style>
<div id="couponwheel_notice_content">
<span></span><a id="couponwheel_notice_close_btn"><?php echo $wheel->lang_close ?></a>
</div>

<div style="display:none">
	<div id="couponwheel_notice_escaper">
		<?php echo $notice; ?>
	</div>
</div>

<script>
jQuery('#couponwheel_notice_close_btn').click(function()
{
	jQuery('#couponwheel_notice_content').animate({ height: 'toggle', opacity: 'toggle' });
	window.couponwheel_notice.clear();
});

window.couponwheel_notice.start(jQuery('#couponwheel_notice_escaper').html(),
								<?php echo json_encode( $template_vars ) ?>,
								<?php echo esc_js($expire_timestamp); ?>,
								'<?php echo esc_js($wheel->lang_days); ?>');

</script>
