<?php
  require_once("session.php");
  require_once("Errore.php");
  require_once("genera_pagina.php");

  $contenuto = file_get_contents("../html/redirect.html");
  $contenuto = str_replace("%ERRORE%", Errore::getErrore($_GET['error']), $contenuto);
  $pagina = Genera_pagina::genera("../html/base.html", "redirect", $contenuto);
  echo $pagina;

  header("Refresh:10; url=index.php");
  exit;
?>
