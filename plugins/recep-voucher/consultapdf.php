<?php
//Inicializamos la sesión
//revisado: cambie los'<?' por '<?php'
session_start();
//--------------------
require_once("conexion.php");
$db=conectar();
    //registro el baucher
	//--------------------
	//------REviso que no exista el folio-----//
//$busqueda= mysql_query("SELECT * FROM voucher WHERE folio='$folio'"); 
$datos = mysql_query("SELECT * FROM vvt_voucher_recep WHERE idvoucher='".$_GET[folio]."'");
$row2=mysql_fetch_array($datos);
if(mysql_num_rows($datos)>0) { // ó " !=0 " como se quiera ver 
      // si existe no hago nada:  
	  	 $folio=$_GET[folio];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="voucher.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
var j_accion="solograba";
var objetivo="_self"

function valida()
{
document.getElementById("accion").value=j_accion
if (j_accion=="solograba") j_mensaje="Confirma la actualización de los datos del Voucher No. <?php= sprintf("%08d",$folio) ?>?"
if (j_accion=="duplicar") j_mensaje="Confirma que desea DUPLICAR la información del folio <?php= sprintf("%08d",$folio) ?> en uno nuevo?"
if (j_accion=="PDF") j_mensaje="Confirma que desea generar nuevamente el archivo PDF para este Voucher?"
if (j_accion=="cancelar") j_mensaje="Confirma que desea CANCELAR el Voucher No. <?php= sprintf("%08d",$folio) ?>?"

if (j_accion=="PDF") objetivo="_blank";

if(document.getElementById("codigo1").value.length<2)
			      {                                	
  				 	alert("El Codigo debe tener mas de 2 Caracteres")
					document.getElementById("codigo1").focus()
					return;
                  } 



if (confirm(j_mensaje))
	{
	document.form1.action="voucher_actualizar.php"
	document.form1.target=objetivo 
	document.form1.submit()
	if (j_accion=="PDF") { location.href="<?php=$URLretorno?>" }
	} 
}
			
</script>

</head>
<body topmargin="0" <?php=$row2["folio"]=="CANCELADO"?"background='images/cancelado.gif'":''?> style="background-position:center; background-position:top;">
<form action="javascript:valida()" method="post" id="form1" name="form1"  enctype="multipart/form-data">
<input type="hidden" name="accion" id="accion" />
<input type="hidden" name="URLretorno" id="URLretorno" value="<?php=$URLretorno?>">
<input type="hidden" name="folio" id="folio" value="<?php=$folio?>" />
  <table width="800px" border="1" align="center" cellpadding="0" cellspacing="0" rules="none">
    <tr>
      <td width="208px" height="86px" class="logo_voucher" valign="middle" align="center"><img src="Voucher_Logo.jpg" width="180px" height="80px" /></td>
      <td width="401px" class="titulos_DATOS_voucher" valign="middle">Reg. Nal. Turismo 4000476 R.F.C. VTA 931112Q19<br />
        CASA MATRIZ: Xochicalco N. 201 Col. Reforma, <br />Cuernavaca, Morelos, México.C.P. 62260.<br />
        Conmutador: 01 (777)313 56 03 (10 Lineas)<br />
        Fax: 01 (777)311 38 96 Tel: 01 800 021 8492<br />
        e-mail: vivatours@prodigy.net.mx - www.viajesvivatours.com</td>
      <td width="191px" class="logo" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="30px" valign="middle" class="titulos_DATOS_voucher2" align="center"> BONO DE SERVICIOS / VOUCHER</td>
        </tr>
        <tr>
          <td height="50px" align="center"><table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30px">            
              <td height="50px" class="folios_voucher"><?php= sprintf("%08d",$folio) ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="220" valign="top">
      <table width="220px" border="1" align="left" cellpadding="0" cellspacing="0" rules="none">
        <tr>
          <td width="110px" class="titulos_voucher">TOUR CODIGO / CODE</td>
          <td width="110px" class="titulos_voucher" >FECHA / DATE</td>
        </tr>
        <tr>
          <td class="contenido_voucher"><input name="codigo1" type="text" id="codigo1" size="12" value="<?php=stripslashes($row2[codigo1])?>" /></td>
          <td class="contenido_voucher"><input name="codigo2" type="text" id="codigo2" size="12" value="<?php=stripslashes($row2[codigo2])?>"  /></td>
        </tr>
      </table></td>
      <td valign="top" width="350">
      <table width="350px" border="1" cellspacing="0" cellpadding="0" rules="none">
        <tr>
          <td class="titulos_voucher">CONFIRMADO POR / CONFIRMED BY</td>
        </tr>
        <tr>
          <td  class="contenido_voucher"><input name="codigo3" type="text" id="codigo3" size="50" value="<?php=stripslashes($row2[codigo3])?>" /></td>
        </tr>
      </table></td>
      <td width="220PX" rowspan="4" valign="top">
      
      <table width="220px" border="1" cellpadding="0" cellspacing="0" rules="none">
        <tr><td colspan="6"  class="titulos_voucher">DISTRIBUCION HAB. / ROOMS TYPE AND NO.</td></tr>
        <tr>
          <td class="titulos_voucher" width="25px">DBL</td>
          <td class="titulos_voucher" width="25px">TWN</td>
          <td  class="titulos_voucher" width="25px">TPL</td>
          <td  class="titulos_voucher" width="25px">SGL</td>
          <td  class="titulos_voucher" width="25px">OTHER</td>
          <td  class="titulos_voucher" width="25px">TOTAL PAX.</td>
          </tr>
        <tr>
          <td class="contenido_voucher"><input name="codigo14" type="text" class="input_voucher" id="codigo14" size="5" value="<?php=stripslashes($row2[codigo14])?>"/></td>
          <td class="contenido_voucher"><input name="codigo4" type="text" class="input_voucher" id="codigo4" size="5" value="<?php=stripslashes($row2[codigo4])?>"/></td>
          <td class="contenido_voucher"><input name="codigo5" type="text" class="input_voucher" id="codigo5" size="5" value="<?php=stripslashes($row2[codigo5])?>"/></td>
          <td class="contenido_voucher"><input name="codigo6" type="text" class="input_voucher" id="codigo6" size="5" value="<?php=stripslashes($row2[codigo6])?>"/></td>
          <td class="contenido_voucher"><input name="codigo7" type="text" class="input_voucher" id="codigo7" size="5" value="<?php=stripslashes($row2[codigo7])?>"/></td>
          <td class="contenido_voucher"><input name="codigo8" type="text" class="input_voucher" id="codigo8" size="5" value="<?php=stripslashes($row2[codigo8])?>"/></td>
          </tr>
          <tr>
            <td  colspan="6" align="center" class="titulos_voucher">ESPECIFICACIONES / SPECIFICATIONS</td>
          </tr>
          <tr>
            <td  colspan="6" align="center"><input name="codigo15" type="text" id="codigo15" size="30" value="<?php=stripslashes($row2[codigo15])?>"></td>
          </tr>
      </table>
    
      <table width="220px" border="1" align="left" cellpadding="0" cellspacing="1"  rules="none">
        <tr>
          <td width="197px" class="titulos_voucher">PAGADERO POR / PAYMENT THROUGH</td>
        </tr>
        <tr>
          <td class="contenido_voucher"><input name="codigo12" type="text" id="codigo12" size="30" value="<?php=stripslashes($row2[codigo12])?>"></td>
        </tr>
            <tr>
            <td>
				<table width="220px" border="1" align="left" cellpadding="0" cellspacing="1"  rules="none">
      <tr>
        <td width="220px"  class="titulos_voucher">RESERVADO POR / RESERVED BY</td>
        </tr>
      <tr>
        <td class="contenido_voucher"><input name="codigo13" type="text" id="codigo13" size="30" value="<?phpphp=stripslashes($row2[codigo13])?>"></td>
        </tr>
    </table>            
            </td>
            </tr>
            
            <tr><td>
            	<table width="220px" border="1" align="left" cellpadding="0" cellspacing="0"  rules="none">
      <tr>
        <td width="197px" class="titulos_voucher" >SELLO Y FIRMA / STAMP AND SIGNATURE</td>
        </tr>
      <tr>
        <td height="126px" style="text-align:center">
		<img src="qrcode/<?php=$folio?>.png">
		</td>
        </tr>
    </table>
            
            </td></tr>
      </table>
        </td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="0" cellpadding="0" rules="none">
        <tr>
          <td class="titulos_voucher">NOMBRE DEL CLIENTE - GRUPO / CLIENTS - GROUP NAME</td>
          </tr>
        <tr>
          <td class="contenido_voucher"><input name="codigo9" type="text" id="codigo9" size="87" value="<?php=$row2[codigo9]?>" /></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="0" cellpadding="0"  rules="none">
        <tr>
          <td  class="titulos_voucher">PRESTATARIO / SUPPLIER DIRECCION / ADDRESS CIUDAD / CITY TELEF. /PHONE</td>
          </tr>
        <tr>
          <td class="contenido_voucher">
		  <input name="codigo10" type="text" id="codigo10" size="20" value="<?php=stripslashes($row2[codigo10])?>" />&nbsp;
          <input name="codigo11" type="text" id="codigo11" size="50" value="<?php=stripslashes($row2[codigo11])?>" />
		  </td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top">    
        <table width="569px" border="1" cellspacing="2" cellpadding="0"  rules="none">
          <tr>
            <td colspan="2" class="titulos_voucher">FAVOR DE FACILITAR SIGUIENTES SERVICIOS / KINDLY PROVIDE FOLLOWING SERVICES.</td>
          </tr>
          <tr>
            <td class="titulos_voucher">FECHAS / DATES</td>
            <td class="titulos_voucher">DESCRIPCION DE SERVICIOS / SERVICES DESCRIPTION</td>
          </tr>
          <tr>
            <td width="120px" valign="top" align="center" class="contenido_voucher">
			  <input name="fechas" type="text" class="nobord_voucher" id="fechas"  size="15" value="<?php=stripslashes($row2[fechas])?>" /><br />
              <input name="fechas2" type="text" class="nobord_voucher" id="fechas2" size="15" value="<?php=stripslashes($row2[fechas2])?>" /><br />
              <input name="fechas3" type="text" class="nobord_voucher" id="fechas3" size="15" value="<?php=stripslashes($row2[fechas3])?>" /><br />
              <input name="fechas4" type="text" class="nobord_voucher" id="fechas4" size="15" value="<?php=stripslashes($row2[fechas4])?>" /><br />
              <input name="fechas5" type="text" class="nobord_voucher" id="fechas5" size="15" value="<?php=stripslashes($row2[fechas5])?>" /><br />
              <input name="fechas6" type="text" class="nobord_voucher" id="fechas6" size="15" value="<?php=stripslashes($row2[fechas6])?>" /><br />
              <input name="fechas7" type="text" class="nobord_voucher" id="fechas7" size="15" value="<?php=stripslashes($row2[fechas7])?>" /><br />
              <input name="fechas8" type="text" class="nobord_voucher" id="fechas8" size="15" value="<?php=stripslashes($row2[fechas8])?>" /><br /></td>
            <td width="437" align="left" class="contenido_voucher" valign="top">
			  <input name="descripciones" type="text" class="nobord_voucher" id="descripciones" value="<?php=stripslashes($row2[descripciones])?>" size="80" /><br />
              <input name="descripciones2" type="text" class="nobord_voucher" id="descripciones2" value="<?php=stripslashes($row2[descripciones2])?>" size="80" /><br />
              <input name="descripciones3" type="text" class="nobord_voucher" id="descripciones3" value="<?php=stripslashes($row2[descripciones3])?>" size="80" /><br />
              <input name="descripciones4" type="text" class="nobord_voucher" id="descripciones4" value="<?php=stripslashes($row2[descripciones4])?>" size="80" /><br />
              <input name="descripciones5" type="text" class="nobord_voucher" id="descripciones5" value="<?php=stripslashes($row2[descripciones5])?>" size="80" /><br />
              <input name="descripciones6" type="text" class="nobord_voucher" id="descripciones6" value="<?php=stripslashes($row2[descripciones6])?>" size="80" /><br />
              <input name="descripciones7" type="text" class="nobord_voucher" id="descripciones7" value="<?php=stripslashes($row2[descripciones7])?>" size="80" /><br />
              <input name="descripciones8" type="text" class="nobord_voucher" id="descripciones8" value="<?php=stripslashes($row2[descripciones8])?>" size="80" /></td>
          </tr>
      </table></td>
    </tr>
  </table>
  
<br>

<table width="550" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100" align="center"><input type="button" name="button2" id="button2" value="<- Back" onclick="javascript:history.go(-1)"></td>
     <?php if ($row2["folio"]!="CANCELADO") { ?>
    <td width="100" align="center"><button type="submit"  name="solograba" onclick="j_accion='solograba'">Actualizar </button> </td>    
    <td width="100" align="center"><button type="submit"  name="PDF" onclick="j_accion='PDF'">Generar PDF</button></td>
    <td width="100" align="center"><button type="submit"  name="duplicar" onclick="j_accion='duplicar'">Nuevo Folio</button></td>
    <td width="150" align="center"><button type="submit"  name="cancela" onclick="j_accion='cancelar'">Cancelar Voucher </button> </td>
     <?php } ?>
  </tr>
</table>
</form>
</body>
</html>
<?php
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
echo $html;
//==============================================================
//==============================================================
//==============================================================

 } else { 
//si no existe registro el baucher
echo "El Voucher No. $folio No existe";
	  }?>
<?php
?>