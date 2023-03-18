<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="wrap couponwheel_settings couponwheel_dashboard">
	<h1>LottoPawa2 <?php _e('Global Settings','couponwheel'); ?></h1>

	<?php if ( get_option('couponwheel_devtools') ) { ?>
	<div class="card couponwheel_card">
		<h2><?php _e('Devtools','couponwheel'); ?></h2>
		<a href="<?php echo admin_url('admin.php?page=couponwheel_settings&couponwheel_devtools_upgrade') ?>"><button type="button" class="button">NA/button></a>
		<a href="<?php echo admin_url('admin.php?page=couponwheel_settings&couponwheel_devtools_delete_update_transients') ?>"><button type="button" class="button">NA</button></a>
	</div>
	<?php } ?>

	<form id="couponwheel_settings_form">
		<div class="card couponwheel_card">
			<h2><?php _e('General','couponwheel'); ?></h2>
			<p><?php echo $this->form_checkbox('couponwheel_enable_custom_win_probability',__('Enable custom win probability ratios','couponwheel'),get_option('couponwheel_enable_custom_win_probability')) ?></p>
			<p><?php echo $this->form_checkbox('couponwheel_popup_close_confirm',__('Confirm closing when user clicks away from popup','couponwheel'),get_option('couponwheel_popup_close_confirm')) ?></p>
			<p><?php echo $this->form_checkbox('couponwheel_autofill',__('Autofill form fields with customer data if he is logged in','couponwheel'),get_option('couponwheel_autofill')) ?></p>
			<?php if ($this->woo) { ?>
			<h2><?php _e('WooCommerce extras','couponwheel'); ?></h2>
			<p><?php echo $this->form_checkbox('couponwheel_hide_unique_coupons',__('Hide code','couponwheel'),get_option('couponwheel_hide_unique_coupons')) ?></p>
			<p><?php echo $this->form_checkbox('couponwheel_apply_coupon_automatically',__('Immediately apply on win','couponwheel'),get_option('couponwheel_apply_coupon_automatically')) ?></p>
			<p><?php echo $this->form_checkbox('couponwheel_delete_expired_coupons_automatically',__('Automatically delete wins','couponwheel'),get_option('couponwheel_delete_expired_coupons_automatically')) ?></p>
			<p><a id="delete_expired_coupons" href="#"><?php _e('Delete wins with expired urgency timer now','couponwheel'); ?> (<?php echo $expired_coupons ?>)</a>
			<progress style="display: none;" id="delete_expired_coupons_progress" style="width:8em;" value="0" max="<?php echo $expired_coupons ?>"></progress>
			</p><?php } ?>
			<h2><?php _e('Optimizations','couponwheel'); ?></h2>
			<p><?php echo $this->form_checkbox('couponwheel_skip_google_fonts',__('Skip loading Google fonts and inherit website font','couponwheel'),get_option('couponwheel_skip_google_fonts')) ?></p>
			<p><?php echo $this->form_checkbox('couponwheel_reduce_requests',__('Skip loading shadows','couponwheel'),get_option('couponwheel_reduce_requests')) ?></p>
			<h2><?php _e('Personal Data Erasure Requests','couponwheel'); ?></h2>
			<p><?php echo $this->form_checkbox('couponwheel_remove_personal_data_from_spin_log',__('Remove personal data from spin log','couponwheel'),get_option('couponwheel_remove_personal_data_from_spin_log')) ?></p>
			<p><i style="color: grey;"><?php _e('Data removal option should stay disabled if you use Anti-Cheat feature.','couponwheel'); ?></i></p>
		</div>
		<div class="card couponwheel_card">
			<h2><?php _e('Mailing','couponwheel'); ?></h2>
			<p><?php echo $this->form_text_input('couponwheel_from_email_name',__('Set custom <b>Name</b> from which emails are sent:','couponwheel'),get_option('couponwheel_from_email_name')) ?></p>
			<p><?php echo $this->form_text_input('couponwheel_from_email_address',__('Set custom <b>Email address</b> from which emails are sent:','couponwheel'),get_option('couponwheel_from_email_address')) ?></p>
			<script>
				jQuery(document).ready(function($){
					$('#couponwheel_from_email_address').attr('placeholder','<?php echo $this->default_from_email_address(); ?>');
					$('#couponwheel_from_email_name').attr('placeholder','<?php echo get_bloginfo('name'); ?>');
				});
			</script>
		</div>
		<div class="card couponwheel_card">
			<h2><?php _e('Extras','couponwheel'); ?></h2>
			<p><?php echo $this->form_checkbox('couponwheel_rtl',__('Enable right-to-left language styles','couponwheel'),get_option('couponwheel_rtl')) ?></p>
			<p><?php echo $this->form_checkbox('couponwheel_devtools',__('Enable Devtools (some special options)','couponwheel'),get_option('couponwheel_devtools')) ?></p>
		</div>
	<br>
	<button type="submit" class="button button-primary"><?php _e('Save'); ?></button>
	</form>
</div>
<script>
window.addEventListener('load',function(){
	
	var deleting_expired_coupons = false;
	
	function delete_expired_coupons()
	{
		deleting_expired_coupons = true;
		jQuery('#delete_expired_coupons_progress').show();
		jQuery.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'couponwheel_delete_expired_coupons',
				}
		}).done(function(json){
			response = jQuery.parseJSON(json);
			var max = jQuery('#delete_expired_coupons_progress').attr('max');
			var delete_progress = max - response.current_count;
			if (delete_progress < 0) delete_progress = 0;
			jQuery('#delete_expired_coupons_progress').attr('value',delete_progress);
			if (response.success !== true) { alert('<?php _e('Error deleting expired code','couponwheel'); ?>'); return; }
			if (response.current_count > 0) {
				setTimeout(function() { delete_expired_coupons(); },500);
			} else {
				alert('<?php _e('DONE deleting expired code!','couponwheel'); ?>');
				jQuery('#delete_expired_coupons_progress').hide();
			}
		});
	}
	
	jQuery('#delete_expired_coupons').click(function(event){
		if (deleting_expired_coupons) return;
		if (!confirm(jQuery(event.target).text() + '?')) return;
		delete_expired_coupons();
	});
	
	jQuery('#couponwheel_settings_form').on('submit',function(event){
		event.preventDefault();
		var data = {
			action: 'couponwheel_settings_save',
			form_data: jQuery('#couponwheel_settings_form').serialize(),
			_wpnonce: '<?php echo wp_create_nonce(); ?>'
		};
		jQuery.post(ajaxurl,data,function(json) {
			json = jQuery.parseJSON(json);
			alert(json.error_msg);
		});
	});
});
</script>