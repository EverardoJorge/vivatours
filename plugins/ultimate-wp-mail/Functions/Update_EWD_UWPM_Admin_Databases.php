<?php
/* The file contains all of the functions which make changes to the WordPress tables */

function EWD_UWPM_UpdateOptions() {
	global $EWD_UWPM_Full_Version;

	if (isset($_POST['custom_css'])) {update_option('EWD_UWPM_Custom_CSS', sanitize_text_field($_POST['custom_css']));}
	if (isset($_POST['add_unsubscribe_link'])) {update_option('EWD_UWPM_Add_Unsubscribe_Link', sanitize_text_field($_POST['add_unsubscribe_link']));}
	if (isset($_POST['unsubscribe_redirect_url'])) {update_option('EWD_UWPM_Unsubscribe_Redirect_URL', sanitize_text_field($_POST['unsubscribe_redirect_url']));}
	if (isset($_POST['add_subscribe_checkbox'])) {update_option('EWD_UWPM_Add_Subscribe_Checkbox', sanitize_text_field($_POST['add_subscribe_checkbox']));}
	if (isset($_POST['add_unsubscribe_checkbox'])) {update_option('EWD_UWPM_Add_Unsubscribe_Checkbox', sanitize_text_field($_POST['add_unsubscribe_checkbox']));}
	if (isset($_POST['track_opens'])) {update_option('EWD_UWPM_Track_Opens', sanitize_text_field($_POST['track_opens']));}
	if (isset($_POST['track_clicks'])) {update_option('EWD_UWPM_Track_Clicks', sanitize_text_field($_POST['track_clicks']));}
	if (isset($_POST['woocommerce_integration'])) {update_option('EWD_UWPM_WooCommerce_Integration', sanitize_text_field($_POST['woocommerce_integration']));}
	if (isset($_POST['Options_Submit'])) {
		array_walk($_POST['display_interests'], 'sanitize_text_field');
		update_option('EWD_UWPM_Display_Interests', $_POST['display_interests']);
	}
	if (isset($_POST['display_post_interests'])) {update_option('EWD_UWPM_Display_Post_Interests', sanitize_text_field($_POST['display_post_interests']));}
	if (isset($_POST['Options_Submit'])) {update_option('EWD_UWPM_Email_From_Name', sanitize_text_field($_POST['email_from_name']));}
	if (isset($_POST['Options_Submit'])) {update_option('EWD_UWPM_Email_From_Email', sanitize_email($_POST['email_from_email']));}

	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_Registers', sanitize_text_field($_POST['event_user_registers']));}
	if (isset($_POST['event_user_registers_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_Registers_Email', sanitize_text_field($_POST['event_user_registers_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_Profile_Updated', sanitize_text_field($_POST['event_user_profile_updated']));}
	if (isset($_POST['event_user_profile_updated_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_Profile_Updated_Email', sanitize_text_field($_POST['event_user_profile_updated_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_Role_Changed', sanitize_text_field($_POST['event_user_role_changed']));}
	if (isset($_POST['event_user_role_changed_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_Role_Changed_Email', sanitize_text_field($_POST['event_user_role_changed_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_Password_Reset', sanitize_text_field($_POST['event_user_password_reset']));}
	if (isset($_POST['event_user_password_reset_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_Password_Reset_Email', sanitize_text_field($_POST['event_user_password_reset_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_X_Time_Since_Login', sanitize_text_field($_POST['event_user_x_time_since_login']));}
	if (isset($_POST['event_user_x_time_since_login_count']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_X_Time_Since_Login_Count', sanitize_text_field($_POST['event_user_x_time_since_login_count']));}
	if (isset($_POST['event_user_x_time_since_login_unit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_X_Time_Since_Login_Unit', sanitize_text_field($_POST['event_user_x_time_since_login_unit']));}
	if (isset($_POST['event_user_x_time_since_login_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_User_X_Time_Since_Login_Email', sanitize_text_field($_POST['event_user_x_time_since_login_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_Post_Published', sanitize_text_field($_POST['event_post_published']));}
	if (isset($_POST['event_post_published_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_Post_Published_Email', sanitize_text_field($_POST['event_post_published_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_Post_Published_Interest', sanitize_text_field($_POST['event_post_published_interest']));}
	if (isset($_POST['event_post_published_interest_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_Post_Published_Interest_Email', sanitize_text_field($_POST['event_post_published_interest_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_New_Comment_On_Post', sanitize_text_field($_POST['event_new_comment_on_post']));}
	if (isset($_POST['event_new_comment_on_post_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_New_Comment_On_Post_Email', sanitize_text_field($_POST['event_new_comment_on_post_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_New_Product_Added', sanitize_text_field($_POST['event_wc_new_product_added']));}
	if (isset($_POST['event_wc_new_product_added_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_New_Product_Added_Email', sanitize_text_field($_POST['event_wc_new_product_added_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_X_Time_Since_Cart_Abandoned', sanitize_text_field($_POST['event_wc_x_time_since_cart_abandoned']));}
	if (isset($_POST['event_wc_x_time_since_cart_abandoned_count']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_X_Time_Since_Cart_Abandoned_Count', sanitize_text_field($_POST['event_wc_x_time_since_cart_abandoned_count']));}
	if (isset($_POST['event_wc_x_time_since_cart_abandoned_unit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_X_Time_Since_Cart_Abandoned_Unit', sanitize_text_field($_POST['event_wc_x_time_since_cart_abandoned_unit']));}
	if (isset($_POST['event_wc_x_time_since_cart_abandoned_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_X_Time_Since_Cart_Abandoned_Email', sanitize_text_field($_POST['event_wc_x_time_since_cart_abandoned_email']));}
	if (isset($_POST['Options_Submit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_X_Time_After_Purchase', sanitize_text_field($_POST['event_wc_x_time_after_purchase']));}
	if (isset($_POST['event_wc_x_time_after_purchase_count']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_X_Time_After_Purchase_Count', sanitize_text_field($_POST['event_wc_x_time_after_purchase_count']));}
	if (isset($_POST['event_wc_x_time_after_purchase_unit']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_X_Time_After_Purchase_Unit', sanitize_text_field($_POST['event_wc_x_time_after_purchase_unit']));}
	if (isset($_POST['event_wc_x_time_after_purchase_email']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Event_WC_X_Time_After_Purchase_Email', sanitize_text_field($_POST['event_wc_x_time_after_purchase_email']));}
	
	$Advanced_Send_Ons = array();
	$Counter = 0;
	while ($Counter < 200) {
		if (isset($_POST['Send_On_Action_Type_' . $Counter])) {

			$Advanced_Send_On['Send_On_ID'] = sanitize_text_field($_POST['Send_On_' . $Counter]);
			$Advanced_Send_On['Enabled'] = sanitize_text_field($_POST['Enable_Send_On_' . $Counter]);
			$Advanced_Send_On['Action_Type'] = sanitize_text_field($_POST['Send_On_Action_Type_' . $Counter]);
			$Advanced_Send_On['Includes'] = sanitize_text_field($_POST['Send_On_Includes_' . $Counter]);
			$Advanced_Send_On['Email_ID'] = sanitize_text_field($_POST['Send_On_Email_' . $Counter]);
			$Advanced_Send_On['Interval_Count'] = $_POST['Send_On_Interval_Count_' . $Counter];
			$Advanced_Send_On['Interval_Unit'] = $_POST['Send_On_Interval_Unit_' . $Counter];

			$Advanced_Send_Ons[] = $Advanced_Send_On;
			unset($Advanced_Send_On);
		}
		$Counter++;
	}
	if (isset($_POST['Options_Submit'])) {update_option('EWD_UWPM_Send_On_Actions', $Advanced_Send_Ons);}

	if (isset($_POST['subscribe_label']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Subscribe_Label', sanitize_text_field($_POST['subscribe_label']));}
	if (isset($_POST['unsubscribe_label']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Unsubscribe_Label', sanitize_text_field($_POST['unsubscribe_label']));}
	if (isset($_POST['login_to_select_topics_label']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Login_To_Select_Topics_Label', sanitize_text_field($_POST['login_to_select_topics_label']));}
	if (isset($_POST['select_topics_label']) and $EWD_UWPM_Full_Version == "Yes") {update_option('EWD_UWPM_Select_Topics_Label', sanitize_text_field($_POST['select_topics_label']));}
	
	$update_message = __("Options have been successfully updated.", 'ultimate-wp-mail');
	$update['Message'] = $update_message;
	$update['Message_Type'] = "Update";
	return $update;
}

function EWD_UWPM_UpdateLists() {
	$Email_Lists = array();
	$Counter = 0;
	while ($Counter < 30) {
		if (isset($_POST['Email_Lists_' . $Counter . '_List_Name'])) {
			$Prefix = 'Email_Lists_' . $Counter;

			$Email_List['ID'] = sanitize_text_field($_POST[$Prefix . '_ID']);
			$Email_List['List_Name'] = sanitize_text_field($_POST[$Prefix . '_List_Name']);
			$Email_List['Users'] = json_decode(stripslashes($_POST[$Prefix . '_List_Users']));
			$Email_List['Emails_Sent'] = json_decode(stripslashes($_POST[$Prefix . '_Emails_Sent']));
			$Email_List['Last_Email_Sent_Date'] = $_POST[$Prefix . '_Last_Email_Sent_Date'];

			if (is_array($Email_List['Users'])) {$Email_List['Number_Of_Users'] = sizeOf($Email_List['Users']);}

			$Email_Lists[] = $Email_List;
			unset($Email_List);
		}
		$Counter++;
	}
	if (isset($_POST['Lists_Submit'])) {update_option('EWD_UWPM_Email_Lists_Array', $Email_Lists);}

	$update_message = __("Lists have been successfully updated.", 'ultimate-wp-mail');
	$update['Message'] = $update_message;
	$update['Message_Type'] = "Update";
	return $update;
}

function EWD_UWPM_Unsubscribe() {
	$Unsubscribe_Redirect_URL = get_option("EWD_UWPM_Unsubscribe_Redirect_URL");

	$Email = sanitize_email($_GET['Email']);
	$User_ID = sanitize_text_field(substr($_GET['Code'], 0, strpos($_GET['Code'], 'PL')));
	
	$User = get_user_by('email', $Email);

	if ($User->ID == $User_ID) {update_usermeta($User_ID, 'EWD_UWPM_User_Unsubscribe', 'Yes');}

	if (wp_redirect($Unsubscribe_Redirect_URL)) {exit();}
}



