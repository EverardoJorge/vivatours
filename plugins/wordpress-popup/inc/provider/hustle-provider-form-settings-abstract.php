<?php
/**
 * Class Hustle_Provider_Form_Settings_Abstract
 * Any change(s) to this file is subject to:
 * - Properly Written DocBlock! (what is this, why is that, how to be like those, etc, as long as you want!)
 * - Properly Written Changelog!
 * 
 * This class should be extended by your integration in order to display a settings section for it within Hustle.
 * For more information, more examples, and even sample integrations, visit this page at WPMUDev's site:
 * @see https://linktodocumentation.com 
 *
 * @since 3.0.5
 */
abstract class Hustle_Provider_Form_Settings_Abstract {

	/**
	 * Integration's instance.
	 * Instance of the integration to whom the form settings belongs to.
	 *
	 * @since 3.0.5
	 * @var Hustle_Provider_Abstract
	 */
	protected $provider;
	
	public function __construct( Hustle_Provider_Abstract $provider ) {
		$this->provider = $provider;
	}
	
	/**
	 * Gets the array that define the contents of the settings wizard.
	 * Override this function to set wizardable settings.
	 * Default is an empty array which is indicating that Provider doesn't have settings.
	 * 
	 * -Optional. Required if your integration has settings.
	 *
	 * It's a multi-array with numerical keys, starting with `0`.
	 * Every step you'd like your settings wizard to have should be an array within the $steps array.
	 * Every step's array must have these key => value pairs:
	 * 
	 * - 'callback' :	  array with the function to be called by 'call_user_func'. @example array( $this, 'sample_first_step_callback' ),
	 * 					  @see Hustle_Provider_Form_Settings_Abstract::sample_first_step_callback()
	 * 
	 * - 'is_completed' : array with the function to be called by 'call_user_func'. @example array( $this, 'sample_is_first_step_completed' ),
	 *		   			  @see Hustle_Provider_Form_Settings_Abstract::sample_is_first_step_completed()
	 * 
	 * @since 3.0.5
	 * @return array
	 */
	public function form_settings_wizards() {
		// What this function returns should look like this:
		$steps = array(
			// 1st Step / step '0'
			array(
				/**
				 * The value within 'callback' will be passed as the first argument of 'call_user_func'.
				 * Passing '$this' as a reference such as "array( $this, 'sample_first_step_callback' )" is not required but it's encouraged.
				 * Passing '$this' class instance is helpful for calling private functions or variables inside your class.
				 * You could make this value to be 'some_function_name' as long as it's globally callable, which will be checked by 'is_callable'.
				 *
				 * This callback should accept 2 arguments and return an array.
				 * @see Hustle_Provider_Form_Settings_Abstract::sample_first_step_callback()
				 *
				 */
				'callback'     => array( $this, 'sample_first_step_callback' ),
				/**
				 * When moving forward on the wizard's steps (when going from step 1 to step 2, for exmaple), 
				 * Hustle will call 'is_completed' from the previous step before calling the 'callback' function.
				 * If this function returns 'false', the wizard won't move forward to the next step.
				 * Just like 'callback', the value of this element will be passed as the first argument of `call_user_func`.
				 * 
				 * This callback should accept 1 argument and return a boolean.
				 * @see Hustle_Provider_Form_Settings_Abstract::sample_is_first_step_completed()
				 *
				 */
				'is_completed' => array( $this, 'sample_is_first_step_completed' ),
			),
			/*
			2nd step / step '1'
			array (
				'callback' 	   => array( $this, 'sample_second_step_callback' ),
				'is_completed' => array( $this, 'sample_is_second_step_completed' ),
			),
			*/
		);

		return array();
	}
	
	/**
	 * Handles the current wizard step.
	 * This function retrieves the form to be shown and handles the submitted data.
	 * 
	 * Sample of what this function should return:
	 * @example 
	 * $returned_data = [
	 * 	'html' => string. Contains the HTML of the form settings to be displayed. 
	 * 	'has_errors' => boolean. True when it has errors, such as an invalid input. The wizard won't move forward if there are errors.
	 * 	'buttons' =>
	 * 		'submit' => [
	 *          markup => '<a>Submit</a>'
	 *      ],
	 *      'cancel' => [
	 *          markup: '<a>Cancel</a>'
	 *      ]
	 * ]
	 * 	'is_close' => boolean. True if wizard should be instead of showing this step.
	 * ]
	 *
	 * @since   3.0.5
	 * @param array $submitted_data Array of the submitted data POST-ed by the user or by Hustle. Already sanitized by @see Hustle_Api_Utils::validate_and_sanitize_fields()
	 * @param bool $is_submit Indicates whether the call is made by a form submission or by opening the wizard.
	 * @return array
	 */
	private function sample_first_step_callback( $submitted_data, $is_submit ) {
		return array(
			'html'       => '<p>Hello im from first step settings</p>',
			'has_errors' => false,
		);

	}

	/**
	 * Checks if the previous step was completed.
	 * When Hustle requests the wizard, it will check if the previous step 'is_completed' before proceeding to the next one.
	 *
	 * @since   3.0.5
	 * @param array $submitted_data Data submitted by the user and handled by the step's callback function.
	 * @return bool
	 */
	private function sample_is_first_step_completed( $submitted_data ) {
		// Do some validation here and return 'true' if everything is okay to go to the next step and save the data.
		return true;
	}

	/**
	 * Retrieves the HTML that's used by most of the integrated providers to show the saved list.
	 * Override if this doesn't fit the provider's saved values, or just don't use it.
	 * -Helper.
	 * 
	 * @since   3.0.5
	 * @return bool
	 */
	protected function get_current_list_name_markup() {
		// The tags with the class "current_{field name}" will be filled in the frontend 
		// with the saved settings named by {field_name}
		$html = '<div id="optin-provider-saved-args" class="refresh-lists-hide">';

		$html .= '<label class="wpmudev-label--notice"><span>';
		$html .= sprintf( __('Selected list (%s). Press the Fetch Lists button to update value.', Opt_In::TEXT_DOMAIN ), '<strong class="current_list_name"></strong>' ); 
		$html .= '</span></label>';

		$html .= '</div>';
		
		return $html;
	}

	/**
	 * Retrieves the HTML markup given an array of options.
	 * The array should be something like:
	 * array(
	 * 		"optin_url_label" => array(
	 *			"id"    => "optin_url_label",
	 *			"for"   => "optin_url",
	 *			"value" => "Enter a Webhook URL:",
	 *			"type"  => "label",
	 *		),
	 *		"optin_url_field_wrapper" => array(
	 *			"id"        => "optin_url_id",
	 *			"class"     => "optin_url_id_wrapper",
	 *			"type"      => "wrapper",
	 *			"elements"  => array(
	 *				"optin_url_field" => array(
	 *					"id"            => "optin_url",
	 *					"name"          => "api_key",
	 *					"type"          => "text",
	 *					"default"       => "",
	 *					"value"         => "",
	 *					"placeholder"   => "",
	 *					"class"         => "wpmudev-input_text",
	 *				)
	 *			)
	 *		),
	 *	);
	 *
	 * @since 3.0.5
	 * @uses Opt_In::static_render()
	 * @param array $options
	 * @return string
	 */
	protected static function get_html_for_options( $options ) {
		$html = '';
		foreach( $options as $key =>  $option ){
			$html .= Opt_In::static_render("general/option", array_merge( $option, array( "key" => $key ) ), true);
		}
		return $html;
	}

	/**
	 * Retrieves the markup of the "Back" button for settings.
	 * -Helper.
	 * 
	 * @since   3.0.5
	 * @param string $value
	 * @param string $class
	 * @return string
	 */
	protected function get_previous_button_markup( $value = '', $class = '' ) {
		$back_button = array(
			'id'    => '',
			'type'  => 'ajax_button',
			'value' => empty( $value ) ? __( 'Back', Opt_In::TEXT_DOMAIN ) : $value,
			'class' => 'hustle-provider-prev wpmudev-button wpmudev-button-ghost ' . $class,
		);
		return Opt_In::static_render('general/option', $back_button, true);
	}

	/**
	 * Retrieves the markup of the "Cancel" button for settings.
	 * -Helper.
	 * 
	 * @since   3.0.5
	 * @param string $value
	 * @param string $class
	 * @return string
	 */
	protected function get_cancel_button_markup( $value = '', $class = '' ) {
		$cancel_button = array(
			'id'    => 'wph-cancel-add-service',
			'type'  => 'ajax_button',
			'value' => empty( $value ) ? __( 'Cancel', Opt_In::TEXT_DOMAIN ) : $value,
			'class' => 'wpmudev-button wpmudev-button-ghost ' . $class,
		);
		return Opt_In::static_render('general/option', $cancel_button, true);
	}

	/**
	 * Retrieves the markup of the "Next" button for settings.
	 * -Helper.
	 * 
	 * 
	 * @since   3.0.5
	 * @param string $value
	 * @param string $class
	 * @return string
	 */
	protected function get_next_button_markup( $value = '', $class = '' ) {
		$next_button = array(
			'id'    => '',
			'type'  => 'ajax_button',
			'value' => empty( $value ) ? __( 'Update service', Opt_In::TEXT_DOMAIN ) : $value,
			'class' => 'hustle-provider-next wpmudev-button wpmudev-button-blue ' . $class,
		);
		return Opt_In::static_render('general/option', $next_button, true);
	}

	/**
	 * Saves the property 'desc' getting it's value from the property 'api_key'.
	 * Intended to be used on the 1st step when assigning the value of 'api_key' to 'desc' on the returned array.
	 * The value of 'desc' is what will be shown below the Provider’s title and above the text saying 
	 * “Click here to edit or change your email provider”, under “Email collection module” section on “Content” tab.
	 * -Helper.
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
		return $data;
	}
}
