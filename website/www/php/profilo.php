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
    $num_timbri = 0;
    if($_SESSION['tipo_utente'] == 'User') {
      $num_timbri = $con->getTimbriUtente($_SESSION['email_utente'])[0]['numero_timbri'];
      for($i=0; $i<$num_timbri; ++$i) {
        $img_timbri .= '<img id="#timbro_' . ($i+1) . '" src="../img/carta_fedelta/2.png"/>' . "\n";
      }
    }
    $con->closeConnection();
    $aggTimbri = '<p class="addedProduct">Grazie per il tuo acquisto!</p>';
    $minPrezzoTimbro = 10; //prezzo acquisto che dà diritto ad un timbro
    if(isset($_SESSION['valAcquisto']) && !empty($_SESSION['valAcquisto'])
        && $_SESSION['valAcquisto'] % $minPrezzoTimbro > 0) {
      $num_timbri['numero_timbri'] += $_SESSION['valAcquisto'] % $minPrezzoTimbro;
      $aggTimbri = '<p class="addedProduct">Grazie per il tuo acquisto! Ti sono stati convalidate delle caselle
       nella tua carta fedelt&agrave;: quando la tua carta sar&agrave; piena, recati in negozio
       per sfruttarla come buono da 15&euro;.</p>';
    }

    $contenuto = file_get_contents("../html/profilo.html");
    $contenuto = str_replace("%AGG_TIMBRI%", $aggTimbri, $contenuto);
    $contenuto = str_replace("%TIMBRI%", $img_timbri, $contenuto);
    $contenuto = str_replace("%NUMERO_TIMBRI%", $num_timbri['numero_timbri'], $contenuto);
    $pagina = Genera_pagina::genera("../html/base.html", "profilo", $contenuto);
    echo $pagina;
  } else {
    header('Location: redirect.php?error=3');
    exit;
  }
