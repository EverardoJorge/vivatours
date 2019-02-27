<?php
/**
 * @author: Hoang Ngo
 */
// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}


if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( is_plugin_active( 'wp-defender/wp-defender.php' ) ) {
	return;
}

$path = dirname( __FILE__ );
include_once $path . DIRECTORY_SEPARATOR . 'wp-defender.php';

$tweakFixed = \WP_Defender\Module\Hardener\Model\Settings::instance()->getFixed();

foreach ( $tweakFixed as $rule ) {
	$rule->getService()->revert();
}

$scan = \WP_Defender\Module\Scan\Model\Scan::findAll();
foreach ( $scan as $model ) {
	$model->delete();
}

\WP_Defender\Module\Scan\Component\Scan_Api::flushCache();

$cache = \Hammer\Helper\WP_Helper::getCache();
$cache->delete( 'wdf_isActivated' );
$cache->delete( 'wdfchecksum' );
$cache->delete( 'cleanchecksum' );

\WP_Defender\Module\Scan\Model\Settings::instance()->delete();
\WP_Defender\Module\Hardener\Model\Settings::instance()->delete();
\WP_Defender\Module\IP_Lockout\Model\Settings::instance()->delete();
\WP_Defender\Module\Advanced_Tools\Model\Auth_Settings::instance()->delete();
\WP_Defender\Module\Advanced_Tools\Model\Mask_Settings::instance()->delete();
//clear old stuff
delete_site_option( 'wp_defender' );
delete_option( 'wp_defender' );
delete_site_option( 'wd_db_version' );
delete_option( 'wd_db_version' );
delete_option( 'wdf_noNotice' );
delete_site_option( 'wdf_noNotice' );