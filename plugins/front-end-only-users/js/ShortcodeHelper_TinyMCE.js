jQuery(document).ready(function() {
	setLoginShortcodeHelpHandler_tinyMCE();
	setLogoutShortcodeHelpHandler_tinyMCE();
	setConfirmForgotPasswordShortcodeHelpHandler_tinyMCE();
	setAccountDetailsShortcodeHelpHandler_tinyMCE();
	setForgotPasswordShortcodeHelpHandler_tinyMCE();
	setEditProfileShortcodeHelpHandler_tinyMCE();
	setLoginLogoutToggleShortcodeHelpHandler_tinyMCE();
	setRegisterShortcodeHelpHandler_tinyMCE();
	//setResetPasswordShortcodeHelpHandler_tinyMCE();
	setUserDataShortcodeHelpHandler_tinyMCE();
	setUserListShortcodeHelpHandler_tinyMCE();
	setUserProfileShortcodeHelpHandler_tinyMCE();
	setUserSearchShortcodeHelpHandler_tinyMCE();
	setRestrictedShortcodeHelpHandler_tinyMCE();
});

function setLoginShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[login help") >= 0) {
				var AttributeString = "[login <br />";
				AttributeString += "redirect_page='#' the page to redirect visitors after a successful login <br />";
				AttributeString += "redirect_field='' advanced redirect feature based on a specific field<br />";
				AttributeString += "redirect_array_string='' the array of redirects based on the redirect field attribute<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[login help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setLogoutShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[logout help") >= 0) {
				var AttributeString = "[logout <br />";
				AttributeString += "no_message='No' set to Yes to have no message appear on logout<br />";
				AttributeString += "redirect_page='#' the page to redirect visitors after a successful logout<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[logout help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setConfirmForgotPasswordShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[confirm-forgot-password help") >= 0) {
				var AttributeString = "[confirm-forgot-password <br />";
				AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission<br />";
				AttributeString += "login_page='' the URL of your login page if you want a link to appear<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[confirm-forgot-password help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setAccountDetailsShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[account-details help") >= 0) {
				var AttributeString = "[account-details <br />";
				AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission<br />";
				AttributeString += "login_page='' the URL of your login page if you want a link to appear<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[account-details help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setForgotPasswordShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[forgot-password help") >= 0) {
				var AttributeString = "[forgot-password <br />";
				AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission<br />";
				AttributeString += "loggedin_page='/' the page to redirect users to if they're logged in and viewing this page<br />";
				AttributeString += "reset_email_url='' the URL of your page with the confirm-forgot-password shortcode<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[forgot-password help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setEditProfileShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[edit-profile help") >= 0) {
				var AttributeString = "[edit-profile <br />";
				AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission<br />";
				AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page<br />";
				AttributeString += "omit_fields='' fields to not include for editing (if you want them locked after registration)<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[edit-profile help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setLoginLogoutToggleShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[login-logout-toggle help") >= 0) {
				var AttributeString = "[login-logout-toggle <br />";
				AttributeString += "login_redirect_page='#' the page to redirect visitors after a visitor logs in<br />";
				AttributeString += "logout_redirect_page='#' the page to redirect visitors after a visitor logs out<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[login-logout-toggle help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setRegisterShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[register help") >= 0) {
				var AttributeString = "[register <br />";
				AttributeString += "redirect_page='#' the page to redirect visitors after a successful registration<br />";
				AttributeString += "redirect_field='' advanced redirect feature based on a specific field<br />";
				AttributeString += "redirect_array_string='' the array of redirects based on the redirect field attribute<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[register help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

/*function setResetPasswordShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[reset-password help") >= 0) {
				var AttributeString = "[reset-password <br />";
				AttributeString += "redirect_page='#' the page to redirect visitors after a successful submission<br />";
				AttributeString += "login_page='' the URL of your login page if you want a link to appear<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[reset-password help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}*/

function setUserDataShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[user-data help") >= 0) {
				var AttributeString = "[user-data <br />";
				AttributeString += "field_name='Username' the name of the field that you want to display the user's information for<br />";
				AttributeString += "plain_text='Yes' set to No to add HTML to style the data<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[user-data help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setUserListShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[user-list help") >= 0) {
				var AttributeString = "[user-list <br />";
				AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page if login necessary is set to Yes<br />";
				AttributeString += "field_name='' the name of the field to filter users by - leave blank to show all users<br />";
				AttributeString += "field_value='' the value of the field you're showing (ex: Male if field name is Gender)<br />";
				AttributeString += "login_necessary='Yes' set to No to allow anyone to view this list<br />";
				AttributeString += "display_field='Username' the field you want to display for using matching your list criteria<br />";
				AttributeString += "user_profile_page='' the URL of the page with the user profile shortcode, if you want to link to user profiles<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[user-list help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setUserProfileShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[user-profile help") >= 0) {
				var AttributeString = "[user-profile (premium shortcode) <br />";
				AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page if login necessary is set to Yes<br />";
				AttributeString += "omit_fields='' a comma-separated list of fields that you don't want to display<br />";
				AttributeString += "login_necessary='Yes' set to No to allow anyone to view profiles<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[user-profile help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setUserSearchShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[user-search help") >= 0) {
				var AttributeString = "[user-search <br />";
				AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page if login necessary is set to Yes<br />";
				AttributeString += "login_necessary='Yes' set to No to allow anyone to search users<br />";
				AttributeString += "search_fields='Username' what field(s) should be searchable?<br />";
				AttributeString += "display_field='Username' what field should be displayed for the matching users<br />";
				AttributeString += "search_logic='OR' set to AND to only display matching meeting all of the search criteria<br />";
				AttributeString += "user_profile_page='' the URL of the page with the user profile shortcode, if you want to link to user profiles<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[user-search help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}

function setRestrictedShortcodeHelpHandler_tinyMCE() {
	tinymce.PluginManager.add('keyup_event', function(editor, url) {
        // Create keyup event
        editor.on('keyup', function(e) {
            var tinymce_content = tinyMCE.activeEditor.getContent();
			if (tinymce_content.indexOf("[restricted help") >= 0) {
				var AttributeString = "[restricted <br />";
				AttributeString += "login_page='' the URL of your login page if you want a link to appear when a non-logged in visitor views this page<br />";
				AttributeString += "block_logged_in='No' set to Yes to restrict this content to only non-logged in users<br />";
				AttributeString += "no_message='No' set to Yes to not show a message when content is hidden from a visitor<br />";
				AttributeString += "minimum_level='' the minimum user level that can access this page - OK to leave blank<br />";
				AttributeString += "maximum_level='' the maximum user level that can access this page - OK to leave blank<br />";
				AttributeString += "level='' the only user level that can access this page - OK to leave blank<br />";
				AttributeString += "field_name='' the name of the field you want to use to restrict access - OK to leave blank<br />";
				AttributeString += "field_value='' the value of the field you want to use to restrict access - OK to leave blank<br />";
				AttributeString += "sneak_peak_characters='0' the number of characters of the content that should be shown if a user isn't able to access the content<br />";
				AttributeString += "sneak_peak_words='0' the number of words of the content that should be shown if a user isn't able to access the content<br />";
				AttributeString += "]";

				tinymce_content = tinymce_content.replace('[restricted help', AttributeString);
				tinyMCE.activeEditor.setContent(tinymce_content)
			}
        });
    });
}