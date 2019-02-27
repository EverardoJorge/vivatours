<?php

/**
 * Class Hustle_Module_Model
 *
 * @property Hustle_Module_Decorator $decorated
 */

class Hustle_Module_Model extends Hustle_Model {

	const POPUP_MODULE = 'popup';
	const SLIDEIN_MODULE = 'slidein';
	const WIDGET_MODULE = 'widget';
	const SHORTCODE_MODULE = 'shortcode';
	const EMBEDDED_MODULE = 'embedded';
	const SOCIAL_SHARING_MODULE = 'social_sharing';
	const SUBSCRIPTION              = "subscription";
	const ERROR_LOG = "error_logs"; //phpcs:ignore

	/**
	 * @var $_provider_details object
	 */
	private $_provider_details;

	public static function instance(){
		return new self();
	}

	public static function get_embedded_types() {
		return array( 'after_content', 'widget', 'shortcode' );
	}

	/**
	 * Decorates current model
	 *
	 * @return Hustle_Module_Decorator
	 */
	public function get_decorated(){

		if( !$this->_decorator )
			$this->_decorator = new Hustle_Module_Decorator( $this );

		return $this->_decorator;
	}

	/**
	 * Content Model based upon module type.
	 *
	 * @return Class
	 */
	public function get_content( $type = 'popup' ) {
		$data = $this->get_settings_meta( self::KEY_CONTENT, '{}', true );
      // If redirect url is set then esc it.
		if ( isset( $data['redirect_url'] ) ) {
			$data['redirect_url'] = esc_url( $data['redirect_url'] );
		}

		switch ( $type ) {
			case 'popup':
				return new Hustle_Popup_Content( $data, $this );
			case 'slidein':
				return new Hustle_Slidein_Content( $data, $this );
			case 'embedded':
				return new Hustle_Embedded_Content( $data, $this );
		}
	}

	public function get_design() {
		return new Hustle_Popup_Design( $this->get_settings_meta( self::KEY_DESIGN, '{}', true ), $this );
	}

	public function get_display_settings() {
		return new Hustle_Popup_Settings( $this->get_settings_meta( self::KEY_SETTINGS, '{}', true ), $this );
	}

	public function get_shortcode_id() {
		return $this->get_meta( self::KEY_SHORTCODE_ID );
	}

	public function get_custom_field( $key, $value ) {
		$custom_fields = $this->get_content()->__get( 'form_elements' );

		foreach ( $custom_fields as $field ) {
			if ( isset( $field[ $key ] ) && $value == $field[ $key ] ) {
				return $field;
			}
		}
	}

	public function is_embedded_type_active($type) {
		$settings = $this->get_display_settings()->to_array();
		if ( isset( $settings[ $type . '_enabled' ] ) && in_array( $settings[ $type . '_enabled' ], array( 'true', true ), true ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the module's data. Used to display it.
	 *
	 * @since 3.0.7
	 *
	 * @param bool is_preview
	 * @return array
	 */
	public function get_module_data_to_display( $is_preview = false ) {

		if ( 'social_sharing' === $this->module_type ) {
			global $post;

			$data = array(
				'content' => $this->get_sshare_content()->to_array(),
				'design' => $this->get_sshare_design()->to_array(),
				'settings' => $this->get_sshare_display_settings()->to_array(),
				'tracking_types' => $this->get_tracking_types(),
				'test_types' => $this->get_test_types()
			);

			$data = wp_parse_args( $this->get_data(), $data );

			if( ! $is_preview && $this->is_click_counter_type_enabled( 'native' ) && is_object( $post ) ) {
				$data = $this->set_network_shares( $data, $post->ID );
			}

			// backwards compatibility for new counter types from 3.0.3
			if ( isset( $data['content']['click_counter'] ) ) {
				if ( '1' === $data['content']['click_counter'] ) {
					$data['content']['click_counter'] = 'click';
				} elseif ( '0' === $data['content']['click_counter'] ) {
					$data['content']['click_counter'] = 'none';
				}
			}
		} else {

			$data = array(
				'content' => $this->get_content()->to_array(),
				'design' => $this->get_design()->to_array(),
				'settings' => $this->get_display_settings()->to_array(),
				'tracking_types' => $this->get_tracking_types(),
				'test_types' => $this->get_test_types()
			);

			$data = wp_parse_args( $this->get_data(), $data );

			if ( isset( $data['content']['main_content'] ) && ! empty( $data['content']['main_content'] ) ) {
				$data['content']['main_content'] = do_shortcode( $data['content']['main_content'] );
			}

			// handle provider args
			if ( isset( $data['content']['active_email_service'] ) ) {
				$provider = Opt_In_Utils::get_provider_by_slug( $data['content']['active_email_service'] );
				if( is_callable( array( $provider, 'get_args' ) ) ) {
					$data['content']['args'] = $provider->get_args( $data['content'] );
					if ( ! empty( $data['content']['args'] ) ) {
						$data['content']['args']['module_id'] = $data['module_id'];
					}
				}
			}

			// remove provider credentials
			if ( isset( $data['content']['email_services'] ) ) {
				unset($data['content']['email_services']);
			}

		}

		return $data;
	}

	/**
	 * Save log to DB for every failed subscription.
	 *
	 * @param (array) $data			Submitted field data.
	 **/
	public function log_error( $data ) {
		$data = wp_parse_args( array( 'date' => date( 'Y-m-d' ) ), $data );
		$this->add_meta( self::ERROR_LOG, wp_json_encode( $data ) );
	}

	/**
	 * Returns total error count
	 *
	 * @return int
	 */
	public function get_total_log_errors(){
		return (int) $this->_wpdb->get_var( $this->_wpdb->prepare( "SELECT COUNT(meta_id) FROM " . $this->get_meta_table() . " WHERE `module_id`=%d AND `meta_key`=%s AND `meta_value` != '' ", $this->id, self::ERROR_LOG )  );
	}

	/**
	 * Retrieve logs
	 **/
	public function get_error_log() {
		return array_map( "json_decode", $this->_wpdb->get_col( $this->_wpdb->prepare( "SELECT `meta_value` FROM " . $this->get_meta_table()  . " WHERE `meta_key`=%s AND `module_id`=%d AND `meta_value` != '' ",
			self::ERROR_LOG,
			$this->id
		)));
	}

	/**
	 * Clear error logs.
	 **/
	public function clear_error_log() {
		$this->_wpdb->query( $this->_wpdb->prepare( "DELETE FROM " . $this->get_meta_table() . " WHERE `meta_key`=%s AND `module_id`=%d", self::ERROR_LOG, $this->id ) );
	}

	/**
	 * Adds new subscription to the local collection
	 *
	 * @since 1.1.0
	 * @param array $data
	 * @return bool
	 */
	public function add_local_subscription( array $data ){
		if( !$this->has_subscribed( $data['email'] ) )
			return $this->add_meta( self::SUBSCRIPTION, wp_json_encode( $data ) );

		return new WP_Error("email_already_added", __("This email address has already subscribed.", Opt_In::TEXT_DOMAIN));
	}

	/**
	 * Returns an array with the IDs of the modules to which the given email is subscribed to the local list.
	 *
	 * @since 3.0.5
	 *
	 * @param string $email
	 * @return array
	 */
	public function get_modules_id_by_email_in_local_list( $email ){
		$email_like = '%"' . $email .'"%';
		$sql = $this->_wpdb->prepare( "SELECT `module_id` FROM " . $this->get_meta_table() . " WHERE `meta_key`=%s AND `meta_value`  LIKE %s ", self::SUBSCRIPTION, $email_like  );
		return $this->_wpdb->get_col( $sql );
	}

	public function has_subscribed( $email ){
		$email_like = '%"' . $email .'"%';
		$sql = $this->_wpdb->prepare( "SELECT `meta_id` FROM " . $this->get_meta_table() . " WHERE `module_id`=%d AND `meta_key`=%s AND `meta_value`  LIKE %s ", $this->id, self::SUBSCRIPTION, $email_like  );
		return $this->_wpdb->get_var( $sql );
	}


	/**
	 * Removes the given email from the local list of the given module id.
	 *
	 * @since 3.0.5
	 *
	 * @param string $email
	 * @param int $module_id
	 * @return array
	 */
	public function remove_local_subscription_by_email_and_module_id( $email, $module_id ) {
		$email_like = '%"' . $email .'"%';
		$sql = $this->_wpdb->prepare( "SELECT `meta_id` FROM " . $this->get_meta_table() . " WHERE `module_id`=%d AND `meta_key`=%s AND `meta_value`  LIKE %s ", $module_id, self::SUBSCRIPTION, $email_like  );
		$meta_id = $this->_wpdb->get_var( $sql );
		return $this->_wpdb->delete( $this->get_meta_table(), array( 'meta_id' => $meta_id ), array( '%d' ) );
	}

	/**
	 * Returns locally collected subscriptions saved to the local collection
	 *
	 * @return array
	 */
	public function get_local_subscriptions(){

		return array_map( "json_decode", $this->_wpdb->get_col( $this->_wpdb->prepare( "SELECT `meta_value` FROM " . $this->get_meta_table()  . " WHERE `meta_key`=%s AND `module_id`=%d AND `meta_value` != '' ",
			self::SUBSCRIPTION,
			$this->id
		)));
	}

	/**
	 * Returns total conversion count
	 *
	 * @return int
	 */
	public function get_total_subscriptions(){
		return (int) $this->_wpdb->get_var( $this->_wpdb->prepare( "SELECT COUNT(meta_id) FROM " . $this->get_meta_table() . " WHERE `module_id`=%d AND `meta_key`=%s AND `meta_value` != '' ", $this->id, self::SUBSCRIPTION )  );
	}

	/**
	 * Checks if this module is allowed to be displayed
	 *
	 * @return bool
	 */
	public function is_allowed_to_display( $settings, $type ) {

		// if Disabled for current user type or test mode, do not display
		if (
			// If disabled.
			!$this->get_display()
			// If test status and not admin.
			|| ($this->is_test_type_active($type) && !current_user_can('administrator'))
		) {
			return false;
		}
		// If no conditions are set, display.
		if ( !isset( $settings['conditions'] ) || empty( $settings['conditions'] ) ) {
			// If 404 page and no conditions, do not display.
			if (is_404()) return false;
			// Otherwise display.
			return true;
		}

		global $post;
		$conditions = $settings['conditions'];
		$skip_all_cpt = false;
		$display = true;

		// If not 404 page, remove 404 condition.
		// Functionality has been changed so this condition only affects 404 pages.
		if ( !is_404() ) {
			// Unset "not found" condition so it displays on other pages.
			unset($conditions['only_on_not_found']);
			// If conditions are now empty, display module.
			if (empty($conditions)) {
				return true;
			}
		} else {
			// Prevent categories condition from overriding 404 page condition.
			unset($conditions['categories']);
		}

		// If this is a single page or home page is posts.
		if ( is_singular() || (is_home() && is_front_page())) {
			// unset not needed post_type
			if ( isset($post->post_type) && 'post' === $post->post_type ) {
				unset($conditions['pages']);
				$skip_all_cpt = true;
			} elseif ( isset($post->post_type) && 'page' === $post->post_type ) {
				unset($conditions['posts']);
				unset($conditions['categories']);
				unset($conditions['tags']);
				$skip_all_cpt = true;
			} else {
				// unset posts and pages since this is CPT
				unset($conditions['posts']);
				unset($conditions['pages']);
				if ( empty( $conditions ) ) {
					$display = false;
				}
			}
		} else {
			if( class_exists('woocommerce') ) {
				if ( is_shop() ){
					//unset the same from pages since shop should be treated as page
					unset($conditions['posts']);
					unset($conditions['categories']);
					unset($conditions['tags']);
					$skip_all_cpt = true;
				}
			} else {
				// unset posts and pages
				unset($conditions['posts']);
				unset($conditions['pages']);
				$skip_all_cpt = true;
			}
			// unset not needed taxonomy
			if ( is_category() ) {
				unset($conditions['tags']);
			}
			if ( is_tag() ) {
				unset($conditions['categories']);
			}
		}

		// $display is TRUE if all conditions were met
		foreach ($conditions as $condition_key => $args) {
			// only cpt have 'post_type' and 'post_type_label' properties
			if ( is_array($args) && isset($args['post_type']) && isset($args['post_type_label']) ) {

				// skip ms_invoice
				if ( 'ms_invoice' === $args['post_type'] ) {
					continue;
				}

				// handle ms_membership
				if ( !in_array( $args['post_type'], array( 'ms_membership', 'ms_membership-n' ), true )
						&& ( $skip_all_cpt || (isset($post->post_type) && $post->post_type !== $args['post_type'] )) ) {
					continue;
				}

				$condition = Hustle_Condition_Factory::build('cpt', $args);

			} else {
				$condition = Hustle_Condition_Factory::build($condition_key, $args);
			}
			if ( $condition ) {
				$condition->set_type($type);
				$display = ( $display && $condition->is_allowed($this) );
			}
		}

		return $display;
	}

	/**
	 * Returns array of active conditions objects
	 *
	 * @param $type
	 * @return array
	 */
	public function get_obj_conditions( $settings ){
		$conditions = array();
		// defaults
		$_conditions = array(
			'posts' => array(),
			'pages' => array(),
			'categories' => array(),
			'tags' => array()
		);

		if ( !isset( $settings['conditions'] ) ) {
			return $conditions;
		}

		$_conditions = wp_parse_args( $settings['conditions'], $_conditions );

		if ( isset($_conditions['scalar']) ) {
			unset($_conditions['scalar']);
		}

		if( !empty( $_conditions ) ){
			foreach( $_conditions as $condition_key => $args ){
				// only cpt have 'post_type' and 'post_type_label' properties
				if ( is_array($args) && isset($args['post_type']) && isset($args['post_type_label']) ) {
					$conditions[$condition_key] = Hustle_Condition_Factory::build( 'cpt', $args );
				} else {
					$conditions[$condition_key] = Hustle_Condition_Factory::build( $condition_key, $args );
				}
				if( $conditions[$condition_key] ) $conditions[$condition_key]->set_type( $this->module_type );
			}
		}

		return $conditions;
	}

	/**
	 * Check allowed type for import/export
	 *
	 * @param string $type
	 * @param string $origin_type
	 * @return bool
	 */
	public static function import_export_check_type( $type, $origin_type ) {
		$allowed_types = array(
			self::POPUP_MODULE,
			self::SLIDEIN_MODULE,
			self::EMBEDDED_MODULE,
			self::SOCIAL_SHARING_MODULE,
		);
		return $origin_type !== $type || !in_array( $type, $allowed_types, true );
	}

	// These methods from below are not directly associated with a specific module at the moment.
	// If per module settings are not implemented in 4.0, it would be best if we move these to a class handling global actions instead.

	/**
	 * Creates and store the nonce used to validate email unsubscriptions.
	 *
	 * @since 3.0.5
	 * @param string $email Email to be unsubscribed.
	 * @param array $lists_id IDs of the modules to which it will be unsubscribed.
	 * @return boolean
	 */
	public function create_unsubscribe_nonce( $email, array $lists_id ) {
		// Since we're supporting php 5.2, random_bytes or other strong rng are not available. So using this instead.
		$nonce = hash_hmac( 'md5', $email, wp_rand() . time() );

		$data = get_option( self::KEY_UNSUBSCRIBE_NONCES, array() );

		// If the email already created a nonce and didn't use it, replace its data.
		$data[ $email ] = array(
			'nonce' => $nonce,
			'lists_id' => $lists_id,
			'date_created' => time(),
		);

		$updated = update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );
		if ( $updated ) {
			return $nonce;
		} else {
			return false;
		}
	}

	/**
	 * Does the actual email unsubscription.
	 *
	 * @since 3.0.5
	 * @param string $email Email to be unsubscribed.
	 * @param string $nonce Nonce associated with the email for the unsubscription.
	 * @return boolean
	 */
	public function unsubscribe_email( $email, $nonce ) {
		$data = get_option( self::KEY_UNSUBSCRIBE_NONCES, false );
		if ( ! $data ) {
			return false;
		}
		if ( ! isset( $data[ $email ] ) || ! isset( $data[ $email ]['nonce'] ) || ! isset( $data[ $email ]['lists_id'] ) ) {
			return false;
		}
		$email_data = $data[ $email ];
		if ( ! hash_equals( (string) $email_data['nonce'], $nonce ) ) {
			return false;
		}
		// Nonce expired. Remove it. Currently giving 1 day of life span.
		if ( ( time() - (int) $email_data['date_created'] ) > DAY_IN_SECONDS ) {
			unset( $data[ $email ] );
			update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );
			return false;
		}

		// Proceed to unsubscribe
		foreach( $email_data['lists_id'] as $id ) {
			$unsubscribed = $this->remove_local_subscription_by_email_and_module_id( $email, $id );
		}

		// The email was unsubscribed and the nonce was used. Remove it from the saved list.
		unset( $data[ $email ] );
		update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );

		return true;

	}

	/**
	 * Gets the saved or default global unsubscription messages.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_unsubscribe_messages() {

		$unsubscription_settings = get_option( 'hustle_global_unsubscription_settings', array() );

		// Use customized unsubscribe messages if they're set, and if it's enabled (for frontend), or is_admin() (for settings page)
		$saved_messages = isset( $unsubscription_settings['messages'] ) && ( '0' !== (string) $unsubscription_settings['messages']['enabled'] || is_admin() ) ?
			$unsubscription_settings['messages'] : array();

		if ( ! empty( $saved_messages ) ) {
			$unsubscription_messages = stripslashes_deep( $saved_messages );
		} else {
			// Default unsubscription messages
			$unsubscription_messages = array(
				"enabled" => "0",
				"get_lists_button_text" => __( "Get Lists", Opt_In::TEXT_DOMAIN ),
				"submit_button_text" => __( "Unsubscribe!", Opt_In::TEXT_DOMAIN ),
				"invalid_email" => __( "Please enter a valid email address.", Opt_In::TEXT_DOMAIN ),
				"email_not_found" => __( "Looks like you're not in our list!", Opt_In::TEXT_DOMAIN ),
				"invalid_data" => __( "The unsubscription data doesn't seem to be correct.", Opt_In::TEXT_DOMAIN ),
				"email_submitted" => __( "Please check your email to confirm your unsubscription.", Opt_In::TEXT_DOMAIN ),
				"successful_unsubscription" => __( "You've been successfully unsubscribed.", Opt_In::TEXT_DOMAIN ),
				"email_not_processed" => __( "Something went wrong submitting the email. Please make sure a list is selected.", Opt_In::TEXT_DOMAIN ),
			);
		}


		return apply_filters( 'hustle_get_unsubscribe_messages', $unsubscription_messages );
	}

	/**
	 * Gets the saved or default global unsubscription email settings.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_unsubscribe_email_settings() {

		$settings = get_option( 'hustle_global_unsubscription_settings', array() );

		// Use customized unsubscribe email messages if they're set, and if it's enabled (for frontend), or is_admin() (for settings page)
		$saved_settings = isset( $settings['email'] ) && ( '0' !== (string) $settings['email']['enabled'] || is_admin() ) ?
			$settings['email'] : array();

		if ( ! empty( $saved_settings ) ) {
			$saved_settings['email_body'] = json_decode( $saved_settings['email_body'] );
			$email_settings =  stripslashes_deep( $saved_settings );
		} else {
			$default_email_body = sprintf(
				__( 'We are sorry to see you go! %1$s Click on the link below to unsubscribe: %2$s %3$sUnsubscribe.%4$s', Opt_In::TEXT_DOMAIN ),
				'<br>', '<br>', '<a href="{hustle_unsubscribe_link}">', '</a>'
			);

			$email_settings = array(
				'enabled' => '0',
				'email_subject' => __( 'Unsubscribe', Opt_In::TEXT_DOMAIN ),
				'email_body' => wp_json_encode( $default_email_body ),
			);
		}

		return apply_filters( 'hustle_get_unsubscribe_email', $email_settings );
	}

	/**
	 * Gets the saved or default global email settings.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_email_settings() {

		$default_email_settings = array(
			'sender_email_name' => get_bloginfo( 'name' ),
			'sender_email_address' => get_option( 'admin_email', '' ),
		);
		$saved_email_settings = array_filter( get_option( 'hustle_global_email_settings', array() ), 'strlen');
		$email_settings = wp_parse_args( $saved_email_settings, $default_email_settings );

		return apply_filters( 'hustle_get_email_settings', $email_settings );
	}

	/**
	 * Gets the saved or default global reCaptcha settings.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_recaptcha_settings() {
		$default = array(
			'enabled' => '0',
			'sitekey' => '',
			'secret' => '',
		);
		$saved_settings = get_option( 'hustle_settings', array() );
		$saved_recaptcha = !empty( $saved_settings['recaptcha'] ) ? $saved_settings['recaptcha'] : array();
		$recaptcha_settings = wp_parse_args( $saved_recaptcha, $default );
		return apply_filters( 'hustle_get_recaptcha_settings', $recaptcha_settings );
	}

}
