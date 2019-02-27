<?php 

require_once 'class-wonderplugin-carousel-model.php';
require_once 'class-wonderplugin-carousel-view.php';
require_once 'class-wonderplugin-carousel-update.php';

class WonderPlugin_Carousel_Controller {

	private $view, $model, $update;

	function __construct() {

		$this->model = new WonderPlugin_Carousel_Model($this);	
		$this->view = new WonderPlugin_Carousel_View($this);
		$this->update = new WonderPlugin_Carousel_Update($this);
		
		$this->init();
	}
	
	function add_metaboxes()
	{
		$this->view->add_metaboxes();
	}
	
	function show_overview() {
		
		$this->view->print_overview();
	}
	
	function show_items() {
	
		$this->view->print_items();
	}
	
	function add_new() {
		
		$this->view->print_add_new();
	}
	
	function show_item()
	{
		$this->view->print_item();
	}
	
	function edit_item()
	{
		$this->view->print_edit_item();
	}
	
	function register()
	{
		$this->view->print_register();
	}
	
	function check_license($options)
	{
		return $this->model->check_license($options);
	}
	
	function deregister_license($options)
	{
		return $this->model->deregister_license($options);
	}
	
	function save_plugin_info($info)
	{
		return $this->model->save_plugin_info($info);
	}
	
	function get_plugin_info()
	{
		return $this->model->get_plugin_info();
	}
	
	function get_update_data($action, $key)
	{
		return $this->update->get_update_data($action, $key);
	}
	
	function generate_body_code($id, $itemname, $has_wrapper) {
		
		return $this->model->generate_body_code($id, $itemname, $has_wrapper);
	}
	
	function delete_item($id)
	{
		return $this->model->delete_item($id);
	}
	
	function trash_item($id)
	{
		return $this->model->trash_item($id);
	}
	
	function restore_item($id)
	{
		return $this->model->restore_item($id);
	}
	
	function clone_item($id)
	{
		return $this->model->clone_item($id);
	}
	
	function save_item($item)
	{
		return $this->model->save_item($item);	
	}
	
	function get_list_data() {
	
		return $this->model->get_list_data();
	}
	
	function get_item_data($id) {
		
		return $this->model->get_item_data($id);
	}
	
	function edit_settings()
	{
		$this->view->print_edit_settings();
	}
	
	function save_settings($options)
	{
		$this->model->save_settings($options);
	}
	
	function get_settings()
	{
		return $this->model->get_settings();
	}
	
	function init() {
	
		$engine = array("WordPress Carousel", "WordPress Carousel Plugin", "WordPress Image Carousel", "WordPress Image Carousel Plugin", "WordPress Image Scroller", "WordPress Image Scroller Plugin", "WordPress Carousel Slider", "WordPress Carousel Slider Plugin", "Responsive WordPress Carousel", "Responsive WordPress Carousel Plugin", "Responsive WordPress Image Carousel", "Responsive WordPress Image Carousel Plugin", "Responsive WordPress Image Scroller", "Responsive WordPress Image Scroller Plugin", "Responsive WordPress Carousel Slider", "Responsive WordPress Carousel Slider Plugin");
		$option_name = 'wonderplugin-carousel-engine';
		if ( get_option( $option_name ) == false )
			update_option( $option_name, $engine[array_rand($engine)] );
	}
	
	function import_export()
	{
		$this->view->import_export();
	}
	
	function search_replace_items($post)
	{
		return $this->model->search_replace_items($post);
	}
	
	function import_carousel($post, $files)
	{
		return $this->model->import_carousel($post, $files);
	}
	
	function export_carousel() {
	
		return $this->model->export_carousel();
	}
}