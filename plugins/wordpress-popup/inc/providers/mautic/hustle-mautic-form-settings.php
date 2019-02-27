<?php
if( !class_exists("Hustle_Mautic_Form_Settings") ):

/**
 * Class Hustle_Mautic_Form_Settings
 * Form Settings Mautic Process
 *
 */
class Hustle_Mautic_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'Mautic requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
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
			$saved_url = Hustle_Mautic::_get_api_url( $module );
			$saved_username = Hustle_Mautic::_get_api_username( $module );
			$saved_password = Hustle_Mautic::_get_api_password( $module );
		} else {
			$saved_url = '';
			$saved_username = '';
			$saved_password = '';
		}
		$url 		= ! isset( $submitted_data['url'] ) ? $saved_url : $submitted_data['url'];
		$username 	= ! isset( $submitted_data['username'] ) ? $saved_username : $submitted_data['username'];
		$password 	= ! isset( $submitted_data['password'] ) ? $saved_password : $submitted_data['password'];

		$options = array(
			'opt_base_url_label' => array(
				'id' 	=> 'opt_base_url_label',
				'for' 	=> 'url',
				'type' 	=> 'label',
				'value' => __( 'Enter your Mautic installation URL', Opt_In::TEXT_DOMAIN ),
			),
			'opt_url' => array(
				'id' 			=> 'url',
				'name' 			=> 'url',
				'value' 		=> $url,
				'placeholder' 	=> 'https://your-name-here.mautic.net',
				'type' 			=> 'text',
				"class"         => "wpmudev-input_text",
			),
			array(
				'id' 	=> 'username-label',
				'for' 	=> 'username',
				'type' 	=> 'label',
				'value' => __( 'Enter your login email', Opt_In::TEXT_DOMAIN ),
			),
			array(
				'id' 		=> 'username',
				'name' 		=> 'username',
				'type' 		=> 'text',
				'value' 	=> $username,
				"class" 	=> "wpmudev-input_text"
			),
			array(
				'id' 	=> 'opt-pass-label',
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
							"data-action" => "hustle_mautic_refresh_lists",
							"data-nonce"  => wp_create_nonce("hustle_mautic_refresh_lists"),
							"data-dom_wrapper"  => "#optin-provider-account-options"
						)
					),
				),
			),
			"instructions" => array(
				"id"    => "optin_api_instructions",
				"for"   => "",
				"value" => __( "Ensure you enable API and HTTP Basic Auth in your Mautic configuration API settings. Your Mautic installation URL must start with either http or https", Opt_In::TEXT_DOMAIN ),
				"type"  => "small",
			),
		);

		return $options;
	}

	/**
	 * Modifies the data from 1st step that's going to be saved
	 *
	 * @since 3.0.5
	 *
	 * @param array $data
	 * @return array
	 */
	protected function before_save_first_step( $data ) {
		if( isset( $data['url'] ) ) {
			$data['desc'] = $data['url'];
		}
		return $data;
	}

	/**
	 * Returns array with $html to be inserted into the $wrapper DOM object
	 *
	 * @since 3.0.5
	 *
	 * @return array
	 */
	public function ajax_refresh_lists() {
		Hustle_Api_Utils::validate_ajax_call( 'hustle_mautic_refresh_lists' );
		
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

		$url = $submitted_data['url'];
		$username = $submitted_data['username'];
		$password = $submitted_data['password'];
		$_lists = array();
		
		// Check if API key is valid
		$api = Hustle_Mautic::api( $url, $username, $password );
		if ( $api ) {
			$_lists = $api->get_segments();
		}

		if( ! is_wp_error( $_lists ) && ! empty( $_lists ) ) {
			$options = $this->refresh_lists_options( $_lists );
		
			if ( !is_wp_error( $options ) ) {
				return $this->get_html_for_options( $options );
				
			} else {
				Hustle_Api_Utils::maybe_log( implode( "; ", $options->get_error_messages() ) );
				
				return '<label class="wpmudev-label--notice"><span>' . __( 'There was an error retrieving the options.' , Opt_In::TEXT_DOMAIN ) . '</span></label>';
			}
			
		} else {
			if( is_wp_error( $_lists ) )
				Hustle_Api_Utils::maybe_log( implode( "; ", $_lists->get_error_messages() ) );

			return '<label class="wpmudev-label--notice"><span>' . __( 'No audience list defined for this account. Please double check your settings are okay.' , Opt_In::TEXT_DOMAIN ) . '</span></label>';
	
		}
	}

	/**
	 * Retrieves options of the Mautic account with the given api_key
	 *
	 * @param string $submitted_data
	 * @return array
	 */
	private function refresh_lists_options( $segments ) {
		$value 		= '';
		$list 		= array();

		foreach ( $segments as $segment ) {
			$list[ $segment['id'] ] = array(
				'value' => $segment['id'],
				'label' => $segment['name'],
			);
		}

		return array(
			array(
				'type' 	=> 'label',
				'for' 	=> 'list_id',
				'value' => __( 'Choose Segment', Opt_In::TEXT_DOMAIN ),
			),
			array(
				'label' 	=> __( 'Choose Segment', Opt_In::TEXT_DOMAIN ),
				'id' 		=> 'list_id',
				'name' 		=> 'list_id',
				'type' 		=> 'select',
				'value' 	=> $value,
				'options' 	=> $list,
				'selected' 	=> $value,
				"attributes"    => array(
					'class'         => "wpmudev-select"
				)
			),
		);
	}

	/**
	 * Registers AJAX endpoints for provider's custom actions
	 *
	 */
	public function register_ajax_endpoints(){
		add_action( "wp_ajax_hustle_mautic_refresh_lists", array( $this , "ajax_refresh_lists" ) );
	}
}
if ( is_admin() ) {
	Hustle_Api_Utils::register_ajax_endpoints( 'Hustle_Mautic' );
}

endif;
