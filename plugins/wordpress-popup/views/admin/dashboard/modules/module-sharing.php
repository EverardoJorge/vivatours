<?php

$content_hide = false;
?>

<div id="wpmudev-dashboard-widget-shares" class="wpmudev-box wpmudev-box-close">

    <div class="wpmudev-box-head">

        <?php $this->render("general/icons/admin-icons/icon-shares" ); ?>

        <h2><?php esc_attr_e("Social Shares", Opt_In::TEXT_DOMAIN); ?></h2>

        <div class="wpmudev-box-action"><?php $this->render("general/icons/icon-plus" ); ?></div>

    </div>

    <div class="wpmudev-box-body<?php if ( true === $content_hide ) echo ' wpmudev-hidden'; ?>">

        <?php if ( count($social_sharings) ) { ?>

            <?php if ( count($ss_share_stats_data) > 0 ) { ?>

                <table cellspacing="0" cellpadding="0" class="wpmudev-table wpmudev-table-cumulative">

                    <thead>

                        <tr>

                            <th><?php esc_attr_e("Page / Post", Opt_In::TEXT_DOMAIN); ?></th>

                            <th><?php esc_attr_e("Cumulative Shares", Opt_In::TEXT_DOMAIN); ?></th>

                        </tr>

                    </thead>

                    <tbody>

						<?php foreach( $ss_share_stats_data as $ss ) : ?>

							<tr>

								<td>
									<?php $url = ( $ss->ID ) ? get_permalink($ss->ID) : get_home_url(); ?>
									<?php $title = ( $ss->ID ) ? $ss->post_title : bloginfo('title'); ?>
									<a target="_blank" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a></td><td><?php echo esc_html( $ss->page_shares ); ?>

								</td>

							</tr>

						<?php endforeach; ?>

                    </tbody>

                    <?php if ( $ss_total_share_stats > 5 ) { ?>

                        <tfoot>

                            <tr><td colspan="2"><a href="#" id="sshare_view_all_stats" class="wpmudev-button wpmudev-button-sm wpmudev-button-blue">View All Shares</a></td></tr>

                        </tfoot>

                    <?php } ?>

                </table>

            <?php } else { ?>

                <p><?php esc_attr_e( "Nothing has been shared yet.", Opt_In::TEXT_DOMAIN ); ?></p>

            <?php } ?>

        <?php } else { ?>

            <p>
				<?php esc_attr_e("You don't have any social sharing modules set-up just yet.", Opt_In::TEXT_DOMAIN); ?>
				<br />
				<?php esc_attr_e("Click the button below to setup social sharing.", Opt_In::TEXT_DOMAIN); ?>
			</p>

            <p><a href="<?php echo esc_url( admin_url( "admin.php?page=" . Hustle_Module_Admin::SOCIAL_SHARING_WIZARD_PAGE ) ); ?>" class="wpmudev-button wpmudev-button-sm wpmudev-button-ghost"><?php esc_attr_e("Setup Social Sharing", Opt_In::TEXT_DOMAIN); ?></a></p>

        <?php } ?>

    </div>

</div>
