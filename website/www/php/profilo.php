<?php
  require_once("session.php");
  require_once("DBAccess.php");
      require_once("genera_pagina.php");

  if($_SESSION['auth']) {
    $contenuto = file_get_contents("../html/profilo.html");
    $pagina = Genera_pagina::genera("../html/base.html", "profilo", $contenuto);
    echo $pagina;
  } else {
    header('Location: redirect.php?error=3');
    exit;
  }
