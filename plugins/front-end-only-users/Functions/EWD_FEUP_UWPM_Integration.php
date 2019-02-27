<?php
function EWD_FEUP_Add_UWPM_Element_Sections() {
	if (function_exists('uwpm_register_custom_element_section')) {
		uwpm_register_custom_element_section('ewd_feup_uwpm_elements', array('label' => 'Front-End Users Tags'));
	}
}
add_action('uwpm_register_custom_element_section', 'EWD_FEUP_Add_UWPM_Element_Sections');

function EWD_FEUP_Add_UWPM_Elements() {
	global $wpdb;
	global $ewd_feup_fields_table_name;

	if (function_exists('uwpm_register_custom_element')) {
		uwpm_register_custom_element('ewd_feup_username', 
			array(
				'label' => 'Username',
				'callback_function' => 'EWD_FEUP_UWPM_Username',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);
		uwpm_register_custom_element('ewd_feup_user_level', 
			array(
				'label' => 'User Level',
				'callback_function' => 'EWD_FEUP_UWPM_User_Level',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);
		uwpm_register_custom_element('ewd_feup_confirmation_link', 
			array(
				'label' => 'Confirmation Link',
				'callback_function' => 'EWD_FEUP_UWPM_Confirmation_Link',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);
		uwpm_register_custom_element('ewd_feup_admin_approved', 
			array(
				'label' => 'Admin Approved',
				'callback_function' => 'EWD_FEUP_UWPM_Admin_Approved',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);
		uwpm_register_custom_element('ewd_feup_date_created', 
			array(
				'label' => 'User Join Date',
				'callback_function' => 'EWD_FEUP_UWPM_Date_Created',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);
		uwpm_register_custom_element('ewd_feup_user_last_login', 
			array(
				'label' => 'User Last Login',
				'callback_function' => 'EWD_FEUP_UWPM_Last_Login',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);
		uwpm_register_custom_element('ewd_feup_password_reset_code', 
			array(
				'label' => 'Password Reset Code',
				'callback_function' => 'EWD_FEUP_UWPM_Password_Reset_Code',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);
		uwpm_register_custom_element('ewd_feup_membership_fees_paid', 
			array(
				'label' => 'Membership Fees Paid',
				'callback_function' => 'EWD_FEUP_UWPM_Membership_Fees_Paid',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);
		uwpm_register_custom_element('ewd_feup_membership_expiry_date', 
			array(
				'label' => 'Membership Expiry Date',
				'callback_function' => 'EWD_FEUP_UWPM_Membership_Expiry_Date',
				'section' => 'ewd_feup_uwpm_elements'
			)
		);

		$Fields = $wpdb->get_results("SELECT Field_Name, Field_Slug FROM $ewd_feup_fields_table_name");
		foreach ($Fields as $Field) {
			if ($Field->Field_Slug == '') {continue;}
			uwpm_register_custom_element('ewd_feup_' . $Field->Field_Slug, 
				array(
					'label' => $Field->Field_Name,
					'callback_function' => 'EWD_FEUP_UWPM_Field_Replace_Function',
					'section' => 'ewd_feup_uwpm_elements'
				)
			);
		}
	}
}
add_action('uwpm_register_custom_element', 'EWD_FEUP_Add_UWPM_Elements');

function EWD_FEUP_UWPM_Username($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	if (!isset($Params['feup_user_id'])) {
		$User_WP_ID = $Params['User_ID'];

		$wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $User_WP_ID));

		if ($User_ID == '') {return;}
		else {$Params['feup_user_id'] = $User_ID;}
	}

	return $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Params['feup_user_id']));
}

function EWD_FEUP_UWPM_User_Level($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	global $ewd_feup_levels_table_name;

	if (!isset($Params['feup_user_id'])) {
		$User_WP_ID = $Params['User_ID'];

		$wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $User_WP_ID));

		if ($User_ID == '') {return;}
		else {$Params['feup_user_id'] = $User_ID;}
	}

	$Level_ID = $wpdb->get_var($wpdb->prepare("SELECT Level_ID FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Params['feup_user_id']));

	return $wpdb->get_var($wpdb->prepare("SELECT Level_Name FROM $ewd_feup_levels_table_name WHERE Level_ID=%d", $Level_ID));
}

function EWD_FEUP_UWPM_Confirmation_Link($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	if (isset($Params['Confirmation_Link'])) {
		return'<table><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"><a href="' . urlencode($Params['Confirmation_Link']) . '" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">' . __("Verify Email", 'front-end-only-users') . '</a></td></tr></table>';
	}
	else {return;}
}

function EWD_FEUP_UWPM_Admin_Approved($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	if (!isset($Params['feup_user_id'])) {
		$User_WP_ID = $Params['User_ID'];

		$wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $User_WP_ID));

		if ($User_ID == '') {return;}
		else {$Params['feup_user_id'] = $User_ID;}
	}

	return $wpdb->get_var($wpdb->prepare("SELECT User_Admin_Approved FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Params['feup_user_id']));
}

function EWD_FEUP_UWPM_Date_Created($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	if (!isset($Params['feup_user_id'])) {
		$User_WP_ID = $Params['User_ID'];

		$wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $User_WP_ID));

		if ($User_ID == '') {return;}
		else {$Params['feup_user_id'] = $User_ID;}
	}

	return $wpdb->get_var($wpdb->prepare("SELECT User_Date_Created FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Params['feup_user_id']));
}

function EWD_FEUP_UWPM_Last_Login($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	if (!isset($Params['feup_user_id'])) {
		$User_WP_ID = $Params['User_ID'];

		$wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $User_WP_ID));

		if ($User_ID == '') {return;}
		else {$Params['feup_user_id'] = $User_ID;}
	}

	return $wpdb->get_var($wpdb->prepare("SELECT User_Last_Login FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Params['feup_user_id']));
}

function EWD_FEUP_UWPM_Password_Reset_Code($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	if (isset($Params['Reset_Code'])) {
		return'<table><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"><a href="' . site_url() . "/" . $Params['Reset_Email_URL'] . "?add=" . urlencode($Params['User_Email']) . "&rc=" . $Params['Reset_Code'] . '" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">' . __("Reset Password", 'front-end-only-users') . '</a></td></tr></table>';
	}
	else {return;}
}

function EWD_FEUP_UWPM_Membership_Fees_Paid($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	if (!isset($Params['feup_user_id'])) {
		$User_WP_ID = $Params['User_ID'];

		$wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $User_WP_ID));

		if ($User_ID == '') {return;}
		else {$Params['feup_user_id'] = $User_ID;}
	}

	return $wpdb->get_var($wpdb->prepare("SELECT User_Membership_Fees_Paid FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Params['feup_user_id']));
}

function EWD_FEUP_UWPM_Membership_Expiry_Date($Params, $User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	if (!isset($Params['feup_user_id'])) {
		$User_WP_ID = $Params['User_ID'];

		$wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $User_WP_ID));

		if ($User_ID == '') {return;}
		else {$Params['feup_user_id'] = $User_ID;}
	}

	return $wpdb->get_var($wpdb->prepare("SELECT User_Account_Expiry FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Params['feup_user_id']));
}

function EWD_FEUP_UWPM_Field_Replace_Function($Params, $User) {
	global $wpdb;
	global $ewd_feup_fields_table_name;
	global $ewd_feup_user_fields_table_name;
	
	if (!isset($Params['feup_user_id'])) {
		$User_WP_ID = $Params['User_ID'];

		$wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $User_WP_ID));

		if ($User_ID == '') {return;}
		else {$Params['feup_user_id'] = $User_ID;}
	}

	if (!isset($Params['replace_slug'])) {return;}

	$Field = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Slug=%s", substr($Params['replace_slug'], 9)));

	return $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field->Field_ID, $Params['feup_user_id']));
}
