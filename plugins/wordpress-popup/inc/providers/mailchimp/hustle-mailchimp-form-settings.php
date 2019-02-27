<?php
if( !class_exists("Hustle_Mailchimp_Form_Settings") ):

/**
 * Class Hustle_Mailchimp_Form_Settings
 * Form Settings Mailchimp Process
 *
 */
class Hustle_Mailchimp_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

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
				'is_completed' => array( $this, 'is_first_step_completed' ),
			)
		);
	}
		
	/**
	 * Check if step is completed
	 *
	 * @since 3.0.5 
	 * @return bool
	 */
	public function is_first_step_completed( $submitted_data ) {
		if( ! isset( $submitted_data['api_key'] ) || empty( $submitted_data['api_key'] ) ) {
			return false;
		}
		if( ! isset( $submitted_data['list_id'] ) || empty( $submitted_data['list_id'] ) ) {
			return false;
		}
		return true;
	}
	
	/**
	 * Returns all settings and conditions for 1st step of MailChimp settings
	 *
	 * @since 3.0.5
	 *
	 * @param array $submitted_data
	 * @param boolean $is_submit
	 * @return array
	 */
	public function first_step_callback( $submitted_data, $is_submit ) {
		if ( $is_submit ) {
			$error_messages = array();
			if ( ! isset( $submitted_data['api_key'] ) || empty( $submitted_data['api_key'] ) ) {
				$error_messages[] = __( 'The API key is required.', Opt_In::TEXT_DOMAIN );
			} else {
				$info = Hustle_Mailchimp::api( $submitted_data['api_key'] )->get_info();
				if ( is_wp_error( $info ) ) {
					$error_messages[] = __( 'The API key is invalid.', Opt_In::TEXT_DOMAIN );
				}
			}
			if ( ! isset( $submitted_data['list_id'] ) || empty( $submitted_data['list_id'] ) ) {
				$error_messages[] = __( 'The email list is required.', Opt_In::TEXT_DOMAIN );
			}
			if ( ! empty( $error_messages ) ) {
				$error_message = implode( '<br/>', $error_messages );
			}
		}

		if( ! $this->provider->is_activable() ) {
			wp_send_json_error( 'Mailchimp requires a higher version of PHP or Hustle, or the extension is not configured correctly.' );
		}
		
		$options = $this->first_step_options( $submitted_data );

		$html = '';
		foreach( $options as $key =>  $option ) {
			$html .= Hustle_Api_Utils::static_render("general/option", array_merge( $option, array( "key" => $key ) ), true);
		}

		if( ! isset( $error_message ) ) {
			$step_html = $html;
			$has_errors = false;
		} else {
			$step_html = '<label class="wpmudev-label--notice"><span>' . $error_message . '</span></label>';
			$step_html .= $html;
			$has_errors = true;
		}
		$step_html .= $this->get_current_settings();
		

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

		// The data in $response['data_to_save'] is the one that's saved
		// Save only after the step has been validated and there are no errors
		if( $is_submit && ! $has_errors ){
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
			$saved_api_key = Hustle_Mailchimp::_get_api_key( $module );
			$saved_auto_optin = Hustle_Mailchimp::_get_auto_optin( $module );
			$saved_allow_subscribed	= Hustle_Mailchimp::get_allow_subscribed_users( $module );
		} else {
			$saved_api_key = '';
			$saved_auto_optin = '';	
			$saved_allow_subscribed = '';
		}

		$api_key    = ! isset( $submitted_data['api_key'] ) ? $saved_api_key : $submitted_data['api_key'];
		$checked    = ! isset( $submitted_data['auto_optin'] ) ? $saved_auto_optin : $submitted_data['auto_optin'];
		$allow_subscribed_checked = ! isset( $submitted_data['allow_subscribed_users'] ) ? $saved_allow_subscribed : $submitted_data['allow_subscribed_users'];

		return array(
			"label" => array(
				"id"    => "api_key_label",
				"for"   => "api_key",
				"value" => __("Choose your API key:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"wrapper" => array(
				"id"    => "",
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
						"id"    => "refresh_mailchimp_lists",
						"name"  => "refresh_mailchimp_lists",
						"type"  => "ajax_button",
						"value" => "<span class='wpmudev-loading-text'>" . __( "Fetch Lists", Opt_In::TEXT_DOMAIN ) . "</span><span class='wpmudev-loading'></span>",
						"class" => "wpmudev-button wpmudev-button-sm hustle_provider_on_click_ajax",
						"attributes" => array(
							"data-action" => "hustle_mailchimp_refresh_lists",
							"data-nonce"  => wp_create_nonce("hustle_mailchimp_refresh_lists"),
							"data-dom_wrapper"  => "#optin-provider-account-options"
						)
					),
				)
			),
			"instructions" => array(
				"id"    => "optin_api_instructions",
				"for"   => "",
				"value" => sprintf( __("Log in to your <a href='%s' target='_blank'>MailChimp account</a> to get your API Key.", Opt_In::TEXT_DOMAIN), 'https://admin.mailchimp.com/account/api/' ),
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
				),
			),
			"allow_subscribed_setup" => array(
				"id"    => "",
				"class" => "wpmudev-switch-labeled",
				"type"  => "wrapper",
				"elements" => array(
					"allow_subscribed_users" => array(
						"id"    => "",
						"class" => "wpmudev-switch",
						"type"  => "wrapper",
						"elements" => array(
							"opt_in" => array(
								"type"          => 'checkbox',
								'name'          => "allow_subscribed_users",
								'id'            => "allow_subscribed_users",
								"default"       => "",
								'value'         => "allow",
								"attributes"    => array(
									'class'   => "toggle-checkbox",
									'checked' => 'allow' === $allow_subscribed_checked ? 'checked' : '',
								)
							),
							"label" => array(
								"id"            => "allow_subscribed_users_label",
								"for"           => "allow_subscribed_users",
								"value"         => "",
								"type"          => "label",
								"attributes"    => array(
									'class'     => "wpmudev-switch-design"
								)
							)
						),
					),
					"allow_subsccribed_switch_instructions" => array(
						"id"            => "allow_subscribed_users_label",
						"for"           => "allow_subscribed_users",
						"value"         => __("Allow already subscribed users to submit the form", Opt_In::TEXT_DOMAIN),
						"type"          => "label",
						"attributes"    => array(
							'class'     => "wpmudev-switch-label"
						)
					),
				)
			),
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
		if( isset( $data['api_key'] ) ) {
			$data['desc'] = $data['api_key'];
		}
		if( isset( $data['list_id'] ) && isset( $data['group'] ) ) {
			$list_id = $data['list_id'];
			$group = $data['group'];
			$groups_config 	= get_site_transient( Hustle_Mailchimp::GROUP_TRANSIENT  . $list_id );
			$selected 		= null;
	
			if ( $groups_config && is_array( $groups_config ) ) {
				foreach( $groups_config as $groups ){
					if ( $groups->id === $group ) {
						$selected = $groups;
					}
				}
			}
			
			if ( ! is_null( $selected ) && is_object( $selected ) ) {
				$options = array();
				foreach( $selected->interests as $interest ){ 
					$options[] = $interest->name;
				}
				
				$data['group_name'] = $selected->title;
				$data['interest_options'] = implode( ', ', $options );
			} else {
				$data['group_name'] = '';
				$data['interest_options'] = '';
			}
		}
		if ( isset( $data['list_id'] ) && isset( $data['group_interest'] ) ) {
			$list_id = $data['list_id'];
			$groups_config = get_site_transient( Hustle_Mailchimp::GROUP_TRANSIENT  . $list_id );
			if( !$groups_config || !is_array( $groups_config ) )
				return $data;
			
			$interest_options = array();
			foreach( $groups_config as $group ){
				foreach( $group->interests as $interest ){
					$interest_options[ $interest->id ] = $interest->name;
				}
			}
			
			$selected_interests = $data['group_interest'];
			$insterest_name = array();
			if ( is_array( $selected_interests ) ) {
				foreach( $selected_interests as $interest ) {
					if ( isset( $interest_options[ $interest ] ) ) {
						$interest_name[] = $interest_options[ $interest ];
					}
				}
				$interest_name = implode( ', ', $interest_name );
			} else {
				$interest_name = isset( $interest_options[ $selected_interests ] ) ? $interest_options[ $selected_interests ] : '';
			}
			
			$data['group_interest_name'] = $interest_name;
		}
		
		return $data;
	}

	/**
	 * Returns HTML string that will be filled in the frontend with the selected settings
	 *
	 * @since 3.0.5
	 *
	 * @return string
	 */
	private function get_current_settings() {
		// The tags with the class "current_{field name}" will be filled in the frontend with the saved settings
		$html = '<div id="optin-provider-saved-args" class="refresh_mailchimp_lists-empty">';
		
		$html .= '<label class="wpmudev-label--notice"><span>';
		$html .= sprintf( __( 'Selected list (%s). Press the Fetch Lists button to update value.', Opt_In::TEXT_DOMAIN ), '<strong class="current_list_name"></strong>' ); 
		$html .= '</span></label>';
		
		$html .= '<p>';
		$html .= '<br/><strong>' . __( 'Interest group', Opt_In::TEXT_DOMAIN ) . '</strong>';
		$html .= '<br/>' . __( 'Name: ', Opt_In::TEXT_DOMAIN ) . '<span class="current_group_name">' . __( 'No interest group selected.', Opt_In::TEXT_DOMAIN ) . '</span>';
		$html .= '<br/>' . __( 'Options: ', Opt_In::TEXT_DOMAIN ) . '<span class="current_interest_options">' . __( 'No options available for the selected group.', Opt_In::TEXT_DOMAIN ) . '</span>';
		$html .= '<br/>' . __( 'Selected: ', Opt_In::TEXT_DOMAIN ) . '<span class="current_group_interest_name"></span>';
		$html .= '</p>';
		
		$html .= '</div>';
		
		return $html;
	}

	/**
	 * Returns array with $html to be inserted into the $wrapper DOM object
	 *
	 * @since 3.0.5
	 *
	 * @return array
	 */
	public function ajax_refresh_lists() {
		Hustle_Api_Utils::validate_ajax_call( 'hustle_mailchimp_refresh_lists' );
		
		$submitted_data = Hustle_Api_Utils::validate_and_sanitize_fields( $_REQUEST );
		$options = $this->refresh_lists_options( $submitted_data );
		$response = array(
			'html' => $this->get_html_for_options( $options ),
			'wrapper' => $submitted_data['dom_wrapper'],
		);
		wp_send_json_success( $response );
	}

	/**
	 * Returns array with options to build refresh lists html
	 *
	 * @since 3.0.5
	 *
	 * @param string $submitted_data
	 * @return array
	 */
	private function refresh_lists_options( $submitted_data ) {
		
		$api_key = $submitted_data['api_key'];
		
		//Load more function
		$load_more = ( isset( $submitted_data['load_more'] ) && 'true' === $submitted_data['load_more'] );

		$lists = array();
		
		if ( $load_more ) {
			$response = $this->lists_pagination( $api_key );
			list( $lists, $total ) =  $response;
		} else {
			$response = Hustle_Mailchimp::api( $api_key )->get_lists();
			$_lists   = $response->lists;
			$total    = $response->total_items;
			if( is_array( $_lists ) ) {
				foreach( $_lists as $list ) {
					$list = (array) $list;
					$lists[ $list['id'] ]['value'] = $list['id'];
					$lists[ $list['id'] ]['label'] = $list['name'];
				}
				delete_site_transient( Hustle_Mailchimp::LIST_PAGES );
			}
		}

		$total_lists = count( $lists );

		$first = $total_lists > 0 ? reset( $lists ) : "";
		if( !empty( $first ) )
			$first = $first['value'];

		if( ! isset( $submitted_data['list_id'] ) ) {
			$selected_list = $first;
		} else {
			$selected_list = array_key_exists( $submitted_data['list_id'], $lists ) ? $submitted_data['list_id'] : $first;
		}

		$default_options =  array(
			"label" => array(
				"id"    => "list_id_label",
				"for"   => "list_id",
				"value" => __("Choose email list:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"choose_email_list" => array(
				"type"          => 'select',
				'name'          => "list_id",
				'id'            => "wph-email-provider-lists",
				"default"       => "",
				'options'       => $lists,
				'value'         => $selected_list,
				'selected'      => $selected_list,
				"attributes"    => array(
					"data-action"   => "hustle_mailchimp_get_list_groups",
					"data-nonce"    => wp_create_nonce("mailchimp_choose_email_list"),
					'class'         => "wpmudev-select mailchimp_optin_email_list hustle_provider_on_change_ajax",
					"data-dom_wrapper"  => "#wph-optin-list-groups",
				)
			),
			'loadmore' => array(
				"id"    => "loadmore_mailchimp_lists",
				"name"  => "loadmore_mailchimp_lists",
				"type"  => "button",
				"value" => __("Load More Lists", Opt_In::TEXT_DOMAIN),
				"class" => "wpmudev-button wpmudev-button-sm hustle_provider_on_click_ajax",
				"attributes"    => array(
					"data-action" => 'hustle_mailchimp_refresh_lists',
					"data-nonce" => wp_create_nonce('hustle_mailchimp_refresh_lists'),
					"data-load_more" => 'true',
					"data-dom_wrapper"  => "#optin-provider-account-options"
				)
			),
			"label" => array(
				"id"    => "list_id_label",
				"for"   => "list_id",
				"value" => __("Choose email list:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			)
		);

		if ( $total_lists <= 0 ) {
			//If we have no items, no need to show the button
			unset( $default_options['loadmore'] );
		} else if ( $total <= $total_lists ) {
			//If we have reached the end, remove the button
			unset( $default_options['loadmore'] );
		}

		$list_group_options = $this->_get_list_group_options( $api_key, $selected_list, $submitted_data );

		return array_merge( $default_options,  array(
			"wph-optin-list-groups-wrapper" => array(
				"id"        => "wph-optin-list-groups",
				"class"     => "wph-optin-list-groups",
				"type"      => "wrapper",
				"elements"  =>  is_a( $list_group_options, "Mailchimp_Error" ) ? array() : $list_group_options,
			),
			"wph-optin-list-group-interests-wrapper" => array(
				"id"        => "wph-optin-list-group-interests-wrap",
				"class"     => "wph-optin-list-group-interests-wrap wph-email-provider-lists-empty refresh_mailchimp_lists-empty",
				"type"      => "wrapper",
				"elements"  =>  array()
			)
		));
	}

	/**
	 * Lists pagination
	 *
	 * @return array
	 */
	private function lists_pagination( $api_key ) {
		
		$lists      = array();
		$list_pages = get_site_transient( Hustle_Mailchimp::LIST_PAGES );

		$offset     = 2; //Default limit to first page
		$total      = 0; //Default we have 0

		if ( $list_pages ) {
			$total  = isset( $list_pages['total'] ) ? $list_pages['total'] : 0;
			$offset = isset( $list_pages['offset'] ) ? $list_pages['offset'] : 2;
		} else {
			$list_pages = array();
		}

		if ( $offset > 0 ) {
			$response = Hustle_Mailchimp::api( $api_key )->get_lists( $offset );
			$_lists   = $response->lists;
			$total    = $response->total_items;

			if ( is_array( $_lists ) ) {
				foreach( $_lists as $list ){
					$list = (array) $list;
					$lists[ $list['id'] ]['value'] = $list['id'];
					$lists[ $list['id'] ]['label'] = $list['name'];
				}
				if ( count( $_lists ) >= $total ) {
					$offset = 0; //We have reached the end. No more pagination
				} else {
					$offset = $offset++; 
				}

				$list_pages['offset'] = $offset;
				$list_pages['total']  = $total;
				set_site_transient( Hustle_Mailchimp::LIST_PAGES , $list_pages );
			} else {
				delete_site_transient( Hustle_Mailchimp::LIST_PAGES );
			}
		} else {
			delete_site_transient( Hustle_Mailchimp::LIST_PAGES );
		}
		
		return array( $lists, $total );
	}

	/**
	 * Ajax endpoint to render html for group options based on given $list_id and $api_key
	 *
	 * @since 1.0.1
	 */
	public function ajax_get_list_groups(){
		Hustle_Api_Utils::validate_ajax_call( 'mailchimp_choose_email_list' );

		$list_id = filter_input( INPUT_POST, 'list_id', FILTER_SANITIZE_STRING );
		$api_key = filter_input( INPUT_POST, 'api_key', FILTER_SANITIZE_STRING );
		
		$options = $this->_get_list_group_options( $api_key, $list_id );

		$html = "";
		if( is_array( $options ) && !is_a( $options, "Mailchimp_Error" )  ){
			foreach( $options as $option )
				$html .= Hustle_Api_Utils::static_render("general/option", $option , true);
			$response = array( 
				'html' => $html, 
				'wrapper' => '.wph-optin-list-groups' 
			);
			wp_send_json_success( $response ); 
		}

		wp_send_json_error( $options );
	}

	private function _get_list_group_options( $api_key, $list_id, $submitted_data = array() ){
		if( empty( $list_id ) && isset( $submitted_data['list_id'] ) ) {
			$list_id = $submitted_data['list_id'];
		}
		$group_options = array();
		$options = array(
			-1 => array(
				"value" 	=> -1,
				"label" 	=> __( "No group", Opt_In::TEXT_DOMAIN ),
				"interests" => __("First choose interest group", Opt_In::TEXT_DOMAIN)
			)
		);

		$api  = Hustle_Mailchimp::api( $api_key );
		try{
			$api_categories = $api->get_interest_categories( $list_id );
			if ( is_wp_error( $api_categories ) ) {
				return array(
					array(
						"value" => "<label class='wpmudev-label--notice'><span>" . __( 'There was an error fetching the data. Please review your settings and try again.', Opt_In::TEXT_DOMAIN ) . "</span></label>",
						"type"  => "label",
					)
				);
			}
			$total_groups = $api_categories->total_items;
			if ( $total_groups < 10 ) {
				$total_groups = 10;
			}
			$groups = (array) $api->get_interest_categories( $list_id, $total_groups )->categories;
		}catch (Exception $e){
			return $e;
		}

		if( !is_array( $groups ) ) return $group_options;

		foreach( $groups as $group_key => $group ){
			$group = (array) $group;
			
			// get interests for each group category
			$total_interests = $api->get_interests( $list_id, $group['id'] )->total_items;
			if ( $total_interests < 10 ) {
				$total_interests = 10;
			}
			$groups[$group_key]->interests = (array) $api->get_interests( $list_id, $group['id'], $total_interests )->interests;
			
			$options[ $group['id'] ]['value'] = $group['id'];
			$options[ $group['id'] ]['label'] = $group['title'] . " ( " . ucfirst( $group['type'] ) . " )";
		}
		
		set_site_transient( Hustle_Mailchimp::GROUP_TRANSIENT  . $list_id, $groups );

		$current = current( $options );
		$first = $current['value'];
		
		if ( isset( $submitted_data['group'] ) && '-1' !== $submitted_data['group'] && isset( $options[ $submitted_data['group'] ] ) ) {
			$first = $options[ $submitted_data['group'] ]['value'];
		}
		return array(
			"group_label" => array(
				"id"    => "group_label",
				"for"   => "group",
				"value" => __("Choose interest group:", Opt_In::TEXT_DOMAIN),
				"type"  => "label",
			),
			"group" => array(
				"type"      => 'select',
				'name'      => "group",
				'id'        => "group",
				'class'     => "wpmudev-select hustle_provider_on_change_ajax",
				"default"   => "",
				'options'   => $options,
				'value'     => $first,
				'selected'  => $first,
				"attributes" => array(
					"data-action" => "hustle_mailchimp_get_group_interests",
					"data-nonce"  => wp_create_nonce("hustle_mailchimp_get_group_interests"),
					"data-dom_wrapper" => "#wph-optin-list-group-interests-wrap",
				)
			),
			"group_instructions" => array(
				"id"    => "group_instructions",
				//"class" => "wpmudev-label--notice",
				"value" => "<label class='wpmudev-label--notice'><span>" . __( "Leave this option blank if you would like to opt-in users without adding them to a group first", Opt_In::TEXT_DOMAIN ) . "</span></label>",
				"type"  => "label",
			)
		);

	}

	/**
	 * Returns array with $html to be inserted into the $wrapper DOM object
	 *
	 * @since 3.0.5
	 *
	 * @return array
	 */
	public function ajax_refresh_interests() {
		Hustle_Api_Utils::validate_ajax_call( 'hustle_mailchimp_get_group_interests' );
		
		$submitted_data = Hustle_Api_Utils::validate_and_sanitize_fields( $_REQUEST );
		
		$groups = $this->get_group_interests( $submitted_data );
	
		$response = array(
			'html' => $groups['html'],
			'wrapper' => $submitted_data['dom_wrapper'],
		);
		wp_send_json_success( $response );
	}
	
	/**
	 * Return interest options of given list id and group id
	 *
	 * @since 3.0.5
	 */
	private function get_group_interests( $submitted_data ){

		$list_id 	= $submitted_data['list_id'];
		$group_id 	= $submitted_data['group'];

		$groups_config = get_site_transient( Hustle_Mailchimp::GROUP_TRANSIENT  . $list_id );
		if( !$groups_config || !is_array( $groups_config ) )
			wp_send_json_error( __("Invalid list id: ", Opt_In::TEXT_DOMAIN) . $list_id );

		$args 	= $this->_get_group_interest_args( $list_id, $group_id );
		$fields = $args['fields'];
		$html 	= "";

		if ( is_array( $fields ) ) {
			foreach( $fields as $field ) {
				$html .= Hustle_Api_Utils::static_render("general/option", $field , true);
			}
		} 
		$response =  array(
			"html" => $html,
			"wrapper" => $submitted_data['dom_wrapper'],
			"group" => $args['group']
		);
		
		return $response;
	}

	/**
	 * Returns interest args for the given $group_id and $list_id
	 *
	 * @since 1.0.1
	 *
	 * @param string $list_id
	 * @param string $group_id
	 * @return array
	 */
	private function _get_group_interest_args( $list_id, $group_id ){
		$interests_config = $this->_get_group_interests( $list_id, $group_id );
		$interests = $interests_config['interests'];

		$_type = $interests_config['type'];

		$type = "radio" === $interests_config['type'] ? "radios" : $interests_config['type'];
		$type = "dropdown" === $type || "hidden" === $type ? "select" : $type;


		$class = ( 'select' === $type ) ? 'wpmudev-select' : '';

		$first = current( $interests );

		$interests_config['group']['interests'] = array_map( array( $this, "normalize_group_interest" ), $interests_config['group']['interests'] );

		$name = "group_interest";

		$choose_prompt = __("Choose default interest:", Opt_In::TEXT_DOMAIN);

		if( "checkboxes" === $_type )
			$choose_prompt = __("Choose default interest(s):", Opt_In::TEXT_DOMAIN);

		if( "hidden" === $_type )
			$choose_prompt = __("Set default interest:", Opt_In::TEXT_DOMAIN);

		if( "radios" === $type )
			$choose_prompt .= sprintf(" ( <a href='#' data-name='group_interest' class='clear_options' >%s</a> )", __("clear selection", Opt_In::TEXT_DOMAIN) );

		return array(
			'group'     => $interests_config['group'],
			"fields"    => array(
				"group_interest_label" => array(
					"id"    => "group_interest_label",
					"for"   => "group_interest",
					"value" => $choose_prompt,
					"type"  => "label",
				),
				"group_interest" => array(
					"type"      => $type,
					'name'      => $name,
					'id'        => "group_interest",
					"default"   => "",
					'options'   => $interests,
					'value'     => $first,
					'selected'  => array(),
					'class'     => $class,
					"item_attributes" => array()
				),
				"mailchimp_groups_interest_instructions" => array(
					"id"    => "mailchimp_groups_interest_instructions",
					"for"   => "",
					"value" =>  __( "What you select here will appear pre-selected for users. If this is a hidden group, the interest will be set but not shown to users.", Opt_In::TEXT_DOMAIN ),
					"type"  => "label",
				)
			)
		);
	}

	/**
	 * Returns interest for given $list_id, $group_id
	 *
	 * @since 1.0.1
	 *
	 * @param $list_id
	 * @param $group_id
	 * @return array
	 */
	public function _get_group_interests( $list_id, $group_id ){

		$interests = array(
			-1 => array(
				"id" 	=> -1,
				"label" => __("No default choice", Opt_In::TEXT_DOMAIN)
			)
		);

		$groups = get_site_transient( Hustle_Mailchimp::GROUP_TRANSIENT  . $list_id );

		if( !$groups || !is_array( $groups ) ) return $interests;

		$the_group = array();

		foreach( $groups as $group ){
			$group = (array) $group;
			if( $group["id"] === $group_id )
				$the_group = $group;
		}
		
		if( array() === $the_group ) return $interests;

		if( in_array($the_group['type'], array("radio", "checkboxes", "hidden"), true ) )
			$interests = array();

		$interests = array_merge( $interests,  array_map( array( $this, "normalize_group_interest" ),  $the_group['interests']) );

		if(  "hidden" === $the_group['type'] && isset( $the_group['interests'][0] ) ) {
			$interest = $the_group['interests'][0];
			if ( is_object( $interest ) ) {
				$the_group['selected'] = $interest->id;
			} else {
				$the_group['selected'] = $interest['id'];
			}
		}

		return array(
			'group'     => $the_group,
			"interests" => $interests,
			"type"      => $the_group['type']
		);
	}
		
	/**
	 * Normalizes api response for groups interests
	 *
	 *
	 * @since 1.0.1
	 *
	 * @param $interest
	 * @return mixed
	 */
	private function normalize_group_interest( $interest ){
		$interest = (array) $interest;
		$interest_arr = array();
		$interest_arr["label"] = $interest['name'];
		$interest_arr["value"] = $interest['id'];

		return $interest_arr;
	}
	
	/**
	 * @used by array_map in _get_group_interest_args to map interests to their id/value
	 *
	 * @since 1.0.1
	 * @param $interest
	 * @return mixed
	 */
	private function _map_interests_to_ids( $interest ){
		return $interest['value'];
	}
	
	/**
	 * Registers AJAX endpoints for provider's custom actions
	 *
	 */
	public function register_ajax_endpoints(){
		add_action( "wp_ajax_hustle_mailchimp_refresh_lists", array( $this , "ajax_refresh_lists" ) );
		add_action( "wp_ajax_hustle_mailchimp_get_list_groups", array( $this , "ajax_get_list_groups" ) );
		add_action( "wp_ajax_hustle_mailchimp_get_group_interests", array( $this , "ajax_refresh_interests" ) );
	}
}
if ( is_admin() ) {
	Hustle_Api_Utils::register_ajax_endpoints( 'Hustle_Mailchimp' );
}
endif;
