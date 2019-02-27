<div id="col-full">
<div class="col-wrap">

<!-- Display a list of the products which have already been created -->
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
	if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
	else {$Page = 1;}

	$User_Query_Args = array(
		'number' => 20,
		'paged' => $Page
	);
	if (isset($_GET['Order'])) {$User_Query_Args['order'] = $_GET['Order'];}

	$Users = get_users($User_Query_Args);


	if (isset($_GET['Order'])) {
		if ($_GET['Order'] == 'ASC' or $_GET['Order'] == 'DESC') {$Current_Page_With_Order_By = 'admin.php?page=EWD-UWPM-Options&DisplayPage=UserStats&OrderBy=Username&Order=' . $_GET['Order'];}
		
	}
	else {$Current_Page_With_Order_By = 'admin.php?page=EWD-UWPM-Options&DisplayPage=UserStats&OrderBy=Username';}
	$User_Count = count_users();

	$Number_of_Pages = ceil($User_Count['total_users'] / 20);
?>

<div class="tablenav top">
		<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
				<span class="displaying-num"><?php echo $User_Count['total_users']; ?> <?php _e("users", 'ultimate-wp-mail') ?></span>
				<span class='pagination-links'>
						<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
						<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
						<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'ultimate-wp-mail') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
						<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
						<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
				</span>
		</div>
</div>

<table class="wp-list-table striped widefat tags sorttable fields-list ui-sortable" cellspacing="0">
	<thead>
		<tr>
			<th scope='col' class='manage-column column-type sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Username" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UWPM-Options&DisplayPage=UserStats&OrderBy=Username&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UWPM-Options&DisplayPage=UserStats&OrderBy=Username&Order=ASC'>";} ?>
					<span><?php _e("Username", 'ultimate-wp-mail') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='type' class='manage-column column-type'  style="">
				<span><?php _e("Emails Sent", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='description' class='manage-column column-description'  style="">
				<span><?php _e("Email Opened", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='required' class='manage-column column-users'  style="">
				<span><?php _e("Links Clicked", 'ultimate-wp-mail') ?></span>
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope='col' class='manage-column column-type sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Username" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-UWPM-Options&DisplayPage=UserStats&OrderBy=Username&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-UWPM-Options&DisplayPage=UserStats&OrderBy=Username&Order=ASC'>";} ?>
					<span><?php _e("Username", 'ultimate-wp-mail') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='type' class='manage-column column-type'  style="">
				<span><?php _e("Emails Sent", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='description' class='manage-column column-description'  style="">
				<span><?php _e("Email Opened", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='required' class='manage-column column-users'  style="">
				<span><?php _e("Links Clicked", 'ultimate-wp-mail') ?></span>
			</th>
		</tr>
	</tfoot>

	<tbody id="the-list" class='list:tag'>
		<?php
			if ($Users) { 
	  			foreach ($Users as $User) {
					$Emails_Sent = $wpdb->get_var($wpdb->prepare("SELECT COUNT(Email_Send_ID) FROM $ewd_uwpm_email_send_events WHERE User_ID=%d", $User->ID));
					$Emails_Opened = $wpdb->get_var($wpdb->prepare("SELECT COUNT(Email_Open_ID) FROM $ewd_uwpm_email_open_events INNER JOIN $ewd_uwpm_email_send_events ON $ewd_uwpm_email_send_events.Email_Send_ID = $ewd_uwpm_email_open_events.Email_Send_ID WHERE $ewd_uwpm_email_send_events.User_ID=%d", $User->ID));
					$Links_Clicked = $wpdb->get_var($wpdb->prepare("SELECT COUNT(Email_Link_Clicked_ID) FROM $ewd_uwpm_email_links_clicked_events INNER JOIN $ewd_uwpm_email_send_events ON $ewd_uwpm_email_send_events.Email_Send_ID = $ewd_uwpm_email_links_clicked_events.Email_Send_ID WHERE $ewd_uwpm_email_send_events.User_ID=%d", $User->ID));

					echo "<tr id='list-item-" . $User->ID . "' class='list-item'>";
					echo "<td class='username column-username'>";
					echo "<strong>";
					echo "<a class='row-title' href='admin.php?page=EWD-UWPM-Options&DisplayPage=UserStatsDetails&User_ID=" . $User->ID ."' title='View " . $User->user_login . "'>" . $User->user_login . "</a>";
					echo "</strong>";
					echo "</td>";
					echo "<td class='users column-sends'>" . $Emails_Sent . "</td>";
					echo "<td class='users column-opens'>" . $Emails_Opened . "</td>";
					echo "<td class='users column-links-clicked'>" . $Links_Clicked . "</td>";
					echo "</tr>";
				}
			}
		?>
	</tbody>
</table>

</div>
</div>

