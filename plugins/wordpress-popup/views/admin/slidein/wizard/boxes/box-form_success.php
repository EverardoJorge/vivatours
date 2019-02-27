<div id="wph-wizard-content-form_success" class="wpmudev-box-content last">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Success message closing", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

		<label class="wpmudev-helper"><?php esc_attr_e( "Choose if you want user to close success message, or close it automaticaly after a certain amount of time.", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-slidein-success_message_closing" class="toggle-checkbox" type="checkbox" data-attribute="auto_close_success_message" {{_.checked(_.isTrue(auto_close_success_message), true)}} >

				<label class="wpmudev-switch-design" for="wph-slidein-success_message_closing" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-slidein-success_message_closing"><?php esc_attr_e( "Automatically close success message", Opt_In::TEXT_DOMAIN ); ?></label>

		</div>

        <div id="wph-wizard-content-form_success_options" class="wpmudev-box-gray {{ ( _.isFalse(auto_close_success_message) ) ? 'wpmudev-hidden' : 'wpmudev-show' }}">

            <label><?php esc_attr_e( "Automatically close message after", Opt_In::TEXT_DOMAIN ); ?></label>

            <div class="wpmudev-fields-group">

                <input type="number" data-attribute="auto_close_time" value="{{auto_close_time}}" class="wpmudev-input_number" min="0">

                <select class="wpmudev-select" data-attribute="auto_close_unit">
                    <option value="seconds" {{ ( 'seconds' === auto_close_unit ) ? 'selected' : '' }}>Seconds</option>
                    <option value="minutes" {{ ( 'minutes' === auto_close_unit ) ? 'selected' : '' }}>Minutes</option>
                </select>

            </div>

        </div>

		<br>

		<div id="wph-wizard-content-after_subscription_options">

			<label><?php esc_attr_e( "After Hustle's form is submitted and opening this page again", Opt_In::TEXT_DOMAIN ); ?></label>
			<select class="wpmudev-select" data-attribute="after_subscription" >
				<option value="keep_show" {{ ( 'keep_show' === after_subscription ) ? 'selected' : '' }} ><?php esc_attr_e( "Keep showing this message", Opt_In::TEXT_DOMAIN ); ?></option>
				<option value="no_show_on_post" {{ ( 'no_show_on_post' === after_subscription ) ? 'selected' : '' }} ><?php esc_attr_e( "No longer show this message on this post / page", Opt_In::TEXT_DOMAIN ); ?></option>
				<option value="no_show_all" {{ ( 'no_show_all' === after_subscription ) ? 'selected' : '' }} ><?php esc_attr_e( "No longer show this message across the site", Opt_In::TEXT_DOMAIN ); ?></option>
			</select>

		</div>

	</div>

</div><?php // #wph-wizard-content-form_success ?>
