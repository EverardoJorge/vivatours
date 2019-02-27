<?php
/* Add any update or error notices to the top of the admin page */
function EWD_FEUP_Error_Notices(){
    global $feup_message;
	if (isset($feup_message)) {
		if (is_array($feup_message) and $feup_message['Message_Type'] == "Update") {echo "<div class='updated'><p>" . $feup_message['Message'] . "</p></div>";}
		if (is_array($feup_message) and $feup_message['Message_Type'] == "Error") {echo "<div class='error'><p>" . $feup_message['Message'] . "</p></div>";}
	} 

	if( get_transient( 'ewd-feup-admin-install-notice' ) ){ ?>
		<div class="updated notice is-dismissible">
            <p>Head over to the <a href="admin.php?page=EWD-FEUP-options">Front-End Only Users Dashboard</a> to get started using the plugin!</p>
        </div>

        <?php
        delete_transient( 'ewd-feup-admin-install-notice' );
	}

	$Ask_Review_Date = get_option('EWD_FEUP_Ask_Review_Date');
	if (get_option("EWD_FEUP_Install_Time") == "") {update_option("EWD_FEUP_Install_Time", time());}
	if ($Ask_Review_Date == "") {$Ask_Review_Date = get_option("EWD_FEUP_Install_Time") + 3600*24*4;}

	if ($Ask_Review_Date < time() and get_option("EWD_FEUP_Install_Time") < time() - 3600*24*4) {

		global $pagenow;
		if($pagenow != 'post.php' && $pagenow != 'post-new.php'){ ?>

			<div class='notice notice-info is-dismissible ewd-feup-main-dashboard-review-ask' style='display:none'>
				<div class='ewd-feup-review-ask-plugin-icon'></div>
				<div class='ewd-feup-review-ask-text'>
					<p class='ewd-feup-review-ask-starting-text'>Enjoying using the Front End Users plugin?</p>
					<p class='ewd-feup-review-ask-feedback-text feup-hidden'>Help us make the plugin better! Please take a minute to rate the plugin. Thanks!</p>
					<p class='ewd-feup-review-ask-review-text feup-hidden'>Please let us know what we could do to make the plugin better!<br /><span>(If you would like a response, please include your email address.)</span></p>
					<p class='ewd-feup-review-ask-thank-you-text feup-hidden'>Thank you for taking the time to help us!</p>
				</div>
				<div class='ewd-feup-review-ask-actions'>
					<div class='ewd-feup-review-ask-action ewd-feup-review-ask-not-really ewd-feup-review-ask-white'>Not Really</div>
					<div class='ewd-feup-review-ask-action ewd-feup-review-ask-yes ewd-feup-review-ask-green'>Yes!</div>
					<div class='ewd-feup-review-ask-action ewd-feup-review-ask-no-thanks ewd-feup-review-ask-white feup-hidden'>No Thanks</div>
					<a href='https://wordpress.org/support/plugin/front-end-only-users/reviews/?filter=5' target='_blank'>
						<div class='ewd-feup-review-ask-action ewd-feup-review-ask-review ewd-feup-review-ask-green feup-hidden'>OK, Sure</div>
					</a>
				</div>
				<div class='ewd-feup-review-ask-feedback-form feup-hidden'>
					<div class='ewd-feup-review-ask-feedback-explanation'>
						<textarea></textarea>
						<br>
						<input type="email" name="feedback_email_address" placeholder="<?php _e('Email Address', 'front-end-only-users'); ?>">
					</div>
					<div class='ewd-feup-review-ask-send-feedback ewd-feup-review-ask-action ewd-feup-review-ask-green'>Send Feedback</div>
				</div>
				<div class='ewd-feup-clear'></div>
			</div>

			<?php
		}
	}
}



