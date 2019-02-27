<div id="wph-modal-styles-palette" class="wpmudev-box-gray {{ ( _.isTrue(customize_colors) ) ? 'wpmudev-show' : 'wpmudev-hidden' }}">

	<h5><?php esc_attr_e( 'Basic', Opt_In::TEXT_DOMAIN ); ?></h5>

	<div class="wpmudev-row" style="z-index: 5;">

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 3;">

			<label><?php esc_attr_e( 'Main background', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-picker"><input id="popup_main_background" class="wpmudev-color_picker" type="text"  value="{{main_bg_color}}" data-attribute="main_bg_color" data-alpha="true" /></div>

		</div>

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 2;">

			<label><?php esc_attr_e( 'Title color', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-picker"><input id="popup_title_color" class="wpmudev-color_picker" type="text"  value="{{title_color}}" data-attribute="title_color" data-alpha="true" /></div>

		</div>

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 1;">

			<label><?php esc_attr_e( 'Subtitle color', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-picker"><input id="popup_subtitle_color" class="wpmudev-color_picker" type="text"  value="{{subtitle_color}}" data-attribute="subtitle_color" data-alpha="true" /></div>

		</div>

	</div>

	<div class="wpmudev-row" style="z-index: 4;">

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 3;">

			<label><?php esc_attr_e( 'Image container BG', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-picker"><input id="popup_image_background" class="wpmudev-color_picker" type="text"  value="{{image_container_bg}}" data-attribute="image_container_bg" data-alpha="true" /></div>

		</div>

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 2;">

			<label><?php esc_attr_e( 'Content color', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-picker"><input id="popup_content_color" class="wpmudev-color_picker" type="text"  value="{{content_color}}" data-attribute="content_color" data-alpha="true" /></div>

		</div>

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 1;">

			<label><?php esc_attr_e( 'Link color', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-pickers">
				<div class="wpmudev-picker"><input id="popup_link_color" class="wpmudev-color_picker" type="text"  value="{{link_static_color}}" data-attribute="link_static_color" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_link_color_hover" class="wpmudev-color_picker" type="text"  value="{{link_hover_color}}" data-attribute="link_hover_color" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_link_color_focus" class="wpmudev-color_picker" type="text"  value="{{link_active_color}}" data-attribute="link_active_color" data-alpha="true" /></div>
			</div>

		</div>

	</div>

	<h5><?php esc_attr_e( 'Call To Action', Opt_In::TEXT_DOMAIN ); ?></h5>

	<div class="wpmudev-row" style="z-index: 3;">

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 2;">

			<label><?php esc_attr_e( 'Button BG', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-pickers">
				<div class="wpmudev-picker"><input id="popup_cta_backgrounds" class="wpmudev-color_picker" type="text"  value="{{cta_button_static_bg}}" data-attribute="cta_button_static_bg" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_cta_backgrounds_hover" class="wpmudev-color_picker" type="text"  value="{{cta_button_hover_bg}}" data-attribute="cta_button_hover_bg" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_cta_backgrounds_focus" class="wpmudev-color_picker" type="text"  value="{{cta_button_active_bg}}" data-attribute="cta_button_active_bg" data-alpha="true" /></div>
			</div>

		</div>

		<div class="wpmudev-col col-12 col-xs-8 col-sm-12 col-lg-8" style="z-index: 1;">

			<label><?php esc_attr_e( 'Button color', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-pickers">
				<div class="wpmudev-picker"><input id="popup_cta_color" class="wpmudev-color_picker" type="text"  value="{{cta_button_static_color}}" data-attribute="cta_button_static_color" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_cta_color_hover" class="wpmudev-color_picker" type="text"  value="{{cta_button_hover_color}}" data-attribute="cta_button_hover_color" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_cta_color_focus" class="wpmudev-color_picker" type="text"  value="{{cta_button_active_color}}" data-attribute="cta_button_active_color" data-alpha="true" /></div>
			</div>

		</div>

	</div>

	<h5><?php esc_attr_e( 'GDPR Field', Opt_In::TEXT_DOMAIN ); ?></h5>

	<div class="wpmudev-row" style="z-index: 2;">

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 3;">

			<label><?php esc_attr_e( 'GDPR Content', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-picker"><input id="popup_gdpr_content" class="wpmudev-color_picker" type="text"  value="{{ gdpr_content }}" data-attribute="gdpr_content" data-alpha="true" /></div>

		</div>

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 2;">

			<label><?php esc_attr_e( 'Checkbox BG', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-pickers">
				<div class="wpmudev-picker"><input id="popup_gdpr_chechbox_background_static" class="wpmudev-color_picker" type="text"  value="{{ gdpr_chechbox_background_static }}" data-attribute="gdpr_chechbox_background_static" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_gdpr_checkbox_background_active" class="wpmudev-color_picker" type="text"  value="{{ gdpr_checkbox_background_active }}" data-attribute="gdpr_checkbox_background_active" data-alpha="true" /></div>
			</div>

		</div>

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 1;">

			<label><?php esc_attr_e( 'Checkbox Icon', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-picker"><input id="popup_gdpr_checkbox_icon" class="wpmudev-color_picker" type="text"  value="{{ gdpr_checkbox_icon }}" data-attribute="gdpr_checkbox_icon" data-alpha="true" /></div>

		</div>

	</div>

	<h5><?php esc_attr_e( 'Additional Styles', Opt_In::TEXT_DOMAIN ); ?></h5>

	<div class="wpmudev-row" style="z-index: 1;">

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 2;">

			<label><?php esc_attr_e( 'Close (x) btn color', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-pickers">
				<div class="wpmudev-picker"><input id="popup_close_color" class="wpmudev-color_picker" type="text"  value="{{close_button_static_color}}" data-attribute="close_button_static_color" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_close_color_hover" class="wpmudev-color_picker" type="text"  value="{{close_button_hover_color}}" data-attribute="close_button_hover_color" data-alpha="true" /></div>
				<div class="wpmudev-picker"><input id="popup_close_color_focus" class="wpmudev-color_picker" type="text"  value="{{close_button_active_color}}" data-attribute="close_button_active_color" data-alpha="true" /></div>
			</div>

		</div>

		<div class="wpmudev-col col-12 col-xs-4 col-sm-12 col-lg-4" style="z-index: 1;">

			<label><?php esc_attr_e( 'Pop-up overlay', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-picker"><input id="popup_overlay_color" class="wpmudev-color_picker" type="text"  value="{{overlay_bg}}" data-attribute="overlay_bg" data-alpha="true" /></div>

		</div>

	</div>

</div><?php // #wph-modal-palette ?>
