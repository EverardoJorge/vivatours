<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the login-logout toggle shortcode*/
function Insert_Login_Logout_Toggle($atts) {
	global $user_message, $feup_success;

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Time = time();
	$feup_Label_Logout =  get_option("EWD_FEUP_Label_Logout");
	if ($feup_Label_Logout == "") {$feup_Label_Logout = __("Logout", 'front-end-only-users');}
	$ReturnString = "";
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				 					'login_redirect_page' => '#',
									'logout_redirect_page' => '#',
									'include_wordpress' => ''),
									$atts
			)
	);
		
	$User = new FEUP_User;
	if (!$User->Is_Logged_In() or (isset($_POST['Login_Submit']) and $$login_redirect_page != '#')) {
		return do_shortcode("[login redirect_page='" . $login_redirect_page . "' include_wordpress='" . $include_wordpress . "']");
	}
	elseif (isset($_POST['Logout_Submit'])) {
		$user_message['Message'] = do_shortcode("[logout redirect_page='" . $logout_redirect_page . "']");
		return do_shortcode("[login redirect_page='" . $login_redirect_page . "' include_wordpress='" . $include_wordpress . "']");
	}
	else {
		$ReturnString .= "<style type='text/css'>";
		$ReturnString .= $Custom_CSS;
	 	$ReturnString .= EWD_FEUP_Add_Modified_Styles();
		
		$ReturnString .= "<div id='ewd-feup-login-form-div'>";
		if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
		$ReturnString .= "<form action='#' method='post' id='ewd-feup-login-form' class='feup-pure-form feup-pure-form-aligned'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='login'>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Logout_Submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Logout_Submit' value='" . $feup_Label_Logout . "'>";
		$ReturnString .= "</div>";
		$ReturnString .= "</form>";
		$ReturnString .= "</div>";
		
		return $ReturnString;
	}
}
add_shortcode("login-logout-toggle", "Insert_Login_Logout_Toggle");

?>
