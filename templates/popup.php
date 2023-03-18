<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php

$current_user = wp_get_current_user();
$autofill = (bool) get_option('couponwheel_autofill');

?><div data-item="<?php echo $wheel->wheel_hash?>">
<style>
<?php if (!get_option('couponwheel_skip_google_fonts')) { ?>
#couponwheel<?php echo $wheel->wheel_hash?> {
	font-family: 'Roboto';
}
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_coupon_code {
	font-family: 'Roboto Mono', monospace;
}
<?php } ?>
<?php if ($wheel->slice_font_size != 100) { ?>
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label {
	font-size: <?php echo (1.2*$wheel->slice_font_size)/100 ?>em;
}
<?php } ?>
#couponwheel<?php echo $wheel->wheel_hash?> .input-text,
#couponwheel<?php echo $wheel->wheel_hash?> input[type=email],
#couponwheel<?php echo $wheel->wheel_hash?> input[type=text] {
    background-color: #f2f2f2;
    color: #43454b;
    outline: 0;
    border: 0;
    -webkit-appearance: none;
    font-weight: 400;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.125);
}
#couponwheel<?php echo $wheel->wheel_hash?> button:hover {
	background-color: #d5d5d5;
	border-color: #d5d5d5;
	color: #333333;
}
#couponwheel<?php echo $wheel->wheel_hash?> button {
	background-color: #eeeeee;
	border-color: #eeeeee;
	color: #333333;
}
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_spin_again_btn {
	display: block;
	margin-top: 1.5em;
	padding: .5em 0;
	cursor: pointer;
	text-align: center;
	text-decoration: underline;
}
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_heading_text {
	color: <?php echo $wheel->popup_heading_text_color ?>;
}
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_main_text,
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_form_error_text,
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_rules_text,
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_rules_checkbox_label,
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_coupon_code,
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_offers_text,
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_spin_again_btn
{
	color: <?php echo $wheel->popup_main_text_color ?>;
}
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_ajax_loader > div {
	background-color: <?php echo $wheel->popup_main_text_color ?>;
}
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_background {
	background-color: rgb(128,128,128);
	background: <?php echo $wheel->popup_background_css ?>
}
<?php if ( ! empty( $wheel->custom_background_img ) ) { ?>
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_background {
	background-size: cover;
	background-image: url('<?php echo $wheel->custom_background_img ?>');
}
<?php } ?>
<?php if (in_array($wheel->wheel_gfx,range(2,7))) { ?>
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label:nth-of-type(even) {
	color: inherit;
}
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label:nth-of-type(4),
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label:nth-of-type(8),
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label:nth-of-type(12) {
	color: white;
}
<?php } ?>
<?php if ($wheel->wheel_gfx == 8) { ?>
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label {
	color: #222;
}
<?php } ?>
<?php if ($wheel->wheel_gfx == 9) { ?>
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label {
	color: #222;
}
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label:nth-of-type(4n) {
	color: white;
}
<?php } ?>
<?php if ($wheel->wheel_gfx == 10) { ?>
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_slice_label {
	color: white;
	text-shadow: 1px 1px 2px rgba(0,0,0,.8);
}
<?php } ?>
<?php if (!empty($wheel->popup_header_image)) { ?>
@media screen and (min-width: 40em) {
	#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_heading_text {
		margin-top: 0;
	}
}
<?php } ?>
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_offers_progressbar div {
	width: <?php echo $wheel->offers_claimed_percentage?>%;
	background-color: <?php echo $wheel->offers_progressbar_color?>;
}

<?php if ( get_option('couponwheel_rtl') ) { ?>
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup { direction: ltr; }
#couponwheel<?php echo $wheel->wheel_hash?> .couponwheel_popup_form_container { direction: rtl; }
<?php } ?>

</style>

<div id="couponwheel<?php echo $wheel->wheel_hash?>">
	<div class="couponwheel_popup_shadow"></div>
	<div class="couponwheel_popup">
		<div class="couponwheel_popup_background">
			<?php if ($preview_mode) { ?><p style="text-align: center; font-weight: bold; background-color: rgba(0,0,0,.33)">PREVIEW MODE</p> <?php } ?>
			<div class="couponwheel_popup_form_container">
				<div class="couponwheel_form">
					<div class="couponwheel_popup_close_container"><div class="couponwheel_popup_close_btn">×</div></div>
					<?php if (!empty($wheel->popup_header_image)) { ?><img class="couponwheel_popup_header_image" src="<?php echo $wheel->popup_header_image?>"><?php } ?>
						<form class="couponwheel_form_stage1">
							<div class="couponwheel_popup_heading_text"><?php echo nl2br($wheel->popup_heading_text) ?></div>
							<div class="couponwheel_popup_main_text"><?php echo do_shortcode(nl2br($wheel->popup_main_text)) ?></div>
							<?php if($wheel->require_email) { ?><input value="<?php echo ( $autofill && $current_user->exists() ) ? $current_user->user_email : '' ; ?>" type="email" placeholder="<?php echo $wheel->lang_enter_your_email ?>" name="email" required><?php } ?>
							<?php if($wheel->require_first_name) { ?><input value="<?php echo ( $autofill && $current_user->exists() ) ? $current_user->user_firstname : '' ; ?>" type="text" placeholder="<?php echo $wheel->lang_enter_your_first_name ?>" name="first_name" required><?php } ?>
							<?php if($wheel->require_last_name) { ?><input value="<?php echo ( $autofill && $current_user->exists() ) ? $current_user->user_lastname : '' ; ?>" type="text" placeholder="<?php echo $wheel->lang_enter_your_last_name ?>" name="last_name" required><?php } ?>
							<?php if($wheel->require_phone_number) { ?><input value="" type="text" placeholder="<?php echo $wheel->lang_enter_phone_number ?>" name="phone_number" required><?php } ?>
							<?php
// Get the entered phone number
                              $entered_phone_number = $_POST['phone_number'];

// Check if the phone number exists in lottopawa_users table
                              $sql = "SELECT * FROM lottopawa_users WHERE phone_number = '$entered_phone_number'";
                               $result = mysqli_query($conn, $sql);
                               if(mysqli_num_rows($result) == 0) {
                                    echo "Invalid phone number!";
                                        } 

// Check if the phone number exists in wpgc_couponwheel_wheels table
                                $sql = "SELECT * FROM wpgc_couponwheel_wheels WHERE require_phone_number = '$entered_phone_number'";
                                     $result = mysqli_query($conn, $sql);
                                     if(mysqli_num_rows($result) == 0) {
                                     echo "Phone number not required!";
                                       }
                                          ?>
							<?php if($wheel->require_rules) { ?><div class="couponwheel_popup_checkbox_container"><input type="hidden" name="rules_checked" value="0"><input type="checkbox" id="rules_checkbox<?php echo $wheel->wheel_hash?>" name="rules_checked" value="1" required><label class="couponwheel_popup_rules_checkbox_label" for="rules_checkbox<?php echo $wheel->wheel_hash?>"><?php echo $wheel->lang_i_agree ?></label></div><?php } ?>
							<?php if($wheel->require_recaptcha) { ?>
							<div id="couponwheel<?php echo $wheel->wheel_hash?>_recaptcha" class="couponwheel_recaptcha"></div>
							<?php } ?>
							<div class="couponwheel_ajax_loader"><div></div><div></div><div></div></div>
							<div class="couponwheel_popup_form_error_text"></div>
							<button class="couponwheel_stage1_submit_btn" type="submit" disabled><?php echo $wheel->lang_spin_button ?></button>
							<?php if ($wheel->show_offers_claimed) { ?>
							<div class="couponwheel_offers_progressbar">
								<div></div>
							</div>
							<div class="couponwheel_offers_text"><?php echo $wheel->offers_claimed_text ?></div>
							<?php } ?>
							<div class="couponwheel_popup_rules_text">
								<?php echo do_shortcode(nl2br($wheel->popup_rules_text)) ?>
							</div>
							<input type="hidden" name="wheel_hash" value="<?php echo $wheel->wheel_hash ?>">
						</form>
					<div class="couponwheel_form_stage2 couponwheel_hidden">
						<div class="couponwheel_popup_heading_text"></div>
						<div class="couponwheel_popup_main_text"></div>
						<button class="couponwheel_stage2_continue_btn"><?php echo $wheel->lang_continue_button ?></button>
						<?php if($wheel->show_spin_again) { ?><a class="couponwheel_spin_again_btn"><?php echo $wheel->lang_spin_again ?></a><?php } ?>
					</div>
				</div>
			</div>
			<div class="couponwheel_popup_wheel_container">
				<div class="couponwheel_wheel_container"><!--
						---><?php if (!get_option('couponwheel_reduce_requests')) { ?><div class="couponwheel_wheel_crop" style="overflow: visible; position: absolute; z-index: 1000; display: inline-block;"><img class="couponwheel_wheel_img" style="width: 101.15%; max-width: none;" src="<?php echo $plugin_dir_url ?>assets/wheel_shadow.png"></div><?php } ?><!--
						---><div class="couponwheel_wheel_crop"><!--
						---><div class="couponwheel_wheel"><!--
							--><img class="couponwheel_wheel_img" src="<?php echo $plugin_dir_url ?>assets/wheel<?php echo $wheel->wheel_gfx?>.png"><!--
							--><div class="couponwheel_slice_labels"><!--
							--><?php foreach(range(1,12) as $i) { ?><!--
							--><div class="couponwheel_slice_label"><?php echo $wheel->{"slice$i"."_label"} ?></div><!--
							--><?php } ?><!--
							--></div><!--
						---></div><!--
					---></div><!--
					---><img src="<?php echo $plugin_dir_url ?>assets/marker.png" class="couponwheel_marker">
				</div>
			</div>
		</div>
	</div>
</div>

<?php if ($wheel->manual_open) { ?>
<div class="couponwheel_manual_open couponwheel_manual_open_position_<?php echo $wheel->manual_open_position ?>">
	<?php $gift_icon = (empty($wheel->custom_gift_icon)) ? $plugin_dir_url . 'assets/gift.png' : $wheel->custom_gift_icon; ?>
	<a style="cursor: pointer" onclick="couponwheel<?php echo $wheel->wheel_hash?>.show_popup(0);"><img class="<?php echo apply_filters( 'couponwheel_gift_icon_class', 'couponwheel_effects_animated couponwheel_effects_tada' ) ?>" src="<?php echo $gift_icon ?>"></a>
</div>
<?php } ?>

<script data-cfasync="false">
<?php echo sprintf("// %s \n",get_option('couponwheel_version')) ?>
var couponwheel<?php echo $wheel->wheel_hash?> = new couponwheel({
				wheel_hash:'<?php echo $wheel->wheel_hash?>',
				wheel_dom:'#couponwheel<?php echo $wheel->wheel_hash?>',
				timed_trigger: <?php echo $wheel->timed_trigger ? 'true' : 'false' ?>,
				exit_trigger: <?php echo $wheel->exit_trigger ? 'true' : 'false' ?>,
				show_popup_after: <?php echo $wheel->show_popup_after ?>,
				preview_key: <?php echo ($preview_mode) ? "'$wheel->popup_preview_key'" : 'false' ?>,
				recaptcha_sitekey: '<?php echo get_option('couponwheel_recaptcha_site_key')?>',
				require_recaptcha: <?php echo $wheel->require_recaptcha ?>,
				prevent_triggers_on_mobile: <?php echo $wheel->prevent_triggers_on_mobile ? 'true' : 'false' ?>,
				kiosk_mode: <?php echo $wheel->kiosk_mode ? 'true' : 'false' ?>,
				confirm_close_text: '<?php echo ( bool ) get_option('couponwheel_popup_close_confirm') ? __('Close') : ''; ?>'
});
<?php if ($preview_mode) { ?>
couponwheel<?php echo $wheel->wheel_hash?>.show_popup();
<?php } ?>
</script>
</div>