<?php
if( !class_exists("Hustle_HubSpot_Form_Settings") ):

/**
 * Class Hustle_HubSpot_Form_Settings
 * Form Settings HubSpot Process
 *
 */
class Hustle_HubSpot_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'Hubspot requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
		}

		$module_type =  $submitted_data['module_type'];

		// Make sure to use the correct module type's page for setting up the referrer.
		$this->provider->current_page = 'hustle_' . $module_type;

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
			$email_list = Hustle_HubSpot::_get_email_list( $module );
		} else {
			$email_list = '';
		}

		$options = array();
		$api = $this->provider->api();
		$is_authorize = $api && ! $api->is_error && $api->is_authorized();
		$link 	= sprintf( '<a href="#" class="hustle_provider_on_click_ajax" data-current_page="%s" data-action="update_hubspot_referrer" data-nonce="%s">%3$s</a>', $this->provider->current_page, wp_create_nonce('update_hubspot_referrer'), __( 'click here', Opt_In::TEXT_DOMAIN ) );

		if ( $api && ! $api->is_error ) {
			if ( ! $is_authorize ) {
				$info = __( 'Please %s to connect to your Hubspot account. You will be asked to give us access to your selected account and will be redirected back to this page.', Opt_In::TEXT_DOMAIN );
				$info = sprintf( $info, $link );
				$options['info'] = array(
					'type' 	=> 'label',
					'value' => $info,
					'for' 	=> '',
				);
			} else {
				$info = __( 'Please %s to reconnect to your Hubspot account. You will be asked to give us access to your selected account and will be redirected back to this page.', Opt_In::TEXT_DOMAIN );
				$info = sprintf( $info, $link );
				$list = $api->get_contact_list();
				$options = array(
					array(
						'type' 	=> 'label',
						'value' => $info,
						'for' 	=> '',
					),
					array(
						'type' 	=> 'label',
						'class'	=> 'wpmudev-label--loading',
						'for' 	=> 'list_id',
						"value" => "<span class='wpmudev-loading-text'>" . __( "Fetch Lists", Opt_In::TEXT_DOMAIN ) . "</span>",
					),
					array(
						'type' 		=> 'select',
						'id' 		=> 'wph-email-provider-lists',
						'name' 		=> 'list_id',
						'options' 	=> $list,
						'selected' 	=> $email_list,
						'class'     => "wpmudev-select"
					)
				);
			}
		}

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
		// TODO: add the selected list
		return $data;
	}

	public static function update_hubspot_referrer() {
		Hustle_Api_Utils::validate_ajax_call( "update_hubspot_referrer" );

		$module_id = filter_input( INPUT_POST, 'module_id', FILTER_VALIDATE_INT );
		$current_page = filter_input( INPUT_POST, 'current_page', FILTER_SANITIZE_STRING );

		if ( class_exists( 'Hustle_HubSpot_Api') ) {
			$hubspot = new Hustle_HubSpot_Api();
			$redirect_url = $hubspot->get_authorization_uri( $module_id, true, $current_page );
			
			wp_send_json_success(
				array( 'redirect_url' => $redirect_url )
			);
		} else {
			
			Hustle_Api_Utils::maybe_log( __CLASS__, 'Hustle_HubSpot_Api does not exist.' );
			wp_send_json_success(
				array( 
					'html' => '<label class="wpmudev-label--notice"><span>'. __('The required API does not exist.', Opt_In::TEXT_DOMAIN ) . '</span></label>',
					'wrapper' => '#wph-provider-account-details'
				)
			);
		}
	}
	
	/**
	 * Registers AJAX endpoints for provider's custom actions
	 *
	 */
	public function register_ajax_endpoints(){
		add_action( "wp_ajax_update_hubspot_referrer", array( __CLASS__ , "update_hubspot_referrer" ) );
	}
}
if ( is_admin() ) {
	Hustle_Api_Utils::register_ajax_endpoints( 'Hustle_HubSpot' );
}
endif;
