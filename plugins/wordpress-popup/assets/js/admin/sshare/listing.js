Hustle.define("SShare.Listing", function($, doc, win){
	"use strict";
	var Delete_Confirmation = Hustle.get("Delete_Confirmation"),
		Upgrade_Modal = Hustle.get("Upgrade_Modal");
	return Backbone.View.extend({
		el: "#wpmudev-hustle",
		events: {
			"click .wpmudev-row .wpmudev-box-head" : "toggle_module_accordion",
			"click #hustle-free-version-create": "show_upgrade_modal",
			"click .wpmudev-row .wpmudev-box-head .wpmudev-box-action" : "module_toggle_clicked",
			"click .social-sharing-edit": "edit",
			"click .hustle-delete-module": "delete_module",
			"change .social-sharing-toggle-activity": "toggle_module_activity",
			"change .social-sharing-toggle-tracking-activity": "toggle_tracking_activity",
			"change [name='wph-module-status']": "module_status_updated",
			"click .module-duplicate": "duplicate",
			'click .import-module-settings': 'import_settings',
			"change #wph-optin-service-import-form input[type=file]": "toggle_notice",
		},
		delete_confirmations: {},
		initialize: function(){
			this.delete_confirmation = new Delete_Confirmation({
				action: 'hustle_delete_module',
				onSuccess: function(res){
					if ( res.success ) {
						location.reload();
					}
				}
			});
			
			var self = this,
				$item = $('#wpmudev-hustle-content .wpmudev-row'),
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

			this.upgrade_modal = new Upgrade_Modal();
			if ( Module.Utils.get_url_param( 'requires_pro' ) ) {
				this.show_upgrade_modal();
			}
		},
		module_toggle_clicked: function(e) {
			e.stopPropagation();
			$(e.target).closest('.wpmudev-box-head').click();
		},
		toggle_module_accordion: function(e) {			
			if( _.indexOf( ['wpmudev-box-head', 'wpmudev-box-action', 'wpmudev-box-group', 'wpmudev-box-group--inner', 'wpmudev-group-title', 'wpmudev-helper'], e.target.className  ) === -1 ) return;
			
			var $this = $(e.target),
				$icon = $this.parents('.wpmudev-row').find(".wpmudev-box-action"),
				$body = $this.parents('.wpmudev-row').find(".wpmudev-box-body");
				
			$body.slideToggle( 'fast', function(){
				$icon.toggleClass("wpmudev-action-show");
				$body.toggleClass('wpmudev-hidden');
			} );
			

		},
		toggle_module_activity: function(e){
			e.stopPropagation();
			var $this = $(e.target),
				id = $this.data("id"),
				nonce = $this.data("nonce"),
				new_state = $this.is(":checked"),
				$row = $this.parents('.wpmudev-row')
			;

			$this.prop("disabled", true);

			if ( new_state ) {
				// Show settings.
				$row.find(".wpmudev-box-body .wpmudev-box-disabled")
					.removeClass("wpmudev-box-disabled")
					.addClass("wpmudev-box-enabled")
				;
				// Enable inputs
				$row.find("input").prop("disabled", false);
			} else {
				// Hide settings.
				$row.find(".wpmudev-box-body .wpmudev-box-enabled")
					.removeClass("wpmudev-box-enabled")
					.addClass("wpmudev-box-disabled")
				;
				// Disable inputs
				$row.find("input").prop("disabled", true);
			}

			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					action: "hustle_sshare_module_toggle_state",
					id: id,
					_ajax_nonce: nonce
				},
				complete: function(){
					$this.prop("disabled", false);
				},
				success: function( res ){
					if( !res.success )
						$this.attr("checked", !new_state);
				},
				error: function(){
					$this.attr("checked", !new_state);
				}
			});
		},
		toggle_tracking_activity: function(e){
			e.stopPropagation();
			var $this = $(e.target),
				id = $this.data("id"),
				nonce = $this.data("nonce"),
				type = $this.data("type"),
				new_state = $this.is(":checked");

			$this.attr("disabled", true);

			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					action: "hustle_sshare_toggle_tracking_activity",
					id: id,
					type: type,
					_ajax_nonce: nonce
				},
				complete: function(){
					$this.attr("disabled", false);
				},
				success: function( res ){
					if( !res.success )
						$this.attr("checked", !new_state);
				},
				error: function(res){
					if( !res.success )
						$this.attr("checked", !new_state);
				}
			});
		},
		toggle_type_activity: function(e){
			e.stopPropagation();
			var $this = $(e.target),
				id = $this.data("id"),
				nonce = $this.data("nonce"),
				type = $this.data("type"),
				new_state = $this.is(":checked");

			$this.attr("disabled", true);

			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					action: "hustle_social_sharing_toggle_type_activity",
					id: id,
					type: type,
					_ajax_nonce: nonce
				},
				complete: function(){
					$this.attr("disabled", false);
				},
				success: function( res ){
					if( !res.success )
						$this.attr("checked", !new_state);
				},
				error: function(res){
					if( !res.success )
						$this.attr("checked", !new_state);
				}
			});
		},
		edit: function(e){
			e.stopPropagation();
		},
		delete_module: function(e) {
			var $this = $(e.target).closest('a.hustle-delete-module'),
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
				data.action = "hustle_sshare_toggle_test_activity";
			} else {
				data.action = "hustle_sshare_module_toggle_type_state";
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

		/**
		 * Duplicate Social share
		 *
		 * @since 3.0.5
		 */
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
					action: "hustle_social_share_duplicate",
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
		}

	});

});
