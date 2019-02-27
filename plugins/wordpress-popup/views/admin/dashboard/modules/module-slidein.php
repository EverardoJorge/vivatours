<?php

$content_hide = false;
?>

<div id="wpmudev-dashboard-widget-slideins" class="wpmudev-box wpmudev-box-close">

    <div class="wpmudev-box-head">

        <?php $this->render("general/icons/admin-icons/icon-slidein" ); ?>

        <h2><?php esc_attr_e("Slide-ins", Opt_In::TEXT_DOMAIN); ?></h2>

        <div class="wpmudev-box-action"><?php $this->render("general/icons/icon-plus" ); ?></div>

    </div>

    <div class="wpmudev-box-body<?php if ( true === $content_hide ) echo ' wpmudev-hidden'; ?>">

        <?php if ( count($slideins) ) { ?>

            <table cellspacing="0" cellpadding="0" class="wpmudev-table">

                <thead>

                    <tr>

                        <th class="wpmudev-table--name"><?php esc_attr_e("Name", Opt_In::TEXT_DOMAIN); ?></th>

                        <th class="wpmudev-table--views"><?php esc_attr_e( "Views", Opt_In::TEXT_DOMAIN ); ?></th>

                        <th class="wpmudev-table--rate"><?php esc_attr_e( "Rate", Opt_In::TEXT_DOMAIN ); ?></th>

                        <th class="wpmudev-table--status"><?php esc_attr_e( "Status", Opt_In::TEXT_DOMAIN ); ?></th>

                        <th class="wpmudev-table--button"></th>

                    </tr>

                </thead>

                <tbody>

                    <?php
					foreach( $slideins as $slidein ) :

						$module_status = "off";
						if ( $slidein->is_test_type_active( $slidein->module_type ) ) {
							$module_status = "test";
						} elseif ( $slidein->active ) {
							$module_status = "live";
						}

					?>

                        <tr>

                            <td class="wpmudev-table--name"><?php echo esc_attr( $slidein->module_name ); ?></td>

                            <td class="wpmudev-table--views" data-name="<?php esc_attr_e( 'Views', Opt_In::TEXT_DOMAIN ); ?>"><?php echo esc_html( $slidein->get_statistics($slidein->module_type)->views_count ); ?></td>

                            <td class="wpmudev-table--rate" data-name="<?php esc_attr_e( 'rate', Opt_In::TEXT_DOMAIN ); ?>"><?php echo esc_html( $slidein->get_statistics($slidein->module_type)->conversion_rate ); ?>%</td>

                            <td class="wpmudev-table--status" data-name="<?php esc_attr_e( 'Status', Opt_In::TEXT_DOMAIN ); ?>"><span class="module-status-<?php echo esc_attr( $module_status ); ?>"><?php if ( "off" === $module_status ) esc_attr_e( "Off", Opt_In::TEXT_DOMAIN ); ?><?php if ( "test" === $module_status ) esc_attr_e( "Test", Opt_In::TEXT_DOMAIN ); ?><?php if ( "live" === $module_status ) esc_attr_e( "Live", Opt_In::TEXT_DOMAIN ); ?></span></td>

                            <td class="wpmudev-table--button"><a href="<?php echo esc_url( $slidein->decorated->get_edit_url( Hustle_Module_Admin::SLIDEIN_WIZARD_PAGE, '' ) ); ?>" class="wpmudev-button wpmudev-button-sm wpmudev-button-ghost"><?php esc_attr_e("Edit", Opt_In::TEXT_DOMAIN); ?></a></td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

                <tfoot>

                    <tr><td colspan="5"><a href="<?php echo esc_url( admin_url( "admin.php?page=" . Hustle_Module_Admin::SLIDEIN_WIZARD_PAGE ) ); ?>" class="wpmudev-button wpmudev-button-sm wpmudev-button-blue<?php echo $is_limited ? ' hustle-free-version-create' : '' ?>"><?php esc_attr_e("New Slide-in", Opt_In::TEXT_DOMAIN); ?></a></td></tr>

                </tfoot>

            </table>

        <?php } else { ?>

            <p><?php esc_attr_e("You currently don't have any slide-ins. You can create a new slide-in with any kind of content e.g. An advert or a promotion. You can also create slide-ins for collecting your customers' emails.", Opt_In::TEXT_DOMAIN); ?></p>

            <p><a href="<?php echo esc_url( admin_url( "admin.php?page=" . Hustle_Module_Admin::SLIDEIN_WIZARD_PAGE ) ); ?>" class="wpmudev-button wpmudev-button-sm wpmudev-button-ghost"><?php esc_attr_e("Create Slide-in", Opt_In::TEXT_DOMAIN); ?></a></p>

        <?php } ?>

    </div>

</div>
