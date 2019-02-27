<?php
/* Add any update or error notices to the top of the admin page */
function EWD_UWPM_Error_Notices(){
    global $ewd_uwpm_message;
	if (isset($ewd_uwpm_message)) {
		if (isset($ewd_uwpm_message['Message_Type']) and $ewd_uwpm_message['Message_Type'] == "Update") {echo "<div class='updated'><p>" . $ewd_uwpm_message['Message'] . "</p></div>";}
		if (isset($ewd_uwpm_message['Message_Type']) and $ewd_uwpm_message['Message_Type'] == "Error") {echo "<div class='error'><p>" . $ewd_uwpm_message['Message'] . "</p></div>";}
	}

	if( get_transient( 'ewd-uwpm-admin-install-notice' ) ){ ?>
		<div class="updated notice is-dismissible">
            <p>Head over to the <a href="admin.php?page=EWD-UWPM-Options">Ultimate WP Mail</a> to get started using the plugin!</p>
        </div>

        <?php
        delete_transient( 'ewd-uwpm-admin-install-notice' );
	}

	$Ask_Review_Date = get_option('EWD_UWPM_Ask_Review_Date');
	if ($Ask_Review_Date == "") {$Ask_Review_Date = get_option("EWD_UWPM_Install_Time") + 3600*24*4;}

	if ($Ask_Review_Date < time() and get_option("EWD_UWPM_Install_Time") < time() - 3600*24*4) {

		global $pagenow;
		if($pagenow != 'post.php' && $pagenow != 'post-new.php'){ ?>

			<div class='notice notice-info is-dismissible ewd-uwpm-main-dashboard-review-ask' style='display:none'>
				<div class='ewd-uwpm-review-ask-plugin-icon'></div>
				<div class='ewd-uwpm-review-ask-text'>
					<p class='ewd-uwpm-review-ask-starting-text'>Enjoying using the Ultimate WP Mail plugin?</p>
					<p class='ewd-uwpm-review-ask-feedback-text uwpm-hidden'>Help us make the plugin better! Please take a minute to rate the plugin. Thanks!</p>
					<p class='ewd-uwpm-review-ask-review-text uwpm-hidden'>Please let us know what we could do to make the plugin better!<br /><span>(If you would like a response, please include your email address.)</span></p>
					<p class='ewd-uwpm-review-ask-thank-you-text uwpm-hidden'>Thank you for taking the time to help us!</p>
				</div>
				<div class='ewd-uwpm-review-ask-actions'>
					<div class='ewd-uwpm-review-ask-action ewd-uwpm-review-ask-not-really ewd-uwpm-review-ask-white'>Not Really</div>
					<div class='ewd-uwpm-review-ask-action ewd-uwpm-review-ask-yes ewd-uwpm-review-ask-green'>Yes!</div>
					<div class='ewd-uwpm-review-ask-action ewd-uwpm-review-ask-no-thanks ewd-uwpm-review-ask-white uwpm-hidden'>No Thanks</div>
					<a href='https://wordpress.org/support/plugin/ultimate-wp-mail/reviews/?filter=5' target='_blank'>
						<div class='ewd-uwpm-review-ask-action ewd-uwpm-review-ask-review ewd-uwpm-review-ask-green uwpm-hidden'>OK, Sure</div>
					</a>
				</div>
				<div class='ewd-uwpm-review-ask-feedback-form uwpm-hidden'>
					<div class='ewd-uwpm-review-ask-feedback-explanation'>
						<textarea></textarea>
						<br>
						<input type="email" name="feedback_email_address" placeholder="<?php _e('Email Address', 'ultimate-wp-mail'); ?>">
					</div>
					<div class='ewd-uwpm-review-ask-send-feedback ewd-uwpm-review-ask-action ewd-uwpm-review-ask-green'>Send Feedback</div>
				</div>
				<div class='ewd-uwpm-clear'></div>
			</div>

			<?php
		}
	}

}



