<?php
  require_once("session.php");
  require_once("genera_pagina.php");

  if($_SESSION['auth'] && $_SESSION['tipo_utente'] == "Admin") {

    $contenuto = file_get_contents("../html/admin.html");
    $pagina = Genera_pagina::genera("../html/base5.html", "admin", $contenuto);
    echo $pagina;
  } // end if $_SESSION['auth'] && $_SESSION['tipo_utente'] == "Admin"
  else {
    header('Location: redirect.php?error=3');
    exit;
  }
?>
