<?php
/**
 * Template Name: Directorio viva
 * The main template file for display page.
 *
 * @package WordPress
*/


/**
*	Get Current page object
**/
require_once("includes/conexion.php");
$db=conectar();
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
            <form method="post" id="form_ciudad" name="form_ciudad" action="#" class="feup-pure-form feup-pure-form-aligned">
<table width="100%" border="0" align="center" id="directorios" cellspacing="0" cellpadding="0" style="border:none;" rules="none">
  <tr>
    <td height="30" align="right" valign="middle" style="padding-right:30px;"><span style="color:#205776; font-size:16px;  font-weight:bold; ">Selecciona tu Ciudad</span></td>
    <td align="left" valign="middle"><select name="ciudad" class="ewd-feup-select"  id="ciudad" onchange="this.form.submit();">
      <option value="">VER CIUDADES</option>
      <?php
	  
		 // $query_ciudad=;
          $res_ciudad= mysql_query("SELECT Ciudad, Estado FROM vvt_directorio WHERE Ciudad <> '' GROUP BY Ciudad",$db);

          while($row_ciudad = mysql_fetch_array($res_ciudad)){
		  	//$query_estado = 

		  ?>
      <option value="<?php=$row_ciudad['Ciudad']?>"  <?php=($row_ciudad['Ciudad'] == $_POST[ciudad]) ? "selected" : ""?>>
        <?php=$row_ciudad['Ciudad'].", ".$row_ciudad['Estado']?>
        </option>
      <?php
		  }
		  ?>
    </select>    
   </td>
  </tr>
  <tr>
    <td height="30" align="right" valign="middle" style="padding-right:30px;"><span style="color:#205776; font-size:16px; font-weight:bold;">Buscar por Agencia:</span>
    </td>
    <td height="30" align="left" valign="middle">
            <input name="nombre_agencia" type="text" id="nombre_agencia" value="<?phpphp=$_POST[nombre_agencia]?>"  class="ewd-feup-text-input"/>
              
             <input type="submit" class="feup-pure-button-primary" value="BUSCAR">
               </span> <div id="resultado" ></div>   </td>
  </tr>
 
</table> </form>
				<?php //Inicializamos la sesión
				
if(($_POST )and (empty($_GET['agencia']))){
	  
	  /////SI SE SELECCIONO SOLO CIUDAD////
	 if((!empty($_POST['ciudad'])) and (empty($_POST[nombre_agencia]) ) ){
	    		
$_pagi_sql = "SELECT * FROM vvt_directorio where Ciudad='".$_POST['ciudad']."' ORDER BY rand(" . time() . " * " . time() . " )";

}
				
			
			
  ////////SI SOLO PUSO NOMBRE DE AGENCIA////////	 
 if((empty($_POST['ciudad'])) and (!empty($_POST[nombre_agencia]) ) ){
	$_pagi_sql = "SELECT * FROM vvt_directorio WHERE  Razon_social LIKE '".$_POST[nombre_agencia]."' ORDER BY rand(" . time() . " * " . time() . " )";
 }
				 
	////////SI PUSO AGENCIA Y CIUDAD
 if((!empty($_POST['ciudad'])) and (!empty($_POST[nombre_agencia]) ) ){
 
$_pagi_sql = "SELECT * FROM vvt_directorio WHERE  Razon_social LIKE '".$_POST[nombre_agencia]."%' ORDER BY rand(" . time() . " * " . time() . " )";
 }


$_pagi_nav_num_enlaces=5;
$_pagi_cuantos = 20; 
include("includes/paginator.inc.php"); 


while($row = mysql_fetch_array($_pagi_result)){ 

    
		?> 
 <table  align="left" width="32%" style="margin:5px; border:1px solid #999 !important; width:32% !important;" >
  <tr>
    <td align="center" height="100" valign="middle"><center><?php 
if ($registro->Imagen_perfil!=''){ 
$logo= '<img src="../wp-content/uploads/ewd-feup-user-uploads/'.$row[Imagen_perfil].'" border="0"  width="90"/>'; }
else {
$logo= '<img src="../wp-content/uploads/ewd-feup-user-uploads/nodisponible.jpg" border="0" " width="90"/>';
}
echo do_shortcode( '[tg_image src="'.$logo.'" animation="fadeIn"]' );
?>
</center></td>
  </tr>

  <tr>
  <td height="50px" align="left" valign="top"  style=" padding:10px 10px 5px 10px;"><?php=strtoupper($row["Nombre_Comercial"])?><br />
 <?php //$row['Nombre']?> <?php //$row['Apellidos']?>
  <?php=$row[Direccion]?> <?php=(!empty($row[Colonia])) ? ",". $row[Colonia] : ""?>  <?php=$row[Ciudad]?>, <?php=$row[Estado]?>, <?php=$row[Codigo_postal]?> <br>
 <br><center> <a href="https://www.viajesvivatours.com/directorio/contacto-agencia/?agencia=<?php=$row["id_user"]?>&id_programa=<?php=$_GET[id_programa]?>" class="button">Contactar Agencia</a></center></td></tr>
</table>


<?php }
?>
<table align="center" width="100%">
 <tr>

    <td align='center' colspan='8'>

<?php=$_pagi_navegacion?></td>

  </tr>

  <tr>

    <td align='center' colspan='9'><?php=$_pagi_info?></td>


  </tr></table><?php } ?>
            
            
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