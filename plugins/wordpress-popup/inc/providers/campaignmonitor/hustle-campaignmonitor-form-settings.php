<?php
if( !class_exists("Hustle_Campaignmonitor_Form_Settings") ):
/**
 * Class Hustle_Campaignmonitor_Form_Settings
 * Form Settings Campaign Monitor Process
 *
 */
class Hustle_Campaignmonitor_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'Campaign Monitor requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
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
		
		if ( isset( $submitted_data['api_key'] ) ) {
			$api_key =  $submitted_data['api_key'];
		} elseif ( isset( $submitted_data['module_id'] ) ) {
			$module_id = $submitted_data['module_id'];
			$module = Hustle_Module_Model::instance()->get( $module_id );
			$api_key = Hustle_Campaignmonitor::_get_api_key( $module );
		} else {
			$api_key = '';
		}

		$api_key_tooltip = '<span class="wpoi-tooltip tooltip-right" tooltip="' . __('Once logged in, click on your profile picture at the top-right corner to open te menu, then click on Account Settings and finally click on API keys.', Opt_In::TEXT_DOMAIN) . '"><span class="dashicons dashicons-warning wpoi-icon-info"></span></span>';
		return array(
			"label" => array(
				"id"    => "api_key_label",
				"for"   => "api_key",
				"value" => __("Enter Your API Key:", Opt_In::TEXT_DOMAIN),
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
						"class"         => "wpmudev-input_text",
					),
					'refresh' => array(
						"id"    => "refresh-lists",
						"type"  => "ajax_button",
						"value" => "<span class='wpmudev-loading-text'>" . __( "Fetch Lists", Opt_In::TEXT_DOMAIN ) . "</span><span class='wpmudev-loading'></span>",
						'class' => "wpmudev-button wpmudev-button-sm hustle_provider_on_click_ajax",
						"attributes" => array(
							"data-action" => "hustle_campaignmonitor_refresh_lists",
							"data-nonce"  => wp_create_nonce("hustle_campaignmonitor_refresh_lists"),
							"data-dom_wrapper"  => "#optin-provider-account-options"
						)
					),
				)
			),
			"instructions" => array(
				"id" => "optin_api_instructions",
				"for" => "",
				"value" => sprintf(
					esc_html__( 'To get your API key, log in to your %1$s, then click on your profile picture at the top-right corner to open a menu, then select %2$s and finally click on %3$s.', Opt_In::TEXT_DOMAIN),
					sprintf( '<a href="%1$s" target="_blank">%2$s</a>',
						'https://login.createsend.com/l/?ReturnUrl=%2Faccount%2Fapikeys',
						esc_html__( 'Campaign Monitor account' )
					),
					sprintf( '<strong>%s</strong>', esc_html__( 'Account Settings' ) ),
					sprintf( '<strong>%s</strong>', esc_html__( 'API keys' ) )
				),
				"type" => "small",
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
		Hustle_Api_Utils::validate_ajax_call( 'hustle_campaignmonitor_refresh_lists' );
		
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
		
		// Check if API key is valid
		try {
			$clients = Hustle_Campaignmonitor::api( $api_key )->get_clients();
		} catch ( CurlException $e ) {
			Hustle_Api_Utils::maybe_log( $e->message );
			return '<label class="wpmudev-label--notice"><span>' . __( 'There was an error retrieving the options. Please make sure your API key is okay.' , Opt_In::TEXT_DOMAIN ) . '</span></label>';
		}
		if ( $clients->was_successful() ) {
			$options = $this->refresh_lists_options( $clients, $api_key );
	
			if ( !is_wp_error( $options ) ) {
				$html = '';
				if ( !empty( $options ) ) {
					foreach( $options as $key =>  $option ){
						$html .= Hustle_Api_Utils::static_render("general/option", array_merge( $option, array( "key" => $key ) ), true);
					}
				}
				return $html;
			}
		}
		
		return '<label class="wpmudev-label--notice"><span>' . __( 'There was an error retrieving the options. Please make sure your API key is okay.' , Opt_In::TEXT_DOMAIN ) . '</span></label>';
	}

	/**
	 * Retrieves options of the Campaign Monitor account with the given api_key
	 *
	 * @param string $submitted_data
	 * @return array
	 */
	private function refresh_lists_options( $clients, $api_key ) {

		$cids = array();
		$lists = array();

		foreach( $clients->response as $client => $details ) {
			$cids[] = $details->ClientID; // phpcs:ignore
		}

		if ( ! empty( $cids ) ) {
			foreach( $cids as $id ) {
				$client = new CS_REST_Clients( $id,  array('api_key' => $api_key) );
				$_lists = $client->get_lists();

				foreach ( $_lists->response as $key => $list ) {
					$lists[ $list->ListID ]['value'] = $list->ListID; // phpcs:ignore
					$lists[ $list->ListID ]['label'] = $list->Name; // phpcs:ignore

				}
			}
		}

		$first = count( $lists ) > 0 ? reset( $lists ) : "";
		if( !empty( $first ) )
			$first = $first['value'];

		return array(
			"label" => array(
				"id"    => "list_id_label",
				"for"   => "list_id",
				"value" => __("Choose Email List:", Opt_In::TEXT_DOMAIN),
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
				"attributes"    => array(
					'class'         => "wpmudev-select"
				)
			)
		);
	}

	/**
	 * Registers AJAX endpoints for provider's custom actions
	 *
	 */
	public function register_ajax_endpoints(){
		add_action( "wp_ajax_hustle_campaignmonitor_refresh_lists", array( $this , "ajax_refresh_lists" ) );
	}
}
if ( is_admin() ) {
	Hustle_Api_Utils::register_ajax_endpoints( 'Hustle_Campaignmonitor' );
}

endif;
