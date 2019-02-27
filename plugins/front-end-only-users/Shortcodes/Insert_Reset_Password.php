<?php
function Insert_Reset_Password_Form($atts) {
	global $wpdb, $user_message, $feup_success;
	global $ewd_feup_user_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Time = time();

	$feup_Label_Require_Login_Message =  get_option("EWD_FEUP_Label_Require_Login_Message");
	if ($feup_Label_Require_Login_Message == "") {$feup_Label_Require_Login_Message =  __('You must be logged in to access this page.', 'front-end-only-users');}
	$feup_Label_Please =  get_option("EWD_FEUP_Label_Please");
	if ($feup_Label_Please == "") {$feup_Label_Please = __("Please", 'front-end-only-users');}
	$feup_Label_To_Continue =  get_option("EWD_FEUP_Label_To_Continue");
	if ($feup_Label_To_Continue == "") {$feup_Label_To_Continue = __("To Continue", 'front-end-only-users');}
	$feup_Label_Login =  get_option("EWD_FEUP_Label_Login");
	if ($feup_Label_Login == "") {$feup_Label_Login = __("Login", 'front-end-only-users');}
	$feup_Label_Email =  get_option("EWD_FEUP_Label_Email");
	if ($feup_Label_Email == "") {$feup_Label_Email = __("Email", 'front-end-only-users');}
	$feup_Label_Password =  get_option("EWD_FEUP_Label_Password");
	if ($feup_Label_Password == "") {$feup_Label_Password = __("Password", 'front-end-only-users');}
	$feup_Label_Repeat_Password = get_option("EWD_FEUP_Label_Repeat_Password");
	if ($feup_Label_Repeat_Password == "") {$feup_Label_Repeat_Password = __("Repeat Password", 'front-end-only-users');}

	$ReturnString = "";

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'redirect_page' => '#',
				'login_page' => '',
				'submit_text' => __('Update Account', 'front-end-only-users')),
			$atts
		)
	);
	if (get_option("EWD_FEUP_Label_Update_Account") != "") {$submit_text = get_option("EWD_FEUP_Label_Update_Account");}
	
	$CheckCookie = CheckLoginCookie();
		
	if ($CheckCookie['Username'] == "") {
		$ReturnString .= $feup_Label_Require_Login_Message;
		if ($login_page != "") {$ReturnString .= "<br />" . $feup_Label_Please . " <a href='" . $login_page . "'>" . $feup_Label_Login . "</a> " . $feup_Label_To_Continue ;}
		return $ReturnString;
	}
		
	/*$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
	$Fields = $wpdb->get_results($Sql);*/
	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
												
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= "</style>";
		
	if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
		
	$ReturnString .= "<div id='ewd-feup-edit-profile-form-div' class='ewd-feup-form-div'>";
	if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
	$ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-profile-form' class='feup-pure-form feup-pure-form-aligned'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-account'>";
	$ReturnString .= "<input type='hidden' name='Username' value='" . $User->Username . "'>";
	$ReturnString .= "<div id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . $feup_Label_Email . ": " . $User->Username . "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='User_Password' id='ewd-feup-edit-password' class='ewd-feup-field-label'>" . $feup_Label_Password . ": </label>";
	$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password' class='ewd-feup-text-input' value='' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='Confirm_User_Password' id='ewd-feup-edit-confirm-password' class='ewd-feup-field-label'>" . $feup_Label_Repeat_Password . ": </label>";
	$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password' class='ewd-feup-text-input' value='' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Edit_Password_Submit' value='" . $submit_text . "'></div>";
	$ReturnString .= "</div>";
	$ReturnString .= "</form>";

	return $ReturnString;
}
add_shortcode("reset-password", "Insert_Reset_Password_Form");
?>
