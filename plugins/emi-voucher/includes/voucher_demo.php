<?php
/**
	Plugin Name:	Voucher Emisor
	Plugin URI:		http://www.louimania.com/
	Description:	Plugin crear voucher de Emisor
	Version: 		0.1
	Author:			Luis alberto Garcia
	Author URI:		http://www.louimania.com
	License:		GPLv2 or later
**/

/*
* Función para añadir una página al menú de administrador de wordpress
*/
function conasa_plugin_menu(){
	//Añade una página de menú a wordpress
	add_menu_page('Ajustes plugin Max Length Content',			//Título de la página
					'Max Length Content',						//Título del menú
					'administrator',							//Rol que puede acceder
				  	'conasa-max-length-content-settings',		//Id de la página de opciones
				  	'conasa_max_length_content_page_settings',	//Función que pinta la página de configuración del plugin
				  	'dashicons-admin-generic');					//Icono del menú
}
add_action('admin_menu','conasa_plugin_menu');

 function VoucherEmi($attributes)
 {
	   global $wpdb;
 
    	 
   // $link = mysqli_connect("localhost", "root", "lgslgs666");
//mysqli_select_db($link, "viajesvi_2013");
$query = $wpdb->get_results("SELECT * FROM vvt_voucher", ARRAY_A);
//$_pagi_result = $wpdb->get_results( "SELECT * FROM vvt_voucher");
//$_pagi_result = mysqli_query($link, "SELECT * FROM vvt_voucher");
//$_pagi_result = "SELECT * FROM voucher ORDER BY idvoucher DESC";
?>

<table width="100%" align="center" cellpadding="0" cellspacing="0"  rules="none" class="table table-bordered table-hover table-condensed">
<thead/>
  <tr class="info">

   <th width="8%" height="29"  ><a href="?id_seccion=<?=$id_seccion?>&buscar=<?=$buscar?>&orden=<?=($orden=="idvoucher:DESC") ? "idvoucher:ASC":"idvoucher:DESC" ?>" title="Ordenar por">FOLIO</a></th>

    <th width="9%" ><a href="?id_seccion=<?=$id_seccion?>&buscar=<?=$buscar?>&orden=<?=($orden=="codigo1:DESC") ? "codigo1:ASC":"codigo1:DESC" ?>" title="Ordenar por" >CODE</a></th>

    <th width="8%" ><a href="?id_seccion=<?=$id_seccion?>&buscar=<?=$buscar?>&orden=<?=($orden=="codigo2:DESC") ? "codigo2:ASC":"codigo2:DESC" ?>" title="Ordenar por" >FECHA</a></th>

	<th width="26%" ><a href="?id_seccion=<?=$id_seccion?>&buscar=<?=$buscar?>&orden=<?=($orden=="codigo9:DESC") ? "codigo9:ASC":"codigo9:DESC" ?>" title="Ordenar por" >CLIENTE</a></th>

	<th width="26%" ><a href="?id_seccion=<?=$id_seccion?>&buscar=<?=$buscar?>&orden=<?=($orden=="codigo3:DESC") ? "codigo3:ASC":"codigo3:DESC" ?>" title="Ordenar por" >CONFIRMADO</a></th>
    
    <th width="26%" ><a href="?id_seccion=<?=$id_seccion?>&buscar=<?=$buscar?>&orden=<?=($orden=="codigo10:DESC") ? "codigo10:ASC":"codigo10:DESC" ?>" title="Ordenar por" >PRESTATARIO</a></th>
    
<th width="23%" ><a href="?id_seccion=<?=$id_seccion?>&buscar=<?=$buscar?>&orden=<?=($orden=="codigo13:DESC") ? "codigo13:ASC":"codigo13:DESC" ?>" title="Ordenar por">RESERVADO</a></th>
  </tr>
</thead>  <tbody><?

foreach($query as $row){
 echo "<tr>

     <td ><a href='vervoucher.php?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".sprintf("%08d",$row['idvoucher'])."</a></td>

    <td ><a href='vervoucher.php?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo1']."</a></td>

	<td ><a href='consultapdf.php?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo2']."</a></td>

	<td ><a href='consultapdf.php?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo9']."</a></td>

	<td ><a href='consultapdf.php?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo3']."</a></td>
	<td ><a href='consultapdf.php?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo10']."</a></td>
	
	<td ><a href='consultapdf.php?folio=".$row['idvoucher']."&URLretorno=".$URLretorno."' title='Consultar Voucher' >".$row['codigo13']."</a></td>

	</tr>"; 
}


?></tfoot></table>
<?
mysqli_free_result($result);
mysqli_close($link);
 }

 add_shortcode('VoucherEmi', 'VoucherEmi');

?>