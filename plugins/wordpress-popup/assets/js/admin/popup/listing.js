Hustle.define("Popup.Listing", function($){
		"use strict";
		var Delete_Confirmation = Hustle.get("Delete_Confirmation"),
			Upgrade_Modal = Hustle.get("Upgrade_Modal");

		return Backbone.View.extend({
				el: "#wpmudev-hustle",
				logShown: false,
				events: {
						'click .import-module-settings': 'import_settings',
						"click .hustle-delete-module": "delete_module",
						"click #hustle-free-version-create": "show_upgrade_modal",
						"click .button-view-email-list": "view_email_list",
						"click .module-toggle-tracking-activity": "toggle_tracking_activity",
						"click .module-duplicate": "duplicate",
						"click .button-view-log-list": "view_error_log_list",
						"click #wpmudev-bulk-action-button": "apply_bulk_action",
						"change [name='wph-module-status']": "module_status_updated",
						"change #wph-all-popups": "select_all",
						"change #wph-optin-service-import-form input[type=file]": "toggle_notice",
				},
				initialize: function(){
						var self = this;

						var $item = $('.wpmudev-list .wpmudev-list--element'),
								totalItems = $item.length,
								itemCount  = totalItems;
						
						$item.each(function() {

								$(this).css('z-index', itemCount);
								itemCount--;

								var $dropdown = $(this).find('.wpmudev-dots-dropdown'),
										$button = $dropdown.find('.wpmudev-dots-button'),
										$droplist = $dropdown.find('.wpmudev-dots-nav');

								$button.on('click', function(){
										$(this).toggleClass('wpmudev-active');
										$droplist.toggleClass('wpmudev-hide');
										self.$('.wpmudev-dots-nav').not($droplist).each( function() {
											if ( !$(this).hasClass('wpmudev-hide') ) {
												$(this).toggleClass('wpmudev-hide');
											}
										});
								});

						});
						
						this.delete_confirmation = new Delete_Confirmation({
							action: 'hustle_delete_module',
							onSuccess: function(res){
								if ( res.success ) {
									location.reload();
								}
							}
						});
						
						this.upgrade_modal = new Upgrade_Modal();
						if ( Module.Utils.get_url_param( 'requires_pro' ) ) {
							this.show_upgrade_modal();
						}
						
				},
				delete_module: function(e) {
						var $this = $(e.target),
							id = $this.data('id'),
							nonce = $this.data('nonce'); 
						
						if ( this.delete_confirmation ) {
							this.delete_confirmation.opts.id = id;
							this.delete_confirmation.opts.nonce = nonce;
							this.delete_confirmation.$el.addClass('wpmudev-modal-active');
						}
				},
				toggle_notice: function(e) {
					//hide/show notice for selecting file
					if ( $(e.target).val() ) {
						$('#select_file_error').addClass('wpmudev-hidden');
					} else {
						$('#select_file_error').removeClass('wpmudev-hidden');
					}
				},
				import_settings: function(e) {
					//show import popup
					e.preventDefault();
					e.stopPropagation();

					var $this = this.$(e.target),
							id = $this.data("id"),
							type = $this.data("type"),
							name = $this.data("name"),
							nonce = $this.data("nonce"),
							Modal_Import = Hustle.get("Modal_Import");

					// Get rid of old import.
					if ( this.importShown ) {
						this.importShown.remove();
					}
					// Render modal.
					this.importShown = new Modal_Import({
						model: {
							id: id,
							name: name,
							nonce: nonce,
							type: type
						}
					});
				},
				show_upgrade_modal: function(e) {
					if ( typeof( e ) !== 'undefined' ) {
						e.preventDefault();
					}
					if ( this.upgrade_modal ) {
						this.upgrade_modal.$el.addClass('wpmudev-modal-active');
					}
				},
				module_status_updated: function(e) {
					var $this = this.$(e.target),
						value = $this.val(),
						data = $this.data(),
						$li = $this.closest('li.wpmudev-tabs-menu_item');
						
					$li.addClass('current');
					$li.siblings().removeClass('current');
						
					data._ajax_nonce = data.nonce;
					
					if ( value === 'test' ) {
						data.action = "hustle_popup_toggle_test_activity";
					} else {
						data.action = "hustle_popup_module_toggle_state";
						if ( value === 'off' ) {
							data.enabled = 'false';
						} else {
							data.enabled = 'true';
						}
					}
					
					$.post(ajaxurl, data,function(response){
						// nothing for now
					});
				},
				view_email_list: function(e){
					e.preventDefault();
					e.stopPropagation();
					var $this = this.$(e.target),
							id = $this.data("id"),
							name = $this.data("name"),
							total = $this.data("total"),
							Modal_Email = Hustle.get("Modal_Email");

					// Get rid of old modal.
					if ( this.emailsShown ) {
						this.emailsShown.remove();
					}
					// Render modal.
					this.emailsShown = new Modal_Email({
						model: {
							id: id,
							total: total,
							name: name,
							type: 'popup',
							module_fields: []
						}
					});
				},
				set_testmode_visibiliy: function( active_toggle, speed ) {
						if( typeof speed === 'undefined' ) speed = 400;
						var $this = active_toggle,
								data = $this.data() || {};

						var $test_mode_toggle = this.$('.wpoi-testmode-active-state[data-id="' + data.id + '"][data-type="' + data.type + '"]').closest(".test-mode");
						if( $this.is( ":checked" ) ){
								$test_mode_toggle.fadeOut( speed );
						} else {
								$test_mode_toggle.fadeIn( speed );
						}

				},
				toggle_tracking_activity: function(e){
						var $this = $(e.target),
								id = $this.data("id"),
								nonce = $this.data("nonce"),
								type = $this.data("type");

						$.ajax({
								url: ajaxurl,
								type: "POST",
								data: {
										action: "hustle_popup_module_toggle_tracking_activity",
										id: id,
										type: type,
										_ajax_nonce: nonce
								},
								complete: function(){
									location.reload();
								}
						});
				},
				duplicate: function(e){
						var self = this,
							$this = $(e.target),
							id = $this.data("id"),
							nonce = $this.data("nonce"),
							type = $this.data("type");
						$.ajax({
								url: ajaxurl,
								type: "POST",
								data: {
										action: "hustle_popup_duplicate",
										id: id,
										type: type,
										_ajax_nonce: nonce
								},
								success: function( res ) {
									if ( res.success ) {
										location.reload();
									} else {
										if ( res.data.requires_pro ) {
											self.show_upgrade_modal();
										}
									}
								},
								error: function(){
									location.reload();
								}
						});
				},
				view_error_log_list: function(e){
					var target = $(e.currentTarget),
						data = target.data(),
						id = data.id,
						name = data.name,
						type = 'popup',
						Modal_Error = Hustle.get( 'Modal_Error' );

					// Get rid of old modal.
					if ( this.logShown ) {
						this.logShown.remove();
					}
					// Render modal.
					this.logShown = new Modal_Error({
						button: target,
						model: {
							name: name,
							id: id,
							type: type,
							total: data.total
						}
					});
				},
				select_all: function(e) {
					var $this = $(e.target);
					
					if ( $this.is(':checked') ) {
						this.$('.wph-module-checkbox').prop( 'checked', true );
					} else {
						this.$('.wph-module-checkbox').prop( 'checked', false );
					}
				},
				apply_bulk_action: function(e) {
					var $this = $(e.target),
						action = this.$('select#wpmudev-bulk-action').val(),
						nonce = this.$('select#wpmudev-bulk-action option[value="'+action+'"]').data('nonce'),
						ids = [];
						
					if ( action === 'delete' ) {
						this.$('.wph-module-checkbox:checked').each( function() {
							ids.push( $(this).data('id') );
						});
						
						if ( !_.isEmpty( ids ) ) {
							if ( this.delete_confirmation ) {
								this.delete_confirmation.opts.ids = JSON.stringify(ids);
								this.delete_confirmation.opts.multiple = 1;
								this.delete_confirmation.opts.nonce = nonce;
								this.delete_confirmation.$el.addClass('wpmudev-modal-active');
							}
						}
					}
				}
	});
});
