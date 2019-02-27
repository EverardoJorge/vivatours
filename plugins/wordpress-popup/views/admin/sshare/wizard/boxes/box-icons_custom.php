<div id="wph-wizard-services-icons-custom" class="wpmudev-box-content last wph-wizard-services-icons-custom {{ ( 'native' === service_type ) ? 'wpmudev-hidden' : '' }}">

    <div class="wpmudev-box-right">

        <h4><strong><?php esc_attr_e( "Pick social icons & set URLs for them", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

        <div class="wpmudev-social wpmudev-social-custom">

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.facebook ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-facebook-custom" class="toggle-checkbox" type="checkbox" data-id="facebook" {{ ( 'undefined' === typeof social_icons.facebook ) ? '' : _.checked(social_icons.facebook.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-facebook-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-facebook">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/facebook"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.facebook ? '' : social_icons.facebook.link }}">

            </div>

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.twitter ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-twitter-custom" class="toggle-checkbox" type="checkbox" data-id="twitter" {{ ( 'undefined' === typeof social_icons.twitter ) ? '' : _.checked(social_icons.twitter.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-twitter-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-twitter">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/twitter"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.twitter ? '' : social_icons.twitter.link }}">

            </div>

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.google ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-google-custom" class="toggle-checkbox" type="checkbox" data-id="google" {{ ( 'undefined' === typeof social_icons.google ) ? '' : _.checked(social_icons.google.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-google-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-google">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/google"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.google ? '' : social_icons.google.link }}">

            </div>

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.pinterest ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-pinterest-custom" class="toggle-checkbox" type="checkbox" data-id="pinterest" {{ ( 'undefined' === typeof social_icons.pinterest ) ? '' : _.checked(social_icons.pinterest.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-pinterest-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-pinterest">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/pinterest"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.pinterest ? '' : social_icons.pinterest.link }}">

            </div>

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.reddit ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-reddit-custom" class="toggle-checkbox" type="checkbox" data-id="reddit" {{ ( 'undefined' === typeof social_icons.reddit ) ? '' : _.checked(social_icons.reddit.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-reddit-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-reddit">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/reddit"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.reddit ? '' : social_icons.reddit.link }}">

            </div>

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.linkedin ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-linkedin-custom" class="toggle-checkbox" type="checkbox" data-id="linkedin" {{ ( 'undefined' === typeof social_icons.linkedin ) ? '' : _.checked(social_icons.linkedin.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-linkedin-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-linkedin">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/linkedin"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.linkedin ? '' : social_icons.linkedin.link }}">

            </div>

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.vkontakte ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-vkontakte-custom" class="toggle-checkbox" type="checkbox" data-id="vkontakte" {{ ( 'undefined' === typeof social_icons.vkontakte ) ? '' : _.checked(social_icons.vkontakte.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-vkontakte-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-vkontakte">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/vkontakte"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.vkontakte ? '' : social_icons.vkontakte.link }}">

            </div>

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.fivehundredpx ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-fivehundredpx-custom" class="toggle-checkbox" type="checkbox" data-id="fivehundredpx" {{ ( 'undefined' === typeof social_icons.fivehundredpx ) ? '' : _.checked(social_icons.fivehundredpx.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-fivehundredpx-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-fivehundredpx">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/fivehundredpx"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.fivehundredpx ? '' : social_icons.fivehundredpx.link }}">

            </div>

			<div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.houzz ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-houzz-custom" class="toggle-checkbox" type="checkbox" data-id="houzz" {{ ( 'undefined' === typeof social_icons.houzz ) ? '' : _.checked(social_icons.houzz.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-houzz-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-houzz">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/houzz"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.houzz ? '' : social_icons.houzz.link }}">

            </div>

            <div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.instagram ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-instagram-custom" class="toggle-checkbox" type="checkbox" data-id="instagram" {{ ( 'undefined' === typeof social_icons.instagram ) ? '' : _.checked(social_icons.instagram.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-instagram-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-instagram">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/instagram"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.instagram ? '' : social_icons.instagram.link }}">

            </div>

			<div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.twitch ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-twitch-custom" class="toggle-checkbox" type="checkbox" data-id="twitch" {{ ( 'undefined' === typeof social_icons.twitch ) ? '' : _.checked(social_icons.twitch.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-twitch-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-twitch">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/twitch"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.twitch ? '' : social_icons.twitch.link }}">

            </div>

			<div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.youtube ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-youtube-custom" class="toggle-checkbox" type="checkbox" data-id="youtube" {{ ( 'undefined' === typeof social_icons.youtube ) ? '' : _.checked(social_icons.youtube.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-youtube-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-youtube">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/youtube"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.youtube ? '' : social_icons.youtube.link }}">

            </div>

			<div class="wpmudev-social-item {{ ( 'undefined' === typeof social_icons.telegram ) ? 'disabled' : '' }}">

                <div class="wpmudev-switch">

                    <input id="wph-sshares-telegram-custom" class="toggle-checkbox" type="checkbox" data-id="telegram" {{ ( 'undefined' === typeof social_icons.telegram ) ? '' : _.checked(social_icons.telegram.enabled, 'true') }}>

                    <label class="wpmudev-switch-design" for="wph-sshares-telegram-custom" aria-hidden="true"></label>

                </div>

                <div class="hustle-social-icon hustle-icon-rounded hustle-icon-telegram">

                    <div class="hustle-icon-container"><?php $this->render("general/icons/social/telegram"); ?></div>

                </div>

                <input class="wpmudev-input_text" type="text" placeholder="<?php esc_attr_e( "Type URL here", Opt_In::TEXT_DOMAIN ); ?>" value="{{ 'undefined' === typeof social_icons.telegram ? '' : social_icons.telegram.link }}">

            </div>

        </div>

    </div>

</div><?php // #wph-wizard-services-icons ?>
