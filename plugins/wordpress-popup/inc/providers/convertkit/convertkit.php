<?php
//Direct Load
require_once dirname( __FILE__ ) . '/hustle-convertkit.php';
require_once dirname( __FILE__ ) . '/hustle-convertkit-form-settings.php';
Hustle_Providers::get_instance()->register( 'Hustle_ConvertKit' );
