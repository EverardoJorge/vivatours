<?php
if ( ! class_exists( 'Hustle_Mautic' ) ) :

/**
 * Mautic Integration
 *
 * @class Hustle_Mautic
 * @version 1.0.0
 **/
class Hustle_Mautic extends Hustle_Provider_Abstract {
	
	const SLUG = "mautic";

	/**
	 * Provider Instance
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
	public static $_min_php_version    = '5.3';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_slug 				   = 'mautic';

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
	protected $_title                  = 'Mautic';

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
	protected $_form_settings = 'Hustle_Mautic_Form_Settings';

	public function __construct() {
		$this->_icon = plugin_dir_path( __FILE__ ) . 'views/icon.php';

		if ( ! class_exists( 'Hustle_Mautic_Api' ) ) {
			include_once 'hustle-mautic-api.php';
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

	public static function api( $base_url = '', $username = '', $password = '' ) {
		if ( ! class_exists( 'Hustle_Mautic_Api' ) ) {
			include_once 'hustle-mautic-api.php';
		}
		try {
			return new Hustle_Mautic_Api( $base_url, $username, $password );
		} catch ( Exception $e ) {
			return $e;
		}
	}

	public function subscribe( Hustle_Module_Model $module, array $data ) {

		$url 		= self::_get_api_url( $module );
		$username 	= self::_get_api_username( $module );
		$password 	= self::_get_api_password( $module );
		$list_id 	= self::_get_email_list( $module );

		if ( isset( $data['first_name'] ) ) {
			$data['firstname'] = $data['first_name'];
			unset( $data['first_name'] );
		}
		if ( isset( $data['last_name'] ) ) {
			$data['lastname'] = $data['last_name'];
			unset( $data['last_name'] );
		}
		if ( isset( $data['f_name'] ) ) {
			$data['firstname'] = $data['f_name'];
			unset( $data['f_name'] );
		}
		if ( isset( $data['l_name'] ) ) {
			$data['lastname'] = $data['l_name'];
			unset( $data['l_name'] );
		}

		$err = new WP_Error();
		$geo = new Opt_In_Geo();
		$data['ipAddress'] = $geo->get_user_ip();

		$api = self::api( $url, $username, $password );

		$exist = $api->email_exist( $data['email'] );

		if ( $exist && ! is_wp_error( $exist ) ) {
			$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
			return $err;
		}
		$contact_id = $api->add_contact( $data, $module );

		if ( is_wp_error( $contact_id ) ) {
			// Remove ipAddress
			unset( $data['ipAddress'] );
			$error_code = $contact_id->get_error_code();
			$data['error'] = $contact_id->get_error_message( $error_code );
			$module->log_error( $data );
		} else {
			$api->add_contact_to_segment( $list_id, $contact_id );
		}

		return $contact_id;
	}

	public static function _get_api_url( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'url', self::SLUG );
	}

	public static function _get_api_username( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'username', self::SLUG );
	}

	public static function _get_api_password( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'password', self::SLUG );
	}

	private static function _get_email_list( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function add_custom_field( $fields, $module_id ) {
		$module 	= Hustle_Module_Model::instance()->get( $module_id );
		$url 		= self::_get_api_url( $module );
		$username 	= self::_get_api_username( $module );
		$password 	= self::_get_api_password( $module );

		$api = self::api( $url, $username, $password );

		$custom_fields = $api->get_custom_fields();
		foreach ( $fields as $field ) {
			$label = $field['label'];
			$alias = $field['name'];
			$exist = false;

			if ( is_array( $custom_fields ) ) {
				foreach ( $custom_fields as $custom_field ) {
					if ( $label === $custom_field['label'] ) {
						$exist = true;
						$field['name'] = $custom_field['alias'];
					} elseif ( $custom_field['alias'] === $alias ) {
						$exist = true;
					}
				}
			}

			if ( false === $exist ) {
				$custom_field = array(
					'label' => $label,
					'alias' => $alias,
					'type' 	=> ( 'email' === $field['type'] || 'name' === $field['type'] || 'address' === $field['type'] || 'phone' === $field['type'] ) ? 'text' : $field['type'],
				);

				$exist = $api->add_custom_field( $custom_field );
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
			'code' => '',
		);
	}
}

endif;
