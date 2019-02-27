<?php
//Direct Load
require_once dirname( __FILE__ ) . '/hustle-sendy.php';
require_once dirname( __FILE__ ) . '/hustle-sendy-form-settings.php';
Hustle_Providers::get_instance()->register( 'Hustle_Sendy' );
