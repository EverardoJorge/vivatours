<?php
class UWPM_Element {
	public $slug;
	public $label;
	public $section = '';
	public $callback_function;
	public $attributes = array();
	public $styling_options = array();

	public function __construct($element_type, $params = array()) {
		$this->slug = $element_type;

		$this->set_props($params);
	}

	public function set_props($params) {
		$defaults = array(
			'label' => $this->slug,
			'section' => 'uncategorized',
			'callback_function' => 'print_r',
			'attributes' => array(),
			'styling_options' => array()
		);

		$props = array_merge($defaults, $params);

		$this->label = $props['label'];
		$this->section = $props['section'];
		$this->callback_function = $props['callback_function'];
		$this->attributes = $props['attributes'];
		$this->styling_options = $props['styling_options'];
	}
}

?>