<?php
  require_once("genera_pagina.php");

  $contenuto = file_get_contents("../html/informazioni.html");
  $pagina = Genera_pagina::genera("../html/base.html", "informazioni", $contenuto);
  echo $pagina;
?>
