<?php 
//Inicializamos la sesión
session_start();
//--------------------
require_once("conexion.php");
$db=conectar();
	// Creamos una conexión con la Base de datos
	//$mysql = new MYSQL;
	//$mysql-> connect();
	//@mysql_query('SET NAMES \'utf8\'');
	//--------------------
    //registro el baucher
if ($_POST[accion]=="solograba" || $_POST[accion]=="PDF") {	

$query_insertar_voucher =  mysql_query("UPDATE vvt_voucher_recep SET
codigo1='".addslashes($_POST['codigo1'])."',
codigo2='".addslashes($_POST['codigo2'])."',
codigo3='".addslashes($_POST['codigo3'])."',
codigo4='".addslashes($_POST['codigo4'])."',
codigo5='".addslashes($_POST['codigo5'])."',
codigo6='".addslashes($_POST['codigo6'])."',
codigo7='".addslashes($_POST['codigo7'])."',
codigo8='".addslashes($_POST['codigo8'])."',
codigo9='".addslashes($_POST['codigo9'])."',
codigo10='".addslashes($_POST['codigo10'])."',
codigo11='".addslashes($_POST['codigo11'])."',
codigo12='".addslashes($_POST['codigo12'])."',
codigo13='".addslashes($_POST['codigo13'])."',
fechas='".addslashes($_POST['fechas'])."',
fechas2='".addslashes($_POST['fechas2'])."',
fechas3='".addslashes($_POST['fechas3'])."',
fechas4='".addslashes($_POST['fechas4'])."',
fechas5='".addslashes($_POST['fechas5'])."',
fechas6='".addslashes($_POST['fechas6'])."',
fechas7='".addslashes($_POST['fechas7'])."',
fechas8='".addslashes($_POST['fechas8'])."',
descripciones='".addslashes($_POST['descripciones'])."',
descripciones2='".addslashes($_POST['descripciones2'])."',
descripciones3='".addslashes($_POST['descripciones3'])."',
descripciones4='".addslashes($_POST['descripciones4'])."',
descripciones5='".addslashes($_POST['descripciones5'])."',
descripciones6='".addslashes($_POST['descripciones6'])."',
descripciones7='".addslashes($_POST['descripciones7'])."',
descripciones8='".addslashes($_POST['descripciones8'])."', 
codigo14='".addslashes($_POST['codigo14'])."',
codigo15='".addslashes($_POST['codigo15'])."' WHERE idvoucher = ".$_POST['folio']."",$db)or die ($query_insertar_voucher.mysql_error());
//echo $query_insertar_voucher;
	//$mysql->insert($query_insertar_voucher);

}

if ($_POST[accion]=="cancelar") {
	$query_cancelar_voucher = mysql_query("UPDATE vvt_voucher_recep SET folio='CANCELADO' WHERE idvoucher = ".$_POST[folio]."",$db)or die ($query_cancelar_voucher.mysql_error());
	//$mysql->insert($query_insertar_voucher);
}

if ($_POST[accion]=="duplicar") {

	$query_insert = mysql_query("insert into vvt_voucher_recep select 0, folio, codigo1, codigo2, codigo3, codigo4, codigo5, codigo6, codigo7, codigo8, 
	codigo9, codigo10, codigo11, codigo12, codigo13,fechas, fechas2, fechas3, fechas4, fechas5, fechas6, fechas7, fechas8,descripciones, descripciones2, descripciones3, descripciones4, descripciones5, descripciones6,descripciones7, descripciones8, codigo14, codigo15 from vvt_voucher_recep WHERE idvoucher = ".$_POST[folio]."",$db)or die ($query_insert.mysql_error());
	//$mysql->insert($query_insert);

	$rs = mysql_query("SELECT @@identity AS id",$db);
    $row = mysql_fetch_row($rs); 
    $folio_Nuevo = trim($row[0]);
 
    require('qrcode/phpqrcode.php');
    $url="https://viajesvivatours.com/wp-content/plugins/recep-voucher/consultapdf.php?folio=".$folio_Nuevo;
    QRcode::png($url, "qrcode/$folio_Nuevo.png",'Q',2,0); 
}

     
if ($_POST[accion]=="PDF") {
	
$html = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="voucher.css" rel="stylesheet" type="text/css">
</head>
<body topmargin="0">
<form id="form1" name="form1" method="post" action="">
  <table width="800px" border="1" align="center" cellpadding="0" cellspacing="0" rules="none">
    <tr>
      <td width="208px" height="86px" class="logo_voucher" valign="middle" align="center"><img src="images/Voucher_Logo.jpg" width="180px" height="80px" /></td>
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
              <td height="50px" class="folios_voucher">'. sprintf("%08d",$_POST[folio]).'</td>
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
          <td class="contenido_voucher">'.$_POST[codigo1].'</td>
          <td class="contenido_voucher">'.$_POST[codigo2].'</td>
        </tr>
      </table></td>
      <td valign="top" width="350">
      <table width="350px" border="1" cellspacing="0" cellpadding="0" rules="none">
        <tr>
          <td class="titulos_voucher">CONFIRMADO POR / CONFIRMED BY</td>
        </tr>
        <tr>
          <td  class="contenido_voucher">'.$_POST[codigo3].'</td>
        </tr>
      </table></td>
      <td width="220PX" rowspan="4" valign="top">
      
      <table width="220px" border="1" cellpadding="0" cellspacing="0" rules="none">
        <tr><td colspan="6"  class="titulos_voucher">DISTRIBUCION HAB. / ROOMS TYPE AND NO.</td></tr>
        <tr>
		  <td  class="titulos_voucher" width="20px">DBL</td>
          <td  class="titulos_voucher" width="20px">TWN</td>
          <td  class="titulos_voucher" width="20px">TPL</td>
          <td  class="titulos_voucher" width="20px">SGL</td>
          <td  class="titulos_voucher" width="20px">OTHER</td>
          <td  class="titulos_voucher" width="25px">TOTAL PAX.</td>
          </tr>
        <tr>
		  <td class="contenido_voucher">'.$_POST[codigo14].'</td>
          <td class="contenido_voucher">'.$_POST[codigo4].'</td>
          <td class="contenido_voucher">'.$_POST[codigo5].'</td>
          <td class="contenido_voucher">'.$_POST[codigo6].'</td>
          <td class="contenido_voucher">'.$_POST[codigo7].'</td>
          <td class="contenido_voucher">'.$_POST[codigo8].'</td>
          </tr>
		  <tr>
            <td  colspan="6" align="center" class="titulos_voucher">ESPECIFICACIONES / SPECIFICATIONS</td>
          </tr>
          <tr>
            <td  colspan="6" align="center" class="contenido_voucher">'.$_POST[codigo15].'</td>
          </tr>
		  
		  
      </table>
    
      <table width="220px" border="1" align="left" cellpadding="0" cellspacing="1"  rules="none">
        <tr>
          <td width="197px" class="titulos_voucher">PAGADERO POR / PAYMENT THROUGH</td>
        </tr>
        <tr>
          <td class="contenido_voucher">'.$_POST[codigo12].'</td>
        </tr>
      </table>
    <table width="220px" border="1" align="left" cellpadding="0" cellspacing="1"  rules="none">
      <tr>
        <td width="220px"  class="titulos_voucher">RESERVADO POR / RESERVED BY</td>
        </tr>
      <tr>
        <td class="contenido_voucher">'.$_POST[codigo13].'</td>
        </tr>
    </table>    <table width="220px" border="1" align="left" cellpadding="0" cellspacing="0"  rules="none">
      <tr>
        <td width="197px" class="titulos_voucher">SELLO Y FIRMA / STAMP AND SIGNATURE</td>
        </tr>
      <tr>
        <td height="80px" style="padding-left:4px; text-align:center">
		<img src="qrcode/'.$_POST[folio].'.png"/>
		
		</td>
        </tr>
    </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="0" cellpadding="0" rules="none">
        <tr>
          <td class="titulos_voucher">NOMBRE DEL CLIENTE - GRUPO / CLIENTS - GROUP NAME</td>
          </tr>
        <tr>
          <td class="contenido_voucher">'.$_POST[codigo9].'</td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="0" cellpadding="0"  rules="none">
        <tr>
          <td  class="titulos_voucher">PRESTATARIO / SUPPLIER DIRECCION / ADDRESS CIUDAD / CITY TELEF. /PHONE</td>
          </tr>
        <tr>
          <td class="contenido_voucher">'.$_POST[codigo10].'&nbsp;'.$_POST[codigo11].'</td>
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
            <td width="120px" valign="top" align="center" class="contenido_voucher">'.$_POST[fechas].'<br />
              '.$_POST[fechas2].'<br />
              '.$_POST[fechas3].'<br />
              '.$_POST[fechas4].'<br />
              '.$_POST[fechas5].'<br />
              '.$_POST[fechas6].'<br />
              '.$_POST[fechas7].'<br />
            '.$_POST[fechas8].'<br /></td>
            <td width="437" align="left" class="contenido_voucher" valign="top">'.$_POST[descripciones].'<br />
              '.$_POST[descripciones2].'<br />
              '.$_POST[descripciones3].'<br />
              '.$_POST[descripciones4].'<br />
              '.$_POST[descripciones5].'<br />
              '.$_POST[descripciones6].'<br />
              '.$_POST[descripciones7].'<br />
            '.$_POST[descripciones8].'</td>
          </tr>
		  <tr>
		      <td colspan="2">
		         <p class="advertencia_voucher">PARA EL PROVEEDOR:  La factura deberá ir acompañada de este bono.<br /> FOR SUPPLIER ONLY: The invoice must be sent with this voucher.</p> 
		      </td>
	      </tr>
      </table></td>
    </tr>
  </table>  
<br>
<br>
</form>
</body>
</html>';
//==============================================================
//==============================================================
//==============================================================
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

//==============================================================
//==============================================================
//==============================================================
} // fin del "solograba"
?>
<script language="javascript">
location.href="<?php=$_POST[URLretorno]?>"
</script>