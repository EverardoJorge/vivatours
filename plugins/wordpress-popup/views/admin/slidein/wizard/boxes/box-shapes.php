<div id="wph-wizard-design-shapes" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Shapes, borders, icons", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

	</div>

	<div class="wpmudev-box-right">

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-slidein-border" class="toggle-checkbox" type="checkbox" data-attribute="border" {{_.checked(_.isTrue(border), true)}} >

				<label class="wpmudev-switch-design" for="wph-slidein-border" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-slidein-border"><?php esc_attr_e( "Slide-in module border", Opt_In::TEXT_DOMAIN ); ?></label>

		</div><?php // .wpmudev-switch-labeled ?>

		<div id="wph-wizard-design-border-options" class="wpmudev-box-gray {{ ( _.isFalse(border) ) ? 'wpmudev-hidden' : 'wpmudev-show' }}">

			<div class="wpmudev-row">

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Radius", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{border_radius}}" data-attribute="border_radius" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Weight", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{border_weight}}" data-attribute="border_weight" class="wpmudev-input_number">

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

					<div class="wpmudev-picker"><input id="slidein_modal_border" class="wpmudev-color_picker" type="text"  value="{{border_color}}" data-attribute="border_color" /></div>

				</div>

			</div>

		</div><?php // .wpmudev-box-gray ?>

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-slidein-border_fields" class="toggle-checkbox" type="checkbox" data-attribute="form_fields_border" {{_.checked(_.isTrue(form_fields_border), true)}}>

				<label class="wpmudev-switch-design" for="wph-slidein-border_fields" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-slidein-border_fields"><?php esc_attr_e( "Form fields border", Opt_In::TEXT_DOMAIN ); ?></label>

		</div><?php // .wpmudev-switch-labeled ?>

		<div id="wph-wizard-design-form-fields-border-options" class="wpmudev-box-gray {{ ( _.isFalse(form_fields_border) ) ? 'wpmudev-hidden' : 'wpmudev-show' }}">

			<div class="wpmudev-row">

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Radius", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{form_fields_border_radius}}" data-attribute="form_fields_border_radius" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Weight", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{form_fields_border_weight}}" data-attribute="form_fields_border_weight" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Type", Opt_In::TEXT_DOMAIN ); ?></label>

					<select class="wpmudev-select" data-attribute="form_fields_border_type" >
						<option value="solid" {{ ( 'solid' === form_fields_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Solid", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="dotted" {{ ( 'dotted' === form_fields_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Dotted", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="dashed" {{ ( 'dashed' === form_fields_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Dashed", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="double" {{ ( 'double' === form_fields_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Double", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="none" {{ ( 'none' === form_fields_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "None", Opt_In::TEXT_DOMAIN ); ?></option>
					</select>

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Border color", Opt_In::TEXT_DOMAIN ); ?></label>

					<div class="wpmudev-picker"><input id="form_fields_border_color" class="wpmudev-color_picker" type="text"  value="{{form_fields_border_color}}" data-attribute="form_fields_border_color" /></div>

				</div>

			</div>

		</div><?php // .wpmudev-box-gray ?>

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-slidein-border_button" class="toggle-checkbox" type="checkbox" data-attribute="button_border" {{_.checked(_.isTrue(button_border), true)}}>

				<label class="wpmudev-switch-design" for="wph-slidein-border_button" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-slidein-border_button"><?php esc_attr_e( "Button border", Opt_In::TEXT_DOMAIN ); ?></label>

		</div><?php // .wpmudev-switch-labeled ?>

        <div id="wph-wizard-design-button-border-options" class="wpmudev-box-gray {{ ( _.isFalse(button_border) ) ? 'wpmudev-hidden' : 'wpmudev-show' }}">

			<div class="wpmudev-row">

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Radius", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{button_border_radius}}" data-attribute="button_border_radius" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Weight", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{button_border_weight}}" data-attribute="button_border_weight" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Type", Opt_In::TEXT_DOMAIN ); ?></label>

					<select class="wpmudev-select" data-attribute="button_border_type" >
						<option value="solid" {{ ( 'solid' === button_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Solid", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="dotted" {{ ( 'dotted' === button_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Dotted", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="dashed" {{ ( 'dashed' === button_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Dashed", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="double" {{ ( 'double' === button_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Double", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="none" {{ ( 'none' === button_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "None", Opt_In::TEXT_DOMAIN ); ?></option>
					</select>

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Border color", Opt_In::TEXT_DOMAIN ); ?></label>

                    <div class="wpmudev-picker"><input id="slidein_modal_button_border" class="wpmudev-color_picker" type="text"  value="{{button_border_color}}" data-attribute="button_border_color" data-alpha="true" /></div>

				</div>

			</div>

		</div><?php // .wpmudev-box-gray ?>

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-popup-gdpr_border" class="toggle-checkbox" type="checkbox" data-attribute="gdpr_border" {{_.checked(_.isTrue(gdpr_border), true)}}>

				<label class="wpmudev-switch-design" for="wph-popup-gdpr_border" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-popup-gdpr_border"><?php esc_attr_e( "GDPR checkbox border", Opt_In::TEXT_DOMAIN ); ?></label>

		</div><?php // .wpmudev-switch-labeled ?>

        <div id="wph-wizard-design-gdpr-border-options" class="wpmudev-box-gray {{ ( _.isFalse(gdpr_border) ) ? 'wpmudev-hidden' : 'wpmudev-show' }}">

			<div class="wpmudev-row">

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Radius", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{gdpr_border_radius}}" data-attribute="gdpr_border_radius" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Weight", Opt_In::TEXT_DOMAIN ); ?></label>

					<input type="number" value="{{gdpr_border_weight}}" data-attribute="gdpr_border_weight" class="wpmudev-input_number">

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Type", Opt_In::TEXT_DOMAIN ); ?></label>

                    <select class="wpmudev-select" data-attribute="gdpr_border_type" >
						<option value="solid" {{ ( 'solid' === gdpr_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Solid", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="dotted" {{ ( 'dotted' === gdpr_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Dotted", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="dashed" {{ ( 'dashed' === gdpr_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Dashed", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="double" {{ ( 'double' === gdpr_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "Double", Opt_In::TEXT_DOMAIN ); ?></option>
						<option value="none" {{ ( 'none' === gdpr_border_type ) ? 'selected' : '' }} ><?php esc_attr_e( "None", Opt_In::TEXT_DOMAIN ); ?></option>
					</select>

				</div>

				<div class="wpmudev-col">

					<label><?php esc_attr_e( "Border color", Opt_In::TEXT_DOMAIN ); ?></label>

                    <div class="wpmudev-picker"><input id="popup_modal_gdpr_border" class="wpmudev-color_picker" type="text"  value="{{gdpr_border_color}}" data-attribute="gdpr_border_color" data-alpha="true" /></div>

				</div>

			</div>

		</div><?php // .wpmudev-box-gray ?>

		<label><?php esc_attr_e( "Form fields icon", Opt_In::TEXT_DOMAIN ); ?></label>

		<div class="wpmudev-tabs">

            <ul id="wpmudev-form-fields-icon" class="wpmudev-tabs-menu wpmudev-tabs-menu_full wpmudev-form-fields-icon-options">

				<li class="wpmudev-tabs-menu_item{{ ( 'none' === form_fields_icon ) ? ' current' : '' }}">
                    <input type="checkbox" value="none">
                    <label><?php esc_attr_e( "No icon", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

				<li class="wpmudev-tabs-menu_item{{ ( 'static' === form_fields_icon ) ? ' current' : '' }}">
                    <input type="checkbox" value="static">
                    <label><?php esc_attr_e( "Static icon", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

				<li class="wpmudev-tabs-menu_item{{ ( 'animated' === form_fields_icon ) ? ' current' : '' }}">
                    <input type="checkbox" value="animated">
                    <label><?php esc_attr_e( "Animated icon", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

            </ul>

        </div>

		<label><?php esc_attr_e( "Form fields proximity", Opt_In::TEXT_DOMAIN ); ?></label>

		<div class="wpmudev-tabs">

            <ul id="wpmudev-form-fields-proximity" class="wpmudev-tabs-menu wpmudev-tabs-menu_full wpmudev-form-fields-proximity-options">

				<li class="wpmudev-tabs-menu_item{{ ( 'separated' === form_fields_proximity ) ? ' current' : '' }}">
                    <input type="checkbox" value="separated">
                    <label><?php esc_attr_e( "Separated form fields", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

				<li class="wpmudev-tabs-menu_item{{ ( 'joined' === form_fields_proximity ) ? ' current' : '' }}">
                    <input type="checkbox" value="joined">
                    <label><?php esc_attr_e( "Joined form fields", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

            </ul>

        </div>

	</div>

</div><?php // #wph-wizard-design-shape ?>
