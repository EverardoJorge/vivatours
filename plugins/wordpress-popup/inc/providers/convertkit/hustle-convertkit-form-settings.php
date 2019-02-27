<?php
if( !class_exists("Hustle_ConvertKit_Form_Settings") ):

/**
 * Class Hustle_ConvertKit_Form_Settings
 * Form Settings ConvertKit Process
 *
 */
class Hustle_ConvertKit_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'ConvertKit requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
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
		
		$link 		 = '<a href="https://app.convertkit.com/account/edit" target="_blank">ConvertKit</a>';
		$instruction = sprintf( __( 'Log in to your %s account to get your API Key.', Opt_In::TEXT_DOMAIN ), $link );
		
		if ( isset( $submitted_data['module_id'] ) ) {
			$module_id = $submitted_data['module_id'];
			$module = Hustle_Module_Model::instance()->get( $module_id );
			$saved_api_key = Hustle_ConvertKit::_get_api_key( $module );
			$saved_api_secret = Hustle_ConvertKit::_get_api_secret( $module );	
		} else {
			$saved_api_key = '';
			$saved_api_secret = '';	
		}
		
		$api_key = ! isset( $submitted_data['api_key'] ) ? $saved_api_key : $submitted_data['api_key'];
		$api_secret = ! isset( $submitted_data['api_secret'] ) ? $saved_api_secret : $submitted_data['api_secret'];

		$options = array(
			'api_secret_label' => array(
				'id' 	=> 'api-secret-label',
				'for' 	=> 'api_secret',
				'value' => __("Enter your API Secret:", Opt_In::TEXT_DOMAIN),
				'type' 	=> 'label',
			),
			'optin_api_secret_wrapper' => array(
				'id' 	=> 'wpoi-api-secret-wrapper',
				'class' => 'wpmudev-provider-group',
				'type' 	=> 'wrapper',
				'elements' => array(
					'api_secret' => array(
						'id' 			=> 'api_secret',
						'name' 			=> 'api_secret',
						'type' 			=> 'text',
						'default' 		=> '',
						'value' 		=> $api_secret,
						'placeholder' 	=> '',
						"class"         => "wpmudev-input_text",
					),
				)
			),
			'label' => array(
				'id' 	=> 'api_key_label',
				'for' 	=> 'api_key',
				'value' => __("Enter your API Key:", Opt_In::TEXT_DOMAIN),
				'type' 	=> 'label',
			),
			'wrapper' => array(
				'id' 	=> 'wpoi-get-lists',
				'class' => 'wpmudev-provider-group',
				'type' 	=> 'wrapper',
				'elements' => array(
					'api_key' => array(
						'id' 			=> 'api_key',
						'name' 			=> 'api_key',
						'type' 			=> 'text',
						'default' 		=> '',
						'value' 		=> $api_key,
						'placeholder' 	=> '',
						"class"         => "wpmudev-input_text",
					),
					'refresh' => array(
						'id' 	=> 'refresh-lists',
						'type' 	=> 'ajax_button',
						"value" => "<span class='wpmudev-loading-text'>" . __( "Fetch Forms", Opt_In::TEXT_DOMAIN ) . "</span><span class='wpmudev-loading'></span>",
						'class' => "wpmudev-button wpmudev-button-sm hustle_provider_on_click_ajax",
						"attributes" => array(
							"data-action" => "hustle_convertkit_refresh_lists",
							"data-nonce"  => wp_create_nonce("hustle_convertkit_refresh_lists"),
							"data-dom_wrapper"  => "#optin-provider-account-options"
						)
					),
				)
			),
			'instruction' => array(
				'id' 	=> 'optin_convertkit_instruction',
				'type' 	=> 'small',
				'value' => $instruction,
				'for' 	=> '',
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
		Hustle_Api_Utils::validate_ajax_call( 'hustle_convertkit_refresh_lists' );
		
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
		$api_key = $submitted_data['api_key'];
		$forms = Hustle_ConvertKit::api( $api_key )->get_forms();
		
		if( ! is_wp_error( $forms ) ) {
			$options = $this->refresh_lists_options( $forms );
	
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
			Hustle_Api_Utils::maybe_log( implode( "; ", $forms->get_error_messages() ) );
			return '<label class="wpmudev-label--notice"><span>' . __( 'No active form is found for the API. Please set up a form in ConvertKit or check your API.' , Opt_In::TEXT_DOMAIN ) . '</span></label>';
		}
	}

	/**
	 * Retrieves options of the Provider's account with the given api_key
	 *
	 * @param string $submitted_data
	 * @return array
	 */
	private function refresh_lists_options( $forms ) {
		$lists = array();
		foreach(  ( array) $forms as $form ){
			$lists[ $form->id ]['value'] = $form->id;
			$lists[ $form->id ]['label'] = $form->name;
		}
		
		$first = count( $lists ) > 0 ? reset( $lists ) : "";
		if( !empty( $first ) ) 
			$first = $first['value'];

		return  array(
			"label" => array(
				"id" => "list_id_label",
				"for" => "list_id",
				"value" => __("Choose a form:", Opt_In::TEXT_DOMAIN),
				"type" => "label",
			),
			"choose_email_list" => array(
				"type" 			=> 'select',
				'name' 			=> "list_id",
				'id' 			=> "wph-email-provider-lists",
				"default" 		=> "",
				'options' 		=> $lists,
				'value' 		=> $first,
				'selected' 		=> $first,
				"attributes" 	=> array(
					"data-nonce" 	=> wp_create_nonce("convert_kit_choose_form"),
					'class' 		=> "wpmudev-select convert_kit_choose_form"
				)
			)
		);
	}

	/**
	 * Registers AJAX endpoints for provider's custom actions
	 *
	 */
	public function register_ajax_endpoints(){
		add_action( "wp_ajax_hustle_convertkit_refresh_lists", array( $this , "ajax_refresh_lists" ) );
	}
}
if ( is_admin() ) {
	Hustle_Api_Utils::register_ajax_endpoints( 'Hustle_ConvertKit' );
}

endif;
