<?php
if( !class_exists("Hustle_Activecampaign_Form_Settings") ):

/**
 * Class Hustle_Activecampaign_Form_Settings
 * Form Settings ActiveCampaign Process
 *
 */
class Hustle_Activecampaign_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'ActiveCampaign requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
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
		//$step_html .= $this->get_current_list_name_markup();
		
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

		if( $validate ) {
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
			$saved_api_key = Hustle_Activecampaign::_get_api_key( $module );
			$saved_ac_url = Hustle_Activecampaign::_get_api_url( $module );
			$saved_sign_up_to = Hustle_Activecampaign::get_sign_up_to( $module );
		} else {
			$saved_api_key = '';
			$saved_ac_url = '';
			$saved_sign_up_to = '';
		}
		
		$api_key = ! isset( $submitted_data['api_key'] ) ? $saved_api_key : $submitted_data['api_key'];
		$ac_url = ! isset( $submitted_data['url'] ) ? $saved_ac_url : $submitted_data['url'];
		$sign_up_to = ! isset( $submitted_data['sign_up_to'] ) ? $saved_sign_up_to : $submitted_data['sign_up_to'];

		return array(
			"url_label" => array(
				"id"    => "url_label",
				"for"   => "url",
				"value" => __("Enter your ActiveCampaign URL:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"url_field_wrapper" => array(
				"id"        => "url_id",
				"class"     => "url_id_wrapper",
				"type"      => "wrapper",
				"elements"  => array(
					"url_field" => array(
						"id"            => "url",
						"name"          => "url",
						"type"          => "text",
						"default"       => "",
						"value"         => $ac_url,
						"placeholder"   => "",
						"class"         => "wpmudev-input_text",
					)
				)
			),
			"api_key_label" => array(
				"id" => "api_key_label",
				"for" => "api_key",
				"value" => __("Enter your API key:", Opt_In::TEXT_DOMAIN),
				"type" => "label",
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
						"class"         => "wpmudev-input_text",
					),
				)
			),
			"instructions" => array(
				"id"    => "optin_api_instructions",
				"for"   => "",
				"value" => __("Log in to your <a href='http://www.activecampaign.com/login/' target='_blank'>ActiveCampaign account</a> to get your URL and API Key.", Opt_In::TEXT_DOMAIN),
				"type"  => "small",
			),
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
								'name'          => "sign_up_to",
								'id'            => "sign_up_to",
								"default"       => "",
								'value'         => "form",
								"attributes"    => array(
									'class'   => "toggle-checkbox",
									'checked' => ( 'form' === $sign_up_to ) ? 'checked' : ''
								)
							),
							"label" => array(
								"id"            => "sign_up_to_label",
								"for"           => "sign_up_to",
								"value"         => "",
								"type"          => "label",
								"attributes"    => array(
									'class'     => "wpmudev-switch-design"
								)
							)
						),
					),
					"switch_instructions" => array(
						"id"            => "sign_up_to_instructions",
						"for"           => "sign_up_to",
						"value"         => __("Enable to choose from your existing Forms instead of your existing Lists.", Opt_In::TEXT_DOMAIN),
						"type"          => "label",
						"attributes"    => array(
							'class'     => "wpmudev-switch-label"
						)
					),
				),
			),
			"more_switch_instructions" => array(
				"for"           => "sign_up_to",
				"value"         => __("Double opt-in is only available when using Forms.", Opt_In::TEXT_DOMAIN),
				"type"          => "label",
			),
		);
	}

	
	/**
	 * Second step callback
	 *
	 * @since 3.0.7
	 *
	 * @param array $submitted_data
	 * @param boolean $validate
	 * @return array
	 */
	public function second_step_callback( $submitted_data, $validate ) {
		$error_message = '';

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
			'previous' => array(
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

		if( $validate ) {
			$response['data_to_save'] = $submitted_data;
		}
		return $response;
	}

	/**
	 * Returns array with options for second step
	 *
	 * @since 3.0.7
	 *
	 * @param string $submitted_data
	 * @return array
	 */
	private function second_step_options( $submitted_data ) {
		
		$sign_up_to = $submitted_data['sign_up_to'];
		$url = $submitted_data['url'];
		$api_key = $submitted_data['api_key'];
		
		// Retrieve lists if "sign_up_to" is not set to "forms".
		if ( 'form' !== $sign_up_to ) {
			$_lists = Hustle_Activecampaign::api( $url, $api_key )->get_lists();
	
			if( is_wp_error( $_lists ) || empty( $_lists ) ) {
				if( is_wp_error( $_lists ) )
				Hustle_Api_Utils::maybe_log( implode( "; ", $_lists->get_error_messages() ) );
	
				return array(
					"label" => array(
						"class"   => "wpmudev-label--notice",
						"value" => '<span>' . __( 'No audience list defined for this account. Please double check your settings are okay.' , Opt_In::TEXT_DOMAIN ) . '</span>',
						"type"  => "label",
					),
				);
			}
	
			if( !is_array( $_lists )  )
				$_lists = array( $_lists );
	
			$lists = array();
			foreach(  ( array) $_lists as $list ){
				$list = (object) (array) $list;
	
				$lists[ $list->id ] = array(
					'value' => $list->id,
					'label' => $list->name,
				);
	
			}
	
			$first = count( $lists ) > 0 ? reset( $lists ) : "";
			if( !empty( $first ) )
				$first = $first['value'];
	
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
					'value'         => $first,
					'selected'      => $first,
					'class' 		=> 'wpmudev-select',
				)
			);	

		} else { 
			// Retrieve forms otherwise
			
			$_forms = Hustle_Activecampaign::api( $url, $api_key )->get_forms();

			if( is_wp_error( $_forms ) || empty( $_forms ) ) {
				if( is_wp_error( $_forms ) )
				Hustle_Api_Utils::maybe_log( implode( "; ", $_forms->get_error_messages() ) );
	
				return array(
					"label" => array(
						"class"   => "wpmudev-label--notice",
						"value" => '<span>' . __( 'No audience list defined for this account. Please double check your settings are okay.' , Opt_In::TEXT_DOMAIN ) . '</span>',
						"type"  => "label",
					),
				);
			}

			$forms = array();
			foreach( $_forms as $form => $data ) {
				$forms[ $data['id'] ] = array(
					'value' => $data['id'],
					'label' => $data['name'],
				);
			}

			$first = count( $forms ) > 0 ? reset( $forms ) : "";
			if( !empty( $first ) )
				$first = $first['value'];
	
			return  array(
				"label" => array(
					"id"    => "form_id_label",
					"for"   => "form_id",
					"value" => __("Choose form:", Opt_In::TEXT_DOMAIN),
					"type"  => "label",
				),
				"choose_email_form" => array(
					"type"          => 'select',
					'name'          => "form_id",
					'id'            => "wph-email-provider-lists",
					"default"       => "",
					'options'       => $forms,
					'value'         => $first,
					'selected'      => $first,
					"class"			=> "wpmudev-select",
				)
			);	

		}
	}

}

endif;
