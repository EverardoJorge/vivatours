<?php
function Update_EWD_FEUP_Tables() {
	/* Add in the required globals to be able to create the tables */
  	global $wpdb;
   	global $EWD_FEUP_db_version;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_events_table_name, $ewd_feup_payments_table_name;
    
	/* Create the users table */  
   	$sql = "CREATE TABLE $ewd_feup_user_table_name (
  		User_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Username text NULL,
		User_Password text NULL,
		Level_ID mediumint(9) DEFAULT 0 NOT NULL,
		User_Email_Confirmed text NULL,
		User_Confirmation_Code text NULL,
		User_Admin_Approved text NULL,
		User_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Last_Login datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Total_Logins mediumint(9) DEFAULT 0 NOT NULL,
		User_Password_Reset_Code text NULL,
		User_Password_Reset_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Sessioncheck varchar(255) DEFAULT NULL,
		User_WP_ID mediumint(9) DEFAULT 0 NOT NULL,
		User_Membership_Fees_Paid text NULL,
		User_Account_Expiry text NULL,
		User_Registration_Type text NULL,
		User_Third_Party_ID text NULL,
  		UNIQUE KEY id (User_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the fields table */
	$sql = "CREATE TABLE $ewd_feup_fields_table_name (
  		Field_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Field_Name text   NULL,
  		Field_Slug text NULL,
		Field_Description text   NULL,
		Field_Type text   NULL,
		Field_Options text   NULL,
		Field_Show_In_Admin text   NULL,
		Field_Show_In_Front_End   text NULL,
		Field_Required text   NULL,
		Field_Order mediumint(9) DEFAULT 0 NOT NULL,
		Field_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
		Field_Equivalent text   NULL,
		Level_Exclude_IDs text   NULL,
  		UNIQUE KEY id (Field_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the user-fields table */
	$sql = "CREATE TABLE $ewd_feup_user_fields_table_name (
  		User_Field_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		Field_ID mediumint(9) DEFAULT 0 NOT NULL,
		User_ID mediumint(9) DEFAULT 0 NOT NULL,
  		Field_Name text NULL,
		Field_Value text NULL,
		User_Field_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (User_Field_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the levels table */
	$sql = "CREATE TABLE $ewd_feup_levels_table_name (
  		Level_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Level_Name text   NULL,
		Level_Privilege text   NULL,
		Level_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (Level_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);

   	/* Create the user-events table */
	$sql = "CREATE TABLE $ewd_feup_user_events_table_name (
  		User_Event_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		User_ID mediumint(9) DEFAULT 0 NOT NULL,
  		Event_Type text NULL,
  		Event_Location text NULL,
  		Event_Location_ID mediumint(9) DEFAULT 0 NOT NULL,
  		Event_Location_Title text NULL,
		Event_Value text NULL,
		Event_Target_ID mediumint(9) DEFAULT 0 NOT NULL,
		Event_Target_Title text NULL,
		Event_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (User_Event_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);

   	/* Create the payments table */
	$sql = "CREATE TABLE $ewd_feup_payments_table_name (
  		Payment_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		User_ID mediumint(9) DEFAULT 0 NOT NULL,
		Username text NULL,
		Payer_ID text NULL,
  		PayPal_Receipt_Number text NULL,
  		Payment_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		Next_Payment_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		Payment_Amount text NULL,
  		Discount_Code_Used text NULL,
  		UNIQUE KEY id (Payment_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
 	
 	if (get_option("EWD_FEUP_First_Install_Version") == "") {update_option("EWD_FEUP_First_Install_Version", "2.6");}
 	if (get_option("EWD_FEUP_Minimum_Password_Length") == "") {add_option("EWD_FEUP_Minimum_Password_Length", 3);}
 	if (get_option("EWD_FEUP_Include_WP_Users") == "") {add_option("EWD_FEUP_Include_WP_Users", "No");}
   	if (get_option("EWD_FEUP_Use_Crypt") == "true" or get_option("EWD_FEUP_Use_Crypt")) {add_option("EWD_FEUP_Use_Crypt", "Yes");}
   	if (get_option("EWD_FEUP_Use_Crypt") == "false" or get_option("EWD_FEUP_Use_Crypt") == "") {add_option("EWD_FEUP_Use_Crypt", "No");}
	if (get_option("EWD_FEUP_Username_Is_Email") == "true" or get_option("EWD_FEUP_Username_Is_Email")) {add_option("EWD_FEUP_Username_Is_Email", "Yes");}
	if (get_option("EWD_FEUP_Username_Is_Email") == "false" or get_option("EWD_FEUP_Username_Is_Email") == "") {add_option("EWD_FEUP_Username_Is_Email", "No");}
	if (get_option("EWD_FEUP_Show_TinyMCE") == "") {add_option("EWD_FEUP_Show_TinyMCE", "No");}

	if (get_option("EWD_FEUP_Use_Captcha") == "") {add_option("EWD_FEUP_Use_Captcha", "No");}
	if (get_option("EWD_FEUP_Allow_Level_Choice") == "") {add_option("EWD_FEUP_Allow_Level_Choice", "No");}
	if (get_option("EWD_FEUP_Track_Events") == "") {add_option("EWD_FEUP_Track_Events", "No");}
	if (get_option("EWD_FEUP_Create_WordPress_Users") == "") {update_option("EWD_FEUP_Create_WordPress_Users", "No");}

	if (get_option("EWD_FEUP_Use_SMTP") == "") {update_option("EWD_FEUP_Use_SMTP", "Yes");}
	if (get_option("EWD_FEUP_Port") == "") {update_option("EWD_FEUP_Port", "25");}

	if (get_option("EWD_FEUP_Payment_Frequency") == "") {update_option("EWD_FEUP_Payment_Frequency", "None");}
	if (get_option("EWD_FEUP_Payment_Types") == "") {update_option("EWD_FEUP_Payment_Types", "Membership");}
	if (get_option("EWD_FEUP_Membership_Cost") == "") {update_option("EWD_FEUP_Membership_Cost", "0");}
	if (get_option("EWD_FEUP_Free_Trial_Days") == "") {update_option("EWD_FEUP_Free_Trial_Days", 0);}
	if (get_option("EWD_FEUP_Levels_Payment_Array") == "") {update_option("EWD_FEUP_Levels_Payment_Array", array());}
	if (get_option("EWD_FEUP_Pricing_Currency_Code") == "") {update_option("EWD_FEUP_Pricing_Currency_Code", "AUD");}
	if (get_option("EWD_FEUP_Thank_You_URL") == "") {update_option("EWD_FEUP_Thank_You_URL", "");}
	if (get_option("EWD_FEUP_Discount_Codes_Array") == "") {update_option("EWD_FEUP_Discount_Codes_Array", array());}
	if (get_option("EWD_FEUP_Payment_Gateway") == "") {update_option("EWD_FEUP_Payment_Gateway", "PayPal");}
	if (get_option("EWD_FEUP_Stripe_Currency_Symbol_Placement") == "") {update_option("EWD_FEUP_Stripe_Currency_Symbol_Placement", "Before");}

	if (get_option("EWD_FEUP_WooCommerce_Integration") == "") {update_option("EWD_FEUP_WooCommerce_Integration", "No");}
	if (get_option("EWD_FEUP_WooCommerce_First_Name_Field") == "") {update_option("EWD_FEUP_WooCommerce_First_Name_Field", "First Name");}
	if (get_option("EWD_FEUP_WooCommerce_Last_Name_Field") == "") {update_option("EWD_FEUP_WooCommerce_Last_Name_Field", "Last Name");}
	if (get_option("EWD_FEUP_WooCommerce_Company_Field") == "") {update_option("EWD_FEUP_WooCommerce_Company_Field", "Company");}
	if (get_option("EWD_FEUP_WooCommerce_Address_Line_One_Field") == "") {update_option("EWD_FEUP_WooCommerce_Address_Line_One_Field", "Address Line One");}
	if (get_option("EWD_FEUP_WooCommerce_Address_Line_Two_Field") == "") {update_option("EWD_FEUP_WooCommerce_Address_Line_Two_Field", "Address Line Two");}
	if (get_option("EWD_FEUP_WooCommerce_City_Field") == "") {update_option("EWD_FEUP_WooCommerce_City_Field", "City");}
	if (get_option("EWD_FEUP_WooCommerce_Postcode_Field") == "") {update_option("EWD_FEUP_WooCommerce_Postcode_Field", "Postcode");}
	if (get_option("EWD_FEUP_WooCommerce_Country_Field") == "") {update_option("EWD_FEUP_WooCommerce_Country_Field", "Country");}
	if (get_option("EWD_FEUP_WooCommerce_State_Field") == "") {update_option("EWD_FEUP_WooCommerce_State_Field", "State");}
	if (get_option("EWD_FEUP_WooCommerce_Email_Field") == "") {update_option("EWD_FEUP_WooCommerce_Email_Field", "Email");}
	if (get_option("EWD_FEUP_WooCommerce_Phone_Field") == "") {update_option("EWD_FEUP_WooCommerce_Phone_Field", "Phone");}

	if (get_option("EWD_FEUP_Last_User_Return_Check") == "") {add_option("EWD_FEUP_Last_User_Return_Check", time());}

	if (get_option("EWD_FEUP_Install_Time") == "") {update_option("EWD_FEUP_Install_Time", time());}

	if (get_option("EWD_FEUP_Email_Messages_Array") == "") {
		$Email_Subject = get_option("EWD_FEUP_Email_Subject");
		$Message_Body = get_option("EWD_FEUP_Message_Body");

		$Registrant_Email = array(
			'ID' => 0,
			'Name' => 'Default Registration Email',
			'Subject' => $Email_Subject,
			'Message' => $Message_Body
		);
		$Emails_Array = EWD_FEUP_Create_Default_Email($Registrant_Email);

		update_option("EWD_FEUP_Email_Messages_Array", $Emails_Array);

		if (get_option("EWD_FEUP_Sign_Up_Email") == "Yes") {update_option("EWD_FEUP_Sign_Up_Email", 0);}
		else {update_option("EWD_FEUP_Sign_Up_Email", -1);}

		if (get_option("EWD_FEUP_Admin_Email_On_Registration") == "Yes") {update_option("EWD_FEUP_Admin_Email_On_Registration", 1);}
		else {update_option("EWD_FEUP_Admin_Email_On_Registration", -1);}

		if (get_option("EWD_FEUP_Email_On_Admin_Approval") == "Yes") {update_option("EWD_FEUP_Email_On_Admin_Approval", 2);}
		else {update_option("EWD_FEUP_Email_On_Admin_Approval", -1);}

		update_option("EWD_FEUP_Password_Reset_Email", 3);
	}

	if (get_option("EWD_FEUP_Mailchimp_Integration") == "") {add_option("EWD_FEUP_Mailchimp_Integration", "No");}

	update_option("EWD_FEUP_db_version", $EWD_FEUP_db_version);
}

function EWD_FEUP_Create_Default_Email($Registrant_Email) {
	$Admin_Approval = $Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$New_Registration_Email_to_Admin = get_option("EWD_FEUP_New_Registration_Email_to_Admin");

	if ($Registrant_Email != "") {$Emails = array($Registrant_Email);}
	else {$Emails = array();}

	$Admin_Email = array(
		'ID' => 1,
		'Name' => 'Default Admin Email',
		'Subject' => 'New FEUP User Registration'
	);

	if ($New_Registration_Email_to_Admin == "") {
		$Admin_Email['Message'] = "[section]Hello Administrator,[/section]";
		$Admin_Email['Message'] .= "[section]You have received a new registration on your site" . get_bloginfo('name') . "[/section]";
		$Admin_Email['Message'] .= "[section]The username for the new user is: [username] and the user has been assigned an ID of: [user-id][/section]";
		if ($Admin_Approval == "Yes") {$Admin_Email['Message'] .= "[section]Your site currently requires admin approval of users, therefore you will need to approve this user before they are able to log in.[/section]";}
		$Admin_Email['Message'] .= "[section]Thanks for using the Front-End Only Users plugin![/section]";
	}
	else {
		$Admin_Email['Message'] = $New_Registration_Email_to_Admin;
	}

	$Emails[] = $Admin_Email;

	$Admin_Approved_Email = array(
		'ID' => 2,
		'Name' => 'Default Approval Email',
		'Subject' => "You've been approved!",
		'Message' => $EWD_FEUP_Admin_Approval_Message_Body
	);

	$Emails[] = $Admin_Approved_Email;

	$Passowrd_Reset_Email = array(
		'ID' => 3,
		'Name' => 'Default Password Reset Email',
		'Subject' => 'Password reset requested from ' . get_bloginfo('name')
	);
	$Passowrd_Reset_Email['Message'] = "[section]Greetings from " . get_bloginfo('name') . ",[/section]";
	$Passowrd_Reset_Email['Message'] .= "[section]Somebody requested a password reset for you. If this wasn't you, you can ignore this mail.[/section]";
	$Passowrd_Reset_Email['Message'] .= "[section]If you want to reset the password, please visit: [reset-password-link].[/section]";
	$Passowrd_Reset_Email['Message'] .= "[section]If the link above doesn't work, go to the password reset confirmation page and enter your email address and the following code: [reset-code].[/section]";

	$Emails[] = $Passowrd_Reset_Email;

	return $Emails;
}
?>
