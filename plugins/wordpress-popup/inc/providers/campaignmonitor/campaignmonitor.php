<?php
//Direct Load
require_once dirname( __FILE__ ) . '/hustle-campaignmonitor.php';
require_once dirname( __FILE__ ) . '/hustle-campaignmonitor-form-settings.php';
Hustle_Providers::get_instance()->register( 'Hustle_Campaignmonitor' );
