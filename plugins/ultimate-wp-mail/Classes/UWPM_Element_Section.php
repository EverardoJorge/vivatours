<?php
class UWPM_Element_Section {
	public $slug;
	public $label;

	public function __construct($element_type, $params = array()) {
		$this->slug = $element_type;
		
		if (array_key_exists('label', $params)) {$this->label = $params['label'];}
		else {$this->label = $this->slug;}
	}
}

?>