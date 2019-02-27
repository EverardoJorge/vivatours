(function( $ ) {
	"use strict";

	Optin = Optin || {};

	Optin.Hustle_Embeddeds = {
		render_hustle_module_embeds: function(use_compat) {
			var me = this;
			$('.hustle_module_after_content_wrap, .hustle_module_widget_wrap, .hustle_module_shortcode_wrap').each(function () {
				var $this = $(this),
					id = $this.data('id'),
					type = $this.data('type'),
					unique_id = $this.data('unique_id'),
					//is_admin = hustle_vars.is_admin === '1';
					is_admin = inc_opt.is_admin === '1';

				if( !id ) return;

				var module = _.find(Modules, function ( mod, key ) {
					return id === parseInt( mod[ 'module_id' ] );
				});

				if (!module) return;

				if ( module.test_types !== null ) {
					// if not admin and test mode enabled
					if ( typeof module.test_types !== 'undefined'
							&& typeof module.test_types[type] !== 'undefined'
							&& ( module.test_types[type] || module.test_types[type] === 'true' )
							&& !is_admin ) {
						return;

					} else if ( typeof module.test_types !== 'undefined'
							&& typeof module.test_types[type] !== 'undefined'
							&& ( module.test_types[type] || module.test_types[type] === 'true' )
							&& is_admin ) {
						// bypass the enabled settings
						module.settings[ type + '_enabled' ] = 'true';
					}
				}


				if ( !_.isTrue( module.settings[ type + '_enabled' ] ) ) return;

				//check if user is already subscribed
				var display = true;
				var cookie_key = Optin.EMBEDDED_COOKIE_PREFIX + id + '_success';

				if ( 'no_show_on_post' === module.content.after_subscription ) {
					if ( parseInt( inc_opt.page_id, 10 ) > 0 ) {
						display = !_.isTrue( Optin.cookie.get( cookie_key + '_' + inc_opt.page_id ) );
					} else {
						display = true;
					}
				} else if ( 'no_show_all' === module.content.after_subscription ) {
					display = !_.isTrue( Optin.cookie.get( cookie_key ) );
				}
				if ( ! display ) return;

				// sanitize cta_url
				if ( module.content.cta_url ) {
					if (!/^(f|ht)tps?:\/\//i.test(module.content.cta_url)) {
						module.content.cta_url = "http://" + module.content.cta_url;
					}
				}
				if ( typeof unique_id === 'undefined' )
					unique_id = '';

				module.unique_id = unique_id;

				var template = ( parseInt(module.content.use_email_collection, 10) )
					? Optin.template("wpmudev-hustle-modal-with-optin-tpl")
					: Optin.template("wpmudev-hustle-modal-without-optin-tpl");

				$this.html( template(module) );


				me.maybeRenderRecaptcha( module, $this );

				// supply with provider args
				if ( typeof module.content.args !== 'undefined' && module.content.args !== null && typeof module.content.active_email_service !== 'undefined' ) {
					var provider_template = Optin.template( 'optin-'+ module.content.active_email_service + '-' + module.module_id + '-args-tpl' ),
						provider_content = provider_template( module.content.args),
						$target_provider_container = $('.module_id_' + module.module_id + ' .hustle-modal-provider-args-container');

					if ( $target_provider_container.length ) {
						$target_provider_container.html(provider_content);
					}
				}

				module.type = type;
				me.on_animation_in(module, $this);

				// bypass type from (widget,shortcode) into embedded for cookie purposes
				module.type = 'embedded';
				// added display type for log view cookie purposes
				module.display_type = type;
				// trigger the log view
				$(document).trigger( 'hustle:module:displayed', module );

				// Log cta conversion
				$this.find('a.hustle-modal-cta').on( 'click', function(){
					if ( typeof Optin.Module_log_cta_conversion != 'undefined' ) {
						var log_cta_conversion = new Optin.Module_log_cta_conversion();
						log_cta_conversion.set( 'type', type );
						log_cta_conversion.set( 'module_type', 'embedded' );
						log_cta_conversion.set( 'module_id', id );
						log_cta_conversion.save();
					}
				} );

				// Hide close button.
				//$this.find('.hustle-modal-close').remove();
			});

		},

		maybeRenderRecaptcha: function( module, $this ) {

			if ( 'false' === module.content.use_email_collection || '0' === module.content.use_email_collection ) {
				return;
			}

			/**
			 * reCAPTCHA
			 *
			 * @since 3.0.7
			 */
			if (
				'undefined' !== typeof inc_opt.recaptcha
				&& 'undefined' !== typeof inc_opt.recaptcha.enabled
				&& 'undefined' !== typeof inc_opt.recaptcha.sitekey
				&& '1' === inc_opt.recaptcha.enabled
				&& 'undefined' !== typeof module.content.form_elements.recaptcha
			) {
				var id = 'hustle-modal-recaptcha' + module.module_id + module.unique_id;
				$this.find('.hustle-modal-recaptcha').attr('id', id);

				$this.find('.hustle-modal-body button').attr('disabled', true );

				grecaptcha.ready( function() {
					var recaptcha_id = grecaptcha.render( id, {
						'sitekey' : inc_opt.recaptcha.sitekey,
						'callback': function() {
							$this.find('.hustle-modal-body button').removeAttr('disabled' );
						}
					});

					$this.find('.hustle-modal-recaptcha').attr('recaptcha-id', recaptcha_id);

					Optin.apply_custom_size( module, $this );
				});
			}

		},

		on_animation_in: function( module, $this ) {
			var me = this,
				$modal = $this.find('.hustle-modal'),
				animation_in = module.settings.animation_in;

			if ( $modal.hasClass('hustle-animated') ) {
				setTimeout( function() {
					$modal.addClass('hustle-animate-' + animation_in );
					Optin.apply_custom_size(module, $this);
				}, 100);
			} else {
				// Apply custom size regardless of no animation.
				Optin.apply_custom_size(module, $this);
			}
		},
	};

	// added delay to wait for markups to finish
	_.delay( function(){
		Optin.Hustle_Embeddeds.render_hustle_module_embeds(false);
	}, 500 );

	Hustle.Events.on("upfront:editor:widget:render", function(widget) {
		Optin.Hustle_Embeddeds.render_hustle_module_embeds(true);
	});
	Hustle.Events.on("upfront:editor:shortcode:render", function(shortcode) {
		Optin.Hustle_Embeddeds.render_hustle_module_embeds(true);
	});

}(jQuery));
