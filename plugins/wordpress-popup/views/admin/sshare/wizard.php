<main id="wpmudev-hustle" class="wpmudev-ui wpmudev-hustle-sshare-wizard-view">

	<header id="wpmudev-hustle-title">

		<h1><?php $is_edit ? esc_attr_e('Edit Social Share', Opt_In::TEXT_DOMAIN) : esc_attr_e('New Social Share', Opt_In::TEXT_DOMAIN); ?></h1>

	</header>

	<section id="wpmudev-hustle-content">

		<div class="wpmudev-tabs-page">

			<aside class="wpmudev-menu">

				<ul>

                    <?php $id_link = ( $is_edit ) ? '&id=' . $module_id : ''; ?>

					<li class="wpmudev-menu-services-link<?php if ( 'services' === $section ) echo ' current'; ?>"><a href="<?php echo esc_url( admin_url( 'admin.php?page=hustle_sshare' . $id_link ) ); ?>" data-link="<?php echo esc_url( admin_url( 'admin.php?page=hustle_sshare' . $id_link ) ); ?>"><?php esc_attr_e( "Name & Services", Opt_In::TEXT_DOMAIN ); ?></a></li>
					<li class="wpmudev-menu-design-link<?php if ( 'design' === $section ) echo ' current'; ?>"><a href="<?php echo ( $is_edit ) ? esc_url( admin_url( 'admin.php?page=hustle_sshare'. $id_link .'&section=design' ) ) : '#'; ?>" data-link="<?php echo esc_url( admin_url( 'admin.php?page=hustle_sshare'. $id_link .'&section=design' ) ); ?>"><?php esc_attr_e( "Design", Opt_In::TEXT_DOMAIN ); ?></a></li>
					<li class="wpmudev-menu-settings-link<?php if ( 'settings' === $section ) echo ' current'; ?>"><a href="<?php echo ( $is_edit ) ? esc_url( admin_url( 'admin.php?page=hustle_sshare'. $id_link .'&section=settings' ) ) : '#'; ?>" data-link="<?php echo esc_url( admin_url( 'admin.php?page=hustle_sshare'. $id_link .'&section=settings' ) ); ?>" ><?php esc_attr_e( "Display Settings", Opt_In::TEXT_DOMAIN ); ?></a></li>

				</ul>

				<select class="wpmudev-select">

					<option value="services" <?php if ( 'services' === $section ) echo 'selected'; ?>><?php esc_attr_e( "Name & Services", Opt_In::TEXT_DOMAIN ); ?></option>
					<option value="design" <?php if ( 'design' === $section ) echo 'selected'; ?>><?php esc_attr_e( "Design", Opt_In::TEXT_DOMAIN ); ?></option>
					<option value="settings" <?php if ( 'settings' === $section ) echo 'selected'; ?>><?php esc_attr_e( "Display Settings", Opt_In::TEXT_DOMAIN ); ?></option>

				</select>

			</aside>

			<section class="wpmudev-content">

				<div class="wpmudev-box">

					<div class="wpmudev-box-head">

						<?php if ( 'services' === $section ) { ?>

							<h3><?php esc_attr_e( "Name & Services", Opt_In::TEXT_DOMAIN ); ?></h3>

						<?php } ?>

						<?php if ( 'design' === $section ) { ?>

							<h3><?php esc_attr_e( "Design", Opt_In::TEXT_DOMAIN ); ?></h3>

						<?php } ?>

						<?php if ( 'settings' === $section ) { ?>

							<h3><?php esc_attr_e( "Display Settings", Opt_In::TEXT_DOMAIN ); ?></h3>

						<?php } ?>

					</div>

					<div class="wpmudev-box-body">

						<?php
						if ( 'services' === $section ) {
							$this->render( "admin/sshare/wizard/wizard-services", array(
                                'module' => $module
                            ) );
						}
						if ( 'design' === $section ) {
							$this->render( "admin/sshare/wizard/wizard-design", array(
                                'module' => $module
                            ) );
						}
						if ( 'settings' === $section ) {
							$this->render( "admin/sshare/wizard/wizard-settings", array(
                                'module' => $module
                            ) );
						}
						?>

						<div class="wpmudev-box-footer">

							<div class="wpmudev-box-fwrap">

								<?php if ( 'services' === $section ) { ?>

									<a class="wpmudev-button wpmudev-button-cancel"><?php esc_attr_e( "Cancel", Opt_In::TEXT_DOMAIN ); ?></a>

								<?php } ?>

								<?php if ( 'design' === $section || 'settings' === $section ) { ?>

									<a class="wpmudev-button wpmudev-button-back">
										<span class="wpmudev-loading-text"><?php esc_attr_e( "Back", Opt_In::TEXT_DOMAIN ); ?></span>
										<span class="wpmudev-loading"></span>
									</a>

								<?php } ?>

							</div>

							<div class="wpmudev-box-fwrap">

								<?php if ( 'services' === $section || 'design' === $section ) { ?>

									<a class="wpmudev-button wpmudev-button-save" data-nonce="<?php echo esc_attr( $save_nonce ); ?>" data-id="<?php echo esc_attr( $module_id ); ?>">
										<span class="wpmudev-loading-text"><?php esc_html_e( "Save Changes", Opt_In::TEXT_DOMAIN ); ?></span>
										<span class="wpmudev-loading"></span>
									</a>


									<a class="wpmudev-button wpmudev-button-blue wpmudev-button-continue" data-nonce="<?php echo esc_attr( $save_nonce ); ?>" data-id="<?php echo esc_attr( $module_id ); ?>">
										<span class="wpmudev-loading-text"><?php esc_html_e( "Continue", Opt_In::TEXT_DOMAIN ); ?></span>
										<span class="wpmudev-loading"></span>
									</a>

								<?php } ?>

								<?php if ( 'settings' === $section ) { ?>

									<?php if ( ! $module->active ) { ?>
										<a class="wpmudev-button wpmudev-button-save" data-nonce="<?php echo esc_attr( $save_nonce ); ?>" data-id="<?php echo esc_attr( $module_id ); ?>">
											<span class="wpmudev-loading-text"><?php esc_html_e( "Save Draft", Opt_In::TEXT_DOMAIN ); ?></span>
											<span class="wpmudev-loading"></span>
										</a>
									<?php } else { ?>
										<a class="wpmudev-button wpmudev-button-save" data-nonce="<?php echo esc_attr( $save_nonce ); ?>" data-id="<?php echo esc_attr( $module_id ); ?>">
											<span class="wpmudev-loading-text"><?php esc_html_e( "Save Changes", Opt_In::TEXT_DOMAIN ); ?></span>
											<span class="wpmudev-loading"></span>
										</a>
									<?php } ?>


									<a class="wpmudev-button wpmudev-button-blue wpmudev-button-finish" data-nonce="<?php echo esc_attr( $save_nonce ); ?>" data-id="<?php echo esc_attr( $module_id ); ?>">
										<?php
											if ( $module->active ) {
												esc_html_e( "Update", Opt_In::TEXT_DOMAIN );
											} else {
												esc_html_e( "Publish", Opt_In::TEXT_DOMAIN );
											}
										?>
										<span class="wpmudev-loading"></span>
									</a>

								<?php } ?>

							</div>

						</div>

					</div>

				</div>

			</section>

		</div>

	</section>

    <?php $this->render("admin/settings/conditions"); ?>

</main>

<?php $this->render( 'admin/footer/footer-simple' ); ?>