<?php

function Process_EWD_FEUP_Front_End_Forms() {
	global $user_message;
		
	if (isset($_POST['ewd-feup-action'])) {
		switch ($_POST['ewd-feup-action']) {
			case "register":
			case "edit-profile":
			case "edit-account":
				$user_message = Add_Edit_User();
				break;
			case "login":
				$user_message['Message'] = Confirm_Login();
				break;
			case "forgot-password":
				$user_message['Message'] = Forgot_Password();
				break;

			case "confirm-forgot-password":
				$user_message['Message'] = Confirm_Forgot_Password();
				break;
		}
	}
}

function Confirm_Login($Username = "", $Third_Party_Login = "No") {
	global $wpdb, $feup_success;
	global $ewd_feup_user_table_name;
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Payment_Frequency = get_option("EWD_FEUP_Payment_Frequency");
	$Payment_Types = get_option("EWD_FEUP_Payment_Types");

	$feup_Label_Login_Successful =  get_option("EWD_FEUP_Label_Login_Successful");
	if ($feup_Label_Login_Successful == "") {$feup_Label_Login_Successful = __("Login successful ", 'front-end-only-users');}
	$feup_Label_Login_Failed_Confirm_Email =  get_option("EWD_FEUP_Label_Login_Failed_Confirm_Email");
	if ($feup_Label_Login_Failed_Confirm_Email == "") {$feup_Label_Login_Failed_Confirm_Email = __("Login failed - you need to confirm your email before you can log in", 'front-end-only-users');}
	$feup_Label_Login_Failed_Admin_Approval =  get_option("EWD_FEUP_Label_Login_Failed_Admin_Approval");
	if ($feup_Label_Login_Failed_Admin_Approval == "") {$feup_Label_Login_Failed_Admin_Approval = __("Login failed - an administrator needs to approve your registration before you can log in", 'front-end-only-users');}
	$feup_Label_Login_Failed_Payment_Required =  get_option("EWD_FEUP_Label_Login_Failed_Payment_Required");
	if ($feup_Label_Login_Failed_Payment_Required == "") {$feup_Label_Login_Failed_Payment_Required = __("Payment required. Please use the form below to pay your membership or subscription fee.", 'front-end-only-users');}
	$feup_Label_Login_Failed_Incorrect_Credentials =  get_option("EWD_FEUP_Label_Login_Failed_Incorrect_Credentials");
	if ($feup_Label_Login_Failed_Incorrect_Credentials == "") {$feup_Label_Login_Failed_Incorrect_Credentials = __("Login failed - incorrect username or password", 'front-end-only-users');}
		
	if ($Username == "") {$Username = $_POST['Username'];}

	if (isset($_POST['ewd-feup-include-wordpress'])) {$Include_WordPress = $_POST['ewd-feup-include-wordpress'];}
	else {$Include_WordPress = "No";}

	if ($Include_WordPress == "Only") {
		$EWD_FEUP_WP_Login = EWD_FEUP_Process_WordPress_Login();
		return $EWD_FEUP_WP_Login;
	}
	
	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username ='%s'", $Username));
	
	if (!$User) {$Passwords_Match = false;}
	elseif ($Third_Party_Login != "Yes") {	
		$Passwords_Match = false;
		if (function_exists('hash_equals')) {
			if($Use_Crypt == "Yes") {
				$Passwords_Match = hash_equals($User->User_Password, crypt($_POST['User_Password'], $User->User_Password));
			} else {
				$Passwords_Match = hash_equals($User->User_Password, sha1(md5($_POST['User_Password'].$Salt)));
			}
		} else {
			if($Use_Crypt == "Yes") {
				if (strcmp($User->User_Password, crypt($_POST['User_Password'], $User->User_Password)) === 0) {
					$Passwords_Match = true;
				} else {
					$Passwords_Match = false;
				}
			} else {
				if (strcmp($User->User_Password, sha1(md5($_POST['User_Password'].$Salt)), $User->User_Password) === 0) {
					$Passwords_Match = true;
				} else {
					$Passwords_Match = false;
				}
			}
		}
	}
	else {
		$Passwords_Match = true;
	}
	if (is_object($User)) { $User_Account_Expiry = $User->User_Account_Expiry; }
	else {  $User_Account_Expiry = ""; }
	$Account_Expiry = strtotime($User_Account_Expiry);
	if ($Account_Expiry == "") {$Account_Expiry = 9446593304;}

	if($Passwords_Match) {
		if ($Payment_Frequency == "None" or $Payment_Types == "Levels" or ($User->User_Membership_Fees_Paid == "Yes" or $Account_Expiry < time())) {
			if ($Admin_Approval != "Yes" or $User->User_Admin_Approved == "Yes") {
				if ($Email_Confirmation != "Yes" or $User->User_Email_Confirmed == "Yes") {
				  	CreateLoginCookie($User->Username, $User->User_Password);
					$Date = date("Y-m-d H:i:s");   
					$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Last_Login='" . $Date . "', User_Total_Logins=User_Total_Logins+1 WHERE Username ='%s'", $User->Username));
					$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Sessioncheck='%s' WHERE Username ='%s'", sha1(md5($_SERVER['REMOTE_ADDR'].$Salt).$_SERVER['HTTP_USER_AGENT']), $User->Username));
					$feup_success = true;

					if ($Include_WordPress == "Yes") {EWD_FEUP_Run_WP_Login($User->User_ID);}

					return '<span class="ewd-feup-login-successful-message">' . $feup_Label_Login_Successful . '</span>';
				}
				return $feup_Label_Login_Failed_Confirm_Email;
			}
			return $feup_Label_Login_Failed_Admin_Approval;
		}
		else {
			$_POST['Payment_Required'] = "Yes";
		}
		$ReturnString = $feup_Label_Login_Failed_Payment_Required;
		$ReturnString .= do_shortcode("[account-payment username='" . $User->Username . "']");
		return $ReturnString;
	}
	return $feup_Label_Login_Failed_Incorrect_Credentials;
}

function EWD_FEUP_Process_WordPress_Login() {
	global $feup_success;

	$feup_Label_Login_Successful =  get_option("EWD_FEUP_Label_Login_Successful");
	if ($feup_Label_Login_Successful == "") {$feup_Label_Login_Successful = __("Login successful ", 'front-end-only-users');}

	$Username = $_POST['Username'];
	$Password = $_POST['User_Password'];

	$user = wp_signon(array('user_login' => $Username, 'user_password' => $Password));

	//if (is_wp_error($user)) {return $user->get_error_message();}
	if (is_wp_error($user)) {return "Login failed";}
	else {
		$feup_success = true;
		return '<span class="ewd-feup-login-successful-message">' . $feup_Label_Login_Successful . '</span>';
	}
}

function EWD_FEUP_Run_WP_Login($User_ID) {
	global $wpdb, $feup_success;
	global $ewd_feup_user_table_name;

	$User_WP_ID = $wpdb->get_var($wpdb->prepare("SELECT User_WP_ID FROM $ewd_feup_user_table_name WHERE User_ID=%d", $User_ID));

	add_filter("auth_cookie_expiration", "EWD_FEUP_Set_Login_Time", 90, 1);

	if ($User_WP_ID) {wp_set_auth_cookie($User_WP_ID);}
}

function EWD_FEUP_Set_Login_Time() {
	$LoginTime = get_option("EWD_FEUP_Login_Time");

	$ExpirySecond = time() + (1+$LoginTime)*60;

	return $ExpirySecond;
}

function Forgot_Password() {
	global $wpdb, $feup_success;
	global $ewd_feup_user_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name;

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	$Use_Captcha = get_option("EWD_FEUP_Use_Captcha");
	$Email_Field = get_option("EWD_FEUP_Email_Field");
	$Email_Field = str_replace(" ", "_", $Email_Field);

	$Password_Reset_Email = get_option("EWD_FEUP_Password_Reset_Email");
		
	//$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username ='%s'", $_POST['Email']));
	if($Username_Is_Email == "Yes") {
		$User = $wpdb -> get_row( $wpdb -> prepare( "SELECT * FROM $ewd_feup_user_table_name WHERE Username = '%s'", $_POST['Email'] ) );
		$User_Email = $User->Username;
	} else {
		$User_Fields = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE Field_Value = '%s' AND Field_Name = '%s' ", $_POST['Email'], $Email_Field));
		if (is_object($User_Fields)) {  $User_ID = $User_Fields->User_ID;}
		else {  $User_ID = ""; }
		$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE User_ID = '%d'", $User_ID ));
		if (is_object($User_Fields)) { $Field_Value = $User_Fields->Field_Value; }
		else {  $Field_Value = ""; }
		$User_Email = $Field_Value;
	}

	if ($Use_Captcha == "Yes") {$Validate_Captcha = EWD_FEUP_Validate_Captcha();}
	else {$Validate_Captcha = "Yes";}
		
	if( !empty( $User ) and $Validate_Captcha == "Yes")
	{
		$Options = array('User_ID' => $User->User_ID, 'Email_ID' => $Password_Reset_Email);

		// generate pw reset code
		$Options['Reset_Code'] = EWD_FEUP_RandomString(15);
		$Options['Reset_Email_URL'] = $_POST['ewd-feup-reset-email-url'];
		
		$wpdb -> update( $ewd_feup_user_table_name, array(
				'User_Password_Reset_Code' => $Options['Reset_Code'],
				'User_Password_Reset_Date' => date('Y-m-d H:i:s', time())
			),
			array(
				'User_ID' => $User->User_ID,
			),
			array(
				'%s'
			)
		);
		
		EWD_FEUP_Send_Email($Options);

		$feup_success = true;
		
		//return success message
		return __("For completing the password reset procedure, please follow the instructions in your email.", 'front-end-only-users');
	}
	elseif ($_POST['ewd-feup-reveal-no-username'] == "Yes") {
		return __("There is no user listed for the username/email address you entered in the database. Please go to the registration page to sign up.", 'front-end-only-users');
	}
	else
	{
		//return success message even though operation failed - we don't want 'them' to know which
		// email addresses are used
		return __("For completing the password reset procedure, please follow the instructions in your email.", 'front-end-only-users');
	}
}

function Confirm_Forgot_Password() {
	global $wpdb, $feup_success;
	global $ewd_feup_user_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name;

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Minimum_Password_Length = get_option("EWD_FEUP_Minimum_Password_Length");

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	$Email_Field = get_option("EWD_FEUP_Email_Field");
	$Email_Field = str_replace(" ", "_", $Email_Field);
	$Given_Reset_Code = $_POST['Resetcode'];
	$Given_Password = $_POST['User_Password'];

	if (!empty($Given_Reset_Code)) {
		if (strcmp($Given_Password, $_POST['Confirm_User_Password']) === 0) {
			if (strlen($Given_Password) >= $Minimum_Password_Length) {

				if ($Username_Is_Email == "Yes") {
					$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username = '%s'", $_POST['Email']));
					$User_Email = $User->Username;
				} else {
					$User_Fields = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE Field_Value = '%s' AND Field_Name = '%s' ", $_POST['Email'], $Email_Field));
					if (is_object($User_Fields)) {  $User_ID = $User_Fields->User_ID; }
					else {  $User_ID = 0;}
					if (is_object($User_Fields)) {  $Field_Value = $User_Fields->Field_Value;}
					else { $Field_Value = ""; }
					$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE User_ID = '%d'", $User_ID));
					$User_Email = $Field_Value;
				}

				if (!empty($User) && !empty($User->User_Password_Reset_Code)) {
					$Current_Date = new DateTime();
					$Request_Date = new DateTime($User->User_Password_Reset_Date);
					$Time_Since_Reset_Requested = $Current_Date->diff($Request_Date);
					if($Time_Since_Reset_Requested->d < 7) {
						if (strcmp($Given_Reset_Code, $User->User_Password_Reset_Code) === 0) {
							// everything seems ok, let's change the pw
							// also remove the reset code so it can't be reused
							if ($Use_Crypt == "Yes") {
								$New_Password_Hash = Generate_Password($Given_Password);
							}
							else {
								$New_Password_Hash = sha1(md5($Given_Password.$Salt));
							}
							$wpdb->update($ewd_feup_user_table_name, array(
									'User_Password_Reset_Code' => '',
									'User_Password' => $New_Password_Hash,
								),
								array(
									'User_ID' => $User->User_ID,
								),
								array(
									'%s'
								)
							);

							if ($_POST['ewd-feup-inlcude-wp'] == "Yes") {
								$User_WP_ID = $wpdb->get_var($wpdb->prepare("SELECT User_WP_ID FROM $ewd_feup_user_table_name WHERE User_ID=%d", $User->User_ID));
								if ($User_WP_ID != 0) {wp_set_password($Given_Password, $User_WP_ID);}
							}


							$feup_success = true;

							//return success message
							return __("Your password has been successfully changed. You can log in using your new password now.", 'front-end-only-users');
						} else {
							return __("The password reset code you entered was wrong. You need to get a new one before using this function again.", 'front-end-only-users');
						}
					} else {
						$wpdb->update($ewd_feup_user_table_name, array(
								'User_Password_Reset_Code' => '',
							),
							array(
								'User_ID' => $User->User_ID,
							),
							array(
								'%s'
							)
						);
						return __("This password reset code is too old and we have disabled it for your security. Please use the 'I forgot my password' function to acquire a new one.");
					}
				} else {
					return __("You need a password reset code to reset your password. Please use the 'I forgot my password' function first to acquire one.");
				}
			} else {
				return __("Please select a longer password");
			}
		} else {
			return __("The passwords you entered did not match");
		}
	} else {
		return __("You need a password reset code to reset your password. Please use the 'I forgot my password' function first to acquire one.");
	}
}

function FEUPRedirect($redirect_page) {
	header("location:" . $redirect_page);
}

function ConfirmUserEmail() {
	global $wpdb, $ewd_feup_user_table_name;

	$User_ID = $_GET['User_ID'];
	$Email_Address = $_GET['ConfirmEmail'];
	$Confirmation_Code = $_GET['ConfirmationCode'];

	$Retrieved_User_ID = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_ID=%d AND User_Confirmation_Code=%s", $User_ID, $Confirmation_Code));
	if (isset($Retrieved_User_ID->User_ID)) {
		$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Email_Confirmed='Yes' WHERE User_ID=%d", $Retrieved_User_ID->User_ID));
		$ConfirmationSuccess = "Yes";
	}
	else {
		$ConfirmationSuccess = "No";
	}

	return $ConfirmationSuccess;
}

function Get_User_Search_Results($search_logic, $display_fields_array, $order_by, $order) {
	global $wpdb, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
		
	foreach ($_POST as $field => $value) {
		if (substr($field, 0, 7) == "search_") {
			if (!isset($DataSet['Criteria'])) { $DataSet['Criteria'] = ""; }
			$DataSet['Criteria'] .= str_replace("_", " ", substr($field, 7));
			if ($value != "") {$DataSet['Value'] = "%" . $value . "%";}
			else {$DataSet['Value'] = "";}
			$Criterion[] = $DataSet;
			unset($DataSet);
		}
	}

	if (!is_array($Criterion)) {return array();}
		
	$list = array();
	foreach ($Criterion as $DataSet) {
		unset($IDs);
		$IDs = array();
		if ($DataSet['Criteria'] == "Username") {$UserList = $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username LIKE '%s'", $DataSet['Value']));}
		else {$UserList = $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_fields_table_name WHERE Field_Name='%s' AND Field_Value LIKE '%s'", $DataSet['Criteria'], $DataSet['Value']));}
		foreach ($UserList as $User) {
			$IDs[] = $User->User_ID;
		}
		$list[] = $IDs;
	}
		
	if (sizeOf($list) < 2) {
		$UserIDs = $IDs;
	} else {
		if ($search_logic == "AND") {$UserIDs = call_user_func_array('array_intersect',$list);}
		else {
			foreach ($list as $Criteria_List) {
				foreach ($Criteria_List as $Matching_User) {
					$Combined_IDs[] = $Matching_User;
				}
			}
			if (isset($Combined_IDs) and is_array($Combined_IDs)) {$UserIDs = array_unique($Combined_IDs);}
		}
	}

	if ($order_by != "") {
		$User_ID_String = implode(",", $UserIDs);
		if ($order_by == "User_ID" or $order_by == "Username") {$User_ID_Objects = $wpdb->get_results("SELECT DISTINCT User_ID FROM $ewd_feup_user_table_name WHERE User_ID IN ( $User_ID_String ) ORDER BY $order_by $order");}
		else {$User_ID_Objects = $wpdb->get_results("SELECT DISTINCT $ewd_feup_user_table_name.User_ID FROM $ewd_feup_user_table_name INNER JOIN $ewd_feup_user_fields_table_name ON $ewd_feup_user_table_name.User_ID = $ewd_feup_user_fields_table_name.User_ID WHERE $ewd_feup_user_table_name.User_ID IN ( $User_ID_String ) ORDER BY '$ewd_feup_user_fields_table_name.$order_by' $order");}
		$UserIDs = array();
		foreach ($User_ID_Objects as $ID_Object) {$UserIDs[] = $ID_Object->User_ID;}
	}

	if (isset($UserIDs) and is_array($UserIDs)) {
		foreach ($UserIDs as $UserID) {
			$UserInformation = array();
			foreach ($display_fields_array as $display_field) {
				if ($display_field == "Username") {$User = $wpdb->get_row($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $UserID));}
				else {$User = $wpdb->get_row($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d' and field_name=%s", $UserID, trim($display_field)));}
				if ($display_field == "Username"){$UserInformation[$display_field] = $User->Username;}
				else {$UserInformation[$display_field] = $User->Field_Value;}
			}
			$UserInformation['User_ID'] = $UserID;
			$Users[] = $UserInformation;
			unset($UserInformation);
		}
	}
		
	return isset($Users) ? $Users : array();
}

function EWD_FEUP_Process_Twitter_Login($Twitter_ID) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	$Username = $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_Registration_Type='Twitter' AND User_Third_Party_ID=%s", $Twitter_ID));
	
	$Return_Array['Message'] = Confirm_Login($Username, "Yes");

	if ($Return_Array['Message'] == __("Login successful", 'front-end-only-users')) {$Return_Array['Status'] = "Success";}
	else {$Return_Array['Status'] = "Failure";}

	return $Return_Array;
}

function EWD_FEUP_Process_Facebook_Login($Facebook_ID) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	$Username = $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_Registration_Type='Facebook' AND User_Third_Party_ID=%s", $Facebook_ID));
	
	$Return_Array['Message'] = Confirm_Login($Username, "Yes");
	
	if ($Return_Array['Message'] == __("Login successful", 'front-end-only-users')) {$Return_Array['Status'] = "Success";}
	else {$Return_Array['Status'] = "Failure";}

	return $Return_Array;
}
?>
