jQuery(document).ready(function() {
	setLoginShortcodeHelpHandler();
	setLogoutShortcodeHelpHandler();
	setConfirmForgotPasswordShortcodeHelpHandler();
	setAccountDetailsShortcodeHelpHandler();
	setForgotPasswordShortcodeHelpHandler();
	setEditProfileShortcodeHelpHandler();
	setLoginLogoutToggleShortcodeHelpHandler();
	setRegisterShortcodeHelpHandler();
	setResetPasswordShortcodeHelpHandler();
	setUserDataShortcodeHelpHandler();
	setUserListShortcodeHelpHandler();
	setUserProfileShortcodeHelpHandler();
	setUserSearchShortcodeHelpHandler();
	setRestrictedShortcodeHelpHandler();
});

function setLoginShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[login help") >= 0) {
			var AttributeString = "[login \n";
			AttributeString += "redirect_page='#' the page to redirect visitors after a successful login \n";
			AttributeString += "redirect_field='' advanced redirect feature based on a specific field\n";
			AttributeString += "redirect_array_string='' the array of redirects based on the redirect field attribute\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[login help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setLogoutShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[logout help") >= 0) {
			var AttributeString = "[logout \n";
			AttributeString += "no_message='No' set to Yes to have no message appear on logout\n";
			AttributeString += "redirect_page='#' the page to redirect visitors after a successful logout\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[logout help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setConfirmForgotPasswordShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[confirm-forgot-password help") >= 0) {
			var AttributeString = "[confirm-forgot-password \n";
			AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission\n";
			AttributeString += "login_page='' the URL of your login page if you want a link to appear\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[confirm-forgot-password help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setAccountDetailsShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[account-details help") >= 0) {
			var AttributeString = "[account-details \n";
			AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission\n";
			AttributeString += "login_page='' the URL of your login page if you want a link to appear\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[account-details help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setForgotPasswordShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[forgot-password help") >= 0) {
			var AttributeString = "[forgot-password \n";
			AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission\n";
			AttributeString += "loggedin_page='/' the page to redirect users to if they're logged in and viewing this page\n";
			AttributeString += "reset_email_url='' the URL of your page with the confirm-forgot-password shortcode\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[forgot-password help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setEditProfileShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[edit-profile help") >= 0) {
			var AttributeString = "[edit-profile \n";
			AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission\n";
			AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page\n";
			AttributeString += "omit_fields='' fields to not include for editing (if you want them locked after registration)\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[edit-profile help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setLoginLogoutToggleShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[login-logout-toggle help") >= 0) {
			var AttributeString = "[login-logout-toggle \n";
			AttributeString += "login_redirect_page='#' the page to redirect visitors after a visitor logs in\n";
			AttributeString += "logout_redirect_page='#' the page to redirect visitors after a visitor logs out\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[login-logout-toggle help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setRegisterShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[register help") >= 0) {
			var AttributeString = "[register \n";
			AttributeString += "redirect_page='#' the page to redirect visitors after a successful registration\n";
			AttributeString += "redirect_field='' advanced redirect feature based on a specific field\n";
			AttributeString += "redirect_array_string='' the array of redirects based on the redirect field attribute\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[register help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setResetPasswordShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[reset-password help") >= 0) {
			var AttributeString = "[reset-password \n";
			AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission\n";
			AttributeString += "login_page='' the URL of your login page if you want a link to appear\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[reset-password help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setUserDataShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[user-data help") >= 0) {
			var AttributeString = "[user-data \n";
			AttributeString += "field_name='Username' the name of the field that you want to display the user's information for\n";
			AttributeString += "plain_text='Yes' set to No to add HTML to style the data\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[user-data help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setUserListShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[user-list help") >= 0) {
			var AttributeString = "[user-list \n";
			AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page if login necessary is set to Yes\n";
			AttributeString += "field_name='' the name of the field to filter users by - leave blank to show all users\n";
			AttributeString += "field_value='' the value of the field you're showing (ex: Male if field name is Gender)\n";
			AttributeString += "login_necessary='Yes' set to No to allow anyone to view this list\n";
			AttributeString += "display_field='Username' the field you want to display for using matching your list criteria\n";
			AttributeString += "order_by='' the field you want to order your results based on\n";
			AttributeString += "order='ASC' the direction of the ordering (ASC OR DESC)\n";
			AttributeString += "user_profile_page='' the URL of the page with the user profile shortcode, if you want to link to user profiles\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[user-list help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setUserProfileShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[user-profile help") >= 0) {
			var AttributeString = "[user-profile (premium shortcode)\n";
			AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page if login necessary is set to Yes\n";
			AttributeString += "omit_fields='' a comma-separated list of fields that you don't want to display\n";
			AttributeString += "login_necessary='Yes' set to No to allow anyone to view profiles\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[user-profile help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setUserSearchShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[user-search help") >= 0) {
			var AttributeString = "[user-search \n";
			AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page if login necessary is set to Yes\n";
			AttributeString += "login_necessary='Yes' set to No to allow anyone to search users\n";
			AttributeString += "search_fields='Username' what field(s) should be searchable?\n";
			AttributeString += "display_field='Username' what field should be displayed for the matching users\n";
			AttributeString += "search_logic='OR' set to AND to only display matching meeting all of the search criteria\n";
			AttributeString += "order_by='' the field you want to order your results based on\n";
			AttributeString += "order='ASC' the direction of the ordering (ASC OR DESC)\n";
			AttributeString += "user_profile_page='' the URL of the page with the user profile shortcode, if you want to link to user profiles\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[user-search help', AttributeString);
			jQuery(this).val(text);
		}
	})
}

function setRestrictedShortcodeHelpHandler() {
	jQuery('.wp-editor-area').on('keyup', function(){
		if (jQuery(this).val().indexOf("[restricted help") >= 0) {
			var AttributeString = "[restricted \n";
			AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page\n";
			AttributeString += "block_logged_in='No' set to Yes to restrict this content to only non-logged in users\n";
			AttributeString += "no_message='No' set to Yes to not show a message when content is hidden from a visitor\n";
			AttributeString += "minimum_level='' the minimum user level that can access this page - OK to leave blank\n";
			AttributeString += "maximum_level='' the maximum user level that can access this page - OK to leave blank\n";
			AttributeString += "level='' the only user level that can access this page - OK to leave blank\n";
			AttributeString += "field_name='' the name of the field you want to use to restrict access - OK to leave blank\n";
			AttributeString += "field_value='' the value of the field you want to use to restrict access - OK to leave blank\n";
			AttributeString += "sneak_peak_characters='0' the number of characters of the content that should be shown if a user isn't able to access the content\n";
			AttributeString += "sneak_peak_words='0' the number of words of the content that should be shown if a user isn't able to access the content\n";
			AttributeString += "]";

			var text = jQuery(this).val();
			text = text.replace('[restricted help', AttributeString);
			jQuery(this).val(text);
		}
	})
}