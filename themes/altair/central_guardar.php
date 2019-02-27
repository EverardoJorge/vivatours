<?php
/**
 * REVISADO Y CORREGIDO <?PHP
 */
/**
 * Template Name: Central Guardar
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
		
		
		/////datos del usuario 
$usuario = do_shortcode("[user-data field_name='Username']");
$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM vvt_EWD_FEUP_Users WHERE Username='".$usuario."'"));
//$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_ewd_feup_users WHERE Username='".$usuario."'"));

$max_cliente = $User->User_ID;
$EmailCliente = $User->Username;

////
$db=conectar();
$Codigo=generarCodigo(10);

////////// Numero de compras
$query_compras = mysql_query("SELECT * FROM vvt_central_compras WHERE IDCliente = '".$max_cliente."' ORDER BY id_compra DESC",$db)or die ($query_insert.mysql_error());
		
		$numero_compras = mysql_num_rows($query_compras);
		if ($numero_compras==0){
		$numero_compras=1;
		$clienteceros = sprintf("%05d", $max_cliente) ;
		$comprasceros = sprintf("%03d", $numero_compras);
		$compras = "$clienteceros-$comprasceros";
		}else {
			
		$numero_compras++;
		$clienteceros = sprintf("%05d", $max_cliente) ;
		$comprasceros = sprintf("%03d", $numero_compras);
		$compras = "$clienteceros-$comprasceros";
					}
		
$vencimiento = date('Y-m-d', strtotime('+2 day')) ;
  ///////////

$compra= mysql_query("INSERT INTO vvt_central_compras (IDCliente,IDProducto,IDcokiee,compras,Fecha,vencimiento,Hora,Estatus) VALUES ('".$max_cliente."','".$_POST[id_programa]."','".$Codigo."','".$compras."', NOW(),'".$vencimiento."', CURRENT_TIME(),'1')",$db)or die ($query_insert.mysql_error());
 
///////////////////mis pininos////////////
if(isset($_POST[Tipo])){
			
if(isset($_POST[pax1])){
 $query1= mysql_query("INSERT INTO vvt_central (IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES ('".$max_cliente."', 
 '".strip_tags(strtoupper($_POST[id_programa]))."',
  '".$_POST[pax1]."', 
  '".$_POST[Tipo]."', 
  '".$_POST[Categoria]."', 
  '".$_POST[Edad1]."', 
  '".$_POST[pax1_dia]."/".$_POST[pax1_mes]."/".$_POST[pax1_anio]."',   
  '".$Codigo."',
  '".$compras."','1')",$db) or die ("Error in query: $query. ".mysql_error());
  		 }	 
		 
if(isset($_POST[pax2])){
 $query1=  mysql_query("INSERT INTO vvt_central(IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES 
 ('".$max_cliente."', 
 '".$_POST[id_programa]."', 
 '".$_POST[pax2]."', 
 '".$_POST[Tipo]."', 
 '".$_POST[Categoria]."', 
 '".$_POST[Edad2]."', 
 '".$_POST[pax2_dia]."/".$_POST[pax2_mes]."/".$_POST[pax2_anio]."',

  '".$Codigo."',
  '".$compras."',
  '1')",$db);
		
		 }
		 
if(isset($_POST[pax3])){
 $query1=  mysql_query("INSERT INTO vvt_central(IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES ('$max_cliente', 
 '".$_POST[id_programa]."', 
 '".$_POST[pax3]."', 
'".$_POST[Tipo]."', 
 '".$_POST[Categoria]."',  
 '".$_POST[Edad3]."', 
 '".$_POST[pax3_dia]."/".$_POST[pax3_mes]."/".$_POST[pax3_anio]."', 

 '".$Codigo."',
 '".$compras."','1')",$db);
		
		 }
 
}

////////////////////si elije dos habitaciones////////////
	if(isset($_POST[Tipo2])and $_POST[habitaciones]>=2){
			
if(isset($_POST[pax1_2])){
 $query1=  mysql_query("INSERT INTO vvt_central(IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES ('$max_cliente',
  '".$_POST[id_programa]."',
  '".$_POST[pax1_2]."', 
'".$_POST[Tipo2]."', 
 '".$_POST[Categoria2]."',
  '".$Codigo."', 
  '".$_POST[pax1_2dia]."/".$_POST[pax1_2mes]."/".$_POST[pax1_2anio]."', 
 
  '".$Codigo."',
  '".$compras."',
	 '2')",$db);
		
		 }
		 
if(isset($_POST[pax2_2])){
 $query1=  mysql_query("INSERT INTO vvt_central(IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES ('$max_cliente', 
 '".$_POST[id_programa]."', 
 '".$_POST[pax2_2]."',
 '".$_POST[Tipo2]."', 
 '".$_POST[Categoria2]."',
    '".$_POST[Edad2_2]."', 
	'".$_POST[pax2_2dia]."/".$_POST[pax2_2mes]."/".$_POST[pax2_2anio]."',
	
	
	  '".$Codigo."',
	 '".$compras."',
	 '2')",$db);
		
		 }
}

////////////////////si elije tres habitaciones////////////
	if(isset($_POST[Tipo3])and $_POST[habitaciones]>=3){
			
if(isset($_POST[pax1_3])){
 $query1=  mysql_query("INSERT INTO vvt_central(IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES ('$max_cliente',
  '".$_POST[id_programa]."', 
  '".$_POST[pax1_3]."', 
 '".$_POST[Tipo3]."', 
 '".$_POST[Categoria3]."', 
   '".$_POST[Edad1_3]."', 
   '".$_POST[pax1_3dia]."/".$_POST[pax1_3mes]."/".$_POST[pax1_3anio]."',
   
   '".$Codigo."',
   '".$compras."','3')",$db);
		
		 }
		 
if(isset($_POST[pax2_3])){
 $query1=  mysql_query("INSERT INTO vvt_central(IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES ('$max_cliente',
  '".$_POST[id_programa]."',
   '".$_POST[pax2_3]."', 
   '".$_POST[Tipo3]."', 
 '".$_POST[Categoria3]."',
	'".$_POST[Edad2_3]."', 
	'".$_POST[pax2_3dia]."/".$_POST[pax2_3mes]."/".$_POST[pax2_3anio]."',
	 
	
	 '".$Codigo."',
	 '".$compras."',
	 '3')",$db);
		
		 }
		 
}

//////////si elije cuatro

	if(isset($_POST[Tipo4]) and $_POST[habitaciones]==4){
			
if(isset($_POST[pax1_4])){
 $query1=  mysql_query("INSERT INTO vvt_central(IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES ('$max_cliente',
  '".$_POST[id_programa]."',
   '".$_POST[pax1_4]."',
  '".$_POST[Tipo4]."', 
 '".$_POST[Categoria4]."', 
	'".$_POST[Edad1_4]."', 
	'".$_POST[pax1_4dia]."/".$_POST[pax1_4mes]."/".$_POST[pax1_4anio]."',
	 
	
	  '".$Codigo."',
	 '".$compras."',
	 '4')",$db);
		
		 }
		 
if(isset($_POST[pax2_4])){
 $query1=  mysql_query("INSERT INTO vvt_central(IDCliente,Producto,Pasajero,Habitacion,Categoria,Edad,Fecha,IDcokiee,IDCompra,Num_habitacion) VALUES ('$max_cliente',
  '".$_POST[id_programa]."',
   '".$_POST[pax2_4]."', 
  '".$_POST[Tipo4]."', 
 '".$_POST[Categoria4]."',
   '".$_POST[Edad2_4]."', 
   '".$_POST[pax2_4dia]."/".$_POST[pax2_4mes]."/".$_POST[pax2_4anio]."',
 
   '".$Codigo."',
   '".$compras."',
   '4')",$db);
		
		 }	
}	
///////////asta aki////////////////////////////

$tr="<table width='500' align='center' cellpadding='4' cellspacing='0' class='box1' border='1' style='border-collapse:collapse'>";
//////////////////////

	if(isset($_POST[Tipo])){
if ($_POST[Tipo]==1){ $habitacion_tipo1= "SGL";}
if ($_POST[Tipo]==2){ $habitacion_tipo1= "DBL";}
if ($_POST[Tipo]==3){ $habitacion_tipo1= "TRP";}	
   $tr.='<tr style="vertical-align:middle; font-size:14px; font-weight:bold; color:#fff; height:20px;" align="center" bgcolor="#333333">
<td width="32%"  align="center">Habitación 1: '.$habitacion_tipo1;
$tr.='</td><td width="46%" align="center">Categoria';
$tr.=': '.$_POST[Categoria].'</td><td width="22%" >';
$tr.='</td></tr>
<tr bgcolor="#CCCCCC" style="font-size:12px; font-weight:bold; "><td align="center">Nombre</td><td align="center">Descuento</td><td align="center">Fecha Nac.en Pasaporte</td></tr>';
 if(!empty($_POST[pax1]) && $_POST[pax1] != ''){ 
 $tr.='
 <tr align="center" class="texto_pasajeros">
 <td align="center"  colspan="">'.$_POST[pax1].'</td>
<td colspan="" align="center" >'. $_POST[Edad1].'</td>
<td colspan="" align="center"> '.$_POST[pax1_dia]."/".$_POST[pax1_mes]."/".$_POST[pax1_anio].'</td> 
  </tr>';
 }
  if(!empty($_POST[pax2]) && $_POST[pax2] != ''){ 
 	$tr.='
 <tr align="center" class="texto_pasajeros">
 <td align="center"  colspan="">'.$_POST[pax2].'</td>
<td colspan="" align="center">'.$_POST[Edad2].'</td>
<td colspan="" align="center">'.$_POST[pax2_dia]."/".$_POST[pax2_mes]."/".$_POST[pax2_anio].'</td><tr>';
 
 }
 if(!empty($_POST[pax3]) && $_POST[pax3] != ''){ 
 	$tr.='
 <tr align="center" class="texto_pasajeros">
 <td align="center"  colspan="">'.$_POST[pax3].'</td>
<td colspan="" align="center">'.$_POST[Edad3].'</td>
<td colspan="" align="center">'.$_POST[pax3_dia]."/".$_POST[pax3_mes]."/".$_POST[pax3_anio].'</td><tr>';
 
 }
 
  } 
  
 	if(!empty($_POST[Tipo2])){	
	if ($_POST[Tipo2]==1){ $habitacion_tipo2= "SGL";}
if ($_POST[Tipo2]==2){ $habitacion_tipo2= "DBL";}
if ($_POST[Tipo2]==3){ $habitacion_tipo2= "TRP";}
   $tr.='<tr style="vertical-align:middle; font-size:14px; font-weight:bold; color:#fff; height:20px;" align="center" bgcolor="#333333">
<td width="32%"  align="center">Habitación 2: '.$habitacion_tipo2;

$tr.= '</td><td width="46%" align="center" class="td_titulo">Categoria:';
$tr.=$_POST[Categoria2].' </td> <td width="22%" >';
$tr.='</td></tr>
 <tr bgcolor="#CCCCCC" style="font-size:12px; font-weight:bold; "><td align="center">Nombre</td><td align="center">Descuento</td><td align="center">Fecha Nac.en Pasaporte</td></tr>';
 
 if(!empty($_POST[pax1_2]) && $_POST[pax1_2] != ''){ 
 $tr.='
 <tr align="center">
 <td align="center" >'.$_POST[pax1_2].'</td>
<td align="center" >'. $_POST[Edad1_2].'</td>
<td align="center"> '.$_POST[pax1_2dia]."/".$_POST[pax1_2mes]."/".$_POST[pax1_2anio].'</td> 
  </tr>';
 }
  if(!empty($_POST[pax2_2]) && $_POST[pax2_2] != ''){ 
 $tr.='
 <tr align="center">
 <td align="center" >'.$_POST[pax2_2].'</td>
<td align="center" >'. $_POST[Edad2_2].'</td>
<td align="center"> '.$_POST[pax2_2dia]."/".$_POST[pax2_2mes]."/".$_POST[pax2_2anio].'</td> 
  </tr>';
 }
   
  } 
  
  if(!empty($_POST[Tipo3])){	
if ($_POST[Tipo3]==1){ $habitacion_tipo3= "SGL";}
if ($_POST[Tipo3]==2){ $habitacion_tipo3= "DBL";}
if ($_POST[Tipo3]==3){ $habitacion_tipo3= "TRP";}
   $tr.='<tr  style="vertical-align:middle; font-size:14px; font-weight:bold; color:#fff; height:20px;" align="center" bgcolor="#333333">
<td width="32%"  align="center">Habitación 3: '.$habitacion_tipo3;

$tr.=' </td><td width="46%" align="center" >Categoria:';
$tr.=$_POST[Categoria3].' </td> <td width="22%">';
$tr.='
 </td></tr>
 <tr bgcolor="#CCCCCC" style="font-size:12px; font-weight:bold; "><td align="center" >Nombre</td><td align="center">Descuento</td><td align="center">Fecha Nac.en Pasaporte</td></tr>';
 if(!empty($_POST[pax1_3]) && $_POST[pax1_3] != ''){ 
 $tr.='
 <tr align="center">
 <td align="center">'.$_POST[pax1_3].'</td>
<td  align="center" >'. $_POST[Edad1_3].'</td>
<td  align="center"> '.$_POST[pax1_3dia]."/".$_POST[pax1_3mes]."/".$_POST[pax1_3anio].'</td> 
  </tr>';
 }
   if(!empty($_POST[pax2_3]) && $_POST[pax2_3] != ''){ 
 $tr.='
 <tr align="center">
 <td align="center">'.$_POST[pax2_3].'</td>
<td  align="center" >'. $_POST[Edad2_3].'</td>
<td  align="center"> '.$_POST[pax2_3dia]."/".$_POST[pax2_3mes]."/".$_POST[pax2_3anio].'</td> 
  </tr>';
 }
   
  } 
  
  
//////////////////////	
	
  if(!empty($_POST[Tipo4])){
if ($_POST[Tipo4]==1){ $habitacion_tipo4= "SGL";}
if ($_POST[Tipo4]==2){ $habitacion_tipo4= "DBL";}
if ($_POST[Tipo4]==3){ $habitacion_tipo4= "TRP";}

$tr.='<tr  style="vertical-align:middle; font-size:14px; font-weight:bold; color:#fff; height:20px;" align="center" bgcolor="#333333">
<td width="32%"  align="center">Habitación 4: '.$habitacion_tipo4;

$tr.=$_POST[Tipo4].' </td><td width="46%" align="center">Categoria:';
$tr.=$_POST[Categoria4].' </td><td width="22%">';
$tr.='</td></tr>
<tr bgcolor="#CCCCCC" style="font-size:12px; font-weight:bold; "><td align="center">Nombre</td><td align="center">Descuento</td><td align="center">Fecha Nac.en Pasaporte</td></tr>';
 if(!empty($_POST[pax1_4]) && $_POST[pax1_4] != ''){ 
 $tr.='
 <tr align="center" class="texto_pasajeros">
 <td align="center" >'.$_POST[pax1_4].'</td>
<td  align="center" >'. $_POST[Edad1_4].'</td>
<td align="center"> '.$_POST[pax1_4dia]."/".$_POST[pax1_4mes]."/".$_POST[pax1_4anio].'</td> 
  </tr>';
 }
 if(!empty($_POST[pax2_4]) && $_POST[pax2_4] != ''){ 
 $tr.='
 <tr align="center" class="texto_pasajeros">
 <td align="center" >'.$_POST[pax2_4].'</td>
<td  align="center" >'. $_POST[Edad2_4].'</td>
<td align="center"> '.$_POST[pax2_4dia]."/".$_POST[pax2_4mes]."/".$_POST[pax2_4anio].'</td> 
  </tr>';
 }
   
  } 
	
	////////////
$tr.="</table>";


//Destinatario
                  $recipient = $EmailCliente;
                  //Asunto del email
                  $subject = 'Formulario de Bloqueo No.'. $compras;
                  //La dirección de envio del email es la de nuestro blog por lo que agregando este header podremos responder al remitente original
                  $headers[] = "Reply-to:  <agencias@viajesvivatours.com>\r\n";
				  $headers[] = 'From: Viajes Vivatours <agencias@viajesvivatours.com>';
                  $headers[] = 'Cc: Agencias Vivatours <agencias@viajesvivatours.com>';
                 // $headers[] = 'Cc: Agencias Vivatours <agencias@viajesvivatours.com>'; // note you can just use a simple email address
                  //Montamos el cuerpo de nuestro e-mail
				  
				  
				  
                  $message = '
				 <table width="865" border="0" align="center" cellpadding="5" cellspacing="0" rules="none" style="font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
  <tr bgcolor="#009999" >
    <th colspan="4" scope="col"><p style="font-size:18px; color:#FFF;">Gracias por reservar con:</p>
    <p><a href="https://viajesvivatours.com"><img src="https://www.viajesvivatours.com/wp-content/uploads/2017/10/logoViva1-1.png" width="100" /></a></p></th>
    <th width="548" scope="col"><font style="font-size:18px; color:#FFF; ">Este bloqueo es  preventivo. </font><br />
    <font style="font-size:18px; color:#ffff00; ">Cuentas con 72 horas para 
    realizar<br />
el pago</font><font style="font-size:18px; color:#FFF;"> de esta reserva para<br />garantizar tus espacios y salida.  </font></th>
  </tr>
  <tr>
    <td colspan="5" align="left" valign="middle">
    
    <strong><font style="color:#F00;">No. Bloqueo:'.$compras.'</font><br>
    <br>
    Nombre:</strong>'.do_shortcode('[user-data field_name="Nombre Comercial "]' ).'<br />
	<strong>Correo:</strong>'.$EmailCliente.'<br />
	<strong>Teléfono:</strong>'.do_shortcode('[user-data field_name="Teléfono "]' ).'<br />
	<strong>Programa:</strong>'.$_POST[Programa].'<br />
	<strong>No. Habitaciones:</strong>'.$_POST[habitaciones].'<br>
	<strong>Comentarios:</strong>'.$_POST[Comentarios].'
	</td>
  </tr>
  <tr>
    <td colspan="5" align="center" valign="middle">Aquí puedes consultar todos los detalles de tu reservación, cualquier duda consultanos.</td>
  </tr>
  <tr>
    <td colspan="5">'.$tr.'</td>
  </tr>
  <tr>
    <td colspan="5"  ><span style="color:#FFF;">Datos Bancarios:</span></td>
  </tr>
  <tr>
    <td colspan="5" bgcolor="#009999" style="color:#FFF;" >PARA DEPOSITOS EN PESOS (Pedir tipo de cambio del día) .</td>
  </tr>
  <tr>
    <td colspan="5"><p>Viva Tours Agencia Mayorista de Viajes SA de CV  <br>
      Cuenta Nº: 03902426153  <br>
      Suc: 006  <br>
      Plaza: 039 </p>
      <p>CLABE 044540039024261532  <br>
      SCOTIABANK INVERLAT SA  <br>
      DOMICILIO: CUERNAVACA, MORELOS. MEXICO<br>
Viva Tours Agencia Mayorista de Viajes SA de CV  <br>
Cuenta Nº: 16926  <br>
Suc: 997  <br>
CLABE 002540099700169260  <br>
BANAMEX, SA  <br>
DOMICILIO: CUERNAVACA, MORELOS. MEXICO  <br>
<br>
Viva Tours Agencia Mayorista de Viajes SA de CV<br>
Cuenta Nº: 0176917232  <br>
Suc: 0817  <br>
CLABE 012540001769172328  <br>
BBVA BANCOMER SA  <br>
DOMICILIO: CUERNAVACA, MORELOS. MEXICO</p>
      </td></tr><tr><td colspan="5" bgcolor="#009999" style="color:#FFF;" >
      <p>PARA DEPOSITOS EN DOLARES:   </td></tr><tr><td colspan="5">
        Viva Tours Agencia Mayorista de Viajes SA de CV  <br>
        Cuenta Nº: 03900002771  <br>
        Suc: 006  <br>
        Plaza: 039<br>
        <br>
        CLABE 044540039000027716  <br>
        SCOTIABANK INVERLAT SA  <br>
        SWIFT CODE: MBCOMXMM  <br>
        Av. Vicente Guerrero No. 110<br>
        Col. Lomas de la Selva CP62270<br>
        Cuernavaca. Morelos. MEXICO  <br>
        <br>
        Viva Tours Agencia Mayorista de Viajes SA de CV<br>
        Cuenta Nº: 9000208  <br>
        Suc: 997 <br>
        CLABE 002540099790002081  <br>
        BANAMEX, SA  <br>
        DOMICILIO: CUERNAVACA, MORELOS. MEXICO  <br>
        <br>
        Viva Tours Agencia Mayorista de Viajes SA de CV  <br>
        Cuenta Nº: 0176922287  <br>
        Suc: 0817<br>
        CLABE PARA TRANSFERENCIA: 012540001769222870  <br>
        BBVA BANCOMER SA  <br>
        CODIGO SWIFT: BCMRMXMM  <br>
        DOMICILIO: CUERNAVACA, MORELOS. MEXICO  <br>
        </td></tr><tr><td colspan="5" bgcolor="#009999" style="color:#FFF;" >
        PARA TRANSFERNECIA EN EUROS: </td></tr><tr><td colspan="5">
        <br>
        Viva Tours Agencia Mayorista de Viajes SA de CV  <br>
        Cuenta Nº: 0176922562  Suc: 0817  <br>
        CLABE 012540001769225628  <br>
        BBVA BANCOMER SA  <br>
        CODIGO SWIFT: BCMRMXMM  <br>
    DOMICILIO: CUERNAVACA, MORELOS. MEXICO</p></td>
  </tr>
</table>
';
    //Filtro para indicar que email debe ser enviado en modo HTML
                  add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
                  //Por último enviamos el email
                  $envio = wp_mail( $recipient, $subject, $message, $headers, $attachments);
                  //Si el e-mail se envía correctamente mostramos un mensaje y vaciamos las variables con los datos. En caso contrario mostramos un mensaje de error
                  if ($envio) {
				
                   ?>
                    <div class="alert alert-success alert-dismissable">
                   
                      El formulario ha sido enviado correctamente.
                    </div>
                  <?php }else {?>
                    <div class="alert alert-danger alert-dismissable">
                    
                     Se ha producido un error enviando el formulario. Puede intentarlo más tarde o ponerse en contacto con nosotros escribiendo un mail a "agencias@viajesvivatours.com"
                    </div>
                  <?php }
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