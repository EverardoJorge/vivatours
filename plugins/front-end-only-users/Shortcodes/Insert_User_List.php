<?php 
function EWD_FEUP_User_List_Block() {
    if(function_exists('render_block_core_block')){  
		wp_register_script( 'ewd-feup-blocks-js', plugins_url( '../blocks/ewd-feup-blocks.js', __FILE__ ), array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ) );
		wp_register_style( 'ewd-feup-blocks-css', plugins_url( '../blocks/ewd-feup-blocks.css', __FILE__ ), array( 'wp-edit-blocks' ), filemtime( plugin_dir_path( __FILE__ ) . '../blocks/ewd-feup-blocks.css' ) );
		register_block_type( 'front-end-only-users/ewd-feup-user-list-block', array(
			'attributes'      => array(
				'login_necessary' => array(
					'type' => 'string',
				),
				'login_page' => array(
					'type' => 'string',
				),
				'field_name' => array(
					'type' => 'string',
				),
				'field_value' => array(
					'type' => 'string',
				),
				'display_field' => array(
					'type' => 'string',
				),
				'user_profile_page' => array(
					'type' => 'string',
				),
			),
			'editor_script'   => 'ewd-feup-blocks-js',
			'editor_style'  => 'ewd-feup-blocks-css',
			'render_callback' => 'User_List',
		) );
	}
	// Define our shortcode, too, using the same render function as the block.
	add_shortcode("user-list", "User_List");
}
add_action( 'init', 'EWD_FEUP_User_List_Block' );

/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function User_List($atts, $content = null) {
	// Include the required global variables, and create a few new ones
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;

	$feup_Label_Require_Login_Message =  get_option("EWD_FEUP_Label_Require_Login_Message");
	if ($feup_Label_Require_Login_Message == "") {$feup_Label_Require_Login_Message =  __('You must be logged in to access this page.', 'front-end-only-users');}
	$feup_Label_Please =  get_option("EWD_FEUP_Label_Please");
	if ($feup_Label_Please == "") {$feup_Label_Please = __("Please", 'front-end-only-users');}
	$feup_Label_To_Continue =  get_option("EWD_FEUP_Label_To_Continue");
	if ($feup_Label_To_Continue == "") {$feup_Label_To_Continue = __("To Continue", 'front-end-only-users');}
	$feup_Label_Login =  get_option("EWD_FEUP_Label_Login");
	if ($feup_Label_Login == "") {$feup_Label_Login = __("Login", 'front-end-only-users');}

	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	
	$UserCookie = CheckLoginCookie();
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
					'login_page' => '',
					'field_name' => '',
					'field_value' => '',
					'login_necessary' => 'Yes',
					'display_field' => 'Username',
					'order_by' => '',
					'order' => '',
					'user_profile_page' => ''),
					$atts
			)
	);

	if ($order_by == "") {$order_by = "User_ID";}
	if ($order == "") {$order = "ASC";}
		
	$display_fields = explode(",", $display_field);

	$ReturnString = "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= EWD_FEUP_Add_Modified_Styles();

	if (!$UserCookie and $login_necessary == "Yes") {
		$ReturnString .= $feup_Label_Require_Login_Message;
		if ($login_page != "") {$ReturnString .= "<br />" . $feup_Label_Please . " <a href='" . $login_page . "'>" . $feup_Label_Login . "</a> " . $feup_Label_To_Continue ;}
		return $ReturnString;
	}
		
	if ($field_name != ""  and $field_value != "") {
		$User_IDs = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT User_ID FROM $ewd_feup_user_fields_table_name WHERE Field_Name='%s' AND Field_Value='%s' ORDER BY '$order_by' $order", $field_name, $field_value));
	}
	else {
		if ($order_by == "User_ID") {$User_IDs = $wpdb->get_results("SELECT DISTINCT User_ID FROM $ewd_feup_user_table_name");}
		else {$User_IDs = $wpdb->get_results("SELECT DISTINCT $ewd_feup_user_table_name.User_ID FROM $ewd_feup_user_table_name INNER JOIN $ewd_feup_user_fields_table_name ON $ewd_feup_user_table_name.User_ID = $ewd_feup_user_fields_table_name.User_ID ORDER BY '$ewd_feup_user_fields_table_name.$order_by' $order");}
	}

	foreach ($User_IDs as $User_ID) {
		foreach ($display_fields as $display_field) {
			if ($display_field == "Username") {
				$User = $wpdb->get_row($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $User_ID->User_ID));
				$Return_User[$display_field] = $User->Username;
			}
			else {
				$User = $wpdb->get_row($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d' and Field_Name=%s", $User_ID->User_ID, $display_field));
				if (is_object($User)) { $Field_Value = $User->Field_Value; }
				else { $Field_Value = ""; }
				$Return_User[$display_field] = $Field_Value;
			}
		}
		$Return_User['User_ID'] = $User_ID->User_ID;
		$UserDataSet[] = $Return_User;
		unset($Return_User);
	}
	if (!isset($UserDataSet)) { $UserDataSet = array(); }
	if (is_array($UserDataSet)) {
		sort($UserDataSet);
		foreach ($UserDataSet as $User_Data) {			
			$ReturnString .= "<div class='ewd-feup-user-list-result' id='ewd-feup-user-list'>";
			if ($user_profile_page != "") {$ReturnString .= "<a href='" . $user_profile_page . "?User_ID=" . $User_Data['User_ID'] . "'>";}
			foreach ($display_fields as $display_field) {
				$ReturnString .= esc_html($User_Data[$display_field]) . " ";
			}
			if ($user_profile_page != "") {$ReturnString .= "</a>";}
			$ReturnString .= "</div>";
		}
	}
		
	return $ReturnString;
}

