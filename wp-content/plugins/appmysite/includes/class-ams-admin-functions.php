<?php
 /**
 * Plugin info.
 * @package    AppMySite
 * @author     AppMySite <support@appmysite.com>
 * @copyright  Copyright (c) 2023 - 2024, AppMySite
 * @link       https://appmysite.com
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
if ( !class_exists( 'AMS_Admin_Functions' ) ) {
		
	final class AMS_Admin_Functions{
		
		/**
		 * AMS_Admin_Scripts Constructor.
		 **/
		
		function __construct() {
			
			$this->ams_plugin_deactivation_survey();

			add_action( 'admin_menu', array( &$this, 'ams_admin_menu' ) );
			
			add_action( 'wp_ajax_ams_license_key_form_submit', array( &$this, 'ams_license_key_form_submit' ) );
			
			add_action( 'wp_ajax_ams_safe_mode_form_submit', array( &$this, 'ams_safe_mode_form_submit' ) );
			
		}
		
		function ams_plugin_deactivation_survey(){
			
			require_once untrailingslashit( dirname( AMS_PLUGIN_DIR )) . '/includes/ams-plugin-deactivation-survey.php';
				
			add_action( 'wp_ajax_ams_deactivation_form_submit', 'ams_deactivation_form_submit' );
			
		}

		// adds ams menu item to wordpress admin dashboard
		function ams_admin_menu() {
			
			add_menu_page( __( 'AppMySite Dashboard' ),
			__( 'AppMySite' ),
			'manage_options',
			'ams-home',
			array( &$this, 'ams_admin_menu_page' ),'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCI+CiAgPGRlZnM+CiAgICA8Y2xpcFBhdGggaWQ9ImNsaXAtcGF0aCI+CiAgICAgIDxyZWN0IGlkPSJSZWN0YW5nbGVfMjQ3NDEiIGRhdGEtbmFtZT0iUmVjdGFuZ2xlIDI0NzQxIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0xODM3IC0xNzMwNCkiIGZpbGw9IiNmZmYiLz4KICAgIDwvY2xpcFBhdGg+CiAgPC9kZWZzPgogIDxnIGlkPSJNYXNrX0dyb3VwXzI1MDMwIiBkYXRhLW5hbWU9Ik1hc2sgR3JvdXAgMjUwMzAiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE4MzcgMTczMDQpIiBjbGlwLXBhdGg9InVybCgjY2xpcC1wYXRoKSI+CiAgICA8cGF0aCBpZD0iVW5pb25fMjUzNyIgZGF0YS1uYW1lPSJVbmlvbiAyNTM3IiBkPSJNLjY2MiwxNC40NTNhLjY2My42NjMsMCwwLDEtLjYxMS0uOTE4TDUuNDIuNjI4QTEuMDE4LDEuMDE4LDAsMCwxLDYuMzU3LDBoMy43ODNhLjY1My42NTMsMCwwLDEsLjU4Ny4zNjRjLjAwNy4wMTQuMDEzLjAyOS4wMi4wNDRhLjY2NS42NjUsMCwwLDEsLjAyMy4wNjhsNC4wMzgsOS43MTEsMS4xLDIuNjI3YTEuMTgsMS4xOCwwLDAsMS0xLjA4NiwxLjYzNUgxMC44M2ExLjUwNSwxLjUwNSwwLDAsMS0xLjIzLS42MzdMNy42MTYsMTEuMDA3YS41Mi41MiwwLDAsMSwuNDIzLS44MkgxMC4yTDguNTQzLDYuMjA2bC0zLjIsNy43MDhhLjg3MS44NzEsMCwwLDEtLjguNTM4WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTE4MzQuOTk4IC0xNzMwMS4yMjcpIiBmaWxsPSIjZmZmIiBzdHJva2U9InJnYmEoMCwwLDAsMCkiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgc3Ryb2tlLXdpZHRoPSIxIi8+CiAgPC9nPgo8L3N2Zz4='
			);
			
		}

		function ams_admin_menu_page() {
			// Load home page
			require_once untrailingslashit( dirname( AMS_PLUGIN_DIR )) . '/includes/views/ams-home.php'; 
		}
		
		
		function ams_license_key_form_submit() { 

			if ( ! check_ajax_referer( 'ajax-nonce', 'nonce', false ) ) {
				wp_send_json_error();
				wp_die();
			}			
			$form_data   =  wp_parse_args( $_POST['form-data'] ) ; // clean
			
			$ams_license_key = sanitize_text_field($form_data['ams_license_key']);
			$site_url = esc_url( get_bloginfo( 'url' ) );
			$is_valid_ams_license_key = false;
						
			/**
			 * Make a POST request to verify the token.
			 */
			$response    = wp_remote_post(
				'https://wordpress.api.appmysite.com/api/verify-license',
				array(
					'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
					'body' => json_encode(array(
						'ams_license_key'  => $ams_license_key
					))
				)
			);

			if ( ! is_wp_error( $response ) ) {
				$response_body = wp_remote_retrieve_body( $response );
				$response_code = wp_remote_retrieve_response_code($response);
				if($response_code == 200){
					$json_response = json_decode($response_body, true);
					if(isset($json_response['is_valid'])&& $json_response['is_valid']=="yes"){		//Valid license				
						/*  Logic to modify wp-config file  */
						try {
							$config_transformer = new WPConfigTransformer( $this->get_config_path() );
							
							if ( $config_transformer->exists( 'constant', 'AMS_LICENSE_KEY' ) ) {
								// update constant
								$config_transformer->update( 'constant', 'AMS_LICENSE_KEY', $ams_license_key, array( 'normalize' => true ) ); //'raw' => true
								$config_transformer->update( 'constant', 'AMS_LICENSE_STATUS', 'Verified', array( 'normalize' => true ) );
							}
							else{
								// add constant
								$config_transformer->update( 'constant', 'AMS_LICENSE_KEY', $ams_license_key, array( 'normalize' => true ) ); //'raw' => true
								$config_transformer->update( 'constant', 'AMS_LICENSE_STATUS', 'Verified', array( 'normalize' => true ) );
							}
							
							wp_send_json_success(
								array(
									'is_valid' => "yes",
									'msg' =>'License key saved successfully.'
								)
							);
							wp_die();
							
						} catch ( \Exception $e ) {
							$messsage = 'Unable to update AMS_LICENSE_KEY in wp-config. ' . $e->getMessage();
							
							wp_send_json_success(
								array(
									'is_valid' => "no",
									'msg'	=> $messsage
								)
							);
							wp_die();
						}
						
					}else{	//Invalid license
						try {
							$config_transformer = new WPConfigTransformer( $this->get_config_path() );
							
							// update constant
							$config_transformer->update( 'constant', 'AMS_LICENSE_KEY', $ams_license_key, array( 'normalize' => true ) ); //'raw' => true
							$config_transformer->update( 'constant', 'AMS_LICENSE_STATUS', 'Unverified', array( 'normalize' => true ) );
							
							wp_send_json_success(
									array(
										'is_valid' => "no",
										'msg' =>'Invalid license key.'
									)
								);
							wp_die();
							
						} catch ( \Exception $e ) {
							$messsage = 'Unable to update AMS_LICENSE_KEY in wp-config. ' . $e->getMessage();
							
							wp_send_json_success(
								array(
									'is_valid' => "no",
									'msg'	=> $messsage
								)
							);
							wp_die();
						}
						
					}
					
				}else{
					wp_send_json_success(
						array(
							'is_valid' => "no",
							'msg' =>'Could not verify license with AppMySite.'.$response_body.''
						)
					);
					wp_die();
				}
			} else {
				wp_send_json_success(
					array(
						'is_valid' => "no",
						'msg' =>$response->get_error_message()
					)
				);
				wp_die();
			}
			
		}

		function ams_safe_mode_form_submit() { 

			if ( ! check_ajax_referer( 'ajax-nonce', 'nonce', false ) ) {
				wp_send_json_error();
				wp_die();
			}			
			$form_data   =  wp_parse_args( $_POST['form-data'] ) ; // clean
			
			$ams_safe_mode = sanitize_text_field($form_data['ams_safe_mode']);
			
			/* Check if twenty * is installed */
			$if_twenty_theme_is_installed = false;

				// Check if twenty * is installed, and if so, activate it.
				$themes = wp_get_themes();
				
				if(array_key_exists('twentytwentyfour', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentytwentythree', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentytwentytwo', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentytwentyone', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentytwenty', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentynineteen', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentyeighteen', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentyseventeen', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentysixteen', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentyfifteen', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentyfourteen', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentythirteen', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentytwelve', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentyeleven', $themes))
					$if_twenty_theme_is_installed = true;
				else if(array_key_exists('twentyten', $themes))
					$if_twenty_theme_is_installed = true;
			
			if(!$if_twenty_theme_is_installed){
				wp_send_json_error(
					array(
						'ams_safe_mode' => AMS_SAFE_MODE,
						'msg'	=> "No default WordPress theme found on your website. Please install at least one default theme to enable safe mode."
					)
				);
				wp_die();
			}
			
			/*  Logic to modify wp-config file  */
			try {
				$config_transformer = new WPConfigTransformer( $this->get_config_path() );
				
				// update constant
					$config_transformer->update( 'constant', 'AMS_SAFE_MODE', $ams_safe_mode, array( 'normalize' => true ) ); //'raw' => true
				
				if($ams_safe_mode=='on'){
					wp_send_json_success(
						array(
							'ams_safe_mode' => $ams_safe_mode,
							'msg' =>'Safe mode has been activated successfully. We recommend not leaving it on for an extended period.'
						)
					);
					wp_die();
					
				}else{
					wp_send_json_success(
						array(
							'ams_safe_mode' => $ams_safe_mode,
							'msg' =>'Safe mode has been deactivated successfully.'
						)
					);
					wp_die();
				}
				
			} catch ( \Exception $e ) {
				$messsage = 'Unable to update AMS_SAFE_MODE in wp-config.  ' . $e->getMessage();
				
				wp_send_json_error(
					array(
						'ams_safe_mode' => AMS_SAFE_MODE,
						'msg'	=> $messsage
					)
				);
				wp_die();
			}

							
		}

		
		private function get_config_path() {
			$config_path = ABSPATH . 'wp-config.php';

			if ( ! file_exists( $config_path ) ) {
				if ( @file_exists( dirname( ABSPATH ) . '/wp-config.php' ) && ! @file_exists( dirname( ABSPATH ) . '/wp-settings.php' ) ) {
					$config_path = dirname( ABSPATH ) . '/wp-config.php';
				}
			}

			return apply_filters( 'wp_debugging_config_path', $config_path );
		}	
	}

}

