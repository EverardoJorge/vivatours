<?php

if( !class_exists("Hustle_SendinBlue") ) :

/**
* Class Hustle_SendinBlue
*/
class Hustle_SendinBlue extends Hustle_Provider_Abstract  {

	const SLUG = "sendinblue";
	//const NAME = "SendinBlue";

	const LIST_PAGES = "hustle-sendinblue-list-pages";
	const CURRENT_LISTS = "hustle-sendinblue-current-list";
	
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
	protected $_slug 				   = 'sendinblue';

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
	protected $_title                  = 'SendinBlue';

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
	protected $_form_settings = 'Hustle_SendinBlue_Form_Settings';

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
		if ( ! class_exists( 'Hustle_SendinBlue_Api' ) )
			require_once 'hustle-sendinblue-api.php';

		$api = new Hustle_SendinBlue_Api( $api_key );

		return $api;
	}

	/**
	 * Subscribe an email
	 * This handles save or update, so no need to check if an email exists
	 *
	 * @param $module
	 * @param $data
	 *
	 * @return bool|WP_Error
	 */
	public function subscribe( Hustle_Module_Model $module, array $data ) {

		$api_key    = self::_get_api_key( $module );
		$list_id    = self::_get_email_list( $module );

		$err = new WP_Error();

		$err->add( 'something_wrong', __( 'Something went wrong. Please try again', Opt_In::TEXT_DOMAIN ) );

		$email = $data['email'];
		$merge_vals = array();

		if ( isset( $data['first_name'] ) ) {
			$name = $data['first_name'];
			$merge_vals['FIRSTNAME'] = $data['first_name'];
			$merge_vals['NAME'] = $data['first_name'];
		}
		elseif ( isset( $data['f_name'] ) ) {
			$name = $data['f_name'];
			$merge_vals['FIRSTNAME'] = $data['f_name']; // Legacy
			$merge_vals['NAME'] = $data['f_name']; // Legacy
		}
		if ( isset( $data['last_name'] ) ) {
			$surname = $data['last_name'];
			$merge_vals['LASTNAME'] = $data['last_name'];
			$merge_vals['NAME'] .= ' '.$data['last_name'];
		}
		elseif ( isset( $data['l_name'] ) ) {
			$surname = $data['l_name'];
			$merge_vals['LASTNAME'] = $data['l_name']; // Legacy
			$merge_vals['NAME'] .= ' '.$data['last_name']; // Legacy
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
		$merge_vals = array_change_key_case($merge_vals, CASE_UPPER);

		$api = self::api( $api_key );

		if ( $api && ! empty( $email ) ) {

			$list_array = array( $list_id );

			//First get the contact
			//We cannot add to a new list without getting the old list
			//We first get the old list id and merge with the new one
			$contact = $api->get_user( array( 'email' => $email ) );
			if ( !is_wp_error( $contact ) ) {
				if ( 'failure' !== $contact['code'] || ( isset( $contact['data'] ) && isset( $contact['data']['listid'] ) ) ) {
					if ( in_array( $list_id, $contact['data']['listid'], true ) ) {
						$err = new WP_Error();
						$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
						return $err;
					} else {
						$list_array = array_merge( $list_array, $contact['data']['listid'] );
					}
				}
			}

			$subscribe_data = array(
				'email'         => $email,
				'listid'        => $list_array
			);
			if ( ! empty( $merge_vals ) ) {
				$subscribe_data['attributes'] = $merge_vals;
			}
			$res = $api->create_update_user( $subscribe_data );

			if ( !is_wp_error( $res ) && isset( $res['code'] ) && 'success' === $res['code'] ) {
				return true;
			} else {
				$data['error'] = $res->get_error_message();
				$module->log_error( $data );
			}
			
		}

		return $err;
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

		
		try {
			$api = self::api( $api_key );
			foreach ( $fields as $field ) {
				$type = ( 'email' === $field['type'] || 'name' === $field['type'] || 'address' === $field['type'] || 'phone' === $field['type'] ) ? 'text' : $field['type'];
				$api->create_attribute( array(
					"type" => "normal", 
					"data" => array(
					strtoupper( $field['name'] ) => strtoupper( $type )
					)
				) );
			}
			
			// double check if already on our system
			/*$current_module_fields = $module->get_design()->__get( 'module_fields' );
			foreach( $current_module_fields as $m_field ) {
				if ( $m_field['name'] == $field['name'] ) {
					return array( 'error' => true, 'code' => 'custom', 'message' => __( 'Field already exists.', Opt_In::TEXT_DOMAIN ) );
				}
			}*/
			
		} catch ( Exception $e ) {
			return array(
				'error' => true,
				'code' => 'custom',
				'message' => $e->getMessage(),
			);
		}
		return array(
			'success' => true,
			'field' => $fields,
		);
	}
}

endif;
