<?php
$custom_colors = false;
?>

<div id="wph-wizard-design-style_colors">

	<div class="wpmudev-switch-labeled">

		<div class="wpmudev-switch">

			<input id="wph-slidein-style_colors" class="toggle-checkbox" type="checkbox" data-id="" data-nonce="" <?php checked( $custom_colors ); ?>>

			<label class="wpmudev-switch-design" for="wph-slidein-style_colors" aria-hidden="true"></label>

		</div>

		<label class="wpmudev-switch-label" for="wph-slidein-style_colors"><?php esc_attr_e( "Customize colors", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

</div>
