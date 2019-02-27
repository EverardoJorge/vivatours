<div id="wpmudev-settings-widget-unsubscribe" class="wpmudev-box  wpmudev-box-close">

	<div class="wpmudev-box-head">

		<h2><?php esc_attr_e( 'Unsubscribe', Opt_In::TEXT_DOMAIN ); ?></h2>

		<div class="wpmudev-box-action"><?php $this->render( 'general/icons/icon-plus' ); ?></div>

	</div>

	<div class="wpmudev-box-body">

		<label class="wpmudev-helper"><?php esc_html_e( 'By default, the Unsubscribe form works for all the modules. If you want to let visitors unsubscribe from specific modules only, add the comma separated module IDs in the ID attribute.', Opt_In::TEXT_DOMAIN ); ?></label>
		<label class="wpmudev-helper"><?php printf( esc_html__( 'You can find the module\'s ID in the URL of the module\'s wizard page: %s', Opt_In::TEXT_DOMAIN ), '<br>/wp-admin/admin.php?page=hustle_popup&<b>id=58</b>' ); ?></label>

		<input type="text" class="wpmudev-shortcode" value='[wd_hustle_unsubscribe id="" ]' readonly="readonly" />


		<form id="wpmudev-settings-unsubscribe-messages" data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_save_unsubscribe_messages_settings' ) ); ?>">

			<div class="wpmudev-switch-labeled">

				<div class="wpmudev-switch">
					<input type="checkbox"
						id="wph-unsub-edit-message"
						class="toggle-checkbox"
						value ="1"
						name="enabled"
						<?php echo ( '0' === (string) $messages['enabled'] ? '' : 'checked="checked"' ); ?>
						>
					<label class="wpmudev-switch-design" for="wph-unsub-edit-message" aria-hidden="true"></label>
				</div>

				<label class="wpmudev-switch-label" for="wph-unsub-edit-message"><?php esc_attr_e( 'Edit unsubscribe messages', Opt_In::TEXT_DOMAIN ); ?></label>

			</div>

			<div class="wpmudev-box-gray <?php echo ( '0' === (string) $messages['enabled'] ? 'wpmudev-hidden' : '' ); ?>">

				<?php
				$options = array(
					'button_text_label'                 => array(
						'id'    => 'submit-button-text-label',
						'for'   => 'submit-button-text',
						'type'  => 'label',
						'value' => __( 'Submit button text', Opt_In::TEXT_DOMAIN ),
					),
					'button_text'                       => array(
						'id'          => 'submit-button-text',
						'name'        => 'submit_button_text',
						'value'       => $messages['submit_button_text'],
						'placeholder' => '',
						'type'        => 'text',
						'class'       => 'wpmudev-input_text',
					),
					'get_lists_button_text_label'       => array(
						'id'    => 'lists-button-text-label',
						'for'   => 'lists-button-text',
						'type'  => 'label',
						'value' => __( 'Search lists button text', Opt_In::TEXT_DOMAIN ),
					),
					'get_lists_button_text'             => array(
						'id'          => 'lists-button-text',
						'name'        => 'get_lists_button_text',
						'value'       => $messages['get_lists_button_text'],
						'placeholder' => '',
						'type'        => 'text',
						'class'       => 'wpmudev-input_text',
					),
					'invalid_email_message_label'       => array(
						'id'    => 'invalid-email-message-label',
						'for'   => 'invalid-email',
						'type'  => 'label',
						'value' => __( 'Invalid email error message', Opt_In::TEXT_DOMAIN ),
					),
					'invalid_email_message'             => array(
						'id'    => 'invalid-email-message',
						'name'  => 'invalid_email',
						'type'  => 'text',
						'value' => $messages['invalid_email'],
						'class' => 'wpmudev-input_text',
					),
					'email_not_found_message_label'     => array(
						'id'    => 'iemail-not-found-message-label',
						'for'   => 'email-not-found-message',
						'type'  => 'label',
						'value' => __( 'Email not found message', Opt_In::TEXT_DOMAIN ),
					),
					'email_not_found_message'           => array(
						'id'    => 'email-not-found-message',
						'name'  => 'email_not_found',
						'type'  => 'text',
						'value' => $messages['email_not_found'],
						'class' => 'wpmudev-input_text',
					),
					'invalid_data_message_label'        => array(
						'id'    => 'invalid-data-message-label',
						'for'   => 'invalid-data-message',
						'type'  => 'label',
						'value' => __( 'Data not valid message', Opt_In::TEXT_DOMAIN ),
					),
					'invalid_data_message'              => array(
						'id'    => 'invalid-data-message',
						'name'  => 'invalid_data',
						'type'  => 'text',
						'value' => $messages['invalid_data'],
						'class' => 'wpmudev-input_text',
					),
					'email_not_submitted_message_label' => array(
						'id'    => 'email-not-submitted-message-label',
						'for'   => 'email-not-submitted-message',
						'type'  => 'label',
						'value' => __( 'Email couldn\'t be submitted message', Opt_In::TEXT_DOMAIN ),
					),
					'email_not_submitted_message'       => array(
						'id'    => 'email-not-submitted-message',
						'name'  => 'email_not_processed',
						'type'  => 'text',
						'value' => $messages['email_not_processed'],
						'class' => 'wpmudev-input_text',
					),
					'email_submitted_message_label'     => array(
						'id'    => 'email-submitted-message-label',
						'for'   => 'email-submitted-message',
						'type'  => 'label',
						'value' => __( 'Check your email for confirmation message', Opt_In::TEXT_DOMAIN ),
					),
					'email_submitted_message'           => array(
						'id'    => 'email-submitted-message',
						'name'  => 'email_submitted',
						'type'  => 'text',
						'value' => $messages['email_submitted'],
						'class' => 'wpmudev-input_text',
					),
					'successful_unsubscription_message_label' => array(
						'id'    => 'successful-unsubscription-message-label',
						'for'   => 'successful-unsubscription-message',
						'type'  => 'label',
						'value' => __( 'Successful unsubscription message', Opt_In::TEXT_DOMAIN ),
					),
					'successful_unsubscription_message' => array(
						'id'    => 'successful-unsubscription-message',
						'name'  => 'successful_unsubscription',
						'type'  => 'text',
						'value' => $messages['successful_unsubscription'],
						'class' => 'wpmudev-input_text',
					),
					'submit'                            => array(
						'id'    => 'unsub-messages-submit',
						'type'  => 'submit_button',
						'value' => '<span class="wpmudev-loading-text">' . __( 'Save', Opt_In::TEXT_DOMAIN ) . '</span><span class="wpmudev-loading"></span>',
						'class' => 'wpmudev-button wpmudev-button-blue wpmudev-button-sm',
					),
				);

				foreach ( $options as $key => $option ) {
					$this->render( 'general/option', $option );
				}
				?>

			</div>
		</form>

		<form id="wpmudev-settings-unsubscribe-email" data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_save_unsubscribe_email_settings' ) ); ?>">

			<div class="wpmudev-switch-labeled" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #E9E9E9;">

				<div class="wpmudev-switch">
					<input type="checkbox"
						id="wph-unsub-edit-email"
						class="toggle-checkbox"
						value ="1"
						name="enabled"
						<?php echo ( '0' === (string) $email['enabled'] ? '' : 'checked="checked"' ); ?>
						>
					<label class="wpmudev-switch-design" for="wph-unsub-edit-email" aria-hidden="true"></label>
				</div>

				<label class="wpmudev-switch-label" for="wph-unsub-edit-email"><?php esc_attr_e( 'Edit unsubscribe email', Opt_In::TEXT_DOMAIN ); ?></label>

			</div>

			<div class="wpmudev-box-gray <?php echo ( '0' === (string) $email['enabled'] ? 'wpmudev-hidden' : '' ); ?>">

				<?php
				$options = array(
					'email_subject_label' => array(
						'id' 	=> 'email-subject-label',
						'for' 	=> 'email-subject',
						'type' 	=> 'label',
						'value' => __( 'Email subject', Opt_In::TEXT_DOMAIN ),
					),
					'email_subject' => array(
						'id' 			=> 'email-subject',
						'name' 			=> 'email_subject',
						'value' 		=> $email['email_subject'],
						'placeholder' 	=> '',
						'type' 			=> 'text',
						'class'         => 'wpmudev-input_text',
					),
					'email_message_label' => array(
						'id' 	=> 'email-message-label',
						'for' 	=> 'email_message',
						'type' 	=> 'label',
						'value' =>sprintf( __( 'Email body. Use %s to add the unsubscription link.', Opt_In::TEXT_DOMAIN ), '{hustle_unsubscribe_link}'),
					),
				);

				foreach ( $options as $key => $option ) {
					$this->render( 'general/option', $option );
				}

				wp_editor( $email['email_body'], 'email_message', array(
					'textarea_name'    => 'email_message',
					'editor_css'       => '<style>
						.wp-editor-wrap { margin-bottom: 15px; }
						#wp-main_content-editor-tools { margin-bottom: 5px; }
						#wp-main_content-editor-tools .wp-media-buttons .insert-media {
							margin: 0 5px 0 0;
							border: 1px solid #E1E1E1;
							border-radius: 5px;
							-moz-border-radius: 5px;
							-webkit-border-radius: 5px;
							background: #FAFAFA;
							box-shadow: none;
							-moz-box-shadow: none;
							-webkit-box-shadow: none;
							color: #6E6E6E;
							font: 500 13px/24px "Roboto", "Open Sans", Arial, sans-serif;
							text-align: center;
						}
						#wp-main_content-editor-tools .wp-media-buttons .insert-media:hover,
						#wp-main_content-editor-tools .wp-media-buttons .insert-media:focus {
							border-color: #E1E1E1;
							background: #EEE;
							box-shadow: none;
							-moz-box-shadow: none;
							-webkit-box-shadow: none;
							color: #6E6E6E;
						}
						#wp-main_content-editor-tools .wp-media-buttons .button:active {
							top: auto;
							margin-top: 0;
							margin-bottom: 0;
							border-color: #E1E1E1;
							background: #EEE;
							box-shadow: none;
							-moz-box-shadow: none;
							-webkit-box-shadow: none;
							transform: translateY(0);
							-moz-transform: translateY(0);
							-webkit-transform: translateY(0);
							color: #333;
						}
						#wp-main_content-editor-tools .wp-media-buttons span.wp-media-buttons-icon {
							width: 16px;
							height: 16px;
							position: relative;
							vertical-align: text-bottom;
							top: 1px;
							color: #6E6E6E;
						}
						#wp-main_content-editor-tools .wp-media-buttons .add_media span.wp-media-buttons-icon:before {
							font-size: 14px;
						}
						#wp-main_content-editor-tools .wp-media-buttons .button:active span.wp-media-buttons-icon,
						#wp-main_content-editor-tools .wp-media-buttons .button:active span.wp-media-buttons-icon:before {
							color: #333;
						}
						#wp-main_content-editor-tools .wp-switch-editor {
							width: 58px;
							height: auto;
							top: 0;
							margin: 0 0 0 5px;
							padding: 0 5px;
							border: 1px solid #E1E1E1;
							border-radius: 5px;
							-moz-border-radius: 5px;
							-webkit-border-radius: 5px;
							background: #FAFAFA;
							color: #6E6E6E;
							font: 500 13px/26px "Roboto", "Open Sans", Arial, sans-serif;
							text-align: center;
							transition: .2s ease-in;
							-moz-transition: .2s ease-in;
							-webkit-transition: .2s ease-in;
						}
						#wp-main_content-editor-tools .wp-switch-editor:hover,
						#wp-main_content-editor-tools .wp-switch-editor:focus {
							border-color: #E1E1E1;
							background: #EEE;
							color: #6E6E6E;
						}
						#wp-main_content-editor-tools .wp-switch-editor:first-child {
							margin: 0;
						}
						.html-active #wp-main_content-editor-tools .switch-html,
						.tmce-active #wp-main_content-editor-tools .switch-tmce {
							border-color: #E1E1E1;
							border-bottom-color: #E1E1E1;
							background: #EEE;
							color: #333;
						}
						#wp-main_content-editor-container {
							border: 1px solid #E1E1E1;
							border-radius: 5px;
							-moz-border-radius: 5px;
							-webkit-border-radius: 5px;
						}
						#wp-main_content-editor-container div.mce-panel { border-radius: 5px; }
						#wp-main_content-editor-container div.mce-toolbar-grp {
							border-bottom: 1px solid #E1E1E1;
							border-radius: 5px 5px 0 0;
							-moz-border-radius: 5px 5px 0 0;
							-webkit-border-radius: 5px 5px 0 0;
							background: #FAFAFA;
						}
						#wp-main_content-editor-container .mce-toolbar .mce-ico {
							color: #888;
						}
						#wp-main_content-editor-container .mce-toolbar .mce-btn-group .mce-btn.mce-listbox {
							border: 1px solid #DDD;
							border-radius: 3px;
							-moz-border-radius: 3px;
							-webkit-border-radius: 3px;
							background: #FAFAFA;
							box-shadow: none;
							-moz-box-shadow: none;
							-webkit-box-shadow: none;
						}
						#wp-main_content-editor-container .mce-toolbar .mce-btn-group .mce-btn:focus,
						#wp-main_content-editor-container .mce-toolbar .mce-btn-group .mce-btn:hover,
						#wp-main_content-editor-container .qt-dfw:focus, .qt-dfw:hover {
							border-color: #888;
							box-shadow: none;
							-moz-box-shadow: none;
							-webkit-box-shadow: none;
							color: #888;
						}
						#wp-main_content-editor-container .mce-toolbar .mce-btn-group .mce-menubtn.mce-fixed-width button {
							border-radius: 3px;
							-moz-border-radius: 3px;
							-webkit-border-radius: 3px;
						}
						#wp-main_content-editor-container .mce-toolbar .mce-btn-group .mce-menubtn.mce-fixed-width span {
							color: #6E6E6E;
							font-family: "Roboto", Arial, sans-serif;
							font-weight: 500;
							letter-spacing: -0.22px;
						}
						#wp-main_content-editor-container .mce-panel .mce-btn i.mce-caret {
							border-top-color: #888;
						}
						#wp-main_content-editor-container .mce-panel .mce-active i.mce-caret {
							border-bottom-color: #888;
						}
						#wp-main_content-editor-container div.mce-statusbar {
							border-top: 1px solid #E1E1E1;
							border-radius: 0 0 5px 5px;
							-moz-border-radius: 0 0 5px 5px;
							-webkit-border-radius: 0 0 5px 5px;
							background: #FAFAFA;
						}
						#wp-main_content-editor-container .quicktags-toolbar {
							border-bottom: 1px solid #E1E1E1;
							border-radius: 5px 5px 0 0;
							-moz-border-radius: 5px 5px 0 0;
							-webkit-border-radius: 5px 5px 0 0;
							background: #FAFAFA;
						}
						#wp-main_content-editor-container .quicktags-toolbar .button {
							border-color: #E1E1E1;
							background: #FAFAFA;
							box-shadow: none;
							-moz-box-shadow: none;
							-webkit-box-shadow: none;
							color: #6E6E6E;
							letter-spacing: -0.22px;
							font-family: "Roboto", "Open Sans", Arial;
							font-weight: 500;
						}
						#wp-main_content-editor-container .quicktags-toolbar .button:hover,
						#wp-main_content-editor-container .quicktags-toolbar .button:focus {
							border-color: #E1E1E1;
							background: #EEE;
							color: #6E6E6E;
						}
						#wp-main_content-editor-container .quicktags-toolbar .button:active {
							border: 1px solid #E1E1E1;
							background: #EEE;
							transform: translateY(0);
							color: #333;
							font-family: "Roboto", "Open Sans", Arial;
							font-weight: 500;
						}
						#wp-main_content-editor-container textarea.wp-editor-area {
							border-radius: 0 0 5px 5px;
							-moz-border-radius: 0 0 5px 5px;
							-webkit-border-radius: 0 0 5px 5px;
						}
					</style>',
					'editor_height' => 130,
					'drag_drop_upload' => true,
				) ); ?>
				<br>
				<?php
				$submit = array(
					'id' 	=> 'mail-submit',
					'type' 	=> 'submit_button',
					'value' => '<span class="wpmudev-loading-text">' . __( 'Save', Opt_In::TEXT_DOMAIN ) . '</span><span class="wpmudev-loading"></span>',
					'class' => 'wpmudev-button wpmudev-button-blue wpmudev-button-sm',
				);
				$this->render( 'general/option', $submit );
				?>

			</div>

		</form>

	</div>

</div>
