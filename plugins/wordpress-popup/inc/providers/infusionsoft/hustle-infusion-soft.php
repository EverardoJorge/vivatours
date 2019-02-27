<?php

if( !class_exists("Hustle_Infusion_Soft") ):

include_once "hustle-infusion-soft-api.php";

class Hustle_Infusion_Soft extends Hustle_Provider_Abstract {

	const SLUG = "infusionsoft";

	const CLIENT_ID = "inc_opt_infusionsoft_clientid";
	const CLIENT_SECRET = "inc_opt_infusionsoft_clientsecret";
	const API_CODE = "inc_opt_infusionsoft_api_code";
	const API_SCOPE = "inc_opt_infusionsoft_api_scope";
	const ACCESS_TOKEN_RES = "inc_opt_infusionsoft_access_token";
	const CURRENT_PAGE_URL = "inc_opt_infusionsoft_current_page_url";

	/**
	 * @var Opt_In_Infusionsoft_Api $api
	 */
	protected  static $api;
	/**
	 * @var WP_Error $errors
	 */
	protected static $errors;

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
	protected $_slug 				   = 'infusionsoft';

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
	protected $_title                  = 'Infusionsoft';

	/**
	 * Class name of form settings
	 *
	 * @var string
	 */
	protected $_form_settings = 'Hustle_Infusion_Soft_Form_Settings';

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
	 * Returns a cached api
	 *
	 * @param $api_key
	 * @param $app_name
	 * @return Opt_In_Infusionsoft_Api
	 */
	//protected static function api( $api_key, $app_name){
	public static function api( $api_key, $app_name){

		if( empty( self::$api ) ){
			try {
				self::$errors = array();
				self::$api = new Opt_In_Infusionsoft_Api( $api_key , $app_name);
			} catch (Exception $e) {
				self::$errors = array("api_error" => $e) ;
			}

		}

		return self::$api;
	}

	public function subscribe( Hustle_Module_Model $module, array $contact ) {

		$api_key        = self::_get_api_key( $module );
		$account_name   = self::_get_account_name( $module );
		$list_id        = self::_get_email_list( $module );
		$allow_subscribed = self::get_allow_subscribed_users( $module );
	
		$api = self::api( $api_key, $account_name );

		$original_contact = $contact;

		if ( isset( $contact['email'] ) ) {
			$contact['Email'] = $contact['email'];
		}
		if ( isset( $contact['first_name'] ) ) {
			$contact['FirstName'] = $contact['first_name'];
		}
		elseif ( isset( $contact['f_name'] ) ) {
			$contact['FirstName'] = $contact['f_name']; // Legacy
		}
		if ( isset( $contact['last_name'] ) ) {
			$contact['LastName'] = $contact['last_name'];
		}
		elseif ( isset( $contact['l_name'] ) ) {
			$contact['LastName'] = $contact['l_name'];
		}
		$contact = array_diff_key( $contact, array(
			'email' => '',
			'first_name' => '',
			'last_name' => '',
			'f_name' => '',
			'l_name' => '',
		) );

		$custom_fields = $module->get_meta( 'is_custom_fields' );

		if ( empty( $custom_fields ) ) {
			$custom_fields = $api->get_custom_fields();
		} else {
			$custom_fields = json_decode( $custom_fields );
		}

		$extra_custom_fields = array_diff_key( $contact, array_fill_keys( $custom_fields, 1 ) );
		$found_extra = array();

		if ( ! empty( $extra_custom_fields ) ) {

			foreach ( $extra_custom_fields as $key => $value ) {
				$field = $module->get_custom_field( 'name', $key );
				$label = str_replace( ' ', '', ucwords( $field['label'] ) );

				// Attempt to check the label
				if ( in_array( $label, $custom_fields, true ) ) {
					$contact[ $label ] = $value;
				} else {
					$found_extra[ $key ] = $value;
				}
				unset( $contact[ $key ] );
			}
		}

		if ( ! empty( $found_extra ) ) {
			$data = $original_contact;
			$data['error'] = __( 'Some fields are not successfully added.', Opt_In::TEXT_DOMAIN );
			$module->log_error( $data );
		}

		$email_exists = $api->email_exist( $contact['Email'] );

		// If the email is already subscribed and subscribed users are allowed, update the contact.
		if ( 'allow' === $allow_subscribed && $email_exists ) {
			$contact_id = $api->update_contact( $contact );
		} else {
			$contact_id = $api->add_contact( $contact );
		}

		if( !is_wp_error( $contact_id ) ) {
			$contact_id = $api->add_tag_to_contact( $contact_id, $list_id );
			return __("Contact successfully added", Opt_In::TEXT_DOMAIN) ;
		} else {
			$error_code = $contact_id->get_error_code();

			if ( 'email_exist' !== $error_code ) {
				$original_contact['error'] = $contact_id->get_error_message( $error_code );
				$module->log_error( $original_contact );
			}

			return $contact_id;
		}
	}

	private static function _get_email_list( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}

	public static function _get_account_name( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'account_name', self::SLUG );
	}

	public static function get_allow_subscribed_users( Hustle_Module_Model $module ) {
		$allow_subscribed = 'not-allow';
		$saved_allow_subscribed = self::get_provider_details( $module, 'allow_subscribed_users', self::SLUG );
		if ( $saved_allow_subscribed && !empty( $saved_allow_subscribed ) && 'not-allow' !== $saved_allow_subscribed ) {
			$allow_subscribed = 'allow';
		}
		return $allow_subscribed;
	}

	public static function add_custom_field( $fields, $module_id ) {
		$account_name   = "";
		$api_key        = "";
		$module         = Hustle_Module_Model::instance()->get( $module_id );
		$api_key        = self::_get_api_key( $module );
		$account_name   = self::_get_account_name( $module );
		$api            = self::api( $api_key, $account_name );
		$custom_fields  = $api->get_custom_fields();

		// Update custom fields meta
		$module->add_meta( 'is_custom_fields', $custom_fields );

		foreach ( $fields as $field ) {
			// Check if custom field name exist on existing custom fields
			if ( in_array( $field['name'], $custom_fields, true ) ) {
				return array(
					'success' => true,
					'field' => $field,
				);
			}

			// Check if label can be use as name
			$label = str_replace( ' ', '', ucwords( $field['label'] ) );
			if ( in_array( $label, $custom_fields, true ) ) {
				// Replace the field name
				$field['name'] = $label;

				return array(
					'success' => true,
					'field' => $field,
				);
			}
		}

		return array(
			'error' => true,
			'code' => 'custom_field_not_exist',
		);
	}
}

endif;
