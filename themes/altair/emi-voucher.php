<?php
/**
 * Template Name: Voucher Emisor
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
	    		<? //Inicializamos la sesión
session_start();
//--------------------
 
global $wpdb;
    	 
// $link = mysqli_connect("localhost", "root", "lgslgs666");
//mysqli_select_db($link, "viajesvi_2013");

//$busqueda= mysql_query("SELECT * FROM voucher WHERE folio='$folio'"); 
$query = $wpdb->get_results("SELECT * FROM vvt_voucher WHERE idvoucher='".$_GET[folio]."'", ARRAY_A );
$datos= $wpdb->num_rows;
 $folio=$_GET[folio];
//$datos = mysql_query("SELECT * FROM voucher WHERE idvoucher='$folio'");
//$row2=mysql_fetch_array($query);
if($datos>0) { // ó " !=0 " como se quiera ver 
      // si existe no hago nada:  
	  	 
?>
<?php
function dameURL(){
$url1="https://".$_SERVER['HTTP_HOST']."/wp-content/plugins/emi-voucher/";
return $url1;
}
$url1= dameURL();
?>
<script type="text/javascript">
var j_accion="solograba";
var objetivo="_self"

function valida()
{
document.getElementById("accion").value=j_accion

if (j_accion=="solograba") j_mensaje="Confirma la actualización de los datos del Voucher No. <?= sprintf("%08d",$_GET[folio]) ?>?"
if (j_accion=="duplicar") j_mensaje="Confirma que desea DUPLICAR la información del folio <?= sprintf("%08d",$_GET[folio]) ?> en uno nuevo?"
if (j_accion=="PDF") j_mensaje="Confirma que desea generar nuevamente el archivo PDF para este Voucher?"
if (j_accion=="cancelar") j_mensaje="Confirma que desea CANCELAR el Voucher No. <?= sprintf("%08d",$_GET[folio]) ?>?"

if (j_accion=="PDF") objetivo="_blank";
if (j_accion=="solograba") objetivo="_self";

if(document.getElementById("codigo1").value.length<2)
			      {                                	
  				 	alert("El Codigo debe tener mas de 2 Caracteres")
					document.getElementById("codigo1").focus()
					return;
                  } 



if (confirm(j_mensaje))
	{
	document.form1.action="<?=$url1?>voucher_actualizar.php"
	document.form1.target=objetivo; 
	document.form1.submit();
		} 
}
			
</script>

<div style="background:url(<?=$query[0]["folio"]=="CANCELADO"?"".$url1."images/cancelado.gif":''?>)">
<form action="#" method="post" id="form1" name="form1"  enctype="multipart/form-data">
<input type="hidden" name="accion" id="accion" />
<input type="hidden" name="URLretorno" id="URLretorno" value="<?=$_GET[URLretorno]?>">
<input type="hidden" name="folio" id="folio" value="<?=$_GET[folio]?>" />
   <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" rules="none" class="table table-hover table-condensed">
    <tr>
      <td width="27%" align="center" valign="middle" class="logo_voucher"><img src="<?=$url1?>images/voucher_logo.png" width="180px" height="80px" /></td>
      <td width="43%" valign="middle" class="titulos_DATOS_voucher">Reg. Nal. Turismo 4000476 R.F.C. VTA 931112Q19<br />
        CASA MATRIZ: Rio Bravo N. 6 Col. Vista Hermosa,<br /> Cuernavaca, Morelos, México.C.P. 62290.<br />
        Conmutador: 01 (777)313 56 03 (10 Lineas)<br />
        Fax: 01 (777)311 38 96 Tel: 01 800 021 8492<br />
        e-mail: vivatours@prodigy.net.mx - www.viajesvivatours.com</td>
      <td width="30%" valign="top" class="logo">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="30px" valign="middle" class="titulos_DATOS_voucher" align="center" style="text-align:center;"> BONO DE SERVICIOS / VOUCHER</td>
        </tr>
        <tr>
          <td height="50px" align="center"  class="folios_voucher" style="text-align:center; vertical-align:middle;"><? //sprintf("%08d",$folio)?>
           <?=$query[0]["folio"]=="CANCELADO"?'<font style="text-decoration:line-through;">'. sprintf("%08d",$folio).'</font>':''.sprintf("%08d",$folio).''?></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-bordered table-hover table-condensed">
    <tr>
      <td width="27%" valign="top">
      <table width="220px" border="1" align="left" cellpadding="0" cellspacing="0" rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td width="110px" class="">TOUR CODIGO / CODE</td>
          <td width="110px" class="" >FECHA / DATE</td>
        </tr>
        <tr>
          <td class=""><input name="codigo1" type="text" id="codigo1" size="12" value="<?=$query[0]["codigo1"]?>" /></td>
          <td class=""><input name="codigo2" type="text" id="codigo2" size="12" value="<?=$query[0]["codigo2"]?>"  /></td>
        </tr>
      </table></td>
      <td valign="top" width="35%">
      <table width="350px" border="1" cellspacing="0" cellpadding="0" rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td class="">CONFIRMADO POR / CONFIRMED BY</td>
        </tr>
        <tr>
          <td  class=""><input name="codigo3" type="text" id="codigo3" size="50" value="<?=$query[0][codigo3]?>" /></td>
        </tr>
      </table></td>
      <td width="38%" rowspan="4" align="center" valign="top">
      
      <table  border="1" cellpadding="0" cellspacing="0" rules="none" class="table table-bordered table-hover table-condensed">
        <tr><td colspan="6"  class="">DISTRIBUCION HAB. / ROOMS TYPE AND NO.</td></tr>
        <tr>
          <td class="">DBL</td>
          <td class="">TWN</td>
          <td  class="">TPL</td></tr>
          <tr>
        <tr>
          <td class=""><input name="codigo14" type="text" class="input_voucher" id="codigo14" size="5" value="<?=$query[0][codigo14]?>"/></td>
          <td class=""><input name="codigo4" type="text" class="input_voucher" id="codigo4" size="5" value="<?=$query[0][codigo4]?>"/></td>
          <td class=""><input name="codigo5" type="text" class="input_voucher" id="codigo5" size="5" value="<?=$query[0][codigo5]?>"/></td></tr><tr>
          <td  class="">SGL</td>
          <td  class="">OTHER</td>
          <td  class="">TOTAL PAX.</td>
          </tr>
          <td class=""><input name="codigo6" type="text" class="input_voucher" id="codigo6" size="5" value="<?=$query[0][codigo6]?>"/></td>
          <td class=""><input name="codigo7" type="text" class="input_voucher" id="codigo7" size="5" value="<?=$query[0][codigo7]?>"/></td>
          <td class=""><input name="codigo8" type="text" class="input_voucher" id="codigo8" size="5" value="<?=$query[0][codigo8]?>"/></td>
          </tr>
          <tr>
            <td  colspan="6" align="center" class="">ESPECIFICACIONES / SPECIFICATIONS</td>
          </tr>
          <tr>
            <td  colspan="6" align="center"><input name="codigo15" type="text" id="codigo15" size="30" value="<?=$query[0][codigo15]?>"></td>
          </tr>
      </table>
    
      <table width="242" border="1" align="center" cellpadding="0" cellspacing="1"  rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td width="236" class="">PAGADERO POR / PAYMENT THROUGH</td>
        </tr>
        <tr>
          <td class=""><input name="codigo12" type="text" id="codigo12" size="30" value="<?=$query[0][codigo12]?>"></td>
        </tr>
            <tr>
            <td>
				<table width="220px" border="1" align="center" cellpadding="0" cellspacing="1"  rules="none">
      <tr>
        <td width="220px"  class="">RESERVADO POR / RESERVED BY</td>
        </tr>
      <tr>
        <td class=""><input name="codigo13" type="text" id="codigo13" size="30" value="<?=$query[0][codigo13]?>"></td>
        </tr>
    </table>            
            </td>
            </tr>
            
            <tr><td>
            	<table width="220px" border="1" align="center" cellpadding="0" cellspacing="0"  rules="none">
      <tr>
        <td width="197px" class="">SELLO Y FIRMA / STAMP AND SIGNATURE</td>
        </tr>
      <tr>
        <td height="126" align="center" style="text-align:center; vertical-align:middle;">
		<img src="<?=$url1?>qrcode/<?=$folio?>.png">
		</td>
        </tr>
    </table>
            
            </td></tr>
      </table>
        </td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="0" cellpadding="0" rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td class="">NOMBRE DEL CLIENTE - GRUPO / CLIENTS - GROUP NAME</td>
          </tr>
        <tr>
          <td class=""><input name="codigo9" type="text" id="codigo9" size="87" value="<?=$query[0][codigo9]?>" /></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="0" cellpadding="0"  rules="none">
        <tr>
          <td  class="">PRESTATARIO / SUPPLIER DIRECCION / ADDRESS CIUDAD / CITY TELEF. /PHONE</td>
          </tr>
        <tr>
          <td class="">
		  <input name="codigo10" type="text" id="codigo10" size="20" value="<?=$query[0][codigo10]?>" />&nbsp;
          <input name="codigo11" type="text" id="codigo11" size="50" value="<?=$query[0][codigo11]?>" />
		  </td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top">    
        <table width="569px" border="1" cellspacing="2" cellpadding="0"  rules="none" class="table table-bordered table-hover table-condensed">
          <tr>
            <td colspan="2" class="">FAVOR DE FACILITAR SIGUIENTES SERVICIOS / KINDLY PROVIDE FOLLOWING SERVICES.</td>
          </tr>
          <tr>
            <td class="">FECHAS / DATES</td>
            <td class="">DESCRIPCION DE SERVICIOS / SERVICES DESCRIPTION</td>
          </tr>
          <tr>
            <td width="120px" valign="top" align="center" class="">
			  <input name="fechas" type="text" class="" id="fechas"  size="15" value="<?=$query[0][fechas]?>" /><br />
              <input name="fechas2" type="text" class="" id="fechas2" size="15" value="<?=$query[0][fechas2]?>" /><br />
              <input name="fechas3" type="text" class="" id="fechas3" size="15" value="<?=$query[0][fechas3]?>" /><br />
              <input name="fechas4" type="text" class="" id="fechas4" size="15" value="<?=$query[0][fechas4]?>" /><br />
              <input name="fechas5" type="text" class="" id="fechas5" size="15" value="<?=$query[0][fechas5]?>" /><br />
              <input name="fechas6" type="text" class="" id="fechas6" size="15" value="<?=$query[0][fechas6]?>" /><br />
              <input name="fechas7" type="text" class="" id="fechas7" size="15" value="<?=$query[0][fechas7]?>" /><br />
              <input name="fechas8" type="text" class="" id="fechas8" size="15" value="<?=$query[0][fechas8]?>" /><br /></td>
            <td width="437" align="left" class="" valign="top">
			  <input name="descripciones" type="text" class="" id="descripciones" value="<?=$query[0][descripciones]?>" size="80" /><br />
              <input name="descripciones2" type="text" class="" id="descripciones2" value="<?=$query[0][descripciones2]?>" size="80" /><br />
              <input name="descripciones3" type="text" class="" id="descripciones3" value="<?=$query[0][descripciones3]?>" size="80" /><br />
              <input name="descripciones4" type="text" class="" id="descripciones4" value="<?=$query[0][descripciones4]?>" size="80" /><br />
              <input name="descripciones5" type="text" class="" id="descripciones5" value="<?=$query[0][descripciones5]?>" size="80" /><br />
              <input name="descripciones6" type="text" class="" id="descripciones6" value="<?=$query[0][descripciones6]?>" size="80" /><br />
              <input name="descripciones7" type="text" class="" id="descripciones7" value="<?=$query[0][descripciones7]?>" size="80" /><br />
              <input name="descripciones8" type="text" class="" id="descripciones8" value="<?=$query[0][descripciones8]?>" size="80" /></td>
          </tr>
      </table></td>
    </tr>
  </table>
  
<br>

<table width="550%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-borderless table-hover table-condensed">
  <tr>
    <td width="100" align="center"><input type="button" name="button2" id="button2" value="<- Back" onClick="javascript:history.go(-1)"></td>
     <? //if ($query[0][folio]!="CANCELADO") { ?>
    <td width="100" align="center"><button type="button" class="button"  name="solograba" onClick="j_accion='solograba'; objetivo='_self'; valida();">Actualizar </button> </td>    
    <td width="100" align="center"><button type="button" class="button" name="PDF" onClick="j_accion='PDF'; valida();">Generar PDF</button></td>
    <td width="100" align="center"><button type="button" class="button" name="duplicar" onClick="j_accion='duplicar'; objetivo='_self'; valida();">Nuevo Folio</button></td>
    <td width="150" align="center"><button type="button" class="button" name="cancela" onClick="j_accion='cancelar'; objetivo='_self'; valida();">Cancelar Voucher </button> </td>
     <? //} ?>
  </tr>
</table>
</form></div>
<?
//==============================================================
//==============================================================
//==============================================================
/*
include("pdf/mpdf.php");
$mpdf = new mPDF('',    // mode - default ''
 '',    // format - A4, for example, default ''
	 0,     // font size - default 0
	 'arial',    // default font family
	 15,    // margin_left
	 4,    // margin right
	 6,     // margin top
	 0,    // margin bottom
	 0,     // margin header
	 0,     // margin footer
	 'L');  // L - landscape, P - portrait
$mpdf->defaultheaderfontsize=8;
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
*/
//echo $html;
//==============================================================
//==============================================================
//==============================================================

 } else { 
//si no existe registro el baucher
echo "El Voucher No. $folio No existe";
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