<?php
if( !class_exists("Hustle_ConstantContact_Form_Settings") ):

/**
 * Class Hustle_ConstantContact_Form_Settings
 * Form Settings ActiveCampaign Process
 *
 */
class Hustle_ConstantContact_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'Constant Contact requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
		}
		
		$module_type =  $submitted_data['module_type'];

		// Make sure to use the correct module type's page for setting ConstantContact referrer
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
		
		$api = Hustle_ConstantContact::static_api();
		if ( is_wp_error( $api ) ) {
			return array(
				'auth_code_label' => array(
					"id"    => "auth_code_label",
					"value" => __('An error occured initializing Constant Contact', Opt_In::TEXT_DOMAIN),
					"type"  => "label",
				)
			);
		}

	    $access_token = $api->get_token( 'access_token' );

		if ( !$access_token ) {
			
	        $default_options = array(
		        'auth_code_label' => array(
			        "id"    => "auth_code_label",
			        "value" => sprintf(
				        __('Please <a href="#" class="hustle_provider_on_click_ajax" data-current_page="%1$s" data-action="update_constantcontact_referrer" data-nonce="%2$s">click here</a> to connect to ConstantContact. You will be asked to give us access to your ConstantContact account and then be redirected back to this screen.', Opt_In::TEXT_DOMAIN),
				        $this->provider->current_page, wp_create_nonce('update_constantcontact_referrer')
			        ),
			        "type" => "label",
		        ),
				'notice' => array(
					'type' => 'notice',
					'value' => __( 'ConstantContact requires your site to have SSL certificate.', Opt_In::TEXT_DOMAIN ),
					'class' => 'wpmudev-label--notice wpmudev-label--persist_notice'
				)
			);

			if ( is_ssl() ) {
				unset( $default_options['notice'] );
			}
			
			return $default_options;
		}

	    if ( isset( $submitted_data['module_id'] ) ) {
			$module_id = $submitted_data['module_id'];
			$module = Hustle_Module_Model::instance()->get( $module_id );
			$email_list = Hustle_ConstantContact::_get_email_list( $module );
	    } else {
			$email_list = '';
		}

		$list = $this->first_step_get_lists( $api );
		
		$default_options =  array(
			"auth_label" => array(
				"id" => "auth_code_label",
			    "value" => sprintf(
			    	__('Please <a href="#" class="hustle_provider_on_click_ajax" data-current_page="%1$s" data-action="update_constantcontact_referrer" data-nonce="%2$s">click here</a> to reconnect to ConstantContact. You will be asked to give us access to your ConstantContact account and then be redirected back to this screen.', Opt_In::TEXT_DOMAIN),
				    $this->provider->current_page, wp_create_nonce('update_constantcontact_referrer')
			    ),
			    "type" => "label",
			),
			"notice" => array(
				'type' => 'notice',
				'value' => __( 'Constant Contact requires your site to have SSL certificate.', Opt_In::TEXT_DOMAIN ),
				'class' => 'wpmudev-label--notice wpmudev-label--persist_notice'
			),
			"label" => array(
				"id"    => "list_id_label",
				"for"   => "list_id_list",
				"value" => __( "Choose Email List:", Opt_In::TEXT_DOMAIN ),
				"type"  => "label",
			),
			"choose_email_list" => array(
				"type"      => 'select',
				'name'      => "list_id",
				'id'        => "wph-email-provider-lists",
				"default"   => "",
				'options'   => $list,
				'selected'  => $email_list,
				"attributes" => array(
					'class' => "wpmudev-select constantContact_optin_email_list"
				)
			)
		);

		if ( is_ssl() ) {
			unset( $default_options['notice'] );
		}

	    return $default_options;
	}

	public function first_step_get_lists( $api ) {

	    $lists = array();

		try {
			$lists_data = $api->get_contact_lists();
			foreach( $lists_data as $list ){
				$list = (array) $list;
				$lists[ $list['id'] ]['value'] = $list['id'];
				$lists[ $list['id'] ]['label'] = $list['name'];
			}
		} catch (Exception $e) {
			Hustle_Api_Utils::maybe_log( $e->getMessage() );
		}
		return $lists;
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
		// TODO: set description with selected list's name
		return $data;
	}

	/**
	 * Updates the referrer on the database
	 *
	 * @since 3.0.5
	 *
	 * @return string|array
	 */
	public static function update_constantcontact_referrer() {
		Hustle_Api_Utils::validate_ajax_call( 'update_constantcontact_referrer' );

		$module_id = filter_input( INPUT_POST, 'module_id', FILTER_VALIDATE_INT );
		$current_page = filter_input( INPUT_POST, 'current_page', FILTER_SANITIZE_STRING );
		
		if ( version_compare( PHP_VERSION, '5.3', '>=' ) && class_exists( 'Hustle_ConstantContact_Api') ) {
			$constantcontact = new Hustle_ConstantContact_Api();
			$redirect_url = $constantcontact->get_authorization_uri( $module_id, true, $current_page );
		
			wp_send_json_success(
				array( 'redirect_url' => $redirect_url )
			);
		} else {
			Hustle_Api_Utils::maybe_log( '[ConstantContact] Hustle_ConstantContact_Api does not exist, or PHP version is lower than 5.3.' );
			wp_send_json_success(
				array( 
					'html' => '<label class="wpmudev-label--notice"><span>Constant Contact requires PHP version 5.3 or higher. </span></label>',
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
		add_action( "wp_ajax_update_constantcontact_referrer", array( __CLASS__ , "update_constantcontact_referrer" ) );
	}
}
if ( is_admin() ) {
	Hustle_Api_Utils::register_ajax_endpoints( 'Hustle_ConstantContact' );
}
endif;
