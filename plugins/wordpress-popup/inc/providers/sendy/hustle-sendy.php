<?php
if( !class_exists("Hustle_Sendy") ):

class Hustle_Sendy extends Hustle_Provider_Abstract {

	const SLUG = "sendy";
	//const NAME = "Sendy";

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
	protected $_slug 				   = 'sendy';

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
	protected $_title                  = 'Sendy';

	/**
	 * Class name of form settings
	 *
	 * @var string
	 */
	protected $_form_settings = 'Hustle_Sendy_Form_Settings';

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
	 * Adds contact to the the campaign
	 *
	 * @param Hustle_Module_Model $module
	 * @param array $data
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe( Hustle_Module_Model $module, array $data ) {
		$api_key    		= self::_get_api_key( $module );
		$email_list 		= self::_get_email_list( $module );
		$installation_url 	= self::_get_api_url( $module );

		$err 				= new WP_Error();
		$_data = array(
			"boolean" => 'true',
			"list" => $email_list
		);
		$_data['email'] =  $data['email'];

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
			$_data['name'] = implode(" ", $name);

		// Add extra fields
		$extra_fields = array_diff_key( $data, array(
			'email' => '',
			'first_name' => '',
			'last_name' => '',
			'f_name' => '',
			'l_name' => '',
		) );
		$extra_fields = array_filter( $extra_fields );

		if ( ! empty( $extra_fields ) ) {
			$_data = array_merge( $_data, $extra_fields );
		}

		if ( !empty( $installation_url ) ) {
			$url = trailingslashit( $installation_url ) . "subscribe";
		} else {
			$err->add( 'broke', __( 'Empty installation url', Opt_In::TEXT_DOMAIN ) );
			return $err;
		}

		$res = wp_remote_post( $url, array(
			"header" => 'Content-type: application/x-www-form-urlencoded',
			"body" => $_data
		));
		
		if ( is_wp_error( $res ) ) {
			return $res;
		}

		if ( $res['response']['code'] <= 204 ) {
			if ( 'Already subscribed.' === $res['body'] ) {
				$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
				return $err;
			} elseif ( 'Invalid list ID.' === $res['body'] ) {
				$err->add( 'invalid_list_id', __( 'Invalid list id.', Opt_In::TEXT_DOMAIN ) );
				return $err;
			} elseif ( 'Some fields are missing.' === $res['body'] ) {
				$err->add( 'field_missing', __( 'Some fields are missing.', Opt_In::TEXT_DOMAIN ) );
				return $err;
			} elseif ( 'Invalid email address.' === $res['body'] ) {
				$err->add( 'invalid_email', __( 'Invalid email address.', Opt_In::TEXT_DOMAIN ) );
				return $err;
			}
			return true;
		} else {
			$err->add( $res['response']['code'], $res['response']['message']  );
			return $err;
		}
	}

	public static function _get_api_url( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'installation_url', self::SLUG );
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}

	public static function _get_email_list( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	/**
	 *
	 *
	 * @param $module Hustle_Module_Model
	 * @return bool
	 */
	public static function show_selected_list( $val, $module ){
		if( self::SLUG === $module->content->active_email_service )
			return false;

		return true;
	}

	public static function add_values_to_previous_optins( $option, $module  ){
		if( self::SLUG !== $module->content->active_email_service ) return $option;

		$list   = self::_get_email_list( $module );
		$url    = self::_get_api_url( $module );

		if( "wpoi-sendy-get-lists" === $option['id'] ){
			$option['elements']['choose_email_list']['value'] = $list;
		}

		if( "wpoi-sendy-installation-url" === $option['id'] && isset( $url ) ){
			$option['elements']['installation_url']['value'] = $url;
		}

		return $option;
	}
}

add_filter("wpoi_optin_sendy_show_selected_list",  array( "Hustle_Sendy", "show_selected_list" ), 10, 2 );
add_filter("wpoi_optin_filter_optin_options",  array( "Hustle_Sendy", "add_values_to_previous_optins" ), 10, 2 );

endif;
