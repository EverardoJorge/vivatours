<?php

function Update_EWD_UWPM_Front_End_Actions() {
global $ewd_uwpm_message;
if (isset($_GET['Action'])) {
		switch ($_GET['Action']) {
			case "EWD_UWPM_Unsubscribe":
       			$ewd_uwpm_message = EWD_UWPM_Unsubscribe();
				break;
			default:
				$ewd_uwpm_message = __("The form has not worked correctly. Please contact the plugin developer.", 'ultimate-reviews');
				break;
		}
	}
}

?>