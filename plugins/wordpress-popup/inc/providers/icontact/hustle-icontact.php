<?php
if ( ! class_exists( 'Hustle_Icontact' ) ) :

class Hustle_Icontact extends Hustle_Provider_Abstract {

	const SLUG = "icontact";
	//const NAME = "IContact";

	protected static $api;

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
	protected $_slug 				   = 'icontact';

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
	protected $_title                  = 'iContact';

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
	protected $_form_settings = 'Hustle_Icontact_Form_Settings';

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
	 * API Set up
	 *
	 * @param String $_app_id - the application id
	 * @param String $_api_password - the api password
	 * @param String $_api_username - the api username
	 *
	 * @return WP_Error|Object
	 */
	public static function api( $app_id, $api_password, $api_username ) {
		if ( ! class_exists( 'Hustle_Icontact_Api' ) )
			require_once 'hustle-icontact-api.php';
		
		if( empty( self::$api ) ){
			try {
				self::$api = new Hustle_Icontact_Api( $app_id, $api_password, $api_username );
			} catch( Exception $e ) {
				return new WP_Error( 'something_wrong', $e->getMessage() );
			}
		}
		return self::$api;
	}

	public function subscribe( Hustle_Module_Model $module, array $data ) {
		$app_id     = self::_get_app_id( $module );
		$username   = self::_get_username( $module );
		$password   = self::_get_password( $module );
		$list_id    = self::_get_list_id( $module );
		$status 	= 'pending' === self::_get_auto_optin( $module ) ? 'pending' : 'normal';
		$confirmation_message_id = self::_get_confirmation_message_id( $module );
		$api 		= self::api( $app_id, $password, $username );
		$err 		= new WP_Error();
		$err->add( 'something_wrong', __( 'Something went wrong. Please try again', Opt_In::TEXT_DOMAIN ) );
		if ( !is_wp_error( $api ) ) {
			$email = $data['email'];
			$merge_vals = array();

			if ( isset( $data['first_name'] ) ) {
				$merge_vals['firstName'] = $data['first_name'];
			}
			elseif ( isset( $data['f_name'] ) ) {
				$merge_vals['firstName'] = $data['f_name']; // Legacy
			}
			if ( isset( $data['last_name'] ) ) {
				$merge_vals['lastName'] = $data['last_name'];
			}
			elseif ( isset( $data['l_name'] ) ) {
				$merge_vals['lastName'] = $data['l_name']; // Legacy
			}

			// Add extra fields
			$merge_data = array_diff_key( $data, array(
				'email' => '',
				'first_name' => '',
				'last_name' => '',
				'f_name' => '',
				'l_name' => '',
			) );
			$merge_data = array_filter( $merge_data );

			if ( ! empty( $merge_data ) ) {
				$merge_vals = array_merge( $merge_vals, $merge_data );
			}

			if ( $this->_is_subscribed( $api, $list_id, $email ) ) {
				$err = new WP_Error();
				$err->add( 'email_exist', __( 'This email address has already subscribed', Opt_In::TEXT_DOMAIN ) );
				return $err;
			}
			$subscribe_data = array(
				'email'     => $email,
				'status'    => 'normal'
			);
			$subscribe_data = array_merge( $subscribe_data, $merge_vals );

			$response = $api->add_subscriber( $list_id, $subscribe_data, $status, $confirmation_message_id );
			if ( !is_wp_error( $response ) ) {
				return true;
			} else {
				$data['error'] = $response->get_error_message();
				$optin->log_error( $data );
			}
		} else {
			$data['error'] 	= $api->get_error_message();
			$module->log_error( $data );
		}
		return $err;
	}

	/**
	 * Check if email is already subcribed to list
	 */
	private function _is_subscribed( $api, $list_id, $email ) {
		$contacts = $api->get_contacts( $list_id );
		if ( !is_wp_error( $contacts ) ) {
			if ( is_array( $contacts ) && isset( $contacts['contacts'] ) && is_array( $contacts['contacts'] ) ) {
				foreach ( $contacts['contacts'] as $contact ) {
					if ( $contact['email'] === $email ){
						return true;
					}
				}
			}
		}
		return false;
	}

	public static function _get_app_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'app_id', self::SLUG );
	}

	public static function _get_username( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'username', self::SLUG );
	}

	public static function _get_password( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'password', self::SLUG );
	}

	public static function _get_confirmation_message_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'confirmation_message_id', self::SLUG );
	}
	
	public static function _get_auto_optin( Hustle_Module_Model $module ) {
		$auto_optin = 'subscribed';
		$saved_auto_optin = self::get_provider_details( $module, 'auto_optin', self::SLUG );
		if ( $saved_auto_optin && !empty( $saved_auto_optin ) && 'subscribed' !== $saved_auto_optin ) {
			$auto_optin = 'pending';
		}
		return $auto_optin;
	}

	private static function _get_list_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function add_custom_field( $fields, $module_id ) {
		$module 	= Hustle_Module_Model::instance()->get( $module_id );
		$app_id     = self::_get_app_id( $module );
		$username   = self::_get_username( $module );
		$password   = self::_get_password( $module );

		$api 	= self::api( $app_id, $password, $username );

		$existed = array();
		$added = array();
		$error = array();
		foreach ( $fields as $field ) {
			$response = $api->add_custom_field( array(
				'displayToUser'  => 1,
				'privateName'    => $field['name'],
				'fieldType'      => ( 'email' === $field['type'] ) ? 'text' : $field['type']
			) );
			if ( isset( $response['customfields'] ) && isset( $response['warnings'][0] ) && is_array( $response['warnings'][0] ) ) {
				$existed[] = $field['name'];
			} else if ( isset( $response['customfields'] ) && ! empty( $response['customfields'] ) ) {
				$added[] = $field['name'];
			} else if ( isset( $response['warnings'][0] ) && ! is_array( $response['warnings'][0] ) ) {
				Hustle_Api_Utils::maybe_log( $response['warnings'][0] );
				$error[] = $field['name'];
			}
		}
		return array( 
			'success' => true,
			'field' => $fields,
			'added' => $added,
			'existed' => $existed,
			'error' => $error,
		);
	}
}

endif;
