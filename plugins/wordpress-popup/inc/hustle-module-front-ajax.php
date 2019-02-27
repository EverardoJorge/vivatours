<?php

class Hustle_Module_Front_Ajax {

	private $_hustle;

	public function __construct( Opt_In $hustle ){
		$this->_hustle = $hustle;
		// When module is viewed
		add_action("wp_ajax_module_viewed", array( $this, "module_viewed" ));
		add_action("wp_ajax_nopriv_module_viewed", array( $this, "module_viewed" ));

		// When module form is submitted
		add_action("wp_ajax_module_form_submit", array( $this, "submit_form" ));
		add_action("wp_ajax_nopriv_module_form_submit", array( $this, "submit_form" ));

		// Update the number of shares of each network
		add_action("wp_ajax_update_network_shares", array( $this, 'update_network_shares' ));
		add_action("wp_ajax_nopriv_update_network_shares", array( $this, 'update_network_shares' ));

		// When cta is clicked
		add_action("wp_ajax_hustle_cta_converted", array( $this, "log_cta_conversion" ) );
		add_action("wp_ajax_nopriv_hustle_cta_converted", array( $this, "log_cta_conversion" ) );

		// When SShare is converted to
		add_action("wp_ajax_hustle_sshare_converted", array( $this, "log_sshare_conversion" ) );
		add_action("wp_ajax_nopriv_hustle_sshare_converted", array( $this, "log_sshare_conversion" ) );

		// Handles unsubscribe form submisisons
		add_action("wp_ajax_hustle_unsubscribe_form_submission", array( $this, "unsubscribe_submit_form" ));
		add_action("wp_ajax_nopriv_hustle_unsubscribe_form_submission", array( $this, "unsubscribe_submit_form" ));
	}


	public function submit_form(){
		$data = $_POST['data']; // WPCS: CSRF ok.
		parse_str( $data['form'], $form_data );

		if( !is_email( $form_data['email'] ) )
			wp_send_json_error( __("Invalid email address", Opt_In::TEXT_DOMAIN) );

		$module = Hustle_Module_Model::instance()->get( $data['module_id'] );
		$module_content = $module->get_content();

		//check GDPR
		$show_gdpr = $module_content->show_gdpr;
		if ( !empty( $show_gdpr ) && empty( $data['gdpr'] ) ) {
			wp_send_json_error( __( 'You have to agree to our terms and conditions.', Opt_In::TEXT_DOMAIN ) );
		}

		$form_elements = is_array( $module_content->form_elements ) ? $module_content->form_elements : json_decode( $module_content->form_elements );
		$recaptcha_settings = Hustle_Module_Model::get_recaptcha_settings();
		$recaptcha_secret = isset( $recaptcha_settings['secret'] ) && !empty( $recaptcha_settings['enabled'] ) ? $recaptcha_settings['secret'] : '';

		if ( $recaptcha_secret && key_exists( 'recaptcha', $form_elements ) ) {
			if ( empty( $data['recaptcha'] ) ) {
				$failed = true;
			} else {
				# Verify captcha
				$response = wp_remote_get( add_query_arg( array(
					'secret'   => $recaptcha_secret,
					'response' => $data['recaptcha'],
					'remoteip' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']
				), 'https://www.google.com/recaptcha/api/siteverify' ) );

				$json = !empty( $response['body'] ) ? json_decode( $response['body'] ) : '';
				if( is_wp_error( $response ) || ! $json  || ! $json->success ) {
					$failed = true;
				}
			}

			if ( !empty( $failed ) ) {
				wp_send_json_error( __( 'reCAPTCHA validation failed. Please try again.', Opt_In::TEXT_DOMAIN ) );
			}
		}

		$module_type = $data['type'];
		$provider = false;
		$api_result = false;
		$local_saved = false;
		$is_save_to_local = (bool) $module->content->save_local_list;
		$is_test_mode = (bool) $module->test_mode;
		$active_email_service = $module->content->active_email_service;
		$has_active_email_service = (bool) $active_email_service;
		$activable_providers = $this->_hustle->get_providers();

		// If the selected provider is not available, disable it, enable local list, and log errors.
		if ( $has_active_email_service && ! isset( $activable_providers[ $active_email_service ] ) ) {
			$is_save_to_local = true;
			$has_active_email_service = false;
			$error_data = $form_data;
			$error_data['error'] = sprintf( __( 'This conversion was stored in your module\'s local list because the selected provider (%s) is not available. ', Opt_In::TEXT_DOMAIN ), $active_email_service );
			$module->log_error( $error_data );
		}

		if( $has_active_email_service ){

			$provider = Opt_In_Utils::get_provider_by_slug( $module->content->active_email_service );

			if( !is_subclass_of( $provider, "Hustle_Provider_Abstract") && !$is_test_mode )
			   wp_send_json_error( __("Invalid provider", Opt_In::TEXT_DOMAIN) );

		}

		if( $is_save_to_local && !$is_test_mode && !$provider){ // Save to local collection
			$local_subscription_data = wp_parse_args( $form_data, array(
				'module_type' => $module_type,
				'time' => current_time( 'timestamp' ),
			) );

			$local_saved = $module->add_local_subscription( $local_subscription_data );

			if ( is_wp_error( $local_saved ) ) {
				// Send the error back
				wp_send_json_error( $local_saved->get_error_messages() );
			}
		}

		if ( $local_saved && !$has_active_email_service ) {
			// if no provider and was able to save it locally
			$this->log_conversion($module, $data);
			wp_send_json_success( $local_saved );
		}

		if( $provider ) {
			$form_data = apply_filters( 'hustle_form_data_before_subscription', $form_data, $module, $provider );
			$api_result = $provider->subscribe( $module, $form_data );
		}


		if( ( $api_result && !is_wp_error( $api_result ) ) && ( !$local_saved || !is_wp_error( $local_saved ) )  ){
			$this->log_conversion($module, $data);

			if($is_save_to_local){
				$local_subscription_data = wp_parse_args( $form_data, array(
					'module_type' => $module_type,
					'time' => current_time( 'timestamp' ),
				) );

				$local_saved = $module->add_local_subscription( $local_subscription_data );

				if ( is_wp_error( $local_saved ) ) {
					// Send the error back
					wp_send_json_error( $local_saved->get_error_messages() );
				}
			}

			$message = $api_result ? $api_result : $local_saved;
			wp_send_json_success( $message );
		}

		$collected_errs_messages = array();
		if( is_wp_error( $api_result )  )
			$collected_errs_messages = $api_result->get_error_messages();

		if( is_wp_error( $local_saved )  ) {
			$collected_errs_messages = array_merge( $collected_errs_messages, $local_saved->get_error_messages() );
		}

		if( array() !== $collected_errs_messages  ){
			wp_send_json_error( $collected_errs_messages);
		}

		wp_send_json_error( $api_result );
	}

	/**
	 * Handles the unsubscribe form submission.
	 *
	 * @since 3.0.5
	 */
	public function unsubscribe_submit_form() {

		parse_str( $_POST['data'], $submitted_data ); // WPCS: CSRF ok.
		$sanitized_data = Opt_In_Utils::validate_and_sanitize_fields( $submitted_data );
		$messages = Hustle_Module_Model::get_unsubscribe_messages();

		// Check if we got the email address and if it's valid.
		if ( isset( $sanitized_data['email'] ) && filter_var( $sanitized_data['email'], FILTER_VALIDATE_EMAIL ) ) {

			$email = $sanitized_data['email'];

			// Handle 'choose_list' form step
			if ( isset( $sanitized_data['form_step'] ) && 'choose_list' === $sanitized_data['form_step'] ) {

				// If the lists are defined, submit the email with the nonce.
				if ( isset( $sanitized_data['lists_id'] ) && ! empty( $sanitized_data['lists_id'] ) && isset( $sanitized_data['current_url'] ) ) {

					// Do the process to send the unsubscription email.
					$email_processed = Hustle_Mail::handle_unsubscription_user_email( $email, $sanitized_data['lists_id'], $sanitized_data['current_url'] );

					if ( $email_processed ) {

						$html = $messages['email_submitted'];
						$wrapper = '.hustle-form-body';

						$response = array(
							'html' => apply_filters( 'hustle_unsubscribe_email_processed_html', $html, $sanitized_data ),
							'wrapper' => apply_filters( 'hustle_unsubscribe_email_processed_wrapper', $wrapper, $sanitized_data ),
						);
						wp_send_json_success( $response );

					}
				}

				$html = $messages['email_not_processed'];
				apply_filters( 'hustle_unsubscribe_email_not_processed_html', $html, $sanitized_data );
				wp_send_json_error( array( 'html' => $html ) );

			} elseif ( isset( $sanitized_data['form_step'] ) && 'enter_email' === $sanitized_data['form_step'] ) {

				// The lists are not defined yet. Show the list for the user to select them.
				$module = Hustle_Module_Model::instance();
				$modules_id = $module->get_modules_id_by_email_in_local_list( $email );

				// If not showing all, show only the ones defined in the shortcode.
				if ( '-1' !== $sanitized_data['form_module_id'] && ! empty( $sanitized_data['form_module_id'] ) ) {
					$form_modules_id = array_map( 'trim', explode( ',', $submitted_data['form_module_id'] ) );
					$modules_id = array_intersect( $form_modules_id, $modules_id );
				}

				// If the email is not in any of the selected lists.
				if ( empty( $modules_id ) ) {

					$html = $messages['email_not_found'];
					$wrapper = '.hustle-form-body';

					$response = array(
						'html' => apply_filters( 'hustle_unsubscribe_email_not_found_html', $html, $modules_id, $email ),
						'wrapper' => apply_filters( 'hustle_unsubscribe_email_not_found_wrapper', $wrapper, $modules_id, $email ),
					);
					wp_send_json_success( $response );
				}

				$params = array(
					'ajax_step' => true,
					'modules_id' => $modules_id,
					'module' => $module,
					'email' => $sanitized_data['email'],
					'current_url' => $sanitized_data['current_url'],
					'messages' => $messages,
					);
				$html = $this->_hustle->render( 'general/unsubscribe-form', $params, true );
				$wrapper = '.hustle-form-body';

				$response = array(
					'html' => apply_filters( 'hustle_render_unsubscribe_lists_html', $html, $modules_id, $email ),
					'wrapper' => apply_filters( 'hustle_render_unsubscribe_list_wrapper', $wrapper, $modules_id, $email ),
				);
				wp_send_json_success( $response );
			}
		} else {
			// Return an error if the email is missing or is invalid.
			$html =  $messages['invalid_email'];
			apply_filters( 'hustle_unsubscribe_invalid_email_address_message', $html, $sanitized_data );
			wp_send_json_error( array( 'html' => $html ) );
		}

		wp_send_json_success( $sanitized_data );
	}

	/**
	 * Update the number of shares
	 */
	public function update_network_shares() {
		$post_id = filter_input( INPUT_POST, 'page_id', FILTER_VALIDATE_INT );

		if ( !$post_id ) {
			wp_send_json_success();
		}
		$modules = Hustle_Module_Collection::instance()->get_all(true);
		$modules = apply_filters( 'hustle_sort_modules', $modules );
		foreach( $modules as $module ) {
			if ( 'social_sharing' === $module->module_type ) {
				$data = array(
					'content' => $module->get_sshare_content()->to_array(),
					'design' => $module->get_sshare_design()->to_array(),
					'settings' => $module->get_sshare_display_settings()->to_array(),
					'tracking_types' => $module->get_tracking_types(),
					'test_types' => $module->get_test_types()
				);
				if( $module->is_click_counter_type_enabled( 'native' ) && !empty( $data['content']['social_icons'] )
						&& ! $module->check_if_use_stored( $post_id, true ) ) {
					$module->retrieve_network_shares( $post_id );
				}
			}
		}

		wp_send_json_success();
	}

	public function log_cta_conversion(){
		$data = json_decode( file_get_contents( 'php://input' ) );
		$data = get_object_vars( $data );

		$module_id = is_array( $data ) ? $data['module_id'] : null;

		if( empty( $module_id ) )
			wp_send_json_error( __("Invalid Request!", Opt_In::TEXT_DOMAIN ) . $module_id );

		$module = Hustle_Module_Model::instance()->get( $module_id );

		$res = new WP_Error();
		if ( $module->id ) {
			$res = $this->log_conversion($module, $data);
		}

		if( is_wp_error( $res ) || empty( $data ) )
			wp_send_json_error( __("Error saving stats", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_success( __("Stats Successfully saved", Opt_In::TEXT_DOMAIN) );
	}

	public function log_sshare_conversion(){
		$data = json_decode( file_get_contents( 'php://input' ) );
		$data = get_object_vars( $data );

		$module_id = is_array( $data ) ? $data['module_id'] : null;
		$type = is_array( $data ) ? $data['type'] : null;
		$track = is_array( $data ) ? (bool) $data['track'] : false;
		$source = is_array( $data ) ? $data['source'] : '';
		$service_type = is_array( $data ) ? $data['service_type'] : false;

		if( empty( $module_id ) )
			wp_send_json_error( __("Invalid Request: Invalid Social Sharing ", Opt_In::TEXT_DOMAIN ) . $module_id );

		$ss = Hustle_SShare_Model::instance()->get( $module_id );

		// only update the social counter for Native Social Sharing
		if( $service_type && 'native' === $service_type && $source ) {
			$social = str_replace( '_icon', '', $source );
			$services_content = $ss->get_sshare_content()->to_array();

			if( isset($services_content['social_icons']) && isset($services_content['social_icons'][$social]) ) {
				$social_data = $services_content['social_icons'][$social];
				$social_data['counter'] = ( (int) $social_data['counter'] ) + 1;
				$services_content['social_icons'][$social] = $social_data;
				$ss->update_meta( $this->_hustle->get_const_var( "KEY_CONTENT", $ss ), $services_content );
			}
		}

		$res = new WP_Error();
		if( $ss->id && $track )
			$res = $ss->log_conversion( array(
				'page_type' => $data['page_type'],
				'page_id'   => $data['page_id'],
				'module_id' => $ss->id,
				'uri' => $data['uri'],
				'module_type' => 'social_sharing',
				'source' => $data['source']
			), $type );

			// update meta for social sharing share stats
			$ss->log_share_stats($data['page_id']);

		if( is_wp_error( $res ) || empty( $data ) )
			wp_send_json_error( __("Error saving stats", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_success( __("Stats Successfully saved", Opt_In::TEXT_DOMAIN) );
	}

	public function module_viewed(){
		$data = json_decode( file_get_contents( 'php://input' ) );
		$data = get_object_vars( $data );

		$module_id = is_array( $data ) ?  $data['module_id'] : null;
		$module_type = is_array( $data ) ?  $data['module_type'] : null;
		$display_type = is_array( $data ) ?  $data['type'] : null;

		if( empty( $module_id ) )
			wp_send_json_error( __("Invalid Request: Module id invalid") );

		$module = Hustle_Module_Model::instance()->get( $module_id );

		$res = new WP_Error();

		if( $module->id )
			$res = $module->log_view( array(
				'page_type' => $data['page_type'],
				'page_id'   => $data['page_id'],
				'module_id' => $module_id,
				'uri' => $data['uri'],
				'module_type' => $module_type
			), $display_type );

		if( is_wp_error( $res ) || empty( $data ) )
			wp_send_json_error( __("Error saving stats", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_success( __("Stats Successfully saved", Opt_In::TEXT_DOMAIN) );

	}

	public function log_conversion( $module, $data ) {
		$module_type = ( isset( $data['type'] ) ) ? $data['type'] : '';
		$tracking_types = $module->get_tracking_types();
		if ( $tracking_types && ( (bool) $tracking_types[$module_type] ) ) {
			$module->log_conversion( array(
				'page_type' => $data['page_type'],
				'page_id'   => $data['page_id'],
				'module_id' => $module->id
			), $module_type );
		}
	}
}
