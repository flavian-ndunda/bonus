<?php
/*
	Plugin Name: Lottopawa2
	Description: :-)
	Version: 2
	Author: Rawalski
	Author URI: https://awuoro.co.ke
	Plugin URI: https://awuoro.co.ke
	Text Domain: LottoPawa2
*/

/*
	It is allowed to use this file only if you have a license and by terms & conditions defined in your license. More information:
	
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

include('lib/updater.php');

class couponwheel {

	private $db_wheel_table = '';
	private $db_run_log_table = '';
	private $db_popup_log_table = '';
	private $woo = false;
	private $raw_post;
	private $embeded_wheels = array();
	private $updater;
	private $updater_url = 'https://awuoro.co.ke/';
	
	public function __construct()
	{
		add_action('plugins_loaded', array($this,'capture_raw_post'));
		add_action('init', array($this,'init'));
		
		global $wpdb;
		
		$this->db_wheel_table = $wpdb->prefix . 'couponwheel_wheels';
		$this->db_run_log_table = $wpdb->prefix . 'couponwheel_wheel_run_log';
		
		register_activation_hook(__FILE__, array($this,'plugin_upgrade'));
		register_deactivation_hook(__FILE__, array($this,'plugin_deactivate'));
		
		$this->updater = new couponwheel_updater(array(
			'url' => $this->updater_url,
			'option' => 'couponwheel_updater',
			'version' => $this->get_version(),
			'plugin' => plugin_basename( __FILE__ ),
			'push_update' => get_option('couponwheel_push_update')
			)
		);
	}
	
	public function get_version()
	{
		$file_data = get_file_data(__FILE__, array('Version' => 'Version'));
		return $file_data['Version'];
	}
	
	public function capture_raw_post()
	{
        if (isset($_POST)) $this->raw_post = $_POST;
	}
	
	public function plugin_upgrade()
	{
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();
		
		$table_name = $this->db_wheel_table;
		
		$sql = file_get_contents(plugin_dir_path(__FILE__) . 'lib/wheels.sql');
		$sql = sprintf($sql,$this->db_wheel_table,$charset_collate);

		dbDelta($sql);

		$table_name = $this->db_run_log_table;
		
		$sql = file_get_contents(plugin_dir_path(__FILE__) . 'lib/run_log.sql');
		$sql = sprintf($sql,$this->db_run_log_table,$charset_collate);
		
		dbDelta($sql);
		
		update_option('couponwheel_version', $this->get_version());

		add_option('couponwheel_apply_coupon_automatically','0');
		add_option('couponwheel_hide_unique_coupons','0');
		add_option('couponwheel_reduce_requests','0');
		add_option('couponwheel_push_update','1');
		
		if (get_option('couponwheel_reduce_requests')) add_option('couponwheel_skip_google_fonts','1');
		add_option('couponwheel_skip_google_fonts','0');
		
		add_option('couponwheel_recaptcha_site_key','');
		add_option('couponwheel_recaptcha_secret','');
		add_option('couponwheel_reload_webpage_on_wheel_close','0');
		add_option('couponwheel_remove_personal_data_from_spin_log','0');
		add_option('couponwheel_autofill','1');
		add_option('couponwheel_delete_expired_coupons_automatically','0');
		
		add_option('couponwheel_from_email_address', '');
		add_option('couponwheel_from_email_name', '');
		
		add_option('couponwheel_rtl', '0');
		
		add_option('couponwheel_enable_custom_win_probability', '0');
		add_option('couponwheel_popup_close_confirm', '1');
		
		add_option('couponwheel_devtools',0);
		delete_option('couponwheel_updater_timeout');

		wp_clear_scheduled_hook('couponwheel_cron');
		wp_schedule_event(time()+30,'hourly','couponwheel_cron');
	}
	
	public function couponwheel_cron()
	{
		if (get_option('couponwheel_delete_expired_coupons_automatically')) $this->couponwheel_delete_expired_coupons();
	}
	
	public function plugin_deactivate()
	{
		wp_clear_scheduled_hook('couponwheel_cron');
	}

	public function init()
	{
		global $wpdb;

		load_plugin_textdomain('couponwheel', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		
		if(get_option('couponwheel_version') !== FALSE) {
			if(version_compare($this->get_version(),get_option('couponwheel_version'),'>')) {
				$this->plugin_upgrade();
			}
		}
		
		add_action('couponwheel_cron',array($this,'couponwheel_cron'));
		
		// Check if WooCommerce is active
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		if (is_plugin_active('woocommerce/woocommerce.php')) $this->woo = true;
		
		if (!wp_doing_ajax()) add_action('wp_enqueue_scripts',array($this,'load_assets'));
		
		$admin_capability = $this->get_admin_capability();
		
		if ( current_user_can( $admin_capability ) ) {
			if (isset($_GET['couponwheel_devtools_upgrade'])) $this->plugin_upgrade();
			if (isset($_GET['couponwheel_devtools_delete_update_transients'])) $this->delete_update_transients();
			add_action('admin_enqueue_scripts', array($this,'load_admin_assets'));
			add_action('admin_menu',array($this,'add_admin_menu'));
			add_action('admin_init',array($this,'check_redirections'));
			add_action('wp_ajax_couponwheel_popup_background_picker',array($this,'popup_background_picker'));
			add_action('wp_ajax_couponwheel_configure_wheel_save',array($this,'configure_wheel_save'));
			add_action('wp_ajax_couponwheel_settings_save',array($this,'settings_save'));
			add_action('wp_ajax_couponwheel_get_mailchimp_lists',array($this,'couponwheel_get_mailchimp_lists'));
			add_action('wp_ajax_couponwheel_html_log',array($this,'html_log'));
			add_action('wp_ajax_couponwheel_cpt_search',array($this,'couponwheel_cpt_search'));
			add_action('wp_ajax_couponwheel_delete_expired_coupons',array($this,'couponwheel_delete_expired_coupons'));
		}

		add_action('wp_ajax_nopriv_couponwheel_wheel_run',array($this,'couponwheel_wheel_run'));
		add_action('wp_ajax_couponwheel_wheel_run',array($this,'couponwheel_wheel_run'));

		add_action('wp_ajax_nopriv_couponwheel_event',array($this,'couponwheel_event'));
		add_action('wp_ajax_couponwheel_event',array($this,'couponwheel_event'));
		
		add_action('wp_ajax_nopriv_couponwheel_load_popups',array($this,'couponwheel_load_popups'));
		add_action('wp_ajax_couponwheel_load_popups',array($this,'couponwheel_load_popups'));

		add_action('wp_ajax_nopriv_couponwheel_notice',array($this,'couponwheel_notice_deprecated'));
		add_action('wp_ajax_couponwheel_notice',array($this,'couponwheel_notice_deprecated'));

		add_shortcode('couponwheel_embed',array($this,'embed'));
		
		if ($this->woo)
		{
			add_action('pre_get_posts',array($this,'woo_unique_coupon_filter'));
			
			if (get_option('couponwheel_delete_expired_coupons_automatically'))
			{
				add_action('woocommerce_before_checkout_process',array($this,'remove_expired_coupon_from_cart'));
			}
		}
		
		add_action('wp_footer', array($this,'add_coupon_notice'));
		
		add_filter('wp_privacy_personal_data_exporters',array($this,'register_personal_data_exporters'));
		add_filter('wp_privacy_personal_data_erasers',array($this,'register_personal_data_erasers'));
	}
	
	public function delete_update_transients()
	{
		global $wpdb;
		$wpdb->update( 'wp_options', array( 'option_value' => '' ), array( 'option_name' => '_site_transient_update_plugins') );
		update_option('couponwheel_updater_timeout',1);
	}
	
	public function embed($atts)
	{
		ob_start();
		
		$wheel = $this->get_wheel($atts['wheel_hash']);

		if ($wheel && $wheel->is_live)
		{
			include('templates/embed.php');
		} else {		
			echo '<span style="font-size: 2em">&#9208;</span>';
		}
		
		return ob_get_clean();
	}
	
	public function remove_expired_coupon_from_cart()
	{
		
		$coupons = WC()->cart->get_applied_coupons();
		
		foreach ($coupons as $coupon)
		{
			$id = wc_get_coupon_id_by_code($coupon);
			$exp_time = get_post_meta($id,'_couponwheel_coupon_expire_timestamp',true);
			if ((!empty($exp_time)) && (time() > $exp_time)) (new WC_Coupon($id))->delete(true);
		}

	}
	
	public function get_expired_coupons()
	{
		$expired_coupons_args = array(
			'posts_per_page' => 10,
			'meta_key' => '_couponwheel_coupon_expire_timestamp',
			'meta_value' => time(),
			'meta_compare' => '<',
			'post_type' => 'shop_coupon',
			'fields' => 'ids'
		);
		return get_posts($expired_coupons_args);
	}
	
	public function count_expired_coupons()
	{
		
		$query = new WP_Query(array(
					'posts_per_page' => -1,
					'meta_key' => '_couponwheel_coupon_expire_timestamp',
					'meta_value' => time(),
					'meta_compare' => '<',
					'post_type' => 'shop_coupon',
					'fields' => 'ids'
				));

		return $query->found_posts;
	
	}

	public function couponwheel_delete_expired_coupons()
	{
		$ecs = $this->get_expired_coupons();
		
		foreach($ecs as $ec)
		{
			wp_delete_post($ec,true);
		}
		
		if (wp_doing_ajax())
		{
			$response['current_count'] = $this->count_expired_coupons();
			$response['success'] = true;
			echo json_encode($response);
			wp_die();
		}
		
	}
	
	public function couponwheel_notice_deprecated()
	{
		wp_die();
	}
	
	public function register_personal_data_erasers($erasers)
	{
		$erasers['couponwheel'] = array(
			'eraser_friendly_name' => 'LottoPawa2 Plugin',
			'callback' => array($this,'personal_data_eraser')
		);
		return $erasers;
	}
	
	public function register_personal_data_exporters($exporters)
	{
		$exporters['couponwheel'] = array(
			'exporter_friendly_name' => 'LottoPawa2',
			'callback' => array($this,'personal_data_exporter')
		);
		return $exporters;
	}
	
	public function personal_data_eraser($email_address)
	{
		global $wpdb;
		
		$wheels = $this->get_wheels();

		$items_removed = false;
		$items_retained = false;
		
		$messages = array();
		
		$email_search = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $this->db_run_log_table WHERE email = %s",$email_address));
		
		if ($email_search)
		{
			
			if (!get_option('couponwheel_remove_personal_data_from_spin_log'))
			{
			
				$messages[] = 'LottoPawa2 retained data in spin log.';
				$items_retained = true;
				
			} else {
	
				$eraser = array(
								'email' => __('[deleted]'),
								'first_name' => __('[deleted]'),
								'last_name' => __('[deleted]'),
								'phone_number' => __('[deleted]'),
								'ip' => __('[deleted]'),
								'user_cookie' => __('[deleted]')
								);
				$wpdb->update($this->db_run_log_table,$eraser, array('email'=>$email_address));
				$items_removed = true;
			}
			
		}
		
		return array(
					'items_removed' => $items_removed,
					'items_retained' => $items_retained,
					'messages' => $messages,
					'done' => true,
		);
	}
	
	public function personal_data_exporter($email_address)
	{
		
		global $wpdb;

		$rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->db_run_log_table WHERE email = %s ORDER BY id DESC",$email_address));
		
		$data_to_export = array();
		
		foreach($rows as $row)
		{

			unset($data);
			foreach ($row as $name => $value)
			{
				if (empty($value)) continue;
				$data[] = array('name'=>$name,'value'=>esc_html($value));
			}
			
			$data_to_export[] = array(
							'group_id' => 'couponwheel_spin_data',
							'group_label' => 'LottoPawa2',
							'item_id' => 'couponwheel_spin_data_'.$row->id,
							'data' => $data
						   );
		}

		return array(
					 'data' => $data_to_export,
					 'done' => true
					 );
	}
	
	public function couponwheel_cpt_search()
	{
		
		if (empty($_POST['query'])) { echo '{}'; wp_die(); }
		
		$args = array(
			'posts_per_page' => 250,
			'post_type' => 'any',
			'post_status' => 'publish',
			's' => $_POST['query']
		);
			
		$results = get_posts($args);

		$output = array();

		foreach($results as $result) {
			if (mb_strpos(mb_strtolower($result->post_title),mb_strtolower($_POST['query'])) === false) continue;
			$output[$result->ID] = strtoupper($result->post_type) .': '. $result->post_title . ' (#'.$result->ID.')';
		}
	
		echo json_encode($output);
		
		wp_die();
	}
	
	public function clear_redundant_cookies()
	{
		foreach ($_COOKIE as $key => $val)
		{
			if (strpos($key,'couponwheel') === 0)
			{
				if (strpos($key,'_seen') === 17)
				{
					if (!$this->get_wheel(substr($key,11,6)))
					{
						setcookie($key,1,1,'/');
					}
				}
			}
		}	
	}
	
	public function manage_cookies()
	{
		$cookie_hash = md5(uniqid('',true));
		$cookie_hash = substr( $cookie_hash, 0, 10 );
		if ( isset($_COOKIE['couponwheel_session']) ) $cookie_hash = $_COOKIE['couponwheel_session'];
		setcookie('couponwheel_session',$cookie_hash,strtotime('+365 days'),'/');

		return $cookie_hash;
	}
	
	public function check_redirections()
	{
		global $pagenow;
		
		$page = (isset($_REQUEST['page']) ? $_REQUEST['page'] : false);
		
		if ($pagenow == 'admin.php' && $page == 'couponwheel_dashboard')
		{
			if (isset($_GET['action']) && ($_GET['action'] == 'add_new_wheel'))
			{
				check_admin_referer($_GET['action']);
				$this->add_new_wheel();
				wp_redirect('?page=couponwheel_dashboard');
				exit;
			}
			
			if (isset($_GET['action']) && ($_GET['action'] == 'clone_wheel'))
			{
				check_admin_referer($_GET['action']);
				$this->clone_wheel($_GET['wheel_hash']);
				wp_redirect('?page=couponwheel_dashboard');
				exit;
			}
			
			if (isset($_GET['action']) && ($_GET['action'] == 'delete_wheel'))
			{
				check_admin_referer($_GET['action']);
				$this->delete_wheel($_GET['wheel_hash']);
				wp_redirect('?page=couponwheel_dashboard');
				exit;
			}
			
			if (isset($_GET['action']) && ($_GET['action'] == 'reset_wheel'))
			{
				check_admin_referer($_GET['action']);
				$this->reset_wheel($_GET['wheel_hash']);
				wp_redirect('?page=couponwheel_dashboard');
				exit;
			}
			
			if (isset($_GET['action']) && ($_GET['action'] == 'reset_seen_key'))
			{
				check_admin_referer($_GET['action']);
				$this->reset_seen_wheel($_GET['wheel_hash']);
				wp_redirect('?page=couponwheel_dashboard');
				exit;
			}
		}
	}
	
	public function couponwheel_notice($wheel,$template_vars)
	{
		
		if (empty($template_vars['couponcode_raw']) || (!$wheel->show_coupon_notice)) return false;
		
		ob_start();
		
		global $wpdb;

		if ($wheel->show_coupon_notice)
		{
			$expire_timestamp = time() + ($wheel->coupon_urgency_timer * 60);
			$notice = $wheel->lang_coupon_notice;
			$plugin_dir_url = plugin_dir_url( __FILE__ );
			include(plugin_dir_path(__FILE__) . 'templates/coupon_notice_content.php');
		}
		
		return ob_get_clean();
	}
	
	public function add_coupon_notice()
	{
		include(plugin_dir_path(__FILE__) . 'templates/coupon_notice.php');
	}
	
	public function couponwheel_event()
	{
		global $wpdb;
		
		$wheel = $this->get_wheel($_POST['wheel_hash']);
		if (!$wheel) return;
	
		$preview_mode = $this->validate_preview_mode($_POST['preview_key'],$wheel->popup_preview_key);
	
		if (!$preview_mode) {
			if (!$wheel->is_live) return;
		}
	
		if ($_POST['code'] == 'show_popup')
		{
			$wpdb->query($wpdb->prepare("UPDATE $this->db_wheel_table SET popup_impressions = popup_impressions+1 WHERE wheel_hash = %s",$_POST['wheel_hash']));
			if (!$preview_mode) setcookie("couponwheel$wheel->wheel_hash"."_seen",$wheel->seen_key,strtotime("+$wheel->show_popup_every days"),'/');
		}
		wp_die();
	}
	
	public function woo_unique_coupon_filter($query)
	{
		if (!get_option('couponwheel_hide_unique_coupons')) return;
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			if (isset($screen->id) && (($screen->id) == 'edit-shop_coupon'))
			{
				if ($query->query['post_type'] == 'shop_coupon')
				{
					$meta_query = (array) $query->get('meta_query');
					$meta_query[] =	array(
											'key' => '_couponwheel_autogenerated',
											'value' => 'yes',
											'compare' => 'NOT EXISTS'
										);
					$query->set('meta_query',$meta_query);
				}
			}
		}
	}
	
	public function couponwheel_get_mailchimp_lists($mc_api_key = '')
	{
		if (isset($_POST['mc_api_key'])) $mc_api_key = $_POST['mc_api_key'];
		
		$mc_server_arr = explode('-',$mc_api_key);
		$mc_server = $mc_server_arr[1];
	
		$response['success'] = false;
		
		unset($ch_setup);
		$ch_setup['url'] = "https://$mc_server.api.mailchimp.com/3.0/lists/?count=1000";
		$ch_setup['arg']['headers'] = array('Content-Type' => 'application/json','Authorization' => "apikey $mc_api_key");
		$ch = wp_remote_get($ch_setup['url'],$ch_setup['arg']);
	
		if (!is_wp_error($ch))
		{
			$response['mc_response'] = json_decode($ch['body']);
			$response['success'] = true;
		}

		echo json_encode($response);
		wp_die();
	}
	
	public function mailchimp_signup($mc_api_key,$list,$email,$first_name = '',$last_name = '',$double_optin = false)
	{
		$mc_server_arr = explode('-',$mc_api_key);
		$mc_server = $mc_server_arr[1];
		
		unset($ch_setup);
		$ch_setup['url'] = "https://$mc_server.api.mailchimp.com/3.0/lists/$list/members/".md5($email);
		$ch_setup['arg']['headers'] = array('Content-Type' => 'application/json','Authorization' => "apikey $mc_api_key");
		$ch = wp_remote_get($ch_setup['url'],$ch_setup['arg']);
		
		if (is_wp_error($ch)) return false;
		
		$response = json_decode($ch['body']);
		
		if ($response->status == 404)
		{
			$data['email_address'] = $email;
			$data['status'] = 'subscribed';
			
			if ($double_optin)
			{
				$data['status'] = 'pending';
			}
			
			$data['merge_fields'] = array('FNAME' => $first_name,'LNAME' => $last_name);
			$endpoint = '';
			$method = 'POST';
		}
		
		if ($response->status == 'unsubscribed')
		{
			$endpoint = md5($email);
			$data['status'] = 'pending';
			$method = 'PATCH';
		}

		if (!isset($data['status'])) return false;

		unset($ch_setup);
		$ch_setup['url'] = "https://$mc_server.api.mailchimp.com/3.0/lists/$list/members/$endpoint";
		$ch_setup['arg']['method'] = $method;
		$ch_setup['arg']['headers'] = array('Content-Type' => 'application/json','Authorization' => "apikey $mc_api_key");
		$ch_setup['arg']['body'] = json_encode($data);
		$ch = wp_remote_request($ch_setup['url'],$ch_setup['arg']);
		
		if (is_wp_error($ch)) return false;
		
		return true;
	}
	
	public function email_dot_duplicate_check($email)
	{
		global $wpdb;
		
		//additional anti-cheat checker for gmail addresses
		if (strpos($email,'@gmail.com') === false) return $email;
	
		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM (SELECT REPLACE(email,'.','') as check_alias, email FROM $this->db_run_log_table) as result WHERE check_alias = %s",str_replace('.','',$email)));

		if (!empty($row)) return $row->email;
		return $email;
	}

	public function ace_limiter($type,$input,$wheel)
	{
		global $wpdb;
		
		if ($type == 'email') $field_to_check = 'email';
		if ($type == 'cookie') $field_to_check = 'user_cookie';
		if ($type == 'ip') $field_to_check = 'ip';
		if ($type == 'phone_number') $field_to_check = 'phone_number';
		
		
		if ($type == 'email')
		{
			$input = $this->email_dot_duplicate_check($input);
		}
		
		$reset_counter_days = time() - ($wheel->reset_counter_days*86400);
		if ($wheel->reset_counter_days == 0) $reset_counter_days = 1;
		$ace_num_rows = $wpdb->get_row($wpdb->prepare("SELECT COUNT(id) as num_rows FROM $this->db_run_log_table WHERE wheel_id = $wheel->id AND $field_to_check = %s AND timestamp > %d",
								$input,
								$reset_counter_days))->num_rows;
		if ($ace_num_rows >= $wheel->max_spins_per_user)
		{
			return false;
		};
		
		return true;
	}
	
	public function ace_validate_email($email,$mx_check = false)
	{
		$is_valid_mail = filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;

		$mail_domain = ltrim(strstr($email, '@'),'@');
		$domain_hash = md5($mail_domain);
		
		if (strpos($email,'+') !== false) return false;
		
		if ($mx_check)
		{
			if (get_transient("couponwheel_ace_mx_domain$domain_hash") !== false)
			{
				$is_valid_domain = get_transient("couponwheel_ace_mx_domain$domain_hash");
			} else {
				$is_valid_domain = checkdnsrr($mail_domain,'MX');
				set_transient("couponwheel_ace_mx_domain$domain_hash",$is_valid_domain,86400);
			}
			
			if($is_valid_mail && $is_valid_domain) return true;
			return false;
		}
		
		if ($is_valid_mail) return true;
		return false;
	}
	
	public function get_page_id() {
		if (function_exists('is_shop') && function_exists('wc_get_page_id'))
		{
			if (is_shop()) return wc_get_page_id('shop');
		}
		return get_the_ID();
	}
	
	public function wheel_run_ajax_error($error_msg,$error_code,$hide_popup = false)
	{
		echo json_encode(array(
			'error_msg' => $error_msg,
			'error_code' => $error_code,
			'hide_popup' => $hide_popup,
			'success' => false
		));
		wp_die();
	}
	
	public function template_parser($template,$template_vars)
	{	
		$output = $template;
		$template_vars['siteurl'] = get_bloginfo('url');
		
		foreach($template_vars as $key => $value)
		{
			if(!empty($template_vars["$key"])) $output = str_replace('{'.$key.'}',$template_vars["$key"],$output);
		}
		
		return $output;
	}
	
	public function validate_preview_mode($submited_key, $preview_key)
	{
		if (isset($submited_key) && $submited_key == $preview_key) return true;
		return false;
	}
	
	public function couponwheel_wheel_run()
	{		

		$this->disable_page_cache();
		
		if ($this->woo) global $woocommerce;
		
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') { return; }

		$response = array();

		global $wpdb;
		
		parse_str($_POST['form_data'],$form_data);


		// sanitize
		if (isset($form_data['email'])) $form_data['email'] = mb_strtolower(sanitize_email($form_data['email']));
		
		foreach (array('first_name','last_name','rules_checked') as $field)
		{
			if (isset($form_data[$field])) $form_data[$field] = sanitize_text_field($form_data[$field]);
		}
		//


		$wheel = $this->get_wheel($form_data['wheel_hash']);
		
		$couponwheel_session = '';
		if ( $wheel->ace_cookie_limit_check ) $couponwheel_session = $this->manage_cookies();
		
		$preview_mode = $this->validate_preview_mode($_POST['preview_key'],$wheel->popup_preview_key);
		
		if (!$wheel) $this->wheel_run_ajax_error('WHEEL HASH ERROR. Please reload webpage.','form_error');
		
		if (!$preview_mode)
		{
			if (!$wheel->is_live) $this->wheel_run_ajax_error('WHEEL NOT LIVE. Please reload webpage.','form_error');
		}
		
		if ($wheel->require_email && empty($form_data['email'])) $this->wheel_run_ajax_error($wheel->lang_input_missing,'form_error');
		if ($wheel->require_first_name && empty($form_data['first_name'])) $this->wheel_run_ajax_error($wheel->lang_input_missing,'form_error');
		if ($wheel->require_last_name && empty($form_data['last_name'])) $this->wheel_run_ajax_error($wheel->lang_input_missing,'form_error');
		if ($wheel->require_phone_number && empty($form_data['phone_number'])) $this->wheel_run_ajax_error($wheel->lang_input_missing,'form_error');
		if ($wheel->require_rules && empty($form_data['rules_checked'])) $this->wheel_run_ajax_error($wheel->lang_input_missing,'form_error');
		
		if ($wheel->require_phone_number)
		{
			$phone_number_length = sprintf('{%s}',$wheel->phone_number_length);
			
			if($wheel->phone_number_length == 0) {
				$phone_number_length = '{1,30}';
			}
			
			if(!preg_match('/^[\+]?\d'.$phone_number_length.'$/',$form_data['phone_number'])) $this->wheel_run_ajax_error($wheel->lang_input_missing,'form_error');
			
			if (($wheel->ace_phone_number_check) && (!$this->ace_limiter('phone_number',$form_data['phone_number'],$wheel))) $this->wheel_run_ajax_error($wheel->lang_ace_limit_reached,'form_error');
		}
		
		if ($wheel->require_email && !$this->ace_validate_email($form_data['email'],$wheel->ace_mx_check)) $this->wheel_run_ajax_error($wheel->lang_ace_email_check,'form_error');

		if (($wheel->require_email) && ($wheel->ace_email_limit_check))
		{
			if (!$this->ace_limiter('email',$form_data['email'],$wheel)) $this->wheel_run_ajax_error($wheel->lang_ace_limit_reached,'form_error');
		}

		if ($wheel->ace_cookie_limit_check)
		{
			if (!$this->ace_limiter('cookie',$couponwheel_session,$wheel)) $this->wheel_run_ajax_error($wheel->lang_ace_limit_reached,'form_error');
		}

		if ($wheel->ace_ip_limit_check)
		{
			if (!$this->ace_limiter('ip',$_SERVER['REMOTE_ADDR'],$wheel)) $this->wheel_run_ajax_error($wheel->lang_ace_limit_reached,'form_error');
		}
		
		$get_option_couponwheel_recaptcha_site_key = get_option('couponwheel_recaptcha_site_key');
		$get_option_couponwheel_recaptcha_secret = get_option('couponwheel_recaptcha_secret');
		
		if ($wheel->require_recaptcha && !empty($get_option_couponwheel_recaptcha_site_key) && !empty($get_option_couponwheel_recaptcha_secret))
		{
		
			$ch = wp_remote_post('https://www.google.com/recaptcha/api/siteverify',array(
				'body' => array('secret'=>get_option('couponwheel_recaptcha_secret'),'response'=>$form_data['g-recaptcha-response'])
			));
		
			if (!is_wp_error($ch))
			{
				$recaptcha_reply = json_decode($ch['body']);
			
				if (isset($recaptcha_reply))
				{
					if (!$recaptcha_reply->success)
					{
						$this->wheel_run_ajax_error($wheel->lang_input_missing,'form_error');
					}
				} else {
					$this->wheel_run_ajax_error('ReCAPTCHA Error. Please reload webpage.','form_error');
				}
			} else {
				$this->wheel_run_ajax_error('ReCAPTCHA Error. Please reload webpage.','form_error');
			}
		}

		$wpdb->query("LOCK TABLES $this->db_run_log_table WRITE");
		
		$available_slices = array();

		foreach(range(1,12) as $i) {
			if($wheel->{"slice$i"."_infinite"})
			{
				foreach(range(1,$wheel->{"slice$i".'_win_multiplier'}) as $i_win_m)
				{
					$available_slices[] = $i;
				}
				if ($wheel->infinite_has_more_chance)
				{
					foreach(range(1,120) as $ii)
					{
						$available_slices[] = $i;
					}
				}
			}
			if ((!$wheel->{"slice$i"."_infinite"}) && ($wheel->{"slice$i"."_qty"} > 0))
			{
				if ($wheel->{"slice$i"."_qty"} > ($wpdb->get_row("SELECT COUNT(wheel_id) as count FROM $this->db_run_log_table WHERE wheel_id = $wheel->id AND slice_number = $i LIMIT 1")->count))
				{
					foreach(range(1,$wheel->{"slice$i".'_win_multiplier'}) as $i_win_m)
					{
						$available_slices[] = $i;
					}
				};
			}
		}
	
		if(empty($available_slices))
		{
			$wpdb->query("UNLOCK TABLES");
			$this->wheel_run_ajax_error($wheel->lang_no_spins,'form_error');
		}

		$wheel_slice_number = $available_slices[array_rand($available_slices)];
		
		$response['wheel_slice_number'] = $wheel_slice_number;
		$response['wheel_deg_end'] = (360*(ceil($wheel->wheel_spin_time/3))) + (360 - (($wheel_slice_number * 30) - 30)) + rand(-5,5);
		$response['wheel_time_end'] = $wheel->wheel_spin_time * 1000;
		
		foreach(array('email','first_name','last_name','rules_checked','phone_number') as $input_field)
		{
			if(!isset($form_data[$input_field])) $form_data[$input_field] = '';
		}
		
		foreach(array('first_name','last_name','phone_number') as $key)
		{
			$form_data[$key] = mb_convert_case(trim(strip_tags($form_data[$key])), MB_CASE_TITLE, 'UTF-8');
		}
		
		$coupon_expire_timestamp = time() + ($wheel->coupon_urgency_timer * 60);

		$spin_row = array(
									'wheel_id' => $wheel->id,
									'email' => $form_data['email'],
									'first_name' => $form_data['first_name'],
									'last_name' => $form_data['last_name'],
									'rules_checked' => $form_data['rules_checked'],
									'phone_number' => $form_data['phone_number'],
									'ip' => $wheel->ace_ip_limit_check ? $_SERVER['REMOTE_ADDR'] : '',
									'slice_number' => $wheel_slice_number,
									'slice_label' => $wheel->{'slice'.$wheel_slice_number.'_label'},
									'user_cookie' => $couponwheel_session,
									'referer' => empty( $_SERVER['HTTP_REFERER'] ) ? '' : $_SERVER['HTTP_REFERER'],
									'timestamp' => time(),
									'coupon_expire_timestamp' => $coupon_expire_timestamp
								);

		$wpdb->insert($this->db_run_log_table,$spin_row);
		
		$wheel_run_id = $wpdb->insert_id;
		
		$wpdb->query("UNLOCK TABLES");
		
		if( ! $wheel_run_id ) $this->wheel_run_ajax_error( 'Spin could not be completed', 'wheel_run_id == 0' );
		
		$coupon_code = $wheel->{"slice$wheel_slice_number"."_coupon_code"};

		if (($this->woo) && ($wheel->{"slice$wheel_slice_number"."_unique_gen"}))
		{
			// UCG
			$args = array(
				'title' => $coupon_code,
				'posts_per_page' => 1,
				'post_type' => 'shop_coupon',
				'post_status' => 'publish',
				'orderby' => 'ID',
				'order' => 'DESC',
			);
			
			$get_posts_arr = get_posts($args);
			$orig_coupon_post = $get_posts_arr[0];

			if (empty($coupon_code) || empty($orig_coupon_post)) $this->wheel_run_ajax_error(' TEMPLATE NOT FOUND. Please reload webpage.','form_error');
			
			if (mb_strtolower($orig_coupon_post->post_title) != mb_strtolower($coupon_code)) $this->wheel_run_ajax_error(' TEMPLATE MISMATCH. Please reload webpage.','form_error');

			$template_coupon = (array) $orig_coupon_post;
			unset($template_coupon['ID']);
			unset($template_coupon['post_name']);
			unset($template_coupon['guid']);
			
			$unique_coupon_gen_retry = 0;
			
			do {
				$template_coupon['post_title'] = mb_strtoupper($wheel->unique_coupon_prefix . substr(md5($coupon_code . uniqid('',true)),0,7));

				$args = array(
					'title' => $template_coupon['post_title'],
					'posts_per_page' => 1,
					'post_type' => 'shop_coupon',
					'orderby' => 'date',
					'order' => 'DESC',
				);
				
				$get_posts_arr = get_posts($args);
				$coupon_exists = empty($get_posts_arr[0]) ? false : true;
				
				if($coupon_exists) usleep(100);
				$unique_coupon_gen_retry += 1;
				
				if ($unique_coupon_gen_retry > 20) $this->wheel_run_ajax_error(' GENERATE ERROR. Please reload webpage.','form_error');
				
			} while ( $coupon_exists );
			
			$template_coupon['post_excerpt'] = "Coupon Wheel (wheel id: $wheel->id, template: $coupon_code)";
			$orig_coupon_code = $coupon_code;
			$coupon_code = $template_coupon['post_title'];
			$template_coupon_ID = wp_insert_post($template_coupon);
			if ($template_coupon_ID) {
				$orig_coupon_meta = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = $orig_coupon_post->ID");
				foreach ($orig_coupon_meta as $row) {
					$wpdb->insert($wpdb->postmeta,array(
						'post_id' => $template_coupon_ID,
						'meta_key' => $row->meta_key,
						'meta_value' => $row->meta_value
					));
				}
				add_post_meta($template_coupon_ID,'_couponwheel_autogenerated','yes');
				add_post_meta($template_coupon_ID,'_couponwheel_wheel_id',$wheel->id);
				add_post_meta($template_coupon_ID,'_couponwheel_orig_coupon_code',$orig_coupon_code);
				add_post_meta($template_coupon_ID,'_couponwheel_coupon_expire_timestamp',$coupon_expire_timestamp);
				
				$wc_coupon = new WC_Coupon($template_coupon_ID);
				
				$wc_coupon->set_usage_count(0);

				if ($wheel->force_individual_use) $wc_coupon->set_individual_use(true);
				if ($wheel->force_usage_limit) $wc_coupon->set_usage_limit(1);
				if ($wheel->bind_coupon_to_email) $wc_coupon->set_email_restrictions(array($form_data['email']));
				
				if ( $wheel->woo_expire_coupons ) $wc_coupon->set_date_expires( $coupon_expire_timestamp );
				
				$wc_coupon->save();
			}
		}
		// --------
		
		$wpdb->update($this->db_run_log_table,array('coupon_code'=>$coupon_code),array('id'=>$wheel_run_id));
		
		if ( $wheel->webhooks_enabled && apply_filters( 'couponwheel_allow_webhook_call', true, $wheel_run_id ) )
		{
			$webhook_data = $wpdb->get_row("SELECT * FROM $this->db_run_log_table WHERE id = $wheel_run_id",ARRAY_A);
			unset($webhook_data['webhooks_http_status']);
			$this->send_data_to_webhook($wheel->webhooks_url,$wheel_run_id,$webhook_data);
		}
		
		$template_vars['firstname'] = $form_data['first_name'];
		$template_vars['lastname'] = $form_data['last_name'];
		$template_vars['phonenumber'] = $form_data['phone_number'];
		$template_vars['email'] = $form_data['email'];
		$template_vars['slice'] = strip_tags($wheel->{"slice$wheel_slice_number"."_label"});
		$template_vars['couponcode'] = '<span class="couponwheel_coupon_code">'.$coupon_code.'</span>';
		$template_vars['couponcode_raw'] = $coupon_code;
		
		$response['on_win_url_target_blank'] = (bool)$wheel->on_win_url_target_blank;
		
		if (empty($coupon_code))
		{
			$response['stage2_heading_text'] = nl2br($wheel->popup_lose_heading_text);
			$response['stage2_main_text'] = do_shortcode(nl2br($this->template_parser($wheel->popup_lose_main_text,$template_vars)));
			$response['on_win_url'] = $wheel->on_lose_url;
			do_action('couponwheel_user_has_lost',array('wheel'=>$wheel,'form_data'=>$form_data));
		} else {
		   
    // User has won , update wallet table
    global $wpdb;
    $user_id = get_current_user_id();
    $amount_won = strip_tags($wheel->{"slice$wheel_slice_number"."_label"});
    
    // Insert a new row in the wallet table
    $table_name = $wpdb->prefix . 'wallet';
    $data = array(
         'user_id' => $user_id,
         'amount' => $amount_won, ( 'balance'));

    $wpdb->insert($wallet, $data);     
    // Set the response message
    $response['stage2_heading_text'] = nl2br($wheel->popup_win_heading_text);
    $response['stage2_main_text'] = do_shortcode(nl2br($this->template_parser($wheel->popup_win_main_text,$template_vars)));
    $response['on_win_url'] = $wheel->on_win_url;
    do_action('couponwheel_user_has_won',array('wheel'=>$wheel,'form_data'=>$form_data));

			$response['on_win_url'] = $wheel->on_win_url;
			$response['stage2_heading_text'] = nl2br($wheel->popup_win_heading_text);
			$response['stage2_main_text'] = do_shortcode(nl2br($this->template_parser($wheel->popup_win_main_text,$template_vars)));
			$couponwheel_from_email_name = get_option('couponwheel_from_email_name');
			$couponwheel_from_email_address = get_option('couponwheel_from_email_address');
			
			if (empty($couponwheel_from_email_name)) $couponwheel_from_email_name = get_bloginfo('name');
			if (empty($couponwheel_from_email_address)) $couponwheel_from_email_address = $this->default_from_email_address();
			
			if ($wheel->require_email && $wheel->send_mail_after_spin)
			{
				$mail_headers[] = 'Content-Type: text/html; charset=UTF-8';
				$mail_headers[] = sprintf('From: %s <%s>',$couponwheel_from_email_name,$couponwheel_from_email_address);

				$mail_message = ( get_option('couponwheel_rtl') ) ? '<html><body style="direction: rtl;">' : '<html><body>';
				$mail_message .= nl2br($this->template_parser($wheel->email_win_message,$template_vars));
				$mail_message .= '<div style="font-size: .7em; margin-top: 5rem;">'.date(DATE_W3C).'</div>';
				$mail_message .= '</body></html>';
				
				wp_mail($form_data['email'],
						$wheel->email_win_subject,
						$mail_message,
						$mail_headers
						);
			}
			
			if ($wheel->notify_admin_on_win)
			{
				unset($mail_headers);
				unset($mail_message);
				
				__('Email','couponwheel');
				__('Wheel hash','couponwheel');
				__('First name','couponwheel');
				__('Last name','couponwheel');
				__('Rules checked','couponwheel');
				__('Phone number','couponwheel');
				
				foreach($form_data as $field=>$value)
				{
					$translate_string = ucfirst(strtolower(str_replace('_',' ',$field)));
					$form_data_for_admin[] = "<p>". __($translate_string,'couponwheel').": <strong>$value</strong></p>";
				}
				$form_data_for_admin[] = "<p>" . __('Slice','couponwheel') . ": <strong>" . strip_tags($wheel->{"slice$wheel_slice_number"."_label"}) . "</strong></p>";
				$form_data_for_admin[] = "<p>" . __('NA','couponwheel') . ": <strong>$coupon_code</strong></p>";
				
				
				$mail_headers[] = 'Content-Type: text/html; charset=UTF-8';
				$mail_headers[] = sprintf('From: %s <%s>',$couponwheel_from_email_name,$couponwheel_from_email_address);

				$mail_message = ( get_option('couponwheel_rtl') ) ? '<html><body style="direction: rtl;">' : '<html><body>';
				$mail_message .= implode('',$form_data_for_admin);
				$mail_message .= '<div style="font-size: .7em; margin-top: 5rem;">'.date(DATE_W3C).'</div>';
				$mail_message .= '</body></html>';
				
				$notify_admin_email = empty( $wheel->notify_admin_email ) ? get_bloginfo('admin_email') : $wheel->notify_admin_email;
				
				wp_mail($notify_admin_email,
						$this->default_host() . ' - ' . __('User has won ','couponwheel'),
						$mail_message,
						$mail_headers
						);
			}
			
			if ($this->woo && get_option('couponwheel_apply_coupon_automatically')) $woocommerce->cart->add_discount($coupon_code);
			if ($this->woo && get_option('couponwheel_apply_coupon_automatically') && (!$preview_mode)) $response['reload_page'] = true;
		
			do_action('couponwheel_user_has_won',array('wheel'=>$wheel,'form_data'=>$form_data,'coupon_code'=>$coupon_code));
		}
		
		if ($wheel->require_email && $wheel->mailchimp_enabled && !empty($wheel->mailchimp_api_key) && !empty($wheel->mailchimp_list_id))
		{
			$this->mailchimp_signup($wheel->mailchimp_api_key,
									$wheel->mailchimp_list_id,
									$form_data['email'],
									$form_data['first_name'],
									$form_data['last_name'],
									$wheel->mailchimp_double_optin
									);
		}
		
		$response['success'] = true;

		$response['notice'] = $this->couponwheel_notice($wheel,$template_vars);
		
		echo json_encode($response);
		wp_die();
	}
	
	public function send_data_to_webhook($url,$wheel_run_id,$data)
	{
		
		global $wpdb;
		
		$r = wp_remote_post($url,array(
			'body' => json_encode($data)
		));
		
		$code = 0;

		if (!is_wp_error($r)) $code = $r['response']['code'];

		$wpdb->update($this->db_run_log_table,array('webhooks_http_status'=>$code),array('id'=>$wheel_run_id));
	
	}
	
	public function popup_background_picker()
	{
		include(plugin_dir_path(__FILE__) . 'templates/popup_background_picker.php');
		wp_die();
	}
	
	public function form_hidden_input($name,$value = '')
	{
		return "<input type=\"hidden\" id=\"$name\" name=\"$name\" value=\"$value\">";
	}

	public function form_checkbox($name,$label,$checked = 0)
	{
		$checked = ((bool)$checked) ? 'checked' : '';
		return "<label class=\"input_spacing\" for=\"$name\">$label</label> <input type=\"hidden\" name=\"$name\" value=\"0\"><input type=\"checkbox\" name=\"$name\" id=\"$name\" value=\"1\" $checked>";
	}
	
	public function form_textarea_input($name,$label,$value = '')
	{
		return "<label class=\"input_spacing_textarea\" for=\"$name\">$label</label> <textarea class=\"couponwheel_customize_text_textarea\" name=\"$name\" id=\"$name\">$value</textarea>";
	}
	
	public function default_from_email_address()
	{
		return 'no-reply@' . $this->default_host();
	}
	
	public function default_host()
	{
		return wp_parse_url(get_bloginfo('url'),PHP_URL_HOST);
	}
	
	public function form_text_input($name,$label,$value = '', $additional = '')
	{
		$value = esc_html($value);
		return "<label class=\"input_spacing\" for=\"$name\">$label</label> <input type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\"> $additional";
	}

	public function form_color_picker($name,$label,$value = '')
	{
		$value = esc_html($value);
		return "<label class=\"input_spacing\" for=\"$name\">$label</label> <span style=\"display: inline-block;\"><input class=\"couponwheel_color_picker\" type=\"text\" name=\"$name\" id=\"$name\" value=\"$value\"></span>";
	}
	
	public function form_number_input($name,$label,$min = '',$max = '',$value = '', $additional = '')
	{
		$value = esc_html($value);
		return "<label class=\"input_spacing\" for=\"$name\">$label</label> <input required type=\"number\" min=\"$min\" max=\"$max\" name=\"$name\" id=\"$name\" value=\"$value\"> $additional";
	}

	public function rating_footer()
	{
		return '<span style="font-size: 1em">If you like <strong>LottoPawa2</strong> please leave us a <a target="_new" href="https://awuoro.co.ke">a> Thanks!</span><br><span style="font-style: italic">Copyright (c) '.date('Y').' Awuoro</span>';
	}
	
	public function couponwheel_load_popups()
	{
		//ajax function for loading popups
		$this->disable_page_cache();
		
		$this->clear_redundant_cookies();
		
		if ( ($this->woo) && (!$this->preview_key_in_referer()) )
		{
			global $woocommerce;
			$applied_coupons = $woocommerce->cart->get_coupons();
			if (!empty($applied_coupons)) wp_die();
		}
		
		$this->load_popups($_POST);
		wp_die();
	}
	
	public function load_popup_template($wheel,$preview_mode = false)
	{
		$plugin_dir_url = plugin_dir_url( __FILE__ );
		include(plugin_dir_path(__FILE__) . 'templates/popup.php');
	}
	
	public function preview_key_in_referer()
	{
		if (isset($_SERVER['HTTP_REFERER']))
		{
			parse_str(parse_url($_SERVER['HTTP_REFERER'],PHP_URL_QUERY), $get_array);
			if (isset($get_array['couponwheel_popup_preview_key'])) return $get_array['couponwheel_popup_preview_key'];
		}
		if (isset($_GET['couponwheel_popup_preview_key'])) return $_GET['couponwheel_popup_preview_key'];
		return false;
	}
	
	public function load_popups($post_data)
	{
		
		if ($this->preview_key_in_referer())
		{
			$wheel = $this->get_preview_wheel($this->preview_key_in_referer());
			if (!$wheel) return;
			$this->load_popup_template($wheel,true);
			return;
		}
		
		// MANUAL JS TRIGGER
		
		if (isset($post_data['wheel_hash']))
		{
			foreach($this->get_wheels() as $wheel)
			{
				if (!$wheel->is_live) continue;
				if ($wheel->wheel_hash == $post_data['wheel_hash'])
				{
					$this->load_popup_template($wheel);
					return;
				}
			}
		}

		// AUTO-LOAD POPUPS
		
		if (!isset($post_data['page_id'])) return;
		
		$page_id = $post_data['page_id'];

		foreach($this->get_wheels() as $wheel)
		{
			if (!$wheel->is_live) continue;
			if (isset($_COOKIE["couponwheel$wheel->wheel_hash"."_seen"]) && $_COOKIE["couponwheel$wheel->wheel_hash"."_seen"] == $wheel->seen_key) continue;
			
			if ($wheel->user_filter == 1 && is_user_logged_in() ) continue;
			if ($wheel->user_filter == 2 && ! is_user_logged_in() ) continue;
			
			if ( ! empty( $wheel->locale_filter ) ) {
				$locales = explode( ',', $wheel->locale_filter );
				if ( ! isset( $post_data['locale'] ) || ! in_array( $post_data['locale'], $locales) ) continue;
			}
			
			$page_filter = explode(',',$wheel->page_filter);
			if (count($page_filter) > 1)
			{
				if (isset($post_data['post_is_single'])) {
					if (in_array($page_id,$page_filter) &&
						(get_post_type($page_id) !== 'page') &&
						$post_data['post_is_single'])
					{
						$this->load_popup_template($wheel);
					}
				}
				
				if (in_array($page_id,$page_filter) &&
					(get_post_type($page_id) === 'page'))
				{
					$this->load_popup_template($wheel);
				}
				
				if ((in_array('-10',$page_filter)) &&
					(get_post_type($page_id) === 'post'))
				{
					$this->load_popup_template($wheel);
				}
				
				if ((in_array('-20',$page_filter)) &&
					(get_post_type($page_id) === 'product'))
				{
					$this->load_popup_template($wheel);
				}
				
				if ((in_array('-30',$page_filter)) &&
					!empty($post_data['order_received']))
				{
					$this->load_popup_template($wheel);
				}
				
			} else {
				$this->load_popup_template($wheel);
			}
		}
	}
	
	public function smart_loader_for_recaptcha()
	{
		$smart_loader = 0;

		foreach($this->get_wheels() as $wheel)
		{
			if (!$wheel->require_recaptcha) continue;
			$smart_loader += 1;
		}
		
		if ($smart_loader == 0) return false;	

		return true;
	}
	
	public function disable_page_cache()
	{
		defined('DONOTCACHEPAGE') or define('DONOTCACHEPAGE', true);
	}
	
	public function load_assets()
	{

		wp_enqueue_style('couponwheel', plugin_dir_url(__FILE__) . 'assets/frontend.css', array(), filemtime(plugin_dir_path(__FILE__) . 'assets/frontend.css'));
		
		if (!get_option('couponwheel_skip_google_font'))
		{
			wp_enqueue_style('couponwheel_robotofont', 'https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto:400,700,900&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
		}
		
		if ($this->smart_loader_for_recaptcha())
		{
			wp_enqueue_script('couponwheel-recaptcha','https://www.google.com/recaptcha/api.js',array(),false,true);
		}
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-effects-core');
		
		wp_enqueue_script('couponwheel', plugin_dir_url(__FILE__) . 'assets/couponwheel.js', array('jquery','couponwheel-dialog-trigger'), filemtime(plugin_dir_path(__FILE__) . 'assets/couponwheel.js'),true);
		wp_localize_script('couponwheel','couponwheel_notice_translations',array(	'h' => __('h','couponwheel'),
																					'm' =>  __('m','couponwheel'),
																					's' => __('s','couponwheel'),
																				));
		
		$ajax_url = admin_url('admin-ajax.php');
		$get_page_id = ( ! empty( $this->get_page_id() ) ) ? $this->get_page_id() : 0;
		$post_is_single = ( int ) is_single();
		$get_locale = get_user_locale();
		
		$get_order_received = 0;
		
		if ( $this->woo ) {
			$get_order_received = ( int ) ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) );
		}
		
		$get_locale = explode( '_', $get_locale )[0];
		
		$inline_vars[] = "var couponwheel_ajaxurl = '$ajax_url';";
		$inline_vars[] = "var couponwheel_page_id = '$get_page_id';";
		$inline_vars[] = "var couponwheel_post_is_single = '$post_is_single';";
		$inline_vars[] = "var couponwheel_locale = '$get_locale';";
		$inline_vars[] = "var couponwheel_order_received = '$get_order_received';";
		
		wp_add_inline_script('couponwheel',implode('',$inline_vars),'before');
		
		wp_enqueue_script('couponwheel-dialog-trigger', plugin_dir_url(__FILE__) . 'assets/dialog_trigger.js', array('jquery'), filemtime(plugin_dir_path(__FILE__) . 'assets/dialog_trigger.js'),true);
	}
	
	public function load_admin_assets()
	{
		wp_enqueue_style('couponwheel', plugin_dir_url(__FILE__) . 'assets/backend.css', array(), filemtime(plugin_dir_path(__FILE__) . 'assets/backend.css'));
		wp_enqueue_style('wp-color-picker');
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('wp-color-picker');
		
		wp_enqueue_media();
	}
	
	public function get_admin_capability()
	{
		$admin_capability = 'manage_options';
		if ( current_user_can( 'manage_woocommerce' ) ) $admin_capability = 'manage_woocommerce';
		
		return $admin_capability;
	}
	
	public function add_admin_menu()
	{
		$admin_capability = $this->get_admin_capability();
		
		add_menu_page('Coupon Wheel Dashboard', 'LottoPawa2', $admin_capability, 'couponwheel_dashboard', array($this,'dashboard'),'dashicons-admin-settings');
		add_submenu_page('couponwheel_dashboard', __('Dashboard','couponwheel'), __('Dashboard','couponwheel'), $admin_capability, 'couponwheel_dashboard', array($this,'dashboard'));
		add_submenu_page('couponwheel_dashboard', __('Global Settings','couponwheel'), __('Global Settings','couponwheel'), $admin_capability, 'couponwheel_settings', array($this,'settings'));
	}
	
	public function add_new_wheel()
	{
		global $wpdb;
		$wpdb->insert($this->db_wheel_table,array(
			'wheel_hash' => substr(md5(uniqid('',true)),0,6),
			'seen_key' => substr(md5(uniqid('',true)).'seen_key',0,6),
			'popup_background_css' => 'linear-gradient(rgb(15, 12, 41), rgb(48, 43, 99), rgb(36, 36, 62))',
			'popup_heading_text' => __('WE HAVE A BONUS FOR NEW SUBSCRIBERS','couponwheel'),
			'popup_main_text' => __('You have a chance to win . Enter your details below and try your luck on our Wheel:','couponwheel'),
			'popup_rules_text' => __("<strong>Rules</strong>\n- One spin per user\n- No cheating allowed",'couponwheel'),
			'popup_win_heading_text' => __('CONGRATULATIONS YOU HAVE WON!','couponwheel'),
			'popup_win_main_text' => __("{firstname}, here is you coupon for {slice}:\n\n{couponcode}",'couponwheel'),
			'popup_lose_heading_text' => __('OH SNAP!','couponwheel'),
			'popup_lose_main_text' => __('Try again next time','couponwheel'),
			'email_win_subject' => __('Your code','couponwheel'),
			'email_win_message' => __("{firstname}, here is you code for {slice}:\n\n{couponcode}\n\nVisit us at {siteurl}",'couponwheel'),
			'page_filter' => '0',
			
			'offers_progressbar_color' => '#9acd32',
			'popup_heading_text_color' => '#ffffff',
			'popup_main_text_color' => '#ffffff',
			'popup_background_label' => 'Lawrencium',
			'notice_text_color' => '#ffffff',
			'notice_background_color' => '#000000',
			
			'slice1_label' => __('Ksh 50,000','couponwheel'),
			'slice2_label' => __('Ksh 5,000','couponwheel'),
			'slice3_label' => __('Ksh 500','couponwheel'),
			'slice4_label' => __('Ksh 100','couponwheel'),
			'slice5_label' => __('Ksh 60','couponwheel'),
			'slice6_label' => __('Ksh 0','couponwheel'),
			
			
			'slice2_infinite' => 0,
			'slice2_infinite' => 0,
			'slice3_infinite' => 0,
			'slice4_infinite' => 0,
			'slice5_infinite' => 0,
			'slice6_infinite' => 0,
			
			'popup_preview_key' => md5(uniqid('',true)),
			'show_offers_claimed' => 1,
			'offers_claimed_text' => __('70% offers have been claimed, spin the wheel now to claim yours!','couponwheel'),
			'offers_claimed_percentage' => 70,
			'notify_admin_email' => '',
			'require_first_name' => 0,
			'manual_open' => 1,
			
			'ace_email_limit_check' => 0,
			'require_rules' => 0,
			'require_email' => 0,
			
			'lang_enter_your_email' => __('Enter your e-mail','couponwheel'),
			'lang_enter_your_first_name' => __('Enter your first name','couponwheel'),
			'lang_enter_your_last_name' => __('Enter your last name','couponwheel'),
			'lang_i_agree' => __('I agree with rules and privacy policy','couponwheel'),
			'lang_spin_button' => __('SPIN THE WHEEL','couponwheel'),
			'lang_continue_button' => __('CONTINUE','couponwheel'),
			'lang_input_missing' => __('Please fill out form completely','couponwheel'),
			'lang_no_spins' => __('No spins available','couponwheel'),
			'lang_ace_email_check' => __('Please provide valid e-mail address','couponwheel'),
			'lang_ace_limit_reached' => __('Your spin limit has been reached','couponwheel'),
			'lang_coupon_notice' => __('Your code: {couponcode} is reserved for {timer}. You can apply it at checkout.','couponwheel'),
			'lang_enter_phone_number' => __('Enter your phone number','couponwheel'),
			'lang_close' => __('Close','couponwheel'),
			'lang_days' => __('days','couponwheel'),
			'lang_spin_again' => __('Spin again','couponwheel'),
			
		));
		
		$insert_id = $wpdb->insert_id;
		$wpdb->update( $this->db_wheel_table, array( 'wheel_name' => "Wheel $insert_id" ), array( 'id' => $insert_id ) );
	}
	
	public function settings()
	{
		$expired_coupons = $this->count_expired_coupons();
		include(plugin_dir_path(__FILE__) . 'templates/settings.php');
	}
	
	public function dashboard()
	{
		
		global $wpdb;
		
		add_filter('admin_footer_text',array($this,'rating_footer'));
		add_filter('update_footer', function(){ return ''; });
		
		if (isset($_GET['action']) && ($_GET['action'] == 'configure_wheel'))
		{
			$this->configure_wheel($_GET['wheel_hash']);
			return;
		}
		
		$wheels = $this->get_wheels();
		
		foreach ($wheels as $wheel)
		{
			$wheel_totals[$wheel->id] = (object) array (
				'wheel_spins' => $wpdb->get_row("SELECT COUNT(id) AS wheel_spins FROM $this->db_run_log_table WHERE wheel_id = $wheel->id")->wheel_spins,
				'emails_collected' => $wpdb->get_row("SELECT COUNT(DISTINCT(email)) AS emails_collected FROM $this->db_run_log_table WHERE wheel_id = $wheel->id AND email <> ''")->emails_collected
			);
		}
		
		include(plugin_dir_path(__FILE__) . 'templates/dashboard.php');
	}
	
	public function reset_wheel($wheel_hash)
	{
		global $wpdb;
		$wheel = $this->get_wheel($wheel_hash);
		
		if (!$wheel) return;
		
		$wpdb->delete($this->db_run_log_table,array('wheel_id'=>$wheel->id));
		$wpdb->query("UPDATE $this->db_wheel_table SET popup_impressions = 0 WHERE id = $wheel->id");
		$this->reset_seen_wheel($wheel_hash);
	}
	
	public function reset_seen_wheel($wheel_hash)
	{
		global $wpdb;
		$wheel = $this->get_wheel($wheel_hash);
		
		if (!$wheel) return;

		$seen_key = substr(md5(uniqid('',true)).'seen_key',0,6);
		$wpdb->query("UPDATE $this->db_wheel_table SET seen_key = '$seen_key' WHERE id = $wheel->id");
	}
	
	public function clone_wheel($wheel_hash)
	{
		global $wpdb;
		$wheel = $this->get_wheel($wheel_hash);
		
		if (!$wheel) return;
		
		$wheel = (array) $wheel;
		unset($wheel['id']);
		$wheel['wheel_hash'] = substr(md5(uniqid('',true)),0,6);
		$wheel['seen_key'] = substr(md5(uniqid('',true)).'seen_key',0,6);
		$wheel['is_live'] = 0;
		$wheel['wheel_name'] = 'Cloned Wheel ' . date('Y-m-d H:i:s',time());
		$wheel['popup_impressions'] = 0;
		$wheel['popup_preview_key'] = md5(uniqid('',true));
		$wpdb->insert($this->db_wheel_table,$wheel);
	}
	
	public function delete_wheel($wheel_hash)
	{
		global $wpdb;
		
		$wheel = $this->get_wheel($wheel_hash);
		
		if (!$wheel) return;
		$wpdb->delete($this->db_wheel_table,array('wheel_hash'=>$wheel->wheel_hash));
		$wpdb->delete($this->db_run_log_table,array('wheel_id'=>$wheel->id));
	}

	
	public function settings_save()
	{
		if (!wp_verify_nonce($_POST['_wpnonce'])) wp_die();
		if(empty($_POST)) return;
		parse_str($_POST['form_data'],$form_data);
		
		foreach ($form_data as $key => $value)
		{
			update_option($key,$value);
		}
		
		echo json_encode(array('error_msg' => __('Settings saved successfully!','couponwheel'),'error_code' => '0'));
		wp_die();
	}
	
	public function configure_wheel_save()
	{
		global $wpdb;
		
		if(empty($this->raw_post)) return;
		if (!wp_verify_nonce($this->raw_post['_wpnonce'])) wp_die();
		parse_str($this->raw_post['form_data'],$form_data);

		foreach (array('couponwheel_recaptcha_site_key',
					   'couponwheel_recaptcha_secret') as $key)
		{
			update_option($key,$form_data[$key]);
			unset($form_data[$key]);
		}
		
		if (isset($form_data['page_filter'])) $form_data['page_filter'] = implode(',',$form_data['page_filter']);
		if (isset($form_data['locale_filter'])) $form_data['locale_filter'] = trim( $form_data['locale_filter'] );
		
		if (empty($form_data['mailchimp_list_id'])) $form_data['mailchimp_list_name'] = '';
		
		if($wpdb->update($this->db_wheel_table,$form_data,array('wheel_hash'=>$form_data['wheel_hash'])) === false)
		{
			$error_msg = __('Form save error. Please try again.','couponwheel');
			$error_code = 'configure_wheel_save_error';
		}

		if(!empty($error_code))
		{
			echo json_encode(array('error_msg' => $error_msg,'error_code' => $error_code));
			wp_die();
			return;
		}

		echo json_encode(array('error_msg' => __('Settings saved successfully!','couponwheel'),'error_code' => '0'));
		wp_die();
	}
	
	public function configure_wheel($wheel_hash)
	{
		$wheel = $this->get_wheel($wheel_hash);
		if (!$wheel) return;
		
		global $wpdb;
		
		if ($this->woo)
		{
			$args = array(
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'asc',
				'post_type' => 'shop_coupon',
				'post_status' => 'publish',
				'meta_query' => array(
										array(
											'key' => '_couponwheel_autogenerated',
											'value' => 'yes',
											'compare' => 'NOT EXISTS'
										)
									)
			);
				
			$all_coupons = get_posts($args);

			$coupons_autocomplete = array();
			
			foreach ($all_coupons as $coupon)
			{
				$coupons_autocomplete[] = $coupon->post_title;
			}
			
			$coupons_autocomplete = json_encode($coupons_autocomplete);
		}

		include(plugin_dir_path(__FILE__) . 'templates/configure_wheel.php');
	}
	
	public function get_wheel($wheel_hash)
	{
		global $wpdb;
		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->db_wheel_table WHERE wheel_hash = %s LIMIT 1",$wheel_hash));
		if($row === null) return false;
		return $row;
	}
	
	public function get_preview_wheel($preview_key)
	{
		global $wpdb;
		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->db_wheel_table WHERE popup_preview_key = %s LIMIT 1",$preview_key));
		if($row === null) return false;
		return $row;
	}
	
	public function get_wheels()
	{
		global $wpdb;
		$wheels = $wpdb->get_results("SELECT * FROM $this->db_wheel_table ORDER BY id DESC");
		return $wheels;
	}
	
	public function get_slice_totals($wheel_id,$slice_number)
	{
		global $wpdb;
		return $wpdb->get_row($wpdb->prepare("SELECT COUNT(wheel_id) as slice_totals FROM $this->db_run_log_table WHERE wheel_id = %d AND slice_number = %d",$wheel_id,$slice_number))->slice_totals;
	}
	
	public function html_log()
	{
		global $wpdb;
		
		$wheel = $this->get_wheel($_GET['wheel_hash']);
		if (!$wheel) return;

		$fields = explode(',','wheel_id,timestamp,email,first_name,last_name,rules_checked,phone_number,coupon_code,slice_number,slice_label,referer,ip,user_cookie');
		$limit = '';
		
		if (isset($_GET['display']))
		{
			$limit = ' LIMIT 200';
		}
		
		$query = "SELECT *, FROM_UNIXTIME(timestamp) as timestamp FROM $this->db_run_log_table WHERE wheel_id = $wheel->id ORDER BY timestamp DESC $limit";
		
		if (isset($_GET['download']))
		{
			$rows = $wpdb->get_results($query, ARRAY_A);
			header('Content-type: text/plain');
			header('Content-Disposition: attachment; filename=couponwheel_log.csv');
			echo "sep=,\n";
			echo $this->array2csv($rows);
			wp_die();
		}
		
		$rows = $wpdb->get_results($query);
		
		include(plugin_dir_path(__FILE__) . 'templates/html_log.php');
		
		wp_die();
	}

	public function array2csv(array &$array)
	{
		if (count($array) == 0) {
			return null;
		}
		ob_start();
		$df = fopen('php://output', 'w');
		fputcsv($df, array_keys(reset($array)));
		foreach ($array as $row) {
			fputcsv($df, $row);
		}
		fclose($df);
		return ob_get_clean();
	}	
	
}

new couponwheel();