<?php
  require_once("website/www/php/back/genera_pagina.php");

  $contenuto = file_get_contents("website/www/html/la_mia_storia.html");
  $pagina = Genera_pagina::genera("website/www/html/base.html", "la_mia_storia", $contenuto);
  echo $pagina;
?>
