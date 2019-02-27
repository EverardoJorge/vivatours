<?php
if( !class_exists("Hustle_Activecampaign") ):

include_once 'hustle-activecampaign-api.php';

class Hustle_Activecampaign extends Hustle_Provider_Abstract {
	
	const SLUG = "activecampaign";
	//const NAME = "ActiveCampaign";

	/**
	 * @var $api Activecampaign
	 */
	protected  static $api;
	protected  static $errors;
	
	/**
	 * Provider Instance
	 *
	 * @since 3.0.5
	 *
	 * @var self|null
	 */
	protected static $_instance = null;

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_slug 				   = 'activecampaign';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_version				   = '1.0';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_class				   = __CLASS__;

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_title                  = 'ActiveCampaign';

	/**
	 * @since 3.0.5
	 * @var bool
	 */
	protected $_supports_fields = true;

	/**
	 * Class name of form settings
	 *
	 * @var string
	 */
	protected $_form_settings = 'Hustle_Activecampaign_Form_Settings';

	/**
	 * Provider constructor.
	 */	
	public function __construct() {
		$this->_icon = plugin_dir_path( __FILE__ ) . 'views/icon.php';
	}

	/**
	 * Get Instance
	 *
	 * @return self|null
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * @param $username
	 * @param $api_key
	 * @return Hustle_Activecampaign_Api
	 */
	//protected static function api( $url, $api_key ){
	public static function api( $url, $api_key ){

		if( empty( self::$api ) ){
			try {
				self::$api = new Hustle_Activecampaign_Api( $url, $api_key );
				self::$errors = array();
			} catch (Exception $e) {
				self::$errors = array("api_error" => $e) ;
			}

		}

		return self::$api;
	}

	/**
	 * Adds contact to the the campaign
	 *
	 * @param Hustle_Module_Model $module
	 * @param array $data
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe( Hustle_Module_Model $module, array $data ){
		$api_key    = self::_get_api_key( $module );
		$ac_url     = self::_get_api_url( $module );
		//$list_id    = self::_get_api_list_id( $module );
		//$form_id	= self::get_api_form_id( $module );
		$sign_up_to = self::get_sign_up_to( $module );

		$id = ( 'list' === $sign_up_to ) ? self::_get_api_list_id( $module ) : self::get_api_form_id( $module );

		$api = self::api( $ac_url, $api_key );

		if ( isset( $data['f_name'] ) ) {
			$data['first_name'] = $data['f_name']; // Legacy
			unset( $data['f_name'] );
		}
		if( isset( $data['l_name'] ) ) {
			$data['last_name'] = $data['l_name']; // Legacy
			unset( $data['l_name'] );
		}
		$custom_fields = array_diff_key( $data, array( 
			'first_name' => '', 
			'last_name' => '', 
			'email' => '' 
		) );
		$orig_data = $data;

		if ( ! empty( $custom_fields ) ) {
			foreach ( $custom_fields as $key => $value ) {
				$key = 'field[%' . $key . '%,0]';
				$data[ $key ] = $value;
			}
		}

		return $api->subscribe( $id, $data, $module, $orig_data, $sign_up_to );
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}
	
	public static function _get_api_url( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'url', self::SLUG );
	}

	private static function _get_api_list_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	/**
	 * Get the stored form_id
	 * 
	 * @since 3.0.7
	 *
	 * @param Hustle_Module_Model $module
	 * @return string
	 */
	private static function get_api_form_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'form_id', self::SLUG );
	}

	/**
	 * Get if the subscription should be related to a form or a list
	 *
	 * @since 3.0.7
	 * 
	 * @param Hustle_Module_Model $module
	 * @return string
	 */
	public static function get_sign_up_to( Hustle_Module_Model $module ) {
		$sign_up_to = 'list';
		$saved_sign_up_to = self::get_provider_details( $module, 'sign_up_to', self::SLUG );
		if ( $saved_sign_up_to && !empty( $saved_sign_up_to ) && 'list' !== $saved_sign_up_to ) {
			$sign_up_to = 'form';
		}
		return $sign_up_to;
	}

	public static function add_values_to_previous_optins( $option, $module  ){
		if( "activecampaign" !== $module->optin_provider ) return $option;

		if( "optin_username_id" === $option['id'] && isset( $module->provider_args->username ) ){
			$option['elements']['optin_username_field']['value'] = $module->provider_args->username;
		}

		return $option;
	}

	/**
	 * Prevents default selected list from showing
	 *
	 * @param $val
	 * @param $module Hustle_Module_Model
	 * @return bool
	 */
	public static function show_selected_list(  $val, $module  ){
		if( self::SLUG !== $module->optin_provider ) return true;
		return false;
	}

	/**
	 * Renders selected list row
	 *
	 * @param $module Hustle_Module_Model
	 */
	public static function render_selected_list( $module ){
		if( self::SLUG !== $module->optin_provider || !$module->optin_mail_list ) return;
		printf( esc_attr__("Selected audience list: %s (Press the GET LISTS button to update value)", Opt_In::TEXT_DOMAIN), esc_attr( $module->optin_mail_list ) );
	}

	public static function add_custom_field( $fields, $module_id ) {
		$module     = Hustle_Module_Model::instance()->get( $module_id );
		$api_key    = self::_get_api_key( $module );
		$ac_url     = self::_get_api_url( $module );
		$list_id    = self::_get_api_list_id( $module );

		$api        = self::api( $ac_url, $api_key );
		
		$available_fields = array( 'first_name', 'last_name', 'email' );
		
		foreach ( $fields as $field ) {
			if ( ! in_array( $field['name'], $available_fields, true ) ) {
				$custom_field = array( $field['name'] => $field['label'] );
				$api->add_custom_fields( $custom_field, $list_id, $module );
			}
		}

		return array( 
			'success' => true, 
			'fields' => $fields,
		);
	}
}

add_filter("wpoi_optin_filter_optin_options",  array( "Hustle_Activecampaign", "add_values_to_previous_optins" ), 10, 2 );
add_filter("wpoi_optin_activecampaign_show_selected_list",  array( "Hustle_Activecampaign", "show_selected_list" ), 10, 2 );
add_action("wph_optin_show_selected_list_after",  array( "Hustle_Activecampaign", "render_selected_list" ) );

endif;
