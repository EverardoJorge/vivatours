<div id="wph-wizard-content-image_position" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Featured image position", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "Choose the featured image location", Opt_In::TEXT_DOMAIN ); ?></label>

        <div class="wpmudev-tabs" >

            <ul class="wpmudev-tabs-menu wpmudev-tabs-menu_full wpmudev-feature-image-position-options">

                <li class="wpmudev-tabs-menu_item{{ ( 'left' === feature_image_position ) ? ' current' : '' }}">
                    <input type="checkbox" value="left">
                    <label><?php esc_attr_e( "Left", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item{{ ( 'right' === feature_image_position ) ? ' current' : '' }}">
                    <input type="checkbox" value="right">
                    <label><?php esc_attr_e( "Right", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li id="wpmudev-tabs-menu_item_above" class="wpmudev-tabs-menu_item{{ ( 'above' === feature_image_position ) ? ' current' : '' }}">
                    <input type="checkbox" value="above">
                    <label><?php esc_attr_e( "Above content", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li id="wpmudev-tabs-menu_item_below" class="wpmudev-tabs-menu_item{{ ( 'below' === feature_image_position ) ? ' current' : '' }}">
                    <input type="checkbox" value="below">
                    <label><?php esc_attr_e( "Below content", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

            </ul>

        </div>

	</div>

</div><?php // #wph-wizard-content-form_image ?>
