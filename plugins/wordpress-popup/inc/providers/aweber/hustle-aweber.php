<?php
if( !class_exists("Hustle_Aweber") ):

if( !class_exists( "AWeberAPI" ) )
	require_once Opt_In::$vendor_path . 'aweber/aweber/aweber_api/aweber_api.php';

class Hustle_Aweber extends Hustle_Provider_Abstract {

	const SLUG = "aweber";
	//const NAME = "AWeber";

	const APP_ID = 'b0cd0152';

	const AUTH_CODE = "aut_code";
	const CONSUMER_KEY = "consumer_key";
	const CONSUMER_SECRET = "consumer_secret";
	const ACCESS_TOKEN = "access_token";
	const ACCESS_SECRET = "access_secret";

	/**
	 * @var $api AWeberAPI
	 */
	protected  static $api;
	protected  static $errors;

	/**
	 * Aweber Provider Instance
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
	protected $_slug = 'aweber';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_version = '1.0';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_class = __CLASS__;

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_title = 'Aweber';

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
	protected $_form_settings = 'Hustle_Aweber_Form_Settings';

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
	 * Helper function get an option in static mode.
	 **/
	public static function static_get_option( $option_key, $default ) {
		return get_site_option( self::SLUG . "_" . $option_key, $default );
	}

	/**
	 * @param $api_key
	 * @param $secret
	 * @return AWeberAPI
	 */
	public static function api( $api_key, $secret ){

		if( empty( self::$api ) ){
			try {
				self::$api = new AWeberAPI( $api_key, $secret );
				self::$errors = array();
			} catch ( AWeberException $e ) {
				self::$errors = array("api_error" => $e) ;
			}

		}
		self::$api->adapter->debug = false;
		return self::$api;
	}

	public function subscribe( Hustle_Module_Model $module, array $data  ){

		$account = $this->get_account();
		if ( ! $account ) {
			return false;
		}

		$account_id =  isset( $account->data, $account->data['id'] ) ? $account->data['id'] : false;
		if( !$account_id )
			return false;

		$list_id   = self::_get_list_id( $module );

		try {
			$url = "/accounts/{$account_id}/lists/{$list_id}";
			$list = $account->loadFromUrl($url);
			$subscribe_data = $data;
			$name = array();

			if ( ! empty( $data['first_name'] ) ) {// Check first_name field first
				$name['first_name'] = $data['first_name'];
				unset( $subscribe_data['first_name'] );
			}
			elseif ( ! empty( $data['f_name'] ) ) {// Legacy field name
				$name['first_name'] = $data['f_name'];
				unset( $subscribe_data['f_name'] );
			}
			if ( ! empty( $data['last_name'] ) ) { // Add last_name
				$name['last_name'] = $data['last_name'];
				unset( $subscribe_data['last_name'] );
			}
			elseif ( ! empty( $data['l_name'] ) ) {// Check legacy f_name
				$name['last_name'] = $data['l_name'];
				unset( $subscribe_data['l_name'] );
			}
			$subscribe_data['name'] = implode( ' ', $name );
			$custom_fields = array_diff_key( $data, array(
				'first_name' => '',
				'last_name' => '',
				'l_name' => '',
				'f_name' => '',
				'email' => '',
			) );

			if ( ! empty( $custom_fields ) ) {
				$subscribe_data['custom_fields'] = array();

				foreach ( $custom_fields as $key => $value ) {

					//$field = $module->get_custom_field( 'name', $key );
					$api_custom_fields = $list->custom_fields;
					//$name = $field['name'];
					//$subscribe_data['custom_fields'][ $name ] = $value;
					$subscribe_data['custom_fields'][ $key ] = $value;
					unset( $subscribe_data[ $key ] );
				}
			}

			$err = new WP_Error();
			$find_by_email = $list->subscribers->find( array( 'email' => $subscribe_data['email'] ) );

			if ( ! empty( $find_by_email ) && ! empty( $find_by_email->data ) && ! empty( $find_by_email->data['entries'] ) ) {
				$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
				return $err;
			}

			$subscriber = $list->subscribers->create($subscribe_data);

			if ( empty( $subscriber ) ) {
				$data['error'] = __( 'Something went wrong. Unable to add subscriber', Opt_In::TEXT_DOMAIN );
				$module->log_error( $data );
				Hustle_Api_Utils::maybe_log( 'Something went wrong. Unable to add subscriber' );
			} else if( ! empty( $subscriber->data ) && ! empty( $subscribe_data['custom_fields'] ) ) {
				// Let's double check if all custom fields are successfully added
				$found_missing_field = 0;

				foreach ( array_filter( $subscribe_data['custom_fields'] ) as $label => $field ) {
					if ( ! isset( $subscriber->data['custom_fields'][ $label ] ) || empty( $subscriber->data['custom_fields'][ $label ] ) ) {
						$found_missing_field++;
					}
				}

				if ( $found_missing_field > 0 ) {
					$data['error'] = __( 'Some fields are not successfully added.', Opt_In::TEXT_DOMAIN );
					$module->log_error( $data );
					Hustle_Api_Utils::maybe_log( 'Some fields were not successfully added.' );
				}
			}

			return $subscriber;

		} catch(Exception $e) {
			Hustle_Api_Utils::maybe_log( $e->getMessage() );
			self::$errors['subcription'] = $e;
			return $e;
		}
	}

	/**
	 * Gets the Aweber account object, instance of AWeberEntry
	 * 
	 * @since 3.0.6
	 *
	 */
	public function get_account( $api_key = null ) {

		if ( ! is_null( $api_key ) && $this->get_provider_option( self::AUTH_CODE, '' ) !== $api_key ) {
			
			// Check if API key is valid
			try {
				$aweber_data = AWeberAPI::getDataFromAweberID( $api_key );
			} catch ( AWeberException $e ) {
				Hustle_Api_Utils::maybe_log( $e->message );
				return false;
			}
			
			list($consumer_key, $consumer_secret, $access_token, $access_secret) = $aweber_data;

			$this->update_provider_option( self::CONSUMER_KEY, $consumer_key );
			$this->update_provider_option( self::CONSUMER_SECRET, $consumer_secret );
			$this->update_provider_option( self::ACCESS_TOKEN, $access_token );
			$this->update_provider_option( self::ACCESS_SECRET, $access_secret );

			$this->update_provider_option( self::AUTH_CODE, $api_key );

		} else {
			$consumer_key = $this->get_provider_option( self::CONSUMER_KEY, '' );
			$consumer_secret = $this->get_provider_option( self::CONSUMER_SECRET, '' );
			$access_token = $this->get_provider_option( self::ACCESS_TOKEN, '' );
			$access_secret = $this->get_provider_option( self::ACCESS_SECRET, '' );
		}

		// Check if account is valid
		try {
			$account = self::api( $consumer_key, $consumer_secret )->getAccount( $access_token, $access_secret );
		} catch ( AWeberException $e ) {
			Hustle_Api_Utils::maybe_log( $e->message );
			return false;
		}

		return $account;

	}
	
	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}
	
	private static function _get_list_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function add_custom_field( $fields, $module_id ) {
		$consumer_key    = self::static_get_option( self::CONSUMER_KEY, false );
		$consumer_secret = self::static_get_option( self::CONSUMER_SECRET, false );
		$access_token    = self::static_get_option( self::ACCESS_TOKEN, false );
		$access_secret   = self::static_get_option( self::ACCESS_SECRET, false );

		$module         = Hustle_Module_Model::instance()->get( $module_id );
		$services       = $module->get_content()->email_services;
		$list_id        = "";

		if( !is_null( $services ) && isset( $services['aweber'] ) ) {
			if ( isset( $services['aweber']['list_id'] ) ) {
				$list_id = $services['aweber']['list_id'];
			}
		}

		if( $consumer_key && $consumer_secret && $access_token && $access_secret && $list_id ) {
			$api = self::api( $consumer_key, $consumer_secret );
			$account =  $api->getAccount($access_token, $access_secret);
			$account_id =  isset( $account->data, $account->data['id'] ) ? $account->data['id'] : false;

			if( $account_id ) {
				$url = "/accounts/{$account_id}/lists/{$list_id}";
				$list = $account->loadFromUrl($url);
				$custom_fields = $list->custom_fields;

				foreach ( $fields as $field ) {
					$exist = false;
					$name = $field['name'];

					if ( $custom_fields && ! empty( $custom_fields->data ) && ! empty( $custom_fields->data['entries'] ) ) {

						foreach ( $custom_fields->data['entries'] as $custom_field ) {
							if ( $custom_field['name'] === $name ) {
								$exist = true;
							}
						}
					}

					if ( false === $exist ) {
						// Create custom field
						$custom_field = array( 'name' => $name );
						try{
							$list->custom_fields->create( array( 'name' => 'yup') );
						} catch( AWeberAPIException $exc ){
							Hustle_Api_Utils::maybe_log( $exc->type . '. ' . $exc->message . '. ' . $exc->documentation_url );
						}	
						$exist = true;
					}
				}
			}
		}

		if ( $exist ) {
			return array( 
				'success' => true, 
				'fields' => $fields,
			);
		}

		return array( 
			'error' => true, 
			'code' => 'cannot_create_custom_field',
		);
	}
}
endif;
