<?php
/*
Plugin Name: WonderPlugin Carousel
Plugin URI: http://www.wonderplugin.com
Description: WordPress Image and Video Carousel Plugin
Version: 12.3
Author: Magic Hills Pty Ltd
Author URI: http://www.wonderplugin.com
License: Copyright 2015 Magic Hills Pty Ltd, All Rights Reserved
*/

define('WONDERPLUGIN_CAROUSEL_VERSION', '12.3');
define('WONDERPLUGIN_CAROUSEL_URL', plugin_dir_url( __FILE__ ));
define('WONDERPLUGIN_CAROUSEL_PATH', plugin_dir_path( __FILE__ ));
define('WONDERPLUGIN_CAROUSEL_PLUGIN', basename(dirname(__FILE__)) . '/' . basename(__FILE__));
define('WONDERPLUGIN_CAROUSEL_PLUGIN_VERSION', '12.3');

require_once 'app/class-wonderplugin-carousel-controller.php';

class WonderPlugin_Carousel_Plugin {
	
	function __construct() {
	
		$this->init();
	}
	
	public function init() {
		
		// init controller
		$this->wonderplugin_carousel_controller = new WonderPlugin_Carousel_Controller();
		
		add_action( 'admin_menu', array($this, 'register_menu') );
		
		add_shortcode( 'wonderplugin_carousel', array($this, 'shortcode_handler') );
		
		add_action( 'init', array($this, 'register_script') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_script') );
		
		if ( is_admin() )
		{
			add_action( 'wp_ajax_wonderplugin_carousel_save_config', array($this, 'wp_ajax_save_item') );
			add_action( 'admin_init', array($this, 'admin_init_hook') );
			add_action( 'admin_post_wonderplugin_carousel_export', array($this, 'export_carousel') );
		}
		
		$supportwidget = get_option( 'wonderplugin_carousel_supportwidget', 1 );
		if ( $supportwidget == 1 )
		{
			add_filter('widget_text', 'do_shortcode');
		}
	}
	
	function register_menu()
	{
		$settings = $this->get_settings();
		$userrole = $settings['userrole'];
		
		$menu = add_menu_page(
				__('WonderPlugin Carousel', 'wonderplugin_carousel'),
				__('WonderPlugin Carousel', 'wonderplugin_carousel'),
				$userrole,
				'wonderplugin_carousel_overview',
				array($this, 'show_overview'),
				WONDERPLUGIN_CAROUSEL_URL . 'images/logo-16.png' );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_carousel_overview',
				__('Overview', 'wonderplugin_carousel'),
				__('Overview', 'wonderplugin_carousel'),
				$userrole,
				'wonderplugin_carousel_overview',
				array($this, 'show_overview' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_carousel_overview',
				__('New Carousel', 'wonderplugin_carousel'),
				__('New Carousel', 'wonderplugin_carousel'),
				$userrole,
				'wonderplugin_carousel_add_new',
				array($this, 'add_new' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_carousel_overview',
				__('Manage Carousels', 'wonderplugin_carousel'),
				__('Manage Carousels', 'wonderplugin_carousel'),
				$userrole,
				'wonderplugin_carousel_show_items',
				array($this, 'show_items' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_carousel_overview',
				__('Import/Export', 'wonderplugin_carousel'),
				__('Import/Export', 'wonderplugin_carousel'),
				'manage_options',
				'wonderplugin_carousel_import_export',
				array($this, 'import_export' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_carousel_overview',
				__('Settings', 'wonderplugin_carousel'),
				__('Settings', 'wonderplugin_carousel'),
				'manage_options',
				'wonderplugin_carousel_edit_settings',
				array($this, 'edit_settings' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		
		$menu = add_submenu_page(
				null,
				__('View Carousel', 'wonderplugin_carousel'),
				__('View Carousel', 'wonderplugin_carousel'),	
				$userrole,	
				'wonderplugin_carousel_show_item',	
				array($this, 'show_item' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				null,
				__('Edit Carousel', 'wonderplugin_carousel'),
				__('Edit Carousel', 'wonderplugin_carousel'),
				$userrole,
				'wonderplugin_carousel_edit_item',
				array($this, 'edit_item' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
	}
	
	function register_script()
	{		
		wp_register_script('wonderplugin-carousel-template-script', WONDERPLUGIN_CAROUSEL_URL . 'app/wonderplugincarouseltemplate.js', array('jquery'), WONDERPLUGIN_CAROUSEL_VERSION, false);
		wp_register_script('wonderplugin-carousel-skins-script', WONDERPLUGIN_CAROUSEL_URL . 'engine/wonderplugincarouselskins.js', array('jquery'), WONDERPLUGIN_CAROUSEL_VERSION, false);
		wp_register_script('wonderplugin-carousel-script', WONDERPLUGIN_CAROUSEL_URL . 'engine/wonderplugincarousel.js', array('jquery'), WONDERPLUGIN_CAROUSEL_VERSION, false);
		wp_register_script('wonderplugin-carousel-creator-script', WONDERPLUGIN_CAROUSEL_URL . 'app/wonderplugin-carousel-creator.js', array('jquery'), WONDERPLUGIN_CAROUSEL_VERSION, false);
		wp_register_style('wonderplugin-carousel-engine-css', WONDERPLUGIN_CAROUSEL_URL . 'engine/wonderplugincarouselengine.css');
		wp_register_style('wonderplugin-carousel-admin-style', WONDERPLUGIN_CAROUSEL_URL . 'wonderplugincarousel.css');
		wp_register_style('wonderplugin-carousel-socialmedia-style', WONDERPLUGIN_CAROUSEL_URL . 'engine/icons/css/fontello.css');
	}
	
	function enqueue_script()
	{
		wp_enqueue_style('wonderplugin-carousel-engine-css');
		
		$addjstofooter = get_option( 'wonderplugin_carousel_addjstofooter', 0 );
		if ($addjstofooter == 1)
		{
			wp_enqueue_script('wonderplugin-carousel-skins-script', false, array(), false, true);
			wp_enqueue_script('wonderplugin-carousel-script', false, array(), false, true);
		}
		else
		{
			wp_enqueue_script('wonderplugin-carousel-skins-script');
			wp_enqueue_script('wonderplugin-carousel-script');
		}
	}
	
	function enqueue_admin_script($hook)
	{
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style ('wp-jquery-ui-dialog');
		
		wp_enqueue_script('post');
		if (function_exists("wp_enqueue_media"))
		{
			wp_enqueue_media();
		}
		else
		{
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
		}
		wp_enqueue_script('wonderplugin-carousel-template-script');
		wp_enqueue_script('wonderplugin-carousel-skins-script');
		wp_enqueue_script('wonderplugin-carousel-script');
		wp_enqueue_script('wonderplugin-carousel-creator-script');
		wp_enqueue_style('wonderplugin-carousel-admin-style');
		wp_enqueue_style('wonderplugin-carousel-socialmedia-style');
		wp_enqueue_style('wonderplugin-carousel-engine-css');
	}
	
	function generate_lightbox_options()
	{
		return '<div class="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="' . WONDERPLUGIN_CAROUSEL_URL . 'engine/" style="display:none;"></div>';
	}
	
	function admin_init_hook()
	{
		$settings = $this->get_settings();
		$userrole = $settings['userrole'];
		if ( !current_user_can($userrole) )
			return;
		
		// change text of history media uploader
		if (!function_exists("wp_enqueue_media"))
		{
			global $pagenow;
			
			if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
				add_filter( 'gettext', array($this, 'replace_thickbox_text' ), 1, 3 );
			}
		}
		
		// add meta boxes
		$this->wonderplugin_carousel_controller->add_metaboxes();
	}
	
	function replace_thickbox_text($translated_text, $text, $domain) {
		
		if ('Insert into Post' == $text) {
			$referer = strpos( wp_get_referer(), 'wonderplugin-carousel' );
			if ( $referer != '' ) {
				return __('Insert into carousel', 'wonderplugin_carousel' );
			}
		}
		return $translated_text;
	}
	
	function show_overview() {
		
		$this->wonderplugin_carousel_controller->show_overview();
	}
	
	function show_items() {
		
		$this->wonderplugin_carousel_controller->show_items();
	}
	
	function add_new() {
		
		$this->wonderplugin_carousel_controller->add_new();
	}
	
	function show_item() {
		
		$this->wonderplugin_carousel_controller->show_item();
	}
	
	function edit_item() {
	
		$this->wonderplugin_carousel_controller->edit_item();
	}
	
	function edit_settings() {
	
		$this->wonderplugin_carousel_controller->edit_settings();
	}
	
	function register() {
	
		$this->wonderplugin_carousel_controller->register();
	}
	
	function get_settings() {
	
		return $this->wonderplugin_carousel_controller->get_settings();
	}
	
	function shortcode_handler($atts) {
		
		if ( !isset($atts['id']) && !isset($atts['name']) )
			return __('Please specify a carousel id or name', 'wonderplugin_carousel');
		
		return $this->generate_lightbox_options() . "\r\n" . $this->wonderplugin_carousel_controller->generate_body_code( (isset($atts['id']) ? $atts['id'] : null), (isset($atts['name']) ? $atts['name'] : null), false);
	}
	
	function wp_ajax_save_item() {
		
		check_ajax_referer( 'wonderplugin-carousel-ajaxnonce', 'nonce' );
		
		$settings = $this->get_settings();
		$userrole = $settings['userrole'];
		if ( !current_user_can($userrole) )
			return;
		
		$jsonstripcslash = get_option( 'wonderplugin_carousel_jsonstripcslash', 1 );
		if ($jsonstripcslash == 1)
			$json_post = trim(stripcslashes($_POST["item"]));
		else
			$json_post = trim($_POST["item"]);
		$json_post = str_replace("\\\\", "\\\\\\\\", $json_post);
		$items = json_decode($json_post, true);
		
		if ( empty($items) )
		{
			$json_error = "json_decode error";
			if ( function_exists('json_last_error_msg') )
				$json_error .= ' - ' . json_last_error_msg();
			else if ( function_exists('json_last_error') )
				$json_error .= 'code - ' . json_last_error();
			
			header('Content-Type: application/json');
			echo json_encode(array(
					"success" => false,
					"id" => -1, 
					"message" => $json_error
				));
			wp_die();
		}
		
		add_filter('safe_style_css', 'wonderplugin_carousel_css_allow');
		add_filter('wp_kses_allowed_html', 'wonderplugin_carousel_tags_allow', 'post');
		foreach ($items as $key => &$value)
		{
			if ($value === true)
				$value = "true";
			else if ($value === false)
				$value = "false";
			else if ( is_string($value) )
				$value = wp_kses_post($value);
		}
		
		if (isset($items["slides"]) && count($items["slides"]) > 0)
		{
			foreach ($items["slides"] as $key => &$slide)
			{
				foreach ($slide as $key => &$value)
				{
					if ($value === true)
						$value = "true";
					else if ($value === false)
						$value = "false";
					else if ( is_string($value) )
						$value = wp_kses_post($value);
				}
			}
		}
		remove_filter('wp_kses_allowed_html', 'wonderplugin_carousel_tags_allow', 'post');
		remove_filter('safe_style_css', 'wonderplugin_carousel_css_allow');
		
		header('Content-Type: application/json');
		echo json_encode($this->wonderplugin_carousel_controller->save_item($items));
		wp_die();
		
	}
	
	function import_export() {
	
		$this->wonderplugin_carousel_controller->import_export();
	}
	
	function export_carousel() {
	
		check_admin_referer('wonderplugin-carousel', 'wonderplugin-carousel-export');
	
		if ( !current_user_can('manage_options') )
			return;
	
		$this->wonderplugin_carousel_controller->export_carousel();
	}
}

/**
 * Init the plugin
 */
$wonderplugin_carousel_plugin = new WonderPlugin_Carousel_Plugin();

/**
 * Uninstallation
 */
function wonderplugin_carousel_uninstall() {
	
	if ( ! current_user_can( 'activate_plugins' ) )
		return;
	
	global $wpdb;
	
	$keepdata = get_option( 'wonderplugin_carousel_keepdata', 1 );
	if ( $keepdata == 0 )
	{
		$table_name = $wpdb->prefix . "wonderplugin_carousel";
		$wpdb->query("DROP TABLE IF EXISTS $table_name");
	}
}

if ( function_exists('register_uninstall_hook') )
{
	register_uninstall_hook( __FILE__, 'wonderplugin_carousel_uninstall' );
}

define('WONDERPLUGIN_CAROUSEL_VERSION_TYPE', 'F');
