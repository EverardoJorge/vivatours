<!-- Upgrade to pro link box -->
<!-- TOP BOX-->
<?php //echo get_option("EWD_UWPM_Debugging"); ?>
<?php global $wpdb;
?>

<!--Middle box-->
<div class="ewd-dashboard-middle">
<div id="col-full">
<h3 class="ewd-uwpm-dashboard-h3">Emails Summary</h3>
<div>
	<table class='ewd-uwpm-overview-table wp-list-table widefat fixed striped posts'>
		<thead>
			<tr>
				<th><?php _e("Title", 'EWD_ABCO'); ?></th>
				<th><?php _e("Created Date", 'EWD_ABCO'); ?></th>
				<th><?php _e("Emails Sent", 'EWD_ABCO'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$args = array(
					'post_type' => 'uwpm_mail_template'
				);

				$Dashboard_Emails_Query = new WP_Query($args);
				$Dashboard_Emails = $Dashboard_Emails_Query->get_posts();

				if (sizeOf($Dashboard_Emails) == 0) {echo "<tr><td colspan='3'>" . __("No emails to display yet. Create an email and then view it for it to be displayed here.", 'ultimate-wp-mail') . "</td></tr>";}
				else {
					foreach ($Dashboard_Emails as $Dashboard_Email) { ?>
						<tr>
							<td><a href='post.php?post=<?php echo $Dashboard_Email->ID;?>&action=edit'><?php echo $Dashboard_Email->post_title; ?></a></td>
							<td><?php echo $Dashboard_Email->post_date; ?></td>
							<td><?php echo $wpdb->get_var($wpdb->prepare("SELECT COUNT(Email_Send_ID) FROM $ewd_uwpm_email_send_events WHERE Email_ID=%d", $Dashboard_Email->ID)); ?></td>
						</tr>
					<?php }
				}
			?>
		</tbody>
	</table>
</div>
<br class="clear" />
</div>
</div>

<!-- END MIDDLE BOX -->
