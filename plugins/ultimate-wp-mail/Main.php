<?php
/*
Plugin Name: Ultimate WP Mail
Plugin URI: http://www.EtoileWebDesign.com/plugins/
Description: Create and send custom HTML emails to segments of users registered on your site
Author: Etoile Web Design
Author URI: http://www.EtoileWebDesign.com/
Terms and Conditions: http://www.etoilewebdesign.com/plugin-terms-and-conditions/
Text Domain: ultimate-wp-mail
Version: 0.16
*/

global $ewd_uwpm_message;
global $EWD_UWPM_Full_Version;
global $ewd_uwpm_email_send_events, $ewd_uwpm_email_open_events, $ewd_uwpm_email_links_clicked_events, $ewd_uwpm_email_only_users;
$ewd_uwpm_email_send_events = $wpdb->prefix . "ewd_uwpm_email_send_events";
$ewd_uwpm_email_open_events = $wpdb->prefix . "ewd_uwpm_email_open_events";
$ewd_uwpm_email_links_clicked_events = $wpdb->prefix . "ewd_uwpm_email_links_clicked_events";
$ewd_uwpm_email_only_users = $wpdb->prefix . "ewd_uwpm_email_only_users";
global $UWPM_Custom_Element_Types;
$UWPM_Custom_Element_Types = array();
global $UWPM_Custom_Element_Section_Types;
$UWPM_Custom_Element_Section_Types = array();

$EWD_UWPM_Version = '0.10b';

define( 'EWD_UWPM_CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EWD_UWPM_CD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

//define('WP_DEBUG', true);

register_activation_hook(__FILE__,'Set_EWD_UWPM_Options');
register_activation_hook(__FILE__,'EWD_UWPM_Show_Dashboard_Link');

add_action( 'init', 'EWD_UWPM_Allow_Custom_Element_Registration', 11 );
function EWD_UWPM_Allow_Custom_Element_Registration() {
	do_action('uwpm_register_custom_element_section');
	do_action('uwpm_register_custom_element');
}

/* Hooks neccessary admin tasks */
if ( is_admin() ){
	add_action('admin_head', 'EWD_UWPM_Admin_Options');
	add_action('widgets_init', 'Update_EWD_UWPM_Content');
	add_action('admin_head', 'Add_EWD_UWPM_Scripts');
	add_action('admin_notices', 'EWD_UWPM_Error_Notices');
}
add_action('init', 'Update_EWD_UWPM_Front_End_Actions');
add_action('init', 'Handle_EWD_UWPM_Tracking');

function EWD_UWPM_Enable_Menu() {
	global $submenu;

	$Admin_Approval = get_option("EWD_UWPM_Admin_Approval");

	add_menu_page( 'Ultimate WP Mail', 'Emails', 'edit_posts', 'EWD-UWPM-Options', 'EWD_UWPM_Output_Options', 'dashicons-email', '49.1' );
	$submenu['EWD-UWPM-Options'][4] = $submenu['EWD-UWPM-Options'][1];
	$submenu['EWD-UWPM-Options'][1] = array( 'Emails', 'edit_posts', "edit.php?post_type=uwpm_mail_template", "Forms" );
	$submenu['EWD-UWPM-Options'][2] = array( 'Add New', 'edit_posts', "post-new.php?post_type=uwpm_mail_template", "Add New" );
	$submenu['EWD-UWPM-Options'][3] = array( 'Categories', 'edit_posts', "edit-tags.php?taxonomy=uwpm-category&post_type=uwpm_mail_template", "Categories" );
	add_submenu_page('EWD-UWPM-Options', 'UWPM Lists', 'Lists', 'edit_posts', 'EWD-UWPM-Options&DisplayPage=Lists', 'EWD_UWPM_Output_Options');
	add_submenu_page('EWD-UWPM-Options', 'UWPM User Stats', 'User Stats', 'edit_posts', 'EWD-UWPM-Options&DisplayPage=UserStats', 'EWD_UWPM_Output_Options');
	add_submenu_page('EWD-UWPM-Options', 'UWPM Options', 'Options', 'edit_posts', 'EWD-UWPM-Options&DisplayPage=Options', 'EWD_UWPM_Output_Options');

	//$submenu['EWD-UWPM-Options'][0][0] = "Dashboard";
	ksort($submenu['EWD-UWPM-Options']);
}
add_action('admin_menu' , 'EWD_UWPM_Enable_Menu');

function EWD_UWPM_Add_Header_Bar($Called = "No") {
	global $pagenow;

	if ($Called != "Yes" and (!isset($_GET['post_type']) or $_GET['post_type'] != "uwpm_mail_template")) {return;}

	$Admin_Approval = get_option("EWD_UWPM_Admin_Approval"); ?>

	<div class="EWD_UWPM_Menu">
		<h2 class="nav-tab-wrapper">
			<a id="ewd-uwpm-dash-mobile-menu-open" href="#" class="MenuTab nav-tab"><?php _e("MENU", 'ultimate-wp-mail'); ?><span id="ewd-uwpm-dash-mobile-menu-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-uwpm-dash-mobile-menu-up-caret">&nbsp;&nbsp;&#9650;</span></a>
			<a id="Dashboard_Menu" href='admin.php?page=EWD-UWPM-Options' class="MenuTab nav-tab <?php if (!isset($_GET['post_type']) and ($_GET['DisplayPage'] == '' or $_GET['DisplayPage'] == 'Dashboard')) {echo 'nav-tab-active';}?>"><?php _e("Dashboard", 'ultimate-wp-mail'); ?></a>
			<a id="Emails_Menu" href='edit.php?post_type=uwpm_mail_template' class="MenuTab nav-tab <?php if (isset($_GET['post_type']) and $_GET['post_type'] == 'uwpm_mail_template' and $pagenow == 'edit.php') {echo 'nav-tab-active';}?>"><?php _e("Emails", 'ultimate-wp-mail'); ?></a>
			<a id="Add_New_Menu" href='post-new.php?post_type=uwpm_mail_template' class="MenuTab nav-tab <?php if (isset($_GET['post_type']) and $_GET['post_type'] == 'uwpm_mail_template' and $pagenow == 'post-new.php') {echo 'nav-tab-active';}?>"><?php _e("Add New", 'ultimate-wp-mail'); ?></a>
			<a id="Categories_Menu" href='edit-tags.php?taxonomy=uwpm-category&post_type=uwpm_mail_template' class="MenuTab nav-tab <?php if (isset($_GET['post_type']) and $_GET['post_type'] == 'uwpm_mail_template' and $pagenow == 'edit-tags.php') {echo 'nav-tab-active';}?>"><?php _e("Categories", 'ultimate-wp-mail'); ?></a>
			<a id="Lists_Menu" href='admin.php?page=EWD-UWPM-Options&DisplayPage=Lists' class="MenuTab nav-tab <?php if (!isset($_GET['post_type']) and $_GET['DisplayPage'] == 'Lists') {echo 'nav-tab-active';}?>"><?php _e("Lists", 'ultimate-wp-mail'); ?></a>
			<a id="UserStats_Menu" href='admin.php?page=EWD-UWPM-Options&DisplayPage=UserStats' class="MenuTab nav-tab <?php if (!isset($_GET['post_type']) and $_GET['DisplayPage'] == 'UserStats') {echo 'nav-tab-active';}?>"><?php _e("User Stats", 'ultimate-wp-mail'); ?></a>
			<a id="Options_Menu" href='admin.php?page=EWD-UWPM-Options&DisplayPage=Options' class="MenuTab nav-tab <?php if (!isset($_GET['post_type']) and $_GET['DisplayPage'] == 'Options') {echo 'nav-tab-active';}?>"><?php _e("Options", 'ultimate-wp-mail'); ?></a>
		</h2>
	</div>
<?php }
add_action('admin_notices', 'EWD_UWPM_Add_Header_Bar');

/* Add localization support */
function EWD_UWPM_localization_setup() {
		load_plugin_textdomain('ultimate-wp-mail', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}
add_action('after_setup_theme', 'EWD_UWPM_localization_setup');

// Add settings link on plugin page
function EWD_UWPM_plugin_settings_link($links) {
  $settings_link = '<a href="admin.php?page=EWD-UWPM-Options">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'EWD_UWPM_plugin_settings_link' );

function EWD_UWPM_Show_Dashboard_Link() {
	set_transient('ewd-uwpm-admin-install-notice', true, 5);
}

function Add_EWD_UWPM_Scripts() {
	global $EWD_UWPM_Version;
	global $post;

    wp_enqueue_script('ewd-uwpm-review-ask', plugins_url("js/ewd-uwpm-dashboard-review-ask.js", __FILE__), array('jquery'), $EWD_UWPM_Version);

	if ((isset($_GET['post_type']) && $_GET['post_type'] == 'uwpm_mail_template') or (isset($_GET['page']) && $_GET['page'] == 'EWD-UWPM-Options') or (isset($post) and $post->post_type == 'uwpm_mail_template')) {
        wp_enqueue_script(  'jquery-ui-sortable' );
		wp_enqueue_script('ewd-uwpm-admin-js', plugins_url("ultimate-wp-mail/js/Admin.js"), array('jquery', 'jquery-ui-sortable'), $EWD_UWPM_Version);
		wp_enqueue_script('spectrum', plugins_url("ultimate-wp-mail/js/spectrum.js"), array('jquery'));

		$Emails = get_posts(array('post_type' => 'uwpm_mail_template', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));
		$WC_Categories = get_terms(array('hide_empty' => false, 'taxonomy' => 'product_cat'));
		$Products = get_posts(array('post_type' => 'product', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));

		$ewd_uwpm_php_data = array(
			'emails' => $Emails,
			'categories' =>$WC_Categories,
			'products' => $Products
		);

		//$ewd_uwpm_php_data = array('test' => 'value');

		wp_localize_script('ewd-uwpm-admin-js', 'ewd_uwpm_php_data', $ewd_uwpm_php_data);
	}
}

add_action( 'wp_enqueue_scripts', 'Add_EWD_UWPM_FrontEnd_Scripts' );
function Add_EWD_UWPM_FrontEnd_Scripts() {
	global $EWD_UWPM_Version;
	wp_enqueue_script('ewd-uwpm-js', plugins_url( '/js/ewd-uwpm-js.js' , __FILE__ ), array( 'jquery' ), $EWD_UWPM_Version);
	wp_localize_script('ewd-uwpm-js', 'ewd_uwpm_data', array('ajaxurl' => admin_url('admin-ajax.php')));
}


add_action( 'wp_enqueue_scripts', 'EWD_UWPM_Add_Stylesheet' );
function EWD_UWPM_Add_Stylesheet() {
	global $EWD_UWPM_Version;
    wp_enqueue_style( 'ewd-uwpm-css', plugins_url('css/ewd-uwpm-css.css', __FILE__), $EWD_UWPM_Version );
}

function EWD_UWPM_Admin_Options() {
	global $EWD_UWPM_Version;
	global $post;

	wp_enqueue_style( 'ewd-uwpm-dashboard-review-ask-css', plugins_url("ultimate-wp-mail/css/ewd-uwpm-dashboard-review-ask.css"), array(), $EWD_UWPM_Version);

	if ((isset($_GET['post_type']) && $_GET['post_type'] == 'uwpm_mail_template') or (isset($_GET['page']) && $_GET['page'] == 'EWD-UWPM-Options') or (isset($post) and $post->post_type == 'uwpm_mail_template')) {
		wp_enqueue_style( 'spectrum', plugins_url("ultimate-wp-mail/css/spectrum.css"));
		wp_enqueue_style( 'ewd-uwpm-admin', plugins_url("ultimate-wp-mail/css/Admin.css"), array(), $EWD_UWPM_Version);
	}
}

add_filter( 'mce_buttons', 'EWD_UWPM_Register_TinyMCE_Buttons' );
function EWD_UWPM_Register_TinyMCE_Buttons( $buttons ) {
   array_push( $buttons, 'separator', 'UWPM_Tags' );
   return $buttons;
}

add_filter( 'mce_external_plugins', 'EWD_UWPM_Register_TinyMCE_Javascript' );
function EWD_UWPM_Register_TinyMCE_Javascript( $plugin_array ) {
   $plugin_array['UWPM_Tags'] = plugins_url( '/js/tinymce-plugin.js',__FILE__ );

   return $plugin_array;
}

add_action('admin_head', 'EWD_UWPM_Output_TinyMCE_Vars');
function EWD_UWPM_Output_TinyMCE_Vars() {
	global $post;

	$User_Fields = EWD_UWPM_Get_User_Fields();
	$Custom_Elements = EWD_UWPM_Get_Custom_Elements();
	$Custom_Element_Section_Types = EWD_UWPM_Get_Custom_Element_Sections();

	$Post_Fields = array(
		array('slug' => 'post_title', 'name' => 'Post Title'),
		array('slug' => 'post_content', 'name' => 'Post Content'),
		array('slug' => 'post_date', 'name' => 'Post Date'),
		array('slug' => 'post_status', 'name' => 'Post Status'),
		array('slug' => 'post_type', 'name' => 'Post Type'),
	);

	if (empty($Custom_Elements)) {$Custom_Elements = array(array('slug' => -1, 'name' => 'No Elements Registered', 'attributes' => array()));}

	echo "<script type='text/javascript'>";
	if (isset($post) and $post->post_type == 'uwpm_mail_template') {echo "var uwpm_insert_variables = 'Yes';\n";}
	echo "var uwpm_user_tags = " . json_encode($User_Fields) . ";\n";
	echo "var uwpm_post_tags = " . json_encode($Post_Fields) . ";\n";
	echo "var uwpm_custom_elements = " . json_encode($Custom_Elements) . ";\n";
	echo "var uwpm_custom_element_sections = " . json_encode($Custom_Element_Section_Types) . ";\n";
	echo "</script>";
}

$EWD_UWPM_Full_Version = get_option("EWD_UWPM_Full_Version");

function EWD_UWPM_Run_Delayed_Send_On_Emails() {
	if (time() > get_option('EWD_UWPM_Delayed_Send_Cache_Time') + 15*60) { //15 minutes
		EWD_UWPM_Send_Scheduled_Emails();
		EWD_UWPM_Send_On_User_X_Time_Since_Login();
		EWD_UWPM_Send_On_WC_X_Time_Since_Cart_Abandoned();
		EWD_UWPM_Send_On_WC_X_Time_After_Purchase();
		EWD_UWPM_WC_Advanced_Send_On_Actions();

		update_option('EWD_UWPM_Delayed_Send_Cache_Time', time());
	}
}
add_action('init', 'EWD_UWPM_Run_Delayed_Send_On_Emails');

function EWD_UWPM_User_Last_Activity() {
	if (get_current_user_id() == 0) {return;}

	update_usermeta(get_current_user_id(), 'EWD_UWPM_User_Last_Activity', time());
	update_usermeta(get_current_user_id(), 'EWD_UWPM_Login_Reminder_Sent', 'No');
}
add_action('init', 'EWD_UWPM_User_Last_Activity');

function EWD_UWPM_Track_Current_Cart() {
	global $woocommerce;

	$User_ID = get_current_user_id();
	if ($User_ID == 0) {return;}

	$Product_IDs = array();
	foreach ($woocommerce->cart->get_cart() as $Cart_Item) {
		$Product = $Cart_Item['data'];
        if(!empty($Product)){
        	$Product_IDs[] = $Product->ID;
        }
	}

	if (!empty($Product_IDs)) {
		update_usermeta($User_ID, 'EWD_UWPM_User_Cart_Contents', $Product_IDs);
		update_usermeta($User_ID, 'EWD_UWPM_User_Cart_Update_Time', time());
		update_usermeta($User_ID, 'EWD_UWPM_Abandoned_Cart_Reminder_Sent', 'No');
	}
	else {
		delete_usermeta($User_ID, 'EWD_UWPM_User_Cart_Contents');
		delete_usermeta($User_ID, 'EWD_UWPM_User_Cart_Update_Time');
		delete_usermeta($User_ID, 'EWD_UWPM_Abandoned_Cart_Reminder_Sent');
	}
}
add_action('woocommerce_add_to_cart', 'EWD_UWPM_Track_Current_Cart');
add_action('woocommerce_cart_item_removed', 'EWD_UWPM_Track_Current_Cart');
add_action('woocommerce_cart_item_restored', 'EWD_UWPM_Track_Current_Cart');

function EWD_UWPM_Create_Sent_WC_Emails_Meta($post_id) {
	update_option("EWD_UWPM_Debug", "Getting called");
	update_post_meta($post_id, 'EWD_UWPM_Emails_Sent', array());
}
add_action('woocommerce_new_order', 'EWD_UWPM_Create_Sent_WC_Emails_Meta');

include "blocks/ewd-uwpm-blocks.php";
include "Functions/Error_Notices.php";
include "Functions/EWD_UWPM_Change_Button_Text.php";
include "Functions/EWD_UWPM_Deactivation_Survey.php";
include "Functions/EWD_UWPM_Edit_Email_Content.php";
include "Functions/EWD_UWPM_Manage_User_Interests.php";
include "Functions/EWD_UWPM_Output_Buffering.php";
include "Functions/EWD_UWPM_Output_Options.php";
include "Functions/EWD_UWPM_Register_WooCommerce_Custom_Elements.php";
include "Functions/EWD_UWPM_Scheduled_Send.php";
include "Functions/EWD_UWPM_Send_Emails.php";
include "Functions/EWD_UWPM_Send_On_Actions.php";
include "Functions/EWD_UWPM_Subscribe_Unsubscribe_Checkboxes.php";
include "Functions/EWD_UWPM_Tracking.php";
include "Functions/EWD_UWPM_Widgets.php";
include "Functions/Process_EWD_UWPM_Ajax.php";
include "Functions/Register_EWD_UWPM_Posts_Taxonomies.php";
include "Functions/Update_EWD_UWPM_Admin_Databases.php";
include "Functions/Update_EWD_UWPM_Content.php";
include "Functions/Update_EWD_UWPM_Front_End_Actions.php";
include "Functions/Update_EWD_UWPM_Tables.php";

include "Classes/UWPM_Custom_Element.php";
include "Classes/UWPM_Element_Section.php";

include "Functions/uwpm_register_custom_element.php";
include "Functions/uwpm_register_custom_element_section.php";

include "Shortcodes/Display_Subscription_Interests.php";

if ($EWD_UWPM_Version != get_option('EWD_UWPM_Version')) {
	Set_EWD_UWPM_Options();
	Update_EWD_UWPM_Tables();

	update_option('EWD_UWPM_Version', $EWD_UWPM_Version);
}

function Set_EWD_UWPM_Options() {
	if (get_option('EWD_UWPM_User_Unsubscribe') == '') {update_option('EWD_UWPM_User_Unsubscribe', 'Yes');}
	if (get_option('EWD_UWPM_Add_Unsubscribe_Link') == '') {update_option('EWD_UWPM_Add_Unsubscribe_Link', 'Yes');}
	if (get_option('EWD_UWPM_Add_Subscribe_Checkbox') == '') {update_option('EWD_UWPM_Add_Subscribe_Checkbox', 'No');}
	if (get_option('EWD_UWPM_Add_Unsubscribe_Checkbox') == '') {update_option('EWD_UWPM_Add_Unsubscribe_Checkbox', 'Yes');}
	if (get_option('EWD_UWPM_Track_Opens') == '') {update_option('EWD_UWPM_Track_Opens', 'No');}
	if (get_option('EWD_UWPM_Track_Clicks') == '') {update_option('EWD_UWPM_Track_Clicks', 'No');}
	if (get_option('EWD_UWPM_WooCommerce_Integration') == '') {update_option('EWD_UWPM_WooCommerce_Integration', 'Yes');}
	if (get_option('EWD_UWPM_Display_Interests') == '') {update_option('EWD_UWPM_Display_Interests', array('none'));}
	if (get_option('EWD_UWPM_Display_Post_Interests') == '') {update_option('EWD_UWPM_Display_Post_Interests', 'None');}

    if (get_option("EWD_UWPM_Install_Time") == "") {update_option("EWD_UWPM_Install_Time", time());}
    if(!get_option("EWD_UWPM_Ask_Review_Date")){add_option("EWD_UWPM_Ask_Review_Date", "");}

	if (get_option('EWD_UWPM_WC_Advanced_Send_On_Actions') == '') {update_option('EWD_UWPM_WC_Advanced_Send_On_Actions', array());}

	if (get_option("EWD_UWPM_Send_On_Actions") == '') {
		// Update the send on events so that multiple emails can be sent for every event
		$Max_ID = 0;
		$Send_On_Actions = get_option("EWD_UWPM_WC_Advanced_Send_On_Actions");
		foreach ($Send_On_Actions as $Send_On_Action) {
			$Max_ID = max($Max_ID, $Send_On_Action['Send_On_ID']);
		}
	
		if (get_option("EWD_UWPM_Event_User_Registers") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'User_Registers',
				'Email_ID' => get_option("EWD_UWPM_Event_User_Registers_Email")
			);
		}
	
		if (get_option("EWD_UWPM_Event_User_Profile_Updated") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'User_Profile_Updated',
				'Email_ID' => get_option("EWD_UWPM_Event_User_Profile_Updated_Email")
			);
		}
	
		if (get_option("EWD_UWPM_Event_User_Role_Changed") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'User_Role_Changed',
				'Email_ID' => get_option("EWD_UWPM_Event_User_Role_Changed_Email")
			);
		}
	
		if (get_option("EWD_UWPM_Event_User_Password_Reset") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'User_Password_Reset',
				'Email_ID' => get_option("EWD_UWPM_Event_User_Password_Reset_Email")
			);
		}
	
		if (get_option("EWD_UWPM_Event_User_X_Time_Since_Login") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'User_X_Time_Since_Login',
				'Interval_Count' => get_option("EWD_UWPM_Event_User_X_Time_Since_Login_Count"),
				'Interval_Unit' => get_option("EWD_UWPM_Event_User_X_Time_Since_Login_Unit"),
				'Email_ID' => get_option("EWD_UWPM_Event_User_X_Time_Since_Login_Email")
			);
		}
	
		if (get_option("EWD_UWPM_Event_Post_Published") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'Post_Published',
				'Email_ID' => get_option("EWD_UWPM_Event_Post_Published_Email")
			);
		}
	
		if (get_option("EWD_UWPM_Event_Post_Published_Interest") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'Post_Published_Interest',
				'Email_ID' => get_option("EWD_UWPM_Event_Post_Published_Interest_Email")
			);
		}
	
		if (get_option("EWD_UWPM_Event_New_Comment_On_Post") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'New_Comment_On_Post',
				'Email_ID' => get_option("EWD_UWPM_Event_New_Comment_On_Post_Email")
			);
		}

		if (get_option("EWD_UWPM_Event_WC_New_Product_Added") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'Product_Added',
				'Interval_Count' => 1,
				'Interval_Unit' => 'Minutes',
				'Includes' => 'Any',
				'Email_ID' => get_option("EWD_UWPM_Event_WC_New_Product_Added_Email")
			);
		}

		if (get_option("EWD_UWPM_Event_WC_X_Time_Since_Cart_Abandoned") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'WC_X_Time_Since_Cart_Abandoned',
				'Interval_Count' => get_option("EWD_UWPM_Event_WC_X_Time_Since_Cart_Abandoned_Count"),
				'Interval_Unit' => get_option("EWD_UWPM_Event_WC_X_Time_Since_Cart_Abandoned_Unit"),
				'Email_ID' => get_option("EWD_UWPM_Event_WC_X_Time_Since_Cart_Abandoned_Email")
			);
		}

		if (get_option("EWD_UWPM_Event_WC_X_Time_After_Purchase") == "Yes") {
			$Max_ID++;
			$Send_On_Actions[] = array(
				'Send_On_ID' => $Max_ID,
				'Enabled' => 'Yes',
				'Action_Type' => 'WC_X_Time_After_Purchase',
				'Interval_Count' => get_option("EWD_UWPM_Event_WC_X_Time_After_Purchase_Count"),
				'Interval_Unit' => get_option("EWD_UWPM_Event_WC_X_Time_After_Purchase_Unit"),
				'Email_ID' => get_option("EWD_UWPM_Event_WC_X_Time_After_Purchase_Email")
			);
		}

		update_option("EWD_UWPM_Send_On_Actions", $Send_On_Actions);
	} 

	if (get_option('EWD_UWPM_Full_Version') == '') {update_option('EWD_UWPM_Full_Version', 'Yes');}
}

?>
