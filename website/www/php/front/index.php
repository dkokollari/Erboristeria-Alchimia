<?php
  require_once("../back/session.php");

  if($_SESSION['auth']) {
    echo $_SESSION['auth']."\n";
    echo $_SESSION['nome_utente']."\n";
    echo $_SESSION['cognome_utente']."\n";
    echo $_SESSION['email_utente']."\n";
    echo $_SESSION['password_utente']."\n";
    echo $_SESSION['tipo_utente']."\n";
    echo $_SESSION['data_nascita_utente']."\n";
  }
  else echo 'non sei loggato';
?>
