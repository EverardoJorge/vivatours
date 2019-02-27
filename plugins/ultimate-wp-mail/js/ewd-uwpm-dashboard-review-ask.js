jQuery(document).ready(function($) {
	jQuery('.ewd-uwpm-main-dashboard-review-ask').css('display', 'block');

	jQuery('.ewd-uwpm-main-dashboard-review-ask').on('click', function(event) {
		if (jQuery(event.srcElement).hasClass('notice-dismiss')) {
			var data = 'Ask_Review_Date=3&action=ewd_uwpm_hide_review_ask';
        	jQuery.post(ajaxurl, data, function() {});
        }
	});

	jQuery('.ewd-uwpm-review-ask-yes').on('click', function() {
		jQuery('.ewd-uwpm-review-ask-feedback-text').removeClass('uwpm-hidden');
		jQuery('.ewd-uwpm-review-ask-starting-text').addClass('uwpm-hidden');

		jQuery('.ewd-uwpm-review-ask-no-thanks').removeClass('uwpm-hidden');
		jQuery('.ewd-uwpm-review-ask-review').removeClass('uwpm-hidden');

		jQuery('.ewd-uwpm-review-ask-not-really').addClass('uwpm-hidden');
		jQuery('.ewd-uwpm-review-ask-yes').addClass('uwpm-hidden');

		var data = 'Ask_Review_Date=7&action=ewd_uwpm_hide_review_ask';
    	jQuery.post(ajaxurl, data, function() {});
	});

	jQuery('.ewd-uwpm-review-ask-not-really').on('click', function() {
		jQuery('.ewd-uwpm-review-ask-review-text').removeClass('uwpm-hidden');
		jQuery('.ewd-uwpm-review-ask-starting-text').addClass('uwpm-hidden');

		jQuery('.ewd-uwpm-review-ask-feedback-form').removeClass('uwpm-hidden');
		jQuery('.ewd-uwpm-review-ask-actions').addClass('uwpm-hidden');

		var data = 'Ask_Review_Date=1000&action=ewd_uwpm_hide_review_ask';
    	jQuery.post(ajaxurl, data, function() {});
	});

	jQuery('.ewd-uwpm-review-ask-no-thanks').on('click', function() {
		var data = 'Ask_Review_Date=1000&action=ewd_uwpm_hide_review_ask';
        jQuery.post(ajaxurl, data, function() {});

        jQuery('.ewd-uwpm-main-dashboard-review-ask').css('display', 'none');
	});

	jQuery('.ewd-uwpm-review-ask-review').on('click', function() {
		jQuery('.ewd-uwpm-review-ask-feedback-text').addClass('uwpm-hidden');
		jQuery('.ewd-uwpm-review-ask-thank-you-text').removeClass('uwpm-hidden');

		var data = 'Ask_Review_Date=1000&action=ewd_uwpm_hide_review_ask';
        jQuery.post(ajaxurl, data, function() {});
	});

	jQuery('.ewd-uwpm-review-ask-send-feedback').on('click', function() {
		var Feedback = jQuery('.ewd-uwpm-review-ask-feedback-explanation textarea').val();
		var EmailAddress = jQuery('.ewd-uwpm-review-ask-feedback-explanation input[name="feedback_email_address"]').val();
		var data = 'Feedback=' + Feedback + '&EmailAddress=' + EmailAddress + '&action=ewd_uwpm_send_feedback';
        jQuery.post(ajaxurl, data, function() {});

        var data = 'Ask_Review_Date=1000&action=ewd_uwpm_hide_review_ask';
        jQuery.post(ajaxurl, data, function() {});

        jQuery('.ewd-uwpm-review-ask-feedback-form').addClass('uwpm-hidden');
        jQuery('.ewd-uwpm-review-ask-review-text').addClass('uwpm-hidden');
        jQuery('.ewd-uwpm-review-ask-thank-you-text').removeClass('uwpm-hidden');
	});
});