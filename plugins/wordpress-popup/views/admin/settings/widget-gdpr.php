<div class="wpmudev-box wpmudev-box-close">

	<div class="wpmudev-box-head">

		<h2><?php esc_html_e( 'Privacy Settings', Opt_In::TEXT_DOMAIN ); ?></h2>

        <div class="wpmudev-box-action"><?php $this->render("general/icons/icon-plus" ); ?></div>

	</div>

	<div class="wpmudev-box-body">

		<form id="wph-gdpr-settings">
			<p><?php esc_html_e( 'List here IPs you want to remove from the database. Remember to add one IP per line to avoid issues. Up to 10 IPs per request.', Opt_In::TEXT_DOMAIN ); ?></p>

			<textarea id="hustle-delete-ip" class="wpmudev-textarea" name="delete_ip" form="wph-gdpr-settings" rows="4" placeholder="190.190.1.1&#10;190.190.1.2"></textarea>
		</form>

		<div class="wpmudev-box-footer">

			<?php /* <button class="wpmudev-button wpmudev-button-sm"><?php esc_html_e( 'Clear All', Opt_In::TEXT_DOMAIN ); ?></button> */?>

			<button id="wph-gdpr-settings-submit" class="wpmudev-button wpmudev-button-blue wpmudev-button-sm" data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_remove_ips' ) ); ?>"><span class="wpmudev-loading-text"><?php esc_html_e( 'Update', Opt_In::TEXT_DOMAIN ); ?></span><span class="wpmudev-loading"></span></button>

		</div>

	</div>

</div>
