 <?php

/** 
 * ESTE ARCHIVO HA SIDO REVISADO Y MODIFICADO A <?PHP
*/

/**
 * Template Name: Central Administración
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
    		<?php 
    			if ( have_posts() ) {
    		    while ( have_posts() ) : the_post(); ?>		
    	
    		    <?php the_content(); break;  ?>

    		<?php endwhile; 
    		}
    		?>
<?php
$db=conectar();

$hoy = date("Y-m-d");

$fecha = date(); //fecha inicial?>
          <div><h3> Ultimo Bloqueo</h3></div>
            <?php
			$ultimo_hoy=mysql_fetch_array(mysql_query("SELECT * FROM wp_central_compras WHERE Fecha='$hoy'",$db));	
			?>
            
            <table class="minimo">
   <tr>
     <th width="7%"  align="center" > Bloqueo</th>
          <th width="8%" align="center">Programa</th>
          <th width="26%" align="center">Agencia</th>
          <th width="12%" align="center">Registrado</th>
          <th width="12%" height="20" align="center" >Vence</th>
    <th width="7%" height="20" align="center" >Estatus</th>
  </tr>
  <tr>
    <td><?php=$ultimo_hoy[id_compra]?></td>
    <td><?php=$ultimo_hoy[IDProducto]?></td>
    <td><?php=$ultimo_hoy[compras]?></td>
     <td><?php=$ultimo_hoy[Fecha]?></td>
    <td><?php=$ultimo_hoy[vencimiento]?></td>
    <td><?php=$ultimo_hoy[Estatus]?></td>
  </tr>
</table>
            <h3>Vencen Hoy</h3>
            
<?php $vence_hoy=mysql_fetch_array(mysql_query("SELECT * FROM wp_central_compras WHERE vencimiento = '$hoy' ORDER BY vencimiento DESC",$db)); ?>
            
            <table class="minimo">
              <tr>
                <th width="7%"  align="center" > Bloqueo</th>
                <th width="8%" align="center">Programa</th>
                <th width="26%" align="center">Agencia</th>
                <th width="12%" align="center">Registrado</th>
                <th width="12%" height="20" align="center" >Vence</th>
                <th width="7%" height="20" align="center" >Estatus</th>
              </tr>
              <tr>
                <td><?php=$vence_hoy[id_compra]?></td>
                <td><?php=$vence_hoy[IDProducto]?></td>
                <td><?php=$vence_hoy[compras]?></td>
                <td><?php=$vence_hoy[Fecha]?></td>
                <td><?php=$vence_hoy[vencimiento]?></td>
                <td><?php=$vence_hoy[Estatus]?></td>
              </tr>            
            </table>
            <h3>Vencen Mañana</h3>
            <?php
$x = 1; //dias a sumar
//$vencimiento = date("Y-m-d", strtotime("$fecha + ". $x ." days")); //se suman los $x dias 
$vencimiento_manana = date('Y-m-d', strtotime('+1 day')) ; // Suma 5 días 
$vence_manana=mysql_fetch_array(mysql_query("SELECT * FROM wp_central_compras WHERE vencimiento = '$vencimiento_manana' ORDER BY vencimiento DESC",$db));
?>
            <table class="minimo">
              <tr>
                <th width="7%"  align="center" > Bloqueo</th>
                <th width="8%" align="center">Programa</th>
                <th width="26%" align="center">Agencia</th>
                <th width="12%" align="center">Registrado</th>
                <th width="12%" height="20" align="center" >Vence</th>
                <th width="7%" height="20" align="center" >Estatus</th>
              </tr>
              <tr>
                <td><?php=$vence_manana[id_compra]?></td>
                <td><?php=$vence_manana[IDProducto]?></td>
                <td><?php=$vence_manana[compras]?></td>
                <td><?php=$vence_manana[Fecha]?></td>
                <td><?php=$vence_manana[vencimiento]?></td>
                <td><?php=$vence_manana[Estatus]?></td>
              </tr>
               </table>
                    <?php
$x = 1; //dias a sumar
//$vencimiento = date("Y-m-d", strtotime("$fecha + ". $x ." days")); //se suman los $x dias 
$vencimiento_pasado_manana = date('Y-m-d', strtotime('+2 day')) ; // Suma 5 días 
$vence_pasado_manana=mysql_query("SELECT * FROM wp_central_compras WHERE vencimiento = '$vencimiento_pasado_manana' ORDER BY vencimiento DESC",$db);
?>
            <h3>Vencen Pasado Mañana</h3>
            <table class="minimo">
              <tr>
                <th width="7%"  align="center" > Bloqueo</th>
                <th width="8%" align="center">Programa</th>
                <th width="26%" align="center">Agencia</th>
                <th width="12%" align="center">Registrado</th>
                <th width="12%" height="20" align="center" >Vence</th>
                <th width="7%" height="20" align="center" >Estatus</th>
              </tr>
              <?php 
			while($vence_pasado_row = mysql_fetch_array($vence_pasado_manana)){
			  ?>
              <tr>
                <td><?php=$vence_pasado_row[id_compra]?></td>
                <td><?php=$vence_pasado_row[IDProducto]?></td>
                <td><?php=$vence_pasado_row[compras]?></td>
                <td><?php=$vence_pasado_row[Fecha]?></td>
                <td><?php=$vence_pasado_row[vencimiento]?></td>
                <td><?php=$vence_pasado_row[Estatus]?></td>
              </tr>  <?php }?>            
            </table>
            <h3>Historial</h3>
            <table class="minimo">
              <tr>
                <th width="7%"  align="center" > Bloqueo</th>
                <th width="8%" align="center">Programa</th>
                <th width="26%" align="center">Agencia</th>
                <th width="12%" align="center">Registrado</th>
                <th width="12%" height="20" align="center" >Vence</th>
                <th width="7%" height="20" align="center" >Estatus</th>
              </tr>
              <tr>
                <td><?php=$ultimo_hoy[id_compra]?></td>
                <td><?php=$ultimo_hoy[IDProducto]?></td>
                <td><?php=$ultimo_hoy[compras]?></td>
                <td><?php=$ultimo_hoy[Fecha]?></td>
                <td><?php=$ultimo_hoy[vencimiento]?></td>
                <td><?php=$ultimo_hoy[Estatus]?></td>
              </tr>
              
            </table>
          
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