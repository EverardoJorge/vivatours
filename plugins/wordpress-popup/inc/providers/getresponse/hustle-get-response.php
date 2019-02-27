<?php

if( !class_exists("Hustle_Get_Response") ):

include_once 'hustle-get-response-api.php';

/**
 * Defines and adds neeed methods for GetResponse email service provider
 *
 * Class Hustle_Get_Response
 */
class Hustle_Get_Response extends Hustle_Provider_Abstract {

	const SLUG = "getresponse";
	//const NAME = "GetResponse";


	/**
	 * @var $api GetResponse
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
	protected $_slug 				   = 'getresponse';

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
	protected $_title                  = 'GetResponse';

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
	protected $_form_settings = 'Hustle_Get_Response_Form_Settings';

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
	 * @param $api_key
	 * @return Hustle_Get_Response_Api
	 */
	//protected static function api( $api_key ){
	public static function api( $api_key ){

		if( empty( self::$api ) ){
			try {
				self::$api = new Hustle_Get_Response_Api( $api_key, array("debug" => true) );
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
		$list_id    = self::_get_email_list( $module );

		$email =  $data['email'];

		$geo = new Opt_In_Geo();

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

		$new_data = array(
			'email'         => $email,
			"dayOfCycle"    => apply_filters( "hustle_optin_get_response_cycle", "0" ),
			'campaign'      => array(
				"campaignId" => $list_id
			),
			"ipAddress"     => $geo->get_user_ip()
		);

		if( count( $name ) )
			$new_data['name'] = implode(" ", $name);

		// Extra fields
		$extra_data = array_diff_key( $data, array(
			'email' => '',
			'first_name' => '',
			'last_name' => '',
			'f_name' => '',
			'l_name' => '',
		) );
		$extra_data = array_filter( $extra_data );

		if ( ! empty( $extra_data ) ) {
			$new_data['customFieldValues'] = array();

			foreach ( $extra_data as $key => $value ) {
				$meta_key = 'gr_field_' . $key;
				$custom_field_id = $module->get_meta( $meta_key );
				$custom_field = array(
					'name' => $key,
					'type' => 'text', // We only support text for now
					'hidden' => false,
					'values' => array(),
				);

				if ( empty( $custom_field_id ) ) {
					$custom_field_id = self::api( $api_key )->add_custom_field( $custom_field );

					if ( ! empty( $custom_field_id ) ) {
						$module->add_meta( $meta_key, $custom_field_id );
					}
				}
				$new_data['customFieldValues'][] = array(
					'customFieldId' => $custom_field_id, 
					'value' => array( $value ),
				);
			}
		}

		$res = self::api( $api_key )->subscribe( $new_data );

		if ( is_wp_error( $res ) ) {
			$error_code = $res->get_error_code();
			$error_message = $res->get_error_message( $error_code );

			if ( preg_match( '%Conflict%', $error_message ) ) {
				$res->add( $error_code, __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
			} else {
				$data['error'] = $error_message;
				$module->log_error( $data );
			}
		}

		return $res;
	}

	private static function _get_email_list( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}

	public static function add_custom_field( $fields, $module_id ) {

		$module     = Hustle_Module_Model::instance()->get( $module_id );
		$api_key    = self::_get_api_key( $module );

		$api = self::api( $api_key );
		$api_fields = $api->get_custom_fields();

		foreach ( $fields as $field ) {
			$type = ! in_array( $field['type'], array( 'text', 'number' ), true ) ? 'text' : $field['type'];
			$key = $field['name'];
			$exist = false;

			// Check for existing custom fields
			if ( ! is_wp_error( $api_fields ) && is_array( $api_fields ) ) {
				foreach ( $api_fields as $custom_field ) {
					$name = $custom_field->name;
					$custom_field_id = $custom_field->customFieldId; // phpcs:ignore
					$meta_key = "gr_field_{$name}";

					// Update meta
					$module->add_meta( $meta_key, $custom_field_id );

					if ( $name === $key ) {
						$exist = true;
					}
				}
			}

			// Add custom field if it doesn't exist
			if ( false === $exist ) {
				$custom_field = array(
					'name' => $key,
					'type' => $type,
					'hidden' => false,
					'values' => array(),
				);
				$custom_field_id = $api->add_custom_field( $custom_field );
				$module->add_meta( "gr_field_{$key}", $custom_field_id );
			}
		}

		return array(
			'success' => true, 
			'field' => $field,
		);
	}
}

endif;
