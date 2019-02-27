<?php ?>
<script id="hustle-modal-import-tpl" type="text/template">

	<div class="wpmudev-modal-mask"></div>

	<div class="wpmudev-box-modal">

		<div class="wpmudev-box-head">

			<div class="wpmudev-box-reset">

				<h2>{{name}}</h2>

				<label class="wpmudev-helper"><?php esc_attr_e( "Import Settings", Opt_In::TEXT_DOMAIN ); ?></label>

			</div>

			<?php $this->render("general/icons/icon-close" ); ?>

		</div>

		<div class="wpmudev-box-body">
			<form id="wph-optin-service-import-form" enctype="multipart/form-data">
				<p>
					<input type="file" name="import" class="input-key" accept=".json">
				</p>
				<p id="select_file_error"><label class="wpmudev-label--notice"><span><?php esc_html_e( "Please, select a file", Opt_In::TEXT_DOMAIN ); ?></span></label></p>
				<p id="import_error"></p>
			</form>
		</div>

		<div class="wpmudev-box-footer">

			<div class="wpmudev-footer-clear">

				<span class="hustle-confirmation wpmudev-hidden">

					<label><?php esc_attr_e( "Are you sure?", Opt_In::TEXT_DOMAIN ); ?></label>

					<button type="button" class="wpmudev-button wpmudev-button-sm wpmudev-button-confirm-import" data-id="{{id}}" data-type="{{type}}" data-nonce="{{nonce}}">
						<?php esc_attr_e( "Yes", Opt_In::TEXT_DOMAIN ); ?>
					</button>

					<button type="button" class="wpmudev-button wpmudev-button-sm wpmudev-button-cancel-import"><?php esc_attr_e( "No", Opt_In::TEXT_DOMAIN ); ?></button>

				</span>

			</div>

			<div class="wpmudev-footer-import">
				<a href="#" id="wpmudev-button-import" class="wpmudev-button wpmudev-button-blue">
					<?php esc_attr_e("Upload file and import settings", Opt_In::TEXT_DOMAIN); ?>
				</a>
			</div>

		</div>

	</div>

</script>
