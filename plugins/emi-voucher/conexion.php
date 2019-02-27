<?php
function conectar() 
{ 
	$base_de_datos = "admin_viajes";
	$db_usuario = "admin_viajes"; 
	$db_password = "a137JsiGWf";
   
	if (!($link = mysql_connect("localhost", $db_usuario, $db_password))) 
	{ 

		echo "Error conectando a la base de datos."; 
		exit(); 
	} 
	if (!mysql_select_db($base_de_datos, $link)) 
	{ 
		echo "Error seleccionando la base de datos."; 
		exit(); 
	} 
	@mysql_query("SET NAMES 'utf8'");  
	return $link; 

} 
?>