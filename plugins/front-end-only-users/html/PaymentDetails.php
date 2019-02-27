
<!-- The details of a specific product for editing, based on the product ID -->
		
		<?php $Payment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_payments_table_name WHERE Payment_ID ='%d'", $_GET['Payment_ID'])); ?>
		
		<div class="OptionTab ActiveTab" id="EditField">
				<div class="form-wrap EditField">
						<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Payment" class="NoUnderline">&#171; <?php _e("Back", 'front-end-only-users') ?></a>
						<h3>Edit <?php echo $Payment->Username;?></h3>
						<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_EditField&DisplayPage=Payment" class="validate" enctype="multipart/form-data">
								<input type="hidden" name="action" value="Edit_Payment" />
								<?php wp_nonce_field( 'EWD_FEUP_Admin_Nonce', 'EWD_FEUP_Admin_Nonce' );  ?>
								<?php wp_referer_field(); ?>
								<input type='hidden' name='Payment_ID' value='<?php echo $Payment->Payment_ID; ?>'>
								<div class="form-field form-required">
									<label for="User_ID"><?php _e("User ID", 'front-end-only-users') ?></label>
									<input name="User_ID" class='ewd-admin-regular-text' id="User_ID" type="text" value="<?php echo $Payment->User_ID; ?>" size="60" />
									<p><?php _e("The user ID of the person who made the payment.", 'front-end-only-users') ?></p>
								</div>
								<div class="form-field form-required">
									<label for="Username"><?php _e("Username", 'front-end-only-users') ?></label>
									<input name="Username" class='ewd-admin-regular-text' id="Username" type="text" value="<?php echo $Payment->Username; ?>" size="60" />
									<p><?php _e("The username of the person who made the payment.", 'front-end-only-users') ?></p>
								</div>
								<div class="form-field form-required">
									<label for="Payer_ID"><?php _e("Payer ID", 'front-end-only-users') ?></label>
									<input name="Payer_ID" class='ewd-admin-regular-text' id="Payer_ID" type="text" value="<?php echo $Payment->Payer_ID; ?>" size="60" />
									<p><?php _e("The PayPal payer ID of the person who made the payment.", 'front-end-only-users') ?></p>
								</div>
								<div class="form-field form-required">
									<label for="Payment_Receipt_Number"><?php _e("Payment Receipt Number", 'front-end-only-users') ?></label>
									<input name="Payment_Receipt_Number" class='ewd-admin-regular-text' id="Payment_Receipt_Number" type="text" value="<?php echo $Payment->PayPal_Receipt_Number; ?>" size="60" />
									<p><?php _e("The payment receipt number of this payment.", 'front-end-only-users') ?></p>
								</div>
								<div class="form-field form-required">
									<label for="Payment_Date"><?php _e("Payment Date", 'front-end-only-users') ?></label>
									<input name="Payment_Date" class='ewd-admin-regular-text' id="Payment_Date" type="datetime" value="<?php echo $Payment->Payment_Date; ?>" size="60" />
									<p><?php _e("The date upon which the payment was made.", 'front-end-only-users') ?></p>
								</div>
								<div class="form-field form-required">
									<label for="Next_Payment_Date"><?php _e("Next Payment Date", 'front-end-only-users') ?></label>
									<input name="Next_Payment_Date" class='ewd-admin-regular-text' id="Next_Payment_Date" type="datetime" value="<?php echo $Payment->Next_Payment_Date; ?>" size="60" />
									<p><?php _e("The next date at which a payment is expected, if payments are recurring.", 'front-end-only-users') ?></p>
								</div>
								<div class="form-field form-required">
									<label for="Payment_Amount"><?php _e("Payment Amount", 'front-end-only-users') ?></label>
									<input name="Payment_Amount" class='ewd-admin-regular-text' id="Payment_Amount" type="text" value="<?php echo $Payment->Payment_Amount; ?>" size="60" />
									<p><?php _e("The amount that was received in this payment.", 'front-end-only-users') ?></p>
								</div>
								<div class="form-field form-required">
									<label for="Discount_Code_Used"><?php _e("Discount Code", 'front-end-only-users') ?></label>
									<input name="Discount_Code_Used" class='ewd-admin-regular-text' id="Discount_Code_Used" type="text" value="<?php echo $Payment->Discount_Code_Used; ?>" size="60" />
									<p><?php _e("The discount code for this payment, if any.", 'front-end-only-users') ?></p>
								</div>

								<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Edit Payment', 'front-end-only-users') ?>"  /></p></form>

				</div>
		</div>	