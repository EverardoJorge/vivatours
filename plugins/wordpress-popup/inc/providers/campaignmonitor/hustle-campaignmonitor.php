<?php

if( !class_exists("Hustle_Campaignmonitor") ):

if( !class_exists( "CS_REST_General" ) )
	require_once Opt_In::$vendor_path . 'campaignmonitor/createsend-php/csrest_general.php';

if( !class_exists( "CS_REST_Subscribers" ) )
	require_once Opt_In::$vendor_path . 'campaignmonitor/createsend-php/csrest_subscribers.php';

if( !class_exists( "CS_REST_Clients" ) )
	require_once Opt_In::$vendor_path . 'campaignmonitor/createsend-php/csrest_clients.php';

if( !class_exists( "CS_REST_Lists" ) )
	require_once Opt_In::$vendor_path . 'campaignmonitor/createsend-php/csrest_lists.php';

class Hustle_Campaignmonitor extends Hustle_Provider_Abstract {

	const SLUG = "campaignmonitor";
	//const NAME = "Campaign Monitor";

	/**
	 * @var $api AWeberAPI
	 */
	protected  static $api;
	protected  static $errors;

	/**
	 * Activecampaign Provider Instance
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
	protected $_slug 				   = 'campaignmonitor';

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
	protected $_title                  = 'Campaign Monitor';
	
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
	protected $_form_settings = 'Hustle_Campaignmonitor_Form_Settings';

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
	 * @return CS_REST_General
	 */
	public static function api( $api_key ){
		if( empty( self::$api ) ){
			try {
				self::$api = new CS_REST_General( array('api_key' => $api_key) );
				self::$errors = array();
			} catch (Exception $e) {
				self::$errors = array("api_error" => $e) ;
			}

		}

		return self::$api;
	}

	public function subscribe( Hustle_Module_Model $module, array $data ){
		$api_key    = self::_get_api_key( $module );
		$list_id    = self::_get_api_list_id( $module );

		$email = $data['email'];
		$name = array();

		if ( isset( $data['first_name'] ) ) {
			$name['first_name'] = $data['first_name'];
		}
		elseif ( isset( $data['f_name'] ) ) {
			$name['first_name'] = $data['f_name'];
		}
		if ( isset( $data['last_name'] ) ) {
			$name['last_name'] = $data['last_name'];
		}
		elseif ( isset( $data['l_name'] ) ) {
			$name['last_name'] = $data['l_name'];
		}
		$name = implode( ' ', $name );

		// Remove unwanted fields
		$old_data = $data;
		$data = array_diff_key( $data, array(
			'first_name' => '',
			'last_name' => '',
			'f_name' => '',
			'l_name' => '',
			'email' => '',
		) );

		$custom_fields = array();
		if( ! empty( $data ) ){
			foreach( $data as $key => $d ){
				$custom_fields[] = array(
					'Key' => $key,
					'Value' => $d,
				);
			}
		}

		$api = new CS_REST_Subscribers( $list_id, array('api_key' => $api_key ));
		$is_subscribed = $api->get( $email );
		$err = new WP_Error();

		if ( $is_subscribed->was_successful() ) {
			$err->add("already_subscribed", __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
		} else {
			$res = $api->add( array(
				'EmailAddress' => $email,
				'Name'         => $name,
				'Resubscribe'  => true,
				'CustomFields' => $custom_fields
			) );

			if( $res->was_successful() ) {
				return array( 'success' => 'success' );
			} else {
				$err->add( 'request_error', __( 'Unexpected error occurred. Please try again.', Opt_In::TEXT_DOMAIN ) );
				$data['error'] = __( 'Unable to add to subscriber list.', Opt_In::TEXT_DOMAIN );
				$module->log_error( $data );
			}
		}

		return $err;
	}

	public static function _get_api_key( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}

	private static function _get_api_list_id( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function add_custom_field( $fields, $module_id ) {
		$module     = Hustle_Module_Model::instance()->get( $module_id );
		$api_key    = self::_get_api_key( $module );
		$list_id    = self::_get_api_list_id( $module );

		$api_cf         = new CS_REST_Lists( $list_id, array( 'api_key' => $api_key ) );
		$custom_fields  = $api_cf->get_custom_fields();
		$failed_custom_fields = 0;
		
		foreach ( $fields as $field ) {
			$exist      = false;
			$key        = $field['name'];
		    $meta_key   = "cm_field_{$key}";
			if ( ! empty( $custom_fields ) && ! empty( $custom_fields->response ) ) {
				foreach ( $custom_fields->response as $custom_field ) {
					if ( $custom_field->Key === $field['name'] ) { // phpcs:ignore
						$exist = true;
					}
					$module->add_meta( "cm_field_". $custom_field->Key, $custom_field->FieldName ); // phpcs:ignore
				}
			}

			if ( false === $exist ) {
				$cm_field = array(
					'FieldName' => $key,
					'Key'       => $key,
					'DataType'  => CS_REST_CUSTOM_FIELD_TYPE_TEXT, // We only support text for now,
					'Options'   => '',
					'VisibleInPreferenceCenter' => true,
				);				
				if ( $api_cf->create_custom_field( $cm_field ) ) {
					$module->add_meta( $meta_key, $field['name'] );
				} else {
					$failed_custom_fields++;
				}
				$exist = true;
			}
		}

		if ( $exist && !$failed_custom_fields ) {
			return array( 
				'success' => true,
				'field' => $fields,
			);
		} else {
			Hustle_Api_Utils::maybe_log('There was an error creating new custom fields.');
			return array( 
				'error' => true,
				'code' => 'cannot_create_custom_field',
			);
		}
	}
}
endif;
