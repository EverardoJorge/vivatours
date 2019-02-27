<div id="col-full">
<div class="col-wrap">

<!-- Display a list of the products which have already been created -->
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
			if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
			else {$Page = 1;}
			
			$Sql = "SELECT * FROM $ewd_feup_payments_table_name ";
				if (isset($_GET['OrderBy']) and $_GET['DisplayPage'] == "Payments") {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
				else {$Sql .= "ORDER BY Payment_Date ";}
				$Sql .= "LIMIT " . ($Page - 1)*20 . ",20";
				$myrows = $wpdb->get_results($Sql);
				$TotalPayments = $wpdb->get_results("SELECT Payment_ID FROM $ewd_feup_payments_table_name");
				$num_rows = $wpdb->num_rows; 
				$Number_of_Pages = ceil($num_rows/20);
				$Current_Page_With_Order_By = "admin.php?page=EWD-FEUP-options&DisplayPage=Payments";
				if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By .= "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}?>

<form action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_MassDeletePayments&DisplayPage=Payments" method="post">    
<div class="tablenav top">
		<div class="alignleft actions">
				<select name='action'>
  					<option value='-1' selected='selected'><?php _e("Bulk Actions", 'front-end-only-users') ?></option>
						<option value='delete'><?php _e("Delete", 'front-end-only-users') ?></option>
				</select>
				<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'front-end-only-users') ?>"  />
		</div>
		<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
				<span class="displaying-num"><?php echo $wpdb->num_rows; ?> <?php _e("items", 'front-end-only-users') ?></span>
				<span class='pagination-links'>
						<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
						<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
						<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'front-end-only-users') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
						<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
						<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
				</span>
		</div>
</div>

<table class="wp-list-table striped widefat tags sorttable fields-list ui-sortable" cellspacing="0">
		<thead>
				<tr>
						<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
								<input type="checkbox" /></th><th scope='col' id='field-name' class='manage-column column-name sortable desc'  style="">
										<?php if ($_GET['OrderBy'] == "Username" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Username&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Username&Order=ASC'>";} ?>
											  <span><?php _e("Username", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Payment_Date" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Payment_Date&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Payment_Date&Order=ASC'>";} ?>
											  <span><?php _e("Payment Date", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Payment_Amount" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Payment_Amount&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Payment_Amount&Order=ASC'>";} ?>
											  <span><?php _e("Amount", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Next_Payment_Date" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Next_Payment_Date&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Next_Payment_Date&Order=ASC'>";} ?>
											  <span><?php _e("Next Payment", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Discount_Code_Used" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Discount_Code_Used&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Discount_Code_Used&Order=ASC'>";} ?>
											  <span><?php _e("Discount Code", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
				</tr>
		</thead>

		<tfoot>
				<tr>
						<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
								<input type="checkbox" /></th><th scope='col' id='field-name' class='manage-column column-name sortable desc'  style="">
										<?php if ($_GET['OrderBy'] == "Username" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Username&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Username&Order=ASC'>";} ?>
											  <span><?php _e("Username", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Payment_Date" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Payment_Date&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Payment_Date&Order=ASC'>";} ?>
											  <span><?php _e("Payment Date", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Payment_Amount" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Payment_Amount&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Payment_Amount&Order=ASC'>";} ?>
											  <span><?php _e("Amount", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Next_Payment_Date" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Next_Payment_Date&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Next_Payment_Date&Order=ASC'>";} ?>
											  <span><?php _e("Next Payment", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Discount_Code_Used" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Discount_Code_Used&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Payments&OrderBy=Discount_Code_Used&Order=ASC'>";} ?>
											  <span><?php _e("Discount Code", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
				</tr>
		</tfoot>

	<tbody id="the-list" class='list:tag'>
		
		 <?php
				if ($myrows) { 
	  			  foreach ($myrows as $Payment) {
								echo "<tr id='list-item-" . $Payment->Payment_ID . "' class='list-item'>";
								echo "<th scope='row' class='check-column'>";
								echo "<input type='checkbox' name='Payments_Bulk[]' value='" . $Payment->Payment_ID ."' />";
								echo "</th>";
								echo "<td class='name column-name'>";
								echo "<strong>";
								echo "<a class='row-title' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_Payment_Details&Payment_ID=" . $Payment->Payment_ID ."' title='View " . $Payment->Username . "'>" . $Payment->Username . "</a></strong>";
								echo "<br />";
								echo "<div class='row-actions'>";
								echo "<span class='delete'>";
								echo "<a class='delete-tag' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_DeletePayment&DisplayPage=Payments&Payment_ID=" . $Payment->Payment_ID ."'>" . __("Delete", 'front-end-only-users') . "</a>";
		 						echo "</span>";
								echo "</div>";
								echo "<div class='hidden' id='inline_" . $Payment->Payment_ID ."'>";
								echo "<div class='name'>" . $Payment->Username . "</div>";
								echo "</div>";
								echo "</td>";
								echo "<td class='description column-date'>" . $Payment->Payment_Date . "</td>";
								echo "<td class='users column-amount'>" . $Payment->Payment_Amount . "</td>";
								echo "<td class='users column-next-date'>" . $Payment->Next_Payment_Date . "</td>";
								echo "<td class='users column-discount'>" . $Payment->Discount_Code_Used . "</td>";
								echo "</tr>";
						}
				}
		?>

	</tbody>
</table>

</form>
</div>
</div>

