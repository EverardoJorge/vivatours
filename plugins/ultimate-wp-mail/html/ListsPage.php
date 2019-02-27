<?php
$Email_Lists_Array = get_option("EWD_UWPM_Email_Lists_Array");
if (!is_array($Email_Lists_Array)) {$Email_Lists_Array = array();}
?>

		<form method="post" action="admin.php?page=EWD-UWPM-Options&DisplayPage=Lists&Action=EWD_UWPM_UpdateLists">

			<div id='Lists' class='uwpm-option-set'>

				<br />

				<div class="ewd-uwpm-admin-section-heading"><?php _e('Lists Options', 'ultimate-wp-mail'); ?></div>
				<?php global $EWD_UWPM_Full_Version; ?>
				<table class="form-table ewd-uwpm-premium-options-table <?php echo $EWD_UWPM_Full_Version; ?>">
					<tr>
						<th scope="row">Email Lists</th>
						<td>
							<fieldset><legend class="screen-reader-text"><span>Email Lists</span></legend>
								<table id='ewd-uwpm-email-lists-table'>
									<thead>
										<tr>
											<th class="ewd-uwpm-admin-no-info-button">List Name</th>
											<th class="ewd-uwpm-admin-no-info-button">Number of Users</th>
											<th class="ewd-uwpm-admin-no-info-button">Emails Sent</th>
											<th class="ewd-uwpm-admin-no-info-button">Last Email Sent</th>
											<th class="ewd-uwpm-admin-no-info-button"></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$Counter = 0;
										$Max_ID = 0;
										foreach ($Email_Lists_Array as $Email_Lists_Item) { ?>
											<tr id='ewd-uwpm-email-lists-row-<?php echo $Counter; ?>' class='ewd-uwpm-email-list' data-rowcounter='<?php echo $Counter; ?>' data-rowid='<?php echo $Email_Lists_Item['ID']; ?>'>
												<td class='ewd-uwpm-email-list-details'>
													<?php echo $Email_Lists_Item['List_Name']; ?>
												</td>
												<td class='ewd-uwpm-user-count'>
													<?php echo $Email_Lists_Item['Number_Of_Users']; ?>
												</td>
												<td>
													<input type='hidden' name='Email_Lists_<?php echo $Counter; ?>_Emails_Sent' value='<?php echo json_encode($Email_Lists_Item['Emails_Sent']); ?>' />
													<?php echo is_array($Email_Lists_Item['Emails_Sent']) ? sizeof($Email_Lists_Item['Emails_Sent']) : "0"; ?>
												</td>
												<td>
													<input type='hidden' name='Email_Lists_<?php echo $Counter; ?>_Last_Email_Sent_Date' value='<?php echo $Email_Lists_Item['Last_Email_Sent_Date']; ?>' />
													<?php echo isset($Email_Lists_Item['Last_Email_Sent_Date']) ? $Email_Lists_Item['Last_Email_Sent_Date'] : "N/A"; ?>
												</td>
												<td>
													<input type='hidden' name='Email_Lists_<?php echo $Counter; ?>_ID' value='<?php echo $Email_Lists_Item['ID']; ?>' />
													<input type='hidden' class='ewd-uwpm-list-users' name='Email_Lists_<?php echo $Counter; ?>_List_Users' value='<?php echo json_encode($Email_Lists_Item['Users']); ?>' />
													<input type='hidden' class='ewd-uwpm-list-name-input' name='Email_Lists_<?php echo $Counter; ?>_List_Name' value='<?php echo $Email_Lists_Item['List_Name']; ?>' />
													<a class='ewd-uwpm-delete-email-lists-item' data-listcounter='<?php echo $Counter; ?>'>Delete</a>
												</td>
											</tr>
											<?php $Counter++;
											$Max_ID = max($Max_ID, $Email_Lists_Item['ID']);
										}
										$Max_ID++;
										 ?>
										<tr><td colspan='5'><a class='ewd-uwpm-add-email-lists-item ewd-uwpm-admin-add-button' data-nextcounter='<?php echo $Counter; ?>' data-nextid='<?php echo $Max_ID; ?>'><?php _e('&plus;&nbsp;&nbsp;ADD', 'ultimate-wp-mail'); ?></a></td></tr>
									</tbody>
								</table>
								<p>
									Create lists of WordPress users that you can send specific emails to.<br /><br />
									There are also default lists that you can email, depending on the integrations you have turned on (ex. all users who previously made a purchase through WooCommerce)
								</p>
							</fieldset>
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
				<div class='ewd-uwpm-list-background uwpm-hidden'></div>
				<div class='ewd-uwpm-edit-list uwpm-hidden' data-currentid='0'>
					<div class='ewd-uwpm-close-list-edit'>Close</div>
					<div class='ewd-uwpm-edit-list-name'>
						<?php _e("List name: ", 'ultimate-wp-mail'); ?>
						<input type='text' class='ewd-uwpm-list-name' />
					</div>
					<div class="ewd-uwpm-edit-list-inside">
						<div class='ewd-uwpm-edit-list-users-div'>
							<h3><?php _e("Add Users", 'ultimate-wp-mail'); ?></h3>
							<div class='ewd-uwpm-all-users-table'>
								<?php 
									$Users = get_users();
									foreach ($Users as $User) {
										echo "<div class='ewd-uwpm-user-entry'>";
										echo "<input type='checkbox' name='Add_Users[]' class='ewd-uwpm-add-user-id' value='" . $User->ID . "' data-name='" . $User->display_name . "' />";
										echo "<span class='ewd-uwpm-user-display-name'>" . $User->display_name . "</span>";
										echo "</div>";
									}
								?>
							</div>
							<button class='ewd-uwpm-add-list-users ewd-uwpm-button'><?php _e("Add Users", 'ultimate-wp-mail'); ?></button>
						</div>
						<div class='ewd-uwpm-edit-list-users-div'>
							<h3><?php _e("Current Users", 'ultimate-wp-mail'); ?></h3>
							<div class='ewd-uwpm-current-users-table'>
							</div>
							<button class='ewd-uwpm-remove-list-users ewd-uwpm-button'><?php _e("Remove Users", 'ultimate-wp-mail'); ?></button>
						</div>
					</div> <!-- ewd-uwpm-edit-list-inside -->
					<div class='ewd-uwpm-clear'></div>
					<div class='ewd-uwpm-save-list-edit ewd-uwpm-button'><?php _e("Save", 'ultimate-wp-mail'); ?></div>
				</div>
			</div>

			<p class="submit"><input type="submit" name="Lists_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p>

		</form>

