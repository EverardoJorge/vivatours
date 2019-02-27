<?php
/* Creates the admin page, and fills it in based on whether the user is looking at
*  the overview page or an individual item is being edited */
function EWD_UWPM_Output_Options() {
	global $wpdb;
	global $ewd_uwpm_email_send_events, $ewd_uwpm_email_open_events, $ewd_uwpm_email_links_clicked_events;

	if (!isset($_GET['DisplayPage'])) {$_GET['DisplayPage'] = "";}

	include( plugin_dir_path( __FILE__ ) . '../html/AdminHeader.php');
	if ($_GET['DisplayPage'] == 'Dashboard' or $_GET['DisplayPage'] == "") {include( plugin_dir_path( __FILE__ ) . '../html/DashboardPage.php');}
	if ($_GET['DisplayPage'] == 'Lists') {include( plugin_dir_path( __FILE__ ) . '../html/ListsPage.php');}
	if ($_GET['DisplayPage'] == 'UserStats') {include( plugin_dir_path( __FILE__ ) . '../html/UserStatsPage.php');}
	if ($_GET['DisplayPage'] == 'UserStatsDetails') {include( plugin_dir_path( __FILE__ ) . '../html/UserStatsDetailsPage.php');}
	if ($_GET['DisplayPage'] == 'Options') {include( plugin_dir_path( __FILE__ ) . '../html/OptionsPage.php');}
	include( plugin_dir_path( __FILE__ ) . '../html/AdminFooter.php');
}
?>