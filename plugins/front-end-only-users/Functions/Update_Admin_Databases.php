<?php
/* The file contains all of the functions which make changes to the FEUP tables */

/* Adds a single new user to the FEUP database */
function Add_EWD_FEUP_User($User_Data_Array) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	
	$wpdb->insert($ewd_feup_user_table_name, $User_Data_Array);
	$update = __("User has been successfully created.", 'front-end-only-users');
	return $update;
}

function EWD_FEUP_Add_WP_User($User_ID, $User_Fields) {
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name;

	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	
	define( 'WP_IMPORTING', 'SKIP_EMAIL_EXIST' );
	$wp_user_id = wp_create_user($User_Fields['Username'], $User_Fields['User_Password'], $User_Fields['Username']);

	add_user_meta($wp_user_id, "EWD FEUP ID", $User_ID);

	$wpdb->update($ewd_feup_user_table_name, array(
			'User_WP_ID' => $wp_user_id
		),
		array(
			'User_ID' => $User_ID,
		)
	);

	$user_data = array('ID' => $wp_user_id);
	$Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name");
	foreach ($Fields as $Field) {
		if ($Field->Field_Equivalent == "First_Name") {$user_data['first_name'] = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field->Field_ID, $User_ID));}
		elseif ($Field->Field_Equivalent == "Last_Name") {$user_data['last_name'] = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field->Field_ID, $User_ID));}
		elseif ($Username_Is_Email != "Yes" and $Field->Field_Equivalent == "Email") {$user_data['user_email'] = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field->Field_ID, $User_ID));}
	}
	if (sizeof($user_data) > 1) {wp_update_user($user_data);}

	return $wp_user_id;
}

/* Edits a single user with a given ID in the FEUP database */
function Edit_EWD_FEUP_User($User_ID, $User_Data_Array) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	
	$wpdb->update(
		$ewd_feup_user_table_name,
		$User_Data_Array,
		array( 'User_ID' => $User_ID)
	);
	$update = __("User has been successfully edited.", 'front-end-only-users');
	return $update;
}

/* Deletes a single user with a given ID in the FEUP database */
function Delete_EWD_FEUP_User($User_ID) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	global $ewd_feup_user_fields_table_name;
	
	$wpdb->delete(
		$ewd_feup_user_table_name,
		array('User_ID' => $User_ID)
	);
	$wpdb->delete(
		$ewd_feup_user_fields_table_name,
		array('User_ID' => $User_ID)
	);

	$update = __("User has been successfully deleted.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

if (!class_exists('ComposerAutoloaderInit4618f5c41cf5e27cc7908556f031e4d4')) {require_once EWD_FEUP_CD_PLUGIN_PATH . 'PHPSpreadsheet/vendor/autoload.php';}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
function Add_FEUP_Users_From_Spreadsheet($Excel_File_Name) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	global $ewd_feup_user_fields_table_name;
	global $ewd_feup_levels_table_name;
	global $ewd_feup_fields_table_name;
	global $EWD_FEUP_Full_Version;

	$Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	$Create_WordPress_Users = get_option("EWD_FEUP_Create_WordPress_Users");

	if (!wp_verify_nonce($_POST['_wpnonce'])) {return __("There has been a validation error.", 'front-end-only-users');}
		
	$Excel_URL = '../wp-content/plugins/front-end-only-users/user-sheets/' . $Excel_File_Name;
		
	// Build the workbook object out of the uploaded spredsheet
	$objWorkBook = \PhpOffice\PhpSpreadsheet\IOFactory::load($Excel_URL);
		
	// Create a worksheet object out of the product sheet in the workbook
	$sheet = $objWorkBook->getActiveSheet();
		
	//List of fields that can be accepted via upload
	$Allowed_Fields = array ("Username" => "Username", "Password" => "User_Password", "Level" => "Level_Name", "Email Confirmed" => "User_Email_Confirmed", "Admin Approved" => "User_Admin_Approved");
	$Custom_Fields_From_DB = $wpdb->get_results("SELECT Field_ID, Field_Name, Field_Options, Field_Type FROM $ewd_feup_fields_table_name");
	if (is_array($Custom_Fields_From_DB)) {
		foreach ($Custom_Fields_From_DB as $Custom_Field_From_DB) {
			$Allowable_Custom_Fields[$Custom_Field_From_DB->Field_Name] = $Custom_Field_From_DB->Field_Name;
			$Field_IDs[$Custom_Field_From_DB->Field_Name] = $Custom_Field_From_DB->Field_ID;
		}
	}
		
	// Get column names
	$highestColumn = $sheet->getHighestColumn();
	$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
	for ($column = 1; $column <= $highestColumnIndex; $column++) {
		$Titles[$column] = trim($sheet->getCellByColumnAndRow($column, 1)->getValue());
	}

	// Make sure all columns are acceptable based on the acceptable fields above
	foreach ($Titles as $key => $Title) {
		if ($Title != "" and !array_key_exists($Title, $Allowed_Fields) and !array_key_exists($Title, $Allowable_Custom_Fields)) {
			$Error = __("You have a column which is not recognized: ", 'front-end-only-users') . $Title . __(". <br>Please make sure that the column names match the user field labels exactly.", 'front-end-only-users');
			$user_update = array("Message_Type" => "Error", "Message" => $Error);
			return $user_update;
		}
		if ($Title == "") {
			$Error = __("You have a blank column that has been edited.<br>Please delete that column and re-upload your spreadsheet.", 'front-end-only-users');
			$user_update = array("Message_Type" => "Error", "Message" => $Error);
			return $user_update;
		}
		if (is_array($Allowable_Custom_Fields)) {
			if (array_key_exists($Title, $Allowable_Custom_Fields)) {
				$Custom_Fields[$key] = $Title;
				unset($Titles[$key]);
			}
		}
	}
	if (!is_array($Custom_Fields)) {$Custom_Fields = array();}
		
	// Put the spreadsheet data into a multi-dimensional array to facilitate processing
	$highestRow = $sheet->getHighestRow();
	for ($row = 2; $row <= $highestRow; $row++) {
		for ($column = 1; $column <= $highestColumnIndex; $column++) {
			$Data[$row][$column] = $sheet->getCellByColumnAndRow($column, $row)->getValue();
		}
	}

	// Create an array of the levels currently in the FEUP database, 
	// with Level_Name as the key and Level_ID as the value
	$Levels_From_DB = $wpdb->get_results("SELECT * FROM $ewd_feup_levels_table_name");
	foreach ($Levels_From_DB as $Level) {
		$Levels[$Level->Level_Name] = $Level->Level_ID;
	}

	// Creates an array of the field names which are going to be inserted into the database
	// and then turns that array into a string so that it can be used in the query
	for ($column = 1; $column <= $highestColumnIndex; $column++) {
		if ($Allowed_Fields[$Titles[$column]] != "Level_Name" and !array_key_exists($column, $Custom_Fields)) {$Fields[] = $Allowed_Fields[$Titles[$column]];}
		if ($Allowed_Fields[$Titles[$column]] == "Level_Name") {$Level_Column = $column; $Fields[] = "Level_ID";}
		if ($Allowed_Fields[$Titles[$column]] == "User_Password") {$Password_Column = $column;}
		if ($Allowed_Fields[$Titles[$column]] == "Username") {$Username_Column = $column;}
	}
	$FieldsString = implode(",", $Fields);
		
	$ShowStatus = "Show";
	$Today = date("Y-m-d H:i:s"); 
	$wpdb->show_errors();

	// Create the query to insert the users one at a time into the database and then run it
	foreach ($Data as $User) {
				
		// Create an array of the values that are being inserted for each user
		foreach ($User as $Col_Index => $Value) {
			if ((!isset($Password_Column) or $Password_Column != $Col_Index) and (!isset($Level_Column) or $Level_Column != $Col_Index) and !array_key_exists($Col_Index, $Custom_Fields)) {$Values[] = esc_sql($Value);}
			if (isset($Level_Column) and $Level_Column == $Col_Index) {
				$Values[] = $Levels[$Value];
			}
			if (isset($Password_Column) and $Password_Column == $Col_Index) {
				if($Use_Crypt == "Yes") {
					$Values[] = Generate_Password($Value);
				} else {
					$Values[] = sha1(md5($Value.$Salt));
				}
				$Password = $Value;
			}
			if (isset($Username_Column) and $Username_Column == $Col_Index) {
				$Username = $Value;
			}
			if (array_key_exists($Col_Index, $Custom_Fields)) {
				$Custom_Fields_To_Insert[$Custom_Fields[$Col_Index]] = $Value;
			}
		}
				
		$ValuesString = implode("','", $Values);
		$wpdb->query(
			$wpdb->prepare("INSERT INTO $ewd_feup_user_table_name (" . $FieldsString . ", User_Date_Created) VALUES ('" . $ValuesString . "','%s')", $Today)
		);

		$User_ID = $wpdb->insert_id;
		if ($Sign_Up_Email == "Yes") {EWD_FEUP_Send_Email(array(), array(), $User_ID);}

		if ($Create_WordPress_Users == "Yes" and $Username_Is_Email == "Yes") {$WP_ID = EWD_FEUP_Add_WP_User($User_ID, array('Username' => $Username, 'Password' => $Password));}
				
		if (is_array($Custom_Fields_To_Insert)) {
			foreach ($Custom_Fields_To_Insert as $Field => $Value) {
				$Trimmed_Field = trim($Field);
				$Field_ID = $Field_IDs[$Trimmed_Field];
				$wpdb->query($wpdb->prepare("INSERT INTO $ewd_feup_user_fields_table_name (Field_ID, User_ID, Field_Name, Field_Value, User_Field_Date_Created) VALUES (%d, %d, %s, %s, %s)", $Field_ID, $User_ID, $Trimmed_Field, $Value, $Today));
			}
		}

		unset($Values);
		unset($User_ID);
		unset($ValuesString);
		unset($Custom_Fields_To_Insert);
	}
	
	$message = __("Users added successfully.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $message);
	return $user_update;
}

function Add_EWD_FEUP_Field($Field_Name, $Field_Slug, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required, $Field_Date_Created, $Field_Equivalent = "") {
	global $wpdb;
	global $ewd_feup_fields_table_name;
		
	$wpdb->insert($ewd_feup_fields_table_name, 
		array( 'Field_Name' => $Field_Name,
			'Field_Slug' => $Field_Slug,
			'Field_Type' => $Field_Type,
			'Field_Description' => $Field_Description,
			'Field_Options' => $Field_Options,
			'Field_Show_In_Admin' => $Field_Show_In_Admin,
			'Field_Show_In_Front_End' => $Field_Show_In_Front_End,
			'Field_Required' => $Field_Required,
			'Field_Date_Created' => $Field_Date_Created, 
			'Field_Equivalent' => $Field_Equivalent)
	);							
		
	$update = __("Field has been successfully created.", 'front-end-only-users');
	return $update;
}

function Edit_EWD_FEUP_Field($Field_ID, $Field_Name, $Field_Slug, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required, $Field_Equivalent = "") {
	global $wpdb;
	global $ewd_feup_fields_table_name;
		
	$wpdb->update($ewd_feup_fields_table_name, 
		array( 'Field_Name' => $Field_Name,
			'Field_Slug' => $Field_Slug,
			'Field_Type' => $Field_Type,
			'Field_Description' => $Field_Description,
			'Field_Options' => $Field_Options,
			'Field_Show_In_Admin' => $Field_Show_In_Admin,
			'Field_Show_In_Front_End' => $Field_Show_In_Front_End,
			'Field_Required' => $Field_Required,
			'Field_Equivalent' => $Field_Equivalent),
		array( 'Field_ID' => $Field_ID)
	);
		
	$update = __("Field has been successfully edited.", 'front-end-only-users');
	return $update;
}

function Delete_EWD_FEUP_Field($Field_ID) {
		global $wpdb;
		global $ewd_feup_fields_table_name;
		
		$wpdb->delete(
						$ewd_feup_fields_table_name,
						array('Field_ID' => $Field_ID)
					);
		
		$update = __("Field has been successfully deleted.", 'front-end-only-users');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

function Add_EWD_FEUP_User_Field($Field_ID, $User_ID, $Field_Name, $Field_Value, $date) {
	global $wpdb;
	global $ewd_feup_user_fields_table_name;
	
	$wpdb->insert($ewd_feup_user_fields_table_name, 
			array( 'Field_ID' => $Field_ID,
					'User_ID' => $User_ID,
					'Field_Name' => $Field_Name,
					'Field_Value' => $Field_Value,
					'User_Field_Date_Created' => $date)
			);
		
	$update = __("Field has been successfully created.", 'front-end-only-users');
	return $update;
}

function Edit_EWD_FEUP_User_Field($Field_ID, $User_ID, $Field_Name, $Field_Value) {
	global $wpdb;
	global $ewd_feup_user_fields_table_name;
	
	$User_Field_ID = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID ='%d' AND User_ID='%d'", $Field_ID, $User_ID));
	
	$wpdb->update($ewd_feup_user_fields_table_name, 
			array( 'Field_Name' => $Field_Name,
					'Field_Value' => $Field_Value),
			array( 'User_Field_ID' => $User_Field_ID->User_Field_ID)
			);
	
	$update = __("Field has been successfully edited.", 'front-end-only-users');
	return $update;
}

function Delete_EWD_FEUP_User_Field($User_Field_ID) {
	global $wpdb;
	global $ewd_feup_user_fields_table_name;
	
	$wpdb->delete(
			$ewd_feup_user_fields_table_name,
			array('User_Field_ID' => $User_Field_ID)
		);
				
	$update = __("Field has been successfully deleted.", 'front-end-only-users');
	return $update;
}

function Add_EWD_FEUP_Payment($User_ID, $Username, $Payer_ID, $PayPal_Receipt_Number, $Payment_Date, $Next_Payment_Date, $Payment_Amount, $Discount_Code_Used) {
	global $wpdb;
	global $ewd_feup_payments_table_name;
	
	$wpdb->insert($ewd_feup_payments_table_name, 
			array( 'User_ID' => $User_ID,
					'Username' => $Username,
					'Payer_ID' => $Payer_ID,
					'PayPal_Receipt_Number' => $PayPal_Receipt_Number,
					'Payment_Date' => $Payment_Date,
					'Next_Payment_Date' => $Next_Payment_Date,
					'Payment_Amount' => $Payment_Amount,
					'Discount_Code_Used' => $Discount_Code_Used)
			);
		
	$update = __("Payment has been successfully created.", 'front-end-only-users');
	return $update;
}

function Edit_EWD_FEUP_Payment($Payment_ID, $User_ID, $Username, $Payer_ID, $PayPal_Receipt_Number, $Payment_Date, $Next_Payment_Date, $Payment_Amount, $Discount_Code_Used) {
	global $wpdb;
	global $ewd_feup_payments_table_name;
	
	$wpdb->update($ewd_feup_payments_table_name, 
			array( 'User_ID' => $User_ID,
					'Username' => $Username,
					'Payer_ID' => $Payer_ID,
					'PayPal_Receipt_Number' => $PayPal_Receipt_Number,
					'Payment_Date' => $Payment_Date,
					'Next_Payment_Date' => $Next_Payment_Date,
					'Payment_Amount' => $Payment_Amount,
					'Discount_Code_Used' => $Discount_Code_Used),
			array( 'Payment_ID' => $Payment_ID)
			);
	
	$update = __("Payment has been successfully edited.", 'front-end-only-users');
	return $update;
}

function Delete_EWD_FEUP_Payment($Payment_ID) {
	global $wpdb;
	global $ewd_feup_payments_table_name;
	
	$wpdb->delete(
			$ewd_feup_payments_table_name,
			array('Payment_ID' => $Payment_ID)
		);
					
	$update = __("Payment has been successfully deleted.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $update;
}

function Add_EWD_FEUP_Level($Level_Name, $Level_Privilege, $Level_Date_Created) {
	global $wpdb;
	global $ewd_feup_levels_table_name;
		
	$wpdb->insert($ewd_feup_levels_table_name, 
				array( 'Level_Name' => $Level_Name,
						'Level_Privilege' => $Level_Privilege,
						'Level_Date_Created' => $Level_Date_Created)
			);
		
	$update = __("Level has been successfully created.", 'front-end-only-users');
	return $update;
}

/* Edits a single category with a given ID in the UPCP database */
function Edit_EWD_FEUP_Level($Level_ID, $Level_Name, $Level_Privilege, $Level_Date_Created) {
	global $wpdb;
	global $ewd_feup_levels_table_name;

	$wpdb->update($ewd_feup_levels_table_name, 
				array( 'Level_Name' => $Level_Name,
						'Level_Privilege' => $Level_Privilege,
						'Level_Date_Created' => $Level_Date_Created),
						array( 'Level_ID' => $Level_ID)
		);
	$update = __("Level has been successfully edited.", 'front-end-only-users');
	return $update;
}

/* Deletes a single category with a given ID in the UPCP database */
function Delete_EWD_FEUP_Level($Level_ID) {
	global $wpdb;
	global $ewd_feup_levels_table_name;
		
	$wpdb->delete(
				$ewd_feup_levels_table_name,
				array('Level_ID' => $Level_ID)
			);

	$update = __("Level has been successfully deleted.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function EWD_FEUP_Update_Field_Excludes($Level_ID, $Level_Include_Fields) {
	global $wpdb;
	global $ewd_feup_fields_table_name;

	$Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name");
	foreach ($Fields as $Field) {
		if (!in_array($Field->Field_ID, $Level_Include_Fields)) {
			$Field_Level_Exclude_IDs = unserialize($Field->Level_Exclude_IDs);
			$Field_Level_Exclude_IDs[] = $Level_ID;
			$Field_Level_Exclude_IDs = array_unique($Field_Level_Exclude_IDs);
			$Exclude_String = serialize($Field_Level_Exclude_IDs);
			$wpdb->query("UPDATE $ewd_feup_fields_table_name SET Level_Exclude_IDs='" . $Exclude_String . "' WHERE Field_ID='" . $Field->Field_ID . "'");
		}
		else {
			$Field_Level_Exclude_IDs = unserialize($Field->Level_Exclude_IDs);
			if(is_array($Field_Level_Exclude_IDs) and ($key = array_search($Level_ID, $Field_Level_Exclude_IDs)) !== false) {unset($Field_Level_Exclude_IDs[$key]);}
			$Exclude_String = serialize($Field_Level_Exclude_IDs);
			$wpdb->query("UPDATE $ewd_feup_fields_table_name SET Level_Exclude_IDs='" . $Exclude_String . "' WHERE Field_ID='" . $Field->Field_ID . "'");
		}
	}
}

function Add_User_Event($User_ID, $Event_Type, $Event_Location, $Event_Location_ID, $Event_Location_Title, $Event_Value = null, $Event_Target_ID = 0, $Event_Target_Title = null) {
	global $wpdb;
	global $ewd_feup_user_events_table_name;

	$Event_Date = date("Y-m-d H:i:s");
		$wpdb->show_errors();
	$wpdb->insert($ewd_feup_user_events_table_name, 
				array( 'User_ID' => $User_ID,
						'Event_Type' => $Event_Type,
						'Event_Location' => $Event_Location,
						'Event_Location_ID' => $Event_Location_ID,
						'Event_Location_Title' => $Event_Location_Title,
						'Event_Value' => $Event_Value,
						'Event_Target_ID' => $Event_Target_ID,
						'Event_Target_Title' => $Event_Target_Title,
						'Event_Date' => $Event_Date)
			);
	return $wpdb->last_query;
	$update = __("Event has been successfully created.", 'front-end-only-users');
	return $update;
}

function EWD_FEUP_Add_Page($Title, $Page_Content) {
	$page = array(
				'post_title' => $Title,
				'post_status' => 'publish',
				'post_type' => 'page',
				'post_content' => $Page_Content
	);
	
	$Page_ID = wp_insert_post($page);
	return $Page_ID;
}

function Update_EWD_FEUP_Options() {
	global $EWD_FEUP_Full_Version;

	if ( ! isset( $_POST['EWD_FEUP_Admin_Nonce'] ) ) {return;}

    if ( ! wp_verify_nonce( $_POST['EWD_FEUP_Admin_Nonce'], 'EWD_FEUP_Admin_Nonce' ) ) {return;}

	$First_Install_Version = floatval(get_option("EWD_FEUP_First_Install_Version"));
	
	//Import all WP users if the option is toggled
	if (get_option("EWD_FEUP_Include_WP_Users") == "No" and (isset($_POST['include_wp_users']) and $_POST['include_wp_users'] == "Yes")){EWD_FEUP_Import_WP_Users();}
	if (!isset($_POST['login_options'])) { $_POST['login_options'] = "";}
	// Set options that have been sent
	if (isset($_POST['login_time'])) {update_option('EWD_FEUP_Login_Time', $_POST['login_time']);}
	if (isset($_POST['minimum_password_length'])) {update_option('EWD_FEUP_Minimum_Password_Length', $_POST['minimum_password_length']);}
	if (isset($_POST['include_wp_users'])) {update_option('EWD_FEUP_Include_WP_Users', $_POST['include_wp_users']);}
	if (isset($_POST['sign_up_email'])) {update_option("EWD_FEUP_Sign_Up_Email", $_POST['sign_up_email']);}
	if (isset($_POST['custom_css'])) {update_option("EWD_FEUP_Custom_CSS", $_POST['custom_css']);}
	if (isset($_POST['use_crypt'])) {update_option("EWD_FEUP_Use_Crypt", $_POST['use_crypt']);}
	if (isset($_POST['username_is_email'])) {update_option("EWD_FEUP_Username_Is_Email", $_POST['username_is_email']);}
	if (isset($_POST['required_field_symbol'])) {update_option("EWD_FEUP_Required_Field_Symbol", $_POST['required_field_symbol']);}
	if (isset($_POST['show_tinymce'])) {update_option('EWD_FEUP_Show_TinyMCE', stripslashes_deep($_POST['show_tinymce']));}

	if (isset($_POST['use_captcha']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Use_Captcha", $_POST['use_captcha']);}
	if (isset($_POST['allow_level_choice']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Allow_Level_Choice", $_POST['allow_level_choice']);}
	if (isset($_POST['track_events']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Track_Events", $_POST['track_events']);}
	if (isset($_POST['email_confirmation']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Email_Confirmation", $_POST['email_confirmation']);}
	if (isset($_POST['email_confirmation_redirect']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Email_Confirmation_Redirect", $_POST['email_confirmation_redirect']);}
	if (isset($_POST['admin_approval']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Admin_Approval", $_POST['admin_approval']);}
	if (isset($_POST['email_on_admin_approval']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Email_On_Admin_Approval", $_POST['email_on_admin_approval']);}
	if (isset($_POST['admin_email_on_registration']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Admin_Email_On_Registration", $_POST['admin_email_on_registration']);}
	if (isset($_POST['default_user_level']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_Default_User_Level", $_POST['default_user_level']);}
	if (isset($_POST['create_wordpress_users']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Create_WordPress_Users", $_POST['create_wordpress_users']);}
	if (isset($_POST['Options_Submit']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Login_Options', stripslashes_deep($_POST['login_options']));}

	if (isset($_POST['facebook_app_id']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Facebook_App_ID', stripslashes_deep($_POST['facebook_app_id']));}
	if (isset($_POST['facebook_secret']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Facebook_Secret', stripslashes_deep($_POST['facebook_secret']));}
	if (isset($_POST['twitter_key']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Twitter_Key', stripslashes_deep($_POST['twitter_key']));}
	if (isset($_POST['twitter_secret']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Twitter_Secret', stripslashes_deep($_POST['twitter_secret']));}

	if (isset($_POST['payment_frequency']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Payment_Frequency", $_POST['payment_frequency']);}
	if (isset($_POST['payment_types']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Payment_Types", $_POST['payment_types']);}
	if (isset($_POST['membership_cost']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Membership_Cost", $_POST['membership_cost']);}
	if (isset($_POST['free_trial_days']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Free_Trial_Days", $_POST['free_trial_days']);}
	if (isset($_POST['thank_you_url']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Thank_You_URL", $_POST['thank_you_url']);}
	if (isset($_POST['pricing_currency_code']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Pricing_Currency_Code", $_POST['pricing_currency_code']);}
	if (isset($_POST['payment_gateway']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Payment_Gateway", $_POST['payment_gateway']);}
	if (isset($_POST['paypal_email_address']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_PayPal_Email_Address", $_POST['paypal_email_address']);}
	if (isset($_POST['stripe_currency_symbol']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Stripe_Currency_Symbol", $_POST['stripe_currency_symbol']);}
	if (isset($_POST['stripe_currency_symbol_placement']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Stripe_Currency_Symbol_Placement", $_POST['stripe_currency_symbol_placement']);}
	if (isset($_POST['stripe_live_secret']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Stripe_Live_Secret", $_POST['stripe_live_secret']);}
	if (isset($_POST['stripe_live_publishable']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Stripe_Live_Publishable", $_POST['stripe_live_publishable']);}
	if (isset($_POST['stripe_plan_id']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_Stripe_Plan_ID", $_POST['stripe_plan_id']);}
	
	if (isset($_POST['woocommerce_integration']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Integration", $_POST['woocommerce_integration']);}
	if (isset($_POST['first_name_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_First_Name_Field", $_POST['first_name_field']);}
	if (isset($_POST['last_name_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Last_Name_Field", $_POST['last_name_field']);}
	if (isset($_POST['company_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Company_Field", $_POST['company_field']);}
	if (isset($_POST['address_line_one_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Address_Line_One_Field", $_POST['address_line_one_field']);}
	if (isset($_POST['address_line_two_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Address_Line_Two_Field", $_POST['address_line_two_field']);}
	if (isset($_POST['city_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_City_Field", $_POST['city_field']);}
	if (isset($_POST['postcode_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Postcode_Field", $_POST['postcode_field']);}
	if (isset($_POST['country_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Country_Field", $_POST['country_field']);}
	if (isset($_POST['state_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_State_Field", $_POST['state_field']);}
	if (isset($_POST['email_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Email_Field", $_POST['email_field']);}
	if (isset($_POST['phone_field']) and $EWD_FEUP_Full_Version == "Yes") {update_option("EWD_FEUP_WooCommerce_Phone_Field", $_POST['phone_field']);}
	
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_login'])) {update_option("EWD_FEUP_Label_Login", $_POST['feup_label_login']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_logout'])) {update_option("EWD_FEUP_Label_Logout", $_POST['feup_label_logout']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_username'])) {update_option("EWD_FEUP_Label_Username", $_POST['feup_label_username']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_username_placeholder'])) {update_option("EWD_FEUP_Label_Username_Placeholder", $_POST['feup_label_username_placeholder']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_register'])) {update_option("EWD_FEUP_Label_Register", $_POST['feup_label_register']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_successful_logout_message'])) {update_option("EWD_FEUP_Label_Successful_Logout_Message", $_POST['feup_label_successful_logout_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_restricted_message'])) {update_option("EWD_FEUP_Label_Require_Login_Message", $_POST['feup_label_restricted_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_image_number'])) {update_option("EWD_FEUP_Label_Image_Number", $_POST['feup_label_image_number']);}
	
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_upgrade_account'])) {update_option("EWD_FEUP_Label_Upgrade_Account", $_POST['feup_label_upgrade_account']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_upgrade_account'])) {update_option("EWD_FEUP_Label_Update_Account", $_POST['feup_label_update_account']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_upgrade_level_message'])) {update_option("EWD_FEUP_Label_Upgrade_Level_Message", $_POST['feup_label_upgrade_level_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_level'])) {update_option("EWD_FEUP_Label_Level", $_POST['feup_label_level']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_next'])) {update_option("EWD_FEUP_Label_Next", $_POST['feup_label_next']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_discount_message'])) {update_option("EWD_FEUP_Label_Discount_Message", $_POST['feup_label_discount_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_stripe_submit_payment_text'])) {update_option("EWD_FEUP_Label_Stripe_Submit_Payment_Text", $_POST['feup_label_stripe_submit_payment_text']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_discount_code'])) {update_option("EWD_FEUP_Label_Discount_Code", $_POST['feup_label_discount_code']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_use_discount_code'])) {update_option("EWD_FEUP_Label_Use_Discount_Code", $_POST['feup_label_use_discount_code']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_edit_profile'])) {update_option("EWD_FEUP_Label_Edit_Profile", $_POST['feup_label_edit_profile']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_current_file'])) {update_option("EWD_FEUP_Label_Current_File", $_POST['feup_label_current_file']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_current_picture'])) {update_option("EWD_FEUP_Label_Current_Picture", $_POST['feup_label_current_picture']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_update_picture'])) {update_option("EWD_FEUP_Label_Update_Picture", $_POST['feup_label_update_picture']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_confirm_email_message'])) {update_option("EWD_FEUP_Label_Confirm_Email_Message", $_POST['feup_label_confirm_email_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_incorrect_confirm_message'])) {update_option("EWD_FEUP_Label_Incorrect_Confirm_Message", $_POST['feup_label_incorrect_confirm_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_captcha_fail'])) {update_option("EWD_FEUP_Label_Captcha_Fail", $_POST['feup_label_captcha_fail']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_login_successful'])) {update_option("EWD_FEUP_Label_Login_Successful", $_POST['feup_label_login_successful']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_login_failed_confirm_email'])) {update_option("EWD_FEUP_Label_Login_Failed_Confirm_Email", $_POST['feup_label_login_failed_confirm_email']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_select_valid_profile'])) {update_option("EWD_FEUP_Label_Select_Valid_Profile", $_POST['feup_label_select_valid_profile']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_nonlogged_message'])) {update_option("EWD_FEUP_Label_Nonlogged_Message", $_POST['feup_label_nonlogged_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_low_account_level_message'])) {update_option("EWD_FEUP_Label_Low_Account_Level_Message", $_POST['feup_label_low_account_level_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_high_account_level_message'])) {update_option("EWD_FEUP_Label_High_Account_Level_Message", $_POST['feup_label_high_account_level_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_wrong_account_level_message'])) {update_option("EWD_FEUP_Label_Wrong_Account_Level_Message", $_POST['feup_label_wrong_account_level_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_restrict_access_message'])) {update_option("EWD_FEUP_Label_Restrict_Access_Message", $_POST['feup_label_restrict_access_message']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_login_failed_admin_approval'])) {update_option("EWD_FEUP_Label_Login_Failed_Admin_Approval", $_POST['feup_label_login_failed_admin_approval']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_login_failed_payment_required'])) {update_option("EWD_FEUP_Label_Login_Failed_Payment_Required", $_POST['feup_label_login_failed_payment_required']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_login_failed_incorrect_credentials'])) {update_option("EWD_FEUP_Label_Login_Failed_Incorrect_Credentials", $_POST['feup_label_login_failed_incorrect_credentials']);}

	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_please'])) {update_option("EWD_FEUP_Label_Please", $_POST['feup_label_please']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_to_continue'])) {update_option("EWD_FEUP_Label_To_Continue", $_POST['feup_label_to_continue']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_password'])) {update_option("EWD_FEUP_Label_Password", $_POST['feup_label_password']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_repeat_password'])) {update_option("EWD_FEUP_Label_Repeat_Password", $_POST['feup_label_repeat_password']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_password_strength'])) {update_option("EWD_FEUP_Label_Password_Strength", $_POST['feup_label_password_strength']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_reset_password'])) {update_option("EWD_FEUP_Label_Reset_Password", $_POST['feup_label_reset_password']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_email'])) {update_option("EWD_FEUP_Label_Email", $_POST['feup_label_email']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_reset_code'])) {update_option("EWD_FEUP_Label_Reset_Code", $_POST['feup_label_reset_code']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_change_password'])) {update_option("EWD_FEUP_Label_Change_Password", $_POST['feup_label_change_password']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_too_short'])) {update_option("EWD_FEUP_Label_Too_Short", $_POST['feup_label_too_short']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_mismatch'])) {update_option("EWD_FEUP_Label_Mismatch", $_POST['feup_label_mismatch']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_weak'])) {update_option("EWD_FEUP_Label_Weak", $_POST['feup_label_weak']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_good'])) {update_option("EWD_FEUP_Label_Good", $_POST['feup_label_good']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_label_strong'])) {update_option("EWD_FEUP_Label_Strong", $_POST['feup_label_strong']);}

	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_form_font'])) {update_option("EWD_FEUP_Styling_Form_Font", $_POST['feup_styling_form_font']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_form_font_size'])) {update_option("EWD_FEUP_Styling_Form_Font_Size", $_POST['feup_styling_form_font_size']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_form_font_weight'])) {update_option("EWD_FEUP_Styling_Form_Font_Weight", $_POST['feup_styling_form_font_weight']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_form_font_color'])) {update_option("EWD_FEUP_Styling_Form_Font_Color", $_POST['feup_styling_form_font_color']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_form_margin'])) {update_option("EWD_FEUP_Styling_Form_Margin", $_POST['feup_styling_form_margin']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_form_padding'])) {update_option("EWD_FEUP_Styling_Form_Padding", $_POST['feup_styling_form_padding']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_submit_bg_color'])) {update_option("EWD_FEUP_Styling_Submit_Bg_Color", $_POST['feup_styling_submit_bg_color']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_submit_font'])) {update_option("EWD_FEUP_Styling_Submit_Font", $_POST['feup_styling_submit_font']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_submit_font_color'])) {update_option("EWD_FEUP_Styling_Submit_Font_Color", $_POST['feup_styling_submit_font_color']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_submit_margin'])) {update_option("EWD_FEUP_Styling_Submit_Margin", $_POST['feup_styling_submit_margin']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_submit_padding'])) {update_option("EWD_FEUP_Styling_Submit_Padding", $_POST['feup_styling_submit_padding']);}
	
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userlistings_font'])) {update_option("EWD_FEUP_Styling_Userlistings_Font", $_POST['feup_styling_userlistings_font']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userlistings_font_size'])) {update_option("EWD_FEUP_Styling_Userlistings_Font_Size", $_POST['feup_styling_userlistings_font_size']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userlistings_font_weight'])) {update_option("EWD_FEUP_Styling_Userlistings_Font_Weight", $_POST['feup_styling_userlistings_font_weight']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userlistings_font_color'])) {update_option("EWD_FEUP_Styling_Userlistings_Font_Color", $_POST['feup_styling_userlistings_font_color']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userlistings_margin'])) {update_option("EWD_FEUP_Styling_Userlistings_Margin", $_POST['feup_styling_userlistings_margin']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userlistings_padding'])) {update_option("EWD_FEUP_Styling_Userlistings_Padding", $_POST['feup_styling_userlistings_padding']);}

	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userprofile_label_font'])) {update_option("EWD_FEUP_Styling_Userprofile_Label_Font", $_POST['feup_styling_userprofile_label_font']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userprofile_label_font_size'])) {update_option("EWD_FEUP_Styling_Userprofile_Label_Font_Size", $_POST['feup_styling_userprofile_label_font_size']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userprofile_label_font_weight'])) {update_option("EWD_FEUP_Styling_Userprofile_Label_Font_Weight", $_POST['feup_styling_userprofile_label_font_weight']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userprofile_label_font_color'])) {update_option("EWD_FEUP_Styling_Userprofile_Label_Font_Color", $_POST['feup_styling_userprofile_label_font_color']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userprofile_content_font'])) {update_option("EWD_FEUP_Styling_Userprofile_Content_Font", $_POST['feup_styling_userprofile_content_font']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userprofile_content_font_size'])) {update_option("EWD_FEUP_Styling_Userprofile_Content_Font_Size", $_POST['feup_styling_userprofile_content_font_size']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userprofile_content_font_weight'])) {update_option("EWD_FEUP_Styling_Userprofile_Content_Font_Weight", $_POST['feup_styling_userprofile_content_font_weight']);}
	if (($EWD_FEUP_Full_Version == "Yes" or $First_Install_Version <= 2.6) and isset($_POST['feup_styling_userprofile_content_font_color'])) {update_option("EWD_FEUP_Styling_Userprofile_Content_Font_Color", $_POST['feup_styling_userprofile_content_font_color']);}
	
	//Saving level payments

	$Counter = 0;
	while ($Counter < 30) {
		if (isset($_POST['Level_Payment_' . $Counter . '_Level'])) {
			$Prefix = 'Level_Payment_' . $Counter;
			if (!isset($_POST[$Prefix.'_Cumulative' ])) { $_POST[$Prefix. '_Cumulative'] = ""; }
			$Level_Payment['Level'] = $_POST[$Prefix . '_Level'];
			$Level_Payment['Amount'] = $_POST[$Prefix . '_Amount'];
			$Level_Payment['Cumulative'] = $_POST[$Prefix . '_Cumulative'];

			$Level_Payments[] = $Level_Payment; 
			unset($Level_Payment);
		}
		$Counter++;
	}

	if (isset($Level_Payments) and is_array($Level_Payments)) {usort($Level_Payments, 'EWD_FEUP_Level_Payments_Sort');}
	if (isset($_POST['pricing_currency_code'])) {update_option("EWD_FEUP_Levels_Payment_Array", $Level_Payments);}

	//Saving level payments
	$Counter = 0;
	while ($Counter < 30) {
		if (isset($_POST['Discount_Code_' . $Counter . '_Code'])) {
			$Prefix = 'Discount_Code_' . $Counter;
		
			$Discount_Code['Code'] = $_POST[$Prefix . '_Code'];
			$Discount_Code['Amount'] = $_POST[$Prefix . '_Amount'];
			$Discount_Code['Recurring'] = $_POST[$Prefix . '_Recurring'];
			$Discount_Code['Applicable'] = $_POST[$Prefix . '_Applicable'];
			$Discount_Code['Expiry'] = $_POST[$Prefix . '_Expiry'];

			$Discount_Codes[] = $Discount_Code; 
			unset($Discount_Code);
		}
		$Counter++;
	}
	if (!isset($Discount_Codes)) { $Discount_Codes= "";}
	if (is_array($Discount_Codes)) {usort($Discount_Codes, 'EWD_FEUP_Discount_Codes_Sort');}
	if (isset($_POST['pricing_currency_code'])) {update_option("EWD_FEUP_Discount_Codes_Array", $Discount_Codes);}

	$update = __("Options have been succesfully updated.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function EWD_FEUP_Level_Payments_Sort($a, $b) {
    return $a['Level'] - $b['Level'];
}

function EWD_FEUP_Discount_Codes_Sort($a, $b) {
    return $b['Expiry'] - $a['Expiry'];
}

function Update_EWD_FEUP_Email_Settings() {
	global $EWD_FEUP_Full_Version;

	if ( ! isset( $_POST['EWD_FEUP_Admin_Nonce'] ) ) {return;}

    if ( ! wp_verify_nonce( $_POST['EWD_FEUP_Admin_Nonce'], 'EWD_FEUP_Admin_Nonce' ) ) {return;}

	$Admin_Email = isset($_POST['admin_email'])?$_POST['admin_email']:"";
	$Email_Field = isset($_POST['email_field'])?$_POST['email_field']:"";
	$Password_Reset_Email = isset($_POST['password_reset_email'])?$_POST['password_reset_email']:"";
	
	$Admin_Email = stripslashes_deep($Admin_Email);
	$Email_Field = stripslashes_deep($Email_Field);
	$Password_Reset_Email = stripslashes_deep($Password_Reset_Email);
	
	update_option('EWD_FEUP_Admin_Email', $Admin_Email);
	update_option('EWD_FEUP_Email_Field', $Email_Field);
	update_option('EWD_FEUP_Password_Reset_Email', $Password_Reset_Email);

	$Counter = 0;
	while ($Counter < 30) {
		if (isset($_POST['Email_Message_' . $Counter . '_Name'])) {
			$Prefix = 'Email_Message_' . $Counter;

			$Email['ID'] = sanitize_text_field($_POST[$Prefix . '_ID']);
			$Email['Name'] = sanitize_text_field($_POST[$Prefix . '_Name']);
			$Email['Subject'] = sanitize_text_field($_POST[$Prefix . '_Subject']);
			$Email['Message'] = sanitize_text_field($_POST[$Prefix . '_Body']);

			$Emails[] = $Email;
			unset($Email);
		}
		$Counter++;
	}
	if (isset($_POST['Options_Submit'])) {update_option('EWD_FEUP_Email_Messages_Array', $Emails);}

	if (isset($_POST['email_reminder_background_color']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Email_Reminder_Background_Color', stripslashes_deep($_POST['email_reminder_background_color']));}
	if (isset($_POST['email_reminder_inner_color']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Email_Reminder_Inner_Color', stripslashes_deep($_POST['email_reminder_inner_color']));}
	if (isset($_POST['email_reminder_text_color']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Email_Reminder_Text_Color', stripslashes_deep($_POST['email_reminder_text_color']));}
	if (isset($_POST['email_reminder_button_background_color']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Email_Reminder_Button_Background_Color', stripslashes_deep($_POST['email_reminder_button_background_color']));}
	if (isset($_POST['email_reminder_button_background_hover_color']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Email_Reminder_Button_Background_Hover_Color', stripslashes_deep($_POST['email_reminder_button_background_hover_color']));}
	if (isset($_POST['email_reminder_button_text_color']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Email_Reminder_Button_Text_Color', stripslashes_deep($_POST['email_reminder_button_text_color']));}
	if (isset($_POST['email_reminder_button_text_hover_color']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Email_Reminder_Button_Text_Hover_Color', stripslashes_deep($_POST['email_reminder_button_text_hover_color']));}
	
	if (isset($_POST['mailchimp_integration']) and $_POST['mailchimp_integration'] == "Yes" and get_option('EWD_FEUP_Mailchimp_Integration') == "No") {EWD_FEUP_Mailchimp_Sync_Current_Users();}
	if (isset($_POST['mailchimp_integration']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Mailchimp_Integration', stripslashes_deep($_POST['mailchimp_integration']));}
	if (isset($_POST['mailchimp_api_key']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Mailchimp_API_Key', stripslashes_deep($_POST['mailchimp_api_key']));}
	if (isset($_POST['mailchimp_list_id']) and $EWD_FEUP_Full_Version == "Yes") {update_option('EWD_FEUP_Mailchimp_List_ID', stripslashes_deep($_POST['mailchimp_list_id']));}
	
	$Counter = 0;
	while ($Counter < 30) {
		if (isset($_POST['Field_ID_' . $Counter])) {
			$Mailchimp_Sync_Field['Field_ID'] = sanitize_text_field($_POST['Field_ID_' . $Counter]);
			$Mailchimp_Sync_Field['Mailchimp_Field_ID'] = sanitize_text_field($_POST['Mailchimp_Field_ID_' . $Counter]);

			$Mailchimp_Sync_Fields[] = $Mailchimp_Sync_Field;
			unset($Mailchimp_Sync_Field);
		}
		$Counter++;
	}
	if (!isset($Mailchimp_Sync_Fields)) { $Mailchimp_Sync_Fields = ""; }
	if (isset($_POST['Options_Submit'])) {update_option('EWD_FEUP_Mailchimp_Sync_Fields', $Mailchimp_Sync_Fields);}
	

	$update = __("Email options have been succesfully updated.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function EWD_FEUP_Return_Users_To_Original_Levels() {
	global $wpdb;
	global $ewd_feup_user_table_name;

	$Return_Levels = get_option("EWD_FEUP_Return_Levels");
	if (!is_array($Return_Levels)) {$Return_Levels = array();}

	$New_Return_Levels = array();
	foreach ($Return_Levels as $Return_Level) {
		if ($Return_Level['Return_Time'] < time()) {
			$wpdb->query("UPDATE $ewd_feup_user_table_name SET Level_ID='" . $Return_Level['Level_ID'] . "' WHERE User_ID='" . $Return_Level['User_ID'] . "'");
		}
		else {
			$New_Return_Levels[] = $Return_Level;
		}
	}

	update_option("EWD_FEUP_Return_Levels", $New_Return_Levels);
}
?>