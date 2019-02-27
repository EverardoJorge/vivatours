<?php

/**
 * Class Hustle_GHBlock_Social_Share
 *
 * @since 1.0 Gutenberg Addon
 */
class Hustle_GHBlock_Social_Share extends Hustle_GHBlock_Abstract {

	/**
	 * Block identifier
	 *
	 * @since 1.0 Gutenberg Addon
	 *
	 * @var string
	 */
	protected $_slug = 'social-share';

	/**
	 * Hustle_GHBlock_Social_Share constructor.
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function __construct() {
		// Initialize block
		$this->init();
	}

	/**
	 * Render block markup on front-end
	 *
	 * @since 1.0 Gutenberg Addon
	 * @param array $properties Block properties
	 *
	 * @return string
	 */
	public function render_block( $properties = array() ) {
		$css_class = isset( $properties['css_class'] ) ? $properties['css_class'] : '';

		if ( isset( $properties['id'] ) ) {
			return '[wd_hustle id="' . $properties['id'] . '" type="social_sharing" css_class="' . $css_class . '"]';
		}
	}

	/**
	 * Enqueue assets ( scritps / styles )
	 * Should be overriden in block class
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function load_assets() {
		// Scripts
		wp_enqueue_script(
			'hustle-block-social-share',
			Hustle_Gutenberg::get_plugin_url() . '/js/social-share-block.min.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			filemtime( Hustle_Gutenberg::get_plugin_dir() . '/js/social-share-block.min.js' )
		);
		
		// Localize scripts
		wp_localize_script(
			'hustle-block-social-share',
			'hustle_ss_data',
			array(
				'modules' => $this->get_modules(),
				'admin_url' => admin_url( 'admin.php' ),
				'shortcode_tag' => Hustle_Module_Front::SHORTCODE,
				'nonce' => wp_create_nonce( 'hustle_gutenberg_get_module' ),
				'l10n' => $this->localize()
			)
		);
		wp_enqueue_style( 'hustle_front', Opt_In::$plugin_url  . 'assets/css/front.min.css', array( 'dashicons' ), Opt_In::VERSION );
		wp_enqueue_style( 'hustle_front_ie', Opt_In::$plugin_url  . 'assets/css/ie-front.min.css', array( 'dashicons' ), Opt_In::VERSION );
		
		
	}

	public function get_modules() {

		$modules = Hustle_Module_Collection::instance()->get_all( true, array( 'module_type' => 'social_sharing' ) );

		$module_list = array(
			array(
				'value' => '',
				'label' => esc_html__( 'Choose module name', Opt_In::TEXT_DOMAIN )
			)
		);

		if ( is_array( $modules ) ) {

			foreach ( $modules as $module ) {

				$settings = $module->get_display_settings()->to_array();
				if ( 'false' === $settings['shortcode_enabled'] ) {
					continue;
				}
				
				$module_list[] = array(
					'value' => $module->get_shortcode_id(),
					'label' => $module->module_name,
				);
			}
		}

		return $module_list;

	}

	private function localize() {
		return array(
			'advanced' => esc_html__( 'Advanced', Opt_In::TEXT_DOMAIN ),
			'additional_css_classes' => esc_html__( 'Additional CSS Classes', Opt_In::TEXT_DOMAIN ),
			'name' => esc_html__( 'Name', Opt_In::TEXT_DOMAIN ),
			'module' => esc_html__( 'Module', Opt_In::TEXT_DOMAIN ),
			'customize_module' => esc_html__( 'Customize Social Share', Opt_In::TEXT_DOMAIN ),
			'rendering' => esc_html__( 'Rendering...', Opt_In::TEXT_DOMAIN ),
			'block_name' => esc_html__( 'Social Share', Opt_In::TEXT_DOMAIN ),
			'block_description' => esc_html__( 'Display your Hustle Social Share module in this block.', Opt_In::TEXT_DOMAIN ),
		);
	}

}

new Hustle_GHBlock_Social_Share();
