<?php
/**
 * Class Hustle_Provider_Abstract
 * Extend this class to create a new hustle provider / integration
 * Any change(s) to this file is subject to:
 * - Properly Written DocBlock! (what is this, why is that, how to be like those, etc, as long as you want!)
 * - Properly Written Changelog!
 * 
 * This class must be extended by your integration in order to be integrated into Hustle.
 * For more information, more examples, and even sample integrations, visit this page at WPMUDev's site:
 * @see https://linktodocumentation.com 
 *
 * @since 3.0.5
 */
abstract class Hustle_Provider_Abstract implements Hustle_Provider_Interface{
	
	const LISTS = "lists";

	/**
	 * Provider Instance
	 * Assigned and used by Hustle's core.
	 * -Required. Must be overridden and set to null.
	 *
	 * @since 3.0.5
	 * @var self|null
	 */
	protected static $_instance;

	/**
	 * Minimum Hustle version required by your integration in order to work properly.
	 * Your integration won't be instantiated if the active Hustle version is lower than the defined here.
	 * If the minimum Hustle version your integration requires is different than this one, override this property.
	 * Kept public so it can be retrieved by the abstract class on PHP 5.2.
	 * -Required. It's '3.0.5' by default.
	 *
	 * @example '3.0.6'
	 * @since 3.0.5
	 * @var string
	 */
	public static $_min_hustle_version = '3.0.5';
	
	/**
	 * Minimum PHP version required by your integration in order to work properly.
	 * Your integration won't be instantiated if the current PHP version is lower than the defined here.
	 * If your integration requires a minimum PHP version, override this property.
	 * Kept public so it can be retrieved by the abstract class on PHP 5.2.
	 * -Required. There's no minimum by default.
	 * 
	 * @example '7.0'
	 * @since 3.0.5
	 * @var string
	 */
	public static $_min_php_version = PHP_VERSION;

	/**
	 * Slug will be used as an identifier throughout hustle.
	 * Make sure it's unique, else it won't be loaded or will carelessly override other provider with same slug.
	 * -Required.
	 *
	 * @example 'my_unique_provider_slug'
	 * @since 3.0.5
	 * @var string
	 */
	protected $_slug;

	/**
	 * Version number of the integration.
	 * -Required.
	 *
	 * @example '1.0'
	 * @since 3.0.5
	 * @var string
	 */
	protected $_version;

	/**
	 * Class name of your integration's main class.
	 * That's the one extending Hustle_Provider_Abstract class. Yes, this class.
	 * -Required.
	 *
	 * @example __CLASS__
	 * @since 3.0.5
	 * @var string
	 */
	protected $_class;
	
	/**
	 * Whether the provider supports custom fields. Override it if your integration does accept custom fields.
	 * Leaving it as false will disable adding fields to opt-in forms.
	 * -Required. It's false by default.
	 *
	 * @since 3.0.5
	 * @var bool
	 */
	protected $_supports_fields = false;

	/**
	 * The path to the frontend args template.
	 * Used if your integration must display arguments in the opt-in form. It could be simple information or form fields.
	 * Hustle will look into this path to render the file.
	 * This template will be inserted within <script> tags, so javascript code can be included in there.
	 * The variables provided by @see Hustle_Provider_Abstract::get_args() will be available within your file when rendered by Hustle.
	 * -Optional.
	 * 
	 * @see Hustle_Mailchimp_Provider
	 * @example plugin_dir_path( __FILE__ ) . 'views/front_args_template.php'
	 * @since 3.0.5
	 * @var string
	 */
	protected $_front_args;

	/**
	 * Title of your integration.
	 * It will be shown on the integration's list, and when your integration is selected.
	 * -Required.
	 * 
	 * @example 'My Unique Provider'
	 * @since 3.0.5
	 * @var string
	 */
	protected $_title;
	
	/**
	 * Url or path of the icon that will be displayed when the provider is selected.
	 * If it's PNG or JPG, use the URL to the icon. It will be used within the "src" attribute of an "img" element.
	 * @see Hustle_Mad_Mimi
	 * @example plugin_dir_url( __FILE__ ) . 'assets/icon-madmimi.png'
	 * If it's a SVG, use the path to the PHP template file containing the SVG code.
	 * @see Hustle_Mailchimp
	 * @example plugin_dir_path( __FILE__ ) . 'views/icon.php'
	 * -Optional. Looks nice.
	 *
	 * @since  3.0.5
	 * @var string
	 */
	protected $_icon;

	/**
	 * Retina icon url that will be displayed when the provider is selected,
	 * Used for JPG and PNG icons.
	 * -Optional. Required if you have a JPG or PNG icon set on @see Hustle_Provider_Abstract::$_icon .
	 * 
	 * @example plugin_dir_url( __FILE__ ) . 'assets/icon-madmimi_x2.png'
	 * @since  3.0.5
	 * @var string
	 */
	protected $_icon_x2;

	/**
	 * Flag that a provider can be activated.
	 * Hustle will assign its value according to @see Hustle_Provider_Abstract::check_is_activable().
	 * -Shouldn't be overridden.
	 *
	 * @since 3.0.5
	 * @var bool
	 */
	private $is_activable = null;
	
	/**
	 * Class name of your integration form settings class.
	 * Leave empty your integration doesn't have settings.
	 * This class must exist on runtime in order to work.
	 * -Optional.
	 * 
	 * @example 'Hustle_Mailchimp_Form_Settings'
	 * @since 3.0.5
	 * @var null|string
	 */
	protected $_form_settings = null;

	/**
	 * Form Setting Instance.
	 * If your integration has a value assigned to @see Hustle_Provider_Abstract::$_form_settings ,
	 * an instance of that class will be assigned to this property.
	 * -Shouldn't be overridden.
	 *
	 * @since  3.0.5
	 * @var Hustle_Provider_Form_Settings_Abstract
	 */
	protected $_provider_form_settings_instance = null;

	/**
	 * Gets the instance of your integration.
	 * This must be added to each provider's class for it to work properly with PHP 5.2.
	 * -Required.
	 *
	 * @since 3.0.5
	 * @return self|null
	 * 
	 * 	
	 * public static function get_instance() {
	 * 		if ( is_null( self::$_instance ) ) {
	 * 			self::$_instance = new self();
	 * 		}
	 * 
	 * 		return self::$_instance;
	 * 	}
	 * 
	 */

	
	/**
	 * Gets this provider slug.
	 * @see Hustle_Provider_Abstract::$_slug
	 *
	 * The slug property behaves as `IDENTIFIER`, used for:
	 * - Easily calling this instance with @see Opt_In_Utils::get_provider_by_slug(`slug`)
	 * - Avoid collision, registered as FIFO by @see Hustle_Providers::register()
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_slug() {
		return $this->_slug;
	}

	/**
	 * Gets this integration version.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_version() {
		return $this->_version;
	}

	/**
	 * Gets this integration class name.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_class() {
		return $this->_class;
	}
	
	/**
	 * Gets if this integration supports custom fields.
	 *
	 * @since  3.0.5
	 * @return bool
	 */
	final public function get_supports_fields() {
		return $this->_supports_fields;
	}

	/**
	 * Gets if this integration has args.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_front_args() {
		return $this->_front_args;
	}

	/**
	 * Gets the title of this integration. 
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_title() {
		return $this->_title;
	}
	
	/**
	 * Gets the icon path or URL.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_icon() {
		return $this->_icon;
	}

	/**
	 * Gets retina icon URL.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_icon_x2() {
		return $this->_icon_x2;
	}

	/**
	 * Transforms some properties of the integration instance into an array.
	 *
	 * @return array
	 */
	final public function to_array() {
		return array(
			'slug'                   => $this->get_slug(),
			'title'                  => $this->get_title(),
			'icon'                   => $this->get_icon(),
			'icon_x2'                => $this->get_icon_x2(),
			'version'                => $this->get_version(),
			'class'                  => $this->get_class(),
			'supports_fields'		 => $this->get_supports_fields(),
			'front_args'			 => $this->get_front_args(),
			'is_activable'           => $this->is_activable(),
			'is_form_settings_available'  => $this->is_form_settings_available(),
		);
	}
	
	/**
	 * Gets activable status.
	 *
	 * @return bool
	 */
	final public function is_activable() {
		if ( is_null( $this->is_activable ) ) {
			$this->is_activable = $this->check_is_activable();
		}

		return $this->is_activable;
	}
	
	/**
	 * Checks if the integration meets the requirements to be activated.
	 * Override this method if you have another logic for checking activable integrations.
	 * Non-activable integrations are instantiated, but not listed for the users to be used.
	 * If your integration has certain requirements that should prevent it from being 
	 * instantiated if not met, override @see Hustle_Provider_Abstract::check_is_compatible() instead.
	 * -Optional.
	 *
	 * @return bool
	 */
	public function check_is_activable() {
		if ( ! self::check_is_compatible( $this->_class ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if the provider meets the requirements to be instantiated.
	 * If the provider is not compatible, it won't be instantiated.
	 * Instantiating a not compatible provider may trigger PHP errors.
	 * By default, it will return false if:
	 * -The installed PHP version is lower than the required by your integration.
	 * -The installed Hustle version is lower than the required by your integration.
	 * 
	 * Override this method if you have another logic for checking if your integration is compatible.
	 * -Optional.
	 * 
	 * @since 3.0.5
	 * 
	 * @param string $class_name
	 * @return bool
	 */
	public static function check_is_compatible( $class_name ) {

		// PHP 5.2 compatibility.
		$reflector = new ReflectionClass( $class_name );

		$_min_php_version = $reflector->getStaticPropertyValue( '_min_php_version' );
		$is_php_version_supported = version_compare( PHP_VERSION, $_min_php_version, '>=' );
		if ( ! $is_php_version_supported ) {
			return false;
		}

		// If it's a test version, skip Hustle version validation
		if ( false !== stripos( Opt_in::VERSION, 'beta' ) || false !== stripos( Opt_in::VERSION, 'alpha' ) ) {
			return true;
		}

		$_min_hustle_version = $reflector->getStaticPropertyValue( '_min_hustle_version' );
		$is_hustle_version_supported = version_compare( Opt_In::VERSION, $_min_hustle_version, '>=' );
		if ( ! $is_hustle_version_supported  ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Get Form Setting Wizard
	 * This function will process @see Hustle_Provider_Abstract::form_settings_wizard()
	 * Please keep in mind this function will only be called when @see Hustle_Provider_Abstract::is_form_settings_available() returns `true`
	 * which will call @see Hustle_Provider_Abstract::form_settings_wizard() to check if requirements are passed.
	 *
	 * @since 3.0.5
	 *
	 * @param array $submitted_data Array with the submitted data. Softly sanitized by @see Hustle_Api_Utils::validate_and_sanitize_fields().
	 * @param int   $current_step Step from which the call is made.
	 * @param int   $step Step to which the user is going.
	 * @param bool  $is_submit Whether the call is made as a form submission or to display the settings form.
	 * @param bool  $is_step Whether the call is made by navigating through the steps or by opening the integration's settings.
	 *
	 * @return array|mixed
	 */
	final public function get_form_settings_wizard( $submitted_data, $current_step = 0, $step = 0, $is_submit = false, $is_step = true ) {

		$steps = $this->get_form_settings_steps();

		if ( ! is_array( $steps ) ) {
			return $this->get_empty_wizard( sprintf( __( 'No Form Settings available for %1$s', Opt_In::TEXT_DOMAIN ), $this->get_title() ) );
		}
		$total_steps = count( $steps );
		if ( $total_steps < 1 ) {
			return $this->get_empty_wizard( sprintf( __( 'No Form Settings available for %1$s', Opt_In::TEXT_DOMAINDOMAIN ), $this->get_title() ) );
		}

		if ( ! isset( $steps[ $step ] ) ) {
			// go to last step
			$step = $total_steps - 1;
			return $this->get_form_settings_wizard( $submitted_data, $current_step, $step, $is_submit );
		}

		$is_step = ( 'true' === $is_step );
		// only validate when moving forward and it's submit
		if ( $step > $current_step || ( intval( $step ) === --$total_steps && ! $is_submit && $is_step )  ) {

			$current_step_result = $this->get_form_settings_wizard( $submitted_data, $current_step, $current_step, true );
			if ( isset( $current_step_result['has_errors'] ) && true === $current_step_result['has_errors'] ) {
				return $current_step_result;
			} elseif ( isset( $current_step_result['data_to_save'] ) ) {
				$returned_data = $current_step_result['data_to_save'];
			}
			if ( intval( $step ) === $total_steps && $is_step ) {
				$close = true;
			}
		}
		
		if ( $step > 0 && ! $is_submit ) {
			//check previous step is complete
			$prev_step              = $step - 1;
			$prev_step_is_completed = true;
			// only call `is_completed` when its defined
			if ( isset( $steps[ $prev_step ]['is_completed'] ) && is_callable( $steps[ $prev_step ]['is_completed'] ) ) {
				$prev_step_is_completed = call_user_func( $steps[ $prev_step ]['is_completed'], $submitted_data );
			}
			if ( ! $prev_step_is_completed ) {
				$step --;

				return $this->get_form_settings_wizard( $submitted_data, $current_step, $step, true );
			}
		}

		$data_to_save = isset( $returned_data ) ? $returned_data : array();
		$is_close = isset( $close ) ? $close : false;
		return $this->get_wizard( $steps, $submitted_data, $step, $is_close, $is_submit, $data_to_save );
	}

	/**
	 * Gets the steps from integration's form settings wizard.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	final private function get_form_settings_steps() {
		$form_settings_steps    = array();
		$form_settings_instance = $this->get_provider_form_settings();
		if ( ! is_null( $form_settings_instance ) && $form_settings_instance instanceof Hustle_Provider_Form_Settings_Abstract ) {
			$form_settings_steps = $form_settings_instance->form_settings_wizards();
		}

		return $form_settings_steps;
	}
	

	/**
	 * Checks whether the integration has available form settings.
	 * This function will check @see Hustle_Provider_Form_Settings_Abstract::form_settings_wizards()
	 * as a valid multi array.
	 *
	 * @since 3.0.5
	 * @return bool
	 */
	final public function is_form_settings_available() {
		if( ! is_admin() ) {
			return false;
		}
		$steps = $this->get_form_settings_steps();

		if ( ! is_array( $steps ) ) {
			return false;
		}

		if ( count( $steps ) < 1 ) {
			return false;
		}

		return true;
	}

	/**
	 * Gets the class name of the integration's form settings class.
	 * @see   Hustle_Provider_Form_Settings_Abstract
	 *
	 * @since 3.0.5
	 * @return null|string
	 */
	final public function get_form_settings_class_name() {
		$provider_slug            = $this->get_slug();
		$form_settings_class_name = $this->_form_settings;

		/**
		 * Filter the class name of the integration's form settings class.
		 *
		 * Form settings class name is a string
		 * it will be validated by `class_exists` and must be instanceof @see Hustle_Provider_Form_Settings_Abstract
		 *
		 * @since 3.0.5
		 * @param string $form_settings_class_name
		 */
		$form_settings_class_name = apply_filters( 'hustle_provider_' . $provider_slug . '_form_settings_class_name', $form_settings_class_name );

		return $form_settings_class_name;
	}

	/**
	 * Gets Form Settings Instance.
	 *
	 * @since   3.0.5
	 * @return Hustle_Provider_Form_Settings_Abstract | null
	 */
	final public function get_provider_form_settings() {
		$class_name = $this->get_form_settings_class_name();
		if ( is_null( $this->_provider_form_settings_instance ) || ! $this->_provider_form_settings_instance instanceof Hustle_Provider_Form_Settings_Abstract ) {
			if ( empty( $class_name ) ) {
				return null;
			}

			if ( ! class_exists( $class_name ) ) {
				return null;
			}

			try {
				$form_settings_instance = new $class_name( $this );
				if ( ! $form_settings_instance instanceof Hustle_Provider_Form_Settings_Abstract ) {
					throw new Exception( $class_name . ' is not instanceof Hustle_Provider_Form_Settings_Abstract' );
				}
				$this->_provider_form_settings_instance = $form_settings_instance;
			} catch ( Exception $e ) {
				Hustle_Api_Utils::maybe_log( $this->get_slug(), 'Failed to instantiate its _form_settings_instance', $e->getMessage() );

				return null;
			}
		}

		return $this->_provider_form_settings_instance;
	}

	/**
	 * Gets the requested wizard.
	 *
	 * @since 3.0.5
	 *
	 * @param array $steps Array with all the wizard's steps from the integration.
	 * @param array $submitted_data Array with the submitted data. Softly sanitized by @see Hustle_Api_Utils::validate_and_sanitize_fields().
	 * @param int   $step Step from which the call is made.
	 * @param bool	$is_close Whether the settings wizard should be closed or the step should be displayed.
	 * @param bool  $is_submit Whether the call is made as a form submission or to display the settings form.
	 * @param array $data_to_save Integration's data to be saved.
	 *
	 * @return array|mixed
	 */
	private function get_wizard( $steps, $submitted_data, $step = 0, $is_close, $is_submit = false, $data_to_save = array() ) {
		$total_steps = count( $steps );

		if ( ! $is_close ) {
			// validate callback, when its empty or not callable, mark as no wizard
			if ( ! isset( $steps[ $step ]['callback'] ) || ! is_callable( $steps[ $step ]['callback'] ) ) {
				return $this->get_empty_wizard( sprintf( __( 'No Settings available for %1$s', Opt_In::TEXT_DOMAIN ), $this->get_title() ) );
			}

			$wizard = call_user_func( $steps[ $step ]['callback'], $submitted_data, $is_submit );
			// a wizard to be able to processed by our application need to has at least `html` which will be rendered or `redirect` which will be the url for redirect user to go to
			if ( ! isset( $wizard['html'] ) && ! isset( $wizard['redirect'] ) ) {
				return $this->get_empty_wizard( sprintf( __( 'No Settings available for %1$s', Opt_In::TEXT_DOMAIN ), $this->get_title() ) );
			}
		} else {
			$wizard = array();
			$wizard['html'] = '';
		}
		$wizard['opt_in_provider_current_step']  = $step;
		$wizard['opt_in_provider_count_step']    = $total_steps;
		$wizard['opt_in_provider_has_next_step'] = ( ( $step + 1 ) >= $total_steps ? false : true );
		$wizard['opt_in_provider_has_prev_step'] = ( $step > 0 ? true : false );
		if ( ! isset( $wizard['has_errors'] ) ) {
			$wizard['has_errors'] = false;
		}

		if ( ! isset( $wizard['is_close'] ) ) {
			$wizard['is_close'] = $is_close;
		}

		if ( ! isset( $wizard['notification'] ) ) {
			$wizard['notification'] = false;
		}
		
		if ( ! empty( $data_to_save ) ) {
			$wizard['data_to_save'] = $data_to_save;
		}

		return $wizard;
	}

	/**
	 * Function to submit the data filled on the opt-in form by the user.
	 * This is the actual function that will submit the user's data to your integration.
	 * You can send the to an external API, store the values, anything.
	 * Here you can also validate the data that's required, check if the user is already subscribed,
	 * register custom fields to the API being used, and so on.
	 * -Required.
	 *
	 * @since 3.0.5
	 * @param Hustle_Module_Model $module Instance of the module from where the form was submitted. Useful for retrieving saved data within subscribe().
	 * @param array $data Data submitted through the opt-in form by the user.
	 * @return true|WP_Error true on success, WP_Error with its error message to be shown to frontend users on failure.
	 */
	public function subscribe( Hustle_Module_Model $module, array $data ) {
		
		/**
		 * The default fields from Hustle' opt-in are named:
		 * 'email', 'first_name' ('f_name' on previous versions), and 'last_name' ('l_name' on previous versions). 
		 * Other data sent on submission by default is:
		 * 'module_id': id of the module from which the data is submitted,
		 * 'page_type': post type from where the submission was done,
		 * 'page_id': id of the post from where the submission was done,
		 * 'uri': url from where the data is submitted,
		 * 'type': type of the module doing the submission. If it's a Pop-Up, a Slide-in, or an Embed.
		 * 
		 * Any other field is considered a custom field.
		 * If your integration doesn't support custom fields, these are the only fields you'll need to handle.
		 * 
		 */
		Hustle_Api_Utils::maybe_log( __METHOD__, $this->get_title() . ' provider does not have a proper "subscribe" method.' );
		return new WP_Error( 'invalid_subscribe_method', __( "Something went wrong. Please, contact the site's support.", Opt_In::TEXT_DOMAIN ) );
	}
	
	/**
	 * Gets empty wizard markup.
	 * Helper to display a user friendly step when no settings are available.
	 *
	 * @since 3.0.5
	 * @param string $notice
	 * @return array
	 */
	public function get_empty_wizard( $notice ) {
		return array(
			'html'    => '<div class="sui-notice sui-notice-error">' . esc_html( $notice ) . '</div>',
			'buttons' => array(
				'close' => array(
					'action' => 'close',
					'data'   => array(),
					'markup' => '<a href="" class="hustle-provider-next wpmudev-button wpmudev-button-ghost">' . __( 'Close', Opt_In::TEXT_DOMAIN ) . '</a>',
				),
			),
		);
	}

	/**
	 * Gets the provider's data.
	 * General function to get the provider's details from database based on a module_id and field key.
	 * This method required an instance of Hustle_Module_Model. Now it accepts the module_id in order to prevent
	 * third party integrations from having to use Hustle_Module_Model::instance()->get( $module_id ) just to use this method.
	 * -Helper.
	 *
	 * @param int|Hustle_Module_Model $module_id The ID of the module from which the data will be retrieved.
	 * @param string $field The field name in which the requested data is stored.
	 * @param string $slug The slug of the provider which data is retrieved.
	 *
	 * @return string
	 */
	public static function get_provider_details( $module_id, $field, $slug ) {
		$details = '';
		if ( is_object( $module_id ) && $module_id instanceof Hustle_Module_Model ) {
			$module = $module_id;
		} else {
			if ( ! ( $module_id instanceof Hustle_Module_Model ) || 0 === (int) $module_id ) {
				return $details;
			}
			$module = Hustle_Module_Model::instance()->get( $module_id );
		}
		
		if ( !is_null( $module->content->email_services ) 
			&& isset( $module->content->email_services[$slug] ) 
			&& isset( $module->content->email_services[$slug][$field] ) ) {

			$details = $module->content->email_services[$slug][$field];
		}
		return $details;
	}

	/**
	 * Gets a set of arguments to be passed into your integration's frontend template.
	 * @see Hustle_Provider_Abstract::$_front_args
	 * Include any variables your integration may require when displaying the template on frontend by returning them on this method.
	 * This method is commented out because it should only be defined by the integrations that use it.
	 * The returned array will be available within the $args array on your template, and also as javascript variables.
	 * -Optional. Required if displaying arguments in the opt-in form.
	 * 
	 * @param array $data The information stored into the module's content, plus its ID.
	 * @return array The array you'll be accessing from your template through the variable called $args.
	 * 
	 * public function get_args( $data ) {
	 * 
	 * 		// For example, getting some custom value stored on the module
	 * 		$my_args = array();
	 *  	if ( isset( $data['email_services']['my_integration'] ) ) {
	 * 			$my_args['custom_value'] = $data['email_services']['my_integration']['my_custom_value'];
	 * 		}
	 * 
	 * 		// Accessible within the $args array on your template, as $args['custom_value'], and as a javascript variable named 'custom_value'
	 * 		return $my_args;
	 * }
	 */

	/**
	 * Updates provider's db option with the new value.
	 *
	 * @uses update_site_option
	 * @param string $option_key
	 * @param mixed $option_value
	 * @return bool
	 */
	public function update_provider_option($option_key, $option_value){
		return update_site_option( $this->get_slug() . "_" . $option_key, $option_value);
	}
	
	/**
	 * Retrieves provider's option from db.
	 *
	 * @uses get_site_option
	 * @param string $option_key
	 * @return mixed
	 */
	public function get_provider_option($option_key, $default){
		return get_site_option( $this->get_slug() . "_" . $option_key, $default );
	}
}
