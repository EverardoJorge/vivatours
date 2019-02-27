<div class="dev-box report-sale">
    <div class="box-title">
        <h3><?php esc_html_e( "Reporting", "defender-security" ) ?></h3>
        <a class="button button-green button-small"
           href="<?php echo \WP_Defender\Behavior\Utils::instance()->campaignURL('defender_iplockouts_reports_upgrade_button') ?>" target="_blank"><?php _e( "Upgrade to Pro", "defender-security" ) ?></a>
    </div>
    <div class="box-content">
        <form method="post" id="settings-frm" class="ip-frm">
            <div class="sale-overlay">

            </div>
            <div class="columns">
                <div class="column is-one-third">
                    <label>
						<?php esc_html_e( "Lockouts report", "defender-security" ) ?>
                    </label>
                    <span class="sub">
                        <?php esc_html_e( "Configure Defender to automatically email you a lockout report for this website. ", "defender-security" ) ?>
					</span>
                </div>
                <div class="column">
                    <span
                            tooltip="<?php echo esc_attr( __( "Send regular email report", "defender-security" ) ) ?>"
                            class="toggle float-l">
	                                        <input type="hidden" name="report" value="0"/>
                                <input type="checkbox"
                                       name="report"
                                       value="1" class="toggle-checkbox"
                                       id="toggle_report"/>
                                <label class="toggle-label" for="toggle_report"></label>
                                </span>
                    <label for="toggle_report" role="checkbox" aria-checked="true">
						<?php esc_html_e( "Send regular email report", "defender-security" ) ?>
                    </label>
                    <div class="clear mline"></div>
                    <div class="well well-white schedule-box">
                        <strong><?php esc_html_e( "SCHEDULE", "defender-security" ) ?></strong>
                        <label><?php esc_html_e( "Frequency", "defender-security" ) ?></label>
                        <select name="report_frequency">
                            <option value="1"><?php esc_html_e( "Daily", "defender-security" ) ?></option>
                        </select>
                        <div class="days-container">
                            <label><?php esc_html_e( "Day of the week", "defender-security" ) ?></label>
                            <select name="report_day">
								<?php foreach ( \WP_Defender\Behavior\Utils::instance()->getDaysOfWeek() as $day ): ?>
                                    <option value="<?php echo strtolower( $day ) ?>"><?php echo $day ?></option>
								<?php endforeach;; ?>
                            </select>
                        </div>
                        <label><?php esc_html_e( "Time of day", "defender-security" ) ?></label>
                        <select name="report_time">
							<?php foreach ( \WP_Defender\Behavior\Utils::instance()->getTimes() as $timestamp => $time ): ?>
                                <option value="<?php echo $timestamp ?>"><?php echo strftime( '%I:%M %p', strtotime( $time ) ) ?></option>
							<?php endforeach; ?>
                        </select>
                        <!--						<span>-->
						<?php //printf( esc_html__( "You will receive a lockout report email %s.", "defender-security" ), date_i18n( WD_Utils::get_date_time_format(), \WP_Defender\IP_Lockout\Component\Login_Protection_Api::get_report_sending_time() ) ) ?><!--</span>-->
                    </div>
                </div>
            </div>

            <div class="columns last">
                <div class="column is-one-third">
                    <label>
						<?php esc_html_e( "Email recipients", "defender-security" ) ?>
                    </label>
                    <span class="sub">
						<?php esc_html_e( "Choose which of your website’s users will receive the lockout report.", "defender-security" ) ?>
					</span>
                </div>
                <div class="column">
					<?php $email_search->renderInput() ?>
                </div>
            </div>
            <div class="clear line"></div>
        </form>
        <div class="presale-text">
            <div>
			    <?php printf( __( "Schedule automated file scanning and email reporting for all your websites. This feature is included in a WPMU DEV membership along with 100+ plugins, 24/7 support and lots of handy site management tools  – <a target='_blank' href=\"%s\">Try it all FREE today!</a>", "defender-security" ), \WP_Defender\Behavior\Utils::instance()->campaignURL('defender_iplockouts_reports_upsell_link') ) ?>
            </div>
        </div>
    </div>
</div>
<?php $controller->renderPartial('pro-feature') ?>
