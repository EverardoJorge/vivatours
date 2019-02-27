<div id="wph-wizard-settings-triggers" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Embed triggers", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

        <label class="wpmudev-helper"><?php esc_attr_e( "After Contents can be triggered after a certain amount of Time, when the user Scrolls past an element, on Click, if the user tries to Leave or if we detect AdBlock", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

        <div class="wpmudev-tabs">

            <ul class="wpmudev-tabs-menu wpmudev-tabs-menu_full wpmudev-display-triggers">

                <li class="wpmudev-tabs-menu_item {{ ( 'time' === triggers.trigger ) ? 'current' : '' }}">
                    <input type="checkbox" value="time">
                    <label><?php esc_attr_e( "Time", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item {{ ( 'scroll' === triggers.trigger ) ? 'current' : '' }}">
                    <input type="checkbox" value="scroll">
                    <label><?php esc_attr_e( "Scroll", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item {{ ( 'click' === triggers.trigger ) ? 'current' : '' }}">
                    <input type="checkbox" value="click">
                    <label><?php esc_attr_e( "Click", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item {{ ( 'exit' === triggers.trigger ) ? 'current' : '' }}">
                    <input type="checkbox" value="exit">
                    <label><?php esc_attr_e( "Exit intent", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

                <li class="wpmudev-tabs-menu_item {{ ( 'adblock' === triggers.trigger ) ? 'current' : '' }}">
                    <input type="checkbox" value="adblock">
                    <label><?php esc_attr_e( "AdBlock", Opt_In::TEXT_DOMAIN ); ?></label>
                </li>

            </ul>

            <div class="wpmudev-tabs-content">

                <div id="wpmudev-display-trigger-time" class="wpmudev-tabs-content_item {{ ( 'time' === triggers.trigger ) ? 'current' : '' }}">

                    <div class="wpmudev-switch-labeled top">

                        <div class="wpmudev-switch">

                            <input id="wph-aftercontent-trigger_time" class="toggle-checkbox" type="checkbox" data-attribute="on_time" {{_.checked(_.isTrue(triggers.on_time), true)}}>

                            <label class="wpmudev-switch-design" for="wph-aftercontent-trigger_time" aria-hidden="true"></label>

                        </div>

                        <div class="wpmudev-switch-labels">

                            <label class="wpmudev-switch-label" for="wph-aftercontent-trigger_time"><?php esc_attr_e( "Show after certain time", Opt_In::TEXT_DOMAIN ); ?></label>

                            <label class="wpmudev-helper"><?php esc_attr_e( "If switched off, embed will be shown as soon as page is loaded.", Opt_In::TEXT_DOMAIN ); ?></label>

                        </div>

                    </div>

                    <div id="wpmudev-display-trigger-time-options" class="wpmudev-box-gray {{ ( _.isTrue(triggers.on_time) ) ? 'wpmudev-show' : 'wpmudev-hidden' }}">

                        <label><?php esc_attr_e( "Show embed after", Opt_In::TEXT_DOMAIN ); ?></label>

                        <div class="wpmudev-fields-group">

                            <input type="number" value="{{triggers.on_time_delay}}" data-attribute="on_time_delay" class="wpmudev-input_number">

                            <select class="wpmudev-select" data-attribute="on_time_unit">

                                <option value="seconds" {{ ( 'seconds' === triggers.on_time_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "seconds", Opt_In::TEXT_DOMAIN ); ?></option>
                                <option value="minutes" {{ ( 'minutes' === triggers.on_time_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "minutes", Opt_In::TEXT_DOMAIN ); ?></option>
                                <option value="hours" {{ ( 'hours' === triggers.on_time_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "hours", Opt_In::TEXT_DOMAIN ); ?></option>

                            </select>

                        </div>

                    </div>

                </div>

                <div id="wpmudev-display-trigger-scroll" class="wpmudev-tabs-content_item {{ ( 'scroll' === triggers.trigger ) ? 'current' : '' }}">

                    <?php
                    $on_page_pcg = false;
                    ?>

                    <div class="wpmudev-radio_with_label">

                        <div class="wpmudev-input_radio">

                            <input type="radio" id="wpmudev-display-trigger-scroll-on_page_pcg" name="trigger_on_scroll" value="" data-attribute="" <?php checked( $on_page_pcg ); ?>>

                            <label for="wpmudev-display-trigger-scroll-on_page_pcg" class="wpdui-fi wpdui-fi-check"></label>

                        </div>

                        <label for="wpmudev-display-trigger-scroll-on_page_pcg"><?php esc_attr_e( "Show after page scrolled", Opt_In::TEXT_DOMAIN ); ?></label>

                    </div>

                    <div class="wpmudev-radio_with_label">

                        <div class="wpmudev-input_radio">

                            <input type="radio" id="wpmudev-display-trigger-scroll-on_css_selector" name="trigger_on_scroll" value="" data-attribute="" <?php checked( $on_page_pcg ); ?>>

                            <label for="wpmudev-display-trigger-scroll-on_css_selector" class="wpdui-fi wpdui-fi-check"></label>

                        </div>

                        <label for="wpmudev-display-trigger-scroll-on_css_selector"><?php esc_attr_e( "Show after passed selector", Opt_In::TEXT_DOMAIN ); ?></label>

                    </div>

                    <div id="wpmudev-display-trigger-scroll-options" class="wpmudev-box-gray">

                        <?php if ( true === $on_page_pcg ) { ?>

                            <label><?php esc_attr_e( "Show embed after page has been scrolled", Opt_In::TEXT_DOMAIN ); ?></label>

                        <?php } else { ?>

                            <label><?php esc_attr_e( "Show embed after user passed a CSS selector", Opt_In::TEXT_DOMAIN ); ?></label>

                        <?php } ?>

                        <div class="wpmudev-fields-group">

                            <?php if ( true === $on_page_pcg ) { ?>

                                <input type="number" value="20" data-attribute="" class="wpmudev-input_number">

                                <label class="wpmudev-helper">%</label>

                            <?php } else { ?>

                                <input type="text" placeholder=".custom-css" value="" data-attribute="" class="wpmudev-input_text">

                            <?php } ?>

                        </div>

                    </div>

                </div>

                <div id="wpmudev-display-trigger-click" class="wpmudev-tabs-content_item {{ ( 'click' === triggers.trigger ) ? 'current' : '' }}">

                    <label class="wpmudev-helper"><?php esc_attr_e( "Use shortcode to render clickable button", Opt_In::TEXT_DOMAIN ); ?></label>

                    <input type="text" value="[wd_hustle id='new-hustle' type='aftercontent']Click here[/wd_hustle]" data-attribute="" class="wpmudev-shortcode" readonly="readonly" />

                    <label class="wpmudev-helper"><?php esc_attr_e( "Trigger after user clicks on existing element with this ID or Class", Opt_In::TEXT_DOMAIN ); ?></label>

                    <input type="text" placeholder="<?php esc_attr_e( 'Only use .class or #ID selector', Opt_In::TEXT_DOMAIN ); ?>" value="{{triggers.on_click_element}}" data-attribute="triggers.on_click_element" class="wpmudev-input_text">

                </div>

                <div id="wpmudev-display-trigger-exit" class="wpmudev-tabs-content_item {{ ( 'exit' === triggers.trigger ) ? 'current' : '' }}">

                    <div class="wpmudev-switch-labeled">

                        <div class="wpmudev-switch">

                            <input id="wph-aftercontent-trigger_session" class="toggle-checkbox" type="checkbox" data-attribute="on_exit_intent_per_session" {{_.checked(_.isTrue(triggers.on_exit_intent_per_session), true)}}>

                            <label class="wpmudev-switch-design" for="wph-aftercontent-trigger_session" aria-hidden="true"></label>

                        </div>

                        <label class="wpmudev-switch-label" for="wph-aftercontent-trigger_session"><?php esc_attr_e( "Trigger once per session only", Opt_In::TEXT_DOMAIN ); ?></label>

                    </div>

                </div>

                <div id="wpmudev-display-trigger-adblock" class="wpmudev-tabs-content_item {{ ( 'adblock' === triggers.trigger ) ? 'current' : '' }}">

                    <div class="wpmudev-switch-labeled">

                        <div class="wpmudev-switch">

                            <input id="wph-aftercontent-trigger_adblock" class="toggle-checkbox" type="checkbox" data-attribute="on_adblock" {{_.checked(_.isTrue(triggers.on_adblock), true)}}>

                            <label class="wpmudev-switch-design" for="wph-aftercontent-trigger_adblock" aria-hidden="true"></label>

                        </div>

                        <label class="wpmudev-switch-label" for="wph-aftercontent-trigger_adblock"><?php esc_attr_e( "Trigger when AdBlock is detected", Opt_In::TEXT_DOMAIN ); ?></label>

                    </div>

                    <div id="wpmudev-display-trigger-adblock-options" class="wpmudev-box-gray {{ ( _.isTrue(triggers.adblock) ) ? 'wpmudev-show' : 'wpmudev-hidden' }}">

                        <label><?php esc_attr_e( "Show embed after", Opt_In::TEXT_DOMAIN ); ?></label>

                        <div class="wpmudev-fields-group">

                            <input type="number" value="{{triggers.adblock_delay}}" data-attribute="triggers.adblock_delay" class="wpmudev-input_number">

                            <select class="wpmudev-select" data-attribute="triggers.adblock_unit">

                                <option value="seconds" {{ ( 'seconds' === triggers.adblock_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "seconds", Opt_In::TEXT_DOMAIN ); ?></option>
                                <option value="minutes" {{ ( 'minutes' === triggers.adblock_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "minutes", Opt_In::TEXT_DOMAIN ); ?></option>
                                <option value="hours" {{ ( 'hours' === triggers.adblock_unit ) ? 'selected' : '' }} ><?php esc_attr_e( "hours", Opt_In::TEXT_DOMAIN ); ?></option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>

        </div>

	</div>

</div><?php // #wph-wizard-settings-triggers ?>
