<?php
  require_once("genera_pagine.php");

  $contenuto = file_get_contents('prodotti.html');
  $pagina = Genera_pagina::genera("base.html", "prodotti", $contenuto);
  echo $pagina;
?>
