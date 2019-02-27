<?php 
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Payment_Frequency = get_option("EWD_FEUP_Payment_Frequency");

	$Levels = $wpdb->get_results("SELECT * FROM $ewd_feup_levels_table_name ORDER BY Level_Privilege ASC"); 
?>

<div class="OptionTab ActiveTab" id="EditProduct">


	<div id="ewd-feup-new-edit-user-screen">

		<div id="col-left">
			<div class="col-wrap ewd-feup-user-details-wrap">

				<div class="form-wrap UserDetail">

					<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users" class="NoUnderline">&#171; <?php _e("Back to Users list", 'front-end-only-users') ?></a>
					<div style="clear: both;"></div>
					<h2><?php _e("Add User", 'front-end-only-users'); ?></h2>

					<?php $Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name"); ?>
					<!-- Form to update a user -->
					<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_EditUser&DisplayPage=Users" class="validate" enctype="multipart/form-data">
						<input type="hidden" name="action" value="Add_User" />
						<?php wp_nonce_field( 'EWD_FEUP_Admin_Nonce', 'EWD_FEUP_Admin_Nonce' );  ?>
						<?php wp_referer_field(); ?>

						<div class="ewd-feup-admin-edit-product-left">

							<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full ewd-feup-admin-closeable-widget-box ewd-feup-admin-edit-product-left-full-widget-box" id="ewd-feup-admin-edit-user-details-widget-box">
								<div class="ewd-feup-dashboard-new-widget-box-top"><?php _e('User Details', 'front-end-only-users'); ?><span class="ewd-feup-admin-edit-product-down-caret">&nbsp;&nbsp;&#9660;</span><span class="ewd-feup-admin-edit-product-up-caret">&nbsp;&nbsp;&#9650;</span></div>
								<div class="ewd-feup-dashboard-new-widget-box-bottom">
									<table class="form-table">
										<tr>
											<th><label for="Username"><?php _e("Username", 'front-end-only-users') ?></label></th>
											<td>
												<input type='text' name='Username' class='ewd-admin-regular-text' size='60' />
											</td>
										</tr>
										<tr>
											<th><label for="User_Password"><?php _e("Password", 'front-end-only-users') ?></label></th>
											<td>
												<input type='password' name='User_Password' class='ewd-admin-regular-text' size='60' />
											</td>
										</tr>
										<tr>
											<th><label for="Confirm_User_Password"><?php _e("Confirm Password", 'front-end-only-users') ?></label></th>
											<td>
												<input type='password' name='Confirm_User_Password' class='ewd-admin-regular-text' size='60' />
											</td>
										</tr>
										<tr>
											<th><label for="Level_ID"><?php _e("Level", 'front-end-only-users') ?></label></th>
											<td>
												<select name='Level_ID'>
													<option value='0'>None (0)</option>
													<?php foreach ($Levels as $Level) {
														echo "<option value='" . $Level->Level_ID . "'>" . $Level->Level_Name . " (" . $Level->Level_Privilege . ")</option>";
													}?> 
												</select>
											</td>
										</tr>
										<?php foreach ($Fields as $Field) { ?>
											<tr>
												<th><label for="<?php echo $Field->Field_Name; ?>"><?php echo $Field->Field_Name; ?></label></th>
												<td>
													<?php if ($Field->Field_Type == "text") {?><input name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-regular-text' id="<?php echo $Field->Field_Name; ?>" type="text" size="60" />
													<?php } elseif ($Field->Field_Type == "mediumint") {?><input name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-regular-text' id="<?php echo $Field->Field_Name; ?>" type="text" size="60" />
													<?php } elseif ($Field->Field_Type == "email") {?><input name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-regular-text' id="<?php echo $Field->Field_Name; ?>" type="email" size="60" />
													<?php } elseif ($Field->Field_Type == "tel") {?><input name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-regular-text' id="<?php echo $Field->Field_Name; ?>" type="tel" size="60" />
													<?php } elseif ($Field->Field_Type == "url") {?><input name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-regular-text' id="<?php echo $Field->Field_Name; ?>" type="url" size="60" />
													<?php } elseif ($Field->Field_Type == "date") {?>
																<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-date-input pure-input-1-3' type='date' />
													<?php } elseif ($Field->Field_Type == "datetime") { ?>
																<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-datetime-input pure-input-1-3' type='datetime-local' />
													<?php } elseif ($Field->Field_Type == "textarea") { ?>
																<textarea name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-large-text' id="<?php echo $Field->Field_Name; ?>"></textarea>
													<?php } elseif ($Field->Field_Type == "file") {?>
																<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-date-input pure-input-1-3' type='file' value='' />
													<?php } elseif ($Field->Field_Type == "picture") { ?>
																<input name='<?php echo $Field->Field_Name ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-file-input' type='file' value=''/>
													<?php } elseif ($Field->Field_Type == "select" or $Field->Field_Type == "countries") { ?>
																<?php $Options = explode(",", $Field->Field_Options); ?>
																<?php if ($Field->Field_Type == "countries") {$Options = EWD_FEUP_Return_Country_Array();} ?>
																<select name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>">
																<?php foreach ($Options as $Option) { ?><option value='<?php echo $Option; ?>'><?php echo $Option; ?></option><?php } ?>
																</select>
													<?php } elseif ($Field->Field_Type == "radio") { ?>
																<?php $Options = explode(",", $Field->Field_Options); ?>
																<?php foreach ($Options as $Option) { ?><input type='radio' name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-small-input' value="<?php echo $Option; ?>" ><?php echo $Option ?><br/><?php } ?>
													<?php } elseif ($Field->Field_Type == "checkbox") { ?>
																<?php $Options = explode(",", $Field->Field_Options); ?>
																<?php foreach ($Options as $Option) { ?><input type="checkbox" class='ewd-admin-small-input' name="<?php echo $Field->Field_Name; ?>[]" value="<?php echo $Option; ?>"><?php echo $Option; ?></br><?php } ?>
													<?php } ?>
													<p><?php echo $Field->Field_Description; ?></p>
												</td>
											</tr>
										<?php } ?>
									</table>
								</div>
							</div>			

						</div> <!--ewd-feup-admin-edit-product-left-->

						<div class="ewd-feup-admin-edit-product-right">

							<p class="submit ewd-feup-admin-edit-product-submit-p"><input type="submit" name="submit" id="submit" class="button-primary ewd-feup-admin-edit-product-save-button" value="<?php _e('Add User ', 'front-end-only-users') ?>"  /></p></form>

							<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full ewd-feup-admin-closeable-widget-box" id="ewd-feup-admin-edit-user-need-help-widget-box">
								<div class="ewd-feup-dashboard-new-widget-box-top"><?php _e('Need Help?', 'front-end-only-users'); ?><span class="ewd-feup-admin-edit-product-down-caret">&nbsp;&nbsp;&#9660;</span><span class="ewd-feup-admin-edit-product-up-caret">&nbsp;&nbsp;&#9650;</span></div>
								<div class="ewd-feup-dashboard-new-widget-box-bottom">
									 <div class='ewd-feup-need-help-box'>
										<div class='ewd-feup-need-help-text'>Visit our Support Center for documentation and tutorials</div>
										<a class='ewd-feup-need-help-button' href='https://www.etoilewebdesign.com/support-center/?Plugin=FEUP' target='_blank'>GET SUPPORT</a>
									</div>
								</div>
							</div>

							<div class="ewd-feup-dashboard-new-widget-box ewd-widget-box-full ewd-feup-admin-closeable-widget-box" id="ewd-feup-admin-edit-user-custom-fields-widget-box">
								<div class="ewd-feup-dashboard-new-widget-box-top"><?php _e('User Approval and Payment', 'front-end-only-users'); ?><span class="ewd-feup-admin-edit-product-down-caret">&nbsp;&nbsp;&#9660;</span><span class="ewd-feup-admin-edit-product-up-caret">&nbsp;&nbsp;&#9650;</span></div>
								<div class="ewd-feup-dashboard-new-widget-box-bottom">
									<table class="form-table">
										<?php if ($Admin_Approval == "Yes") { ?>
											<tr>												
												<th><label for='Admin Approved' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Admin Approved', 'front-end-only-users');?>: </label></th>
												<td>
													<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='Yes'>Yes<br />
													<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='No' checked>No<br />
												</td>
											</tr>
										<?php } ?>
										<?php if ($Email_Confirmation == "Yes") { ?>
											<tr>												
												<th><label for='Email Confirmation' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Email Confirmed', 'front-end-only-users');?>: </label></th>
												<td>
													<input type='radio' class='ewd-feup-text-input' name='Email_Confirmation' value='Yes'>Yes<br />
													<input type='radio' class='ewd-feup-text-input' name='Email_Confirmation' value='No' checked>No<br />
												</td>
											</tr>
										<?php } ?>
										<?php if ($Payment_Frequency != "None") { ?>
											<tr>												
												<th><label for='User Membership Fees Paid' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Membership Fees Paid', 'front-end-only-users');?>: </label></th>
												<td>
													<input type='radio' class='ewd-feup-text-input' name='User_Membership_Fees_Paid' value='Yes'>Yes<br />
													<input type='radio' class='ewd-feup-text-input' name='User_Membership_Fees_Paid' value='No' checked>No<br />
												</td>
											</tr>
										<?php } ?>
										<?php if ($Payment_Frequency == "Yearly" or $Payment_Frequency == "Monthly") { ?>
											<tr>												
												<th><label for='User Membership Fees Paid' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Account Expiry Date', 'front-end-only-users');?>: </label></th>
												<td>
													<input type='datetime-local' class='ewd-feup-text-input' name='User_Account_Expiry'>
												</td>
											</tr>
										<?php } ?>
									</table>
								</div>
							</div>

						</div> <!--ewd-feup-admin-edit-product-right-->

					</form>

				</div> <!--UserDetail-->
			</div> <!--col-wrap-->
		</div> <!--col-left-->

	</div> <!--ewd-feup-new-edit-user-screen-->


</div><!--ActiveTab-->



