<?php
// define method that MUST be implemented by addon here, if its optional, put it on abstract
interface Hustle_Provider_Interface {

	/**
	 * Use it to instantiate provider class
	 *
	 * @param string $class_name We can't avoid it via `static::` because we're supporting PHP 5.2
	 * @return self
	 */
	public static function get_instance();

	/**
	 * Use it to handle the the data submitted by users.
	 * Called when an opt-in form is submitted on frontend and your integration is enabled.
	 *
	 * @param Hustle_Module_Model $module Instance of the module making the submission.
	 * @param array $data Data submitted through the opt-in form by the user.
	 * @return true|WP_Error true on success, WP_Error with its error message to be shown to frontend users on failure.
	 */
	public function subscribe( Hustle_Module_Model $module, array $data );

}
