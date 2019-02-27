Hustle.define("Settings.Mail_Settings", function($){
	"use strict";
	return Backbone.View.extend({
		el: "#wpmudev-settings-mail",
		events: {
			"submit #wpmudev-settings-mail-form": "save_email_settings",
		},
		initialize: function(){
		
		},
		save_email_settings: function(e){
			e.preventDefault();

			var $form = this.$( e.target ),
				nonce = $form.data("nonce"),
				data = $form.serialize();

			$form.find( 'button[type=submit]').prop( 'disabled', true );

			$.ajax( {
				url: ajaxurl,
				type: "POST",
				data:  {
					action: "hustle_save_global_email_settings",
					data: data,
					_ajax_nonce: nonce
				},
				success: function( res ){
					if ( res.success ) {
						$form.append( '<label class="wpmudev-label--success">' + optin_vars.messages.settings_saved + '</label>' );
						setTimeout( function() {
							$form.find( '.wpmudev-label--success' ).remove();
							$form.find( 'button[type=submit]').prop( 'disabled', false );
						}, 3000);
					}

					
				}

			});

		}
	});

});
