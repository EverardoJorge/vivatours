<div id="wph-wizard-settings-additional" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Additional settings", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "After Embed is closed", Opt_In::TEXT_DOMAIN ); ?></label>

        <div class="wpmudev-box-gray">

            <select class="wpmudev-select" data-attribute="after_close" >
                <option value="no_show" {{ ( 'no_show' === after_close ) ? 'selected' : '' }} ><?php esc_attr_e( "No longer show this message on this post / page", Opt_In::TEXT_DOMAIN ); ?></option>
            </select>

            <label><?php esc_attr_e( "Expires (after expiry, user will see the Embed again)", Opt_In::TEXT_DOMAIN ); ?></label>

            <div class="wpmudev-fields-group">

                <input type="number" value="{{expiration}}" class="wpmudev-input_number" data-attribute="expiration" >

                <select class="wpmudev-select" data-attribute="expiration_unit" >
                    <option value="days" {{ ( 'days' === expiration_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "days", Opt_In::TEXT_DOMAIN ); ?></option>
                    <option value="weeks" {{ ( 'weeks' === expiration_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "weeks", Opt_In::TEXT_DOMAIN ); ?></option>
                    <option value="months" {{ ( 'months' === expiration_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "months", Opt_In::TEXT_DOMAIN ); ?></option>
                    <option value="years" {{ ( 'years' === expiration_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "years", Opt_In::TEXT_DOMAIN ); ?></option>
                </select>

            </div>

        </div>

        <div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-aftercontent-setting_scroll" class="toggle-checkbox" type="checkbox" data-attribute="allow_scroll_page" {{_.checked(_.isTrue(allow_scroll_page), true)}} >

				<label class="wpmudev-switch-design" for="wph-aftercontent-setting_scroll" aria-hidden="true"></label>

			</div>

			<div class="wpmudev-switch-labels">

                <label class="wpmudev-switch-label" for="wph-aftercontent-setting_scroll"><?php esc_attr_e( "Allow page to be scrolled while Embed is visible", Opt_In::TEXT_DOMAIN ); ?></label>

            </div>

		</div>

        <div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-aftercontent-setting_close" class="toggle-checkbox" type="checkbox" data-attribute="not_close_on_background_click" {{_.checked(_.isTrue(not_close_on_background_click), true)}} >

				<label class="wpmudev-switch-design" for="wph-aftercontent-setting_close" aria-hidden="true"></label>

			</div>

            <div class="wpmudev-switch-labels">

			    <label class="wpmudev-switch-label" for="wph-aftercontent-setting_close"><?php esc_attr_e( "Clicking on the background does not close Embed", Opt_In::TEXT_DOMAIN ); ?></label>

            </div>

		</div>

    </div>

</div><?php // #wph-wizard-settings-additional ?>
