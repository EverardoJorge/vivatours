<?php
/**
 * Template Name: Contact Fullscreen
 * The main template file for display contact page.
 *
 * @package WordPress
*/

/**
*	Get Current page object
**/
$current_page = get_page($post->ID);
$current_page_id = '';

if(isset($current_page->ID))
{
    $current_page_id = $current_page->ID;
}

//Get Page Sidebar
$page_sidebar = get_post_meta($current_page_id, 'page_sidebar', true);
if(empty($page_sidebar))
{
	$page_sidebar = 'Contact Sidebar';
}
global $pp_hook_menu_transparent;
$pp_hook_menu_transparent = TRUE;
get_header(); 
?>

<?php
	$pp_page_bg = '';
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full') && empty($term))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    }
    
    $pp_contact_form = unserialize(get_option('pp_contact_form_sort_data'));
    wp_enqueue_script("jquery.validate", get_template_directory_uri()."/js/jquery.validate.js", false, THEMEVERSION, true);
    wp_register_script("script-contact-form", get_template_directory_uri()."/templates/script-contact-form.php", false, THEMEVERSION, true);
	$params = array(
	  'ajaxurl' => admin_url('admin-ajax.php'),
	  'ajax_nonce' => wp_create_nonce('tgajax-post-contact-nonce'),
	);
	wp_localize_script( 'script-contact-form', 'tgAjax', $params );
	wp_enqueue_script("script-contact-form", get_template_directory_uri()."/templates/script-contact-form.php", false, THEMEVERSION, true);
?>
<div id="tour_book_wrapper" <?php if(!empty($pp_page_bg)) { ?>style="background-image: url('<?php echo $pp_page_bg; ?>');display:block;z-index:1;"<?php } ?>>
	<div class="tour_book_content">
		<div class="tour_book_form">
			<div class="tour_book_form_wrapper">
				<h2 class="ppb_title"><?php echo get_the_title(); ?></h2><br/>
				
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>		
	
	    			<?php the_content(); ?>
	
	    		<?php endwhile; ?>
	    		
	    		<?php
	    			$pp_contact_address = get_option('pp_contact_address');
	    			if(!empty($pp_contact_address))
	    			{	
	    				echo tg_apply_content($pp_contact_address);
	    			}
	    		?>
	    		
	    		<br/>
				
				<div id="reponse_msg"><ul></ul></div>
    			
    			<form id="contact_form" method="post" action="/wp-admin/admin-ajax.php">
	    			<input type="hidden" id="action" name="action" value="pp_contact_mailer"/>

    				<?php 
			    		if(is_array($pp_contact_form) && !empty($pp_contact_form))
			    		{
			    			foreach($pp_contact_form as $form_input)
			    			{
			    				switch($form_input)
			    				{
			    					case 1:
			    	?>
			    					<label for="your_name"><?php echo _e( 'Name *', THEMEDOMAIN ); ?></label>
			        				<input id="your_name" name="your_name" type="text" class="required_field" style="width:96%"/>
			        				<br/>		
			    	<?php
			    					break;
			    					
			    					case 2:
			    	?>
			    					
			    					<label for="email"><?php echo _e( 'Email *', THEMEDOMAIN ); ?></label>
			        				<input id="email" name="email" type="text" class="required_field email" style="width:96%"/>
			        				<br/>			
			    	<?php
			    					break;
			    					
			    					case 3:
			    	?>
			    					
			    					<label for="message"><?php echo _e( 'Message *', THEMEDOMAIN ); ?></label>
			        				<textarea id="message" name="message" rows="3" cols="10" class="required_field" style="width:96%"></textarea>
			        				<br/>			
			    	<?php
			    					break;
			    					
			    					case 4:
			    	?>
			    					
			    					<label for="address"><?php echo _e( 'Address', THEMEDOMAIN ); ?></label>
			        				<input id="address" name="address" type="text" style="width:96%"/>
			        				<br/>		
			    	<?php
			    					break;
			    					
			    					case 5:
			    	?>
			    					
			    					<label for="phone"><?php echo _e( 'Phone', THEMEDOMAIN ); ?></label>
			        				<input id="phone" name="phone" type="text" style="width:96%"/>
			        				<br/>		
			    	<?php
			    					break;
			    					
			    					case 6:
			    	?>
			    					
			    					<label for="mobile"><?php echo _e( 'Mobile', THEMEDOMAIN ); ?></label>
			        				<input id="mobile" name="mobile" type="text" style="width:96%"/>
			        				<br/>			
			    	<?php
			    					break;
			    					
			    					case 7:
			    	?>
			    					
			    					<label for="company"><?php echo _e( 'Company Name', THEMEDOMAIN ); ?></label>
			        				<input id="company" name="company" type="text" style="width:96%"/>
			        				<br/>			
			    	<?php
			    					break;
			    					
			    					case 8:
			    	?>
			    					
			    					<label for="country"><?php echo _e( 'Country', THEMEDOMAIN ); ?></label>				
			        				<input id="country" name="country" type="text" style="width:96%"/>
			        				<br/>			
			    	<?php
			    					break;
			    				}
			    			}
			    		}
			    	?>
			    	
			    	<?php
			    		$pp_contact_enable_captcha = get_option('pp_contact_enable_captcha');
			    		
			    		if(!empty($pp_contact_enable_captcha))
			    		{
			    	?>
			    		
			    		<div id="captcha-wrap" class="center">
							<div class="captcha-box">
								<img src="<?php echo get_template_directory_uri(); ?>/get_captcha.php" alt="" id="captcha" />
							</div>
							<div class="text-box">
								<label>Type the two words:</label>
								<input name="captcha-code" type="text" id="captcha-code">
							</div>
							<div class="captcha-action">
								<img src="<?php echo get_template_directory_uri(); ?>/images/refresh.jpg"  alt="" id="captcha-refresh" />
							</div>
						</div>
						<br class="clear"/><br/><br/>
					
					<?php
					}
					?>
			    	<br/>
			    	<p>
    					<input id="contact_submit_btn" type="submit" value="<?php echo _e( 'Submit Form', THEMEDOMAIN ); ?>"/>
			    	</p>
    			</form>
			</div>
		</div>
	</div>
	<div class="parallax_overlay_header"></div>
</div>
<?php get_footer(); ?>		