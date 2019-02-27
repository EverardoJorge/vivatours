<?php
function Handle_EWD_UWPM_Tracking() {
	global $ewd_uwpm_message;
	if (isset($_GET['ewd_upwm_id'])) {
		if (isset($_GET['ewd_upwm_link_url'])) {EWD_UWPM_Track_Links_Clicked();}
		else {EWD_UWPM_Track_Opens();}

		add_action('shutdown', 'EWD_UWPM_clean_ob_end', 1);
	}
}

function EWD_UWPM_Track_Opens() {
	global $wpdb;
	global $ewd_uwpm_email_send_events, $ewd_uwpm_email_open_events;

	header("Content-Type: image/png");
	readfile(EWD_UWPM_CD_PLUGIN_PATH . "images/transparent.png");
	
	$Email_Unique_Identifier = sanitize_text_field($_GET['ewd_upwm_id']);

	$Email_Send_ID = $wpdb->get_var($wpdb->prepare("SELECT Email_Send_ID FROM $ewd_uwpm_email_send_events WHERE Email_Unique_Identifier=%s", $Email_Unique_Identifier));
	
	if ($Email_Send_ID == 0) {return;}

	$wpdb->get_row($wpdb->prepare("SELECT Email_Send_ID FROM $ewd_uwpm_email_open_events WHERE Email_Send_ID=%d", $Email_Send_ID));

	if ($wpdb->num_rows == 0) {
		$Email_Opened_Datetime = date("Y-m-d H:i:s");

		$wpdb->insert(
			$ewd_uwpm_email_open_events,
			array(
				'Email_Send_ID' => $Email_Send_ID,
				'Email_Opened' => 'Yes',
				'Email_Opened_Datetime' => $Email_Opened_Datetime
			)
		);
	}
}

function EWD_UWPM_Track_Links_Clicked() {
	global $wpdb;
	global $ewd_uwpm_email_send_events, $ewd_uwpm_email_links_clicked_events;
	
	$Email_Unique_Identifier = sanitize_text_field($_GET['ewd_upwm_id']);
	$Link_URL = sanitize_url($_GET['ewd_upwm_link_url']);

	$Email_Send_ID = $wpdb->get_var($wpdb->prepare("SELECT Email_Send_ID FROM $ewd_uwpm_email_send_events WHERE Email_Unique_Identifier=%s", $Email_Unique_Identifier));
	
	if ($Email_Send_ID == 0) {
		header("location:" . $Link_URL);
	}

	$Email_Link_Clicked = $wpdb->get_row($wpdb->prepare("SELECT Email_Send_ID, Link_Click_Count FROM $ewd_uwpm_email_open_events WHERE Email_Send_ID=%d AND Link_URL=%s", $Email_Send_ID, $Link_URL));

	if ($wpdb->num_rows == 0) {
		$Link_Clicked_Datetime = date("Y-m-d H:i:s");

		$wpdb->insert(
			$ewd_uwpm_email_links_clicked_events,
			array(
				'Email_Send_ID' => $Email_Send_ID,
				'Link_URL' => $Link_URL,
				'Link_Click_Count' => 1,
				'Link_Clicked_Datetime' => $Link_Clicked_Datetime
			)
		);
	}
	else {
		$wpdb->update(
			$ewd_uwpm_email_links_clicked_events,
			array(
				'Email_Opened' => 'Yes',
				'Link_Click_Count' => $Email_Link_Clicked->Link_Click_Count + 1
			),
			array(
				'Email_Link_Clicked_ID' => $Email_Link_Clicked->Email_Link_Clicked_ID
			)
		);
	}

	header("location:" . $Link_URL);
}
?>