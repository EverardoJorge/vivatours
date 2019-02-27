<?php

/** 
 * ESTE ARCHIVO HA SIDO REVISADO Y CORREGIDO A <?PHP
*/
/**
 * Template Name: Central Formulario
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
            
<?php	    	 $db=conectar();
/////datos del usuario 
$usuario = do_shortcode("[user-data field_name='Username']");
$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM vvt_EWD_FEUP_Users WHERE Username='".$usuario."'"));
$max_cliente = $User->User_ID;
$EmailCliente = $User->Username;

if ($User) {?>
         <form action="guardar-bloqueo" method="post" name="forma_carrito_1" id="forma_carrito_1"  class="feup-pure-form feup-pure-form-aligned">
 
  <table width="100%"  rules="none" align="center" cellpadding="0" cellspacing="0" class="minimo">
    <tr align="center">
      <th align="left" style="font-size:20pt;  color:#333; padding:5px;">
        
        <?php
	 $programa=mysql_fetch_array(mysql_query("SELECT * FROM vvt_posts WHERE  ID=".$_GET['id_programa']."",$db));	
	 echo "Bloquear programa: ".$programa['post_title'];
	 
	 ?>
        <input name="Programa" type="hidden" value="<?php=$programa['post_title']?>">
        <input name="id_programa" type="hidden" value="<?php=$_GET['id_programa']?>">
      </th></tr>   
       <tr><td> <script type="text/javascript">
			
				
		jQuery(document).ready(function(){
			
			
			jQuery("select[name=habitaciones]").change(function(){
		jQuery("select[name=habitaciones] option[value='0']").remove();
		if (jQuery('select[name=habitaciones]').val() == '1') {
            jQuery("#habitaciones1").hide();
		    jQuery("#habitaciones2").hide();
			 jQuery("#habitaciones3").hide();
			  jQuery("#habitaciones4").hide();
		  jQuery("#habitaciones1").fadeToggle(2000);
		   jQuery("#habitaciones2 input").removeAttr('required');
		   jQuery("#habitaciones3 input").removeAttr('required');
			jQuery("#habitaciones4 input").removeAttr('required');
		   
		   }
		   
		if (jQuery('select[name=habitaciones]').val() == '2') {
           jQuery("#habitaciones1").hide();
		    jQuery("#habitaciones2").hide();
			 jQuery("#habitaciones3").hide();
			  jQuery("#habitaciones4").hide();
		  
		  
		   jQuery("#habitaciones1").fadeToggle(2000);
		    jQuery("#habitaciones2").fadeToggle(2000);
			jQuery("#pax1_2").attr('required', 'true');
			jQuery("#habitaciones3 input").removeAttr('required');
			jQuery("#habitaciones4 input").removeAttr('required');
		   }
		   
		   	if (jQuery('select[name=habitaciones]').val() == '3') {
          jQuery("#habitaciones1").hide();
		    jQuery("#habitaciones2").hide();
			 jQuery("#habitaciones3").hide();
			  jQuery("#habitaciones4").hide();
			  jQuery("#pax1_3").attr('required', 'true');
		   jQuery("#habitaciones1").fadeToggle(2000);
		    jQuery("#habitaciones2").fadeToggle(2000);
			jQuery("#habitaciones3").fadeToggle(2000);
			jQuery("#habitaciones4 input").removeAttr('required');
		   }
		   if (jQuery('select[name=habitaciones]').val() == '4') {
          jQuery("#habitaciones1").hide();
		    jQuery("#habitaciones2").hide();
			 jQuery("#habitaciones3").hide();
			 jQuery("#habitaciones4").hide();
			 jQuery("#pax1_4").attr('required', 'true');
		   jQuery("#habitaciones1").fadeToggle(2000);
		    jQuery("#habitaciones2").fadeToggle(2000);
			jQuery("#habitaciones3").fadeToggle(2000);
			jQuery("#habitaciones4").fadeToggle(2000);
			
		   }
			});
		});
		</script>
   
   <div style="float:left; width:30%; text-align:right;"><label for="habitaciones" class="">No. de Habitaciones:</label></div><div  style="float:left; width:70%;"><select name="habitaciones" id="select" class="ewd-feup-select">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
      </select></div>
    
</td>
  </tr>
  <tr id="habitaciones1" style="display:none;" >
    <td>
 
    <script type="text/javascript">
	
jQuery(document).ready(function(){
			jQuery("select[name=Tipo]").change(function(){
		
		if (jQuery('select[name=Tipo]').val() == '1') {
            jQuery("#div_pax1").hide();
			jQuery("#div_pax2").hide();
			jQuery("#div_pax3").hide();		
		    jQuery("#div_pax1").fadeToggle(2000);
			jQuery("#pax1").attr('required', 'true');
			jQuery("#pax2").removeAttr('required');
			jQuery("#pax3").removeAttr('required');
			jQuery("#pax2").attr('disabled', 'true');
			jQuery("#pax3").attr('disabled', 'true');
		   }
		   
		if (jQuery('select[name=Tipo]').val() == '2') {
           jQuery("#div_pax1").hide();
		    jQuery("#div_pax2").hide();
			 jQuery("#div_pax3").hide();
		  
		  
		   jQuery("#div_pax1").fadeToggle(2000);
		   jQuery("#div_pax2").fadeToggle(2000);
		   jQuery("#pax2").attr('required', 'true');
		   jQuery("#pax3").removeAttr('required');
		    jQuery("#pax2").removeAttr('disabled');
        	jQuery("#pax3").attr('disabled', 'true');
		   }
		   
		   	if (jQuery('select[name=Tipo]').val() == '3') {
          jQuery("#div_pax1").hide();
		   jQuery("#div_pax2").hide();
			 jQuery("#div_pax3").hide();
			 
		   jQuery("#div_pax1").fadeToggle(2000);
		    jQuery("#div_pax2").fadeToggle(2000);
			jQuery("#div_pax3").fadeToggle(2000);
			jQuery("#pax2").attr('required', 'true');
			jQuery("#pax3").attr('required', 'true');
			jQuery("#pax2").removeAttr('disabled');
			jQuery("#pax3").removeAttr('disabled');
		   }
			});
			 
			 
 jQuery(function() {
   jQuery( ".fechana" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
  });
		});
		</script>
    <table width="100%" border="0" cellspacing="3" cellpadding="3" class="central"><tr><td colspan="2"> 
    <label for="Tipo" class="">Habitación</label>
    <select name="Tipo" id="Tipo"  class="ewd-feup-select">
  <option value="0">Elije una Opcion</option>
  <option value="1">SGL</option>
  <option value="2">DBL</option>
  <option value="3">TRP</option>   
</select>
    </td>
    <td width="33%"><label>Categoria:</label>
        <select name="Categoria" id="Categoria" onchange="activa_boton(this,this.form.Submit2)"  class="ewd-feup-select" style="width:80%;">
          <option value="Unica">Unica</option>
            </select>
      <?php		    ?>
    </td></tr>

  <tr>
    <td width="33%" align="left">Nombre Pax:</td>
    <td width="33%" align="left">Descuento:</td>
    <td width="33%" align="left">Fecha de Nacimiento:</td>
  </tr>
<tr  id="div_pax1" style="display:none;">
<td align="center" width="33%"><input name="pax1" type="text" id="pax1" size="30"  class=""  required style="width:100%;"/></td>
<td align="center" width="33%"><select name="Edad1" id="Edad1"  class="ewd-feup-select" style="width:80%;">
<option value="Sin Descuento" selected="selected"> Sin Descuento </option>
<option value="Menor">Menor</option>
<option value="Mayor">3ra. Edad (+65)</option>
</select></td>
<td align="center" width="33%"> 
            
           <select name="pax1_dia" class="ewd-feup-select" style="width:30%;"><?php foreach($dias as $dkey => $diass){ ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option><?php  }  ?></select>
             <select name="pax1_mes" class="ewd-feup-select" style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option><?php  }  ?></select>
           <select name="pax1_anio" class="ewd-feup-select" style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
       </td></tr>
       
       <tr id="div_pax2" style="display:none;">
            <td align="center" width="33%"><input name="pax2" type="text"  class="" id="pax2" size="30"  style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad2" id="Edad2"  class="ewd-feup-select"  style="width:80%;">
              <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%"> 
            
           <select name="pax2_dia" class="ewd-feup-select" style="width:30%;"><?php foreach($dias as $dkey => $diass){ ?>
           <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option><?php  }  ?></select>         
           <select name="pax2_mes" class="ewd-feup-select"  style="width:30%;"><?php foreach($meses as $mkey => $mes){?>
           <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option><?php  }  ?></select>
           <select name="pax2_anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
       </td> 
          </tr>
          
          <tr id="div_pax3" style="display:none;">
            <td align="center" width="33%"><input name="pax3" type="text" id="pax3" size="30"  style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad3" id="Edad3" class="ewd-feup-select"  style="width:80%;">
               <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%"> 
           
           <select name="pax3_dia" class="ewd-feup-select" style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>
             
           </select>
         
           <select name="pax3_mes" class="ewd-feup-select" style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
          
           <select name="pax3_anio" class="ewd-feup-select" style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
        </td>
            </tr></table>
   </td></tr>
   
   <tr id="habitaciones2" style="display:none;"><td>
 
    <script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("select[name=Tipo2]").change(function(){
		
		if (jQuery('select[name=Tipo2]').val() == '1') {
            jQuery("#div_pax1_2").hide();
		    jQuery("#div_pax2_2").hide();
			
		   jQuery("#div_pax1_2").fadeToggle(2000);
		   jQuery("#pax1_2").attr('required', 'true');
		   jQuery("#pax2_2").removeAttr('required');
		   	jQuery("#pax2_2").attr('disabled', 'true');
		   }
		   
		if (jQuery('select[name=Tipo2]').val() == '2') {
           jQuery("#div_pax1_2").hide();
		    jQuery("#div_pax2_2").hide();
					  
		   jQuery("#div_pax1_2").fadeToggle(2000);
		    jQuery("#div_pax2_2").fadeToggle(2000);
			jQuery("#pax1_2").attr('required', 'true');
			jQuery("#pax2_2").attr('required', 'true');
			 jQuery("#pax2_2").removeAttr('disabled');
		   }
		   
		   	
			});
		});
		</script>
    <table width="100%" border="0" cellspacing="3" cellpadding="3" class="central">
        <tr>
    <td colspan="2">    
        <label >Habitación</label>
       <select name="Tipo2" id="Tipo2"  class="ewd-feup-select">
  <option value="0">Elije una Opcion</option>
  <option value="1">SGL</option>
  <option value="2">DBL</option>
        </select>
       
    </td>
    <td width="33%" >
    <label> Categoria: </label>
       <select name="Categoria2" id="Categoria2"  class="ewd-feup-select" style="width:80%;">
            <option value="Unica" >Unica</option>          
          </select>
 
    </td> </tr>

  <tr>
    <td width="33%" align="left" >Nombre Pax:</td>
    <td width="33%" align="left" >Descuento:</td>
    <td width="33%" align="left" >Fecha de Nacimiento:</td>
  </tr>
  
  <tr id="div_pax1_2" style="display:none;">
            <td align="center" width="33%"><input name="pax1_2" type="text"  class="" id="pax1_2"  style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad1_2" id="Edad1_2"  class="ewd-feup-select" style="width:80%;">
              <option value="Sin Descuento" selected="selected"  class="form-control input-sm"> Sin Descuento </option>
           <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
             <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%">
            
           <select name="pax1_2dia" class="ewd-feup-select" style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>
             
           </select>
      
           <select name="pax1_2mes" class="ewd-feup-select"  style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
        
           <select name="pax1_2anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
        </td>
           
          </tr>
          
          <tr id="div_pax2_2" style="display:none;">
            <td align="center" width="33%"><input name="pax2_2" type="text" class="" id="pax2_2" style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad2_2" id="Edad2_2"  class="ewd-feup-select"  style="width:80%;">
              <option value="Sin Descuento" selected="selected"  class=""> Sin Descuento </option>
            <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%">
             
           <select name="pax2_2dia" class="ewd-feup-select"  style="width:30%;">
              <?
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>
             
           </select>
          
           <select name="pax2_2mes" class="ewd-feup-select"  style="width:30%;" >
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
          
           <select name="pax2_2anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
             
     </td></tr></table>
     </td></tr>
     <tr id="habitaciones3" style="display:none;"><td>       
      
    <script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("select[name=Tipo3]").change(function(){
		
		if (jQuery('select[name=Tipo3]').val() == '1') {
            jQuery("#div_pax1_3").hide();
		    jQuery("#div_pax2_3").hide();
			 
		   jQuery("#div_pax1_3").fadeToggle(2000);
		   jQuery("#pax1_3").attr('required', 'true');
		   jQuery("#pax2_3").removeAttr('required');
		   	jQuery("#pax2_3").attr('disabled', 'true');
		   }
		   
		if (jQuery('select[name=Tipo3]').val() == '2') {
           jQuery("#div_pax1_3").hide();
		    jQuery("#div_pax2_3").hide();
					  
		   jQuery("#div_pax1_3").fadeToggle(2000);
		    jQuery("#div_pax2_3").fadeToggle(2000);
			jQuery("#pax1_3").attr('required', 'true');
			jQuery("#pax2_3").attr('required', 'true');
			jQuery("#pax2_3").removeAttr('disabled');
		   }
		   
		   	
			});
		});
		</script>
    <table width="100%" border="0" cellspacing="3" cellpadding="3" class="central">
        <tr>
    <td colspan="2">
      
        <label> Habitación</label>
           <select name="Tipo3" id="Tipo3"  class="ewd-feup-select">
            <option value="0">Elije una Opcion</option>
            <option value="1">SGL</option>
            <option value="2">DBL</option>
            </select>
         
    </td>
    <td width="33%">
   <label>Categoria</label>
 <select name="Categoria3" id="Categoria3" onchange="activa_boton(this,this.form.Submit2)"  class="ewd-feup-select" style="width:80%;">
<option value="Unica">Unica</option>
</select> </td>
      </tr>

  <tr>
    <td width="33%" align="left" >Nombre Pax:</td>
    <td width="33%" align="left" >Descuento:</td>
    <td width="33%" align="left" >Fecha de Nacimiento:</td>
  </tr>
<tr id="div_pax1_3" style="display:none;">
            <td align="center" width="33%"><input name="pax1_3" type="text"  class="" id="pax1_3"  style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad1_3" id="Edad1_3"  class="ewd-feup-select" style="width:80%;">
               <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%"> 
           <select name="pax1_3dia" class="ewd-feup-select"  style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>
             
           </select>
           <select name="pax1_3mes" class="ewd-feup-select"   style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
           <select name="pax1_3anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
       </td>
           
          </tr>
          <tr id="div_pax2_3" style="display:none;">
            <td align="center" width="33%"><input name="pax2_3"  id="pax2_3" type="text"   style="width:100%;"/></td>
            <td align="center" width="33%">
            <select name="Edad2_3" id="Edad2_3"  class="ewd-feup-select" style="width:80%;">
              <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
             <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%"> 
           <select name="pax2_3dia" class="ewd-feup-select"    style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>
             
           </select>
        
           <select name="pax2_3mes" class="ewd-feup-select"   style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
        
           <select name="pax2_3anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
      </td>
            
          </tr></table>
         
   </td></tr>
   <tr id="habitaciones4" style="display:none;"><td>
       
    <script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("select[name=Tipo4]").change(function(){
		
		if (jQuery('select[name=Tipo4]').val() == '1') {
            jQuery("#div_pax1_4").hide();
		    jQuery("#div_pax2_4").hide();
			jQuery("#div_pax1_4").fadeToggle(2000);
			jQuery("#pax1_4").attr('required', 'true');
		   jQuery("#pax2_4").removeAttr('required');
		   	jQuery("#pax2_4").attr('disabled', 'true');
		   }
		   
		if (jQuery('select[name=Tipo4]').val() == '2') {
           jQuery("#div_pax1_4").hide();
		    jQuery("#div_pax2_4").hide();
			  
		  
		  jQuery("#div_pax1_4").fadeToggle(2000);
		    jQuery("#div_pax2_4").fadeToggle(2000);
			jQuery("#pax1_4").attr('required', 'true');
			jQuery("#pax2_4").removeAttr('disabled');
		   jQuery("#pax2_4").attr('required', 'true');
		   jQuery("#pax2_4").removeAttr('disabled');
		   }
		   
		   	
			});
		});
		</script>
    <table width="100%" border="0" cellspacing="3" cellpadding="3" class="central">
        <tr><td colspan="2">
       <label>Habitación</label>
        <select name="Tipo4" id="Tipo4"  class="ewd-feup-select">
  <option value="0">Elije una Opcion</option>
  <option value="1">SGL</option>
  <option value="2">DBL</option>
 
            </select>
    </td>
    <td width="33%">
   <label> Categoria</label>
    <select name="Categoria4" id="Categoria4" onchange="activa_boton(this,this.form.Submit2)"  class="ewd-feup-select"  style="width:80%;">
           <option value="Unica">Unica</option>
            </select>
    </td>
   </tr>

  <tr>
    <td width="33%" align="left" >Nombre Pax:</td>
    <td width="33%" align="left" >Descuento:</td>
    <td width="33%"  align="left">Fecha de Nacimiento:</td>
  </tr><tr id="div_pax1_4" style="display:none;">
            <td align="center" width="33%"><input name="pax1_4" type="text" id="pax1_4"  style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad1_4" id="Edad1_4"  class="ewd-feup-select" style="width:80%;">
              <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%"> 
           <select name="pax1_4dia" class="ewd-feup-select"  style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>
             
           </select>
           <select name="pax1_4mes" class="ewd-feup-select"   style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
            <select name="pax1_4anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
      </td>
           
          </tr>
          <tr id="div_pax2_4" style="display:none;">
            <td align="center" width="33%"><input name="pax2_4" type="text" id="pax2_4" style="width:100%;"/></td>
            <td align="center" width="33%"><select name="Edad2_4" id="Edad2_4"  class="ewd-feup-select" style="width:80%;">
             <option value="Sin Descuento" selected="selected"> Sin Descuento </option>
              <option value="Menor">Menor</option>
              <option value="Mayor">3ra. Edad (+65)</option>
            </select></td>
            <td align="center" width="33%"> 
           <select name="pax2_4dia" class="ewd-feup-select"  style="width:30%;">
              <?php
		     foreach($dias as $dkey => $diass)
               { ?>
             <option value="<?php echo $diass;?>" <?php echo (01 == $dkey) ? " selected" : ""; ?>><?php echo $diass; ?></option>
             <?php  }  ?>
             
           </select>
           <select name="pax2_4mes" class="ewd-feup-select"   style="width:30%;">
            <?php foreach($meses as $mkey => $mes){?>
            <option value="<?php echo $mkey;?>" <?php echo (1 == $mkey) ? " selected" : ""; ?>><?php echo $mes; ?></option>
            <?php  }  ?>
           </select>
           <select name="pax2_4anio" class="ewd-feup-select"  style="width:30%;">
           <?php  foreach($anios as $akey => $anio)  { ?>
              <option value="<?php echo $anio;?>" <?php echo (1980 == $akey) ? " selected" : ""; ?>><?php echo $anio; ?></option>
          <?php } ?>
              </select>
        </td>
            
          </tr></table>
         

            </td>
  </tr>
      <tr align="center" >
        <td align="right" ><label>Comentarios:</label>
          <textarea name="Comentarios"  rows="4" id="Comentarios"  style="width:100%;"><?php=$Comentarios?></textarea><br /><br /><center>
          <input name="Submit2" id="Submit2" type="submit" value="Bloquear Espacio"/> 
          <input type="button" name="Button2" value="Limpiar"  onclick="location.href='#'" />     </center> </td>
      </tr>
      </table>
</form>

<?php } else {
	echo "Debes estar registrado para poder hacer  bloqueos.";
	}?>
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