<?php
$Include_WP_Users = get_option("EWD_FEUP_Include_WP_Users");

function EWD_FEUP_Import_WP_Users() {
	global $wpdb;
	global $ewd_feup_user_table_name;

	$Blog_ID = get_current_blog_id();
	$WP_Users = get_users( 'blog_id=' . $Blog_ID ); 
	foreach ($WP_Users as $WP_User) {
		$FEUP_User = $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $WP_User->ID));
		if ($wpdb->num_rows == 0) {
			EWD_FEUP_Create_WP_FEUP_User($WP_User);
		}
	} 
}

if ($Include_WP_Users == "Yes") {add_action( 'user_register', 'EWD_FEUP_Add_User_From_WP', 10, 1 );}
function EWD_FEUP_Add_User_From_WP($WP_User_ID) {
	$WP_User = get_user_by('id', $WP_User_ID);
	EWD_FEUP_Create_WP_FEUP_User($WP_User);
}

if ($Include_WP_Users == "Yes") {add_action('wp_login', 'EWD_FEUP_WP_User_Login', 10, 2);}
function EWD_FEUP_WP_User_Login($User_Login, $WP_User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	$FEUP_User = $wpdb->get_row($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $WP_User->ID));
	$WP_Login = "Yes";
	
	Confirm_Login($FEUP_User->Username, $WP_Login);
}

function EWD_FEUP_Create_WP_FEUP_User($WP_User) {
	global $wpdb;
	global $ewd_feup_user_table_name;

	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Default_User_Level = get_option("EWD_Default_User_Level");
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

	if($Use_Crypt == "Yes") {
		$User_Fields['User_Password'] = Generate_Password(EWD_FEUP_RandomString());
	} else {
		$User_Fields['User_Password'] = sha1(md5(EWD_FEUP_RandomString().$Salt));
	}

	if ($Username_Is_Email == "Yes") {$User_Fields['Username'] = $WP_User->user_email;}
	else {$User_Fields['Username'] = $WP_User->user_login;}
	$User_Fields['User_Admin_Approved'] = "No";
	$User_Fields['User_Email_Confirmed'] = "No";
	$User_Fields['User_Date_Created'] = date("Y-m-d H:i:s");
	$User_Fields['Level_ID'] = $Default_User_Level;
	$User_Fields['User_WP_ID'] = $WP_User->ID;

	$User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username=%s", $User_Fields['Username']));

	if (!$User_ID) {Add_EWD_FEUP_User($User_Fields);}
}

$Include_WP_Users = get_option("EWD_FEUP_Include_WP_Users");
$Create_WordPress_Users = get_option("EWD_FEUP_Create_WordPress_Users");
if ($Include_WP_Users == "Yes" or $Create_WordPress_Users == "Yes") {add_filter("get_user_metadata", "EWD_FEUP_Return_Meta_Data", 90, 4);}
function EWD_FEUP_Return_Meta_Data($meta_data, $wp_user_id, $meta_key, $single) {
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_fields_table_name;

	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

	$User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID  FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $wp_user_id));

	if (!$User_ID) {return $meta_data;}

	switch ($meta_key) {
		case 'first_name':
			$Field_ID = $wpdb->get_var($wpdb->prepare("SELECT Field_ID FROM $ewd_feup_fields_table_name WHERE Field_Equivalent=%s", "First_Name"));
			if ($Field_ID) {$meta_data = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field_ID, $User_ID));}
			break;
		case 'last_name':
			$Field_ID = $wpdb->get_var($wpdb->prepare("SELECT Field_ID FROM $ewd_feup_fields_table_name WHERE Field_Equivalent=%s", "Last_Name"));
			if ($Field_ID) {$meta_data = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field_ID, $User_ID));}
			break;
		case 'email':
			if ($Username_Is_Email == "Yes") {$meta_data = $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE  User_ID=%d", $User_ID));}
			else {
				$Field_ID = $wpdb->get_var($wpdb->prepare("SELECT Field_ID FROM $ewd_feup_fields_table_name WHERE Field_Equivalent=%s", "Email"));
				if ($Field_ID) {$meta_data = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field_ID, $User_ID));}
			}
			break;		
		default:
			$Field_ID = $wpdb->get_var($wpdb->prepare("SELECT Field_ID FROM $ewd_feup_fields_table_name WHERE Field_Name=%s", $meta_key));
			if ($Field_ID) {$meta_data = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field_ID, $User_ID));}
			break;
	}

	if (!$single and !is_array($meta_data)) {
		$return_meta[0] = $meta_data;
	}
	else {
		$return_meta = $meta_data;
	}
}


add_action('show_user_profile', 'EWD_FEUP_Custom_User_Profile_Fields');
add_action('edit_user_profile', 'EWD_FEUP_Custom_User_Profile_Fields');

function EWD_FEUP_Custom_User_Profile_Fields($user) {
	global $wpdb, $ewd_feup_user_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name;

	$User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $user->ID));

	if (!$User_ID) {return;}

	$Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name");
?>
    <h2><?php _e('Front-End Only Users Fields'); ?></h2>
    <table class="form-table">
    <?php 
    	foreach ($Fields as $Field) {
    		$Meta_Value = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field->Field_ID, $User_ID));
    ?>
        	<tr>
        	    <th>
        	        <label for="code"><?php echo $Field->Field_Name; ?></label>
        	    </th>
        	    <td>
        	        <input type="text" name="FEUP_<? echo $Field->Field_ID; ?>" id="code" value="<?php echo $Meta_Value; ?>" class="regular-text" />
        	    </td>
        	</tr>
    <?php } ?>
    </table>
<?php
}

add_action( 'personal_options_update', 'EWD_FEUP_Update_Extra_Profile_Fields' );
add_action( 'edit_user_profile_update', 'EWD_FEUP_Update_Extra_Profile_Fields' );

function EWD_FEUP_Update_Extra_Profile_Fields($user_id) {
    global $wpdb, $ewd_feup_user_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name;
    
    if (current_user_can('edit_user', $user_id)) {
    	$User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID  FROM $ewd_feup_user_table_name WHERE User_WP_ID=%d", $user_id));

		if (!$User_ID) {return;}

    	foreach ($_POST as $Post_Field_ID => $Value) {
    		if (substr($Post_Field_ID, 0, 4) == "FEUP") {
    			$Field_ID = substr($Post_Field_ID, 5);
    			$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_fields_table_name SET Field_Value=%s WHERE Field_ID=%d AND User_ID=%d", $Value, $Field_ID, $User_ID));
    		}
    	}
    }
        
}

?>