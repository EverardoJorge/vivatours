Hustle.define("Settings.Gdpr_Settings", function($){
	"use strict";
	return Backbone.View.extend({
		el: "#wpmudev-settings-gdpr",
		events: {
			"click #wph-gdpr-settings-submit": "remove_ip",
		},
		initialize: function() {

		},
		remove_ip: function(e) {
			e.preventDefault();
			var $this = this.$( e.target ),
				$form = this.$el.find('#wph-gdpr-settings'),
				$button = this.$el.find( '#wph-gdpr-settings-submit' ),
				data = _.reduce( $form.serializeArray(), function(obj, item){
				obj[ item['name'] ] = item['value'];
				return obj;
			}, {});
			data.action = 'hustle_remove_ips';
			data._ajax_nonce = $this.data('nonce');
			$button.prop( 'disabled', true );
			$button.addClass( 'wpmudev-button-onload' );
			this.$el.find( '.wpmudev-loading' ).show();
			this.process_batch( 0, data, this, 0 );
		},
		process_batch: function( offset, data, self, updated ) {
			data.offset = offset;
			data.updated = updated;
			$.post({
				url: ajaxurl,
				type: 'post',
				data: data,
			})
			.done( function ( result ) {
				if ( result && result.success ) {
					if ( 'done' === result.data.offset ) {
						var $form = self.$el.find('#wph-gdpr-settings'),
							$button = self.$el.find( '#wph-gdpr-settings-submit' );
						$button.removeClass( 'wpmudev-button-onload' );
						self.$el.find( '.wpmudev-loading' ).hide();
						$form.find( '#hustle-delete-ip').val('');
						$form.append( '<label class="wpmudev-label--success">' + result.data.updated + optin_vars.messages.settings_rows_updated + '</label>' );
						setTimeout( function() {
							$form.find( '.wpmudev-label--success' ).remove();
							$button.prop( 'disabled', false );
						}, 6000);
					} else {
						self.process_batch( parseInt( result.data.offset ), data, self, parseInt(result.data.updated )  );
					}
				}
			});
		},
	});

});
