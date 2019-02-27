<?php

if( !class_exists("Hustle_SendGrid") ):

class Hustle_SendGrid extends Hustle_Provider_Abstract {

	const SLUG = "sendgrid";

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
	protected $_slug 				   = 'sendgrid';

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
	protected $_title                  = 'SendGrid';

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
	protected $_form_settings = 'Hustle_SendGrid_Form_Settings';

	public function __construct() {
		$this->_icon = plugin_dir_path( __FILE__ ) . 'views/icon.php';

		if( ! class_exists( 'Hustle_SendGrid_Api' ) ) {
			include_once 'hustle-sendgrid-api.php';
		}
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

	public static function api( $api_key = '' ) {
		if( ! class_exists( 'Hustle_SendGrid_Api' ) ) {
			include_once 'hustle-sendgrid-api.php';
		}
		try {
			return new Hustle_SendGrid_Api( $api_key );
		} catch ( Exception $e ) {
			return $e;
		}
	}

	/**
	 * Adds contact to the the SendGrid
	 *
	 * @param Hustle_Module_Model $module
	 * @param array $data
	 * @return mixed|bool|WP_Error
	 */
	public function subscribe( Hustle_Module_Model $module, array $data ) {
		$api_key 	= self::_get_api_key( $module );
		$list_id 	= self::_get_list_id( $module );

		$err 		= new WP_Error();
		$api 		= self::api( $api_key );
		$email 		= $data['email'];

		$existing_member = $api->email_exists( $email, $list_id );
		if ( $existing_member ) {
			$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
			return $err;
		}

		if ( empty( $data['first_name'] ) && isset( $data['f_name'] ) ) {
			$data['first_name'] = $data['f_name']; // Legacy
		}

		if ( empty( $data['last_name'] ) && isset( $data['l_name'] ) ) {
			$data['last_name'] = $data['l_name']; // Legacy
		}
		unset( $data['f_name'], $data['f_name'] );

		$res =  $api->create_and_add_recipient_to_list( $list_id, $data );
		if ( !is_wp_error( $res ) ) {
			return true;
		} else {
			$data['error'] 	= $res->get_error_message();
			$module->log_error( $data );
		}

		return $err;
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}

	private static function _get_list_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function add_custom_field( $fields, $module_id ) {
		$module 	= Hustle_Module_Model::instance()->get( $module_id );
		$api_key    = self::_get_api_key( $module );
		$api = self::api( $api_key );
		foreach ( $fields as $field ) {
			$type = strtolower( $field['type'] );
			if ( !in_array( $type, array( 'text', 'number', 'date' ), true ) ) {
				$type = 'text';
			}
			$api->add_custom_field( array(
				"name"	=> strtolower( $field['name'] ),
				"type"  => $type,
			) );
		}
	}
}

endif;
