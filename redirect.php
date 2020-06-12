<?php
  require_once('menu_pagina.php');

  $pagina = file_get_contents('base.html');
  $pagina = str_replace("%TITOLO_PAGINA%", "reindirizzamento", $pagina);
  $pagina = str_replace("%DESCRIZIONE_PAGINA%", 'pagina di reindirizzamento', $pagina);
  $pagina = str_replace("%KEYWORDS_PAGINA%", 'redirect, reindirizzamento, erboristeria, alchimia', $pagina);
  $pagina = str_replace("%CONTAINER_PAGINA%", "container", $pagina);
  $pagina = str_replace("%LISTA_MENU%", menu_pagina::menu("redirect.php"), $pagina);
  $pagina = str_replace("%CONTENUTO_PAGINA%", file_get_contents('redirect.html'), $pagina);
  echo $pagina;
  header("Refresh:2; url=home.php");
  exit;
?>
