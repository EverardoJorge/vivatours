<?php
define('WP_USE_THEMES', false);
require('../../../wp-blog-header.php');
global $is_no_header;
$is_no_header = TRUE;
get_header(); 

//require( '../../../wp-load.php' );
$my_id = $_GET[id];
$post = get_post($my_id);
$content = $post->post_content;
add_shortcode( 'pdfword', '__return_false' );
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]>', $content);
$titulo = $post->post_title;
$correo_envia = do_shortcode('[user-data]');

do_shortcode('[tg_header]');
//$datos_usuario=do_shortcode('[datosusuario]');
do_shortcode('[restricted no_message="Yes"]');
do_shortcode('[/restricted]');


?>

<!-- Begin content -->
<?php $html='<div style="width:90%;margin:auto;">';
	$html.='<h2 class="ppb_title">'. $post->post_title.'</h2>';
    $html.='<div align="left" style="text-align:left !important;">'. $content.'</div>';?>
    <?php	
	$html.='</div>';?>

<?php //////////////
if ($_GET[accion]=="word") {
	global $is_no_header;
$is_no_header = TRUE;
get_header(); 
$Titulo = $post->post_title; 
header("Content-type: application/vnd.ms-word");
header("Content-Type: application/msword; charset=utf-8"); 
header("Content-Disposition: attachment;Filename=".$Titulo.".doc");
$html_word= "<html>";
$html_word.= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
$html_word.= "<style type='text/css'>
body {	font-family: 'Arial';
font-size:10pt;
margin:0px;
padding:0px;
}
#mobile_main_menu{display = 'none'}

 p.MsoNormal, li.MsoNormal, div.MsoNormal
    {margin:0cm;
    margin-bottom:.0001pt;
    font-size:12.0pt;
    font-family:'Arial';}
@page Section1
    {size:595.3pt 841.9pt;
    margin:1.0cm 30.5pt 1.0cm 1.0cm;}
div.Section1
    {page:Section1;}


</style>";
$html_word.= "<body><div class=Section1>";

/*$html_word.="<p style='font-size:12px; font-family:Arial; font-style:oblique; color:#FF0000;'><strong>Instrucciones:</strong>  Si lo deseas, para editar este Word, puedes cambiar el modo de visualizaci�n a vista de impresi�n en el men� de vista de Word.</p>
<br>";*/
$html_word.= $html;

echo $html_word;
$html_word.="</div></body></html>";
 } ?>

<?php if ($_GET[accion]=="pdf") {
	global $is_no_header;
$is_no_header = TRUE;
get_header(); 
//==============================================================
$html_pdf=$html;
 ob_end_clean();

include("pdf/mpdf.php");

$mpdf = new mPDF('',    // mode - default ''

 'Letter',    // format - A4, for example, default ''

	 10,     // font size - default 0

	 'Arial',    // default font family

	 10,    // margin_left

	 10,    // margin right

	 6,     // margin top

	 0,    // margin bottom

	 0,     // margin header

	 0,     // margin footer

	 'L');  // L - landscape, P - portrait

$mpdf->WriteHTML($stylesheet,1);
//$mpdf->defaultheaderfontsize=8;
$mpdf->SetTitle($titulo);
$mpdf->WriteHTML($html_pdf);
$mpdf->Output();
exit;
 }?>
 

 <?php
 if ($_GET[accion]=="Enviar") {

$html=$html;
if ($_POST){
	$html2=$_POST['mail_mensaje'];
	
if($correo_envia!=''){
	$de=$correo_envia; }else{ $de = "agencias@viajesvivatours.com";}

$headers = "MIME-Version: 1.0 \r\n"; 
$headers .= "Content-type: text/html; charset=UTF-8 \r\n"; 

//direcci�n del quien  envia 
$headers .= "From: Viajes Vivatours - <".$de."> \r\n"; 

//direcciones que recibir�n copia oculta 
$headers .= "Bcc: Viajes Vivatours <agencias@viajesvivatours.com> \r\n";
//mando el correo...
$para = "Viajes Vivatours - < ".$_POST['mail_destino']."> \r\n";
$titulo = "Itinerario ". strip_tags($titulo);
if(mail($para,$titulo,$html2.$html, $headers)){
	$Mensaje_ok="El itinerario ha sido enviado con �xito";
	
}
else{
	$Mensaje_ok="El itinerario no se a podido enviar, por favor intentelo de nuevo.";
	}

}
 ?>
<title>Viajes Vivatours</title>
<?php if(isset($_POST['mensajeok'])){ ?><br />
 <div class='alert alert-success alert-dismissible' role='alert' align="center"><strong>Itinerario Enviado</strong></div>
 <div class="jumbotron" align="center">
   <p><?php=$_POST[Mensaje_ok]?></p>
  <p><a class="btn btn-primary btn-lg" href="https://www.viajesvivatours.com/" role="button">Continuar</a></p>
</div>



<br />
 <?php } else {?><br />
<form action="" method="post" class="form-horizontal">
<input name="mensajeok" type="hidden" value="1" />
<input name="id" type="hidden" value="<?php=$my_id?>"/>
<input name="enviar_programa" type="hidden" value="enviar_programa" />
   <input name="accion" type="hidden" value="enviar" /><table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="parrafo_tabla" style="border:1px solid 
   #666;">
  <tr>
    <th colspan="2" align="center" valign="middle" class="tdsubtitulos">Enviar Itinerario</th>
    </tr>
  <tr>
    <td colspan="2" align="right"><div class="form-group">
     <label for="mail_destino" class="col-sm-3 col-xs-12  control-label">Enviar a:</label>
     <div class=" col-xs-12 col-sm-8">    
       <input name="mail_destino" type="email" id="mail_destino" size="50" class="form-control input-sm"  placeholder="Para..." value="<?php echo $usuario;?>" required></div></div>
    
     <div class="form-group"> <label for="mail_destino" class="col-xs-12 col-sm-3 control-label">Mensaje:</label><div class="col-xs-12 col-sm-8"> 
      <textarea name="mail_mensaje" id="mail_mensaje" cols="20" rows="5" placeholder="Mensaje..."  class="form-control input-sm" ><?php echo $mail_mensaje;?></textarea>
     </div> </div><?php

?></td>
  </tr>
  <tr>
    <td colspan="2" align="center">  <input type="submit" name="enviar_itinerario" id="enviar_itinerario"  value="Enviar Itinerario" class="btn btn-primary "  style="
    background-color: #e50076;
    border-color: #e50076;
" > 
    
    
      
      </td>
  </tr>
  </table>
</form><?php } ?>
<?php } 
//fin de eviar
?>