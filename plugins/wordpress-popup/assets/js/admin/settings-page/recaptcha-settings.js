Hustle.define("Settings.reCAPTCHA_Settings", function($){
	"use strict";
	return Backbone.View.extend({
		el: "#wpmudev-settings-recaptcha",
		events: {
			"submit #wpmudev-settings-recaptcha-form": "save_recaptcha_settings",
		},
		initialize: function(){
		},
		save_recaptcha_settings: function(e){
			e.preventDefault();
			var $form = this.$( e.target ),
				nonce = $form.data("nonce"),
				data = $form.serialize();
			$form.find( 'button[type=submit]').prop( 'disabled', true );
			$.ajax( {
				url: ajaxurl,
				type: "POST",
				data:  {
					action: "hustle_save_global_recaptcha_settings",
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
