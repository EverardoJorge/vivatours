<?php
add_action( 'add_meta_boxes', 'EWD_UWPM_Add_Meta_Boxes' );
function EWD_UWPM_Add_Meta_Boxes () {
	add_meta_box("form-meta", __("Build Email", 'ultimate-wp-mail'), "EWD_UWPM_Meta_Box", "uwpm_mail_template", "normal", "high");
	add_meta_box("ewd-uwpm-send-events", __("Send Events", 'ultimate-wp-mail'), "EWD_UWPM_Sent_Statistics_Box", "uwpm_mail_template", "normal", "high");
	add_meta_box("ewd-uwpm-send-mail-meta-box", __("Send Email",'ultimate-wp-mail'), "EWD_UWPM_Send_Mail_Metabox", array("uwpm_mail_template"), "side", "high", null);
}

add_filter( 'get_sample_permalink_html', 'EWD_UWPM_Remove_Permalink', 11, 2 );
function EWD_UWPM_Remove_Permalink( $content, $post_id ) {
  $post = get_post($post_id);

  if ($post->post_type == 'uwpm_mail_template') {
  	$content = "<div id='ewd-uwpm-send-test-email-overlay' class='ewd-uwpm-preview-dark-overlay ewd-uwpm-hidden'></div>";
	$content .= "<div id='ewd-uwpm-send-test-email' class='ewd-uwpm-hidden'>";
	$content .= "<div id='ewd-uwpm-send-test-email-close'>Close</div>";
	$content .= "<form>";
	$content .= "<div id='ewd-uwpm-send-test-email-inside'>";
	$content .= "<label>" . __('Receiving Email Address:', 'ultimate-wp-mail') . "</label>";
	$content .= "<input type='text' name='EWD_UWPM_Test_Email_Address' placeholder='Email Address...' />";
	$content .= "</div>";
	$content .= "</form><br />";
	$content .= "<button id='ewd-uwpm-send-test' class='button button-primary button-large'>" . __('Send Test E-mail', 'ultimate-wp-mail') . "</button>";
	$content .= "</div>";

	$content .= "<div id='ewd-uwpm-send-reponse-message' class='ewd-uwpm-hidden'></div>";
  }

  return $content;
}

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function EWD_UWPM_Meta_Box( $post ) {
	global $wpdb;
	global $UWPM_Custom_Element_Types;
	global $UWPM_Custom_Element_Section_Types;

	$User_Fields = EWD_UWPM_Get_User_Fields();
	$Custom_Element_Count = 0;

	$Current_Email_Template = get_post_meta($post->ID, 'EWD_UWPM_Mail_Content', true);
	$Plain_Text_Email = get_post_meta($post->ID, 'EWD_UWPM_Plain_Text_Mail_Content', true);

	$Content_Alignment = get_post_meta($post->ID, 'EWD_UWPM_Content_Alignment', true);
	$Max_Width = get_post_meta($post->ID, 'EWD_UWPM_Max_Width', true);
	$Email_Background_Color = get_post_meta($post->ID, 'EWD_UWPM_Email_Background_Color', true);
	$Body_Background_Color = get_post_meta($post->ID, 'EWD_UWPM_Body_Background_Color', true);
	$Block_Background_Color = get_post_meta($post->ID, 'EWD_UWPM_Block_Background_Color', true);
	$Block_Border = get_post_meta($post->ID, 'EWD_UWPM_Block_Border', true);

	?>
	<div class="ewd-uwpm-full-screen-inside">
	<?php

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'EWD_UWPM_Save_Meta_Box_Data', 'EWD_UWPM_meta_box_nonce' ); ?>

		<input type='hidden' id='ewd-uwpm-email-id' value='<?php echo $post->ID;?>' />

		<div class='ewd-uwpm-element-selector'>
			<div class='ewd-uwpm-columns-container'>
				<div class='ewd-uwpm-column-type'  data-type='1'><?php _e('1-Column', 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-column-type'  data-type='2'><?php _e('2-Column', 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-column-type'  data-type='3'><?php _e('3-Column', 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-column-type'  data-type='4'><?php _e('4-Column', 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-column-type'  data-type='1-2'><?php _e('1:2 Column', 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-column-type'  data-type='2-1'><?php _e('2:1 Column', 'ultimate-wp-mail'); ?></div>
			</div>
			<div class='ewd-uwpm-full-screen'><?php _e("Full Screen Editor", 'ultimate-wp-mail'); ?></div>
			<div class='ewd-uwpm-exit-full-screen ewd-uwpm-hidden'><?php _e("Exit Full Screen Editor", 'ultimate-wp-mail'); ?></div>
			<div class='ewd-uwpm-clear'></div>
		</div>

		<div id='ewd-uwpm-email-input' class='ewd-uwpm-hidden'>
			<textarea name='Email_Content'><?php echo $Current_Email_Template; ?></textarea>
		</div>

		<?php if (strlen($Current_Email_Template) == 0) { ?>
			<div class='ewd-uwpm-email-templates' data-pluginlink='<?php echo EWD_UWPM_CD_PLUGIN_URL; ?>'>
				<p><?php _e("Start building with an email template:", 'ultimate-wp-mail'); ?></p>
				<div class='ewd-uwpm-template' data-template='newsletter'>
					<img src='<?php echo EWD_UWPM_CD_PLUGIN_URL . "/images/templates/Newsletter.png"; ?>' />
					<div class='ewd-uwpm-template-title'><?php _e("Newsletter", 'ultimate-wp-mail'); ?></div>
				</div>
				<div class='ewd-uwpm-template' data-template='product_showcase'>
					<img src='<?php echo EWD_UWPM_CD_PLUGIN_URL . "/images/templates/Product_Showcase.png"; ?>' />
					<div class='ewd-uwpm-template-title'><?php _e("Product Showcase", 'ultimate-wp-mail'); ?></div>
				</div>
				<div class='ewd-uwpm-template' data-template='thank_you'>
					<img src='<?php echo EWD_UWPM_CD_PLUGIN_URL . "/images/templates/Thank_You.png"; ?>' />
					<div class='ewd-uwpm-template-title'><?php _e("Thank You", 'ultimate-wp-mail'); ?></div>
				</div>
				<div class='ewd-uwpm-template' data-template='promotion'>
					<img src='<?php echo EWD_UWPM_CD_PLUGIN_URL . "/images/templates/Promotion.png"; ?>' />
					<div class='ewd-uwpm-template-title'><?php _e("Promotion/Discount", 'ultimate-wp-mail'); ?></div>
				</div>
				<div class='ewd-uwpm-template' data-template='follow_up'>
					<img src='<?php echo EWD_UWPM_CD_PLUGIN_URL . "/images/templates/Follow_Up.png"; ?>' />
					<div class='ewd-uwpm-template-title'><?php _e("Follow-Up Email", 'ultimate-wp-mail'); ?></div>
				</div>
				<div class='ewd-uwpm-template' data-template='tutorial'>
					<img src='<?php echo EWD_UWPM_CD_PLUGIN_URL . "/images/templates/Tutorial.png"; ?>' />
					<div class='ewd-uwpm-template-title'><?php _e("Tutorial/Help for Product/Service", 'ultimate-wp-mail'); ?></div>
				</div>
				<div class='ewd-uwpm-clear'></div>
				<hr>
				<p><?php _e("Or start building an email manually using the buttons at the top.", 'ultimate-wp-mail'); ?></p>
			</div>
		<?php } ?>

		<div id='ewd-uwpm-visual-builder-area'>
			<?php echo $Current_Email_Template; ?>
			<div id='ewd-uwpm-template-information' data-sectioncount='<?php echo substr_count($Current_Email_Template, 'class="ewd-uwpm-section'); ?>' ></div>
			<div class='ewd-uwpm-clear'></div>
		</div>

		<div id='ewd-uwpm-email-styling-options'>
			<div class='ewd-uwpm-styling-option'>
				<h4><?php _e("Styling Options", 'ultimate-wp-mail'); ?></h4>
				<div class='ewd-uwpm-styling-label'><?php _e("Content Alignment:", 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-styling-input'>
					<select name='Content_Alignment'>
						<option value='left' <?php echo ($Content_Alignment == 'left' ? 'selected' : ''); ?> ><?php _e('Left', 'ultimate-wp-mail'); ?></option>
						<option value='center' <?php echo (($Content_Alignment != 'left' and $Content_Alignment != 'right') ? 'selected' : ''); ?> ><?php _e('Center', 'ultimate-wp-mail'); ?></option>
						<option value='right' <?php echo ($Content_Alignment == 'right' ? 'selected' : ''); ?> ><?php _e('Right', 'ultimate-wp-mail'); ?></option>
					</select>
				</div>
			</div>
			<div class='ewd-uwpm-styling-option'>
				<div class='ewd-uwpm-styling-label'><?php _e("Max Email Width:", 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-styling-input'><input type='text' name='Max_Width' value='<?php echo $Max_Width; ?>' /></div>
			</div>
			<div class='ewd-uwpm-styling-option'>
				<div class='ewd-uwpm-styling-label'><?php _e("Email Background Color:", 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-styling-input ewd-uwpm-spectrum'><input type='text' name='Email_Background_Color' value='<?php echo $Email_Background_Color; ?>' /></div>
			</div>
			<div class='ewd-uwpm-styling-option'>
				<div class='ewd-uwpm-styling-label'><?php _e("Body Background Color:", 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-styling-input ewd-uwpm-spectrum'><input type='text' name='Body_Background_Color' value='<?php echo $Body_Background_Color; ?>' /></div>
			</div>
			<div class='ewd-uwpm-styling-option'>
				<div class='ewd-uwpm-styling-label'><?php _e("Blocks Background Color:", 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-styling-input ewd-uwpm-spectrum'><input type='text' name='Block_Background_Color' value='<?php echo $Block_Background_Color; ?>' /></div>
			</div>
			<div class='ewd-uwpm-styling-option'>
				<div class='ewd-uwpm-styling-label'><?php _e("Blocks Border:", 'ultimate-wp-mail'); ?></div>
				<div class='ewd-uwpm-styling-input'><input type='text' name='Block_Border' value='<?php echo $Block_Border; ?>' /></div>
			</div>
			<p><?php _e('Styling settings will only display in emails after saving!', 'ultimate-wp-mail'); ?></p>
			<div class='ewd-uwpm-clear'></div>
		</div>

		<div id='ewd-uwpm-section-editor' class='ewd-uwpm-hidden' data-sectionid=''>
			<div id='ewd-uwpm-section-editor-text-editor'>
				<?php wp_editor("", "ewd-uwpm-editor-textarea", array('textarea_rows' => 6)); ?>
			</div>
			<!--<div id='ewd-uwpm-section-editor-advanced-button'><?php _e('Advanced', 'ultimate-wp-mail'); ?></div>-->
			<div id='ewd-uwpm-section-editor-save-button'><?php _e('Save', 'ultimate-wp-mail'); ?></div>
			<div id='ewd-uwpm-section-editor-advanced-options'></div>
			<div class='ewd-uwpm-clear'></div>
		</div>

		<div class="ewd-uwpm-clear"></div>

		<div id='ewd-uwpm-ajax-email-preview' class='ewd-uwpm-hidden'>
			<div id='ewd-uwpm-ajax-email-preview-exit'><?php _e("Close Preview", 'ultimate-wp-mail'); ?></div>
			<div id='ewd-uwpm-ajax-email-preview-body'></div>
		</div>
		<div id='ewd-uwpm-email-preview-overlay' class='ewd-uwpm-preview-dark-overlay ewd-uwpm-hidden'></div>

		<div class="ewd-uwpm-clear"></div>

		<!-- <div id='ewd-uwpm-plain-text-toggle'><h2><?php _e("Plain Text Version", 'ultimate-wp-mail'); ?></h2></div>
		<div id='ewd-uwpm-plain-text-version' class='ewd-uwpm-hidden'>
			<textarea name='Plain_Text_Email' id='ewd-uwpm-pain-text-email'><?php echo $Plain_Text_Email; ?></textarea>
		</div> -->

	</div> <!-- ewd-uwpm-full-screen-inside -->

	<?php
}

function EWD_UWPM_Get_User_Fields() {
	$Contact_Methods = wp_get_user_contact_methods();

	$User_Fields = array(
		array('slug' => 'username', 'name' => 'Username'),
		array('slug' => 'fname', 'name' => 'First Name'),
		array('slug' => 'lname', 'name' => 'Last Name'),
		array('slug' => 'nickname', 'name' => 'Nickname'),
		array('slug' => 'dname', 'name' => 'Display Name'),
		array('slug' => 'email', 'name' => 'Email'),
		array('slug' => 'website', 'name' => 'Website')
	);

	foreach ($Contact_Methods as $key => $Contact_Method) {
		$User_Fields[] = array('slug' => $key, 'name' => $Contact_Method);
	}

	return $User_Fields;
}

function EWD_UWPM_Sent_Statistics_Box($post) {
	global $wpdb;
	global $ewd_uwpm_email_send_events, $ewd_uwpm_email_open_events, $ewd_uwpm_email_links_clicked_events;

	$Track_Opens = get_option("EWD_UWPM_Track_Opens");
	$Track_Clicks = get_option("EWD_UWPM_Track_Clicks");
	
	$Previous_Sends = get_post_meta($post->ID, 'EWD_UWPM_Send_Events', true);
	if (!is_array($Previous_Sends)) {$Previous_Sends = array();} ?>

	<div class='ewd-uwpm-sent-events'>
		<h3><?php _e("Previous Email Sends", 'ultimate-wp-mail'); ?></h3>
		<table>
			<thead>
				<tr>
					<th><?php _e("Send Time", 'ultimate-wp-mail'); ?></th>
					<th><?php _e("Send Type", 'ultimate-wp-mail'); ?></th>
					<th><?php _e("Recipient(s)", 'ultimate-wp-mail'); ?></th>
					<th><?php _e("Successful Sends", 'ultimate-wp-mail'); ?></th>
					<?php if ($Track_Opens == "Yes") {echo "<th>" . __("Number of Opens", 'ultimate-wp-mail') . "</th>";} ?>
					<?php if ($Track_Clicks == "Yes") {echo "<th>" . __("Number of Links Clicked", 'ultimate-wp-mail') . "</th>";} ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($Previous_Sends as $Previous_Send) { ?>
					<?php 
						$Email_Opens = $wpdb->get_var($wpdb->prepare("SELECT COUNT(Email_Open_ID) FROM $ewd_uwpm_email_open_events o INNER JOIN $ewd_uwpm_email_send_events s ON o.Email_Send_ID=s.Email_Send_ID WHERE s.Email_ID=%d AND s.Event_ID=%d", $post->ID, $Previous_Send['ID']));
						$Links_Clicked = $wpdb->get_var($wpdb->prepare("SELECT COUNT(Email_Link_Clicked_ID) FROM $ewd_uwpm_email_links_clicked_events lc INNER JOIN $ewd_uwpm_email_send_events s ON lc.Email_Send_ID=s.Email_Send_ID WHERE s.Email_ID=%d AND s.Event_ID=%d", $post->ID, $Previous_Send['ID']));
					?>
					<tr>
						<td><?php echo $Previous_Send['Send_Time']; ?></td>
						<td><?php echo $Previous_Send['Send_Type']; ?></td>
						<td>
							<?php  
								if ($Previous_Send['Send_Type'] == 'List') {echo EWD_UWPM_Get_List_Name_From_ID($Previous_Send['List_ID']);}
								elseif ($Previous_Send['Send_Type'] == 'User') {$User = get_userdata($Previous_Send['User_ID']); echo $User->display_name;}
								else {echo "All Users";}
							?>
						</td>
						<td><?php echo $Previous_Send['Emails_Sent']; ?></td>
						<?php if ($Track_Opens == "Yes") {echo "<td>" . $Email_Opens . "</td>";} ?>
						<?php if ($Track_Clicks == "Yes") {echo "<td>" . $Links_Clicked . "</td>";} ?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
<?php	
}

function EWD_UWPM_Test_List_Sends() {
	$Email_ID = 10984;
	$List_ID = -2;

	$Interests['Post_Categories'] = array(293, 4);
	$Interests['UWPM_Categories'] = array();
	$Interests['WC_Categories'] = array();

	$WC_Info = Array
        (
            'Previous_Purchasers' => true,
            'Product_Purchasers' => false,
            'Previous_WC_Products' => null,
            'Category_Purchasers' => false,
            'Previous_WC_Categories' => null
        );
	
	echo EWD_UWPM_Email_User_List($List_ID, $Email_ID, $Email_Title, $Email_Content, $Interests, $WC_Info);
}

function EWD_UWPM_Send_Mail_Metabox($post) { 
	$Users = get_users();

	$WooCommerce_Integration = get_option("EWD_UWPM_WooCommerce_Integration");

	$Email_Lists_Array = get_option("EWD_UWPM_Email_Lists_Array");
	if (!is_array($Email_Lists_Array)) {$Email_Lists_Array = array();}
	?>
	<div class='ewd-uwpm-send-options'>
		<?php if (!is_object($post) or !isset($post->ID)) {_e("Save email first to be able to track opens and clicks", 'ultimate-wp-mail');} ?>
		<div class='ewd-uwpm-schedule-sending'>
			<select class='ewd-uwpm-delay-send-toggle'>
				<option value='Now'><?php _e("Send Now", 'ultimate-wp-mail'); ?></option>
				<option value='Later'><?php _e("Send Later", 'ultimate-wp-mail'); ?></option>
			</select>
			<input id="ewd-uwpm-send-datetime" class='uwpm-hidden' type="datetime-local" />
		</div>
		<hr class=''>
		<div class='ewd-uwpm-email-specific-user'>
			<select id='ewd-uwpm-email-user-select'>
				<?php foreach ($Users as $User) { $Unsubscribe = get_user_meta($User->ID, 'EWD_UWPM_User_Unsubscribe', true); ?>
					<option value='<?php echo $User->ID; ?>' <?php echo $Unsubscribe == 'Yes' ? 'disabled' : ''; ?>><?php echo $User->user_login; ?> (<?php echo $User->user_email; ?>)</option>
				<?php } ?>
			</select>
			<div class='ewd-uwpm-clear'></div>
			<button id='ewd-uwpm-email-specific-user' class='button button-primary button-large' ><?php _e("Email User", 'ultimate-wp-mail'); ?></button>
		</div>
		<div class='ewd-uwpm-email-option-separator'></div>
		<div class='ewd-uwpm-email-user-list-div'>
			<select id='ewd-uwpm-email-list-select'>
				<?php foreach ($Email_Lists_Array as $Email_Lists_Item) { ?>
					<option value='<?php echo $Email_Lists_Item['ID']; ?>'><?php echo $Email_Lists_Item['List_Name']; ?> (<?php echo $Email_Lists_Item['Number_Of_Users'] . __(' Users', 'ultimate-wp-mail'); ?>)</option>
				<?php } ?>
				<optgroup label='<?php _e("Automatically Created Lists", 'ultimate-wp-mail'); ?>'>
					<option value='-1'><?php _e("Select a list...", 'ultimate-wp-mail'); ?></option>
				</optgroup>
			</select>
			<div class='ewd-uwpm-clear'></div>
			<button id='ewd-uwpm-email-user-list' class='button button-primary button-large'><?php _e("Email User List", 'ultimate-wp-mail');?></button>
		</div>
		<div class='ewd-uwpm-email-option-separator'></div>
		<div class='ewd-uwpm-email-all-users-div'>
			<button id='ewd-uwpm-email-all' class='button button-primary button-large'><?php _e("Email All Users", 'ultimate-wp-mail');?></button>
		</div>
		<hr/>
		<div id='ewd-uwpm-send-test-button-div'>
			<button id='ewd-uwpm-send-test-button' class='button button-primary button-large'><?php _e("Send Test Email", 'ultimate-wp-mail');?></button>
		</div>
	</div>

	<div class='ewd-uwpm-al-dark-overlay ewd-uwpm-auto-list-overlay ewd-uwpm-hidden'></div>
	<div class='ewd-uwpm-auto-list-options ewd-uwpm-hidden'>
		<h2 class='ewd-uwpm-al-interests ewd-uwpm-auto-list-tab-active'><?php _e("Interest Lists", 'ultimate-wp-mail'); ?></h2>
		<?php if ($WooCommerce_Integration == "Yes") { echo "<h2 class='ewd-uwpm-al-wc'>" . __("WooCommerce Lists", 'ultimate-wp-mail') . "</h2>"; } ?>
		<div class='ewd-uwpm-clear'></div>
		<div class='ewd-uwpm-al-interest-groups'>
			<div class='ewd-uwpm-al-post-categories ewd-uwpm-col-<?php echo ($WooCommerce_Integration == "Yes" ? "3" : "2"); ?>'>
				<h4><?php _e("Post Categories", 'ultimate-wp-mail'); ?></h4>
				<?php 
					$Categories = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));
					foreach ($Categories as $Category) {
						echo "<input type='checkbox' class='ewd-uwpm-al-post-category' value='" . $Category->term_id . "' /><span>" . $Category->name . "</span><br/>";
					}
				?>
			</div>
			<div class='ewd-uwpm-al-email-categories ewd-uwpm-col-<?php echo ($WooCommerce_Integration == "Yes" ? "3" : "2"); ?>''>
				<h4><?php _e("Email Categories", 'ultimate-wp-mail'); ?></h4>
				<?php 
					$Categories = get_terms(array('taxonomy' => 'uwpm-category', 'hide_empty' => false));
					foreach ($Categories as $Category) {
						echo "<input type='checkbox' class='ewd-uwpm-al-uwpm-category' value='" . $Category->term_id . "' /><span>" . $Category->name . "</span><br/>";
					}
				?>
			</div>
			<div class='ewd-uwpm-al-wc-categories ewd-uwpm-col-<?php echo ($WooCommerce_Integration == "Yes" ? "3" : "2"); ?>''>
				<h4><?php _e("WooCommerce Categories", 'ultimate-wp-mail'); ?></h4>
				<?php 
					$Categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
					foreach ($Categories as $Category) {
						echo "<input type='checkbox' class='ewd-uwpm-al-wc-category' value='" . $Category->term_id . "' /><span>" . $Category->name . "</span><br/>";
					}
				?>
			</div>
		</div>

		<?php if ($WooCommerce_Integration == "Yes") { ?>
			<div class='ewd-uwpm-al-woocommerce-lists ewd-uwpm-hidden'>
				<input type='checkbox' class='ewd-uwpm-al-wc-previous-purchasers' /><span><?php _e("All Previous Purchasers", 'ultimate-wp-mail'); ?></span>
				<div class='ewd-uwpm-clear'></div>
				<input type='checkbox' class='ewd-uwpm-al-wc-previous-products' /><span><?php _e("Previous Purchasers of:", 'ultimate-wp-mail'); ?></span><br />
				<?php 
					$Products = get_posts(array('post_type' => 'product', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));
					echo "<select class='ewd-uwpm-al-wc-products' multiple>";
					foreach ($Products as $Product) {
						echo "<option value='" . $Product->ID . "'>" . $Product->post_title . "</option>";
					}
					echo "</select><br/>";
				?>
				<div class='ewd-uwpm-clear'></div>
				<input type='checkbox' class='ewd-uwpm-al-wc-previous-categories' /><span><?php _e("Previous Purchasers of Product in:", 'ultimate-wp-mail'); ?></span><br />
				<?php 
					echo "<select class='ewd-uwpm-al-wc-categories' multiple>";
					foreach ($Categories as $Category) {
						echo "<option value='" . $Category->term_id . "'>" . $Category->name . "</option>";
					}
					echo "</select>";
				?>
			</div>
		<?php } ?>
		<button class='ewd-uwpm-submit-al button button-primary button-large'><?php _e("Send Email to Selected Groups", 'ultimate-wp-mail'); ?></button>
	</div>
<?php }

add_action( 'save_post', 'EWD_UWPM_Save_Meta_Box_Data' );
function EWD_UWPM_Save_Meta_Box_Data($post_id) {
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['EWD_UWPM_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['EWD_UWPM_meta_box_nonce'], 'EWD_UWPM_Save_Meta_Box_Data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if (get_post_type($post_id) != 'uwpm_mail_template') {return;}

	if (isset($_POST['Email_Content'])) {update_post_meta($post_id, 'EWD_UWPM_Mail_Content', $_POST['Email_Content']);}
	if (isset($_POST['Plain_Text_Email'])) {update_post_meta($post_id, 'EWD_UWPM_Plain_Text_Mail_Content', $_POST['Plain_Text_Email']);}

	if (isset($_POST['Content_Alignment'])) {update_post_meta($post_id, 'EWD_UWPM_Content_Alignment', $_POST['Content_Alignment']);}
	if (isset($_POST['Max_Width'])) {update_post_meta($post_id, 'EWD_UWPM_Max_Width', $_POST['Max_Width']);}
	if (isset($_POST['Email_Background_Color'])) {update_post_meta($post_id, 'EWD_UWPM_Email_Background_Color', $_POST['Email_Background_Color']);}
	if (isset($_POST['Body_Background_Color'])) {update_post_meta($post_id, 'EWD_UWPM_Body_Background_Color', $_POST['Body_Background_Color']);}
	if (isset($_POST['Block_Background_Color'])) {update_post_meta($post_id, 'EWD_UWPM_Block_Background_Color', $_POST['Block_Background_Color']);}
	if (isset($_POST['Block_Border'])) {update_post_meta($post_id, 'EWD_UWPM_Block_Border', $_POST['Block_Border']);}
}

function EWD_UWPM_Get_List_Name_From_ID($List_ID) {
	$Email_Lists_Array = get_option("EWD_UWPM_Email_Lists_Array");
	if (!is_array($Email_Lists_Array)) {$Email_Lists_Array = array();}

	foreach ($Email_Lists_Array as $Email_Lists_Item) {
		if ($Email_Lists_Item['ID'] != $List_ID) {return $Email_Lists_Item['List_Name'];}
	}

	return '';
}
?>