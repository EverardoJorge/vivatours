<?php 
	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	$Email_Field = get_option("EWD_FEUP_Email_Field");
	$Password_Reset_Email = get_option("EWD_FEUP_Password_Reset_Email");

	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

	$Email_Messages_Array = get_option("EWD_FEUP_Email_Messages_Array");
	if (!is_array($Email_Messages_Array)) {$Email_Messages_Array = array();}

	$Email_Reminder_Background_Color = get_option("EWD_FEUP_Email_Reminder_Background_Color");
	$Email_Reminder_Inner_Color = get_option("EWD_FEUP_Email_Reminder_Inner_Color");
	$Email_Reminder_Text_Color = get_option("EWD_FEUP_Email_Reminder_Text_Color");
	$Email_Reminder_Button_Background_Color = get_option("EWD_FEUP_Email_Reminder_Button_Background_Color");
	$Email_Reminder_Button_Text_Color = get_option("EWD_FEUP_Email_Reminder_Button_Text_Color");
	$Email_Reminder_Button_Background_Hover_Color = get_option("EWD_FEUP_Email_Reminder_Button_Background_Hover_Color");
	$Email_Reminder_Button_Text_Hover_Color = get_option("EWD_FEUP_Email_Reminder_Button_Text_Hover_Color");

	$Mailchimp_Integration = get_option("EWD_FEUP_Mailchimp_Integration");
	$Mailchimp_API_Key = get_option("EWD_FEUP_Mailchimp_API_Key");
	$Mailchimp_List_ID = get_option("EWD_FEUP_Mailchimp_List_ID");
	$Mailchimp_Sync_Fields = get_option("EWD_FEUP_Mailchimp_Sync_Fields");
	if (!is_array($Mailchimp_Sync_Fields)) {$Mailchimp_Sync_Fields = array();}

	$Levels = $wpdb->get_results("SELECT * FROM $ewd_feup_levels_table_name ORDER BY Level_Privilege ASC");
	//$Fields = $wpdb->get_results("SELECT Field_Name, Field_ID FROM $ewd_feup_fields_table_name");

	$UWPM_Banner_Time = get_option("EWD_FEUP_UWPM_Ask_Time");
	if ($UWPM_Banner_Time == "") {$UWPM_Banner_Time = 0;}
?>
<div class="wrap">
<h2>Email Settings</h2>

<?php if (time() > $UWPM_Banner_Time) { ?>
	<br />
	<div class="ewd-feup-uwpm-banner">
		<div class="ewd-feup-uwpm-banner-remove"><span>X</span></div>
		<div class="ewd-feup-uwpm-banner-icon">
			<img src='<?php echo EWD_FEUP_CD_PLUGIN_URL . "/images/ewd-uwpm-icon.png"; ?>' />
		</div>
		<div class="ewd-feup-uwpm-banner-text">
			<div class="ewd-feup-uwpm-banner-title">
				<?php _e("Customize Your Emails With", 'front-end-only-users'); ?>
				<span>Ultimate WP Mail</span>
			</div>
			<ul>
				<li>Completely FREE</li>
				<li>Uses Shortcodes and Variables</li>
				<li>Integrates Seamlessly</li>
				<li>Custom Subject Lines For Each Email</li>
				<li>Visual Builder</li>
				<li>An Easy Email Experience</li>
			</ul>
			<div class="ewd-feup-clear"></div>
		</div>
		<div class="ewd-feup-uwpm-banner-buttons">
			<a class="ewd-feup-uwpm-banner-download-button" href='plugin-install.php?s=ultimate+wp+mail&tab=search&type=term'>
				<?php _e("Download Now", 'front-end-only-users'); ?>
			</a>
			<span class="ewd-feup-uwpm-banner-reminder"><? _e("Remind Me Later", 'front-end-only-users'); ?></span>
		</div>
		<div class="ewd-feup-clear"></div>
	</div>
	<br / >
<?php } ?>

<div class="ewd-feup-shortcode-reminder-two">
	<?php _e('<strong>REMINDER:</strong> If you\'re having trouble with sending emails, we recommend you use a plugin such as <a href="https://wordpress.org/plugins/wp-mail-smtp/" target="_blank">WP Mail SMTP</a> to configure your SMTP settings.', 'front-end-only-users'); ?>
</div>

<form method="post" action="admin.php?page=EWD-FEUP-options&DisplayPage=Emails&Action=EWD_FEUP_UpdateEmailSettings">
<?php wp_nonce_field( 'EWD_FEUP_Admin_Nonce', 'EWD_FEUP_Admin_Nonce' );  ?>

<br />

<div class="ewd-feup-admin-section-heading"><?php _e('Emails', 'front-end-only-users'); ?></div>

<table class="form-table">
<?php if ($Username_Is_Email == "No") { ?>
<tr>
<th scope="row">Email Field Name</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Email Field Name</span></legend>
	<label title='Email Field Name'>
		<select name='email_field'> 
			<?php foreach ($Fields as $Field) { ?>
				<option value='<?php echo $Field->Field_Name; ?>' <?php echo ($Field->Field_Name == $Email_Field ? 'selected' : ''); ?>><?php echo $Field->Field_Name; ?></option>
			<?php } ?>
		</select>
	</label><br />
	<p>The name of the field that should be used to send the email to for your registration form, if "Username is Email" on the "Options" tab isn't set to "Yes". Note: this field can be left blank is "Username is Email" is set to "Yes".</p>
	</fieldset>
</td>
</tr>
<?php } ?>
<tr>
<th scope="row">Admin Email</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Admin Email</span></legend>
	<label title='Admin Email'><input type='text' name='admin_email' value='<?php echo $Admin_Email; ?>' /> </label><br />
	<p>If "Admin Email on Registration" is set to "Yes", what email address should the notification email be sent to?</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row" class="ewd-feup-admin-no-info-button">Password Reset Email</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Password Reset Email</span></legend>
		<select name='password_reset_email'>
			<?php foreach ($Email_Messages_Array as $Email_Message_Item) { ?>
				<option value='<?php echo $Email_Message_Item['ID']; ?>' <?php echo ($Password_Reset_Email == $Email_Message_Item['ID'] ? "selected" : ""); ?>><?php echo $Email_Message_Item['Name']; ?></option>
			<?php } ?>
			<optgroup label='Ultimate WP Mail'>
				<?php $UWPM_Emails = get_posts(array('post_type' => 'uwpm_mail_template', 'posts_per_page' => -1));
					foreach ($UWPM_Emails as $Email) { ?>
						<option value='-<?php echo $Email->ID; ?>' <?php echo ($Password_Reset_Email * -1 == $Email->ID ? 'selected' : ''); ?>><?php echo $Email->post_title ?></option>
				<?php } ?>
			</optgroup>
		</select>
	</fieldset>
</td>
</tr>

<tr class="ewd-feup-email-options-table-border ewd-feup-email-options-table-spacer">
	<th class="ewd-feup-admin-no-info-button"></th>
	<td></td>
</tr>
<tr class="ewd-feup-email-options-table-spacer">
	<th class="ewd-feup-admin-no-info-button"></th>
	<td></td>
</tr>

<tr>
	<th scope="row" class="ewd-feup-admin-no-info-button">Email Messages</th>
	<td>
		<fieldset><legend class="screen-reader-text"><span>Email Messages</span></legend>
		<table id='ewd-feup-email-messages-table'>
			<tr>
				<th class="ewd-feup-admin-no-info-button">Email Name</th>
				<th class="ewd-feup-admin-no-info-button">Message Subject</th>
				<th class="ewd-feup-admin-no-info-button">Message</th>
				<th class="ewd-feup-admin-no-info-button"></th>
			</tr>
			<?php
				$Counter = 0;
				$Max_ID = 0;
				foreach ($Email_Messages_Array as $Email_Message_Item) {
					echo "<tr id='ewd-feup-email-message-" . $Counter . "'>";
						echo "<td><input class='ewd-feup-array-text-input' type='text' name='Email_Message_" . $Counter . "_Name' value='" . $Email_Message_Item['Name']. "'/></td>";
						echo "<td><input class='ewd-feup-array-text-input' type='text' name='Email_Message_" . $Counter . "_Subject' value='" . $Email_Message_Item['Subject']. "'/></td>";
						echo "<td><textarea class='ewd-feup-array-textarea' name='Email_Message_" . $Counter . "_Body' rows='5'>" . stripslashes($Email_Message_Item['Message']) . "</textarea></td>";
						echo "<td><input type='hidden' name='Email_Message_" . $Counter . "_ID' value='" . $Email_Message_Item['ID'] . "' /><a class='ewd-feup-delete-message' data-messagecounter='" . $Counter . "'>Delete</a></td>";
					echo "</tr>";
					$Counter++;
					$Max_ID = max($Max_ID, $Email_Message_Item['ID']);
				}
				$Max_ID++;
				echo "<tr><td colspan='3'><a class='ewd-feup-add-email ewd-feup-admin-new-add-button' data-nextcounter='" . $Counter . "' data-maxid='" . $Max_ID . "'>&plus; " . __('ADD', 'front-end-only-users') . "</a></td></tr>";
			?>
		</table>
		<ul>
			<li>Use the table above to build emails for your users.</li>
			<li>You can use [section]...[/section] and [footer]...[/footer] to split up the content of your email. You can also include a link button, like so: [button link='LINK_URL_GOES_HERE']BUTTON_TEXT[/button]</li>
			<li>You can also put any of the field values for the fields you've created in the "Fields" tab by putting in [field-slug] (the field's slug surrounded by square brackets).</li>
			<li>Use the area at the bottom of the page to send yourself a sample email.</li>
		</ul>
		</fieldset>
	</td>
</tr>

<tr class="ewd-feup-email-options-table-border ewd-feup-email-options-table-spacer">
	<th class="ewd-feup-admin-no-info-button"></th>
	<td></td>
</tr>
<tr class="ewd-feup-email-options-table-spacer">
	<th class="ewd-feup-admin-no-info-button"></th>
	<td></td>
</tr>

<tr>
	<th scope="row">Send Sample Email</th>
	<td>
		<fieldset><legend class="screen-reader-text"><span>Send Sample Email</span></legend>
			<div class="ewd-feup-send-sample-email-labels">Select Email:</div>
			<select class='ewd-feup-test-email-selector'>
				<?php foreach ($Email_Messages_Array as $Email_Message_Item) { ?>
					<option value="<?php echo $Email_Message_Item['ID']; ?>"><?php echo $Email_Message_Item['Name']; ?></option>
				<?php } ?>
			</select><br/>
			<div class="ewd-feup-send-sample-email-labels">Email Address:</div>
			<input type='text' class='ewd-feup-test-email-address' />
			<p><button type='button' class='ewd-feup-send-test-email'>Send Sample Email</button></p>
			<p>Make sure that you click the "Save Changes" button below before sending the test message, to receive the most recent version of your email.</p>
		</fieldset>
	</td>
</tr>
</table>

<br />

<div class="ewd-feup-admin-section-heading"><?php _e('Premium Email Options', 'front-end-only-users'); ?></div>

<div class="ewd-feup-admin-styling-section">
	<div class="ewd-feup-admin-styling-subsection noBottomBorder">
		<div class="ewd-feup-admin-styling-subsection-label"><?php _e('Send Email to Users', 'front-end-only-users'); ?></div>
		<div class="ewd-feup-admin-styling-subsection-content">
			<div class="ewd-feup-admin-styling-subsection-content-each">
				<fieldset><legend class="screen-reader-text"><span>Send Email to Users</span></legend>
					<div class="ewd-feup-send-sample-email-labels">Select Email:</div>
					<select class='ewd-feup-email-blast-selector'>
						<?php foreach ($Email_Messages_Array as $Email_Message_Item) { ?>
							<option value="<?php echo $Email_Message_Item['ID']; ?>"><?php echo $Email_Message_Item['Name']; ?></option>
						<?php } ?>
					</select><br/>
					<div class="ewd-feup-send-sample-email-labels">Select User Level:</div>
					<select class='ewd-feup-blast-level-selector'>
						<option value="0">All Levels</option>
						<?php  foreach ($Levels as $Level) { ?>
							<option value='<?php echo $Level->Level_ID; ?>' ><?php echo $Level->Level_Name; ?></option>
						<?php } ?>
					</select><br/>
					<p><button type='button' class='ewd-feup-send-email-blast' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?>>Send Email Blast</button></p>
					<p>Make sure that you click the "Save Changes" button below before sending the test message, so users receive the most recent version of your email.</p>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="ewd-feup-admin-styling-subsection">
		<div class="ewd-feup-admin-styling-subsection-label"><?php _e('Colors', 'front-end-only-users'); ?></div>
		<div class="ewd-feup-admin-styling-subsection-content">
			<div class="ewd-feup-admin-styling-subsection-content-each">
				<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Email', 'front-end-only-users'); ?></div>
				<div class="ewd-feup-admin-styling-subsection-content-right">
					<div class="ewd-feup-admin-styling-subsection-content-color-picker">
						<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Background', 'front-end-only-users'); ?></div>
						<input type='text' class='ewd-feup-spectrum' name='email_reminder_background_color' value='<?php echo $Email_Reminder_Background_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> />
					</div>
					<div class="ewd-feup-admin-styling-subsection-content-color-picker">
						<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Inner Background', 'front-end-only-users'); ?></div>
						<input type='text' class='ewd-feup-spectrum' name='email_reminder_inner_color' value='<?php echo $Email_Reminder_Inner_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> />
					</div>
					<div class="ewd-feup-admin-styling-subsection-content-color-picker">
						<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Text', 'front-end-only-users'); ?></div>
						<input type='text' class='ewd-feup-spectrum' name='email_reminder_text_color' value='<?php echo $Email_Reminder_Text_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> />
					</div>
				</div>
			</div>
			<div class="ewd-feup-admin-styling-subsection-content-each">
				<div class="ewd-feup-admin-styling-subsection-content-label"><?php _e('Button', 'front-end-only-users'); ?></div>
				<div class="ewd-feup-admin-styling-subsection-content-right">
					<div class="ewd-feup-admin-styling-subsection-content-color-picker">
						<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Background', 'front-end-only-users'); ?></div>
						<input type='text' class='ewd-feup-spectrum' name='email_reminder_button_background_color' value='<?php echo $Email_Reminder_Button_Background_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> />
					</div>
					<div class="ewd-feup-admin-styling-subsection-content-color-picker">
						<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Text', 'front-end-only-users'); ?></div>
						<input type='text' class='ewd-feup-spectrum' name='email_reminder_button_text_color' value='<?php echo $Email_Reminder_Button_Text_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> />
					</div>
					<div class="ewd-feup-admin-styling-subsection-content-color-picker">
						<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Hover Background', 'front-end-only-users'); ?></div>
						<input type='text' class='ewd-feup-spectrum' name='email_reminder_button_background_hover_color' value='<?php echo $Email_Reminder_Button_Background_Hover_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> />
					</div>
					<div class="ewd-feup-admin-styling-subsection-content-color-picker">
						<div class="ewd-feup-admin-styling-subsection-content-color-picker-label"><?php _e('Hover Text', 'front-end-only-users'); ?></div>
						<input type='text' class='ewd-feup-spectrum' name='email_reminder_button_text_hover_color' value='<?php echo $Email_Reminder_Button_Text_Hover_Color; ?>' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> />
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
		<div class="ewd-feup-premium-options-table-overlay">
			<div class="ewd-feup-unlock-premium">
				<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Front End Users Premium">
				<p>Access this section by by upgrading to premium</p>
				<a href="https://www.etoilewebdesign.com/plugins/front-end-only-users/#buy" class="ewd-feup-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
			</div>
		</div>
	<?php } ?>
</div>

<br />

<div class="ewd-feup-admin-section-heading"><?php _e('MailChimp Integration Options', 'front-end-only-users'); ?></div>

<table class="form-table">
<tr>
	<th scope="row">Enable MailChimp Integration</th>
	<td>
		<fieldset><legend class="screen-reader-text"><span>Enable MailChimp Integration</span></legend>
			<div class="ewd-feup-admin-hide-radios">
				<label title='Yes'><input type='radio' name='mailchimp_integration' value='Yes' <?php if($Mailchimp_Integration == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
				<label title='No'><input type='radio' name='mailchimp_integration' value='No' <?php if($Mailchimp_Integration == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
			</div>
			<label class="ewd-feup-admin-switch">
				<input type="checkbox" class="ewd-feup-admin-option-toggle" data-inputname="mailchimp_integration" <?php if($Mailchimp_Integration == "Yes") {echo "checked='checked'";} ?>>
				<span class="ewd-feup-admin-switch-slider round"></span>
			</label>		
			<p>Should users automatically be added to your MailChimp email list when a new user is created?</p>
		</fieldset>
	</td>
</tr>
<tr>
<th scope="row">MailChimp API Key</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>MailChimp API Key</span></legend>
	<label title='Mailchimp API Key'><input type='text' name='mailchimp_api_key' value='<?php echo $Mailchimp_API_Key; ?>' /> </label><br />
	<p>Create an API key for your Mailchimp account, and enter that key in the field above.</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">MailChimp List ID</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>MailChimp List ID</span></legend>
	<label title='Mailchimp List ID'><input type='text' name='mailchimp_list_id' value='<?php echo $Mailchimp_List_ID; ?>' /> </label><br />
	<p>What is the ID of the MailChimp list that you'd like to add your users to?</p>
	</fieldset>
</td>
</tr>
<tr>
	<th scope="row" class="ewd-feup-admin-no-info-button">MailChimp Import Fields</th>
	<td>
		<fieldset><legend class="screen-reader-text"><span>MailChimp Import Fields</span></legend>
		<table id='ewd-feup-mc-fields-table'>
			<tr>
				<th class="ewd-feup-admin-no-info-button">Field Name</th>
				<th class="ewd-feup-admin-no-info-button">MailChimp Merge Field Tag</th>
				<th class="ewd-feup-admin-no-info-button"></th>
			</tr>
			<?php
				$Counter = 0;
				$Max_ID = 0;
				foreach ($Mailchimp_Sync_Fields as $Mailchimp_Sync_Field) {
					echo "<tr id='ewd-feup-mc-field-" . $Counter . "'>";
						echo "<td><select class='ewd-feup-array-select' name='Field_ID_" . $Counter . "'>";
						foreach ($Fields as $Field) {echo "<option value='" . $Field->Field_ID . "' " . ($Mailchimp_Sync_Field['Field_ID'] == $Field->Field_ID ? "selected" : "") . ">" . $Field->Field_Name . "</option>";}
						echo "</select></td>";
						echo "<td><input class='ewd-feup-array-text-input' type='text' name='Mailchimp_Field_ID_" . $Counter . "' value='" . $Mailchimp_Sync_Field['Mailchimp_Field_ID']. "'/></td>";
						echo "<td><a class='ewd-feup-delete-mc-field' data-mcfieldcounter='" . $Counter . "'>Delete</a></td>";
					echo "</tr>";
					$Counter++;
				}
				echo "<tr><td colspan='2'><a class='ewd-feup-add-mc-field ewd-feup-admin-new-add-button' data-nextcounter='" . $Counter . "'>&plus; " . __('ADD', 'front-end-only-users') . "</a></td></tr>";
			?>
		</table>
		<ul>
			<li>Use the table above to select fields to import into MailChimp.</li>
		</ul>
		</fieldset>
	</td>
</tr>
</table>
<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

</div>