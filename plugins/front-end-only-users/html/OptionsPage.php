<?php 
	$Login_Time = get_option("EWD_FEUP_Login_Time");
	$Minimum_Password_Length = get_option("EWD_FEUP_Minimum_Password_Length");
	$Include_WP_Users = get_option("EWD_FEUP_Include_WP_Users");
	$Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Default_User_Level = get_option("EWD_Default_User_Level");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	$Required_Field_Symbol = get_option("EWD_FEUP_Required_Field_Symbol");
	$Show_TinyMCE = get_option("EWD_FEUP_Show_TinyMCE");

	$Use_Captcha = get_option("EWD_FEUP_Use_Captcha");
	$Allow_Level_Choice = get_option("EWD_FEUP_Allow_Level_Choice");
	$Track_Events = get_option("EWD_FEUP_Track_Events");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_On_Admin_Approval = get_option("EWD_FEUP_Email_On_Admin_Approval");
	$Admin_Email_On_Registration = get_option("EWD_FEUP_Admin_Email_On_Registration");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Email_Confirmation_Redirect = get_option("EWD_FEUP_Email_Confirmation_Redirect");
	$Default_User_Level = get_option("EWD_Default_User_Level");
	$Create_WordPress_Users = get_option("EWD_FEUP_Create_WordPress_Users");
	update_option("EWD_FEUP_Login_Options", "array()");
	$Login_Options = get_option("EWD_FEUP_Login_Options");
	if (!is_array($Login_Options)) {$Login_Options = array();}

	$Email_Messages_Array = get_option("EWD_FEUP_Email_Messages_Array");
	if (!is_array($Email_Messages_Array)) {$Email_Messages_Array = array();}
	
	$Facebook_App_ID = get_option("EWD_FEUP_Facebook_App_ID");
	$Facebook_Secret = get_option("EWD_FEUP_Facebook_Secret");
	$Twitter_Key = get_option("EWD_FEUP_Twitter_Key");
	$Twitter_Secret = get_option("EWD_FEUP_Twitter_Secret");
	
	$Payment_Frequency = get_option("EWD_FEUP_Payment_Frequency");
	$Payment_Types = get_option("EWD_FEUP_Payment_Types");
	$Membership_Cost = get_option("EWD_FEUP_Membership_Cost");
	$Free_Trial_Days = get_option("EWD_FEUP_Free_Trial_Days");
	$Levels_Payment_Array = get_option("EWD_FEUP_Levels_Payment_Array");
	$Pricing_Currency_Code = get_option("EWD_FEUP_Pricing_Currency_Code");
	$Thank_You_URL = get_option("EWD_FEUP_Thank_You_URL");
	$Discount_Codes_Array = get_option("EWD_FEUP_Discount_Codes_Array");
	$Payment_Gateway = get_option("EWD_FEUP_Payment_Gateway");
	$PayPal_Email_Address = get_option("EWD_FEUP_PayPal_Email_Address");
	$Stripe_Currency_Symbol = get_option("EWD_FEUP_Stripe_Currency_Symbol");
	$Stripe_Currency_Symbol_Placement = get_option("EWD_FEUP_Stripe_Currency_Symbol_Placement");
	$Stripe_Live_Secret = get_option("EWD_FEUP_Stripe_Live_Secret");
	$Stripe_Live_Publishable = get_option("EWD_FEUP_Stripe_Live_Publishable");
	$Stripe_Plan_ID = get_option("EWD_FEUP_Stripe_Plan_ID");

	$WooCommerce_Integration = get_option('EWD_FEUP_WooCommerce_Integration');
	$First_Name_Field = get_option('EWD_FEUP_WooCommerce_First_Name_Field');
	$Last_Name_Field = get_option('EWD_FEUP_WooCommerce_Last_Name_Field');
	$Company_Field = get_option('EWD_FEUP_WooCommerce_Company_Field');
	$Address_Line_One_Field = get_option('EWD_FEUP_WooCommerce_Address_Line_One_Field');
	$Address_Line_Two_Field = get_option('EWD_FEUP_WooCommerce_Address_Line_Two_Field');
	$City_Field = get_option('EWD_FEUP_WooCommerce_City_Field');
	$Postcode_Field = get_option('EWD_FEUP_WooCommerce_Postcode_Field');
	$Country_Field = get_option('EWD_FEUP_WooCommerce_Country_Field');
	$State_Field = get_option('EWD_FEUP_WooCommerce_State_Field');
	$Email_Field = get_option('EWD_FEUP_WooCommerce_Email_Field');
	$Phone_Field = get_option('EWD_FEUP_WooCommerce_Phone_Field');

	$First_Install_Version = floatval(get_option("EWD_FEUP_First_Install_Version"));

	$feup_Label_Login =  get_option("EWD_FEUP_Label_Login");
	$feup_Label_Logout =  get_option("EWD_FEUP_Label_Logout");
	$feup_Label_Username =  get_option("EWD_FEUP_Label_Username");
	$feup_Label_Username_Placeholder = get_option("EWD_FEUP_Label_Username_Placeholder");
	$feup_Label_Register =  get_option("EWD_FEUP_Label_Register");
	$feup_Label_Successful_Logout_Message =  get_option("EWD_FEUP_Label_Successful_Logout_Message");
	$feup_Label_Require_Login_Message =  get_option("EWD_FEUP_Label_Require_Login_Message");
	$feup_Label_Image_Number =  get_option("EWD_FEUP_Label_Image_Number");

	$feup_Label_Upgrade_Account =  get_option("EWD_FEUP_Label_Upgrade_Account");
	$feup_Label_Update_Account =  get_option("EWD_FEUP_Label_Update_Account");
	$feup_Label_Upgrade_Level_Message =  get_option("EWD_FEUP_Label_Upgrade_Level_Message");
	$feup_Label_Level =  get_option("EWD_FEUP_Label_Level");
	$feup_Label_Next =  get_option("EWD_FEUP_Label_Next");
	$feup_Label_Discount_Message =  get_option("EWD_FEUP_Label_Discount_Message");
	$feup_Label_Stripe_Submit_Payment_Text = get_option("EWD_FEUP_Label_Stripe_Submit_Payment_Text");
	$feup_Label_Discount_Code =  get_option("EWD_FEUP_Label_Discount_Code");
	$feup_Label_Use_Discount_Code =  get_option("EWD_FEUP_Label_Use_Discount_Code");
	$feup_Label_Edit_Profile =  get_option("EWD_FEUP_Label_Edit_Profile");
	$feup_Label_Current_File =  get_option("EWD_FEUP_Label_Current_File");
	$feup_Label_Current_Picture =  get_option("EWD_FEUP_Label_Current_Picture");
	$feup_Label_Update_Picture =  get_option("EWD_FEUP_Label_Update_Picture");
	$feup_Label_Confirm_Email_Message =  get_option("EWD_FEUP_Label_Confirm_Email_Message");
	$feup_Label_Incorrect_Confirm_Message =  get_option("EWD_FEUP_Label_Incorrect_Confirm_Message");
	$feup_Label_Captcha_Fail =  get_option("EWD_FEUP_Label_Captcha_Fail");
	$feup_Label_Login_Successful =  get_option("EWD_FEUP_Label_Login_Successful");
	$feup_Label_Login_Failed_Confirm_Email =  get_option("EWD_FEUP_Label_Login_Failed_Confirm_Email");
	$feup_Label_Select_Valid_Profile =  get_option("EWD_FEUP_Label_Select_Valid_Profile");
	$feup_Label_Nonlogged_Message =  get_option("EWD_FEUP_Label_Nonlogged_Message");
	$feup_Label_Low_Account_Level_Message =  get_option("EWD_FEUP_Label_Low_Account_Level_Message");
	$feup_Label_High_Account_Level_Message =  get_option("EWD_FEUP_Label_High_Account_Level_Message");
	$feup_Label_Wrong_Account_Level_Message =  get_option("EWD_FEUP_Label_Wrong_Account_Level_Message");
	$feup_Label_Restrict_Access_Message =  get_option("EWD_FEUP_Label_Restrict_Access_Message");
	$feup_Label_Login_Failed_Admin_Approval =  get_option("EWD_FEUP_Label_Login_Failed_Admin_Approval");
	$feup_Label_Login_Failed_Payment_Required =  get_option("EWD_FEUP_Label_Login_Failed_Payment_Required");
	$feup_Label_Login_Failed_Incorrect_Credentials =  get_option("EWD_FEUP_Label_Login_Failed_Incorrect_Credentials");

	$feup_Label_Please =  get_option("EWD_FEUP_Label_Please");
	$feup_Label_To_Continue =  get_option("EWD_FEUP_Label_To_Continue");
	$feup_Label_Password =  get_option("EWD_FEUP_Label_Password");
	$feup_Label_Repeat_Password =  get_option("EWD_FEUP_Label_Repeat_Password");
	$feup_Label_Password_Strength =  get_option("EWD_FEUP_Label_Password_Strength");
	$feup_Label_Reset_Password =  get_option("EWD_FEUP_Label_Reset_Password");
	$feup_Label_Email =  get_option("EWD_FEUP_Label_Email");
	$feup_Label_Reset_Code =  get_option("EWD_FEUP_Label_Reset_Code");
	$feup_Label_Change_Password =  get_option("EWD_FEUP_Label_Change_Password");
	$feup_Label_Too_Short =  get_option("EWD_FEUP_Label_Too_Short");
	$feup_Label_Mismatch =  get_option("EWD_FEUP_Label_Mismatch");
	$feup_Label_Weak =  get_option("EWD_FEUP_Label_Weak");
	$feup_Label_Good =  get_option("EWD_FEUP_Label_Good");
	$feup_Label_Strong =  get_option("EWD_FEUP_Label_Strong");

	$feup_Styling_Form_Font =  get_option("EWD_FEUP_Styling_Form_Font");
	$feup_Styling_Form_Font_Size =  get_option("EWD_FEUP_Styling_Form_Font_Size");
	$feup_Styling_Form_Font_Weight =  get_option("EWD_FEUP_Styling_Form_Font_Weight");
	$feup_Styling_Form_Font_Color =  get_option("EWD_FEUP_Styling_Form_Font_Color");
	$feup_Styling_Form_Margin =  get_option("EWD_FEUP_Styling_Form_Margin");
	$feup_Styling_Form_Padding =  get_option("EWD_FEUP_Styling_Form_Padding");
	$feup_Styling_Submit_Bg_Color =  get_option("EWD_FEUP_Styling_Submit_Bg_Color");
	$feup_Styling_Submit_Font =  get_option("EWD_FEUP_Styling_Submit_Font");
	$feup_Styling_Submit_Font_Color =  get_option("EWD_FEUP_Styling_Submit_Font_Color");
	$feup_Styling_Submit_Margin =  get_option("EWD_FEUP_Styling_Submit_Margin");
	$feup_Styling_Submit_Padding =  get_option("EWD_FEUP_Styling_Submit_Padding");

	$feup_Styling_Userlistings_Font =  get_option("EWD_FEUP_Styling_Userlistings_Font");
	$feup_Styling_Userlistings_Font_Size =  get_option("EWD_FEUP_Styling_Userlistings_Font_Size");
	$feup_Styling_Userlistings_Font_Weight =  get_option("EWD_FEUP_Styling_Userlistings_Font_Weight");
	$feup_Styling_Userlistings_Font_Color =  get_option("EWD_FEUP_Styling_Userlistings_Font_Color");
	$feup_Styling_Userlistings_Margin = get_option("EWD_FEUP_Styling_Userlistings_Margin");
	$feup_Styling_Userlistings_Padding = get_option("EWD_FEUP_Styling_Userlistings_Padding");
	$feup_Styling_Userprofile_Label_Font =  get_option("EWD_FEUP_Styling_Userprofile_Label_Font");
	$feup_Styling_Userprofile_Label_Font_Size =  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Size");
	$feup_Styling_Userprofile_Label_Font_Weight =  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Weight");
	$feup_Styling_Userprofile_Label_Font_Color =  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Color");
	$feup_Styling_Userprofile_Content_Font =  get_option("EWD_FEUP_Styling_Userprofile_Content_Font");
	$feup_Styling_Userprofile_Content_Font_Size =  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Size");
	$feup_Styling_Userprofile_Content_Font_Weight =  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Weight");
	$feup_Styling_Userprofile_Content_Font_Color =  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Color");

	if (isset($_POST['Display_Tab'])) {$Display_Tab = $_POST['Display_Tab'];}
	else {$Display_Tab = "";}

	$UWPM_Emails = get_posts(array('post_type' => 'uwpm_mail_template', 'posts_per_page' => -1));
?>

<div class="wrap feup-options-page-tabbed">
<div class="feup-options-submenu-div">
	<ul class="feup-options-submenu feup-options-page-tabbed-nav">
		<li><a id="Basic_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == '' or $Display_Tab == 'Basic') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Basic');">Basic</a></li>
		<li><a id="Premium_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Premium') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Premium');">Premium</a></li>
		<li><a id="Payment_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Payment') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Payment');">Payment</a></li>
		<li><a id="WooCommerce_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'WooCommerce') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('WooCommerce');">Commerce</a></li>
		<li><a id="Labelling_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Labelling') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Labelling');">Labelling</a></li>
		<li><a id="Styling_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Styling') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Styling');">Styling</a></li>
	</ul>
</div>



<div class="feup-options-page-tabbed-content">
<form method="post" action="admin.php?page=EWD-FEUP-options&DisplayPage=Options&Action=EWD_FEUP_UpdateOptions">
	<?php wp_nonce_field( 'EWD_FEUP_Admin_Nonce', 'EWD_FEUP_Admin_Nonce' );  ?>

	<input type='hidden' name='Display_Tab' value='<?php echo $Display_Tab; ?>' />

	<div id='Basic' class='feup-option-set<?php echo ( ($Display_Tab == '' or $Display_Tab == 'Basic') ? '' : ' feup-hidden' ); ?>'>

	<br />

	<div class="ewd-feup-shortcode-reminder">
		<div class="ewd-feup-shortcode-reminder-inside"><?php _e('For a list of the available shortcodes, please see the Shortcodes &amp; Attributes section <a href="https://www.etoilewebdesign.com/support-center/?Plugin=FEUP&Type=FAQs" target="_blank">here</a>.', 'front-end-only-users'); ?></div>
		<div class="ewd-feup-shortcode-reminder-inside"><?php _e('REMINDER: Type help after a shortcode (e.g. [login help) to see a list of its attributes and what they do.', 'front-end-only-users'); ?></div>
	</div>

	<br />

	<div class="ewd-feup-admin-section-heading"><?php _e('Basic Options', 'front-end-only-users'); ?></div>

	<table class="form-table">
	<tr>
		<th scope="row">Login Time</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Login Time</span></legend>
			<label title='Login Time'><input type='text' name='login_time' value='<?php echo $Login_Time; ?>' /><span> Minutes</span></label><br />
			<p>For reference: 1440 minutes in a day, 10080 minutes in a week, 43200 minutes in a 30-day month, 525600 minutes in a year</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Minimum Password Length</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Minimum Password Length</span></legend>
			<label title='Minimum Password Length'><input type='text' name='minimum_password_length' value='<?php echo $Minimum_Password_Length; ?>' /></label><br />
			<p>We recommend 6 or more, but at a minimum, this should be set to 3.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Include WordPress Users</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Include WordPress Users</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='include_wp_users' value='Yes' <?php if($Include_WP_Users == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='include_wp_users' value='No' <?php if($Include_WP_Users == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="include_wp_users" <?php if($Include_WP_Users == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should WordPress users automatically be imported into the plugin, so that they can access restricted areas and create profiles?<br />Warning: To remove access for WordPress users at a later date, you would need to delete all FEUP accounts for WordPress users, switching the option back to "No" only stops new users from being added.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Sign Up Email</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Sign Up Email</span></legend>
				<select name='sign_up_email'>
					<option value='-1' <?php echo ($Sign_Up_Email == -1 ? "selected" : ""); ?>>No</option>
					<?php foreach ($Email_Messages_Array as $Email_Message_Item) { ?>
						<option value='<?php echo $Email_Message_Item['ID']; ?>' <?php echo ($Sign_Up_Email == $Email_Message_Item['ID'] ? "selected" : ""); ?>><?php echo $Email_Message_Item['Name']; ?></option>
					<?php } ?>
					<optgroup label='Ultimate WP Mail'>
						<?php foreach ($UWPM_Emails as $Email) { ?>
								<option value='-<?php echo $Email->ID; ?>' <?php echo ($Sign_Up_Email * -1 == $Email->ID ? 'selected' : ''); ?>><?php echo $Email->post_title ?></option>
						<?php } ?>
					</optgroup>
				</select>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Custom CSS</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Custom CSS</span></legend>
			<textarea name='custom_css'><?php echo $Custom_CSS ?></textarea><br />
			<p>Custom CSS that should be included on any page that uses one of the FEUP shortcodes.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Use Crypt</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Use Crypt</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='use_crypt' value='Yes' <?php if($Use_Crypt == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='use_crypt' value='No' <?php if($Use_Crypt == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="use_crypt" <?php if($Use_Crypt == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should the plugin use crypt to encode user passwords? (Higher security)<br /><strong>Warning! All current user passwords will permanently stop working when switching between encoding methods!</strong></p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Username is Email</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Username is Email</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='username_is_email' value='Yes' <?php if($Username_Is_Email == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='username_is_email' value='No' <?php if($Username_Is_Email == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="username_is_email" <?php if($Username_Is_Email == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should your users register using their email addresses instead of by creating usernames?</p>
			</fieldset>
		</td>
		</tr>
		<th scope="row">Required Field Symbol</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Required Field Symbol</span></legend>
			<label title='Login Time'><input type='text' name='required_field_symbol' value='<?php echo $Required_Field_Symbol; ?>' /></label><br />
			<p>Appears next to each required field on the registration form. Default value is an asterisk (*).</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Shortcode Builder</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Shortcode Builder</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='show_tinymce' value='Yes' <?php if($Show_TinyMCE == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='show_tinymce' value='No' <?php if($Show_TinyMCE  == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="show_tinymce" <?php if($Show_TinyMCE == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should a shortcode builder be added to the tinyMCE toolbar in the page editor?</p>
			</fieldset>
		</td>
		</tr>
		</table>
	</div>

<div id='Premium' class='feup-option-set<?php echo ( $Display_Tab == 'Premium' ? '' : ' feup-hidden' ); ?>'>

	<br />

	<div class="ewd-feup-admin-section-heading"><?php _e('Premium Options', 'front-end-only-users'); ?></div>

	<table class="form-table ewd-feup-premium-options-table">
		<tr>
		<th scope="row">Captcha</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Captcha</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='use_captcha' value='Yes' <?php if($Use_Captcha == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='use_captcha' value='No' <?php if($Use_Captcha == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="use_captcha" <?php if($Use_Captcha == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should Captcha be added to the registration and forgot password forms to prevent spamming? (requires image-creation support for your PHP installation)</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Allow Level Choice</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Allow Level Choice</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='allow_level_choice' value='Yes' <?php if($Allow_Level_Choice == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='allow_level_choice' value='No' <?php if($Allow_Level_Choice == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="allow_level_choice" <?php if($Allow_Level_Choice == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should users be able to select their user level when registering?</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Track User Activity</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Track User Activity</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='track_events' value='Yes' <?php if($Track_Events == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='track_events' value='No' <?php if($Track_Events == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="track_events" <?php if($Track_Events == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>See what pages, attachments, images, etc. each user has looked at, in what order and when, to better tailor your content to your members.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Email Confirmation</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Email Confirmation</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='email_confirmation' value='Yes' <?php if($Email_Confirmation == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='email_confirmation' value='No' <?php if($Email_Confirmation == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="email_confirmation" <?php if($Email_Confirmation == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Make users confirm their email address before they can log in.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Email Confirmation Redirect</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Email Confirmation Redirect</span></legend>
				<input type='text' name='email_confirmation_redirect' value='<?php echo $Email_Confirmation_Redirect; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?>/>
				<p>URL of the page you would like users to be redirected to once they confirm their email address.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Admin Approval of Users</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Admin Approval of Users</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='admin_approval' value='Yes' <?php if($Admin_Approval == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='admin_approval' value='No' <?php if($Admin_Approval == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="admin_approval" <?php if($Admin_Approval == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Require users to be approved by an administrator in the WordPress back-end before they can log in.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Email On Admin Approval</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Email On Admin Approval</span></legend>
				<select name='email_on_admin_approval'>
					<option value='-1' <?php echo ($Email_On_Admin_Approval == -1 ? "selected" : ""); ?>>No</option>
					<?php foreach ($Email_Messages_Array as $Email_Message_Item) { ?>
						<option value='<?php echo $Email_Message_Item['ID']; ?>' <?php echo ($Email_On_Admin_Approval == $Email_Message_Item['ID'] ? "selected" : ""); ?>><?php echo $Email_Message_Item['Name']; ?></option>
					<?php } ?>
					<optgroup label='Ultimate WP Mail'>
						<?php foreach ($UWPM_Emails as $Email) { ?>
								<option value='-<?php echo $Email->ID; ?>' <?php echo ($Email_On_Admin_Approval * -1 == $Email->ID ? 'selected' : ''); ?>><?php echo $Email->post_title ?></option>
						<?php } ?>
					</optgroup>
				</select>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Admin Email On Registration</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Admin Email On Registration</span></legend>
				<select name='admin_email_on_registration'>
					<option value='-1' <?php echo ($Admin_Email_On_Registration == -1 ? "selected" : ""); ?>>No</option>
					<?php foreach ($Email_Messages_Array as $Email_Message_Item) { ?>
						<option value='<?php echo $Email_Message_Item['ID']; ?>' <?php echo ($Admin_Email_On_Registration == $Email_Message_Item['ID'] ? "selected" : ""); ?>><?php echo $Email_Message_Item['Name']; ?></option>
					<?php } ?>
					<optgroup label='Ultimate WP Mail'>
						<?php foreach ($UWPM_Emails as $Email) { ?>
								<option value='-<?php echo $Email->ID; ?>' <?php echo ($Admin_Email_On_Registration * -1 == $Email->ID ? 'selected' : ''); ?>><?php echo $Email->post_title ?></option>
						<?php } ?>
					</optgroup>
				</select>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Default User Level</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Default User Level</span></legend>
			<label title='Default User Level'><select name='default_user_level' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?>></label>
				<option value='0'>None (0)</option>
				<?php foreach ($Levels as $Level) {
						echo "<option value='" . $Level->Level_ID . "' ";
						if ($Default_User_Level == $Level->Level_ID) {echo "selected=selected";}
						echo ">" . $Level->Level_Name . " (" . $Level->Level_Privilege . ")</option>";
				}?> 
			</select>
			<p>Which level should users be set to when they register (created on the "Levels" tab)?</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Create WordPress User</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Create WordPress User</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='create_wordpress_users' value='Yes' <?php if($Create_WordPress_Users == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='create_wordpress_users' value='No' <?php if($Create_WordPress_Users == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="create_wordpress_users" <?php if($Create_WordPress_Users == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should a WordPress account also be created on registration for each user? This can be used with the login form attribute "wordpress_form" to let users log in to both the FEUP plugin and WordPress at the same time.<br />Only applies to new account and only works if "Username is Email" is set to "Yes".</p>
			</fieldset>
		</td>
		</tr>
		<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
			<tr class="ewd-feup-premium-options-table-overlay">
				<th colspan="2">
					<div class="ewd-feup-unlock-premium">
						<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front-End Only Users Premium">
						<p>Access this section by by upgrading to premium</p>
						<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
					</div>
				</th>
			</tr>
		<?php } ?>
	</table>

</div>

<div id='Payment' class='feup-option-set<?php echo ( $Display_Tab == 'Payment' ? '' : ' feup-hidden' ); ?>'>

	<br />

	<div class="ewd-feup-admin-section-heading"><?php _e('Payment Options', 'front-end-only-users'); ?></div>

	<table class="form-table ewd-feup-premium-options-table">
		<tr>
		<th scope="row">Payment Frequency</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Payment Frequency</span></legend>
			<label title='None' class='ewd-feup-admin-input-container'><input type='radio' name='payment_frequency' value='None' <?php if($Payment_Frequency == "None") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>None</span></label><br />
			<label title='One Time' class='ewd-feup-admin-input-container'><input type='radio' name='payment_frequency' value='One_Time' <?php if($Payment_Frequency == "One_Time") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>One Time</span></label><br />
			<label title='Yearly' class='ewd-feup-admin-input-container'><input type='radio' name='payment_frequency' value='Yearly' <?php if($Payment_Frequency == "Yearly") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>Yearly</span></label><br />
			<label title='Monthly' class='ewd-feup-admin-input-container'><input type='radio' name='payment_frequency' value='Monthly' <?php if($Payment_Frequency == "Monthly") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>Monthly</span></label><br />
			<p>Should payments (subscriptions) to your site be possible, and if so, how often are they charged?</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Payment Type</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Payment Type</span></legend>
			<label title='Membership' class='ewd-feup-admin-input-container'><input type='radio' name='payment_types' value='Membership' <?php if($Payment_Types == "Membership") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>Membership</span></label><br />
			<label title='Levels' class='ewd-feup-admin-input-container'><input type='radio' name='payment_types' value='Levels' <?php if($Payment_Types == "Levels") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>Levels</span></label><br />
			<p>Are payments necessary for membership, or only for certain levels?</p>
			</fieldset>
		</td>
		</tr>

		<tr>
		<th scope="row">Membership Cost</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Membership Cost</span></legend>
			<label title='Membership Cost'><input type='text' name='membership_cost' value='<?php echo $Membership_Cost; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /></label><br />
			<p>If payment type is set to membership, how much should a membership cost?</p>
			</fieldset>
		</td>
		</tr>

		<tr>
		<th scope="row">Level Payments</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Level Payments</span></legend>
			<table id='ewd-feup-level-payments-table'>
				<tr>
					<th class="ewd-feup-admin-no-info-button"></th>
					<th class="ewd-feup-admin-no-info-button">Level</th>
					<th class="ewd-feup-admin-no-info-button">Payment Amount</th>
					<!--<th>Cumulative?</th>-->
				</tr>
				<?php 
					$Counter = 0;
					if (!is_array($Levels_Payment_Array)) {$Levels_Payment_Array = array();}
					foreach ($Levels_Payment_Array as $Levels_Payment_Item) { 
						echo "<tr id='ewd-feup-level-payment-row-" . $Counter . "'>";
							echo "<td><a class='ewd-feup-delete-level-payment' data-levelpaymentid='" . $Counter . "'>Delete</a></td>";
							echo "<td><input type='hidden' name='Level_Payment_" . $Counter . "_Level' value='" . $Levels_Payment_Item['Level'] . "'/>";
							foreach ($Levels as $Level) {if ($Level->Level_ID == $Levels_Payment_Item['Level']) {echo $Level->Level_Name;}}
							echo "</td>";
							echo "<td><input type='hidden' name='Level_Payment_" . $Counter . "_Amount' value='" . $Levels_Payment_Item['Amount'] ."'/>" . $Levels_Payment_Item['Amount'] . "</td>";
							//echo "<td><input type='hidden' name='Level_Payment_" . $Counter . "_Cumulative' value='" . $Levels_Payment_Item['Cumulative'] . "'/>" . $Levels_Payment_Item['Cumulative'] . "</td>";
						echo "</tr>";
						$Counter++;
					} 
					//echo "<tr><td colspan='4'><a class='ewd-feup-add-level-payment' data-nextid='" . $Counter . "'>Add</a></td></tr>";
					echo "<tr><td colspan='3'><a class='ewd-feup-add-level-payment' data-nextid='" . $Counter . "'>Add</a></td></tr>";
				?>
			</table>
			<p>If payment type is set to levels, which levels should require payment and how much should that payment be?<br />
			<!--Is the payment amount cumulative? Ex. If payments are cumulative, if level 1 is $2 and level 2 is an additional $2, then the total cost for level 2 would be $4.--></p>
			</fieldset>
		</td>
		</tr>

		<tr>
		<th scope="row">Free Trial Days</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Free Trial Days</span></legend>
			<label title='Free Trial Days'><input type='text' name='free_trial_days' value='<?php echo $Free_Trial_Days; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /></label><br />
			<p>How many free trial days (maximum 90), if any, should someone get when they purchase a membership? Only applies to recurring payments (not "One-Time" payments) made via PayPal, without a discount code.</p>
			</fieldset>
		</td>
		</tr>
		
		<tr>
		<th scope="row">"Thank You" Page URL</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>"Thank You" Page URL</span></legend>
			<label title='Thank You Page URL'><input type='text' name='thank_you_url' value='<?php echo $Thank_You_URL; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /></label><br />
			<p>What page should customers be taken to after successfully completing a payment?</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Pricing Currency</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Pricing Currency</span></legend>
			<label title='Pricing Currency'></label><select name='pricing_currency_code' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?>>
			<option value="AUD" <?php if($Pricing_Currency_Code == "AUD") {echo " selected=selected";} ?>><?php _e("Australian Dollar", 'EWD_UASP'); ?></option>
			<option value="BRL" <?php if($Pricing_Currency_Code == "BRL") {echo " selected=selected";} ?>><?php _e("Brazilian Real", 'EWD_UASP'); ?></option>
			<option value="CAD" <?php if($Pricing_Currency_Code == "CAD") {echo " selected=selected";} ?>><?php _e("Canadian Dollar", 'EWD_UASP'); ?></option>
			<option value="CZK" <?php if($Pricing_Currency_Code == "CZK") {echo " selected=selected";} ?>><?php _e("Czech Koruna", 'EWD_UASP'); ?></option>
			<option value="DKK" <?php if($Pricing_Currency_Code == "DKK") {echo " selected=selected";} ?>><?php _e("Danish Krone", 'EWD_UASP'); ?></option>
			<option value="EUR" <?php if($Pricing_Currency_Code == "EUR") {echo " selected=selected";} ?>><?php _e("Euro", 'EWD_UASP'); ?></option>
			<option value="HKD" <?php if($Pricing_Currency_Code == "HKD") {echo " selected=selected";} ?>><?php _e("Hong Kong Dollar", 'EWD_UASP'); ?></option>
			<option value="HUF" <?php if($Pricing_Currency_Code == "HUF") {echo " selected=selected";} ?>><?php _e("Hungarian Forint", 'EWD_UASP'); ?></option>
			<option value="ILS" <?php if($Pricing_Currency_Code == "ILS") {echo " selected=selected";} ?>><?php _e("Israeli New Sheqel", 'EWD_UASP'); ?></option>
			<option value="JPY" <?php if($Pricing_Currency_Code == "JPY") {echo " selected=selected";} ?>><?php _e("Japanese Yen", 'EWD_UASP'); ?></option>
			<option value="MYR" <?php if($Pricing_Currency_Code == "MYR") {echo " selected=selected";} ?>><?php _e("Malaysian Ringgit", 'EWD_UASP'); ?></option>
			<option value="MXN" <?php if($Pricing_Currency_Code == "MXN") {echo " selected=selected";} ?>><?php _e("Mexican Peso", 'EWD_UASP'); ?></option>
			<option value="NOK" <?php if($Pricing_Currency_Code == "NOK") {echo " selected=selected";} ?>><?php _e("Norwegian Krone", 'EWD_UASP'); ?></option>
			<option value="NZD" <?php if($Pricing_Currency_Code == "NZD") {echo " selected=selected";} ?>><?php _e("New Zealand Dollar", 'EWD_UASP'); ?></option>
			<option value="PHP" <?php if($Pricing_Currency_Code == "PHP") {echo " selected=selected";} ?>><?php _e("Philippine Peso", 'EWD_UASP'); ?></option>
			<option value="PLN" <?php if($Pricing_Currency_Code == "PLN") {echo " selected=selected";} ?>><?php _e("Polish Zloty", 'EWD_UASP'); ?></option>
			<option value="GBP" <?php if($Pricing_Currency_Code == "GBP") {echo " selected=selected";} ?>><?php _e("Pound Sterling", 'EWD_UASP'); ?></option>
			<option value="RUB" <?php if($Pricing_Currency_Code == "RUB") {echo " selected=selected";} ?>><?php _e("Russian Ruble", 'EWD_UASP'); ?></option>
			<option value="SGD" <?php if($Pricing_Currency_Code == "SGD") {echo " selected=selected";} ?>><?php _e("Singapore Dollar", 'EWD_UASP'); ?></option>
			<option value="SEK" <?php if($Pricing_Currency_Code == "SEK") {echo " selected=selected";} ?>><?php _e("Swedish Krona", 'EWD_UASP'); ?></option>
			<option value="CHF" <?php if($Pricing_Currency_Code == "CHF") {echo " selected=selected";} ?>><?php _e("Swiss Franc", 'EWD_UASP'); ?></option>
			<option value="TWD" <?php if($Pricing_Currency_Code == "TWD") {echo " selected=selected";} ?>><?php _e("Taiwan New Dollar", 'EWD_UASP'); ?></option>
			<option value="THB" <?php if($Pricing_Currency_Code == "THB") {echo " selected=selected";} ?>><?php _e("Thai Baht", 'EWD_UASP'); ?></option>
			<option value="TRY" <?php if($Pricing_Currency_Code == "TRY") {echo " selected=selected";} ?>><?php _e("Turkish Lira", 'EWD_UASP'); ?></option>
			<option value="USD" <?php if($Pricing_Currency_Code == "USD") {echo " selected=selected";} ?>><?php _e("U.S. Dollar", 'EWD_UASP'); ?></option>
			</select>
			<p>What currency are your subscriptions priced in?</p>
			</fieldset>
		</td>
		</tr>

		<tr>
		<th scope="row">Discount Codes</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Discount Codes</span></legend>
			<table id='ewd-feup-discount-codes-table'>
				<tr>
					<th class="ewd-feup-admin-no-info-button"></th>
					<th class="ewd-feup-admin-no-info-button">Code</th>
					<th class="ewd-feup-admin-no-info-button">Discount Amount</th>
					<th class="ewd-feup-admin-no-info-button">Recurring Discount?</th>
					<th class="ewd-feup-admin-no-info-button">Applies To?</th>
					<th class="ewd-feup-admin-no-info-button">Expiry</th>
				</tr>
				<?php 
					$Counter = 0;
					if (!is_array($Discount_Codes_Array)) {$Discount_Codes_Array = array();}
					foreach ($Discount_Codes_Array as $Discount_Code_Item) { 
						echo "<tr id='ewd-feup-discount-code-row-" . $Counter . "'>";
							echo "<td><a class='ewd-feup-delete-discount-code' data-reminderid='" . $Counter . "'>Delete</a></td>";
							echo "<td><input type='hidden' name='Discount_Code_" . $Counter . "_Code' value='" . $Discount_Code_Item['Code'] . "'/>" . $Discount_Code_Item['Code'] . "</td>";
							echo "<td><input type='hidden' name='Discount_Code_" . $Counter . "_Amount' value='" . $Discount_Code_Item['Amount'] . "'/>" . $Discount_Code_Item['Amount'] . "</td>";
							echo "<td><input type='hidden' name='Discount_Code_" . $Counter . "_Recurring' value='" . $Discount_Code_Item['Recurring'] ."'/>" . $Discount_Code_Item['Recurring'] . "</td>";
							echo "<td><input type='hidden' name='Discount_Code_" . $Counter . "_Applicable' value='" . $Discount_Code_Item['Applicable'] . "'/>" . $Discount_Code_Item['Applicable'] . "</td>";
							echo "<td><input type='hidden' name='Discount_Code_" . $Counter . "_Expiry' value='" . $Discount_Code_Item['Expiry'] . "'/>" . $Discount_Code_Item['Expiry'] . "</td>";
						echo "</tr>";
						$Counter++;
					} 
					echo "<tr><td colspan='6'><a class='ewd-feup-add-discount-code' data-nextid='" . $Counter . "'>Add</a></td></tr>";
				?>
			</table>
			<p>Are you offering any discount codes on subscriptions?<br />
			A recurring discount code means the subscription price will always be reduced (for yearly or monthly payments)<br />
			Codes can be applicable only to specific membership levels (using the 'Applicable' field)</p>
			</fieldset>
		</td>
		</tr>

		<tr>
		<th scope="row">Payment Gateway</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Payment Gateway</span></legend>
			<label title='PayPal' class='ewd-feup-admin-input-container'><input type='radio' id='ewd-feup-paypal-option' name='payment_gateway' value='PayPal' <?php if($Payment_Gateway == "PayPal") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>PayPal</span></label><br />
			<label title='Stripe' class='ewd-feup-admin-input-container'><input type='radio' id='ewd-feup-stripe-option' name='payment_gateway' value='Stripe' <?php if($Payment_Gateway == "Stripe") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>Stripe</span></label><br />
			<p>Which payment gateway should be used to process payments?<br/>
			To use Stripe as your payment gateway, please make sure you have PHP version 5.3 or higher and please try out a test payment as well.</p>
			</fieldset>
		</td>
		</tr>

		<tr class='ewd-feup-paypal-option ewd-feup-specific-payment-option'>
		<th scope="row">PayPal Email Address</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>PayPal Email Address</span></legend>
			<label title='PayPal Email Address'><input type='text' name='paypal_email_address' value='<?php echo $PayPal_Email_Address; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /></label><br />
			<p>If PayPal payments are required or optional, what email address is associated with the PayPal account?</p>
			</fieldset>
		</td>
		</tr>

		<tr class='ewd-feup-stripe-option ewd-feup-specific-payment-option'>
		<th scope="row" class="ewd-feup-admin-no-info-button">Stripe Currency Symbol</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Stripe Currency Symbol</span></legend>
			<label title='Stripe Currency Symbol'><input type='text' name='stripe_currency_symbol' value='<?php echo $Stripe_Currency_Symbol; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /></label><br />
			</fieldset>
		</td>
		</tr>

		<tr class='ewd-feup-stripe-option ewd-feup-specific-payment-option'>
		<th scope="row" class="ewd-feup-admin-no-info-button">Stripe Currency Symbol Placement</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Stripe Currency Symbol Placement</span></legend>
			<label title='Before' class='ewd-feup-admin-input-container'><input type='radio' name='stripe_currency_symbol_placement' value='Before' <?php if($Stripe_Currency_Symbol_Placement == "Before") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>Before</span></label><br />
			<label title='After' class='ewd-feup-admin-input-container'><input type='radio' name='stripe_currency_symbol_placement' value='After' <?php if($Stripe_Currency_Symbol_Placement == "After") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /><span class='ewd-feup-admin-radio-button'></span> <span>After</span></label><br />
			</fieldset>
		</td>
		</tr>

		<tr class='ewd-feup-stripe-option ewd-feup-specific-payment-option'>
		<th scope="row">Stripe Live Secret</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Stripe Live Secret</span></legend>
			<label title='Stripe Live Secret'><input type='text' name='stripe_live_secret' value='<?php echo $Stripe_Live_Secret; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /></label><br />
			<p>Paste your live secret key.</p>
			</fieldset>
		</td>
		</tr>

		<tr class='ewd-feup-stripe-option ewd-feup-specific-payment-option'>
		<th scope="row">Stripe Live Publishable</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Stripe Live Publishable</span></legend>
			<label title='Stripe Live Publishable'><input type='text' name='stripe_live_publishable' value='<?php echo $Stripe_Live_Publishable; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /></label><br />
			<p>Paste your live publishable key.</p>
			</fieldset>
		</td>
		</tr>

		<tr class='ewd-feup-stripe-option ewd-feup-specific-payment-option'>
		<th scope="row">Stripe Plan ID</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Stripe Plan ID</span></legend>
			<label title='Stripe Live Publishable'><input type='text' name='stripe_plan_id' value='<?php echo $Stripe_Plan_ID; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /></label><br />
			<p>The ID of the Stripe payment plan you have set up. This only needs to be included if you are using recurring payments.<br />
			Please note that at this time, Stripe recurring payments cannot be used with multiple level payments or with discount codes.</p>
			</fieldset>
		</td>
		</tr>
		<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
			<tr class="ewd-feup-premium-options-table-overlay">
				<th colspan="2">
					<div class="ewd-feup-unlock-premium">
						<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front-End Only Users Premium">
						<p>Access this section by by upgrading to premium</p>
						<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
					</div>
				</th>
			</tr>
		<?php } ?>
	</table>
</div>

<div id='WooCommerce' class='feup-option-set<?php echo ( $Display_Tab == 'WooCommerce' ? '' : ' feup-hidden' ); ?>'>

	<br />

	<div class="ewd-feup-admin-section-heading"><?php _e('WooCommerce Integration Options', 'front-end-only-users'); ?></div>

		<table class="form-table ewd-feup-premium-options-table">
		<tr>
		<th scope="row">WooCommerce Integration</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>WooCommerce Integration</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='woocommerce_integration' value='Yes' <?php if($WooCommerce_Integration == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='woocommerce_integration' value='No' <?php if($WooCommerce_Integration == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="woocommerce_integration" <?php if($WooCommerce_Integration == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should checkout fields in WooCommerce automatically be filled in for logged in users?</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">First Name Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>First Name Field</span></legend>
			<label title='First Name Field'><input type='text' name='first_name_field' value='<?php echo $First_Name_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?>  /></label><br />
			<p>The name of the FEUP field that should be filled in as "First Name" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Last Name Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Last Name Field</span></legend>
			<label title='Last Name Field'><input type='text' name='last_name_field' value='<?php echo $Last_Name_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "Last Name" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Company Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Company Field</span></legend>
			<label title='Company Field'><input type='text' name='company_field' value='<?php echo $Company_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "Company" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Address Line One Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Address Line One Field</span></legend>
			<label title='Address Line One Field'><input type='text' name='address_line_one_field' value='<?php echo $Address_Line_One_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "Address Line One" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Address Line Two Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Address Line Two Field</span></legend>
			<label title='Address Line Two Field'><input type='text' name='address_line_two_field' value='<?php echo $Address_Line_Two_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "Address Line Two" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">City Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>City Field</span></legend>
			<label title='City Field'><input type='text' name='city_field' value='<?php echo $City_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "City" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Postcode Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Postcode Field</span></legend>
			<label title='Postcode Field'><input type='text' name='postcode_field' value='<?php echo $Postcode_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "Postcode" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Country Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Country Field</span></legend>
			<label title='Country Field'><input type='text' name='country_field' value='<?php echo $Country_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "Country" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">State Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>State Field</span></legend>
			<label title='State Field'><input type='text' name='state_field' value='<?php echo $State_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "State" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Email Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Email Field</span></legend>
			<label title='Email Field'><input type='text' name='email_field' value='<?php echo $Email_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "Email" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Phone Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Phone Field</span></legend>
			<label title='Phone Field'><input type='text' name='phone_field' value='<?php echo $Phone_Field; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> /></label><br />
			<p>The name of the FEUP field that should be filled in as "Phone" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
			<tr class="ewd-feup-premium-options-table-overlay">
				<th colspan="2">
					<div class="ewd-feup-unlock-premium">
						<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front-End Only Users Premium">
						<p>Access this section by by upgrading to premium</p>
						<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
					</div>
				</th>
			</tr>
		<?php } ?>
		</table>
		</div>
		<!-- Labelling -->
		<div id='Labelling' class='feup-option-set<?php echo ( $Display_Tab == 'Labelling' ? '' : ' feup-hidden' ); ?>'>
			<h2 id="labelling-options" class="feup-options-tab-title">Labelling Options</h2>

			<br />

			<div class="ewd-feup-admin-section-heading"><?php _e('Registration, login &amp; profile', 'front-end-only-users'); ?></div>

			<div class="ewd-feup-admin-styling-section">
				<div class="ewd-feup-admin-styling-subsection">
					<div class="ewd-admin-labelling-section">
						<label>
							<p><?php _e("Please", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_please' value='<?php echo $feup_Label_Please; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Login", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_login' value='<?php echo $feup_Label_Login; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("To Continue", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_to_continue' value='<?php echo $feup_Label_To_Continue; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Logout", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_logout' value='<?php echo $feup_Label_Logout; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Username", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_username' value='<?php echo $feup_Label_Username; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Username placeholder", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_username_placeholder' value='<?php echo $feup_Label_Username_Placeholder; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Regsiter", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_register' value='<?php echo $feup_Label_Register; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Level", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_level' value='<?php echo $feup_Label_Level; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Next", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_next' value='<?php echo $feup_Label_Next; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Edit Profile", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_edit_profile' value='<?php echo $feup_Label_Edit_Profile; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Current file:", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_current_file' value='<?php echo $feup_Label_Current_File; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Current Picture", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_current_picture' value='<?php echo $feup_Label_Current_Picture; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Update Picture", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_update_picture' value='<?php echo $feup_Label_Update_Picture; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Image Number", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_image_number' value='<?php echo $feup_Label_Image_Number; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Discount Code", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_discount_code' value='<?php echo $feup_Label_Discount_Code; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Use Discount Code", 'front-end-only-users')?></p>
						<input type='text' name='feup_label_use_discount_code' value='<?php echo $feup_Label_Use_Discount_Code; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Have a discount code? Enter it below.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_discount_message' value='<?php echo $feup_Label_Discount_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Submit a payment of", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_stripe_submit_payment_text' value='<?php echo $feup_Label_Stripe_Submit_Payment_Text; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
					</div>
				</div>
				<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
					<div class="ewd-feup-premium-options-table-overlay">
						<div class="ewd-feup-unlock-premium">
							<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front-End Only Users Premium">
							<p>Access this section by by upgrading to premium</p>
							<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
						</div>
					</div>
				<?php } ?>
			</div>

			<br />

			<div class="ewd-feup-admin-section-heading"><?php _e('Account &amp password', 'front-end-only-users'); ?></div>

			<div class="ewd-feup-admin-styling-section">
				<div class="ewd-feup-admin-styling-subsection">
					<p>Apply custom labelling to the update account page</p>
					<div class="ewd-admin-labelling-section">
						<label>
							<p><?php _e("Password", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_password' value='<?php echo $feup_Label_Password; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Repeat Password", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_repeat_password' value='<?php echo $feup_Label_Repeat_Password; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Password Strength", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_password_strength' value='<?php echo $feup_Label_Password_Strength; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Reset Password", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_reset_password' value='<?php echo $feup_Label_Reset_Password; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Upgrade Account", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_upgrade_account' value='<?php echo $feup_Label_Upgrade_Account; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Update Account", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_update_account' value='<?php echo $feup_Label_Update_Account; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Email", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_email' value='<?php echo $feup_Label_Email; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Reset Code", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_reset_code' value='<?php echo $feup_Label_Reset_Code; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Change Password", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_change_password' value='<?php echo $feup_Label_Change_Password; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Too Short", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_too_short' value='<?php echo $feup_Label_Too_Short; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Mismatch", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_mismatch' value='<?php echo $feup_Label_Mismatch; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Weak", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_weak' value='<?php echo $feup_Label_Weak; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Good", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_good' value='<?php echo $feup_Label_Good; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Strong", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_strong' value='<?php echo $feup_Label_Strong; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
					</div>
				</div>
				<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
					<div class="ewd-feup-premium-options-table-overlay">
						<div class="ewd-feup-unlock-premium">
							<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front-End Only Users Premium">
							<p>Access this section by by upgrading to premium</p>
							<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
						</div>
					</div>
				<?php } ?>
			</div>
			<br />

			<div class="ewd-feup-admin-section-heading"><?php _e('User Messages', 'front-end-only-users'); ?></div>

			<div class="ewd-feup-admin-styling-section">
				<div class="ewd-feup-admin-styling-subsection">
					<p>Modify the wording of user messages</p>
					<div class="ewd-admin-labelling-section full-wide">
						<label>
							<p><?php _e("You have been successfully logged out.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_successful_logout_message' value='<?php echo $feup_Label_Successful_Logout_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Please login to access this content.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_restrict_access_message' value='<?php echo $feup_Label_Restrict_Access_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("You must be logged in to access this page.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_restricted_message' value='<?php echo $feup_Label_Require_Login_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Select the level you'd like to upgrade to using the form below:", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_upgrade_level_message' value='<?php echo $feup_Label_Upgrade_Level_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Thanks for confirming your email address!", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_confirm_email_message' value='<?php echo $feup_Label_Confirm_Email_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Please select a valid user profile", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_select_valid_profile' value='<?php echo $feup_Label_Select_Valid_Profile; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Login successful", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_login_successful' value='<?php echo $feup_Label_Login_Successful; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Login failed - you need to confirm your email before you can log in", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_login_failed_confirm_email' value='<?php echo $feup_Label_Login_Failed_Confirm_Email; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Login failed - an administrator needs to approve your registration before you can log in", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_login_failed_admin_approval' value='<?php echo $feup_Label_Login_Failed_Admin_Approval; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("This content is only for non-logged in users", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_nonlogged_message' value='<?php echo $feup_Label_Nonlogged_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Sorry, your account level is too low to access this content.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_low_account_level_message' value='<?php echo $feup_Label_Low_Account_Level_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Sorry, your account level is too high to access this content.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_high_account_level_message' value='<?php echo $feup_Label_High_Account_Level_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Sorry, your account isn't the correct level to access this content.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_wrong_account_level_message' value='<?php echo $feup_Label_Wrong_Account_Level_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("The confirmation number provided was incorrect. Please contact the site administrator for assistance.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_incorrect_confirm_message' value='<?php echo $feup_Label_Incorrect_Confirm_Message; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("The Captcha text did not match the image.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_captcha_fail' value='<?php echo $feup_Label_Captcha_Fail; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Payment required. Please use the form below to pay your membership or subscription fee.", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_login_failed_payment_required' value='<?php echo $feup_Label_Login_Failed_Payment_Required; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
						<label>
							<p><?php _e("Login failed - incorrect username or password", 'front-end-only-users')?></p>
							<input type='text' name='feup_label_login_failed_incorrect_credentials' value='<?php echo $feup_Label_Login_Failed_Incorrect_Credentials; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
						</label>
					</div>
				</div>
				<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
					<div class="ewd-feup-premium-options-table-overlay">
						<div class="ewd-feup-unlock-premium">
							<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front-End Only Users Premium">
							<p>Access this section by by upgrading to premium</p>
							<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
						</div>
					</div>
				<?php } ?>
			</div>

		</div>

<!-- Styling -->
		<div id='Styling' class='feup-option-set<?php echo ( $Display_Tab == 'Styling' ? '' : ' feup-hidden' ); ?>'>
			<h2 id="styling-options" class="feup-options-tab-title">Styling Options</h2>

			<br />

			<div class="ewd-feup-admin-section-heading"><?php _e('Forms', 'front-end-only-users'); ?></div>

			<div class="ewd-feup-admin-styling-section">
				<div class="ewd-feup-admin-styling-subsection">
					<div class="ewd-feup-admin-styling-subsection-label"><?php _e('Form Fields', 'front-end-only-users'); ?></div>
					<div class="ewd-feup-admin-styling-subsection-content">
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Color', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<div class="ewd-feup-admin-styling-subsection-content-color-picker">
									<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"></div>
									<input type='text' class='ewd-feup-spectrum' name='feup_styling_form_font_color' value='<?php echo $feup_Styling_Form_Font_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
								</div>
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Family', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_form_font' value='<?php echo $feup_Styling_Form_Font; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Size', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_form_font_size' value='<?php echo $feup_Styling_Form_Font_Size; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Weight', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_form_font_weight' value='<?php echo $feup_Styling_Form_Font_Weight; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Row Margin', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_form_margin' value='<?php echo $feup_Styling_Form_Margin; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Row Padding', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_form_padding' value='<?php echo $feup_Styling_Form_Padding; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
					</div>
				</div>
				<div class="ewd-feup-admin-styling-subsection">
					<div class="ewd-feup-admin-styling-subsection-label"><?php _e('Submit Button', 'front-end-only-users'); ?></div>
					<div class="ewd-feup-admin-styling-subsection-content">
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Colors', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<div class="ewd-feup-admin-styling-subsection-content-color-picker">
									<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Background', 'front-end-only-users'); ?></div>
									<input type='text' class='ewd-feup-spectrum' name='feup_styling_submit_bg_color' value='<?php echo $feup_Styling_Submit_Bg_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
								</div>
								<div class="ewd-feup-admin-styling-subsection-content-color-picker">
									<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Text', 'front-end-only-users'); ?></div>
									<input type='text' class='ewd-feup-spectrum' name='feup_styling_submit_font_color' value='<?php echo $feup_Styling_Submit_Font_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
								</div>
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Family', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_submit_font' value='<?php echo $feup_Styling_Submit_Font; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Button Margin', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_submit_margin' value='<?php echo $feup_Styling_Submit_Margin; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Button Padding', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_submit_padding' value='<?php echo $feup_Styling_Submit_Padding; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
					</div>
				</div>
				<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
					<div class="ewd-feup-premium-options-table-overlay">
						<div class="ewd-feup-unlock-premium">
							<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front-End Only Users Premium">
							<p>Access this section by by upgrading to premium</p>
							<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
						</div>
					</div>
				<?php } ?>
			</div>

			<br />

			<div class="ewd-feup-admin-section-heading"><?php _e('Other Styling', 'front-end-only-users'); ?></div>

			<div class="ewd-feup-admin-styling-section">
				<div class="ewd-feup-admin-styling-subsection">
					<div class="ewd-feup-admin-styling-subsection-label"><?php _e('User Listings', 'front-end-only-users'); ?></div>
					<div class="ewd-feup-admin-styling-subsection-content">
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Color', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<div class="ewd-feup-admin-styling-subsection-content-color-picker">
									<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"></div>
									<input type='text' class='ewd-feup-spectrum' name='feup_styling_userlistings_font_color' value='<?php echo $feup_Styling_Userlistings_Font_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
								</div>
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Family', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userlistings_font' value='<?php echo $feup_Styling_Userlistings_Font; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Size', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userlistings_font_size' value='<?php echo $feup_Styling_Userlistings_Font_Size; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Font Weight', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userlistings_font_weight' value='<?php echo $feup_Styling_Userlistings_Font_Weight; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Row Margin', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userlistings_margin' value='<?php echo $feup_Styling_Userlistings_Margin; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Row Padding', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userlistings_padding' value='<?php echo $feup_Styling_Userlistings_Padding; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
					</div>
				</div>
				<div class="ewd-feup-admin-styling-subsection">
					<div class="ewd-feup-admin-styling-subsection-label"><?php _e('User Profile Page', 'front-end-only-users'); ?></div>
					<div class="ewd-feup-admin-styling-subsection-content">
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Colors', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<div class="ewd-feup-admin-styling-subsection-content-color-picker">
									<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Label', 'front-end-only-users'); ?></div>
									<input type='text' class='ewd-feup-spectrum' name='feup_styling_userprofile_label_font_color' value='<?php echo $feup_Styling_Userprofile_Label_Font_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
								</div>
								<div class="ewd-feup-admin-styling-subsection-content-color-picker">
									<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Content', 'front-end-only-users'); ?></div>
									<input type='text' class='ewd-feup-spectrum' name='feup_styling_userprofile_content_font_color' value='<?php echo $feup_Styling_Userprofile_Content_Font_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
								</div>
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Label Font Family', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userprofile_label_font' value='<?php echo $feup_Styling_Userprofile_Label_Font; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Label Font Size', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userprofile_label_font_size' value='<?php echo $feup_Styling_Userprofile_Label_Font_Size; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Label Font Weight', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userprofile_label_font_weight' value='<?php echo $feup_Styling_Userprofile_Label_Font_Weight; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Content Font Family', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userprofile_content_font' value='<?php echo $feup_Styling_Userprofile_Content_Font; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Content Font Size', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userprofile_content_font_size' Content='<?php echo $feup_Styling_Userprofile_Content_Font_Size; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
						<div class="ewd-feup-admin-styling-subsection-content-each">
							<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Content Font Weight', 'front-end-only-users'); ?></div>
							<div class="ewd-feup-admin-styling-subsection-content-right">
								<input type='text' class='ewd-feup-admin-font-size' name='feup_styling_userprofile_content_font_weight' value='<?php echo $feup_Styling_Userprofile_Content_Font_Weight; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes" and $First_Install_Version >= 2.7) {echo "disabled";} ?> />
							</div>
						</div>
					</div>
				</div>
				<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
					<div class="ewd-feup-premium-options-table-overlay">
						<div class="ewd-feup-unlock-premium">
							<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front-End Only Users Premium">
							<p>Access this section by by upgrading to premium</p>
							<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
						</div>
					</div>
				<?php } ?>
			</div>

		</div>

		<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

		</div>
		</div>