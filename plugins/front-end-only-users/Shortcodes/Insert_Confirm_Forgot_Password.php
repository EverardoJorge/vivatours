<?php
function EWD_FEUP_Confirm_Forgot_Password_Block() {
    if(function_exists('render_block_core_block')){  
		wp_register_script( 'ewd-feup-blocks-js', plugins_url( '../blocks/ewd-feup-blocks.js', __FILE__ ), array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ) );
		wp_register_style( 'ewd-feup-blocks-css', plugins_url( '../blocks/ewd-feup-blocks.css', __FILE__ ), array( 'wp-edit-blocks' ), filemtime( plugin_dir_path( __FILE__ ) . '../blocks/ewd-feup-blocks.css' ) );
		register_block_type( 'front-end-only-users/ewd-feup-confirm-forgot-password-block', array(
			'attributes'      => array(
				'redirect_page' => array(
					'type' => 'string',
				),
				'login_page' => array(
					'type' => 'string',
				),
			),
			'editor_script'   => 'ewd-feup-blocks-js',
			'editor_style'  => 'ewd-feup-blocks-css',
			'render_callback' => 'Insert_Confirm_Forgot_Password',
		) );
	}
	// Define our shortcode, too, using the same render function as the block.
	add_shortcode("confirm-forgot-password", "Insert_Confirm_Forgot_Password");
}
add_action( 'init', 'EWD_FEUP_Confirm_Forgot_Password_Block' );

function Insert_Confirm_Forgot_Password($atts) {
	global $wpdb, $user_message, $feup_success;
	global $ewd_feup_user_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");	
	$Create_WordPress_Users = get_option("EWD_FEUP_Create_WordPress_Users");

	$CheckCookie = CheckLoginCookie();
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Time = time();
	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
		
	$ReturnString = "";
		
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'redirect_page' => '#',
				'include_wordpress' => '',
				'login_page' => '',
				'submit_text' => __('Change password', 'front-end-only-users')),
			$atts
		)
	);

	$feup_Label_Change_Password =  get_option("EWD_FEUP_Label_Change_Password");
	if ($feup_Label_Change_Password == "") {$feup_Label_Change_Password = $submit_text;}
	$feup_Label_Email =  get_option("EWD_FEUP_Label_Email");
	if ($feup_Label_Email == "") {$feup_Label_Email = __("Email", 'front-end-only-users');}	
	$feup_Label_Reset_Code =  get_option("EWD_FEUP_Label_Reset_Code");
	if ($feup_Label_Reset_Code == "") {$feup_Label_Reset_Code = __("Reset Code", 'front-end-only-users');}
	$feup_Label_Password =  get_option("EWD_FEUP_Label_Password");
	if ($feup_Label_Password == "") {$feup_Label_Password = __("Password", 'front-end-only-users');}
	$feup_Label_Repeat_Password = get_option("EWD_FEUP_Label_Repeat_Password");
	if ($feup_Label_Repeat_Password == "") {$feup_Label_Repeat_Password = __("Repeat Password", 'front-end-only-users');}

	if ($include_wordpress == "") {$include_wordpress = $Create_WordPress_Users;}

	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	 $ReturnString .= EWD_FEUP_Add_Modified_Styles();
	
		
	if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}

	$ReturnString .= "<div id='ewd-feup-edit-profile-form-div'>";
	if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
	if (!isset($_GET['add'])) { $_GET['add'] = ""; }
	if (!isset($_GET['rc'])) { $_GET['rc'] = ""; }
	$ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-profile-form' class='feup-pure-form pure-form-aligned feup-pure-form-aligned'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-inlcude-wp' value='" . $include_wordpress . "' />";
	$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='confirm-forgot-password'>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='Email' id='ewd-feup-edit-password' class='ewd-feup-field-label'>" . $feup_Label_Email . ": </label>";
	$ReturnString .= "<input type='email' class='ewd-feup-text-input' name='Email' class='ewd-feup-text-input' value='".$_GET['add']."' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='Resetcode' id='ewd-feup-edit-password' class='ewd-feup-field-label'>" . $feup_Label_Reset_Code . ": </label>";
	$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Resetcode' class='ewd-feup-text-input' value='".$_GET['rc']."' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='User_Password' id='ewd-feup-edit-password' class='ewd-feup-field-label'>" . $feup_Label_Password . ": </label>";
	$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password' class='ewd-feup-text-input' value='' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='Confirm_User_Password' id='ewd-feup-edit-confirm-password' class='ewd-feup-field-label'>" . $feup_Label_Repeat_Password . ": </label>";
	$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password' class='ewd-feup-text-input' value='' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Edit_Password_Submit' value='" . $feup_Label_Change_Password . "'></div>";
	$ReturnString .= "</div>";
	$ReturnString .= "</form>";

	return $ReturnString;
}


