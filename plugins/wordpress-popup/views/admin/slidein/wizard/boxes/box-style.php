<div id="wph-wizard-design-style" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Style & Colors", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

		<label class="wpmudev-helper"><?php esc_attr_e( "Choose a pre-made style for your Slide-in and further customize its appearance.", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "Select a style to use:", Opt_In::TEXT_DOMAIN ); ?></label>

		<select class="wpmudev-select" data-attribute="style">

			<option value="simple" {{ ( 'simple' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Simple", Opt_In::TEXT_DOMAIN ); ?></option>
			<option value="minimal" {{ ( 'minimal' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Minimal", Opt_In::TEXT_DOMAIN ); ?></option>
			<option value="cabriolet" {{ ( 'cabriolet' === style ) ? 'selected' : '' }} ><?php esc_attr_e( "Cabriolet", Opt_In::TEXT_DOMAIN ); ?></option>

		</select>

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-slidein-style_colors" class="toggle-checkbox" type="checkbox" data-attribute="customize_colors" {{_.checked(_.isTrue(customize_colors), true)}}>

				<label class="wpmudev-switch-design" for="wph-slidein-style_colors" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-slidein-style_colors"><?php esc_attr_e( "Customize colors", Opt_In::TEXT_DOMAIN ); ?></label>

		</div>

		<?php $this->render( "admin/commons/wizard/colors-style", array() ); ?>

	</div>

</div><?php // #wph-wizard-design-style ?>
