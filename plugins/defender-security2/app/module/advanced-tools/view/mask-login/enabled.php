<div class="dev-box">
    <div class="box-title">
        <h3 class="def-issues-title">
			<?php _e( "Mask Login Area", "defender-security" ) ?>
        </h3>
    </div>
    <div class="box-content issues-box-content">
        <form method="post" id="ad-mask-settings-frm" class="advanced-settings-frm">
            <p class="line"><?php _e( "Change your default WordPress login URL to hide your login area from hackers and bots.", "defender-security" ) ?></p>
			<?php if ( isset( wp_defender()->global['compatibility'] ) ): ?>
                <div class="well well-error with-cap">
                    <i class="def-icon icon-warning icon-yellow "></i>
					<?php echo implode( '<br/>', array_unique( wp_defender()->global['compatibility'] ) ); ?>
                </div>
			<?php else: ?>
				<?php if ( strlen( trim( $settings->maskUrl ) ) == 0 ): ?>
                    <div class="well well-yellow with-cap">
                        <i class="def-icon icon-warning icon-yellow "></i>
						<?php _e( "Masking is currently inactive. Choose your URL and save your settings to finish setup. ", "defender-security" ) ?>
                    </div>
				<?php else: ?>
                    <div class="well well-green with-cap">
                        <i class="def-icon icon-tick"></i>
						<?php printf( __( "Masking is currently active at <strong>%s</strong>", "defender-security" ), \WP_Defender\Module\Advanced_Tools\Component\Mask_Api::getNewLoginUrl() ) ?>
                    </div>
				<?php endif; ?>
			<?php endif; ?>

            <input type="hidden" name="action" value="saveATMaskLoginSettings"/>
			<?php wp_nonce_field( 'saveATMaskLoginSettings' ) ?>
            <div class="columns">
                <div class="column is-one-third">
                    <label><?php _e( "Masking URL", "defender-security" ) ?></label>
                    <span class="sub">
                        <?php _e( 'Choose a new slug where users of your website will now login instead of visiting /wp-login.', "defender-security" ) ?>
                    </span>
                </div>
                <div class="column">
                    <span class="form-help"><?php _e( "You can choose any slug you like using alphanumeric characters and '-'s only. For security reasons, less obvious slugs are recommended as they are harder for bots to guess.", "defender-security" ) ?></span>
                    <span class="form-help"><strong><?php _e( 'New Login Slug', "defender-security" ) ?></strong></span>
                    <input type="text" class="tl block" name="maskUrl" value="<?php echo $settings->maskUrl ?>" placeholder="<?php _e( 'I.e. dashboard', "defender-security" ); ?>"/>
                    <span class="form-help-s"><?php printf( __( "Users will login at <strong>%s</strong>. Note: Registration and Password Reset emails have hardcoded URLs in them. We will update them automatically to match your new login URL.", "defender-security" ), get_site_url() . '/' . $settings->maskUrl ) ?></span>
                </div>
            </div>
            <div class="columns">
                <div class="column is-one-third">
                    <label><?php _e( "Redirect traffic", "defender-security" ) ?></label>
                    <span class="sub">
                        <?php _e( "With this feature you can send visitors and bots who try to visit the default WordPress login URLs to a separate URL to avoid 404s.", "defender-security" ) ?>
                    </span>
                </div>
                <div class="column">
                    <span class="toggle">
                        <input type="hidden" name="redirectTraffic" value="0"/>
                        <input type="checkbox" <?php checked( 1, $settings->redirectTraffic ) ?> name="redirectTraffic"
                               value="1"
                               class="toggle-checkbox" id="redirectTraffic"/>
                        <label class="toggle-label" for="redirectTraffic"></label>
                    </span>&nbsp;
                    <span><?php _e( "Enable 404 redirection", "defender-security" ) ?></span>
                    <div class="clear mline"></div>
                    <div class="well well-white <?php echo $settings->redirectTraffic == false ? 'is-hidden' : null ?>">
                        <p>
                            <span class="form-help"><strong><?php _e( "Redirection URL", "defender-security" ) ?></strong></span>
                        </p>
                        <input type="text" class="block" name="redirectTrafficUrl"
                               value="<?php echo $settings->redirectTrafficUrl ?>">
						<?php if ( strlen( $settings->redirectTrafficUrl ) ): ?>
                            <p>
                                <span class="form-help-s"><?php printf( __( "Visitors who visit the default login URLs will be redirected to <strong>%s</strong>", "defender-security" ), get_site_url() . '/' . $settings->redirectTrafficUrl ) ?></span>
                            </p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="columns mline">
                <div class="column is-one-third">
                    <label><?php _e( "Deactivate", "defender-security" ) ?></label>
                </div>
                <div class="column">
                    <button type="button" class="button button-secondary deactivate-atmasking">
						<?php _e( "Deactivate", "defender-security" ) ?>
                    </button>
                </div>
            </div>
            <div class="clear line"></div>
            <button type="submit" class="button button-primary float-r">
				<?php _e( "Save Settings", "defender-security" ) ?>
            </button>
            <div class="clear"></div>
        </form>
    </div>
</div>
