<?php 
header("content-type: application/x-javascript"); 
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );

//Get contact form ID
$contact_form_id = 'pp_booking_form';
$response_id = 'reponse_msg';

if(isset($_GET['form']))
{
	$contact_form_id.= '_'.$_GET['form'];
	$response_id.= '_'.$_GET['form'];
}
?>
jQuery(document).ready(function() {
	jQuery('form#<?php echo $contact_form_id; ?>').submit(function() {
		jQuery('form#<?php echo $contact_form_id; ?> .error').remove();
		var hasError = false;
		jQuery('.required_field').each(function() {
			if(jQuery.trim(jQuery(this).val()) == '') {
				var labelText = jQuery(this).prev('label').text();
				jQuery('#<?php echo $response_id; ?> ul').append('<li class="error"><?php echo _e( 'Please enter', THEMEDOMAIN ); ?> '+labelText+'</li>');
				hasError = true;
			} else if(jQuery(this).hasClass('email')) {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if(!emailReg.test(jQuery.trim(jQuery(this).val()))) {
					var labelText = jQuery(this).prev('label').text();
					jQuery('#<?php echo $response_id; ?> ul').append('<li class="error"><?php echo _e( 'Please enter valid', THEMEDOMAIN ); ?> '+labelText+'</li>');
					hasError = true;
				}
			}
		});
		if(!hasError) {
			var contactData = jQuery('#<?php echo $contact_form_id; ?>').serialize();

			jQuery('#booking_submit_btn').fadeOut('normal', function() {
				jQuery(this).parent().append('<img src="<?php echo get_template_directory_uri(); ?>/images/loading.gif" alt="Loading" />');
			});
 			
 			jQuery.ajax({
			    type: 'POST',
			    url: tgAjax.ajaxurl,
			    data: contactData+'&tg_security='+tgAjax.ajax_nonce,
			    success: function(results){
			    	jQuery('#<?php echo $contact_form_id; ?>').hide();
			    	jQuery('#<?php echo $response_id; ?>').html(results);
			    	
			    	jQuery('#booking_close_form').click(function() {
						jQuery('#tour_book_wrapper').fadeOut();
					});
			    }
			});
		}
		
		return false;
		
	});
});