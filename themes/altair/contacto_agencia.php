<?php
/**
 * Template Name: Directorio - Contactar
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
            <div style="width:70%;margin:auto;">
	    		<?php
	    			//Get page header display setting
					$page_hide_header = get_post_meta($current_page_id, 'page_hide_header', true);
					if(empty($page_hide_header))
					{
				?>
	    		<h2 class="ppb_title"><?php the_title(); ?></h2>
	    		<?php
	    			}
	    		?>
	    		<?php 
	    			if ( have_posts() ) {
	    		    while ( have_posts() ) : the_post(); ?>		
	    	
	    		    <?php the_content(); break;  ?>
	
	    		<?php endwhile; 
	    		}
	    		?>
	    		</div>
                <?
				 $row= mysql_fetch_array(mysql_query("SELECT * FROM wp_directorio WHERE id_user=".$_GET[agencia]."",$db));
				?>
                <? if (!$_POST){?>
                <table class="aligncenter" style="border-color: #e3e3e3; height: 150px;" width="600"><tr><td>
                <noscript class="ninja-forms-noscript-message">
    Aviso: JavaScript es necesario para este contenido.</noscript>
                <form id="commentForm"  class="feup-pure-form feup-pure-form-aligned"  method="post" action="#">
<div class="" align="center" style="width:100%;">
<h5>Agradecemos tu interés en nuestro servicios y opciones de viaje, por favor llena el formulario para que la agencia de tu preferencia pueda brindarte información más detallada.</h5>
  <div class="panel-body" style="padding:0;">
  <?
   $programa=mysql_fetch_array(mysql_query("SELECT * FROM wp_posts WHERE  ID=".$_GET['id_programa']."",$db));	
 ?>
  <h3> <? if ($_GET['id_programa']!=''){?>INTERESADO EN:<?=strip_tags(strtoupper($programa['post_title']))?><? } ?></h3> 
 <spam> AGENCIA A CONTACTAR: <?=strip_tags(strtoupper($row[Nombre_Comercial]))?></spam> <spam> CIUDAD: <?=strip_tags(strtoupper($row[Ciudad]))?></spam><br />
 
 <div class="" style="width:100%;" ><br>
  <input name="titulo_programa" type="hidden" value="<?=strip_tags(strtoupper($programa['post_title']))?>"/>
    <input name="EmailAgencia" type="hidden" value="<?=$row["Username"]?>">
    <input name="accion" type="hidden" value="contactar" />
    <input name="programa" type="hidden" value="<?=strip_tags(strtoupper($row_prod[Programa]))?>" />
    <input name="nombre_agencia" type="hidden" value="<?=strip_tags(strtoupper($row[Nombre_Comercial]))?>" />
    

<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="nombre" class="">Nombre: </label></div> <div style="width:70%; text-align:left;"><input name="nombre" type="text" id="nombre"  class="form-control"  placeholder="Nombre..."  /></div>

<br />
<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="Apellidos" class="">Apellidos:</label></div> <div style="width:70%; text-align:left;"><input name="Apellidos" type="text" id="Apellidos"  class="form-control input-sm"  placeholder="Apellidos..."  /></div>
<br />
<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="Email" class=" control-label">Email:</label></div> <div style="width:70%; text-align:left;"> <input name="Email" type="email" id="Email"  class="form-control input-sm"  placeholder="Email..." /></div>
<br />
<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="ciudad" class="control-label">Ciudad:</label></div> <div style="width:70%; text-align:left;"><input name="ciudad" type="text" id="ciudad"  class="form-control input-sm"  placeholder="Ciudad..."  /></div>
<br />
<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="lada" class=" control-label">Lada:</label></div> <div style="width:70%; text-align:left;"><input name="lada" type="text" id="lada"  class="form-control input-sm"  placeholder="lada..." /></div>
<br />
<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="telefono" class="control-label">Teléfono:</label></div> <div style="width:70%; text-align:left;"><input name="telefono" type="text" id="telefono"  class="form-control input-sm"  placeholder="Telefono..." /></div>
<br />
<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="n_pasajeros" class="control-label">No. de Pasajeros:</label></div> <div style="width:70%; text-align:left;"><input name="n_pasajeros" type="text" id="n_pasajeros"  class="form-control input-sm"  placeholder="" /></div>
<br />
<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="fecha_viaje" class="control-label">Fecha Tentativa de Viaje:</label></div> <div style="width:70%; text-align:left;"><input name="fecha_viaje" type="date" id="fecha_viaje"  class="form-control input-sm"  /></div>
<br />
<div style="width:30%; float:left; text-align:right;" class="nf-field-label"><label for="Comentario" class="control-label">Comentario:</label></div> <div style="width:70%; text-align:left;"><textarea name="Comentario"  class="form-control"  id="Comentario" ></textarea></div><br /><br />
<br />

   <input type="submit" class="feup-pure-button-primary" value="Contactar">
</div></div></div>
</form></td></tr></table>
	    		<? }else{
					
					//Destinatario
                  $recipient = $_POST[EmailAgencia];
                  //Asunto del email
                  $subject = 'Formulario de contacto ';
                  //La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
                  $headers[] = "Reply-to:  <agencias@viajesvivatours.com>\r\n";
				  $headers[] = 'From: Viajes Vivatours <agencias@viajesvivatours.com>';
                  $headers[] = 'Cc: Agencias Vivatours <agencias@viajesvivatours.com>';
                  $headers[] = 'Cc: Contactar Agencia <'.$_POST[Email].'>';
				 // $headers[] = 'Cc: Contactar Agencia D <agencias@viajesvivatours.com>';
                  //$headers[] = 'Cc: iluvwp@wordpress.org'; // note you can just use a simple email address
                  //Montamos el cuerpo de nuestro e-mail
                
				 $message ='  
				<table width="865" border="0" align="center" cellpadding="5" cellspacing="0" rules="none" style="font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
  <tr bgcolor="#009999" style="color:#FFF" >
    <td><strong>Gracias por tu interés en nuestras salidas especiales.</strong><br /><br>
      En breve alguno de nuestros agentes se pondrá en contacto contigo para dar seguimiento a tu consulta del viaje seleccionado.</td>
    </tr>
  <tr >
    <td >
    <strong>Interesado en:</strong>'.$_POST[titulo_programa].'
    <br /><br />
    <strong>Agencia contactada:</strong> '.$_POST[nombre_agencia].'<br>
    <strong>Nombre: </strong>'.$_POST[nombre].'<br>			 
    <strong>Apellidos:</strong> '.$_POST[Apellidos].'<br>
    <strong>E-mail:</strong> '.$_POST[Email].'<br>
    <strong>Ciudad:</strong> '.$_POST[ciudad].'<br>
    <strong>Lada:</strong> '.$_POST[lada].'<br>
    <strong>Teléfono:</strong> '.$_POST[telefono].'<br>
    <strong>No. Pasajeros:</strong> '.$_POST[n_pasajeros].'<br>
    <strong>Fecha tentativa:</strong> '.$_POST[fecha_viaje].'<br>
    <strong>Mensaje:</strong> '.$_POST[Comentario].'<br>
    </td>
  </tr>
  <tr bgcolor="#009999"  style="color:#FFF" >
    <td scope="col">Te invitamos a consultar toda la programacion en <font style="font-size:24px; color:#CC0; font-style:italic;"><a href="https://viajesvivatours.com" title="Vivatours" target="_blank"  style="font-size:24px; color:#CC0; font-style:italic;">Vivatours</a></font><br />
    <br />
    Te ofrecemos la mejor selección de viajes para Europa, Medio Oriente, Lejano Oriente, Asia Central, México y Viajes de Autor a la medida.</td>
  </tr>
</table>';
                
                  //Filtro para indicar que email debe ser enviado en modo HTML
                  add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
                  //Por último enviamos el email
                  $envio = wp_mail( $recipient, $subject, $message, $headers, $attachments);
                  //Si el e-mail se envía correctamente mostramos un mensaje y vaciamos las variables con los datos. En caso contrario mostramos un mensaje de error
                  if ($envio) {
                   ?><br/>
                    <div class="alert alert-success alert-dismissable">
                    
                   <h3> El formulario ha sido enviado correctamente.
En breve un agente de viajes se pondrá en contacto para darle seguimiento a tu solicitud</h3>
                    </div>
                  <?php }else {?><br />
                    <div class="alert alert-danger alert-dismissable">
                     
                      Se ha producido un error enviando el formulario. Puede intentarlo más tarde o ponerse en contacto con nosotros escribiendo un mail a "agencias@viajesvivatours.com"
                    </div>
                  <?php }  
				  }?>
               
	    		    <?php //the_content(); break;  ?>
	
	    		
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