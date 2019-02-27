<?php
// CAN WE PLEASE ADD THIS FUNCTIONS SOMEWHERE TO USE IN THE FUTURE?
// THAT WAY IF WE NEED TO ADD A NEW FIELD WITH CONTENT TO BE COPIED
// WE CAN SIMPLY USE THE FUNCTION BELOW:

function wpmudev_copy_field($field_value) {

    echo '<div class="wpmudev-copy">
        <input class="wpmudev-input_text" type="text" value="' . esc_attr( $field_value ) . '" readonly>
        <button class="wpmudev-button wpmudev-button-sm">' . esc_attr__( "Copy", Opt_In::TEXT_DOMAIN ) . '</button>
    </div>';

}
?>

<div id="wph-wizard-settings-shortcode" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Widgets & Shortcodes", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

		<label class="wpmudev-helper"><?php esc_attr_e( "Widget & Shortcode display location is controlled manually.", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "Widgets", Opt_In::TEXT_DOMAIN ); ?></label>

        <p>
			<?php
			printf(
				esc_html__('You can configure %1$s, %2$s and %3$s opt-ins in the sections that follow.', Opt_In::TEXT_DOMAIN),
				sprintf(
					'<strong>%s</strong>',
					esc_html__('After Content', Opt_In::TEXT_DOMAIN)
				),
				sprintf(
					'<strong>%s</strong>',
					esc_html__('Pop-up', Opt_In::TEXT_DOMAIN)
				),
				sprintf(
					'<strong>%s</strong>',
					esc_html__('Slide-in', Opt_In::TEXT_DOMAIN)
				)
			);
			?>
		</p>

        <p>
			<?php
			printf(
				esc_html__('Widget opt-in can be set-up in %s', Opt_In::TEXT_DOMAIN),
				sprintf(
					'<strong>%1$s » %2$s</strong>',
					esc_html__('Appearance', Opt_In::TEXT_DOMAIN),
					sprintf(
						'<a href="%1$s" target="_blank">%2$s</a>',
						esc_url( admin_url( 'widgets.php' ) ),
						esc_html__('Widgets', Opt_In::TEXT_DOMAIN)
					)
				)
			);
			?>
		</p>

        <label><?php esc_attr_e( "Shortcode", Opt_In::TEXT_DOMAIN ); ?></label>

        <?php wpmudev_copy_field("[wd_hustle id='". $module->shortcode_id ."' type='social_sharing']"); ?>

	</div>

</div><?php // #wph-wizard-settings-shortcode ?>
