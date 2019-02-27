<div class="dev-box report-sale">
    <div class="box-title">
        <h3><?php _e( "Reporting", "defender-security" ) ?></h3>
        <a class="button button-green button-small"
           href="<?php echo \WP_Defender\Behavior\Utils::instance()->campaignURL('defender_filescanning_reports_upgrade_button') ?>" target="_blank"><?php _e( "Upgrade to Pro", "defender-security" ) ?></a>
    </div>
    <div class="box-content">
        <form method="post" class="">
            <div class="sale-overlay">

            </div>
            <div class="columns">
                <div class="column is-one-third">
                    <strong><?php _e( "Schedule scans", "defender-security" ) ?></strong>
                    <span class="sub">
                        <?php _e( "Configure Defender to automatically and regularly scan your website and email you reports.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="column">
                    <span class="toggle">
                        <input type="checkbox" class="toggle-checkbox" name="notification" value="1" id="chk1"/>
                        <label class="toggle-label" for="chk1"></label>
                    </span>
                    <label><?php _e( "Run regular scans & reports", "defender-security" ) ?></label>
                    <div class="clear mline"></div>
                    <div class="well well-white schedule-box">
                        <strong><?php _e( "Schedule", "defender-security" ) ?></strong>
                        <label><?php _e( "Frequency", "defender-security" ) ?></label>
                        <select name="frequency">
                            <option value="1"><?php _e( "Daily", "defender-security" ) ?></option>
                        </select>
                        <div class="days-container">
                            <label><?php _e( "Day of the week", "defender-security" ) ?></label>
                            <select name="day">
								<?php foreach ( \WP_Defender\Behavior\Utils::instance()->getDaysOfWeek() as $day ): ?>
                                    <option value="<?php echo $day ?>"><?php echo ucfirst( $day ) ?></option>
								<?php endforeach; ?>
                            </select>
                        </div>
                        <label><?php _e( "Time of day", "defender-security" ) ?></label>
                        <select name="time">
							<?php foreach ( \WP_Defender\Behavior\Utils::instance()->getTimes() as $time ): ?>
                                <option value="<?php echo $time ?>"><?php echo strftime( '%I:%M %p', strtotime( $time ) ) ?></option>
							<?php endforeach;; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="columns last">
                <div class="column is-one-third">
                    <strong><?php _e( "Email recipients", "defender-security" ) ?></strong>
                    <span class="sub">
                        <?php _e( "Choose which of your website’s users will receive scan report results to their email inboxes.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="column">
					<?php $email->renderInput() ?>
                </div>
            </div>
        </form>
        <div class="presale-text">
            <div>
				<?php printf( __( "Schedule automated file scanning and email reporting for all your websites. This feature is included in a WPMU DEV membership along with 100+ plugins, 24/7 support and lots of handy site management tools  – <a target='_blank' href=\"%s\">Try it all FREE today!</a>", "defender-security" ), \WP_Defender\Behavior\Utils::instance()->campaignURL('defender_filescanning_reports_upsell_link') ) ?>
            </div>
        </div>
    </div>
</div>
