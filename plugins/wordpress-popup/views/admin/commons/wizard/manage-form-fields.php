<div id="wph-edit-form-modal" class="wpmudev-modal">

    <div class="wpmudev-modal-mask" aria-hidden="true"></div>

    <div class="wpmudev-box-modal">

    </div>

</div>

<script id="wpmudev-hustle-modal-manage-form-fields-tpl" type="text/template">

    <div class="wpmudev-box-head">

        <div class="wpmudev-box-reset">

            <h2><?php esc_attr_e( "Edit Form Fields", Opt_In::TEXT_DOMAIN ); ?></h2>

            <a id="wph-new-form-field" href="" class="wpmudev-button wpmudev-button-sm wpmudev-button-ghost"><?php esc_attr_e( "Add New Field", Opt_In::TEXT_DOMAIN ); ?></a>

        </div>

        <?php $this->render("general/icons/icon-close" ); ?>

    </div>

    <div class="wpmudev-box-body">

        <form action="" id="wph-optin-form-fields-form">

            <div class="wpmudev-table-fields">

                <div class="wpmudev-table-head">

                    <div class="wpmudev-table-head-item wpmudev-head-item-label"><?php esc_attr_e( "Label", Opt_In::TEXT_DOMAIN ); ?></div>
                    <div class="wpmudev-table-head-item wpmudev-head-item-name"><?php esc_attr_e( "Name", Opt_In::TEXT_DOMAIN ); ?></div>
                    <div class="wpmudev-table-head-item wpmudev-head-item-type"><?php esc_attr_e( "Type", Opt_In::TEXT_DOMAIN ); ?></div>
                    <div class="wpmudev-table-head-item wpmudev-head-item-required"><span class="wpdui-fi wpdui-fi-asterisk"></span></div>
                    <div class="wpmudev-table-head-item wpmudev-head-item-placeholder"><?php esc_attr_e( "Placeholder", Opt_In::TEXT_DOMAIN ); ?></div>

                </div>

                <div class="wpmudev-table-body">

                    <?php // will be replaced with actual fields content ?>

                </div>

            </div>

        </form>

    </div>

    <div class="wpmudev-box-footer">

        <a href="" id="wph-cancel-edit-form" class="wpmudev-button wpmudev-button-ghost"><?php esc_attr_e( "Cancel", Opt_In::TEXT_DOMAIN ); ?></a>

        <a href="" id="wph-save-edit-form" class="wpmudev-button wpmudev-button-blue"><?php esc_attr_e( "Save Form", Opt_In::TEXT_DOMAIN ); ?></a>

    </div>

</script>

<script id="wpmudev-hustle-modal-add-form-fields-tpl" type="text/template">

	<#
		var field_label = ( 'undefined' !== typeof field.label ) ? field.label : '<?php esc_attr_e( 'Field Label', Opt_In::TEXT_DOMAIN ); ?>',
			field_name = ( 'undefined' !== typeof field.name ) ? field.name : '<?php esc_attr_e( 'Field Name', Opt_In::TEXT_DOMAIN ); ?>',
			field_type = ( 'undefined' !== typeof field.type ) ? field.type : '<?php esc_attr_e( 'Field Type', Opt_In::TEXT_DOMAIN ); ?>',
			field_placeholder = ( 'undefined' !== typeof field.placeholder ) ? field.placeholder : '<?php esc_attr_e( 'Field Placeholder', Opt_In::TEXT_DOMAIN ); ?>',
			field_delete = ( 'undefined' !== typeof field.delete ) ? field.delete : true;
	#>

	<div class="wph-field-row wpmudev-table-body-row {{ ( _.isTrue(new_field) ) ? 'wpmudev-open' : 'wpmudev-close' }}" data-id="{{field_name}}">

        <div class="wpmudev-table-body-preview">

            <div class="wpmudev-table-preview-item wpmudev-preview-item-drag"><?php $this->render( "general/icons/icon-drag" ); ?></div>

            <div class="wpmudev-table-preview-item wpmudev-preview-item-label">{{ field_label }}</div>

            <div class="wpmudev-table-preview-item wpmudev-preview-item-name">{{ field_name }}</div>

            <div class="wpmudev-table-preview-item wpmudev-preview-item-type">{{field_type}}</div>

            <div class="wpmudev-table-preview-item wpmudev-preview-item-required wph-form-field-required-">

				<# if ( 'undefined' !== typeof field.required && _.isTrue( field.required ) || 'recaptcha' === field_type ) { #>
					<span class="wpdui-fi wpdui-fi-check"></span>
				<# } #>

			</div>

            <div class="wpmudev-table-preview-item wpmudev-preview-item-placeholder">{{ field_placeholder }}</div>

            <div class="wpmudev-table-preview-item wpmudev-preview-item-manage"><?php $this->render("general/icons/icon-plus" ); ?></div>

        </div>

        <div class="wpmudev-table-body-content">
			<# var recaptcha_class = 'recaptcha' === field_type ? ' wpmudev-hidden' : ''; #>

            <div class="wpmudev-row{{ recaptcha_class }}">

                <div class="wpmudev-col col-12 col-sm-6">

                    <label><?php esc_attr_e('Field label', Opt_In::TEXT_DOMAIN); ?></label>

                    <input type="text" name="label" placeholder="<?php esc_attr_e('Type label...', Opt_In::TEXT_DOMAIN); ?>" value="{{field_label}}" class="wpmudev-input_text">

                </div>

                <div class="wpmudev-col col-12 col-sm-6">

                    <label><?php esc_attr_e('Field name', Opt_In::TEXT_DOMAIN); ?></label>

                    <input type="text" name="name" placeholder="<?php esc_attr_e('Type name...', Opt_In::TEXT_DOMAIN); ?>" value="{{field_name}}" class="wpmudev-input_text" {{ _.isFalse( field_delete ) ? 'disabled="disabled"' : '' }}>

                </div>

            </div>

            <div class="wpmudev-row">

                <div class="wpmudev-col col-12 col-sm-6">

                    <label><?php esc_attr_e('Field type', Opt_In::TEXT_DOMAIN); ?></label>

                    <select class="wpmudev-select" name="type" {{ _.isFalse( field_delete ) ? 'disabled="disabled"' : '' }}>

                        <option><?php esc_attr_e( "Choose field type", Opt_In::TEXT_DOMAIN ); ?></opion>
                        <option value="name" {{ ( 'name' === field_type ) ? 'selected="selected"' : '' }}><?php esc_attr_e( "Name", Opt_In::TEXT_DOMAIN ); ?></option>
                        <option value="address" {{ ( 'address' === field_type ) ? 'selected="selected"' : '' }}><?php esc_attr_e( "Address", Opt_In::TEXT_DOMAIN ); ?></option>
                        <option value="phone" {{ ( 'phone' === field_type ) ? 'selected="selected"' : '' }}><?php esc_attr_e( "Phone", Opt_In::TEXT_DOMAIN ); ?></option>
                        <option value="text" {{ ( 'text' === field_type ) ? 'selected="selected"' : '' }}><?php esc_attr_e( "Text", Opt_In::TEXT_DOMAIN ); ?></option>
                        <option value="number" {{ ( 'number' === field_type ) ? 'selected="selected"' : '' }} ><?php esc_attr_e( "Number", Opt_In::TEXT_DOMAIN ); ?></option>
                        <option value="email" {{ ( 'email' === field_type ) ? 'selected="selected"' : '' }} ><?php esc_attr_e( "Email", Opt_In::TEXT_DOMAIN ); ?></option>
                        <option value="url" {{ ( 'url' === field_type ) ? 'selected="selected"' : '' }} ><?php esc_attr_e( "URL", Opt_In::TEXT_DOMAIN ); ?></option>
                        <?php if ( $recaptcha_enabled ) { ?>
							<option value="recaptcha" {{ ( 'recaptcha' === field_type ) ? 'selected="selected"' : '' }} ><?php esc_attr_e( "reCaptcha", Opt_In::TEXT_DOMAIN ); ?></option>
						<?php } ?>
						<# if ( 'submit' === field_type ) { #>
                            <option value="submit" selected="selected" ><?php esc_attr_e( "Button", Opt_In::TEXT_DOMAIN ); ?></option>
                        <# } #>
                    </select>

                </div>

                <div class="wpmudev-col col-12 col-sm-6{{ recaptcha_class }}">

                    <label><?php esc_attr_e('Field placeholder', Opt_In::TEXT_DOMAIN); ?></label>

                    <input type="text" name="placeholder" placeholder="<?php esc_attr_e('Type placeholder...', Opt_In::TEXT_DOMAIN); ?>" value="{{field_placeholder}}" class="wpmudev-input_text">

                </div>

            </div>

            <div class="wpmudev-row wph-form-field-delete-edit-">

                <# if ( _.isTrue( field_delete ) ) { #>

                    <div class="wpmudev-col col-12 col-sm-6">

                        <div class="wpmudev-switch-labeled{{ recaptcha_class }}">

                            <div class="wpmudev-switch">

                                <input id="wph-field-{{field_name}}" class="toggle-checkbox wph-field-edit-required-{{field_name}}" name="required" type="checkbox" {{ _.checked( ( 'undefined' !== typeof field.required && _.isTrue( field.required ) ), true ) }}>

                                <label class="wpmudev-switch-design" for="wph-field-{{field_name}}"></label>

                            </div>

                            <label class="wpmudev-switch-label" for="wph-field-{{field_name}}">This field is required</label>

                        </div>

                    </div>

                    <div class="wpmudev-col col-12 col-sm-6">

                        <input type="hidden" name="delete" class="wph-field-edit-delete-{{field_name}}" value="true" />

                        <a href="#" data-id="wph-field-{{field_name}}" class="wpmudev-icon-delete" aria-hidden="true"><?php $this->render("general/icons/icon-delete"); ?><span><?php esc_attr_e( "Delete field", Opt_In::TEXT_DOMAIN ); ?></span></a>

                        <a href="#" data-id="wph-field-{{field_name}}" class="wpmudev-screen-reader-text"><?php esc_attr_e( "Delete field", Opt_In::TEXT_DOMAIN ); ?></a>

                    </div>

                <# } else { #>

                    <input type="hidden" name="required" value="true" />

                    <input type="hidden" name="delete" value="false" />

				<# } #>

            </div>

        </div>

    </div>

</script>
