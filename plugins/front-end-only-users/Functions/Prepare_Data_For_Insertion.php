<?php

function Generate_Password($plainPassword = null) {
	if(!$plainPassword) {
		return false;
	}
	$intermediateSalt = bin2hex(openssl_random_pseudo_bytes(30));
	$intermediateSalt = substr($intermediateSalt,0,22);
	$finalSalt = '$2y$13$'.$intermediateSalt.'$';
	$hashedPassword = crypt($plainPassword,$finalSalt);
	return $hashedPassword;
}

/* Prepare the data to add or edit a single product */
function Add_Edit_User() {
	global $wpdb, $feup_success, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
	$Default_User_Level = get_option("EWD_Default_User_Level");
	$Minimum_Password_Length = get_option("EWD_FEUP_Minimum_Password_Length");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	$Use_Captcha = get_option("EWD_FEUP_Use_Captcha");
	$Allow_Level_Choice = get_option("EWD_FEUP_Allow_Level_Choice");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_On_Admin_Approval = get_option("EWD_FEUP_Email_On_Admin_Approval");
	$Admin_Email_On_Registration = get_option("EWD_FEUP_Admin_Email_On_Registration");
	$Create_WordPress_Users = get_option("EWD_FEUP_Create_WordPress_Users");
	$Mailchimp_Integration = get_option("EWD_FEUP_Mailchimp_Integration");

	$feup_Label_Captcha_Fail =  get_option("EWD_FEUP_Label_Captcha_Fail");
		if ($feup_Label_Captcha_Fail == "") {$feup_Label_Captcha_Fail = __("<span class='ewd-feup-captcha-failed'>The Captcha text did not match the image</span>", 'front-end-only-users');}

	$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
	$Fields = $wpdb->get_results($Sql);
	
	$date = date("Y-m-d H:i:s");

	$UserCookie = CheckLoginCookie();

	if (!isset($_POST['ewd-feup-action']) and (!isset( $_POST['EWD_FEUP_Admin_Nonce'] ) or !wp_verify_nonce( $_POST['EWD_FEUP_Admin_Nonce'], 'EWD_FEUP_Admin_Nonce' ) )) {return;}

	if ($UserCookie['Username'] != "" and ((isset($_POST['action']) and $_POST['action'] == "Add_User") or (isset($_POST['ewd-feup-action']) and $_POST['ewd-feup-action'] == "register"))) {
		$user_update = array("Message_Type" => "Error", "Message" => __("You are currently logged in. Please log out to create a new account.", 'front-end-only-users')); return $user_update;
	}

	if (isset($_POST['User_Account_Expiry'])) {$User_Fields['User_Account_Expiry'] = sanitize_text_field($_POST['User_Account_Expiry']);}

	if (isset($_POST['ewd-feup-action']) and $_POST['ewd-feup-action'] == "register" and $Use_Captcha == "Yes") {$Validate_Captcha = EWD_FEUP_Validate_Captcha();}
	else {$Validate_Captcha = "Yes";}

	$User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", $UserCookie['Username']));
	if (is_object($User)) {
		$User_ID = $User->User_ID;
	}

	if (is_admin()) {$User_ID = (isset($_POST['User_ID'])? sanitize_text_field($_POST['User_ID']) : "");}

	if (isset($User_ID)) {
		$User = $wpdb->get_row($wpdb->prepare("SELECT User_Admin_Approved FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $User_ID));
		if (is_object($User)) { $User_Admin_Approved = $User->User_Admin_Approved;}
		else    {$User_Admin_Approved = "";}
		$User_Current_Admin_Approved = $User_Admin_Approved;
	}
	else {
		$User_Current_Admin_Approved = "No";
	}
	
	if (isset($_POST['Omit_Fields'])) {$Omitted_Fields = explode(",", sanitize_text_field($_POST['Omit_Fields']));}
	else {$Omitted_Fields = array();}
		
	if (isset($_POST['Username'])) {$User_Fields['Username'] = sanitize_text_field($_POST['Username']);}
	if (isset($_POST['ewd-registration-type'])) {$User_Fields['User_Registration_Type'] = sanitize_text_field($_POST['ewd-registration-type']);}
	if (isset($_POST['Third_Party_ID'])) {$User_Fields['User_Third_Party_ID'] = sanitize_text_field($_POST['Third_Party_ID']);}
	// check if the password is empty - so we won't try to update it if it is empty
	if (empty($_POST['User_Password'])) { unset($_POST['User_Password']); }
	if (strlen($_POST['User_Password']) < $Minimum_Password_Length) {unset($_POST['User_Password']); unset($_POST['Confirm_User_Password']);}

	if($Use_Crypt == "Yes") {
		if (isset($_POST['User_Password'])) {$User_Fields['User_Password'] = Generate_Password($_POST['User_Password']);}
	} else {
	if (isset($_POST['User_Password'])) {$User_Fields['User_Password'] = sha1(md5($_POST['User_Password'].$Salt));}
	}
	if (isset($_POST['Level_ID'])) {$User_Fields['Level_ID'] = sanitize_text_field($_POST['Level_ID']);}
	elseif (isset($_POST['level']) and $Allow_Level_Choice == "Yes") {$User_Fields['Level_ID'] = sanitize_text_field($_POST['level']);}
	elseif (isset($_POST['ewd-feup-omit-level']) and $_POST['ewd-feup-omit-level'] != "Yes") {$User_Fields['Level_ID'] = $Default_User_Level;}
	if (isset($_POST['Admin_Approved']) and $_POST['Admin_Approved'] == "Yes") {$User_Fields['User_Admin_Approved'] = "Yes";}
	if (isset($_POST['Admin_Approved']) and $_POST['Admin_Approved'] == "No") {$User_Fields['User_Admin_Approved'] = "No";}
	if (isset($_POST['Email_Confirmation']) and $_POST['Email_Confirmation'] == "Yes") {$User_Fields['User_Email_Confirmed'] = "Yes";}
	if (isset($_POST['Email_Confirmation']) and $_POST['Email_Confirmation'] == "No") {$User_Fields['User_Email_Confirmed'] = "No";}
	if (isset($_POST['User_Membership_Fees_Paid']) and $_POST['User_Membership_Fees_Paid'] == "Yes") {$User_Fields['User_Membership_Fees_Paid'] = "Yes";}
	if (isset($_POST['User_Membership_Fees_Paid']) and $_POST['User_Membership_Fees_Paid'] == "No") {$User_Fields['User_Membership_Fees_Paid'] = "No";}
	if ((isset($_POST['User_Password']) and $_POST['User_Password']) != (isset($_POST['Confirm_User_Password']) and $_POST['Confirm_User_Password'])) {$user_update = array("Message_Type" => "Error", "Message" => __("The passwords you entered did not match.", 'front-end-only-users')); return $user_update;}
	
	if ((isset($_POST['action']) and $_POST['action'] == "Add_User") or (isset($_POST['ewd-feup-action']) and $_POST['ewd-feup-action'] == "register")) {
		if (empty($_POST['User_Password'])) { $user_update = array("Message_Type" => "Error", "Message" => __("The password entered was too short.", 'front-end-only-users')); return $user_update;}
		$wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", sanitize_text_field($_POST['Username'])));
		if ($wpdb->num_rows > 0) {$user_update = array("Message_Type" => "Error", "Message" => __("There is already a user with that Username, please select a different one.", 'front-end-only-users')); return $user_update;}
		if (strlen($_POST['Username']) < 3) {$user_update = array("Message_Type" => "Error", "Message" => __("Username must be at least 3 characters.", 'front-end-only-users')); return $user_update;}
	}

	if (!isset($_POST['ewd-feup-action']) or $_POST['ewd-feup-action'] != "edit-account") {
		if (!isset($Additional_Fields_Array)) {$Additional_Fields_Array=array();}
		foreach ($Fields as $Field) {
			if (!in_array($Field->Field_Name, $Omitted_Fields)) {
				if ($Field->Field_Options != "") {$Field_Allowed_Values = explode(",", $Field->Field_Options);}
				$Field_Name = str_replace(" ", "_", $Field->Field_Name);
				if(!isset($Field_Allowed_Values)){ $Field_Allowed_Values = ""; }
				if (!isset($_POST[$Field_Name])) { $_POST[$Field_Name] = "";}
				if (!is_array($Field_Allowed_Values) or in_array($_POST[$Field_Name], $Field_Allowed_Values) or is_array($_POST[$Field_Name]) or !isset($_POST[$Field_Name])) {
					$Additional_Fields_Array[$Field->Field_Name]['Field_ID'] = $Field->Field_ID;
					$Additional_Fields_Array[$Field->Field_Name]['Field_Name'] = $Field->Field_Name;
					if ($Field->Field_Type == "file" or $Field->Field_Type == "picture") {
					  	$File_Upload_Return = Handle_File_Upload($Field_Name);
						if ($File_Upload_Return['Success'] == "No") {return array("Message_Type" => "Error", "Message" => $File_Upload_Return['Data']);}
						elseif ($File_Upload_Return['Success'] == "N/A") {unset($Additional_Fields_Array[$Field->Field_Name]);}
						else {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $File_Upload_Return['Data'];}
					}
					elseif (is_array($_POST[$Field_Name])) {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep(implode(",", $_POST[$Field_Name]));}
					else {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = sanitize_text_field(stripslashes_deep($_POST[$Field_Name]));}
				}
				unset($Field_Allowed_Values);
			}
		}
	}

	if (!isset($error) and $Validate_Captcha == "Yes") {
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the user */
		if ((isset($_POST['action']) and $_POST['action'] == "Add_User") or (isset($_POST['ewd-feup-action']) and $_POST['ewd-feup-action'] == "register")) {
			if (is_object($User)) {$user_update = __("There is already an account with that Username. Please select a different one.", 'front-end-only-users'); return array("Message_Type" => "Error", "Message" => $user_update);}
			if (!isset($User_Fields['User_Admin_Approved'])) {$User_Fields['User_Admin_Approved'] = "No";}
			if (!isset($User_Fields['User_Email_Confirmed'])) {$User_Fields['User_Email_Confirmed'] = "No";}
			$User_Fields['User_Date_Created'] = $date;
			$User_Fields['User_Last_Login'] = $date;
			$user_update = Add_EWD_FEUP_User($User_Fields);
			$User_ID = $wpdb->insert_id;
			if ($Create_WordPress_Users == "Yes" and $Username_Is_Email == "Yes") {$WP_ID = EWD_FEUP_Add_WP_User($User_ID, $User_Fields);}
			if (!isset($Additional_Fields_Array)) {
				$Additional_Fields_Array=array();
			}
			foreach ($Additional_Fields_Array as $Field) {
				$user_fields_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value'], $date);
				if ($user_fields_update != __("Field has been successfully created.", 'front-end-only-users')) {$user_update = $user_fields_update;}
			}
			if ($Sign_Up_Email != -1) {EWD_FEUP_Send_Registration_Email($User_ID);}
			if (isset($_POST['ewd-feup-action']) and $_POST['ewd-feup-action'] == "register") {
				$user_update = __("Your account has been succesfully created.", 'front-end-only-users');
				if ($Admin_Email_On_Registration != -1) {EWD_FEUP_Send_Admin_Registration_Email($User_ID);}
				if ($Email_Confirmation != "Yes" and $Admin_Approval != "Yes") {
					Confirm_Login();
					//CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
					$feup_success = true;
				}
			}
		}
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the user */
		else {
			if (isset($User_Fields)) {$user_update = Edit_EWD_FEUP_User($User_ID, $User_Fields);}
			if (!isset($Additional_Fields_Array)) {$Additional_Fields_Array=array();}
			if (is_array($Additional_Fields_Array)) {
				foreach ($Additional_Fields_Array as $Field) {
					$CurrentField = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID='%d' AND User_ID='%d'", $Field['Field_ID'], $User_ID));
					if ($CurrentField->User_Field_ID != "") {$user_update = Edit_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value']);}
					else {$user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value'], $date);}
				}
			}
			if (isset($_POST['ewd-feup-action']) and $_POST['ewd-feup-action'] == "edit-account") {CreateLoginCookie(sanitize_text_field($_POST['Username']), $_POST['User_Password']);}
		}

		// If the user receives admin approval for the first time and the option is selected, send them an e-mail
		//Need to check earlier, as it already gets set before this
		if (!isset($User_Fields['User_Admin_Approved'])){ $User_Fields['User_Admin_Approved'] = "";}
		if (($User_Current_Admin_Approved == "No" and $User_Fields['User_Admin_Approved'] == "Yes") and $Email_On_Admin_Approval != -1) {
			$User_Fields['User_Date_Created'] = $date;
			EWD_FEUP_Send_Admin_Approval_Email($User_Fields, $Additional_Fields_Array, $User_ID);
		}

		if ($Mailchimp_Integration == "Yes") {EWD_FEUP_Mailchimp_Subscriber_Sync(array('User_ID' => $User_ID));}

		$user_update = array("Message_Type" => "Update", "Message" => $user_update);
		$feup_success = true;
		return $user_update;
	}
	/* Return any error that might have occurred */
	else {
			if ($Validate_Captcha != "Yes") {$error = "The Captcha text did not match the image";}
			$output_error = array("Message_Type" => "Error", "Message" => $error);
			return $output_error;
	}
}

function EWD_FEUP_Send_Registration_Email($User_ID) {
	global $wpdb, $ewd_feup_user_table_name;

	$Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");

	$Options = array('User_ID' => $User_ID, 'Email_ID' => $Sign_Up_Email);
	
	$Confirmation_Code = EWD_FEUP_RandomString();
	$PageLink = get_permalink( isset($_POST['ewd-feup-post-id']) ? $_POST['ewd-feup-post-id'] : "");
	if (strpos($PageLink, "?") !== false) {
		$ConfirmationLink = $PageLink . "&ConfirmEmail=true&User_ID=" . $User_ID . "&ConfirmationCode=" . $Confirmation_Code;
	}
	else {
		$ConfirmationLink = $PageLink . "?ConfirmEmail=true&User_ID=" . $User_ID . "&ConfirmationCode=" . $Confirmation_Code;
	}
	$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Confirmation_Code=%s WHERE User_ID=%d", $Confirmation_Code, $User_ID));

	$Options['Confirmation_Link'] = $ConfirmationLink;
	
	EWD_FEUP_Send_Email($Options);
}

function EWD_FEUP_Send_Admin_Registration_Email($User_ID) {
	$Admin_Email_On_Registration = get_option("EWD_FEUP_Admin_Email_On_Registration");

	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	if ($Admin_Email == "") {$Admin_Email = get_option('admin_email');}

	$Options = array('User_ID' => $User_ID, 'Email_ID' => $Admin_Email_On_Registration, 'To_Email_Address' => $Admin_Email);

	EWD_FEUP_Send_Email($Options);
}

function EWD_FEUP_Send_Admin_Approval_Email($User_Fields, $Additional_Fields_Array, $User_ID = 0) {
	$Email_On_Admin_Approval = get_option("EWD_FEUP_Email_On_Admin_Approval");

	$Options = array('User_ID' => $User_ID, 'Email_ID' => $Email_On_Admin_Approval);

	EWD_FEUP_Send_Email($Options);
}

/* Prepare the data to add multiple users from a spreadsheet */
function Add_Users_From_Spreadsheet() {
		
	if (!is_user_logged_in()) {exit();}

	/* Test if there is an error with the uploaded spreadsheet and return that error if there is */
	if (!empty($_FILES['Users_Spreadsheet']['error']))
	{
		switch($_FILES['Users_Spreadsheet']['error'])	{

			case '1':
				$error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini', 'front-end-only-users');
				break;
			case '2':
				$error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'front-end-only-users');
				break;
			case '3':
				$error = __('The uploaded file was only partially uploaded', 'front-end-only-users');
				break;
			case '4':
				$error = __('No file was uploaded.', 'front-end-only-users');
				break;

			case '6':
				$error = __('Missing a temporary folder', 'front-end-only-users');
				break;
			case '7':
				$error = __('Failed to write file to disk', 'front-end-only-users');
				break;
			case '8':
				$error = __('File upload stopped by extension', 'front-end-only-users');
				break;
			case '999':
				default:
				$error = __('No error code avaiable', 'front-end-only-users');
			}
	}
		/* Make sure that the file exists */ 	 	
		elseif (empty($_FILES['Users_Spreadsheet']['tmp_name']) || $_FILES['Users_Spreadsheet']['tmp_name'] == 'none') {
				$error = __('No file was uploaded here..', 'front-end-only-users');
		}
		/* Check that it is a .xls or .xlsx file */
		if(!preg_match("/\.(xls.?)$/", $_FILES['Users_Spreadsheet']['name']) and !preg_match("/\.(csv.?)$/", $_FILES['Users_Spreadsheet']['name'])) {
			$error = __('File must be .csv, .xls or .xlsx', 'front-end-only-users');
		}
		/* Move the file and store the URL to pass it onwards*/ 	 	
		else {				 
				 	  $msg .= $_FILES['Users_Spreadsheet']['name'];
						//for security reason, we force to remove all uploaded file
						$target_path = ABSPATH . 'wp-content/plugins/front-end-only-users/user-sheets/';

						$target_path = $target_path . basename( $_FILES['Users_Spreadsheet']['name']); 

						if (!move_uploaded_file($_FILES['Users_Spreadsheet']['tmp_name'], $target_path)) {
						//if (!$upload = wp_upload_bits($_FILES["Item_Image"]["name"], null, file_get_contents($_FILES["Item_Image"]["tmp_name"]))) {
				 			  $error .= "There was an error uploading the file, please try again!";
						}
						else {
				 				$Excel_File_Name = basename( $_FILES['Users_Spreadsheet']['name']);
						}	
		}

		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the users */
		if (!isset($error)) {
				$user_update = Add_FEUP_Users_From_Spreadsheet($Excel_File_Name);
				return $user_update;
		}
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function Handle_File_Upload($Field_Name) {
	
	/* Test if there is an error with the uploaded file and return that error if there is */
	if (!empty($_FILES[$Field_Name]['error'])) {
		switch($_FILES[$Field_Name]['error']) {
			case '1':
				$error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini', 'front-end-only-users');
				break;
			case '2':
				$error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'front-end-only-users');
				break;
			case '3':
				$error = __('The uploaded file was only partially uploaded', 'front-end-only-users');
				break;
			case '4':
				$error = __('No file was uploaded.', 'front-end-only-users');
				break;

			case '6':
				$error = __('Missing a temporary folder', 'front-end-only-users');
				break;
			case '7':
				$error = __('Failed to write file to disk', 'front-end-only-users');
				break;
			case '8':
				$error = __('File upload stopped by extension', 'front-end-only-users');
				break;
			case '999':
			default:
				$error = __('No error code available', 'front-end-only-users');
		}
	}
	/* Make sure that the file exists */ 	 	
	elseif (empty($_FILES[$Field_Name]['tmp_name']) || $_FILES[$Field_Name]['tmp_name'] == 'none') {
		$error = __('No file was uploaded here..', 'front-end-only-users');
	}
	/* Move the file and store the URL to pass it onwards*/ 	 	
	else {
		if (!isset($msg)) { $msg = "";}
		$msg .= $_FILES[$Field_Name]['name'];
		//for security reason, we force to remove all uploaded file
		$target_path = ABSPATH . 'wp-content/uploads/ewd-feup-user-uploads/';
						
		//create the uploads directory if it doesn't exist
		if (!file_exists($target_path)) {
			  mkdir($target_path, 0777, true);
		}

		$Random = EWD_FEUP_RandomString();
		$target_path = $target_path . $Random . basename( $_FILES[$Field_Name]['name']); 

		if (!move_uploaded_file($_FILES[$Field_Name]['tmp_name'], $target_path)) {
		//if (!$upload = wp_upload_bits($_FILES["Item_Image"]["name"], null, file_get_contents($_FILES["Item_Image"]["tmp_name"]))) {
			$error .= "There was an error uploading the file, please try again!";
		}
		else {
					$User_Upload_File_Name = $Random . basename( $_FILES[$Field_Name]['name']);
		}	
	}
		
	/* Return the file name, or the error that was generated. */
	if (isset($error) and $error == __('No file was uploaded.', 'front-end-only-users')) {
		$Return['Success'] = "N/A";
		$Return['Data'] = __('No file was uploaded.', 'front-end-only-users');
	}
	elseif (!isset($error)) {
		$Return['Success'] = "Yes";
		$Return['Data'] = $User_Upload_File_Name;
	}
	else {
		$Return['Success'] = "No";
		$Return['Data'] = $error;
	}
	return $Return;
}

function EWD_FEUP_RandomString($CharLength = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $CharLength; $i++) {
        $randstring .= $characters[rand(0, strlen($characters)-1)];
    }
    return $randstring;
}

function EWD_FEUP_Mass_User_Action() {
	if (isset($_POST['action']) and $_POST['action'] == "delete") {$update = Mass_Delete_EWD_FEUP_Users();}
	elseif (isset($_POST['action']) and $_POST['action'] == "approve") {$update = Mass_Approve_EWD_FEUP_Users();}
	elseif (isset($_POST['action']) and $_POST['action'] == "-1") {}
	else {$update = Mass_Assign_Levels_EWD_FEUP_Users();}
	if (!isset($update)) {  $update = "";}
	return $update;
}

function Mass_Delete_EWD_FEUP_Users() {
	$Users = $_POST['Users_Bulk'];
		
	if (is_array($Users)) {
		foreach ($Users as $User) {
			if ($User != "") {
				Delete_EWD_FEUP_User($User);
			}
		}
	}
		
	$update = __("Users have been successfully deleted.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Mass_Approve_EWD_FEUP_Users() {
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;

	$Email_On_Admin_Approval = get_option("EWD_FEUP_Email_On_Admin_Approval");

	$Users = $_POST['Users_Bulk'];

	if (is_array($Users)) {
		foreach ($Users as $User) {
			$Current_User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $User));
			$User_Update = $wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Admin_Approved='Yes' WHERE User_ID=%d", $User));
			if(is_object($Current_User)) { $User_Admin_Approved = $Current_User->User_Admin_Approved;}
			else{ $User_Admin_Approved = ""; }
			if ($User_Admin_Approved == "No" and $Email_On_Admin_Approval == "Yes") {
				$User_Fields['Username'] = $Current_User->Username;
				$User_Fields['User_Date_Created'] = $Current_User->User_Date_Created;

				$Field_Values = $wpdb->get_results("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='" . $Current_User->User_ID . "'");
				foreach ($Field_Values as $Field_Name => $Field_Value) {$Additional_Fields_Array[$Field_Name]['Field_Value'] = $Field_Value;}
				EWD_FEUP_Send_Admin_Approval_Email($User_Fields, $Additional_Fields_Array, $Current_User->User_ID);
			}
		}
	}

	$update = __("Users have been successfully approved.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Mass_Assign_Levels_EWD_FEUP_Users() {
	global $wpdb, $ewd_feup_user_table_name;
	$Users = isset($_POST['Users_Bulk']) ? $_POST['Users_Bulk'] : "";

	if (is_array($Users)) {
		foreach ($Users as $User) {
			$Users = $wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET Level_ID=%d WHERE User_ID=%d", $_POST['action'], $User));
		}
	}

	$update = __("User levels have been successfully updated.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Delete_All_EWD_FEUP_Users() {
	global $wpdb, $ewd_feup_user_table_name;
	$Users = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");
		
	if (is_array($Users)) {
		foreach ($Users as $User) {
			if ($User->User_ID != "") {
				Delete_EWD_FEUP_User($User->User_ID);
			}
		}
	}
		
	$update = __("Users have been successfully deleted.", 'EWD_OTP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Add_Edit_Field() {
	global $wpdb, $ewd_feup_fields_table_name;

	if ( ! isset( $_POST['EWD_FEUP_Admin_Nonce'] ) ) {return;}

    if ( ! wp_verify_nonce( $_POST['EWD_FEUP_Admin_Nonce'], 'EWD_FEUP_Admin_Nonce' ) ) {return;}

	if (!isset($_POST['Field_ID'])) { $_POST['Field_ID'] = ""; }
	if (!isset($_POST['Field_Name'])) { $_POST['Field_Name'] = ""; }
	if (!isset($_POST['Field_Slug'])) { $_POST['Field_Slug'] = ""; }
	if (!isset($_POST['Field_Type'])) { $_POST['Field_Type'] = ""; }
	if (!isset($_POST['Field_Description'])) { $_POST['Field_Description'] = ""; }
	if (!isset($_POST['Field_Options'])) { $_POST['Field_Options'] = ""; }
	if (!isset($_POST['Field_Show_In_Admin'])) { $_POST['Field_Show_In_Admin'] = "";}
	if (!isset($_POST['Field_Show_In_Front_End'])) { $_POST['Field_Show_In_Front_End'] = "";}
	if (!isset($_POST['Field_Required'])) { $_POST['Field_Required'] = "";}
	if (!isset($_POST['Field_Equivalent'])) { $_POST['Field_Equivalent'] = "";}

 	$Field_ID = stripslashes_deep($_POST['Field_ID']);
	$Field_Name = stripslashes_deep($_POST['Field_Name']);
	$Field_Slug = stripslashes_deep($_POST['Field_Slug']);
	$Field_Type = stripslashes_deep($_POST['Field_Type']);
	$Field_Description = stripslashes_deep($_POST['Field_Description']);
	$Field_Options = stripslashes_deep($_POST['Field_Options']);
	$Field_Show_In_Admin = stripslashes_deep($_POST['Field_Show_In_Admin']);
	$Field_Show_In_Front_End = stripslashes_deep($_POST['Field_Show_In_Front_End']);
	$Field_Required = stripslashes_deep($_POST['Field_Required']);
	$Field_Equivalent = stripslashes_deep($_POST['Field_Equivalent']);
		
	$Field_Date_Created = date("Y-m-d H:i:s");

	if (!isset($error)) {
		if (!isset($_POST['action'])) { $_POST['action'] = "";}
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
		if ($_POST['action'] == "Add_Field") {
			  $user_update = Add_EWD_FEUP_Field($Field_Name, $Field_Slug, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required, $Field_Date_Created, $Field_Equivalent);
		}
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
		else {
				$user_update = Edit_EWD_FEUP_Field($Field_ID, $Field_Name, $Field_Slug, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required, $Field_Equivalent);
		}
		$user_update = array("Message_Type" => "Update", "Message" => $user_update);
		return $user_update;
	}
	/* Return any error that might have occurred */
	else {
		$output_error = array("Message_Type" => "Error", "Message" => $error);
		return $output_error;
	}
}

function Mass_Delete_EWD_FEUP_Fields() {
	$Fields = $_POST['Fields_Bulk'];
		
	if (is_array($Fields)) {
		foreach ($Fields as $Field) {
			if ($Field != "") {
				Delete_EWD_FEUP_Field($Field);
			}
		}
	}
		
	$update = __("Fields have been successfully deleted.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Add_Edit_Payment($args) {
	global $wpdb, $ewd_feup_payments_table_name;
	
	extract( shortcode_atts( array(
				'action' => $_POST['action'],
				'Payment_ID' => stripslashes_deep($_POST['Payment_ID']),
				'User_ID' => stripslashes_deep($_POST['User_ID']),
				'Username' => stripslashes_deep($_POST['Username']),
				'Payer_ID' => stripslashes_deep($_POST['Payer_ID']),
				'PayPal_Receipt_Number' => stripslashes_deep($_POST['PayPal_Receipt_Number']),
				'Payment_Date' => stripslashes_deep($_POST['Payment_Date']),
				'Next_Payment_Date' => stripslashes_deep($_POST['Next_Payment_Date']),
				'Payment_Amount' => stripslashes_deep($_POST['Payment_Amount']),
				'Discount_Code_Used' => stripslashes_deep($_POST['Discount_Code_Used'])),
			$args
		)
	);

	if (!isset($error)) {
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
		if ($action == "Add_Payment") {
			  $user_update = Add_EWD_FEUP_Payment($User_ID, $Username, $Payer_ID, $PayPal_Receipt_Number, $Payment_Date, $Next_Payment_Date, $Payment_Amount, $Discount_Code_Used);
		}
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
		else {
				if ( ! isset( $_POST['EWD_FEUP_Admin_Nonce'] ) ) {return;}

    			if ( ! wp_verify_nonce( $_POST['EWD_FEUP_Admin_Nonce'], 'EWD_FEUP_Admin_Nonce' ) ) {return;}

				$user_update = Edit_EWD_FEUP_Payment($Payment_ID, $User_ID, $Username, $Payer_ID, $PayPal_Receipt_Number, $Payment_Date, $Next_Payment_Date, $Payment_Amount, $Discount_Code_Used);
		}
		$user_update = array("Message_Type" => "Update", "Message" => $user_update);
		return $user_update;
	}
	/* Return any error that might have occurred */
	else {
		$output_error = array("Message_Type" => "Error", "Message" => $error);
		return $output_error;
	}
}

function Mass_Delete_EWD_FEUP_Payments() {
	$Payments = $_POST['Payments_Bulk'];
		
	if (is_array($Payments)) {
		foreach ($Payments as $Payment) {
			if ($Payment != "") {
				Delete_EWD_FEUP_Payment($Payment);
			}
		}
	}
		
	$update = __("Payments have been successfully deleted.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Add_Edit_Level() {
	if ( ! isset( $_POST['EWD_FEUP_Admin_Nonce'] ) ) {return;}

    if ( ! wp_verify_nonce( $_POST['EWD_FEUP_Admin_Nonce'], 'EWD_FEUP_Admin_Nonce' ) ) {return;}
		
	$Level_ID = isset($_POST['Level_ID']) ? $_POST['Level_ID'] : '';
	$Level_Name = isset($_POST['Level_Name']) ? $_POST['Level_Name'] : '';
	$Level_Privilege = isset($_POST['Level_Privilege']) ? $_POST['Level_Privilege'] : '';

	$Level_Include_Fields = isset($_POST['Field_IDs']) ? $_POST['Field_IDs'] : '';
		
	$Level_Date_Created = date("Y-m-d H:i:s");

	if (!isset($error)) {
		if (!isset($_POST['action'])) { $_POST['action'] = ''; }
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
		if ($_POST['action'] == "Add_Level") {
			  $user_update = Add_EWD_FEUP_Level($Level_Name, $Level_Privilege, $Level_Date_Created);
		}
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
		else {
				$user_update = Edit_EWD_FEUP_Level($Level_ID, $Level_Name, $Level_Privilege, $Level_Date_Created);
		}
		if (is_array($Level_Include_Fields) and $Level_ID != "") {EWD_FEUP_Update_Field_Excludes($Level_ID, $Level_Include_Fields);}

		$user_update = array("Message_Type" => "Update", "Message" => $user_update);
		return $user_update;
	}
	/* Return any error that might have occurred */
	else {
		$output_error = array("Message_Type" => "Error", "Message" => $error);
		return $output_error;
	}
}

function Mass_Delete_EWD_FEUP_Levels() {
	$Levels = $_POST['Levels_Bulk'];
		
	if (is_array($Levels)) {
		foreach ($Levels as $Level) {
			if ($Level != "") {
				Delete_EWD_FEUP_Level($Level);
			}
		}
	}
		
	$update = __("Fields have been successfully deleted.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function EWD_FEUP_One_Click_Install() {
	global $EWD_FEUP_Full_Version, $Links_Array;

	if ($EWD_FEUP_Full_Version != "Yes") {exit();}

	if ( ! isset( $_POST['EWD_FEUP_Admin_Nonce'] ) ) {return;}

    if ( ! wp_verify_nonce( $_POST['EWD_FEUP_Admin_Nonce'], 'EWD_FEUP_Admin_Nonce' ) ) {return;}

	foreach ($_POST['page'] as $Page) {
		$Page_Attribute_One = $_POST[$Page . "_attribute_one"];
		$Page_Attribute_Two = $_POST[$Page . "_attribute_two"];
		$Page_Attribute_Three = $_POST[$Page . "_attribute_three"];

		if ($Page_Attribute_One != "") {$Attribute_One_Value = $_POST[$Page . "_" . $Page_Attribute_One];}
		if ($Page_Attribute_One != "") {$Attribute_Two_Value = $_POST[$Page . "_" . $Page_Attribute_Two];}
		if ($Page_Attribute_One != "") {$Attribute_Three_Value = $_POST[$Page . "_" . $Page_Attribute_Three];}

		$Attributes = array();
		if ($Page_Attribute_One != "") {$Attributes[$Page_Attribute_One] = EWD_FEUP_Get_Attribute_Insert_Value($Page, $Page_Attribute_One, $Attribute_One_Value);}
		if ($Page_Attribute_One != "") {$Attributes[$Page_Attribute_Two] = EWD_FEUP_Get_Attribute_Insert_Value($Page, $Page_Attribute_Two, $Attribute_Two_Value);}
		if ($Page_Attribute_One != "") {$Attributes[$Page_Attribute_Three] = EWD_FEUP_Get_Attribute_Insert_Value($Page, $Page_Attribute_Three, $Attribute_Three_Value);}

		$Page_Content = EWD_FEUP_Get_Page_Content($Page, $Attributes);

		$Page_ID = EWD_FEUP_Add_Page(str_replace("_", " ", $Page), $Page_Content);
	}

	EWD_FEUP_Handle_Newly_Created_Page_Links($Links_Array);

	$update = __("Pages have been successfully added. Check the 'Pages' section to modify them.", 'front-end-only-users');
	$user_update = array("Message_Type" => "Update", "Message" => $update);

	return $user_update;
}

function EWD_FEUP_Get_Attribute_Insert_Value($Page, $Page_Attribute, $Attribute_Value) {
	global $Links_Array;

	if ($Page_Attribute == "redirect_page" or $Page_Attribute == "login_page" or $Page_Attribute == "loggedin_page" or $Page_Attribute == "reset_email_url") {
		if (strpos($Attribute_Value, "(Newly Created)") !== FALSE) {
			$Links_Array[$Page . "|" . $Page_Attribute] = $Attribute_Value;
			$Insert_Attribute_Value = $Page . "|" . $Page_Attribute;
		}
		else {
			$Redirect_Page_Object = get_page_by_title(str_replace("_", " ", $Attribute_Value));
			$Insert_Attribute_Value  = get_permalink($Redirect_Page_Object->ID);
		}
	}
	else {$Insert_Attribute_Value = $Attribute_Value;}

	return $Insert_Attribute_Value;
}

function EWD_FEUP_Get_Page_Content($Page, $Attributes = array()) {
	switch ($Page) {
		case 'Edit_Profile':
			$Content = "[edit-profile";
			break;
		case 'Register':
			$Content = "[register";
			break;
		case 'Login':
			$Content = "[login";
			break;
		case 'Logout':
			$Content = "[logout";
			break;
		case 'Forgot_Password':
			$Content = "[forgot-password";
			break;
		case 'Confirm_Forgot_Password':
			$Content = "[confirm-forgot-password";
			break;
		case 'Change_Password':
			$Content = "[reset-password";
			break;
		case 'Login_Logout_Toggle':
			$Content = "[login-logout-toggle";
			break;
		case 'User_Profile':
			$Content = "[user-profile";
			break;
		case 'User_Search':
			$Content = "[user-search";
			break;
		case 'User_List':
			$Content = "[user-list";
			break;
		case 'User_Data':
			$Content = "[user-data";
			break;
	}

	foreach ($Attributes as $Attribute_Name => $Attribute_Value) {
		if ($Attribute_Value != "") {$Content .= " " . $Attribute_Name . "='" . $Attribute_Value . "'";}
	}
	
	$Content .= "]";

	return $Content;
}

function EWD_FEUP_Handle_Newly_Created_Page_Links($Links_Array) {
	if (is_array($Links_Array)) {
		foreach ($Links_Array as $Replace_Value => $Page_Name) {
			$Page_Name = substr($Page_Name, 0, -16);
			$Replace_Page_Name = substr($Replace_Value, 0, strpos($Replace_Value, "|"));
		
			$Redirect_Page_Object = get_page_by_title(str_replace("_", " ", $Page_Name));
			$Insert_Attribute_Value  = get_permalink($Redirect_Page_Object->ID);
			
			$Replace_Page_Object = get_page_by_title(str_replace("_", " ", $Replace_Page_Name));
			$Current_Content = $Replace_Page_Object->post_content;
			$New_Content = str_replace($Replace_Value, $Insert_Attribute_Value, $Current_Content);

			$update_post = array(
							'ID' => $Replace_Page_Object->ID, 
							'post_content' => $New_Content
							);
			wp_update_post($update_post);
		}
	}
}
?>
