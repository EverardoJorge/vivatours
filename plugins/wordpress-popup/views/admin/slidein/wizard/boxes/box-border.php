<div id="wph-wizard-design-border" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Border", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

	</div>

	<div class="wpmudev-box-right">

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-slidein-border" class="toggle-checkbox" type="checkbox" data-attribute="border" {{_.checked(_.isTrue(border), true)}} >

				<label class="wpmudev-switch-design" for="wph-slidein-border" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-slidein-border"><?php esc_attr_e( "Show border", Opt_In::TEXT_DOMAIN ); ?></label>

		</div>

		<div id="wph-wizard-design-border-options" class="wpmudev-box-gray {{ ( _.isFalse(border) ) ? 'wpmudev-hidden' : 'wpmudev-show' }}">

			<div class="wpmudev-row">

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Radius", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" data-attribute="border_radius" value="{{border_radius}}" class="wpmudev-input_number" min="0" >

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Weight", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" data-attribute="border_weight" value="{{border_weight}}" class="wpmudev-input_number" min="0" >

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Type", Opt_In::TEXT_DOMAIN ); ?></label>

					<select class="wpmudev-select" data-attribute="border_type" >
						<option value="solid" {{ ( 'solid' === border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Solid", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="dotted" {{ ( 'dotted' === border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Dotted", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="dashed" {{ ( 'dashed' === border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Dashed", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="double" {{ ( 'double' === border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Double", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="none" {{ ( 'none' === border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "None", Opt_In::TEXT_DOMAIN ); ?></option>
					</select>


				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Border color", Opt_In::TEXT_DOMAIN ); ?></label>

					<div class="wpmudev-picker"><input id="slidein_modal_border" class="wpmudev-color_picker" type="text"  value="{{border_color}}" data-attribute="border_color" data-alpha="true" /></div>

				</div>

			</div>

		</div>

	</div>

</div><?php // #wph-wizard-design-border ?>
