<?php
/**
 * Plugin Name: Voucher Emisor
 * Plugin URI: http://viajesvivatours.com
 * Description: Viajes Vivatours
 * Version: 1.0
 * Author: Luis Alberto Garcia
 * Author URI: http://viajesvivatours.com
 * License: MIT
 */
 function VoucherEmisor($attributes)
 {
	 require_once("conexion.php");
$db=conectar();

function dameURL(){
$url1="https://".$_SERVER['HTTP_HOST']."/wp-content/plugins/emi-voucher/";
return $url1;
}
$url1= dameURL();

 if($_GET[nuevo]=="1"){?>
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
	document.form1.action="<?php=$url1?>crear_pdf.php"
	document.form1.target=objetivo 
	document.form1.submit()

	if (j_accion!="solograba") { history.back(-1) }

	} 

}

			

</script>



<table width="800px" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td align="right"><INPUT type=button value=" <- Back " onClick="history.back(-1);"></td>

  </tr>

</table>

<br />

<form action="javascript:valida()" method="post" id="form1" name="form1"  enctype="multipart/form-data">

<input type="hidden" name="accion" id="accion" />

<input type="hidden" name="URLretorno" id="URLretorno" value="<?php='https://www.viajesvivatours.com/voucher-emisor/'?>">

  <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" rules="none" class="table table-bordered table-hover table-condensed">

    <tr>

      <td width="26%" height="110" class="logo_voucher" valign="middle" align="center"><img src="https://www.viajesvivatours.com/wp-content/plugins/emi-voucher/images/voucher_logo.jpg" width="180" height="80" /></td>

      <td width="49%" class="titulos_DATOS_voucher" valign="middle">Reg. Nal. Turismo 4000476 R.F.C. VTA 931112Q19<br />

        CASA MATRIZ: Xochicalco N. 201 Col. Reforma, <br />Cuernavaca, Morelos, México.C.P. 62260.<br />

        Conmutador: 01 (777)313 56 03 (10 Lineas)<br />

        Fax: 01 (777)311 38 96 Tel: 01 800 021 8492<br />

        e-mail: vivatours@prodigy.net.mx - www.viajesvivatours.com</td>

      <td width="25%" class="logo" valign="top">

      <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>

          <td height="30" valign="middle" class="titulos_DATOS_voucher2" align="center"> BONO DE SERVICIOS / VOUCHER</td>

        </tr>

        <tr>

          <td height="100%" align="center"><table width="95%" border="0" cellspacing="0" cellpadding="0">

            <tr height="30px">            

              <td height="51" class="folios_voucher">

			 

             

              <input name="folio" type="hidden" value="<?php=$folio;?>" /></td>

            </tr>

          </table></td>

        </tr>

      </table></td>

    </tr>

  </table>

  <br>
  <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-bordered table-hover table-condensed">
    <tr>
      <td width="27%" valign="top"><table width="220px" border="1" align="left" cellpadding="0" cellspacing="0" rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td width="110px" class="">TOUR CODIGO / CODE</td>
          <td width="110px" class="" >FECHA / DATE</td>
        </tr>
        <tr>
          <td class=""><input name="codigo1" type="text" id="codigo1" size="12" value="" /></td>
          <td class=""><input name="codigo2" type="text" id="codigo2" size="12" onfocus="if(this.value=='dd/mm/aaaa') this.value=''" onblur="if(this.value=='') this.value='dd/mm/aaaa'" onkeyup="mascara(this,'/',patron,true)" value="dd/mm/aaaa" /></td>
        </tr>
      </table></td>
      <td valign="top" width="35%"><table width="350px" border="1" cellspacing="0" cellpadding="0" rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td class="">CONFIRMADO POR / CONFIRMED BY</td>
        </tr>
        <tr>
          <td  class=""><input name="codigo3" type="text" id="codigo3" size="50" value="" /></td>
        </tr>
      </table></td>
      <td width="38%" rowspan="4" align="center" valign="top"><table  border="1" cellpadding="0" cellspacing="0" rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td colspan="6"  class="">DISTRIBUCION HAB. / ROOMS TYPE AND NO.</td>
        </tr>
        <tr>
          <td class="">DBL</td>
          <td class="">TWN</td>
          <td  class="">TPL</td>
        </tr>
        <tr></tr>
        <tr>
          <td class=""><input name="codigo14" type="text" class="input_voucher" id="codigo14" size="5"/></td>
          <td class=""><input name="codigo4" type="text" class="input_voucher" id="codigo4" size="5"/></td>
          <td class=""><input name="codigo5" type="text" class="input_voucher" id="codigo5" size="5"/></td>
        </tr>
        <tr>
          <td  class="">SGL</td>
          <td  class="">OTHER</td>
          <td  class="">TOTAL PAX.</td>
        </tr>
        <tr>
          <td class=""><input name="codigo6" type="text" class="input_voucher" id="codigo6" size="5"/></td>
          <td class=""><input name="codigo7" type="text" class="input_voucher" id="codigo7" size="5"/></td>
          <td class=""><input name="codigo8" type="text" class="input_voucher" id="codigo8" size="5"/></td>
        </tr>
        <tr>
          <td  colspan="6" align="center" class="">ESPECIFICACIONES / SPECIFICATIONS</td>
        </tr>
        <tr>
          <td  colspan="6" align="center"><input name="codigo15" type="text" id="codigo15" size="30" /></td>
        </tr>
      </table>
        <table width="242" border="1" align="center" cellpadding="0" cellspacing="1"  rules="none" class="table table-bordered table-hover table-condensed">
          <tr>
            <td width="236" class="">PAGADERO POR / PAYMENT THROUGH</td>
          </tr>
          <tr>
            <td class=""><input name="codigo12" type="text" id="codigo12" size="30" /></td>
          </tr>
          <tr>
            <td><table width="220px" border="1" align="center" cellpadding="0" cellspacing="1"  rules="none">
              <tr>
                <td width="220px"  class="">RESERVADO POR / RESERVED BY</td>
              </tr>
              <tr>
                <td class=""><input name="codigo13" type="text" id="codigo13" size="30" /></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="220px" border="1" align="center" cellpadding="0" cellspacing="0"  rules="none">
              <tr>
                <td width="197px" class="">SELLO Y FIRMA / STAMP AND SIGNATURE</td>
              </tr>
              <tr>
                <td height="126" align="center" style="text-align:center; vertical-align:middle;"><p class="advertencia_voucher" >IMPORTANTE: La factura deberá ir acompañada de esta bono.<br />

            IMPORTANT: The invoice must be sent with this voucher.</p></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="0" cellpadding="0" rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td class="">NOMBRE DEL CLIENTE - GRUPO / CLIENTS - GROUP NAME</td>
        </tr>
        <tr>
          <td class=""><input name="codigo9" type="text" id="codigo9" size="87" value="" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="0" cellpadding="0"  rules="none">
        <tr>
          <td  class="">PRESTATARIO / SUPPLIER DIRECCION / ADDRESS CIUDAD / CITY TELEF. /PHONE</td>
        </tr>
        <tr>
          <td class=""><input name="codigo10" type="text" id="codigo10" size="20" value="" />
            &nbsp;
            <input name="codigo11" type="text" id="codigo11" size="50" value="" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><table width="569px" border="1" cellspacing="2" cellpadding="0"  rules="none" class="table table-bordered table-hover table-condensed">
        <tr>
          <td colspan="2" class="">FAVOR DE FACILITAR SIGUIENTES SERVICIOS / KINDLY PROVIDE FOLLOWING SERVICES.</td>
        </tr>
        <tr>
          <td class="">FECHAS / DATES</td>
          <td class="">DESCRIPCION DE SERVICIOS / SERVICES DESCRIPTION</td>
        </tr>
        <tr>
          <td width="120px" valign="top" align="center" class=""><input name="fechas" type="text" class="" id="fechas"  size="15" />
            <br />
            <input name="fechas2" type="text" class="" id="fechas2" size="15" />
            <br />
            <input name="fechas3" type="text" class="" id="fechas3" size="15" />
            <br />
            <input name="fechas4" type="text" class="" id="fechas4" size="15" />
            <br />
            <input name="fechas5" type="text" class="" id="fechas5" size="15" />
            <br />
            <input name="fechas6" type="text" class="" id="fechas6" size="15" />
            <br />
            <input name="fechas7" type="text" class="" id="fechas7" size="15" />
            <br />
            <input name="fechas8" type="text" class="" id="fechas8" size="15" />
            <br /></td>
          <td width="437" align="left" class="" valign="top"><input name="descripciones" type="text" class="" id="descripciones" size="80" />
            <br />
            <input name="descripciones2" type="text" class="" id="descripciones2" size="80" />
            <br />
            <input name="descripciones3" type="text" class="" id="descripciones3" size="80" />
            <br />
            <input name="descripciones4" type="text" class="" id="descripciones4" size="80" />
            <br />
            <input name="descripciones5" type="text" class="" id="descripciones5" size="80" />
            <br />
            <input name="descripciones6" type="text" class="" id="descripciones6" size="80" />
            <br />
            <input name="descripciones7" type="text" class="" id="descripciones7" size="80" />
            <br />
            <input name="descripciones8" type="text" class="" id="descripciones8" size="80" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
  <table width="300" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-bordered table-hover table-condensed">

  <tr>

    <td width="100" align="center"><button type="submit"  class="button"  name="solograba" onclick="j_accion='solograba'">Grabar </button> </td>

    <td width="100" align="center"><button type="submit"  class="button"  name="PDF" onclick="j_accion='todo'; valida();">GenerarPDF</button></td>

    <td width="100" align="center"><input type="reset"  class="button" name="button2" id="button2" value="Restablecer"></td>

  </tr>

</table>

<br />

</form><?php } ?>


<?php if(empty($_GET[nuevo])){?>

<form id="form2" name="form2" method="get" action="" class="form-inline">
<label>Criterio de filtrado:(Usar cliente, confirmado o prestatario) por: </label><div class="input-group">
            <input name="buscar" type="text" id="buscar" value="<?php=$_GET[buscar]?>" class="form-control" />
               <span class="input-group-btn">
             <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
            </button></span></div>




 <div class="form-group">
 <input name="Submit2" type="button" onclick="location.href='?'" value="ACTUALIZAR LISTADO" class="btn btn-warning"/>  <input name="Button" type="button" class="btn btn-success" value="CREAR NUEVO" onclick="location.href='https://www.viajesvivatours.com/voucher-emisor/?nuevo=1'"/></div>
    </form>
<hr />
<table width="100%" align="center" cellpadding="0" cellspacing="0"  rules="none" class="table table-bordered table-hover table-condensed tablesorter">
<thead/>
  <tr >
 <th width="3%" height="29" align="center" style="text-align:center !important;">/</th>
 
<th width="7%" height="29"  >FOLIO</th>
<th width="6%" >CODE</th>
<th width="5%" >FECHA</th>
<th width="19%" >CLIENTE</th>
<th width="19%" >CONFIRMADO</th>    
<th width="19%" >PRESTATARIO</th>
<th width="22%" >RESERVADO</th>
  </tr>
</thead>  <tbody>
  <?php
  if(isset($_GET['buscar'])){ 
$buscar=$_GET['buscar'];
} 
  
	//	if($ordenar)
	 if (isset($buscar)) {
		 
   //CUENTA EL NUMERO DE PALABRAS
   $trozos=explode(" ",$buscar);
   $numero=count($trozos);
  if ($numero==1) {
   //SI SOLO HAY UNA PALABRA DE BUSQUEDA SE ESTABLECE UNA INSTRUCION CON LIKE
   $_pagi_sql="SELECT * FROM vvt_voucher WHERE (codigo3 LIKE '".$buscar."%' OR codigo9 LIKE '".$buscar."%' OR codigo10 LIKE '".$buscar."%')";
  } elseif ($numero>1) {
  //SI HAY UNA FRASE SE UTILIZA EL ALGORTIMO DE BUSQUEDA AVANZADO DE MATCH AGAINST
  //busqueda de frases con mas de una palabra y un algoritmo especializado
  $_pagi_sql="SELECT *, MATCH (codigo3,codigo9,codigo10) AGAINST ( '".$buscar."' ) AS Score FROM vvt_voucher WHERE MATCH (codigo3,codigo9,codigo10) AGAINST ( '".$buscar."' ) ";
  } 
//////
	 }
else{
	//	if($ordenar)
$_pagi_sql = "SELECT * FROM vvt_voucher ORDER BY idvoucher DESC"; 
 }

 if (isset($orden)) {
$ordenar=explode(":",$orden);
 $_pagi_sql .= " ORDER BY ".$ordenar[0]." ".$ordenar[1]." ";
 }


 
	 $_pagi_nav_num_enlaces=5;
$_pagi_cuantos = 20; 
//Incluimos el script de paginación. Éste ya ejecuta la consulta automáticamente 

include("includes/paginator.inc.php"); 

$URLretorno='https://'.$_SERVER['HTTP_HOST'].str_replace("&","%26",$_SERVER['REQUEST_URI']);

$color='#CCCCCC';  // este es el primer color que queremos que aparezca 

for($i=0;$i<20;$i++) 

{ //Leemos y escribimos los registros de la página actual 



while($row = mysql_fetch_array($_pagi_result)){ 

$datos = mysql_query("SELECT * FROM vvt_voucher");

$row2=mysql_fetch_array($datos);



 echo "<tr> 

<td style='text-align:center !important;'><a href='../ver-voucher-emisor/?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' ></a>
";
if ($row["folio"]=='CANCELADO'){
echo "<img src='$url1/images/x.png' width='15px' height='15px' />";
}else{
echo "<img src='$url1/images/y.png' width='15px' height='15px' />";
}
echo "</td>
<td><a href='../ver-voucher-emisor/?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".sprintf("%08d",$row['idvoucher'])."</a></td>
<td><a href='../ver-voucher-emisor/?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo1']."</a></td>
<td><a href='../ver-voucher-emisor/?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo2']."</a></td>
<td><a href='../ver-voucher-emisor/?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo9']."</a></td>
<td><a href='../ver-voucher-emisor/?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo3']."</a></td>
<td><a href='../ver-voucher-emisor/?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo10']."</a></td>
<td><a href='../ver-voucher-emisor/?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo13']."</a></td>
     </tr>"; 

 

} }}
  ?>
  
 <?php 

echo "
</tbody>
<tfoot>
  <tr>

    <td align='center' colspan='8'>

".$_pagi_navegacion."</td>

  </tr>

  <tr>

    <td align='center' colspan='9'>".$_pagi_info."</td>

  </tr>
";

?></tfoot></table>
<?php }
 add_shortcode('VoucherEmisor', 'VoucherEmisor');