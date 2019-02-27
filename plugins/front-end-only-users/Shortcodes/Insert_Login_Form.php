<?php 
function EWD_FEUP_Login_Form_Block() {
    if(function_exists('render_block_core_block')){  
		wp_register_script( 'ewd-feup-blocks-js', plugins_url( '../blocks/ewd-feup-blocks.js', __FILE__ ), array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ) );
		wp_register_style( 'ewd-feup-blocks-css', plugins_url( '../blocks/ewd-feup-blocks.css', __FILE__ ), array( 'wp-edit-blocks' ), filemtime( plugin_dir_path( __FILE__ ) . '../blocks/ewd-feup-blocks.css' ) );
		register_block_type( 'front-end-only-users/ewd-feup-login-form-block', array(
			'attributes'      => array(
				'redirect_page' => array(
					'type' => 'string',
				),
			),
			'editor_script'   => 'ewd-feup-blocks-js',
			'editor_style'  => 'ewd-feup-blocks-css',
			'render_callback' => 'Insert_Login_Form',
		) );
	}
	// Define our shortcode, too, using the same render function as the block.
	add_shortcode("login", "Insert_Login_Form");
}
add_action( 'init', 'EWD_FEUP_Login_Form_Block' );

/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Login_Form($atts) {
		global $user_message, $feup_success;
		// Include the required global variables, and create a few new ones
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
		$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
		$Create_WordPress_Users = get_option("EWD_FEUP_Create_WordPress_Users");
		$Login_Options = get_option("EWD_FEUP_Login_Options");
		if (!is_array($Login_Options)) {$Login_Options = array();}
		$Time = time();

		$Payment_Frequency = get_option("EWD_FEUP_Payment_Frequency");
		$Payment_Types = get_option("EWD_FEUP_Payment_Types");
		$Membership_Cost = get_option("EWD_FEUP_Membership_Cost");
		$Levels_Payment_Array = get_option("EWD_FEUP_Levels_Payment_Array");
		$feup_Label_Login =  get_option("EWD_FEUP_Label_Login");
		if ($feup_Label_Login == "") {$feup_Label_Login = __("Login", 'front-end-only-users');}
		$feup_Label_Email =  get_option("EWD_FEUP_Label_Email");
		if ($feup_Label_Email == "") {$feup_Label_Email = __("Email", 'front-end-only-users');}
		$feup_Label_Username =  get_option("EWD_FEUP_Label_Username");
		if ($feup_Label_Username == "") {$feup_Label_Username = __("Username", 'front-end-only-users');}
		$feup_Label_Username_Placeholder = get_option("EWD_FEUP_Label_Username_Placeholder");
		if ($feup_Label_Username_Placeholder == "") {$feup_Label_Username_Placeholder = __("Username", 'front-end-only-users');}
		$feup_Label_Password =  get_option("EWD_FEUP_Label_Password");
		if ($feup_Label_Password == "") {$feup_Label_Password = __("Password", 'front-end-only-users');}
		
		$ReturnString = "";
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
					 	'redirect_page' => '#',
					 	'include_wordpress' => '',
						'redirect_field' => '',
						'redirect_array_string' => '',
						'submit_text' => __('Login', 'front-end-only-users')),
						$atts
				)
		);
		
		if ($include_wordpress == "") {$include_wordpress = $Create_WordPress_Users;}

		if (in_array("Facebook", $Login_Options) or in_array("Twitter", $Login_Options)) {
			$Logged_In_User = EWD_FEUP_Get_External_Login();
			if ($Logged_In_User['Login_Status'] == "Twitter") {$Third_Party_Login = EWD_FEUP_Process_Twitter_Login($Logged_In_User['Twitter_ID']);}
			if ($Logged_In_User['Login_Status'] == "Facebook") {$Third_Party_Login = EWD_FEUP_Process_Facebook_Login($Logged_In_User['Facebook_Profile']['id']);}

			if ($Third_Party_Login['Status'] == "Success") {
				$feup_success = true;
			}

			$user_message['Message'] = $Third_Party_Login['Message'];
		}
		
		$ReturnString .= "<style type='text/css'>";
		$ReturnString .= $Custom_CSS;
		$ReturnString .= EWD_FEUP_Add_Modified_Styles();
		$ReturnString .= "</style>";

		if (isset($_POST['Payment_Required']) and $_POST['Payment_Required'] == "Yes") {
			if (($Payment_Types == "Membership" and is_numeric($Membership_Cost) and $Membership_Cost != "") or
				($Payment_Types == "Levels" and sizeof($Levels_Payment_Array) >0 )) {

				$ReturnString .= do_shortcode("[account-payment]");
				return $ReturnString;
			}
		}
		
		if ($feup_success and $redirect_field != "") {$redirect_page = Determine_Redirect_Page($redirect_field, $redirect_array_string, $redirect_page);}

		// if there is no redirect page, don't try to redirect
		if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
		
		$ReturnString .= "<div id='ewd-feup-login' class='ewd-feup-login-form-div' class='ewd-feup-form-div'>";
		if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
		if (strpos($user_message['Message'], "Payment required.") !== false) {$ReturnString .= "</div>"; return $ReturnString;} //Payment required
		if (in_array("Facebook", $Login_Options) or in_array("Twitter", $Login_Options)) {
			$ReturnString .= "<div class='ewd-feup-social-login-options'>";
			$ReturnString .= "<div class='ewd-feup-social-login-instructions'>";
			$ReturnString .= __("If you signed up using an external login, you can use the links below to log in.", 'front-end-only-users');
			$ReturnString .= "</div>";
			if (in_array("Facebook", $Login_Options)) {
				$ReturnString .= "<div class='ewd-feup-login-option' id='ewd-feup-facebook-login'>";
				$ReturnString .= "<a href='" . $Logged_In_User['Facebook_Login_URL'] . "'><img src='" . EWD_FEUP_CD_PLUGIN_URL . "images/fb_login.png'></a>";
				$ReturnString .= "</div>";
			}
			if (in_array("Twitter", $Login_Options)) {
				$ReturnString .= "<div class='ewd-feup-login-option' id='ewd-feup-twitter-login'>";
				$ReturnString .= "<a href='" . $Logged_In_User['Twitter_Login_URL'] . "'><img src='" . EWD_FEUP_CD_PLUGIN_URL . "images/sign-in-with-twitter.png'></a>";
				$ReturnString .= "</div>";
			}
			$ReturnString .= "</div>";
		}
		$ReturnString .= "<form action='#' method='post' id='ewd-feup-login-form' class='pure-form pure-form-aligned feup-pure-form-aligned'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='login'>";
		if ($include_wordpress == "Yes") {$ReturnString .= "<input type='hidden' name='ewd-feup-include-wordpress' value='Yes' />";}
		elseif ($include_wordpress == "Only") {$ReturnString .= "<input type='hidden' name='ewd-feup-include-wordpress' value='Only' />";}
		else {$ReturnString .= "<input type='hidden' name='ewd-feup-include-wordpress' value='No' />";}
		$ReturnString .= "<div class='feup-pure-control-group'>";
		if($Username_Is_Email == "Yes") {
			$ReturnString .= "<label for='Username' id='ewd-feup-login-username-div' class='ewd-feup-field-label ewd-feup-login-label'>" . $feup_Label_Email . ": </label>";
			$ReturnString .= "<input type='text' id='ewd-feup-login-email-input' class='ewd-feup-text-input ewd-feup-login-field' name='Username' placeholder='" . $feup_Label_Email . "...'>";
		} else {
		$ReturnString .= "<label for='Username' id='ewd-feup-login-username-div' class='ewd-feup-field-label ewd-feup-login-label'>" . $feup_Label_Username . ": </label>";
		$ReturnString .= "<input type='text' class='ewd-feup-text-input ewd-feup-login-field' name='Username' placeholder='" . $feup_Label_Username_Placeholder . "...'>";
		}
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Password' id='ewd-feup-login-password-div' class='ewd-feup-field-label ewd-feup-login-label'>" . $feup_Label_Password . ": </label>";
		$ReturnString .= "<input type='password' class='ewd-feup-text-input ewd-feup-login-field' name='User_Password'>";
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Submit'></label><input type='submit' class='ewd-feup-submit ewd-feup-login-submit feup-pure-button feup-pure-button-primary' name='Login_Submit' value='" . $feup_Label_Login . "'>";
		$ReturnString .= "</div>";
		$ReturnString .= "</form>";

		$ReturnString .= "</div>";
		
		return $ReturnString;
}

function EWD_FEUP_Get_External_Login() {
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	
	$Login_Options = get_option("EWD_FEUP_Login_Options");
	if (!is_array($Login_Options)) {$Login_Options = array();}

	$Permalink = get_the_permalink();
	if (strpos($Permalink, "?") !== false) {$PageLink = $Permalink . "&";}
	else {$PageLink = $Permalink . "?";}

	$facebook = EWD_FEUP_Facebook_Config();
	$fbuser = $facebook->getUser();
	$fbPermissions = 'public_profile';  //Required facebook permissions
	if ($Username_Is_Email == "Yes") {$fbPermissions .= ",email";}

	if (isset($_GET['Run_Login']) and $_GET['Run_Login'] == "Twitter" or isset($_GET['oauth_token'])) {EWD_FEUP_Twitter_Login($PageLink);}

	if ($fbuser and (!array_key_exists('Logout',$_GET) or $_GET['Logout'] != "Facebook")) {
		$Facebook_Logged_In = true;

		$user_profile = $facebook->api('/me?fields=id,name,email,gender,locale,picture');;
		$Logged_In_User['Facebook_Profile'] = $user_profile;
		$Logged_In_User['Facebook_Output'] = "<a href='" . $PageLink . "Logout=Facebook' >" . __("Logout", 'front-end-only-users') . "</a>";
	}
	elseif (in_array("Facebook", $Login_Options)) {
		$LoginURL = $facebook->getLoginUrl(array('redirect_uri'=>$Permalink,'scope'=>$fbPermissions));
		$Logged_In_User['Facebook_Login_URL'] = $LoginURL;
	}

	if (isset($_COOKIE['EWD_FEUP_status']) and $_COOKIE['EWD_FEUP_status'] == 'verified'  and (!array_key_exists('Logout',$_GET) or $_GET['Logout'] != "Twitter")) {
		$Twitter_Logged_In = true;

		$request_vars = unserialize($_COOKIE['EWD_FEUP_request_vars']);
		$Logged_In_User['Twitter_ID'] = $request_vars['user_id'];
		$Logged_In_User['Twitter_Output'] = "<a href='" . $PageLink . "Logout=Twitter' >" . __("Logout", 'front-end-only-users') . "</a>";
	}
	elseif (in_array("Twitter", $Login_Options)) {
		$Logged_In_User['Twitter_Login_URL'] = $PageLink . "Run_Login=Twitter";
	}

	if (array_key_exists('Logout',$_GET)) {
		if ($_GET['Logout'] != "Facebook") {$facebook->destroySession();}
		elseif ($_GET['Logout'] != "Twitter") {EWD_FEUP_Erase_Twitter_Data();}
	}
if (!isset($Twitter_Logged_In)) { $Twitter_Logged_In = ""; }
if (!isset($Facebook_Logged_In)) { $Facebook_Logged_In = ""; }
	if (in_array("Twitter", $Login_Options) and $Twitter_Logged_In) {
		$Logged_In_User['Login_Status'] = "Twitter";
	}
	elseif (in_array("Facebook", $Login_Options) and $Facebook_Logged_In) {
		$Logged_In_User['Login_Status'] = "Facebook";
	}
	else {
		$Logged_In_User['Login_Status'] = "None";
	}

	return $Logged_In_User;
}
?>
