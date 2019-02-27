<div id="wph-wizard-settings-closing" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Closing behavior", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

	</div>

	<div class="wpmudev-box-right">

        <div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

                <input id="wph-slidein-auto_hide" class="toggle-checkbox" type="checkbox" data-attribute="auto_hide" {{_.checked(_.isTrue(auto_hide), true)}}>

				<label class="wpmudev-switch-design" for="wph-slidein-auto_hide" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-slidein-auto_hide"><?php esc_attr_e( "Automatically hide Slide-in", Opt_In::TEXT_DOMAIN ); ?></label>

		</div>

        <div id="wpmudev-display-auto_hide-options" class="wpmudev-box-gray {{( _.isTrue(auto_hide) ) ? 'wpmudev-show' : 'wpmudev-hidden'}}">

            <label><?php esc_attr_e( "Automatically hide Slide-in after", Opt_In::TEXT_DOMAIN ); ?></label>

        	<div class="wpmudev-fields-group">
            	<input type="number" class="wpmudev-input_number" data-attribute="auto_hide_time" value="{{auto_hide_time}}">

            	<select class="wpmudev-select" data-attribute="auto_hide_unit">

                	<option value="hours" {{ ( 'hours' === auto_hide_unit ) ? 'selected' : '' }}><?php esc_attr_e( "hours", Opt_In::TEXT_DOMAIN ); ?></option>
                	<option value="minutes" {{ ( 'minutes' === auto_hide_unit ) ? 'selected' : '' }}><?php esc_attr_e( "minutes", Opt_In::TEXT_DOMAIN ); ?></option>
                	<option value="seconds" {{ ( 'seconds' === auto_hide_unit ) ? 'selected' : '' }}><?php esc_attr_e( "seconds", Opt_In::TEXT_DOMAIN ); ?></option>

            	</select>
			</div>

        </div>

        <div id="wph-slidein-close">

            <h5><?php esc_attr_e( "After Slide-in is closed", Opt_In::TEXT_DOMAIN ); ?></h5>

        	<label class="wpmudev-helper"><?php esc_attr_e( "Choose how your Slide-in will behave when it is closed.", Opt_In::TEXT_DOMAIN ); ?></label>


        	<label class="wpmudev-label--notice"><span><?php esc_attr_e( "This option does not work with auto-hide because a user action is required.", Opt_In::TEXT_DOMAIN ); ?></span></label>

            <div class="wpmudev-box-gray">

				<select class="wpmudev-select" data-attribute="after_close" >
					<option value="no_show_on_post" {{ ( 'no_show_on_post' === after_close ) ? 'selected' : '' }} ><?php esc_attr_e( "No longer show this message on this post / page", Opt_In::TEXT_DOMAIN ); ?></option>
					<option value="no_show_all" {{ ( 'no_show_all' === after_close ) ? 'selected' : '' }} ><?php esc_attr_e( "No longer show this message across the site", Opt_In::TEXT_DOMAIN ); ?></option>
					<option value="keep_show" {{ ( 'keep_show' === after_close  || '' === after_close ) ? 'selected' : '' }} ><?php esc_attr_e( "Keep showing this message", Opt_In::TEXT_DOMAIN ); ?></option>
				</select>

                <label><?php esc_attr_e( "Expires (after expiracy, user will see the Slide-in again)", Opt_In::TEXT_DOMAIN ); ?></label>

        		<div class="wpmudev-fields-group">

                    <input type="number" class="wpmudev-input_number" value="{{expiration}}" data-attribute="expiration">

                    <select class="wpmudev-select" data-attribute="expiration_unit">

                	    <option value="months" {{ ( 'months' === expiration_unit ) ? 'selected' : '' }}><?php esc_attr_e( "months", Opt_In::TEXT_DOMAIN ); ?></option>
                	    <option value="weeks" {{ ( 'weeks' === expiration_unit ) ? 'selected' : '' }}><?php esc_attr_e( "weeks", Opt_In::TEXT_DOMAIN ); ?></option>
                	    <option value="days" {{ ( 'days' === expiration_unit ) ? 'selected' : '' }}><?php esc_attr_e( "days", Opt_In::TEXT_DOMAIN ); ?></option>
                	    <option value="hours" {{ ( 'hours' === expiration_unit ) ? 'selected' : '' }}><?php esc_attr_e( "hours", Opt_In::TEXT_DOMAIN ); ?></option>
                	    <option value="minutes" {{ ( 'minutes' === expiration_unit ) ? 'selected' : '' }}><?php esc_attr_e( "minutes", Opt_In::TEXT_DOMAIN ); ?></option>
                	    <option value="seconds" {{ ( 'seconds' === expiration_unit ) ? 'selected' : '' }}><?php esc_attr_e( "seconds", Opt_In::TEXT_DOMAIN ); ?></option>

                    </select>

                </div>

            </div>

        </div>

	</div>

</div><?php // #wph-wizard-settings-closing ?>
