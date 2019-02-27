<?php
//Direct Load
require_once dirname( __FILE__ ) . '/hustle-sendgrid.php';
require_once dirname( __FILE__ ) . '/hustle-sendgrid-form-settings.php';
Hustle_Providers::get_instance()->register( 'Hustle_SendGrid' );
