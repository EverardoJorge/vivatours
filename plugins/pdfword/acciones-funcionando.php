<?php
define('WP_USE_THEMES', false);
require('../../../wp-blog-header.php');

mysql_connect("localhost", "root", "lgslgs666"); //use your creditials
mysql_select_db("admin_wp2"); //use your WordPress DB name
$result = mysql_query("SELECT * FROM wp_posts where ID = ".$_GET[id].""); //replace wp_ with your table prefix

while ($post = mysql_fetch_object($result)) {
 $titulo = $post->post_title;
 $html= $post->post_title;
 $html.= $post->post_content;
  }



if ($_GET[accion]=="word") {

header("Content-type: application/vnd.ms-word");
header("Content-Type: application/msword; charset=utf-8"); 
header("Content-Disposition: attachment;Filename=".$titulo.".doc");

$html_word= "<html>";
$html_word.= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
$html_word.= "<style type='text/css'>
body {	font-family: 'Arial';
font-size:10pt;
margin:0px;
padding:0px;
}

 p.MsoNormal, li.MsoNormal, div.MsoNormal
    {margin:0cm;
    margin-bottom:.0001pt;
    font-size:12.0pt;
    font-family:'Arial';}
@page Section1
    {size:595.3pt 841.9pt;
    margin:1.0cm 30.5pt 1.0cm 1.0cm;}
div.Section1
    {page:Section1;}


</style>";
$html_word.= "<body><div class=Section1>";

$html_word.="<p style='font-size:12px; font-family:Arial; font-style:oblique; color:#FF0000;'><strong>Instrucciones:</strong>  Si lo deseas, para editar este Word, puedes cambiar el modo de visualización a vista de impresión en el menú de vista de Word.</p>
<br>";
$html_word.= $html;

echo $html_word;
$html_word.="</div></body></html>";

mysql_free_result($result);


}

?>