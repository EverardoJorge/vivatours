<?php
/**
 * @var Hustle_Embedded_Admin $this
 * @var bool $is_edit if it's in edit mode
 */
$recaptcha_settings = Hustle_Module_Model::get_recaptcha_settings();
$recaptcha_key = isset( $recaptcha_settings['sitekey'] ) && '1' === $recaptcha_settings['enabled'] ? $recaptcha_settings['sitekey'] : '';
?>


<main id="wpmudev-hustle" class="wpmudev-ui wpmudev-hustle-embedded-wizard-view">

	<header id="wpmudev-hustle-title">

		<h1><?php $is_edit ? esc_attr_e('Edit Embed', Opt_In::TEXT_DOMAIN) : esc_attr_e('New Embed', Opt_In::TEXT_DOMAIN); ?></h1>

	</header>

	<section id="wpmudev-hustle-content">

		<div class="wpmudev-tabs-page">

			<aside class="wpmudev-menu">

				<ul>

                    <?php $id_link = ( $is_edit ) ? '&id=' . $module_id : ''; ?>

                    <li class="wpmudev-menu-content-link<?php if ( 'content' === $section ) echo ' current'; ?>"><a href="<?php echo esc_url( admin_url( 'admin.php?page=hustle_embedded' . $id_link ) ); ?>" data-link="<?php echo esc_url( admin_url( 'admin.php?page=hustle_embedded' . $id_link ) ); ?>"><?php esc_attr_e( "Content", Opt_In::TEXT_DOMAIN ); ?></a></li>
					<li class="wpmudev-menu-design-link<?php if ( 'design' === $section ) echo ' current'; ?>"><a href="<?php echo ( $is_edit ) ? esc_url( admin_url( 'admin.php?page=hustle_embedded'. $id_link .'&section=design' ) ) : '#'; ?>" data-link="<?php echo esc_url( admin_url( 'admin.php?page=hustle_embedded'. $id_link .'&section=design' ) ); ?>"><?php esc_attr_e( "Design", Opt_In::TEXT_DOMAIN ); ?></a></li>
					<li class="wpmudev-menu-settings-link<?php if ( 'settings' === $section ) echo ' current'; ?>"><a href="<?php echo ( $is_edit ) ? esc_url( admin_url( 'admin.php?page=hustle_embedded'. $id_link .'&section=settings' ) ) : '#'; ?>" data-link="<?php echo esc_url( admin_url( 'admin.php?page=hustle_embedded'. $id_link .'&section=settings' ) ); ?>"><?php esc_attr_e( "Display Settings", Opt_In::TEXT_DOMAIN ); ?></a></li>

				</ul>

				<select class="wpmudev-select">

					<option value="content" <?php if ( 'content' === $section ) echo 'selected'; ?>><?php esc_attr_e( "Content", Opt_In::TEXT_DOMAIN ); ?></option>
					<option value="design" <?php if ( 'design' === $section ) echo 'selected'; ?>><?php esc_attr_e( "Design", Opt_In::TEXT_DOMAIN ); ?></option>
					<option value="settings" <?php if ( 'settings' === $section ) echo 'selected'; ?>><?php esc_attr_e( "Display Settings", Opt_In::TEXT_DOMAIN ); ?></option>

				</select>

				<div class="wpmudev-preview-anchor" aria-hidden="true"></div>

				<div 
					class="wpmudev-preview"
					aria-hidden="true" 
					data-sitekey="<?php echo esc_attr( $recaptcha_key ); ?>"
					data-nonce="<?php echo esc_attr( $shortcode_render_nonce ); ?>" 
					data-custom-css-nonce="<?php echo esc_attr( wp_create_nonce('hustle_module_prepare_custom_css') ); ?>">

					<?php $this->render( "general/icons/icon-preview", array() ); ?>

					<span><?php esc_attr_e( "Preview Embed", Opt_In::TEXT_DOMAIN ); ?></span>

				</div>

			</aside>

			<section class="wpmudev-content">

				<div class="wpmudev-box">

					<div class="wpmudev-box-head">

						<?php if ( 'content' === $section ) { ?>

							<h3><?php esc_attr_e( "Content", Opt_In::TEXT_DOMAIN ); ?></h3>

						<?php } ?>

						<?php if ( 'design' === $section ) { ?>

							<h3><?php esc_attr_e( "Design", Opt_In::TEXT_DOMAIN ); ?></h3>

						<?php } ?>

						<?php if ( 'settings' === $section ) { ?>

							<h3><?php esc_attr_e( "Display Settings", Opt_In::TEXT_DOMAIN ); ?></h3>

						<?php } ?>

					</div>

					<div class="wpmudev-box-body">

                        <?php if ( 'content' === $section ) { ?>

                            <?php
							$this->render( "admin/embedded/wizard/wizard-content", array(
                                'is_edit' => $is_edit,
                                'module' => $module,
                                'providers' => $providers,
								'default_form_fields' => $default_form_fields,
								'allowed_extensions' => array(
									'image_ext' => Opt_In_Utils::get_allowed_image_extensions(),
									'render_ext' => Opt_In_Utils::get_allowed_renderable_extensions(),
								),
                            ) );
							?>

                        <?php } ?>

                        <?php if ( 'design' === $section ) { ?>

                            <?php $this->render( "admin/embedded/wizard/wizard-design", array( 'content_data' => ( !is_null($module) && $module ) ? $module->get_content() : array() ) ); ?>

                        <?php } ?>

                        <?php if ( 'settings' === $section ) { ?>

                            <?php $this->render( "admin/embedded/wizard/wizard-settings", array() ); ?>

                        <?php } ?>

						<div class="wpmudev-box-footer">

							<div class="wpmudev-box-fwrap">

								<?php if ( 'content' === $section ) { ?>

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

								<?php if ( 'content' === $section || 'design' === $section ) { ?>

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

    <?php
	$this->render( "admin/commons/wizard/add-new-service", array(
        'module' => $module,
        'providers' => $providers
    ) );
	?>

	<?php
	$this->render( "admin/commons/wizard/manage-form-fields", array(
		'recaptcha_enabled' => $recaptcha_enabled,
		'module' => $module,
		'default_form_fields' => $default_form_fields
	) );
	?>

	<?php $this->render( "admin/commons/wizard/preview-modal", array() ); ?>

    <?php $this->render("admin/settings/conditions"); ?>

</main>

<?php $this->render( 'admin/footer/footer-simple' ); ?>