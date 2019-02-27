<?php
/**
 * ESTE ARCHIVO HA SIDO REVISADO
 */
/**
 * The main template file for display single post tour.
 *
 * @package WordPress
*/

/**
*	Get Current page object
**/
$page = get_page($post->ID);

/**
*	Get current page id
**/

if(!isset($current_page_id) && isset($page->ID))
{
    $current_page_id = $page->ID;
}

get_header(); 

//Include custom header feature
get_template_part("/templates/template-header-tour");
?>  
    <div class="inner">

    	<!-- Begid Main content -->
    	<div class="inner_wrapper">
    	
    		<?php
    			//Get Tour Meta
				$tour_country= get_post_meta($current_page_id, 'tour_country', true);
				$tour_price= get_post_meta($current_page_id, 'tour_price', true);
				$tour_price_discount= get_post_meta($current_page_id, 'tour_price_discount', true);
				$tour_price_currency= get_post_meta($current_page_id, 'tour_price_currency', true);
				$tour_availability= get_post_meta($current_page_id, 'tour_availability', true);
				$tour_booking_url= get_post_meta($current_page_id, 'tour_booking_url', true);
				
				//Get number of your days
				$tour_start_date= get_post_meta($current_page_id, 'tour_start_date', true);
				$tour_end_date= get_post_meta($current_page_id, 'tour_end_date', true);
				$tour_start_date_raw= get_post_meta($current_page_id, 'tour_start_date_raw', true);
				$tour_end_date_raw= get_post_meta($current_page_id, 'tour_end_date_raw', true);
				$tour_days = pp_date_diff($tour_start_date_raw, $tour_end_date_raw);
				if($tour_days > 0)
				{
				    $tour_days = intval($tour_days+1).' '.__( 'Días', THEMEDOMAIN );
				}
				else
				{
				    $tour_days = intval($tour_days+1).' '.__( 'Día', THEMEDOMAIN );
				}
				
				$tour_price_display = 0;
				if(empty($tour_price_discount))
				{   
				    if(!empty($tour_price))
				    {
				    	$tour_price_display = $tour_price_currency.pp_number_format($tour_price);
				    }
				}
				else
				{
				    $tour_price_display = '<span class="tour_normal_price">'.$tour_price_currency.pp_number_format($tour_price).'</span>';
				    $tour_price_display.= '<span class="tour_discount_price">'.$tour_price_currency.pp_number_format($tour_price_discount).'</span>';
				}
    		
    			//Check if display tour attribute
    			$pp_tour_attribute = get_option('pp_tour_attribute');
    			if(empty($pp_tour_attribute))
    			{
    				//Set tour attribute block class
					$tour_block_class = 'one_fifth';
					$tour_block_count = 5;
					
					if(empty($tour_start_date) OR empty($tour_end_date))
					{
						$tour_block_count--;
						$tour_block_count--;
					} 
					
					if(empty($tour_price_display))
					{	
						$tour_block_count--;
					}
					
					switch($tour_block_count)
					{
						case 5:
						default:
							$tour_block_class = 'one_fifth';
						break;
						
						case 4:
							$tour_block_class = 'one_fourth';
						break;
						
						case 3:
							$tour_block_class = 'one_third';
						break;
						
						case 2:
							$tour_block_class = 'one_half';
						break;
						
						case 1:
							$tour_block_class = 'one';
						break;
					}
				?>
				
				<div class="tour_meta_wrapper">
					<div class="page_content_wrapper">
						<?php
					    	if(!empty($tour_start_date) && !empty($tour_end_date))
					    	{
					    ?>
	
     <?php 
	 /////agregado
	 function get_slug( $current_page_id = NULL ) {
 
   if( $current_page_id != NULL ) {
     return basename( get_permalink($current_page_id) );
   }
 
   global $post;
   if( empty($post) ) return;
 
   return $post->post_name;
 
}
$posicion_coincidencia = strpos(basename( get_permalink($current_page_id) ), "especial");
if ($posicion_coincidencia === false) {
  ?>
<div class="<?php echo esc_attr($tour_block_class); ?>">
 <div class="tour_meta_title"><?php echo _e( 'Fecha', THEMEDOMAIN ); ?></div>
 <div class="tour_meta_value"><?php 
 
 
 echo date_i18n('d M', strtotime($tour_start_date)); ?> - <?php echo date_i18n('d M', strtotime($tour_end_date)); ?></div>
</div>
  <?php
    } else {?>
<div class="<?php echo esc_attr($tour_block_class); ?>">
 <div class="tour_meta_title"> &nbsp;</div>
 <div class="tour_meta_value">&nbsp; </div>
</div>
            
			
			<?php }
?>
                        <div class="<?php echo esc_attr($tour_block_class); ?>">
					    	<div class="tour_meta_title"><?php echo _e( 'Duración', THEMEDOMAIN ); ?></div>
					    	<div class="tour_meta_value"><?php echo $tour_days; ?></div>
					    </div>
					    <?php
						    }
						?>
					    <?php
					    	if(!empty($tour_price_display))
					    	{
					    ?>
					    <div class="<?php echo esc_attr($tour_block_class); ?>">
					    	<div class="tour_meta_title"><?php echo _e( 'Precio', THEMEDOMAIN ); ?></div>
					    	<div class="tour_meta_value"><?php echo $tour_price_display; ?></div>
					    </div>
					    <?php
					    	}
					    ?>
					    <div class="<?php echo esc_attr($tour_block_class); ?>">
					    	<div class="tour_meta_title"><?php echo _e( 'Disponibilidad', THEMEDOMAIN ); ?></div>
					    	<div class="tour_meta_value"><?php echo $tour_availability; ?></div>
					    </div>
					    <div class="<?php echo esc_attr($tour_block_class); ?> last">
                     
                 <?php   /////datos del usuario 
$usuario = do_shortcode("[user-data field_name='Username']");
$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM vvt_EWD_FEUP_Users WHERE Username='".$usuario."'"));
$max_cliente = $User->User_ID;
$EmailCliente = $User->Username;

if ($User) {
    // Contenido para el resto de usuarios registrados
        // Cualquier ID menos los anteriores (2 y 3)
		if ($posicion_coincidencia === false) {	
		    
            if ($tour_availability=="A SOLICITUD"){?>
				 <a name="tour_book_btn" class="button center" id="tour_book_btn" <?php if(!empty($tour_booking_url)) { ?>href="#"<?php }?>><?php echo _e( 'A SOLICITUD', THEMEDOMAIN ); ?></a>
				<?php }else{   ?>
        <a name="tour_book_btn" class="button center" id="tour_book_btn" <?php if(!empty($tour_booking_url)) { ?>href="/central-formulario/?id_programa=<?php=$current_page_id?>"<?php }?>><?php echo _e( 'RESERVAR', THEMEDOMAIN ); ?></a><?php }} else { ?>
			
			<a id="tour_book_btn" <?php if(!empty($tour_booking_url)) { ?>href="<?php  echo $tour_booking_url; ?>?id_programa=<?php=$current_page_id?>"<?php }?> class="button center"><?php echo _e( 'RESERVAR', THEMEDOMAIN );  // Contenido para los usuarios que no están registrados?></a><?php } ?>
			         
        
   <?php
} else {?>
  <a id="tour_book_btn" <?php if(!empty($tour_booking_url)) { ?>href="<?php  echo $tour_booking_url; ?>?id_programa=<?php=$current_page_id?>"<?php }?> class="button center"><?php echo _e( 'RESERVAR', THEMEDOMAIN );  // Contenido para los usuarios que no están registrados?></a>
    
<?php } ?> 
					    </div>
					</div>
				</div>
			<?php
				}
			?>

	    	<div class="sidebar_content full_width">
	    	
		    	<div class="page_content_wrapper">
		    		<?php
						if (have_posts())
						{ 
							while (have_posts()) : the_post();
			
							the_content();
			    		    
			    		    endwhile; 
			    		}
			    	?>
			    	
			    	<?php
			    		//Get Social Share
						get_template_part("/templates/template-share");
			    	?>
		    	</div>
		    	
		    	<?php
		    		//Check if enable comment
		    		$pp_tour_comment = get_option('pp_tour_comment');
		    		
		    		if(!empty($pp_tour_comment))
		    		{
		    	?>
		    	<div class="page_content_wrapper">
		    	<?php
						comments_template( '' );
				?>
		    	</div>
				<?php
		    		}
		    	?>
		    	
		    	<?php
		    		//Get tour gallery
		    		$tour_gallery= get_post_meta($current_page_id, 'tour_gallery', true);
		    		
		    		if(!empty($tour_gallery))
		    		{
		    			$images_arr = get_post_meta($tour_gallery, 'wpsimplegallery_gallery', true);
		    			$pp_lightbox_enable_title = get_option('pp_lightbox_enable_title');
		    	?>
		    	<div id="portfolio_filter_wrapper" class="three_cols gallery tour_single fullwidth section content clearfix">
		    		<?php
		    			foreach($images_arr as $key => $image)
						{
							$image_url = wp_get_attachment_image_src($image, 'original', true);
							$small_image_url = wp_get_attachment_image_src($image, 'gallery_grid', true);
							$image_caption = get_post_field('post_excerpt', $image);
		    		?>
		    			<div class="element portfolio3filter_wrapper">
							<div class="one_third gallery3 filterable gallery_type animated1">
								<a href="<?php echo $image_url[0]; ?>" <?php if(!empty($pp_lightbox_enable_title)) { ?>title="<?php echo esc_attr($image_caption); ?>"<?php } ?> class="fancy-gallery">
				        		    <img src="<?php echo $small_image_url[0]; ?>" alt="" />
				        		</a>
							</div>
		    			</div>
		    		<?php
		    			}
		    		?>
		    	</div>
		    	<?php
		    		}
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
				    
				    if(isset($image_thumb[0]))
				    {
					    $background_image = $image_thumb[0];
						$background_image_width = $image_thumb[1];
						$background_image_height = $image_thumb[2];
					}
				    
				    if(!empty($pp_page_bg))
				    {
		    	?>
		    </div></div>
			  	<div class="tour_call_to_action parallax" <?php if(!empty($pp_page_bg)) { ?>style="background-image:url('<?php echo $pp_page_bg; ?>');"<?php } ?>>
						<div class="parallax_overlay_header tour"></div>
						
						<div class="tour_call_to_action_box">
							<div class="tour_call_to_action_price"><?php _e( "Precio inicial", THEMEDOMAIN ); ?> <?php echo $tour_price_display; ?></div>
							<div class="tour_call_to_action_book"><?php _e( "Reservar", THEMEDOMAIN ); ?></div>
				<?php	if ($User) {
    // Contenido para el resto de usuarios registrados
        // Cualquier ID menos los anteriores (2 y 3)
		if ($posicion_coincidencia === false) {	
		    
            if ($tour_availability=="A SOLICITUD"){?>
				 <a name="tour_book_btn" class="button center" id="tour_book_btn" <?php if(!empty($tour_booking_url)) { ?>href="#"<?php }?>><?php echo _e( 'A SOLICITUD', THEMEDOMAIN ); ?></a>
				<?php }else{   ?>
        <a name="tour_book_btn" class="button center" id="tour_book_btn" <?php if(!empty($tour_booking_url)) { ?>href="/central-formulario/?id_programa=<?php=$current_page_id?>"<?php }?>><?php echo _e( 'RESERVAR', THEMEDOMAIN ); ?></a><?php }} else { ?>
			
			<a id="tour_book_btn" <?php if(!empty($tour_booking_url)) { ?>href="<?php  echo $tour_booking_url; ?>?id_programa=<?php=$current_page_id?>"<?php }?> class="button center"><?php echo _e( 'RESERVAR', THEMEDOMAIN );  // Contenido para los usuarios que no están registrados?></a><?php } ?>
			         
        
   <?php
} else {?>
  <a id="tour_book_btn" <?php if(!empty($tour_booking_url)) { ?>href="<?php  echo $tour_booking_url; ?>?id_programa=<?php=$current_page_id?>"<?php }?> class="button center"><?php echo _e( 'RESERVAR', THEMEDOMAIN );  // Contenido para los usuarios que no están registrados?></a>
    
<?php } ?> 
						</div>
			    	</div>
		    	<?php
		    		}
		    	?>
		    	
		    	<?php
		    		if(empty($pp_tour_attribute))
					{
						//Set tour attribute block class
						$tour_block_class = 'one_fourth';
						$tour_block_count = 4;
						
						if(empty($tour_start_date) OR empty($tour_end_date))
						{
							$tour_block_count--;
							$tour_block_count--;
						} 
						
						if(empty($tour_price_display))
						{	
							$tour_block_count--;
						}
						
						switch($tour_block_count)
						{
							case 4:
							default:
								$tour_block_class = 'one_fourth';
							break;
							
							case 3:
								$tour_block_class = 'one_third';
							break;
							
							case 2:
								$tour_block_class = 'one_half';
							break;
							
							case 1:
								$tour_block_class = 'one';
							break;
						}
						?>
			    	<!--<div class="tour_meta_wrapper toaction">
						<div class="page_content_wrapper">
							<?php
						    	if(!empty($tour_start_date) && !empty($tour_end_date))
						    	{
						    
						 if ($posicion_coincidencia === false) {
  ?>
<div class="<?php echo esc_attr($tour_block_class); ?>">
 <div class="tour_meta_title"><?php echo _e( 'Fecha', THEMEDOMAIN ); ?></div>
 <div class="tour_meta_value"><?php echo date_i18n('d M', strtotime($tour_start_date)); ?> - <?php echo date_i18n('d M', strtotime($tour_end_date)); ?></div>
</div>
  <?php
    } else {?>
<div class="<?php echo esc_attr($tour_block_class); ?>">
 <div class="tour_meta_title">&nbsp;</div>
 <div class="tour_meta_value">&nbsp; </div>
</div>
            
			
			<?php }
?>
						    <div class="<?php echo esc_attr($tour_block_class); ?>">
						    	<div class="tour_meta_title"><?php echo _e( 'Duración', THEMEDOMAIN ); ?></div>
						    	<div class="tour_meta_value"><?php echo $tour_days; ?></div>
						    </div>
						    <?php
						    	}
						    ?>
						    <?php
						    	if(!empty($tour_price_display))
						    	{
						    ?>
						    <div class="<?php echo esc_attr($tour_block_class); ?>">
						    	<div class="tour_meta_title"><?php echo _e( 'Precio', THEMEDOMAIN ); ?></div>
						    	<div class="tour_meta_value"><?php echo $tour_price_display; ?></div>
						    </div>
						    <?php
						    	}
						    ?>
						    <div class="<?php echo esc_attr($tour_block_class); ?> last">
						    	<div class="tour_meta_title"><?php echo _e( 'Disponibilidad', THEMEDOMAIN ); ?></div>
						    	<div class="tour_meta_value"><?php echo $tour_availability; ?></div>
						    </div>
						</div>
					</div>-->
				<?php
					}
				?>
		    	
		    	
		    	<?php
		    	$pp_tour_next_prev = get_option('pp_tour_next_prev');
		    	if(!empty($pp_tour_next_prev))
		    	{
				    //Get Previous and Next Post
				    $prev_post = get_previous_post();
				    $next_post = get_next_post();
				?>
				<div class="blog_next_prev_wrapper tour">
				   <div class="post_previous">
				      	<?php
				    	    //Get Previous Post
				    	    if (!empty($prev_post)): 
				    	    	$prev_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post->ID), 'thumbnail', true);
				    	    	if(isset($prev_image_thumb[0]))
				    	    	{
									$image_file_name = basename($prev_image_thumb[0]);
				    	    	}
				    	?>
				      		<span class="post_previous_icon"><i class="fa fa-angle-left"></i></span>
				      		<div class="post_previous_content">
				      			<h6><?php echo _e( 'Anterior', THEMEDOMAIN ); ?></h6>
				      			<strong><a <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>class="post_prev_next_link" data-img="<?php echo $prev_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo $prev_post->post_title; ?></a></strong>
				      		</div>
				      	<?php endif; ?>
				   </div>
				   <span class="separated"></span>
				   <div class="post_next">
				   		<?php
				    	    //Get Next Post
				    	    if (!empty($next_post)): 
				    	    	$next_image_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID), 'thumbnail', true);
				    	    	if(isset($next_image_thumb[0]))
				    	    	{
									$image_file_name = basename($next_image_thumb[0]);
				    	    	}
				    	?>
				      		<span class="post_next_icon"><i class="fa fa-angle-right"></i></span>
				      		<div class="post_next_content">
				      			<h6><?php echo _e( 'Siguiente', THEMEDOMAIN ); ?></h6>
				      			<strong><a <?php if(isset($prev_image_thumb[0]) && $image_file_name!='default.png') { ?>class="post_prev_next_link" data-img="<?php echo $next_image_thumb[0]; ?>"<?php } ?> href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo $next_post->post_title; ?></a></strong>
				      		</div>
				      	<?php endif; ?>
				   </div>
				</div>
				<?php
				}
				?>
		    </div>
   
</div> 

<?php
	$pp_page_bg = '';
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full') && empty($term))
    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    }
    
    wp_enqueue_script("jquery.validate", get_template_directory_uri()."/js/jquery.validate.js", false, THEMEVERSION, true);
    wp_register_script("script-booking-form", get_template_directory_uri()."/templates/script-booking-form.php", false, THEMEVERSION, true);
	$params = array(
	  'ajaxurl' => admin_url('admin-ajax.php'),
	  'ajax_nonce' => wp_create_nonce('tgajax-post-contact-nonce'),
	);
	wp_localize_script( 'script-booking-form', 'tgAjax', $params );
	wp_enqueue_script("script-booking-form", get_template_directory_uri()."/templates/script-booking-form.php", false, THEMEVERSION, true);
?>
<div id="tour_book_wrapper" <?php if(!empty($pp_page_bg)) { ?>style="background-image: url('<?php echo $pp_page_bg; ?>');"<?php } ?>>
	<div class="tour_book_content">
		<a id="booking_cancel_btn" href="javascript:;"><i class="fa fa-close"></i></a>
		<div class="tour_book_form">
			<div class="tour_book_form_wrapper">
				<h2 class="ppb_title"><?php _e( "Reservar por ", THEMEDOMAIN ); ?><?php echo get_the_title(); ?></h2>
				<div id="reponse_msg"><ul></ul></div>
				
				<form id="pp_booking_form" method="post" action="/wp-admin/admin-ajax.php">
			    	<input type="hidden" id="action" name="action" value="pp_booking_mailer"/>
			    	<input type="hidden" id="tour_title" name="tour_title" value="<?php echo get_the_title(); ?>"/>
			    	<input type="hidden" id="tour_url" name="tour_url" value="<?php echo get_permalink($current_page_id); ?>"/>
			    	
			    	<div class="one_half">
				    	<label for="first_name"><?php echo _e( 'Nombre', THEMEDOMAIN ); ?></label>
						<input id="first_name" name="first_name" type="text" class="required_field"/>
			    	</div>
					
					<div class="one_half last">
						<label for="last_name"><?php echo _e( 'Apellido', THEMEDOMAIN ); ?></label>
						<input id="last_name" name="last_name" type="text" class="required_field"/>
					</div>
					
					<br class="clear"/><br/>
					
					<div class="one_half">
						<label for="email"><?php echo _e( 'Email', THEMEDOMAIN ); ?></label>
						<input id="email" name="email" type="text" class="required_field"/>
					</div>
					
					<div class="one_half last">
						<label for="phone"><?php echo _e( 'Teléfono', THEMEDOMAIN ); ?></label>
						<input id="phone" name="phone" type="text"/>
					</div>
					
					<br class="clear"/><br/>
					
					<div class="one">
						<label for="message"><?php echo _e( 'Comentario', THEMEDOMAIN ); ?></label>
					    <textarea id="message" name="message" rows="7" cols="10"></textarea>
					</div>
					
					<br class="clear"/>
				    
				    <div class="one">
					    <p>
		    				<input id="booking_submit_btn" type="submit" value="<?php echo _e( 'Reservar por e-mail', THEMEDOMAIN ); ?>"/>
					    </p>
				    </div>
				</form>
			</div>
		</div>
	</div>
	<div class="parallax_overlay_header tour"></div>
</div>

<?php get_footer(); ?>