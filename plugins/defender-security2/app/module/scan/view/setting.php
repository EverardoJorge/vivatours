<div class="dev-box">
    <div class="box-title">
        <h3><?php _e( "Settings", "defender-security" ) ?></h3>
    </div>
    <div class="box-content">
        <form method="post" class="scan-frm scan-settings">
            <div class="columns <?php echo wp_defender()->isFree ? 'has-presale' : null ?>">
                <div class="column is-one-third">
                    <strong><?php _e( "Scan Types", "defender-security" ) ?></strong>
                    <span class="sub">
                        <?php _e( "Choose the scan types you would like to include in your default scan. It's recommended you enable all types.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="column">
                    <span class="toggle" aria-hidden="true" role="presentation">
                        <input type="hidden" name="scan_core" value="0"/>
                        <input role="presentation" type="checkbox" name="scan_core" class="toggle-checkbox" id="core-scan" value="1"
	                        <?php checked( true, $setting->scan_core ) ?>/>
                        <label aria-hidden="true"  class="toggle-label" for="core-scan"></label>
                    </span>
                    <label for="core-scan"><?php _e( "WordPress Core", "defender-security" ) ?></label>
                    <span class="sub inpos">
                        <?php _e( "Defender checks for any modifications or additions to WordPress core files.", "defender-security" ) ?>
                    </span>
                    <div class="clear mline"></div>
                    <span class="toggle" aria-hidden="true" role="presentation">
                        <input type="hidden" name="scan_vuln" value="0"/>
                        <input role="presentation" type="checkbox" class="toggle-checkbox" name="scan_vuln" value="1"
                               id="scan-vuln" <?php checked( true, $setting->scan_vuln ) ?>/>
                        <label aria-hidden="true" class="toggle-label" for="scan-vuln"></label>
                    </span>
                    <label for="scan-vuln"><?php _e( "Plugins & Themes", "defender-security" ) ?></label>
                    <span class="sub inpos">
                        <?php _e( "Defender looks for publicly reported vulnerabilities in your installed plugins and themes.", "defender-security" ) ?>
                    </span>
                    <div class="clear mline"></div>
                    <span class="toggle" aria-hidden="true" role="presentation">
                        <input type="hidden" name="scan_content" value="0"/>
                        <input role="presentation" type="checkbox" class="toggle-checkbox" name="scan_content" value="1"
                               id="scan-content" <?php checked( true, $setting->scan_content ) ?>/>
                        <label aria-hidden="true" class="toggle-label" for="scan-content"></label>
                    </span>
                    <label for="scan-content"><?php _e( "Suspicious Code", "defender-security" ) ?></label>
                    <span class="sub inpos">
                        <?php _e( "Defender looks inside all of your files for suspicious and potentially harmful code.", "defender-security" ) ?>
                    </span>
                </div>
            </div>
            <div class="columns">
                <div class="column is-one-third">
                    <strong><?php _e( "Maximum included file size", "defender-security" ) ?></strong>
                    <span class="sub">
                        <?php _e( "Defender will skip any files larger than this size. The smaller the number, the faster Defender will scan your website.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="column">
                    <input type="text" size="4" value="<?php echo esc_attr( $setting->max_filesize ) ?>"
                           name="max_filesize"> <?php _e( "MB", "defender-security" ) ?>
                </div>
            </div>
            <div class="columns">
                <div class="column is-one-third">
                    <strong><?php _e( "Optional emails", "defender-security" ) ?></strong>
                    <span class="sub">
                        <?php _e( "By default, you'll only get email reports when your site runs into trouble. Turn this option on to get reports even when your site is running smoothly.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="column">
                    <span class="toggle">
                        <input type="hidden" name="always_send" value="0"/>
                        <input type="checkbox" name="always_send" class="toggle-checkbox" value="1"
                               id="always_send" <?php checked( true, $setting->always_send ) ?>/>
                        <label class="toggle-label" for="always_send"></label>
                    </span>
                    <label><?php _e( "Send all scan report emails", "defender-security" ) ?></label>
                </div>
            </div>
            <div class="columns">
                <div class="column is-one-third">
                    <strong><?php _e( "Email subject", "defender-security" ) ?></strong>
                </div>
                <div class="column">
                    <input type="text" name="email_subject" value="<?php echo esc_attr( $setting->email_subject ) ?>"/>
                </div>
            </div>
            <div class="columns">
                <div class="column is-one-third">
                    <strong><?php _e( "Email templates", "defender-security" ) ?></strong>
                    <span class="sub">
                         <?php _e( "When Defender scans your website, a report will be generated with any issues that have been found. You can choose to have reports emailed to you.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="column">
                    <ul class="dev-list">
                        <li>
                            <div>
                                <span class="list-label"><?php _e( "When an issue is found", "defender-security" ) ?></span>
                                <span class="list-detail tr">
                                    <a href="#issue-found" rel="dialog" role="button"><?php _e( "Edit", "defender-security" ) ?></a></span>
                            </div>
                        </li>
                        <li>
                            <div>
                                <span class="list-label"><?php _e( "When no issues are found", "defender-security" ) ?></span>
                                <span class="list-detail tr">
                                    <a href="#all-ok"
                                       rel="dialog" role="button"><?php _e( "Edit", "defender-security" ) ?></a></span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear line"></div>
            <input type="hidden" name="action" value="saveScanSettings"/>
			<?php wp_nonce_field( 'saveScanSettings' ) ?>
            <button class="button float-r"><?php _e( "Update Settings", "defender-security" ) ?></button>
            <div class="clear"></div>
        </form>
    </div>
</div>
<dialog id="issue-found" title="<?php esc_attr_e( "Issues found", "defender-security" ) ?>">
    <div class="wp-defender">
        <form method="post" class="scan-frm scan-settings">
            <textarea rows="12" name="email_has_issue"><?php echo $setting->email_has_issue ?></textarea>
            <strong class="small">
				<?php _e( "Available variables", "defender-security" ) ?>
            </strong>
            <input type="hidden" name="action" value="saveScanSettings"/>
            <div class="clearfix"></div>
            <span class="def-tag tag-generic">{USER_NAME}</span>
            <span class="def-tag tag-generic">{SITE_URL}</span>
            <span class="def-tag tag-generic">{ISSUES_COUNT}</span>
            <span class="def-tag tag-generic">{ISSUES_LIST}</span>
			<?php wp_nonce_field( 'saveScanSettings' ) ?>
            <div class="clearfix mline"></div>
            <hr class="mline"/>
            <button type="button"
                    class="button button-light close"><?php _e( "Cancel", "defender-security" ) ?></button>
            <button class="button float-r"><?php _e( "Save Template", "defender-security" ) ?></button>
        </form>
    </div>
</dialog>
<dialog id="all-ok" title="<?php esc_attr_e( 'All OK', "defender-security" ) ?>">
    <div class="wp-defender">
        <form method="post" class="scan-frm scan-settings">
            <input type="hidden" name="action" value="saveScanSettings"/>
			<?php wp_nonce_field( 'saveScanSettings' ) ?>
            <textarea rows="12" name="email_all_ok"><?php echo $setting->email_all_ok ?></textarea>
            <strong class="small">
		        <?php _e( "Available variables", "defender-security" ) ?>
            </strong>
            <div class="clearfix"></div>
            <span class="def-tag tag-generic">{USER_NAME}</span>
            <span class="def-tag tag-generic">{SITE_URL}</span>
            <div class="clearfix mline"></div>
            <hr class="mline"/>
            <button type="button"
                    class="button button-light close"><?php _e( "Cancel", "defender-security" ) ?></button>
            <button class="button float-r"><?php _e( "Save Template", "defender-security" ) ?></button>
        </form>
    </div>
</dialog>