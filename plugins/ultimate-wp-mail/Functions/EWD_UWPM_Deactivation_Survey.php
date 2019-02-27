<?php
add_action('current_screen', 'EWD_UWPM_Deactivation_Survey');
function EWD_UWPM_Deactivation_Survey() {
	if (in_array(get_current_screen()->id, array( 'plugins', 'plugins-network' ), true)) {
		add_action('admin_enqueue_scripts', 'EWD_UWPM_Enqueue_Deactivation_Scripts');
		add_action( 'admin_footer', 'EWD_UWPM_Deactivation_Survey_HTML'); 
	}
}

function EWD_UWPM_Enqueue_Deactivation_Scripts() {
	wp_enqueue_style('ewd-uwpm-deactivation-css', EWD_UWPM_CD_PLUGIN_URL . 'css/ewd-uwpm-plugin-deactivation.css');
	wp_enqueue_script('ewd-uwpm-deactivation-js', EWD_UWPM_CD_PLUGIN_URL . 'js/ewd-uwpm-plugin-deactivation.js', array('jquery'));

	wp_localize_script('ewd-uwpm-deactivation-js', 'ewd_uwpm_deactivation_data', array('site_url' => site_url()));
}

function EWD_UWPM_Deactivation_Survey_HTML() {
	$Install_Time = get_option("EWD_UWPM_Install_Time");

	$options = array(
		1 => array(
			'title'   => esc_html__( 'I no longer need the plugin', 'ultimate-wp-mail' ),
		),
		2 => array(
			'title'   => esc_html__( 'I\'m switching to a different plugin', 'ultimate-wp-mail' ),
			'details' => esc_html__( 'Please share which plugin', 'ultimate-wp-mail' ),
		),
		3 => array(
			'title'   => esc_html__( 'I couldn\'t get the plugin to work', 'ultimate-wp-mail' ),
			'details' => esc_html__( 'Please share what wasn\'t working', 'ultimate-wp-mail' ),
		),
		4 => array(
			'title'   => esc_html__( 'It\'s a temporary deactivation', 'ultimate-wp-mail' ),
		),
		5 => array(
			'title'   => esc_html__( 'Other', 'ultimate-wp-mail' ),
			'details' => esc_html__( 'Please share the reason', 'ultimate-wp-mail' ),
		),
	);
	?>
	<div class="ewd-uwpm-deactivate-survey-modal" id="ewd-uwpm-deactivate-survey-ultimate-faqs">
		<div class="ewd-uwpm-deactivate-survey-wrap">
			<form class="ewd-uwpm-deactivate-survey" method="post" data-installtime="<?php echo $Install_Time; ?>">
				<span class="ewd-uwpm-deactivate-survey-title"><span class="dashicons dashicons-testimonial"></span><?php echo ' ' . __( 'Quick Feedback', 'ultimate-wp-mail' ); ?></span>
				<span class="ewd-uwpm-deactivate-survey-desc"><?php echo __('If you have a moment, please share why you are deactivating Ultimate WP Mail:', 'ultimate-wp-mail' ); ?></span>
				<div class="ewd-uwpm-deactivate-survey-options">
					<?php foreach ( $options as $id => $option ) : ?>
						<div class="ewd-uwpm-deactivate-survey-option">
							<label for="ewd-uwpm-deactivate-survey-option-ultimate-faqs-<?php echo $id; ?>" class="ewd-uwpm-deactivate-survey-option-label">
								<input id="ewd-uwpm-deactivate-survey-option-ultimate-faqs-<?php echo $id; ?>" class="ewd-uwpm-deactivate-survey-option-input" type="radio" name="code" value="<?php echo $id; ?>" />
								<span class="ewd-uwpm-deactivate-survey-option-reason"><?php echo $option['title']; ?></span>
							</label>
							<?php if ( ! empty( $option['details'] ) ) : ?>
								<input class="ewd-uwpm-deactivate-survey-option-details" type="text" placeholder="<?php echo $option['details']; ?>" />
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="ewd-uwpm-deactivate-survey-footer">
					<button type="submit" class="ewd-uwpm-deactivate-survey-submit button button-primary button-large"><?php _e('Submit and Deactivate', 'ultimate-wp-mail' ); ?></button>
					<a href="#" class="ewd-uwpm-deactivate-survey-deactivate"><?php _e('Skip and Deactivate', 'ultimate-wp-mail' ); ?></a>
				</div>
			</form>
		</div>
	</div>
	<?php
}

?>