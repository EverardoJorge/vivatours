<div id="wph-reorder-icons" class="wpmudev-box-reorder">

    <# if ( _.isEmpty(icons_order) ) { #>

        <?php // default order ?>
        <# if ( 'undefined' !== typeof social_icons.facebook && _.isTrue(social_icons.facebook.enabled) ) { #>

            <div data-id="facebook" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/facebook"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.twitter && _.isTrue(social_icons.twitter.enabled) ) { #>

            <div data-id="twitter" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/twitter"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.google && _.isTrue(social_icons.google.enabled) ) { #>

            <div data-id="google" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/google"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.pinterest && _.isTrue(social_icons.pinterest.enabled) ) { #>

            <div data-id="pinterest" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/pinterest"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.reddit && _.isTrue(social_icons.reddit.enabled) ) { #>

            <div data-id="reddit" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/reddit"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.linkedin && _.isTrue(social_icons.linkedin.enabled) ) { #>

            <div data-id="linkedin" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/linkedin"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.vkontakte && _.isTrue(social_icons.vkontakte.enabled) ) { #>

            <div data-id="vkontakte" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/vkontakte"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.fivehundredpx && _.isTrue(social_icons.fivehundredpx.enabled) ) { #>

            <div data-id="fivehundredpx" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/fivehundredpx"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.houzz && _.isTrue(social_icons.houzz.enabled) ) { #>

            <div data-id="houzz" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/houzz"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.instagram && _.isTrue(social_icons.instagram.enabled) ) { #>

            <div data-id="instagram" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/instagram"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.twitch && _.isTrue(social_icons.twitch.enabled) ) { #>

            <div data-id="twitch" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/twitch"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.youtube && _.isTrue(social_icons.youtube.enabled) ) { #>

            <div data-id="youtube" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/youtube"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.telegram && _.isTrue(social_icons.telegram.enabled) ) { #>

            <div data-id="telegram" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/telegram"); ?></div>

            </div>

        <# } #>

    <# } else {
        var icons_order_arr = icons_order.split(',');
        _.each( icons_order_arr, function(icon, key){
            if ( 'undefined' !== typeof social_icons[icon] && _.isTrue(social_icons[icon].enabled) ) { #>
                <div data-id="{{icon}}" class="hustle-social-icon hustle-icon-{{icon_style}}">
                    <# if ( 'facebook' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/facebook"); ?></div>
                    <# } #>
                    <# if ( 'twitter' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/twitter"); ?></div>
                    <# } #>
                    <# if ( 'google' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/google"); ?></div>
                    <# } #>
                    <# if ( 'pinterest' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/pinterest"); ?></div>
                    <# } #>
                    <# if ( 'reddit' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/reddit"); ?></div>
                    <# } #>
                    <# if ( 'linkedin' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/linkedin"); ?></div>
                    <# } #>
                    <# if ( 'vkontakte' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/vkontakte"); ?></div>
                    <# } #>
                    <# if ( 'fivehundredpx' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/fivehundredpx"); ?></div>
                    <# } #>
                    <# if ( 'houzz' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/houzz"); ?></div>
                    <# } #>
                    <# if ( 'instagram' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/instagram"); ?></div>
                    <# } #>
                    <# if ( 'twitch' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/twitch"); ?></div>
                    <# } #>
                    <# if ( 'youtube' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/youtube"); ?></div>
                    <# } #>
                    <# if ( 'telegram' === icon ) { #>
                        <div class="hustle-icon-container"><?php $this->render("general/icons/social/telegram"); ?></div>
                    <# } #>

                </div>
    <#      }
        });
		 #>

		<?php //if the icon was not ordered, add it on the default order ?>
		<# if ( 'undefined' !== typeof social_icons.facebook && _.isTrue(social_icons.facebook.enabled) && _.isFalse(_.contains(icons_order_arr, 'facebook')) ) { #>

            <div data-id="facebook" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/facebook"); ?></div>

            </div>

		<# } #>
		        <# if ( 'undefined' !== typeof social_icons.twitter && _.isTrue(social_icons.twitter.enabled) && _.isFalse(_.contains(icons_order_arr, 'twitter')) ) { #>

            <div data-id="twitter" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/twitter"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.google && _.isTrue(social_icons.google.enabled) && _.isFalse(_.contains(icons_order_arr, 'google')) ) { #>

            <div data-id="google" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/google"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.pinterest && _.isTrue(social_icons.pinterest.enabled) && _.isFalse(_.contains(icons_order_arr, 'pinterest')) ) { #>

            <div data-id="pinterest" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/pinterest"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.reddit && _.isTrue(social_icons.reddit.enabled) && _.isFalse(_.contains(icons_order_arr, 'reddit')) ) { #>

            <div data-id="reddit" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/reddit"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.linkedin && _.isTrue(social_icons.linkedin.enabled) && _.isFalse(_.contains(icons_order_arr, 'linkedin')) ) { #>

            <div data-id="linkedin" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/linkedin"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.vkontakte && _.isTrue(social_icons.vkontakte.enabled) && _.isFalse(_.contains(icons_order_arr, 'vkontakte')) ) { #>

            <div data-id="vkontakte" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/vkontakte"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.fivehundredpx && _.isTrue(social_icons.fivehundredpx.enabled) && _.isFalse(_.contains(icons_order_arr, 'fivehundredpx')) ) { #>

            <div data-id="fivehundredpx" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/fivehundredpx"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.houzz && _.isTrue(social_icons.houzz.enabled) && _.isFalse(_.contains(icons_order_arr, 'houzz')) ) { #>

            <div data-id="houzz" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/houzz"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.instagram && _.isTrue(social_icons.instagram.enabled) && _.isFalse(_.contains(icons_order_arr, 'instagram')) ) { #>

            <div data-id="instagram" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/instagram"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.twitch && _.isTrue(social_icons.twitch.enabled) && _.isFalse(_.contains(icons_order_arr, 'twitch')) ) { #>

            <div data-id="twitch" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/twitch"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.youtube && _.isTrue(social_icons.youtube.enabled) && _.isFalse(_.contains(icons_order_arr, 'youtube')) ) { #>

            <div data-id="youtube" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/youtube"); ?></div>

            </div>

        <# } #>

        <# if ( 'undefined' !== typeof social_icons.telegram && _.isTrue(social_icons.telegram.enabled) && _.isFalse(_.contains(icons_order_arr, 'telegram')) ) { #>

            <div data-id="telegram" class="hustle-social-icon hustle-icon-{{icon_style}}">

                <div class="hustle-icon-container"><?php $this->render("general/icons/social/telegram"); ?></div>

            </div>

        <# } #>

	<#
    } #>

</div>
