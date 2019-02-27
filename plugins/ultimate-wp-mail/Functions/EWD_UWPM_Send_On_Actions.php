<?php

function EWD_UWPM_Send_On_User_Registers($user_id) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == 'User_Registers') {EWD_UWPM_Email_User(array('User_ID' => $user_id, 'Email_ID' => $Send_On_Action['Email_ID']));}
	}
}
add_action('user_register', 'EWD_UWPM_Send_On_User_Registers');

function EWD_UWPM_Send_On_User_Profile_Updated($user_id) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == 'User_Profile_Updated') {EWD_UWPM_Email_User(array('User_ID' => $user_id, 'Email_ID' => $Send_On_Action['Email_ID']));}
	}
}
add_action('profile_update', 'EWD_UWPM_Send_On_User_Profile_Updated');

function EWD_UWPM_Send_On_User_Role_Changed($user_id) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == 'User_Role_Changed') {EWD_UWPM_Email_User(array('User_ID' => $user_id, 'Email_ID' => $Send_On_Action['Email_ID']));}
	}
}
add_action('set_user_role', 'EWD_UWPM_Send_On_User_Role_Changed');

function EWD_UWPM_Send_On_User_Password_Reset($Email_Content, $user, $userdata) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == 'User_Password_Reset') {
			$Email_Content['message'] = EWD_UWPM_Email_User(array('User_ID' => $userdata['ID'], 'Email_ID' => $Send_On_Action['Email_ID'], 'Return_Email' => 'Yes'));
			$Email_Content['subject'] = get_the_title($Send_On_Action['Email_ID']);
			$Email_Content['headers'] = array('Content-Type: text/html; charset=UTF-8');
		}
	}

	return $Email_Content;
}
add_filter('password_change_email', 'EWD_UWPM_Send_On_User_Password_Reset');

function EWD_UWPM_Send_On_User_X_Time_Since_Login() {
	global $wpdb;

	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == 'User_X_Time_Since_Login') {
			$Unit_Adjustor = ($Send_On_Action['Interval_Unit'] == 'Weeks' ? 3600 * 24 * 7 : ($Send_On_Action['Interval_Unit'] == 'Days' ? 3600 * 24 : ($Send_On_Action['Interval_Unit'] == 'Hours' ? 3600 : 60)));
			$Seconds_Since_Login = $Unit_Adjustor * $Send_On_Action['Interval_Count'];
	
			$Login_Cutoff = time() - $Seconds_Since_Login;
	
			$Users = $wpdb->get_results(
				"SELECT user_id FROM $wpdb->usermeta 
				WHERE $wpdb->usermeta.meta_key = 'EWD_UWPM_User_Last_Activity'
				AND $wpdb->usermeta.meta_value < $Login_Cutoff
				AND user_id IN (
					SELECT user_id FROM $wpdb->usermeta
					WHERE ($wpdb->usermeta.meta_key = 'EWD_UWPM_Login_Reminder_Sent'
					AND $wpdb->usermeta.meta_value != 'Yes')
				)
				");
	
			foreach ($Users as $User) {
				EWD_UWPM_Email_User(array('User_ID' => $User->user_id, 'Email_ID' => $Send_On_Action['Email_ID']));
				update_usermeta($User->user_id, 'EWD_UWPM_Login_Reminder_Sent', 'Yes');
			}
		}
	}
}

function EWD_UWPM_Send_On_Post_Published($post_id) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == 'Post_Published') {EWD_UWPM_Email_User(array('post_id' => $post_id, 'Email_ID' => $Send_On_Action['Email_ID']));}
	}
}
add_action('publish_post', 'EWD_UWPM_Send_On_Post_Published');

function EWD_UWPM_Send_On_Post_Published_Interest($post_id) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == "Post_Published_Interest") {
			$Categories = wp_get_post_categories($post_id, array('fields' => 'ids'));
			$Params = array(
				'List_ID' => -2,
				'Email_ID' => $Send_On_Action['Email_ID'], 
				'post_id' => $post_id,
				'Interests' => array(
					'Post_Categories' => $Categories,
					'UWPM_Categories' => array(),
					'WC_Categories' => array()
					),
				'WC_Info' => array(
					'Previous_Purchasers' => false,
					'Product_Purchasers' => false,
					'Previous_WC_Products' => '',
					'Category_Purchasers' => false, 
					'Previous_WC_Categories' => ''
				)
			);
	
			EWD_UWPM_Email_User_List($Params);
		}
	}
}
add_action('publish_post', 'EWD_UWPM_Send_On_Post_Published_Interest');

function EWD_UWPM_New_Comment_On_Post($comment_ID, $comment_approved) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	if ($comment_approved == 'spam') {return;}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == "New_Comment_On_Post") {
			$Current_Comment = get_comment($comment_ID);
			$All_Comments = get_comments(array('post_id' => $Current_Comment->comment_post_ID));
	
			foreach ($All_Comments as $Comment) {
				if ($Comment->user_id != 0 and $Comment->user_id != $Current_Comment->user_id) {
					EWD_UWPM_Email_User(array('User_ID' => $Comment->user_id, 'Email_ID' => $Send_On_Action['Email_ID'], 'post_id' => $Current_Comment->comment_post_ID));
				}
			}
		}
	}
}
add_action('comment_post', 'EWD_UWPM_New_Comment_On_Post');

function EWD_UWPM_Send_On_WC_X_Time_Since_Cart_Abandoned() {
	global $wpdb;

	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == "WC_X_Time_Since_Cart_Abandoned") {
			$Unit_Adjustor = ($Send_On_Action['Interval_Unit'] == 'Weeks' ? 3600 * 24 * 7 : ($Send_On_Action['Interval_Unit'] == 'Days' ? 3600 * 24 : ($Send_On_Action['Interval_Unit'] == 'Hours' ? 3600 : 60)));
			$Seconds_Since_Activity = $Unit_Adjustor * $Send_On_Action['Interval_Count'] + 300; //Adding 300 just to be sure that the there's been no cart activity in 5 minutes
	
			$Abandoned_Cutoff = time() - $Seconds_Since_Activity;
	
			$Users = $wpdb->get_results(
				"SELECT user_id FROM $wpdb->usermeta 
				WHERE $wpdb->usermeta.meta_key = 'EWD_UWPM_User_Cart_Update_Time'
				AND $wpdb->usermeta.meta_value < $Abandoned_Cutoff
				AND user_id IN (
					SELECT user_id FROM $wpdb->usermeta
					WHERE $wpdb->usermeta.meta_key = 'EWD_UWPM_Abandoned_Cart_Reminder_Sent'
					AND $wpdb->usermeta.meta_value != 'Yes'
					)
				");
	
			foreach ($Users as $User) {
				EWD_UWPM_Email_User(array('User_ID' => $User->user_id, 'Email_ID' => $Send_On_Action['Email_ID']));
				update_usermeta($User->user_id, 'EWD_UWPM_Abandoned_Cart_Reminder_Sent', 'Yes');
			}
		}
	}
}

// X Time after WC Purchase
function EWD_UWPM_Send_On_WC_X_Time_After_Purchase() {
	global $wpdb;

	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == "WC_X_Time_After_Purchase") {
			$Unit_Adjustor = ($Send_On_Action['Interval_Unit'] == 'Weeks' ? 3600 * 24 * 7 : ($Send_On_Action['Interval_Unit'] == 'Days' ? 3600 * 24 : ($Send_On_Action['Interval_Unit'] == 'Hours' ? 3600 : 60)));
			$Seconds_Since_Order = $Unit_Adjustor * $Send_On_Action['Interval_Count'];
	
			$Order_Datetime = date("Y-m-d H:i:s", time() - $Seconds_Since_Order);
	
			$Orders = $wpdb->get_results(
				"SELECT DISTINCT post_id FROM $wpdb->postmeta 
				INNER JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID
				WHERE $wpdb->postmeta.meta_key = 'EWD_UWPM_Emails_Sent'
				AND $wpdb->postmeta.meta_value NOT LIKE '%\"" . $Send_On_Action['Email_ID'] . "\"%' 
				AND $wpdb->posts.post_date < '$Order_Datetime'
				");
	
			foreach ($Orders as $Order) {
				$User_ID = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=%d AND meta_key=%s", $Order->post_id, '_customer_user'));
				if ($User_ID != '' and $User_ID != 0) {
					EWD_UWPM_Email_User(array('User_ID' => $User_ID, 'Email_ID' => $Send_On_Action['Email_ID']));
	
					$Emails_Sent = get_post_meta($Order->ID, 'EWD_UWPM_Emails_Sent');
					
					if (!is_array($Emails_Sent)) {$Emails_Sent = array();}
					$Emails_Sent[] = $Send_On_Action['Email_ID'];
	
					update_post_meta($Order->post_id, 'EWD_UWPM_Emails_Sent', $Emails_Sent);
				}
			}
		}
	}
} 

function EWD_UWPM_WC_Advanced_Send_On_Actions() {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}
	$WC_Advanced_Scheduled_Sends = get_option("EWD_UWPM_WC_Advanced_Scheduled_Sends");
	if (!is_array($WC_Advanced_Scheduled_Sends)) {$WC_Advanced_Scheduled_Sends = array();}

	foreach ($WC_Advanced_Scheduled_Sends as $key => $Advanced_Scheduled_Send) {
		if ($Advanced_Scheduled_Send['Send_Time'] < time()) {
			$Enabled = "No";
			foreach ($Send_On_Actions as $Send_On_Action) {
				if ($Advanced_Scheduled_Send['Send_On_ID'] == $Send_On_Action['Send_On_ID']) {$Enabled = $Send_On_Action['Enabled'];}
			}
			if ($Enabled != "Yes") {continue;}

			if (!isset($Advanced_Scheduled_Send['User_ID']) and !isset($Advanced_Scheduled_Send['Email_Address'])) {EWD_UWPM_Email_All_Users(array('Email_ID' => $Advanced_Scheduled_Send['Email_ID'], 'post_id' => $Advanced_Scheduled_Send['post_id']));}
			elseif (isset($Advanced_Scheduled_Send['User_ID'])) {EWD_UWPM_Email_User(array('Email_ID' => $Advanced_Scheduled_Send['Email_ID'], 'User_ID' => $Advanced_Scheduled_Send['User_ID'], 'post_id' => $Advanced_Scheduled_Send['post_id']));}
			else {EWD_UWPM_Send_Email_To_Non_User(array('Email_ID' => $Advanced_Scheduled_Send['Email_ID'], 'Email_Address' => $Advanced_Scheduled_Send['Email_Address'], 'post_id' => $Advanced_Scheduled_Send['post_id']));}
			unset($WC_Advanced_Scheduled_Sends[$key]);
		}
	}

	update_option("EWD_UWPM_WC_Advanced_Scheduled_Sends", $WC_Advanced_Scheduled_Sends);
}

function EWD_UWPM_Advanced_Send_On_New_Product_Added($post_id) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}
	$WC_Advanced_Scheduled_Sends = get_option("EWD_UWPM_WC_Advanced_Scheduled_Sends");
	if (!is_array($WC_Advanced_Scheduled_Sends)) {$WC_Advanced_Scheduled_Sends = array();}

	$Cat_IDs = array();
	$Categories = wp_get_post_terms($post_id, 'product_cat');
	foreach ($Categories as $Category) {$Cat_IDs[] = $Category->term_id;}

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == 'Product_Added') {
			if ($Send_On_Action['Includes'] == 'Any' or in_array(substr($Send_On_Action['Includes'], 2), $Cat_IDs)) {
				$Unit_Adjustor = $Send_On_Action['Interval_Unit'] == 'Weeks' ? 3600 * 24 * 7 : ($Send_On_Action['Interval_Unit'] == 'Days' ? 3600 * 24 : ($Send_On_Action['Interval_Unit'] == 'Hours' ? 3600 : 60));
				$Seconds_Delay = $Unit_Adjustor * $Send_On_Action['Interval_Count'];
				$Send_Time = time() + $Seconds_Delay;

				$Send_On = array(
					'Send_Time' => $Send_Time,
					'Send_On_ID' => $Send_On_Action['Send_On_ID'],
					'Email_ID' => $Send_On_Action['Email_ID'],
					'post_id' => $post_id
				);

				$WC_Advanced_Scheduled_Sends[] = $Send_On;
			}
		}
	}

	update_option("EWD_UWPM_WC_Advanced_Scheduled_Sends", $WC_Advanced_Scheduled_Sends);
}
add_action('publish_product', 'EWD_UWPM_Advanced_Send_On_New_Product_Added');

function EWD_UWPM_Advanced_Send_On_Product_Purchased($post_id) {
	$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
	if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}
	$WC_Advanced_Scheduled_Sends = get_option("EWD_UWPM_WC_Advanced_Scheduled_Sends");
	if (!is_array($WC_Advanced_Scheduled_Sends)) {$WC_Advanced_Scheduled_Sends = array();}

	if (!function_exists('wc_get_order')) {return;}

	$Order = wc_get_order($post_id);
	$Products = $Order->get_items();

	$Prod_IDs = array();
	$Cat_IDs = array();
	foreach ($Products as $Product) {
		$Prod_IDs[] = $Product->get_product_id();
		$Categories = wp_get_post_terms($Product->get_product_id(), 'product_cat');
		foreach ($Categories as $Category) {$All_Cat_IDs[] = $Category->term_id;}
	}
	$Cat_IDs = array_unique($All_Cat_IDs);

	$User_ID = get_post_meta($post_id, '_customer_user', true);
	$Email_Address = get_post_meta($post_id, '_billing_email', true);

	foreach ($Send_On_Actions as $Send_On_Action) {
		if ($Send_On_Action['Action_Type'] == 'Product_Purchased' and $Send_On_Action['Enabled'] == "Yes") {
			if ($Send_On_Action['Includes'] == 'Any' or (substr($Send_On_Action['Includes'], 0, 1) == 'C' and in_array(substr($Send_On_Action['Includes'], 2), $Cat_IDs)) or (substr($Send_On_Action['Includes'], 0, 1) and in_array(substr($Send_On_Action['Includes'], 2), $Prod_IDs))) {
				$Params = array(
					'Send_On_ID' => $Send_On_Action['Send_On_ID'],
					'Email_ID' => $Send_On_Action['Email_ID'],
					'post_id' => $post_id
				);

				if ($User_ID) {
					$Params['User_ID'] = $User_ID;
					EWD_UWPM_Email_User($Params);
				}
				else {
					$Params['Email_Address'] = $Email_Address;
					EWD_UWPM_Send_Email_To_Non_User($Params);
				}
			}
		}
	}
}
add_action('woocommerce_new_order', 'EWD_UWPM_Advanced_Send_On_Product_Purchased');
?>