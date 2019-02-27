<?php

/**
 * Conditions utils
 *
 * Most of the methods are courtesy Philipp Stracker
 *
 * Class Opt_In_Utils
 */
class Opt_In_Utils {

	/**
	 * Instance of Opt_In_Geo
	 *
	 * @var Opt_In_Geo
	 */
	private $_geo;

	public function __construct( Opt_In_Geo $geo ) {
		$this->_geo = $geo;
	}

	/**
	 * Checks if user has already commented
	 *
	 * @return bool|int
	 */
	public function has_user_commented() {
		global $wpdb;
		static $comment = null;

		if ( null === $comment ) {
			// Guests (and maybe logged in users) are tracked via a cookie.
			$comment = isset( $_COOKIE['comment_author_' . COOKIEHASH] ) ? 1 : 0;

			if ( ! $comment && is_user_logged_in() ) {
				// For logged-in users we can also check the database.
				$count = absint( $wpdb->get_var( $wpdb->prepare(
						"SELECT COUNT(1) FROM {$wpdb->comments} WHERE user_id = %s",
								get_current_user_id() ) ) );
				$comment = $count > 0;
			}
		}
		return $comment;
	}

	/**
	 * Returns the referrer.
	 *
	 * @return string
	 */
	public function get_referrer() {
		$referrer = '';

		$is_ajax = (defined( 'DOING_AJAX' ) && DOING_AJAX)
			|| ( ! empty( $_POST['_po_method_'] ) && 'raw' === $_POST['_po_method_'] ); // WPCS: CSRF ok.

		if ( isset( $_REQUEST['thereferrer'] ) ) { // WPCS: CSRF ok.
			$referrer = $_REQUEST['thereferrer']; // WPCS: CSRF ok.
		} else if ( ! $is_ajax && isset( $_SERVER['HTTP_REFERER'] ) ) {
			// When doing Ajax request we NEVER use the HTTP_REFERER!
			$referrer = $_SERVER['HTTP_REFERER'];
		}

		return $referrer;
	}

	/**
	 * Tests if the current referrer is one of the referers of the list.
	 * Current referrer has to be specified in the URL param "thereferer".
	 *
	 *
	 * @param  array $list List of referers to check.
	 * @return bool
	 */
	public function test_referrer( $list ) {
		$response = false;
		if ( is_string( $list ) ) {
			$list = array( $list );
		}
		if ( ! is_array( $list ) ) {
			return true;
		}

		$referrer = $this->get_referrer();

		if ( empty( $referrer ) ) {
			$response = false;
		} else {
			foreach ( $list as $item ) {
				$item = trim( $item );
				$res = stripos( $referrer, $item );
				if ( false !== $res ) {
					$response = true;
					break;
				}
			}
		}

		return $response;
	}

	/**
	 * Tests if the $test_url matches any pattern defined in the $list.
	 *
	 * @since  4.6
	 * @param  string $test_url The URL to test.
	 * @param  array $list List of URL-patterns to test against.
	 * @return bool
	 */
	public function check_url( $test_url, $list ) {
		$response = false;
		$list = array_map( 'trim', (array) $list );
		$test_url = strtok( $test_url, '#' );

		if ( empty( $list ) ) {
			$response = true;
		} else {
			foreach ( $list as $match ) {
				$match = preg_quote( strtok( $match, '#' ), null );

				if ( false === strpos( $match, '://' ) ) {
					$match = '\w+://' . $match;
				}
				if ( '/' !== substr( $match, -1 ) ) {
					$match .= '/?';
				} else {
					$match .= '?';
				}
				$exp = '#^' . $match . '$#i';
				$res = preg_match( $exp, $test_url );

				if ( $res ) {
					$response = true;
					break;
				}
			}
		}

		return $response;
	}

	/**
	 * Returns current url
	 * should only be called after plugins_loaded hook is fired
	 *
	 * @return string
	 */
	public static function get_current_url(){
		if( !did_action("plugins_loaded") )
			new Exception("This method should only be called after plugins_loaded hook is fired");

		global $wp;
		return add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	}

	/**
	 * Returns current actual url, the one seen on browser
	 *
	 * @return string
	 */
	public function get_current_actual_url(){
		if( !did_action("plugins_loaded") )
			new Exception("This method should only be called after plugins_loaded hook is fired");

		return "http" . ( isset($_SERVER['HTTPS'] ) ? "s" : "" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}



	/**
	 * Checks if the current user IP belongs to one of the countries defined in
	 * country_codes list.
	 *
	 * @param  array $country_codes List of country codes.
	 * @return bool
	 */
	public function test_country( $country_codes ) {
		$response = true;
		$country = $this->_geo->get_user_country();

		if ( 'XX' === $country ) {
			return $response;
		}

		return in_array( $country, (array) $country_codes, true );
	}

	/**
	 * Checks if user is allowed to perform the ajax actions
	 *
	 * @since 1.0
	 * @return bool
	 */
	public static function is_user_allowed(){
		return current_user_can("manage_options");
	}

	/**
	 * Checks if the ajax
	 *
	 * @since 1.0
	 * @param $action string ajax call action name
	 */
	public static function validate_ajax_call( $action ){
		if( !self::is_user_allowed() || !check_ajax_referer( $action, false, false ) )
			wp_send_json_error( __("Invalid request, you are not allowed to make this request", Opt_In::TEXT_DOMAIN) );
	}

	/**
	 * Verify if current version is FREE
	 **/
	public static function _is_free() {
		$is_free = ! file_exists( Opt_In::$plugin_path . 'lib/wpmudev-dashboard/wpmudev-dash-notification.php' );

		return $is_free;
	}

	/**
	 * Verify if current version is free
	 **/
	public static function is_hustle_free( $type = 'opt-ins' ) {
		$is_free = self::_is_free();

		if ( $is_free ) {
			if ( 'opt-ins' === $type ) {
				$optins = Opt_In_Collection::instance()->get_all_optins( null );
				$is_free = count( $optins ) > 1;
			} else {
				// For CC
				$cc = Hustle_Custom_Content_Collection::instance()->get_all( null );
				$is_free = count( $cc ) > 1;
			}
		}

		return $is_free;
	}

	/**
	 * Remove "-pro" that came from the menu which causes template not to work
	 **/
	public static function clean_current_screen( $screen ) {
		return str_replace( 'hustle-pro', 'hustle', $screen );
	}

	/**
	 * Check if is IE
	 *
	 * @return bool
	 */
	public static function is_ie() {
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			preg_match( '/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches );
			if( count( $matches ) < 2 ) {
				preg_match( '/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches );
			}
			return ( count( $matches ) >1 );
		}
		return false;
	}
	
	
	/**
	 * Providers utilities from here below
	 */
	
	/**
	 * Gets an array with the file extensions for images allowed by Hustle
	 * Intended to be used when including Providers' icons
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_allowed_image_extensions() {
		$allowed_extensions = array( 'jpg', 'png' );
		
		/**
		 * Filter to change the allowed extensions
		 * 
		 * @since 3.0.5
		 */
		apply_filters( 'hustle_get_allowed_image_extensions', $allowed_extensions );
		return $allowed_extensions;
	}
	
	/**
	 *  Gets an array with the file extensions for renderables allowed by Hustle
	 * Intended to be used when including Providers' icons
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_allowed_renderable_extensions() {
		$allowed_extensions = array( 'php', 'html' );
		
		/**
		 * Filter to change the allowed extensions
		 * 
		 * @since 3.0.5
		 */
		apply_filters( 'hustle_get_allowed_renderable_extensions', $allowed_extensions );
		return $allowed_extensions;
	}

	/**
	 * Gets all providers as list
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_registered_providers_list() {
		$providers_list = Hustle_Providers::get_instance()->get_providers()->to_array();

		return $providers_list;
	}

	/**
	 * Returns provider class by name
	 *
	 * @since 3.0.5
	 * @param $slug string provider Slug
	 * @return bool|Opt_In_Provider_Abstract
	 */
	public static function get_provider_by_slug( $slug ){
		return Hustle_Providers::get_instance()->get_provider( $slug );
	}
	
	/**
	 * Get all activable providers as list
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_activable_providers_list(){
		$providers_list = self::get_registered_providers_list();
		foreach ( $providers_list as $key => $provider ) {
			if( ! $providers_list[ $key ]['is_activable'] ) {
				unset( $providers_list[ $key ] );
			}
		}
		return $providers_list;
	}
	

	/**
	 * Used for sanitizing form submissions.
	 * This method will do a simple sanitation of $post_data. It applies sanitize_text_field() to the keys and values of the first level array. 
	 * The keys from second level arrays are converted to numbers, and their values are sanitized with sanitize_text_field() as well.
	 * This method doesn’t do an exhaustive sanitation, so you should handled special cases if your integration requires something different.
	 * The names passed on $required_fields are searched into $post_data array keys. If the key is not set, an array with the key “errors” is returned.
	 *
	 * @since 3.0.5
	 * @param array $post_data The data to be sanitized and validated.
	 * @param array $required_fields Fields that must exist on $post_data so the validation doesn't fail.
	 * @return array
	 */
	public static function validate_and_sanitize_fields( $post_data, $required_fields = array() ) {
		//for serialized data or form
		if ( ! is_array( $post_data ) && is_string( $post_data ) ) {
			$post_string = $post_data;
			$post_data   = array();
			wp_parse_str( $post_string, $post_data );
		}

		$errors = array();
		foreach ( $required_fields as $key => $required_field ) {
			if ( ! isset( $post_data[ $required_field ] ) || ( empty( $post_data[ $required_field ] ) && '0' !== $post_data[ $required_field ] ) ) {
				/* translators: ... */
				$errors[ $required_field ] = sprintf( __( 'Field %s is required.', Opt_In::TEXT_DOMAIN ), $required_field );
				continue;
			}
		}

		if ( ! empty( $errors ) ) {
			return array( 'errors' => $errors );
		}

		$sanitized_data = array();
		foreach ( $post_data as $key => $post_datum ) {
			/**
			 * Sanitize here every request so we dont need to sanitize it again on other methods,
			 *  unless special treatment is required.
			 */
			$sanitized_data[ sanitize_text_field( $key ) ] = self::sanitize_text_input_deep( $post_datum );
		}

		return $sanitized_data;
	}

	/**
	 * Sanitizes the values of a multi-dimensional array.
	 * The keys of the sub-arrays are converted to numerical arrays.
	 * Sub-arrays are expected to have numerical indexes. 
	 *
	 * @since 3.0.5
	 * @param array|string $value
	 * @return string
	 */
	public static function sanitize_text_input_deep( $value, $key = null ) {
		$value = is_array( $value ) ? 
					array_map( array( 'Opt_In_Utils', 'sanitize_text_input_deep' ), $value, array_keys($value) ) :
					sanitize_text_field( $value );

		return $value;
	}

	/**
	 * Adds an entry to debug log
	 *
	 * By default it will check `WP_DEBUG` to decide whether to add the log,
	 * then will check `filters`.
	 *
	 * @since 3.0.5
	 */
	public static function maybe_log() {
		$enabled = ( defined( 'WP_DEBUG' ) && WP_DEBUG );

		/**
		 * Filter to enable or disable log for Hustle
		 *
		 * By default it will check `WP_DEBUG`
		 *
		 * @since 3.0.5
		 *
		 * @param bool $enabled current enabled status
		 */
		$enabled = apply_filters( 'hustle_enable_log', $enabled );

		if ( $enabled ) {
			$args    = func_get_args();
			$message = wp_json_encode( $args );
			if ( false !== $message ) {
				error_log( '[Hustle] ' . $message ); // phpcs:ignore
			}

		}
	}
}
