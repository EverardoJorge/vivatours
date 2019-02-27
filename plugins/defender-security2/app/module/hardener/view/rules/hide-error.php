<div class="rule closed" id="disable-file-editor">
    <div class="rule-title" role="link" tabindex="0">
		<?php if ( $controller->check() == false ): ?>
            <i class="def-icon icon-warning" aria-hidden="true"></i>
		<?php else: ?>
            <i class="def-icon icon-tick" aria-hidden="true"></i>
		<?php endif; ?>
		<?php _e( "Hide error reporting", "defender-security" ) ?>
    </div>
    <div class="rule-content">
        <h3><?php _e( "Overview", "defender-security" ) ?></h3>
        <div class="line end">
			<?php _e( "In addition to hiding error logs, developers often use the built-in front-end PHP and scripts error debugging feature, which displays code errors on the front-end. This provides hackers yet another way to find loopholes in your site's security.", "defender-security" ) ?>
        </div>
        <h3>
			<?php _e( "How to fix", "defender-security" ) ?>
        </h3>
        <div class="well">
			<?php if ( $controller->check() ): ?>
                <p class=""><?php _e( "All PHP errors are hidden.", "defender-security" ) ?></p>
			<?php else: ?>
				<?php
				//if WP debug == true, we will display a form to turn it off
				if ( WP_DEBUG == true && ( ! defined( 'WP_DEBUG_DISPLAY' ) || WP_DEBUG_DISPLAY != false ) ): ?>
                    <div class="line">
                        <p><?php _e( "We will add the necessary code to prevent these errors displaying.", "defender-security" ) ?></p>
                    </div>
                    <form method="post" class="hardener-frm rule-process">
						<?php $controller->createNonceField(); ?>
                        <input type="hidden" name="action" value="processHardener"/>
                        <input type="hidden" name="slug" value="<?php echo $controller::$slug ?>"/>
                        <button class="button float-r"
                                type="submit"><?php _e( "Disable error debugging", "defender-security" ) ?></button>
                    </form>
					<?php $controller->showIgnoreForm() ?>
					<?php
				//php debug is turn off, however the error still dsplay, need to show user about this
				else: ?>
                    <p class="line">
						<?php _e( "We attempted to disable the display_errors setting to prevent code errors displaying but it’s being overridden by your server config. Please contact your hosting provider and ask them to set display_errors to false.", "defender-security" ) ?>
                    </p>
					<?php $controller->showIgnoreForm() ?>
				<?php endif; ?>
			<?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
