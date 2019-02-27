<?php
/**
 * Template Name: Central de Reservas
 * The main template file for display page.
 *
 * @package WordPress
*/


/**
*	Get Current page object
**/
require_once("includes/conexion.php");

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

 
<div id="accordion">
 
           
 <?

    $db=conectar();
	$taxonomidas=  mysql_query("SELECT * FROM vvt_term_taxonomy WHERE taxonomy='tourcats' and description='especial' and parent = 0 ORDER BY Orden",$db);
	 
	 while($row=mysql_fetch_array($taxonomidas)){
		 $terms= mysql_fetch_array( mysql_query("SELECT * FROM  vvt_terms where term_id='".$row['term_id']."'",$db));
		 echo " <h3>".$terms[name]."</h3>  <div>"; 
 
		 $relacion= mysql_query("SELECT * FROM vvt_term_relationships WHERE term_taxonomy_id='".$row['term_taxonomy_id']."'",$db);
		   while( $row_relacion= mysql_fetch_array($relacion)){
			$central= mysql_query("SELECT * FROM vvt_posts WHERE  ID='".$row_relacion[object_id]."' ORDER BY post_title ASC",$db);
			while($row_central=mysql_fetch_array($central)){
					$tour_availability= get_post_meta($row_relacion[object_id], 'tour_availability', true);
				if($tour_availability=="A SOLICITUD"){
					$SOLICITUD=" - ".$tour_availability; 
				echo "<p>".$row_central['post_title'].$SOLICITUD."</p>";
					 } else 
					{
						 echo "<p><a href='central-formulario/?id_programa=".$row_relacion[object_id]."'>".$row_central['post_title']."</a></p>";
						}
	        
	 }
			 
			  
			  }
		  
         /////////////taxonomias hijas
	 	$tax_hijas=  mysql_query("SELECT * FROM wp_term_taxonomy WHERE taxonomy='tourcats' and parent = ".$row['term_taxonomy_id']."",$db);
		
	     while($row_hijas=mysql_fetch_array($tax_hijas)){
		 $terms= mysql_fetch_array( mysql_query("SELECT * FROM  wp_terms where term_id='".$row_hijas['term_id']."'",$db));
		 echo "<h5>".$terms[name]."</h5>";
		 
		     $relacion2= mysql_query("SELECT * FROM wp_term_relationships WHERE term_taxonomy_id='".$row_hijas['term_taxonomy_id']."'",$db);
		      while( $row_relacion2= mysql_fetch_array($relacion2)){
				   echo "<h5>".$row_relacion2[name]."</h5>";
				  $central2= mysql_query("SELECT * FROM wp_posts WHERE  ID='".$row_relacion2[object_id]."'  ORDER BY post_title ASC",$db);
		 while($row_central2=mysql_fetch_array($central2)){
	         echo "<p><a href='central-formulario/?id_programa=".$row_relacion2[object_id]."'>".$row_central2['post_title']."</a></p>";
	 }
			 
			  
			  }
		 
		 
		 }
		////// 
		echo " </div>";
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