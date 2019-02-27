<? if($nuevo!=""){?>

<script type="text/javascript">
var j_accion="solograba";
var objetivo="_self";

function valida()
{
document.getElementById("accion").value=j_accion
if (j_accion=="solograba") { objetivo="_self" } else { objetivo="_blank" }

if(document.getElementById("codigo1").value.length<2)
			      {                                	
  				 	alert("El Codigo debe tener mas de 2 Caracteres")
					document.getElementById("codigo1").focus()
					return;
                  } 

if (confirm("Confirma el registro de este Voucher?"))
	{
	document.form1.action="crear_pdf.php"
	document.form1.target=objetivo 
	document.form1.submit()
	} 
}
			
</script>

<table width="800px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right"><INPUT type=button value=" Back " onClick="history.back(-1);"></td>
  </tr>
</table>
<br />
<form action="javascript:valida()" method="post" id="form1" name="form1"  enctype="multipart/form-data">
<input type="hidden" name="accion" id="accion" />
<input type="hidden" name="URLretorno" id="URLretorno" value="<?=$url='http://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];?>">
  <table width="800" border="1" align="center" cellpadding="0" cellspacing="0" rules="none">
    <tr>
      <td width="208" height="110" class="logo_voucher" valign="middle" align="center"><img src="images/voucher_logo.png" width="180" height="80" /></td>
      <td width="401" class="titulos_DATOS_voucher" valign="middle">Reg. Nal. Turismo 4000476 R.F.C. VTA 931112Q19<br />
        CASA MATRIZ: Calle Herradura de Plata No. 127 Planta Alta.<br />
        Col. Lomas de la Selva, Cuernavaca, Morelos, México.C.P. 62270<br />
        Conmutador: 01 (777)313 56 03 (10 Lineas)<br />
        Fax: 01 (777)311 38 96 Tel: 01 800 021 8492<br />
        e-mail: vivatours@prodigy.net.mx - www.viajesvivatours.com</td>
      <td width="191" class="logo" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="30" valign="middle" class="titulos_DATOS_voucher" align="center"> BONO DE SERVICIOS / VOUCHER</td>
        </tr>
        <tr>
          <td height="100%" align="center"><table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr height="30px">            
              <td height="51" class="folios_voucher">
			 
             
              <input name="folio" type="hidden" value="<?=$folio;?>" /></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <br>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="2">
    <tr>
        <td height="46" colspan="4" align="center"  valign="top" ><table width="220" border="1" align="left" cellpadding="0" cellspacing="0" rules="none">
          <tr>
            <td width="25%" class="titulos_voucher" h>TOUR CODIGO / CODE</td>
            <td width="25%" class="titulos_voucher">FECHA / DATE</td>
          </tr>
          <tr>
            <td height="30" align="center"><input name="codigo1" type="text" id="codigo1" size="12" /></td>
            <td align="center"><input name="codigo2" type="text" id="codigo2" size="12"  /></td>
          </tr>
      </table>
          <table width="344" border="1" cellspacing="0" cellpadding="0" rules="none">
            <tr>
              <td width="551" class="titulos_voucher">CONFIRMADO POR / CONFIRMED BY</td>
            </tr>
            <tr>
              <td height="30" align="center"><input name="codigo3" type="text" id="codigo3" size="50" /></td>
            </tr>
          </table>
      </td>
      <td width="239" rowspan="6" align="right" valign="top">
      <table width="220" border="1" cellpadding="0" cellspacing="0" rules="none">
          <tr>
            <td  colspan="6" align="center" class="titulos_voucher">DISTRIBUCION HAB. / ROOMS TYPE AND NO.</td>
          </tr>
          <tr>
            <td width="44" height="20" class="titulos_voucher">DBL</td>
            <td width="44" height="20" class="titulos_voucher">TWBD</td>
            <td width="44" class="titulos_voucher">TRPB</td>
            <td width="44" class="titulos_voucher">SGLB</td>
            <td width="44" class="titulos_voucher">OTHER</td>
            <td width="44" class="titulos_voucher">TOTAL PAX.</td>
          </tr>
          <tr>
            <td height="44" align="center"><input name="codigo14" type="text" class="input_voucher" id="codigo14" size="5"/></td>
            <td height="44" align="center"><input name="codigo4" type="text" class="input_voucher" id="codigo4" size="5"/></td>
            <td align="center"><input name="codigo5" type="text" id="codigo5" size="5" class="input_voucher"/></td>
            <td align="center"><input name="codigo6" type="text" id="codigo6" size="5"  class="input_voucher"/></td>
            <td align="center"><input name="codigo7" type="text" id="codigo7" size="5"  class="input_voucher"/></td>
            <td align="center"><input name="codigo8" type="text" id="codigo8" size="5" class="input_voucher"/></td>
          </tr>
          <tr>
            <td  colspan="6" align="center" class="titulos_voucher">ESPECIFICACIONES / SPECIFICATIONS</td>
          </tr>
          <tr>
            <td  colspan="6" align="center"><input name="codigo15" type="text" id="codigo15" size="30"></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td height="46" colspan="4" align="center"  valign="top" ><table width="569" border="1" cellspacing="0" cellpadding="0" rules="none">
        <tr>
          <td width="197" class="titulos_voucher">NOMBRE DEL CLIENTE - GRUPO / CLIENT'S - GROUP NAME</td>
        </tr>
        <tr>
          <td height="30" align="center"><input name="codigo9" type="text" id="codigo9" size="87" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td width="569" valign="top"><table width="569" border="1" cellspacing="0" cellpadding="0"  rules="none">
      <tr>
        <td width="197" class="titulos_voucher">PRESTATARIO / SUPPLIER DIRECCION / ADDRESS CIUDAD / CITY TELEF. /PHONE</td>
      </tr>
      <tr>
        <td align="center" height="30"><input name="codigo10" type="text" id="codigo10" size="27" />
          <input name="codigo11" type="text" id="codigo11" size="55" /></td>
      </tr>
    </table></td>
    <td width="225" valign="top"><table width="220" border="1" align="right" cellpadding="0" cellspacing="0"  rules="none">
      <tr>
        <td width="197" class="titulos_voucher">PAGADERO POR / PAYMENT THROUGH</td>
      </tr>
      <tr>
        <td align="center" height="30"><input name="codigo12" type="text" id="codigo12" size="30"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td rowspan="2" valign="top"><table width="569" border="1" cellspacing="2" cellpadding="0"  rules="none">
      <tr>
        <td colspan="2" class="titulos_voucher">FAVOR DE FACILITAR SIGUIENTES SERVICIOS / KINDLY PROVIDE FOLLOWING SERVICES.</td>
      </tr>
      <tr>
        <td align="center" class="titulos_voucher">FECHAS / DATES</td>
        <td align="center" class="titulos_voucher">DESCRIPCION DE SERVICIOS / SERVICES DESCRIPTION</td>
      </tr>
      <tr>
        <td width="120" valign="top" align="center"><input name="fechas" type="text" class="nobord_voucher" id="fechas" value="" size="15" />
          <input name="fechas2" type="text" class="nobord_voucher" id="fechas2" value="" size="15" />
          <input name="fechas3" type="text" class="nobord_voucher" id="fechas3" value="" size="15" />
          <input name="fechas4" type="text" class="nobord_voucher" id="fechas4" value="" size="15" />
          <input name="fechas5" type="text" class="nobord_voucher" id="fechas5" value="" size="15" />
          <input name="fechas6" type="text" class="nobord_voucher" id="fechas6" value="" size="15" />
          <input name="fechas7" type="text" class="nobord_voucher" id="fechas7" value="" size="15" />
          <input name="fechas8" type="text" class="nobord_voucher" id="fechas8" value="" size="15" /></td>
        <td width="437" align="center"><input name="descripciones" type="text" class="nobord_voucher" id="descripciones" value="" size="80" />
          <br />
          <input name="descripciones2" type="text" class="nobord_voucher" id="descripciones2" value="" size="80" />
          <br />
          <input name="descripciones3" type="text" class="nobord_voucher" id="descripciones3" value="" size="80" />
          <br />
          <input name="descripciones4" type="text" class="nobord_voucher" id="descripciones4" value="" size="80" />
          <br />
          <input name="descripciones5" type="text" class="nobord_voucher" id="descripciones5" value="" size="80" />
          <br />
          <input name="descripciones6" type="text" class="nobord_voucher" id="descripciones6" value="" size="80" />
          <br />
          <input name="descripciones7" type="text" class="nobord_voucher" id="descripciones7" value="" size="80" />
          <br />
          <input name="descripciones8" type="text" class="nobord_voucher" id="descripciones8" value="" size="80" /></td>
      </tr>
    </table></td>
    <td valign="top"><table width="220" border="1" align="right" cellpadding="0" cellspacing="0"  rules="none">
      <tr>
        <td width="197" class="titulos_voucher">RESERVADO POR / RESERVED BY</td>
      </tr>
      <tr>
        <td align="center" height="30"><input name="codigo13" type="text" id="codigo13" size="30"></td>
      </tr>
  </table>
      <br /></td>
  </tr>
  <tr>
    <td height="272" valign="top"><table width="220" border="1" align="right" cellpadding="0" cellspacing="0"  rules="none">
      <tr>
        <td width="197" class="titulos_voucher">SELLO Y FIRMA / STAMP AND SIGNATURE</td>
        </tr>
      <tr>
        <td height="153"><p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p class="advertencia_voucher" >IMPORTANTE: La factura deberá ir acompañada de esta bono.<br />
            IMPORTANT: The invoice must be sent with this voucher.</p></td>
        </tr>
      </table></td>
  </tr>
  </table>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="150"><button type="submit"  name="solograba" onclick="j_accion='solograba'">Grabar </button> </td>
    <td width="150"><button type="submit"  name="PDF" onclick="j_accion='todo'">GenerarPDF</button></td>
    <td width="150"><input type="reset" name="button2" id="button2" value="Restablecer"></td>
  </tr>
</table>
<br />
</form><? }else{?>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right">
      <form id="form2" name="form2" method="post" action="">
        <input name="nuevo" type="hidden" id="nuevo" value="1" />
        <input name="Submit" type="submit" class="style11" value="CREAR NUEVO" />

    </form><br /></td>
  </tr>
</table>


<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0"  rules="none" class="box1">
  <tr class="Tablas_titulos">
   <td height="29" align='center' ><a href="?id_seccion=<?=$id_seccion?>&orden=idvoucher" title="Ordenar por" class="Tablas_titulos">FOLIO</a></td>
    <td align='center' ><a href="?id_seccion=<?=$id_seccion?>&orden=codigo1" title="Ordenar por" class="Tablas_titulos">CODE</a></td>
    <td align='center' ><a href="?id_seccion=<?=$id_seccion?>&orden=codigo2" title="Ordenar por" class="Tablas_titulos">FECHA</a></td>
	<td align='center' ><a href="?id_seccion=<?=$id_seccion?>&orden=codigo9" title="Ordenar por" class="Tablas_titulos">CLIENTE</a></td>
	<td align='center' ><a href="?id_seccion=<?=$id_seccion?>&orden=codigo3" title="Ordenar por" class="Tablas_titulos">CONFIRMADO</a></td>
  </tr>
      <? 
	  if (isset($orden)) {
   // echo "Esta variable está definida, así que se imprimirá";
	//	if($ordenar)
$_pagi_sql = "SELECT * FROM voucher ORDER BY $orden"; 

$_pagi_cuantos = 20; 

//Incluimos el script de paginación. Éste ya ejecuta la consulta automáticamente 
include("includes/paginator.inc.php"); 

$color='#CCCCCC';  // este es el primer color que queremos que aparezca 
for($i=0;$i<20;$i++) 
{ //Leemos y escribimos los registros de la página actual 
$r=1;
while($row = mysql_fetch_array($_pagi_result)){ 
$datos = mysql_query("SELECT * FROM voucher ORDER BY $orden");
$row2=mysql_fetch_array($datos);
  echo "<tr bgcolor=\"$color\">
     <td class=\"textos_voucher\" align='center'><a href='consultapdf.php?folio=".$row['idvoucher']."' title='Consultar Voucher' >".sprintf("%08d",$row['idvoucher'])."</a></td>
    <td class=\"textos_voucher\" align='center'>".$row['codigo1']."</td>
	<td class=\"textos_voucher\" align='center'>".$row['codigo2']."</td>
	<td class=\"textos_voucher\" align='center'>".$row['codigo9']."</td>
	<td class=\"textos_voucher\" align='center'>".$row['codigo3']."</td>
	</tr>"; 
 $color=('#E7E7E7'==$color)?'#CCCCCC':'#E7E7E7'; 
$r++;
} }
}
else{
	//	if($ordenar)
$_pagi_sql = "SELECT * FROM voucher ORDER BY idvoucher DESC"; 

$_pagi_cuantos = 20; 

//Incluimos el script de paginación. Éste ya ejecuta la consulta automáticamente 
include("includes/paginator.inc.php"); 

$color='#CCCCCC';  // este es el primer color que queremos que aparezca 
for($i=0;$i<20;$i++) 
{ //Leemos y escribimos los registros de la página actual 
$r=1;
while($row = mysql_fetch_array($_pagi_result)){ 
$datos = mysql_query("SELECT * FROM voucher ORDER BY idvoucher DESC");
$row2=mysql_fetch_array($datos);
  echo "<tr bgcolor=\"$color\">
     <td class=\"textos_voucher\" align='center'><a href='consultapdf.php?folio=".$row['idvoucher']."' title='Consultar Voucher' >".sprintf("%08d",$row['idvoucher'])
	 ."</a></td>
    <td class=\"textos_voucher\" align='center'>".$row['codigo1']."</td>
	<td class=\"textos_voucher\" align='center'>".$row['codigo2']."</td>
	<td class=\"textos_voucher\" align='center'>".$row['codigo9']."</td>
	<td class=\"textos_voucher\" align='center'>".$row['codigo3']."</td>
	</tr>"; 
 $color=('#E7E7E7'==$color)?'#CCCCCC':'#E7E7E7'; 
$r++;
} }
}

?>
</table>
<p><br /><? 
echo "
<table width='80%' border='1' cellspacing='0' cellpadding='0' align='center' rules='none' class='box1'>
  <tr>
    <td align='center'>
".$_pagi_navegacion."</td>
  </tr>
  <tr>
    <td align='center'>".$_pagi_info."</td>
  </tr>
</table>";
?>  

<? }?>