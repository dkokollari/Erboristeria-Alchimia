<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("genera_pagina.php");

  if($_SESSION['auth']) {
    $con = new DBAccess();
    if(!$con->openConnection()) {
      header('Location: redirect.php?error=1');
      exit;
    }
    $img_timbri = '';
    $num_timbri = '0';
    if($_SESSION['tipo_utente'] == 'User') {
      $num_timbri = $con->getTimbriUtente($_SESSION['email_utente']);
      for($i=0; $i<$num_timbri['numero_timbri']; ++$i) {
        $img_timbri .= '<img id="#timbro_' . ($i+1) . '" src="../img/carta_fedelta/2.png"/>' . "\n";
      }
    }
    $con->closeConnection();
    $contenuto = file_get_contents("../html/profilo.html");
    $contenuto = str_replace("%TIMBRI%", $img_timbri, $contenuto);
    $contenuto = str_replace("%NUMERO_TIMBRI%", $num_timbri['numero_timbri'], $contenuto);
    $pagina = Genera_pagina::genera("../html/base.html", "profilo", $contenuto);
    echo $pagina;
  } else {
    header('Location: redirect.php?error=3');
    exit;
  }
