<?php
function EWD_UWPM_Return_Email_Preview() {
	$Email_Content = urldecode(stripslashes($_POST['Email_Content']));
	$Email_ID = $_POST['Email_ID'];

	$Email_Unique_Identifier = EWD_UWPM_Random_String(20);

	$User = get_user_by('id', get_current_user_id());
	//$Email_Content = EWD_UWPM_Replace_Classes(EWD_UWPM_Replace_Variables($Email_Content, $Email_Unique_Identifier, $User), $Email_ID);
	$Email_Content = EWD_UWPM_Replace_Classes($Email_Content, $Email_ID);

	echo $Email_Content;

	die();
}
add_action('wp_ajax_ewd_uwpm_ajax_preview_email', 'EWD_UWPM_Return_Email_Preview');

function EWD_UWPM_Send_Test_Email_AJAX() {
	$Email_Address = $_POST['Email_Address'];
	$Email_Title = $_POST['Email_Title'];
	$Email_Content = urldecode($_POST['Email_Content']);
	$Email_ID = $_POST['Email_ID'];
	
	echo EWD_UWPM_Send_Test_Email($Email_Address, $Email_Title, $Email_Content, $Email_ID);

	die();
}
add_action('wp_ajax_ewd_uwpm_send_test_email', 'EWD_UWPM_Send_Test_Email_AJAX');

function EWD_UWPM_Email_All_Users_AJAX() {
	if ($_POST['Email_ID'] != '') {$Params['Email_ID'] = $_POST['Email_ID'];}
	if ($_POST['Email_Title'] != '') {$Params['Email_Title'] = $_POST['Email_Title'];}
	if ($_POST['Email_Content'] != '') {$Params['Email_Content'] = urldecode($_POST['Email_Content']);}
	$Send_Time = $_POST['Send_Time'];

	if ($Send_Time == 'Now') {echo EWD_UWPM_Email_All_Users($Params);}
	else {EWD_UWPM_Schedule_Email_Send($Params['Email_ID'], $Send_Time, "All");}

	die();
}
add_action('wp_ajax_ewd_uwpm_email_all_users', 'EWD_UWPM_Email_All_Users_AJAX');

function EWD_UWPM_Email_User_List_AJAX() {
	if ($_POST['Email_ID'] != '') {$Params['Email_ID'] = $_POST['Email_ID'];}
	if ($_POST['Email_Title'] != '') {$Params['Email_Title'] = $_POST['Email_Title'];}
	if ($_POST['Email_Content'] != '') {$Params['Email_Content'] = urldecode($_POST['Email_Content']);}
	$Params['List_ID'] = $_POST['List_ID'];
	$Send_Time = $_POST['Send_Time'];

	$Params['Interests']['Post_Categories'] = explode(",", $_POST['Post_Categories']);
	$Params['Interests']['UWPM_Categories'] = explode(",", $_POST['UWPM_Categories']);
	$Params['Interests']['WC_Categories'] = explode(",", $_POST['WC_Categories']);

	$Params['WC_Info']['Previous_Purchasers'] = $_POST['Previous_Purchasers'];
	$Params['WC_Info']['Product_Purchasers'] = $_POST['Product_Purchasers'];
	$Params['WC_Info']['Previous_WC_Products'] = $_POST['Previous_WC_Products'];
	$Params['WC_Info']['Category_Purchasers'] = $_POST['Category_Purchasers'];
	$Params['WC_Info']['Previous_WC_Categories'] = $_POST['Previous_WC_Categories'];

	if ($Send_Time == 'Now') {echo EWD_UWPM_Email_User_List($Params);}
	else {EWD_UWPM_Schedule_Email_Send($Params['Email_ID'], $Send_Time, "List", $Params['List_ID'], $Params);}

	die();
}
add_action('wp_ajax_ewd_uwpm_email_user_list', 'EWD_UWPM_Email_User_List_AJAX');

function EWD_UWPM_Email_Specific_User_AJAX() {
	if ($_POST['Email_ID'] != '') {$Params['Email_ID'] = $_POST['Email_ID'];}
	if ($_POST['Email_Title'] != '') {$Params['Email_Title'] = $_POST['Email_Title'];}
	if ($_POST['Email_Content'] != '') {$Params['Email_Content'] = urldecode($_POST['Email_Content']);}
	if ($_POST['User_ID'] != '') {$Params['User_ID'] = $_POST['User_ID'];}
	$Send_Time = $_POST['Send_Time'];

	if ($Send_Time == 'Now') {echo EWD_UWPM_Email_User($Params);}
	else {EWD_UWPM_Schedule_Email_Send($Params['Email_ID'], $Send_Time, "User", $Params['User_ID']);}

	die();
}
add_action('wp_ajax_ewd_uwpm_email_specific_user', 'EWD_UWPM_Email_Specific_User_AJAX');

function EWD_UWPM_User_Interests_AJAX() {
	$Post_Categories = explode(",", $_POST['Post_Categories']);
	$UWPM_Categories = explode(",", $_POST['UWPM_Categories']);
	$WC_Categories = explode(",", $_POST['WC_Categories']);

	$Possible_Post_Categories = explode(",", $_POST['Possible_Post_Categories']);
	$Possible_UWPM_Categories = explode(",", $_POST['Possible_UWPM_Categories']);
	$Possible_WC_Categories = explode(",", $_POST['Possible_WC_Categories']);

	$User_ID = get_current_user_id();

	echo EWD_UWPM_Change_User_Interests($User_ID, $Post_Categories, $Possible_Post_Categories, $UWPM_Categories, $Possible_UWPM_Categories, $WC_Categories, $Possible_WC_Categories);

	die();
}
add_action('wp_ajax_ewd_uwpm_interests_sign_up', 'EWD_UWPM_User_Interests_AJAX');

//REVIEW ASK POP-UP
function EWD_UWPM_Hide_Review_Ask(){   
    $Ask_Review_Date = sanitize_text_field($_POST['Ask_Review_Date']);

    if (get_option('EWD_UWPM_Ask_Review_Date') < time()+3600*24*$Ask_Review_Date) {
    	update_option('EWD_UWPM_Ask_Review_Date', time()+3600*24*$Ask_Review_Date);
    }

    die();
}
add_action('wp_ajax_ewd_uwpm_hide_review_ask','EWD_UWPM_Hide_Review_Ask');

function EWD_UWPM_Send_Feedback() {   
    $headers = 'Content-type: text/html;charset=utf-8' . "\r\n";  
    $Feedback = sanitize_text_field($_POST['Feedback']);
    $Feedback .= '<br /><br />Email Address: ';
    $Feedback .= sanitize_text_field($_POST['EmailAddress']);

    wp_mail('contact@etoilewebdesign.com', 'UWPM Feedback - Dashboard Form', $Feedback, $headers);

    die();
}
add_action('wp_ajax_ewd_uwpm_send_feedback','EWD_UWPM_Send_Feedback');




