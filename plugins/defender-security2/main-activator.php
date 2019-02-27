<?php

/**
 * Author: Hoang Ngo
 */
class WD_Main_Activator {
	public $wp_defender;

	public function __construct( WP_Defender_Free $wp_defender ) {
		$this->wp_defender = $wp_defender;
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'wp_loaded', array( &$this, 'maybeShowNotice' ) );
		add_action( 'wp_ajax_hideDefenderNotice', array( &$this, 'hideNotice' ) );
		//add_action( 'activated_plugin', array( &$this, 'redirectToDefender' ) );
	}

	/**
	 * redirect to defender dahsboard after plugin activated
	 */
	public function redirectToDefender( $plugin ) {
		if ( isset( $_POST['plugin_status'] ) && $_POST['plugin_status'] == 'all' ) {
			//seem like a bulk action, do nothing
			return;
		}
		if ( $plugin == wp_defender()->plugin_slug ) {
			exit( wp_redirect( network_admin_url( 'admin.php?page=wp-defender' ) ) );
		}
	}

	/**
	 * Initial
	 */
	public function init() {
		$db_ver = get_site_option( 'wd_db_version' );
		if ( version_compare( $db_ver, '1.7', '<' ) ) {
			if ( ! \WP_Defender\Module\IP_Lockout\Component\Login_Protection_Api::checkIfTableExists() ) {
				add_site_option( 'defenderLockoutNeedUpdateLog', 1 );
				\WP_Defender\Module\IP_Lockout\Component\Login_Protection_Api::createTables();
				update_site_option( 'wd_db_version', "1.7" );
			}
		}
		if ( version_compare( $db_ver, '1.7.1', '<' ) ) {
			\WP_Defender\Module\IP_Lockout\Component\Login_Protection_Api::alterTableFor171();
			update_site_option( 'wd_db_version', "1.7.1" );
		}

		add_filter( 'plugin_action_links_' . plugin_basename( wp_defender()->plugin_slug ), array( &$this, 'addSettingsLink' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'register_styles' ) );
		if ( ! \WP_Defender\Behavior\Utils::instance()->checkRequirement() ) {
		} else {
			wp_defender()->isFree = true;
			//check if we do have API
			//start to init navigators
			\Hammer\Base\Container::instance()->set( 'dashboard', new \WP_Defender\Controller\Dashboard() );
			\Hammer\Base\Container::instance()->set( 'hardener', new \WP_Defender\Module\Hardener() );
			\Hammer\Base\Container::instance()->set( 'scan', new \WP_Defender\Module\Scan() );
			\Hammer\Base\Container::instance()->set( 'audit', new \WP_Defender\Module\Audit() );
			\Hammer\Base\Container::instance()->set( 'lockout', new \WP_Defender\Module\IP_Lockout() );
			\Hammer\Base\Container::instance()->set( 'advanced_tool', new \WP_Defender\Module\Advanced_Tools() );
			//no need to set debug
			require_once $this->wp_defender->getPluginPath() . 'free-dashboard/module.php';
			add_filter( 'wdev-email-message-' . plugin_basename( __FILE__ ), array( &$this, 'defenderAdsMessage' ) );
			do_action(
				'wdev-register-plugin',
				/* 1             Plugin ID */
				plugin_basename( __FILE__ ),
				'Defender',
				'/plugins/defender-security/',
				/* 4      Email Button CTA */
				__( 'Get Secure!', "defender-security" ),
				/* 5  getdrip Plugin param */
				'0cecf2890e'
			);
		}
	}

	public function defenderAdsMessage( $message ) {
		$message = __( "You're awesome for installing Defender! Are you interested in how to make the most of this plugin? We've collected all the best security resources we know in a single email - just for users of Defender!", "defender-security" );

		return $message;
	}

	public function hideNotice() {
		$utils = \WP_Defender\Behavior\Utils::instance();
		if ( ! $utils->checkPermission() ) {
			return;
		}

		update_site_option( 'wdf_noNotice', 1 );
		wp_send_json_success();
	}

	public function maybeShowNotice() {
		$utils = \WP_Defender\Behavior\Utils::instance();
		if ( get_site_option( 'wdf_noNotice' ) == 1 ) {
			return;
		}

		if ( $utils->checkPermission()
		     && ( is_admin() || is_network_admin() )
		     && class_exists( 'WPMUDEV_Dashboard' )
		     && $utils->getAPIKey() != false
		) {
			if ( \WP_Defender\Behavior\Utils::instance()->isActivatedSingle() ) {
				add_action( 'admin_notices', array( &$this, 'showUpgradeNotification' ) );
			} else {
				add_action( 'network_admin_notices', array( &$this, 'showUpgradeNotification' ) );
			}
			add_action( 'wp_ajax_installDefenderPro', array( &$this, 'installDefenderPro' ) );
			add_action( 'defender_enqueue_assets', array( &$this, 'enqueueUpgradeJs' ) );
		}
	}

	public function enqueueUpgradeJs() {
		wp_enqueue_script( 'defender-upgrader', wp_defender()->getPluginUrl() . '/assets/js/upgrader.js', array( 'jquery' ), wp_defender()->version );
	}

	public function showUpgradeNotification() {
		$class   = 'notice notice-info is-dismissible wp-defender-notice';
		$message = sprintf( __( "%s, you now have access to Defender's pro features but you still have the free version installed. Let's upgrade Defender and unlock all those juicy features! &nbsp; %s", "defender-security" ),
			\WP_Defender\Behavior\Utils::instance()->getDisplayName(),
			'<button id="install-defender-pro" type="button" data-id="1081723" data-nonce="' . wp_create_nonce( 'installDefenderPro' ) . '" class="button button-small">' . __( "Upgrade", "defender-security" ) . '</button>'
		);
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
	}

	public function installDefenderPro() {
		if ( \WP_Defender\Behavior\Utils::instance()->checkPermission() == false ) {
			return;
		}

		if ( ! wp_verify_nonce( \Hammer\Helper\HTTP_Helper::retrieve_post( 'nonce' ), 'installDefenderPro' ) ) {
			return;
		}

		if ( ! class_exists( 'WPMUDEV_Dashboard' ) ) {
			//usually should not here
			return;
		}

		$upgrader = WPMUDEV_Dashboard::$upgrader;
		if ( file_exists( dirname( __DIR__ ) . '/wp-defender/wp-defender.php' ) || $upgrader->install( '1081723' ) ) {
			//activate this
			activate_plugin( 'wp-defender/wp-defender.php' );
			wp_send_json_success( array(
				'url' => network_admin_url( 'admin.php?page=wp-defender' )
			) );
		} else {
			wp_send_json_error( array(
				'message' => __( "<br/>Something went wrong. Please try again later!", "defender-security" )
			) );
		}
	}

	/**
	 * Add a setting link in plugins page
	 * @return array
	 */
	public function addSettingsLink( $links ) {
		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=wp-defender' ) . '">' . __( "Settings", "defender-security" ) . '</a>',
		);

		$mylinks = array_merge( $mylinks, $links );
		$mylinks = array_merge( $mylinks, array(
			'<a target="_blank" href="https://premium.wpmudev.org/docs/wpmu-dev-plugins/defender/">' . __( "Docs", "defender-security" ) . '</a>',
			'<a style="color: #1ABC9C" target="_blank" href="'.\WP_Defender\Behavior\Utils::instance()->campaignURL('defender_wppluginslist_upgrade').'">' . __( "Upgrade", "defender-security" ) . '</a>',
		) );
		return $mylinks;
	}

	/**
	 * Register globally css, js will be load on each module
	 */
	public function register_styles() {
		wp_enqueue_style( 'defender-menu', wp_defender()->getPluginUrl() . 'assets/css/defender-icon.css' );

		$css_files = array(
			'defender' => wp_defender()->getPluginUrl() . 'assets/css/styles.css'
		);

		foreach ( $css_files as $slug => $file ) {
			wp_register_style( $slug, $file, array(), wp_defender()->version );
		}

		$js_files = array(
			'defender' => wp_defender()->getPluginUrl() . 'assets/js/scripts.js'
		);

		foreach ( $js_files as $slug => $file ) {
			wp_register_script( $slug, $file, array(), wp_defender()->version );
		}

		do_action( 'defender_enqueue_assets' );
	}

	public function activationHook() {

	}
}
