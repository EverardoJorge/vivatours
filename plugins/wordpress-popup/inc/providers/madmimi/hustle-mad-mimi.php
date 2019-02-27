<?php

if( !class_exists("Hustle_Mad_Mimi") ):

include_once 'hustle-mad-mimi-api.php';

class Hustle_Mad_Mimi extends Hustle_Provider_Abstract {
	
	const SLUG = "mad_mimi";
	//const NAME = "Mad Mimi";

	/**
	 * @var $api Mad Mimi
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
	protected $_slug 				   = 'mad_mimi';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_version				   = '1.0';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_class 				   = __CLASS__;

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_title                  = 'Mad Mimi';

	/**
	 * @since 3.0.5
	 * @var bool
	 */
	protected $_supports_fields 	   = true;

	/**
	 * Class name of form settings
	 *
	 * @var string
	 */
	protected $_form_settings = 'Hustle_Mad_Mimi_Form_Settings';

	/**
	 * Provider constructor.
	 */	
	public function __construct() {
		$this->_icon = plugin_dir_url( __FILE__ ) . 'assets/icon-madmimi.png';
		$this->_icon_x2 = plugin_dir_url( __FILE__ ) . 'assets/icon-madmimi.png';
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
	 * @return Hustle_Mad_Mimi_Api
	 */
	public static function api( $username, $api_key ){

		if( empty( self::$api ) ){
			try {
				self::$api = new Hustle_Mad_Mimi_Api( $username, $api_key );
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

		$d = array();
		$d['email'] =  $data['email'];
		
		$api_key 	= self::_get_api_key( $module );
		$username 	= self::_get_username( $module );
		$list_id 	= self::_get_email_list( $module );

		if ( $this->email_exist( $d['email'], $api_key, $username, $list_id ) ) {
			$err = new WP_Error();
			$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
			return $err;
		}

		$name = array();

		if ( ! empty( $data['first_name'] ) ) {
			$name['first_name'] = $data['first_name'];
		}
		elseif ( ! empty( $data['f_name'] ) ) {
			$name['first_name'] = $data['f_name']; // Legacy
		}
		if ( ! empty( $data['last_name'] ) ) {
			$name['last_name'] = $data['last_name'];
		}
		elseif ( ! empty( $data['l_name'] ) ) {
			$name['last_name'] = $data['l_name']; // Legacy
		}

		if( count( $name ) )
			$d['name'] = implode(" ", $name);

		// Add extra fields
		$data = array_diff_key( $data, array(
			'email' => '',
			'first_name' => '',
			'last_name' => '',
			'f_name' => '',
			'l_name' => '',
		) );
		$data = array_filter( $data );

		if ( ! empty( $data ) ) {
			$d = array_merge( $d, $data );
		}

		$res = self::api( $username, $api_key )->subscribe( $list_id, $d );

		if ( is_wp_error( $res ) ) {
			$error_code = $res->get_error_code();
			$data['error'] = $res->get_error_message( $error_code );
			$module->log_error( $data );
		}

		return $res;
	}


	/**
	 * Validate if email already subscribe
	 *
	 * @param $email string - Current guest user email address.
	 * @param $module object - Hustle_Module_Model
	 * 
	 * @return bool Returns true if the specified email already subscribe otherwise false.
	 */
	public function email_exist( $email, $api_key, $username, $list_id ) {
		$api = self::api( $username, $api_key );
		$res = $api->search_by_email( $email );

		if ( is_object( $res ) && ! empty( $res->member ) && $email === $res->member->email ) {
			$_lists = $api->search_email_lists( $email );
			if( !is_wp_error( $_lists ) && !empty( $_lists ) ) {
				if ( !is_array( $_lists ) ) {
					$_lists = array( $_lists );
				}
				foreach( $_lists as $list ){
					$list = (object) (array) $list;
					$list = $list->{'@attributes'};
					if ( $list['id'] === $list_id ) {
						return true;
					}
				}
			}
			
		}
		return false;
	}

	private static function _get_email_list( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}

	public static function _get_username( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'username', self::SLUG );
	}


	public static function add_values_to_previous_optins( $option, $module  ){
		if( self::SLUG !== $module->content->active_email_service ) return $option;
		
		$username = self::_get_username( $module );

		if( "optin_username_id" === $option['id'] && isset( $username ) ){
			$option['elements']['optin_username_field']['value'] = $username;
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
		return ( self::SLUG !== $module->content->active_email_service );
	}

	/**
	 * Renders selected list row
	 *
	 * @param $module Hustle_Module_Model
	 */
	public static function render_selected_list( $module ){
		$list_id = self::_get_email_list( $module );
		if( self::SLUG !== $module->content->active_email_service || !$list_id ) return;
		printf( esc_attr__("Selected audience list: %s (Press the GET LISTS button to update value)", Opt_In::TEXT_DOMAIN), esc_attr( $list_id ) );
	}
}

	add_filter("wpoi_optin_filter_optin_options",  array( "Hustle_Mad_Mimi", "add_values_to_previous_optins" ), 10, 2 );
	add_filter("wpoi_optin_mad_mimi_show_selected_list",  array( "Hustle_Mad_Mimi", "show_selected_list" ), 10, 2 );
	add_action("wph_optin_show_selected_list_after",  array( "Hustle_Mad_Mimi", "render_selected_list" ) );
endif;
