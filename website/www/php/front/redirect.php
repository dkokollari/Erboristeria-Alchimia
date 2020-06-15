<?php
  require_once("website/www/php/back/genera_pagina.php");
  require_once("website/www/php/back/Errore.php");

  $contenuto = file_get_contents("website/www/html/redirect.html");
  $contenuto = str_replace("%ERRORE%", Errore::getErrore($_GET['error']), $contenuto);
  $pagina = Genera_pagina::genera("website/www/html/base.html", "redirect", $contenuto);
  echo $pagina;

  header("Refresh:10; url=website/www/php/front/home.php");
  exit;
?>
