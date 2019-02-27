<?php
if( !class_exists("Hustle_Icontact_Form_Settings") ):

/**
 * Class Hustle_Icontact_Form_Settings
 * Form Settings iContact Process
 *
 */
class Hustle_Icontact_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

	/**
	 * Array with pre-defined error messages to display to users.
	 * This will be filled on construct in order to have translators available.
	 *
	 * @var array
	 */
	protected $error_messages = array();

	public function __construct( Hustle_Provider_Abstract $provider ) {
	   parent::__construct( $provider );

	   // Late init for translators to be available.
	   $this->error_messages['wrong_api_credentials'] = __( 'There was an error connecting to your account. Please make sure your credentials are correct.', Opt_In::TEXT_DOMAIN );
	}
	
	/**
	 * For settings Wizard steps
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public function form_settings_wizards() {
		// already filtered on Abstract
		// numerical array steps
		return array(
			// 0
			array(
				'callback'     => array( $this, 'first_step_callback' ),
				'is_completed' => array( $this, 'first_step_is_completed' ),
			),
			// 1
			array(
				'callback'     => array( $this, 'second_step_callback' ),
				'is_completed' => array( $this, 'second_step_is_completed' ),
			),
		);
	}
		
	/**
	 * Check if step is completed
	 *
	 * @since 3.0.5 
	 * @return bool
	 */
	public function first_step_is_completed( $submitted_data ) {
		$required_fields = array(
			'app_id' => '',
			'username' => '',
			'password' => '',
			'list_id' => '',
		);
		$errors = Hustle_Api_Utils::check_for_required_fields( $submitted_data, $required_fields );

		if ( !empty( $errors ) ) {
			return false;
		}

		return true;
	}
	
	/**
	 * Returns all settings and conditions for 1st step of Provider settings
	 *
	 * @since 3.0.5
	 *
	 * @param array $submitted_data
	 * @param boolean $is_submit
	 * @return array
	 */
	public function first_step_callback( $submitted_data, $is_submit ) {
		$error_message = '';

		if ( $is_submit ) {
			$required_fields = array(
				'app_id' => __( 'Your API App-ID', Opt_In::TEXT_DOMAIN ),
				'username' => __( 'Your e-mail address', Opt_In::TEXT_DOMAIN ),
				'password' => __( 'Your password', Opt_In::TEXT_DOMAIN ),
				'list_id' => __( 'The list', Opt_In::TEXT_DOMAIN ),
			);

			$errors = Hustle_Api_Utils::check_for_required_fields( $submitted_data, $required_fields );

			if ( ! empty( $errors ) ) {
				$error_message = implode( '<br/>', $errors );
			} else {
				$api = Hustle_Icontact::api( $submitted_data['app_id'], $submitted_data['password'], $submitted_data['username'] );
				if ( is_wp_error( $api ) ) {
					$error_message = $this->error_messages['wrong_api_credentials'];
				}
			}

		}

		if( ! $this->provider->is_activable() ) {
			wp_send_json_error( 'iContact requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
		}
		
		$options = $this->first_step_options( $submitted_data );

		$html = $this->get_html_for_options( $options );
		
		if( empty( $error_message ) ) {
			$step_html = $html;
			$has_errors = false;
		} else {
			$step_html = '<label class="wpmudev-label--notice"><span>' . $error_message . '</span></label>';
			$step_html .= $html;
			$has_errors = true;
		}
		$step_html .= $this->get_current_list_name_markup();
		
		$buttons = array(
			'cancel' => array(
				'markup' => $this->get_cancel_button_markup(),
			), 
			'save' => array(
				'markup' => $this->get_next_button_markup( __( 'Continue', Opt_In::TEXT_DOMAIN ) ),
			), 
		);
		
		$response = array(
			'html'       => $step_html,
			'buttons'    => $buttons,
			'has_errors' => $has_errors,
		);

		if( $is_submit && ! $has_errors ){
			$response['data_to_save'] = $this->before_save_first_step( $submitted_data );
		}
		return $response;
	}
	
	/**
	 * Returns array with options to be converted into HTML by Opt_In->render()
	 *
	 * @since 3.0.5
	 *
	 * @param string $submitted_data
	 * @return array
	 */
	private function first_step_options( $submitted_data ) {
		
		if ( isset( $submitted_data['module_id'] ) ) {
			$module_id = $submitted_data['module_id'];
			$module = Hustle_Module_Model::instance()->get( $module_id );
			$saved_app_id =  Hustle_Icontact::_get_app_id( $module );
			$saved_username = Hustle_Icontact::_get_username( $module );
			$saved_password = Hustle_Icontact::_get_password( $module );
		} else {
			$saved_api_key = '';
			$saved_ac_url = '';	
			$saved_password = '';	
		}
		$app_id     = ! isset( $submitted_data['app_id'] ) ? $saved_app_id : $submitted_data['app_id'];
		$username   = ! isset( $submitted_data['username'] ) ? $saved_username : $submitted_data['username'];
		$password   = ! isset( $submitted_data['password'] ) ? $saved_password : $submitted_data['password'];

		$options = array(
			'api_id_label' => array(
				'id' 	=> 'app_id_label',
				'for' 	=> 'app_id',
				'type' 	=> 'label',
				'value' => __( 'Enter your API APP-ID', Opt_In::TEXT_DOMAIN ),
			),
			'app_id' => array(
				'id' 			=> 'app_id',
				'name' 			=> 'app_id',
				'value' 		=> $app_id,
				'placeholder' 	=> '',
				'type' 			=> 'text',
				"class"         => "wpmudev-input_text",
			),
			array(
				'id' 	=> 'username-label',
				'for' 	=> 'username',
				'type' 	=> 'label',
				'value' => __( 'Enter your account email address', Opt_In::TEXT_DOMAIN ),
			),
			array(
				'id' 		=> 'username',
				'name' 		=> 'username',
				'type' 		=> 'text',
				'value' 	=> $username,
				"class" 	=> "wpmudev-input_text"
			),
			array(
				'id' 	=> 'password-label',
				'for' 	=> 'password',
				'type' 	=> 'label',
				'value' => __( 'Enter your Password', Opt_In::TEXT_DOMAIN ),
			),
			'wrapper2' => array(
				'id' 	=> 'wpoi-get-lists',
				'type' 	=> 'wrapper',
				'class' => 'wpmudev-provider-group',
				'elements' => array(
					array(
						'id' 	=> 'password',
						'name' 	=> 'password',
						'type' 	=> 'text',
						'value' => $password,
						"class" => "wpmudev-input_text"
					),
					'refresh' => array(
						"id" 	=> "refresh-lists",
						"type" 	=> "ajax_button",
						"value" => "<span class='wpmudev-loading-text'>" . __( "Fetch Lists", Opt_In::TEXT_DOMAIN ) . "</span><span class='wpmudev-loading'></span>",
						'class' => "wpmudev-button wpmudev-button-sm hustle_provider_on_click_ajax",
						"attributes" => array(
							"data-action" => "hustle_icontact_refresh_lists",
							"data-nonce"  => wp_create_nonce("hustle_icontact_refresh_lists"),
							"data-dom_wrapper"  => "#optin-provider-account-options"
						)
					),
				),
			),
			"instructions" => array(
				"id"    => "optin_api_instructions",
				"for"   => "",
				"value" => sprintf( __( "Set up a new application in your <a href='%s' target='_blank'>IContact account</a> to get your credentials. (2.0) Make sure the AppID is enabled in your account", Opt_In::TEXT_DOMAIN ), "https://app.icontact.com/icp/core/registerapp/" ),
				"type"  => "small",
			),
		);

		return $options;
	}

	/**
	 * Returns array with $html to be inserted into the $wrapper DOM object
	 *
	 * @since 3.0.5
	 *
	 * @return array
	 */
	public function ajax_refresh_lists() {
		Hustle_Api_Utils::validate_ajax_call( 'hustle_icontact_refresh_lists' );
		
		$submitted_data = Hustle_Api_Utils::validate_and_sanitize_fields( $_REQUEST ); // phpcs:ignore
		$response = array(
			'html' => $this->refresh_lists_html( $submitted_data ),
			'wrapper' => $submitted_data['dom_wrapper'],
		);
		wp_send_json_success( $response );
	}

	/**
	 * Returns HTML for when refreshing lists
	 *
	 * @since 3.0.5
	 *
	 * @param string $submitted_data
	 * @return string
	 */
	private function refresh_lists_html( $submitted_data ){
		$app_id = $submitted_data['app_id'];
		$password = $submitted_data['password'];
		$username = $submitted_data['username'];

		$lists 	= array();
		$value 	= '';
		$list 	= array();
		
		// Check if API key is valid
		$api 	= Hustle_Icontact::api( $app_id, $password, $username );
		
		if ( !is_wp_error( $api ) ) {
			$_lists = $api->get_lists();

			if ( !is_wp_error( $_lists ) ) {
				$options = $this->refresh_lists_options( $_lists );
				$html = '';
				if ( ! empty( $options ) ) {
					$html = $this->get_html_for_options( $options );
				}
				return $html;
			} else {
				Hustle_Api_Utils::maybe_log( implode( "; ", $_lists->get_error_messages() ) );
			}
		} else {
			Hustle_Api_Utils::maybe_log( implode( "; ", $api->get_error_messages() ) );
		}
		return '<label class="wpmudev-label--notice"><span>' . $this->error_messages['wrong_api_credentials'] . '</span></label>';
	}

	/**
	 * Retrieves options from the provider's account with the given api_key
	 *
	 * @param string $submitted_data
	 * @return array
	 */
	private function refresh_lists_options( $_lists ) {
		$value = '';
		$lists = array();
		if( count( $_lists ) && isset( $_lists['lists'] ) ) {
			foreach( $_lists['lists'] as $list ) {
				$list = (array) $list;
				$lists[ $list['listId'] ]['value'] = $list['listId'];
				$lists[ $list['listId'] ]['label'] = $list['name'];
				// Save it in order to get the selected list name before saving first step
			}

			$total_lists = count( $lists );
			if ( !empty( $first ) ) {
				$value = $first['value'];
			}
		}

		return  array(
			"label" => array(
				"id"    => "list_id_label",
				"for"   => "list_id",
				"value" => __("Choose list:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"choose_email_list" => array(
				"type"          => 'select',
				'name'          => "list_id",
				'id'            => "wph-email-provider-lists",
				"default"       => "",
				'options'       => $lists,
				'value'         => $value,
				'selected'      => $value,
				'class'         => "wpmudev-select icontact_choose_campaign"
			)
		);
	}
	
	protected function before_save_first_step( $data ) {
		if( isset( $data['app_id'] ) ) {
			$data['desc'] = $data['app_id'];
		}
		//if( isset( $data['list_id'] ) ) {
		//	$data['list_name'] = isset( $this->account_lists[ $data['list_id'] ] ) ? $this->account_lists[ $data['list_id'] ] . 'he' : '' ;
		//}
		return $data;
	}

	/**
	 * Check if step is completed
	 *
	 * @since 3.0.5 
	 * @return bool
	 */
	public function second_step_is_completed( $submitted_data ) {
		// Do validation here
		return true;
	}

	private function before_save_second_step( $data ) {
		return $data;
	}

	/**
	 * Returns all settings and conditions for 1st step of Provider settings
	 *
	 * @since 3.0.5
	 *
	 * @param array $submitted_data
	 * @param boolean $is_submit
	 * @return array
	 */
	public function second_step_callback( $submitted_data, $is_submit ) {
		$error_message = '';
		if ( $is_submit ) {
			if ( 'pending' === $submitted_data['auto_optin'] && empty( $submitted_data['confirmation_message_id'] ) ) {
				$error_message = __( 'The confirmation message is required when double opt-in is enabled.', Opt_In::TEXT_DOMAIN );
			}
		}
		
		$options = $this->second_step_options( $submitted_data );

		$html = $this->get_html_for_options( $options );
		
		if( empty( $error_message ) ) {
			$step_html = $html;
			$has_errors = false;
		} else {
			$step_html = '<label class="wpmudev-label--notice"><span>' . $error_message . '</span></label>';
			$step_html .= $html;
			$has_errors = true;
		}
		
		$buttons = array(
			'cancel' => array(
				'markup' => $this->get_previous_button_markup(),
			), 
			'save' => array(
				'markup' => $this->get_next_button_markup(),
			), 
		);
		
		$response = array(
			'html'       => $step_html,
			'buttons'    => $buttons,
			'has_errors' => $has_errors,
		);

		if( $is_submit && ! $has_errors ){
			$response['data_to_save'] = $this->before_save_second_step( $submitted_data );
		}
		return $response;
	}

	private function second_step_options( $submitted_data ) {
		if ( isset( $submitted_data['module_id'] ) ) {
			$module_id = $submitted_data['module_id'];
			$module = Hustle_Module_Model::instance()->get( $module_id );
			//$saved_api_key = Hustle_Icontact::_get_api_key( $module );
			$saved_auto_optin = Hustle_Icontact::_get_auto_optin( $module );	
		} else {
			//$saved_api_key = '';
			$saved_auto_optin = '';	
		}

		//$api_key    = ! isset( $submitted_data['api_key'] ) ? $saved_api_key : $submitted_data['api_key'];
		$checked = ! isset( $submitted_data['auto_optin'] ) ? $saved_auto_optin : $submitted_data['auto_optin'];
		$is_double_optin_enabled = ( 'pending' === $checked || '1' === $checked ) ? true : false;
		
		$options = array(
			"subscription_setup" => array(
				"id"    => "",
				"class" => "wpmudev-switch-labeled",
				"type"  => "wrapper",
				"elements" => array(
					"subscription_mode" => array(
						"id"    => "",
						"class" => "wpmudev-switch",
						"type"  => "wrapper",
						"elements" => array(
							"opt_in" => array(
								"type"          => 'checkbox',
								'name'          => "auto_optin",
								'id'            => "auto_optin",
								"default"       => "",
								'value'         => "pending",
								"attributes"    => array(
									'class'   => "toggle-checkbox hustle_provider_on_change_ajax",
									'checked' => $is_double_optin_enabled ? 'checked' : '',
									"data-action" => "hustle_icontact_get_existing_messages",
									"data-nonce"  => wp_create_nonce("hustle_icontact_get_existing_messages"),
									"data-dom_wrapper"  => "#hustle-icontact-message-section"
								)
							),
							"label" => array(
								"id"            => "auto_optin_label",
								"for"           => "auto_optin",
								"value"         => "",
								"type"          => "label",
								"attributes"    => array(
									'class'     => "wpmudev-switch-design"
								)
							)
						),
					),
					"switch_instructions" => array(
						"id"            => "auto_optin_label",
						"for"           => "auto_optin",
						"value"         => __("Enable double opt-in", Opt_In::TEXT_DOMAIN),
						"type"          => "label",
						"attributes"    => array(
							'class'     => "wpmudev-switch-label"
						)
					),
				),
			),
			"messages_section" => array(
				"id"    => "hustle-icontact-message-section",
				"class" => "auto_optin-eccmpty",
				"type"  => "wrapper",
				"elements" => array()
			),
		);

		if( $is_double_optin_enabled ) {
			$options['messages_section']['elements'] = $this->get_existing_messages_options( $submitted_data );
		}

		return $options;
	}

	private function get_existing_messages_options( $submitted_data ) {

		$app_id = $submitted_data['app_id'];
		$password = $submitted_data['password'];
		$username = $submitted_data['username'];

		$api = Hustle_Icontact::api( $app_id, $password, $username );
		if ( is_wp_error( $api ) ) {
			Hustle_Api_Utils::maybe_log( __METHOD__, $api->get_error_message() );
			return array();
		}
		$existing_messages = $api->get_existing_messages();
		$messages = $existing_messages['messages'];
		$confirmation_messages_list = array();
		foreach( $messages as $message ) {
			if( 'confirmation' === $message['messageType'] ) {
				$confirmation_messages_list[ $message['messageId'] ]['label'] = $message['messageName'];
				$confirmation_messages_list[ $message['messageId'] ]['value'] = $message['messageId'];
			}
		}

		if ( empty( $confirmation_messages_list ) ) {
			return array();
		}

		$current = current( $confirmation_messages_list );
		$selected_list = $current['value'];

		return array(
			"label" => array(
				"id"    => "confirmation_message_id_label",
				"for"   => "confirmation_message_id",
				"value" => __("Choose message:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"choose_email_list" => array(
				"type"          => 'select',
				'name'          => "confirmation_message_id",
				'id'            => "confirmation_message_id",
				"default"       => "",
				'options'       => $confirmation_messages_list,
				'value'         => $selected_list,
				'selected'      => $selected_list,
				"attributes"    => array(
					'class'         => "wpmudev-select",
				)
			),
			/*'loadmore' => array(
				"id"    => "loadmore_mailchimp_lists",
				"name"  => "loadmore_mailchimp_lists",
				"type"  => "button",
				"value" => __("Load More Lists", Opt_In::TEXT_DOMAIN),
				"class" => "wpmudev-button wph-button--spaced wph-button wph-button--filled wph-button--gray mailchimp_optin_load_more_lists wph-email-provider-lists-hide hustle_provider_on_click_ajax",
				"attributes"    => array(
					"data-action" => 'hustle_mailchimp_load_more_lists',
					"data-nonce" => wp_create_nonce('hustle_mailchimp_load_more_lists'),
					"data-load_more" => 'true',
					"data-dom_wrapper"  => "#optin-provider-account-options"
				)
			),*/
			"label" => array(
				"id"    => "confirmation_message_id_label",
				"for"   => "confirmation_message_id",
				"value" => __("Choose message:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			)
		);
	}

	public function get_existing_messages(){
		Hustle_Api_Utils::validate_ajax_call( 'hustle_icontact_get_existing_messages' );
		
		$sanitized_data = Hustle_Api_Utils::validate_and_sanitize_fields( $_POST ); // phpcs:ignore

		$double_optin_enabled = isset( $sanitized_data['auto_optin'] ) && 'pending' === $sanitized_data['auto_optin'] ? true : false;

		$html = $this->get_html_for_options( $this->get_existing_messages_options( $sanitized_data ) );

		$response = array(
			'html' => $double_optin_enabled ? $html : '',
			'wrapper' => $sanitized_data['dom_wrapper'],
		);
		wp_send_json_success( $response );
	}

	/**
	 * Registers AJAX endpoints for provider's custom actions
	 *
	 */
	public function register_ajax_endpoints(){
		add_action( "wp_ajax_hustle_icontact_refresh_lists", array( $this , "ajax_refresh_lists" ) );
		add_action( "wp_ajax_hustle_icontact_get_existing_messages", array( $this , "get_existing_messages" ) );
	}
}
if ( is_admin() ) {
	Hustle_Api_Utils::register_ajax_endpoints( 'Hustle_Icontact' );
}

endif;
