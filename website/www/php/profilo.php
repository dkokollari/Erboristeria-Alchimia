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
    $num_timbri = 0;
    if($_SESSION['tipo_utente'] == 'User') {
      $num_timbri = $con->getTimbriUtente($_SESSION['email_utente'])[0]['numero_timbri'];
      $con->closeConnection();
      for($i = 0; $i < $num_timbri; $i++) {
        $img_timbri .= '<img id="#timbro_'.($i+1).'" src="../img/carta_fedelta/2.png"/>'."\n";
      }
      $compleanno = DateTime::createFromFormat("Y-m-d", $_SESSION['data_nascita_utente']);
      if($compleanno->format('d') == date('d') && $compleanno->format('m') == date('m')) {
        $auguri = '<p class="addedProduct">Tanti auguri di buon compleanno da Erboristeria Alchimia, '.
        $_SESSION['nome_utente'].'! Per la giornata di oggi, hai diritto ad uno sconto di 10&euro;
         su un prodotto a tua scelta: corri in negozio, ti aspettiamo; ci teniamo a farti gli auguri
         di persona! </p>';
      }
    } // end if $_SESSION['tipo_utente'] == 'User'
    $minPrezzoTimbro = 10; // prezzo acquisto che dà diritto ad un timbro
    if(isset($_SESSION['valAcquisto']) && !empty($_SESSION['valAcquisto'])) {
      $_SESSION["shopping_cart"] = null; // svuoto il carrello
      $aggTimbri = '<p class="addedProduct">Grazie per il tuo acquisto!</p>';
      if($_SESSION['valAcquisto'] % $minPrezzoTimbro > 0) {
        $num_timbri['numero_timbri'] += $_SESSION['valAcquisto'] % $minPrezzoTimbro;
        $aggTimbri = '<p class="addedProduct">Grazie per il tuo acquisto! Ti sono state riempite delle caselle
        nella tua carta fedelt&agrave;: quando la tua carta sar&agrave; piena, recati in negozio
        per sfruttarla come buono da 15&euro;.</p>';
      }
    } 

    $contenuto = file_get_contents("../html/profilo.html");
    $contenuto = str_replace("%AGG_TIMBRI%", $aggTimbri, $contenuto);
    $contenuto = str_replace("%TIMBRI%", $img_timbri, $contenuto);
    $contenuto = str_replace("%NUMERO_TIMBRI%", $num_timbri['numero_timbri'], $contenuto);
    $contenuto = str_replace("%AUGURI%", $auguri, $contenuto);
    $pagina = Genera_pagina::genera("../html/base.html", "profilo", $contenuto);
    echo $pagina;
  } // end if $_SESSION['auth']
  else {
    header('Location: redirect.php?error=3');
    exit;
  }
?>
