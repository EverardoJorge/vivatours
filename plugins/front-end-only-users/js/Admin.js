/* Used to show and hide the admin tabs for FEUP */
function ShowTab(TabName) {
		jQuery(".OptionTab").each(function() {
				jQuery(this).addClass("HiddenTab");
				jQuery(this).removeClass("ActiveTab");
		});
		jQuery("#"+TabName).removeClass("HiddenTab");
		jQuery("#"+TabName).addClass("ActiveTab");
		
		jQuery(".nav-tab").each(function() {
				jQuery(this).removeClass("nav-tab-active");
		});
		jQuery("#"+TabName+"_Menu").addClass("nav-tab-active");
}

function ShowMoreOptions() {
	jQuery(".feup-email-advanced-settings").toggle();
	jQuery(".feup-email-toggle-show").toggle();
	jQuery(".feup-email-toggle-hide").toggle();

	return false;
}

function ShowOptionTab(TabName) {
	jQuery(".feup-option-set").each(function() {
		jQuery(this).addClass("feup-hidden");
	});
	jQuery("#"+TabName).removeClass("feup-hidden");
	
	jQuery(".options-subnav-tab").each(function() {
		jQuery(this).removeClass("options-subnav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("options-subnav-tab-active");
	jQuery('input[name="Display_Tab"]').val(TabName);
}

jQuery(document).ready(function() {	
	jQuery('.ewd-feup-one-click-install-div-load').on('click', function() {
		jQuery('#ewd-feup-one-click-install-div').removeClass('ewd-feup-oci-no-show');
		jQuery('#ewd-feup-one-click-install-div').addClass('ewd-feup-oci-main-event');
		jQuery('#ewd-feup-one-click-blur').addClass('ewd-feup-grey-out');
		jQuery('#ewd-feup-one-click-blur').width(jQuery('#ewd-feup-one-click-blur').width() + 180);
	});

	jQuery('#ewd-feup-one-click-blur').on('click', function() {
		jQuery('#ewd-feup-one-click-install-div').addClass('ewd-feup-oci-no-show');
		jQuery('#ewd-feup-one-click-install-div').removeClass('ewd-feup-oci-main-event');
		jQuery('#ewd-feup-one-click-blur').removeClass('ewd-feup-grey-out');
	});

});

/* This code is required to make changing the field order a drag-and-drop affair */
jQuery(document).ready(function() {	
	jQuery('.fields-list').sortable({
		items: '.list-item',
		opacity: 0.6,
		cursor: 'move',
		axis: 'y',
		update: function() {
			var order = jQuery(this).sortable('serialize') + '&action=ewd_feup_update_field_order';
			jQuery.post(ajaxurl, order, function(response) {});
		}
	});

	/*jQuery('.levels-list').sortable({
		items: '.list-item',
		opacity: 0.6,
		cursor: 'move',
		axis: 'y',
		update: function() {
			var order = jQuery(this).sortable('serialize') + '&action=ewd_feup_update_levels_order';
			alert(order);
			jQuery.post(ajaxurl, order, function(response) {alert(response);});
		}
	});*/
});

jQuery(document).ready(function() {
	SetDiscountDeleteHandlers();

	jQuery('.ewd-feup-add-discount-code').on('click', function(event) {
		var ID = jQuery(this).data('nextid');

		var HTML = "<tr id='ewd-feup-discount-code-row-" + ID + "'>";
		HTML += "<td><a class='ewd-feup-delete-discount-code' data-reminderID='" + ID + "'>Delete</a></td>";
		HTML += "<td><input type='text' name='Discount_Code_" + ID + "_Code'></td>";
		HTML += "<td><input type='text' name='Discount_Code_" + ID + "_Amount'></td>";
		HTML += "<td><select name='Discount_Code_" + ID + "_Recurring'>";
		HTML += "<option value='No'>No</option>";
		HTML += "<option value='Yes'>Yes</option>";
		HTML += "</select></td>";
		HTML += "<td><select name='Discount_Code_" + ID + "_Applicable'>";
		HTML += "<option value='Membership'>Membership</option>";
		//HTML += "<option value='Level'>Hour(s)</option>";
		//HTML += "<option value='Days'>Day(s)</option>";
		HTML += "</select></td>";
		HTML += "<td><input type='datetime-local' name='Discount_Code_" + ID + "_Expiry'></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-feup-add-reminder').before(HTML);
		jQuery('#ewd-feup-discount-codes-table tr:last').before(HTML);

		ID++;
		jQuery(this).data('nextid', ID); //updates but doesn't show in DOM

		SetDiscountDeleteHandlers();

		event.preventDefault();
	});
});

function SetDiscountDeleteHandlers() {
	jQuery('.ewd-feup-delete-discount-code').on('click', function(event) {
		var ID = jQuery(this).data('reminderid');
		var tr = jQuery('#ewd-feup-discount-code-row-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	SetLevelDeleteHandlers();

	jQuery('.ewd-feup-add-level-payment').on('click', function(event) {
		var ID = jQuery(this).data('nextid');

		var HTML = "<tr id='ewd-feup-level-payment-row-" + ID + "'>";
		HTML += "<td><a class='ewd-feup-delete-level-payment' data-levelpaymentid='" + ID + "'>Delete</a></td>";
		HTML += "<td><select class='ewd-feup-insert-levels' name='Level_Payment_" + ID + "_Level'></select></td>";
		HTML += "<td><input type='text' name='Level_Payment_" + ID + "_Amount'><input type='hidden' name='Level_Payment_" + ID + "_Cumulative' value='No'></td>";
		//HTML += "<td><select name='Level_Payment_" + ID + "_Cumulative'>";
		//HTML += "<option value='No'>No</option>";
		//HTML += "<option value='Yes'>Yes</option>";
		//HTML += "</select></td>";
		HTML += "</tr>";

		jQuery('#ewd-feup-level-payments-table tr:last').before(HTML);

		ID++;
		jQuery(this).data('nextid', ID); //updates but doesn't show in DOM

		SetLevelDeleteHandlers();
		GetEWDFEUPLevelOptions();

		event.preventDefault();
	});
});

function SetLevelDeleteHandlers() {
	jQuery('.ewd-feup-delete-level-payment').on('click', function(event) {
		var ID = jQuery(this).data('levelpaymentid');
		var tr = jQuery('#ewd-feup-level-payment-row-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

function GetEWDFEUPLevelOptions() {
	var data = 'action=get_ewd_feup_levels';
	jQuery.post(ajaxurl, data, function(response) {
		Levels = jQuery.parseJSON(response);
		jQuery(Levels).each(function() {
			jQuery('.ewd-feup-insert-levels').append("<option value='"+this.Level_ID+"'>"+this.Level_Name+"</option>");
		});
		jQuery('.ewd-feup-insert-levels').removeClass('ewd-feup-insert-levels');
	});
}

jQuery(document).ready(function() {
	SetMessageDeleteHandlers();

	jQuery('.ewd-feup-add-email').on('click', function(event) {
		var Counter = jQuery(this).data('nextcounter');
		var Max_ID = jQuery(this).data('maxid');

		var HTML = "<tr id='ewd-feup-email-message-" + Counter + "'>";
		HTML += "<td><input type='text' name='Email_Message_" + Counter + "_Name'></td>";
		HTML += "<td><input type='text' name='Email_Message_" + Counter + "_Subject'></td>";
		HTML += "<td><textarea name='Email_Message_" + Counter + "_Body'></textarea></td>";
		HTML += "<td><input type='hidden' name='Email_Message_" + Counter + "_ID' value='" + Max_ID + "' /><a class='ewd-feup-delete-message' data-messagecounter='" + Counter + "'>Delete</a></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-feup-add-reminder').before(HTML);
		jQuery('#ewd-feup-email-messages-table tr:last').before(HTML);

		Counter++;
		Max_ID++;
		jQuery(this).data('nextcounter', Counter); //updates but doesn't show in DOM
		jQuery(this).data('maxid', Max_ID); //updates but doesn't show in DOM

		SetMessageDeleteHandlers();

		event.preventDefault();
	});
});

function SetMessageDeleteHandlers() {
	jQuery('.ewd-feup-delete-message').on('click', function(event) {
		var ID = jQuery(this).data('messagecounter');
		var tr = jQuery('#ewd-feup-email-message-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	jQuery('.ewd-feup-send-test-email').on('click', function() {
		jQuery('.ewd-feup-test-email-response').remove();

		var Email_Address = jQuery('.ewd-feup-test-email-address').val();
		var Email_To_Send = jQuery('.ewd-feup-test-email-selector').val();

		if (Email_Address == "" || Email_To_Send == "") {
			jQuery('.ewd-feup-send-test-email').after('<div class="ewd-feup-test-email-response">Error: Select an email and enter an email address before sending.</div>');
		}

		var data = 'Email_Address=' + Email_Address + '&Email_To_Send=' + Email_To_Send + '&action=feup_send_test_email';
        jQuery.post(ajaxurl, data, function(response) {
        	jQuery('.ewd-feup-send-test-email').after(response);
        });
	});

	jQuery('.ewd-feup-send-email-blast').on('click', function() {
		jQuery('.ewd-feup-email-blast-response').remove();

		var Email_Level = jQuery('.ewd-feup-blast-level-selector').val();
		var Email_To_Send = jQuery('.ewd-feup-email-blast-selector').val();

		if (Email_To_Send == "") {
			jQuery('.ewd-feup-send-test-email').after('<div class="ewd-feup-email-blast-response">Error: Select an email and enter an email address before sending.</div>');
		}

		var data = 'Email_Level=' + Email_Level + '&Email_To_Send=' + Email_To_Send + '&action=feup_send_email_blast';
        jQuery.post(ajaxurl, data, function(response) {
        	jQuery('.ewd-feup-send-email-blast').after(response);
        });
	});
});

jQuery(document).ready(function() {
	SetMCFieldDeleteHandlers();

	jQuery('.ewd-feup-add-mc-field').on('click', function(event) {
		var Counter = jQuery(this).data('nextcounter');

		var HTML = "<tr id='ewd-feup-mc-field-" + Counter + "'>";
		HTML += "<td><select name='Field_ID_" + Counter + "'>";
		jQuery(ewd_feup_field_data).each(function(index, el) {
			HTML += "<option value='" + el.Field_ID + "'>" + el.Field_Name + "</option>";
		});
		HTML += "</td>";
		HTML += "<td><input type='text' name='Mailchimp_Field_ID_" + Counter + "'></td>";
		HTML += "<td><a class='ewd-feup-delete-mc-field' data-mcfieldcounter='" + Counter + "'>Delete</a></td>";
		HTML += "</tr>";

		//jQuery('table > tr#ewd-feup-add-reminder').before(HTML);
		jQuery('#ewd-feup-mc-fields-table tr:last').before(HTML);

		Counter++;
		jQuery(this).data('nextcounter', Counter); //updates but doesn't show in DOM

		SetMCFieldDeleteHandlers();

		event.preventDefault();
	});
});

function SetMCFieldDeleteHandlers() {
	jQuery('.ewd-feup-delete-message').on('click', function(event) {
		var ID = jQuery(this).data('messagecounter');
		var tr = jQuery('#ewd-feup-email-message-'+ID);

		tr.fadeOut(400, function(){
            tr.remove();
        });

		event.preventDefault();
	});
}

jQuery(document).ready(function() {
	jQuery('.ewd-feup-spectrum').spectrum({
		showInput: true,
		showInitial: true,
		preferredFormat: "hex",
		allowEmpty: true
	});

	jQuery('.ewd-feup-spectrum').css('display', 'inline');

	jQuery('.ewd-feup-spectrum').on('change', function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_FEUP_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
		else {
			jQuery(this).css('background', 'none');
		}
	});

	jQuery('.ewd-feup-spectrum').each(function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_FEUP_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
	});
});

function EWD_FEUP_hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

jQuery(document).ready(function() {
	jQuery('#ewd-feup-facebook-login-option').on('change', {optionType: "facebook"}, EWD_FEUP_Update_Options);
	jQuery('#ewd-feup-twitter-login-option').on('change', {optionType: "twitter"}, EWD_FEUP_Update_Options);
	
	EWD_FEUP_Update_Options();
});

function EWD_FEUP_Update_Options(params) {
	if (params === undefined || params.data.optionType == "facebook") {
		if (jQuery('#ewd-feup-facebook-login-option').is(':checked')) {
			jQuery('.ewd-feup-facebook-login-option').removeClass('feup-hidden');
		}
		else {
			jQuery('.ewd-feup-facebook-login-option').addClass('feup-hidden');
		}
	}
	if (params === undefined || params.data.optionType == "twitter") {
		if (jQuery('#ewd-feup-twitter-login-option').is(':checked')) {
			jQuery('.ewd-feup-twitter-login-option').removeClass('feup-hidden');
		}
		else {
			jQuery('.ewd-feup-twitter-login-option').addClass('feup-hidden');
		}
	}
}

jQuery(document).ready(function() {
	jQuery('#ewd-feup-paypal-option').on('change', EWD_FEUP_Update_Options);
	jQuery('#ewd-feup-stripe-option').on('change', EWD_FEUP_Update_Options);
	
	EWD_FEUP_Update_Options();
});

function EWD_FEUP_Update_Options(params) {
	if (jQuery('#ewd-feup-paypal-option').is(':checked')) {
		jQuery('.ewd-feup-paypal-option').removeClass('feup-hidden');
	}
	else {
		jQuery('.ewd-feup-paypal-option').addClass('feup-hidden');
	}
	if (jQuery('#ewd-feup-stripe-option').is(':checked')) {
		jQuery('.ewd-feup-stripe-option').removeClass('feup-hidden');
	}
	else {
		jQuery('.ewd-feup-stripe-option').addClass('feup-hidden');
	}
}


//NEW DASHBOARD MOBILE MENU AND WIDGET TOGGLING
jQuery(document).ready(function($){
	$('#ewd-feup-dash-mobile-menu-open').click(function(){
		$('.EWD_FEUP_Menu .nav-tab:nth-of-type(1n+2)').toggle();
		$('#ewd-feup-dash-mobile-menu-up-caret').toggle();
		$('#ewd-feup-dash-mobile-menu-down-caret').toggle();
		return false;
	});
	$(function(){
		$(window).resize(function(){
			if($(window).width() > 785){
				$('.EWD_FEUP_Menu .nav-tab:nth-of-type(1n+2)').show();
			}
			else{
				$('.EWD_FEUP_Menu .nav-tab:nth-of-type(1n+2)').hide();
				$('#ewd-feup-dash-mobile-menu-up-caret').hide();
				$('#ewd-feup-dash-mobile-menu-down-caret').show();
			}
		}).resize();
	});	
	$('#ewd-feup-dashboard-support-widget-box .ewd-feup-dashboard-new-widget-box-top').click(function(){
		$('#ewd-feup-dashboard-support-widget-box .ewd-feup-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-feup-dash-mobile-support-up-caret').toggle();
		$('#ewd-feup-dash-mobile-support-down-caret').toggle();
	});
	$('#ewd-feup-dashboard-optional-table .ewd-feup-dashboard-new-widget-box-top').click(function(){
		$('#ewd-feup-dashboard-optional-table .ewd-feup-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-feup-dash-optional-table-up-caret').toggle();
		$('#ewd-feup-dash-optional-table-down-caret').toggle();
	});
	$('#ewd-feup-dashboard-one-click .ewd-feup-dashboard-new-widget-box-top').click(function(){
		$('#ewd-feup-dashboard-one-click .ewd-feup-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-feup-dash-one-click-up-caret').toggle();
		$('#ewd-feup-dash-one-click-down-caret').toggle();
	});
});


//REVIEW ASK POP-UP
jQuery(document).ready(function() {
    jQuery('.ewd-feup-hide-review-ask').on('click', function() {
        var Ask_Review_Date = jQuery(this).data('askreviewdelay');

        jQuery('.ewd-feup-review-ask-popup, #ewd-feup-review-ask-overlay').addClass('feup-hidden');

        var data = 'Ask_Review_Date=' + Ask_Review_Date + '&action=ewd_feup_hide_review_ask';
        jQuery.post(ajaxurl, data, function() {});
    });
    jQuery('#ewd-feup-review-ask-overlay').on('click', function() {
    	jQuery('.ewd-feup-review-ask-popup, #ewd-feup-review-ask-overlay').addClass('feup-hidden');
    })
});


//OPTIONS HELP/DESCRIPTION TEXT
jQuery(document).ready(function($) {
	$('.feup-option-set .form-table tr').each(function(){
		var thisOptionClick = $(this);
		thisOptionClick.find('th').click(function(){
			thisOptionClick.find('td p').toggle();
		});
	});
	$('.ewdOptionHasInfo').each(function(){
		var thisNonTableOptionClick = $(this);
		thisNonTableOptionClick.find('.ewd-feup-admin-styling-subsection-label').click(function(){
			thisNonTableOptionClick.find('fieldset p').toggle();
		});
	});
	$('.toplevel_page_EWD-FEUP-options #Emails .form-table tr').each(function(){
		var thisEmailsPageOptionClick = $(this);
		thisEmailsPageOptionClick.find('th').click(function(){
			thisEmailsPageOptionClick.find('td p').toggle();
		});
	});
	$(function(){
		$(window).resize(function(){
			$('.feup-option-set .form-table tr').each(function(){
				var thisOption = $(this);
				if( $(window).width() < 783 ){
					if( thisOption.find('.ewd-feup-admin-hide-radios').length > 0 ) {
						thisOption.find('td p').show();			
						thisOption.find('th').css('background-image', 'none');			
						thisOption.find('th').css('cursor', 'default');			
					}
					else{
						thisOption.find('td p').hide();
						thisOption.find('th').css('background-image', 'url(../wp-content/plugins/front-end-only-users/images/options-asset-info.png)');			
						thisOption.find('th').css('background-position', '95% 20px');			
						thisOption.find('th').css('background-size', '18px 18px');			
						thisOption.find('th').css('background-repeat', 'no-repeat');			
						thisOption.find('th').css('cursor', 'pointer');								
					}		
				}
				else{
					thisOption.find('td p').hide();
					thisOption.find('th').css('background-image', 'url(../wp-content/plugins/front-end-only-users/images/options-asset-info.png)');			
					thisOption.find('th').css('background-position', 'calc(100% - 20px) 15px');			
					thisOption.find('th').css('background-size', '18px 18px');			
					thisOption.find('th').css('background-repeat', 'no-repeat');			
					thisOption.find('th').css('cursor', 'pointer');			
				}
			});
			$('.ewdOptionHasInfo').each(function(){
				var thisNonTableOption = $(this);
				if( $(window).width() < 783 ){
					if( thisNonTableOption.find('.ewd-feup-admin-hide-radios').length > 0 ) {
						thisNonTableOption.find('fieldset p').show();			
						thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-image', 'none');			
						thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('cursor', 'default');			
					}
					else{
						thisNonTableOption.find('fieldset p').hide();
						thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-image', 'url(../wp-content/plugins/front-end-only-users/images/options-asset-info.png)');			
						thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-position', 'calc(100% - 30px) 15px');			
						thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-size', '18px 18px');			
						thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-repeat', 'no-repeat');			
						thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('cursor', 'pointer');								
					}		
				}
				else{
					thisNonTableOption.find('fieldset p').hide();
					thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-image', 'url(../wp-content/plugins/front-end-only-users/images/options-asset-info.png)');			
					thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-position', 'calc(100% - 30px) 15px');			
					thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-size', '18px 18px');			
					thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('background-repeat', 'no-repeat');			
					thisNonTableOption.find('ewd-feup-admin-styling-subsection-label').css('cursor', 'pointer');			
				}
			});
			$('.toplevel_page_EWD-FEUP-options #Emails .form-table tr').each(function(){
				var thisEmailsPageOption = $(this);
				if( $(window).width() < 783 ){
					thisEmailsPageOption.find('td p').hide();
					thisEmailsPageOptionthisEmailsPageOption.find('th').css('background-image', 'url(../wp-content/plugins/order-tracking/images/options-asset-info.png)');			
					thisEmailsPageOption.find('th').css('background-position', '95% 20px');			
					thisEmailsPageOption.find('th').css('background-size', '18px 18px');			
					thisEmailsPageOption.find('th').css('background-repeat', 'no-repeat');			
					thisEmailsPageOption.find('th').css('cursor', 'pointer');								
				}
				else{
					thisEmailsPageOption.find('td p').hide();
					thisEmailsPageOption.find('th').css('background-image', 'url(../wp-content/plugins/order-tracking/images/options-asset-info.png)');			
					thisEmailsPageOption.find('th').css('background-position', 'calc(100% - 20px) 15px');			
					thisEmailsPageOption.find('th').css('background-size', '18px 18px');			
					thisEmailsPageOption.find('th').css('background-repeat', 'no-repeat');			
					thisEmailsPageOption.find('th').css('cursor', 'pointer');			
				}
			});
		}).resize();
	});	
});


//OPTIONS PAGE YES/NO TOGGLE SWITCHES
jQuery(document).ready(function($) {
	jQuery('.ewd-feup-admin-option-toggle').on('change', function() {
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
			$('.feup-option-set .form-table tr').each(function(){
				var thisOptionTr = $(this);
				if( $(window).width() < 783 ){
					if( thisOptionTr.find('.ewd-feup-admin-switch').length > 0 ) {
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


/*************************************************************************
NEW USERS TAB FORMATTING
**************************************************************************/
jQuery(document).ready(function($){
	$('#ewd-feup-admin-add-by-spreadsheet-button').click(function(){
		$('.toplevel_page_EWD-FEUP-options #Users #col-right').removeClass('ewd-feup-admin-products-table-full');
		$('.toplevel_page_EWD-FEUP-options #Users #col-left').removeClass('feup-hidden');
		$('#ewd-feup-admin-add-manually').addClass('feup-hidden');
		$('#ewd-feup-admin-add-from-spreadsheet').removeClass('feup-hidden');
	});
});

/*************************************************************************
CREATE/EDIT USER WIDGET TOGGLING
**************************************************************************/
jQuery(document).ready(function($){
	$('.ewd-feup-admin-closeable-widget-box').each(function(){
		var thisClosableWidgetBox = $(this);
		thisClosableWidgetBox.find('.ewd-feup-dashboard-new-widget-box-top').click(function(){
			thisClosableWidgetBox.find('.ewd-feup-dashboard-new-widget-box-bottom').toggle();
			thisClosableWidgetBox.find('.ewd-feup-admin-edit-product-down-caret').toggle();
			thisClosableWidgetBox.find('.ewd-feup-admin-edit-product-up-caret').toggle();
		});
	});
});


/*************************************************************************
* EMAILS TAB UWPM BANNER
**************************************************************************/
jQuery(document).ready(function($) {
	jQuery('.ewd-feup-uwpm-banner-remove').on('click', function() {console.log("Clicked");
		jQuery('.ewd-feup-uwpm-banner').addClass('feup-hidden');
	
		var data = 'hide_length=999&action=ewd_feup_hide_uwpm_banner';
		jQuery.post(ajaxurl, data, function(response) {});
	});
	jQuery('.ewd-feup-uwpm-banner-reminder').on('click', function() {console.log("Clicked");
		jQuery('.ewd-feup-uwpm-banner').addClass('feup-hidden');
	
		var data = 'hide_length=7&action=ewd_feup_hide_uwpm_banner';
		jQuery.post(ajaxurl, data, function(response) {});
	});
});




