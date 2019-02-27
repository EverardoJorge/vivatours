<div class="dev-box">
	<div class="box-title">
		<h3 class="def-issues-title">
			<?php _e( "Mask Login Area", "defender-security" ) ?>
		</h3>
	</div>
	<div class="box-content issues-box-content tc">
		<img src="<?php echo wp_defender()->getPluginUrl() . 'assets/img/2factor-disabled.svg' ?>"/>
		<p>
			<?php _e( 'Change the location of WordPress’s default login area, making it harder for automated bots to find and also more convenient for your users.', "defender-security" ) ?>
		</p>
		<form method="post" id="advanced-settings-frm" class="advanced-settings-frm">

			<div class="clear line"></div>
			<input type="hidden" name="action" value="saveATMaskLoginSettings"/>
			<?php wp_nonce_field( 'saveATMaskLoginSettings' ) ?>
			<input type="hidden" name="enabled" value="1"/>
			<button type="submit" class="button button-primary">
				<?php _e( "Activate", "defender-security" ) ?>
			</button>
			<div class="clear"></div>
		</form>
	</div>
</div>
