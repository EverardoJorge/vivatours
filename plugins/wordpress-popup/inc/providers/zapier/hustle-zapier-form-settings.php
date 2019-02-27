<?php
if( !class_exists("Hustle_Zapier_Form_Settings") ):

/**
 * Class Hustle_Zapier_Form_Settings
 * Form Settings Zapier Process
 *
 */
class Hustle_Zapier_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'Zapier requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
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
		//$step_html .= $this->get_current_settings( $submitted_data['module_id'] );
		
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
		
		if ( isset( $submitted_data['api_key'] ) ) {
			$webhook_url =  $submitted_data['api_key'];
		} elseif ( isset( $submitted_data['module_id'] ) ) {
			$module_id = $submitted_data['module_id'];
			$module = Hustle_Module_Model::instance()->get( $module_id );
			$webhook_url = Hustle_Zapier::_get_webhook_url( $module );
		} else {
			$webhook_url = '';
		}

		return array(
			"optin_url_label" => array(
				"id"    => "optin_url_label",
				"for"   => "optin_url",
				"value" => __("Enter a Webhook URL:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"optin_url_field_wrapper" => array(
				"id"        => "optin_url_id",
				"class"     => "optin_url_id_wrapper",
				"type"      => "wrapper",
				"elements"  => array(
					"optin_url_field" => array(
						"id"            => "optin_url",
						"name"          => "api_key",
						"type"          => "text",
						"default"       => "",
						"value"         => $webhook_url,
						"placeholder"   => "",
						"class"         => "wpmudev-input_text",
					)
				)
			),
			"instructions" => array(
				"id"    => "optin_api_instructions",
				"for"   => "",
				"value" => sprintf( __("Create a trigger into <a href='%s' target='_blank'>Zapier</a> using \"Webhooks\" app and choose \"Catch Hook\" option. Then insert Webhook URL above.", Opt_In::TEXT_DOMAIN ), 'https://zapier.com/app/editor/' ),
				"type"  => "small",
			),
		);
	}

}

endif;
