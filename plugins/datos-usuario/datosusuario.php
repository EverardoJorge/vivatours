<?php
//revisado
/**
 * Plugin Name: Datos de Usuario
 * Plugin URI: http://viajesviatours.com
 * Description: Datos de las agencias
 * Version: 1.0
 * Author: Luis Alberto Garcia
 * Author URI: http://viajesviatours.com
 * License: MIT
 */
    // Shortcode del div para flotar a un lado
    add_shortcode('datosusuario', 'datosusuario');
    // Función del shortcode para crear un div flotante:
    function datosusuario() {
		$imagen=do_shortcode('[user-data field_name="Imagen de perfil "]' );
		$nombre=do_shortcode('[user-data field_name="Username" ]' );
		$rs=do_shortcode('[user-data field_name="Nombre Comercial "]' );
		$apellido=do_shortcode('[user-data field_name="Apellido "]' );
		$direccion=do_shortcode('[user-data field_name="Calle y número "]' );
		$colonia=do_shortcode('[user-data field_name="Colonia "]' );
		$ciudad=do_shortcode('[user-data field_name="Ciudad "]' );
		$estado=do_shortcode('[user-data field_name="Estado "]' );
		$cp=do_shortcode('[user-data field_name="Código postal "]' );
		$tel=do_shortcode('[user-data field_name="Teléfono "]' );
		$email=do_shortcode('[user-data field_name="Imagen de perfil "]' );
		
   
     $resultado='   
		<table id="datos_usuario2" border="0" width="95%" cellspacing="2" cellpadding="2" align="center" style="padding:0px !important; margin:0px !important" class="table table-condensed">
      <tbody>
        <tr>
          <td width="18%" align="left" valign="middle"><img src="https://www.viajesvivatours.com/wp-content/uploads/ewd-feup-user-uploads/'.$imagen.'" width="200" height="100" style="margin: 10px 10px 10px 0px;float: left; "></td>
          <td width="82%" align="left" valign="middle">'.$rs.'<br>
            '.$direccion.', '.$ciudad.', '.$estado.', '.$cp.'<br>
            Tel:'.$tel.'<br>
            Email:'.$nombre.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle">Atendido por:'.$nombre.'</td>
        </tr>
      </tbody>
    </table>';   
    return $resultado;
    }?>