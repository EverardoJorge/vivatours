<?php
if( !class_exists("Hustle_ConstantContact") ):
	
class Hustle_ConstantContact extends Hustle_Provider_Abstract {

	const SLUG = "constantcontact";

	protected static $errors;

	/**
	 * Constant Contact Provider Instance
	 *
	 * @since 3.0.5
	 *
	 * @var self|null
	 */
	protected static $_instance 	   = null;
	
	/**
	 * @since 3.0.5
	 * @var string
	 */
	public static $_min_php_version	   = '5.3';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_slug 				   = 'constantcontact';

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
	protected $_title                  = 'Constant Contact';

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
	protected $_form_settings = 'Hustle_ConstantContact_Form_Settings';

	/**
	 * Hustle_ConstantContact constructor.
	 */	
	public function __construct() {
		$this->_icon = plugin_dir_path( __FILE__ ) . 'views/icon.php';

		if ( ! class_exists( 'Hustle_ConstantContact_Api' ) ) {
			require_once 'hustle-constantcontact-api.php';
		}
		// Instantiate the API on constructor because it's required after getting the authorization
		$hustle_constantcontact = new Hustle_ConstantContact_Api();
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
	 * @return bool|Opt_In_HubSpot_Api
	 */
	public function api() {
		return self::static_api();
	}

	public static function static_api() {
		if ( ! class_exists( 'Hustle_ConstantContact_Api' ) ){
			require_once 'hustle-constantcontact-api.php';
		}
			

		if ( class_exists( 'Hustle_ConstantContact_Api' ) ){
			$api = new Hustle_ConstantContact_Api();
			return $api;
		} else {
			return new WP_Error( 'error', __( "API Class could not be initialized", Opt_In::TEXT_DOMAIN )  );
		}

		
	}

	public function subscribe( Hustle_Module_Model $module, array $data ) {
		$err = new WP_Error();
		

		try {
			$api = $this->api();
			if ( is_wp_error( $api ) ) {
				return $api;
			}
			$email_list = self::_get_email_list( $module );
			$existing_contact = $api->email_exist( $data['email'], $email_list );
			if ( true === (bool)$existing_contact ) {
				$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
				return $err;
			} 

			$first_name = '';
			$last_name = '';

			if ( isset( $data['first_name'] ) ) {
				$first_name = $data['first_name'];
			}
			elseif ( isset( $data['f_name'] ) ) {
				$first_name = $data['f_name']; // Legacy call
			}
			if ( isset( $data['last_name'] ) ) {
				$last_name = $data['last_name'];
			}
			elseif ( isset( $data['l_name'] ) ) {
				$last_name = $data['l_name']; // Legacy call
			}

			$custom_fields = array_diff_key( $data, array(
				'email' => '',
				'first_name' => '',
				'last_name' => '',
				'f_name' => '',
				'l_name' => '',
			) );
			$custom_fields = array_filter( $custom_fields );

			if ( is_object( $existing_contact ) ) {
				$response = $api->updateSubscription( $existing_contact, $first_name, $last_name, $email_list, $custom_fields );
			} else {
				$response = $api->subscribe( $data['email'], $first_name, $last_name, $email_list, $custom_fields );
			}

			if ( isset( $response ) ) {
				self::$errors['success'] = 'success';
			    return true;
			}

		} catch ( Exception $e ) {
			$err->add( 'subscribe_error', __( 'Something went wrong. Please try again.', Opt_In::TEXT_DOMAIN ) );
			$error_message = json_decode( $e->getMessage() );

			if ( is_array( $error_message ) ) {
				$error_message = array_pop( $error_message );
				$error_message = $error_message->error_message;
			}

			$data['error'] = $error_message;

			$module->log_error( $data );
		}

		return $err;
	}

	public static function _get_email_list( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

}
endif;
