<div id="wph-wizard-services-behaviour" class="wpmudev-box-content">

	<div class="wpmudev-box-right">

        <h4><strong><?php esc_attr_e( "Icons and behavior", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

        <label class="wpmudev-helper"><?php esc_attr_e( "Pick social icons you want to display and how they should behave. Default is an action that is native to the service. e.g. share on facebook, twitter etc. Custom allows you to add your profile links for these services.", Opt_In::TEXT_DOMAIN ); ?></label>

		<div class="wpmudev-tabs">

            <ul class="wpmudev-tabs-menu wpmudev-tabs-menu wpmudev-tabs-menu_lg wpmudev-icons_behaviour-options">

                <li class="wpmudev-tabs-menu_item {{ ( 'native' === service_type ) ? 'current' : '' }}">

                    <input type="checkbox" data-attribute="service_type" value="native" >

                    <label><?php esc_attr_e( "Default", Opt_In::TEXT_DOMAIN ); ?></label>

                </li>

                <li class="wpmudev-tabs-menu_item {{ ( 'custom' === service_type ) ? 'current' : '' }}">

                    <input type="checkbox" data-attribute="service_type" value="custom" >

                    <label><?php esc_attr_e( "Custom", Opt_In::TEXT_DOMAIN ); ?></label>

                </li>

            </ul>

        </div>

        <div id="wpmudev-sshare-counter-options" class="{{ ( 'custom' === service_type ) ? 'wpmudev-hidden' : '' }}">

			<h4><strong><?php esc_attr_e( "Counters", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

			<label class="wpmudev-helper"><?php esc_attr_e( 'Set the behaviour of the counters. "None" will disable the counters. "Click" will show the number of times a social icon has been clicked, not linked to actual service. "Native" will retrieve the number of shares from each network\'s API when available, linked to actual service.', Opt_In::TEXT_DOMAIN ); ?></label>

			<div class="wpmudev-tabs">

				<ul class="wpmudev-tabs-menu wpmudev-tabs-menu wpmudev-tabs-menu_lg wpmudev-icons_counter-options">

					<li class="wpmudev-tabs-menu_item {{ ( 'none' === click_counter ) ? 'current' : '' }}">

						<input type="checkbox" data-attribute="click_counter" value="none" >

						<label><?php esc_attr_e( "None", Opt_In::TEXT_DOMAIN ); ?></label>

					</li>

					<li class="wpmudev-tabs-menu_item {{ ( 'click' === click_counter ) ? 'current' : '' }}">

						<input type="checkbox" data-attribute="click_counter" value="click" >

						<label><?php esc_attr_e( "Click", Opt_In::TEXT_DOMAIN ); ?></label>

					</li>

					<li class="wpmudev-tabs-menu_item {{ ( 'native' === click_counter ) ? 'current' : '' }}">

						<input type="checkbox" data-attribute="click_counter" value="native" >

						<label><?php esc_attr_e( "Native", Opt_In::TEXT_DOMAIN ); ?></label>

					</li>

				</ul>

			</div>

		<label class="wpmudev-label--notice hustle-twitter-notice {{ ( 'native' !== click_counter ) ? 'wpmudev-hidden' : '' }}"><span><?php printf( esc_attr__( 'Twitter deprecated its native counter functionality. Sign-up to %1$s this service %2$s in order to retrieve your Twitter stats. Keep in mind that this only tracks new shares after you register your site. Linkedin and Google+ do not support counters.', Opt_In::TEXT_DOMAIN ), '<a href="http://newsharecounts.com/" target="_blank">', '</a>' ); ?></span></label>

		</div>

	</div>

</div><?php // #wph-wizard-services-behaviour ?>
