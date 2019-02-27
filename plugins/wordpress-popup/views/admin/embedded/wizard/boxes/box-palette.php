<div id="wph-wizard-design-palette" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Colors Palette", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

		<label class="wpmudev-helper"><?php esc_attr_e( "Choose a pre-made palette for your Embed and further customize its appearance.", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "Select color palette", Opt_In::TEXT_DOMAIN ); ?></label>

		<select class="wpmudev-select" data-attribute="style">

			<option value="gray_slate" {{ ( 'gray_slate' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Gray Slate", Opt_In::TEXT_DOMAIN ); ?></option>
			<option value="coffee" {{ ( 'coffee' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Coffee", Opt_In::TEXT_DOMAIN ); ?></option>
			<option value="ectoplasm" {{ ( 'ectoplasm' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Ectoplasm", Opt_In::TEXT_DOMAIN ); ?></option>
			<option value="blue" {{ ( 'blue' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Blue", Opt_In::TEXT_DOMAIN ); ?></option>
			<option value="sunrise" {{ ( 'sunrise' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Sunrise", Opt_In::TEXT_DOMAIN ); ?></option>
			<option value="midnight" {{ ( 'midnight' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Midnight", Opt_In::TEXT_DOMAIN ); ?></option>

		</select>

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-aftercontent-custom_palette" class="toggle-checkbox" type="checkbox" data-attribute="customize_colors" {{_.checked(_.isTrue(customize_colors), true)}}>

				<label class="wpmudev-switch-design" for="wph-aftercontent-custom_palette" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-aftercontent-custom_palette"><?php esc_attr_e( "Customize colors", Opt_In::TEXT_DOMAIN ); ?></label>

		</div>

		<?php $this->render( "admin/commons/wizard/colors-palette", array() ); ?>

	</div>

</div><?php // #wph-wizard-design-palette ?>
