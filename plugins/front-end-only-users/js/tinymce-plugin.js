(function() {
    tinymce.PluginManager.add('FEUP_Shortcodes', function( editor, url ) {
        editor.addButton( 'FEUP_Shortcodes', {
            title: 'FEUP Shortcodes',
            text: 'FE Users',
            type: 'menubutton',
            icon: 'wp_code',
            menu: [{
            	text: 'Register',
            	value: 'register',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert Register Shortcode',
				        body: [{
            				type: 'textbox',
            				name: 'redirect_page',
            				label: 'Redirect URL on Successful Registration:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[register redirect_page="'+ e.data.redirect_page +'"]');
				        }
				    });
				}
			},
			{
            	text: 'Login',
            	value: 'login',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert Login Shortcode',
				        body: [{
            				type: 'textbox',
            				name: 'redirect_page',
            				label: 'Redirect on Successful Login:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[login redirect_page="'+ e.data.redirect_page +'"]');
				        }
				    });
				}
			},
			{
            	text: 'Logout',
            	value: 'logout',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert Logout Shortcode',
				        body: [{
            				type: 'textbox',
            				name: 'redirect_page',
            				label: 'Redirect on Logout:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[logout redirect_page="'+ e.data.redirect_page +'"]');
				        }
				    });
				}
			},
			{
            	text: 'Login-Logout Toggle',
            	value: 'login-logout-toggle',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert Login-Logout Toggle Shortcode',
				        body: [{
            				type: 'textbox',
            				name: 'login_redirect_page',
            				label: 'Redirect on Successful Login:',
				        },
				        {
            				type: 'textbox',
            				name: 'logout_redirect_page',
            				label: 'Redirect on Logout:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[login-logout-toggle login_redirect_page="'+ e.data.login_redirect_page +'" logout_redirect_page="'+ e.data.logout_redirect_page +'"]');
				        }
				    });
				}
			},
			{
            	text: 'Forgot Password',
            	value: 'forgot-password',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert Forgot Password Shortcode',
				        body: [{
            				type: 'textbox',
            				name: 'redirect_page',
            				label: 'Redirect After Submitting:',
				        },
				        {
            				type: 'textbox',
            				name: 'reset_email_url',
            				label: 'Confirm Reset Password URL:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[forgot-password redirect_page="'+ e.data.redirect_page +'" reset_email_url="'+ e.data.reset_email_url +'"]');
				        }
				    });
				}
			},
			{
            	text: 'Confirm Forgot Password',
            	value: 'confirm-forgot-password',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert Confirm Forgot Password Shortcode',
				        body: [{
            				type: 'textbox',
            				name: 'redirect_page',
            				label: 'Redirect After Resetting Password:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[confirm-forgot-password redirect_page="'+ e.data.redirect_page +'"]');
				        }
				    });
				}
			},
			{
            	text: 'Edit Profile',
            	value: 'edit-profile',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert Edit Profile Shortcode',
				        body: [{
            				type: 'textbox',
            				name: 'redirect_page',
            				label: 'Redirect After Editing Profile:',
				        },
				        {
            				type: 'textbox',
            				name: 'login_page',
            				label: 'Login Page URL:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[edit-profile redirect_page="'+ e.data.redirect_page +'" login_page="' + e.data.login_page  + '"]');
				        }
				    });
				}
			},
			{
            	text: 'User List',
            	value: 'user-list',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert User List Shortcode',
				        body: [{
            				type: 'listbox',
            				name: 'login_necessary',
            				label: 'Only Logged In Users Can View:',
				            'values': [
            				    {text: 'Yes', value: 'Yes'},
            				    {text: 'No', value: 'No'}
            				]
				        },
				        {
            				type: 'textbox',
            				name: 'login_page',
            				label: 'Login Page URL:',
				        },
				        {
            				type: 'listbox',
            				name: 'display_field',
            				label: 'Field to Display:',
				            'values': EWD_FEUP_Create_Field_List()
				        },
				        {
            				type: 'textbox',
            				id: 'ul-user-profile-page',
            				name: 'user_profile_page',
            				label: 'User Profile Page URL:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[user-list login_necessary="'+ e.data.login_necessary +'" login_page="' + e.data.login_page  + '" display_field="' + e.data.display_field  + '" user_profile_page="' + e.data.user_profile_page  + '"]');
				        }
				    });
				}
			},
			{
            	text: 'User Search',
            	value: 'user-search',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert User Search Shortcode',
				        body: [{
            				type: 'listbox',
            				name: 'login_necessary',
            				label: 'Only Logged In Users Can View:',
				            'values': [
            				    {text: 'Yes', value: 'Yes'},
            				    {text: 'No', value: 'No'}
            				]
				        },
				        {
            				type: 'listbox',
            				name: 'search_field',
            				label: 'Field to Search:',
				            'values': EWD_FEUP_Create_Field_List()
				        },
				        {
            				type: 'listbox',
            				name: 'display_field',
            				label: 'Field to Display:',
				            'values': EWD_FEUP_Create_Field_List()
				        },
				        {
            				type: 'textbox',
            				id: 'us-user-profile-page',
            				name: 'user_profile_page',
            				label: 'User Profile Page URL:',
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[user-search login_necessary="'+ e.data.login_necessary + '" search_fields="'+ e.data.search_field + '" display_field="'+ e.data.display_field + '" user_profile_page="'+ e.data.user_profile_page + '"]');
				        }
				    });
				}
			},
			{
            	text: 'User Profile Page',
            	onPostRender: function() {EWD_FEUP_Non_Premium_User_Profile();},
            	value: 'user-profile-page',
            	id: 'user-profile-page',
            	onclick: function() {
				    var premium = EWD_FEUP_Is_Premium();
				    if (!premium) {return;}

				    var win = editor.windowManager.open( {
				        title: 'Insert User List Shortcode',
				        body: [{
            				type: 'listbox',
            				name: 'login_necessary',
            				label: 'Only Logged In Users Can View:',
				            'values': [
            				    {text: 'Yes', value: 'Yes'},
            				    {text: 'No', value: 'No'}
            				]
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[user-profile login_necessary="'+ e.data.login_necessary + '"]');
				        }
				    });
				}
			},
			{
            	text: 'User Data',
            	value: 'user-data',
            	onclick: function() {
				    var win = editor.windowManager.open( {
				        title: 'Insert User Data Shortcode',
				        body: [{
            				type: 'listbox',
            				name: 'field_name',
            				label: 'Field to Display:',
				            'values': EWD_FEUP_Create_Field_List()
				        }],
				        onsubmit: function( e ) {
				            editor.insertContent( '[user-data field_name="'+ e.data.field_name + '"]');
				        }
				    });
				}
			}],
        });
    });
})();

function EWD_FEUP_Create_Field_List() {
	var result = new Array();

	jQuery(feup_fields).each(function(index, el) {
		var d = {};
		d['text'] = el;
		d['value'] = el;
		result.push(d)
	});

    return result;
}

function EWD_FEUP_Non_Premium_User_Profile() {
	var premium = EWD_FEUP_Is_Premium();

	if (!premium) {
		jQuery('#user-profile-page').css('opacity', '0.5');
		jQuery('#user-profile-page').css('cursor', 'default');
	}
}

function EWD_FEUP_Is_Premium() {
	if (feup_premium == "Yes") {return true;}
	
	return false;
}
