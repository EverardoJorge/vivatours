<?php
if( !class_exists("Hustle_Mad_Mimi_Form_Settings") ):

/**
 * Class Hustle_Mad_Mimi_Form_Settings
 * Form Settings Mad Mimi Process
 *
 */
class Hustle_Mad_Mimi_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
		);
	}
		
	/**
	 * Check if step is completed
	 *
	 * @since 3.0.5 
	 * @return bool
	 */
	public function first_step_is_completed( $submitted_data ) {
		// Do validation here
		return true;
	}
	
	/**
	 * Returns all settings and conditions for 1st step of Provider settings
	 *
	 * @since 3.0.5
	 *
	 * @param array $submitted_data
	 * @param boolean $validate
	 * @return array
	 */
	public function first_step_callback( $submitted_data, $validate ) {
		$error_message = '';

		if( ! $this->provider->is_activable() ) {
			wp_send_json_error( 'Mad Mimi requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
		}
		
		$options = $this->first_step_options( $submitted_data );

		$html = '';
		foreach( $options as $key =>  $option ) {
			$html .= Hustle_Api_Utils::static_render("general/option", array_merge( $option, array( "key" => $key ) ), true);
		}

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
				'markup' => $this->get_next_button_markup(),
			), 
		);
		
		$response = array(
			'html'       => $step_html,
			'buttons'    => $buttons,
			'has_errors' => $has_errors,
		);

		if( $validate ){
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
			$saved_api_key = Hustle_Mad_Mimi::_get_api_key( $module );
			$saved_username =  Hustle_Mad_Mimi::_get_username( $module );
		} else {
			$saved_api_key = '';
			$saved_username = '';
		}
		$api_key 	= ! isset( $submitted_data['api_key'] ) ? $saved_api_key : $submitted_data['api_key'];
		$username 	= ! isset( $submitted_data['username'] ) ? $saved_username : $submitted_data['username'];

		return array(
			"username_label" => array(
				"id"    => "username_label",
				"for"   => "username",
				"value" => __("Enter your username (email address):", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"optin_username_field_wrapper" => array(
				"id"    => "optin_username_id",
				"class" => "optin_username_id_wrapper",
				"type"  => "wrapper",
				"elements" => array(
					"username_field" => array(
						"id"            => "username",
						"name"          => "username",
						"type"          => "text",
						"default"       => "",
						"value"         => $username,
						"placeholder"   => "",
						"class"         => "wpmudev-input_text"
					)
				)
			),
			"api_key_label" => array(
				"id"    => "api_key_label",
				"for"   => "api_key",
				"value" => __("Enter your API key:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"wrapper" => array(
				"id"    => "wpoi-get-lists",
				"class" => "wpmudev-provider-group",
				"type"  => "wrapper",
				"elements" => array(
					"api_key" => array(
						"id"            => "api_key",
						"name"          => "api_key",
						"type"          => "text",
						"default"       => "",
						"value"         => $api_key,
						"placeholder"   => "",
						"class"         => "wpmudev-input_text"
					),
					'refresh' => array(
						"id"    => "refresh-lists",
						"type"  => "ajax_button",
						"value" => "<span class='wpmudev-loading-text'>" . __( "Fetch Lists", Opt_In::TEXT_DOMAIN ) . "</span><span class='wpmudev-loading'></span>",
						'class' => "wpmudev-button wpmudev-button-sm hustle_provider_on_click_ajax",
						"attributes" => array(
							"data-action" => "hustle_mad_mimi_refresh_lists",
							"data-nonce"  => wp_create_nonce("hustle_mad_mimi_refresh_lists"),
							"data-dom_wrapper"  => "#optin-provider-account-options"
						)
					),
				)
			),
			"instructions" => array(
				"id"    => "optin_api_instructions",
				"for"   => "",
				"value" => sprintf(__("Log in to your <a href='%s' target='_blank'>Mad Mimi account</a> to get your API Key.", Opt_In::TEXT_DOMAIN), 'https://madmimi.com' ),
				"type"  => "small",
			),
		);
	}

	/**
	 * Returns array with $html to be inserted into the $wrapper DOM object
	 *
	 * @since 3.0.5
	 *
	 * @return array
	 */
	public function ajax_refresh_lists() {
		Hustle_Api_Utils::validate_ajax_call( 'hustle_mad_mimi_refresh_lists' );
		
		$submitted_data = Hustle_Api_Utils::validate_and_sanitize_fields( $_REQUEST );
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

		$username = $submitted_data['username'];
		$api_key = $submitted_data['api_key'];
		
		// Check if API key is valid
		$_lists = Hustle_Mad_Mimi::api( $username, $api_key )->get_lists();

		if( ! is_wp_error( $_lists ) && ! empty( $_lists ) ) {
			$options = $this->refresh_lists_options( $_lists );
			
			if ( !is_wp_error( $options ) ) {
				$html = '';
				if ( !empty( $options ) ) {
					foreach( $options as $key =>  $option ){
						$html .= Hustle_Api_Utils::static_render("general/option", array_merge( $option, array( "key" => $key ) ), true);
					}
				}
				return $html;
				
			} else {
				Hustle_Api_Utils::maybe_log( implode( "; ", $options->get_error_messages() ) );
				
				return '<label class="wpmudev-label--notice"><span>' . __( 'There was an error retrieving the options.' , Opt_In::TEXT_DOMAIN ) . '</span></label>';
			}
			
		} else {
			if( is_wp_error( ( array ) $_lists ) )
				Hustle_Api_Utils::maybe_log( implode( "; ", $_lists->get_error_messages() ) );

			return '<label class="wpmudev-label--notice"><span>' . __( 'No audience list defined for this account. Please double check your settings are okay.' , Opt_In::TEXT_DOMAIN ) . '</span></label>';
		}
	}

	/**
	 * Retrieves options of the Mad Mimi account with the given api_key
	 *
	 * @param string $submitted_data
	 * @return array
	 */
	private function refresh_lists_options( $_lists ) {
		if( !is_array( $_lists )  )
			$_lists = array( $_lists );

		$lists = array();
		foreach( $_lists as $list ){
			$list = (object) (array) $list;
			$list = $list->{'@attributes'};
			$lists[ $list['id']]['value'] = $list['id'];
			$lists[ $list['id']]['label'] = $list['name'];
		}


		$first = count( $lists ) > 0 ? reset( $lists ) : "";
		if( !empty( $first ) )
			$first = $first['value'];

		return  array(
			"label" => array(
				"id"    => "list_id_label",
				"for"   => "list_id",
				"value" => __("Choose email list:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"choose_email_list" => array(
				"type"      => 'select',
				'name'      => "list_id",
				'id'        => "wph-email-provider-lists",
				"default"   => "",
				'options'   => $lists,
				'value'     => $first,
				'selected'  => $first,
				'class'     => "wpmudev-select"
			)
		);
	}

	/**
	 * Registers AJAX endpoints for provider's custom actions
	 *
	 */
	public function register_ajax_endpoints(){
		add_action( "wp_ajax_hustle_mad_mimi_refresh_lists", array( $this , "ajax_refresh_lists" ) );
	}
}
if ( is_admin() ) {
	Hustle_Api_Utils::register_ajax_endpoints( 'Hustle_Mad_Mimi' );
}

endif;
