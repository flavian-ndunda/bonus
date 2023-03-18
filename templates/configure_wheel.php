<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php add_thickbox(); ?>
<script>
jQuery(function() {
	jQuery('#couponwheel_configre_wheel_tabs').tabs({
		beforeActivate: function (event, ui) { history.pushState(null,null, ui.newPanel.selector); }
	});
	jQuery('.couponwheel_configre_wheel_tabs_sections').tabs();
});
</script>

<div class="wrap couponwheel_dashboard">
	<a href="?page=couponwheel_dashboard"><?php _e('Go to Dashboard','couponwheel'); ?></a>
		
	<h1>Lottopawa <a id="couponwheel_save_all_and_preview" class="page-title-action"><?php _e('Save all & Preview Popup','couponwheel'); ?></a></h1>
	
	<h2><?php echo esc_html( $wheel->wheel_name ) ?></h2>
	
	<div id="couponwheel_configre_wheel_tabs">
		<ul class="couponwheel_tabs_buttons">
			<li><a href="#couponwheel_configre_wheel_tabs_basic"><?php _e('Basic','couponwheel'); ?></a></li>
			<li><a href="#couponwheel_configre_wheel_tabs_slices"><?php _e('Slices','couponwheel'); ?></a></li>
			<li><a href="#couponwheel_configre_wheel_tabs_data_collection"><?php _e('Data Collection','couponwheel'); ?></a></li>
			<li><a href="#couponwheel_configre_wheel_tabs_customize_popup"><?php _e('Popup Style','couponwheel'); ?></a></li>
			<li><a href="#couponwheel_configre_wheel_tabs_edit_popup_strings"><?php _e('Popup Text','couponwheel'); ?></a></li>
			<li><a href="#couponwheel_configre_wheel_tabs_mailing"><?php _e('Mailing','couponwheel'); ?></a></li>
			<li><a href="#couponwheel_configre_wheel_tabs_display_options"><?php _e('Display Options','couponwheel'); ?></a></li>
			<li><a href="#couponwheel_configre_wheel_tabs_ACE"><?php _e('Anti-Cheat Engine','couponwheel'); ?></a></li>
			<li><a href="#couponwheel_configre_wheel_tabs_maintenace"><?php _e('Maintenace & Extras','couponwheel'); ?></a></li>
		</ul>
		<form id="couponwheel_configure_wheel_form">
			<input type="hidden" name="wheel_hash" value="<?php echo $wheel->wheel_hash ?>">
			<div id="couponwheel_configre_wheel_tabs_basic">
				<div class="card couponwheel_card">
					<h2><?php _e('Basic','couponwheel'); ?></h2>
					<p><?php echo $this->form_text_input('wheel_name',__('Wheel name','couponwheel'),$wheel->wheel_name) ?></p>
					<p style="font-size: 1.2em"><?php echo $this->form_checkbox('is_live','<b>'.__('Start showing this wheel to website visitors','couponwheel').'</b>',$wheel->is_live) ?></p>
				</div>
			</div>
			<div id="couponwheel_configre_wheel_tabs_slices" style="display: none">
				<div class="card couponwheel_card" style="padding: 1.5em; max-width: none;">
					<h2><?php _e('Slices','couponwheel'); ?></h2>

					<div class="couponwheel_slice_table_overflow_fix">
					<table class="couponwheel_slice_table">
						<thead>
							<tr>
								<th></th>
								<th><?php _e('Slice label','couponwheel'); ?></th>
								<th><?php _e('Winning Code/URL Code','couponwheel'); ?></th>
								<th><?php _e('Unlimited<br>Wins','couponwheel'); ?></th>
							<th><?php _e('Available<br>Wins','couponwheel'); ?></th>
                                  <th><?php _e('Win<br>Probability','couponwheel'); ?></th>
							
								<th><?php _e('Times<br>Won','couponwheel'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach(range(1,12) as $i) { ?>
							<tr>
								<td><?php echo $i ?></td>
								<td><input name="slice<?php echo $i?>_label" placeholder="<?php _e('Enter label','couponwheel'); ?>" type="text" value="<?php echo esc_html($wheel->{'slice'.$i.'_label'}) ?>"></td>
								<td><input name="slice<?php echo $i?>_coupon_code" id="couponwheel_coupon_code_autocomplete<?php echo $i?>" class="couponwheel_coupon_code_autocomplete" type="text" value="<?php echo esc_html($wheel->{'slice'.$i.'_coupon_code'}) ?>"></td>
								<?php if ($this->woo) { ?>
								<td><input name="slice<?php echo $i?>_unique_gen" type="hidden" value="0"><!--
								 --><input name="slice<?php echo $i?>_unique_gen" type="checkbox" data-id="<?php echo $i?>" class="couponwheel_unique_gen" value="1" <?php echo ($wheel->{'slice'.$i.'_unique_gen'} == true) ? 'checked' : ''; ?>></td>
								<?php } ?>
								<td><input name="slice<?php echo $i?>_infinite" type="hidden" value="0"><!--
								 --><input name="slice<?php echo $i?>_infinite" class="infinite_wins_checkbox" type="checkbox" value="1" <?php echo ($wheel->{'slice'.$i.'_infinite'} == true) ? 'checked' : ''; ?>></td>
								<td><span class="couponwheel_qty_unlimited" id="slice<?php echo $i?>_qty_unlimited"><?php _e('UNLIMITED','couponwheel'); ?></span><input name="slice<?php echo $i?>_qty" type="number" value="<?php echo $wheel->{'slice'.$i.'_qty'}?>" style="max-width: 5em" min="0"></td>
								<td>
                              
									<select class="win_multiplier" style="width: 8em;" data-for="slice<?php echo $i?>_win_multiplier">
										<option value="1" <?php echo ($wheel->{"slice$i".'_win_multiplier'} == 1) ? 'selected' : ''?>><?php _e('Prize 1','couponwheel'); ?></option>
										<option value="2" <?php echo ($wheel->{"slice$i".'_win_multiplier'} == 2) ? 'selected' : ''?>><?php _e('Prize 2','couponwheel'); ?></option>
										<option value="3" <?php echo ($wheel->{"slice$i".'_win_multiplier'} == 3) ? 'selected' : ''?>><?php _e('Prize 3','couponwheel'); ?></option>
										<option value="4" <?php echo ($wheel->{"slice$i".'_win_multiplier'} == 4) ? 'selected' : ''?>><?php _e('Prize 4','couponwheel'); ?></option>
										<option value="5" <?php echo ($wheel->{"slice$i".'_win_multiplier'} == 5) ? 'selected' : ''?>><?php _e('Prize 5','couponwheel'); ?></option>
									    <option value="85" <?php echo ($wheel->{"slice$i".'_win_multiplier'} == 85) ? 'selected' : ''?>><?php _e('No Prize','couponwheel'); ?></option>
										
										<?php if ( ! in_array( $wheel->{"slice$i".'_win_multiplier'}, array( 1,2,3,4,5,85  ) ) ) { ?>
										<option value="<?php echo $wheel->{"slice$i".'_win_multiplier'} ?>" selected><?php echo $wheel->{"slice$i".'_win_multiplier'} ?></option>
										<?php } ?>
										
									</select>
									<input class="win_multiplier" style="display: none; background-color: azure; font-weight: bold" name="slice<?php echo $i?>_win_multiplier" type="number" min="1" max="1000000" value="<?php echo $wheel->{"slice$i".'_win_multiplier'} ?>">
									<div class="probability_calc" style="border-bottom: 1px solid grey; display: none;">= <span data-win_multiplier="slice<?php echo $i?>_win_multiplier" class="probability_percent"></span>%</div>
								</td>
								<td class="couponwheel_times_won"><?php echo $this->get_slice_totals($wheel->id,$i) ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					</div>
				</div>
				<?php if ( get_option('couponwheel_enable_custom_win_probability') ) { ?>
				<div style="width: 85em">
					<p style="background-color: azure; font-size:1.2em"><?php _e('Custom win probabilty ratios are <strong>enabled</strong>, use numbers to fine tune win ratio, higher number = higher chance to win','couponwheel'); ?></p>
				</div>
				<?php } ?>
				<?php if ($wheel->infinite_has_more_chance) { ?>
				<div class="card couponwheel_card">
					<h2><?php _e('Extra options','couponwheel'); ?></h2>
					<p><?php echo $this->form_checkbox('infinite_has_more_chance',__('Infinite slices have more chance to win','couponwheel'),$wheel->infinite_has_more_chance) ?></p>
				</div>
				<?php } ?>
				<?php if ($this->woo) { ?>
				
				<?php } ?>
			</div>
			<div id="couponwheel_configre_wheel_tabs_data_collection" style="display: none">
				<div class="couponwheel_configre_wheel_tabs_sections">
					<ul>
						<li><a href="#data_collection_section_1" class="button"><?php echo _e('Data Collection','couponwheel'); ?></a></li>
						<li><a href="#data_collection_section_2" class="button"><?php echo _e('Connect With Services','couponwheel'); ?></a></li>
					</ul>
					<div id="data_collection_section_1">
						<div class="card couponwheel_card">
							<h2><?php _e('Data Collection','couponwheel'); ?></h2>
							<p><?php echo $this->form_checkbox('require_email',__('Collect E-mail','couponwheel'),$wheel->require_email) ?></p>
							<p><?php echo $this->form_checkbox('require_first_name',__('Collect First name','couponwheel'),$wheel->require_first_name) ?></p>
							<p><?php echo $this->form_checkbox('require_last_name',__('Collect Last name','couponwheel'),$wheel->require_last_name) ?></p>
							<p><?php echo $this->form_checkbox('require_rules',__('Require user to accept rules','couponwheel'),$wheel->require_rules) ?></p>
							<hr>
							<p><?php echo $this->form_checkbox('require_phone_number',__('Collect Phone number','couponwheel'),$wheel->require_phone_number) ?></p>
							<p><?php echo $this->form_number_input('phone_number_length',__('Phone number length','couponwheel'),0,30,$wheel->phone_number_length,__('(0 disables length checking)','couponwheel')); ?></p>
						</div>
						<div class="card couponwheel_card">
							<h2><?php _e('Export data','couponwheel'); ?></h2>
							<p>
								<a class="button" href="<?php echo admin_url('admin-ajax.php')?>?action=couponwheel_html_log&display=1&wheel_hash=<?php echo $wheel_hash ?>"><?php _e('Display newest 200 entries','couponwheel'); ?></a>
								<a class="button" href="<?php echo admin_url('admin-ajax.php')?>?action=couponwheel_html_log&download=1&wheel_hash=<?php echo $wheel_hash ?>"><?php _e('Download CSV log','couponwheel'); ?></a>
							</p>
						</div>
					</div>
					<div id="data_collection_section_2">
						<div class="card couponwheel_card">
							<h2><?php _e('Connect with MailChimp','couponwheel'); ?> <a target="_new" href="https://mailchimp.com" style="text-decoration:none;"><span class="dashicons dashicons-external"></span></a></h2>
							<p><?php echo $this->form_checkbox('mailchimp_enabled',__('Enabled','couponwheel'),$wheel->mailchimp_enabled) ?></p>
							<p><?php echo $this->form_checkbox('mailchimp_double_optin',__('Double Opt-In','couponwheel'),$wheel->mailchimp_double_optin) ?></p>
							<p><?php echo $this->form_text_input('mailchimp_api_key',__('API Key','couponwheel'),$wheel->mailchimp_api_key) ?></p>
							<p>
								<label class="input_spacing"></label>
								<select id="couponwheel_mailchimp_lists" style="display: none">
								</select>
								<a id="couponwheel_get_mailchimp_lists_btn" class="button"><?php _e('Fetch mailing lists','couponwheel'); ?></a>
							</p>
							<div id="couponwheel_mailchimp_list_toggle">
								<p><?php echo $this->form_text_input('mailchimp_list_name',__('List name','couponwheel'),$wheel->mailchimp_list_name) ?></p>
								<p><?php echo $this->form_text_input('mailchimp_list_id',__('List ID','couponwheel'),$wheel->mailchimp_list_id) ?></p>
							</div>
						</div>
						<div class="card couponwheel_card">
							<h2><?php _e('Connect with Webhooks','couponwheel'); ?></h2>
							<p style="max-width:45em;"><?php _e('Webhooks are event notifications sent to URLs of your choice. They can be used to integrate with third-party services which support them (for example <a href="https://zapier.com" target="_new">Zapier</a>). If enabled, LottoPawa will send collected form data in JSON format to specified URL.','couponwheel'); ?></p>
							<p><?php echo $this->form_checkbox('webhooks_enabled',__('Enabled','couponwheel'),$wheel->webhooks_enabled) ?></p>
							<p><?php echo $this->form_text_input('webhooks_url',__('URL','couponwheel'),$wheel->webhooks_url) ?></p>
						</div>
					</div>
				</div>
			</div>
			<div id="couponwheel_configre_wheel_tabs_display_options" style="display: none">
				<div class="couponwheel_configre_wheel_tabs_sections">
					<ul>
						<li><a href="#display_options_section_1" class="button"><?php echo _e('Display Options','couponwheel'); ?></a></li>
						<li><a href="#display_options_section_2" class="button"><?php echo _e('Page Filtering','couponwheel'); ?></a></li>
						<li><a href="#display_options_section_3" class="button"><?php echo _e('Urgency Timer','couponwheel'); ?></a></li>
					</ul>
					<div id="display_options_section_1">
						<div class="card couponwheel_card">
							<h2><?php _e('Display Options','couponwheel'); ?></h2>
							<p>
							<label class="input_spacing" for="user_filter">Show popup to</label>
							<select name="user_filter" style="max-width: 12em">
								<option <?php if ( $wheel->user_filter == 0 ) echo 'selected'; ?> value="0">Anyone</option>
								<option <?php if ( $wheel->user_filter == 1 ) echo 'selected'; ?> value="1">Guests</option>
								<option <?php if ( $wheel->user_filter == 2 ) echo 'selected'; ?> value="2">Registered users</option>
							</select>
							</p>
							<p><?php echo $this->form_checkbox('manual_open',__('Floating gift button','couponwheel'),$wheel->manual_open) ?></p>
							<p>
							<label class="input_spacing" for="user_filter">Floating gift button postition</label>
							<select name="manual_open_position" style="max-width: 12em">
								<option <?php if ( $wheel->manual_open_position == 1 ) echo 'selected'; ?> value="1"><?php echo _e('Left Top','couponwheel'); ?></option>
								<option <?php if ( $wheel->manual_open_position == 0 ) echo 'selected'; ?> value="0"><?php echo _e('Left Middle','couponwheel'); ?></option>
								<option <?php if ( $wheel->manual_open_position == 2 ) echo 'selected'; ?> value="2"><?php echo _e('Left Bottom','couponwheel'); ?></option>
								<option <?php if ( $wheel->manual_open_position == 3 ) echo 'selected'; ?> value="3"><?php echo _e('Right Top','couponwheel'); ?></option>
								<option <?php if ( $wheel->manual_open_position == 4 ) echo 'selected'; ?> value="4"><?php echo _e('Right Middle','couponwheel'); ?></option>
								<option <?php if ( $wheel->manual_open_position == 5 ) echo 'selected'; ?> value="5"><?php echo _e('Right Bottom','couponwheel'); ?></option>
							</select>
							</p>
							<p><?php echo $this->form_checkbox('timed_trigger',__('Timed Trigger','couponwheel'),$wheel->timed_trigger) ?></p>
							<p><?php echo $this->form_checkbox('exit_trigger',__('Exit Intent Trigger','couponwheel'),$wheel->exit_trigger) ?></p>
							<p><?php echo $this->form_checkbox('prevent_triggers_on_mobile',__('Prevent Timed and Exit Intent triggers <b>on mobile devices</b> (for better UX)','couponwheel'),$wheel->prevent_triggers_on_mobile) ?></p>
							<p><?php echo $this->form_number_input('show_popup_after',__('Show first popup after','couponwheel'),0,999,$wheel->show_popup_after,__('seconds','couponwheel')) ?></p>
							<p><?php echo $this->form_number_input('show_popup_every',__('Show popup every','couponwheel'),0,365,$wheel->show_popup_every,'days') ?></p>
							<p><?php echo $this->form_checkbox('show_spin_again',__('Add <b>Spin again</b> button at the end','couponwheel'),$wheel->show_spin_again) ?></p>
						</div>
					</div>
					<div id="display_options_section_2">
						<div class="card couponwheel_card">
							<h2><?php _e('Page Filtering','couponwheel'); ?></h2>
							<p><?php _e('Popups are shown everywhere by default. If you want to show only on specific pages check them below or use search box to add new posts & pages.','couponwheel'); ?></p>
							<?php $page_filter = explode(',',$wheel->page_filter); ?>
							<div class="page_filter_checkbox_container"><input <?php if(in_array('-10',$page_filter)) echo 'checked' ?> name="page_filter[]" value="-10" type="checkbox" id="page_filter_checkbox_posts"> <label for="page_filter_checkbox_posts"> <strong><?php _e('All single posts (blog)','couponwheel'); ?></strong></label></div>
							<?php if ($this->woo) { ?>
							<div class="page_filter_checkbox_container"><input <?php if(in_array('-20',$page_filter)) echo 'checked' ?> name="page_filter[]" value="-20" type="checkbox" id="page_filter_checkbox_products"> <label for="page_filter_checkbox_products"> <strong><?php _e('All single products ','couponwheel'); ?></strong></label></div>
							<div class="page_filter_checkbox_container"><input <?php if(in_array('-30',$page_filter)) echo 'checked' ?> name="page_filter[]" value="-30" type="checkbox" id="page_filter_checkbox_order_received"> <label for="page_filter_checkbox_order_received"> <strong><?php _e('Order received / Thank you page ','couponwheel'); ?></strong></label></div>
							<?php } ?>
							<p style="font-weight: bold"><?php _e('Custom posts','couponwheel'); ?></p>
							<div class="couponwheel_page_filter_cpt">
								<?php foreach($page_filter as $post_id) { ?>
									<?php if (($post_id) <= 0) continue; ?>
									<div class="page_filter_checkbox_container"><input checked name="page_filter[]" value="<?php echo $post_id; ?>" type="checkbox" id="page_filter_checkbox_<?php echo $post_id; ?>"> <label for="page_filter_checkbox_<?php echo $post_id?>"><?php echo mb_strtoupper(get_post_type($post_id))?>: <?php echo esc_html(get_the_title($post_id)); ?> (#<?php echo $post_id?>)</label></div>
								<?php } ?>
							</div>
							<input type="text" class="couponwheel_page_filter_cpt_search" placeholder="<?php _e('Search by title','couponwheel'); ?>">
							<div class="couponwheel_page_filter_cpt_search_results"></div>
							<br><br>
							<input type="hidden" name="page_filter[]" value="0">
						</div>
					</div>
					<div id="display_options_section_3">
						<div class="card couponwheel_card">
							<h2><?php _e('Reminder and urgency timer','couponwheel'); ?></h2>
							<p><?php echo $this->form_checkbox('show_coupon_notice',__('Show reminder after win','couponwheel'),$wheel->show_coupon_notice) ?></p>
							<p><?php echo $this->form_checkbox('coupon_notice_on_top',__('Reminder on top of page','couponwheel'),$wheel->coupon_notice_on_top) ?></p>
							<p><?php echo $this->form_color_picker('notice_text_color',__('Reminder text color','couponwheel'),$wheel->notice_text_color) ?></p>
							<p><?php echo $this->form_color_picker('notice_background_color',__('Reminder background color','couponwheel'),$wheel->notice_background_color) ?></p>
							<p><?php echo $this->form_number_input('coupon_urgency_timer',__('Set urgency timer','couponwheel'),1,99999,$wheel->coupon_urgency_timer,__('minutes','couponwheel')) ?></p>
						</div>
					</div>
				</div>
			</div>
			<div id="couponwheel_configre_wheel_tabs_ACE" style="display: none">
				<div class="card couponwheel_card">
					<h2><?php _e('Anti-Cheat Engine','couponwheel'); ?></h2>
					<p><?php echo $this->form_checkbox('ace_email_limit_check',__('Limit spins by checking E-mail','couponwheel'),$wheel->ace_email_limit_check) ?></p>
					<p><?php echo $this->form_checkbox('ace_cookie_limit_check',__('Limit spins by checking Cookies','couponwheel'),$wheel->ace_cookie_limit_check) ?></p>
					<p><?php echo $this->form_checkbox('ace_ip_limit_check',__('Limit spins by checking IP','couponwheel'),$wheel->ace_ip_limit_check) ?></p>
					<p><?php echo $this->form_checkbox('ace_phone_number_check',__('Limit spins by checking Phone number','couponwheel'),$wheel->ace_phone_number_check) ?></p>
					<hr>
					<p><?php echo $this->form_number_input('max_spins_per_user',__('Max spins per user','couponwheel'),1,999999,$wheel->max_spins_per_user) ?></p>
					<p><?php echo $this->form_number_input('reset_counter_days',__('Reset spin counter after','couponwheel'),0,365,$wheel->reset_counter_days,'days (set 0 to never reset)') ?></p>
					<!-- MX Check is now on by default
					<p><?php echo $this->form_checkbox('ace_mx_check',__('Validate E-mail domains','couponwheel'),$wheel->ace_mx_check) ?></p>
					-->
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('ReCAPTCHA Settings','couponwheel'); ?></h2>
					<p><?php echo $this->form_checkbox('require_recaptcha',__('Enable ReCAPTCHA','couponwheel'),$wheel->require_recaptcha) ?></p>
					<p><?php echo $this->form_text_input('couponwheel_recaptcha_site_key',__('Site Key','couponwheel'),get_option('couponwheel_recaptcha_site_key')) ?></p>
					<p><?php echo $this->form_text_input('couponwheel_recaptcha_secret',__('Secret','couponwheel'),get_option('couponwheel_recaptcha_secret')) ?></p>
				</div>
			</div>
			<div id="couponwheel_configre_wheel_tabs_maintenace" style="display: none">
				<div class="card couponwheel_card">
					<h2><?php _e('Maintenace','couponwheel'); ?></h2>
					<p class="couponwheel_maintenace_info">
						<span><?php _e('Wheel ID','couponwheel'); ?>: <?php echo $wheel->id?></span>
						<span><?php _e('Hash','couponwheel'); ?>: <?php echo $wheel->wheel_hash?></span>
						<span><?php _e('Popup seen key','couponwheel'); ?>: <?php echo $wheel->seen_key?></span>
					</p>
					<p>
						<a href="?page=couponwheel_dashboard&action=clone_wheel&wheel_hash=<?php echo $wheel->wheel_hash?>&_wpnonce=<?php echo wp_create_nonce('clone_wheel') ?>" class="button couponwheel_js_confirm" data-msg="<?php _e('Clone','couponwheel'); ?>?"><?php _e('Clone wheel','couponwheel'); ?></a>
						<a href="?page=couponwheel_dashboard&action=reset_wheel&wheel_hash=<?php echo $wheel->wheel_hash?>&_wpnonce=<?php echo wp_create_nonce('reset_wheel') ?>" class="button couponwheel_js_prompt" data-msg="<?php _e('Really reset this Wheel? Logs and counters will be reset to 0. Type YES below to confirm:','couponwheel'); ?>" data-check-msg="YES"><?php _e('Reset all wheel data','couponwheel'); ?></a>
						<a href="?page=couponwheel_dashboard&action=delete_wheel&wheel_hash=<?php echo $wheel->wheel_hash?>&_wpnonce=<?php echo wp_create_nonce('delete_wheel') ?>" class="button couponwheel_js_prompt" data-msg="<?php _e('Really delete this Wheel? Type YES below to confirm:','couponwheel'); ?>" data-check-msg="YES"><?php _e('Delete wheel','couponwheel'); ?></a>
						<a href="?page=couponwheel_dashboard&action=reset_seen_key&wheel_hash=<?php echo $wheel->wheel_hash?>&_wpnonce=<?php echo wp_create_nonce('reset_seen_key') ?>" class="button"><?php _e('Reset popup seen key','couponwheel'); ?></a>
					</p>
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('URL redirection','couponwheel'); ?></h2>
					<p><?php echo $this->form_text_input('on_win_url',__('Redirect to URL after win','couponwheel'),$wheel->on_win_url) ?></p>
					<p><?php echo $this->form_text_input('on_lose_url',__('Redirect to URL after lose','couponwheel'),$wheel->on_lose_url) ?></p>
					<p><?php echo $this->form_checkbox('on_win_url_target_blank',__('Open in new window','couponwheel'),$wheel->on_win_url_target_blank) ?></p>
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('Shortcode','couponwheel'); ?></h2>
					<p><?php _e('If you want to embed this Wheel inside page you can use shortcode below:','couponwheel'); ?></p>
					<code>[couponwheel_embed wheel_hash="<?php echo $wheel->wheel_hash ?>"]</code>
					<p><?php _e('Note the wheel popups will be disabled on page which contains this shortcode.','couponwheel'); ?></p>	
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('Triggering Wheel Popup with Javascript','couponwheel'); ?></h2>
					<p><?php _e('If you want to open this Wheel manually you can use function below:','couponwheel'); ?></p>
					<code>couponwheel_manual_trigger('<?php echo $wheel->wheel_hash ?>');</code>
					<p><?php _e('Page and scripts must be <strong>fully</strong> loaded for function to work.','couponwheel'); ?></p>
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('Custom Javascript','couponwheel'); ?></h2>
					<p><?php echo $this->form_textarea_input('custom_on_show_popup',__('Code to run when popup is triggered','couponwheel'),$wheel->custom_on_show_popup) ?></p>
				</div>
				<?php if ( get_option('couponwheel_devtools') ) { ?>
				<div class="card couponwheel_card">
					<h2><?php _e('Site Language Filter','couponwheel'); ?></h2>
					<p><?php _e('','couponwheel'); ?></p>
					<p><?php echo $this->form_text_input('locale_filter',__('Load popup if site language matches','couponwheel'),$wheel->locale_filter) ?></p>
					<p><?php _e('Example: en,de,sl','couponwheel')?></p>
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('Kiosk mode','couponwheel'); ?></h2>
					<p><?php echo $this->form_checkbox('kiosk_mode',__('Enable kiosk mode','couponwheel'),$wheel->kiosk_mode) ?></p>
					<i><?php _e('Kiosk mode will prevent closing of wheel popup. This option is used in special circumstances like events and terminals and should stay disabled for default website usage.','couponwheel'); ?></i>
				</div>
				<?php } ?>
			</div>
			<div id="couponwheel_configre_wheel_tabs_customize_popup" style="display: none">
				<div class="card couponwheel_card">
					<h2><?php _e('Customize Wheel','couponwheel'); ?></h2>
					<p><?php _e('Select Wheel graphic','couponwheel'); ?></p>
					<div class="couponwheel_wheel_customizer">
						<?php foreach(range(1,10) as $i) { ?>
						<label for="wheel_gfx<?php echo $i ?>">
							<img src="<?php echo plugin_dir_url(__FILE__) ?>../assets/wheel<?php echo $i?>.png">
							<input id="wheel_gfx<?php echo $i ?>" type="radio" name="wheel_gfx" value="<?php echo $i ?>" <?php echo ($wheel->wheel_gfx == $i) ? 'checked' : ''?>> <?php echo $i?>
						</label>
						<?php } ?>
					</div>
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('Customize Popup','couponwheel'); ?></h2>
					<p><input id="couponwheel_popup_background_label" type="hidden" name="popup_background_label" value="<?php echo $wheel->popup_background_label ?>"><label class="input_spacing"><?php _e('Background color','couponwheel'); ?></label> <a style="margin-right: 1em" class="thickbox" id="couponwheel_popup_background_select" href="#TB_inline?&width=600&height=550&inlineId=couponwheel_backgrounds"><?php echo $wheel->popup_background_label ?></a>
					</p>
					<!-- BACKGROUNDS -->
					<div id="couponwheel_backgrounds" style="display:none;">
						<div>
							<?php include('backgrounds.php'); ?>
						</div>
					</div>
					<!-- -->

					<p><?php echo $this->form_color_picker('popup_heading_text_color',__('Heading text color','couponwheel'),$wheel->popup_heading_text_color) ?></p>
					<p><?php echo $this->form_color_picker('popup_main_text_color',__('Main text color','couponwheel'),$wheel->popup_main_text_color) ?></p>
					<p><?php echo $this->form_number_input('slice_font_size',__('Slice font size','couponwheel'),1,200,$wheel->slice_font_size) ?></p>
					<p><?php echo $this->form_number_input('wheel_spin_time',__('Wheel spin time','couponwheel'),1,30,$wheel->wheel_spin_time,__('seconds','couponwheel')) ?></p>
					<h3><?php _e('Optonal branding','couponwheel'); ?></h3>
					<p><?php echo $this->form_text_input('popup_header_image',__('Popup header image','couponwheel'),$wheel->popup_header_image) ?><a href="#" class="button" id="couponwheel_popup_header_image_picker"><?php _e('Select image','couponwheel'); ?></a></p>
					<p><?php echo $this->form_text_input('custom_gift_icon',__('Custom gift image','couponwheel'),$wheel->custom_gift_icon) ?><a href="#" class="button" id="couponwheel_custom_gift_icon_picker"><?php _e('Select image','couponwheel'); ?></a></p>
					<p><?php echo $this->form_text_input('custom_background_img',__('Custom background image','couponwheel'),$wheel->custom_background_img) ?><a href="#" class="button" id="couponwheel_custom_background_img_picker"><?php _e('Select image','couponwheel'); ?></a></p>
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('Conversion Booster','couponwheel'); ?></h2>
					<p><?php _e('Use these settings to show urgency elements which will boost your conversions','couponwheel'); ?></p>
					<br>
					<p><?php echo $this->form_checkbox('show_offers_claimed',__('Show how many offers have been claimed','couponwheel'),$wheel->show_offers_claimed) ?></p>
					<p><?php echo $this->form_number_input('offers_claimed_percentage',__('Set how many offers have been claimed','couponwheel'),1,99,$wheel->offers_claimed_percentage,'%') ?></p>
					<p><?php echo $this->form_color_picker('offers_progressbar_color',__('Progressbar color','couponwheel'),$wheel->offers_progressbar_color) ?></p>
					<p><?php echo $this->form_text_input('offers_claimed_text',__('Offers claimed text','couponwheel'),$wheel->offers_claimed_text) ?></p>		
				</div>
			</div>
			<div id="couponwheel_configre_wheel_tabs_edit_popup_strings" style="display: none">
				<div class="couponwheel_configre_wheel_tabs_sections">
					<ul>
						<li><a href="#edit_popup_strings_section_1" class="button"><?php echo _e('Customize Text','couponwheel'); ?></a></li>
						<li><a href="#edit_popup_strings_section_2" class="button"><?php echo _e('Edit Other Text Elements','couponwheel'); ?></a></li>
					</ul>
					<div id="edit_popup_strings_section_1">
						<div class="card couponwheel_card">
							<h2><?php _e('Customize text','couponwheel'); ?></h2>
							<p><?php echo $this->form_text_input('popup_heading_text',__('Main heading','couponwheel'),$wheel->popup_heading_text) ?></p>
							<p><?php echo $this->form_textarea_input('popup_main_text',__('Main text','couponwheel'),$wheel->popup_main_text) ?></p>
							<p><?php echo $this->form_textarea_input('popup_rules_text',__('Rules','couponwheel'),$wheel->popup_rules_text) ?></p>
							<hr>
							<div style="padding: .5em 0; border-radius: 1em; background-color: #dcedc8">
								<p><?php echo $this->form_text_input('popup_win_heading_text',__('Win heading','couponwheel'),$wheel->popup_win_heading_text) ?></p>
								<p><?php echo $this->form_textarea_input('popup_win_main_text',__('Win text','couponwheel'),$wheel->popup_win_main_text) ?></p>
							</div>
							<hr>
							<div style="padding: .5em 0; border-radius: 1em; background-color: #ffebee">
								<p><?php echo $this->form_text_input('popup_lose_heading_text',__('Lose heading','couponwheel'),$wheel->popup_lose_heading_text) ?></p>
								<p><?php echo $this->form_textarea_input('popup_lose_main_text',__('Lose text','couponwheel'),$wheel->popup_lose_main_text) ?></p>
							</div>
						</div>
						<div class="card couponwheel_card">
							<p><strong><?php _e('Quick Tip','couponwheel'); ?></strong></p>
							<p>
							<?php _e('In <strong>Win text</strong>, <strong>Lose text</strong> you can use variables to display info about spin:','couponwheel'); ?><br>
							<code> {email} {firstname} {lastname} {phonenumber} {siteurl} {slice}</code>
							</p>
							<p>
								
							</p>
						</div>
					</div>
					<div id="edit_popup_strings_section_2">
						<div class="card couponwheel_card">
							<h2>Edit Other Text Elements</h2>
							<?php foreach($wheel as $key => $value) { ?>
								<?php if (strpos($key,'lang_') === 0) { ?> 
								<p><?php echo $this->form_text_input($key,$key,$wheel->{$key}) ?></p>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div id="couponwheel_configre_wheel_tabs_mailing" style="display: none">
				<div class="card couponwheel_card">
					<h2><?php _e('Mailing','couponwheel'); ?></h2>
					<p><?php echo $this->form_checkbox('send_mail_after_spin',__('Send mail to user on win','couponwheel'),$wheel->send_mail_after_spin) ?></p>
					<p><?php echo $this->form_text_input('email_win_subject',__('Subject','couponwheel'),$wheel->email_win_subject) ?></p>
					<p><?php echo $this->form_textarea_input('email_win_message',__('Message','couponwheel'),$wheel->email_win_message) ?></p>
				</div>
				<div class="card couponwheel_card">
					<h2><?php _e('Mailing Extras','couponwheel'); ?></h2>
					<p><?php echo $this->form_checkbox('notify_admin_on_win',__('Notify admin if user wins','couponwheel'),$wheel->notify_admin_on_win) ?></p>
					<p><?php echo $this->form_text_input('notify_admin_email',__('Admin email','couponwheel'),$wheel->notify_admin_email) ?></p>
					<script>
						jQuery(document).ready(function($){
							$('#notify_admin_email').attr('placeholder','<?php echo get_bloginfo('admin_email'); ?>');
						});
					</script>
				</div>
				<div class="card couponwheel_card">
					<p><strong><?php _e('Quick Tip','couponwheel'); ?></strong></p>
					<p><?php _e('In <strong>E-mail Message</strong> you can use variables to display info about spin:','couponwheel'); ?><br>
					<code> {email} {firstname} {lastname} {phonenumber} {siteurl} {slice}</code></p>
				</div>
			</div>
			<br>
			<button id="couponwheel_configure_wheel_form_submit_btn" type="submit" class="button button-primary"><?php _e('Save','couponwheel'); ?></button>
		</form>
	</div>
</div>
<script>

var couponwheel_preview_mode = false;
var couponwheel_preview_window;

window.addEventListener('load',function(){
	
	var couponwheel_page_filter_cpt_search_delay = 0;
	
	var enable_custom_win_probability = <?php echo get_option('couponwheel_enable_custom_win_probability') ?>;
	
	if ( enable_custom_win_probability ) {
		jQuery('select.win_multiplier').hide();
		jQuery('input.win_multiplier').show();
		jQuery('.probability_calc').show();
	} else {
		jQuery('input.win_multiplier').hide();
	}
	
	jQuery('select.win_multiplier').change(function(){
		jQuery( 'input[name=' + jQuery(this).attr('data-for') + ']' ).val( this.value );
	});
	
	jQuery('input.win_multiplier').change( function() {
		var totals = 0;
		jQuery.each(jQuery('input.win_multiplier'), function( i, v ) {
			totals += parseInt( jQuery( v ).val() );
		});

		jQuery.each(jQuery('span.probability_percent'), function( i, v ) {
			var name = jQuery( v ).data('win_multiplier');
			var input_val = jQuery('input[name="'+name+'"]').val();
			jQuery( v ).html( ( ( input_val / totals ) * 100 ).toFixed( 2 ) );
		});
	} );
	
	jQuery('input.win_multiplier').trigger('change');
	
	jQuery('#couponwheel_mailchimp_list_toggle input').attr('readonly', true);
	
	if (jQuery('#mailchimp_list_id').val().length == 0) {
		jQuery('#couponwheel_mailchimp_list_toggle').hide();
	}
	
	jQuery('.couponwheel_page_filter_cpt_search').keyup(function(){
		clearTimeout(couponwheel_page_filter_cpt_search_delay);
		if (jQuery('.couponwheel_page_filter_cpt_search').val().length === 0) {
			jQuery('.couponwheel_page_filter_cpt_search').val('');
			jQuery('.couponwheel_page_filter_cpt_search_results').empty();
			jQuery('.couponwheel_page_filter_cpt_search_results').hide();
			return;
		}

		couponwheel_page_filter_cpt_search_delay = setTimeout(function(){
			jQuery('.couponwheel_page_filter_cpt_search_results').show();
			jQuery('.couponwheel_page_filter_cpt_search_results').html('<div style="padding: .8rem"><?php _e('Searching...','couponwheel'); ?></div>');
			
			jQuery.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'couponwheel_cpt_search',
					query: jQuery('.couponwheel_page_filter_cpt_search').val(),
					_wpnonce: '<?php echo wp_create_nonce(); ?>'
					}
			}).done(function(response){
				jQuery('.couponwheel_page_filter_cpt_search_results').html(response);
				
				var cpt_results = JSON.parse(response);
				
				jQuery('.couponwheel_page_filter_cpt_search_results').empty();
				
				if (jQuery(cpt_results).length === 0) {
					jQuery('.couponwheel_page_filter_cpt_search_results').hide();
				} else {
					jQuery.each(cpt_results,function(post_id,title){
						jQuery('.couponwheel_page_filter_cpt_search_results').append('<a class="couponwheel_page_filter_cpt_search_result" data-post_id="'+post_id+'" data-title="'+title+'">'+title+'</a>');
					});
					
					jQuery('.couponwheel_page_filter_cpt_search_result').click(function(event){
						var post_id = jQuery(event.target).data('post_id');
						var title = jQuery(event.target).data('title');
						if (jQuery('#page_filter_checkbox_'+post_id).length === 0)
						{
							jQuery('.couponwheel_page_filter_cpt').append('<div class="page_filter_checkbox_container"><input checked name="page_filter[]" value="'+post_id+'" type="checkbox" id="page_filter_checkbox_'+post_id+'"> <label for="page_filter_checkbox_'+post_id+'">'+title+'</label></div>');
						}
						jQuery('.couponwheel_page_filter_cpt_search').val('');
						jQuery('.couponwheel_page_filter_cpt_search_results').hide();
					});
					
				}

			});
		
		},400);
	});
	
	jQuery('.couponwheel_color_picker').wpColorPicker();
	
	jQuery('#couponwheel_get_mailchimp_lists_btn').click(function(){
		if (jQuery('#mailchimp_api_key').val() === '') return;
		jQuery('#couponwheel_get_mailchimp_lists_btn').prop('disabled',true);
		jQuery.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'couponwheel_get_mailchimp_lists',
				mc_api_key: jQuery('#mailchimp_api_key').val(),
				_wpnonce: '<?php echo wp_create_nonce(); ?>'
				}
		}).done(function(json){
			jQuery('#couponwheel_get_mailchimp_lists_btn').prop('disabled',false);
			json = jQuery.parseJSON(json);
			if (json.mc_response === null) return;
			if (json.mc_response.status >= 400)
			{
				alert('MailChimp response: ' + json.mc_response.detail);
				return;
			}
			alert('MailChimp lists refreshed successfully!');
			jQuery('#couponwheel_mailchimp_list_toggle').show();
			jQuery('#couponwheel_mailchimp_lists').show();
			jQuery('#couponwheel_mailchimp_lists').find('option').remove();
			jQuery('#couponwheel_mailchimp_lists').append('<option data-id="" data-name="">[ <?php _e('Select mailing list here','couponwheel'); ?> ]</option>');
			jQuery.each(json.mc_response.lists,function(i,list){
				jQuery('#couponwheel_mailchimp_lists').append('<option data-id="" data-name=""></option>');
				jQuery('#couponwheel_mailchimp_lists').append('<option data-id="'+list.id+'" data-name="'+list.name+'">'+list.name+'</option>');
			});
		});
	});
	
	jQuery('#couponwheel_mailchimp_lists').change(function(){
		jQuery('#mailchimp_list_id').val(jQuery('#couponwheel_mailchimp_lists :selected').attr('data-id'));
		jQuery('#mailchimp_list_name').val(jQuery('#couponwheel_mailchimp_lists :selected').attr('data-name'));
	});

	jQuery('#couponwheel_save_all_and_preview').click(function(){
		couponwheel_preview_window = window.open("","_blank",'height=720,width=1280,status=yes,toolbar=no,menubar=no,location=no,addressbar=no');
		couponwheel_preview_window.document.write('Loading preview...');
		couponwheel_preview_mode = true;
		jQuery('#couponwheel_configure_wheel_form').submit();
	});
	
	jQuery('#couponwheel_configure_wheel_form').on('submit',function(event){
		event.preventDefault();
		jQuery('#couponwheel_configure_wheel_form').attr('disabled',true);
		jQuery('#couponwheel_configure_wheel_form_submit_btn').attr('disabled',true);
		var data = {
			action: 'couponwheel_configure_wheel_save',
			form_data: jQuery('#couponwheel_configure_wheel_form').serialize(),
			_wpnonce: '<?php echo wp_create_nonce(); ?>'
		};
		jQuery.post(ajaxurl,data,function(json) {
			json = jQuery.parseJSON(json);
			jQuery('#couponwheel_configure_wheel_form').attr('disabled',false);
			jQuery('#couponwheel_configure_wheel_form_submit_btn').attr('disabled',false);
			
			if (couponwheel_preview_mode)
			{
				couponwheel_preview_window.location.href = '<?php echo get_site_url()?>?couponwheel_popup_preview_key=<?php echo $wheel->popup_preview_key?>';
				couponwheel_preview_mode = false;
			} else {
				alert(json.error_msg);
			}
		});
	});
	
	jQuery('.couponwheel_js_confirm').click(function(event) {
		if (!confirm(jQuery(this).data('msg'))) event.preventDefault();
	});
	
	jQuery('.couponwheel_js_prompt').click(function(event) {
		var p = prompt(jQuery(this).data('msg'));
		if ((p === null) || (p.toUpperCase() != jQuery(this).data('check-msg').toUpperCase())) event.preventDefault();
	});
	
	function couponwheel_toggle_qty_fields() {
		jQuery.each(jQuery('.infinite_wins_checkbox'),function(i,el) {
			var qty_el = jQuery('input[name="' + el.name.replace('_infinite','_qty') + '"]');
			var qty_unlimited_el = jQuery('#' + el.name.replace('_infinite','_qty_unlimited'));
			if (el.checked) {
				qty_el.hide();
				qty_unlimited_el.show();
			} else {
				qty_el.show();
				qty_unlimited_el.hide();
			}
		});
	}
	
	couponwheel_toggle_qty_fields();
	
	jQuery('.infinite_wins_checkbox').change(couponwheel_toggle_qty_fields);
	
	jQuery('.couponwheel_unique_gen').click(function(event) {
		if (jQuery(this).prop('checked')) {
			jQuery('#couponwheel_coupon_code_autocomplete'+jQuery(this).data('id')).attr('placeholder','SELECT TEMPLATE');
		} else {
			jQuery('#couponwheel_coupon_code_autocomplete'+jQuery(this).data('id')).attr('placeholder','<?php _e('Enter coupon code','couponwheel'); ?>');
			jQuery('#couponwheel_coupon_code_autocomplete'+jQuery(this).data('id')).val('');
		}
	});
	
	<?php if ($this->woo) { ?>
	jQuery('.couponwheel_coupon_code_autocomplete').autocomplete({
		source: <?php echo $coupons_autocomplete; ?>,
		minLength: 0
	}).focus(function() {
		jQuery(this).autocomplete('search');
	});
	<?php } ?>

});

function couponwheel_set_popup_background(label,css) {
	jQuery('#couponwheel_popup_background_select').html(label);
	jQuery('#couponwheel_popup_background_label').val(label);
	jQuery('#couponwheel_popup_background_css').val(css);
}

jQuery('#couponwheel_popup_header_image_picker').click(function(event) {
	event.preventDefault();
	couponwheel_image_picker('#popup_header_image');
});

jQuery('#couponwheel_custom_gift_icon_picker').click(function(event) {
	event.preventDefault();
	couponwheel_image_picker('#custom_gift_icon');
});

jQuery('#couponwheel_custom_background_img_picker').click(function(event) {
	event.preventDefault();
	couponwheel_image_picker('#custom_background_img');
});

function couponwheel_image_picker(e)
{

	var media = wp.media({
		title: 'Select image',
		button: {
			text: 'Use this image'
		},
		multiple: false
	});
	
	media.on('select',function() {
		var attachment = media.state().get('selection').first().toJSON();
		jQuery(e).val(attachment.url);
	});
	
	media.open();
}
</script>

<style>
#TB_window {
	max-height: 90vh;
	max-width: 100%;
	left: 50%;
	top: 50%;
	transform: translateX(-50%) translateY(-50%);
	margin-left: 0 !important;
	margin-top: 0 !important;
	box-sizing: border-box;
	overflow: auto;
}
#TB_ajaxContent {
	width: 100% !important;
	box-sizing: border-box;
}
</style>