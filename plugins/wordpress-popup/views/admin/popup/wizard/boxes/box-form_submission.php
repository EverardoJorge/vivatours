<div id="wph-wizard-content-form_submission" class="wpmudev-box-content last">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Successful submission behavior", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

		<label class="wpmudev-helper"><?php esc_attr_e( "Choose what you want to happen after your visitor has successfully submitted their email address through Hustle's form.", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "After successfull submission", Opt_In::TEXT_DOMAIN ); ?></label>

		<div class="wpmudev-tabs">

            <ul class="wpmudev-tabs-menu wpmudev-tabs-menu_full wpmudev-after-submit-options">

                <li class="wpmudev-tabs-menu_item {{ ( 'show_success' === after_successful_submission ) ? 'current' : '' }}">
                    <input type="checkbox" value="show_success">
                    <label><?php esc_attr_e( "Show success message", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item {{ ( 'redirect' === after_successful_submission ) ? 'current' : '' }}">
                    <input type="checkbox" value="redirect">
                    <label><?php esc_attr_e( "Page re-direct", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

            </ul>

        </div>

        <input id="wph-wizard-content-form_submission_redirect_url" type="text" data-attribute="redirect_url" value="{{redirect_url}}" placeholder="http://yourwebsite.com/success-page/" class="wpmudev-input_text {{ ( 'show_success' === after_successful_submission ) ? 'wpmudev-hidden' : '' }}">

	</div>

</div><?php // #wph-wizard-content-form_submission ?>

<?php $this->render( "admin/popup/wizard/boxes/box-form_message", array() ); ?>

<?php $this->render( "admin/popup/wizard/boxes/box-form_success", array() ); ?>
