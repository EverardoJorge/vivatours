<?php
/**
 * Plugin Name: Super Buscador
 * Plugin URI: http://viajesviatours.com
 * Description: Super Buscador
 * Version: 1.0
 * Author: Luis Alberto Garcia
 * Author URI: http://viajesviatours.com
 * License: MIT
 */
    // Shortcode del div para flotar a un lado
    add_shortcode('superbuscador', 'superbuscador');
    // Función del shortcode para crear un div flotante:
    function superbuscador($att) {?>
<div align="center">
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" >

    <input type="text" class="field" name="s" id="s" size="20" placeholder="<?php _e( 'Busca tu viaje por ciudad' ); ?> " autocomplete="off" /> 
 <?php //get_terms_dropdown( 'tourcats' ); ?> 
 <select name="tourcats" class="field" style="padding:7px; border:2px #CCC solid;">
        <option value="europa-2019" <?php=($att['tour'] == 'europa-2019') ? "selected" : "";?>>Europa 2019</option>
        <option value="medio-oriente-2019" <?php=php($att['tour'] == 'medio-oriente-2019') ? "selected" : "";?>>Medio Oriente 2019</option>
        <option value="lejano-oriente-y-asia-central-2019-2020"  <?php=($att['tour'] == 'lejano-oriente-y-asia-central-2019-2020') ? "selected" : "";?>>Lejano Oriente y Asia central 2019-2020</option>
        <option value="mexico-2019" <?php=($att['tour'] == 'mexico-2019') ? "selected" : "";?>>México 2019</option>
        <option value="viajes-de-autor" <?php=($att['tour'] == 'viajes-de-autor') ? "selected" : "";?>>Viajes de Autor</option>
        <option value="turismo-religioso-2018" <?php=($att['tour'] == 'turismo-religioso-2018') ? "selected" : "";?>>Turismo Religioso</option>
    </select>

    &nbsp;&nbsp;&nbsp;<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php _e( 'Buscar' ); ?>" />

</form>   </div>
<?php    }?>
