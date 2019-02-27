<main id="wpmudev-hustle" class="wpmudev-ui wpmudev-hustle-welcome-view">

	<header id="wpmudev-hustle-title" class="wpmudev-has-button">

		<h1><?php esc_attr_e( "Social Shares Dashboard", Opt_In::TEXT_DOMAIN ); ?></h1>

		<a class="wpmudev-button wpmudev-button-sm wpmudev-button-ghost" href="<?php echo esc_url( $new_url ); ?>"><?php esc_attr_e('New Social Share', Opt_In::TEXT_DOMAIN); ?></a>

	</header>

	<section id="wpmudev-hustle-content">

		<div class="wpmudev-row">

			<div class="wpmudev-col col-12">

				<div id="wph-welcome-box" class="wpmudev-box" data-nonce="<?php echo esc_attr( wp_create_nonce('hustle_new_welcome_notice') ); ?>">

					<div class="wpmudev-box-head">

						<h2><?php printf( esc_attr__('Hello there, %s', Opt_In::TEXT_DOMAIN), esc_attr( $user_name ) ); ?></h2>

					</div>

					<div class="wpmudev-box-body wpmudev-box-hero">

						<div class="wpmudev-box-character"><?php $this->render("general/characters/character-one" ); ?></div>

						<div class="wpmudev-box-content">

							<h2><?php esc_attr_e( "Let's build your social game!", Opt_In::TEXT_DOMAIN ); ?></h2>

							<p><?php esc_attr_e( "Use floating icons, widgets and shortcodes to start pushing your social media accounts. Get more followers and go viral. Click Create to get started.", Opt_In::TEXT_DOMAIN ); ?></p>

							<p><a href="<?php echo esc_url( $new_url ); ?>" class="wpmudev-button wpmudev-button-blue"><?php esc_attr_e('Create', Opt_In::TEXT_DOMAIN); ?></a></p>

						</div>

					</div>

				</div><?php // .wpmudev-box ?>

			</div><?php // .wpmudev-col ?>

		</div><?php // .wpmudev-row ?>

	</section>

</main>
