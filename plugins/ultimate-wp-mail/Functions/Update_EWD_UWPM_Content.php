<?php
/* This file is the action handler. The appropriate function is then called based 
*  on the action that's been selected by the user. The functions themselves are all
* stored either in Prepare_Data_For_Insertion.php or Update_Admin_Databases.php */
		
function Update_EWD_UWPM_Content() {
	global $ewd_uwpm_message;
	if (isset($_GET['Action'])) {
		switch ($_GET['Action']) {
			case "EWD_UWPM_UpdateOptions":
       			$ewd_uwpm_message = EWD_UWPM_UpdateOptions();
				break;
			case "EWD_UWPM_UpdateLists":
       			$ewd_uwpm_message = EWD_UWPM_UpdateLists();
				break;
			default:
				$ewd_uwpm_message = __("The form has not worked correctly. Please contact the plugin developer.", 'ultimate-reviews');
				break;
		}
	}
}

?>