<?php
/*
Plugin Name: Front End Users
Plugin URI: https://www.etoilewebdesign.com/plugins/front-end-only-users/
Description: A plugin that creates a separate set of users that are front-end only users, who do not appear in the default WordPress users area, and allows content to be tailored based on user profiles
Author: Etoile Web Design
Author URI: https://www.EtoileWebDesign.com/
Terms and Conditions: http://www.etoilewebdesign.com/plugin-terms-and-conditions/
Text Domain: front-end-only-users
Version: 3.2.1
*/

global $EWD_FEUP_db_version;
global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_events_table_name, $ewd_feup_payments_table_name;
global $wpdb;
global $feup_message;
global $user_message;
global $feup_success;
global $EWD_FEUP_Full_Version;

$ewd_feup_user_table_name = $wpdb->prefix . "EWD_FEUP_Users";

$ewd_feup_user_fields_table_name = $wpdb->prefix . "EWD_FEUP_User_Fields";

$ewd_feup_fields_table_name = $wpdb->prefix . "EWD_FEUP_Fields";

$ewd_feup_levels_table_name = $wpdb->prefix . "EWD_FEUP_Levels";
$ewd_feup_user_events_table_name = $wpdb->prefix ."EWD_FEUP_User_Events";
$ewd_feup_payments_table_name = $wpdb->prefix . "EWD_FEUP_Payments";
$EWD_FEUP_db_version = "3.1.16b";

define( 'EWD_FEUP_CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EWD_FEUP_CD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
define('WP_DEBUG', true);
$wpdb->show_errors(); */

/* When plugin is activated */
register_activation_hook(__FILE__,'Install_EWD_FEUP');
register_activation_hook(__FILE__,'Initial_EWD_FEUP_Data');
register_activation_hook(__FILE__,'Run_EWD_FEUP_Tutorial');
register_activation_hook(__FILE__,'EWD_FEUP_Show_Dashboard_Link');
//register_activation_hook(__FILE__,'Initial_EWD_FEUP_Options');

/* When plugin is deactivation*/
register_deactivation_hook( __FILE__, 'Remove_EWD_FEUP' );

/* Creates the admin menu for the contests plugin */
if ( is_admin() ){
	add_action('admin_menu', 'EWD_FEUP_Plugin_Menu');
	add_action('admin_head', 'EWD_FEUP_Admin_Options');
	add_action('admin_init', 'Add_EWD_FEUP_Scripts');
	add_action('init', 'Update_EWD_FEUP_Content', 12);
	add_action('admin_notices', 'EWD_FEUP_Error_Notices');
}

function Remove_EWD_FEUP() {
  	/* Deletes the database field */
	delete_option('EWD_FEUP_db_version');
}

// Process the forms posted by users from the front-end of the plugin
if (isset($_POST['ewd-feup-action'])) {
	add_action('init', 'Process_EWD_FEUP_Front_End_Forms', 12);
}

/* Admin Page setup */
function EWD_FEUP_Plugin_Menu() {
	global $wpdb, $ewd_feup_user_table_name;

	$Access_Role = get_option("EWD_FEUP_Access_Role");
	if ($Access_Role == "") {$Access_Role = "administrator";}

	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	if ($Admin_Approval == "Yes") {
		$TotalUsers = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_Admin_Approved!='Yes'");
		$Title = "F-E Users";
		if ($wpdb->num_rows != 0) {$Title .= " <span class='update-plugins count-2' title='Unapproved Users'><span class='update-count'>" . $wpdb->num_rows . "</span></span>";}
	}
	else {$Title = "F-E Users";}

	add_menu_page('Front End User Plugin', $Title, $Access_Role, 'EWD-FEUP-options', 'EWD_FEUP_Output_Options', 'dashicons-admin-users' , '50.6');
	add_submenu_page('EWD-FEUP-options', 'FEUP Users', 'Users', $Access_Role, 'EWD-FEUP-options&DisplayPage=Users', 'EWD_FEUP_Output_Options');
	add_submenu_page('EWD-FEUP-options', 'FEUP Fields', 'Fields', $Access_Role, 'EWD-FEUP-options&DisplayPage=Field', 'EWD_FEUP_Output_Options');
	add_submenu_page('EWD-FEUP-options', 'FEUP Statistics', 'Statistics', $Access_Role, 'EWD-FEUP-options&DisplayPage=Statistics', 'EWD_FEUP_Output_Options');
	add_submenu_page('EWD-FEUP-options', 'FEUP Levels', 'Levels', $Access_Role, 'EWD-FEUP-options&DisplayPage=Levels', 'EWD_FEUP_Output_Options');
	add_submenu_page('EWD-FEUP-options', 'FEUP Options', 'Options', $Access_Role, 'EWD-FEUP-options&DisplayPage=Options', 'EWD_FEUP_Output_Options');
	add_submenu_page('EWD-FEUP-options', 'FEUP Emails', 'Emails', $Access_Role, 'EWD-FEUP-options&DisplayPage=Emails', 'EWD_FEUP_Output_Options');
	add_submenu_page('EWD-FEUP-options', 'FEUP Payments', 'Payments', $Access_Role, 'EWD-FEUP-options&DisplayPage=Payment', 'EWD_FEUP_Output_Options');
}

/* Add localization support */
function EWD_FEUP_localization_setup() {
	load_plugin_textdomain('front-end-only-users', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}
add_action('after_setup_theme', 'EWD_FEUP_localization_setup');

// Add settings link on plugin page
function EWD_FEUP_plugin_settings_link($links) {
	$settings_link = '<a href="admin.php?page=EWD-FEUP-options">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'EWD_FEUP_plugin_settings_link' );

/* Put in the pretty permalinks filter */
//add_filter( 'query_vars', 'add_query_vars_filter' );

function EWD_FEUP_tinymce_init() {
    add_filter( 'mce_external_plugins', 'EWD_FEUP_tinymce_plugin' );
}
add_filter('init', 'EWD_FEUP_tinymce_init');

function EWD_FEUP_tinymce_plugin($init) {
    $init['keyup_event'] = EWD_FEUP_CD_PLUGIN_URL . '/js/ShortcodeHelper_TinyMCE.js';
    return $init;
}

function Add_EWD_FEUP_Scripts() {
	global $wpdb;
	global $ewd_feup_fields_table_name;
	global $EWD_FEUP_db_version;

	wp_enqueue_script('ewd-feup-review-ask', plugins_url("js/ewd-feup-dashboard-review-ask.js", __FILE__), array('jquery'), $EWD_FEUP_db_version);

	wp_enqueue_script('ewd-feup-shortcode-helper', plugin_dir_url(__FILE__) . '/js/ShortcodeHelper.js', '1.0.0', true);

	if (isset($_GET['page']) && $_GET['page'] == 'EWD-FEUP-options') {
		$url_one = plugins_url("front-end-only-users/js/Admin.js");
		$url_two = plugins_url("front-end-only-users/js/sorttable.js");
		$url_three = plugins_url("front-end-only-users/js/jquery.confirm.min.js");
		$url_four = plugins_url("front-end-only-users/js/bootstrap.min.js");
		$url_five = plugins_url("front-end-only-users/js/ewd-feup-check-password-strength.js");
		$url_six = plugins_url("front-end-only-users/js/spectrum.js");

		wp_enqueue_script('ewd-feup-admin-js', $url_one, array('jquery'));

		$Fields = $wpdb->get_results("SELECT Field_Name, Field_ID FROM $ewd_feup_fields_table_name");

		wp_localize_script( 'ewd-feup-admin-js', 'ewd_feup_field_data', $Fields );

		wp_register_script('password-strength', $url_five, array('jquery'));

		if (get_option('EWD_FEUP_Label_Mismatch') != "") {$Mismatch_Label = get_option('EWD_FEUP_Label_Mismatch');}
		else {$Mismatch_Label = "Mismatch";}
		if (get_option('EWD_FEUP_Label_Too_Short') != "") {$Too_Short_Label = get_option('EWD_FEUP_Label_Too_Short');}
		else {$Too_Short_Label = "Too Short";}
		if (get_option('EWD_FEUP_Label_Weak') != "") {$Weak_Label = get_option('EWD_FEUP_Label_Weak');}
		else {$Weak_Label = "Weak";}
		if (get_option('EWD_FEUP_Label_Good') != "") {$Good_Label = get_option('EWD_FEUP_Label_Good');}
		else {$Good_Label = "Good";}
		if (get_option('EWD_FEUP_Label_Strong') != "") {$Strong_Label = get_option('EWD_FEUP_Label_Strong');}
		else {$Strong_Label = "Strong";}
		$Translation_Array = array( 'mismatch_label' => $Mismatch_Label,
									'too_short_label' => $Too_Short_Label,
									'weak_label' => $Weak_Label,
									'good_label' => $Good_Label,
									'strong_label' => $Strong_Label
							);
		wp_localize_script( 'password-strength', 'ewd_feup_ajax_translations', $Translation_Array );

		wp_enqueue_script('ewd-feup-admin-js');
		wp_enqueue_script('sorttable', $url_two, array('jquery'));
		wp_enqueue_script('confirmation', $url_three, array('jquery', 'bootstrap'));
		wp_enqueue_script('bootstrap', $url_four, array('jquery'));
		wp_enqueue_script('password-strength');
		wp_enqueue_script('spectrum', $url_six, array('jquery'));
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('update-privilege-level-order', plugin_dir_url(__FILE__) . '/js/update-privilege-level-order.js');
	}
}

add_action( 'wp_enqueue_scripts', 'EWD_FEUP_Add_FrontEnd_Scripts' );
function EWD_FEUP_Add_FrontEnd_Scripts() {
	if (get_option("EWD_FEUP_Track_Events") == "Yes") {wp_enqueue_script('ewd-feup-tracking', plugins_url( '/js/ewd-feup-tracking.js' , __FILE__ ), array( 'jquery' ));}

	wp_register_script('ewd-feup-password-strength', EWD_FEUP_CD_PLUGIN_URL . '/js/ewd-feup-check-password-strength.js', array( 'jquery' ));

	if (get_option('EWD_FEUP_Label_Mismatch') != "") {$Mismatch_Label = get_option('EWD_FEUP_Label_Mismatch');}
	else {$Mismatch_Label = "Mismatch";}
	if (get_option('EWD_FEUP_Label_Too_Short') != "") {$Too_Short_Label = get_option('EWD_FEUP_Label_Too_Short');}
	else {$Too_Short_Label = "Too Short";}
	if (get_option('EWD_FEUP_Label_Weak') != "") {$Weak_Label = get_option('EWD_FEUP_Label_Weak');}
	else {$Weak_Label = "Weak";}
	if (get_option('EWD_FEUP_Label_Good') != "") {$Good_Label = get_option('EWD_FEUP_Label_Good');}
	else {$Good_Label = "Good";}
	if (get_option('EWD_FEUP_Label_Strong') != "") {$Strong_Label = get_option('EWD_FEUP_Label_Strong');}
	else {$Strong_Label = "Strong";}
	$Translation_Array = array( 'mismatch_label' => $Mismatch_Label,
								'too_short_label' => $Too_Short_Label,
								'weak_label' => $Weak_Label,
								'good_label' => $Good_Label,
								'strong_label' => $Strong_Label
						);
	wp_localize_script( 'ewd-feup-password-strength', 'ewd_feup_ajax_translations', $Translation_Array );

	wp_enqueue_script('ewd-feup-password-strength');

	if (get_option("EWD_FEUP_Payment_Gateway") == "Stripe") {
		$Stripe_Live_Publishable = get_option("EWD_FEUP_Stripe_Live_Publishable");

		wp_enqueue_script('stripe', EWD_FEUP_CD_PLUGIN_URL . '/js/stripe.js', array( 'jquery' ));
		wp_enqueue_script('stripe-processing', EWD_FEUP_CD_PLUGIN_URL . '/js/stripe-processing.js', array( 'jquery' ));
		wp_localize_script('stripe-processing', 'stripe_vars', array('publishable_key' => $Stripe_Live_Publishable));
	}
}

function EWD_FEUP_Admin_Head() {
	global $EWD_FEUP_Full_Version;
	$Track_Events = get_option("EWD_FEUP_Track_Events");
	$Minimum_Password_Length = get_option("EWD_FEUP_Minimum_Password_Length");

	$User = new FEUP_User;
	echo "<script>";
	if ($Minimum_Password_Length != "") {echo "var FEUP_Min_Pass = " . $Minimum_Password_Length .";\n";}
	if ($User->Is_Logged_In() and $EWD_FEUP_Full_Version == "Yes" and $Track_Events == "Yes") {
		echo "var User_ID = " . $User->Get_User_ID() . ";\n";
		echo "if (typeof(ajaxurl) == 'undefined' || ajaxurl === null) {";
			echo "var ajaxurl = '" . admin_url('admin-ajax.php') . "';\n";
		echo "}";
	}
	echo "</script>";
}
add_action( 'wp_head', 'EWD_FEUP_Admin_Head' );
add_action( 'admin_head', 'EWD_FEUP_Admin_Head' );


add_action( 'wp_enqueue_scripts', 'EWD_FEUP_Add_Stylesheet' );
function EWD_FEUP_Add_Stylesheet() {
    wp_register_style( 'ewd-feup-style', plugins_url('css/feu-styles.css', __FILE__) );
	wp_register_style( 'feup-yahoo-pure-css', plugins_url('css/feup-pure.css', __FILE__) );
    wp_enqueue_style( 'ewd-feup-style' );
	wp_enqueue_style( 'feup-yahoo-pure-css' );
}

function EWD_FEUP_Admin_Options() {
	global $EWD_FEUP_db_version;
	
	wp_enqueue_style( 'ewd-feup-admin', plugins_url("front-end-only-users/css/Admin.css"), array(), $EWD_FEUP_db_version);
	wp_enqueue_style( 'ewd-feup-spectrum', plugins_url("front-end-only-users/css/spectrum.css"));
}

$Show_TinyMCE = get_option("EWD_FEUP_Show_TinyMCE");
if ($Show_TinyMCE == "Yes") {
	add_filter( 'mce_buttons', 'EWD_FEUP_Register_TinyMCE_Buttons' );
	add_filter( 'mce_external_plugins', 'EWD_FEUP_Register_TinyMCE_Javascript' );
	add_action('admin_head', 'EWD_FEUP_Output_TinyMCE_Vars');
}

function EWD_FEUP_Register_TinyMCE_Buttons( $buttons ) {
   array_push( $buttons, 'separator', 'FEUP_Shortcodes' );
   return $buttons;
}

function EWD_FEUP_Register_TinyMCE_Javascript( $plugin_array ) {
   $plugin_array['FEUP_Shortcodes'] = plugins_url( '/js/tinymce-plugin.js',__FILE__ );

   return $plugin_array;
}

function EWD_FEUP_Output_TinyMCE_Vars() {
   global $EWD_FEUP_Full_Version;
   $Fields = EWD_FEUP_Get_All_Fields();

   echo "<script type='text/javascript'>";
   echo "var feup_premium = '" . $EWD_FEUP_Full_Version . "';\n";
   echo "var feup_fields = " . json_encode($Fields) . ";\n";
   echo "</script>";
}

function EWD_FEUP_Get_All_Fields() {
	global $wpdb, $ewd_feup_fields_table_name;

	$Fields = $wpdb->get_col("SELECT Field_Name FROM $ewd_feup_fields_table_name");
	array_unshift($Fields, "Username");

	return $Fields;
}

function EWD_FEUP_Show_Dashboard_Link() {
	set_transient('ewd-feup-admin-install-notice', true, 5);
}

function Run_EWD_FEUP_Tutorial() {
	update_option("EWD_FEUP_Run_Tutorial", "Yes");
}

if (get_option("EWD_FEUP_Run_Tutorial") == "Yes" and isset($_GET['page']) and $_GET['page'] == 'EWD-FEUP-options') {
	add_action( 'admin_enqueue_scripts', 'EWD_FEUP_Set_Pointers', 10, 1);
}

function EWD_FEUP_Set_Pointers($page) {
	  $Pointers = EWD_FEUP_Return_Pointers();

	  //Arguments: pointers php file, version (dots will be replaced), prefix
	  $manager = new EWD_FEUP_PointersManager( $Pointers, '1.0', 'ewd_feup_admin_pointers' );
	  $manager->parse();
	  $pointers = $manager->filter( $page );
	  if ( empty( $pointers ) ) { // nothing to do if no pointers pass the filter
	    return;
	  }
	  wp_enqueue_style( 'wp-pointer' );
	  $js_url = plugins_url( 'js/ewd-feup-pointers.js', __FILE__ );
	  wp_enqueue_script( 'ewd_feup_admin_pointers', $js_url, array('wp-pointer'), NULL, TRUE );
	  //data to pass to javascript
	  $data = array(
	    'next_label' => __( 'Next' ),
	    'close_label' => __('Close'),
	    'pointers' => $pointers
	  );
	  wp_localize_script( 'ewd_feup_admin_pointers', 'MyAdminPointers', $data );
	update_option("EWD_FEUP_Run_Tutorial", "No");
}

add_action('init', 'EWD_FEUP_Check_Return_Levels');
function EWD_FEUP_Check_Return_Levels() {
	if (time() > (get_option("EWD_FEUP_Last_User_Return_Check") + 3600)) {
		EWD_FEUP_Return_Users_To_Original_Levels(); //in Update_Admin_Databases
		update_option("EWD_FEUP_Last_User_Return_Check", time());
	}
}

add_action('activated_plugin','save_feup_error');
function save_feup_error(){
    update_option('plugin_error',  ob_get_contents());
    //file_put_contents(plugin_dir_path( __FILE__ )."Error.txt", ob_get_contents());
}

$EWD_FEUP_Full_Version = get_option("EWD_FEUP_Full_Version");

if (isset($_POST['EWD_FEUP_Upgrade_To_Full'])) {
	add_action('admin_init', 'EWD_FEUP_Upgrade_To_Full');
}

add_action('admin_init', 'EWD_FEUP_Remove');

include "blocks/ewd-feup-blocks.php";
include "Functions/CheckLoginCookie.php";
include "Functions/CreateLoginCookie.php";
include "Functions/Determine_Redirect_Page.php";
include "Functions/Error_Notices.php";
include "Functions/EWD_FEUP_Add_Captcha.php";
include "Functions/EWD_FEUP_Deactivation_Survey.php";
include "Functions/EWD_FEUP_Export_To_Excel.php";
include "Functions/EWD_FEUP_Facebook_Config.php";
include "Functions/EWD_FEUP_Full_Page_Restriction.php";
include "Functions/EWD_FEUP_Help_Pointers.php";
include "Functions/EWD_FEUP_Mailchimp_Subscriber_Sync.php";
include "Functions/EWD_FEUP_Output_Options.php";
include "Functions/EWD_FEUP_Pointers_Manager_Interface.php";
include "Functions/EWD_FEUP_Pointers_Manager_Class.php";
if (get_option("EWD_FEUP_Payment_Gateway") == "Stripe") {include "Functions/EWD_FEUP_Process_Stripe_Payment.php";}
include "Functions/EWD_FEUP_Remove.php";
include "Functions/EWD_FEUP_Return_Values.php";
include "Functions/EWD_FEUP_Send_Emails.php";
include "Functions/EWD_FEUP_Styling.php";
include "Functions/EWD_FEUP_Track_Page_Load.php";
include "Functions/EWD_FEUP_Twitter_Login.php";
include "Functions/EWD_FEUP_UWPM_Integration.php";
include "Functions/EWD_FEUP_Version_Reversion.php";
include "Functions/EWD_FEUP_Widgets.php";
include "Functions/EWD_FEUP_WooCommerce_Integration.php";
include "Functions/EWD_FEUP_WP_Users_Integration.php";
include "Functions/Full_Upgrade.php";
include "Functions/Initial_Data.php";
include "Functions/Install_EWD_FEUP.php";
include "Functions/Output_Buffering.php";
include "Functions/Prepare_Data_For_Insertion.php";
include "Functions/Process_Ajax.php";
include "Functions/Process_Front_End_Forms.php";
include "Functions/Public_Functions.php";
include "Functions/Update_Admin_Databases.php";
include "Functions/Update_EWD_FEUP_Content.php";
include "Functions/Update_EWD_FEUP_Tables.php";
include "Functions/EWD_FEUP_IPN.php"; //Needs to be last

include "Shortcodes/Insert_Account_Payment.php";
include "Shortcodes/Insert_Confirm_Forgot_Password.php";
include "Shortcodes/Insert_Edit_Account.php";
include "Shortcodes/Insert_Edit_Profile.php";
include "Shortcodes/Insert_Forgot_Password.php";
include "Shortcodes/Insert_Login_Form.php";
include "Shortcodes/Insert_Login_Logout_Toggle.php";
include "Shortcodes/Insert_Logout.php";
include "Shortcodes/Insert_Register_Form.php";
include "Shortcodes/Insert_Reset_Password.php";
include "Shortcodes/Insert_User_Data.php";
include "Shortcodes/Insert_User_List.php";
include "Shortcodes/Insert_User_Profile.php";
include "Shortcodes/Insert_User_Search.php";
include "Shortcodes/Privilege_Level.php";

// Set Login Options back to no selection
update_option("EWD_FEUP_Login_Options", "array()");

// Updates the UPCP database when required
if (get_option('EWD_FEUP_DB_Version') != $EWD_FEUP_db_version) {
	Update_EWD_FEUP_Tables();
}
//Add_EWD_FEUP_Payment('94', 'r.u.set.e.r@gmail.com', 'Q5L8QDQNHWMXG', '4K5980582T2170158', '2015-11-03 22:42:32', '2100-01-01', '', '');
//echo "Query: " . $wpdb->last_query . "<Br>";
/*if (get_option("EWD_FEUP_Update_RR_Rules") == "Yes") {
	  add_filter( 'query_vars', 'add_query_vars_filter' );
		add_filter('init', 'EWD_FEUP_Rewrite_Rules');
		update_option("EWD_FEUP_Update_RR_Rules", "No");
}*/
?>
