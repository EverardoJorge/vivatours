<?php
if( !class_exists("Hustle_Sendy_Form_Settings") ):

/**
 * Class Hustle_Sendy_Form_Settings
 * Form Settings Sendy Process
 *
 */
class Hustle_Sendy_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'Sendy requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
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
			$saved_api_key = Hustle_Sendy::_get_api_key( $module );
			$saved_email_list = Hustle_Sendy::_get_email_list( $module );
			$saved_installation_url = Hustle_Sendy::_get_api_url( $module );
		} else {
			$saved_api_key = '';
			$saved_email_list = '';
			$saved_installation_url = '';
		}
		$api_key    		= ! isset( $submitted_data['api_key'] ) ? $saved_api_key : $submitted_data['api_key'];
		$email_list 		= ! isset( $submitted_data['list_id'] ) ? $saved_email_list : $submitted_data['list_id'];
		$installation_url 	= ! isset( $submitted_data['installation_url'] ) ? $saved_installation_url : $submitted_data['installation_url'];
		
		return array(
			"label" => array(
				"id"    => "api_key_label",
				"for"   => "api_key",
				"value" => __("Enter your API key:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"api_wrapper" => array(
				"id"    => "wpoi-sendy-api-text",
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
				)
			),

			"choose_email_list_label" => array(
				"id"    => "list_id_label",
				"for"   => "wpoi-sendy-get-lists",
				"value" => __("Enter list id:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"list_wrapper" => array(
				"id"    => "wpoi-sendy-get-lists",
				"class" => "wpmudev-provider-group",
				"type"  => "wrapper",
				"elements" => array(
					"choose_email_list" => array(
						"type"          => 'text',
						'name'          => "list_id",
						'id'            => "list_id",
						'value'         => $email_list,
						"placeholder"   => "",
						"class"         => "wpmudev-input_text"
					),
				)
			),

			"installation_url_label" => array(
				"id"    => "installation_url_label",
				"for"   => "installation_url",
				"value" => __("Enter Sendy installation URL:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"installation_wrapper" => array(
				"id"    => "wpoi-sendy-installation-url",
				"class" => "wpmudev-provider-group",
				"type"  => "wrapper",
				"elements" => array(
					"installation_url" => array(
						"id"            => "installation_url",
						"name"          => "installation_url",
						"type"          => "text",
						"default"       => "",
						"value"         => $installation_url,
						"placeholder"   => "",
						"class"         => "wpmudev-input_text"
					),
				)
			),

			"instructions" => array(
				"id"    => "optin_api_instructions",
				"for"   => "",
				"value" => __("Log in to your Sendy installation to get your API Key and list id.", Opt_In::TEXT_DOMAIN),
				"type"  => "small",
			),
		);
	}
}

endif;
