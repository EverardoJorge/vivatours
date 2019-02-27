<?php
if( !class_exists("Hustle_Popup_Admin_Ajax") ):
/**
 * Class Hustle_Popup_Admin_Ajax
 * Takes care of all the ajax calls to admin pages
 *
 */
class Hustle_Popup_Admin_Ajax {

	private $_hustle;
	private $_admin;

	public function __construct( Opt_In $hustle, Hustle_Popup_Admin $admin ){

		$this->_hustle = $hustle;
		$this->_admin = $admin;

		add_action( "wp_ajax_provider_form_settings", array( $this , "get_provider_form_settings" ) );
		add_action("wp_ajax_hustle_save_popup_module", array( $this, "save_popup" ));
		add_action("wp_ajax_hustle_popup_prepare_custom_css", array( $this, "prepare_custom_css" ));
		add_action("wp_ajax_hustle_popup_module_toggle_state", array( $this, "toggle_module_state" ));
		add_action("wp_ajax_hustle_popup_module_toggle_tracking_activity", array( $this, "toggle_tracking_activity" ));
		add_action("wp_ajax_hustle_popup_duplicate", array( $this, "duplicate" ));
		add_action("wp_ajax_hustle_popup_toggle_test_activity", array( $this, "toggle_test_activity" ));
		add_action("wp_ajax_hustle_delete_module", array( $this, "delete_module" ));
		add_action("wp_ajax_hustle_get_email_lists", array( $this, "get_subscriptions_list" ));
		add_action("wp_ajax_inc_optin_export_subscriptions", array( $this, "export_subscriptions" ));
		add_action("wp_ajax_persist_new_welcome_close", array( $this, "persist_new_welcome_close" ));
		// Maybe Legacy -> wp_ajax_add_module_field. Check in embeds and slide-ins as well
		add_action("wp_ajax_add_module_field", array( $this, "add_module_field" ) );
		add_action("wp_ajax_add_module_fields", array( $this, "add_module_fields" ) );
		add_action( "wp_ajax_hustle_import_module", array( $this, "import_module" ));
		add_action( "wp_ajax_get_error_list", array( $this, "get_error_list" ) );
		add_action( "wp_ajax_clear_logs", array( $this, "clear_logs" ) );
		add_action( "wp_ajax_inc_optin_export_error_logs", array( $this, "export_error_logs" ) );
		add_action( "wp_ajax_sshare_show_page_content", array( $this, "sshare_show_page_content" ) );
		add_action( "wp_ajax_get_new_condition_ids", array( $this, "get_new_condition_ids" ) );

		if ( Opt_In_Utils::_is_free() && ! file_exists( WP_PLUGIN_DIR . '/hustle/opt-in.php' ) ) {
			add_action( 'wp_ajax_hustle_dismiss_admin_notice', array( $this, 'dismiss_admin_notice' ) );
		}

		add_action( "wp_ajax_hustle_get_module_id_by_shortcode", array( $this, "get_module_id_by_shortcode" ) );
		add_action( "wp_ajax_hustle_render_module", array( $this, "render_module" ) );
	}

	public function get_provider_form_settings() {
		Opt_In_Utils::validate_ajax_call( "get_provider_form_settings" );

		// Sanitizes the data from $_REQUEST['data'] and validate required fields
		$sanitized_post_data = Opt_In_Utils::validate_and_sanitize_fields( $_REQUEST['data'], array( 'slug', 'step', 'current_step' ) );
		if( isset( $sanitized_post_data['errors'] ) ){
			wp_send_json_error(
				array (
					'message'	=> __( 'Please check the required fields.', Opt_In::TEXT_DOMAIN ),
					'errors'	=> $sanitized_post_data['errors']
				)
			);
		}
		$slug                = $sanitized_post_data['slug'];
		$step                = $sanitized_post_data['step'];
		$current_step        = $sanitized_post_data['current_step'];
		$is_step			 = $sanitized_post_data['is_step'];

		$provider = Opt_In_Utils::get_provider_by_slug( $slug );

		if ( ! $provider ) {
			wp_send_json_error( __( 'Provider not found', Opt_In::TEXT_DOMAIN ) );
		}

		if ( ! $provider->is_form_settings_available() ) {
			wp_send_json_success(
				array(
					'data' =>  $provider->get_empty_wizard( __( 'This Provider does not have form settings available', Opt_In::TEXT_DOMAIN ) ),
				)
			);
		}

		unset( $sanitized_post_data['slug'] );
		unset( $sanitized_post_data['current_step'] );
		unset( $sanitized_post_data['step'] );
		unset( $sanitized_post_data['is_step'] );

		$wizard = $provider->get_form_settings_wizard( $sanitized_post_data, $current_step, $step, false, $is_step );

		wp_send_json_success(
			array(
				'data' => $wizard
			)
		);
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
	 * Finds and repares select2 options
	 *
	 * @global type $wpdb
	 * @since 3.0.7
	 */
	public function get_new_condition_ids() {
		$post_type = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_STRING );
		$search = filter_input( INPUT_POST, 'search' );
		$result = array();
		$limit = 30;
		if ( !empty( $post_type ) ) {
			if ( in_array( $post_type, array( 'tag', 'category' ), true ) ) {
				$args = array(
					'hide_empty' =>false,
					'search' => $search,
					'number' => $limit,
				);
				if ( 'tag' === $post_type ) {
					$args['taxonomy'] = 'post_tag';
				}
				$result = array_map(array( 'Hustle_Module_Admin', "terms_to_select2_data"), get_categories( $args ));
			} else {
				global $wpdb;
				$result = $wpdb->get_results( $wpdb->prepare( "SELECT ID as id, post_title as text FROM {$wpdb->posts} "
				. "WHERE post_type = %s AND post_status = 'publish' AND post_title LIKE %s LIMIT " . intval( $limit ), $post_type, '%'. $search . '%' ) );

				$obj = get_post_type_object( $post_type );
				$all_items = !empty( $obj ) && !empty( $obj->labels->all_items )
						? $obj->labels->all_items : __( "All Items", Opt_In::TEXT_DOMAIN );
				/**
				 * Add ALL Items option
				 */
				$all = new stdClass();
				$all->id = "all";
				$all->text = $all_items;
				if ( empty( $search ) || false !== stripos( $all_items, $search ) ) {
					array_unshift($result, $all);
				}
			}
		}

		wp_send_json_success( $result );
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
	public function save_popup(){

		Opt_In_Utils::validate_ajax_call( "hustle_save_popup_module" );

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
			"success" => false === $res ? false: true,
			"data" => $res
		) );
	}


	/**
	 * Toggles optin active state
	 *
	 * @since 1.0
	 */
	public function toggle_module_state(){

		Opt_In_Utils::validate_ajax_call( "popup_module_toggle_state" );

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

		Opt_In_Utils::validate_ajax_call( "popup_toggle_tracking_activity" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );

		if( !$id || !$type )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$module =  Hustle_Module_Model::instance()->get($id);

		if( 'popup' !== $type )
			wp_send_json_error( sprintf( __("Invalid environment: %s", Opt_In::TEXT_DOMAIN), $type ));

		$result = $module->toggle_type_track_mode( $type );

		if( $result && !is_wp_error( $result ) )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( $result->get_error_message() );
	}

	public function duplicate(){
		Opt_In_Utils::validate_ajax_call( "duplicate_popup" );
		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );
		if( !$id || !$type ) {
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));
		}
		$module =  Hustle_Module_Model::instance()->get($id);
		if( $module->module_type !== $type && in_array( $type, array( 'popup' ), true ) ) {
			wp_send_json_error( __("Invalid environment: %s", Opt_In::TEXT_DOMAIN), $type);
		}

		// Prevent having more than 3 modules when it's free version.
		$total = count(Hustle_Module_Collection::instance()->get_all( null, array( 'module_type' => 'popup' ) ));
		if ( Opt_In_Utils::_is_free() && $total >= 3 ) {
			wp_send_json_error( array( 'requires_pro' => true ) );
		}

		// Prevent having more than 3 modules when it's free version.
		$total = count(Hustle_Module_Collection::instance()->get_all( null, array( 'module_type' => 'embedded' ) ));
		if ( Opt_In_Utils::_is_free() && $total >= 3 ) {
			wp_send_json_error( array( 'requires_pro' => true ) );
		}

		$content = $module->get_content()->to_array();
		$design = $module->get_design()->to_array();
		$settings = $module->get_display_settings()->to_array();
		$shortcode_id = $module->get_shortcode_id();
		unset( $module->id );
		//rename
		$module->module_name .= __(" (copy)", Opt_In::TEXT_DOMAIN);
		//turn status off
		$module->active = 0;
		//save
		$result = $module->save();
		if ( $result ) {
			// save to meta table
			$module->add_meta( $this->_hustle->get_const_var( "KEY_CONTENT", $module ), $content );
			$module->add_meta( $this->_hustle->get_const_var( "KEY_DESIGN", $module ), $design );
			$module->add_meta( $this->_hustle->get_const_var( "KEY_SETTINGS", $module ), $settings );
			$module->add_meta( $this->_hustle->get_const_var( "KEY_SHORTCODE_ID", $module ),  $shortcode_id );
		}
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

		Opt_In_Utils::validate_ajax_call( "popup_toggle_test_activity" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		//$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );

		//if( !$id || !$type )
		if( !$id )
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

	public function import_module() {
		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$type = trim( filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING ) );

		if( !$id || !$type )
			wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

		Opt_In_Utils::validate_ajax_call( 'import_settings' . $id );

		//get old module data
		$module =  Hustle_Module_Model::instance()->get($id);

		//get new module data
		$file = isset( $_FILES['file'] ) ? $_FILES['file'] : false;

		if ( !$file ) {
			wp_send_json_error( __("File is required", Opt_In::TEXT_DOMAIN) );
		} else if ( !empty( $file['error'] ) ) {
			wp_send_json_error( sprintf( __("Error: %s", Opt_In::TEXT_DOMAIN), esc_html( $file['error'] ) ) );
		}
		$overrides = array(
			'test_form' => false,
			'test_type' => false,
		);
		$wp_file = wp_handle_upload( $file, $overrides );
		$filename = $wp_file['file'];
		$file_content = file_get_contents( $filename );

		// Import file if it's json format
		$data = array();
		if ( strpos( $filename, '.json' ) || strpos( $filename, '.JSON' ) ) {
			$data = json_decode( $file_content );
		}

		//check required data
		if ( !isset( $data->module_name ) || empty( $data->module_type ) || !isset( $data->test_mode ) || !isset( $data->active )
				|| empty( $data->content ) || empty( $data->design ) || empty( $data->settings ) || empty( $data->shortcode_id ) ) {
			wp_send_json_error( __("Invalid JSON", Opt_In::TEXT_DOMAIN) );
		}

		//check module type
		if( Hustle_Module_Model::import_export_check_type( $data->module_type, $module->module_type ) )
			wp_send_json_error( sprintf( __("Invalid environment: %s", Opt_In::TEXT_DOMAIN), $data->module_type ) );

		// save to modules table
		$module->module_name = $data->module_name;
		$module->module_type = $data->module_type;
		$module->active = (int) $data->active;
		$module->test_mode = (int) $data->test_mode;
		$module->save();

		// save to meta table
		$module->update_meta( $this->_hustle->get_const_var( "KEY_CONTENT", $module ), $data->content );
		$module->update_meta( $this->_hustle->get_const_var( "KEY_DESIGN", $module ), $data->design );
		$module->update_meta( $this->_hustle->get_const_var( "KEY_SETTINGS", $module ), $data->settings );
		$module->update_meta( $this->_hustle->get_const_var( "KEY_SHORTCODE_ID", $module ), $data->shortcode_id );

		wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
	}

	/**
	 * Delete optin
	 */
	public function delete_module(){

		Opt_In_Utils::validate_ajax_call( "hustle_delete_module" );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$multiple = filter_input( INPUT_POST, 'multiple', FILTER_VALIDATE_INT );
		$ids = json_decode( filter_input( INPUT_POST, 'ids' ) );

		if ( $multiple ) {
			$ids = ( !is_array( $ids ) ) ? (array) $ids : $ids;
			foreach( $ids as $id ) {
				$result = Hustle_Module_Model::instance()->get($id)->delete();
				if ( !$result ) {
					wp_send_json_error( __("Failed", Opt_In::TEXT_DOMAIN) );
				}
			}
		} else {
			if( !$id )
				wp_send_json_error(__("Invalid Request", Opt_In::TEXT_DOMAIN));

			$result = Hustle_Module_Model::instance()->get($id)->delete();
		}

		if( $result )
			wp_send_json_success( __("Successful", Opt_In::TEXT_DOMAIN) );
		else
			wp_send_json_error( __("Failed", Opt_In::TEXT_DOMAIN) );
	}

	/**
	 * Checks conditions required to run given provider
	 *
	 * @param $provider
	 * @return bool|WP_Error
	 */
	/*private function _is_provider_allowed_to_run( $provider ){
		$err = new WP_Error();
		if( ! $provider->is_activable() ){
			$err->add( $provider->get_title() . " Not Allowed", __("This provider requires a higher PHP version or a higher Hustle version. Please upgrade to use this provider.", Opt_In::TEXT_DOMAIN) );
			return $err;
		}

		return true;
	}
	*/

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
		$module_fields = Hustle_Module_Model::instance()->get($id)->get_content()->__get( 'form_elements' );
		// Parse JSON if string, otherwise no parsing.
		$module_fields = ( 'string' === gettype($module_fields) ) ? json_decode($module_fields, true) : $module_fields;

		if( $subscriptions )
			wp_send_json_success( array(
				"subscriptions" => $subscriptions,
				'module_fields'=> $module_fields,
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
		$type = trim( filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING ) );

		if( !$id )
			die(esc_html__("Invalid Request", Opt_In::TEXT_DOMAIN));

		$optin = Hustle_Module_Model::instance()->get($id);
		$module_fields = $optin->get_content($type)->__get( 'form_elements' );
		// Parse JSON if string, otherwise no parsing.
		$module_fields = ( 'string' === gettype($module_fields) ) ? json_decode($module_fields, true) : $module_fields;
		$name = $optin->get_content($type)->__get( 'module_name' );
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
			'message' => __( 'Unable to add custom fields', Opt_In::TEXT_DOMAIN ),
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
		$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );

		if ( (int) $id > 0 ) {
			$optin = Hustle_Module_Model::instance()->get( $id );
			$error_log = $optin->get_error_log();
			$module_fields = Hustle_Module_Model::instance()->get($id)->get_content()->__get( 'form_elements' );
			// Parse JSON if string, otherwise no parsing.
			$module_fields = ( 'string' === gettype($module_fields) ) ? json_decode($module_fields, true) : $module_fields;

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
		$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );

		if ( (int) $id > 0 ) {
			$optin = Hustle_Module_Model::instance()->get( $id );
			$error_log = $optin->get_error_log();
			$module_fields = json_decode($optin->get_content()->__get( 'form_elements' ));
			$name = Hustle_Module_Model::instance()->get($id)->get_content()->__get( 'module_name' );
			$csv = array(array());
			$keys = array();

			foreach ( $module_fields as $field ) {
				if ( 'submit' === $field->name || 'submit' === strtolower($field->label) ) {
					continue;
				}
				$csv[0][] = $field->label;
				$keys[] = $field->name;
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
			echo implode( "\n", $csv );
			die();
		}
		wp_send_json_error(true);
	}

	public function sshare_show_page_content() {
		Opt_In_Utils::validate_ajax_call( "hustle_ss_stats_paged_data" );

		$page_id = filter_input( INPUT_POST, 'page_id', FILTER_VALIDATE_INT );
		$offset = ($page_id - 1) * 5;
		$ss_share_stats = Hustle_Module_Collection::instance()->get_share_stats( $offset, 5 );

		foreach($ss_share_stats as $key => $ss_stats) {
			$ss_share_stats[$key]->page_url = $ss_stats->ID ? esc_url(get_permalink($ss_stats->ID)) : esc_url(get_home_url());
			$ss_share_stats[$key]->page_title = $ss_stats->ID ? $ss_stats->post_title : get_bloginfo();
		}

		wp_send_json_success( array(
			'ss_share_stats' => $ss_share_stats
		) );
	}

	/**
	 * Sets an user meta to prevent admin notice from showing up again after dismissed.
	 *
	 * @since 3.0.6
	 */
	public function dismiss_admin_notice() {
		$user_id = get_current_user_id();
		$notice = filter_input( INPUT_POST, 'dismissed_notice', FILTER_SANITIZE_STRING );

		$dismissed_notices = get_user_meta( $user_id, 'hustle_dismissed_admin_notices', true );
		$dismissed_notices = array_filter( explode( ',', (string) $dismissed_notices ) );

		if ( $notice && ! in_array( $notice, $dismissed_notices, true ) ) {
			$dismissed_notices[] = $notice;
			$to_store = implode( ',', $dismissed_notices );
			update_user_meta( $user_id, 'hustle_dismissed_admin_notices', $to_store );
		}

		wp_send_json_success();
	}

	/**
	 * Get the module_id by the shortcode_id provided.
	 * Used by Gutenberg to create blocks.
	 *
	 * @since 3.0.7
	 *
	 * @return void
	 */
	public function get_module_id_by_shortcode() {
		Opt_In_Utils::validate_ajax_call( "hustle_gutenberg_get_module" );

		$shortcode_id = filter_input( INPUT_GET, 'shortcode_id', FILTER_SANITIZE_STRING );
		$module_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );

		$enforce_type = ( 'embedded' === $module_type || 'social_sharing' === $module_type ) ? true : false;
		$module = Hustle_Module_Model::instance()->get_by_shortcode( $shortcode_id, $enforce_type );

		wp_send_json_success( array( 'module_id' => $module->id ) );
	}

	/**
	 * Send the module's data to be rendered in Gutenberg.
	 *
	 * @since 3.0.7
	 *
	 */
	public function render_module() {
		Opt_In_Utils::validate_ajax_call( "hustle_gutenberg_get_module" );

		$module_id = filter_input( INPUT_GET, 'module_id', FILTER_SANITIZE_STRING );
		$shortcode_id = filter_input( INPUT_GET, 'shortcode_id', FILTER_SANITIZE_STRING );
		$module_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );

		if ( $module_id ) {
			$module = Hustle_Module_Model::instance()->get( $module_id );

		} elseif ( $shortcode_id ) {
			$enforce_type = ( 'embedded' === $module_type || 'social_sharing' === $module_type ) ? true : false;
			$module = Hustle_Module_Model::instance()->get_by_shortcode( $shortcode_id, $enforce_type );

		}

		if ( ( ! $module_id && ! $shortcode_id ) || ! $module->id ) {
			wp_send_json_error();
		}

		if ( 'social_sharing' === $module->module_type ) {
			$module = Hustle_SShare_Model::instance()->get( $module->id );
		}

		$data = $module->get_module_data_to_display( true );


		if ( 'social_sharing' === $module->module_type ) {
			$shortcode_class = Hustle_Module_Front::SSHARE_SHORTCODE_CSS_CLASS;

			$html = $this->_hustle->render( 'general/sshare', array(), true );

		} else {
			$shortcode_class = Hustle_Module_Front::SHORTCODE_CSS_CLASS;

			$is_optin = ( ! $data['content']['use_email_collection'] || 'false' === $data['content']['use_email_collection'] ) ? false : true;

			if ( $is_optin ) {
				$html = $this->_hustle->render( 'general/modals/optin-true', array(), true );

			} else {
				$html = $this->_hustle->render( 'general/modals/optin-false', array(), true );

			}
		}

		$data['shortcode_id'] = $shortcode_id;

		$class =  $shortcode_class . ' hustle_module_' . esc_attr( $module->id ) . ' module_id_' . esc_attr( $module->id );
		$opening_wrapper = '<div class="' . $class . '" data-type="shortcode" data-id="' . $module->id . '">';

		$style = '<style type="text/css" class="hustle-module-styles-' . $module->id . '">' . $module->get_decorated()->get_module_styles( $module->module_type ) . '</style>';

		$response = array(
			'data' => $data,
			'html' => $html,
			'style' => $style,
			'opening_wrapper' => $opening_wrapper
		);

		wp_send_json_success( $response );
	}
}
endif;
