<?php


class Hustle_Settings_Admin_Ajax {
	private $_hustle;

	private $_admin;

	public function __construct( Opt_In $hustle, Hustle_Settings_Admin $admin ) {
		$this->_hustle = $hustle;
		$this->_admin = $admin;

		add_action("wp_ajax_hustle_remove_ips", array( $this, "remove_ip_from_tracking_data" ));
		add_action("wp_ajax_hustle_toggle_module_for_user", array( $this, "toggle_module_for_user" ));
		add_action("wp_ajax_hustle_save_global_email_settings", array( $this, "save_global_email_settings" ));
		add_action("wp_ajax_hustle_toggle_unsubscribe_messages_settings", array( $this, "toggle_unsubscription_custom_messages" ));
		add_action("wp_ajax_hustle_save_unsubscribe_messages_settings", array( $this, "save_unsubscription_messages" ));
		add_action("wp_ajax_hustle_save_unsubscribe_email_settings", array( $this, "save_unsubscription_email" ));
		add_action("wp_ajax_hustle_toggle_unsubscribe_email_settings", array( $this, "toggle_unsubscription_custom_email" ));
		// This is used in wizards. Should be moved into popup-admin-ajax instead, since there's where common ajax actions from wizards are.
		add_action("wp_ajax_hustle_shortcode_render", array( $this, "shortcode_render" ));
		/**
		 * Save reCAPTCHA options
		 *
		 * @since 3.0.7
		 */
		add_action( 'wp_ajax_hustle_save_global_recaptcha_settings', array( $this, 'save_recaptcha' ) );
	}

	/**
	 * Remove the requested IPs from views and conversions on batches.
	 *
	 * @since 3.0.6
	 */
	public function remove_ip_from_tracking_data() {
		Opt_In_Utils::validate_ajax_call("hustle_remove_ips");
		$module = Hustle_Module_Model::instance();

		// Define the transient name.
		$transient = 'hustle_removing_ip_data';

		// Get this request offset.
		$offset = absint( filter_input( INPUT_POST, 'offset', FILTER_SANITIZE_NUMBER_INT ) );

		// Amount of database entries checked by request.
		$increment = 50;

		if ( 0 === $offset ) {
			// Starting the first batch.
			// Set the array of the provided IPs, the meta_id list of all existing entries,
			// and the amount of entries we'll be checking to match the IPs so we know when to stop.

			// Make sure the transient is not already set.
			delete_transient( $transient );

			// Get a meta_id array containing all views and conversions entries.
			$id_array = $module->get_all_views_and_conversions_meta_id();
			$total = count( $id_array );

			// Get the string containing all the ips to be removed.
			$ip_string = filter_input( INPUT_POST, 'delete_ip', FILTER_SANITIZE_STRING );

			// Create an array with their values.
			$ip_array = preg_split('/[\s,]+/', $ip_string, null, PREG_SPLIT_NO_EMPTY );

			// Remove from the array the IPs that are not valid IPs.
			foreach( $ip_array as $key => $ip ) {
				if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
					unset( $ip_array[ $key ] );
					continue;
				}
			}

			// Limit the number of IPs.
			$ip_array = array_slice( $ip_array, 0, apply_filters( 'hustle_remove_selected_ips_from_tracking_limit', 10, $ip_array, $id_array ) );

			$api = new Opt_In_WPMUDEV_API();
			$salt = $api->get_nonce_value();

			$data_to_save = array(
				'total' => $total,
				'ip_array' => $ip_array,
				'id_array' => $id_array,
				'salt' => $salt,
			);
			set_transient( $transient, $data_to_save );

		} else {
			// If it's not the first batch, retrieve the values already stored on the first batch.
			$saved_data = get_transient( $transient );
			$total = absint( $saved_data['total'] );
			$ip_array = $saved_data['ip_array'];
			$id_array = $saved_data['id_array'];
			$salt = $saved_data['salt'];

		}

		// Retrieve the amount of rows updated on the previous batches
		// to retrieve it if we're done, or keep adding rows otherwise.
		$updated_rows = filter_input( INPUT_POST, 'updated', FILTER_SANITIZE_NUMBER_INT );

		// Slice the array to get the current batch.
		$batch = array_slice( $id_array, $offset, $increment );

		// If the batch is empty, or the offset is greater than the amount of metas,
		// delete the transient and finish the loop.
		if ( $offset > $total || empty( $batch ) ) {
			delete_transient( $transient );
			wp_send_json_success( array(
				'offset' => 'done',
				'updated' => $updated_rows,
			) );

		} else {
			// Process this batch of metas.
			// Get the meta_id and meta_value from this batch that matches the passed IPs.
			$metas = $module->get_metas_for_matching_meta_values_in_a_range( $batch, $ip_array );

			foreach( $metas as $key => $value ) {
				// Update the IP of this meta_value and save it again.
				$stored_value = json_decode( $value['meta_value'], true );

				if ( isset( $stored_value['ip'] ) && in_array( $stored_value['ip'], $ip_array, true ) ) {
					$stored_ip = $stored_value['ip'];
					$stored_value['ip'] = md5( $salt . $stored_ip );
					$updated = $module->update_any_meta( $value['meta_id'], $stored_value );

					if ( $updated ) {
						// Increase the updated_rows number to display in front at the end of the process.
						$updated_rows++;
					}

				}
			}

			// Increment the offset to run the next batch.
			$offset += $increment;
			$response = array(
				'offset' => $offset,
				'updated' => $updated_rows,
			);
			wp_send_json_success( $response );

		}
	}

	public function toggle_module_for_user(){
		Opt_In_Utils::validate_ajax_call("hustle_modules_toggle");

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$user_type = filter_input( INPUT_POST, 'user_type', FILTER_SANITIZE_STRING );

		$module = Hustle_Module_Model::instance()->get( $id );

		$result = $module->toggle_activity_for_user( $user_type );

		if( is_wp_error( $result ) )
			wp_send_json_error( $result->get_error_messages() );

		wp_send_json_success( sprintf( __("Successfully toggled for user type %s", Opt_In::TEXT_DOMAIN), $user_type ) );
	}

	/**
	 * Toggles if the customized unsubscription messages are enabled.
	 *
	 * @since 3.0.5
	 */
	public function toggle_unsubscription_custom_messages() {
		Opt_In_Utils::validate_ajax_call("hustle_save_unsubscribe_messages_settings");
		$enabled = filter_input( INPUT_POST, 'enabled', FILTER_SANITIZE_STRING );

		$saved = get_option( 'hustle_global_unsubscription_settings', array() );

		if ( isset( $saved['messages'] ) && ! empty( $saved['messages'] ) ) {
			$saved['messages']['enabled'] = 'false' === $enabled ? '0' : '1';
			update_option( 'hustle_global_unsubscription_settings', $saved );
		}
		wp_send_json_success();
	}

	/**
	 * Saves the global messages to show along the unsubscription process.
	 *
	 * @since 3.0.5
	 */
	public function save_unsubscription_messages() {
		Opt_In_Utils::validate_ajax_call("hustle_save_unsubscribe_messages_settings");
		parse_str( $_POST['data'], $data ); // WPCS: CSRF ok.
		if ( get_magic_quotes_gpc() ) {
			$data = stripslashes_deep( $data );
		}
		$sanitized_data = Opt_In_Utils::validate_and_sanitize_fields( $data );

		$data_to_save = array(
			'enabled' => $sanitized_data['enabled'],
			'get_lists_button_text' => $sanitized_data['get_lists_button_text'],
			'submit_button_text' => $sanitized_data['submit_button_text'],
			'invalid_email'=> $sanitized_data['invalid_email'],
			'email_not_found' => $sanitized_data['email_not_found'],
			'invalid_data' => $sanitized_data['invalid_data'],
			'email_submitted' => $sanitized_data['email_submitted'],
			'successful_unsubscription' => $sanitized_data['successful_unsubscription'],
			'email_not_processed' => $sanitized_data['email_not_processed'],
		);

		$saved = get_option( 'hustle_global_unsubscription_settings', array() );
		$saved['messages'] = $data_to_save;

		update_option( 'hustle_global_unsubscription_settings', $saved );
		wp_send_json_success();
	}

	/**
	 * Toggles if the customized unsubscription email is enabled.
	 *
	 * @since 3.0.5
	 */
	public function toggle_unsubscription_custom_email() {
		Opt_In_Utils::validate_ajax_call("hustle_save_unsubscribe_email_settings");
		$enabled = filter_input( INPUT_POST, 'enabled', FILTER_SANITIZE_STRING );

		$saved = get_option( 'hustle_global_unsubscription_settings', array() );

		if ( isset( $saved['email'] ) && ! empty( $saved['email'] ) ) {
			$saved['email']['enabled'] = 'false' === $enabled ? '0' : '1';
			update_option( 'hustle_global_unsubscription_settings', $saved );
		}

		wp_send_json_success();
	}

	/**
	 * Saves the global settings for subject and body for the unsubscription email.
	 *
	 * @since 3.0.5
	 */
	public function save_unsubscription_email() {
		Opt_In_Utils::validate_ajax_call("hustle_save_unsubscribe_email_settings");
		parse_str( $_POST['data'], $data ); // WPCS: CSRF ok.
		if ( get_magic_quotes_gpc() ) {
			$data = stripslashes_deep( $data );
		}
		$enabled = filter_var( $data['enabled'], FILTER_SANITIZE_STRING );
		$email_subject = filter_var( $data['email_subject'], FILTER_SANITIZE_STRING );
		$email_body = wp_json_encode( $data['email_message'] );

		$data_to_save = array(
			'enabled' => $enabled,
			'email_subject' => $email_subject,
			'email_body' => $email_body,
		);

		$saved = get_option( 'hustle_global_unsubscription_settings', array() );
		$saved['email'] = $data_to_save;

		update_option( 'hustle_global_unsubscription_settings', $saved );
		wp_send_json_success();
	}

	/**
	 * Saves the global email sender name and email address.
	 *
	 * @since 3.0.5
	 */
	public function save_global_email_settings() {
		Opt_In_Utils::validate_ajax_call("hustle_save_global_email_settings");
		parse_str( $_POST['data'], $data ); // WPCS: CSRF ok.

		$name = filter_var( $data['name'], FILTER_SANITIZE_STRING );
		$email = filter_var( $data['email'], FILTER_SANITIZE_EMAIL );
		$email_settings = array(
			'sender_email_name' => $name,
			'sender_email_address' => $email,
		);

		update_option( 'hustle_global_email_settings', $email_settings );

		wp_send_json_success();
	}

	public function shortcode_render() {
		Opt_In_Utils::validate_ajax_call("hustle_shortcode_render");

		$content = filter_input( INPUT_POST, 'content' );
		$rendered_content = apply_filters( 'the_content', $content );

		wp_send_json_success( array(
			"content" => $rendered_content
		));
	}

	/**
	 * Save reCAPTCHA options
	 *
	 * @since 3.0.7
	 */
	public function save_recaptcha() {
		Opt_In_Utils::validate_ajax_call('hustle_save_global_recaptcha_settings');
		parse_str( $_POST['data'], $data ); // WPCS: CSRF ok.
		$enabled = isset( $data['enabled'] ) ? filter_var( $data['enabled'], FILTER_SANITIZE_STRING ) : '0';
		$sitekey = filter_var( $data['sitekey'], FILTER_SANITIZE_STRING );
		$secret = filter_var( $data['secret'], FILTER_SANITIZE_STRING );
		$data_to_save = array(
			'enabled' => $enabled,
			'sitekey' => $sitekey,
			'secret' => $secret,
		);
		$settings = get_option( 'hustle_settings', array() );
		$settings['recaptcha'] = $data_to_save;
		update_option( 'hustle_settings', $settings );
		wp_send_json_success();
	}

}
