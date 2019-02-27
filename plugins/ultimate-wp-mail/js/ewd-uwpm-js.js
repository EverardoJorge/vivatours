jQuery(function(){ //DOM Ready
	jQuery('.ewd-uwpm-topics-sign-up').on('click', function() {
		jQuery(this).attr('disabled', true);

		var Post_Categories = [];
		var UWPM_Categories = [];
		var WC_Categories = [];

		var Possible_Post_Categories = [];
		var Possible_UWPM_Categories = [];
		var Possible_WC_Categories = [];

		jQuery(this).parent().find('.ewd-uwpm-si-post-category').each(function(index, el) {
			if (jQuery(this).is(':checked')) {Post_Categories.push(jQuery(this).val());}
		});
		jQuery(this).parent().find('.ewd-uwpm-si-uwpm-category').each(function(index, el) {
			if (jQuery(this).is(':checked')) {UWPM_Categories.push(jQuery(this).val());}
		});
		jQuery(this).parent().find('.ewd-uwpm-si-wc-category').each(function(index, el) {
			if (jQuery(this).is(':checked')) {WC_Categories.push(jQuery(this).val());}
		});

		jQuery(this).parent().find('.ewd-uwpm-si-possible-post-category').each(function(index, el) {
			Possible_Post_Categories.push(jQuery(this).val());
		});
		jQuery(this).parent().find('.ewd-uwpm-si-possible-uwpm-category').each(function(index, el) {
			Possible_UWPM_Categories.push(jQuery(this).val());
		});
		jQuery(this).parent().find('.ewd-uwpm-si-possible-wc-category').each(function(index, el) {
			Possible_WC_Categories.push(jQuery(this).val());
		});

		var data = 'Post_Categories=' + Post_Categories.join() + '&UWPM_Categories=' + UWPM_Categories.join() + '&WC_Categories=' + WC_Categories.join() + '&Possible_Post_Categories=' + Possible_Post_Categories.join() + '&Possible_UWPM_Categories=' + Possible_UWPM_Categories.join() + '&Possible_WC_Categories=' + Possible_WC_Categories.join() + '&action=ewd_uwpm_interests_sign_up';
		jQuery.post(ewd_uwpm_data.ajaxurl, data, function(response) {
			console.log(response);

			jQuery('.ewd-uwpm-subscription-interests').append('<div class="ewd-uwpm-si-result">Interests have been saved!</div>');
			jQuery('.ewd-uwpm-topics-sign-up').attr('disabled', false);

			setTimeout(function() {
				jQuery('.ewd-uwpm-si-result').fadeOut('400', function() {
					jQuery(this).remove();
				});
			}, 3000);
		});
	});
});