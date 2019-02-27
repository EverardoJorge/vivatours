(function( $ ) {
	"use strict";
	
	var Module = window.Module || {};
	
	Module.Validate = {
		validate_module_name: function() {
			var success = true;
			
			if ( $('input[name=module_name]').length ) {
				var elem = $('input[name=module_name]'),
				    error_label = elem.next('.wpmudev-label--notice');
				success = elem.val().length !== 0;
				if ( !success ){
					elem.focus();
					if ( error_label.hasClass('wpmudev-hidden') ) {
						error_label.removeClass('wpmudev-hidden');
					}
				} else {
					if ( !error_label.hasClass('wpmudev-hidden') ) {
						error_label.addClass('wpmudev-hidden');
					}
					
				}
			}
			return success;
		},
		on_change_validate_module_name: function(e) {
			var val = $(e.target).val(),
				error_label = $(e.target).next('.wpmudev-label--notice');
			if(val.length !== 0 ){
				if ( !error_label.hasClass('wpmudev-hidden') ) {
					error_label.addClass('wpmudev-hidden');
				}
			} else{
				if ( error_label.hasClass('wpmudev-hidden') ) {
					error_label.removeClass('wpmudev-hidden');
				}
			}
		}
	};
	
	Module.Utils = {
		
		/*
		 * Return URL param value
		 */
		get_url_param: function ( param ) {
			var page_url = window.location.search.substring(1),
				url_params = page_url.split('&');

			for ( var i = 0; i < url_params.length; i++ ) {
				var param_name = url_params[i].split('=');
				if ( param_name[0] === param ) {
					return param_name[1];
				}
			}

			return false;
		},

		service_supports_fields: function( save_local_list, active_email_service ) {
			if ( '1' === String(save_local_list) ) {
				return true;
			} else if (
				! _.isEmpty( active_email_service ) && 
				typeof( optin_vars.providers[active_email_service] ) !== 'undefined'
			) {
				return optin_vars.providers[active_email_service].supports_fields;
			}
			return false;
		},
	}
	
	
}(jQuery));
