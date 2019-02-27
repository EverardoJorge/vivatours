<?php

function EWD_UWPM_Add_Registration_Checkboxes() {
	$Add_Subscribe_Checkbox = get_option("EWD_UWPM_Add_Subscribe_Checkbox");
	$Add_Unsubscribe_Checkbox = get_option("EWD_UWPM_Add_Unsubscribe_Checkbox");

	if ($Add_Subscribe_Checkbox == "Yes") { ?>
		<style>.login input[type=checkbox] {width:auto;}</style>
		<label for="ewd_uwpm_subscribe"><?php _e( 'Subscribe to Email Updates', 'ultimate-wp-mail' ) ?><br />
        <input type="checkbox" name="ewd_uwpm_subscribe" id="ewd_uwpm_subscribe" class="input" value="<?php echo esc_attr( wp_unslash( $ewd_uwpm_subscribe ) ); ?>" size="25" /></label>
	<?php }

	if ($Add_Unsubscribe_Checkbox == "Yes") { ?>
		<style>.login input[type=checkbox] {width:auto;}</style>
		<label for="ewd_uwpm_unsubscribe"><?php _e( 'Unsubscribe from Email Updates', 'ultimate-wp-mail' ) ?><br />
        <input type="checkbox" name="ewd_uwpm_unsubscribe" id="ewd_uwpm_unsubscribe" class="input" value="<?php echo esc_attr( wp_unslash( $ewd_uwpm_unsubscribe ) ); ?>" size="25" /></label>
	<?php }
}
add_action('register_form', 'EWD_UWPM_Add_Registration_Checkboxes', 99);

function EWD_UWPM_Save_Registration_Checkboxes($user_id) {
	$Add_Subscribe_Checkbox = get_option("EWD_UWPM_Add_Subscribe_Checkbox");
	$Add_Unsubscribe_Checkbox = get_option("EWD_UWPM_Add_Unsubscribe_Checkbox");

	if ($Add_Subscribe_Checkbox == "Yes") {
		if (isset($_POST['ewd_uwpm_subscribe'])) {update_usermeta($user_id, 'EWD_UWPM_User_Subscribe', 'Yes');}
		else {update_usermeta($user_id, 'EWD_UWPM_User_Subscribe', 'No');}
	}

	if ($Add_Unsubscribe_Checkbox == "Yes") {
		if (isset($_POST['ewd_uwpm_unsubscribe'])) {update_usermeta($user_id, 'EWD_UWPM_User_Unsubscribe', 'Yes');}
		else {update_usermeta($user_id, 'EWD_UWPM_User_Unsubscribe', 'No');}
	}
}
add_action('user_register', 'EWD_UWPM_Save_Registration_Checkboxes');

function EWD_UWPM_Edit_Profile_Checkboxes($profileuser) {
	$Add_Subscribe_Checkbox = get_option("EWD_UWPM_Add_Subscribe_Checkbox");
	$Add_Unsubscribe_Checkbox = get_option("EWD_UWPM_Add_Unsubscribe_Checkbox");

	if ($Add_Subscribe_Checkbox == "Yes") { ?>
		<tr class="show-admin-bar user-admin-bar-front-wrap">
			<th scope="row"><?php _e( 'Subscribe to Email Updates', 'ultimate-wp-mail' ); ?></th>
			<td>
				<fieldset>
					<label for="ewd_uwpm_subscribe">
						<input name="ewd_uwpm_subscribe" type="checkbox" id="ewd_uwpm_subscribe" value="1" <?php echo (get_user_meta($profileuser->ID, 'EWD_UWPM_User_Subscribe', true) == "Yes") ? 'checked' : ''; ?> />
					</label>
				</fieldset>
			</td>
		</tr>
	<?php }

	if ($Add_Unsubscribe_Checkbox == "Yes") { ?>
		<tr class="show-admin-bar user-admin-bar-front-wrap">
			<th scope="row"><?php _e( 'Unsubscribe to Email Updates', 'ultimate-wp-mail' ); ?></th>
			<td>
				<fieldset>
					<label for="ewd_uwpm_subscribe">
						<input name="ewd_uwpm_unsubscribe" type="checkbox" id="ewd_uwpm_unsubscribe" value="1" <?php echo (get_user_meta($profileuser->ID, 'EWD_UWPM_User_Unsubscribe', true) == "Yes") ? 'checked' : ''; ?> />
					</label>
				</fieldset>
			</td>
		</tr>
	<?php }
}
add_action( 'personal_options', 'EWD_UWPM_Edit_Profile_Checkboxes' ); 

function EWD_UWPM_Save_Edit_Profile_Checkboxes( $user_id ) {
    $Add_Subscribe_Checkbox = get_option("EWD_UWPM_Add_Subscribe_Checkbox");
	$Add_Unsubscribe_Checkbox = get_option("EWD_UWPM_Add_Unsubscribe_Checkbox");
	
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    
    if ($Add_Subscribe_Checkbox == "Yes") {
		if (isset($_POST['ewd_uwpm_subscribe'])) {update_usermeta($user_id, 'EWD_UWPM_User_Subscribe', 'Yes');}
		else {update_usermeta($user_id, 'EWD_UWPM_User_Subscribe', 'No');}
	}

	if ($Add_Unsubscribe_Checkbox == "Yes") {
		if (isset($_POST['ewd_uwpm_unsubscribe'])) {update_usermeta($user_id, 'EWD_UWPM_User_Unsubscribe', 'Yes');}
		else {update_usermeta($user_id, 'EWD_UWPM_User_Unsubscribe', 'No');}
	}
}
add_action( 'personal_options_update', 'EWD_UWPM_Save_Edit_Profile_Checkboxes' );
add_action( 'edit_user_profile_update', 'EWD_UWPM_Save_Edit_Profile_Checkboxes' );
?>