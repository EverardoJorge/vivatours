<?php
/**
 * Class Hustle_Mailchimp
 * The class that defines mailchimp provider
 */
if( !class_exists("Hustle_Mailchimp") ):

	include_once 'hustle-mailchimp-api.php';

	class Hustle_Mailchimp extends Hustle_Provider_Abstract{

		const GROUP_TRANSIENT = "hustle-mailchimp-group-transient";
		const LIST_PAGES = "hustle-mailchimp-list-pages";
		
		const SLUG = "mailchimp";
		
		/**
		 * @var $api Mailchimp
		 */
		protected  static $api;
		protected  static $errors;

		/**
		 * Mailchimp Provider Instance
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
		public static $_min_hustle_version = Opt_In::VERSION;

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_slug = 'mailchimp';

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_version	= '1.0';

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_class				   = __CLASS__;

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_title = 'Mailchimp';

		/**
		 * @since 3.0.5
		 * @var bool
		 */
		protected $_supports_fields 	   = true;
		
		/**
		 * Class name of form settings
		 *
		 * @since 3.0.5
		 * @var string
		 */
		protected $_form_settings = 'Hustle_Mailchimp_Form_Settings';

		/**
		 * Provider constructor.
		 */	
		public function __construct() {
			$this->_icon = plugin_dir_path( __FILE__ ) . 'views/icon.php';
			$this->_front_args = plugin_dir_path( __FILE__ ) . 'views/front_args_template.php';
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
		 * @param string $api_key
		 * @return Mailchimp
		 */
		public static function api( $api_key ){

			if( empty( self::$api ) ){
				try {
					$exploded = explode( '-', $api_key );
					$data_center = end( $exploded );
					self::$api = new Hustle_Mailchimp_Api( $api_key, $data_center );
					self::$errors = array();
				} catch (Exception $e) {
					self::$errors = array("api_error" => $e) ;
				}

			}
			return self::$api;
		}

		/**
		 * Subscribe user to list
		 *
		 * @param Hustle_Module_Model $module
		 * @param array $data
		 *
		 * @return string|array|WP_Error|Exception Returns string or array if the subscription was successfully, WP_Error or Exception otherwise.
		 */
		public function subscribe( Hustle_Module_Model $module, array $data ){
			$api        = self::api( self::_get_api_key( $module ) );
			$list_id    = self::_get_list_id( $module );
			$sub_status = self::_get_auto_optin( $module );
			$allow_subscribed = self::get_allow_subscribed_users( $module );

			if ( empty( $api ) ) {
				$err = new WP_Error();
				$err->add( 'server_failed', __( 'API Key is not defined!', Opt_In::TEXT_DOMAIN ) );
				return $err;
			}

			$email =  $data['email'];
			$merge_vals = array();
			$interests = array();

			if ( isset( $data['first_name'] ) ) {
				$merge_vals['MERGE1'] = $data['first_name'];
				$merge_vals['FNAME'] = $data['first_name'];
			}
			elseif ( isset( $data['f_name'] ) ) {
				$merge_vals['MERGE1'] = $data['f_name']; // Legacy
				$merge_vals['FNAME'] = $data['f_name']; // Legacy
			}
			if ( isset( $data['last_name'] ) ) {
				$merge_vals['MERGE2'] = $data['last_name'];
				$merge_vals['LNAME'] = $data['last_name'];
			}
			elseif ( isset( $data['l_name'] ) ) {
				$merge_vals['MERGE2'] = $data['l_name']; // Legacy
				$merge_vals['LNAME'] = $data['l_name']; // Legacy
			}
			// Add extra fields
			$merge_data = array_diff_key( $data, array(
				'email' => '',
				'first_name' => '',
				'last_name' => '',
				'f_name' => '',
				'l_name' => '',
				'mailchimp_group_id' => '',
				'mailchimp_group_interest' => '',
			) );
			$merge_data = array_filter( $merge_data );

			if ( ! empty( $merge_data ) ) {
				$merge_vals = array_merge( $merge_vals, $merge_data );
			}
			$merge_vals = array_change_key_case($merge_vals, CASE_UPPER);
			
			/**
			 * Add args for interest groups
			 */
			if( !empty( $data['mailchimp_group_id'] ) && !empty( $data['mailchimp_group_interest'] ) ){
				$data_interest = (array) $data['mailchimp_group_interest'];
				foreach( $data_interest as $interest ) {
					$interests[$interest] = true;
				}
			}
			
			try {
				$subscribe_data = array(
					'email_address' => $email,
					'status'        => $sub_status
				);
				if ( !empty($merge_vals) ) {
					$subscribe_data['merge_fields'] = $merge_vals;
				}
				if ( !empty($interests) ) {
					$subscribe_data['interests'] = $interests;
				}
				$existing_member = $this->get_member( $email, $module, $data );
				if ( $existing_member ) {
					$member_interests = isset($existing_member->interests) ? (array) $existing_member->interests : array();
					$can_subscribe = 'allow' === $allow_subscribed ? true : false;
					if ( isset( $subscribe_data['interests'] ) ){
						$local_interest_keys = array_keys( $subscribe_data['interests'] );
						if ( !empty( $member_interests ) ) {
							foreach( $member_interests as $member_interest => $subscribed ){
								if( !$subscribed && in_array( $member_interest, $local_interest_keys, true ) ){
									$can_subscribe = true;
								}
							}
						} else {
							$can_subscribe = true;
						}
					}
					if ( 'pending' === $existing_member->status ) {
						$can_subscribe = true;
					}
					if ( 'unsubscribed' === $existing_member->status ) {
						//resend Confirm Subscription Email even if `Automatically opt-in new users to the mailing list` is set
						$subscribe_data['status'] = 'pending';
						$can_subscribe = true;
					} else {
						unset( $subscribe_data['status'] );
					}
					if ( isset( $subscribe_data['interests'] ) || $can_subscribe ) {
						unset( $subscribe_data['email_address'] );
						if ( 'allow' !== $allow_subscribed ) {
							unset( $subscribe_data['merge_fields'] );
						}
						$response = $api->update_subscription( $list_id, $email, $subscribe_data );
						return array(
							'message' => $response,
							'existing' => true,
						);
					} else {
						$err = new WP_Error();
						$err->add( 'email_exist', __( 'This email address has already subscribed', Opt_In::TEXT_DOMAIN ) );
						return $err;
					}
				} else {
					$result = $api->subscribe( $list_id, $subscribe_data );
					return $result;
				}
			} catch( Exception $e ) {
				$data['error'] = $e->getMessage();
				$module->log_error( $data );
				Hustle_Api_Utils::maybe_log( __METHOD__, 'Failed to subscribe user to MailChimp list.', $e->getMessage() );

				$err = new WP_Error();
				$err->add( 'server_failed', __( 'Something went wrong. Please try again.', Opt_In::TEXT_DOMAIN ) );
				return $err;
			}
		}

		/**
		 * @param string $email
		 * @param Hustle_Module_Model $module
		 * @param array $data
		 *
		 * @return Object Returns the member if the email address already exists otherwise false.
		 */
		public function get_member( $email, Hustle_Module_Model $module, $data ) {
			$api = self::api( self::_get_api_key( $module ) );

			try {
				$member_info = $api->check_email( self::_get_list_id( $module ), $email);
				// Mailchimp returns WP error if can't find member on a list
				if ( is_wp_error( $member_info ) &&  404 === $member_info->get_error_code() ) {
					return false;
				}
				return $member_info;
			} catch( Exception $e ) {
				$data['error'] = $e->getMessage();
				$module->log_error($data);
				Hustle_Api_Utils::maybe_log( __METHOD__, 'Failed to get member from MailChimp list.', $e->getMessage() );

				return false;
			}
		}
		
		/**
		 * Get provider's args. Used in frontend.
		 *
		 * @param array $data
		 * @return array
		 */
		public function get_args( $data ) {
			if ( $data && isset( $data['email_services'] ) ) {
				$email_services = $data['email_services'];
				$list_id = ( isset( $email_services['mailchimp']['list_id'] ) ) 
					? $email_services['mailchimp']['list_id']
					: '';
				$group_id = ( isset( $email_services['mailchimp']['group'] ) )
					? $email_services['mailchimp']['group']
					: '';
				$groups = $this->get_provider_form_settings()->_get_group_interests( $list_id, $group_id );
				
				if ( isset( $email_services['mailchimp']['group_interest'] ) ) {
					$groups['selected'] = $email_services['mailchimp']['group_interest'];
				}
				
				return $groups;
			}
		}

		public static function _get_api_key( Hustle_Module_Model $module ) {
			return self::get_provider_details( $module, 'api_key', self::SLUG );
		}
		
		public static function _get_list_id( Hustle_Module_Model $module ) {
			return self::get_provider_details( $module, 'list_id', self::SLUG );
		}
		
		public static function _get_auto_optin( Hustle_Module_Model $module ) {
			$auto_optin = 'pending';
			$saved_auto_optin = self::get_provider_details( $module, 'auto_optin', self::SLUG );
			if ( $saved_auto_optin && !empty( $saved_auto_optin ) && 'pending' !== $saved_auto_optin ) {
				$auto_optin = 'subscribed';
			}
			return $auto_optin;
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
			$module     = Hustle_Module_Model::instance()->get( $module_id );
			$api_key    = self::_get_api_key( $module );
			$list_id    = self::_get_list_id( $module );

			try{
				// Mailchimp does not support "email" field type so let's use text
				// use text as well for name, address and phone
				// returns either the new MailChimp "merge_field" object or WP error (if already existing)
				$api = self::api( $api_key );
				
				foreach ( $fields as $field ) {
					$api->add_custom_field( $list_id, array(
						'tag'   => strtoupper( $field['name'] ),
						'name'  => $field['label'],
						'type'  => ( 'email' === $field['type'] || 'name' === $field['type'] || 'address' === $field['type'] || 'phone' === $field['type'] ) ? 'text' : $field['type']
					) );
				}
				
				// double check if already on our system
				/*$current_module_fields = $module->get_design()->__get( 'module_fields' );
				foreach( $current_module_fields as $m_field ) {
					if ( $m_field['name'] == $field['name'] ) {
						return array( 'error' => true, 'code' => 'custom', 'message' => __( 'Field already exists.', Opt_In::TEXT_DOMAIN ) );
					}
				}*/
				
			}catch (Exception $e){
				return array(
					'error' => true,
					'code' => 'custom',
					'message' => $e->getMessage()
				);
			}
			return array(
				'success' => true,
				'fields' => $fields,
			);
		}
	}
endif;
