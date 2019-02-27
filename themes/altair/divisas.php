<?php
	global $wpdb;
	$meses = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
     $fecha= date('d')." ".$meses[date('n')-1]. " ".date('Y');
/**
 * Template Name: Divisas Vivatours
 * The main template file for display page.
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
	
	global $global_pp_topbar;
?>
<div id="page_caption" <?php if(!empty($pp_page_bg)) { ?>class="hasbg parallax <?php if(empty($page_menu_transparent)) { ?>notransparent<?php } ?> <?php if(!empty($pp_page_bg) && !empty($global_pp_topbar)) { ?> withtopbar<?php } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php } ?>>
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
    		    		<?
/////////////INSERTO EL POST DEL FORMULARIO
if ($_POST){

	$wpdb->update('wp_divisas', 
	
	array(
	'dolar' => $_POST['cambio_Dolar'],
	'euro' => $_POST['cambio_Euro'],
	'Fecha' => $fecha),
    
    array( 'id_divisa' => 1 ));
} //////////////////////////
 
 

    	  
 $registros = $wpdb->get_results( "SELECT * FROM wp_divisas", ARRAY_A );
 
     
?>
<form id="sample-form" name="sample-form" method="post"><ul style="list-style-type: none;margin: 0; padding: 0;">
<table width="250" border="0" cellspacing="5" cellpadding="5" class="table">
  <tr>
    <td>
Dolar: <input id="cambio_Dolar" name="cambio_Dolar" type="number" step="any" value="<?=$registros[0]["dolar"]?>" /></td>
  </tr>
  <tr>
    <td>Euro: <input id="cambio_Euro" name="cambio_Euro" type="number" step="any" value="<?=$registros[0]["euro"]?>" /></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><a href="divisas_update.php" params="lightwindow_form=sample-form" class="lightwindow_action" rel="submitForm"><button>Aceptar</button></a> &nbsp; &nbsp; &nbsp; <a href="#" class="lightwindow_action" style="color: blue;" rel="deactivate">Cancelar</a></td>
  </tr>
  </table>	
</form>
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