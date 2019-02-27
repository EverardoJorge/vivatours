<?php
if( !class_exists("Hustle_Zapier") ):

class Hustle_Zapier extends Hustle_Provider_Abstract {

	const SLUG = "zapier";
	//const NAME = "Zapier";
	
	/**
	 * Provider Instance
	 *
	 * @since 3.0.5
	 *
	 * @var self|null
	 */
	protected static $_instance = null;

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_slug 				   = 'zapier';

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_version				   = '1.0';
	
	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_class 				   = __CLASS__;

	/**
	 * @since 3.0.5
	 * @var string
	 */
	protected $_title                  = 'Zapier';

	/**
	 * @since 3.0.5
	 * @var bool
	 */
	protected $_supports_fields 	   = true;

	/**
	 * Class name of form settings
	 *
	 * @var string
	 */
	protected $_form_settings = 'Hustle_Zapier_Form_Settings';

	/**
	 * Provider constructor.
	 */	
	public function __construct() {
		$this->_icon = plugin_dir_path( __FILE__ ) . 'views/icon.php';
	}
	
	/**
	 * Get Instance
	 *
	 * @return self|null
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Adds contact to the Zapier
	 *
	 * @param Hustle_Module_Model $module
	 * @param array $data
	 * @return mixed|bool|WP_Error
	 */
	public function subscribe( Hustle_Module_Model $module, array $data ) {
		$webhook_url 	= self::_get_webhook_url( $module );
		$err			= new WP_Error();

		$content_type = 'application/json';

		$blog_charset = get_option( 'blog_charset' );
		if ( ! empty( $blog_charset ) ) {
			$content_type .= '; charset=' . $blog_charset;
		}

		$args = array(
			'method'    => 'POST',
			'body'      => wp_json_encode( $data ),
			'headers'   => array(
				'Content-Type'  => $content_type,
			),
		);

		$res = wp_remote_post( $webhook_url, apply_filters( 'hustle_zapier_args', $args ) );

		if ( ! is_wp_error( $res ) ) {
			$body = json_decode( $res['body'], true );
			if ( ! empty( $res['body'] ) && $body && !empty( $body['status'] ) && 'success' === $body['status'] ) {
				return true;
			} else {
				$data['error'] 	= !empty( $body['status'] ) ? 'Status: ' . $body['status'] : 'Something went wrong.';
			}
		} else {
			$data['error'] 	= $res->get_error_message();
		}

		$module->log_error( $data );

		return $err;
	}

	public static function _get_webhook_url( Hustle_Module_Model $module ) {
		return self::get_provider_details( $module, 'api_key', self::SLUG );
	}

}

endif;
