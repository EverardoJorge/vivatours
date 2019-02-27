<?php
/**
 * Plugin Name: pdfword
 * Plugin URI: http://alvaroveliz.cl
 * Description: Alvaro's Placeholder
 * Version: 1.0
 * Author: Alvaro Véliz
 * Author URI: http://alvaroveliz.cl
 * License: MIT
 */
 function pdfword()
 {

$resultado='<div align="center"> 
	<a class="button" href="../../wp-content/plugins/pdfword/acciones.php?accion=Enviar&id='.get_the_ID().'" target="_blank">Enviar</a>
            <a class="button" href="../../wp-content/plugins/pdfword/acciones.php?accion=pdf&id='.get_the_ID().'" target="_blank">Crear PDF</a>
            <a class="button" href="../../wp-content/plugins/pdfword/acciones.php?accion=word&id='.get_the_ID().'" target="_blank">Descargar Word</a>
			<div>
			';



return $resultado;
}
 add_shortcode('pdfword', 'pdfword');
 ?>