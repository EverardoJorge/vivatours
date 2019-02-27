<?php

if( !class_exists("Hustle_HubSpot") ):

require_once 'hustle-hubspot-api.php';

/**
 * Class Hustle_HubSpot
 */
class Hustle_HubSpot extends Hustle_Provider_Abstract {
	const SLUG = "hubspot";
	//const NAME = "Hubspot";

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
	protected $_slug 				   = 'hubspot';

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
	protected $_title                  = 'Hubspot';

	/**
	 * Class name of form settings
	 *
	 * @var string
	 */
	protected $_form_settings = 'Hustle_HubSpot_Form_Settings';

	/**
	 * Provider constructor.
	 */	
	public function __construct() {
		$this->_icon = plugin_dir_path( __FILE__ ) . 'views/icon.php';

		// Instantiate API when instantiating because it's used after getting the authorization
		$hustle_hubpost = new Hustle_HubSpot_Api();
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
	 * @return bool|Hustle_HubSpot_Api
	 */
	public function api() {
		return self::static_api();
	}

	public static function static_api() {
		if ( ! class_exists( 'Hustle_HubSpot_Api' ) )
			require_once 'opt-in-hubspot-api.php';

		$api = new Hustle_HubSpot_Api();

		return $api;
	}

	public function subscribe( Hustle_Module_Model $module, array $data ) {
		$email_list = self::_get_email_list( $module );
		$err = new WP_Error();
		$err->add( 'something_wrong', __( 'Something went wrong. Please try again', Opt_In::TEXT_DOMAIN ) );

		$api = $this->api();

		if ( $api && ! $api->is_error && ! empty( $data['email'] ) ) {
			$email_exist = $api->email_exists( $data['email'] );

			if ( $email_exist ) {
				$contact_id = $email_exist->vid;
				$list_memberships = 'list-memberships';
				$add_to_list = false;

				if ( empty( $email_exist->{$list_memberships} ) )
					$add_to_list = true;

				if ( $add_to_list ) {
					$res = $api->add_to_contact_list( $contact_id, $data['email'], $email_list );

					if ( false === $res ) {
						$data['error'] = __( 'Unable to add this contact to contact list.', Opt_In::TEXT_DOMAIN );
						$module->log_error($data);
					}
				}
				$err->add( 'something_wrong', __( 'This email has already subscribed.', Opt_In::TEXT_DOMAIN ) );
			} else {
				$res = $api->add_contact( $data );

				if ( ! is_object( $res ) && (int) $res > 0 ) {
					$contact_id = $res;
					// Add new contact to contact list
					$res = $api->add_to_contact_list( $contact_id, $data['email'], $email_list );

					if ( false === $res ) {
						$data['error'] = __( 'Unable to add this contact to contact list.', Opt_In::TEXT_DOMAIN );
						$module->log_error($data);
					}
					return true;
				} elseif( is_wp_error( $res ) ) {
					$data['error'] = $res->get_error_message();
					$module->log_error( $data );
				} elseif ( isset( $res->status ) && 'error' === $res->status ) {
					$data['error'] = $res->message;
					$module->log_error($data);
				}
			}
		}

		return $err;
	}

	public static function _get_email_list( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function add_custom_field( $fields, $module_id ) {
		$api 	= self::static_api();
		$exist 	= false;

		if ( $api && ! $api->is_error ) {
			// Get the existing fields
			$props = $api->get_properties();

			$new_fields = array();

			if ( ! empty( $props ) ) {
				// Check for existing property
				foreach ( $props as $property_name => $property_label ){
					foreach ( $fields as $field ) {
						$name 	= $field['name'];
						$label 	= $field['label'];
						if ( $name !== $property_name || $label !== $property_label ) {
							$new_field = array(
								'name' => $property_name,
								'label' => $property_label
							);
							$new_fields[] = $new_field;
						}
					}
				}
					
			}

			if ( ! empty( $new_fields ) ) {
				foreach ( $new_fields as $field ) {
					// Add the new field as property
					$property = array(
						'name' => $field['name'],
						'label' => $field['label'],
						'type' => 'string',
						'fieldType' => 'text',
						'groupName' => 'contactinformation',
					);

					if ( $api->add_property( $property ) )
						$exist = true;
				}
				
			}
		}

		if ( $exist ) {
			return array(
				'success' => true,
				'field' => $fields,
			);
		} else {
			return array(
				'error' => true,
				'code' => 'cannot_create_custom_field',
			);
		}
	}
}

/**
 * Disable selected list description.
 */
add_filter( 'wpoi_optin_hubspot_show_selected_list', '__return_false' );
endif;
