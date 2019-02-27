(function( $, doc, win ) {
	"use strict";
	if( inc_opt.is_upfront ) return;

	Optin = window.Optin || {};

	Optin.PopUp = Optin.Module.extend({
		className: 'wph-modal',
		type: 'popup',

		render: function(args){
			this.$el.addClass( 'hui-module-type--popup' );
			Optin.Module.prototype.render.apply( this, args );
			if ( 'function' === typeof Optin.render_hustle_sshare_module_embeds ) {
				Optin.render_hustle_sshare_module_embeds( false );
			}

		}
	});
}(jQuery, document, window));
