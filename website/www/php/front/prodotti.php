<?php
  require_once("website/www/php/back/genera_pagine.php");

  $contenuto = file_get_contents("website/www/html/prodotti.html");
  $pagina = Genera_pagina::genera("website/www/html/base.html", "prodotti", $contenuto);
  echo $pagina;
?>
