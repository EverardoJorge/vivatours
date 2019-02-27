<?php
/**
 * Plugin Name: Table Sorter
 * Plugin URI: http://wpreloaded.com/tablesorter
 * Description: This plugin makes your standard HTML tables sortable. For more details, check plugin's docs.
 * Version: 2.2
 * Author: Farhan Noor
 * Author URI: http://linkedin.com/in/farhan-noor
 * License: GPLv2 or later
 */

function tablesorter_enque_scripts(){
	wp_register_script('table-sorter',plugins_url('table-sorter/jquery.tablesorter.min.js'),array('jquery'));
	wp_enqueue_script('table-sorter-metadata',plugins_url('table-sorter/jquery.metadata.js'),array('table-sorter'), '2.2');
	wp_enqueue_script('table-sorter-custom-js',plugins_url('table-sorter/wp-script.js'),array('table-sorter'), '2.2');
	wp_enqueue_style('table-sorter-custom-css',plugins_url('table-sorter/wp-style.css'));
}
add_action( 'wp_enqueue_scripts', 'tablesorter_enque_scripts' );


function tablesorter_menu(){
	add_management_page( 'Table Sorter', 'Table Sorter', 'manage_options', 'table-sorter', 'tablesorter_callback');
}
add_action('admin_menu', 'tablesorter_menu');
function tablesorter_callback(){
	require_once('wp-admin-page.php');
}

function tablesorter_row_meta( $links, $file ) {
	if ( strpos( $file, 'table-sorter.php' ) !== false ) {
		$new_links = array('<a href="http://wpreloaded.com/plugins/table-sorter/how-to/" target="_blank">Docs</a>');
		$links = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'tablesorter_row_meta', 10, 2 );

function tablesorter_add_action_links ( $links ) {
	$mylinks = array('<a href="' . admin_url( 'tools.php?page=table-sorter' ) . '">Docs</a>');
	return array_merge( $links, $mylinks );
}
add_filter( 'plugin_action_links_table-sorter/table-sorter.php', 'tablesorter_add_action_links' );