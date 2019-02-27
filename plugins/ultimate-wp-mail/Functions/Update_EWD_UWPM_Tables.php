<?php
function Update_EWD_UWPM_Tables() {
	/* Add in the required globals to be able to create the tables */
  	global $wpdb;
	global $ewd_uwpm_email_send_events, $ewd_uwpm_email_open_events, $ewd_uwpm_email_links_clicked_events, $ewd_uwpm_email_only_users;
		
	/* Create the Email Send Events data table */  
   	$sql = "CREATE TABLE $ewd_uwpm_email_send_events (
  		Email_Send_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		Email_ID mediumint(9) DEFAULT 0 NOT NULL,
		User_ID mediumint(9) DEFAULT 0 NOT NULL,
		Event_ID mediumint(9) DEFAULT 0 NOT NULL,
    Email_Unique_Identifier text DEFAULT '' NOT NULL,
		Email_Sent_Datetime datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (Email_Send_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);

   	/* Create the Email Open Events data table */  
   	$sql = "CREATE TABLE $ewd_uwpm_email_open_events (
  	Email_Open_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		Email_Send_ID mediumint(9) DEFAULT 0 NOT NULL,
		Email_Opened text DEFAULT '' NOT NULL,
		Email_Opened_Datetime datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (Email_Open_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);

   	/* Create the Email Links Clicked Events data table */  
   	$sql = "CREATE TABLE $ewd_uwpm_email_links_clicked_events (
  	Email_Link_Clicked_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		Email_Send_ID mediumint(9) DEFAULT 0 NOT NULL,
		Link_URL text DEFAULT '' NOT NULL,
		Link_Click_Count text DEFAULT '' NOT NULL,
		Link_Clicked_Datetime datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (Email_Link_Clicked_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
				
    $sql = "CREATE TABLE $ewd_uwpm_email_only_users (
    EOU_ID mediumint(9) NOT NULL AUTO_INCREMENT,
    Email_Address text DEFAULT '' NOT NULL,
    EOU_Date_Added datetime DEFAULT '0000-00-00 00:00:00' NULL,
      UNIQUE KEY id (EOU_ID)
      )
    DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
?>
