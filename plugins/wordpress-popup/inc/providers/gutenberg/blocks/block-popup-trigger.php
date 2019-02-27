<?php

/**
 * Class Hustle_GHBlock_Popup_Trigger
 *
 * @since 1.0 Gutenberg Addon
 */
class Hustle_GHBlock_Popup_Trigger extends Hustle_GHBlock_Abstract {

	/**
	 * Block identifier
	 *
	 * @since 1.0 Gutenberg Addon
	 *
	 * @var string
	 */
	protected $_slug = 'popup-trigger';

	/**
	 * Hustle_GHBlock_Popup_Trigger constructor.
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

		$content = isset( $properties['content'] ) ? $properties['content'] : __( 'Click here', Opt_In::TEXT_DOMAIN );
		$css_class = isset( $properties['css_class'] ) ? $properties['css_class'] : '';

		if ( isset( $properties['id'] ) ) {
			return '[wd_hustle id="' . $properties['id'] . '" type="popup" css_class="' . $css_class . '"]' . $content . '[/wd_hustle]';
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
			'hustle-block-popup-trigger',
			Hustle_Gutenberg::get_plugin_url() . '/js/popup-trigger-block.min.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			filemtime( Hustle_Gutenberg::get_plugin_dir() . '/js/popup-trigger-block.min.js' )
		);
		
		// Localize scripts
		wp_localize_script(
			'hustle-block-popup-trigger',
			'hustle_popup_trigger_data',
			array(
				'wizard_page' => Hustle_Module_Admin::POPUP_WIZARD_PAGE,
				'modules' => $this->get_modules(),
				'admin_url' => admin_url( 'admin.php' ),
				'nonce' => wp_create_nonce( 'hustle_gutenberg_get_module' ),
				'shortcode_tag' => Hustle_Module_Front::SHORTCODE,
				'text_domain' => Opt_In::TEXT_DOMAIN,
				'l10n' => $this->localize()
			)
		);
	}

	public function get_modules() {

		$modules = Hustle_Module_Collection::instance()->get_all( true, array( 'module_type' => 'popup' ) );

		$module_list = array();

		if ( is_array( $modules ) ) {

			foreach ( $modules as $module ) {
			
				$settings = $module->get_display_settings()->to_array();
				if ( 'click' === $settings['triggers']['trigger'] ) {
					
					$module_list[] = array(
						'value' => $module->get_shortcode_id(),
						'label' => $module->module_name,
					);
				}
			}

		}
			
		$first_item = array(
			'value' => '',
			'label' => ( ! empty( $module_list ) ) ? esc_html__( 'Choose module name', Opt_In::TEXT_DOMAIN ) : esc_html__( 'No modules were found', Opt_In::TEXT_DOMAIN ),
		);

		array_unshift( $module_list, $first_item );

		return $module_list;

	}

	private function localize() {
		return array(
			'module' => esc_html__( 'Module', Opt_In::TEXT_DOMAIN ),
			'additional_css_classes' => esc_html__( 'Additional CSS Classes', Opt_In::TEXT_DOMAIN ),
			'click_here' => esc_html__( 'Click here', Opt_In::TEXT_DOMAIN ),
			'content_here' => esc_html__( 'Add the clickable text that will trigger the module.', Opt_In::TEXT_DOMAIN ),
			'advanced' => esc_html__( 'Advanced', Opt_In::TEXT_DOMAIN ),
			'trigger_content' => esc_html__( 'Trigger Content', Opt_In::TEXT_DOMAIN ),
			'name' => esc_html__( 'Name', Opt_In::TEXT_DOMAIN ),
			'customize_module' => esc_html__( 'Customize Popup', Opt_In::TEXT_DOMAIN ),
			'rendering' => esc_html__( 'Rendering...', Opt_In::TEXT_DOMAIN ), //Unused
			'block_name' => esc_html__( 'Popup Trigger', Opt_In::TEXT_DOMAIN ),
			'block_description' => esc_html__( 'Embed the trigger button for a popup module.', Opt_In::TEXT_DOMAIN ),
			'block_more_description' => esc_html__( 'Note: the Trigger property of the Popup should be set to Click to embed the trigger button for the module.', Opt_In::TEXT_DOMAIN ),
		); 
	}

}

new Hustle_GHBlock_Popup_Trigger();
