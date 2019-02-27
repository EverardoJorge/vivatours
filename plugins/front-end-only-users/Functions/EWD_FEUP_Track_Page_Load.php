<?php
add_action('wp', 'EWD_FEUP_Track_Page_Load');
function EWD_FEUP_Track_Page_Load() {
	global $wp;
	global $wpdb;
	global $ewd_feup_user_table_name;
	
	if (get_option("EWD_FEUP_Track_Events") != "Yes") {return;}

	$CheckCookie = CheckLoginCookie();
	if ($CheckCookie['Username'] == "") {return;}

	$User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));

	$Event_Type = "Page Load";

	$ID = get_the_ID();
	$Title = get_the_title();
	$URL = get_permalink();

	Add_User_Event($User_ID, $Event_Type, $URL, $ID, $Title);
}

?>