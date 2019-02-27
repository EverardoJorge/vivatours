<?php
if( !class_exists("Hustle_Slidein_Admin_Ajax") ):
/**
 * Class Hustle_Slidein_Admin_Ajax
 * Takes care of all the ajax calls to admin pages
 *
 */
class Hustle_Slidein_Admin_Ajax {

	private $_hustle;
	private $_admin;

	public function __construct( Opt_In $hustle, Hustle_Slidein_Admin $admin ){

		$this->_hustle = $hustle;
		$this->_admin = $admin;

		add_action("wp_ajax_hustle_save_slidein_module", array( $this, "save_slidein" ));
		add_action("wp_ajax_hustle_slidein_prepare_custom_css", array( $this, "prepare_custom_css" ));
		add_action("wp_ajax_hustle_slidein_module_toggle_state", array( $this, "toggle_module_state" ));
		add_action("wp_ajax_hustle_slidein_module_toggle_tracking_activity", array( $this, "toggle_tracking_activity" ));
		add_action("wp_ajax_hustle_slidein_toggle_test_activity", array( $this, "toggle_test_activity" ));
		/**
		 * Duplicate SlideIn module
		 *
		 * @since 3.0.6
		 */
		add_action( 'wp_ajax_hustle_slidein_duplicate', array( $this, 'duplicate' ) );
	}

	/**
	 * Prepares the custom css string for the live previewer
	 *
	 * @since 1.0
	 */
	public function prepare_custom_css(){

		Opt_In_Utils::validate_ajax_call( "hustle_module_prepare_custom_css" );

		$_POST = stripslashes_deep( $_POST );
		if( !isset($_POST['css'] ) ) {
			wp_send_json_error();
		}

		$cssString = $_POST['css'];

		$styles = Opt_In::prepare_css($cssString, "");

		wp_send_json_success( $styles );
	}

	/**
	 * Checks if e-Newsletter should be synced with current local collection
	 *
	 * @since 3.0
	 *
	 * @return true|false
	 */
	public function check_enews_sync(){

		//do sync if e-Newsletter plugin is active, e-Newsletter is the active provider,
		//and if the plugin was deactivated or e-Newsletter wasn't the active provider before
		if( 'e_newsletter' === $_POST['content']['active_email_service'] && class_exists( 'Email_Newsletter' ) ) {

			if( !isset($_POST['content']['email_services']['e_newsletter']['synced']) || '0' === $_POST['content']['email_services']['e_newsletter']['synced'] ){
				$_POST['content']['email_services']['e_newsletter']['synced'] = 1;
				return true;
			}
			return false;

		} else {

			$_POST['content']['email_services']['e_newsletter']['synced'] = 0;
			return false;
		}
	}

	/**
	 * Does the actual sync with the current local collection and e-Newsletter
	 * It's only called when check_enews_sync method returns true
	 *
	 * @since 3.0
	 *
	 * @var int $id
	 */
	public function do_sync( $id ){
		$provider = Opt_In_Utils::get_provider_by_slug( $_POST['content']['active_email_service'] );
		$module = Hustle_Module_Model::instance()->get( $id );
		$lists = isset($_POST['content']['email_services']['e_newsletter']['list_id']) ? $_POST['content']['email_services']['e_newsletter']['list_id'] : array();
		$provider->sync_with_current_local_collection( $module, $lists );
	}

	/**
	 * Saves new optin to db
	 *
	 * @since 1.0
	 */
	public function save_slidein(){

		Opt_In_Utils::validate_ajax_call( "hustle_save_slidein_module" );

		$_POST = stripslashes_deep( $_POST );

		//check if e-Newsletter sync should be done and set new "Synced" value
		if( isset($_POST['content']['email_services']['e_newsletter']) ){
			$do_sync = $this->check_enews_sync();
		}

		if( "-1" === $_POST['id']  )
			$res = $this->_admin->save_new( $_POST );
		else
			$res = $this->_admin->update_module( $_POST );

		//do sync with e-Newsletter after saving because we need the ID
		if( isset($do_sync) && $do_sync ) {
			$this->do_sync( $res );
		}

		wp_send_json( array(
			"success" =>  false === $res ? false: true,
			"data" => $res
		) );
	}


	/**
	 * Toggles optin active state
	 *
	 * @since 1.0
	 */
	public function toggle_module_state(){

		Opt_In_Utils::validate_ajax_call( "slidein_module_toggle_state" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$enabled = trim( filter_input( INPUT_POST, 'enabled', FILTER_SANITIZE_STRING ) );
		$enabled = in_array( $enabled, array( 'true', true ), true );

		if( !$id )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$module = Hustle_Module_Model::instance()->get($id);
		$current_state = (bool) $module->active;

		if ( $enabled !== $current_state ) {
			$result = $module->toggle_state();
		} else {
			$result = true; // all is well
		}

		// Disable test_mode if enabled
		if ( 1 === (int)$module->test_mode ) {
			$module->change_test_mode( false );
		}

		if( $result )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( __("Failed", Opt_In::TEXT_DOMAIN) );
	}

	public function toggle_tracking_activity(){
		Opt_In_Utils::validate_ajax_call( "slidein_toggle_tracking_activity" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );

		if( !$id || !$type )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$module =  Hustle_Module_Model::instance()->get($id);

		if( 'slidein' !== $type )
			wp_send_json_error( sprintf( __("Invalid environment: %s", Opt_In::TEXT_DOMAIN), $type ) );

		$result = $module->toggle_type_track_mode( $type );

		if( $result && !is_wp_error( $result ) )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( $result->get_error_message() );
	}

	/**
	 * Toggles optin type test mode
	 *
	 * @since 1.0
	 */
	public function toggle_test_activity(){

		Opt_In_Utils::validate_ajax_call( "slidein_toggle_test_activity" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );

		if( !$id || !$type )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$module =  Hustle_Module_Model::instance()->get($id);

		$result = $module->change_test_mode( true );

		if ( $result && !is_wp_error( $result ) ) {
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		} else {
			if ( is_wp_error( $result ) ) {
				$message = $result->get_error_message();
			} else {
				$message = false === $result ? 'There was an error updating.' : 'No updated rows.';
			}
			wp_send_json_error( $message );
		}
	}

	/**
	 * Delete optin
	 */
	public function delete_module(){

		Opt_In_Utils::validate_ajax_call( "hustle_delete_module" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );

		if( !$id )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$result = Hustle_Module_Model::instance()->get($id)->delete();

		if( $result )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( __("Failed", Opt_In::TEXT_DOMAIN) );
	}

	/**
	 * Retrieves the subscription list from db
	 *
	 *
	 * @since 1.1.0
	 */
	public function get_subscriptions_list(){
		Opt_In_Utils::validate_ajax_call("hustle_get_emails_list");

		$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );

		if( !$id )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$subscriptions = Hustle_Module_Model::instance()->get($id)->get_local_subscriptions();

		if( $subscriptions )
			wp_send_json_success( array(
				"subscriptions" => $subscriptions,
				'module_fields'=> json_decode(Hustle_Module_Model::instance()->get($id)->get_content()->__get( 'form_elements' )),
			) );
		else
			wp_send_json_error( __("Failed to fetch subscriptions", Opt_In::TEXT_DOMAIN) );
	}

	/**
	 * Save persistent choice of closing new welcome notice on dashboard
	 *
	 * @since 2.0.2
	 */
	public function persist_new_welcome_close() {
		Opt_In_Utils::validate_ajax_call( "hustle_new_welcome_notice" );
		update_option("hustle_new_welcome_notice_dismissed", true);
		wp_send_json_success();
	}


	public function export_subscriptions(){
		Opt_In_Utils::validate_ajax_call( 'inc_optin_export_subscriptions' );

		$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );

		if( !$id )
			die( esc_attr__("Invalid Request", Opt_In::TEXT_DOMAIN) );

		$optin = Hustle_Module_Model::instance()->get($id);
		$module_fields = Hustle_Module_Model::instance()->get($id)->get_design()->__get( 'module_fields' );
		$name = Hustle_Module_Model::instance()->get($id)->get_design()->__get( 'module_name' );
		$subscriptions = $optin->get_local_subscriptions();

		$fields = array();

		foreach ( $module_fields as $field ) {
			$fields[ $field['name'] ] = $field['label'];
		}
		$csv = implode( ', ', $fields ) . "\n";

		foreach( $subscriptions as $row ){
			$subscriber_data = array();

			foreach ( $fields as $key => $label ) {
				// Check for legacy
				if ( isset( $row->f_name ) && 'first_name' === $key )
					$key = 'f_name';
				if ( isset( $row->l_name ) && 'last_name' === $key )
					$key = 'l_name';

				$subscriber_data[ $key ] = isset( $row->$key ) ? $row->$key : '';
			}
			$csv .= implode( ', ', $subscriber_data ) . "\n";
		}

		$file_name = strtolower( sanitize_file_name( $name ) ) . ".csv";

		header("Content-type: application/x-msdownload", true, 200);
		header("Content-Disposition: attachment; filename=$file_name");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $csv; //phpcs:ignore
		die();

	}

	/**
	 * Validate new/updated custom module field.
	 **/
	public function add_module_field() {
		Opt_In_Utils::validate_ajax_call( 'optin_add_module_field' );
		$input = stripslashes_deep( $_REQUEST );

		if ( ! empty( $input ) ) {
			$provider = $input['provider'];
			$registered_providers = $this->_hustle->get_providers();
			$can_add = array(
				'success' => true,
				'field' => $input['field'],
			);

			if ( isset( $registered_providers[ $provider ] ) ) {
				$provider_class = $registered_providers[ $provider ]['class'];

				if ( class_exists( $provider_class )
					&& method_exists( $provider_class, 'add_custom_field' ) ) {
					$optin = Hustle_Module_Model::instance()->get( $input['optin_id'] );
					$can_add = call_user_func( array( $provider_class, 'add_custom_field' ), $input['field'], $optin );
				}
			}

			if ( isset( $can_add['success'] ) ) {
				wp_send_json_success( $can_add );
			} else {
				wp_send_json_error( $can_add );
			}
		}
	}

	/**
	 * Bulk Add optin module fields
	 */
	public function add_module_fields() {
		Opt_In_Utils::validate_ajax_call( 'hustle_save_popup_module' );
		$can_add = array(
			'error' => true,
			'code' => 'custom',
			'message' => __( 'Unable to add custom fields', Opt_In::TEXT_DOMAIN )
		);
		$provider = filter_input( INPUT_POST, 'provider' );
		$module_id = filter_input( INPUT_POST, 'module_id' );
		if ( $provider && $module_id ) {

			$registered_providers = $this->_hustle->get_providers();
			$default_form_elements = $this->_hustle->default_form_fields();
			if ( isset( $registered_providers[ $provider ] ) ) {
				$provider_class = $registered_providers[ $provider ]['class'];
				if ( class_exists( $provider_class )
					&& method_exists( $provider_class, 'add_custom_field' ) ) {

					$new_fields = filter_input( INPUT_POST, 'data' );
					$new_fields = json_decode( $new_fields, true );
					$default_field_keys = array_keys( $default_form_elements );
					if ( !empty ( $new_fields ) ){
						foreach ( $new_fields as $key => $new_field ) {
							if ( in_array( $new_field['name'], $default_field_keys, true ) ) {
								unset( $new_fields[$key] );
							}
						}
					}
					if ( !empty( $new_fields ) ) {
						$can_add = call_user_func( array( $provider_class, 'add_custom_field' ), $new_fields, $module_id );
					}
				}
			}
		}
		wp_send_json_error( $can_add );
	}

	public function get_error_list() {
		Opt_In_Utils::validate_ajax_call( 'hustle_get_error_logs' );
		$id = filter_input( INPUT_GET, 'optin_id', FILTER_VALIDATE_INT );

		if ( (int) $id > 0 ) {
			$optin = Hustle_Module_Model::instance()->get( $id );
			$error_log = $optin->get_error_log();
			$module_fields = $optin->get_design()->__get( 'module_fields' );
			wp_send_json_success( array(
				'logs' => $error_log,
				'module_fields' => $module_fields,
			) );
		}
		wp_send_json_error(true);
	}

	public function clear_logs() {
		Opt_In_Utils::validate_ajax_call( 'optin_clear_logs' );
		$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );

		if ( (int) $id > 0 ) {
			Hustle_Module_Model::instance()->get( $id )->clear_error_log();
		}
		wp_send_json_success(true);
	}

	public function export_error_logs() {
		Opt_In_Utils::validate_ajax_call( 'optin_export_error_logs' );
		$id = filter_input( INPUT_GET, 'optin_id', FILTER_VALIDATE_INT );

		if ( (int) $id > 0 ) {
			$optin = Hustle_Module_Model::instance()->get( $id );
			$error_log = $optin->get_error_log();
			$module_fields = $optin->get_design()->__get( 'module_fields' );
			$name = Hustle_Module_Model::instance()->get($id)->get_design()->__get( 'module_name' );
			$csv = array(array());
			$keys = array();

			foreach ( $module_fields as $field ) {
				$csv[0][] = $field['label'];
				$keys[] = $field['name'];
			}
			$csv[0][] = __( 'Error', Opt_In::TEXT_DOMAIN );
			$csv[0][] = __( 'Date', Opt_In::TEXT_DOMAIN );
			array_push( $keys, 'error', 'date' );

			if ( ! empty( $error_log ) ) {
				foreach ( $error_log as $log ) {
					$logs = array();

					foreach ( $keys as $key ) {
						$logs[ $key ] = sanitize_text_field( $log->$key );
					}
					$csv[] = $logs;
				}
			}

			foreach ( $csv as $index => $_csv ) {
				$csv[ $index ] = implode( ',', $_csv );
			}

			$file_name = strtolower( sanitize_file_name( $name ) ) . "-errors.csv";
			header("Content-type: application/x-msdownload", true, 200);
			header("Content-Disposition: attachment; filename=$file_name");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo implode( "\n", $csv );  //phpcs:ignore
			die();
		}
		wp_send_json_error(true);
	}

	public function update_hubspot_referrer() {
		Opt_In_Utils::validate_ajax_call( "hustle_hubspot_referrer" );

		$optin_id = filter_input( INPUT_GET, 'optin_id', FILTER_VALIDATE_INT );

		if ( class_exists( 'Hustle_HubSpot_Api') ) {
			$hubspot = new Hustle_HubSpot_Api();
			$hubspot->get_authorization_uri( $optin_id );
		}
	}

	/**
	 * Duplicate SlideIn Module
	 *
	 * @since 3.0.6
	 */
	public function duplicate(){
		Opt_In_Utils::validate_ajax_call( 'duplicate_slidein' );
		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );
		if( ! $id || ! $type ) {
			wp_send_json_error(__('Invalid Request', Opt_In::TEXT_DOMAIN));
		}
		$module = Hustle_Module_Model::instance()->get( $id );
		if( $module->module_type !== $type && in_array( $type, array( 'slidein' ), true ) ) {
			wp_send_json_error( __( 'Invalid environment: %s', Opt_In::TEXT_DOMAIN ), $type );
		}
		
		// Prevent having more than 3 modules when it's free version.
		$total = count(Hustle_Module_Collection::instance()->get_all( null, array( 'module_type' => 'slidein' ) ));
		if ( Opt_In_Utils::_is_free() && $total >= 3 ) {
			wp_send_json_error( array( 'requires_pro' => true ) );
		}


		/**
		 * get data, need it, $module is a singleton
		 */
		$content = $module->get_content()->to_array();
		$design = $module->get_design()->to_array();
		$settings = $module->get_display_settings()->to_array();
		$shortcode_id = $module->get_shortcode_id();
		/**
		 * create new one
		 */
		unset( $module->id );
		/**
		 * rename
		 */
		$module->module_name .= __( ' (copy)', Opt_In::TEXT_DOMAIN );
		$content['module_name'] = $module->module_name;
		/**
		 * turn status off
		 */
		$module->active = 0;
		$module->test_mode = 0;
		/**
		 * save
		 */
		$result = $module->save();
		if ( $result && !is_wp_error( $result ) ) {
			$module->add_meta( $this->_hustle->get_const_var( 'KEY_CONTENT', $module ), $content );
			$module->add_meta( $this->_hustle->get_const_var( 'KEY_DESIGN', $module ), $design );
			$module->add_meta( $this->_hustle->get_const_var( 'KEY_SETTINGS', $module ), $settings );
			$module->add_meta( $this->_hustle->get_const_var( 'KEY_SHORTCODE_ID', $module ),  $shortcode_id );
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
