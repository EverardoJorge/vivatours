Hustle.define("Settings.Unsubscribe_Settings", function($){
	"use strict";
	return Backbone.View.extend({
		el: "#wpmudev-settings-unsubscribe",
		events: {
			"submit #wpmudev-settings-unsubscribe-messages": "save_unsubscription_messages",
			"submit #wpmudev-settings-unsubscribe-email": "save_unsubscription_email",
		},
		initialize: function(){

		},
		save_unsubscription_messages: function(e){
			e.preventDefault();

			var $form = this.$( e.target ),
				nonce = $form.data("nonce"),
				data = $form.serialize();

			$form.find( 'button[type=submit]').addClass( 'wpmudev-button-onload' );

			$.ajax( {
				url: ajaxurl,
				type: "POST",
				data:  {
					action: "hustle_save_unsubscribe_messages_settings",
					data: data,
					_ajax_nonce: nonce
				},
				success: function( res ){
					if ( res.success ) {
						$form.append( '<label class="wpmudev-label--success">' + optin_vars.messages.settings_saved + '</label>' );
						$form.find( 'button[type=submit]').removeClass( 'wpmudev-button-onload' );
						setTimeout( function() {
							$form.find( '.wpmudev-label--success' ).remove();
						}, 1000);
					}
					
				}

			});

		},
		save_unsubscription_email: function(e){
			e.preventDefault();

			var $form = this.$( e.target ),
				nonce = $form.data("nonce"),
				data = $form.serialize();

			$form.find( 'button[type=submit]').addClass( 'wpmudev-button-onload' );

			$.ajax( {
				url: ajaxurl,
				type: "POST",
				data:  {
					action: "hustle_save_unsubscribe_email_settings",
					data: data,
					_ajax_nonce: nonce
				},
				success: function( res ){
					if ( res.success ) {
						$form.append( '<label class="wpmudev-label--success">' + optin_vars.messages.settings_saved + '</label>' );
						$form.find( 'button[type=submit]').removeClass( 'wpmudev-button-onload' );
						setTimeout( function() {
							$form.find( '.wpmudev-label--success' ).remove();
						}, 1000);
					}
					
				}

			});

		}
	});

});
