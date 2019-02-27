<?php 
function EWD_FEUP_Version_Reversion() {
	if (get_option("EWD_FEUP_Trial_Happening") != "Yes" or time() < get_option("EWD_FEUP_Trial_Expiry_Time")) {return;}

	update_option("EWD_FEUP_Use_Captcha", "No");
	update_option("EWD_FEUP_Allow_Level_Choice", "No");
	update_option("EWD_FEUP_Track_Events", "No");
	update_option("EWD_FEUP_Email_Confirmation", "No");
	update_option("EWD_FEUP_Admin_Approval", "No");
	update_option("EWD_FEUP_Email_On_Admin_Approval", "No");
	update_option("EWD_FEUP_Admin_Email_On_Registration", "No");
	update_option("EWD_FEUP_Create_WordPress_Users", "No");
	update_option("EWD_FEUP_Login_Options", array());

	update_option("EWD_FEUP_Label_Login", "");
	update_option("EWD_FEUP_Label_Logout", "");
	update_option("EWD_FEUP_Label_Username", "");
	update_option("EWD_FEUP_Label_Register", "");
	update_option("EWD_FEUP_Label_Successful_Logout_Message", "");
	update_option("EWD_FEUP_Label_Require_Login_Message", "");

	update_option("EWD_FEUP_Label_Upgrade_Account", "");
	update_option("EWD_FEUP_Label_Update_Account", "");
	update_option("EWD_FEUP_Label_Upgrade_Level_Message", "");
	update_option("EWD_FEUP_Label_Level", "");
	update_option("EWD_FEUP_Label_Next", "");
	update_option("EWD_FEUP_Label_Discount_Message", "");
	update_option("EWD_FEUP_Label_Discount_Code", "");
	update_option("EWD_FEUP_Label_Use_Discount_Code", "");
	update_option("EWD_FEUP_Label_Edit_Profile", "");
	update_option("EWD_FEUP_Label_Current_File", "");
	update_option("EWD_FEUP_Label_Current_Picture", "");
	update_option("EWD_FEUP_Label_Update_Picture", "");
	update_option("EWD_FEUP_Label_Confirm_Email_Message", "");
	update_option("EWD_FEUP_Label_Incorrect_Confirm_Message", "");
	update_option("EWD_FEUP_Label_Captcha_Fail", "");
	update_option("EWD_FEUP_Label_Login_Successful", "");
	update_option("EWD_FEUP_Label_Login_Failed_Confirm_Email", "");
	update_option("EWD_FEUP_Label_Select_Valid_Profile", "");
	update_option("EWD_FEUP_Label_Nonlogged_Message", "");
	update_option("EWD_FEUP_Label_Low_Account_Level_Message", "");
	update_option("EWD_FEUP_Label_High_Account_Level_Message", "");
	update_option("EWD_FEUP_Label_Wrong_Account_Level_Message", "");
	update_option("EWD_FEUP_Label_Restrict_Access_Message", "");
	update_option("EWD_FEUP_Label_Login_Failed_Admin_Approval", "");
	update_option("EWD_FEUP_Label_Login_Failed_Payment_Required", "");
	update_option("EWD_FEUP_Label_Login_Failed_Incorrect_Credentials", "");

	update_option("EWD_FEUP_Label_Please", "");
	update_option("EWD_FEUP_Label_To_Continue", "");
	update_option("EWD_FEUP_Label_Password", "");
	update_option("EWD_FEUP_Label_Repeat_Password", "");
	update_option("EWD_FEUP_Label_Password_Strength", "");
	update_option("EWD_FEUP_Label_Reset_Password", "");
	update_option("EWD_FEUP_Label_Email", "");
	update_option("EWD_FEUP_Label_Reset_Code", "");
	update_option("EWD_FEUP_Label_Change_Password", "");
	update_option("EWD_FEUP_Label_Too_Short", "");
	update_option("EWD_FEUP_Label_Mismatch", "");
	update_option("EWD_FEUP_Label_Weak", "");
	update_option("EWD_FEUP_Label_Good", "");
	update_option("EWD_FEUP_Label_Strong", "");

	update_option("EWD_FEUP_Styling_Form_Font", "");
	update_option("EWD_FEUP_Styling_Form_Font_Size", "");
	update_option("EWD_FEUP_Styling_Form_Font_Weight", "");
	update_option("EWD_FEUP_Styling_Form_Font_Color", "");
	update_option("EWD_FEUP_Styling_Form_Margin", "");
	update_option("EWD_FEUP_Styling_Form_Padding", "");
	update_option("EWD_FEUP_Styling_Submit_Bg_Color", "");
	update_option("EWD_FEUP_Styling_Submit_Font", "");
	update_option("EWD_FEUP_Styling_Submit_Font_Color", "");
	update_option("EWD_FEUP_Styling_Submit_Margin", "");
	update_option("EWD_FEUP_Styling_Submit_Padding", "");

	update_option("EWD_FEUP_Styling_Userlistings_Font", "");
	update_option("EWD_FEUP_Styling_Userlistings_Font_Size", "");
	update_option("EWD_FEUP_Styling_Userlistings_Font_Weight", "");
	update_option("EWD_FEUP_Styling_Userlistings_Font_Color", "");
	update_option("EWD_FEUP_Styling_Userlistings_Margin", "");
	update_option("EWD_FEUP_Styling_Userlistings_Padding", "");
	update_option("EWD_FEUP_Styling_Userprofile_Label_Font", "");
	update_option("EWD_FEUP_Styling_Userprofile_Label_Font_Size", "");
	update_option("EWD_FEUP_Styling_Userprofile_Label_Font_Weight", "");
	update_option("EWD_FEUP_Styling_Userprofile_Label_Font_Color", "");
	update_option("EWD_FEUP_Styling_Userprofile_Content_Font", "");
	update_option("EWD_FEUP_Styling_Userprofile_Content_Font_Size", "");
	update_option("EWD_FEUP_Styling_Userprofile_Content_Font_Weight", "");
	update_option("EWD_FEUP_Styling_Userprofile_Content_Font_Color", "");

	update_option("EWD_FEUP_Full_Version", "No");
	update_option("EWD_FEUP_Trial_Happening", "No");
	delete_option("EWD_FEUP_Trial_Expiry_Time");
}
add_action('admin_init', 'EWD_FEUP_Version_Reversion');

?>