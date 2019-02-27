<div id="wpmudev-settings-widget-recaptcha" class="wpmudev-box wpmudev-box-close">
    <div class="wpmudev-box-head">
        <h2><?php esc_attr_e( 'reCAPTCHA options', Opt_In::TEXT_DOMAIN ); ?></h2>
        <div class="wpmudev-box-action"><?php $this->render( 'general/icons/icon-plus' ); ?></div>
    </div>
    <div class="wpmudev-box-body">
<?php
$options = array(
	'name_label' => array(
		'id' 	=> 'recaptcha-sitekey-label',
		'for' 	=> 'recaptcha-sitekey',
		'type' 	=> 'label',
		'value' => __( 'Site key', Opt_In::TEXT_DOMAIN ),
	),
	'name_field' => array(
		'id' 			=> 'recaptcha-sitekey',
		'name' 			=> 'sitekey',
		'value' 		=> $sitekey,
		'placeholder' 	=> '',
		'type' 			=> 'text',
		'class'         => 'wpmudev-input_text',
	),
	'secret_label' => array(
		'id' 	=> 'recaptcha-secret-label',
		'for' 	=> 'recaptcha-secret',
		'type' 	=> 'label',
		'value' => __( 'Secret key', Opt_In::TEXT_DOMAIN ),
	),
	'secret_field' => array(
		'id' 			=> 'recaptcha-secret',
		'name' 			=> 'secret',
		'value' 		=> $secret,
		'placeholder' 	=> '',
		'type' 			=> 'text',
		'class'         => 'wpmudev-input_text',
	),
	'submit' => array(
		'id' 	=> 'mail-submit',
		'type' 	=> 'submit_button',
		'value' => __( 'Save', Opt_In::TEXT_DOMAIN ),
		'class' => 'wpmudev-button wpmudev-button-sm',
	),
);
?>
        <form id="wpmudev-settings-recaptcha-form" data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_save_global_recaptcha_settings' ) ); ?>">
            <div class="wpmudev-switch-labeled">
                <div class="wpmudev-switch">
                    <input type="checkbox" id="wph-recaptcha-status" class="toggle-checkbox" value="1" name="enabled" <?php echo ( '1' === (string) $enabled ? 'checked="checked"':'' ); ?> >
                    <label class="wpmudev-switch-design" for="wph-recaptcha-status" aria-hidden="true"></label>
                </div>
                <label class="wpmudev-switch-label" for="wph-recaptcha-status"><?php esc_attr_e( 'Use reCAPTCHA', Opt_In::TEXT_DOMAIN ); ?></label>
            </div>
            <br>&nbsp;
<?php
foreach ( $options as $key => $option ) {
	$this->render( 'general/option', $option );
}
?>
            <br>&nbsp;
        </form>
    </div>
</div>
