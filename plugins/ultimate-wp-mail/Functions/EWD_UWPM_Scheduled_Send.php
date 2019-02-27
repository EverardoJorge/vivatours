<?php
function EWD_UWPM_Schedule_Email_Send($Email_ID, $Send_Time, $Send_Type, $Target_ID, $Params = array()) {
	$Scheduled_Emails = get_option("EWD_UWPM_Scheduled_Emails");
	if (!is_array($Scheduled_Emails)) {$Scheduled_Emails = array();}

	$Timezone = @date_default_timezone_get();
	$Offset = timezone_offset_get($Timezone, time());

	$Unix_Send_Time = $Offset + strtotime($Send_Time);

	$Email = array(
		'Email_ID' => $Email_ID,
		'Send_Time' => $Unix_Send_Time,
		'Send_Type' => $Send_Type,
		'Target_ID' => $Target_ID,
		'Params' => $Params
	);

	$Scheduled_Emails[] = $Email;

	update_option("EWD_UWPM_Scheduled_Emails", $Scheduled_Emails);

	echo __("Email has been scheduled to send at ", 'ultimate-wp-mail') . $Send_Time . "(" . $Timezone . __(" server timezone", 'ultimate-wp-mail') . ")";
}

function EWD_UWPM_Send_Scheduled_Emails() {
	$Scheduled_Emails = get_option("EWD_UWPM_Scheduled_Emails");

	EWD_UWPM_Send_Email_Reminders($Scheduled_Emails);
	update_option("EWD_UWPM_Last_Scheduled_Emails_Call", time());
}

function EWD_UWPM_Send_Email_Reminders($Scheduled_Emails) {
	if (!is_array($Scheduled_Emails)) {$Scheduled_Emails = array();}
	
	$Current_Time = time();

	foreach ($Scheduled_Emails as $Key => $Email) {
		if ($Email['Send_Time'] < $Current_Time) {
			if ($Email['Send_Type'] == "All") {EWD_UWPM_Email_All_Users(array('Email_ID' => $Email['Email_ID']));}
			elseif ($Email['Send_Type'] == "List") {EWD_UWPM_Email_User_List(array('List_ID' => $Email['Target_ID'], 'Email_ID' => $Email['Email_ID'], 'Interests' => $Params['Interests'], 'WC_Info' => $Params['WC_Info']));}
			else {EWD_UWPM_Email_User(array('User_ID' => $Email['Target_ID'], 'Email_ID' => $Email['Email_ID']));}

			unset($Scheduled_Emails[$Key]);
		}
	}

	update_option("EWD_UWPM_Scheduled_Emails", $Scheduled_Emails);
}
?>