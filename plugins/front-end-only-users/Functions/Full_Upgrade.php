<?php 
function EWD_FEUP_Upgrade_To_Full() {
	global $feup_message, $EWD_FEUP_Full_Version;

	$Key = trim($_POST['Key']);
	
	if ($Key == "EWD Trial" and !get_option("EWD_FEUP_Trial_Happening")) {
		$ewd_urp_message = array("Message_Type" => "Update", "Message" => __("Trial successfully started!", 'front-end-only-users'));

		update_option("EWD_FEUP_Trial_Expiry_Time", time() + (7*24*60*60));
		update_option("EWD_FEUP_Trial_Happening", "Yes");
		update_option("EWD_FEUP_Full_Version", "Yes");
		$EWD_FEUP_Full_Version = get_option("EWD_FEUP_Full_Version");

		$Admin_Email = get_option('admin_email');

		$opts = array('http'=>array('method'=>"GET"));
		$context = stream_context_create($opts);
		$Response = unserialize(file_get_contents("http://www.etoilewebdesign.com/UPCP-Key-Check/Register_Trial.php?Plugin=FEUP&Admin_Email=" . $Admin_Email . "&Site=" . get_bloginfo('wpurl'), false, $context));
	}
	elseif ($Key != "EWD Trial") {
		$opts = array('http'=>array('method'=>"GET"));
		$context = stream_context_create($opts);
		$Response = unserialize(file_get_contents("http://www.etoilewebdesign.com/UPCP-Key-Check/EWD_FEUP_KeyCheck.php?Key=" . $Key . "&Site=" . get_bloginfo('wpurl'), false, $context));
		//echo "http://www.etoilewebdesign.com/UPCP-Key-Check/EWD_OTP_KeyCheck.php?Key=" . $Key . "&Site=" . get_bloginfo('wpurl');
		//$Response = file_get_contents("http://www.etoilewebdesign.com/UPCP-Key-Check/KeyCheck.php?Key=" . $Key);
		
		if ($Response['Message_Type'] == "Error") {
			  $feup_message = array("Message_Type" => "Error", "Message" => $Response['Message']);
		}
		else {
				$feup_message = array("Message_Type" => "Update", "Message" => $Response['Message']);
				update_option("EWD_FEUP_Trial_Happening", "No");
				delete_option("EWD_FEUP_Trial_Expiry_Time");
				update_option("EWD_FEUP_Full_Version", "Yes");
				$EWD_FEUP_Full_Version = get_option("EWD_FEUP_Full_Version");
		}
	}
}

 ?>
