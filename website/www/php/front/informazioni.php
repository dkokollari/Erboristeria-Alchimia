<?php
  require_once("website/www/php/back/genera_pagina.php");

  $contenuto = file_get_contents("website/www/html/informazioni.html");
  $pagina = Genera_pagina::genera("website/www/html/base.html", "informazioni", $contenuto);
  echo $pagina;
?>
