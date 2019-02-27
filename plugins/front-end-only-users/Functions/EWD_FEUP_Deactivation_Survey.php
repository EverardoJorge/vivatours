<?php
add_action('current_screen', 'EWD_FEUP_Deactivation_Survey');
function EWD_FEUP_Deactivation_Survey() {
	if (in_array(get_current_screen()->id, array( 'plugins', 'plugins-network' ), true)) {
		add_action('admin_enqueue_scripts', 'EWD_FEUP_Enqueue_Deactivation_Scripts');
		add_action( 'admin_footer', 'EWD_FEUP_Deactivation_Survey_HTML'); 
	}
}

function EWD_FEUP_Enqueue_Deactivation_Scripts() {
	wp_enqueue_style('ewd-feup-deactivation-css', EWD_FEUP_CD_PLUGIN_URL . 'css/ewd-feup-plugin-deactivation.css');
	wp_enqueue_script('ewd-feup-deactivation-js', EWD_FEUP_CD_PLUGIN_URL . 'js/ewd-feup-plugin-deactivation.js', array('jquery'));

	wp_localize_script('ewd-feup-deactivation-js', 'ewd_feup_deactivation_data', array('site_url' => site_url()));
}

function EWD_FEUP_Deactivation_Survey_HTML() {
	$Install_Time = get_option("EWD_FEUP_Install_Time");

	$options = array(
		1 => array(
			'title'   => esc_html__( 'I no longer need the plugin', 'front-end-only-users' ),
		),
		2 => array(
			'title'   => esc_html__( 'I\'m switching to a different plugin', 'front-end-only-users' ),
			'details' => esc_html__( 'Please share which plugin', 'front-end-only-users' ),
		),
		3 => array(
			'title'   => esc_html__( 'I couldn\'t get the plugin to work', 'front-end-only-users' ),
			'details' => esc_html__( 'Please share what wasn\'t working', 'front-end-only-users' ),
		),
		4 => array(
			'title'   => esc_html__( 'It\'s a temporary deactivation', 'front-end-only-users' ),
		),
		5 => array(
			'title'   => esc_html__( 'Other', 'front-end-only-users' ),
			'details' => esc_html__( 'Please share the reason', 'front-end-only-users' ),
		),
	);
	?>
	<div class="ewd-feup-deactivate-survey-modal" id="ewd-feup-deactivate-survey-ultimate-faqs">
		<div class="ewd-feup-deactivate-survey-wrap">
			<form class="ewd-feup-deactivate-survey" method="post" data-installtime="<?php echo $Install_Time; ?>">
				<span class="ewd-feup-deactivate-survey-title"><span class="dashicons dashicons-testimonial"></span><?php echo ' ' . __( 'Quick Feedback', 'front-end-only-users' ); ?></span>
				<span class="ewd-feup-deactivate-survey-desc"><?php echo __('If you have a moment, please share why you are deactivating Front-End Only Users:', 'front-end-only-users' ); ?></span>
				<div class="ewd-feup-deactivate-survey-options">
					<?php foreach ( $options as $id => $option ) : ?>
						<div class="ewd-feup-deactivate-survey-option">
							<label for="ewd-feup-deactivate-survey-option-ultimate-faqs-<?php echo $id; ?>" class="ewd-feup-deactivate-survey-option-label">
								<input id="ewd-feup-deactivate-survey-option-ultimate-faqs-<?php echo $id; ?>" class="ewd-feup-deactivate-survey-option-input" type="radio" name="code" value="<?php echo $id; ?>" />
								<span class="ewd-feup-deactivate-survey-option-reason"><?php echo $option['title']; ?></span>
							</label>
							<?php if ( ! empty( $option['details'] ) ) : ?>
								<input class="ewd-feup-deactivate-survey-option-details" type="text" placeholder="<?php echo $option['details']; ?>" />
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="ewd-feup-deactivate-survey-footer">
					<button type="submit" class="ewd-feup-deactivate-survey-submit button button-primary button-large"><?php _e('Submit and Deactivate', 'front-end-only-users' ); ?></button>
					<a href="#" class="ewd-feup-deactivate-survey-deactivate"><?php _e('Skip and Deactivate', 'front-end-only-users' ); ?></a>
				</div>
			</form>
		</div>
	</div>
	<?php
}

?>