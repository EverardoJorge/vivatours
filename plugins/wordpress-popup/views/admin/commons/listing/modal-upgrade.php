<?php
$path = self::$plugin_url;
?>

<div id="wph-upgrade-modal" class="wpmudev-modal wpmudev-modal-upsell">

	<div class="wpmudev-modal-mask" aria-hidden="true"></div>

	<div class="wpmudev-box-modal wpmudev-show">

		<div class="wpmudev-box-head">

			<?php $this->render( 'general/icons/icon-close' ); ?>

		</div>

		<div class="wpmudev-box-body wpmudev-center-text">

			<h2><?php esc_html_e( 'Upgrade to Pro', Opt_In::TEXT_DOMAIN ); ?></h2>

			<p><?php esc_html_e( 'Get unlimited Popups, Slide-ins, Embeds and social sharing widgets with the Pro version of Hustle. Get it as part of a WPMU DEV membership including Smush Pro, Hummingbird Pro and other popular professional plugins.', Opt_In::TEXT_DOMAIN ); ?></p>

			<a target="_blank"
				href="https://premium.wpmudev.org/project/hustle/?utm_source=hustle&utm_medium=plugin&utm_campaign=hustle_modal_upsell_notice"
				class="wpmudev-button wpmudev-button-sm wpmudev-button-green"><?php esc_html_e( 'Learn more', Opt_In::TEXT_DOMAIN ); ?></a>

			<img src="<?php echo $path . 'assets/img/hustle-upsell.png'; // WPCS: XSS ok. ?>"
				srcset="<?php echo $path . 'assets/img/hustle-upsell.png'; // WPCS: XSS ok. ?> 1x, <?php echo $path . 'assets/img/hustle-upsell@2x.png'; // WPCS: XSS ok. ?> 2x" alt="<?php esc_html_e( 'Upgrade to Hustle Pro!', Opt_In::TEXT_DOMAIN ); ?>"
				class="sui-image sui-image-center fui-image" />

		</div>

	</div>

</div>
