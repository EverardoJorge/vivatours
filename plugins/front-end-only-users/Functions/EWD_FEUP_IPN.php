<?php 
if (isset($_POST['ipn_track_id'])) {
	$Payment_Frequency = get_option("EWD_FEUP_Payment_Frequency");

	if ($Payment_Frequency != "None") {
		EWD_FEUP_IPN();
		add_action('init', 'EWD_FEUP_add_ob_start');
		add_action('shutdown', 'EWD_FEUP_flush_ob_end');
	}
}

function EWD_FEUP_add_ob_start() {
    ob_start();
}

function EWD_FEUP_flush_ob_end() {
    ob_end_clean();
}


function EWD_FEUP_IPN() {
	global $wpdb;
	global $ewd_feup_payments_table_name;
	global $ewd_feup_user_table_name;

	$Payment_Frequency = get_option("EWD_FEUP_Payment_Frequency");
	$Payment_Types = get_option("EWD_FEUP_Payment_Types");
	$Membership_Cost = get_option("EWD_FEUP_Membership_Cost");
	$Levels_Payment_Array = get_option("EWD_FEUP_Levels_Payment_Array");
	$Discount_Codes_Array = get_option("EWD_FEUP_Discount_Codes_Array");

	// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
	// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
	// Set this to 0 once you go live or don't require logging.
	define("DEBUG", 0);
	// Set to 0 once you're ready to go live
	define("USE_SANDBOX", 0);
	define("LOG_FILE", "./ipn.log");
	// Read POST data
	// reading posted data directly from $_POST causes serialization
	// issues with array data in POST. Reading raw POST data from input stream instead.
	$raw_post_data = file_get_contents('php://input');
	$raw_post_array = explode('&', $raw_post_data);
	$myPost = array();
	foreach ($raw_post_array as $keyval) {
		$keyval = explode ('=', $keyval);
		if (count($keyval) == 2)
			$myPost[$keyval[0]] = urldecode($keyval[1]);
	}
	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	if(function_exists('get_magic_quotes_gpc')) {
		$get_magic_quotes_exists = true;
	}
	foreach ($myPost as $key => $value) {
		if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
			$value = urlencode(stripslashes($value));
		} else {
			$value = urlencode($value);
		}
		$req .= "&$key=$value";
	}
	// Post IPN data back to PayPal to validate the IPN data is genuine
	// Without this step anyone can fake IPN data
	if(USE_SANDBOX == true) {
		$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	} else {
		$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	}

	$response = wp_remote_post($paypal_url, array(
		'method' => 'POST',
		'body' => $req,
		'timeout' => 30
	));

	// Inspect IPN validation result and act accordingly
	// Split response headers and payload, a better way for strcmp
	$tokens = explode("\r\n\r\n", trim($response['body']));
	$res = trim(end($tokens));
	if (strcmp ($res, "VERIFIED") == 0) {
		
		$Email = $_POST['payer_email'];
		$PayPal_Receipt_Number = $_POST['txn_id'];
		$Payer_ID = $_POST['payer_id'];
		$Payment_Time = strtotime($_POST['payment_date']);
		$Payment_Date = date("Y-m-d H:i:s", $Payment_Time);
		$Payment_Amount = $_POST['mc_gross'];
		
		parse_str($_POST['custom'], $Custom_Vars);
		$User_ID = $Custom_Vars['User_ID'];
		$Username = $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID=%d", $User_ID));
		$Discount_Code_Used = $Custom_Vars['discount_code'];
		
		//if ($_POST['mc_gross'] == $_POST['payment_gross']) {
			if ($Payment_Frequency == "One_Time") {
				$wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Membership_Fees_Paid='Yes', User_Account_Expiry='2100-01-01' WHERE User_ID=%d", $User_ID));
				$Next_Time = time() + (60*60*24*366*100);
				$Next_Payment_Date = '2100-01-01';
			}
			elseif ($Payment_Frequency == "Yearly") {
				$Next_Time =time() + (60*60*24*366);
				$Next_Payment_Date = date("Y-m-d H:i:s", $Next_Time);
				$wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Membership_Fees_Paid='Yes', User_Account_Expiry=%s WHERE User_ID=%d", $Next_Payment_Date, $User_ID));
			}
			elseif ($Payment_Frequency == "Monthly") {
				$Next_Time =time() + (60*60*24*31);
				$Next_Payment_Date = date("Y-m-d H:i:s", $Next_Time);
				$wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Membership_Fees_Paid='Yes', User_Account_Expiry=%s WHERE User_ID=%d", $Next_Payment_Date, $User_ID));
			}

			if ($Payment_Types == "Levels") {
				$Level_ID = $Custom_Vars['level_id'];
				$Current_Level_ID = $Custom_Vars['current_level_id'];
				$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET Level_ID=%d WHERE User_ID=%d", $Level_ID, $User_ID));

				$Return_Levels = get_option("EWD_FEUP_Return_Levels");
				$New_Return['User_ID'] = $User_ID;
				$New_Return['Level_ID'] = $Current_Level_ID;
				$New_Return['Return_Time'] = $Next_Time;
				$Return_Levels[] = $New_Return;

				update_option("EWD_FEUP_Return_Levels", $Return_Levels);
			}

			Add_EWD_FEUP_Payment($User_ID, $Username, $Payer_ID, $PayPal_Receipt_Number, $Payment_Date, $Next_Payment_Date, $Payment_Amount, $Discount_Code_Used);
			
		//}

		if (DEBUG == true) {
			foreach ($_POST as $key => $value) {
				$SaveString .= $key . ": " . $value . "<br>";
			}
			$Current = file_get_contents("Information.html");
			$SaveContent = $Current . "<BR><BR><BR><BR>FEUP<br>" . $SaveString;
			file_put_contents("Information.html", $SaveContent);
		}
		
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
		}
	} else if (strcmp ($res, "INVALID") == 0) {
		// log for manual investigation
		
		$to = $PayPal_Email_Address;  
		$subject = 'Download Area | Invalid Payment';  
		$message = ' 
		 
		Dear Administrator, 
		 
		A payment has been made but is flagged as INVALID. 
		Please verify the payment manualy and contact the buyer. 
		 
		Buyer Email: '.$email.' 
		';  
		$headers = array('Content-Type: text/html; charset=UTF-8'); 
		  
		wp_mail($to, $subject, $message, $headers); 
	
		if(DEBUG == true) {
			error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
		}
	}
} 

?>