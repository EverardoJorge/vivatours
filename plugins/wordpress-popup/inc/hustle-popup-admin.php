<?php
if( !class_exists("Hustle_Popup_Admin") ):

/**
 * Class Hustle_Popup_Admin
 */
class Hustle_Popup_Admin {

	private $_hustle;
	private $_email_services;

	public function __construct( Opt_In $hustle, Hustle_Email_Services $email_services ){

		$this->_hustle = $hustle;
		$this->_email_services = $email_services;

		add_action( 'admin_init', array( $this, "check_free_version" ) );
		add_action( 'admin_init', array( $this, "export_module" ) );
		add_action( 'admin_menu', array( $this, "register_admin_menu" ) );
		add_action( 'admin_head', array( $this, "hide_unwanted_submenus" ) );
		add_filter( 'hustle_optin_vars', array( $this, "register_current_json" ) );

	}

	/**
	 * Registers admin menu page
	 *
	 * @since 1.0
	 */
	public function register_admin_menu() {

		// Optins
		add_submenu_page( 'hustle', __("Pop-ups", Opt_In::TEXT_DOMAIN) , __("Pop-ups", Opt_In::TEXT_DOMAIN) , "manage_options", Hustle_Module_Admin::POPUP_LISTING_PAGE,  array( $this, "render_popup_listing" )  );
		add_submenu_page( 'hustle', __("New Pop-up", Opt_In::TEXT_DOMAIN) , __("New Pop-up", Opt_In::TEXT_DOMAIN) , "manage_options", Hustle_Module_Admin::POPUP_WIZARD_PAGE,  array( $this, "render_popup_wizard_page" )  );

	}

	/**
	 * Removes the submenu entries for content creation
	 *
	 * @since 2.0
	 */
	public function hide_unwanted_submenus(){
		remove_submenu_page( 'hustle', Hustle_Module_Admin::POPUP_WIZARD_PAGE );
	}

	public function register_current_json( $current_array ){

		if( Hustle_Module_Admin::is_edit() && isset( $_GET['page'] ) && Hustle_Module_Admin::POPUP_WIZARD_PAGE === $_GET['page'] ){ // WPCS: CSRF ok.

			$module = Hustle_Module_Model::instance()->get( filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT) );
			$current_array['current'] = array(
				'listing_page' => Hustle_Module_Admin::POPUP_LISTING_PAGE,
				'wizard_page' => Hustle_Module_Admin::POPUP_WIZARD_PAGE,
				'data' => $module->get_data(),
				'content' => $module->get_content()->to_array(),
				'design' => $module->get_design()->to_array(),
				'settings' => $module->get_display_settings()->to_array(),
				'shortcode_id' => $module->get_shortcode_id(),
				'section' => Hustle_Module_Admin::get_current_section(),
				'providers' => $this->_hustle->get_providers(),
			);
		}

		return $current_array;
	}

	/**
	 * Renders menu page based on if we already any optin
	 *
	* @since 1.0
	 */
	public function render_popup_wizard_page() {
		$module_id = filter_input( INPUT_GET, "id", FILTER_VALIDATE_INT );
		$provider = filter_input( INPUT_GET, "provider" );
		$current_section = Hustle_Module_Admin::get_current_section();
		$recaptcha_settings = Hustle_Module_Model::get_recaptcha_settings();
		$recaptcha_enabled = isset( $recaptcha_settings['enabled'] ) && '1' === $recaptcha_settings['enabled'];

		$this->_hustle->render( "/admin/popup/wizard", array(
			'section' => ( !$current_section ) ? 'content' : $current_section,
			'is_edit' => Hustle_Module_Admin::is_edit(),
			'module_id' => $module_id,
			'module' => $module_id ? Hustle_Module_Model::instance()->get( $module_id ) : $module_id,
			'providers' => $this->_hustle->get_providers(),
			'animations' => $this->_hustle->get_animations(),
			'countries' => $this->_hustle->get_countries(),
			'widgets_page_url' => get_admin_url(null, 'widgets.php'),
			'save_nonce' => wp_create_nonce('hustle_save_popup_module'),
			"shortcode_render_nonce" => wp_create_nonce("hustle_shortcode_render"),
			'default_form_fields' => $this->_hustle->get_default_form_fields(),
			'recaptcha_enabled' => $recaptcha_enabled,
		));
	}

	/**
	 * Check if using free version then redirect to upgrade page
	 *
	* @since 3.0
	 */
	public function check_free_version() {
		if (  isset( $_GET['page'] ) && Hustle_Module_Admin::POPUP_WIZARD_PAGE === $_GET['page'] ) { // WPCS: CSRF ok.
			$collection_args = array( 'module_type' => 'popup' );
			$total_popups = count(Hustle_Module_Collection::instance()->get_all( null, $collection_args ));
			if ( Opt_In_Utils::_is_free() && ! Hustle_Module_Admin::is_edit() && $total_popups >= 3 ) {
				wp_safe_redirect( 'admin.php?page=' . Hustle_Module_Admin::POPUP_LISTING_PAGE . '&' . Hustle_Module_Admin::UPGRADE_MODAL_PARAM . '=true' );
				exit;
			}
		}
	}

	/**
	 * Export module settings
	 */
	public function export_module(){
		$action = filter_input( INPUT_GET, 'action' );
		if ( Opt_In::EXPORT_MODULE_ACTION === $action ) {
			wp_verify_nonce( Opt_In::EXPORT_MODULE_ACTION );

			$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );
			$type = trim( filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING ) );

			//check required parameters
			if( !$id || !$type )
				die(esc_html__("Invalid Request", Opt_In::TEXT_DOMAIN));

			$module =  Hustle_Module_Model::instance()->get($id);

			//check type
			if( Hustle_Module_Model::import_export_check_type( $type, $module->module_type ) )
				die( sprintf( esc_html__("Invalid environment: %s", Opt_In::TEXT_DOMAIN), esc_html( $module->module_type ) ));

			//set needed settings
			$settings['module_name'] = $module->module_name;
			$settings['module_type'] = $module->module_type;
			$settings['active'] = $module->active;
			$settings['test_mode'] = $module->test_mode;
			$settings[ $this->_hustle->get_const_var( "KEY_CONTENT", $module ) ] = $module->get_content()->to_array();
			$settings[ $this->_hustle->get_const_var( "KEY_DESIGN", $module ) ] = $module->get_design()->to_array();
			$settings[ $this->_hustle->get_const_var( "KEY_SETTINGS", $module ) ] = $module->get_display_settings()->to_array();
			$settings[ $this->_hustle->get_const_var( "KEY_SHORTCODE_ID", $module ) ] = $module->get_shortcode_id();

			$filename = sanitize_file_name( $module->module_name . '.json' );

			if ( ob_get_length() )
				ob_clean();

			/**
			 * Print HTTP headers
			 */
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: text/plain; charset=' . get_option( 'blog_charset' ), true );
			/**
			 * Check PHP version, for PHP < 3 do not add options
			 */
			$version = phpversion();
			$compare = version_compare( $version, '5.3', '<' );
			if ( $compare ) {
				echo wp_json_encode( $settings );
				exit;
			}
			$option = defined( 'JSON_PRETTY_PRINT' )? JSON_PRETTY_PRINT : null;
			echo wp_json_encode( $settings, $option );
			exit;
		}
	}

	/**
	 * Renders Popup listing page
	 *
	 * @since 2.0
	 */
	public function render_popup_listing(){
		$current_user = wp_get_current_user();
		$new_module = isset( $_GET['module'] ) ? Hustle_Module_Model::instance()->get( intval($_GET['module'] ) ) : null; // WPCS: CSRF ok.
		$updated_module = isset( $_GET['updated_module'] ) ? Hustle_Module_Model::instance()->get( intval($_GET['updated_module'] ) ) : null; // WPCS: CSRF ok.

		$this->_hustle->render("admin/popup/listing", array(
			'popups' => Hustle_Module_Collection::instance()->get_all( null, array( 'module_type' => 'popup' ) ),
			'new_module' =>  $new_module,
			'updated_module' =>  $updated_module,
			'add_new_url' => admin_url("admin.php?page=hustle_popup"),
			'user_name' => ucfirst($current_user->display_name),
			'is_free' => Opt_In_Utils::_is_free()
		));
	}

	/**
	 * Saves new optin to db
	 *
	 * @since 1.0
	 *
	 * @param $data
	 * @return mixed
	 */
	public function save_new( $data ){
		$module = new Hustle_Module_Model();

		// save to modules table
		$module->module_name = $data['module']['module_name'];
		$module->module_type = Hustle_Module_Model::POPUP_MODULE;
		$module->active = (int) $data['module']['active'];
		$module->test_mode = (int) $data['module']['test_mode'];
		$module->save();

		// save to meta table
		$module->add_meta( $this->_hustle->get_const_var( "KEY_CONTENT", $module ), $data['content'] );
		$module->add_meta( $this->_hustle->get_const_var( "KEY_DESIGN", $module ), $data['design'] );
		$module->add_meta( $this->_hustle->get_const_var( "KEY_SETTINGS", $module ), $data['settings'] );
		$module->add_meta( $this->_hustle->get_const_var( "KEY_SHORTCODE_ID", $module ),  $data['shortcode_id'] );

		return $module->id;

	}


	public function update_module( $data ){
		if( !isset( $data['id'] ) ) return false;

		$module = Hustle_Module_Model::instance()->get( $data['id'] );

		// save to modules table
		$module->module_name = $data['module']['module_name'];
		$module->module_type = Hustle_Module_Model::POPUP_MODULE;
		$module->active = (int) $data['module']['active'];
		$module->test_mode = (int) $data['module']['test_mode'];
		$module->save();

		// save to meta table
		$module->update_meta( $this->_hustle->get_const_var( "KEY_CONTENT", $module ), $data['content'] );
		$module->update_meta( $this->_hustle->get_const_var( "KEY_DESIGN", $module ), $data['design'] );
		$module->update_meta( $this->_hustle->get_const_var( "KEY_SETTINGS", $module ), $data['settings'] );
		$module->update_meta( $this->_hustle->get_const_var( "KEY_SHORTCODE_ID", $module ), $data['shortcode_id'] );

		return $module->id;
	}
}

endif;
