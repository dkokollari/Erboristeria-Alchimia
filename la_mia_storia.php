<?php
  require_once("genera_pagina.php");

  $contenuto = file_get_contents('la_mia_storia.html');
  $pagina = $pagina = Genera_pagina::genera("base.html", "la_mia_storia", $contenuto);
  echo $pagina;
?>
