<?php
if ( ! class_exists( 'Hustle_ConvertKit' ) ) :

include_once 'hustle-convertkit-api.php';

/**
 * Convertkit Email Integration
 *
 * @class Hustle_ConvertKit
 * @version 2.0.3
 **/
class Hustle_ConvertKit extends Hustle_Provider_Abstract {
	
	const SLUG = "convertkit";
	//const NAME = "ConvertKit";
	
	/**
	* @var $api ConvertKit
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
	protected $_slug 				   = 'convertkit';

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
	protected $_title                  = 'ConvertKit';

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
	protected $_form_settings = 'Hustle_ConvertKit_Form_Settings';

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
	* @return Hustle_ConvertKit_Api
	*/
	public static function api( $api_key, $api_secret = '' ){

		if( empty( self::$api ) ){
			try {
				self::$api = new Hustle_ConvertKit_Api( $api_key, $api_secret );
				self::$errors = array();
			} catch (Exception $e) {
				self::$errors = array("api_error" => $e) ;
			}

		}
		return self::$api;
	}
	
	/**
	* Adds subscribers to the form
	*
	* @param Hustle_Module_Model $module
	* @param array $data
	* @return array|mixed|object|WP_Error
	*/
	public function subscribe( Hustle_Module_Model $module, array $data ) {

		$api_secret = self::_get_api_secret( $module );
		$api_key 	= self::_get_api_key( $module );
		$list_id 	= self::_get_email_list( $module );

		if ( !isset($data['email']) ) return false;

		$err = new WP_Error();
		if ( $this->email_exist( $data['email'], $api_key, $api_secret, $list_id ) ) {
			$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
			return $err;
		}

		// deal with custom fields first
		$custom_fields = array(
			'ip_address' => array(
				'label' => 'IP Address'
			)
		);
		// Extra fields
		$additional_fields = array_diff_key( $data, array(
			'email' => '',
			'first_name' => '',
			'f_name' => '',
		) );
		$additional_fields = array_filter( $additional_fields );
		$subscribe_data_fields = array();

		if ( $additional_fields && is_array( $additional_fields ) && count( $additional_fields ) > 0 ) {
			foreach( $additional_fields as $field_name => $value ) {

				// skip defaults
				if ( 'first_name' === $field_name || 'email' === $field_name ) {
					continue;
				}
				$meta_key 	= 'cv_field_' . $field_name;
				$meta_value = $module->get_meta( $meta_key );

				if ( ! $meta_value || $meta_value !== $field_name ) {
					$custom_fields[$field_name] = array(
						'label' => $field_name
					);
				}

				if ( isset($data[$field_name]) ) {
					$subscribe_data_fields[$field_name] = $data[$field_name];
				}
			}
		}


		if ( ! $this->maybe_create_custom_fields( $module, $custom_fields ) ) {
			$data['error'] = __( 'Unable to add custom field.', Opt_In::TEXT_DOMAIN );
			$module->log_error( $data );
			$err->add( 'server_error', __( 'Something went wrong. Please try again.', Opt_In::TEXT_DOMAIN ) );
			return $err;
		}

		// subscription
		$name = '';
		if ( isset( $data['first_name'] ) ) {
			$name = $data['first_name'];	
		} else if ( isset( $data['f_name'] ) ) {
			$name = $data['f_name'];
		}
		$geo = new Opt_In_Geo();
		$subscribe_data = array(
			"api_key" 	=> $api_key,
			"name" 		=> $name,
			"email" 	=> $data['email'],
			"fields" 	=> array(
				"ip_address" => $geo->get_user_ip()
			)
		);
		$subscribe_data['fields'] = wp_parse_args( $subscribe_data_fields, $subscribe_data['fields'] );

		$res = self::api( $api_key )->subscribe( $list_id, $subscribe_data );

		if ( is_wp_error( $res ) ) {
			$error_code = $res->get_error_code();
			$data['error'] = $res->get_error_message( $error_code );
			$module->log_error( $data );
		}

		return $res;
	}

	public function email_exist( $email, $api_key, $api_secret, $list_id ) {
		$api = self::api( $api_key, $api_secret );
		$subscriber =  $api->is_form_subscriber( $email, $list_id );
		return $subscriber;
	}

	public function exclude_args_fields() {
		return array( 'api_key', 'api_secret' );
	}

	/**
	* Creates necessary custom fields for the form
	*
	* @param Hustle_Module_Model $module
	* @return array|mixed|object|WP_Error
	*/
	public function maybe_create_custom_fields( Hustle_Module_Model $module, array $fields ) {
		$api_secret = self::_get_api_secret( $module );
		$api_key 	= self::_get_api_key( $module );
		
		// check if already existing
		$custom_fields = self::api( $api_key, $api_secret )->get_form_custom_fields();
		$proceed = true;
		foreach( $custom_fields as $custom_field ) {
			if ( isset( $fields[$custom_field->key] ) ) {
				unset($fields[$custom_field->key]);
			}
		}
		// create necessary fields
		// Note: we don't delete fields here, let the user do it on ConvertKit app.convertkit.com
		$api = self::api( $api_key );
		foreach( $fields as $key => $field ) {
			$add_custom_field = $api->create_custom_fields( array(
				'api_secret' => $api_secret,
				'label' => $field['label'],
			) );
			if ( is_wp_error($add_custom_field) ) {
				$proceed = false;
				break;
			}
		}

		return $proceed;
	}

	private static function _get_email_list( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}

	public static function _get_api_secret( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_secret', self::SLUG );
	}

	/**
	 * Add Custom Fields
	 *
	 * @param Array - fields
	 * @param Integer - module id
	 */
	public static function add_custom_field( $fields, $module_id ) {

		$module 	= Hustle_Module_Model::instance()->get( $module_id );
		$api_secret = self::_get_api_secret( $module );
		$api_key 	= self::_get_api_key( $module );

		$api = self::api( $api_key );
		$custom_fields = self::api( $api_key, $api_secret )->get_form_custom_fields();

		foreach ( $fields as $field ) {
			$exist = false;
			
			if ( ! empty( $custom_fields ) ) {
				foreach ( $custom_fields as $custom_field ) {
					if ( $field['name'] === $custom_field->key ) {
						$exist = true;
					}
					// Save the key in meta
					$module->add_meta( 'cv_field_' . $custom_field->key, $custom_field->label );
				}
			}

			if ( false === $exist ) {
				$add = $api->create_custom_fields( array(
					'api_secret' => $api_secret,
					'label' => $field['label'],
				) );

				if ( ! is_wp_error( $add ) ) {
					$exist = true;
					$module->add_meta( 'cv_field_' . $field['name'], $field['label'] );
				}
			}
		}

		if ( $exist ) {
			return array(
				'success' => true,
				'field' => $fields,
			);
		}

		return array(
			'error' => true,
			'code' => 'cannot_create_custom_field',
		);
	}
}

endif;
