<?php
  require_once("genera_pagina.php");

  $contenuto = file_get_contents('informazioni.html');
  $pagina = Genera_pagina::genera("base.html", "informazioni", $contenuto);
  echo $pagina;
?>
