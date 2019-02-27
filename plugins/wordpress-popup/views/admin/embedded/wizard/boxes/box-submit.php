<div id="wph-wizard-settings-submit" class="wpmudev-box-content last">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Form submit behavior", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

        <label class="wpmudev-info"><?php esc_attr_e( "If your embed contains a form, you can change the on submit behavior here.", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "After an external form is submitted", Opt_In::TEXT_DOMAIN ); ?></label>

        <select class="wpmudev-select" data-attribute="on_submit" >
            <option value="close" {{ ( 'close' === on_submit ) ? 'selected' : '' }} ><?php esc_attr_e( "Close the embed", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="redirect" {{ ( 'redirect' === on_submit ) ? 'selected' : '' }} ><?php esc_attr_e( "Re-direct to form target URL", Opt_In::TEXT_DOMAIN ); ?></option>
			<option value="nothing" {{ ( 'nothing' === on_submit ) ? 'selected' : '' }} ><?php esc_attr_e( "Do nothing (use for Ajax Forms)", Opt_In::TEXT_DOMAIN ); ?></option>
        </select>

    </div>

</div><?php // #wph-wizard-settings-submit ?>
