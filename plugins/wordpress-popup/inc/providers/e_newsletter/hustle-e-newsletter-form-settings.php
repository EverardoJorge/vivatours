<?php
if( !class_exists("Hustle_E_Newsletter_Form_Settings") ):

/**
 * Class Hustle_E_Newsletter_Form_Settings
 * Form Settings e-Newsletter Process
 *
 */
class Hustle_E_Newsletter_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
			wp_send_json_error( 'e-Newsletter requires a higher version of PHP or Hustle, the e-Newsletter plugin is not active, or the extension is not configured correctly.' );
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
		
		//display a notice only if e-Newsletter plugin is not active
		if( !$this->provider->is_plugin_active() ){
			
			$e_newsletter_url = "https://premium.wpmudev.org/project/e-newsletter/";
			
			return array(
				"label" =>  array(
					"class"	=> "wpmudev-label--notice",
					"type"	=> "notice",
					"value"	=>  sprintf( __( "Please, activate e-Newsletter plugin. If you don't have it installed, <a href='%s' target='_blank'>download it here.</a>", Opt_In::TEXT_DOMAIN ), $e_newsletter_url )
				)
			);
		}
		
		if ( isset( $submitted_data['module_id'] ) ) {
			$module_id = $submitted_data['module_id'];
			$module = Hustle_Module_Model::instance()->get( $module_id );
			$synced = Hustle_E_Newsletter::get_synced( $module );
			$saved_auto_optin = Hustle_E_Newsletter::_get_auto_optin( $module );	
		} else {
			$synced = 0;
			$saved_auto_optin = '';	
		}
		$checked = ! isset( $submitted_data['auto_optin'] ) ? $saved_auto_optin : $submitted_data['auto_optin'];

		$lists = array();
		$_lists = $this->provider->get_groups();
		if( is_array( $_lists ) && !empty( $_lists ) ) {
			foreach( $_lists as $list ) {
				$list = (array) $list;
				$lists[ $list['group_id'] ]['value'] = $list['group_id'];
				$lists[ $list['group_id'] ]['label'] = $list['group_name'];
			}
		}
		
		return array(  
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
								'value'         => "subscribed",
								"attributes"    => array(
									'class'   => "toggle-checkbox",
									'checked' => ( 'subscribed' === $checked || '1' === $checked ) ? 'checked' : ''
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
						"value"         => __("Automatically opt-in new users to the mailing list", Opt_In::TEXT_DOMAIN),
						"type"          => "label",
						"attributes"    => array(
							'class'     => "wpmudev-switch-label"
						)
					),
				)
			),
			"lists_setup" => array(
				"id"    => "optin-provider-account-options",
				"class" => "wpmudev-provider-block",
				"type"  => "wrapper",
				"elements" => array(
					"label" => array(
						"id"    => "list_id_label",
						"for"   => "list_id",
						"value" => empty($lists)? __("There are no email lists to choose from.", Opt_In::TEXT_DOMAIN) : __("Choose email list:", Opt_In::TEXT_DOMAIN),
						"type"  => "label",
					),
					"choose_email_list" => array(
						"id"            => "wph-email-provider-lists",
						"name"          => "list_id",
						"type"          => "checkboxes",
						'selected'		=> Hustle_E_Newsletter::_get_list_id( $module ),
						"default"       => "",
						"value"         => "",
						'options'       => $lists,
					)
				)
			),
			"sync_with_current_local_list" => array(
				"id"    => "",
				"class" => "",
				"type"  => "hidden",
				'name'  => "synced",
				'id'    => "synced",
				'value' => $synced ? 1 : 0,
			)
		);
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
		// TODO: Add the selected groups to description
		return $data;
	}

	/**
	 * Returns HTML string with the saved settings 
	 * Note that only the settings already stored on the DB are the ones that will show up
	 *
	 * @since 3.0.5
	 *
	 * @param integer $module_id
	 * @return string
	 */
	private function get_current_settings( $module_id ) {
		//TODO: retrieve saved settings.
		$html = '';
		
		return $html;
	}

}

endif;
