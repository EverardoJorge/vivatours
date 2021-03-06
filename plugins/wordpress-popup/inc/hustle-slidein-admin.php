<?php
if( !class_exists("Hustle_Slidein_Admin") ):

/**
 * Class Hustle_Slidein_Admin
 */
class Hustle_Slidein_Admin {

	private $_hustle;
	private $_email_services;

	public function __construct( Opt_In $hustle, Hustle_Email_Services $email_services ){

		$this->_hustle = $hustle;
		$this->_email_services = $email_services;

		add_action( 'admin_init', array( $this, "check_free_version" ) );
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
		add_submenu_page( 'hustle', __("Slide-ins", Opt_In::TEXT_DOMAIN) , __("Slide-ins", Opt_In::TEXT_DOMAIN) , "manage_options", Hustle_Module_Admin::SLIDEIN_LISTING_PAGE,  array( $this, "render_slidein_listing" )  );
		add_submenu_page( 'hustle', __("New Slide-in", Opt_In::TEXT_DOMAIN) , __("New Slide-in", Opt_In::TEXT_DOMAIN) , "manage_options", Hustle_Module_Admin::SLIDEIN_WIZARD_PAGE,  array( $this, "render_slidein_wizard_page" )  );

	}

	/**
	 * Removes the submenu entries for content creation
	 *
	 * @since 2.0
	 */
	public function hide_unwanted_submenus(){
		remove_submenu_page( 'hustle', Hustle_Module_Admin::SLIDEIN_WIZARD_PAGE );
	}

	public function register_current_json( $current_array ){

		if( Hustle_Module_Admin::is_edit() && isset( $_GET['page'] ) && Hustle_Module_Admin::SLIDEIN_WIZARD_PAGE === $_GET['page'] ){

			$module = Hustle_Module_Model::instance()->get( filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT) );
			// $ss = Hustle_Social_Sharing_Model::instance()->get( filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT) );
			// $all_ss = Hustle_Social_Sharing_Collection::instance()->get_all( null );
			// $total_ss = count($all_ss);
			$current_array['current'] = array(
				'listing_page' => Hustle_Module_Admin::SLIDEIN_LISTING_PAGE,
				'wizard_page' => Hustle_Module_Admin::SLIDEIN_WIZARD_PAGE,
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
	public function render_slidein_wizard_page() {

		$module_id = filter_input( INPUT_GET, "id", FILTER_VALIDATE_INT );
		$provider = filter_input( INPUT_GET, "provider" );
		$current_section = Hustle_Module_Admin::get_current_section();
		$recaptcha_settings = Hustle_Module_Model::get_recaptcha_settings();
		$recaptcha_enabled = isset( $recaptcha_settings['enabled'] ) && '1' === $recaptcha_settings['enabled'];

		$this->_hustle->render( "/admin/slidein/wizard", array(
			"section" => ( !$current_section ) ? 'content' : $current_section,
			"is_edit" => Hustle_Module_Admin::is_edit(),
			"module_id" => $module_id,
			"module" => $module_id ? Hustle_Module_Model::instance()->get( $module_id ) : $module_id,
			"providers" => $this->_hustle->get_providers(),
			"animations" => $this->_hustle->get_animations(),
			'countries' => $this->_hustle->get_countries(),
			'widgets_page_url' => get_admin_url(null, "widgets.php"),
			"save_nonce" => wp_create_nonce("hustle_save_slidein_module"),
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
		if ( isset( $_GET['page'] ) && Hustle_Module_Admin::SLIDEIN_WIZARD_PAGE === $_GET['page'] ) {
			$collection_args = array( 'module_type' => 'slidein' );
			$total_slideins = count(Hustle_Module_Collection::instance()->get_all( null, $collection_args ));
			if ( Opt_In_Utils::_is_free() && ! Hustle_Module_Admin::is_edit() && $total_slideins >= 3 ) {
				wp_safe_redirect( 'admin.php?page=' . Hustle_Module_Admin::SLIDEIN_LISTING_PAGE . '&' . Hustle_Module_Admin::UPGRADE_MODAL_PARAM . '=true' );
				exit;
			}
		}
	}

	/**
	 * Renders Slide in listing page
	 *
	 * @since 2.0
	 */
	public function render_slidein_listing(){
		$current_user = wp_get_current_user();
		$new_module = isset( $_GET['module'] ) ? Hustle_Module_Model::instance()->get( intval($_GET['module'] ) ) : null;
		$updated_module = isset( $_GET['updated_module'] ) ? Hustle_Module_Model::instance()->get( intval($_GET['updated_module'] ) ) : null;

		$this->_hustle->render("admin/slidein/listing", array(
			'slideins' => Hustle_Module_Collection::instance()->get_all( null, array( 'module_type' => 'slidein' ) ),
			'new_module' =>  $new_module,
			'updated_module' =>  $updated_module,
			'add_new_url' => admin_url("admin.php?page=hustle_slidein"),
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
		$module->module_type = Hustle_Module_Model::SLIDEIN_MODULE;
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
		$module->module_type = Hustle_Module_Model::SLIDEIN_MODULE;
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
