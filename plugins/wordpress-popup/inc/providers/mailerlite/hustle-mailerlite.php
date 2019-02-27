<?php
if ( ! class_exists( 'Hustle_MailerLite' ) ) :

include_once 'hustle-mailerlite-api.php';

class Hustle_MailerLite extends Hustle_Provider_Abstract {

	const SLUG = "mailerlite";
	//const NAME = "MailerLite";

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
	protected $_slug 				   = 'mailerlite';

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
	protected $_title                  = 'MailerLite';

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
	protected $_form_settings = 'Hustle_MailerLite_Form_Settings';

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

	public static function api( $api_key ) {
		$api = new Hustle_MailerLite_Api( $api_key );

		return $api;
	}

	public function subscribe( Hustle_Module_Model $module, array $data ) {

		$api_key 	= self::_get_api_key( $module );
		$list_id 	= self::_get_list_id( $module );

		$err 		= new WP_Error();
		$api 		= self::api( $api_key );

		$email 		= $data['email'];
		$merge_vals = array();

		if ( isset( $data['first_name'] ) ) {
			$merge_vals['name'] = $data['first_name'];
		} elseif ( isset( $data['f_name'] ) ) {
			$merge_vals['name'] = $data['f_name']; // Legacy
		}
		
		if ( isset( $data['last_name'] ) ) {
			$merge_vals['last_name'] = $data['last_name'];
		} elseif ( isset( $data['l_name'] ) ) {
			$merge_vals['last_name'] = $data['l_name']; // Legacy
		}
		
		// Add extra fields
		$merge_data = array_diff_key( $data, array(
			'email' => '',
			'firstname' => '',
			'lastname' => '',
			'f_name' => '',
			'l_name' => '',
		) );

		$merge_data = array_filter( $merge_data );

		if ( ! empty( $merge_data ) ) {
			$merge_vals = array_merge( $merge_vals, $merge_data );
		}

		$existing_member = $this->_email_exists( $list_id, $email, $api );
		if ( $existing_member ) {
			$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
			return $err;
		}

		$subscriber_data = array(
			'email' => $email,
			'type'  => 'active'
		);
		if ( ! empty( $merge_vals ) ) {
			$subscriber_data['fields'] = $merge_vals;
		}

		$res = $api->add_subscriber( $list_id, $subscriber_data, 1 );
		if ( !is_wp_error( $res ) && isset( $res['id'] ) ) {
			return true;
		} else {
			$data['error'] 	= $res->get_error_message();
			$module->log_error( $data );
		}

		return $err;
	}

	/**
	 * Check if an email exists
	 *
	 * @param $group_id - the group id
	 * @param $email - the email
	 * @param $api - the API class
	 *
	 * @return bool
	 */
	private function _email_exists( $group_id, $email, $api ){
		$member_groups = $api->get_subscriber( $email );
		if ( is_wp_error( $member_groups ) ) {
			return false;
		} else {
			if ( !isset( $member_groups['error'] ) ) {
				foreach( $member_groups as $member_group => $group ){
					if ( $group['id'] === $group_id ) {
						return true;
					}
				}
			} else {
				return false;
			}
		}
		return false;
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}
	
	private static function _get_list_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function add_custom_field( $fields, $module_id ) {
		$module 	= Hustle_Module_Model::instance()->get( $module_id );
		$api_key 	= self::_get_api_key( $module );

		$api = self::api( $api_key );

		foreach ( $fields as $field ) {
			$api->add_custom_field( array(
				"title" => $field['name'],
				"type"  => strtoupper( $field['type'] )
			) );
		}

		return array(
			'success' => true,
			'field' => $fields,
		);
	}
}

endif;
