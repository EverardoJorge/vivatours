<div class="rule closed" id="disable_file_editor">
    <div class="rule-title" role="link" tabindex="0">
		<?php if ( $controller->check() == false ): ?>
            <i class="def-icon icon-warning" aria-hidden="true"></i>
		<?php else: ?>
            <i class="def-icon icon-tick" aria-hidden="true"></i>
		<?php endif; ?>
		<?php _e( "Disable the file editor", "defender-security" ) ?>
    </div>
    <div class="rule-content">
        <h3><?php _e( "Overview", "defender-security" ) ?></h3>
        <div class="line end">
			<?php _e( "WordPress comes with a file editor built into the system. This means that anyone with access to your login information can edit your plugin and theme files. We recommend disabling the editor.", "defender-security" ) ?>
        </div>
        <h3>
			<?php _e( "How to fix", "defender-security" ) ?>
        </h3>
        <div class="well">
			<?php if ( $controller->check() ): ?>
				<p class="line"><?php _e( "The file editor is disabled.", "defender-security" ) ?></p>
                <form method="post" class="hardener-frm rule-process">
					<?php $controller->createNonceField(); ?>
                    <input type="hidden" name="action" value="processRevert"/>
                    <input type="hidden" name="slug" value="<?php echo $controller::$slug ?>"/>
                    <button class="button button-small button-grey"
                            type="submit"><?php _e( "Revert", "defender-security" ) ?></button>
                </form>
			<?php else: ?>
                <div class="line">
                    <p><?php _e( "We will disable access to the file editor for you. You can enable it again anytime.", "defender-security" ) ?></p>
                </div>
                <form method="post" class="hardener-frm rule-process">
					<?php $controller->createNonceField(); ?>
                    <input type="hidden" name="action" value="processHardener"/>
                    <input type="hidden" name="slug" value="<?php echo $controller::$slug ?>"/>
                    <button class="button float-r"
                            type="submit"><?php _e( "Disable File Editor", "defender-security" ) ?></button>
                </form>
				<?php $controller->showIgnoreForm() ?>
                <div class="clear"></div>
			<?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>
</div>
