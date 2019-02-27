<?php
$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
?>
<div id="col-right">
<div class="col-wrap">

<!-- Display a list of the products which have already been created -->
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
			if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
			else {$Page = 1;}
			if ($Page < 1) {$Page = 1;}
			
			$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
				if (isset($_GET['OrderBy']) and $_GET['DisplayPage'] == "Fields") {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
				else {$Sql .= "ORDER BY Field_Order ";}
				$Sql .= "LIMIT " . ($Page - 1)*200 . ",200";
				$myrows = $wpdb->get_results($Sql);
				$TotalFields = $wpdb->get_results("SELECT Field_ID FROM $ewd_feup_fields_table_name");
				$num_rows = $wpdb->num_rows; 
				$Number_of_Pages = ceil($num_rows/200);
				$Current_Page_With_Order_By = "admin.php?page=EWD-FEUP-options&DisplayPage=Fields";
				if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By .= "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}?>

<form action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_MassDeleteFields&DisplayPage=Fields" method="post">    
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
										<?php if ($_GET['OrderBy'] == "Field_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Name&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Name&Order=ASC'>";} ?>
											  <span><?php _e("Field Name", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Field_Type" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Type&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Type&Order=ASC'>";} ?>
											  <span><?php _e("Type", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Field_Description" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Description&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Description&Order=ASC'>";} ?>
											  <span><?php _e("Description", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Field_Required" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Required&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Required&Order=ASC'>";} ?>
											  <span><?php _e("Required?", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
				</tr>
		</thead>

		<tfoot>
				<tr>
						<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
								<input type="checkbox" /></th><th scope='col' id='field-name' class='manage-column column-name sortable desc'  style="">
										<?php if ($_GET['OrderBy'] == "Field_Name" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Name&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Name&Order=ASC'>";} ?>
											  <span><?php _e("Field Name", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Field_Type" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Type&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Type&Order=ASC'>";} ?>
											  <span><?php _e("Type", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Field_Description" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Description&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Description&Order=ASC'>";} ?>
											  <span><?php _e("Description", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
						<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
									  <?php if ($_GET['OrderBy'] == "Field_Required" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Required&Order=DESC'>";}
										 			else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Fields&OrderBy=Field_Required&Order=ASC'>";} ?>
											  <span><?php _e("Required?", 'front-end-only-users') ?></span>
												<span class="sorting-indicator"></span>
										</a>
						</th>
				</tr>
		</tfoot>

	<tbody id="the-list" class='list:tag'>
		
		 <?php
				if ($myrows) { 
	  			  foreach ($myrows as $Field) {
								echo "<tr id='list-item-" . $Field->Field_ID . "' class='list-item'>";
								echo "<th scope='row' class='check-column'>";
								echo "<input type='checkbox' name='Fields_Bulk[]' value='" . $Field->Field_ID ."' />";
								echo "</th>";
								echo "<td class='name column-name'>";
								echo "<strong>";
								echo "<a class='row-title' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_Field_Details&Selected=Product&Field_ID=" . $Field->Field_ID ."' title='Edit " . $Field->Field_Name . "'>" . $Field->Field_Name . "</a></strong>";
								echo "<br />";
								echo "<div class='row-actions'>";
								echo "<span class='delete'>";
								echo "<a class='delete-tag' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_DeleteField&DisplayPage=Fields&Field_ID=" . $Field->Field_ID ."'>" . __("Delete", 'front-end-only-users') . "</a>";
		 						echo "</span>";
								echo "</div>";
								echo "<div class='hidden' id='inline_" . $Field->Field_ID ."'>";
								echo "<div class='name'>" . $Field->Field_Name . "</div>";
								echo "</div>";
								echo "</td>";
								echo "<td class='description column-type'>" . ucfirst($Field->Field_Type) . "</td>";
								echo "<td class='description column-description'>" . substr($Field->Field_Description, 0, 60);
								if (strlen($Field->Field_Description) > 60) {echo "...";}
								echo "</td>";
								echo "<td class='users column-required'>" . $Field->Field_Required . "</td>";
								echo "</tr>";
						}
				}
		?>

	</tbody>
</table>

<div class="tablenav bottom">
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
		<br class="clear" />
</div>
</form>

<br class="clear" />
</div>
</div> <!-- /col-right -->


<!-- Form to upload a list of new products from a spreadsheet -->
<div id="col-left">
<div class="col-wrap">

<div class="form-wrap">
<h2><?php _e("Add New Field", 'front-end-only-users') ?></h2>
<!-- Form to create a new field -->
<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_AddField&DisplayPage=Field" class="validate" enctype="multipart/form-data">
<input type="hidden" name="action" value="Add_Field" />
<?php wp_nonce_field( 'EWD_FEUP_Admin_Nonce', 'EWD_FEUP_Admin_Nonce' );  ?>
<?php wp_referer_field(); ?>
<div class="form-field form-required">
	<label for="Field_Name"><?php _e("Name", 'front-end-only-users') ?></label>
	<input name="Field_Name" id="Field_Name" type="text" value="" size="60" />
</div>
<div class="form-field form-required">
	<label for="Field_Slug"><?php _e("Slug", 'front-end-only-users') ?></label>
	<input name="Field_Slug" id="Field_Slug" type="text" value="" size="60" />
	<p><?php _e("The slug of the field your users will see (lower-case letters and dashes only).", 'front-end-only-users') ?></p>
</div>
<div class="form-field">
	<label for="Field_Type"><?php _e("Type", 'front-end-only-users') ?></label>
	<select name="Field_Type" id="Field_Type">
			<option value='text'>Short Text</option>
			<option value='mediumint'>Integer</option>
			<option value='picture'>Profile Picture</option>
			<option value='select'>Select Box</option>
			<option value='radio'>Radio Button</option>
			<option value='checkbox'>Checkbox</option>
			<option value='textarea'>Text Area</option>
			<option value='file'>File</option>
			<option value='date'>Date</option>
			<option value='datetime'>Date/Time</option>
			<option value='countries'>Country Select</option>
			<option value='email'>Email</option>
			<option value='tel'>Telephone</option>
			<option value='url'>URL</option>			
			<option value='label'>Label (No field, just a message)</option>
	</select>
	<p><?php _e("The input method for the field and type of data that the field will hold.", 'front-end-only-users') ?></p>
</div>
<div class="form-field">
	<label for="Field_Description"><?php _e("Description", 'front-end-only-users') ?></label>
	<textarea name="Field_Description" id="Field_Description" rows="2" cols="40"></textarea>
</div>
<div class="form-field">
		<label for="Field_Options"><?php _e("Input Values", 'front-end-only-users') ?></label>
		<input name="Field_Options" id="Field_Options" type="text" size="60" />
		<p><?php _e("A comma-separated list of acceptable input values for this field. These values will be the options for select, checkbox, and radio inputs. All values will be accepted if left blank.", 'front-end-only-users') ?></p>
</div>
<div class="form-field">
		<label for="Field_Show_In_Admin"><?php _e("Show in Admin Table?", 'front-end-only-users') ?></label>
		<input type='radio' name="Field_Show_In_Admin" value="Yes">Yes<br/>
		<input type='radio' name="Field_Show_In_Admin" value="No" checked>No<br/>
</div>
<div class="form-field">
		<label for="Field_Show_In_Front_End"><?php _e("Show in User Profile", 'front-end-only-users') ?></label>
		<input type='radio' name="Field_Show_In_Front_End" value="Yes" checked>Yes<br/>
		<input type='radio' name="Field_Show_In_Front_End" value="No">No<br/>
</div>
<div class="form-field">
		<label for="Field_Required"><?php _e("Make Field Required?", 'front-end-only-users') ?></label>
		<input type='radio' name="Field_Required" value="Yes">Yes<br/>
		<input type='radio' name="Field_Required" value="No" checked>No<br/>
		<p><?php _e("Are users required to fill out this field?", 'front-end-only-users') ?></p>
</div>
<div class="form-field">
	<label for="Field_Equivalent"><?php _e("Field Meaning", 'front-end-only-users') ?></label>
	<select name="Field_Equivalent" id="Field_Equivalent">
			<option value='None'>None</option>
			<option value='First_Name'>First Name</option>
			<option value='Last_Name'>Last Name</option>
			<?php if ($Username_Is_Email == "No") { ?><option value='Email'>Email</option><?php } ?>
			<option value='Phone'>Phone</option>
			<option value='Address'>Address</option>
			<option value='City'>City</option>
			<option value='Province'>Province</option>
			<option value='Country'>Country</option>
			<option value='Postal_Code'>Postal Code</option>
	</select>
	<p><?php 
		//_e("The meaning of the field. This field is only necessary if WordPress users are being created using the plugin, or if data is being pulled from Facebook.", 'front-end-only-users');
		_e("The meaning of the field. This field is only necessary if WordPress users are being created using the plugin.", 'front-end-only-users')
	?></p>
</div>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Add New Field', 'front-end-only-users') ?>"  /></p></form>

</div>

<br class="clear" />
</div>
</div><!-- /col-left -->

