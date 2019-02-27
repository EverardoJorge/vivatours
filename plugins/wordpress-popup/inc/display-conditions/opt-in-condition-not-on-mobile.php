<?php

class Opt_In_Condition_Not_On_Mobile extends Opt_In_Condition_Abstract implements Opt_In_Condition_Interface {
	public function is_allowed( Hustle_Model $optin ){
		return !wp_is_mobile();
	}

	public function label() {
		return __("Not on mobile", Opt_In::TEXT_DOMAIN);
	}
}
