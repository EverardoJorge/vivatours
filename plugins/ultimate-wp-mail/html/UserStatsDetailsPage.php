<div id="col-full">
<div class="col-wrap">

<!-- Display a list of the products which have already been created -->
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
	$Emails = $wpdb->get_results($wpdb->prepare("SELECT Email_Send_ID, Email_ID, Email_Sent_Datetime FROM $ewd_uwpm_email_send_events WHERE User_ID=%d ORDER BY Email_Sent_Datetime", $_GET['User_ID']));
?>

<table class="wp-list-table striped widefat tags sorttable fields-list ui-sortable" cellspacing="0">
	<thead>
		<tr>
			<th scope='col' class='manage-column column-type sortable desc'  style="">
				<span><?php _e("Email Sent", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='type' class='manage-column column-type'  style="">
				<span><?php _e("Email Opened?", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='description' class='manage-column column-description'  style="">
				<span><?php _e("Links Clicked", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='required' class='manage-column column-users'  style="">
				<span><?php _e("Send Date/Time", 'ultimate-wp-mail') ?></span>
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope='col' class='manage-column column-type sortable desc'  style="">
				<span><?php _e("Email Sent", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='type' class='manage-column column-type'  style="">
				<span><?php _e("Email Opened?", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='description' class='manage-column column-description'  style="">
				<span><?php _e("Links Clicked", 'ultimate-wp-mail') ?></span>
			</th>
			<th scope='col' id='required' class='manage-column column-users'  style="">
				<span><?php _e("Send Date/Time", 'ultimate-wp-mail') ?></span>
			</th>
		</tr>
	</tfoot>

	<tbody id="the-list" class='list:tag'>
		<?php
			if ($Emails) { 
	  			foreach ($Emails as $Email) {
					$Email_Opened = $wpdb->get_var($wpdb->prepare("SELECT Email_Opened FROM $ewd_uwpm_email_open_events WHERE Email_Send_ID=%d", $Email->Email_Send_ID));
					$Links_Clicked = $wpdb->get_var($wpdb->prepare("SELECT COUNT(Email_Link_Clicked_ID) FROM $ewd_uwpm_email_links_clicked_events WHERE Email_Send_ID=%d", $Email->Email_Send_ID));

					echo "<tr id='list-item-" . $Email->Email_Send_ID . "' class='list-item'>";
					echo "<td class='users column-email-title'>" . get_the_title($Email->Email_ID) . "</td>";
					echo "<td class='users column-opened'>" . ($Email_Opened != '' ? $Email_Opened : 'No') . "</td>";
					echo "<td class='users column-links-clicked'>" . $Links_Clicked . "</td>";
					echo "<td class='users column-send-date-time'>" . $Email->Email_Sent_Datetime . "</td>";
					echo "</tr>";
				}
			}
		?>
	</tbody>
</table>

</div>
</div>

