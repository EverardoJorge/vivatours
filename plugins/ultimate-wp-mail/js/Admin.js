jQuery(document).ready(function() {
	jQuery('.ewd-uwpm-element-type-select').on('change', function() {
		var type = jQuery(this).val();

		jQuery('.ewd-uwpm-elements-container').addClass('ewd-uwpm-hidden');
		jQuery('.ewd-uwpm-elements-container[data-type="' + type + '"]').removeClass('ewd-uwpm-hidden');
	});

	jQuery('.ewd-uwpm-column-type').on('click', function() {
		jQuery('.ewd-uwpm-email-templates').addClass('ewd-uwpm-hidden');

		var type = jQuery(this).data('type');
		var display_HTML = EWD_UWPM_Get_Display_HTML(type);
		var current_display = jQuery('#ewd-uwpm-visual-builder-area').html();

		jQuery('#ewd-uwpm-visual-builder-area').html(current_display + display_HTML);

		var save_HTML = jQuery('#ewd-uwpm-visual-builder-area').html();
		jQuery('#ewd-uwpm-email-input textarea').val(save_HTML);

		EWD_UWPM_Section_Editor_Click_Handlers();
		EWD_UWPM_Enable_Sortable();
		EWD_UWPM_Enable_Delete_Section();
	});

	jQuery('.ewd-uwpm-full-screen').on('click', function() {
		jQuery('#form-meta div.inside').addClass('ewd-uwpm-full-screen-container');
		jQuery(this).addClass('ewd-uwpm-hidden');
		jQuery('.ewd-uwpm-exit-full-screen').removeClass('ewd-uwpm-hidden');
		jQuery('body').addClass('ewd-uwpm-full-screen-body-overflow');
	});

	jQuery('.ewd-uwpm-exit-full-screen').on('click', function() {
		jQuery('#form-meta div.inside').removeClass('ewd-uwpm-full-screen-container');
		jQuery(this).addClass('ewd-uwpm-hidden');
		jQuery('.ewd-uwpm-full-screen').removeClass('ewd-uwpm-hidden');
		jQuery('body').removeClass('ewd-uwpm-full-screen-body-overflow');
	});

	jQuery('#ewd-uwpm-section-editor-save-button').on('click', function() {
		EWD_UWPM_Save_Section_Editor();

		jQuery('#ewd-uwpm-section-editor').addClass('ewd-uwpm-hidden').removeClass('ewd-uwpm-split-screen');
		jQuery('#ewd-uwpm-email-styling-options').removeClass('ewd-uwpm-hidden');
		jQuery('#ewd-uwpm-visual-builder-area').removeClass('ewd-uwpm-split-screen');

		var save_HTML = jQuery('#ewd-uwpm-visual-builder-area').html();
		jQuery('#ewd-uwpm-email-input textarea').val(save_HTML);
	});

	jQuery('#ewd-uwpm-plain-text-toggle').on('click', function() {
		jQuery('#ewd-uwpm-plain-text-version').toggleClass('ewd-uwpm-hidden');
	});

//	jQuery('.ewd-uwpm-selectable-element').on('click', function() {
//		var type = jQuery(this).data('type');
//		var name = jQuery(this).data('name');
//		var label = jQuery(this).html();
//
//		var display_HTML = EWD_UWPM_Get_Display_HTML(type, name, label);
//
//		var current_display = jQuery('#ewd-uwpm-visual-builder-area').html();
//
//		var new_display = current_display + display_HTML;
//
//		jQuery('#ewd-uwpm-visual-builder-area').html(new_display);
//	});

	jQuery('#ewd-uwpm-visual-builder-area').sortable({
		items: '.ewd-uwpm-section-container',
		handle: '.ewd-uwpm-section-handle',
		cursor: 'move',
		axis: 'y'
	});

	jQuery('#post-preview').off('click');
	jQuery('#post-preview').on('click', function(event) {
		event.preventDefault();

		jQuery('#ewd-uwpm-ajax-email-preview-body').html('Loading email...');

		jQuery('#ewd-uwpm-ajax-email-preview').removeClass('ewd-uwpm-hidden');
		jQuery('#ewd-uwpm-email-preview-overlay').removeClass('ewd-uwpm-hidden');

		var Email_Content = jQuery('#ewd-uwpm-visual-builder-area').html();
		var Email_ID = jQuery('#ewd-uwpm-email-id').val();

		var data = 'Email_Content=' + encodeURIComponent(Email_Content) + '&Email_ID=' + Email_ID + '&action=ewd_uwpm_ajax_preview_email';
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);

			jQuery('#ewd-uwpm-ajax-email-preview-body').html(response);
		});
	});

	jQuery('#ewd-uwpm-ajax-email-preview-exit, #ewd-uwpm-email-preview-overlay').on('click', function() {
		jQuery('#ewd-uwpm-ajax-email-preview').addClass('ewd-uwpm-hidden');
		jQuery('#ewd-uwpm-email-preview-overlay').addClass('ewd-uwpm-hidden');
	});

	jQuery('#ewd-uwpm-send-test-button').on('click', function(event) {
		event.preventDefault();
		jQuery('#ewd-uwpm-send-test-email').removeClass('ewd-uwpm-hidden');
		jQuery('#ewd-uwpm-send-test-email-overlay').removeClass('ewd-uwpm-hidden');
	});

	jQuery('#ewd-uwpm-send-test-email-close, #ewd-uwpm-send-test-email-overlay').on('click', function() {
		jQuery('#ewd-uwpm-send-test-email').addClass('ewd-uwpm-hidden');
		jQuery('#ewd-uwpm-send-test-email-overlay').addClass('ewd-uwpm-hidden');
	});

	jQuery('#ewd-uwpm-send-test').on('click', function(event) {
		event.preventDefault();
		jQuery('#ewd-uwpm-send-reponse-message').html('Sending email...').removeClass('ewd-uwpm-hidden').css('display', 'block');

		var Email_Address = jQuery('input[name="EWD_UWPM_Test_Email_Address"]').val();
		var Email_Title = jQuery('#title').val();
		var Email_Content = jQuery('#ewd-uwpm-visual-builder-area').html();
		var Email_ID = jQuery('#ewd-uwpm-email-id').val();

		var data = 'Email_Address=' + Email_Address + '&Email_Title=' + Email_Title + '&Email_Content=' + encodeURIComponent(Email_Content) + '&Email_ID=' + Email_ID + '&action=ewd_uwpm_send_test_email';
		jQuery.post(ajaxurl, data, function(response) {
			console.log(response);

			jQuery('#ewd-uwpm-send-reponse-message').html(response);

			setTimeout(function() {
				jQuery('#ewd-uwpm-send-reponse-message').fadeOut(400).addClass('ewd-uwpm-hidden');
			}, 4000);

			jQuery('#ewd-uwpm-send-test-email-overlay').addClass('ewd-uwpm-hidden');
			jQuery('#ewd-uwpm-send-test-email').addClass('ewd-uwpm-hidden');
		});
	});

	jQuery('#ewd-uwpm-email-all').on('click', function(event) {
		event.preventDefault();
		jQuery('#ewd-uwpm-send-reponse-message').html('Sending email...').removeClass('ewd-uwpm-hidden').css('display', 'block');

		var Email_ID = jQuery('#ewd-uwpm-email-id').val();
		var Email_Title = jQuery('#title').val();
		var Email_Content = jQuery('#ewd-uwpm-visual-builder-area').html();

		if (jQuery('.ewd-uwpm-delay-send-toggle').val() == 'Now') {var Send_Time = 'Now';}
		else {var Send_Time = jQuery('#ewd-uwpm-send-datetime').val();}

		var data = 'Email_ID=' + Email_ID + '&Email_Title=' + Email_Title + '&Email_Content=' + encodeURIComponent(Email_Content) + '&Send_Time=' + Send_Time + '&action=ewd_uwpm_email_all_users';
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#ewd-uwpm-send-reponse-message').html(response);

			setTimeout(function() {
				jQuery('#ewd-uwpm-send-reponse-message').fadeOut(400).addClass('ewd-uwpm-hidden');
			}, 4000);
		});
	});

	jQuery('#ewd-uwpm-email-specific-user').on('click', function(event) {
		event.preventDefault();
		jQuery('#ewd-uwpm-send-reponse-message').html('Sending email...').removeClass('ewd-uwpm-hidden').css('display', 'block');

		var Email_ID = jQuery('#ewd-uwpm-email-id').val();
		var Email_Title = jQuery('#title').val();
		var Email_Content = jQuery('#ewd-uwpm-visual-builder-area').html();
		var User_ID = jQuery('#ewd-uwpm-email-user-select').val();

		if (jQuery('.ewd-uwpm-delay-send-toggle').val() == 'Now') {var Send_Time = 'Now';}
		else {var Send_Time = jQuery('#ewd-uwpm-send-datetime').val();}

		var data = 'Email_ID=' + Email_ID + '&Email_Title=' + Email_Title + '&Email_Content=' + encodeURIComponent(Email_Content) + '&User_ID=' + User_ID + '&Send_Time=' + Send_Time + '&action=ewd_uwpm_email_specific_user';
		jQuery.post(ajaxurl, data, function(response) {console.log(response);
			jQuery('#ewd-uwpm-send-reponse-message').html(response);

			setTimeout(function() {
				jQuery('#ewd-uwpm-send-reponse-message').fadeOut(400).addClass('ewd-uwpm-hidden');
			}, 4000);
		});
	});

	jQuery('#ewd-uwpm-email-user-list').on('click', function(event) {
		event.preventDefault();

		if (jQuery('#ewd-uwpm-email-list-select').val() == -1) {
			jQuery('.ewd-uwpm-auto-list-overlay').removeClass('ewd-uwpm-hidden');
			jQuery('.ewd-uwpm-auto-list-options').removeClass('ewd-uwpm-hidden');

			return;
		}

		jQuery('#ewd-uwpm-send-reponse-message').html('Sending email...').removeClass('ewd-uwpm-hidden').css('display', 'block');

		var Email_ID = jQuery('#ewd-uwpm-email-id').val();
		var Email_Title = jQuery('#title').val();
		var Email_Content = jQuery('#ewd-uwpm-visual-builder-area').html();
		var List_ID = jQuery('#ewd-uwpm-email-list-select').val();

		if (jQuery('.ewd-uwpm-delay-send-toggle').val() == 'Now') {var Send_Time = 'Now';}
		else {var Send_Time = jQuery('#ewd-uwpm-send-datetime').val();}

		var Post_Categories = [];
		var UWPM_Categories = [];
		var WC_Categories = [];

		jQuery('.ewd-uwpm-al-post-category').each(function(index, el) {
			if (jQuery(this).is(':checked')) {Post_Categories.push(jQuery(this).val());}
		});
		jQuery('.ewd-uwpm-al-uwpm-category').each(function(index, el) {
			if (jQuery(this).is(':checked')) {UWPM_Categories.push(jQuery(this).val());}
		});
		jQuery('.ewd-uwpm-al-wc-category').each(function(index, el) {
			if (jQuery(this).is(':checked')) {WC_Categories.push(jQuery(this).val());}
		});

		var Previous_Purchasers = jQuery('.ewd-uwpm-al-wc-previous-purchasers').is(':checked');
		var Product_Purchasers = jQuery('.ewd-uwpm-al-wc-previous-products').is(':checked');
		var Previous_WC_Products = jQuery('.ewd-uwpm-al-wc-products').val();
		var Category_Purchasers = jQuery('.ewd-uwpm-al-wc-previous-categories').is(':checked');
		var Previous_WC_Categories = jQuery('.ewd-uwpm-al-wc-categories').val();

		if (!jQuery.isArray(Previous_WC_Products)) {Previous_WC_Products = [];}
		if (!jQuery.isArray(Previous_WC_Categories)) {Previous_WC_Categories = [];}

		jQuery('.ewd-uwpm-al-post-category').attr('checked', false);
		jQuery('.ewd-uwpm-al-uwpm-category').attr('checked', false);
		jQuery('.ewd-uwpm-al-wc-category').attr('checked', false);
		jQuery('.ewd-uwpm-al-wc-previous-purchasers').attr('checked', false);
		jQuery('.ewd-uwpm-al-wc-previous-products').attr('checked', false);
		jQuery('.ewd-uwpm-al-wc-previous-categories').attr('checked', false);

		var data = 'Email_ID=' + Email_ID + '&Email_Title=' + Email_Title + '&Email_Content=' + encodeURIComponent(Email_Content) + '&List_ID=' + List_ID + '&Send_Time=' + Send_Time + '&Post_Categories=' + Post_Categories.join() + '&UWPM_Categories=' + UWPM_Categories.join() + '&WC_Categories=' + WC_Categories.join() + '&Previous_Purchasers=' + Previous_Purchasers + '&Product_Purchasers=' + Product_Purchasers + '&Previous_WC_Products=' + Previous_WC_Products.join() + '&Category_Purchasers=' + Category_Purchasers + '&Previous_WC_Categories=' + Previous_WC_Categories.join() + '&action=ewd_uwpm_email_user_list';
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#ewd-uwpm-send-reponse-message').html(response);

			setTimeout(function() {
				jQuery('#ewd-uwpm-send-reponse-message').fadeOut(400).addClass('ewd-uwpm-hidden');
			}, 3000);
		});

		jQuery('#ewd-uwpm-email-list-select option[value=-2]').remove();
	}); 

	jQuery('.ewd-uwpm-submit-al').on('click', function(event) {
		event.preventDefault();

		jQuery('.ewd-uwpm-auto-list-overlay').addClass('ewd-uwpm-hidden');
		jQuery('.ewd-uwpm-auto-list-options').addClass('ewd-uwpm-hidden');

		jQuery('#ewd-uwpm-email-list-select').append('<option value="-2">AL Option</option>');
		jQuery('#ewd-uwpm-email-list-select').val(-2);
		jQuery('#ewd-uwpm-email-user-list').trigger('click');
	});

	jQuery('.ewd-uwpm-auto-list-overlay').on('click', function() {
		jQuery('.ewd-uwpm-auto-list-overlay').addClass('ewd-uwpm-hidden');
		jQuery('.ewd-uwpm-auto-list-options').addClass('ewd-uwpm-hidden');
	});

	jQuery('.ewd-uwpm-al-interests').on('click', function() {
		jQuery('.ewd-uwpm-al-interests').addClass('ewd-uwpm-auto-list-tab-active');
		jQuery('.ewd-uwpm-al-wc').removeClass('ewd-uwpm-auto-list-tab-active');
		jQuery('.ewd-uwpm-al-interest-groups').removeClass('ewd-uwpm-hidden');
		jQuery('.ewd-uwpm-al-woocommerce-lists').addClass('ewd-uwpm-hidden');
	});

	jQuery('.ewd-uwpm-al-wc').on('click', function() {
		jQuery('.ewd-uwpm-al-interests').removeClass('ewd-uwpm-auto-list-tab-active');
		jQuery('.ewd-uwpm-al-wc').addClass('ewd-uwpm-auto-list-tab-active');
		jQuery('.ewd-uwpm-al-interest-groups').addClass('ewd-uwpm-hidden');
		jQuery('.ewd-uwpm-al-woocommerce-lists').removeClass('ewd-uwpm-hidden');
	}); 

	jQuery('.ewd-uwpm-delay-send-toggle').on('change', function() {
		if (jQuery('.ewd-uwpm-delay-send-toggle').val() == 'Now') {jQuery('#ewd-uwpm-send-datetime').addClass('uwpm-hidden');}
		else {jQuery('#ewd-uwpm-send-datetime').removeClass('uwpm-hidden');}
	});

	jQuery('#ewd-uwpm-send-reponse-overlay').on('click', function() {
		jQuery(this).addClass('ewd-uwpm-hidden');
		jQuery('#ewd-uwpm-send-reponse-message').addClass('ewd-uwpm-hidden');
	}); 

	jQuery('.ewd-uwpm-toggle-wc-advanced-events').on('click', function() {
		jQuery(this).removeClass('ewd-uwpm-fake-link');
		jQuery('.ewd-uwpm-wc-advanced-event-table').removeClass('ewd-uwpm-hidden');
	});

	jQuery('.ewd-uwpm-add-advanced-send-on').on('click', function() {
		var Counter = jQuery(this).data('nextrow');
		var Max_ID = jQuery(this).data('maxid');

		jQuery(this).data('nextrow', Counter + 1);
		jQuery(this).data('maxid', Max_ID + 1);

		EWD_UWPM_Add_Advanced_Send_On(Counter, Max_ID);
		EWD_UWPM_Enable_Delete_Send_Ons();
		EWD_UWPM_Enable_Disable_Send_On_Fields();
	});

	EWD_UWPM_Section_Editor_Click_Handlers();
	EWD_UWPM_Enable_Sortable();
	EWD_UWPM_List_Handler();
	EWD_UWPM_Form_Templates();
	EWD_UWPM_Enable_Delete_Section();
	EWD_UWPM_Enable_Delete_Send_Ons();
	EWD_UWPM_Enable_Disable_Send_On_Fields();
});

function EWD_UWPM_Get_Display_HTML(type, section_count, section_one_content, section_two_content, section_three_content, section_four_content) {
	if (typeof section_count === 'undefined' || section_count === null) {section_count = jQuery('#ewd-uwpm-template-information').data('sectioncount');}

	if (typeof section_one_content === 'undefined' || section_one_content === null) {section_one_content = '';}
	if (typeof section_two_content === 'undefined' || section_two_content === null) {section_two_content = '';}
	if (typeof section_three_content === 'undefined' || section_three_content === null) {section_three_content = '';}
	if (typeof section_four_content === 'undefined' || section_four_content === null) {section_four_content = '';}

	if (type == 1) {
		var display_HTML = '<div class="ewd-uwpm-section-container">';
		display_HTML += '<div class="ewd-uwpm-section-handle dashicons dashicons-leftright"></div>';
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-1" data-sectioncount="' + section_count + '">' + section_one_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div class="ewd-uwpm-delete dashicons dashicons-no" data-section="' + section_count + '"></div>';
		display_HTML += '<div class="ewd-uwpm-clear"></div>';
		display_HTML += '</div>';
	}
	else if (type == 2) {
		var display_HTML = '<div class="ewd-uwpm-section-container">';
		display_HTML += '<div class="ewd-uwpm-section-handle dashicons dashicons-leftright"></div>';
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-2" data-sectioncount="' + section_count + '">' + section_one_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-2" data-sectioncount="' + section_count + '">' + section_two_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div class="ewd-uwpm-delete dashicons dashicons-no" data-section="' + section_count + '"></div>';
		display_HTML += '<div class="ewd-uwpm-clear"></div>';
		display_HTML += '</div>';
	}
	else if (type == 3) {
		var display_HTML = '<div class="ewd-uwpm-section-container">';
		display_HTML += '<div class="ewd-uwpm-section-handle dashicons dashicons-leftright"></div>';
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-3" data-sectioncount="' + section_count + '">' + section_one_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-3" data-sectioncount="' + section_count + '">' + section_two_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-3" data-sectioncount="' + section_count + '">' + section_three_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div class="ewd-uwpm-delete dashicons dashicons-no" data-section="' + section_count + '"></div>';
		display_HTML += '<div class="ewd-uwpm-clear"></div>';
		display_HTML += '</div>';
	}
	else if (type == 4) {
		var display_HTML = '<div class="ewd-uwpm-section-container">';
		display_HTML += '<div class="ewd-uwpm-section-handle dashicons dashicons-leftright"></div>';
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-4" data-sectioncount="' + section_count + '">' + section_one_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-4" data-sectioncount="' + section_count + '">' + section_two_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-4" data-sectioncount="' + section_count + '">' + section_three_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-4" data-sectioncount="' + section_count + '">' + section_four_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div class="ewd-uwpm-delete dashicons dashicons-no" data-section="' + section_count + '"></div>';
		display_HTML += '<div class="ewd-uwpm-clear"></div>';
		display_HTML += '</div>';
	}
	else if (type == '1-2') {
		var display_HTML = '<div class="ewd-uwpm-section-container">';
		display_HTML += '<div class="ewd-uwpm-section-handle dashicons dashicons-leftright"></div>';
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-1-3" data-sectioncount="' + section_count + '">' + section_one_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-2-3" data-sectioncount="' + section_count + '">' + section_two_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div class="ewd-uwpm-delete dashicons dashicons-no" data-section="' + section_count + '"></div>';
		display_HTML += '<div class="ewd-uwpm-clear"></div>';
		display_HTML += '</div>';
	}
	else if (type == '2-1') {
		var display_HTML = '<div class="ewd-uwpm-section-container">';
		display_HTML += '<div class="ewd-uwpm-section-handle dashicons dashicons-leftright"></div>';
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-2-3" data-sectioncount="' + section_count + '">' + section_one_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div id="ewd-uwpm-section-' + section_count + '" class="ewd-uwpm-section width-1-3" data-sectioncount="' + section_count + '">' + section_two_content + '<div class="ewd-uwpm-edit dashicons dashicons-edit" data-section="' + section_count + '"></div></div>'; section_count++;
		display_HTML += '<div class="ewd-uwpm-delete dashicons dashicons-no" data-section="' + section_count + '"></div>';
		display_HTML += '<div class="ewd-uwpm-clear"></div>';
		display_HTML += '</div>';
	}

	jQuery('#ewd-uwpm-template-information').attr('data-sectioncount', section_count);
	
	return display_HTML;
}

function EWD_UWPM_Form_Templates() {
	jQuery('.ewd-uwpm-template').on('click', function() {
		jQuery('.ewd-uwpm-email-templates').addClass('ewd-uwpm-hidden');
		var Email_Type = jQuery(this).data('template');

		var Elements = EWD_UWPM_Get_Template_Elements(Email_Type);
		jQuery(Elements).each(function(index, el) {
			jQuery('#ewd-uwpm-visual-builder-area').append(EWD_UWPM_Get_Display_HTML(el.type, el.count, el.cont_one, el.cont_two, el.cont_three, el.cont_four));
		});

		EWD_UWPM_Section_Editor_Click_Handlers();
		EWD_UWPM_Enable_Sortable();
		EWD_UWPM_Enable_Delete_Section();

		var save_HTML = jQuery('#ewd-uwpm-visual-builder-area').html();
		jQuery('#ewd-uwpm-email-input textarea').val(save_HTML);
	});
}

function EWD_UWPM_Get_Template_Elements(Form_Type) {
	var plugin_link = jQuery('.ewd-uwpm-email-templates').data('pluginlink');
	switch(Form_Type) {
		case 'newsletter':
			var Elements = [
				{type: 1, count: 0, cont_one: '<p style="text-align: center;"><img class="alignnone size-medium" src="' + plugin_link + 'images/Logo_Image.png" alt="" width="300" height="135" /></a></p>'},
				{type: 1, count: 1, cont_one: '<h1 style="text-align: center;">Newsletter Title</h1><h4 style="text-align: center;">Subtitle to Tell Subscribers the Key Points in your Newsletter</h4>'},
				{type: 1, count: 2, cont_one: '<p style="text-align: center;"><img class="alignnone size-large" src="' + plugin_link + 'images/Banner_Image.png" alt="" width="840" height="588" /></a></p>'},
				{type: 2, count: 3, cont_one: '<h4 style="text-align: left;">Story Section One</h4><p>Here\'s the first story. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quam mauris, euismod ut turpis vel, finibus tristique nisi. Duis ullamcorper turpis tortor, quis venenatis dolor fringilla egestas.</p>', cont_two: '<h4 style="text-align: left;">Story Section Two</h4><p>Here\'s the second story. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quam mauris, euismod ut turpis vel, finibus tristique nisi. Duis ullamcorper turpis tortor, quis venenatis dolor fringilla egestas.</p>'}
			];
			break;
		case 'product_showcase':
			var Elements = [
				{type: 1, count: 0, cont_one: '<p style="text-align: center;"><img class="alignnone size-medium" src="' + plugin_link + 'images/Logo_Image.png" alt="" width="300" height="135" /></a></p><h1 style="text-align: center;">Awesome Products Below!</h1>'},
				{type: 1, count: 1, cont_one: '<p style="text-align: center;"><img class="alignnone size-large" src="' + plugin_link + 'images/Product_Image.png" alt="" width="840" height="588" /></a></p>'},
				{type: 1, count: 2, cont_one: '<h4 style="text-align: left;">Feature your all-star products!</h4><p>Here\'s the description of your collection. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quam mauris, euismod ut turpis vel, finibus tristique nisi. Duis ullamcorper turpis tortor, quis venenatis dolor fringilla egestas.</p>'},
				{type: 1, count: 3, cont_one: '<h3 style="text-align: center;"><a href="https://www.etoilewebdesign.com">Link to your collection!</a></h3>'}
			];
			break;
		case 'thank_you':
			var Elements = [
				{type: 1, count: 0, cont_one: '<p style="text-align: center;"><img class="alignnone size-medium" src="' + plugin_link + 'images/Logo_Image.png" alt="" width="300" height="135" /></a></p><h1 style="text-align: center;">Thank You!</h1>'},
				{type: 1, count: 1, cont_one: '<p style="text-align: center;"><img class="alignnone size-large" src="' + plugin_link + 'images/Banner_Image.png" alt="" width="840" height="588" /></a></p>'},
				{type: '2-1', count: 2, cont_one: '<h4 style="text-align: left;">Loyal Customer</h4><p>We wanted to thank your for your recent purchase, and also let you know about some other products that we have that you might be interested in. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quam mauris, euismod ut turpis vel, finibus tristique nisi. Duis ullamcorper turpis tortor, quis venenatis dolor fringilla egestas.</p>', cont_two: '<h4>Awesome Product</h4><p><a href="http://www.etoilewebdesign.com/plugins/ultimate-product-catalog/">Product One</a><br/><a href="http://www.etoilewebdesign.com/plugins/ultimate-faq/">Product Two</a></p>'},
				{type: 1, count: 4, cont_one: '<h3 style="text-align: center;">Your friendly suppliers,</h3><p style="text-align: center;">Awesome company name</p>'}
			];
			break;
		case 'promotion':
			var Elements = [
				{type: 1, count: 0, cont_one: '<p style="text-align: center;"><img class="alignnone size-medium" src="' + plugin_link + 'images/Logo_Image.png" alt="" width="300" height="135" /></a></p>'},
				{type: 1, count: 1, cont_one: '<h4 style="text-align: center;">Been waiting for the perfect opportunity to buy our products?</h4><h1 style="text-align: center;">Gigantic Sale</h1>'},
				{type: 1, count: 2, cont_one: '<p style="text-align: center;"><img class="alignnone size-large" src="' + plugin_link + 'images/Product_Image.png" alt="" width="840" height="588" /></a></p>'},
				{type: 2, count: 3, cont_one: '<img class="alignnone size-medium" src="' + plugin_link + 'images/Small_Product_Image.png" alt="" width="214" height="300" />', cont_two: '<img class="alignnone size-medium" src="' + plugin_link + 'images/Small_Product_Image.png" alt="" width="214" height="300" />'}
			];
			break;
		case 'follow_up':
			var Elements = [
				{type: 1, count: 0, cont_one: '<p style="text-align: center;"><img class="alignnone size-medium" src="' + plugin_link + '/images/Logo_Image.png" alt="" width="300" height="135" /></a></p>'},
				{type: 1, count: 1, cont_one: '<h1 style="text-align: center;">Just Checking In</h1>'},
				{type: 1, count: 2, cont_one: '<p>We noticed you haven\'t come by our website recently, and we wanted to make sure everything is alright with the service you\'re getting from us.</p>'},
				{type: 1, count: 3, cont_one: '<h3 style="text-align: center;">Your friendly suppliers,</h3><p style="text-align: center;">Awesome company name</p>'}
			];
			break;
		case 'tutorial':
			var Elements = [
				{type: 1, count: 0, cont_one: '<p style="text-align: center;"><img class="alignnone size-medium" src="' + plugin_link + 'images/Logo_Image.png" alt="" width="300" height="135" /></a></p>'},
				{type: 1, count: 1, cont_one: '<h3>Help customers get started with your product or service</h3>'},
				{type: 1, count: 2, cont_one: '<p>Link to tutorials, product manuals and more! Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quam mauris, euismod ut turpis vel, finibus tristique nisi. Duis ullamcorper turpis tortor, quis venenatis dolor fringilla egestas.</p>'},
				{type: 1, count: 3, cont_one: '<h3 style="text-align: center;"><a href="https://www.etoilewebdesign.com">Link to your help section</a></h3>'},
				{type: 2, count: 4, cont_one: '<h4 style="text-align: left;">Way to Get Help</h4><p>Here\'s one way of getting help. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quam mauris, euismod ut turpis vel, finibus tristique nisi. Duis ullamcorper turpis tortor, quis venenatis dolor fringilla egestas.</p>', cont_two: '<h4 style="text-align: left;">Another Way</h4><p>Here\'s the second way to get help. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quam mauris, euismod ut turpis vel, finibus tristique nisi. Duis ullamcorper turpis tortor, quis venenatis dolor fringilla egestas.</p>'}
			];
			break;
		default:
			var Elements = [];
			break;
	}

	return Elements;
}

function EWD_UWPM_Section_Editor_Click_Handlers() {
	jQuery('.ewd-uwpm-section').off('click')
	jQuery('.ewd-uwpm-section').on('click', function() {
		var section_id = jQuery(this).attr('id');
		var current_HTML = jQuery(this).html();
		var section_count = jQuery(this).data('sectioncount');

		var div_character_count = -74 - String(section_count).length;
		var editor_HTML = current_HTML.slice(0, div_character_count);

		jQuery('#ewd-uwpm-section-editor').removeClass('ewd-uwpm-hidden').data('sectionid', section_id);
		jQuery('#ewd-uwpm-email-styling-options').addClass('ewd-uwpm-hidden');
		if (!tinymce.activeEditor) {
			jQuery('.wp-editor-wrap .switch-tmce').trigger('click');
			setTimeout(function() {EWD_UWPM_Display_TinyMCE_Content(editor_HTML)}, 150);
		}
		else {EWD_UWPM_Display_TinyMCE_Content(editor_HTML);}
	});
}

function EWD_UWPM_Display_TinyMCE_Content(editor_HTML) {
	tinymce.activeEditor.setContent(editor_HTML);

	jQuery('#ewd-uwpm-section-editor').addClass('ewd-uwpm-split-screen');
	jQuery('#ewd-uwpm-visual-builder-area').addClass('ewd-uwpm-split-screen');

	tinymce.activeEditor.focus();
}

function EWD_UWPM_Enable_Sortable() {
	jQuery('.ewd-uwpm-section-container').sortable({
		items: '.ewd-uwpm-section',
		cursor: 'move',
		axis: 'x'
	});
}

function EWD_UWPM_Enable_Delete_Section() {
	jQuery('.ewd-uwpm-delete').off('click');
	jQuery('.ewd-uwpm-delete').on('click', function() {
		jQuery(this).parent().remove();
	});
}

function EWD_UWPM_Save_Section_Editor() {
	var section_id = jQuery('#ewd-uwpm-section-editor').data('sectionid');
	//var editor_HTML = jQuery('#ewd-uwpm-editor-textarea').val();
	var editor_HTML = tinymce.activeEditor.getContent();
	var current_HTML = jQuery('#' + section_id).html();

	var section_count = jQuery('#' + section_id).data('sectioncount');
	var div_character_count = 74 + String(section_count).length ;
	var div_HTML = current_HTML.substr(current_HTML.length - div_character_count);

	jQuery('#' + section_id).html(editor_HTML + div_HTML);
}

function EWD_UWPM_Add_Advanced_Send_On(Counter, Max_ID) {
	var HTML = "<tr class='ewd-uwpm-wc-advanced-event' data-sendid='" + Max_ID + "'>";
	HTML += "<td><input type='checkbox' name='Enable_Send_On_" + Counter + "' value='Yes' /></td>";
	HTML += "<td><select class='ewd-uwpm-action-type-select' name='Send_On_Action_Type_" + Counter + "'>";
	HTML += "<optgroup label='User Events'>";
	HTML += "<option value='User_Registers'>On Registeration</option>";
	HTML += "<option value='User_Profile_Updated'>When Profile Updated</option>";
	HTML += "<option value='User_Role_Changed'>When Role Changes</option>";
	HTML += "<option value='User_Password_Reset'>Password is Reset</option>";
	HTML += "<option value='User_X_Time_Since_Login'>X Time Since Last Login</option>";
	HTML += "</optgroup>";
	HTML += "<optgroup label='Site Events'>";
	HTML += "<option value='Post_Published'>Post Published</option>";
	HTML += "<option value='Post_Published_Interest'>Post Published in Interest</option>";
	HTML += "<option value='New_Comment_On_Post'>New Comment after Commenting</option>";
	HTML += "</optgroup>";
	HTML += "<optgroup label='WooCommerce Events'>";
	HTML += "<option value='WC_X_Time_Since_Cart_Abandoned'>X Time after Cart Abandoned</option>";
	HTML += "<option value='WC_X_Time_After_Purchase'>X Time after Purchase</option>";
	HTML += "<option value='Product_Added'>Product Added</option>";
	HTML += "<option value='Product_Purchased'>Product Purchased</option>";
	HTML += "</optgroup>";
	HTML += "</select></td>";
	HTML += "<td><select class='ewd-uwpm-send-on-includes-select' name='Send_On_Includes_" + Counter + "' disabled>";
	HTML += "<option value='Any'>Any Product</option>";
	HTML += "<optgroup label='Categories'>";
	jQuery(ewd_uwpm_php_data.categories).each(function(index, el) {
		HTML += "<option value='C_" + el.term_id + "'>" + el.name + "</option>";
	});
	HTML += "</optgroup>";
	HTML += "<optgroup label='Products'>";
	jQuery(ewd_uwpm_php_data.products).each(function(index, el) {
		HTML += "<option value='P_" + el.ID + "'>" + el.post_title + "</option>";
	});
	HTML += "</optgroup>";
	HTML += "</select></td>";
	HTML += "<td><select name='Send_On_Email_" + Counter + "'>";
	jQuery(ewd_uwpm_php_data.emails).each(function(index, el) {
		HTML += "<option value='" + el.ID + "'>" + el.post_title + "</option>";
	});
	HTML += "</select></td>";
	HTML += "<td><select name='Send_On_Interval_Count_" + Counter + "' class='ewd-uwpm-send-on-interval-count-select' disabled>";
	for (i=1; i<=31; i++) {
		HTML += "<option value='" + i + "' >" + i + "</option>";
	}
	HTML += "</select></td>";
	HTML += "<td><select name='Send_On_Interval_Unit_" + Counter + "' class='ewd-uwpm-send-on-interval-unit-select' disabled>";
	HTML += "<option value='Minutes' >Minute(s)</option>";
	HTML += "<option value='Hours' >Hour(s)</option>";
	HTML += "<option value='Days' >Day(s)</option>";
	HTML += "<option value='Weeks' >Week(s)</option>";
	HTML += "</select></td>";
	HTML += "<td class='ewd-uwpm-delete-advanced-send-on'><input type='hidden' name='Send_On_" + Counter + "' value='" + Max_ID + "' />Delete</td>";
	HTML += "</tr>";

	jQuery('.ewd-uwpm-advanced-event-table tr:last').before(HTML);
}

function EWD_UWPM_Enable_Delete_Send_Ons() {
	jQuery('.ewd-uwpm-delete-advanced-send-on').off('click');
	jQuery('.ewd-uwpm-delete-advanced-send-on').on('click', function() {
		jQuery(this).parent().remove();
	});
}

function EWD_UWPM_Enable_Disable_Send_On_Fields() {
	jQuery('.ewd-uwpm-action-type-select').off('change');
	jQuery('.ewd-uwpm-action-type-select').on('change', function() {
		if (jQuery(this).val() == 'Product_Added' || jQuery(this).val() == 'Product_Purchased') {
			jQuery(this).parent().parent().find('.ewd-uwpm-send-on-includes-select').prop('disabled', false);
		}
		else {
			jQuery(this).parent().parent().find('.ewd-uwpm-send-on-includes-select').prop('disabled', true);
		}

		if (jQuery(this).val() == 'User_X_Time_Since_Login' || jQuery(this).val() == 'WC_X_Time_Since_Cart_Abandoned' || jQuery(this).val() == 'WC_X_Time_After_Purchase') {
			jQuery(this).parent().parent().find('.ewd-uwpm-send-on-interval-count-select, .ewd-uwpm-send-on-interval-unit-select').prop('disabled', false);
		}
		else {
			jQuery(this).parent().parent().find('.ewd-uwpm-send-on-interval-count-select, .ewd-uwpm-send-on-interval-unit-select').prop('disabled', true);
		}
	});
}

function EWD_UWPM_List_Handler() {
	EWD_UWPM_List_Delete_Handlers();
	EWD_UWPM_List_Edit_Handlers();
	EWD_UWPM_Close_List_Edits();
	EWD_UWPM_Save_List_Edits();
	EWD_UWPM_Handle_Add_Users_Click();
	EWD_UWPM_Handle_Remove_Users_Click();

	jQuery('.ewd-uwpm-add-email-lists-item').on('click', function(event) {
		var ID = jQuery(this).data('nextid');
		jQuery('.ewd-uwpm-list-name').val('');

		jQuery('.ewd-uwpm-list-background, .ewd-uwpm-edit-list').removeClass('uwpm-hidden');

		jQuery('.ewd-uwpm-edit-list').data('currentid', ID);

		jQuery('.ewd-uwpm-current-users-table').html('');

		EWD_UWPM_List_Delete_Handlers();

		event.preventDefault();
	});
}

function EWD_UWPM_List_Delete_Handlers() {
	jQuery('.ewd-uwpm-delete-email-lists-item').off('click');
	jQuery('.ewd-uwpm-delete-email-lists-item').on('click', function(event) {
		var Counter = jQuery(this).data('listcounter');
		var tr = jQuery('#ewd-uwpm-email-lists-row-'+Counter);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

function EWD_UWPM_List_Edit_Handlers() {
	jQuery('.ewd-uwpm-email-list-details').off('click');
	jQuery('.ewd-uwpm-email-list-details').on('click', function() {console.log("Clicked");
		var ID = jQuery(this).parent().data('rowid');
		var Encoded_User_IDs = jQuery(this).parent().find('.ewd-uwpm-list-users').val();
		var User_IDs = JSON.parse(Encoded_User_IDs);

		jQuery('.ewd-uwpm-list-background, .ewd-uwpm-edit-list').removeClass('uwpm-hidden');

		jQuery('.ewd-uwpm-edit-list').data('currentid', ID);

		jQuery('.ewd-uwpm-list-name').val(jQuery('.ewd-uwpm-list-name-input').val());

		jQuery('.ewd-uwpm-current-users-table').html('');

		jQuery(User_IDs).each(function(index, el) {
			var HTML = '<input type="checkbox" class="ewd-uwpm-remove-user-from-list" value="' + el.id + '">';
			HTML += '<div class="ewd-uwpm-list-user" data-userid="' + el.id + '">' + el.name + '</div>';
			HTML += '<div class="ewd-uwpm-clear"></div>';

			jQuery('.ewd-uwpm-current-users-table').append(HTML);
		});
	});
}

function EWD_UWPM_Close_List_Edits() {
	jQuery('.ewd-uwpm-close-list-edit, .ewd-uwpm-list-background').on('click', function() {
		jQuery('.ewd-uwpm-list-background, .ewd-uwpm-edit-list').addClass('uwpm-hidden');
	});
}

function EWD_UWPM_Save_List_Edits() {
	jQuery('.ewd-uwpm-save-list-edit').on('click', function() {
		var ID = jQuery('.ewd-uwpm-edit-list').data('currentid');

		if (!jQuery('tr[data-rowid="' + ID + '"]').length) {
			var Counter = jQuery('.ewd-uwpm-add-email-lists-item').data('nextcounter');
			
			var HTML = "<tr id='ewd-uwpm-email-lists-row-" + Counter + "' data-listusers='' data-rowid='" + ID + "'>";
			HTML += "<td class='ewd-uwpm-email-list-details'></td>";
			HTML += "<td class='ewd-uwpm-user-count'></td>";
			HTML += "<td>0</td>";
			HTML += "<td>N/A</td>";
			HTML += "<td><input type='hidden' name='Email_Lists_" + Counter + "_ID' value='" + ID + "' /><input type='hidden' class='ewd-uwpm-list-users' name='Email_Lists_" + Counter + "_List_Users' /><input type='hidden' class='ewd-uwpm-list-name-input' name='Email_Lists_" + Counter + "_List_Name' /><a class='ewd-uwpm-delete-email-lists-item' data-listcounter='" + Counter + "'>Delete</a></td>";
			HTML += "</tr>";

			jQuery('#ewd-uwpm-email-lists-table tr:last').before(HTML);

			jQuery('.ewd-uwpm-add-email-lists-item').data('nextcounter', Counter + 1);
			jQuery('.ewd-uwpm-add-email-lists-item').data('nextid', ID + 1);

			EWD_UWPM_List_Delete_Handlers();
			EWD_UWPM_List_Edit_Handlers();
		}

		var List_Name = jQuery('.ewd-uwpm-list-name').val();
		var User_IDs = [];
		jQuery('.ewd-uwpm-list-user').each(function(index, el) {
			User_IDs.push({id:jQuery(this).data('userid'), name:jQuery(this).html()});
		});
		var Encoded_User_IDs = JSON.stringify(User_IDs);

		jQuery('tr[data-rowid="' + ID + '"]').find('.ewd-uwpm-list-users').val(Encoded_User_IDs);
		jQuery('tr[data-rowid="' + ID + '"]').find('.ewd-uwpm-email-list-details').html(List_Name);
		jQuery('tr[data-rowid="' + ID + '"]').find('.ewd-uwpm-list-name-input').val(List_Name);
		jQuery('tr[data-rowid="' + ID + '"]').find('.ewd-uwpm-user-count').html(User_IDs.length);

		jQuery('.ewd-uwpm-list-background, .ewd-uwpm-edit-list').addClass('uwpm-hidden');
	});
}

function EWD_UWPM_Handle_Add_Users_Click() {
	jQuery('.ewd-uwpm-add-list-users').on('click', function(event) {
		event.preventDefault();

		jQuery('.ewd-uwpm-add-user-id').each(function() {
			var ID = jQuery(this).val();
			var Name = jQuery(this).data('name');

			if (jQuery(this).attr('checked') && !jQuery('.ewd-uwpm-list-user[data-userid="' + ID + '"]').length) {
				var HTML = '<input type="checkbox" class="ewd-uwpm-remove-user-from-list" value="' + ID + '">';
				HTML += '<div class="ewd-uwpm-list-user" data-userid="' + ID + '">' + Name + '</div>';
				HTML += '<div class="ewd-uwpm-clear"></div>';
				jQuery('.ewd-uwpm-current-users-table').append(HTML);
			}

			jQuery(this).attr('checked', false);
		})
	});
}

function EWD_UWPM_Handle_Remove_Users_Click() {
	jQuery('.ewd-uwpm-remove-list-users').on('click', function(event) {
		event.preventDefault();

		jQuery('.ewd-uwpm-remove-user-from-list').each(function() {
			if (jQuery(this).attr('checked')) {
				var ID = jQuery(this).val();

				jQuery(this).remove();
				jQuery('.ewd-uwpm-list-user[data-userid="' + ID + '"]').remove();
			}
		});
	});
}

function ShowOptionTab(TabName) {
	jQuery(".uwpm-option-set").each(function() {
		jQuery(this).addClass("uwpm-hidden");
	});
	jQuery("#"+TabName).removeClass("uwpm-hidden");

	jQuery(".options-subnav-tab").each(function() {
		jQuery(this).removeClass("options-subnav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("options-subnav-tab-active");
	jQuery('input[name="Display_Tab"]').val(TabName);
}

jQuery(document).ready(function() {
	EWD_UWPM_Setup_Spectrum();

	jQuery('.uwpm-spectrum').each(function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_UWPM_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
	});
});

function EWD_UWPM_Setup_Spectrum() {
	jQuery('.ewd-uwpm-spectrum input').spectrum({
		showInput: true,
		showInitial: true,
		preferredFormat: "hex",
		allowEmpty: true
	});

	jQuery('.ewd-uwpm-spectrum input').css('display', 'inline');

	jQuery('.ewd-uwpm-spectrum input').on('change', function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_UWPM_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
		else {
			jQuery(this).css('background', 'none');
		}
	});
}

function EWD_UWPM_hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}




//NEW DASHBOARD MOBILE MENU AND WIDGET TOGGLING
jQuery(document).ready(function($){
	$('#ewd-uwpm-dash-mobile-menu-open').click(function(){
		$('.EWD_UWPM_Menu .nav-tab:nth-of-type(1n+2)').toggle();
		$('#ewd-uwpm-dash-mobile-menu-up-caret').toggle();
		$('#ewd-uwpm-dash-mobile-menu-down-caret').toggle();
		return false;
	});
	$(function(){
		$(window).resize(function(){
			if($(window).width() > 800){
				$('.EWD_UWPM_Menu .nav-tab:nth-of-type(1n+2)').show();
			}
			else{
				$('.EWD_UWPM_Menu .nav-tab:nth-of-type(1n+2)').hide();
				$('#ewd-uwpm-dash-mobile-menu-up-caret').hide();
				$('#ewd-uwpm-dash-mobile-menu-down-caret').show();
			}
		}).resize();
	});	
	$('#ewd-uwpm-dashboard-support-widget-box .ewd-uwpm-dashboard-new-widget-box-top').click(function(){
		$('#ewd-uwpm-dashboard-support-widget-box .ewd-uwpm-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-uwpm-dash-mobile-support-up-caret').toggle();
		$('#ewd-uwpm-dash-mobile-support-down-caret').toggle();
	});
	$('#ewd-uwpm-dashboard-optional-table .ewd-uwpm-dashboard-new-widget-box-top').click(function(){
		$('#ewd-uwpm-dashboard-optional-table .ewd-uwpm-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-uwpm-dash-optional-table-up-caret').toggle();
		$('#ewd-uwpm-dash-optional-table-down-caret').toggle();
	});
});




//OPTIONS HELP/DESCRIPTION TEXT
jQuery(document).ready(function($) {
	$('.uwpm-option-set .form-table tr').each(function(){
		var thisOptionClick = $(this);
		thisOptionClick.find('th').click(function(){
			thisOptionClick.find('td p').toggle();
		});
	});
	$('.ewdOptionHasInfo').each(function(){
		var thisNonTableOptionClick = $(this);
		thisNonTableOptionClick.find('.ewd-uwpm-admin-styling-subsection-label').click(function(){
			thisNonTableOptionClick.find('fieldset p').toggle();
		});
	});
	$(function(){
		$(window).resize(function(){
			$('.uwpm-option-set .form-table tr').each(function(){
				var thisOption = $(this);
				if( $(window).width() < 783 ){
					if( thisOption.find('.ewd-uwpm-admin-hide-radios').length > 0 ) {
						thisOption.find('td p').show();			
						thisOption.find('th').css('background-image', 'none');			
						thisOption.find('th').css('cursor', 'default');			
					}
					else{
						thisOption.find('td p').hide();
						thisOption.find('th').css('background-image', 'url(../wp-content/plugins/ultimate-wp-mail/images/options-asset-info.png)');			
						thisOption.find('th').css('background-position', '95% 20px');			
						thisOption.find('th').css('background-size', '18px 18px');			
						thisOption.find('th').css('background-repeat', 'no-repeat');			
						thisOption.find('th').css('cursor', 'pointer');								
					}		
				}
				else{
					thisOption.find('td p').hide();
					thisOption.find('th').css('background-image', 'url(../wp-content/plugins/ultimate-wp-mail/images/options-asset-info.png)');			
					thisOption.find('th').css('background-position', 'calc(100% - 20px) 15px');			
					thisOption.find('th').css('background-size', '18px 18px');			
					thisOption.find('th').css('background-repeat', 'no-repeat');			
					thisOption.find('th').css('cursor', 'pointer');			
				}
			});
			$('.ewdOptionHasInfo').each(function(){
				var thisNonTableOption = $(this);
				if( $(window).width() < 783 ){
					if( thisNonTableOption.find('.ewd-uwpm-admin-hide-radios').length > 0 ) {
						thisNonTableOption.find('fieldset p').show();			
						thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-image', 'none');			
						thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('cursor', 'default');			
					}
					else{
						thisNonTableOption.find('fieldset p').hide();
						thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-image', 'url(../wp-content/plugins/ultimate-wp-mail/images/options-asset-info.png)');			
						thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-position', 'calc(100% - 30px) 15px');			
						thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-size', '18px 18px');			
						thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-repeat', 'no-repeat');			
						thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('cursor', 'pointer');								
					}		
				}
				else{
					thisNonTableOption.find('fieldset p').hide();
					thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-image', 'url(../wp-content/plugins/ultimate-wp-mail/images/options-asset-info.png)');			
					thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-position', 'calc(100% - 30px) 15px');			
					thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-size', '18px 18px');			
					thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('background-repeat', 'no-repeat');			
					thisNonTableOption.find('ewd-uwpm-admin-styling-subsection-label').css('cursor', 'pointer');			
				}
			});
		}).resize();
	});	
});


//OPTIONS PAGE YES/NO TOGGLE SWITCHES
jQuery(document).ready(function($) {
	jQuery('.ewd-uwpm-admin-option-toggle').on('change', function() {
		var Input_Name = jQuery(this).data('inputname'); console.log(Input_Name);
		if (jQuery(this).is(':checked')) {
			jQuery('input[name="' + Input_Name + '"][value="Yes"]').prop('checked', true).trigger('change');
			jQuery('input[name="' + Input_Name + '"][value="No"]').prop('checked', false);
		}
		else {
			jQuery('input[name="' + Input_Name + '"][value="Yes"]').prop('checked', false).trigger('change');
			jQuery('input[name="' + Input_Name + '"][value="No"]').prop('checked', true);
		}
	});
	$(function(){
		$(window).resize(function(){
			$('.uwpm-option-set .form-table tr').each(function(){
				var thisOptionTr = $(this);
				if( $(window).width() < 783 ){
					if( thisOptionTr.find('.ewd-uwpm-admin-switch').length > 0 ) {
						thisOptionTr.find('th').css('width', 'calc(90% - 50px');			
						thisOptionTr.find('th').css('padding-right', 'calc(5% + 50px');			
					}
					else{
						thisOptionTr.find('th').css('width', '90%');			
						thisOptionTr.find('th').css('padding-right', '5%');			
					}		
				}
				else{
					thisOptionTr.find('th').css('width', '200px');			
					thisOptionTr.find('th').css('padding-right', '46px');			
				}
			});
		}).resize();
	});	
});

