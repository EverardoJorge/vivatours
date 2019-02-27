/*
	jQuery document ready.
*/
jQuery(document).ready(function()
{
	/*
		assigning keyup event to password field
		so everytime user type code will execute
	*/
	jQuery('.ewd-feup-password-input').keyup(function()
	{
		jQuery('#ewd-feup-password-result').html(checkStrength(jQuery('.ewd-feup-password-input').val(), FEUP_Min_Pass))
	});	

	jQuery('.ewd-feup-check-password-input').keyup(function()
	{
		jQuery('#ewd-feup-password-result').html(checkStrength(jQuery('.ewd-feup-password-input').val(), FEUP_Min_Pass))
	});	
	
	/*
		checkStrength is function which will do the 
		main password strength checking for us
	*/
	
	function checkStrength(password, FEUP_Min_Pass)
	{
		//initial strength
		var strength = 0;
		var checkValue = jQuery('.ewd-feup-check-password-input').val();
console.dir(ewd_feup_ajax_translations);
		if (checkValue != "" && checkValue != password) { 
			jQuery('#ewd-feup-password-result').removeClass();
			jQuery('#ewd-feup-password-result').addClass('ewd-feup-password-mismatch');
			return ewd_feup_ajax_translations.mismatch_label;
		}
		
		if (typeof(FEUP_Min_Pass) == 'undefined' || FEUP_Min_Pass === null) {
			var FEUP_Min_Pass = 6;
		}
		
		//if the password length is less than 6, return message.
		if (password.length < FEUP_Min_Pass) { 
			jQuery('#ewd-feup-password-result').removeClass();
			jQuery('#ewd-feup-password-result').addClass('ewd-feup-password-short');
			return ewd_feup_ajax_translations.too_short_label;
		}
		
		//length is ok, lets continue.
		
		//if length is 8 characters or more, increase strength value
		if (password.length > 7) strength += 1;
		
		//if password contains both lower and uppercase characters, increase strength value
		if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1;
		
		//if it has numbers and characters, increase strength value
		if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1; 
		
		//if it has one special character, increase strength value
		if (password.match(/([!,%,&,@,#,jQuery,^,*,?,_,~])/))  strength += 1;
		
		//if it has two special characters, increase strength value
		if (password.match(/(.*[!,%,&,@,#,jQuery,^,*,?,_,~].*[!,%,&,@,#,jQuery,^,*,?,_,~])/)) strength += 1;
		
		//now we have calculated strength value, we can return messages

		//if value is less than 2
		if (strength < 3 )
		{
			jQuery('#ewd-feup-password-result').removeClass();
			jQuery('#ewd-feup-password-result').addClass('ewd-feup-password-weak');
			return ewd_feup_ajax_translations.weak_label;			
		}
		else if (strength == 3 )
		{
			jQuery('#ewd-feup-password-result').removeClass();
			jQuery('#ewd-feup-password-result').addClass('ewd-feup-password-good');
			return ewd_feup_ajax_translations.good_label;		
		}
		else
		{
			jQuery('#ewd-feup-password-result').removeClass();
			jQuery('#ewd-feup-password-result').addClass('ewd-feup-password-strong');
			return ewd_feup_ajax_translations.strong_label;	
		}
	}
});