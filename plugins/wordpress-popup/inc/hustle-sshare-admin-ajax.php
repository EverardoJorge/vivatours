<?php

if ( !class_exists('Hustle_SShare_Admin_Ajax', false) ):

class Hustle_SShare_Admin_Ajax {

	private static $_hustle;
	private static $_admin;

	public function __construct( $hustle, $admin ) {

		self::$_hustle = $hustle;
		self::$_admin = $admin;

		add_action( 'wp_ajax_hustle_save_sshare_module', array( $this, 'save' ) );
		add_action('wp_ajax_hustle_sshare_module_toggle_state', array( $this, 'toggle_module_state' ));
		add_action('wp_ajax_hustle_sshare_module_toggle_type_state', array( $this, 'toggle_module_type_state' ));
		add_action('wp_ajax_hustle_sshare_toggle_tracking_activity', array( $this, 'toggle_tracking_activity' ));
		add_action('wp_ajax_hustle_sshare_toggle_test_activity', array( $this, 'toggle_test_activity' ));
		add_action('wp_ajax_hustle_sshare_delete', array( $this, 'delete' ));
		/**
		 * Duplicate Social Share module
		 *
		 * @since 3.0.5
		 */
		add_action( 'wp_ajax_hustle_social_share_duplicate', array( $this, 'duplicate' ) );
	}

	public function save() {
		Opt_In_Utils::validate_ajax_call( "hustle_save_sshare_module" );

		$_POST = stripslashes_deep( $_POST );

		if( "-1" === $_POST['id']  )
			$res = self::$_admin->save_new( $_POST );
		else
			$res = self::$_admin->update_module( $_POST );

		if( 'native' === $_POST['content']['click_counter'] && $res ) {
			Hustle_SShare_Model::refresh_all_counters();
		}

		wp_send_json( array(
			"success" => false !== $res,
			"data" => $res
		) );
	}

	public function toggle_module_state(){

		Opt_In_Utils::validate_ajax_call( "sshare_module_toggle_state" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );

		if( !$id )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$result = Hustle_Module_Model::instance()->get($id)->toggle_state();

		if( $result )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( __("Failed", Opt_In::TEXT_DOMAIN) );
	}

	public function toggle_module_type_state(){

		Opt_In_Utils::validate_ajax_call( "sshare_toggle_module_type_state" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );
		$enabled = trim( filter_input( INPUT_POST, 'enabled', FILTER_SANITIZE_STRING ) );

		if( !$id || !$type )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$sshare =  Hustle_SShare_Model::instance()->get($id);

		if( !in_array( $type, Hustle_SShare_Model::get_types(), true ))
			wp_send_json_error( sprintf( __("Invalid environment: %s", Opt_In::TEXT_DOMAIN ), $type ) );

		$settings = $sshare->get_sshare_display_settings()->to_array();
		$test_types = (array) json_decode( $sshare->get_meta( self::$_hustle->get_const_var( "TEST_TYPES", $sshare ) ) );

		if ( isset( $settings[ $type . '_enabled' ] ) ) {
			$settings[ $type . '_enabled' ] = $enabled;
			try {
				// Clear cache.
				$sshare->clean_module_cache();

				// try to save new settings
				$sshare->update_meta( self::$_hustle->get_const_var( "KEY_SETTINGS", $sshare ), $settings );

				if ( isset( $test_types[$type] ) ) {
					// clear test types
					unset($test_types[$type]);
				}
				$sshare->update_meta( self::$_hustle->get_const_var( "TEST_TYPES", $sshare ), $test_types );

				wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );

			} catch (Exception $e) {
				wp_send_json_error( __("Failed", Opt_In::TEXT_DOMAIN) );
			}
		} else {
			wp_send_json_error( __("Failed", Opt_In::TEXT_DOMAIN) );
		}
	}

	public function toggle_tracking_activity(){

		Opt_In_Utils::validate_ajax_call( "sshare_toggle_tracking_activity" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );

		if( !$id || !$type )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$ss =  Hustle_SShare_Model::instance()->get($id);

		if( !in_array( $type, Hustle_SShare_Model::get_types(), true ))
			wp_send_json_error( sprintf( __("Invalid environment: %s", Opt_In::TEXT_DOMAIN), $type ));

		$result = $ss->toggle_type_track_mode( $type );

		if( $result && !is_wp_error( $result ) )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( $result->get_error_message() );
	}

	public function toggle_test_activity(){
		Opt_In_Utils::validate_ajax_call( "sshare_toggle_test_activity" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );

		if( !$id || !$type )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$ss =  Hustle_SShare_Model::instance()->get($id);

		if( !in_array( $type, Hustle_SShare_Model::get_types(), true ))
			wp_send_json_error( sprintf( __("Invalid environment: %s", Opt_In::TEXT_DOMAIN), $type ) );

		$result = $ss->toggle_type_test_mode( $type );

		if( $result && !is_wp_error( $result ) )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( $result->get_error_message() );
	}

	public function delete(){
		Opt_In_Utils::validate_ajax_call( "social-sharing-delete" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );

		if( !$id  )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$result = Hustle_SShare_Model::instance()->get( $id )->delete();

		if( $result )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( __("Error deleting", Opt_In::TEXT_DOMAIN)  );
	}

	/**
	 * Duplicate Social Share Module
	 *
	 * @since 3.0.5
	 */
	public function duplicate(){
		Opt_In_Utils::validate_ajax_call( 'duplicate_social_share' );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );
		if( ! $id || ! $type ) {
			wp_send_json_error(__('Invalid Request', Opt_In::TEXT_DOMAIN));
		}
		$sshare = Hustle_SShare_Model::instance()->get( $id );
		if( $sshare->module_type !== $type && in_array( $type, array( 'social_sharing' ), true ) ) {
			wp_send_json_error( __( 'Invalid environment: %s', Opt_In::TEXT_DOMAIN ), $type );
		}
		
		// Prevent having more than 3 modules when it's free version.
		$total_sshares = count(Hustle_Module_Collection::instance()->get_all( null, array( 'module_type' => 'social_sharing' ) ));
		if ( Opt_In_Utils::_is_free() && $total_sshares >= 3 ) {
			wp_send_json_error( array( 'requires_pro' => true ) );
		}

		/**
		 * get data, need it, $sshare is a singleton
		 */
		$content = $sshare->get_content()->to_array();
		$design = $sshare->get_design()->to_array();
		$settings = $sshare->get_display_settings()->to_array();
		$shortcode_id = $sshare->get_shortcode_id();
		/**
		 * create new one
		 */
		unset( $sshare->id );
		/**
		 * rename
		 */
		$sshare->module_name .= __( ' (copy)', Opt_In::TEXT_DOMAIN );
		$content['module_name'] = $sshare->module_name;
		/**
		 * turn status off
		 */
		$sshare->active = 0;
		$sshare->test_mode = 0;
		/**
		 * save
		 */
		$result = $sshare->save();
		if ( $result && !is_wp_error( $result ) ) {
			$sshare->add_meta( self::$_hustle->get_const_var( 'KEY_CONTENT', $sshare ), $content );
			$sshare->add_meta( self::$_hustle->get_const_var( 'KEY_DESIGN', $sshare ), $design );
			$sshare->add_meta( self::$_hustle->get_const_var( 'KEY_SETTINGS', $sshare ), $settings );
			$sshare->add_meta( self::$_hustle->get_const_var( 'KEY_SHORTCODE_ID', $sshare ),  $shortcode_id );
			/**
			 * success
			 */
			wp_send_json_success( __( 'Successful', Opt_In::TEXT_DOMAIN ) );
		}
		/**
		 * Failed
		 */
		wp_send_json_error( $result->get_error_message() );
	}
}

endif;
