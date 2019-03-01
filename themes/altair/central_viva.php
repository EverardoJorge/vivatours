<?php
/**
 * Template Name: Central de Reservas
 * Muestra un acordión con todas las salidas disponibles, con el fin de reservar
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
$current_page_id = '';

if(isset($page->ID))
{
    $current_page_id = $page->ID;
}

get_header(); 
?>

<?php
    //Get Page RevSlider
    $page_revslider = get_post_meta($current_page_id, 'page_revslider', true);
    $page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);
    $page_header_below = get_post_meta($current_page_id, 'page_header_below', true);
    
    if(!empty($page_revslider) && $page_revslider != -1 && empty($page_header_below))
    {
    	echo '<div class="page_slider ';
    	if(!empty($page_menu_transparent))
    	{
	    	echo 'menu_transparent';
    	}
    	echo '">'.do_shortcode('[rev_slider '.$page_revslider.']').'</div>';
    }
?>

<?php
//Get page header display setting
$page_hide_header = get_post_meta($current_page_id, 'page_hide_header', true);

if($page_revslider != -1 && !empty($page_menu_transparent))
{
	$page_hide_header = 1;
}

if(empty($page_hide_header) && ($page_revslider == -1 OR empty($page_revslider) OR !empty($page_header_below)))
{
	$pp_page_bg = '';
	//Get page featured image
	if(has_post_thumbnail($current_page_id, 'full'))
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
?>
<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
	<div class="page_title_wrapper">
		<h1 <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>class ="withtopbar"<?php } ?>><?php the_title(); ?></h1>
		<?php 
			$pp_breadcrumbs_display = get_option('pp_breadcrumbs_display');
			
			if(!empty($pp_breadcrumbs_display))
			{
				echo dimox_breadcrumbs(); 
			}
		?>
	</div>
	<?php if(!empty($pp_page_bg)) { ?>
		<div class="parallax_overlay_header"></div>
	<?php } ?>
</div>
<br class="clear"/>
<?php
}
else
{
?>
<br/>
<?php
}
?>

<?php
	//Check if use page builder
	$ppb_form_data_order = '';
	$ppb_form_item_arr = array();
	$ppb_enable = get_post_meta($current_page_id, 'ppb_enable', true);
	
	global $global_pp_topbar;
?>
<?php
	if(!empty($ppb_enable))
	{
?>
<div class="ppb_wrapper <?php if(!empty($pp_page_bg)) { ?>hasbg<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?>">
<?php
		tg_apply_builder($current_page_id);
?>
</div>
<?php
	}
	else
	{
?>
<!-- Begin content -->
<div id="page_content_wrapper" class="<?php if(!empty($pp_page_bg)) { ?>hasbg<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?>withtopbar<?php } ?>">
    <div class="inner">
    	<!-- Begin main content -->
    	<div class="inner_wrapper">
    		<div class="sidebar_content full_width">
          <link rel="stylesheet" href="https://viajesvivatours.com/wp-content/themes/altair/js/jquery-ui-1.12.1.acordion/jquery-ui.css">
  
  <script src="https://viajesvivatours.com/wp-content/themes/altair/js/jquery-2.1.1.min.js"></script>
  <script src="https://viajesvivatours.com/wp-content/themes/altair/js/jquery-ui-1.12.1.acordion/jquery-ui.js"></script>

 <script>
  $( function() {
    $( "#accordion" ).accordion({
      active: false,
  collapsible: true,
  autoHeight: false,
  heightStyle: 'content',
    });
  } );
  </script>

 
<div id="accordion" class="motor-reservas">          
 <?php

 /* CONSULTAS A LA BD ACTUALIZADAS USANDO LA CONEXIÓN OFICIAL DE WORDPRESS */
 	$res_taxonomidas= $wpdb->get_results( "SELECT * FROM vvt_term_taxonomy,vvt_terms WHERE vvt_term_taxonomy.taxonomy='tourcats' and vvt_term_taxonomy.description='especial' and parent = 0 and vvt_term_taxonomy.term_taxonomy_id=vvt_terms.term_id ORDER BY Orden" );

    foreach($res_taxonomidas as $row){ 
             echo "<h3>".$row->name."</h3><div>";
             $res_relacion = $wpdb->get_results( "SELECT * FROM vvt_term_relationships, vvt_posts WHERE vvt_term_relationships.term_taxonomy_id='".$row->term_taxonomy_id."' and vvt_term_relationships.object_id= vvt_posts.ID and post_status!='trash' order by vvt_posts.post_title ASC" );

             foreach ($res_relacion as $row_relacion) {
             	$tour_availability= get_post_meta($row_relacion->object_id, 'tour_availability', true);

             	if($tour_availability == "A SOLICITUD"){
					$SOLICITUD = " - ".$tour_availability; 
				echo "<p>".$row_relacion->post_title.$SOLICITUD."</p>";
					 } else {
						 echo "<p><a href='central-formulario/?id_programa=".$row_relacion->object_id."'>".$row_relacion->post_title."</a></p>";
						}
             }

             // taxonomias hijas
             $res_taxhijas =  $wpdb->get_results( "SELECT * FROM vvt_term_taxonomy,vvt_terms WHERE vvt_term_taxonomy.taxonomy='tourcats' and vvt_term_taxonomy.parent = ".$row->term_taxonomy_id."  and vvt_term_taxonomy.term_taxonomy_id=vvt_terms.term_id" );

             foreach ($res_taxhijas as $row_hijas) {
             	echo "<h5>".$row_hijas->name."</h5>";

             	$res_relacion2 = $wpdb->get_results( "SELECT * FROM vvt_term_relationships,vvt_posts WHERE term_taxonomy_id='".$row_hijas->term_taxonomy_id."' and vvt_term_relationships.object_id= vvt_posts.ID  and post_status!='trash' order by vvt_posts.post_title ASC" );      
		       
		      foreach ($res_relacion2 as $row_relacion2) {
		      	$tour_availability= get_post_meta($row_relacion->object_id, 'tour_availability', true);
				if($tour_availability=="A SOLICITUD"){
					$SOLICITUD = " - ".$tour_availability; 
				echo "<p>".$row_relacion2->post_title.$SOLICITUD."</p>";
					 } else {
						 echo "<p><a href='central-formulario/?id_programa=".$row_relacion2->object_id."'>".$row_relacion2->post_title."</a></p>";
						}	
		      }
		      	 

             }

             echo "</div>";
          }
	 

	?>
	</div><!--CIERRA ACORDION -->
    
    
    
	    		
	    		</div>
    	</div>
        
        
    	<!-- End main content -->
       
    </div> 
</div>
<?php
}
?>
<?php
if(empty($ppb_enable))
{
?>
<br class="clear"/><br/><br/>
<?php
}
?>
<?php get_footer(); ?>