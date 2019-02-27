<?php
$Custom_CSS = get_option("EWD_UWPM_Custom_CSS");
$Track_Opens = get_option("EWD_UWPM_Track_Opens");
$Track_Clicks = get_option("EWD_UWPM_Track_Clicks");
$Add_Unsubscribe_Link = get_option("EWD_UWPM_Add_Unsubscribe_Link");
$Unsubscribe_Redirect_URL = get_option("EWD_UWPM_Unsubscribe_Redirect_URL");
$Add_Subscribe_Checkbox = get_option("EWD_UWPM_Add_Subscribe_Checkbox");
$Add_Unsubscribe_Checkbox = get_option("EWD_UWPM_Add_Unsubscribe_Checkbox");
$WooCommerce_Integration = get_option("EWD_UWPM_WooCommerce_Integration");
$Display_Interests = get_option("EWD_UWPM_Display_Interests");
$Display_Post_Interests = get_option("EWD_UWPM_Display_Post_Interests");
$Email_From_Name = get_option("EWD_UWPM_Email_From_Name");
$Email_From_Email = get_option("EWD_UWPM_Email_From_Email");

$Send_On_Actions = get_option("EWD_UWPM_Send_On_Actions");
if (!is_array($Send_On_Actions)) {$Send_On_Actions = array();}

$Subscribe_Label = get_option("EWD_UWPM_Subscribe_Label");
$Unsubscribe_Label = get_option("EWD_UWPM_Unsubscribe_Label");
$Login_To_Select_Topics_Label = get_option("EWD_UWPM_Login_To_Select_Topics_Label");
$Select_Topics_Label = get_option("EWD_UWPM_Select_Topics_Label");

$Emails = get_posts(array('post_type' => 'uwpm_mail_template', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));
$WC_Categories = get_terms(array('hide_empty' => false, 'taxonomy' => 'product_cat'));
$Products = get_posts(array('post_type' => 'product', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));

if (isset($_POST['Display_Tab'])) {$Display_Tab = $_POST['Display_Tab'];}
else {$Display_Tab = "";}
?>

<div class="wrap uwpm-options-page-tabbed">
	<div class="uwpm-options-submenu-div">
		<ul class="uwpm-options-submenu uwpm-options-page-tabbed-nav">
			<li><a id="Basic_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == '' or $Display_Tab == 'Basic') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Basic');">Basic</a></li>
			<li><a id="SendEvents_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'SendEvents') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('SendEvents');">Send Events</a></li>
			<li><a id="Labelling_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Labelling') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Labelling');">Labelling</a></li>
			<!-- <li><a id="Styling_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Styling') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Styling');">Styling</a></li> -->
		</ul>
	</div>


	<div class="uwpm-options-page-tabbed-content">
		<form method="post" action="admin.php?page=EWD-UWPM-Options&DisplayPage=Options&Action=EWD_UWPM_UpdateOptions">

			<input type='hidden' name='Display_Tab' value='<?php echo $Display_Tab; ?>' />

			<div id='Basic' class='uwpm-option-set<?php echo ( ($Display_Tab == '' or $Display_Tab == 'Basic') ? '' : ' uwpm-hidden' ); ?>'>

				<br />

				<div class="ewd-uwpm-shortcode-reminder">
					<?php _e('<strong>REMINDER:</strong> If you\'re having trouble with sending emails, we recommend you use a plugin such as <a href="https://wordpress.org/plugins/wp-mail-smtp/" target="_blank">WP Mail SMTP</a> to configure your SMTP settings.', 'order-tracking'); ?>
				</div>

				<br />

				<div class="ewd-uwpm-admin-section-heading"><?php _e('Basic Options', 'ultimate-wp-mail'); ?></div>

				<table class="form-table">
					<tr>
						<th scope="row">Custom CSS</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Custom CSS</span></legend>
								<label title='Custom CSS'></label><textarea class='ewd-uwpm-textarea' name='custom_css'> <?php echo $Custom_CSS; ?></textarea><br />
								<p>You can add custom CSS styles for your reviews in the box above.</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Add Unsubscribe Link</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Add Unsubscribe Link</span></legend>
								<div class="ewd-uwpm-admin-hide-radios">
									<label title='Yes'><input type='radio' name='add_unsubscribe_link' value='Yes' <?php if($Add_Unsubscribe_Link == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
									<label title='No'><input type='radio' name='add_unsubscribe_link' value='No' <?php if($Add_Unsubscribe_Link == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								</div>
								<label class="ewd-uwpm-admin-switch">
									<input type="checkbox" class="ewd-uwpm-admin-option-toggle" data-inputname="add_unsubscribe_link" <?php if($Add_Unsubscribe_Link == "Yes") {echo "checked='checked'";} ?>>
									<span class="ewd-uwpm-admin-switch-slider round"></span>
								</label>		
								<p>Should an unsubscribe link be added to the bottom of your emails?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Unsubscribe Redirect URL</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Unsubscribe Redirect URL</span></legend>
							<label title='Yes'><input type='text' name='unsubscribe_redirect_url' value='<?php echo $Unsubscribe_Redirect_URL; ?>' /></label><br />
							<p>What URL should someone be redirected to when they unsubscribe?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Add Subscribe Checkbox</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Add Subscribe Checkbox</span></legend>
								<div class="ewd-uwpm-admin-hide-radios">
									<label title='Yes'><input type='radio' name='add_subscribe_checkbox' value='Yes' <?php if($Add_Subscribe_Checkbox == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
									<label title='No'><input type='radio' name='add_subscribe_checkbox' value='No' <?php if($Add_Subscribe_Checkbox == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								</div>
								<label class="ewd-uwpm-admin-switch">
									<input type="checkbox" class="ewd-uwpm-admin-option-toggle" data-inputname="add_subscribe_checkbox" <?php if($Add_Subscribe_Checkbox == "Yes") {echo "checked='checked'";} ?>>
									<span class="ewd-uwpm-admin-switch-slider round"></span>
								</label>		
								<p>Should a subscribe checkbox be added to the bottom of WordPress registration forms and the edit profile page? (This can be used to email only those users who specifically sign up for emails)</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Add Unsubscribe Checkbox</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Add Unsubscribe Checkbox</span></legend>
								<div class="ewd-uwpm-admin-hide-radios">
									<label title='Yes'><input type='radio' name='add_unsubscribe_checkbox' value='Yes' <?php if($Add_Unsubscribe_Checkbox == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
									<label title='No'><input type='radio' name='add_unsubscribe_checkbox' value='No' <?php if($Add_Unsubscribe_Checkbox == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								</div>
								<label class="ewd-uwpm-admin-switch">
									<input type="checkbox" class="ewd-uwpm-admin-option-toggle" data-inputname="add_unsubscribe_checkbox" <?php if($Add_Unsubscribe_Checkbox == "Yes") {echo "checked='checked'";} ?>>
									<span class="ewd-uwpm-admin-switch-slider round"></span>
								</label>		
								<p>Should an unsubscribe checkbox be added to the bottom of WordPress registration forms and the edit profile page?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Track Opens</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Track Opens</span></legend>
								<div class="ewd-uwpm-admin-hide-radios">
									<label title='Yes'><input type='radio' name='track_opens' value='Yes' <?php if($Track_Opens == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
									<label title='No'><input type='radio' name='track_opens' value='No' <?php if($Track_Opens == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								</div>
								<label class="ewd-uwpm-admin-switch">
									<input type="checkbox" class="ewd-uwpm-admin-option-toggle" data-inputname="track_opens" <?php if($Track_Opens == "Yes") {echo "checked='checked'";} ?>>
									<span class="ewd-uwpm-admin-switch-slider round"></span>
								</label>		
								<p>Should the number of users who open each email be tracked?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Track Clicks</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Track Clicks</span></legend>
								<div class="ewd-uwpm-admin-hide-radios">
									<label title='Yes'><input type='radio' name='track_clicks' value='Yes' <?php if($Track_Clicks == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
									<label title='No'><input type='radio' name='track_clicks' value='No' <?php if($Track_Clicks == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								</div>
								<label class="ewd-uwpm-admin-switch">
									<input type="checkbox" class="ewd-uwpm-admin-option-toggle" data-inputname="track_clicks" <?php if($Track_Clicks == "Yes") {echo "checked='checked'";} ?>>
									<span class="ewd-uwpm-admin-switch-slider round"></span>
								</label>		
								<p>Should the number of clicks and the which links have been clicked be tracked?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">WooCommerce Integration</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>WooCommerce Integration</span></legend>
								<div class="ewd-uwpm-admin-hide-radios">
									<label title='Yes'><input type='radio' name='woocommerce_integration' value='Yes' <?php if($WooCommerce_Integration == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
									<label title='No'><input type='radio' name='woocommerce_integration' value='No' <?php if($WooCommerce_Integration == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
								</div>
								<label class="ewd-uwpm-admin-switch">
									<input type="checkbox" class="ewd-uwpm-admin-option-toggle" data-inputname="woocommerce_integration" <?php if($WooCommerce_Integration == "Yes") {echo "checked='checked'";} ?>>
									<span class="ewd-uwpm-admin-switch-slider round"></span>
								</label>		
								<p>Should automatic lists based on WooCommerce purchases be added to the plugin?</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Listed in Interests</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Listed in Interests</span></legend>
							<label title='post_categories' class='ewd-uwpm-admin-input-container'><input type='checkbox' name='display_interests[]' value='post_categories' <?php if(in_array('post_categories', $Display_Interests)) {echo "checked='checked'";} ?> /><span class='ewd-uwpm-admin-checkbox'></span> <span>Post Categories</span></label><br />
							<label title='uwpm_categories' class='ewd-uwpm-admin-input-container'><input type='checkbox' name='display_interests[]' value='uwpm_categories' <?php if(in_array('uwpm_categories', $Display_Interests)) {echo "checked='checked'";} ?> /><span class='ewd-uwpm-admin-checkbox'></span> <span>Ultimate WP Mail Categories</span></label><br />
							<label title='woocommerce_categories' class='ewd-uwpm-admin-input-container'><input type='checkbox' name='display_interests[]' value='woocommerce_categories' <?php if(in_array('woocommerce_categories', $Display_Interests)) {echo "checked='checked'";} ?> /><span class='ewd-uwpm-admin-checkbox'></span> <span>WooCommerce Categories</span></label><br />
							<div class='ewd-uwpm-hidden'><input type='checkbox' name='display_interests[]' value='none' checked='checked' /></div>
							<p>
								What interest options should be displayed by default when using the "Subcribe to Interests" shortcode or widget? These can be overwritten using shortcode attributes or widget options for specific instances.
								<br /><br />
								The available shortcode attributes are:
								<br /><br />
								- post_categories
								<br />
								- woocommerce_categories
								<br />
								- uwpm_categories (mail categories created in this plugin)
								<br /><br />
								So, for example, if, in the shortcode, you wanted to overwrite the options chosen here, and show just the WooCommmerce categories, you would use: [subscription-interests display_interests="woocommerce_categories"]
							</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Display Post Interests</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Display Post Interests</span></legend>
							<label title='Before' class='ewd-uwpm-admin-input-container'><input type='radio' name='display_post_interests' value='Before' <?php if($Display_Post_Interests == "Before") {echo "checked='checked'";} ?> /><span class='ewd-uwpm-admin-radio-button'></span> <span>Before</span></label><br />
							<label title='After' class='ewd-uwpm-admin-input-container'><input type='radio' name='display_post_interests' value='After' <?php if($Display_Post_Interests == "After") {echo "checked='checked'";} ?> /><span class='ewd-uwpm-admin-radio-button'></span> <span>After</span></label><br />
							<label title='None' class='ewd-uwpm-admin-input-container'><input type='radio' name='display_post_interests' value='None' <?php if($Display_Post_Interests == "None") {echo "checked='checked'";} ?> /><span class='ewd-uwpm-admin-radio-button'></span> <span>None</span></label><br />
							<p>
								Should an interests sign-up box be added to all posts, with the specific categories of that post as options?
								<br /><br />
								NOTE: You need to make sure at least one box is checked for the previous option (Listed in Interests) in order for this to have anything in it.
							</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Email "From" Name</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Email "From" Name</span></legend>
							<label title='Yes'><input type='text' name='email_from_name' value='<?php echo $Email_From_Name; ?>' /></label><br />
							<p>Who should the emails be sent from? Leave blank to use the default "From" address for your site.</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row">Email "From" Email Address</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Email "From" Email Address</span></legend>
							<label title='Yes'><input type='text' name='email_from_email' value='<?php echo $Email_From_Email; ?>' /></label><br />
							<p>What email address should the emails be sent from? Leave blank to use the default "From" address for your site.</p>
							</fieldset>
						</td>
					</tr>
				</table>
			</div>

			<div id='SendEvents' class='uwpm-option-set<?php echo ( $Display_Tab == 'SendEvents' ? '' : ' uwpm-hidden' ); ?>'>

				<br />

				<div class="ewd-uwpm-admin-section-heading"><?php _e('Send On Events', 'ultimate-wp-mail'); ?></div>
				<?php global $EWD_UWPM_Full_Version; ?>
				<table class='form-table ewd-uwpm-event-options ewd-uwpm-premium-options-table <?php echo $EWD_UWPM_Full_Version; ?>'>
					<tr>
						<td colspan='2'>
							<table class='ewd-uwpm-advanced-event-table'>
								<tr>
									<th class="ewd-uwpm-admin-no-info-button"><?php _e('Enable?', 'ultimate-wp-mail'); ?></th>
									<th class="ewd-uwpm-admin-no-info-button"><?php _e('Action Type', 'ultimate-wp-mail'); ?></th>
									<th class="ewd-uwpm-admin-no-info-button"><?php _e('Include', 'ultimate-wp-mail'); ?></th>
									<th class="ewd-uwpm-admin-no-info-button"><?php _e('Email to Send', 'ultimate-wp-mail'); ?></th>
									<th class="ewd-uwpm-admin-no-info-button" colspan='2'><?php _e('Delay', 'ultimate-wp-mail'); ?></th>
									<th class="ewd-uwpm-admin-no-info-button"></th>
								</tr>
								<?php 
									$Max_ID = 0;
									$Counter = 0;
									foreach ($Send_On_Actions as $Advanced_Send_On) { 
								?>
									<tr class='ewd-uwpm-wc-advanced-event' data-sendid='<?php echo $Advanced_Send_On['Send_On_ID']; ?>'>
										<td><input type='checkbox' name='Enable_Send_On_<?php echo $Counter; ?>' value='Yes' <?php echo ($Advanced_Send_On['Enabled'] == 'Yes' ? 'checked' : ''); ?> /></td>
										<td><select name='Send_On_Action_Type_<?php echo $Counter; ?>' class='ewd-uwpm-action-type-select'>
											<optgroup label="User Events">
												<option value='User_Registers' <?php echo ($Advanced_Send_On['Action_Type'] == 'User_Registers' ? 'selected' : ''); ?>><?php _e('On Registration', 'ultimate-wp-mail'); ?></option>
												<option value='User_Profile_Updated' <?php echo ($Advanced_Send_On['Action_Type'] == 'User_Profile_Updated' ? 'selected' : ''); ?>><?php _e('When Profile Updated', 'ultimate-wp-mail'); ?></option>
												<option value='User_Role_Changed' <?php echo ($Advanced_Send_On['Action_Type'] == 'User_Role_Changed' ? 'selected' : ''); ?>><?php _e('When Role Changes', 'ultimate-wp-mail'); ?></option>
												<option value='User_Password_Reset' <?php echo ($Advanced_Send_On['Action_Type'] == 'User_Password_Reset' ? 'selected' : ''); ?>><?php _e('Password is Reset', 'ultimate-wp-mail'); ?></option>
												<option value='User_X_Time_Since_Login' <?php echo ($Advanced_Send_On['Action_Type'] == 'User_X_Time_Since_Login' ? 'selected' : ''); ?>><?php _e('X Time Since Last Login', 'ultimate-wp-mail'); ?></option>
											</optgroup>
											<optgroup label="Site Events">
												<!-- <option value='Post_Published' <?php echo ($Advanced_Send_On['Action_Type'] == 'Post_Published' ? 'selected' : ''); ?>><?php _e('Post Published', 'ultimate-wp-mail'); ?></option> -->
												<option value='Post_Published_Interest' <?php echo ($Advanced_Send_On['Action_Type'] == 'Post_Published_Interest' ? 'selected' : ''); ?>><?php _e('Post Published in Interest', 'ultimate-wp-mail'); ?></option>
												<option value='New_Comment_On_Post' <?php echo ($Advanced_Send_On['Action_Type'] == 'New_Comment_On_Post' ? 'selected' : ''); ?>><?php _e('New Comment after Commenting', 'ultimate-wp-mail'); ?></option>
											</optgroup>
											<optgroup label="WooCommerce Events">
												<option value='WC_X_Time_Since_Cart_Abandoned' <?php echo ($Advanced_Send_On['Action_Type'] == 'WC_X_Time_Since_Cart_Abandoned' ? 'selected' : ''); ?>><?php _e('X Time after Cart Abandoned', 'ultimate-wp-mail'); ?></option>
												<option value='WC_X_Time_After_Purchase' <?php echo ($Advanced_Send_On['Action_Type'] == 'WC_X_Time_After_Purchase' ? 'selected' : ''); ?>><?php _e('X Time after Purchase', 'ultimate-wp-mail'); ?></option>
												<option value='Product_Added' <?php echo ($Advanced_Send_On['Action_Type'] == 'Product_Added' ? 'selected' : ''); ?>><?php _e('Product Added', 'ultimate-wp-mail'); ?></option>
												<option value='Product_Purchased' <?php echo ($Advanced_Send_On['Action_Type'] == 'Product_Purchased' ? 'selected' : ''); ?>><?php _e('Product Purchased', 'ultimate-wp-mail'); ?></option>
											</optgroup> 
										</select></td>
										<td><select name='Send_On_Includes_<?php echo $Counter; ?>' <?php echo (($Advanced_Send_On['Action_Type'] != 'Product_Added' and $Advanced_Send_On['Action_Type'] != 'Product_Purchased') ? 'disabled' : ''); ?> class='ewd-uwpm-send-on-includes-select'>
											<option value='Any' <?php echo ($Advanced_Send_On['Includes'] == 'Any' ? 'selected' : ''); ?>><?php _e('Any Product', 'ultimate-wp-mail'); ?></option>
											<optgroup label='Categories'>
												<?php foreach ($WC_Categories as $Category) { ?>
													<option value='C_<?php echo $Category->term_id; ?>' <?php echo ($Advanced_Send_On['Includes'] == 'C_' . $Category->term_id ? 'selected' : ''); ?>><?php echo $Category->name; ?></option>
												<?php } ?>
											</optgroup>
											<optgroup label='Products'>
												<?php foreach ($Products as $Product) { ?>
													<option value='P_<?php echo $Product->ID; ?>' <?php echo ($Advanced_Send_On['Includes'] == 'P_' . $Product->ID ? 'selected' : ''); ?>><?php echo $Product->post_title; ?></option>
												<?php } ?>
											</optgroup>
										</select></td>
										<td><select name='Send_On_Email_<?php echo $Counter; ?>'>
										<?php foreach ($Emails as $Email) {?>
											<option value='<?php echo $Email->ID; ?>' <?php echo ($Advanced_Send_On['Email_ID'] == $Email->ID ? 'selected' : ''); ?>><?php echo $Email->post_title; ?></option>
										<?php } ?>
										</select></td>
										<td><select name='Send_On_Interval_Count_<?php echo $Counter; ?>' <?php echo (($Advanced_Send_On['Action_Type'] != 'User_X_Time_Since_Login' and $Advanced_Send_On['Action_Type'] != 'WC_X_Time_Since_Cart_Abandoned' and $Advanced_Send_On['Action_Type'] != 'WC_X_Time_After_Purchase') ? 'disabled' : ''); ?> class='ewd-uwpm-send-on-interval-count-select'>
											<?php for ($i=1; $i<=31; $i++) {
												echo "<option value='" . $i . "' " . ($Advanced_Send_On['Interval_Count'] == $i ? 'selected' : '') . ">" . $i . "</option>";
											} ?>
										</select></td>
								 		<td><select name='Send_On_Interval_Unit_<?php echo $Counter; ?>' <?php echo (($Advanced_Send_On['Action_Type'] != 'User_X_Time_Since_Login' and $Advanced_Send_On['Action_Type'] != 'WC_X_Time_Since_Cart_Abandoned' and $Advanced_Send_On['Action_Type'] != 'WC_X_Time_After_Purchase') ? 'disabled' : ''); ?> class='ewd-uwpm-send-on-interval-unit-select'>
								 			<option value='Minutes' <?php echo ($Advanced_Send_On['Interval_Unit'] == 'Minutes' ? 'selected': ''); ?>>Minute(s)</option>
								 			<option value='Hours' <?php echo ($Advanced_Send_On['Interval_Unit'] == 'Hours' ? 'selected': ''); ?>>Hour(s)</option>
								 			<option value='Days' <?php echo ($Advanced_Send_On['Interval_Unit'] == 'Days' ? 'selected': ''); ?>>Day(s)</option>
								 			<option value='Weeks' <?php echo ($Advanced_Send_On['Interval_Unit'] == 'Weeks' ? 'selected': ''); ?>>Week(s)</option>
								 		</select></td>
										<td class='ewd-uwpm-delete-advanced-send-on'><input type='hidden' name='Send_On_<?php echo $Counter; ?>' value='<?php echo $Advanced_Send_On['Send_On_ID']; ?>' /><?php _e('Delete', 'ultimate-wp-mail'); ?></td>
									</tr>
								<?php
										$Max_ID = max($Max_ID, $Advanced_Send_On['Send_On_ID']);
										$Counter++; 
									} 
								?>
								<tr><td colspan='7'><a class='ewd-uwpm-add-advanced-send-on ewd-uwpm-fake-link ewd-uwpm-admin-add-button' data-maxid='<?php echo $Max_ID + 1; ?>' data-nextrow='<?php echo $Counter; ?>'><?php _e('Add New Send-On', 'ultimate-wp-mail'); ?></a></td></tr>
							</table>
						</td>
					</tr>
					<?php if ($EWD_UWPM_Full_Version != "Yes") { ?>
						<tr class="ewd-uwpm-premium-options-table-overlay">
							<th colspan="2">
								<div class="ewd-uwpm-unlock-premium">
									<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Ultimate WP Mail Premium">
									<p>Access this section by by upgrading to premium</p>
									<a href="https://www.etoilewebdesign.com/plugins/ultimate-wp-mail/#buy" class="ewd-uwpm-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
								</div>
							</th>
						</tr>
					<?php } ?>
				</table>
			</div>

			<div id='Labelling' class='uwpm-option-set<?php echo ( $Display_Tab == 'Labelling' ? '' : ' uwpm-hidden' ); ?>'>
				<h2 id='label-order-options' class='uwpm-options-page-tab-title'>Labelling Options</h2>

				<br />

				<div class="ewd-uwpm-admin-section-heading"><?php _e('Subscriptions', 'ultimate-wp-mail'); ?></div>

				<div class="ewd-uwpm-admin-styling-section <?php echo $EWD_UWPM_Full_Version; ?>">
					<div class="ewd-uwpm-admin-styling-subsection" style="padding-bottom: 20px;">
						<p>Replace the default text on Ultimate WP Mail pages</p>
						<div class="ewd-admin-labelling-section full-wide">
							<label>
								<p><?php _e("Subscribe", 'ultimate-wp-mail')?></p>
								<input type='text' name='subscribe_label' value='<?php echo $Subscribe_Label; ?>' <?php if ($EWD_UWPM_Full_Version != "Yes") {echo "disabled";} ?> />
							</label>
							<label>
								<p><?php _e("Unsubscribe", 'ultimate-wp-mail')?></p>
								<input type='text' name='unsubscribe_label' value='<?php echo $Unsubscribe_Label; ?>' <?php if ($EWD_UWPM_Full_Version != "Yes") {echo "disabled";} ?> />
							</label>
							<label>
								<p><?php _e("Log in to your account so that you can subscribe to topics you're interested in!", 'ultimate-wp-mail')?></p>
								<input type='text' name='login_to_select_topics_label' value='<?php echo $Login_To_Select_Topics_Label; ?>' <?php if ($EWD_UWPM_Full_Version != "Yes") {echo "disabled";} ?> />
							</label>
							<label>
								<p><?php _e("Select topics you're interested in below to receive emails when new items are posted!", 'ultimate-wp-mail')?></p>
								<input type='text' name='select_topics_label' value='<?php echo $Select_Topics_Label; ?>' <?php if ($EWD_UWPM_Full_Version != "Yes") {echo "disabled";} ?> />
							</label>
						</div>
					</div>
					<?php if ($EWD_UWPM_Full_Version != "Yes") { ?>
						<div class="ewd-uwpm-premium-options-table-overlay">
							<div class="ewd-uwpm-unlock-premium">
								<img src="<?php echo plugins_url( '../images/options-asset-lock.png', __FILE__ ); ?>" alt="Upgrade to Ultimate WP Mail Premium">
								<p>Access this section by by upgrading to premium</p>
								<a href="https://www.etoilewebdesign.com/plugins/ultimate-wp-mail/#buy" class="ewd-uwpm-dashboard-get-premium-widget-button" target="_blank">UPGRADE NOW</a>
							</div>
						</div>
					<?php } ?>
				</div>

			</div>

			<div id='Styling' class='uwpm-option-set<?php echo ( $Display_Tab == 'Styling' ? '' : ' uwpm-hidden' ); ?>'>
				<h2 id='label-styling-options' class='uwpm-options-page-tab-title'>Styling Options</h2>
				<!--<div id='uwpm-styling-options' class="uwpm-options-div uwpm-options-flex">
					<div class='uwpm-subsection'>
						<div class='uwpm-subsection-header'>Review Title</div>
						<div class='uwpm-subsection-content'>
							<div class='uwpm-option uwpm-styling-option'>
								<div class='uwpm-option-label'>Font Family</div>
								<div class='uwpm-option-input'><input type='text' name='uwpm_review_title_font' placeholder='ex: Ariel,Times,etc' value='<?php echo $uwpm_Review_Title_Font; ?>' <?php if ($uwpm_Full_Version != "Yes") {echo "disabled";} ?> /></div>
							</div>
						</div>
					</div>
				</div>-->
			</div>

				<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

			</div>
		</div>
