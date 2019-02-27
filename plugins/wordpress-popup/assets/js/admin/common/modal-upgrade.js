Hustle.define("Upgrade_Modal", function($){
	"use strict";
	return Backbone.View.extend({
		el: "#wph-upgrade-modal",
		opts:{},
		events: {
			"click .wpmudev-i_close": "close"
		},
		initialize: function( options ){
			this.opts = _.extend({}, this.opts, options);
		},
		close: function(e){
			e.preventDefault();
			e.stopPropagation();
			this.$el.removeClass('wpmudev-modal-active');
		}
	});
});
