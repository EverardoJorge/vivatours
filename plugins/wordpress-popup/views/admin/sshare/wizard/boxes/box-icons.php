<div id="wph-wizard-design-icons" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Icons design and order", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

		<label class="wpmudev-helper"><?php esc_attr_e( "Choose what kind of design you want for your icons.", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<div id="wpmudev-choose-icons-style">

			<label><?php esc_attr_e( "Icons style", Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-tabs">

				<ul class="wpmudev-tabs-menu">

					<li class="wpmudev-tabs-menu_item">

						<input id="wpmudev-sshare-flat-icon-style" type="radio" name="icon_style" data-attribute="icon_style" value="flat" {{ _.checked( ( 'flat' === icon_style ) , true) }}>

						<label for="wpmudev-sshare-flat-icon-style">

							<div class="hustle-social-icon hustle-icon-flat">

								<div class="hustle-icon-container"><?php $this->render("general/icons/social/facebook"); ?></div>

							</div>

						</label>

					</li>

                    <li class="wpmudev-tabs-menu_item">

						<input id="wpmudev-sshare-outline-icon-style" type="radio" name="icon_style" data-attribute="icon_style" value="outline" {{ _.checked( ( 'outline' === icon_style ) , true) }}>

						<label for="wpmudev-sshare-outline-icon-style">

							<div class="hustle-social-icon hustle-icon-outline">

								<div class="hustle-icon-container"><?php $this->render("general/icons/social/facebook"); ?></div>

							</div>

						</label>

					</li>

                    <li class="wpmudev-tabs-menu_item">

						<input id="wpmudev-sshare-rounded-icon-style" type="radio" name="icon_style" data-attribute="icon_style" value="rounded" {{ _.checked( ( 'rounded' === icon_style ) , true) }}>

						<label for="wpmudev-sshare-rounded-icon-style">

							<div class="hustle-social-icon hustle-icon-rounded">

								<div class="hustle-icon-container"><?php $this->render("general/icons/social/facebook"); ?></div>

							</div>

						</label>

					</li>

                    <li class="wpmudev-tabs-menu_item">

						<input id="wpmudev-sshare-squared-icon-style" type="radio" name="icon_style" data-attribute="icon_style" value="squared" {{ _.checked( ( 'squared' === icon_style ) , true) }}>

						<label for="wpmudev-sshare-squared-icon-style">

							<div class="hustle-social-icon hustle-icon-squared">

								<div class="hustle-icon-container"><?php $this->render("general/icons/social/facebook"); ?></div>

							</div>

						</label>

					</li>

				</ul>

			</div>

		</div>

		<div id="wpmudev-reoder-icons">

			<label><?php esc_attr_e( "Click & Drag to re-order icons", Opt_In::TEXT_DOMAIN ); ?></label>

			<?php $this->render("admin/commons/wizard/reorder-icons"); ?>

		</div>

	</div>

</div><?php // #wph-wizard-design-style_order ?>
