<?php
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");

	if (isset($_POST['hide_feup_review_box_hidden'])) {update_option('EWD_FEUP_Hide_Dash_Review_Ask', sanitize_text_field($_POST['hide_feup_review_box_hidden']));}
	$hideReview = get_option('EWD_FEUP_Hide_Dash_Review_Ask');
	$Ask_Review_Date = get_option('EWD_FEUP_Ask_Review_Date');
	if ($Ask_Review_Date == "") {$Ask_Review_Date = get_option("EWD_FEUP_Install_Time") + 3600*24*4;}

	$Sql = "SELECT * FROM $ewd_feup_user_table_name ORDER BY User_Last_Login DESC LIMIT 0,10";
	$myrows = $wpdb->get_results($Sql);
?>


<!-- START NEW DASHBOARD -->

<div id="ewd-feup-dashboard-content-area">

	<div id="ewd-feup-dashboard-content-left">

		<?php if ($EWD_FEUP_Full_Version != "Yes" or get_option("EWD_FEUP_Trial_Happening") == "Yes") { ?>
			<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full">
				<div class="ewd-feup-dashboard-new-widget-box-top">
					<form method="post" action="admin.php?page=EWD-FEUP-options" class="ewd-feup-dashboard-key-widget">
						<input class="ewd-feup-dashboard-key-widget-input" name="Key" type="text" placeholder="<?php _e('Enter Product Key Here', 'front-end-only-users'); ?>">
						<input class="ewd-feup-dashboard-key-widget-submit" name="EWD_FEUP_Upgrade_To_Full" type="submit" value="<?php _e('UNLOCK PREMIUM', 'front-end-only-users'); ?>">
						<div class="ewd-feup-dashboard-key-widget-text">Don't have a key? Use the <a href="http://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" target="_blank">Upgrade Now</a> button above to purchase and unlock all premium features.</div>
					</form>
				</div>
			</div>
		<?php } ?>

		<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full" id="ewd-feup-dashboard-support-widget-box">
			<div class="ewd-feup-dashboard-new-widget-box-top">Get Support<span id="ewd-feup-dash-mobile-support-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-feup-dash-mobile-support-up-caret">&nbsp;&nbsp;&#9650;</span></div>
			<div class="ewd-feup-dashboard-new-widget-box-bottom">
				<ul class="ewd-feup-dashboard-support-widgets">
					<li>
						<a href="https://www.youtube.com/watch?v=3HI8-t8a1wA&list=PLEndQUuhlvSolfe-rIpI3eK_TmfeEDPeH" target="_blank">
							<img src="<?php echo plugins_url( '../images/ewd-support-icon-youtube.png', __FILE__ ); ?>">
							<div class="ewd-feup-dashboard-support-widgets-text">YouTube Tutorials</div>
						</a>
					</li>
					<li>
						<a href="https://wordpress.org/plugins/front-end-only-users/#faq" target="_blank">
							<img src="<?php echo plugins_url( '../images/ewd-support-icon-faqs.png', __FILE__ ); ?>">
							<div class="ewd-feup-dashboard-support-widgets-text">Plugin FAQs</div>
						</a>
					</li>
					<li>
						<a href="https://wordpress.org/support/plugin/front-end-only-users" target="_blank">
							<img src="<?php echo plugins_url( '../images/ewd-support-icon-forum.png', __FILE__ ); ?>">
							<div class="ewd-feup-dashboard-support-widgets-text">Support Forum</div>
						</a>
					</li>
					<li>
						<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/documentation-front-end-only-users/" target="_blank">
							<img src="<?php echo plugins_url( '../images/ewd-support-icon-documentation.png', __FILE__ ); ?>">
							<div class="ewd-feup-dashboard-support-widgets-text">Documentation</div>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full" id="ewd-feup-dashboard-optional-table">
			<div class="ewd-feup-dashboard-new-widget-box-top">Recent User Activity<span id="ewd-feup-dash-optional-table-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-feup-dash-optional-table-up-caret">&nbsp;&nbsp;&#9650;</span></div>
			<div class="ewd-feup-dashboard-new-widget-box-bottom">
				<table class='ewd-feup-overview-table wp-list-table widefat fixed striped posts'>
					<thead>
						<tr>
							<th><?php _e("Username", 'front-end-only-users'); ?></th>
							<th><?php _e("Last Login", 'front-end-only-users'); ?></th>
							<th><?php _e("Total Logins", 'front-end-only-users'); ?></th>
							<th><?php _e("Joined Date", 'front-end-only-users'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							if ($myrows) {
					  			foreach ($myrows as $User) {
									echo "<tr id='User-" . $User->User_ID ."'>";
									echo "<td class='name column-name'>";
									echo "<strong>";
									echo "<a class='row-title' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_User_Details&Selected=User&User_ID=" . $User->User_ID ."' title='Edit " . $User->Username . "</a></strong>";
									echo "<br />";
									echo "<div class='username'>" . $User->Username . "</div>";
									echo "</td>";
									echo "<td class='description column-last-login'>" . $User->User_Last_Login . "</td>";
									echo "<td class='description column-description'>" . $User->User_Total_Logins . "</td>";
									echo "<td class='users column-required'>" . $User->User_Date_Created . "</td>";
									echo "</tr>";
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="ewd-feup-dashboard-new-widget-box <?php echo ( ($hideReview != 'Yes' and $Ask_Review_Date < time()) ? 'ewd-widget-box-two-thirds' : 'ewd-widget-box-full' ); ?>">
			<div class="ewd-feup-dashboard-new-widget-box-top">What People Are Saying</div>
			<div class="ewd-feup-dashboard-new-widget-box-bottom">
				<ul class="ewd-feup-dashboard-testimonials">
					<?php $randomTestimonial = rand(0,2);
					if($randomTestimonial == 0){ ?>
						<li id="ewd-feup-dashboard-testimonial-one">
							<img src="<?php echo plugins_url( '../images/dash-asset-stars.png', __FILE__ ); ?>">
							<div class="ewd-feup-dashboard-testimonial-title">"Great Plugin!"</div>
							<div class="ewd-feup-dashboard-testimonial-author">- @sbielby</div>
							<div class="ewd-feup-dashboard-testimonial-text">I've been using this plugin for a while now and it's fantastic. Effective and easy to use with great support from the developer... <a href="https://wordpress.org/support/topic/great-plugin-15905/" target="_blank">read more</a></div>
						</li>
					<?php }
					if($randomTestimonial == 1){ ?>
						<li id="ewd-feup-dashboard-testimonial-two">
							<img src="<?php echo plugins_url( '../images/dash-asset-stars.png', __FILE__ ); ?>">
							<div class="ewd-feup-dashboard-testimonial-title">"Works Great! Support Team Incredible!"</div>
							<div class="ewd-feup-dashboard-testimonial-author">- @elisussman</div>
							<div class="ewd-feup-dashboard-testimonial-text">Everything worked great. I did need help with a feature for my website based on their plugin and the support team got back to me very quickly... <a href="https://wordpress.org/support/topic/works-great-support-team-incredible/" target="_blank">read more</a></div>
						</li>
					<?php }
					if($randomTestimonial == 2){ ?>
						<li id="ewd-feup-dashboard-testimonial-three">
							<img src="<?php echo plugins_url( '../images/dash-asset-stars.png', __FILE__ ); ?>">
							<div class="ewd-feup-dashboard-testimonial-title">"Easy, commercial and outstanding!"</div>
							<div class="ewd-feup-dashboard-testimonial-author">- @speechless</div>
							<div class="ewd-feup-dashboard-testimonial-text">Extremely easy to use, powerful and will let you create a user friendly registration for your visitors. Outstanding support from the Étoile Web Design team... <a href="https://wordpress.org/support/topic/easy-commercial-and-outstanding/" target="_blank">read more</a></div>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<?php if($hideReview != 'Yes' and $Ask_Review_Date < time()){ ?>
			<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-one-third">
				<div class="ewd-feup-dashboard-new-widget-box-top">Leave a review</div>
				<div class="ewd-feup-dashboard-new-widget-box-bottom">
					<div class="ewd-feup-dashboard-review-ask">
						<img src="<?php echo plugins_url( '../images/dash-asset-stars.png', __FILE__ ); ?>">
						<div class="ewd-feup-dashboard-review-ask-text">If you enjoy this plugin and have a minute, please consider leaving a 5-star review. Thank you!</div>
						<a href="https://wordpress.org/plugins/front-end-only-users/#reviews" class="ewd-feup-dashboard-review-ask-button" target="_blank">LEAVE A REVIEW</a>
						<form action="admin.php?page=EWD-FEUP-options" method="post">
							<input type="hidden" name="hide_feup_review_box_hidden" value="Yes">
							<input type="submit" name="hide_feup_review_box_submit" class="ewd-feup-dashboard-review-ask-dismiss" value="I've already left a review">
						</form>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if ($EWD_FEUP_Full_Version != "Yes" or get_option("EWD_FEUP_Trial_Happening") == "Yes") { ?>
			<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full" id="ewd-feup-dashboard-guarantee-widget-box">
				<div class="ewd-feup-dashboard-new-widget-box-top">
					<div class="ewd-feup-dashboard-guarantee">
						<div class="ewd-feup-dashboard-guarantee-title">14-Day 100% Money-Back Guarantee</div>
						<div class="ewd-feup-dashboard-guarantee-text">If you're not 100% satisfied with the premium version of our plugin - no problem. You have 14 days to receive a FULL REFUND. We're certain you won't need it, though. Lorem ipsum dolor sitamet, consectetuer adipiscing elit.</div>
					</div>
				</div>
			</div>
		<?php } ?>

	</div> <!-- left -->

	<div id="ewd-feup-dashboard-content-right">

		<?php if ($EWD_FEUP_Full_Version != "Yes" or get_option("EWD_FEUP_Trial_Happening") == "Yes") { ?>
			<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full" id="ewd-feup-dashboard-get-premium-widget-box">
				<div class="ewd-feup-dashboard-new-widget-box-top">Get Premium</div>
				<?php if(get_option("EWD_FEUP_Trial_Happening") == "Yes"){ 
					$trialExpireTime = get_option("EWD_FEUP_Trial_Expiry_Time");
					$currentTime = time();
					$trialTimeLeft = $trialExpireTime - $currentTime;
					$trialTimeLeftDays = ( date("d", $trialTimeLeft) ) - 1;
					$trialTimeLeftHours = date("H", $trialTimeLeft);
					?>
					<div class="ewd-feup-dashboard-new-widget-box-bottom">
						<div class="ewd-feup-dashboard-get-premium-widget-trial-time">
							<div class="ewd-feup-dashboard-get-premium-widget-trial-days"><?php echo $trialTimeLeftDays; ?><span>days</span></div>
							<div class="ewd-feup-dashboard-get-premium-widget-trial-hours"><?php echo $trialTimeLeftHours; ?><span>hours</span></div>
						</div>
						<div class="ewd-feup-dashboard-get-premium-widget-trial-time-left">LEFT IN TRIAL</div>
					</div>
				<?php } ?>
				<div class="ewd-feup-dashboard-new-widget-box-bottom">
					<div class="ewd-feup-dashboard-get-premium-widget-features-title"<?php echo ( get_option("EWD_FEUP_Trial_Happening") == "Yes" ? "style='padding-top: 20px;'" : ""); ?>>GET FULL ACCESS WITH OUR PREMIUM VERSION AND GET:</div>
					<ul class="ewd-feup-dashboard-get-premium-widget-features">
						<li>User Levels to Restrict Access</li>
						<li>Charge for Membership via PayPal</li>
						<li>Admin Approval &amp; Email Confirmation</li>
						<li>User Statistics</li>
						<li>+ More</li>
					</ul>
					<a href="http://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
					<?php if (!get_option("EWD_FEUP_Trial_Happening")) { ?>
						<form method="post" action="admin.php?page=EWD-FEUP-options">
							<input name="Key" type="hidden" value='EWD Trial'>
							<input name="EWD_FEUP_Upgrade_To_Full" type="hidden" value='EWD_FEUP_Upgrade_To_Full'>
							<button class="ewd-feup-dashboard-get-premium-widget-button ewd-feup-dashboard-new-trial-button">GET FREE 7-DAY TRIAL</button>
						</form>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full">
			<div class="ewd-feup-dashboard-new-widget-box-top">Goes Great With</div>
			<div class="ewd-feup-dashboard-new-widget-box-bottom">
				<ul class="ewd-feup-dashboard-other-plugins">
					<li>
						<a href="https://wordpress.org/plugins/ultimate-faqs/" target="_blank"><img src="<?php echo plugins_url( '../images/ewd-ufaq-icon.png', __FILE__ ); ?>"></a>
						<div class="ewd-feup-dashboard-other-plugins-text">
							<div class="ewd-feup-dashboard-other-plugins-title">Ultimate FAQs</div>
							<div class="ewd-feup-dashboard-other-plugins-blurb">An easy-to-use FAQ plugin that lets you create, order and publicize FAQs, with many styles and options!</div>
						</div>
					</li>
					<li>
						<a href="https://wordpress.org/plugins/order-tracking/" target="_blank"><img src="<?php echo plugins_url( '../images/ewd-otp-icon.png', __FILE__ ); ?>"></a>
						<div class="ewd-feup-dashboard-other-plugins-text">
							<div class="ewd-feup-dashboard-other-plugins-title">Status Tracking</div>
							<div class="ewd-feup-dashboard-other-plugins-blurb">Allows you to manage orders or projects quickly and easily by posting updates that can be viewed through the front-end of your site!</div>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full" id="ewd-feup-dashboard-one-click">
			<div class="ewd-feup-dashboard-new-widget-box-top">One-Click Installer<span id="ewd-feup-dash-one-click-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-feup-dash-one-click-up-caret">&nbsp;&nbsp;&#9650;</span></div>
			<div class="ewd-feup-dashboard-new-widget-box-bottom">
				<div id='ewd-feup-one-click-install'>
					<div class='ewd-feup-one-click-text'>Clich the button below to open the one-click installer</div>
					<a class="ewd-feup-one-click-install-div-load ewd-feup-one-click-button" onclick="ShowTab('OneClickInstall');">Open One-Click Installer</a>
				</div>
			</div>
		</div>

	</div> <!-- right -->	

</div> <!-- ewd-feup-dashboard-content-area -->

<?php if ($EWD_FEUP_Full_Version != "Yes" or get_option("EWD_FEUP_Trial_Happening") == "Yes") { ?>
	<div id="ewd-feup-dashboard-new-footer-one">
		<div class="ewd-feup-dashboard-new-footer-one-inside">
			<div class="ewd-feup-dashboard-new-footer-one-left">
				<div class="ewd-feup-dashboard-new-footer-one-title">What's Included in Our Premium Version?</div>
				<ul class="ewd-feup-dashboard-new-footer-one-benefits">
					<li>User Levels to Restrict Access</li>
					<li>Charge for Membership via PayPal</li>
					<li>User Email Address Confirmation</li>
					<li>Admin Approval of Users</li>
					<li>User Statistics</li>
					<li>Send Emails to One or All Users</li>
					<li>Import/Export Users</li>
					<li>Add a Captcha to Forms</li>
					<li>Facebook &amp; Twitter Integration</li>
					<li>Mailchimp List Integration</li>
					<li>Advanced Styling Options</li>
					<li>Email Support</li>
				</ul>
			</div>
			<div class="ewd-feup-dashboard-new-footer-one-buttons">
				<a class="ewd-feup-dashboard-new-upgrade-button" href="http://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" target="_blank">UPGRADE NOW</a>
			</div>
		</div>
	</div> <!-- ewd-feup-dashboard-new-footer-one -->
<?php } ?>	
<div id="ewd-feup-dashboard-new-footer-two">
	<div class="ewd-feup-dashboard-new-footer-two-inside">
		<img src="<?php echo plugins_url( '../images/ewd-logo-white.png', __FILE__ ); ?>" class="ewd-feup-dashboard-new-footer-two-icon">
		<div class="ewd-feup-dashboard-new-footer-two-blurb">
			At Etoile Web Design, we build reliable, easy-to-use WordPress plugins with a modern look. Rich in features, highly customizable and responsive, plugins by Etoile Web Design can be used as out-of-the-box solutions and can also be adapted to your specific requirements.
		</div>
		<ul class="ewd-feup-dashboard-new-footer-two-menu">
			<li>SOCIAL</li>
			<li><a href="https://www.facebook.com/EtoileWebDesign/" target="_blank">Facebook</a></li>
			<li><a href="https://twitter.com/EtoileWebDesign" target="_blank">Twitter</a></li>
			<li><a href="https://www.etoilewebdesign.com/blog/" target="_blank">Blog</a></li>
		</ul>
		<ul class="ewd-feup-dashboard-new-footer-two-menu">
			<li>SUPPORT</li>
			<li><a href="https://www.youtube.com/watch?v=3HI8-t8a1wA&list=PLEndQUuhlvSolfe-rIpI3eK_TmfeEDPeH" target="_blank">YouTube Tutorials</a></li>
			<li><a href="https://wordpress.org/support/plugin/front-end-only-users" target="_blank">Forums</a></li>
			<li><a href="http://www.etoilewebdesign.com/plugins/front-end-only-users/documentation-front-end-only-users/" target="_blank">Documentation</a></li>
			<li><a href="https://wordpress.org/plugins/front-end-only-users/#faq" target="_blank">FAQs</a></li>
		</ul>
	</div>
</div> <!-- ewd-feup-dashboard-new-footer-two -->

<!-- END NEW DASHBOARD -->