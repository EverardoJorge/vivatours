<?php

/**
 * Wrapper for the utils expected to be used by external integrations.
 *
 * Class Hustle_Api_Utils
 */
class Hustle_Api_Utils {

	/**
	 * Checks if the ajax call is legit.
	 * This method will verify if the user making the request is allowed to make changes, and if the nonces are correct by
	 * checking current_user_can( 'manage_options' ), and check_ajax_referer( $action, false, false ).
	 *
	 * @since 3.0.5
	 * @uses Opt_In_Utils::validate_ajax_call()
	 * @uses check_ajax_referer()
	 * @uses current_user_can()
	 * @param string $action ajax call action name
	 */
	public static function validate_ajax_call( $action ) {
		Opt_In_Utils::validate_ajax_call( $action );
	}

	/**
	 * Renders or returns a template file.
	 *
	 * @since 3.0.5
	 * @uses Opt_In::static_render()
	 * @param string $file
	 * @param array $params
	 * @param bool $return
	 * @return string|null
	 */
	public static function static_render( $file, $params = array(), $return = false ) {
		$view = Opt_In::static_render( $file, $params, $return );
		if ( $return ) {
			return $view;
		}
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

	/**
	 * Registers ajax endpoints for a provider.
	 * It will get an instance of $class_name and make a call to its method named “register_ajax_endpoints”.
	 * It’s a shortcut for registering non-static AJAX endpoints. 
	 *
	 * @since 3.0.5
	 * @param string $class_name
	 */
	public static function register_ajax_endpoints( $class_name ) {

		if ( call_user_func( array( $class_name, 'check_is_compatible' ), $class_name ) ) {

			$instance = call_user_func( array( $class_name, 'get_instance' ) );
			$instance->get_provider_form_settings()->register_ajax_endpoints();
		}

		/*if ( $class_name::check_is_compatible( $class_name ) ) {
			// @todo Check if register_ajax_endpoints() is callable first
			$class_name::get_instance( $class_name )->get_provider_form_settings()->register_ajax_endpoints();
		}*/
	}
	
	/**
	 * Used for sanitizing form submissions.
	 * This method will do a simple sanitation of $post_data. It applies sanitize_text_field() to the keys and values of the first level array. 
	 * The keys from second level arrays are converted to numbers, and their values are sanitized with sanitize_text_field() as well.
	 * This method doesn’t do an exhaustive sanitation, so you should handled special cases if your integration requires something different.
	 * The names passed on $required_fields are searched into $post_data array keys. If the key is not set, an array with the key “errors” is returned.
	 *
	 * @since 3.0.5
	 * @uses Opt_In_Utils::validate_and_sanitize_fields()
	 * @param array $post_data The data to be sanitized and validated.
	 * @param array $required_fields Fields that must exist on $post_data so the validation doesn't fail.
	 * @return array
	 */
	public static function validate_and_sanitize_fields( $post_data, $required_fields = array() ) {
		return Opt_In_Utils::validate_and_sanitize_fields( $post_data, $required_fields );
	}

	/**
	 * Used for checking required fields on form submissions.
	 * The names passed on $required_fields are searched into $post_data array keys. If the key is not set, an array with the key “errors” is returned.
	 *
	 * @since 3.0.5
	 * @param array $submitted_data The data to be validated.
	 * @param array $required_fields Fields that must exist on $post_data so the validation doesn't fail. It must be an associative array, with the field name as the keys.
	 * @param string $error_message Error message to be used on sprintf to retrieve user friendly error messages for each fields.
	 * @return array Empty if everything is good. Filled if there are errors.
	 */
	public static function check_for_required_fields( $submitted_data, $required_fields, $error_message = '' ) {
		$errors = array();
		$error_message = empty( $error_message ) ? __( '%s is required.', Opt_In::TEXT_DOMAIN ) : $error_message;
		foreach ( $required_fields as $key => $required_field ) {
			if ( ! isset( $submitted_data[ $key ] ) || ( empty( $submitted_data[ $key ] ) && '0' !== $submitted_data[ $key ] ) ) {
				$errors[ $key ] = sprintf( $error_message, $required_field );
				continue;
			}
		}
		return $errors;
	}

}
