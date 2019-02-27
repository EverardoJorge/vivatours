<?php
//revisado
/*
Plugin Name: Mi Divisas Control
Plugin URI: http://www.viajesvivatours.com/
Description: Tipo de Cambio Dolar y Euro
Author: Luis A. Garcia
Author URI: http://www.viajesvivatours.com/
Version: 1.0
License: GPLv2
*/



    // Shortcode del div para flotar a un lado
if(!function_exists('mi_divisas_control')){
  
	function mi_divisas_control() {
		 $meses = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
     $fecha= date('d')." ".$meses[date('n')-1]. " ".date('Y');

		
		if ($_POST){
global $wpdb;
	$wpdb->update('wp_divisas', 
	
	array(
	'dolar' => $_POST['cambio_Dolar'],
	'euro' => $_POST['cambio_Euro'],
	'Fecha' => $fecha),
    
    array( 'id_divisa' => 1 ));
	//echo var_dump( $wpdb->last_query );;
	} //////////////////////////
		global $wpdb;
 
$registros = $wpdb->get_results( "SELECT * FROM wp_divisas", ARRAY_A );
$resultado='
<form id="sample-form" name="sample-form" action="" method="post"><ul style="list-style-type: none;margin: 0; padding: 0;">
<table width="250" border="0" cellspacing="5" cellpadding="5" class="table">
  <tr>
    <td>
Dolar: <input id="cambio_Dolar" name="cambio_Dolar" type="text" step="any" class="field" value="'.$registros[0]["dolar"].'" /></td>
  </tr>
  <tr>
    <td>Euro: <input id="cambio_Euro" name="cambio_Euro" type="text" step="any" class="field" value="'.$registros[0]["euro"].'" /></td>
  </tr>
  <tr>
    <td align="center" valign="middle">
	<input name="guardar" type="submit" />
	</td>
  </tr>
  </table>	
</form>
	 
	 
	 
	';
	    
    return $resultado;
    }
} 
    add_shortcode('divisas-control', 'mi_divisas_control');
    // Funcion del shortcode para crear un div flotante:
    ?>