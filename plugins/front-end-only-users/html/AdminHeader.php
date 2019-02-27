		<div class="wrap">
		<div class="Header"><h2><?php _e("Front End Only Users Settings", 'front-end-only-users') ?></h2></div>

		<?php if ($EWD_FEUP_Full_Version != "Yes" or get_option("EWD_FEUP_Trial_Happening") == "Yes") { ?>
			<div class="ewd-feup-dashboard-new-upgrade-banner">
				<div class="ewd-feup-dashboard-banner-icon"></div>
				<div class="ewd-feup-dashboard-banner-buttons">
					<a class="ewd-feup-dashboard-new-upgrade-button" href="http://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" target="_blank">UPGRADE NOW</a>
				</div>
				<div class="ewd-feup-dashboard-banner-text">
					<div class="ewd-feup-dashboard-banner-title">
						GET FULL ACCESS WITH OUR PREMIUM VERSION
					</div>
					<div class="ewd-feup-dashboard-banner-brief">
						Experience the user management and membership plugin that allows for front-end user registration and login
					</div>
				</div>
			</div>
		<?php } ?>