<?php

if ( ! class_exists ('Hustle_E_Newsletter' ) ):

class Hustle_E_Newsletter extends Hustle_Provider_Abstract {
	
	/**
	 * @var $_email_newsletter Email_Newsletter
	 */
	private $_email_newsletter;

	/**
	 * @var $_email_builder Email_Newsletter_Builder
	 */
	private $_email_builder;
	
	const SLUG = "e_newsletter";
	//const NAME = "e-Newsletter";

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
	protected $_slug 				   = 'e_newsletter';

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
	protected $_title                  = 'e-Newsletter';

	/**
	 * Class name of form settings
	 *
	 * @var string
	 */
	protected $_form_settings = 'Hustle_E_Newsletter_Form_Settings';

	/**
	 * Provider constructor.
	 */	
	public function __construct() {
		$this->_icon = plugin_dir_path( __FILE__ ) . 'views/icon.php';
		
		global $email_newsletter, $email_builder; 
		$this->_email_builder = $email_builder;
		$this->_email_newsletter = $email_newsletter;
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
	 * Subscribes to E-Newsletter
	 *
	 *
	 * @param array $data
	 * @param array $groups
	 * @param int $subscribe
	 *
	 * @since 1.1.1
	 * @return array
	 */
	public function subscribe( Hustle_Module_Model $module, array $data ){
		
		$groups = self::get_provider_details( $module, 'list_id', $this->_slug );
		$double_opt_in = self::_get_auto_optin( $module ) === 'pending' ? true : false ;
		$subscribe = $double_opt_in ? "" : 1;
		
		$_data = array();
		$_data['member_email'] = $data['email'];

		if( isset( $data['first_name'] ) )
			$_data['member_fname'] = $data['first_name'];

		if( isset( $data['last_name'] ) )
			$_data['member_lname'] = $data['last_name'];
		
		$_data['is_hustle'] = true;
		$e_newsletter = $this->_email_newsletter;
		
		if( !$this->is_member( $_data['member_email'] ) ){
			$insert_data = $e_newsletter->create_update_member_user( "",  $_data, $subscribe );
			
			if( isset( $insert_data['results'] ) && in_array( "member_inserted", (array) $insert_data['results'], true )  ) {
				$e_newsletter->add_members_to_groups( $insert_data['member_id'], $groups );
				
				if( isset( $e_newsletter->settings['subscribe_newsletter'] ) && $e_newsletter->settings['subscribe_newsletter'] ) {
					$send_details = $e_newsletter->add_send_email_info( $e_newsletter->settings['subscribe_newsletter'], $insert_data['member_id'], 0, 'waiting_send' );
					$e_newsletter->send_email_to_member($send_details['send_id']);
				}
				
				//$subscribe should only be false when double opt-in is enabled 
				if ( !$subscribe ){
					$status = $e_newsletter->do_double_opt_in( $insert_data['member_id'] );
				}
				
				return true;
			}
			
			return new WP_Error("data_not_inserted", __("Something went wrong. Please try again later.", Opt_In::TEXT_DOMAIN), $data);
		}

		return new WP_Error("member_exists", __("Member exists", Opt_In::TEXT_DOMAIN), $data);
	}

	/**
	 * Checks if E-Newsletter plugin is active
	 *
	 * @since 1.1.1
	 * @return bool
	 */
	public function is_plugin_active(){
		return class_exists( 'Email_Newsletter' ) && isset( $this->_email_newsletter ) && isset( $this->_email_builder );
	}

	/**
	 * Returns groups
	 *
	 * @since 1.1.1
	 * @return array
	 */
	public function get_groups(){
		return (array) $this->_email_newsletter->get_groups();
	}

	/**
	 * Checks if member with given email already exits
	 *
	 *
	 * @since 1.1.1
	 *
	 * @param $email
	 * @return bool
	 */
	public function is_member( $email ){
		$member = $this->_email_newsletter->get_member_by_email( $email );
		return !!$member;
	}

	/**
	 * Subscribes $modules's subscribers to e-newsletter
	 *
	 * @since 1.1.2
	 *
	 * @param Hustle_Module_Model $module
	 * @param array $groups
	 */
	public function sync_with_current_local_collection( Hustle_Module_Model $module, $groups = array() ){

		$groups = array() === $groups ?  $this->get_groups() : $groups;

		foreach( $module->get_local_subscriptions() as $subscription ){

			if( isset( $subscription->optin_type  ) && "e-newsletter"  === $subscription->optin_type  ) return;

			$data = array(
				"is_hustle" => true,
				"member_email" => $subscription->email,
				"member_fname" => isset( $subscription->f_name ) ? $subscription->f_name : "",
				"member_lname" => isset( $subscription->l_name ) ? $subscription->l_name : ""
			);
			if( !$this->is_member( $data['member_email'] ) ){
				$insert_data = $this->_email_newsletter->create_update_member_user( "",  $data, 1 );

				if( isset( $insert_data['results'] ) && in_array( "member_inserted", (array) $insert_data['results'], true )  )
				$this->_email_newsletter->add_members_to_groups( $insert_data['member_id'],  $groups );
			}
		}

	}

	public static function _get_list_id( $module ) {
		return self::get_provider_details( $module, 'list_id', self::SLUG );
	}

	public static function get_synced( $module ) {
		return self::get_provider_details( $module, 'synced', self::SLUG );
	}

	public static function _get_auto_optin( Hustle_Module_Model $module ) {
		$auto_optin = 'pending';
		$saved_auto_optin = self::get_provider_details( $module, 'auto_optin', self::SLUG );
		if ( !empty( $saved_auto_optin ) &&  'pending' !== $saved_auto_optin ) {
			$auto_optin = 'subscribed';
		}
		return $auto_optin;
	}

}
endif;
