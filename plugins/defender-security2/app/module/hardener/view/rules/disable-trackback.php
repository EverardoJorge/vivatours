<div class="rule closed" id="disable_trackback">
    <div class="rule-title" role="link" tabindex="0">
		<?php if ( $controller->check() == false ): ?>
            <i class="def-icon icon-warning" aria-hidden="true"></i>
		<?php else: ?>
            <i class="def-icon icon-tick" aria-hidden="true"></i>
		<?php endif; ?>
		<?php _e( "Disable trackbacks and pingbacks", "defender-security" ) ?>
    </div>
    <div class="rule-content">
        <h3><?php _e( "Overview", "defender-security" ) ?></h3>
        <div class="line end">
			<?php _e( "Pingbacks notify a website when it has been mentioned by another website, like a form of courtesy communication. However, these notifications can be sent to any website willing to receive them, opening you up to DDoS attacks, which can take your website down in seconds and fill your posts with spam comments.", "defender-security" ) ?>
        </div>
        <h3>
			<?php _e( "How to fix", "defender-security" ) ?>
        </h3>
        <div class="well">
			<?php if ( $controller->check() ): ?>
                <p class="mline"><?php _e( "Trackbacks and pingbacks are turned off.", "defender-security" ) ?></p>
                <form method="post" class="hardener-frm rule-process">
					<?php $controller->createNonceField(); ?>
                    <input type="hidden" name="action" value="processRevert"/>
                    <input type="hidden" name="slug" value="<?php echo $controller::$slug ?>"/>
                    <button class="button button-small button-grey"
                            type="submit"><?php _e( "Revert", "defender-security" ) ?></button>
                </form>
			<?php else: ?>
                <div class="line">
                    <p><?php _e( "We will turn off trackbacks and pingbacks in your WordPress settings area.", "defender-security" ) ?></p>
                </div>
                <label>
					<?php if ( is_multisite() ) : ?>
						<?php _e( "Disable pingbacks on all existing posts in all sites", "defender-security" ); ?>
					<?php else: ?>
						<?php _e( "Disable pingbacks on all existing posts", "defender-security" ); ?>
					<?php endif; ?>
					<span class="toggle float-r">
						<input type="checkbox" name="update_posts" value="1" class="toggle-checkbox trackback-toggle-update-posts" id="toggle_update_posts"/>
						<label class="toggle-label" for="toggle_update_posts"></label>
					</span>
				</label>
				<div class="clear mline"></div>
                <form method="post" class="hardener-frm rule-process hardener-frm-process-trackback">
					<?php $controller->createNonceField(); ?>
                    <input type="hidden" name="action" value="processHardener"/>
                    <input type="hidden" name="updatePosts" value="no"/>
                    <input type="hidden" name="slug" value="<?php echo $controller::$slug ?>"/>
                    <button class="button float-r"
                            type="submit"><?php _e( "Disable Pingbacks", "defender-security" ) ?></button>
                </form>
				<?php $controller->showIgnoreForm() ?>
			<?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
