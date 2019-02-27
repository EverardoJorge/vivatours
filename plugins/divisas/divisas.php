<?php
//revisado
/*
Plugin Name: Mi Divisas
Plugin URI: http://www.viajesvivatours.com/
Description: Tipo de Cambio Dolar y Euro
Author: Luis A. Garcia
Author URI: http://www.viajesvivatours.com/
Version: 1.0
License: GPLv2
*/
    // Shortcode del div para flotar a un lado
if(!function_exists('mi_divisas')){
   
	function mi_divisas() {
		global $wpdb;
$registros = $wpdb->get_results( "SELECT * FROM wp_divisas", ARRAY_A );
 
     $resultado='
	 <div class=" style="padding-left:5px !important; padding-right:5px !important;">
  <div class="container-fluid" style="padding:0">
    <div class="text-align:center">
       <span class="text-danger">
        <center>
          <strong>Tipo de cambio a '.$registros[0]["Fecha"].'</strong><br>para depositar en MXN</center>
       </span>
     
      <div style="text-align:center;  background-color:#E26512; color:#FFF; font-weight:bold; border-radius: 3px; padding:0px 3px !important;">
       <font style="font-size:16px;">USD '.$registros[0]["dolar"].'<BR>EURO '.$registros[0]["euro"].' </font>
      </div>
</div></div></div>';
	    
    return $resultado;
    }
} 
    add_shortcode('divisas', 'mi_divisas');
    // Funcion del shortcode para crear un div flotante:
    ?>