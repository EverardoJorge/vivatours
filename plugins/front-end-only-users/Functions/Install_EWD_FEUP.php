<?php
function Install_EWD_FEUP() {
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
 
   	if (get_option("EWD_FEUP_db_version") == "") {update_option("EWD_FEUP_db_version", $EWD_FEUP_db_version);}
   	if (get_option("EWD_FEUP_First_Install_Version") == "") {update_option("EWD_FEUP_First_Install_Version", "2.9");}
	if (get_option("EWD_FEUP_Login_Time") == "") {update_option("EWD_FEUP_Login_Time", "1440");}
	if (get_option("EWD_FEUP_Minimum_Password_Length") == "") {update_option("EWD_FEUP_Minimum_Password_Length", 6);}
	if (get_option("EWD_FEUP_Include_WP_Users") == "") {update_option("EWD_FEUP_Include_WP_Users", "No");}
	if (get_option("EWD_FEUP_Use_Captcha") == "") {update_option("EWD_FEUP_Use_Captcha", "No");}
	if (get_option("EWD_FEUP_Allow_Level_Choice") == "") {add_option("EWD_FEUP_Allow_Level_Choice", "No");}
	if (get_option("EWD_FEUP_Track_Events") == "") {update_option("EWD_FEUP_Track_Events", "No");}
	if (get_option("EWD_FEUP_Email_Confirmation") == "") {update_option("EWD_FEUP_Email_Confirmation", "No");}
	if (get_option("EWD_FEUP_Admin_Approval") == "") {update_option("EWD_FEUP_Admin_Approval", "No");}
	if (get_option("EWD_FEUP_Create_WordPress_Users") == "") {update_option("EWD_FEUP_Create_WordPress_Users", "No");}
	if (get_option("EWD_FEUP_Full_Version") == "") {update_option("EWD_FEUP_Full_Version", "No");}
	if (get_option("EWD_FEUP_Use_Crypt") == "") {update_option("EWD_FEUP_Use_Crypt", "Yes");}
	if (get_option("EWD_FEUP_Required_Field_Symbol") == "") {update_option("EWD_FEUP_Required_Field_Symbol", "*");}
	if (get_option("EWD_FEUP_Show_TinyMCE") == "") {update_option("EWD_FEUP_Show_TinyMCE", "Yes");}
	if (get_option("EWD_FEUP_Use_SMTP") == "") {update_option("EWD_FEUP_Use_SMTP", "Yes");}
	if (get_option("EWD_FEUP_Port") == "") {update_option("EWD_FEUP_Port", "25");}
	if (get_option("EWD_FEUP_Custom_CSS") == "") {update_option("EWD_FEUP_Custom_CSS", "");}

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

		$Registrant_Email = array(
			'ID' => 0,
			'Name' => 'Default Registration Email',
			'Subject' => 'Greetings from ' .get_bloginfo('name'),
			'Message' => '[section]Thanks for registering![/section][section]Your new username is [username], we look forward to you visiting the site![/section]'
		);
		$Emails_Array = EWD_FEUP_Create_Default_Email($Registrant_Email);

		update_option("EWD_FEUP_Email_Messages_Array", $Emails_Array);

		update_option("EWD_FEUP_Sign_Up_Email", -1);
		update_option("EWD_FEUP_Admin_Email_On_Registration", -1);
		update_option("EWD_FEUP_Email_On_Admin_Approval", -1);
		update_option("EWD_FEUP_Password_Reset_Email", 3);
	}

	if (get_option("EWD_FEUP_Mailchimp_Integration") == "") {add_option("EWD_FEUP_Mailchimp_Integration", "No");}
}
?>
