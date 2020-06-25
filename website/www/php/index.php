<?php
  require_once("session.php");
  require_once("menu_pagina.php");

  $menu =  menu_pagina::menu("index");

  $pagina = file_get_contents("../html/index.html");

  $pagina = str_replace("%LISTA_MENU%", $menu, $pagina);

  echo $pagina;

  /*if($_SESSION['auth']) {
    echo $_SESSION['auth']."\n";
    echo $_SESSION['nome_utente']."\n";
    echo $_SESSION['cognome_utente']."\n";
    echo $_SESSION['email_utente']."\n";
    echo $_SESSION['password_utente']."\n";
    echo $_SESSION['tipo_utente']."\n";
    echo $_SESSION['data_nascita_utente']."\n";
  }
  else echo 'non sei loggato';*/
?>
