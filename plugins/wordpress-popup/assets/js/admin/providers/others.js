/**
 * Integration of none-too-complicated providers.
 **/

(function($){
	'use strict';

	var providers = Object.keys( optin_vars.providers );

	_.each( providers, function( provider ) {
		Optin.Mixins.add_services_mixin( provider, function( content_view ) {
			return new Optin.Provider({
				id: provider,
				supports_fields: (optin_vars.providers[provider].supports_fields === true),
				provider_args: { enabled: 0 },
				default_data: {
					enabled: false,
					api_key: '',
				},
				show_selected: function() {
					// if not the service being edited do not proceed
					if ( content_view.editing_service !== this.id ) {
						return;
					}
				},
				get_custom_args: function( exclude_some_fields ){
					var $form = $('#wph-add-new-service-modal').find('form'),
						args_raw = $form.serializeArray(),
						arg_list = {},
						$list_id = $form.find( '[name="list_id"]' );
					if( exclude_some_fields ) {
						// List of fields that are handled by Hustle by default, so no need to include them
						var excluded = [ '_wp_http_referer', '_wpnonce' ];
						$.each(args_raw, function(key, arg) {
							if( $.inArray( arg.name, excluded ) === -1 ) {
								if( !( arg_list.hasOwnProperty( arg.name ) ) ) {
									arg_list[ arg.name ] = arg.value;
								} else {
									if( typeof( arg_list[ arg.name ] ) === 'string' ) {
										arg_list[ arg.name ] = [ arg_list[ arg.name ], arg.value ];
									} else {
										arg_list[ arg.name ].push( arg.value );
									}
								}
							}
						});
					} else {
						$.each(args_raw, function(key, arg) {
							arg_list[ arg.name ] = arg.value;
						});
					}
					$form.find('input:checkbox').each(function(){
						if( !this.checked && !arg_list.hasOwnProperty(this.name) ){
							arg_list[this.name] = 0;
						}
					});
					if ( $list_id.length > 0 ) {
						arg_list['list_name'] = $list_id.children(':selected').text();
					}
					return arg_list;
				},
				pre_update_args: function(view) {
					
					// if not the service being edited do not proceed
					if ( view.editing_service !== this.id ) {
						return;
					}
					
					if ( typeof( view.service_modal.pre_saved ) === 'undefined' ) {
						view.service_modal.pre_saved = {};
					}
					
					// set base values not related with the settings form
					if ( ! view.service_modal.prev_step ) {
						var default_args = {
							enabled: view.model.get('email_services')[provider] ? view.model.get('email_services')[provider].enabled : false,
						};
						_.extend( view.service_modal.pre_saved, default_args );
					}
					
					_.extend( view.service_modal.pre_saved, this.get_custom_args( true ) );
				},
				update_args: function(view) {
					// if not the service being edited do not proceed
					if ( view.editing_service !== this.id ) {
						return;
					}
					
					_.extend( view[provider].provider_args, view.service_modal.pre_saved );

					var excluded_properties = [ 'step', 'is_step', 'current_step', 'slug', 'module_id', 'module_type' ];
					$.each( excluded_properties, function( i, val ) {
						if ( typeof( view[provider].provider_args[val] ) !== 'undefined' ){
							delete view[provider].provider_args[val];
						}
					});
					Hustle.Events.trigger("optin.service.saved", view);
				},
				init: function() {
					var me = this,
						view = content_view;

					// Service updated
					var service_updated = function(e) {
						content_view.is_service_modal_updated = true;
					};
					
					$(document).on('change', '#wph-optin-service-details-form input', service_updated );
					Hustle.Events.on( 'optin.service.pre_save', $.proxy( this.pre_update_args, this ) );
					Hustle.Events.on( 'optin.service.prepare', $.proxy( this.update_args, this ) );
					Hustle.Events.on( 'optin.service.show.selected', $.proxy( this.show_selected, this ) );
				}
			});
		});
	});
}(jQuery,document));