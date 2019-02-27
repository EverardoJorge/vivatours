<?php
/**
 * @var $this Hustle_Popup_Admin
 * @var $module Hustle_Module_Model
 * @var $new_module Hustle_Module_Model
 */
?>

<?php if ( count( $popups ) === 0 ) { ?>

	<?php
	$this->render("admin/popup/welcome", array(
        'new_url' => $add_new_url,
        'user_name' => $user_name
    ));
	?>

<?php } else { ?>

	<main id="wpmudev-hustle" class="wpmudev-ui wpmudev-hustle-listings-view">

		<header id="wpmudev-hustle-title" class="wpmudev-has-button">

			<h1><?php esc_attr_e( "Pop-ups Dashboard", Opt_In::TEXT_DOMAIN ); ?></h1>

			<a class="wpmudev-button wpmudev-button-sm wpmudev-button-ghost" <?php if ( $is_free && count( $popups ) >= 3 ) echo 'id="hustle-free-version-create"'; ?> href="<?php echo esc_url( $add_new_url ); ?>"><?php esc_attr_e('New Pop-up', Opt_In::TEXT_DOMAIN); ?></a>

		</header>

		<section id="wpmudev-hustle-content">

			<div class="wpmudev-row">

				<div class="wpmudev-col col-12">

					<div class="wpmudev-list">

						<div class="wpmudev-list--action">

							<div class="wpmudev-action--left">

								<select id="wpmudev-bulk-action" class="wpmudev-select">

									<option value=""><?php esc_attr_e( "Bulk Actions", Opt_In::TEXT_DOMAIN ); ?></option>
									<option value="delete" data-nonce="<?php echo esc_attr( wp_create_nonce('hustle_delete_module') ); ?>" ><?php esc_attr_e( "Delete", Opt_In::TEXT_DOMAIN ); ?></option>

								</select>

								<button id="wpmudev-bulk-action-button" class="wpmudev-button wpmudev-button-ghost"><?php esc_attr_e( "Apply", Opt_In::TEXT_DOMAIN ); ?></button>

							</div>

							<div class="wpmudev-action--right">

								<?php
								$count = count( $popups );

								if ( $count > 1 ) {
									$count_text = esc_attr__("results", Opt_In::TEXT_DOMAIN);
								} else {
									$count_text = esc_attr__("result", Opt_In::TEXT_DOMAIN);
								}
								?>

								<p><?php printf( '%1$s %2$s', esc_html( $count ), esc_html( $count_text ) ); ?></p>

							</div>

						</div>

						<div class="wpmudev-list--header">

							<div class="wpmudev-header--check">

								<div class="wpmudev-input_checkbox">

									<input id="wph-all-popups" type="checkbox">

									<label for="wph-all-popups" class="wpdui-fi wpdui-fi-check"></label>

								</div>

							</div>

							<div class="wpmudev-header--name"><?php esc_attr_e( "Pop-up title", Opt_In::TEXT_DOMAIN ); ?></div>

							<div class="wpmudev-header--email"><?php esc_attr_e( "Email service", Opt_In::TEXT_DOMAIN ); ?></div>

							<div class="wpmudev-header--conditions"><?php esc_attr_e( "Display conditions", Opt_In::TEXT_DOMAIN ); ?></div>

							<div class="wpmudev-header--views"><?php esc_attr_e( "Views", Opt_In::TEXT_DOMAIN ); ?></div>

							<div class="wpmudev-header--conversions"><?php esc_attr_e( "Conversions", Opt_In::TEXT_DOMAIN ); ?></div>

							<div class="wpmudev-header--rate"><?php esc_attr_e( "Conv. rate", Opt_In::TEXT_DOMAIN ); ?></div>

							<div class="wpmudev-header--status"><?php esc_attr_e( "Pop-up status", Opt_In::TEXT_DOMAIN ); ?></div>

							<div class="wpmudev-header--settings"></div>

						</div>

						<div class="wpmudev-list--section">

						<?php
						wp_nonce_field("hustle_get_emails_list", "hustle_get_emails_list_nonce");

						foreach( $popups as $key => $module ) :
							?>

								<div class="wpmudev-list--element">

									<div class="wpmudev-element--check">

										<div class="wpmudev-input_checkbox">

											<input id="wph-popup-<?php echo esc_attr( $module->id ); ?>" class="wph-module-checkbox" type="checkbox" data-id="<?php echo esc_attr( $module->id ); ?>" >

											<label for="wph-popup-<?php echo esc_attr( $module->id ); ?>" class="wpdui-fi wpdui-fi-check" aria-hidden="true"></label>

										</div>

									</div>

									<div class="wpmudev-element--name">

										<p class="wpmudev-element--content"><a href="<?php echo esc_url( $module->decorated->get_edit_url( Hustle_Module_Admin::POPUP_WIZARD_PAGE, '' ) ); ?>"><?php echo esc_html( $module->module_name ); ?></a></p>

									</div>

									<div class="wpmudev-element--email">

										<p class="wpmudev-element--title"><?php esc_attr_e( "Email service", Opt_In::TEXT_DOMAIN ); ?>:</p>

										<p class="wpmudev-element--content"><?php echo (int) $module->test_mode  ? '–' : esc_html( $module->decorated->mail_service_label ); ?></p>

									</div>

									<div class="wpmudev-element--conditions">

										<p class="wpmudev-element--title"><?php esc_attr_e( "Display conditions", Opt_In::TEXT_DOMAIN ); ?>:</p>

										<p class="wpmudev-element--content"><?php echo esc_html( $module->decorated->get_condition_labels(false) ); ?></p>

									</div>

									<div class="wpmudev-element--views">

										<p class="wpmudev-element--title"><?php esc_attr_e( "Views", Opt_In::TEXT_DOMAIN ); ?>:</p>

										<p class="wpmudev-element--content"><?php echo esc_html(  $module->get_statistics($module->module_type)->views_count ); ?></p>

									</div>

									<div class="wpmudev-element--conversions">

										<p class="wpmudev-element--title"><?php esc_attr_e( "Conversions", Opt_In::TEXT_DOMAIN ); ?>:</p>

										<p class="wpmudev-element--content"><?php echo esc_html( $module->get_statistics($module->module_type)->conversions_count ); ?></p>

									</div>

									<div class="wpmudev-element--rate">

										<p class="wpmudev-element--title"><?php esc_attr_e( "Conv. rate", Opt_In::TEXT_DOMAIN ); ?>:</p>

										<p class="wpmudev-element--content"><?php echo esc_html( $module->get_statistics($module->module_type)->conversion_rate ); ?>%</p>

									</div>

									<div class="wpmudev-element--status">

										<p class="wpmudev-element--title"><?php printf( esc_attr__( "%s status", Opt_In::TEXT_DOMAIN ), esc_html( $module->module_name ) ); ?>:</p>

										<div class="wpmudev-element--content">

											<div class="wpmudev-tabs">

												<ul class="wpmudev-tabs-menu wpmudev-tabs-menu_full">

													<li class="wpmudev-tabs-menu_item <?php echo ( !$module->active && !$module->is_test_type_active( $module->module_type ) ) ? 'current' : ''; ?>">
														<input id="wph-module-<?php echo esc_html( $module->id ); ?>-status--off" type="radio" value="off" name="wph-module-status" data-nonce="<?php echo esc_attr( wp_create_nonce('popup_module_toggle_state') ); ?>" data-id="<?php echo esc_attr($module->id); ?>">
														<label for="wph-module-<?php echo esc_html( $module->id ); ?>-status--off" class="wpmudev-status-off"><?php esc_attr_e( "Off", Opt_In::TEXT_DOMAIN ); ?></label>
													</li>

													<li class="wpmudev-tabs-menu_item <?php echo ( $module->is_test_type_active( $module->module_type ) ) ? 'current' : ''; ?>">
														<input id="wph-module-<?php echo esc_html( $module->id ); ?>-status--test" type="radio" value="test" name="wph-module-status" data-nonce="<?php echo esc_attr( wp_create_nonce('popup_toggle_test_activity') ); ?>" data-type="<?php echo esc_attr($module->module_type); ?>" data-id="<?php echo esc_attr($module->id); ?>" >
														<label for="wph-module-<?php echo esc_html( $module->id ); ?>-status--test" class="wpmudev-status-test"><?php esc_attr_e( "Test", Opt_In::TEXT_DOMAIN ); ?></label>
													</li>

													<li class="wpmudev-tabs-menu_item <?php echo ( $module->active && !$module->is_test_type_active( $module->module_type ) ) ? 'current' : ''; ?>">
														<input id="wph-module-<?php echo esc_html( $module->id ); ?>-status--live" type="radio" value="live" name="wph-module-status" data-nonce="<?php echo esc_attr( wp_create_nonce('popup_module_toggle_state') ); ?>" data-id="<?php echo esc_attr($module->id); ?>">
														<label for="wph-module-<?php echo esc_html( $module->id ); ?>-status--live" class="wpmudev-status-live"><?php esc_attr_e( "Live", Opt_In::TEXT_DOMAIN ); ?></label>
													</li>

												</ul>

											</div>

										</div>

									</div>

									<div class="wpmudev-element--settings">

										<p class="wpmudev-element--title"><?php esc_attr_e( "Pop-up status", Opt_In::TEXT_DOMAIN ); ?></p>

										<div class="wpmudev-element--content">

											<div class="wpmudev-dots-dropdown">

												<button class="wpmudev-dots-button"><span></span></button>

												<ul class="wpmudev-dots-nav wpmudev-hide">

													<li><a href="<?php echo esc_url( $module->decorated->get_edit_url( Hustle_Module_Admin::POPUP_WIZARD_PAGE, '' ) ); ?>"><?php esc_attr_e( "Edit Pop-Up", Opt_In::TEXT_DOMAIN ); ?></a></li>
													<?php if( $module->get_total_subscriptions() ) : ?>
														<li><a href="#" class="button-view-email-list" data-total="<?php echo esc_attr( $module->get_total_subscriptions() ); ?>" data-id="<?php echo esc_attr( $module->id ); ?>" data-name="<?php echo esc_attr( $module->module_name ); ?>" ><?php esc_attr_e( "View email list", Opt_In::TEXT_DOMAIN ); ?></a></li>
													<?php endif; ?>
													<?php $log_count = $module->get_total_log_errors(); ?>
													<?php if ( $log_count ) : ?>
														<li><a href="#" class="button-view-log-list" data-total="<?php echo esc_attr( $log_count ); ?>" data-id="<?php echo esc_attr( $module->id ); ?>" data-name="<?php echo esc_attr( $module->module_name ); ?>" ><?php esc_attr_e( "View error log", Opt_In::TEXT_DOMAIN ); ?></a></li>
													<?php endif; ?>
														<li><a href="#" class="module-toggle-tracking-activity" data-id="<?php echo esc_attr( $module->id ); ?>" data-type="<?php echo esc_attr( $module->module_type ); ?>" <?php checked( $module->is_track_type_active( $module->module_type ), true); ?> data-nonce="<?php echo esc_attr( wp_create_nonce('popup_toggle_tracking_activity') ); ?>" data-current="<?php echo esc_attr( $module->is_track_type_active( $module->module_type ) ); ?>" ><?php ( $module->is_track_type_active( $module->module_type ) ) ? esc_attr_e( "Disable tracking", Opt_In::TEXT_DOMAIN ) : esc_attr_e( "Enable tracking", Opt_In::TEXT_DOMAIN ); ?></a></li>
														<li><a href="#" class="module-duplicate" data-id="<?php echo esc_attr( $module->id ); ?>" data-type="<?php echo esc_attr( $module->module_type ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('duplicate_popup') ); ?>" ><?php esc_attr_e( "Duplicate", Opt_In::TEXT_DOMAIN ); ?></a></li>
														<?php
															/**
															 * single optin export
															 */
															$action = Opt_In::EXPORT_MODULE_ACTION;
															$nonce = wp_create_nonce( $action );
															$url = add_query_arg(
																array(
																	'page' => Hustle_Module_Admin::POPUP_LISTING_PAGE,
																	'action' => $action,
																	'id' => $module->id,
																	'type' => $module->module_type,
																	Opt_In::EXPORT_MODULE_ACTION => $nonce,
																),
																admin_url( 'admin.php' )
															);
															$url = wp_nonce_url( $url, $action, $nonce );
														?>
														<li><a href="<?php echo esc_url( $url ); ?>"><?php esc_attr_e( "Export module settings", Opt_In::TEXT_DOMAIN ); ?></a></li>
														<li><a href="#" class="import-module-settings" data-id="<?php echo esc_attr( $module->id ); ?>" data-name="<?php echo esc_attr( $module->module_name ); ?>" data-type="<?php echo esc_attr( $module->module_type ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('import_settings' . $module->id ) ); ?>"><?php esc_attr_e( "Import module settings", Opt_In::TEXT_DOMAIN ); ?></a></li>
													<li><a href="#" class="hustle-delete-module" data-nonce="<?php echo esc_attr( wp_create_nonce('hustle_delete_module') ); ?>" data-id="<?php echo esc_attr( $module->id ); ?>" ><?php esc_attr_e( "Delete Pop-Up", Opt_In::TEXT_DOMAIN ); ?></a></li>

												</ul>

											</div>

										</div>

									</div>

							</div>

						<?php endforeach; ?>

					</div>

				</div>

			</div>

		</section>

		<?php //if( ! is_null( $new_module ) && count( $popups ) === 1 ) $this->render("admin/new-module_success", array( 'new_module' => $new_module, 'types' => $types )); ?>

		<?php $this->render("admin/commons/listing/modal-error"); ?>

		<?php $this->render("admin/commons/listing/modal-email"); ?>

		<?php $this->render("admin/commons/listing/modal-import"); ?>

		<?php $this->render("admin/commons/listing/delete-confirmation"); ?>

		<?php if ( $is_free && count( $popups ) >= 3 ) $this->render("admin/commons/listing/modal-upgrade"); ?>

	</main>

<?php } ?>

<?php $this->render( 'admin/footer/footer-simple' ); ?>