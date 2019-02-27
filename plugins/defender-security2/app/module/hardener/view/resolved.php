<div class="dev-box">
    <div class="box-title">
        <h3><?php _e( "RESOLVED", "defender-security" ) ?>
            <span class="def-tag tag-success count-resolved">
                <?php echo $controller->getCount( 'fixed' ) ?></span></h3>
    </div>
    <div class="box-content">
        <div class="box-content">
            <div class="line">
				<?php _e( "Excellent work. The following vulnerabilities have been fixed.", "defender-security" ) ?>
            </div>
            <div class="rules fixed">
				<?php foreach ( \WP_Defender\Module\Hardener\Model\Settings::instance()->getFixed() as $rule ): ?>
					<?php
					$rule->getDescription();
					?>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
</div>