<?php

class Opt_In_Condition_Visitor_Has_Commented extends Opt_In_Condition_Abstract implements Opt_In_Condition_Interface {
	public function is_allowed( Hustle_Model $optin ){
		return $this->utils()->has_user_commented();
	}

	public function label() {
		return __("Only if user has commented", Opt_In::TEXT_DOMAIN);
	}
}
