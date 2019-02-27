<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_User_Profile($atts) {
	// Include the required global variables, and create a few new ones
	global $wpdb, $user_message;
	global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Time = time();

	$CheckCookie = CheckLoginCookie();
	
	$Sql = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes' ORDER BY Field_Order";
	$Fields = $wpdb->get_results($Sql);
	if (isset($_GET['User_ID'])) {$UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", $_GET['User_ID']));}
	//elseif (isset(get_query_var('user_id')))) {$UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", get_query_var('user_id')));}
	

	if (!isset($UserData)) {return isset($feup_Label_Select_Valid_Profile) ? $feup_Label_Select_Valid_Profile: "";}

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'login_page' => '',
				'omit_fields' => '',
				'login_necessary' => 'Yes',
				'submit_text' => __('Edit Profile', 'front-end-only-users')),
			$atts
		)
	);
	if (get_option("EWD_FEUP_Label_Edit_Profile") != "") {$submit_text = get_option("EWD_FEUP_Label_Edit_Profile");}
	$feup_Label_Require_Login_Message =  get_option("EWD_FEUP_Label_Require_Login_Message");
	if ($feup_Label_Require_Login_Message == "") {$feup_Label_Require_Login_Message =  __('You must be logged in to access this page.', 'front-end-only-users');}
	$feup_Label_Please =  get_option("EWD_FEUP_Label_Please");
	if ($feup_Label_Please == "") {$feup_Label_Please = __("Please", 'front-end-only-users');}
	$feup_Label_To_Continue =  get_option("EWD_FEUP_Label_To_Continue");
	if ($feup_Label_To_Continue == "") {$feup_Label_To_Continue = __("To Continue", 'front-end-only-users');}
	$feup_Label_Login =  get_option("EWD_FEUP_Label_Login");
	if ($feup_Label_Login == "") {$feup_Label_Login = __("Login", 'front-end-only-users');}
	$feup_Label_Select_Valid_Profile =  get_option("EWD_FEUP_Label_Select_Valid_Profile");
	if ($feup_Label_Select_Valid_Profile == "") {$feup_Label_Select_Valid_Profile = __("Please select a valid user profile", 'front-end-only-users');}

	$ReturnString = "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= EWD_FEUP_Add_Modified_Styles();
											
	if ($CheckCookie['Username'] == "" and $login_necessary == "Yes") {
		$ReturnString .= $feup_Label_Require_Login_Message;
		if ($login_page != "") {$ReturnString .= "<br />" . $feup_Label_Please . " <a href='" . $login_page . "'>" . $feup_Label_Login . "</a> " . $feup_Label_To_Continue ;}
		return $ReturnString;
	}

	
	$ReturnString .= "<div id='ewd-feup-user-profile-div' class='ewd-feup-user-profile-div'>";
	
	$Omitted_Fields = explode(",", $omit_fields);
	
	foreach ($Fields as $Field) {
		if (!in_array($Field->Field_Name, $Omitted_Fields)) {
			$Value = "";
			foreach ($UserData as $UserField) {
				if ($Field->Field_Name == $UserField->Field_Name) {$Value = $UserField->Field_Value;}
			}
			$ReturnString .= "<div class='feup-user-profile-field'>";
			$ReturnString .= "<div id='ewd-feup-user-profile-label-" . $Field->Field_ID . "' class='ewd-feup-user-profile-label'>" . $Field->Field_Name . ": </div>";
			if ($Field->Field_Type != "picture" and $Field->Field_Type != "email" and $Field->Field_Type != "tel" and $Field->Field_Type != "url") {$ReturnString .= "<div class='ewd-feup-text-input ewd-feup-user-profile-input'>" . $Value . "</div>";}
			elseif ($Field->Field_Type == "email") {$ReturnString .= "<div class='ewd-feup-text-input ewd-feup-user-profile-input'><a href='mailto:" . $Value . "'>" . $Value . "</a></div>";}
			elseif ($Field->Field_Type == "tel") {$ReturnString .= "<div class='ewd-feup-text-input ewd-feup-user-profile-input'><a href='tel:+" . $Value . "'>" . $Value . "</a></div>";}
			elseif ($Field->Field_Type == "url") {$ReturnString .= "<div class='ewd-feup-text-input ewd-feup-user-profile-input'><a href='" . $Value . "'>" . $Value . "</a></div>";}
			else {$ReturnString .= "<img class='ewd-feup-profile-picture' src='" . site_url("/wp-content/uploads/ewd-feup-user-uploads/") . $Value . "' alt='" . $Field->Field_Name . "'/>";}
			$ReturnString .= "</div>";
		}
	}
	
	$ReturnString .= "</div>";
	
	return $ReturnString;
}
if ($EWD_FEUP_Full_Version == "Yes") {add_shortcode("user-profile", "Insert_User_Profile");}