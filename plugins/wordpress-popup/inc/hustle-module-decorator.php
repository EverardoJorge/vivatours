<?php

/**
 * Class Hustle_Module_Decorator
 *
 * @property string $mail_service_label
 */
class Hustle_Module_Decorator extends Opt_In {

	private $_module;

	public function __construct( Hustle_Module_Model $module ) {
		$this->_module = $module;
	}

	/**
	 * Implements getter magic method
	 *
	 *
	 * @since 1.0.0
	 *
	 * @param $field
	 * @return mixed
	 */
	public function __get( $field ) {

		if ( method_exists( $this, 'get_' . $field ) ) {
			return $this->{ 'get_' . $field }();
		}

		if ( ! empty( $this->_module ) && isset( $this->_module->{$field} ) ) {
			return $this->_module->{$field};
		}

	}

	private function _get_layout_colors() {

		if ( ! $this->_module->design->colors->customize && $this->_module->design->colors->palette ) {
			return $this->get_palette( $this->_module->design->colors->palette );
		} else {
			return $this->_module->design->colors->to_array();
		}
	}

	public function get_module_styles( $module_type ) {

		$styles = '';

		switch ( $module_type ) {

			case 'popup':
				$prefix = '.wph-modal.module_id_' . $this->_module->module_id . ' ';
				$styles = $this->_get_common_styles( $prefix );
				break;

			case 'slidein':
				$prefix = '.wph-modal.module_id_' . $this->_module->module_id . ' ';
				$styles = $this->_get_common_styles( $prefix );
				break;

			case 'embedded':
				$prefix = '.module_id_' . $this->_module->module_id . ' ';
				$styles = $this->_get_common_styles( $prefix );
				break;

			case 'social_sharing':
				$styles = $this->_get_social_sharing_styles();
				break;

			default:
				$prefix = '.wph-modal.module_id_' . $this->_module->module_id . ' ';
				$styles = $this->_get_common_styles( $prefix );
				break;
		}

		return $styles;
	}

	private function _get_common_styles( $prefix ) {

		$styles            = '';
		$stylable_elements = $this->_get_popup_stylable_elements();
		$content           = $this->_module->get_content()->to_array();
		$design            = $this->_module->get_design()->to_array();
		$defaults          = $this->_module->get_design()->defaults;
		$layout_style      = $design['style'];
		$form_layout       = $design['form_layout'];

		// COMMON STYLES
		$colors = $design ? $design : $defaults;

		// Colors Palette: Basic
		$styles .= ' ' . $prefix . $stylable_elements['img_container'] . '{ background-color: ' . $colors['image_container_bg'] . '; }'; // Image container background
		
		/**
		 * Implement styles
		 */
		if ( isset( $design['style'] ) ) {
			$style = $this->pallets_ref( $design['style'] );
			$palettes = $this->get_palettes();
			if ( isset( $palettes[ $style ] ) ) {
				$colors = array_merge( $palettes[ $style ], $colors );
			}
		}
		// image_container_bg
		$styles .= ' ' . $prefix . $stylable_elements['img_container'] . '{ background-color: '. $colors['image_container_bg'] .'; }';

		// Colors Palette: Main Background
		if ( 'cabriolet' === $layout_style ) {
			$styles .= ' ' . $prefix . $stylable_elements['modal_body_cabriolet'] . '{ background-color: ' . $colors['main_bg_color'] . '; }';
		} else {
			$styles .= ' ' . $prefix . $stylable_elements['modal_body'] . '{ background-color: ' . $colors['main_bg_color'] . '; }';
		}

		// Colors Palette: Title Color
		if ( (int) $content['has_title'] && '' !== $content['title'] ) {
			$styles .= ' ' . $prefix . $stylable_elements['modal_title'] . '{ color: ' . $colors['title_color'] . '; }';
		}

		// Colors Palette: Subtitle Color
		if ( (int) $content['has_title'] && '' !== $content['sub_title'] ) {
			$styles .= ' ' . $prefix . $stylable_elements['modal_subtitle_color'] . '{ color: ' . $colors['subtitle_color'] . '; }';
		}

		// Colors Palette: CTA Button
		if ( (int) $content['show_cta'] ) {

			$styles .= ' ' . $prefix . $stylable_elements['optin_cta_button'] . '{'
				. ' color: ' . $colors['cta_button_static_color'] . ';'
				. ' background-color: ' . $colors['cta_button_static_bg'] . ';'
				. ( (int) $colors['border'] ? ' border-radius: ' . $colors['border_radius'] . 'px;' : '' )
				. ' }';
			$styles .= ' ' . $prefix . $stylable_elements['optin_cta_button'] . ':hover {'
				. ' color: ' . $colors['cta_button_hover_color'] . ';'
				. ' background-color: ' . $colors['cta_button_hover_bg'] . ';'
				. ' }';
		}

		// Colors Palette: GDPR Field
		if ( (int) $content['show_gdpr'] ) {

			if ( (int) $design['gdpr_border'] ) {

				$gdpr_border_style = $design['gdpr_border_weight'] . 'px '
					. $design['gdpr_border_type'] . ' '
					. $design['gdpr_border_color'];

				$styles .= ' ' . $prefix . $stylable_elements['gdpr_checkbox'] . '{'
					. ' border: ' . $gdpr_border_style . ';'
					. ' border-radius: ' . $design['gdpr_border_radius'] . 'px;'
					. ' }';
			}

			$styles .= ' ' . $prefix . $stylable_elements['gdpr_content'] . ' { color: ' . $colors['gdpr_content'] . '; }';

			$styles .= ' ' . $prefix . $stylable_elements['gdpr_checkbox'] . ' {'
				. ' background-color: ' . $colors['gdpr_chechbox_background_static'] . ';'
				. ' color: ' . $colors['gdpr_checkbox_icon'] . ';'
				. ' }';

			$styles .= ' ' . $prefix . $stylable_elements['gdpr_checkbox_active'] . ' { background-color: ' . $colors['gdpr_checkbox_background_active'] . ' }';
		}

		// Colors Palette: Opt-in Modules
		if ( (int) $content['use_email_collection'] ) {

			// Colors Palette: Basic
			$styles .= ' ' . $prefix . $stylable_elements['modal_success'] . '{ background-color: ' . $colors['main_bg_color'] . '; }'; // Success background
			$styles .= ' ' . $prefix . $stylable_elements['content_article'] . '{ color: ' . $colors['content_color'] . '; }'; // Content color
			$styles .= ' ' . $prefix . $stylable_elements['content_bq_article'] . '{ border-left-color: ' . $colors['link_static_color'] . '; }'; // Blockquote border color
			$styles .= ' ' . $prefix . $stylable_elements['optin_content_link'] . '{ color: ' . $colors['link_static_color'] . '; }'; // Content link
			$styles .= ' ' . $prefix . $stylable_elements['optin_content_link'] . ':hover { color: ' . $colors['link_hover_color'] . '; }'; // Content link (hover)

			if ( in_array( $form_layout, array( 'one', 'two' ), true ) ) {
				$styles .= ' ' . $prefix . $stylable_elements['footer'] . '{ background-color: ' . $colors['form_area_bg'] . '; }'; // Form area background
			} else {
				$styles .= ' ' . $prefix . $stylable_elements['optin_wrap'] . '{ background-color: ' . $colors['form_area_bg'] . '; }'; // Form area background
			}

			// Colors Palette: Opt-in Form
			$styles .= ' ' . $prefix . $stylable_elements['optin_input'] . '{
				background-color: ' . $colors['optin_input_static_bg'] . ';
				color: ' . $colors['optin_form_field_text_static_color'] . ';
			}'; // Input field
			$styles .= ' ' . $prefix . $stylable_elements['optin_input'] . ':hover {
				background-color: ' . $colors['optin_input_hover_bg'] . ';
				color: ' . $colors['optin_form_field_text_hover_color'] . ';
			}'; // Input field (hover)
			$styles .= ' ' . $prefix . $stylable_elements['optin_input_icon'] . '{ fill: ' . $colors['optin_input_icon'] . '; }'; // Input field icon color
			$styles .= ' ' . $prefix . $stylable_elements['optin_placeholder'] . '{ color: ' . $colors['optin_placeholder_color'] . '; }'; // Input placeholder color
			$styles .= ' ' . $prefix . $stylable_elements['optin_button'] . '{'
				. ' color: ' . $colors['optin_submit_button_static_color'] . ';'
				. ' background-color: ' . $colors['optin_submit_button_static_bg'] . ';'
				. ' }'; // Submit button
			$styles .= ' ' . $prefix . $stylable_elements['optin_button'] . ':hover {'
				. ' color: ' . $colors['optin_submit_button_hover_color'] . ';'
				. ' background-color: ' . $colors['optin_submit_button_hover_bg'] . ';'
				. ' }'; // Submit button (hover)
			$styles .= ' ' . $prefix . $stylable_elements['optin_submit_failure'] . ' {'
				. ' color: ' . $colors['optin_error_text_color'] . ';'
				. ' background: ' . $colors['optin_error_text_bg'] . ';'
				. ' }'; // Error message

			// Colors Palette: Mailchimp Extras
			$styles .= ' ' . $prefix . $stylable_elements['optin_checkbox'] . '{ background-color: ' . $colors['optin_check_radio_bg'] . '; }'; // Checkbox background
			$styles .= ' ' . $prefix . $stylable_elements['optin_checkbox_checked'] . '{ background-color: ' . $colors['optin_check_radio_bg'] . '; }'; // Checkbox background (active)
			$styles .= ' ' . $prefix . $stylable_elements['optin_checkbox_checked_selector'] . '{ color: ' . $colors['optin_check_radio_tick_color'] . '; }'; // Checkbox icon
			$styles .= ' ' . $prefix . $stylable_elements['optin_radio'] . '{ background-color: ' . $colors['optin_check_radio_bg'] . '; }'; // Radio background
			$styles .= ' ' . $prefix . $stylable_elements['optin_radio_checked'] . '{ background-color: ' . $colors['optin_check_radio_bg'] . '; }'; // Radio background (active)
			$styles .= ' ' . $prefix . $stylable_elements['optin_radio_checked_selector'] . '{ color: ' . $colors['optin_check_radio_tick_color'] . '; }'; // Radio icon

			// Colors Palette: Additional Styles
			$styles .= ' ' . $prefix . $stylable_elements['optin_success_tick'] . '{ fill: ' . $colors['optin_success_tick_color'] . '; }';
			$styles .= ' ' . $prefix . $stylable_elements['optin_success_content'] . '{ color: ' . $colors['optin_success_content_color'] . '; }';

			// Button(s) Border
			if ( (int) $design['button_border'] ) {

				$button_border_style = $design['button_border_weight'] . 'px '
					. $design['button_border_type'] . ' '
					. $design['button_border_color'];

				$styles .= ' ' . $prefix . $stylable_elements['optin_button'] . '{'
					. ' border: ' . $button_border_style . ';'
					. ' border-radius: ' . $design['button_border_radius'] . 'px;'
					. ' }';
			}

			// Form Field(s) Border
			if ( (int) $design['form_fields_border'] ) {

				$field_border_style = $design['form_fields_border_weight'] . 'px '
					. $design['form_fields_border_type'] . ' '
					. $design['form_fields_border_color'];

				$styles .= ' ' . $prefix . $stylable_elements['optin_input'] . '{'
					. ' border: ' . $field_border_style . ';'
					. ' border-radius: ' . $design['form_fields_border_radius'] . 'px;'
					. ' }';
			}

			// Colors Palette: Mailchimp Extras
			// These styles needs some review to make sure are pointing
			// to the right element or that are not deprecated.
			$styles .= ' ' . $prefix . $stylable_elements['optin_checkbox_selector'] . '{ color: ' . $colors['optin_check_radio_bg'] . '; }';
			$styles .= ' ' . $prefix . $stylable_elements['optin_radio_selector'] . '{ color: ' . $colors['optin_check_radio_bg'] . '; }';
			$styles .= ' ' . $prefix . $stylable_elements['optin_mc_group_title'] . '{ color: ' . $colors['optin_mailchimp_title_color'] . '; }';
			$styles .= ' ' . $prefix . $stylable_elements['optin_mc_group_labels'] . '{ color: ' . $colors['optin_mailchimp_labels_color'] . '; }';
		} else {

			// Colors Palette: Basic
			$styles .= ' ' . $prefix . $stylable_elements['content'] . '{ color: ' . $colors['content_color'] . '; }'; // Content color
			$styles .= ' ' . $prefix . $stylable_elements['content_bq'] . '{ border-left-color: ' . $colors['link_static_color'] . '; }'; // Blockquote border color
			$styles .= ' ' . $prefix . $stylable_elements['content_link'] . '{ color: ' . $colors['link_static_color'] . '; }';
			$styles .= ' ' . $prefix . $stylable_elements['content_link'] . ':hover { color: ' . $colors['link_hover_color'] . '; }';
			$styles .= ' ' . $prefix . $stylable_elements['content_link_article'] . '{ color: ' . $colors['link_static_color'] . '; }';
			$styles .= ' ' . $prefix . $stylable_elements['content_link_article'] . ':hover { color: ' . $colors['link_hover_color'] . '; }';
		}

		// Colors Palette: Additional Styles
		$styles .= ' ' . $prefix . $stylable_elements['overlay'] . '{ background-color: ' . $colors['overlay_bg'] . '; }'; // Pop-up overlay
		$styles .= ' ' . $prefix . $stylable_elements['close_button'] . '{ fill: ' . $colors['close_button_static_color'] . '; }'; // Close button
		$styles .= ' ' . $prefix . $stylable_elements['close_button'] . ':hover { fill: ' . $colors['close_button_hover_color'] . '; }'; // Close button (hover)
		$styles .= ' ' . $prefix . $stylable_elements['close_button'] . ':active { fill: ' . $colors['close_button_active_color'] . '; }'; // Close button (active)

		// Featured Image
		$horizontal_fit = '';
		$vertical_fit   = '';

		if ( 'contain' === $design['feature_image_fit'] || 'cover' === $design['feature_image_fit'] ) {

			if ( 'custom' === $design['feature_image_horizontal'] ) {
				$horizontal_fit = $design['feature_image_horizontal_px'] . 'px';
			} else {
				$horizontal_fit = $design['feature_image_horizontal'];
			}

			if ( 'custom' === $design['feature_image_vertical'] ) {
				$vertical_fit = $design['feature_image_vertical_px'] . 'px';
			} else {
				$vertical_fit = $design['feature_image_vertical'];
			}

			$styles .= ' ' . $prefix . $stylable_elements['feature_image'] . '{ object-position: ' . $horizontal_fit . ' ' . $vertical_fit . '; }';
		}

		// Module Border
		if ( (int) $design['border'] ) {

			$border_style = $design['border_weight'] . 'px '
				. $design['border_type'] . ' '
				. $design['border_color'];

			if ( 'cabriolet' === $layout_style ) {
				$styles .= ' ' . $prefix . $stylable_elements['modal_body_cabriolet'] . '{ border: ' . $border_style . '; border-radius: ' . $design['border_radius'] . 'px; }';
			} else {
				$styles .= ' ' . $prefix . $stylable_elements['modal_body'] . '{ border: ' . $border_style . '; border-radius: ' . $design['border_radius'] . 'px; }';
			}
		}

		// Module Shadow
		if ( (int) $design['drop_shadow'] ) {

			$box_shadow = $design['drop_shadow_x'] . 'px '
				. $design['drop_shadow_y'] . 'px '
				. $design['drop_shadow_blur'] . 'px '
				. $design['drop_shadow_spread'] . 'px '
				. $design['drop_shadow_color'];

			if ( 'cabriolet' === $layout_style ) {
				$styles .= ' ' . $prefix . $stylable_elements['modal_body_cabriolet'] . '{ box-shadow: ' . $box_shadow . '; }';
			} else {
				$styles .= ' ' . $prefix . $stylable_elements['modal_body'] . '{ box-shadow: ' . $box_shadow . '; }';
			}
		}

		if ( (bool) $design['customize_css'] ) {
			$styles .= Opt_In::prepare_css( $design['custom_css'], $prefix, false, true );
		}

		return $styles;
	}

	private function _get_social_sharing_styles() {

		$styles            = '';
		$prefix            = '.hustle-sshare-module-id-' . $this->_module->id . ' ';
		$content           = $this->_module->get_content()->to_array();
		$designs           = $this->_module->get_sshare_design()->to_array();
		$stylable_elements = $this->_get_sshare_stylable_elements();

		// floating background
		if ( $designs['floating_social_bg'] ) {

			$styles .= sprintf(
				$prefix . $stylable_elements['floating_social_bg'] . '{ background: %s; }',
				$designs['floating_social_bg']
			);
		}

		// counter text
		if ( $designs['floating_counter_color'] ) {

			$styles .= sprintf(
				$prefix . $stylable_elements['floating_counter_color'] . '{ color: %s; }',
				$designs['floating_counter_color']
			);
		}

		// customize color
		if ( (bool) $designs['customize_colors'] ) {

			// icon bg
			if ( 'rounded' === $designs['icon_style'] || 'squared' === $designs['icon_style'] ) {

				$styles .= sprintf(
					$prefix . $stylable_elements['icon_bg_color'] . '{ background-color: %s; }',
					$designs['icon_bg_color']
				);
			} elseif ( 'outline' === $designs['icon_style'] ) {

				$styles .= sprintf(
					$prefix . $stylable_elements['floating_counter_border'] . '{ border: 1px solid %s; }',
					$designs['icon_bg_color']
				);
			}

			// icon color
			if ( $designs['icon_color'] ) {

				$styles .= sprintf(
					$prefix . $stylable_elements['icon_color'] . '{ fill: %s; }',
					$designs['icon_color']
				);
			}

			// border
			if ( $designs['floating_counter_border'] && ( 'native' === $content['service_type'] || (int) $content['click_counter'] ) ) {

				$styles .= sprintf(
					$prefix . $stylable_elements['floating_counter_border'] . '{ border: 1px solid %s; }',
					$designs['icon_bg_color']
				);
			}
		}

		// drop shadow
		if ( (bool) $designs['drop_shadow'] ) {

			$box_shadow = '' .
				$designs['drop_shadow_x'] . 'px ' .
				$designs['drop_shadow_y'] . 'px ' .
				$designs['drop_shadow_blur'] . 'px ' .
				$designs['drop_shadow_spread'] . 'px ' .
				$designs['drop_shadow_color'];

			$styles .= sprintf( $prefix . $stylable_elements['floating_social_bg'] . '{ box-shadow: %s; }', $box_shadow );
		}

		/* WIDGET STYLES */

		// widget background
		if ( $designs['widget_bg_color'] ) {
			$styles .= sprintf( $prefix . $stylable_elements['widget_bg'] . '{ background: %s; }', $designs['widget_bg_color'] );
		}

		// widget counter text
		if ( $designs['widget_counter_color'] ) {
			$styles .= sprintf( $prefix . $stylable_elements['widget_counter_color'] . '{ color: %s; }', $designs['widget_counter_color'] );
		}

		// widget customize color
		if ( (bool) $designs['customize_widget_colors'] ) {

			// widget icon bg
			if ( 'rounded' === $designs['icon_style'] || 'squared' === $designs['icon_style'] ) {
				$styles .= sprintf( $prefix . $stylable_elements['widget_icon_bg_color'] . '{ background-color: %s; }', $designs['widget_icon_bg_color'] );
			} elseif ( 'outline' === $designs['icon_style'] ) {
				$styles .= sprintf( $prefix . $stylable_elements['widget_counter_border'] . '{ border: 1px solid %s; }', $designs['widget_icon_bg_color'] );
			}

			// widget icon color
			if ( $designs['widget_icon_color'] ) {
				$styles .= sprintf( $prefix . $stylable_elements['widget_icon_color'] . '{ fill: %s; }', $designs['widget_icon_color'] );
			}

			// widget border
			if ( $designs['widget_counter_border'] && ( 'native' === $content['service_type'] || (int) $content['click_counter'] ) ) {
				$styles .= sprintf( $prefix . $stylable_elements['widget_counter_border'] . '{ border: 1px solid %s; }', $designs['widget_icon_bg_color'] );
			}
		}

		// widget drop shadow
		if ( (bool) $designs['widget_drop_shadow'] ) {

			$box_shadow = '' .
				$designs['widget_drop_shadow_x'] . 'px ' .
				$designs['widget_drop_shadow_y'] . 'px ' .
				$designs['widget_drop_shadow_blur'] . 'px ' .
				$designs['widget_drop_shadow_spread'] . 'px ' .
				$designs['widget_drop_shadow_color'];

			$styles .= sprintf( $prefix . $stylable_elements['widget_bg'] . '{ box-shadow: %s; }', $box_shadow );
		}

		return $styles;
	}

	private function _get_popup_stylable_elements() {

		return array(
			'modal_body'                      => '.hustle-modal .hustle-modal-body',
			'modal_success'                   => '.hustle-modal .hustle-modal-success',
			'modal_body_cabriolet'            => '.hustle-modal .hustle-modal-body section',
			'modal_title'                     => '.hustle-modal .hustle-modal-title',
			'modal_subtitle_color'            => '.hustle-modal .hustle-modal-subtitle',
			'img_container'                   => '.hustle-modal .hustle-modal-image',
			'content'                         => '.hustle-modal .hustle-modal-message, .hustle-modal .hustle-gdpr-content',
			'content_article'                 => '.hustle-modal .hustle-modal-article, .hustle-modal .hustle-gdpr-content',
			'content_bq'                      => '.hustle-modal .hustle-modal-message blockquote',
			'content_bq_article'              => '.hustle-modal .hustle-modal-message blockquote',
			'content_link'                    => '.hustle-modal .hustle-modal-message a',
			'content_link_article'            => '.hustle-modal article a',
			'cta_button'                      => '.hustle-modal a.hustle-modal-cta',
			'optin_cta_button'                => '.hustle-modal a.hustle-modal-cta',
			'close_container'                 => '.hustle-modal .hustle-modal-close',
			'close_button'                    => '.hustle-modal .hustle-modal-close svg path',
			'overlay'                         => '.wpmudev-modal-mask',
			'feature_image'                   => '.hustle-modal-image img',
			'optin_content_link'              => '.hustle-modal article a:not(.hustle-modal-cta)',
			'optin_input'                     => '.hustle-modal .hustle-modal-optin_field input',
			'optin_input_icon'                => '.hustle-modal-optin_field label .hustle-modal-optin_icon .hustle-icon path',
			'optin_placeholder'               => '.hustle-modal-optin_form .hustle-modal-optin_field label .hustle-modal-optin_placeholder',
			'optin_button'                    => '.hustle-modal form .hustle-modal-optin_button button',
			'optin_success_content'           => '.hustle-modal .hustle-modal-success .hustle-modal-success_message, .hustle-modal .hustle-modal-success .hustle-modal-success_message *',
			'optin_success_tick'              => '.hustle-modal .hustle-modal-success .hustle-modal-success_icon .hustle-icon path',
			'optin_submit_failure'            => '.hustle-modal .wpoi-submit-failure',
			'optin_checkbox'                  => '.hustle-modal .hustle-modal-mc_checkbox input+label',
			'optin_checkbox_checked'          => '.hustle-modal .hustle-modal-mc_checkbox input:checked+label',
			'optin_radio'                     => '.hustle-modal .hustle-modal-mc_radio input+label',
			'optin_radio_checked'             => '.hustle-modal .hustle-modal-mc_radio input:checked+label',
			'optin_checkbox_selector'         => '.hustle-modal .hustle-modal-optin_form .hustle-modal-mc_groups .hustle-modal-mc_option .hustle-modal-mc_checkbox input+label:before',
			'optin_checkbox_checked_selector' => '.hustle-modal .hustle-modal-optin_form .hustle-modal-mc_groups .hustle-modal-mc_option .hustle-modal-mc_checkbox input:checked+label:before',
			'optin_radio_selector'            => '.hustle-modal .hustle-modal-optin_form .hustle-modal-mc_groups .hustle-modal-mc_option .hustle-modal-mc_radio input+label:before',
			'optin_radio_checked_selector'    => '.hustle-modal .hustle-modal-optin_form .hustle-modal-mc_groups .hustle-modal-mc_option .hustle-modal-mc_radio input:checked+label:before',
			'optin_mc_group_title'            => '.hustle-modal .hustle-modal-optin_form .hustle-modal-mc_title label',
			'optin_mc_group_labels'           => '.hustle-modal .hustle-modal-optin_form .hustle-modal-mc_groups .hustle-modal-mc_option .hustle-modal-mc_label label',
			'optin_wrap'                      => '.hustle-modal .hustle-modal-optin_wrap',
			'footer'                          => '.hustle-modal footer',
			'gdpr_content'                    => '.hustle-modal .hustle-gdpr-box .hustle-gdpr-content',
			'gdpr_checkbox'                   => '.hustle-modal .hustle-gdpr-box .hustle-gdpr-checkbox span',
			'gdpr_checkbox_active'            => '.hustle-modal .hustle-gdpr-box .hustle-gdpr-checkbox input:checked+span',
		);
	}

	private function _get_sshare_stylable_elements() {

		return array(
			'floating_social_bg'      => '.hustle-shares-floating',
			'icon_color'              => '.hustle-shares-floating .hustle-social-icon .hustle-icon-path',
			'icon_bg_color'           => '.hustle-shares-floating .hustle-social-icon .hustle-icon-container',
			'floating_counter_border' => '.hustle-shares-floating .hustle-social-icon',
			'floating_counter_color'  => '.hustle-shares-floating .hustle-social-icon .hustle-shares-counter',
			'widget_bg'               => '.hustle-shares-widget',
			'widget_icon_color'       => '.hustle-shares-widget .hustle-social-icon .hustle-icon-path',
			'widget_icon_bg_color'    => '.hustle-shares-widget .hustle-social-icon .hustle-icon-container',
			'widget_counter_border'   => '.hustle-shares-widget .hustle-social-icon',
			'widget_counter_color'    => '.hustle-shares-widget .hustle-social-icon .hustle-shares-counter',
		);
	}

	public function _str_replace_last( $search, $replace, $str ) {

		$pos = strrpos( $str, $search );

		if ( false !== $pos ) {
			$search_length = strlen( $search );
			$str           = substr_replace( $str, $replace, $pos, $search_length );
		}

		return $str;
	}

	private function get_titles( $ids, $type ) {

		$out = '';

		foreach ( (array) $ids as $index => $id ) {

			$title = '';
			$id    = (int) $id;

			switch ( $type ) {

				case 'post':
					$title = sprintf( '<a target="_blank" href="%s">%s</a>', get_the_permalink( $id ), get_the_title( $id ) );
					break;

				case 'tag':
					$tag   = get_tag( $id );
					$title = sprintf( '<a target="_blank" href="%s">%s</a>', get_tag_link( $id ), $tag->name );
					break;

				case 'cat':
					$title = sprintf( '<a target="_blank" href="%s">%s</a>', get_category_link( $id ), get_cat_name( $id ) );
					break;
			}

			if ( $index > 0 ) {

				if ( ( count( $ids ) - 1 ) === (int) $index ) {
					$out .= __( ' and ', Opt_In::TEXT_DOMAIN ) . $title;
				} else {
					$out .= ', ' . $title;
				}
			} else {
				$out .= $title;
			}
		}

		return $out;
	}

	private function _get_hosting_sidebars() {

		global $wp_registered_widgets, $wp_registered_sidebars;

		$sidebars_widgets = wp_get_sidebars_widgets();
		$widgets_settings = get_option( 'widget_inc_opt_widget' );

		$sidebars         = array();
		$hosting_sidebars = array();

		foreach ( (array) $sidebars_widgets as $sidebar_index => $widgets ) {

			foreach ( (array) $widgets as $key => $widget_id ) {

				$matches = preg_match( '/^' . Opt_In_Widget::WIDGET_ID . '\\-\\d+/', $widget_id );

				if ( $matches ) {

					$params = $wp_registered_widgets[ $widget_id ]['params'];

					if ( isset( $params[0], $params[0]['number'] ) ) {

						$sidebars[ $sidebar_index ] = $params[0]['number'];

						if ( $this->_module->id === $widgets_settings[ $sidebars[ $sidebar_index ] ]['optin_id'] ) {
							$hosting_sidebars[] = $wp_registered_sidebars[ $sidebar_index ]['name'];
						}
					}
				}
			}
		}

		return $hosting_sidebars;
	}

	/**
	 * Gets provider name from id/slug
	 *
	 * @param $slug
	 * @return bool | string
	 */
	public function get_service_name_from_id( $slug ) {
		$registered_providers = $this->get_providers(); 
		foreach( $registered_providers as $provider ) {
			if( $provider['slug'] === $slug ) {
				return $provider['title'];
			}
		}

		return false;
	}

	/**
	 * Returns provider's label and 'No Email Service' in case it's not set
	 *
	 * @return string
	 */
	public function get_mail_service_label() {

		$module_content       = $this->_module->get_content();
		$active_email_service = $module_content->active_email_service;

		$label = $this->get_service_name_from_id( $active_email_service );
		$label = ! $label ? ucfirst( $active_email_service ) : $label;

		// When no email service is used, use empty string.
		if ( empty( $active_email_service ) ) {
			$label = '';
		}

		if ( ! empty( $active_email_service ) && intval( $this->_module->test_mode ) ) {
			$label = __( 'Test Mode', Opt_In::TEXT_DOMAIN );
		}

		return $label;
	}

	/**
	 * Returns link to edit page on specific section
	 *
	 * @param $section
	 * @return string
	 */
	public function get_edit_url( $page, $section ) {

		if ( empty( $section ) ) {
			$url = admin_url( 'admin.php?page=' . $page . '&id=' . $this->_module->id );
		} else {
			$url = admin_url( 'admin.php?page=' . $page . '&id=' . $this->_module->id . '&section=' . $section );
		}

		return esc_url( $url );
	}

	/**
	 * Returns conditions labels for given type
	 *
	 * @param $type
	 * @param bool|true $return_array
	 * @return array|string
	 */
	public function get_condition_labels( $return_array = true ) {

		$settings = ( 'social_sharing' === $this->_module->module_type )
			? $this->_module->get_sshare_display_settings()->to_array()
			: $this->_module->get_display_settings()->to_array();

		$conditions = $this->_module->get_obj_conditions( $settings );
		$labels     = array();

		/**
		 * @var $condition Opt_In_Condition_Abstract
		 */
		foreach ( $conditions as $condition ) {

			$label = $condition->label();

			if ( ! empty( $label ) ) {
				$labels[] = $label;
			}
		}

		$labels = array() === $labels ? array( 'everywhere' => esc_html_e( 'Show everywhere', Opt_In::TEXT_DOMAIN ) ) : $labels;

		return $return_array ? $labels : implode( ', ', $labels );
	}
}
