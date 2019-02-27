Hustle.define("Modal_Import", function($){
		"use strict";

		return  Backbone.View.extend({
				id: "wph-modal-import",
				className: "wpmudev-modal",
				template: Optin.template("hustle-modal-import-tpl"),
				show_delay: 350,
				events: {
					'click .wpmudev-button-confirm-import': 'import',
					'click .wpmudev-button-cancel-import': 'cancelImport',
					'click .wpmudev-modal-mask, .wpmudev-i_close, .wpmudev-i_close path': 'hide',
					'click #wpmudev-button-import': 'preImport',
				},
				initialize: function(){
					return this.render();
				},
				render: function(){
					var html = this.template(this.model);

					this.importButton = this.$('#wpmudev-button-import');
					this.$el.html( html );
					this.$el.appendTo( "#wpmudev-hustle" );
					this.show();
					return this;
				},
				cancelImport: function(e) {
					$(e.target).parents('.hustle-confirmation').addClass('wpmudev-hidden');
					this.importButton.prop('disabled', false);
				},
				preImport: function(e) {
					//show Are you sure? notice
					if ( ! $('#wph-optin-service-import-form input').val() ) {
						return;
					}
					$(e.target).parents('.wpmudev-box-footer').find('.hustle-confirmation').removeClass('wpmudev-hidden');
					this.importButton.prop('disabled', true);
				},
				import: function(e) {
					var $this = $(e.target);
					var data;
					var file = $('#wph-optin-service-import-form input[type=file]').get(0).files[0];

					data = new FormData();
					data.append( 'action', 'hustle_import_module' );
					data.append( 'id', $this.data("id") );
					data.append( 'type', $this.data("type") );
					data.append( '_ajax_nonce', $this.data("nonce") );
					data.append( 'file', file );

					$.ajax({
						url: ajaxurl,
						type: "POST",
						data: data,
						cache: false,
						processData: false,
						contentType: false,
						success: function(resp){
							if ( resp.success ) {
								location.reload();
							} else {
								//show error notice
								$('#import_error').html(
									$( '<label><span>' + resp.data + '</span></label>' ).prop({
										class: "wpmudev-label--notice"
									})
								);
								$('#import_error label').delay(5000).fadeOut("slow", function() {
									$(this).remove();
								});
							}
						}
					});
				},
				show: function(){
					// Body.
					$('body').addClass('wpmudev-modal-is_active');
					// Overlay.
					this.$el.addClass('wpmudev-modal-active');
					// Modal.
					this.$el.find('.wpmudev-box-modal').addClass('wpmudev-show').removeClass('wpmudev-hide');
				},
				hide: function(e) {
					var $target = $(e.target),
						$modal = this.$el.find('.wpmudev-box-modal'),
						me = this
					;

					// If target is not close button or mask, quit.
					if (
						!$target.hasClass('wpmudev-modal-mask')
						&& !$target.hasClass('wpmudev-i_close')
						&& !$target.parent().hasClass('wpmudev-i_close')
					) {
						return;
					}
			
					// Modal.
					$modal.removeClass('wpmudev-show').addClass('wpmudev-hide');
					
					setTimeout(function() {
							// Overlay.
							me.$el.removeClass('wpmudev-modal-active');
							// Body.
							$('body').removeClass('wpmudev-modal-is_active');
							// Modal.
							$modal.removeClass('wpmudev-hide');
					}, 500);
				},

		});

});
