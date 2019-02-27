<?php

/**
 * Class Hustle_GHBlock_Abstract
 * Extend this class to create new gutenberg block
 *
 * @since 1.0 Gutenberg Addon
 */
abstract class Hustle_GHBlock_Abstract {

	/**
	 * Type will be used as identifier
	 *
	 * @since 1.0 Gutenber Addon
	 *
	 * @var string
	 */
	protected $_slug;

	/**
	 * Get block type
	 *
	 * @since  1.0 Gutenber Addon
	 * @return string
	 */
	final public function get_slug() {
		return $this->_slug;
	}

	/**
	 * Initialize block
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function init() {
		// Register block
		$this->register_block();

		// Load block scripts
		add_action( 'enqueue_block_editor_assets', array( $this, 'load_assets' ) );
	}

	/**
	 * Register block type callback
	 * Shouldn't be overridden on block class
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function register_block() {
		
		if ( function_exists( 'register_block_type' ) ) {
			
			register_block_type( 'hustle/' . $this->get_slug(), array(
				'render_callback' => array( $this, 'render_block' ),
			) );
		}

	}

	/**
	 * Render block on front-end
	 * Should be overriden in block class
	 *
	 * @since 1.0 Gutenberg Addon
	 * @param array $properties Block properties
	 *
	 * @return string
	 */
	public function render_block( $properties = array() ) {
		return '';
	}
	
	/**
	 * Enqueue assets ( scritps / styles )
	 * Should be overriden in block class
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function load_assets() {
		return true;
	}

}
