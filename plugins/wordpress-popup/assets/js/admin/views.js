(function( $ ) {
	"use strict";

	var Module = window.Module || {};

	Module.Alert = Backbone.View.extend({
		template: Optin.template("optin-alert-modal"),
		events: {
			"click .inc-opt-alert-modal-close": "close",
			"click .inc-opt-alert-modal": "close",
			"click .inc-opt-alert-modal-close-btn": "close",
			"click .inc-opt-alert-modal-inner-container": "prevent_close"
		},
		initialize: function(options){
			this.options = options;
			return this.render();
		},
		render: function(){
			this.$el.html( this.template(_.extend({
				close_text: optin_vars.messages.ok
			}, this.options) ) );
			this.$el.appendTo("body");
		},
		close: function(e){
			this.$el.hide();
			this.remove();
		},
		prevent_close: function(e){
			e.preventDefault();
			e.stopPropagation();
		}
	});

	Module.Service_Modal = Backbone.View.extend({
		template: Optin.template("wpmudev-hustle-modal-add-new-service-tpl"),
		service_modal_target: '#wph-add-new-service-modal .wpmudev-box-modal',
		add_service_modal: $('#wph-add-new-service-modal'),
		initialize: function(options) {
			this.step      = 0;
			this.next_step = false;
			this.prev_step = false;

			this.options = options;
			this.view = options.view;
			return this.render();
		},
		render: function() {
			return this;
		},
		add_service: function($btn) {
			this.view.is_service_modal_updated = false;

			var data = _.extend(
					{
						is_new: true,
						service: 'mailchimp'
					}
				),
				$target_modal = $(this.service_modal_target),
				$this = $btn.closest('a'),
				nonce = $this.data('nonce');

			$target_modal.html('');
			$target_modal.append(this.template(data));
			this.show_modal(false);
		},
		edit_service: function($btn) {
			this.view.is_service_modal_updated = true;

			var $this = $btn.closest('a'),
				service = $this.data('id'),
				data = _.extend(
					{
						is_new: false,
						service: service
					}
				),
				$target_modal = $(this.service_modal_target);
			
			this.nonce = $this.data('nonce');
			$target_modal.html('');
			$target_modal.append(this.template(data));

			this.view.editing_service = service;
			// Get the provider's details.
			this.get_form_settings();
			this.show_modal(true);
		},
		on_provider_changed: function(e) {
			var $this = $(e.target),
				id = $this.val();

			if ( this.view.editing_service !== id ) {
				this.view.is_service_modal_updated = true;
			}

			// Remove previous provider's options from screen
			this.add_service_modal.find('#optin-provider-account-options').html('');
			
			// Remove previous provider's pre_saved values
			this.view.service_modal.pre_saved = {};

			this.view.editing_service = id;
			this.get_form_settings();
		},
		on_click_ajax: function(e) {
			e.preventDefault();

			var me = this.view,
				$this = $(e.target),
				$form = $this.closest("form"),
				$empty = this.add_service_modal.find( '.' + e.target.id + '-empty' ),
				$hide = this.add_service_modal.find( '.' + e.target.id + '-hide' ),
				$show = this.add_service_modal.find( '.' + e.target.id + '-show' ),
				placeholder = $(e.target).data( 'dom_wrapper' ),
				target_data = _.extend( {}, $this.data() ),
				data = _.reduce( $form.serializeArray(), function(obj, item){
					obj[ item['name'] ] = item['value'];
					return obj;
				}, {});
				data.module_id = this.view.module_id;
				data._ajax_nonce = $this.data('nonce');

			if ( 'undefined' !== target_data.nonce ) {
				delete target_data.nonce
			}
			this.view.is_service_modal_updated = true;

			$empty.empty();
			$hide.hide();
			$show.show();
			if ( typeof( placeholder ) !== 'undefined' ) {
				this.add_service_modal.find( placeholder ).html( $( "#wpoi-loading-indicator" ).html() );				
			}

			data = _.extend( {}, this.view.service_modal.pre_saved, target_data, data );

			$this.addClass('wpmudev-button-onload');

			this.custom_request(data);

		},
		on_change_ajax: function(e) {
			var $this = $(e.target),
				$form = $this.closest("form"),
				$empty = this.add_service_modal.find( '.' + e.target.id + '-empty' ),
				$hide = this.add_service_modal.find( '.' + e.target.id + '-hide' ),
				$show = this.add_service_modal.find( '.' + e.target.id + '-show' ),
				placeholder = $(e.target).data( 'dom_wrapper' ),
				target_data = _.extend( {}, $this.data() ),
				data = _.reduce( $form.serializeArray(), function(obj, item){
					obj[ item['name'] ] = item['value'];
					return obj;
				}, {});
				data._ajax_nonce = $this.data('nonce');

			// Loading indicator.
			if ( typeof( placeholder ) !== 'undefined' ) {
				var $placeholder = this.add_service_modal.find( placeholder );
				$placeholder.html( $( "#wpoi-loading-indicator" ).html() );

				if( ["-1", "0"].indexOf(e.target.value) !== -1 ){ // return if selection is not meaningful
					$placeholder.empty();
					return;
				}		
			}
			if ( 'undefined' !== target_data.select2 ) {
				delete target_data.select2
			}
			if ( 'undefined' !== target_data.nonce ) {
				delete target_data.nonce
			}

			_.extend( data, this.view.service_modal.pre_saved, target_data );

			$empty.empty();
			$hide.hide();
			$show.show();

			this.custom_request(data);

		},
		continue_next_step: function(e){
			e.preventDefault();
			
			Hustle.Events.trigger("optin.service.pre_save", this.view);
			
			var data = {},
				slug = this.view.editing_service;
				data.data = {};
				data.data = this.view.service_modal.pre_saved;
				data.data.is_step = true;
				data.data.step = this.get_step();
				data.data.current_step = this.step,
				data.data.slug = this.view.editing_service;
				data.data.module_id = this.view.module_id;
				data.data.module_type = this.view.module_type;
				data.action = this.action;
				data._ajax_nonce = this.nonce;
	
			this.request( data, true );
		},
		go_prev_step: function(e) {
			e.preventDefault();

			var data = {},
				slug = this.view.editing_service;
				data.data = {};
				data.data = this.view[slug].provider_args;
				data.data.is_step = true;
				data.data.step = this.get_prev_step();
				data.data.current_step = this.step;
				data.data.slug = this.view.editing_service;
				data.data.module_id = this.view.module_id;
				data.data.module_type = this.view.module_type;
				data.action = this.action;
				data._ajax_nonce = this.nonce;

			this.request( data, true );

		},
		get_form_settings: function() {
			var data = {};

			data.action = this.action = 'provider_form_settings';
			data.data = {};
			data.data = this.view[this.view.editing_service].provider_args;
			data.data.is_step = false;
			data.data.slug = this.view.editing_service;
			data.data.step = this.step = 0;
			data.data.current_step = this.step;
			data.data.module_id = this.view.module_id;
			data.data.module_type = this.view.module_type;
			data._ajax_nonce = this.nonce;
			
			this.request( data, false );
		},
		custom_request: function ( data ) {
			var self = this,
			 ajax = $.post({
				url: ajaxurl,
				type: 'post',
				data: data
			})
			.done( function ( result ) {
				if ( result ) {
					if ( result.success ) {
						if ( typeof( result.data.html ) !== 'undefined' && typeof( result.data.wrapper ) !== 'undefined' ) {
							// Render content
							self.render_custom_body( result.data.html, result.data.wrapper );								
						}
						
						if ( typeof( result.data.redirect_url ) !== 'undefined' ) {
							// Redirect to another url
							_.delay(function() {
								window.location = result.data.redirect_url;
							}, 300 );							
						}

						if( typeof( result.data.is_close ) !== 'undefined' && result.data.is_close  ) {
							// Handle close modal
							self.close_modal();
						}
					}
				}
				$('.wpmudev-button-onload').removeClass('wpmudev-button-onload');
			});
		},
		render_custom_body: function( html, wrapper ) {
			if ( wrapper ) {
				$( wrapper ).html( html );
			} 
			Hustle.Events.trigger("modules.view.rendered", this.view);
		},
		request: function ( data, is_step ) {		
			if( is_step ) {
				this.add_service_modal.find('#optin-provider-account-options').html('');
				this.add_service_modal.find('#wph-provider-account-details').html($( "#wpoi-loading-indicator" ).html());
				this.add_service_modal.find('.hustle-provider-next').addClass('wpmudev-button-onload');
				this.add_service_modal.find('.hustle-provider-prev').addClass('wpmudev-button-onload');
			}
			var self = this,
			 ajax = $.post({
				url: ajaxurl,
				type: 'post',
				data: data
			})
			.done(function (result) {
				if ( result ) {
					if ( result.success ) {
						// Render content
						self.render_body( result );
						
						// Render footer
						self.render_footer( result );

						if ( typeof( result.data.data.data_to_save ) !== 'undefined' && is_step ) {
							_.extend( self.view.service_modal.pre_saved, result.data.data.data_to_save );
							Hustle.Events.trigger("optin.service.prepare", self.view);
						}
						
						if ( ! is_step ) {
							self.display_saved_values();
						}

						// Update view
						self.update_view( result );

						// Handle close modal
						if( !_.isUndefined( result.data.data.is_close ) && result.data.data.is_close  ) {
							self.close_modal();
						}
					}
				}
				$('.wpmudev-button-onload').removeClass('wpmudev-button-onload');
			});
		},
		render_body: function( result ) {
			// Render the html of the step
			this.add_service_modal.find('#wph-provider-account-details').html( result.data.data.html );
			Hustle.Events.trigger("modules.view.rendered", this.view);
		},
		render_footer: function ( result ) {
			var self = this,
				buttons = result.data.data.buttons;

			// Clear footer from previous buttons
			this.add_service_modal.find('.wpmudev-box-footer').html('');

			// Append buttons
			_.each( buttons, function (button) {
				self.add_service_modal.find('.wpmudev-box-footer').append( button.markup );
			});
			Hustle.Events.trigger("modules.view.rendered", this.view);
		},
		display_saved_values: function(){
			// If creating a new module or editing a new service, hide saved values section and do not proceed
			var email_services = this.view.model.get('email_services'),
			provider = this.view.editing_service;
			if ( 
				typeof( email_services ) === 'undefined' || 
				_.isEmpty( email_services ) || 
				typeof( email_services[provider] ) === 'undefined' 
			) {
				this.add_service_modal.find('#optin-provider-saved-args').hide();
				return;
			}

			// Display the saved values
			var self = this,
				provider = this.view.editing_service,
				api_key = ( 'api_key' in email_services[provider] ) ? email_services[provider].api_key: '';
				
			if ( api_key !== '' ) {
				$('input[name="api_key"]').attr( 'value', api_key ) ;
			}
			$.each( email_services[provider], function( key, value ){
				var $value_placeholder = self.add_service_modal.find( '.current_' + key );
				if ( $value_placeholder.length > 0 ){
					var selected_value = '';
					if ( typeof( value ) === 'string' || typeof( value ) === 'number' ) {
						selected_value = value;	
					} else if ( typeof( value ) === 'array' ){
						selected_value = value.join(', ');
					} else {
						selected_value = $.map( email_services[provider], function(e){
							return e; 
						}).join(', ');
					}
					$value_placeholder.text( selected_value );
				}
			});
		},
		update_view: function( result ){
			// Update has next step
			if( !_.isUndefined( result.data.data.opt_in_provider_has_next_step ) ) {
				this.next_step = ( result.data.data.opt_in_provider_has_next_step === true );
			}
			
			// Update has prev step
			if( !_.isUndefined( result.data.data.opt_in_provider_has_prev_step ) ) {
				this.prev_step = ( result.data.data.opt_in_provider_has_prev_step === true );
			}
			
			// Update step
			if( !_.isUndefined( result.data.data.opt_in_provider_current_step ) ) {
				this.step = +result.data.data.opt_in_provider_current_step;
			}
		},
		get_step: function() {
			if( this.next_step ) {
				return this.step + 1;
			}

			return this.step;
		},
		get_prev_step: function() {
			if( this.prev_step ) {
				return this.step - 1;
			}

			return this.step;
		},
		clear_options: function(e) {
			e.preventDefault();
			var $this = $(e.target),
				name = $this.data('name');
			
			$('input[name="' + name + '"').prop('checked', false);
			
			
		},
		show_modal: function( is_edit ) {
			var $modal = $('#wph-add-new-service-modal'),
				$content = $modal.find('.wpmudev-box-modal'),
				view = this.view,
				me = this,
				services = this.view.model.get('email_services');

			this.add_service_modal.addClass('wpmudev-modal-active');
			$('body').addClass('wpmudev-modal-is_active');

			setTimeout(function(){
				$content.addClass('wpmudev-show');
				Hustle.Events.trigger("modules.view.rendered", view);
				$(document).off("click", ".hustle-provider-next", $.proxy( me.continue_next_step, me ) );
				$(document).on("click", ".hustle-provider-next", $.proxy( me.continue_next_step, me ) );
				$(document).off("click", ".hustle-provider-prev", $.proxy( me.go_prev_step, me ) );
				$(document).on("click", ".hustle-provider-prev", $.proxy( me.go_prev_step, me ) );
				$(document).off("click", ".hustle_provider_on_click_ajax", $.proxy( me.on_click_ajax, me ) );
				$(document).on("click", ".hustle_provider_on_click_ajax", $.proxy( me.on_click_ajax, me ) );
				$(document).off("change", ".hustle_provider_on_change_ajax", $.proxy( me.on_change_ajax, me ) );
				$(document).on("change", ".hustle_provider_on_change_ajax", $.proxy( me.on_change_ajax, me ) );
				$(document).off("click", ".clear_options", $.proxy( me.clear_options, me ) );
				$(document).on("click", ".clear_options", $.proxy( me.clear_options, me ) );
				$(document).off( 'change', 'select[name="optin_provider_name"]', $.proxy( me.on_provider_changed, me ) );
				$(document).on( 'change', 'select[name="optin_provider_name"]', $.proxy( me.on_provider_changed, me ) );
				Hustle.Events.off( 'optin.service.saved', $.proxy( me.save_email_service ) );
				Hustle.Events.on( 'optin.service.saved', $.proxy( me.save_email_service ) );

				// hide other service if editing
				me.hide_or_show_other_services(is_edit);
				// set selected list
				if ( is_edit && !_.isEmpty( services ) ) {
					Hustle.Events.trigger("optin.service.show.selected", view);
				} else {
					// Auto load first provider details.
					$content.find('select[name="optin_provider_name"]').trigger('change');
				}

			}, 100);
		},
		hide_or_show_other_services: function( is_edit ) {
			var $select = $('#wph-provider-select .wpmudev-select'),
				services = this.view.model.get('email_services');

			if ( _.isEmpty( services ) ) {
				services = {
					mailchimp: this.view.mailchimp.default_data
				};
			}

			if ( ! is_edit ) {
				var $siblings = $select.find('option');

				// only show services that are not yet added
				$siblings.each(function(){
					var rel = $(this).attr('value');
					if ( rel in services || rel === 'mailchimp' ) {
						$(this).remove();
					}
				});
			}

		},
		save_email_service: function(view) {
			var service = view.editing_service,
				args = view[service].provider_args,
				email_services = {};
				//email_services = view.model.get('email_services');

			// Only use one email service at a time.
			email_services[service] = args;

			// Multiple email services:
			//if ( _.isEmpty( email_services ) ) {
				//email_services = {};
				//email_services[service] = args;
			//} else {
				//email_services[service] = args;
			//}

			view.model.set( 'email_services', email_services );

			if ( '1' === args.enabled ) {
				view.model.set( 'active_email_service', service );
			} else {
				view.model.set( 'active_email_service', '' );
			}

			if( 
				!_.isUndefined( view.service_modal.pre_saved ) && 
				!_.isEmpty( view.service_modal.pre_saved ) && 
				(
					_.isUndefined( view.service_modal.next_step ) || 
					! view.service_modal.next_step 
				) 
			) {
				delete view.service_modal.pre_saved;
			}
			view.service_modal.append_added_service(service, args);
		},
		close_modal: function() {

			this.add_service_modal.find('.wpmudev-i_close').click();
		},
		append_added_service: function(service, provider_args) {
			var $last_email_provider = $('tr.wph-wizard-content-email-providers').last(),
				//$already_exists = $('table#wph-wizard-content-email-options a[data-id="'+ service +'"]'),
				$cloned = $last_email_provider.clone();

			$last_email_provider.html($cloned.html());
			var $updated_service = $('tr.wph-wizard-content-email-providers').last();
			/*if ( $already_exists.length ) {
				var $updated_service = $already_exists.closest('tr.wph-wizard-content-email-providers');
				$updated_service.html($cloned.html());
			} else {
				$cloned.insertAfter($last_email_provider);
				// Only allow one provider.
				$last_email_provider.remove();
				var $updated_service = $('tr.wph-wizard-content-email-providers').last();
			}*/

			$updated_service.addClass('updated-email-provider');
			$updated_service.siblings().removeClass('updated-email-provider');

			// Updating with updated contents

			var $updated_service = $('tr.updated-email-provider'),
				$checkbox = $updated_service.find('input.wph-email-service-toggle'),
				$label = $checkbox.siblings('label'),
				icon_template = Optin.template('wpmudev-'+ service +'-optin-provider-icon-svg'),
				$icon = $updated_service.find('.wph-email-providers-icon'),
				$name = $updated_service.find('a.wph-email-service-edit-link'),
				desc = ( 'desc' in provider_args )
					? provider_args.desc
					: '';

			$checkbox.attr( 'id', 'wph-popup-list_' + service );
			$checkbox.attr( 'data-attribute', service + '_service_provider' );
			// Disable or enable service.
			$checkbox.prop('checked', ( provider_args.enabled === 'true' || provider_args.enabled === '1' ) );
			$label.attr( 'for', 'wph-popup-list_' + service );
			$name.attr( 'data-id', service );

			$icon.html( icon_template() );

			if ( service in optin_vars.providers ) {
				$name.find('span.wpmudev-table_name').text( optin_vars.providers[service].title );
				$name.find('span.wpmudev-table_desc:first').text( desc );
			}

		}
	});

	Module.Form_Fields = Backbone.View.extend({
		edit_fields_modal : $('#wph-edit-form-modal'),
		field_list_template: Optin.template("wpmudev-hustle-modal-view-form-fields-tpl"),
		fields_template: Optin.template("wpmudev-hustle-modal-manage-form-fields-tpl"),
		new_fields_template: Optin.template("wpmudev-hustle-modal-add-form-fields-tpl"),
		fields_modal_target: '#wph-edit-form-modal .wpmudev-box-modal',
		initialize: function(options) {
			this.options = options;
			this.view = options.view;
			return this.render();
		},
		render: function() {
			return this;
		},
		manage_form: function() {
			var me = this,
				view = this.view,
				$target_modal = $(me.fields_modal_target),
				form_elements = this.view.model.get('form_elements');

			if ( typeof form_elements !== 'object' ) {
				form_elements = JSON.parse(form_elements);
			}

			$target_modal.html('');

			$target_modal.append( me.fields_template( _.extend( {
				form_fields: form_elements
			} ) ) );

			var $fields_container = $target_modal.find('form#wph-optin-form-fields-form .wpmudev-table-body');

			if ( $fields_container.length ) {
				_.each( form_elements, function( form_field, key ) {
					$fields_container.append( me.new_fields_template( _.extend({
						field: form_field,
						new_field: false
					}) ) );
				} );
			}

			var $content = me.edit_fields_modal.find('.wpmudev-box-modal'),
				$table = me.edit_fields_modal.find('.wpmudev-table-body'),
				$rows = me.edit_fields_modal.find('.wpmudev-table-body-row'),
				$new_button = me.edit_fields_modal.find('#wph-new-form-field'),
				$close = me.edit_fields_modal.find('.wpmudev-i_close'),
				$cancel = me.edit_fields_modal.find('#wph-cancel-edit-form'),
				$save_button = me.edit_fields_modal.find('#wph-save-edit-form'),
				$field_rows =  me.edit_fields_modal.find('.wph-field-row');


			me.edit_fields_modal.addClass('wpmudev-modal-active');
			$('body').addClass('wpmudev-modal-is_active');

			setTimeout(function(){
				$content.addClass('wpmudev-show');
				$new_button.on( 'click' , $.proxy( me.new_form_field, me ) );
				$save_button.on( 'click' , $.proxy( me.save_form_fields, me ) );
				$table.sortable();
				$table.disableSelection();
				$rows.each(function(){
					var $this = $(this),
						$plus = $this.find('.wpmudev-preview-item-manage'),
						$select = $this.find('select[name="type"]'),
						$delete = $this.find('.wpmudev-icon-delete');

					$plus.on('click', function(e){
						e.stopPropagation();
						$this.toggleClass('wpmudev-open');
					});
					$delete.on( 'click' , function(e){
						e.preventDefault();
						e.stopPropagation();
						me.delete_form_field($(this));
					});
					$select.on( 'change', function(e){
						if ( 'recaptcha' === $(this).val() ) {
							$this.find('.wpmudev-switch-labeled, .wpmudev-row:first, .wpmudev-row:eq(1) div:eq(1)').addClass('wpmudev-hidden');
							$this.find('.wpmudev-preview-item-label, .wpmudev-preview-item-name, .wpmudev-preview-item-placeholder').text('');
							$this.find('.wpmudev-preview-item-required').html('<span class="wpdui-fi wpdui-fi-check"></span>');
						} else {
							$this.find('.wpmudev-switch-labeled, .wpmudev-row:first, .wpmudev-row:eq(1) div:eq(1)').removeClass('wpmudev-hidden');
							$this.find('input[name="label"],input[name="name"],input[name="placeholder"],input[name="required"]').trigger('change');
						}
					});
				});
				$close.on('click', function(e){
					e.stopPropagation();
					$content.removeClass('wpmudev-show').addClass('wpmudev-hide');

					setTimeout(function(){
						me.edit_fields_modal.removeClass('wpmudev-modal-active');
						$('body').removeClass('wpmudev-modal-is_active');
						$content.removeClass('wpmudev-hide');
					}, 500);
				});

				$cancel.on('click', function(e){
					e.preventDefault();
					e.stopPropagation();
					$content.removeClass('wpmudev-show').addClass('wpmudev-hide');

					setTimeout(function(){
						me.edit_fields_modal.removeClass('wpmudev-modal-active');
						$('body').removeClass('wpmudev-modal-is_active');
						$content.removeClass('wpmudev-hide');
					}, 500);
				});
				$field_rows.each(function(){
					me.form_fields_header($(this));
				});
				Hustle.Events.trigger("modules.view.select.render", me);
				Hustle.Events.off( 'optin.service.saved', $.proxy( me.persist_form_fields, me ) );
				Hustle.Events.on( 'optin.service.saved', $.proxy( me.persist_form_fields, me ) );
			}, 100);
		},
		new_form_field : function(e){
			e.preventDefault();
			e.stopPropagation();
			var me = this,
				view = me.view,
				table = me.edit_fields_modal.find('.wpmudev-table-body'),
				new_button = me.edit_fields_modal.find('#wph-new-form-field'),
				save_local_list = this.view.model.get('save_local_list'),
				active_email_service = this.view.model.get('active_email_service');
			if( Module.Utils.service_supports_fields( save_local_list, active_email_service ) ){
				var rows = table.find('.wpmudev-table-body-row');
				rows.each(function(){
					$(this).removeClass('wpmudev-open');
				});
				table.prepend(me.new_fields_template( _.extend( {
					field: { delete: true },
					new_field: true
				} ) ));

				var $plus = table.find('.wpmudev-preview-item-manage:first'),
					$select = table.find('select[name="type"]:first'),
					$new = table.find('.wph-field-row:first'),
					$delete = table.find('.wpmudev-icon-delete');

				$plus.on('click', function(e){
					e.stopPropagation();
					$(this).closest('.wpmudev-table-body-row').toggleClass('wpmudev-open');
				});

				$delete.on( 'click' , function(e){
					e.preventDefault();
					e.stopPropagation();
					me.delete_form_field($(this));
				});

				$select.on( 'change', function(e){
					if ( 'recaptcha' === $(this).val() ) {
						$new.find('.wpmudev-switch-labeled, .wpmudev-row:first, .wpmudev-row:eq(1) div:eq(1)').addClass('wpmudev-hidden');
						$new.find('input[name="label"],input[name="name"]').val('recaptcha');
						$new.find('input[name="placeholder"]').val('');
						$new.find('.wpmudev-preview-item-required').html('<span class="wpdui-fi wpdui-fi-check"></span>');
					} else {
						$new.find('.wpmudev-switch-labeled, .wpmudev-row:first, .wpmudev-row:eq(1) div:eq(1)').removeClass('wpmudev-hidden');
					}
					$new.find('input[name="label"],input[name="name"],input[name="placeholder"],input[name="required"]').trigger('change');
				});

				me.form_fields_header(table.find('.wpmudev-table-body-row:first'));
				Hustle.Events.trigger("modules.view.select.render", me);
				me.update_model_fields(me);

			}else{
				new_button.html( optin_vars.messages.form_fields.errors.custom_field_not_supported );
			}
		},
		delete_form_field : function(elem){
			var $id = elem.data('id'),
				$parent_container = elem.closest('.wph-field-row.wpmudev-table-body-row');

			$parent_container.fadeOut( "fast", function() {
				$parent_container.remove();
			});
		},
		form_fields_header : function(elem){
			var $content = elem.find('.wpmudev-table-body-content input, .wpmudev-table-body-content select'),
				$header = elem.find('.wpmudev-table-body-preview'),
				$toprow = $header.closest('.wpmudev-table-body-row');
			$content.each(function(){
				if($(this).is(':checkbox')){
					$(this).on('change',function(e){
						if($(this).is(':checked')){
							$header.find('.wpmudev-preview-item-required').html('<span class="wpdui-fi wpdui-fi-check"></span>');
						}else{
							$header.find('.wpmudev-preview-item-required').html('');
						}
					});
				}else{
					$(this).on('change keyup keypress',function(e){
						var name = $(this).attr('name');
						$header.find('.wpmudev-preview-item-'+name).html($(this).val());
						//update the data-id
						if(name === 'name'){
							$toprow.attr('data-id',$(this).val());
						}
					});
				}
			});
		},
		save_form_fields : function(e){
			e.preventDefault();
			e.stopPropagation();
			var me = this,
				view = me.view;
			me.update_model_fields(me, function(data){
				var content = me.field_list_template( { form_fields: data } );
				$('.wph-form-element-list').empty();
				$('.wph-form-element-list').html(content);
				me.edit_fields_modal.find('.wpmudev-i_close').click();
			});

		},
		//update model
		update_model_fields : function(me, callback){
			var view = me.view,
				$row =  me.edit_fields_modal.find('.wph-field-row'),
				data = {},
				elements = {};
			$row.each(function(){
				var id = $(this).attr('data-id');
				var $content = $(this).find('.wpmudev-table-body-content input, .wpmudev-table-body-content select');

				elements[id] = {};
				$content.each(function(){
					var name = $(this).attr('name');
					var value = $(this).val();
					if(name === 'required'){
						if($(this).is(':checkbox')){
							value = $(this).is(':checked');
						} else{
							value = ( value === 'true' );
						}
					}
					if ( name === 'delete') {
						value = ( value === 'true' );
					}
					elements[id][name] = value;
				});
				data[id] = elements[id];

			});
			view.current_form_elements = data;
			view.model.set( 'form_elements', data, {silent:true} );

			if(typeof callback !== 'undefined' && typeof callback === 'function')
				callback(data);
		},
		persist_form_fields : function(view) {
			var me = this;
			//We use default elements incase none is selected
			if ( typeof view.current_form_elements === 'object' ) {
				if( Object.keys(view.current_form_elements).length <= 0 ){
					if( typeof wph_default_form_elements != 'undefined' ){
						view.current_form_elements = wph_default_form_elements;
					}
				}
				if( Object.keys(view.current_form_elements).length > 0 ){
					view.model.set( 'form_elements', view.current_form_elements );
				}
			}
			me.edit_fields_modal.find('.wpmudev-i_close').click();
		}
	});

	/**
	 * Key var to listen user changes before triggering
	 * navigate away message.
	 **/
	Module.hasChanges = false;
	Module.user_change = function() {
		Module.hasChanges = true;
	};

	window.onbeforeunload = function() {
		if ( Module.hasChanges ) {
			return optin_vars.messages.dont_navigate_away;
		}
	};

	$('.highlight_input_text').focus( function(){
		$(this).select();
	});
})( jQuery );
