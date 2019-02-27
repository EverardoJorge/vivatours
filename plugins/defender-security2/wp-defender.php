<?php

/**
 * Plugin Name: Defender
 * Plugin URI: https://premium.wpmudev.org/project/wp-defender/
 * Version:     2.0.1
 * Description: Get regular security scans, vulnerability reports, safety recommendations and customized hardening for your site in just a few clicks. Defender is the analyst and enforcer who never sleeps.
 * Author:      WPMU DEV
 * Author URI:  http://premium.wpmudev.org/
 * License:     GNU General Public License (Version 2 - GPLv2)
 * Text Domain: defender-security
 * Network: true
 */
class WP_Defender_Free {

	/**
	 * Store the WP_Defender object for singleton implement
	 *
	 * @var WP_Defender_Free
	 */
	private static $_instance;
	/**
	 * @var string
	 */
	private $plugin_path;

	/**
	 * @return string
	 */
	public function getPluginPath() {
		return $this->plugin_path;
	}

	/**
	 * @return string
	 */
	public function getPluginUrl() {
		return $this->plugin_url;
	}

	/**
	 * @var string
	 */
	private $plugin_url;
	/**
	 * @var string
	 */
	public $domain = 'defender-security';

	/**
	 * @var string
	 */
	public $version = "1.5";

	/**
	 * @var string
	 */
	public $isFree = false;
	/**
	 * @var array
	 */
	public $global = array();
	/**
	 * @var string
	 */
	public $plugin_slug = 'defender-security/wp-defender.php';

	public $db_version = "1.5-free";

	/**
	 * @return WP_Defender_Free
	 */
	public static function instance() {
		if ( ! is_object( self::$_instance ) ) {
			self::$_instance = new WP_Defender_Free();
		}

		return self::$_instance;
	}

	/**
	 * WP_Defender constructor.
	 */
	private function __construct() {
		$this->initVars();
		$this->includeVendors();
		$this->autoload();
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_styles' ) );
		add_action( 'plugins_loaded', array( &$this, 'loadTextdomain' ) );
		$phpVersion = phpversion();
		if ( version_compare( $phpVersion, '5.3', '>=' ) ) {
			include_once $this->getPluginPath() . 'main-activator.php';
			$this->global['bootstrap'] = new WD_Main_Activator( $this );
		} else {
			include_once $this->getPluginPath() . 'legacy-activator.php';
			$this->global['bootstrap'] = new WD_Legacy_Activator( $this );
		}
	}

	public function loadTextdomain() {
		load_plugin_textdomain( $this->domain, false, $this->plugin_path . 'languages' );
	}

	/**
	 * Init values
	 */
	private function initVars() {
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->plugin_url  = plugin_dir_url( __FILE__ );
	}

	/**
	 * Including vendors
	 */
	private function includeVendors() {
		$phpVersion = phpversion();
		if ( version_compare( $phpVersion, '5.3', '>=' ) ) {
			include_once $this->plugin_path . 'vendor' . DIRECTORY_SEPARATOR . 'hammer' . DIRECTORY_SEPARATOR . 'bootstrap.php';
		}

		include_once $this->plugin_path . 'shared-ui/plugin-ui.php';
	}

	/**
	 * Register the autoload
	 */
	private function autoload() {
		spl_autoload_register( array( &$this, '_autoload' ) );
	}

	/**
	 * Register globally css, js will be load on each module
	 */
	public function register_styles() {
		wp_enqueue_style( 'defender-menu', $this->getPluginUrl() . 'assets/css/defender-icon.css' );

		$css_files = array(
			'defender' => $this->plugin_url . 'assets/css/styles.css'
		);

		foreach ( $css_files as $slug => $file ) {
			wp_register_style( $slug, $file, array(), $this->version );
		}

		$js_files = array(
			'defender' => $this->plugin_url . 'assets/js/scripts.js'
		);

		foreach ( $js_files as $slug => $file ) {
			wp_register_script( $slug, $file, array(), $this->version );
		}

		do_action( 'defender_enqueue_assets' );
	}

	/**
	 * @param $class
	 */
	public function _autoload( $class ) {
		$base_path = __DIR__ . DIRECTORY_SEPARATOR;
		$pools     = explode( '\\', $class );

		if ( $pools[0] != 'WP_Defender' ) {
			return;
		}
		if ( $pools[1] == 'Vendor' ) {
			unset( $pools[0] );
		} else {
			$pools[0] = 'App';
		}

		//build the path
		$path = implode( DIRECTORY_SEPARATOR, $pools );
		$path = $base_path . strtolower( str_replace( '_', '-', $path ) ) . '.php';
		if ( file_exists( $path ) ) {
			include_once $path;
		}
	}
}

if ( ! function_exists( 'wp_defender' ) ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	if ( is_plugin_active( 'wp-defender/wp-defender.php' ) ) {
		return;
	}
	/**
	 * Shorthand to get the instance
	 * @return WP_Defender_Free
	 */
	function wp_defender() {
		return WP_Defender_Free::instance();
	}

	//init
	wp_defender();

	function wp_defender_deactivate() {
		//we disable any cron running
		wp_clear_scheduled_hook( 'processScanCron' );
		wp_clear_scheduled_hook( 'lockoutReportCron' );
		wp_clear_scheduled_hook( 'auditReportCron' );
		wp_clear_scheduled_hook( 'cleanUpOldLog' );
		wp_clear_scheduled_hook( 'scanReportCron' );
	}

	function wp_defender_activate() {
		if ( wp_defender()->isFree ) {
			return;
		}
	}

	register_deactivation_hook( __FILE__, 'wp_defender_deactivate' );
	register_activation_hook( __FILE__, 'wp_defender_activate' );
}