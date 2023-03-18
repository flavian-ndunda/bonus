<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php if (!function_exists('mb_strtolower')) { ?>
<div class="notice notice-error" style="margin-top: 5em; font-size: 1.1em; padding: 1em"><?php _e('This plugin needs Multibyte String PHP Extension. Enable extension in your hosting settings to continue.','couponwheel'); ?></div>
<?php exit(); } ?>

<div class="wrap couponwheel_dashboard couponwheel_settings">
	<h1><?php _e('LottoPawa2 Dashboard','couponwheel'); ?></h1>
	
	<p><?php _e('Version','couponwheel'); ?> <?php echo $this->get_version(); ?> ( <a target="_new" href="https://awuoro.co.ke"><?php _e('Support','couponwheel'); ?></a> )</p>
	
	<a href="?page=couponwheel_dashboard&action=add_new_wheel&_wpnonce=<?php echo wp_create_nonce('add_new_wheel') ?>" class="button button-primary couponwheel_js_confirm" data-msg="<?php _e('Add New Wheel','couponwheel'); ?>?"><?php _e('Add New Wheel','couponwheel'); ?></a>
	
	<?php foreach($wheels as $wheel) { ?>
		<div class="card couponwheel_card">
			<h2><a class="button" style="vertical-align: middle; margin-right: .5em;" href="?page=couponwheel_dashboard&action=configure_wheel&wheel_hash=<?php echo $wheel->wheel_hash?>"><?php _e('Configure','couponwheel'); ?></a> <?php echo esc_html($wheel->wheel_name)?></h2>
				<div class="couponwheel_overview_counters">
					<div>
						<div><?php _e('STATUS','couponwheel'); ?></div>
						<div><?php echo $wheel->is_live ? __('LIVE','couponwheel') : __('PAUSED','couponwheel'); ?></div>
					</div><!--
				---><div>
						<div><?php _e('POPUP IMPRESSIONS','couponwheel'); ?></div>
						<div><?php echo $wheel->popup_impressions ?></div>
					</div><!--
				---><div>
						<div><?php _e('WHEEL SPINS','couponwheel'); ?></div>
						<div><?php echo $wheel_totals[$wheel->id]->wheel_spins ?></div>
					</div><!--
				---><div>
						<div><?php _e('EMAILS COLLECTED','couponwheel'); ?></div>
						<div><?php echo $wheel_totals[$wheel->id]->emails_collected ?></div>
					</div><!--
			---></div>
		</div>
	<?php } ?>
	
</div>
<script>
	jQuery('.couponwheel_js_confirm').click(function(event) {
		if (!confirm(jQuery(this).data('msg'))) event.preventDefault();
	});
</script>