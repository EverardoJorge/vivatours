<?php

function EWD_FEUP_Account_Payment($atts) {
	global  $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_levels_table_name;

	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");

	$Payment_Frequency = get_option("EWD_FEUP_Payment_Frequency");
	$Payment_Types = get_option("EWD_FEUP_Payment_Types");
	$Membership_Cost = get_option("EWD_FEUP_Membership_Cost");
	$Free_Trial_Days = get_option("EWD_FEUP_Free_Trial_Days");
	$Pricing_Currency_Code = get_option("EWD_FEUP_Pricing_Currency_Code");
	$Thank_You_URL = get_option("EWD_FEUP_Thank_You_URL");
	$Discount_Codes_Array = get_option("EWD_FEUP_Discount_Codes_Array");
	$Payment_Gateway = get_option("EWD_FEUP_Payment_Gateway");
	$PayPal_Email_Address = get_option("EWD_FEUP_PayPal_Email_Address");
	$Stripe_Live_Secret = get_option("EWD_FEUP_Stripe_Live_Secret");
	$Stripe_Live_Publishable = get_option("EWD_FEUP_Stripe_Live_Publishable");

	$Levels_Payment_Array = get_option("EWD_FEUP_Levels_Payment_Array");
	if (!is_array($Levels_Payment_Array)) {$Levels_Payment_Array = array();}

	$CheckCookie = CheckLoginCookie();
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'username' => '',
				'level' => '',
				'discount_code' => '',
				),
		$atts
		)
	);

	if ($CheckCookie['Username'] != "") {$username = $CheckCookie['Username'];}

	$ReturnString = "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= EWD_FEUP_Add_Modified_Styles();
	$ReturnString .= "</style>";

	if ($username == "" and isset($_POST['Username'])) {
		$username = $_POST['Username'];
	}
	if ($level == "" and isset($_POST['level'])) {
		$level = $_POST['level'];
	}
	if ($discount_code == "" and isset($_POST['discount_code'])) {
		$discount_code = $_POST['discount_code'];
	}

	$feup_Label_Upgrade_Account =  get_option("EWD_FEUP_Label_Upgrade_Account");
	$feup_Label_Upgrade_Level =  get_option("EWD_FEUP_Label_Upgrade_Level");
	$feup_Label_Username =  get_option("EWD_FEUP_Label_Username");
	$feup_Label_Level =  get_option("EWD_FEUP_Label_Level");
	$feup_Label_Next =  get_option("EWD_FEUP_Label_Next");
	$feup_Label_Discount_Message =  get_option("EWD_FEUP_Label_Discount_Message");
	$feup_Label_Discount_Code =  get_option("EWD_FEUP_Label_Discount_Code");
	$feup_Label_Use_Discount_Code =  get_option("EWD_FEUP_Label_Use_Discount_Code");
	if ($feup_Label_Upgrade_Account == "") {$feup_Label_Upgrade_Account = __("Upgrade Account", 'front-end-only-users');}
	if ($feup_Label_Upgrade_Level == "") {$feup_Label_Upgrade_Level = __("Select the level you'd like to upgrade to using the form below:", 'front-end-only-users');}
	if ($feup_Label_Username == "") {$feup_Label_Username = __('Username', 'front-end-only-users');}
	if ($feup_Label_Level == "") {$feup_Label_Level = __('Level', 'front-end-only-users') ;}
	if ($feup_Label_Next == "") {$feup_Label_Next = __('Next', 'front-end-only-users') ;}
	if ($feup_Label_Discount_Message == "") {$feup_Label_Discount_Message = __("Have a discount code? Enter it below.", 'front-end-only-users');}
	if ($feup_Label_Discount_Code == "") {$feup_Label_Discount_Code = __('Discount Code', 'front-end-only-users') ;}
	if ($feup_Label_Use_Discount_Code == "") {$feup_Label_Use_Discount_Code = __('Use Discount Code', 'front-end-only-users') ;}

	$feup_Label_Stripe_Submit_Payment_Text = get_option("EWD_FEUP_Label_Stripe_Submit_Payment_Text");
	if ($feup_Label_Stripe_Submit_Payment_Text == "") {$feup_Label_Stripe_Submit_Payment_Text = __("Submit a payment of", 'front-end-only-users');}
	$Stripe_Currency_Symbol = get_option("EWD_FEUP_Stripe_Currency_Symbol");
	$Stripe_Currency_Symbol_Placement = get_option("EWD_FEUP_Stripe_Currency_Symbol_Placement");


	if ($username == "" or ($Payment_Types == "Levels" and $level == "")) {
		$ReturnString .= "<div class='ewd-feup-paypal-username-form'>";
		if ($Payment_Types == "Levels") {
			$ReturnString .= "<h4>" . $feup_Label_Upgrade_Account . "</h4>";
			$ReturnString .= "<p>" . $feup_Label_Upgrade_Level . "</p>";
		}
		$ReturnString .= "<form action='#' method='post'>";
		if ($username == "") {
			$ReturnString .= "<div class='feup-pure-control-group'>";
			$ReturnString .= "<label for='Username' id='ewd-feup-paypal-username-div' class='ewd-feup-field-label'>" . $feup_Label_Username  . ": </label>";
			$ReturnString .= "<input type='text' class='ewd-feup-text-input ewd-feup-paypal-username-input' name='Username'>";
			$ReturnString .= "</div>";
		}
		else {
			$ReturnString .= "<div class='feup-pure-control-group'>";
			$ReturnString .= "<label for='Username' id='ewd-feup-paypal-username-label' class='ewd-feup-field-label'>" . $feup_Label_Username  . ": </label>";
			$ReturnString .= "<input type='hidden' name='Username' value='" . $username . "' />";
			$ReturnString .= "<span class='ewd-feup-username'>" . $username . "</span>";
			$ReturnString .= "</div>";
		}
		if ($Payment_Types == "Levels") {
			if ($level == "") {
				$ReturnString .= "<div class='feup-pure-control-group'>";
				$ReturnString .= "<label for='level' id='ewd-feup-paypal-username-div' class='ewd-feup-field-label'>" . $feup_Label_Level . ": </label>";
				$ReturnString .= "<select class='ewd-feup-select-input' name='level'>";
				foreach ($Levels_Payment_Array as $Level_Payment_Item) {
					$Level = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_levels_table_name WHERE Level_ID=%d", $Level_Payment_Item['Level']));
					$ReturnString .= "<option value='" . $Level_Payment_Item['Level'] . "'>" . $Level->Level_Name . " (" . $Level_Payment_Item['Amount'] . ")</option>";
				}
				$ReturnString .= "</select>";
				$ReturnString .= "</div>";
			}
			else {
				$ReturnString .= "<input type='hidden' name='level' value='" . $level . "' />";
			}

		}
		$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='PayPal_Username_Submit' value='" . $feup_Label_Next . "'></div>";
		$ReturnString .= "</form>";
		$ReturnString .= "</div>";
	}

	//Create form with link to pay for membership
	else {
		$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username=%s", $username));

		if ($Payment_Frequency == "One_Time") {
			if ($Payment_Types == "Membership") {
				if ($discount_code != "") {$Discount = EWD_FEUP_Calculate_Discount("Membership", $discount_code, $Payment_Frequency);}
				else {$Discount['Amount'] = 0;}
				$Payment_Amount = $Membership_Cost - $Discount['Amount'];
			}
			else {
				$Selected_Level = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_levels_table_name WHERE Level_ID=%d", $level));
				if ($discount_code != "") {$Discount = EWD_FEUP_Calculate_Discount($level, $discount_code, $Payment_Frequency);}
				else {$Discount['Amount'] = 0;}
				$Level_Cost = EWD_FEUP_Calculate_Level_Payment($level);
				$Payment_Amount = $Level_Cost - $Discount['Amount'];
			}
		}
		else {
			if ($Payment_Types == "Membership") {
				if ($discount_code != "") {$Discount = EWD_FEUP_Calculate_Discount("Membership", $discount_code, $Payment_Frequency);}
				else {$Discount['Amount'] = 0;}
					
				if ($Discount['Amount'] == 0) {
					$Payment_Amount = $Membership_Cost;
				}
				elseif ($Discount['Amount'] != 0 and $Discount['Recurring'] != "Yes") {
						
					$Trial = true;
					$Payment_Amount_Trial = $Membership_Cost - $Discount['Amount'];
					$Payment_Amount = $Membership_Cost;
				}
				else {
					$Payment_Amount = $Membership_Cost - $Discount['Amount'];
				}
			}
			else {
				$Selected_Level = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_levels_table_name WHERE Level_ID=%d", $level));
				if ($discount_code != "") {$Discount = EWD_FEUP_Calculate_Discount($level, $discount_code, $Payment_Frequency);}
				else {$Discount['Amount'] = 0;}
	
				$Level_Cost = EWD_FEUP_Calculate_Level_Payment($level);
					
				if ($Discount['Amount'] == 0) {
					$Payment_Amount = $Level_Cost ;
				}
				elseif ($Discount['Amonut'] != 0 and $Discount['Recurring'] != "Yes") {
					$Trial = true;
					$Payment_Amount_Trial = $Level_Cost - $Discount['Amount'];
					$Payment_Amount = $Level_Cost;
				}
				else {
					$Payment_Amount = $Level_Cost - $Discount['Amount'];
				}
			}
		}

		if ($Payment_Amount == 0) {
			$wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Membership_Fees_Paid='Yes', User_Account_Expiry='2100-01-01' WHERE User_ID=%d", $User->User_ID));

			if ($Payment_Types == "Levels") {
				$Level_ID = $level;
				$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET Level_ID=%d WHERE User_ID=%d", $Level_ID, $User_ID));
			}

			return __("Your account have been successfully created.", 'front-end-only-users');
		}
		elseif ($Payment_Gateway == "PayPal" or $Payment_Gateway == "") {
			$ReturnString .= "<div class='ewd-feup-paypal-form'>";
			$ReturnString .= "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' class='standard-form'>";
			//$ReturnString .= "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' class='standard-form'>";
			if ($Payment_Frequency == "One_Time") {
				if ($Payment_Types == "Membership") {
		        	$ReturnString .= "<input type='hidden' name='item_name_1' value='" . substr(get_bloginfo('name'), 0, 100) . " Site Membership' />";
		        	$ReturnString .= "<input type='hidden' name='custom' value='User_ID=" . $User->User_ID . "&discount_code=" . $discount_code . "' />";
				}
				else {
		        	$ReturnString .= "<input type='hidden' name='item_name_1' value='" . substr(get_bloginfo('name'), 0, 100) . " " . $Selected_Level->Level_Name . " Fee' />";
		 			$ReturnString .= "<input type='hidden' name='custom' value='User_ID=" . $User->User_ID . "&discount_code=" . $discount_code . "&level_id=" . $level . "&current_level_id=" . $User->Level_ID . "' />";
		 		}
	
				$ReturnString .= "<input type='hidden' name='quantity_1' value='1' />";
		        $ReturnString .= "<input type='hidden' name='amount_1' value='" . $Payment_Amount . "' />";
	
		        $ReturnString .= "<input type='hidden' name='cmd' value='_cart' />";
		    	$ReturnString .= "<input type='hidden' name='upload' value='1' />";
			}
			else {
				if ($Payment_Types == "Membership") {
		        	$ReturnString .= "<input type='hidden' name='item_name' value='" . substr(get_bloginfo('name'), 0, 100) . " " . $Payment_Frequency . " Membership' />";
		 			$ReturnString .= "<input type='hidden' name='custom' value='User_ID=" . $User->User_ID . "&discount_code=" . $discount_code . "' />";
				}
				else {
		        	$ReturnString .= "<input type='hidden' name='item_name' value='" . substr(get_bloginfo('name'), 0, 100) . " " . $Payment_Frequency . " " . $Selected_Level->Level_Name . "' />";
		 			$ReturnString .= "<input type='hidden' name='custom' value='User_ID=" . $User->User_ID . "&discount_code=" . $discount_code . "&level_id=" . $level . "&current_level_id=" . $User->Level_ID . "' />";
		 			
				}
	
				if ($Payment_Frequency == "Yearly") {$PP_Frequnecy = "Y";}
				if ($Payment_Frequency == "Monthly") {$PP_Frequnecy = "M";}
	
				$ReturnString .= "<input type='hidden' name='a3' value='" . $Payment_Amount . "' />";
		        $ReturnString .= "<input type='hidden' name='p3' value='1' />";
		        $ReturnString .= "<input type='hidden' name='t3' value='" . $PP_Frequnecy . "' />";
	
		        if ($Trial) {
		        	$ReturnString .= "<input type='hidden' name='a1' value='" . $Payment_Amount_Trial . "' />";
		        	$ReturnString .= "<input type='hidden' name='p1' value='1' />";
		        	$ReturnString .= "<input type='hidden' name='t1' value='" . $PP_Frequnecy . "' />";
		        }
		        elseif ($Free_Trial_Days != 0) {
		        	$ReturnString .= "<input type='hidden' name='a1' value='0' />";
		        	$ReturnString .= "<input type='hidden' name='p1' value='" . $Free_Trial_Days . "' />";
		        	$ReturnString .= "<input type='hidden' name='t1' value='D' />";
		        }
	
		        $ReturnString .= "<input type='hidden' name='cmd' value='_xclick-subscriptions' />";
		        $ReturnString .= "<input type='hidden' name='src' value='1' />";
			}
	
		    $ReturnString .= "<input type='hidden' name='business' value='" . $PayPal_Email_Address . "' />";
		 			
		    $ReturnString .= "<input type='hidden' name='currency_code' value='" . $Pricing_Currency_Code . "' />";
		    $ReturnString .= "<input type='hidden' name='return' value='" . $Thank_You_URL . "' />";
		    $ReturnString .= "<input type='hidden' name='notify_url' value='" . get_site_url() . "' />";
		 			
		    $ReturnString .= "<input type='submit' class='submit-button' value='Proceed to Payment' />";
			$ReturnString .= "</form>";
			$ReturnString .= "</div>";
		}
		elseif ($Payment_Gateway == "Stripe") {
			$ReturnString .= "<div class='ewd-feup-stripe-payment-form'>";
			if($Stripe_Currency_Symbol_Placement == 'After'){
				$ReturnString .= "<h2>" . $feup_Label_Stripe_Submit_Payment_Text . " " . $Payment_Amount . " " . $Stripe_Currency_Symbol . "</h2>";
			}
			else{
				$ReturnString .= "<h2>" . $feup_Label_Stripe_Submit_Payment_Text . " " . $Stripe_Currency_Symbol . $Payment_Amount . "</h2>";
			}
			if (isset($_GET['payment']) && $_GET['payment'] == 'failed') {
				$ReturnString .= '<p class="failure">' . __('There was a problem with your payment, your card has not been charged.', 'front-end-only-users') . '</p>';
			}
			$ReturnString .= "<form action='#' method='POST' id='stripe-payment-form'>";
			$ReturnString .= "<div class='form-row'>";
			$ReturnString .= "<label>" . __('Card Number', 'front-end-only-users') . "</label>";
			$ReturnString .= "<input type='text' size='20' autocomplete='off' data-stripe='card_number'/>";
			$ReturnString .= "</div>";
			$ReturnString .= "<div class='form-row'>";
			$ReturnString .= "<label>" . __('CVC', 'front-end-only-users') . "</label>";
			$ReturnString .= "<input type='text' size='4' autocomplete='off' data-stripe='card_cvc'/>";
			$ReturnString .= "</div>";
			$ReturnString .= "<div class='form-row'>";
			$ReturnString .= "<label>" . __('Expiration (MM/YYYY)', 'front-end-only-users') . "</label>";
			$ReturnString .= "<input type='text' size='2' data-stripe='exp_month'/>";
			$ReturnString .= "<span> / </span>";
			$ReturnString .= "<input type='text' size='4' data-stripe='exp_year'/>";
			$ReturnString .= "</div>";
			$ReturnString .= "<input type='hidden' name='action' value='ewd_feup_stripe'/>";
			$ReturnString .= "<input type='hidden' name='currency' value='" . $Pricing_Currency_Code . "' data-stripe='currency' />";
			$ReturnString .= "<input type='hidden' name='payment_amount' value='" . $Payment_Amount . "' />";
			$ReturnString .= "<input type='hidden' name='User_ID' value='" . $User->User_ID . "' />";
			$ReturnString .= "<input type='hidden' name='discount_code' value='" . $discount_code . "' />";
			if ($level == "" and isset($_POST['level'])) {$ReturnString .= "<input type='hidden' name='level' value='" . $level . "' />";}
			if ($level == "" and isset($_POST['level'])) {$ReturnString .= "<input type='hidden' name='current_level_id' value='" . $User->Level_ID . "' />";}
			if ($Payment_Frequency != "One_Time") {$ReturnString .= "<input type='hidden' name='recurring' value='yes' />";}
			$ReturnString .= "<input type='hidden' name='redirect' value='" . get_permalink() . "'/>";
			$ReturnString .= "<input type='hidden' name='stripe_nonce' value='" . wp_create_nonce('stripe-nonce') . "'/>";
			$ReturnString .= "<button type='submit' id='stripe-submit'>" . __('Submit Payment', 'front-end-only-users') . "</button>";
			$ReturnString .= "</form>";
			$ReturnString .= "<div class='payment-errors'></div>";
			$ReturnString .= "</div>";
		}

		if (sizeof($Discount_Codes_Array) > 0) {
			$ReturnString .= "<div class='ewd-feup-discount-div'>";
			$ReturnString .= $feup_Label_Discount_Message;
			$ReturnString .= "<div class='ewd-feup-discount-form'>";
			$ReturnString .= "<form action='#' method='post'>";
			$ReturnString .= "<input type='hidden' name='Payment_Required' value='Yes' />";
			if ($username != "") {$ReturnString .= "<input type='hidden' name='Username' value='" . $username . "' />";}
			if ($level != "") {$ReturnString .= "<input type='hidden' name='Username' value='" . $level . "' />";}

			$ReturnString .= "<div class='feup-pure-control-group'>";
			$ReturnString .= "<label for='Username' id='ewd-feup-paypal-discount-code-div' class='ewd-feup-field-label'>" . $feup_Label_Discount_Code . ": </label>";
			$ReturnString .= "<input type='text' class='ewd-feup-text-input ewd-feup-paypal-discount-code-input' name='discount_code'>";
			$ReturnString .= "</div>";

			$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Discount_Submit' value='" . $feup_Label_Use_Discount_Code . "'></div>";
			$ReturnString .= "</form>";
			$ReturnString .= "</div>";
		}
	}

	return $ReturnString;
}
add_shortcode('account-payment', "EWD_FEUP_Account_Payment");


function EWD_FEUP_Calculate_Discount($Applies_To, $discount_code, $Payment_Frequency) {
	$Discount_Codes_Array = get_option("EWD_FEUP_Discount_Codes_Array");

	if (!is_array($Discount_Codes_Array)) {
		$Discount['Amount'] = 0;
		$Discount['Recurring'] = 'No';

		return $Discount;
	}

	foreach ($Discount_Codes_Array as $Discount_Code_Item) {
		if ($Discount_Code_Item['Code'] == $discount_code) {
			if ($Discount_Code_Item['Applicable'] == $Applies_To) {
				$Expires = strtotime($Discount_Code_Item['Expiry']);
				$Currently = time();
				if ($Currently < $Expires) {
					$Discount['Amount'] = $Discount_Code_Item['Amount'];
					$Discount['Recurring'] = $Discount_Code_Item['Recurring'];
					
					return $Discount;
				}
			}
		}
	}

	if (!isset($Discount)) {
		$Discount['Amount'] = 0;
		$Discount['Recurring'] = 'No';

		return $Discount;
	}
}

function EWD_FEUP_Calculate_Level_Payment($level) {
	$Levels_Payment_Array = get_option("EWD_FEUP_Levels_Payment_Array");

	if (!is_array($Levels_Payment_Array)) {return 0;}

	foreach ($Levels_Payment_Array as $Level_Payment_Item) {
		if ($Level_Payment_Item['Level'] == $level) {
			return $Level_Payment_Item['Amount'];
		}
	}

	return 0;
}
?>