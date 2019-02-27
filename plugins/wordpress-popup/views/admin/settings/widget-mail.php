<div id="wpmudev-settings-widget-email" class="wpmudev-box wpmudev-box-close">

    <div class="wpmudev-box-head">

        <h2><?php esc_attr_e( "Email options", Opt_In::TEXT_DOMAIN ); ?></h2>

        <div class="wpmudev-box-action"><?php $this->render("general/icons/icon-plus" ); ?></div>

    </div>

    <div class="wpmudev-box-body">

		<?php 
		$options = array(
			'name_label' => array(
				'id' 	=> 'mail-name-label',
				'for' 	=> 'mail-name',
				'type' 	=> 'label',
				'value' => __( 'Sender name', Opt_In::TEXT_DOMAIN ),
			),
			'name_field' => array(
				'id' 			=> 'mail-name',
				'name' 			=> 'name',
				'value' 		=> $name,
				'placeholder' 	=> '',
				'type' 			=> 'text',
				'class'         => 'wpmudev-input_text',
			),
			'email_label' => array(
				'id' 	=> 'mail-email-label',
				'for' 	=> 'mail-email',
				'type' 	=> 'label',
				'value' => __( 'Sender email address', Opt_In::TEXT_DOMAIN ),
			),
			'email_field' => array(
				'id' 		=> 'mail-email',
				'name' 		=> 'email',
				'type' 		=> 'email',
				'value' 	=> $email,
				'class' 	=> 'wpmudev-input_text'
			),
			'submit' => array(
				'id' 	=> 'mail-submit',
				'type' 	=> 'submit_button',
				'value' => __( 'Save', Opt_In::TEXT_DOMAIN ),
				'class' => 'wpmudev-button wpmudev-button-sm',
			),
		);
		?>

		<form id="wpmudev-settings-mail-form" data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_save_global_email_settings' ) ); ?>">
			<?php
			foreach( $options as $key =>  $option ){
				$this->render( 'general/option', $option );
			}
			?>
			<br>&nbsp;
			
		</form>

    </div>

</div>
