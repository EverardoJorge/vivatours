<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Privilege_Level($atts, $content = null) {
	// Include the required global variables, and create a few new ones
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_levels_table_name, $ewd_feup_user_fields_table_name;
	
	$Payment_Types = get_option("EWD_FEUP_Payment_Types");
	$feup_Label_Require_Login_Message =  get_option("EWD_FEUP_Label_Require_Login_Message");
	if ($feup_Label_Require_Login_Message == "") {$feup_Label_Require_Login_Message =  __('You must be logged in to access this page.', 'front-end-only-users');}
	$feup_Label_Please =  get_option("EWD_FEUP_Label_Please");
	if ($feup_Label_Please == "") {$feup_Label_Please = __("Please", 'front-end-only-users');}
	$feup_Label_To_Continue =  get_option("EWD_FEUP_Label_To_Continue");
	if ($feup_Label_To_Continue == "") {$feup_Label_To_Continue = __("To Continue", 'front-end-only-users');}
	$feup_Label_Login =  get_option("EWD_FEUP_Label_Login");
	if ($feup_Label_Login == "") {$feup_Label_Login = __("Login", 'front-end-only-users');}
	$feup_Label_Nonlogged_Message =  get_option("EWD_FEUP_Label_Nonlogged_Message");
	if ($feup_Label_Nonlogged_Message == "") {$feup_Label_Nonlogged_Message = __("This content is only for non-logged in users", 'front-end-only-users');}
	$feup_Label_Low_Account_Level_Message =  get_option("EWD_FEUP_Label_Low_Account_Level_Message");
	if ($feup_Label_Low_Account_Level_Message == "") {$feup_Label_Low_Account_Level_Message =  __("Sorry, your account level is too low to access this content.", 'front-end-only-users');}
	$feup_Label_High_Account_Level_Message =  get_option("EWD_FEUP_Label_High_Account_Level_Message");
	if ($feup_Label_High_Account_Level_Message == "") {$feup_Label_High_Account_Level_Message = __("Sorry, your account level is too high to access this content.", 'front-end-only-users');}
	$feup_Label_Wrong_Account_Level_Message =  get_option("EWD_FEUP_Label_Wrong_Account_Level_Message");
	if ($feup_Label_Wrong_Account_Level_Message == "") {$feup_Label_Wrong_Account_Level_Message = __("Sorry, your account isn't the correct level to access this content.", 'front-end-only-users');}

	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$ReturnString= "";
	
	$UserCookie = CheckLoginCookie();
	
	if ($UserCookie) {
		$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $UserCookie['Username']));
		$PrivilegeLevel = $wpdb->get_row($wpdb->prepare("SELECT Level_Privilege FROM $ewd_feup_levels_table_name WHERE Level_ID='%d'", $User->Level_ID));
		$User_Data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", $User->User_ID));
	}

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
								 	'login_page' => '',
								 	'block_logged_in' => '',
									'no_message' => '',
									'minimum_level' => '',
									'maximum_level' => '',
									'level' => '',
									'field_name' => '',
									'field_value' => '',
									'multiple_fields' => 'No',
									'logic' => 'AND',
									'sneak_peak_characters' => 0,
									'sneak_peak_words' => 0),
									$atts
							)
					);
	
	if ($block_logged_in == "Yes" and $UserCookie) {
		$ReturnString .= $feup_Label_Nonlogged_Message;
		if ($no_message != "Yes") {return $ReturnString;}
		else {return;}
	}
	elseif ($block_logged_in == "Yes") {
		return do_shortcode($content);
	}

	if (!$UserCookie) {
		if ($sneak_peak_characters > 0) {$ReturnString .= substr(do_shortcode($content), 0, $sneak_peak_characters) . "...<br>";}
		if ($sneak_peak_words > 0) {$ReturnString .= Return_Until_Nth_Occurance(do_shortcode($content), " ", $sneak_peak_words) . "...<br>";}
		
	 $ReturnString .= $feup_Label_Require_Login_Message;
		if ($login_page != "") {$ReturnString .= "<br />" . $feup_Label_Please . " <a href='" . $login_page . "'>" . $feup_Label_Login . "</a> " . $feup_Label_To_Continue ;}
		if ($no_message != "Yes") {return $ReturnString;}
		else {return;}
	}
		
	$ReturnString = do_shortcode($content);
	
	if ($minimum_level != '' and $PrivilegeLevel->Level_Privilege < $minimum_level) {
		$ReturnString = "<div class='ewd-feup-error'>" . $feup_Label_Low_Account_Level_Message . "</div>";
		if ($Payment_Types == "Levels") {$ReturnString .= do_shortcode("[account-payment]");}
	}
	if ($maximum_level != '' and $PrivilegeLevel->Level_Privilege > $maximum_level) {$ReturnString = "<div class='ewd-feup-error'>" .  $feup_Label_High_Account_Level_Message . "</div>";}
	if(!isset($level_check)) { $level_check = "";}
	$level_array = explode(",", trim($level_check));
	if (sizeOf($level_array) > 1) {
	  foreach($level_array as $level_item) {
	    if ($level_item != '' and $PrivilegeLevel->Level_Privilege == $level_item) { $level = $level_item;  }  //Check each piece and if it matches, set $level to users privilege level.
	  }
	}
	if ($level != '' and $PrivilegeLevel->Level_Privilege != $level) {$ReturnString = "<div class='ewd-feup-error'>" .  $feup_Label_Wrong_Account_Level_Message . "</div>";}
	if ($field_name != '') {
		if ($multiple_fields == "Yes") {$Field_Values = explode(",", $field_value);}
		foreach ($User_Data as $Field) {
			if ($Field->Field_Name == $field_name and ($Field->Field_Value == $field_value) or ($multiple_fields == "Yes" and in_array($Field->Field_Value, $Field_Values)) or ($logic == "OR" and strpos($Field->Field_Value, $field_value))) {$Validate = "Yes";}
		}
		if (!isset($Validate)) { $Validate = "";}
		if ($Validate != "Yes") {$ReturnString = "<div class='ewd-feup-error'>" . __("Sorry, this content is only for those whose " . $field_name . " is " . $field_value . ".", 'front-end-only-users') . "</div>";}
	}
	
	if (substr($ReturnString, 0, 28) != "<div class='ewd-feup-error'>" or $no_message != "Yes") {return $ReturnString;}
}
add_shortcode("restricted", "Privilege_Level");


function Return_Until_Nth_Occurance($String, $Needle, $N) {
		$Count = 0;
		$ReturnString = "";
		
		while ($Count < $N) {
				$Pos = strpos($String, $Needle);
				if (strpos($String, $Needle) === false) {$Pos = strlen($String); $Count = $N;}
				$ReturnString .= substr($String, 0, $Pos) . $Needle;
				$String = substr($String, $Pos+1);
				$Count++;
		}
		
		return $ReturnString;
}


?>