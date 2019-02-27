<?php if ( ! $ajax_step ) : ?>
	<form class="hustle-unsubscribe-form">

		<div class="hustle-form-body">

			<div class="hustle-email-section">
				<input type="email"
					name="email"
					class="required"
					placeholder="john@doe.com" >
			</div>

			<button type="submit" class="hustle-unsub-button">
				<span class="hustle-loading-text"><?php echo esc_html( $messages['get_lists_button_text'] ); ?></span>
				<span class="hustle-loading-icon"></span>
			</button>
			<input type="hidden" name="form_step" value="enter_email">

			<input type="hidden" name="form_module_id" value="<?php echo esc_attr( $shortcode_attr_id ) ; ?>">
			<input type="hidden" name="current_url" value="<?php echo esc_attr( Opt_In_Utils::get_current_url() ); ?>">

		</div>

	</form>

<?php else : ?>

	<div class="hustle-email-lists">

	<?php foreach( $modules_id as $id ) : ?>	
		<label for="hustle-list-<?php echo esc_attr( $id ); ?>">
			<input type="checkbox" name="lists_id[]" value="<?php echo esc_attr( $id ); ?>" id="hustle-list-<?php echo esc_attr( $id ); ?>">
			<span><?php echo esc_html( $module->get( $id )->content->local_list_name ); ?></span>
		</label>

	<?php endforeach; ?>

	</div>
	<input type="hidden" name="form_step" value="choose_list">
	<input type="hidden" name="email" value="<?php echo esc_attr( $email ); ?>">
	<input type="hidden" name="current_url" value="<?php echo esc_attr( $current_url  ); ?>">
	<button type="submit" class="hustle-unsub-button">
		<span class="hustle-loading-text"><?php echo esc_html( $messages['submit_button_text'] ); ?></span>
		<span class="hustle-loading-icon"></span>
	</button>

<?php endif; ?>
