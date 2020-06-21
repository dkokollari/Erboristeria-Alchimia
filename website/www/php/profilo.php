<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("genera_pagina.php");
  require_once("validate_form.php");

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
        $auguri = '<p class="addedProduct">
                     Tanti auguri di buon compleanno da Erboristeria Alchimia, '.$_SESSION['nome_utente'].'! <br/>
                     Per la giornata di oggi, hai diritto ad uno sconto di 10&euro; su un prodotto a tua scelta: corri in negozio, ti aspettiamo. <br/>
                     Ci teniamo a farti gli auguri di persona!
                   </p>';
      }
    } // end if $_SESSION['tipo_utente'] == 'User'

    $minPrezzoTimbro = 10; // prezzo acquisto che dà diritto ad un timbro
    if(isset($_SESSION['valAcquisto']) && !empty($_SESSION['valAcquisto'])) {
      $_SESSION["shopping_cart"] = null; // svuoto il carrello
      $aggTimbri = '<p class="addedProduct">Grazie per il tuo acquisto!</p>';
      if($_SESSION['valAcquisto'] % $minPrezzoTimbro > 0) {
        $num_timbri['numero_timbri'] += $_SESSION['valAcquisto'] % $minPrezzoTimbro;
        $aggTimbri = '<p class="addedProduct">
                        Grazie per il tuo acquisto! Ti sono state riempite delle caselle nella tua carta fedelt&agrave;: quando la tua carta sar&agrave; piena, recati in negozio per sfruttarla come buono da 15&euro;.
                      </p>';
      }
    }

    if($_POST['Modifica_profilo']) {
      $nome = ucfirst(strtolower(mysql_real_escape_string(trim($_POST['nome']))));
      $cognome = ucfirst(strtolower(mysql_real_escape_string(trim($_POST['cognome']))));
      $email = mysql_real_escape_string(trim($_POST['email']));
      $password = mysql_real_escape_string(trim($_POST['password']));
      $password_conferma = mysql_real_escape_string(trim($_POST['password_conferma']));
      $data_nascita = mysql_real_escape_string(trim($_POST['data_nascita']));
      /* messaggi di errore */
      $errore_empty = '<span class="errore">Completa tutti i campi</span>';
      $errore_full = '<span class="errore">Questa e-mail sembra non essere disponibile</span>';
      $errore_nome = '<span class="errore">Inserisci un nome di lunghezza tra 3 e 100 caratteri</span>'; // riferirsi alle regole di validate_form
      $errore_cognome = '<span class="errore">Inserisci un cognome di lunghezza tra 3 e 100 caratteri</span>'; // riferirsi alle regole di validate_form
      $errore_email = '<span class="errore">Inserisci una email valida</span>';
      $errore_password = '<span class="errore">Inserisci una password di lunghezza tra 8 e 100 caratteri, almeno 1 lettera ed 1 numero</span>'; // riferirsi alle regole di validate_form
      $errore_conferma = '<span class="errore">Le password inserite non corrispondono</span>';
      $errore_sconosciuto = '<span class="errore">Per favore disconnettiti e accedi di nuovo</span>';

      $fields = [$nome, $cognome, $email, $password, $data_nascita];
      if(Validate_form::is_empty($fields)) {
        $errore = $errore_empty;
      }
      else if(!Validate_form::check_str($nome)) {
        $errore = $errore_nome;
      }
      else if(!Validate_form::check_str($cognome)) {
        $errore = $errore_cognome;
      }
      else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errore = $errore_email;
      }
      else if(!Validate_form::check_pwd($password)) {
        $errore = $errore_password;
      }
      else if($password != $password_conferma) {
        $errore = $errore_conferma;
      }
      else {
        $con = new DBAccess();
        if(!$con->openConnection()) {
          header('Location: redirect.php?error=1');
          exit;
        }
        if($email != $_SESSION['email_utente'] && $con->getSingolo_Utenti($email)) {
          $errore = $errore_full;
        }
        else {
          $result = $con->updateUtenti($_SESSION['email_utente'], $nome, $cognome, $email, $password, $data_nascita);
          if(!$result) {
            $errore = $errore_sconosciuto;
          }
          else {
            $array = $con->getSingolo_Utenti($email);
            $_SESSION['nome_utente'] = $array[0]['nome_utente'];
            $_SESSION['cognome_utente'] = $array[0]['cognome_utente'];
            $_SESSION['email_utente'] = $array[0]['email_utente'];
            $_SESSION['password_utente'] = $array[0]['password_utente'];
            $_SESSION['tipo_utente'] = $array[0]['tipo_utente'];
            $_SESSION['data_nascita_utente'] = $array[0]['data_nascita_utente'];
          }
        }
        $con->closeConnection();
      }

      $status = (empty($errore)
                ? "<span>Profilo aggiornato con successo</span>"
                : $errore);
    } // end if $_POST['Modifica_profilo']

    $contenuto = file_get_contents("../html/profilo.html");
    $contenuto = str_replace("%AGG_TIMBRI%", $aggTimbri, $contenuto);
    $contenuto = str_replace("%TIMBRI%", $img_timbri, $contenuto);
    $contenuto = str_replace("%NUMERO_TIMBRI%", $num_timbri['numero_timbri'], $contenuto);
    $contenuto = str_replace("%AUGURI%", $auguri, $contenuto);
    $contenuto .= file_get_contents("../html/form_utente.html");
    $contenuto = str_replace("%ACTION_FORM%", "profilo.php", $contenuto);
    $contenuto = str_replace("%TITOLO%", "Modifica i dati", $contenuto);
    $contenuto = str_replace("%STATO_UTENTE%", $status, $contenuto);
    // pre-fill input, riempimento campi
    $array_place_html = ['nome', 'cognome', 'email', 'data_nascita'];
    $array_place_session = ['nome_utente', 'cognome_utente', 'email_utente', 'data_nascita_utente'];
    foreach (array_combine($array_place_html, $array_place_session) as $html => $session) {
      if($_SESSION[$session]!="") {
        $contenuto = str_replace('<label for="'.$html.'">', '<label class="filled" for="'.$html.'">', $contenuto);
        $contenuto = str_replace('%VALUE_'.$html.'%', 'value="'.$_SESSION[$session].'"', $contenuto);
      }
    }
    $contenuto = str_replace("%NOME_SUBMIT%", "Modifica_profilo", $contenuto);
    $contenuto = str_replace("%AGGIUNGI_SUBMIT%", '<input id="delete_user" type="submit" value="Elimina il profilo" name="Elimina_profilo"/>', $contenuto);
    $pagina = Genera_pagina::genera("../html/base5.html", "profilo", $contenuto);
    echo $pagina;
  } // end if $_SESSION['auth']
  else {
    header('Location: redirect.php?error=3');
    exit;
  }
?>
