<?php
require_once('menu_pagina.php');

$pagina = file_get_contents('base.html');
$pagina = str_replace("%TITOLO_PAGINA%","la mia storia",$pagina);
$pagina = str_replace("%DESCRIZIONE_PAGINA%","Dove e come siamo nati, tutti i dettagli e le curiosità di Erboristeria Alchimia",$pagina);
$pagina = str_replace("%KEYWORDS_PAGINA%","storia, Marika, erboristeria, alchimia",$pagina);
$pagina = str_replace("%CONTAINER_PAGINA%","container_la_mia_storia",$pagina);
$pagina = str_replace("%LISTA_MENU%",menu_pagina::menu("la_mia_storia.php"),$pagina);
$pagina = str_replace("%CONTENUTO_PAGINA%",file_get_contents('la_mia_storia.html'),$pagina);

echo $pagina;
 ?>