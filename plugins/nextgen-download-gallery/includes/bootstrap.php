<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
* kick start the plugin
* needs to hook at priority 0 to beat Event Espresso's load_espresso_addons()
*/
add_action('plugins_loaded', function() {
	require NGG_DLGALL_PLUGIN_ROOT . 'includes/class.NextGENDownloadGallery.php';
	NextGENDownloadGallery::getInstance()->pluginStart();
}, 0);
