<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

class couponwheel_updater {
	
	private $config;
	//public key to verify update server
	private $public_key = 's4H8A96OdHjNuGbVAPHgOG2J2wmVSAm7aQtRsAOEt9s=';
	
	public function __construct( $config ) {
		$this->config = ( object ) $config;
		$this->config->slug = basename($this->config->plugin,'.php');
		$this->config->timeout = $this->config->option . '_timeout';
		
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'push_update' ), 20 );
		add_filter( 'pre_set_transient_update_plugins', array( $this, 'push_update' ), 20 );

		add_filter( 'plugins_api' , array( $this, 'plugins_api' ), 20, 3 );
	}
	
	public function verify_update_server()
	{
		if ( ! function_exists( 'sodium_crypto_sign_verify_detached') ) return false;
		$verification_token = sha1( uniqid( '' , true ) );
		$response = wp_remote_get( $this->config->url . '?verification_token=' . $verification_token);
		$response = wp_remote_retrieve_body ( $response );
		$signature = base64_decode( $response );
		try {
			return sodium_crypto_sign_verify_detached( $signature , $verification_token, base64_decode( $this->public_key ) );
		} catch( Exception $e ) {
			return false;
		}
	}
	
	public function option_expired() {
		return ( time() > get_option( $this->config->timeout, 0 ) );
	}
	
	public function pull_update() {
		if ( $this->option_expired() ) {
			delete_option( $this->config->option );
			delete_option( $this->config->timeout );
		}
		
		$response = get_option( $this->config->option );
		
		if ( $response === false ) {
			update_option( $this->config->timeout, time() + 86400);
			
			if ( $this->verify_update_server() === true ) {			
				$response = wp_remote_post( $this->config->url, array(
					'method' => 'POST',
					'body' => json_encode( array(
									'slug' => $this->config->slug,
									'version' => $this->config->version
									) )
				));
				
				$response = wp_remote_retrieve_body ( $response );
				$response = json_decode( $response );
			} else {
				$response = '';
			}
			
			update_option( $this->config->option, $response );
		}
		
		return $response;
	}
	
	public function get_plugin_information() {
		$response = $this->pull_update();

		if	( ( ! is_null( $response ) ) && isset( $response->plugin_information ) ) {
			$response->plugin_information->sections = ( array ) $response->plugin_information->sections;
			return $response->plugin_information;
		}
		
		return false;
	}
	
	public function push_update( $plugins ) {
		if ( isset( $plugins->response[$this->config->plugin] ) ) unset( $plugins->response[$this->config->plugin] );
		
		if ( defined( 'DISALLOW_FILE_MODS' ) && true === DISALLOW_FILE_MODS ) return $plugins;
		if ( ( isset( $this->config->push_update ) ) && ( ! $this->config->push_update ) ) return $plugins;

		$plugin_information = $this->get_plugin_information();
		
		if ( $plugin_information !== false ) {
			if ( version_compare( $this->config->version , $plugin_information->new_version , '<' ) ) {
				$plugin_information->plugin = $this->config->plugin;
				$plugins->response[$this->config->plugin] = $plugin_information;
			}
		}
		
		return $plugins;
	}
	
	public function plugins_api( $response, $action, $args ) {
		if ( 'plugin_information' === $action && isset( $args->slug ) ) {
			
			if ( $this->config->slug === $args->slug ) {
				$plugin_information = $this->get_plugin_information();
				if ( $plugin_information !== false ) return $plugin_information;
				return false;
			}
			
		}
		
		return $response;
	}
	
}