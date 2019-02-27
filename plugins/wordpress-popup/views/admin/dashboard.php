<main id="wpmudev-hustle" class="wpmudev-ui wpmudev-hustle-dashboard">

    <header id="wpmudev-hustle-title">

		<h1><?php esc_attr_e("Dashboard", Opt_In::TEXT_DOMAIN); ?></h1>

	</header>

	<section id="wpmudev-hustle-content" class="wpmudev-container">

        <?php
		$new_welcome_notice_dismissed = (bool) get_option( "hustle_new_welcome_notice_dismissed", false );

        if ( !$has_modules && !$new_welcome_notice_dismissed ) :
			?>

            <?php $this->render("admin/dashboard/widget-welcome" ); ?>

        <?php endif; ?>

        <?php if ( $has_modules ) : ?>

            <?php
			$this->render("admin/dashboard/widget-resume", array(
                'has_modules' => $has_modules,
                'today_total_conversions' => $today_total_conversions,
                'most_converted_module' => $most_converted_module,
                'active_modules' => $active_modules,
                'user_name' => $user_name
            ) );
			?>

        <?php endif; ?>

        <?php if ( $has_modules ) : ?>

            <?php
			$this->render("admin/dashboard/widget-data", array(
                'has_modules' => $has_modules,
                'top_active_modules' => $top_active_modules,
            ) );
			?>

        <?php endif; ?>

        <?php
		$this->render("admin/dashboard/widget-modules", array(
            'popups' => $popups,
            'slideins' => $slideins,
            'embeds' => $embeds,
            'social_sharings' => $social_shares,
            'ss_share_stats_data' => $ss_share_stats_data,
            'ss_total_share_stats' => $ss_total_share_stats,
            'is_free' => Opt_In_Utils::_is_free(),
        ) );
		?>

    </section>

    <?php
	$this->render( "admin/dashboard/modals/cumulative-shares", array(
		'ss_share_stats_data' => $ss_share_stats_data,
		'ss_total_share_stats' => $ss_total_share_stats,
	) );
	if( Opt_In_Utils::_is_free() ) $this->render( "admin/commons/listing/modal-upgrade" );
	?>

</main>

<?php $this->render( 'admin/footer/footer-large' ); ?>