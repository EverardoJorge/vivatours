<div id="wph-wizard-content-image_fit" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Featured image fitting", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

        <label class="wpmudev-helper"><?php esc_attr_e("Improve the way the featured image fits its container. Preview each option to find one that suits you.", Opt_In::TEXT_DOMAIN); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e("Choose image fitting type", Opt_In::TEXT_DOMAIN); ?></label>

        <div class="wpmudev-tabs">

            <ul class="wpmudev-tabs-menu wpmudev-tabs-menu_full wpmudev-feature-image-fit-options">

                <li class="wpmudev-tabs-menu_item{{ ( 'fill' === feature_image_fit ) ? ' current' : '' }}">
                    <input type="checkbox" value="fill">
                    <label><?php esc_attr_e( "Fill", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item{{ ( 'contain' === feature_image_fit ) ? ' current' : '' }}">
                    <input type="checkbox" value="contain">
                    <label><?php esc_attr_e( "Contain", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item{{ ( 'cover' === feature_image_fit ) ? ' current' : '' }}">
                    <input type="checkbox" value="cover">
                    <label><?php esc_attr_e( "Cover", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item{{ ( 'none' === feature_image_fit ) ? ' current' : '' }}">
                    <input type="checkbox" value="none">
                    <label><?php esc_attr_e( "None", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

            </ul>

        </div>

        <div id="wph-wizard-content-image_fit_horizontal_vertical_options" class="wpmudev-box-gray {{ ( 'contain' === feature_image_fit || 'cover' === feature_image_fit ) ? 'wpmudev-show' : 'wpmudev-hidden' }}">

            <label><?php esc_attr_e("Horizontal image position", Opt_In::TEXT_DOMAIN); ?></label>

            <div class="wpmudev-tabs">

                <ul class="wpmudev-tabs-menu wpmudev-tabs-menu_full wpmudev-feature-image-horizontal-options">

                    <li class="wpmudev-tabs-menu_item{{ ( 'left' === feature_image_horizontal ) ? ' current' : '' }}">
                        <input type="checkbox" value="left">
                        <label><?php esc_attr_e( "Left", Opt_In::TEXT_DOMAIN ); ?></label>
                    </li>

                    <li class="wpmudev-tabs-menu_item{{ ( 'center' === feature_image_horizontal ) ? ' current' : '' }}">
                        <input type="checkbox" value="center">
                        <label><?php esc_attr_e( "Center", Opt_In::TEXT_DOMAIN ); ?></label>
                    </li>

                    <li class="wpmudev-tabs-menu_item{{ ( 'right' === feature_image_horizontal ) ? ' current' : '' }}">
                        <input type="checkbox" value="right">
                        <label><?php esc_attr_e( "Right", Opt_In::TEXT_DOMAIN ); ?></label>
                    </li>

                    <li class="wpmudev-tabs-menu_item{{ ( 'custom' === feature_image_horizontal ) ? ' current' : '' }}">
                        <input type="checkbox" value="custom">
                        <label><?php esc_attr_e( "Custom", Opt_In::TEXT_DOMAIN ); ?></label>
                    </li>

                </ul>

            </div>

            <div id="wph-wizard-design-horizontal-position" class="{{ ( 'custom' === feature_image_horizontal ) ? 'wpmudev-show' : 'wpmudev-hidden' }}">

                <label><?php esc_attr_e("Horizontal position (px)", Opt_In::TEXT_DOMAIN); ?></label>

                <input type="number" data-attribute="feature_image_horizontal_px" value="{{feature_image_horizontal_px}}" class="wpmudev-input_number">

            </div>

            <label><?php esc_attr_e("Vertical image position", Opt_In::TEXT_DOMAIN); ?></label>

            <div class="wpmudev-tabs">

                <ul class="wpmudev-tabs-menu wpmudev-tabs-menu_full wpmudev-feature-image-vertical-options">

                    <li class="wpmudev-tabs-menu_item{{ ( 'top' === feature_image_vertical ) ? ' current' : '' }}">
                        <input type="checkbox" value="top">
                        <label><?php esc_attr_e( "Top", Opt_In::TEXT_DOMAIN ); ?></label>
                    </li>

                    <li class="wpmudev-tabs-menu_item{{ ( 'center' === feature_image_vertical ) ? ' current' : '' }}">
                        <input type="checkbox" value="center">
                        <label><?php esc_attr_e( "Center", Opt_In::TEXT_DOMAIN ); ?></label>
                    </li>

                    <li class="wpmudev-tabs-menu_item{{ ( 'bottom' === feature_image_vertical ) ? ' current' : '' }}">
                        <input type="checkbox" value="bottom">
                        <label><?php esc_attr_e( "Bottom", Opt_In::TEXT_DOMAIN ); ?></label>
                    </li>

                    <li class="wpmudev-tabs-menu_item{{ ( 'custom' === feature_image_vertical ) ? ' current' : '' }}">
                        <input type="checkbox" value="custom">
                        <label><?php esc_attr_e( "Custom", Opt_In::TEXT_DOMAIN ); ?></label>
                    </li>

                </ul>

            </div>

            <div id="wph-wizard-design-vertical-position" class="{{ ( 'custom' === feature_image_vertical ) ? 'wpmudev-show' : 'wpmudev-hidden' }}">

                <label><?php esc_attr_e("Vertical position (px)", Opt_In::TEXT_DOMAIN); ?></label>

                <input type="number" data-attribute="feature_image_vertical_px" value="{{feature_image_vertical_px}}" class="wpmudev-input_number">

            </div>

        </div>

	</div>

</div><?php // #wph-wizard-content-form_image ?>
