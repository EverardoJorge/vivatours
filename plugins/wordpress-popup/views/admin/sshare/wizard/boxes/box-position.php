<div id="wph-wizard-settings-position" class="wpmudev-box-content">

    <div class="wpmudev-box-full">

        <h4><strong><?php esc_attr_e( "Position Floating Social in respect to", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

        <div class="wpmudev-tabs">

            <ul class="wpmudev-tabs-menu wpmudev-tabs-menu_md wpmudev-floating-position">

                <li class="wpmudev-tabs-menu_item">
                    <input  id="wpmudev-sshare-content-location" type="radio" name="location_type" data-attribute="location_type" value="content" {{ _.checked( ( 'content' === location_type ), true ) }} >
                    <label for="wpmudev-sshare-content-location" ><?php esc_attr_e( "Content text", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item">
                    <input id="wpmudev-sshare-screen-location" type="radio" name="location_type" data-attribute="location_type" value="screen" {{ _.checked( ( 'screen' === location_type ), true ) }}  >
                    <label for="wpmudev-sshare-screen-location" ><?php esc_attr_e( "Screen", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item">
                    <input id="wpmudev-sshare-selector-location" type="radio" name="location_type" data-attribute="location_type" value="selector" {{ _.checked( ( 'selector' === location_type ), true ) }} >
                    <label for="wpmudev-sshare-selector-location" ><?php esc_attr_e( "CSS selector", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

            </ul>

        </div>

        <div class="wpmudev-box-gray">

            <div id="wpmudev-sshare-selector-location-options" class="wpmudev-row {{ ( 'selector' !== location_type ) ? 'wpmudev-hidden' : '' }}">

                <div class="wpmudev-col col-12">

                    <label><?php esc_attr_e( "CSS Selector (Class or ID only)", Opt_In::TEXT_DOMAIN ); ?></label>

                    <input type="text" name="location_target" data-attribute="location_target" placeholder="<?php esc_attr_e( 'please include . or # characters to identify your selector', Opt_In::TEXT_DOMAIN ); ?>" class="wpmudev-input_text" value="{{location_target}}">

                </div>

            </div>

            <div class="wpmudev-row">

                <div class="wpmudev-col">

                    <div class="wpmudev-tabs">

                        <ul class="wpmudev-tabs-menu wpmudev-floating-horizontal">

                            <li class="wpmudev-tabs-menu_item">
                                <input type="radio" value="left" id="wpmudev-sshare-location-align-x-left" name="location_align_x" data-attribute="location_align_x" {{ _.checked( ( 'left' === location_align_x ), true ) }}>
                                <label for="wpmudev-sshare-location-align-x-left"><?php esc_attr_e( "Left", Opt_In::TEXT_DOMAIN ); ?></label>
                            </li>

                            <li class="wpmudev-tabs-menu_item">
                                <input type="radio" value="right" id="wpmudev-sshare-location-align-x-right" name="location_align_x" data-attribute="location_align_x" {{ _.checked( ( 'right' === location_align_x ), true ) }}>
                                <label for="wpmudev-sshare-location-align-x-right"><?php esc_attr_e( "Right", Opt_In::TEXT_DOMAIN ); ?></label>
                            </li>

                        </ul>

                        <div class="wpmudev-tabs-content">

                            <div id="wpmudev-floating-horizontal-left" class="wpmudev-tabs-content_item {{ ( 'left' === location_align_x ) ? 'current' : '' }}">

                                <label><?php esc_attr_e( "Left offset", Opt_In::TEXT_DOMAIN ); ?> (px)</label>

                                <input type="number" value="{{location_left}}" class="wpmudev-input_number" data-attribute="location_left">

                            </div>

                            <div id="wpmudev-floating-horizontal-right" class="wpmudev-tabs-content_item {{ ( 'right' === location_align_x ) ? 'current' : '' }}">

                                <label><?php esc_attr_e( "Right offset", Opt_In::TEXT_DOMAIN ); ?> (px)</label>

                                <input type="number" value="{{location_right}}" class="wpmudev-input_number" data-attribute="location_right" >

                            </div>

                        </div>

                    </div>

                </div>

                <div class="wpmudev-col">

                    <div class="wpmudev-tabs">

                        <ul class="wpmudev-tabs-menu wpmudev-floating-vertical">

                            <li class="wpmudev-tabs-menu_item">
                                <input type="radio" value="top" id="wpmudev-sshare-location-align-y-top" name="location_align_y" data-attribute="location_align_y" {{ _.checked( ( 'top' === location_align_y ), true ) }}>
                                <label for="wpmudev-sshare-location-align-y-top"><?php esc_attr_e( "Top", Opt_In::TEXT_DOMAIN ); ?></label>
                            </li>

                            <li class="wpmudev-tabs-menu_item">
                                <input type="radio" value="bottom" id="wpmudev-sshare-location-align-y-bottom" name="location_align_y" data-attribute="location_align_y" {{ _.checked( ( 'bottom' === location_align_y ), true ) }}>
                                <label for="wpmudev-sshare-location-align-y-bottom"><?php esc_attr_e( "Bottom", Opt_In::TEXT_DOMAIN ); ?></label>
                            </li>

                        </ul>

                        <div class="wpmudev-tabs-content">

                            <div id="wpmudev-floating-vertical-top" class="wpmudev-tabs-content_item {{ ( 'top' === location_align_y ) ? 'current' : '' }}">

                                <label><?php esc_attr_e( "Top offset", Opt_In::TEXT_DOMAIN ); ?> (px)</label>

                                <input type="number" value="{{location_top}}" class="wpmudev-input_number" data-attribute="location_top">

                            </div>

                            <div id="wpmudev-floating-vertical-bottom" class="wpmudev-tabs-content_item {{ ( 'bottom' === location_align_y ) ? 'current' : '' }}">

                                <label><?php esc_attr_e( "Bottom offset", Opt_In::TEXT_DOMAIN ); ?> (px)</label>

                                <input type="number" value="{{location_bottom}}" class="wpmudev-input_number" data-attribute="location_bottom">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div><?php // #wph-wizard-settings-position ?>
