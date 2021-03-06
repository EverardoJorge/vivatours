<div class="wp-suggested-text">
	<h2><?php esc_html_e( 'Which modules collect personal data?', Opt_In::TEXT_DOMAIN ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php esc_html_e( 'If you use Hustle to create and embed any Pop-up, Embed, Slide-in, or Social share module, you may need to mention it here to properly distinguish it from other plugins.',
		                  Opt_In::TEXT_DOMAIN ); ?>
	</p>

	<h2><?php esc_html_e( 'What personal data do we collect and why?', Opt_In::TEXT_DOMAIN ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php _e( 'By default, Hustle captures the <strong>IP Address</strong> for each conversion and for each view only if the "tracking" functionality is enabled. Other personal data such as your <strong>name</strong> and <strong>email address</strong> may also be captured,
depending on the form fields.',
		          Opt_In::TEXT_DOMAIN );//wpcs : xss ok ?>
	</p>
	<p class="privacy-policy-tutorial">
		<i>
			<?php esc_html_e( 'Note: In this section you should include any personal data you collected and which form captures personal data to give users more relevant information. You should also include an explanation of why this data is needed. The explanation must note either the legal basis for your data collection and retention of the active consent the user has given.',
			                  Opt_In::TEXT_DOMAIN ); ?></i>
	</p>
	<p>
		<strong class="privacy-policy-tutorial"><?php esc_html_e( 'Suggested text: ', Opt_In::TEXT_DOMAIN ); ?></strong>
		<?php _e( 'When visitors or users submit a form or view a module, we capture the <strong>IP Address</strong> for analyisis purposes. We also capture the <strong>email address</strong> and might capture other personal data included in the form fields.',
		          Opt_In::TEXT_DOMAIN );//wpcs : xss ok ?>
	</p>

	<h2><?php esc_html_e( 'How long we retain your data', Opt_In::TEXT_DOMAIN ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php _e( 'By default Hustle retains all form submissions and tracking data <strong>forever</strong>. You can delete the stored data in <strong>Hustle</strong> &raquo; <strong>Settings</strong> &raquo;
		<strong>Privacy Settings</strong>, and under each module\'s settings.',
		          Opt_In::TEXT_DOMAIN );//wpcs : xss ok ?>
	</p>

	<p>
		<strong class="privacy-policy-tutorial"><?php esc_html_e( 'Suggested text: ', Opt_In::TEXT_DOMAIN ); ?></strong>
		<?php esc_html_e( 'When visitors or users submit a form or view a module we retain the data for 30 days.', Opt_In::TEXT_DOMAIN ); ?>
	</p>
	<h2><?php esc_html_e( 'Where we send your data', Opt_In::TEXT_DOMAIN ); ?></h2>
	<p>
		<strong class="privacy-policy-tutorial"><?php esc_html_e( 'Suggested text: ', Opt_In::TEXT_DOMAIN ); ?></strong>
		<?php esc_html_e( 'All collected data might be shown publicly and we send it to our workers or contractors to perform necessary actions based on the form submission.', Opt_In::TEXT_DOMAIN ); ?>
	</p>
	<h2><?php esc_html_e( 'Third Parties', Opt_In::TEXT_DOMAIN ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php esc_html_e( 'If your forms use either built-in or external third party services, in this section you should mention any third parties and its privacy policy.',
		                  Opt_In::TEXT_DOMAIN ); ?>
	</p>
	<p class="privacy-policy-tutorial">
		<?php esc_html_e( 'By default Hustle optionally use these third party integrations:' ); ?>
	</p>
	<ul class="privacy-policy-tutorial">
		<li><?php esc_html_e( 'ActiveCampaign. Enabled when you activate and setup ActiveCampaign on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Aweber. Enabled when you activate and setup Aweber on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Campaign Monitor. Enabled when you activate and setup Campaign Monitor on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Constant Contact. Enabled when you activate and setup Constant Contact on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'ConvertKit. Enabled when you activate and setup ConvertKit on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'e-Newsletter. Enabled when you activate and setup e-Newsletter on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'GetResponse. Enabled when you activate and setup GetResponse on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'HubSpot. Enabled when you activate and setup HubSpot on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'iContact. Enabled when you activate and setup iContact on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Infusionsoft. Enabled when you activate and setup Infusionsoft on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Mad Mimi. Enabled when you activate and setup Mad Mimi on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Mailchimp. Enabled when you activate and setup Mailchimp on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'MailerLite. Enabled when you activate and setup MailerLite on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Mautic. Enabled when you activate and setup Mautic on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'SendGrid. Enabled when you activated and setup SendGrid on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'SendinBlue. Enabled when you activated and setup SendinBlue on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Sendy. Enabled when you activated and setup Sendy on Email Collection settings.' ); ?></li>
		<li><?php esc_html_e( 'Zapier. Enabled when you activated and setup Zapier on Email Collection settings.' ); ?></li>
		<?php echo esc_html( $external_integrations_list ); ?>
	</ul>
	<p>
		<strong class="privacy-policy-tutorial"><?php esc_html_e( 'Suggested text: ', Opt_In::TEXT_DOMAIN ); ?></strong>
	<p><?php esc_html_e( 'We use ActiveCampaign to manage our subscriber lists. Their privacy policy can be found here : https://www.activecampaign.com/privacy-policy/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use Aweber to manage our subscriber. Their privacy policy can be found here : https://www.aweber.com/privacy.htm.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use Campaign Monitor to manage our subscriber. Their privacy policy can be found here : https://www.campaignmonitor.com/policies/#privacy-policy.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use Constant Contact to manage our subscriber. Their privacy policy can be found here : https://www.endurance.com/privacy.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use ConvertKit to manage our subscriber. Their privacy policy can be found here : https://convertkit.com/privacy/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use e-Newsletter to manage our subscriber. You can learn more about it here https://premium.wpmudev.org/project/e-newsletter/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use GetResponse to manage our subscriber lists. Their privacy policy can be found here : https://www.getresponse.com/legal/privacy.html?lang=en.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use HubSpot to manage our subscriber. Their privacy policy can be found here : https://legal.hubspot.com/legal-stuff.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use iContact to manage our subscriber. Their privacy policy can be found here : https://www.icontact.com/legal/privacy.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use Infusionsoft to manage our subscriber. Their privacy policy can be found here : https://www.infusionsoft.com/legal/privacy-policy.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use Mad Mimi to manage our subscriber. Their privacy policy can be found here : https://madmimi.com/legal/terms.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use MailChimp to manage our subscriber list. Their privacy policy can be found here : https://mailchimp.com/legal/privacy/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use MailerLite to manage our subscriber. Their privacy policy can be found here : https://www.mailerlite.com/privacy-policy.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use Mautic to manage our subscriber. Their privacy policy can be found here : https://www.mautic.org/privacy-policy/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use SendGrid to manage our subscriber. Their privacy policy can be found here : https://sendgrid.com/policies/privacy/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use SendinBlue to manage our subscriber. Their privacy policy can be found here : https://www.sendinblue.com/legal/privacypolicy/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use Sendy to manage our subscriber. Their privacy policy can be found here : https://sendy.co/privacy-policy.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use MailChimp to manage our subscriber list. Their privacy policy can be found here : https://mailchimp.com/legal/privacy/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<p><?php esc_html_e( 'We use Zapier to manage our integration data. Their privacy policy can be found here : https://zapier.com/privacy/.', Opt_In::TEXT_DOMAIN ); ?></p>
	<?php echo esc_html( $external_integrations_privacy_url_list ); ?>

	<h2><?php esc_html_e( 'Cookies', Opt_In::TEXT_DOMAIN ); ?></h2>
	<p class="privacy-policy-tutorial">
		<?php _e( 'By default Hustle uses cookies to count how many times each module is visualized. Cookies might be used to handle other features such as display settings, used when a module should not be displayed for a certain time,
		whether the user commented before, whether the user has subscribed, among others, if their related settings are enabled.',
		          Opt_In::TEXT_DOMAIN );//wpcs : xss ok ?>
	</p>


</div>

