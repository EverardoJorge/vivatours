<?php
$content_hide = false;
?>

<div id="wpmudev-settings-widget-modules" class="wpmudev-box wpmudev-box-close">

	<div class="wpmudev-box-head">

		<h2><?php esc_html_e( "Don't show modules to", Opt_In::TEXT_DOMAIN ); ?></h2>

		<div class="wpmudev-box-action"><?php $this->render( 'general/icons/icon-plus' ); ?></div>

	</div>

	<div class="wpmudev-box-body<?php if ( true === $content_hide ) { echo ' wpmudev-hidden'; } // phpcs:ignore ?>">

		<table cellspacing="0" cellpadding="0" class="wpmudev-table">

			<thead>

				<tr>

					<th><?php esc_html_e( 'Module Name', Opt_In::TEXT_DOMAIN ); ?></th>

					<th><?php esc_html_e( 'Logged-in User', Opt_In::TEXT_DOMAIN ); ?></th>

					<th><?php esc_html_e( 'Admin', Opt_In::TEXT_DOMAIN ); ?></th>

				</tr>

			</thead>

			<tbody>

				<?php
				foreach ( $modules as $module ) :

					$admin_id  = esc_attr( 'hustle-module-admin' . $module->id );
					$logged_id = esc_attr( 'hustle-module-logged_in' . $module->id );
					?>

					<tr>

						<td>

							<div class="wpmudev-settings-module-name">

								<?php
								if ( "popup" === $module->module_type ) {
									$tooltip = esc_attr__( "Pop-up", Opt_In::TEXT_DOMAIN );
								} else if ( "slidein" === $module->module_type ) {
									$tooltip = esc_attr__( "Slide-in", Opt_In::TEXT_DOMAIN );
								} else if ( "embedded" === $module->module_type ) {
									$tooltip = esc_attr__( "Embed", Opt_In::TEXT_DOMAIN );
								} else if ( "social_sharing" === $module->module_type ) {
									$tooltip = esc_attr__( "Social Sharing", Opt_In::TEXT_DOMAIN );
								}
								?>

								<div class="wpmudev-module-icon wpmudev-tip" data-tip="<?php echo esc_attr( $tooltip ); ?>">

									<?php if ( "popup" === $module->module_type ) { ?>

										<?php $this->render( 'general/icons/admin-icons/icon-popup' ); ?>

									<?php } ?>

									<?php if ( "slidein" === $module->module_type ) { ?>

										<?php $this->render( 'general/icons/admin-icons/icon-slidein' ); ?>

									<?php } ?>

									<?php if ( "embedded" === $module->module_type ) { ?>

										<?php $this->render( 'general/icons/admin-icons/icon-shortcode' ); ?>

									<?php } ?>

									<?php if ( "social_sharing" === $module->module_type ) { ?>

										<?php $this->render( 'general/icons/admin-icons/icon-shares' ); ?>

									<?php } ?>

								</div>

								<div class="wpmudev-module-name"><?php echo esc_html( $module->module_name ); ?></div>

							</div>

						</td>

						<td data-title="<?php esc_attr_e( 'Logged-in User', Opt_In::TEXT_DOMAIN ); ?>">

							<div class="wpmudev-switch">

								<input id="<?php echo esc_attr( $logged_id ); ?>" class="toggle-checkbox hustle-for-logged-in-user-toggle" type="checkbox" data-user="logged_in" data-nonce="<?php echo esc_attr( $modules_state_toggle_nonce ); ?>" data-id="<?php echo esc_attr( $module->id ); ?>" <?php checked( !$module->is_active_for_logged_in_user, 1 ); ?>>

								<label class="wpmudev-switch-design" for="<?php echo esc_attr( $logged_id ); ?>" aria-hidden="true"></label>

							</div>

						</td>

						<td data-title="<?php esc_attr_e( 'Admin', Opt_In::TEXT_DOMAIN ); ?>">

							<div class="wpmudev-switch">

								<input id="<?php echo esc_attr( $admin_id ); ?>" class="toggle-checkbox hustle-for-admin-user-toggle" type="checkbox" data-user="admin" data-nonce="<?php echo esc_attr( $modules_state_toggle_nonce ); ?>" data-id="<?php echo esc_attr( $module->id ); ?>" <?php checked( !$module->is_active_for_admin, 1 ); ?>>

								<label class="wpmudev-switch-design" for="<?php echo esc_attr( $admin_id ); ?>" aria-hidden="true"></label>

							</div>

						</td>

					</tr>

				<?php endforeach; ?>

			</tbody>

		</table>

	</div>

</div>
