<div id="wph-cumulative-shares-modal" class="wpmudev-modal">

    <div class="wpmudev-modal-mask" aria-hidden="true"></div>

    <div class="wpmudev-box-modal wpmudev-show">

        <div class="wpmudev-box-head">

            <h2><?php esc_attr_e( "Social Shares Stats", Opt_In::TEXT_DOMAIN ); ?></h2>

            <?php $this->render("general/icons/icon-close" ); ?>

        </div>

        <div class="wpmudev-box-body">

            <table cellspacing="0" cellpadding="0" class="wpmudev-table<?php if ( $ss_total_share_stats > 5 ) echo ' wpmudev-table-paginated'; ?>">

                <thead>

                    <tr>

                        <th><?php esc_attr_e("Page / Post", Opt_In::TEXT_DOMAIN); ?></th>

                        <th><?php esc_attr_e("Cumulative Shares", Opt_In::TEXT_DOMAIN); ?></th>

                    </tr>

                </thead>

                <tbody>

					<?php foreach( $ss_share_stats_data as $ss ) : ?>

						<tr>
							<?php $url = ( ! $ss->ID ) ? esc_url(get_permalink($ss->ID)) : esc_url(get_home_url()); ?>
							<?php $title = ( $ss->ID ) ? $ss->post_title : bloginfo('title'); ?>

							<td><a target="_blank" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a></td>

							<td><?php echo esc_html( $ss->page_shares ); ?></td>

						</tr>

					<?php endforeach; ?>

                </tbody>

                <?php
				if ( $ss_total_share_stats > 5 ) {
						$pages = (int) ($ss_total_share_stats / 5);
						if ( ($ss_total_share_stats % 5) ) {
							$pages++;
						}
						$first_page = 1;
						$last_page = $pages;
				?>

                    <tfoot>

                        <tr><td colspan="2">

								<ul class="wpmudev-pagination" data-total="<?php echo esc_attr( $pages ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('hustle_ss_stats_paged_data') ); ?>">

									<li class="wpmudev-prev wph-sshare--prev_page" data-page="1"><span><?php esc_html( $this->render("general/icons/icon-arrow" ) ); ?></span></li>

								<li class="wpmudev-number wpmudev-current wph-sshare--current_page" data-page="1"><span>1</span></li>

								<?php if( $pages > 1 ): ?>

								<li class="wpmudev-number wph-sshare--page_number" data-page="2"><a href="#">2</a></li>

								<li class="wpmudev-next wph-sshare--next_page" data-page="2"><a href="#"><?php $this->render("general/icons/icon-arrow" ); ?></a></li>

								<?php else: ?>

								<li class="wpmudev-next wph-sshare--next_page" data-page="2"><span><?php $this->render("general/icons/icon-arrow" ); ?></span></li>

								<?php endif; ?>

                            </ul>

                        </td></tr>

                    </tfoot>

                <?php } ?>

            </table>

        </div>

    </div>

</div>
<script id="wpmudev-hustle-sshare-stats-modal-tpl" type="text/template">

    <# _.each( ss_share_stats, function(ss, key){ #>

		<tr>

			<td><a target="_blank" href="{{ss.page_url}}">{{ss.page_title}}</a></td>
			<td>{{ss.page_shares}}</td>

		</tr>

	<# }); #>

</script>
